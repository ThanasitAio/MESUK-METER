<?php

class User extends Model {
    protected $table = 'me_users';
    
    /**
     * ดึงข้อมูลผู้ใช้ทั้งหมด
     */
    public function getAllUsers() {
        try {
            // ใช้ SELECT * เพื่อดึงทุก field
            $sql = "SELECT * FROM {$this->table} ORDER BY created_date DESC";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("getAllUsers: Execute failed - " . print_r($stmt->errorInfo(), true));
                return array();
            }
            
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getAllUsers: Found " . count($users) . " users from table {$this->table}");
            
            if (count($users) > 0) {
                error_log("getAllUsers: First user keys: " . implode(', ', array_keys($users[0])));
            }
            
            return $users;
        } catch (PDOException $e) {
            error_log("Error getting all users: " . $e->getMessage());
            error_log("Table name: " . $this->table);
            return array();
        } catch (Exception $e) {
            error_log("Unexpected error in getAllUsers: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูลผู้ใช้ตาม ID
     */
    public function getUserById($id) {
        try {
            error_log("getUserById called with ID: " . $id);
            error_log("Table name: " . $this->table);
            
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(array($id));
            
            if (!$result) {
                error_log("getUserById: Execute failed - " . print_r($stmt->errorInfo(), true));
                return null;
            }
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                error_log("getUserById: Found user - " . $user['username']);
            } else {
                error_log("getUserById: User not found for ID: " . $id);
                
                // ลองค้นหาด้วย code แทน (เผื่อส่ง code มาแทน id)
                $sql2 = "SELECT * FROM {$this->table} WHERE code = ?";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute(array($id));
                $user = $stmt2->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    error_log("getUserById: Found user by code - " . $user['username']);
                }
            }
            
            return $user;
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            error_log("SQL: SELECT * FROM {$this->table} WHERE id = " . $id);
            return null;
        }
    }
    
    /**
     * ตรวจสอบว่า code ซ้ำหรือไม่
     */
    public function isCodeExists($code, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE code = ?";
            $params = [$code];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking code exists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * ตรวจสอบว่า username ซ้ำหรือไม่
     */
    public function isUsernameExists($username, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = ?";
            $params = [$username];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking username exists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * สร้างผู้ใช้ใหม่
     */
    public function createUser($data) {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (id, code, username, password, name, tel, birthday, 
                    facebook_name, line_id, img, status, role, created_by) 
                    VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['code'],
                $data['username'],
                md5($data['password']), // ใช้ MD5 ตามโครงสร้างเดิม
                isset($data['name']) ? $data['name'] : null,
                isset($data['tel']) ? $data['tel'] : null,
                isset($data['birthday']) ? $data['birthday'] : null,
                isset($data['facebook_name']) ? $data['facebook_name'] : null,
                isset($data['line_id']) ? $data['line_id'] : null,
                isset($data['img']) ? $data['img'] : null,
                isset($data['status']) ? $data['status'] : 'active',
                isset($data['role']) ? $data['role'] : 'user',
                isset($data['created_by']) ? $data['created_by'] : null
            ]);
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * อัพเดทข้อมูลผู้ใช้
     */
    public function updateUser($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET 
                    code = ?, 
                    username = ?, 
                    name = ?, 
                    tel = ?, 
                    birthday = ?, 
                    facebook_name = ?, 
                    line_id = ?, 
                    img = ?, 
                    status = ?, 
                    role = ?, 
                    updated_by = ?,
                    updated_date = NOW()";
            
            $params = [
                $data['code'],
                $data['username'],
                isset($data['name']) ? $data['name'] : null,
                isset($data['tel']) ? $data['tel'] : null,
                isset($data['birthday']) ? $data['birthday'] : null,
                isset($data['facebook_name']) ? $data['facebook_name'] : null,
                isset($data['line_id']) ? $data['line_id'] : null,
                isset($data['img']) ? $data['img'] : null,
                isset($data['status']) ? $data['status'] : 'active',
                isset($data['role']) ? $data['role'] : 'user',
                isset($data['updated_by']) ? $data['updated_by'] : null
            ];
            
            // อัพเดทรหัสผ่านถ้ามีการส่งมา
            if (!empty($data['password'])) {
                $sql .= ", password = ?";
                $params[] = md5($data['password']);
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * ลบผู้ใช้
     */
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * เปลี่ยนสถานะผู้ใช้
     */
    public function changeStatus($id, $status, $updatedBy = null) {
        try {
            $sql = "UPDATE {$this->table} SET 
                    status = ?, 
                    updated_by = ?,
                    updated_date = NOW()
                    WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $updatedBy, $id]);
        } catch (PDOException $e) {
            error_log("Error changing user status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * นับจำนวนผู้ใช้
     */
    public function countUsers($filters = []) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
            $params = [];
            
            if (!empty($filters['status'])) {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['role'])) {
                $sql .= " AND role = ?";
                $params[] = $filters['role'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting users: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * ค้นหาผู้ใช้
     */
    public function searchUsers($keyword) {
        try {
            $sql = "SELECT id, code, username, name, tel, birthday, 
                    facebook_name, line_id, img, status, role, 
                    created_date, created_by, updated_date, updated_by 
                    FROM {$this->table} 
                    WHERE code LIKE ? 
                    OR username LIKE ? 
                    OR name LIKE ? 
                    OR tel LIKE ?
                    ORDER BY created_date DESC";
            
            $searchTerm = "%{$keyword}%";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching users: " . $e->getMessage());
            return [];
        }
    }
}
