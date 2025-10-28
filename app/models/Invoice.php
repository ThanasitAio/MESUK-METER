<?php

class Invoice extends Model {
    protected $table = 'me_meter';

    /**
     * ดึงสถิติใบแจ้งหนี้จาก me_invoice
     * @param int $month
     * @param int $year
     * @return array
     */
    /**
 * ดึงสถิติใบแจ้งหนี้จาก me_invoice
 */
public function getInvoiceStats($month = null, $year = null) {
    try {
        $targetMonth = $month ? (int)$month : (int)date('m');
        $targetYear = $year ? (int)$year : (int)date('Y');

        // ดึง pcode ทั้งหมดที่บันทึกในมิเตอร์แล้ว
        $sqlSaved = "SELECT DISTINCT pcode FROM (
            SELECT pcode FROM me_meter WHERE month = ? AND year = ?
            UNION 
            SELECT pcode FROM me_meter_ohter WHERE month = ? AND year = ?
        ) AS saved_meters";
        
        $stmtSaved = $this->db->prepare($sqlSaved);
        $stmtSaved->execute([$targetMonth, $targetYear, $targetMonth, $targetYear]);
        $savedPcodes = $stmtSaved->fetchAll(PDO::FETCH_COLUMN, 0);

        // ดึง pcode ที่มีใน me_invoice (นับจากเลขที่เอกสารที่ไม่ซ้ำ)
        $sqlOpened = "SELECT COUNT(DISTINCT pcode) FROM me_invoice WHERE month = ? AND year = ?";
        $stmtOpened = $this->db->prepare($sqlOpened);
        $stmtOpened->execute([$targetMonth, $targetYear]);
        $openCount = (int)$stmtOpened->fetchColumn();

        // คำนวณจำนวนที่ยังไม่เปิด
        $notOpenedCount = count($savedPcodes) - $openCount;

        // ราคารวมใบแจ้งหนี้ที่เปิด
        $sqlPrice = "SELECT SUM(price) as total_price FROM me_invoice WHERE month = ? AND year = ?";
        $stmtPrice = $this->db->prepare($sqlPrice);
        $stmtPrice->execute([$targetMonth, $targetYear]);
        $totalPrice = (float)$stmtPrice->fetchColumn();

        return array(
            'open_count' => $openCount,
            'not_opened_count' => $notOpenedCount,
            'total_price' => $totalPrice,
            'total_saved' => count($savedPcodes)
        );
    } catch (PDOException $e) {
        error_log("Error getting invoice stats: " . $e->getMessage());
        return array(
            'open_count' => 0,
            'not_opened_count' => 0,
            'total_price' => 0,
            'total_saved' => 0
        );
    }
}

        /**
         * ดึงข้อมูลทั้งหมดจาก me_invoice (raw)
         */
        public function getAllInvoicesRaw() {
            try {
                $sql = "SELECT inv_id, inv_no, pcode, month, year, type, price, remark, created_at, created_by, updated_at, updated_by FROM me_invoice WHERE 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error getting all invoices: " . $e->getMessage());
                return array();
            }
        }
    /**
     * ดึงข้อมูลทั้งหมด พร้อมคำนวณค่าใช้จ่ายสำหรับช่วงเวลาที่กำหนด
     * ถ้าไม่ระบุเดือน/ปี จะใช้เดือน/ปีปัจจุบัน
     */
    public function getAllInvoices($month = null, $year = null) {
    try {
        // ใช้เดือน/ปีที่ระบุ หรือเดือน/ปีปัจจุบัน
        $targetMonth = $month ? (int)$month : (int)date('m');
        $targetYear = $year ? (int)$year : (int)date('Y');
        
        // ดึงเฉพาะ pcode ที่บันทึกในมิเตอร์แล้วสำหรับเดือน/ปีนี้
        $sqlSavedPcodes = "SELECT DISTINCT pcode FROM (
            SELECT pcode FROM me_meter WHERE month = ? AND year = ?
            UNION 
            SELECT pcode FROM me_meter_ohter WHERE month = ? AND year = ?
        ) AS saved_meters";
        
        $stmtSaved = $this->db->prepare($sqlSavedPcodes);
        $stmtSaved->execute([$targetMonth, $targetYear, $targetMonth, $targetYear]);
        $savedPcodes = $stmtSaved->fetchAll(PDO::FETCH_COLUMN, 0);
        
        if (empty($savedPcodes)) {
            return array(); // ไม่มีข้อมูลที่บันทึกแล้ว
        }
        
        // ดึงรายการ pcode เฉพาะที่บันทึกแล้ว
        $placeholders = str_repeat('?,', count($savedPcodes) - 1) . '?';
        $sql = "SELECT 
                p.pcode,
                p.pdesc,
                pc.cate_name,
                COALESCE(pg1.groupname, pg2.groupname) as groupname,
                COALESCE(p.meter_1_ppu, 0) as electricity_ppu,
                COALESCE(p.meter_0_ppu, 0) as water_ppu
            FROM ali_productcategory pc
            LEFT JOIN ali_productgroup pg1 ON pc.id = pg1.id_cate
            LEFT JOIN ali_product p ON pg1.id = p.group_id
            LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
            WHERE (pc.id = 34 OR pc.id = 54) AND p.sh = 1 
            AND p.pcode IN ($placeholders)
            ORDER BY pc.cate_name, groupname, p.pcode";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($savedPcodes);
        
        if (!$result) {
            error_log("getAllInvoices: Execute failed - " . print_r($stmt->errorInfo(), true));
            return array();
        }
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($products)) {
            return array();
        }
        
        // ดึงข้อมูลทั้งหมดแบบ batch (เฉพาะ pcode ที่บันทึกแล้ว)
        $allMeterData = $this->getAllMeterDataBatch($savedPcodes, $targetMonth, $targetYear);
        $allOtherCosts = $this->getAllOtherCostsBatch($savedPcodes, $targetMonth, $targetYear);
        $allPrevMeterData = $this->getPreviousMonthMeterDataBatch($savedPcodes, $targetMonth, $targetYear);
        $allRemarks = $this->getAllRemarksBatch($savedPcodes, $targetMonth, $targetYear);
        
        $results = array();
        
        // สำหรับแต่ละ pcode (เฉพาะเดือน/ปีที่กำหนดและบันทึกแล้ว)
        foreach ($products as $product) {
            $pcode = $product['pcode'];

            // ตรวจสอบสถานะจากข้อมูลที่ดึงแบบ batch
            $hasMeterData = $this->hasMeterDataBatch($pcode, $allMeterData, $allOtherCosts);
            
            // ข้ามถ้ายังไม่บันทึก (ควรไม่เกิดขึ้นเพราะเรากรองแล้ว)
            if (!$hasMeterData) {
                continue;
            }
            
            // ดึงค่าขยะและค่าส่วนกลางจากข้อมูลที่ดึงแบบ batch
            $garbage = isset($allOtherCosts[$pcode]['ค่าขยะ']) ? $allOtherCosts[$pcode]['ค่าขยะ'] : 0;
            $commonArea = isset($allOtherCosts[$pcode]['ค่าส่วนกลาง']) ? $allOtherCosts[$pcode]['ค่าส่วนกลาง'] : 0;

            // คำนวณค่าไฟจากข้อมูลที่ดึงแบบ batch
            $electricityData = $this->calculateElectricityCostBatch(
                $pcode, 
                $allMeterData, 
                $allPrevMeterData, 
                $product['electricity_ppu']
            );
            
            // คำนวณค่าน้ำจากข้อมูลที่ดึงแบบ batch
            $waterData = $this->calculateWaterCostBatch(
                $pcode, 
                $allMeterData, 
                $allPrevMeterData, 
                $product['water_ppu']
            );
            
            $electricity = $electricityData['electricity'];
            $meterelectricity = $electricityData['meterelectricity'];
            $electricityMeterNumberBefore = $electricityData['electricityMeterNumberBefore'];
            
            $water = $waterData['water'];
            $meterwater = $waterData['meterwater'];
            $waterMeterNumberBefore = $waterData['waterMeterNumberBefore'];
            
            // คำนวณรวม
            $total = $electricity + $water + $garbage + $commonArea;
            
            // ดึงหมายเหตุจากข้อมูล batch
            $remark = isset($allRemarks[$pcode]) ? $allRemarks[$pcode] : '';
            
            // ตรวจสอบว่าเปิดใบแจ้งหนี้แล้วหรือไม่
            $isOpened = $this->isInvoiceOpened($pcode, $targetMonth, $targetYear);
            
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
                'status' => $isOpened ? 'open' : 'closed', // ใช้สถานะจากใบแจ้งหนี้
                'month' => (int)$targetMonth,
                'year' => (int)$targetYear,
                'waterMeterNumberBefore' => (int)$waterMeterNumberBefore,
                'electricityMeterNumberBefore' => (int)$electricityMeterNumberBefore,
                'meterwater' => (int)$meterwater,
                'meterelectricity' => (int)$meterelectricity,
                'remark' => (string)$remark,
                'water_ppu' => (float)$product['water_ppu'],
                'electricity_ppu' => (float)$product['electricity_ppu'],
            );
        }
        
        error_log("getAllInvoices: Found " . count($results) . " saved records");
        return $results;
        
    } catch (PDOException $e) {
        error_log("Error getting all invoices: " . $e->getMessage());
        return array();
    } catch (Exception $e) {
        error_log("Unexpected error in getAllInvoices: " . $e->getMessage());
        return array();
    }
}

