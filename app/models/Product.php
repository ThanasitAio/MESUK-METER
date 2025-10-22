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
            error_log("getProductById called with param: " . $id);
            $product = null;
            // ถ้า $id เป็นตัวเลข ให้ค้นหาด้วย id
            if (is_numeric($id)) {
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
                $stmt->execute(array($id));
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // ถ้าไม่ใช่ตัวเลข ให้ค้นหาด้วย pcode
                $sql = "SELECT 
                        p.*,
                        pc.cate_name,
                        COALESCE(pg1.groupname, pg2.groupname) as groupname
                    FROM ali_product p
                    LEFT JOIN ali_productgroup pg1 ON p.group_id = pg1.id
                    LEFT JOIN ali_productcategory pc ON pg1.id_cate = pc.id
                    LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                    WHERE p.pcode = ? AND p.sh = 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($id));
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            if ($product) {
                error_log("getProductById: Found product - " . $product['pcode']);
            } else {
                error_log("getProductById: Product not found for param: " . $id);
            }
            return $product;
        } catch (PDOException $e) {
            error_log("Error getting product by ID: " . $e->getMessage());
            return null;
        }
    }
    

    /**
     * อัพเดทข้อมูลสินค้า
     */
    public function updateProduct($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET 
                    investor_owner = ?, 
                    investor_address = ?, 
                    investor_phone = ?, 
                    tenant = ?, 
                    tenant_phone = ?,
                    tenant_tax_id = ?,
                    bank_acc_name = ?,
                    bank_name = ?,
                    bank_branch = ?,
                    bank_acc_no = ?,
                    meter_0_ppu = ?,
                    meter_1_ppu = ?
                    WHERE pcode = ? AND sh = 1";
            
            $params = array(
                isset($data['investor_owner']) ? $data['investor_owner'] : null,
                isset($data['investor_address']) ? $data['investor_address'] : null,
                isset($data['investor_phone']) ? $data['investor_phone'] : null,
                isset($data['tenant']) ? $data['tenant'] : null,
                isset($data['tenant_phone']) ? $data['tenant_phone'] : null,
                isset($data['tenant_tax_id']) ? $data['tenant_tax_id'] : null,
                isset($data['bank_acc_name']) ? $data['bank_acc_name'] : null,
                isset($data['bank_name']) ? $data['bank_name'] : null,
                isset($data['bank_branch']) ? $data['bank_branch'] : null,
                isset($data['bank_acc_no']) ? $data['bank_acc_no'] : null,
                isset($data['meter_0_ppu']) ? $data['meter_0_ppu'] : null,
                isset($data['meter_1_ppu']) ? $data['meter_1_ppu'] : null,
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