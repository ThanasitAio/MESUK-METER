<?php

class Meter extends Model {
    protected $table = 'me_meter';

    /**
     * ดึงข้อมูลทั้งหมด พร้อมคำนวณค่าใช้จ่ายสำหรับช่วงเวลาที่กำหนด
     * ถ้าไม่ระบุเดือน/ปี จะใช้เดือน/ปีปัจจุบัน
     */
    public function getAllMeters($month = null, $year = null) {
        try {
            $currentUser = Auth::user();
            $userRole = $currentUser['role'];
            $chckCode = $currentUser['code'];

            $whrData = "";
            if($userRole == 'agent'){
                $whrData .= " AND p.sales_rep_code = '".$chckCode."' ";
            }

            // ดึงรายการ pcode ทั้งหมด
            $sql = "SELECT 
                    p.pcode,
                    p.pdesc,
                    p.sales_rep_code,
                    pc.cate_name,
                    COALESCE(pg1.groupname, pg2.groupname) as groupname,
                    COALESCE(p.meter_1_ppu, 0) as electricity_ppu,
                    COALESCE(p.meter_0_ppu, 0) as water_ppu
                FROM ali_productcategory pc
                LEFT JOIN ali_productgroup pg1 ON pc.id = pg1.id_cate
                LEFT JOIN ali_product p ON pg1.id = p.group_id
                LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                WHERE (pc.id = 34 OR pc.id = 54) AND p.sh = 1 $whrData
                ORDER BY pc.cate_name, groupname, p.pcode";

            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("getAllMeters: Execute failed - " . print_r($stmt->errorInfo(), true));
                return array();
            }
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($products)) {
                return array();
            }
            
            // ใช้เดือน/ปีที่ระบุ หรือเดือน/ปีปัจจุบัน
            $targetMonth = $month ? (int)$month : (int)date('m');
            $targetYear = $year ? (int)$year : (int)date('Y');
            
            // ดึงข้อมูลทั้งหมดแบบ batch
            $pcodes = array_column($products, 'pcode');
            $allMeterData = $this->getAllMeterDataBatch($pcodes, $targetMonth, $targetYear);
            $allOtherCosts = $this->getAllOtherCostsBatch($pcodes, $targetMonth, $targetYear);
            $allPrevMeterData = $this->getPreviousMonthMeterDataBatch($pcodes, $targetMonth, $targetYear);
            $allRemarks = $this->getAllRemarksBatch($pcodes, $targetMonth, $targetYear);
            
            // เพิ่มการ log เพื่อ debug
            error_log("getAllMeters: Previous month data count - " . count($allPrevMeterData));
            foreach($allPrevMeterData as $pcode => $data) {
                error_log("Previous data for $pcode: " . print_r($data, true));
            }
            
            $results = array();
            