/**
 * ตรวจสอบว่า pcode นี้เปิดใบแจ้งหนี้แล้วหรือไม่
 */
private function isInvoiceOpened($pcode, $month, $year) {
    try {
        $sql = "SELECT COUNT(*) FROM me_invoice WHERE pcode = ? AND month = ? AND year = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pcode, $month, $year]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking invoice status: " . $e->getMessage());
        return false;
    }
}
    
    /**
     * ดึงข้อมูล meter ทั้งหมดแบบ batch
     */
    private function getAllMeterDataBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            $placeholders = str_repeat('?,', count($pcodes) - 1) . '?';
            $sql = "SELECT pcode, meter_type, reading_value, remark 
                    FROM me_meter 
                    WHERE pcode IN ($placeholders) AND month = ? AND year = ?";
            
            $params = array_merge($pcodes, [$month, $year]);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['pcode']][$row['meter_type']] = $row['reading_value'];
                // เก็บ remark ถ้ามี
                if (!empty($row['remark'])) {
                    $results[$row['pcode']]['_remark'] = $row['remark'];
                }
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting batch meter data: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูลค่าใช้จ่ายอื่นๆ แบบ batch
     */
    private function getAllOtherCostsBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            $placeholders = str_repeat('?,', count($pcodes) - 1) . '?';
            $sql = "SELECT pcode, meter_ohter_type, price, remark 
                    FROM me_meter_ohter 
                    WHERE pcode IN ($placeholders) AND month = ? AND year = ?";
            
            $params = array_merge($pcodes, [$month, $year]);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['pcode']][$row['meter_ohter_type']] = $row['price'];
                // เก็บ remark ถ้ามี
                if (!empty($row['remark'])) {
                    $results[$row['pcode']]['_remark'] = $row['remark'];
                }
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting batch other costs: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูลหมายเหตุทั้งหมดแบบ batch (ดึงจาก me_meter เป็นหลัก)
     */
    private function getAllRemarksBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            $placeholders = str_repeat('?,', count($pcodes) - 1) . '?';
            $sql = "SELECT pcode, remark 
                    FROM me_meter 
                    WHERE pcode IN ($placeholders) AND month = ? AND year = ? AND remark IS NOT NULL AND remark != ''";
            
            $params = array_merge($pcodes, [$month, $year]);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['pcode']] = $row['remark'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting batch remarks: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * ดึงข้อมูล meter เดือนก่อนหน้าแบบ batch
     */
    private function getPreviousMonthMeterDataBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear = $year - 1;
            }
            
            $placeholders = str_repeat('?,', count($pcodes) - 1) . '?';
            $sql = "SELECT pcode, meter_type, reading_value 
                    FROM me_meter 
                    WHERE pcode IN ($placeholders) AND month = ? AND year = ?";
            
            $params = array_merge($pcodes, [$prevMonth, $prevYear]);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['pcode']][$row['meter_type']] = $row['reading_value'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting batch previous meter data: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * คำนวณค่าไฟจากข้อมูล batch
     */
    private function calculateElectricityCostBatch($pcode, $currentData, $prevData, $priceUnit) {
        $current = isset($currentData[$pcode]['ค่าไฟ']) ? $currentData[$pcode]['ค่าไฟ'] : 0;
        $previous = isset($prevData[$pcode]['ค่าไฟ']) ? $prevData[$pcode]['ค่าไฟ'] : 0;
        
        if (!$current) {
            return [
                'electricity' => 0,
                'meterelectricity' => 0,
                'electricityMeterNumberBefore' => 0,
            ];
        }
        
        $units = $current - $previous;
        
        return [
            'electricity' => $units > 0 ? $units * $priceUnit : 0,
            'meterelectricity' => $current,
            'electricityMeterNumberBefore' => $previous,
        ];
    }
    
    /**
     * คำนวณค่าน้ำจากข้อมูล batch
     */
    private function calculateWaterCostBatch($pcode, $currentData, $prevData, $priceUnit) {
        $current = isset($currentData[$pcode]['ค่าน้ำ']) ? $currentData[$pcode]['ค่าน้ำ'] : 0;
        $previous = isset($prevData[$pcode]['ค่าน้ำ']) ? $prevData[$pcode]['ค่าน้ำ'] : 0;
        
        if (!$current) {
            return [
                'water' => 0,
                'meterwater' => 0,
                'waterMeterNumberBefore' => 0,
            ];
        }
        
        $units = $current - $previous;
        
        return [
            'water' => $units > 0 ? $units * $priceUnit : 0,
            'meterwater' => $current,
            'waterMeterNumberBefore' => $previous,
        ];
    }
    
    /**
     * ตรวจสอบสถานะจากข้อมูล batch
     */
    private function hasMeterDataBatch($pcode, $meterData, $otherCosts) {
        $hasMeter = !empty($meterData[$pcode]);
        $hasOther = !empty($otherCosts[$pcode]);
        
        return $hasMeter || $hasOther;
    }


  

    /**
     * บันทึกหรืออัพเดทค่าใช้จ่ายอื่นๆ พร้อมหมายเหตุ
     */
    public function saveOrUpdateOtherCostWithRemark($pcode, $month, $year, $type, $price, $remark = '') {
        try {
            // ดึง user ID ของผู้ใช้ปัจจุบัน
            $currentUser = Auth::user();
            $userId = isset($currentUser['id']) ? $currentUser['id'] : null;
            
            // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
            $sql = "SELECT COUNT(*) FROM me_meter_ohter 
                    WHERE pcode = ? AND month = ? AND year = ? AND meter_ohter_type = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pcode, $month, $year, $type]);
            $exists = $stmt->fetchColumn() > 0;
            
            if ($exists) {
                // อัพเดท
                $sql = "UPDATE me_meter_ohter 
                        SET price = ?, 
                            remark = ?,
                            updated_at = NOW(), 
                            updated_by = ? 
                        WHERE pcode = ? AND month = ? AND year = ? AND meter_ohter_type = ?";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$price, $remark, $userId, $pcode, $month, $year, $type]);
                error_log("Updated other cost with remark: $type - pcode=$pcode, month=$month, year=$year, price=$price");
            } else {
                // เพิ่มใหม่
                $sql = "INSERT INTO me_meter_ohter 
                        (meter_ohter_type, pcode, month, year, price, remark, created_at, created_by, updated_at, updated_by) 
                        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), ?)";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$type, $pcode, $month, $year, $price, $remark, $userId, $userId]);
                error_log("Inserted other cost with remark: $type - pcode=$pcode, month=$month, year=$year, price=$price");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error saving other cost with remark ($type): " . $e->getMessage());
            return false;
        }
    }
    
    private function hasMeterData($pcode, $month, $year) {
        try {
            $sql = "SELECT COUNT(*) as count FROM me_meter 
                    WHERE pcode = ? AND month = ? AND year = ? 
                    AND meter_type IN ('ค่าไฟ', 'ค่าน้ำ')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pcode, $month, $year]);
            $meterCount = $stmt->fetchColumn();
            
            $sql2 = "SELECT COUNT(*) as count FROM me_meter_ohter 
                    WHERE pcode = ? AND month = ? AND year = ? 
                    AND meter_ohter_type IN ('ค่าขยะ', 'ค่าส่วนกลาง')";
            
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([$pcode, $month, $year]);
            $otherCount = $stmt2->fetchColumn();
            
            return ($meterCount > 0 || $otherCount > 0);
            
        } catch (PDOException $e) {
            error_log("Error in hasMeterData: " . $e->getMessage());
            return false;
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

    /**
 * บันทึกใบแจ้งหนี้ลง me_invoice (สร้าง 4 records แต่ใช้เลขที่เอกสารเดียวกัน)
 */
public function createInvoice($pcode, $month, $year, $electricity, $water, $garbage, $common_area, $remark = '') {
    try {
        // ดึง user ID ของผู้ใช้ปัจจุบัน
        $currentUser = Auth::user();
        $userId = isset($currentUser['id']) ? $currentUser['id'] : null;
        
        // ตรวจสอบว่ามีใบแจ้งหนี้สำหรับ pcode นี้แล้วหรือไม่
        if ($this->invoiceExists($pcode, $month, $year)) {
            return array('success' => false, 'message' => 'มีใบแจ้งหนี้สำหรับรายการนี้แล้ว');
        }
        
        // สร้างเลขที่เอกสาร (INVYYYYMMXXXX)
        $invNo = $this->generateInvoiceNumber($month, $year);
        
        // บันทึกใบแจ้งหนี้ 4 records (แต่ละ type)
        $invoiceTypes = [
            'ค่าไฟ' => $electricity,
            'ค่าน้ำ' => $water,
            'ค่าขยะ' => $garbage,
            'ค่าส่วนกลาง' => $common_area
        ];
        
        $createdCount = 0;
        $totalPrice = 0;
        
        foreach ($invoiceTypes as $type => $price) {
            if ($price > 0) { // บันทึกเฉพาะที่มีค่า
                $sql = "INSERT INTO me_invoice 
                        (inv_id, inv_no, pcode, month, year, type, price, remark, created_at, created_by, updated_at, updated_by) 
                        VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), ?)";
                
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    $invNo, // ใช้เลขที่เอกสารเดียวกัน
                    $pcode,
                    $month,
                    $year,
                    $type, // แต่ละ type
                    $price, // ราคาเฉพาะ type นั้น
                    $remark . ' (' . $type . ')', // หมายเหตุแยกตาม type
                    $userId,
                    $userId
                ]);
                
                if ($result) {
                    $createdCount++;
                    $totalPrice += $price;
                    error_log("Invoice created: $invNo - pcode=$pcode, type=$type, price=$price");
                }
            }
        }
        
        if ($createdCount > 0) {
            return array(
                'success' => true, 
                'message' => 'สร้างใบแจ้งหนี้สำเร็จ (' . $createdCount . ' รายการ)',
                'inv_no' => $invNo,
                'total_price' => $totalPrice,
                'created_count' => $createdCount
            );
        } else {
            error_log("Failed to create any invoice for pcode=$pcode");
            return array('success' => false, 'message' => 'ไม่สามารถสร้างใบแจ้งหนี้ได้');
        }
    } catch (PDOException $e) {
        error_log("Error creating invoice: " . $e->getMessage());
        return array('success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage());
    }
}

