<?php

class Meter extends Model {
    protected $table = 'me_meter';

    /**
     * ดึงข้อมูลทั้งหมด พร้อมคำนวณค่าใช้จ่ายสำหรับช่วงเวลาที่กำหนด
     * ถ้าไม่ระบุเดือน/ปี จะใช้เดือน/ปีปัจจุบัน
     */
    public function getAllMeters($month = null, $year = null) {
        try {
            // ดึงรายการ pcode ทั้งหมด
            $sql = "SELECT 
                    p.pcode,
                    p.pdesc,
                    p.meter_0_latest,
                    p.meter_1_latest,
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
                error_log("getAllMeters: Execute failed - " . print_r($stmt->errorInfo(), true));
                return array();
            }
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // ใช้เดือน/ปีที่ระบุ หรือเดือน/ปีปัจจุบัน
            $targetMonth = $month ? (int)$month : (int)date('m');
            $targetYear = $year ? (int)$year : (int)date('Y');
            
            $results = array();
            
            // สำหรับแต่ละ pcode (เฉพาะเดือน/ปีที่กำหนด)
            foreach ($products as $product) {
                $pcode = $product['pcode'];
                $waterMeterNumberBefore = $product['meter_0_latest'];//เลขมิเตอร์น้ำก่อนหน้า
                $electricityMeterNumberBefore = $product['meter_1_latest'];//เลขมิเตอร์ไฟก่อนหน้า
                
                // คำนวณค่าไฟ
                $electricity = $this->calculateElectricityCost($pcode, $targetMonth, $targetYear);
                
                // คำนวณค่าน้ำ
                $water = $this->calculateWaterCost($pcode, $targetMonth, $targetYear);
                
                // ดึงค่าขยะ
                $garbage = $this->getOtherCost($pcode, $targetMonth, $targetYear, 'ค่าขยะ');
                
                // ดึงค่าส่วนกลาง
                $commonArea = $this->getOtherCost($pcode, $targetMonth, $targetYear, 'ค่าส่วนกลาง');
                
                // คำนวณรวม
                $total = $electricity + $water + $garbage + $commonArea;
                
                // ตรวจสอบสถานะ
                $hasMeterData = $this->hasMeterData($pcode, $targetMonth, $targetYear);
                $status = $hasMeterData ? 'saved' : 'unsaved';
                
                // Debug: log สถานะของ 1-2 รายการแรก
                if (count($results) < 2) {
                    error_log("Debug meter: pcode=$pcode, month=$targetMonth, year=$targetYear, hasMeterData=" . ($hasMeterData ? 'true' : 'false') . ", status=$status");
                }
                
                $results[] = array(
                    'id' => $pcode . '_' . $targetMonth . '_' . $targetYear,
                    'pcode' => (string)$pcode,
                    'pdesc' => (string)(isset($product['pdesc']) ? $product['pdesc'] : ''),
                    'cate_name' => (string)(isset($product['cate_name']) ? $product['cate_name'] : ''),
                    'groupname' => (string)(isset($product['groupname']) ? $product['groupname'] : ''),
                    'electricity' => (float)$electricity,
                    'water' => (float)$water,
                    'garbage' => (float)$garbage,
                    'common_area' => (float)$commonArea,
                    'total' => (float)$total,
                    'status' => (string)$status,
                    'month' => (int)$targetMonth,
                    'year' => (int)$targetYear,
                    'waterMeterNumberBefore' => (int)$waterMeterNumberBefore,
                    'electricityMeterNumberBefore' => (int)$electricityMeterNumberBefore,
                );
            }            error_log("getAllMeters: Found " . count($results) . " results");
            return $results;
            
        } catch (PDOException $e) {
            error_log("Error getting all meters: " . $e->getMessage());
            return array();
        } catch (Exception $e) {
            error_log("Unexpected error in getAllMeters: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * คำนวณค่าไฟ
     */
    private function calculateElectricityCost($pcode, $month, $year) {
        try {
            $sql = "SELECT reading_value 
                    FROM me_meter 
                    WHERE pcode = ? AND month = ? AND year = ? AND meter_type = 'ค่าไฟ'
                    ORDER BY reading_date DESC 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array($pcode, $month, $year));
            $current = $stmt->fetchColumn();
            
            if (!$current) {
                return 0;
            }
            
            // ดึงค่าเดือนก่อนหน้า
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear = $year - 1;
            }
            
            $sqlPrev = "SELECT reading_value 
                        FROM me_meter 
                        WHERE pcode = ? AND month = ? AND year = ? AND meter_type = 'ค่าไฟ'
                        ORDER BY reading_date DESC 
                        LIMIT 1";
            
            $stmtPrev = $this->db->prepare($sqlPrev);
            $stmtPrev->execute(array($pcode, $prevMonth, $prevYear));
            $previous = $stmtPrev->fetchColumn();
            
            if (!$previous) {
                return 0;
            }
            
            // คำนวณหน่วยที่ใช้ * ราคาต่อหน่วย (สมมติ 4 บาท)
            $units = $current - $previous;
            return $units > 0 ? $units * 4 : 0;
            
        } catch (PDOException $e) {
            error_log("Error calculating electricity: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * คำนวณค่าน้ำ
     */
    private function calculateWaterCost($pcode, $month, $year) {
        try {
            $sql = "SELECT reading_value 
                    FROM me_meter 
                    WHERE pcode = ? AND month = ? AND year = ? AND meter_type = 'ค่าน้ำ'
                    ORDER BY reading_date DESC 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array($pcode, $month, $year));
            $current = $stmt->fetchColumn();

            
            
            if (!$current) {
                return 0;
            }
            
            // ดึงค่าเดือนก่อนหน้า
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear = $year - 1;
            }
            
            $sqlPrev = "SELECT reading_value 
                        FROM me_meter 
                        WHERE pcode = ? AND month = ? AND year = ? AND meter_type = 'ค่าน้ำ'
                        ORDER BY reading_date DESC 
                        LIMIT 1";
            
            $stmtPrev = $this->db->prepare($sqlPrev);
            $stmtPrev->execute(array($pcode, $prevMonth, $prevYear));
            $previous = $stmtPrev->fetchColumn();

            
            
            if (!$previous) {
                return 0;
            }
            
            // คำนวณหน่วยที่ใช้ * ราคาต่อหน่วย (สมมติ 18 บาท)
            $units = $current - $previous;
            return $units > 0 ? $units * 18 : 0;
            
        } catch (PDOException $e) {
            error_log("Error calculating water: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * ดึงค่าใช้จ่ายอื่นๆ
     */
    private function getOtherCost($pcode, $month, $year, $type) {
        try {
            $sql = "SELECT price 
                    FROM me_meter_ohter 
                    WHERE pcode = ? AND month = ? AND year = ? AND meter_ohter_type = ?
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array($pcode, $month, $year, $type));
            $price = $stmt->fetchColumn();
            
            return $price ? (float)$price : 0;
            
        } catch (PDOException $e) {
            error_log("Error getting other cost: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
 * ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
 */
private function hasMeterData($pcode, $month, $year) {
    try {
        // ตรวจสอบว่ามีข้อมูลค่าไฟหรือค่าน้ำในเดือน/ปีนี้
        $sql = "SELECT COUNT(*) as count FROM me_meter 
                WHERE pcode = ? AND month = ? AND year = ? 
                AND meter_type IN ('ค่าไฟ', 'ค่าน้ำ')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pcode, $month, $year]);
        $meterCount = $stmt->fetchColumn();
        
        // ตรวจสอบว่ามีข้อมูลขยะหรือส่วนกลางในเดือน/ปีนี้
        $sql2 = "SELECT COUNT(*) as count FROM me_meter_ohter 
                WHERE pcode = ? AND month = ? AND year = ? 
                AND meter_ohter_type IN ('ค่าขยะ', 'ค่าส่วนกลาง')";
        
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute([$pcode, $month, $year]);
        $otherCount = $stmt2->fetchColumn();
        
        // DEBUG
        error_log("hasMeterData: pcode=$pcode, month=$month, year=$year, meterCount=$meterCount, otherCount=$otherCount");
        
        // ถ้ามีข้อมูลอย่างน้อย 1 record ในตารางใดตารางหนึ่ง = บันทึกแล้ว
        return ($meterCount > 0 || $otherCount > 0);
        
    } catch (PDOException $e) {
        error_log("Error in hasMeterData: " . $e->getMessage());
        return false;
    }
}
    /**
     * นับจำนวน
     */
    public function countMeters($filters = array()) {
        try {
            $sql = "SELECT COUNT(DISTINCT p.pcode) 
                    FROM ali_productcategory pc
                    LEFT JOIN ali_productgroup pg1 ON pc.id = pg1.id_cate
                    LEFT JOIN ali_product p ON pg1.id = p.group_id
                    LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                    WHERE (pc.id = 34 OR pc.id = 54) AND p.sh = 1";
            
            $params = array();
            
            // กรองตามคำค้นหา
            if (!empty($filters['search'])) {
                $sql .= " AND (p.pcode LIKE ? OR p.pdesc LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting meters: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * ค้นหา
     */
    public function searchMeter($keyword, $filters = array()) {
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
     * Check if a meter record exists
     */
    public function meterRecordExists($pcode, $month, $year, $type) {
        try {
            $sql = "SELECT COUNT(*) FROM me_meter WHERE pcode = ? AND month = ? AND year = ? AND meter_type = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pcode, $month, $year, $type]);
            
            $count = $stmt->fetchColumn();
            error_log("Count result: " . $count);

            return $count > 0;
        } catch (PDOException $e) {
            error_log("Error checking meter record existence: " . $e->getMessage());
            return false;
        }
    }


    public function saveOrUpdateMeter($data) {
        try {
            // ดึง user ID ของผู้ใช้ปัจจุบัน
            $userId = null;
            if (class_exists('Auth')) {
                $currentUser = Auth::user();
                $userId = isset($currentUser['id']) ? $currentUser['id'] : null;
            }
            
            // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
            $exists = $this->meterRecordExists($data['pcode'], $data['month'], $data['year'], $data['type']);
           
            if ($exists == 'true') {
                // Update existing record (แม้ค่าเป็น 0)
                $sql = "UPDATE me_meter 
                        SET reading_value = ?, 
                            updated_at = NOW(), 
                            updated_by = ? 
                        WHERE pcode = ? AND month = ? AND year = ? AND meter_type = ?";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    $data['reading_value'],
                    $userId,
                    $data['pcode'],
                    $data['month'],
                    $data['year'],
                    $data['type']
                ]);
                error_log("Updated meter: " . $data['type'] . " - pcode=" . $data['pcode'] . ", value=" . $data['reading_value'] . ", result=" . ($result ? 'success' : 'failed'));
            } else {
                 
                // Insert new record (แม้ค่าเป็น 0)
                $sql = "INSERT INTO me_meter 
                        (meter_type, pcode, month, year, reading_value, reading_date, created_at, created_by, updated_at, updated_by) 
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?, NOW(), ?)";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    $data['type'],
                    $data['pcode'],
                    $data['month'],
                    $data['year'],
                    $data['reading_value'],
                    $userId,
                    $userId
                ]);
               
                error_log("Inserted meter: " . $data['type'] . " - pcode=" . $data['pcode'] . ", value=" . $data['reading_value'] . ", result=" . ($result ? 'success' : 'failed'));
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Error saving or updating meter record (" . $data['type'] . "): " . $e->getMessage());
            if (isset($stmt)) {
                error_log("SQL Error Info: " . print_r($stmt->errorInfo(), true));
            }
            return false;
        }
    }

}