            // สำหรับแต่ละ pcode (เฉพาะเดือน/ปีที่กำหนด)
            foreach ($products as $product) {
                $pcode = $product['pcode'];
                
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
                
                // ดึงข้อมูลวันที่
                $dateMeterElectricity = isset($allMeterData[$pcode]['_reading_date_ค่าไฟ']) ? 
                                       $allMeterData[$pcode]['_reading_date_ค่าไฟ'] : '';
                $dateMeterWater = isset($allMeterData[$pcode]['_reading_date_ค่าน้ำ']) ? 
                                 $allMeterData[$pcode]['_reading_date_ค่าน้ำ'] : '';
                
                // คำนวณรวม
                $total = $electricity + $water + $garbage + $commonArea;
                
                // ตรวจสอบสถานะจากข้อมูลที่ดึงแบบ batch
                $hasMeterData = $this->hasMeterDataBatch($pcode, $allMeterData, $allOtherCosts);
                $status = $hasMeterData ? 'saved' : 'unsaved';
                
                // ดึงหมายเหตุจากข้อมูล batch
                $remark = isset($allRemarks[$pcode]) ? $allRemarks[$pcode] : '';
                
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
                    'meterwater' => (int)$meterwater,
                    'meterelectricity' => (int)$meterelectricity,
                    'remark' => (string)$remark,
                    'water_ppu' => (float)$product['water_ppu'],
                    'electricity_ppu' => (float)$product['electricity_ppu'],
                    'dateMeterElectricity' => (string)$dateMeterElectricity,
                    'dateMeterWater' => (string)$dateMeterWater,
                );
            }
            
            error_log("getAllMeters: Found " . count($results) . " results");
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
     * ดึงข้อมูล meter ทั้งหมดแบบ batch
     */
    private function getAllMeterDataBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            $placeholders = str_repeat('?,', count($pcodes) - 1) . '?';
            $sql = "SELECT pcode, meter_type, reading_value, remark, reading_date 
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
                // เก็บ reading_date ถ้ามี
                if (!empty($row['reading_date'])) {
                    $results[$row['pcode']]['_reading_date_' . $row['meter_type']] = $row['reading_date'];
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
     * ดึงข้อมูล meter เดือนก่อนหน้าแบบ batch - แก้ไขแล้ว
     */
    private function getPreviousMonthMeterDataBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            // คำนวณเดือนและปีก่อนหน้า
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
            
            error_log("getPreviousMonthMeterDataBatch: Found " . count($results) . " records for month $prevMonth, year $prevYear");
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting batch previous meter data: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * คำนวณค่าไฟจากข้อมูล batch - แก้ไขแล้ว
     */
    private function calculateElectricityCostBatch($pcode, $currentData, $prevData, $priceUnit) {
        $current = isset($currentData[$pcode]['ค่าไฟ']) ? (float)$currentData[$pcode]['ค่าไฟ'] : 0;
        $previous = isset($prevData[$pcode]['ค่าไฟ']) ? (float)$prevData[$pcode]['ค่าไฟ'] : 0;
        
        // อนุญาตให้คำนวณได้แม้ค่า current จะเป็น 0
        $units = $current - $previous;
        
        // หน่วยต้องไม่ติดลบ
        if ($units < 0) {
            error_log("Warning: Negative units for electricity - pcode: $pcode, current: $current, previous: $previous");
            $units = 0;
        }
        
        return [
            'electricity' => $units * $priceUnit,
            'meterelectricity' => $current,
            'electricityMeterNumberBefore' => $previous,
        ];
    }
    
    /**
     * คำนวณค่าน้ำจากข้อมูล batch - แก้ไขแล้ว
     */
    private function calculateWaterCostBatch($pcode, $currentData, $prevData, $priceUnit) {
        $current = isset($currentData[$pcode]['ค่าน้ำ']) ? (float)$currentData[$pcode]['ค่าน้ำ'] : 0;
        $previous = isset($prevData[$pcode]['ค่าน้ำ']) ? (float)$prevData[$pcode]['ค่าน้ำ'] : 0;
        
        // อนุญาตให้คำนวณได้แม้ค่า current จะเป็น 0
        $units = $current - $previous;
        
        // หน่วยต้องไม่ติดลบ
        if ($units < 0) {
            error_log("Warning: Negative units for water - pcode: $pcode, current: $current, previous: $previous");
            $units = 0;
        }
        
        return [
            'water' => $units * $priceUnit,
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
     * ฟังก์ชัน debug เพื่อตรวจสอบข้อมูล - เพิ่มใหม่
     */
    public function debugMeterData($pcode, $month, $year) {
        try {
            // ข้อมูลเดือนปัจจุบัน
            $currentSql = "SELECT meter_type, reading_value 
                          FROM me_meter 
                          WHERE pcode = ? AND month = ? AND year = ?";
            $stmt = $this->db->prepare($currentSql);
            $stmt->execute([$pcode, $month, $year]);
            $currentData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // ข้อมูลเดือนก่อนหน้า
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear = $year - 1;
            }
            
            $prevSql = "SELECT meter_type, reading_value 
                       FROM me_meter 
                       WHERE pcode = ? AND month = ? AND year = ?";
            $stmt2 = $this->db->prepare($prevSql);
            $stmt2->execute([$pcode, $prevMonth, $prevYear]);
            $prevData = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Debug Meter Data for $pcode:");
            error_log("Current ($month/$year): " . print_r($currentData, true));
            error_log("Previous ($prevMonth/$prevYear): " . print_r($prevData, true));
            
            return [
                'current' => $currentData,
                'previous' => $prevData
            ];
            
        } catch (PDOException $e) {
            error_log("Error in debugMeterData: " . $e->getMessage());
            return null;
        }
    }

   public function saveOrUpdateMeterWithImage($data) {
    try {
        // ดึง user ID ของผู้ใช้ปัจจุบัน
        $userId = null;
        if (class_exists('Auth')) {
            $currentUser = Auth::user();
            $userId = isset($currentUser['id']) ? $currentUser['id'] : null;
        }
        
        $pcode = $data['pcode'];
        $month = $data['month'];
        $year = $data['year'];
        $remark = isset($data['remark']) ? trim($data['remark']) : '';
        $img = isset($data['img']) ? $data['img'] : null;
        
        // แปลง empty string เป็น NULL สำหรับ reading_date (สำคัญสำหรับ DATE field)
        $reading_date = isset($data['reading_date']) && $data['reading_date'] !== '' ? $data['reading_date'] : null;
        
        // อนุญาตให้ reading_value เป็นค่าว่างได้
        $reading_value = isset($data['reading_value']) && $data['reading_value'] !== '' ? $data['reading_value'] : null;
        
        // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
        $exists = $this->meterRecordExists($data['pcode'], $data['month'], $data['year'], $data['type']);
        $datetime = date('Y-m-d H:i:s');
       
        if ($exists) {
            // Update existing record - อนุญาตให้ reading_value เป็น NULL ได้
            $sql = "UPDATE me_meter 
                    SET reading_value = ?, 
                        remark = ?,
                        img = ?,
                        reading_date = ?,
                        updated_at = '$datetime', 
                        updated_by = ? 
                    WHERE pcode = ? AND month = ? AND year = ? AND meter_type = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(array(
                $reading_value,
                $remark,
                $img,
                $reading_date,
                $userId,
                $data['pcode'],
                $data['month'],
                $data['year'],
                $data['type']
            ));
            error_log("Updated meter with image: " . $data['type'] . " - pcode=" . $data['pcode'] . ", reading_value=" . ($reading_value !== null ? $reading_value : 'NULL') . ", img=" . ($img !== null ? $img : 'NULL') . ", reading_date=" . ($reading_date !== null ? $reading_date : 'NULL'));
        } else {
            // Insert new record - อนุญาตให้ reading_value เป็น NULL ได้
            $sql = "INSERT INTO me_meter 
                    (meter_type, pcode, month, year, reading_value, remark, img, reading_date, created_at, created_by, updated_at, updated_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, '$datetime', ?, '$datetime', ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(array(
                $data['type'],
                $data['pcode'],
                $data['month'],
                $data['year'],
                $reading_value,
                $remark,
                $img,
                $reading_date,
                $userId,
                $userId
            ));
            error_log("Inserted meter with image: " . $data['type'] . " - pcode=" . $data['pcode'] . ", reading_value=" . ($reading_value !== null ? $reading_value : 'NULL') . ", img=" . ($img !== null ? $img : 'NULL') . ", reading_date=" . ($reading_date !== null ? $reading_date : 'NULL'));
        }
        
        // ตรวจสอบ error ถ้าบันทึกไม่สำเร็จ
        if (!$result) {
            if (isset($stmt)) {
                $errorInfo = $stmt->errorInfo();
                error_log("SQL execute failed for " . $data['type'] . ": " . print_r($errorInfo, true));
            }
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Error saving or updating meter record with image (" . $data['type'] . "): " . $e->getMessage());
        if (isset($stmt)) {
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error Info: " . print_r($errorInfo, true));
        }
        return false;
    }
}

    /**
     * บันทึกหรืออัพเดทค่าใช้จ่ายอื่นๆ พร้อมหมายเหตุ
     */
    public function saveOrUpdateOtherCostWithRemark($pcode, $month, $year, $type, $price, $remark = '') {
        try {
            // ดึง user ID ของผู้ใช้ปัจจุบัน
            $currentUser = Auth::user();
            $userId = isset($currentUser['id']) ? $currentUser['id'] : null;
            
            // อนุญาตให้ price เป็นค่าว่างได้
            $price_value = $price !== '' ? $price : null;
            
            // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
            $sql = "SELECT COUNT(*) FROM me_meter_ohter 
                    WHERE pcode = ? AND month = ? AND year = ? AND meter_ohter_type = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pcode, $month, $year, $type]);
            $exists = $stmt->fetchColumn() > 0;
            
            $datetime = date('Y-m-d H:i:s');
            if ($exists) {
                // อัพเดท - อนุญาตให้ price เป็น NULL ได้
                $sql = "UPDATE me_meter_ohter 
                        SET price = ?, 
                            remark = ?,
                            updated_at = '$datetime', 
                            updated_by = ? 
                        WHERE pcode = ? AND month = ? AND year = ? AND meter_ohter_type = ?";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$price_value, $remark, $userId, $pcode, $month, $year, $type]);
                error_log("Updated other cost with remark: $type - pcode=$pcode, month=$month, year=$year, price=$price_value");
            } else {
                // เพิ่มใหม่ - อนุญาตให้ price เป็น NULL ได้
                $sql = "INSERT INTO me_meter_ohter 
                        (meter_ohter_type, pcode, month, year, price, remark, created_at, created_by, updated_at, updated_by) 
                        VALUES (?, ?, ?, ?, ?, ?, '$datetime', ?, '$datetime', ?)";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$type, $pcode, $month, $year, $price_value, $remark, $userId, $userId]);
                error_log("Inserted other cost with remark: $type - pcode=$pcode, month=$month, year=$year, price=$price_value");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error saving other cost with remark ($type): " . $e->getMessage());
            return false;
        }
    }

    // ฟังก์ชันอื่นๆ ที่เหลือให้คงเดิม...
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
            error_log("meterRecordExists: pcode=$pcode, month=$month, year=$year, type=$type, count=$count");

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
            
            // อนุญาตให้ reading_value เป็นค่าว่างได้
            $reading_value = isset($data['reading_value']) && $data['reading_value'] !== '' ? $data['reading_value'] : null;
            
            // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
            $exists = $this->meterRecordExists($data['pcode'], $data['month'], $data['year'], $data['type']);
            $datetime = date('Y-m-d H:i:s');
            if ($exists) {
                // Update existing record - อนุญาตให้ reading_value เป็น NULL ได้
                $sql = "UPDATE me_meter 
                        SET reading_value = ?, 
                            updated_at = '$datetime', 
                            updated_by = ? 
                        WHERE pcode = ? AND month = ? AND year = ? AND meter_type = ?";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    $reading_value,
                    $userId,
                    $data['pcode'],
                    $data['month'],
                    $data['year'],
                    $data['type']
                ]);
                error_log("Updated meter: " . $data['type'] . " - pcode=" . $data['pcode'] . ", value=" . $reading_value . ", result=" . ($result ? 'success' : 'failed'));
            } else {
                 
                // Insert new record - อนุญาตให้ reading_value เป็น NULL ได้
                $sql = "INSERT INTO me_meter 
                        (meter_type, pcode, month, year, reading_value, reading_date, created_at, created_by, updated_at, updated_by) 
                        VALUES (?, ?, ?, ?, ?, '$datetime', '$datetime', ?, '$datetime', ?)";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    $data['type'],
                    $data['pcode'],
                    $data['month'],
                    $data['year'],
                    $reading_value,
                    $userId,
                    $userId
                ]);
               
                error_log("Inserted meter: " . $data['type'] . " - pcode=" . $data['pcode'] . ", value=" . $reading_value . ", result=" . ($result ? 'success' : 'failed'));
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