/**
 * ตรวจสอบว่ามีใบแจ้งหนี้สำหรับ pcode นี้แล้วหรือไม่ (ตรวจสอบจากเลขที่เอกสาร)
 */
public function invoiceExists($pcode, $month, $year) {
    try {
        $sql = "SELECT COUNT(*) FROM me_invoice WHERE pcode = ? AND month = ? AND year = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pcode, $month, $year]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking invoice existence: " . $e->getMessage());
        return false;
    }
}

/**
 * สร้างเลขที่เอกสารใบแจ้งหนี้รูปแบบ INRYYYYMMXXXX
 */
private function generateInvoiceNumber($month, $year) {
    try {
        // นับจำนวนเอกสารในเดือน/ปีนี้ (นับจากเลขที่เอกสารที่ไม่ซ้ำ)
        $sql = "SELECT COUNT(DISTINCT inv_no) FROM me_invoice WHERE month = ? AND year = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        $count = $stmt->fetchColumn() + 1;
        
        // รูปแบบ: INV2025100001 (INR + YYYY + MM + XXXX)
        return sprintf('INR%s%02d%04d', $year, $month, $count);
    } catch (PDOException $e) {
        error_log("Error generating invoice number: " . $e->getMessage());
        return sprintf('INR%s%02d%04d', $year, $month, 1);
    }
}

/**
 * ดึงข้อมูลใบแจ้งหนี้ตาม pcode, month, year (ทั้งหมด 4 records)
 */
public function getInvoiceByPcode($pcode, $month, $year) {
    try {
        $sql = "SELECT * FROM me_invoice WHERE pcode = ? AND month = ? AND year = ? ORDER BY type";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pcode, $month, $year]);
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($invoices)) {
            // รวมข้อมูลเพื่อแสดงผล
            $firstInvoice = $invoices[0];
            $totalPrice = 0;
            $types = [];
            
            foreach ($invoices as $invoice) {
                $totalPrice += $invoice['price'];
                $types[] = $invoice['type'] . ' (' . number_format($invoice['price'], 2) . ')';
            }
            
            return [
                'inv_no' => $firstInvoice['inv_no'],
                'total_price' => $totalPrice,
                'types' => implode(', ', $types),
                'all_invoices' => $invoices
            ];
        }
        
        return null;
    } catch (PDOException $e) {
        error_log("Error getting invoice by pcode: " . $e->getMessage());
        return null;
    }
}

}