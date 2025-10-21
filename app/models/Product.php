<?php

class Product extends Model {
    protected $table = 'ali_product';
    
    /**
     * ดึงข้อมูลสินค้าทั้งหมด (แบบมี JOIN ตาราง)
     */
    public function getAllProducts() {
        try {
            $sql = "SELECT 
                    p.pcode,
                    p.pdesc,
                    pc.cate_name,
                    COALESCE(pg1.groupname, pg2.groupname) as groupname
                FROM ali_productcategory pc
                LEFT JOIN ali_productgroup pg1 ON pc.id = pg1.id_cate
                LEFT JOIN ali_product p ON pg1.id = p.group_id
                LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                WHERE (pc.id = 34 OR pc.id = 54) AND p.sh = 1
                ORDER BY pc.cate_name, groupname, p.pcode";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("getAllProducts: Execute failed - " . print_r($stmt->errorInfo(), true));
                return array();
            }
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getAllProducts: Found " . count($products) . " products with category and group");

            if (count($products) > 0) {
                error_log("getAllProducts: First product - " . $products[0]['pcode']);
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Error getting all products with joins: " . $e->getMessage());
            return array();
        } catch (Exception $e) {
            error_log("Unexpected error in getAllProducts: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูลสินค้าตาม ID
     */
    public function getProductById($id) {
        try {
            error_log("getProductById called with ID: " . $id);
            
            $sql = "SELECT 
                    p.*,
                    pc.cate_name,
                    COALESCE(pg1.groupname, pg2.groupname) as groupname
                FROM ali_product p
                LEFT JOIN ali_productgroup pg1 ON p.group_id = pg1.id
                LEFT JOIN ali_productcategory pc ON pg1.id_cate = pc.id
                LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                WHERE p.id = ? AND p.sh = 1";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(array($id));
            
            if (!$result) {
                error_log("getProductById: Execute failed - " . print_r($stmt->errorInfo(), true));
                return null;
            }
            
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                error_log("getProductById: Found product - " . $product['pcode']);
            } else {
                error_log("getProductById: Product not found for ID: " . $id);
                
                // ลองค้นหาด้วย pcode แทน
                $sql2 = "SELECT 
                        p.*,
                        pc.cate_name,
                        COALESCE(pg1.groupname, pg2.groupname) as groupname
                    FROM ali_product p
                    LEFT JOIN ali_productgroup pg1 ON p.group_id = pg1.id
                    LEFT JOIN ali_productcategory pc ON pg1.id_cate = pc.id
                    LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                    WHERE p.pcode = ? AND p.sh = 1";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute(array($id));
                $product = $stmt2->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    error_log("getProductById: Found product by pcode - " . $product['pcode']);
                }
            }
            
            return $product;
        } catch (PDOException $e) {
            error_log("Error getting product by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * ตรวจสอบว่า pcode ซ้ำหรือไม่
     */
    public function isPcodeExists($pcode, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE pcode = ? AND sh = 1";
            $params = array($pcode);
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking pcode exists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * สร้างสินค้าใหม่
     */
    public function createProduct($data) {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (pcode, pdesc, group_id, price, unit, sh, created_date) 
                    VALUES (?, ?, ?, ?, ?, 1, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(array(
                $data['pcode'],
                isset($data['pdesc']) ? $data['pdesc'] : null,
                isset($data['group_id']) ? $data['group_id'] : null,
                isset($data['price']) ? $data['price'] : 0,
                isset($data['unit']) ? $data['unit'] : null
            ));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error creating product: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * อัพเดทข้อมูลสินค้า
     */
    public function updateProduct($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET 
                    pcode = ?, 
                    pdesc = ?, 
                    group_id = ?, 
                    price = ?, 
                    unit = ?,
                    updated_date = NOW() 
                    WHERE id = ? AND sh = 1";
            
            $params = array(
                $data['pcode'],
                isset($data['pdesc']) ? $data['pdesc'] : null,
                isset($data['group_id']) ? $data['group_id'] : null,
                isset($data['price']) ? $data['price'] : 0,
                isset($data['unit']) ? $data['unit'] : null,
                $id
            );
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * ลบสินค้า (soft delete)
     */
    public function deleteProduct($id) {
        try {
            $sql = "UPDATE {$this->table} SET sh = 0 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array($id));
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * นับจำนวนสินค้า
     */
    public function countProducts($filters = array()) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM ali_productcategory pc
                    LEFT JOIN ali_productgroup pg1 ON pc.id = pg1.id_cate
                    LEFT JOIN ali_product p ON pg1.id = p.group_id
                    LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                    WHERE (pc.id = 34 OR pc.id = 54) AND p.sh = 1";
            
            $params = array();
            
            if (!empty($filters['group_id'])) {
                $sql .= " AND p.group_id = ?";
                $params[] = $filters['group_id'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting products: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * ค้นหาสินค้า - รองรับการค้นหาด้วย pcode, pdesc และ category
     */
    public function searchProducts($keyword, $filters = array()) {
        try {
            $sql = "SELECT 
                    p.pcode,
                    p.pdesc,
                    pc.cate_name,
                    COALESCE(pg1.groupname, pg2.groupname) as groupname
                FROM ali_productcategory pc
                LEFT JOIN ali_productgroup pg1 ON pc.id = pg1.id_cate
                LEFT JOIN ali_product p ON pg1.id = p.group_id
                LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                WHERE (pc.id = 34 OR pc.id = 54) AND p.sh = 1";
            
            $params = array();
            
            // ค้นหาด้วย keyword
            if (!empty($keyword)) {
                $sql .= " AND (p.pcode LIKE ? OR p.pdesc LIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            // กรองตาม group_id
            if (!empty($filters['group_id'])) {
                $sql .= " AND p.group_id = ?";
                $params[] = $filters['group_id'];
            }
            
            // กรองตาม category
            if (!empty($filters['category_id'])) {
                $sql .= " AND pc.id = ?";
                $params[] = $filters['category_id'];
            }
            
            $sql .= " ORDER BY pc.cate_name, groupname, p.pcode";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("searchProducts: Found " . count($results) . " products for keyword: " . $keyword);
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error searching products: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูลกลุ่มสินค้า
     */
    public function getProductGroups() {
        try {
            $sql = "SELECT id, groupname FROM ali_productgroup WHERE sh = 1 ORDER BY groupname";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product groups: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูลหมวดหมู่สินค้า
     */
    public function getProductCategories() {
        try {
            $sql = "SELECT id, cate_name FROM ali_productcategory WHERE sh = 1 ORDER BY cate_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product categories: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูลสินค้าตามกลุ่ม
     */
    public function getProductsByGroup($group_id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE group_id = ? AND sh = 1 ORDER BY pcode ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array($group_id));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting products by group: " . $e->getMessage());
            return array();
        }
    }
}