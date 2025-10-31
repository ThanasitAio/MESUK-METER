<?php

class MeterManagementController extends Controller {
    private $meterModel;
    private $db;
    
    public function __construct() {
        // ตรวจสอบการ login
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        // สร้าง Database connection
        if (!class_exists('Database')) {
            require_once __DIR__ . '/../core/Database.php';
        }
        $this->db = Database::getInstance();

        // สร้าง Meter model
        if (!class_exists('Model')) {
            require_once __DIR__ . '/../core/Model.php';
        }
        if (!class_exists('Meter')) {
            require_once __DIR__ . '/../models/Meter.php';
        }
        $this->meterModel = new Meter();
    }
    
    /**
     * แสดง
     */
    public function index() {
        // ดึงข้อมูลของเดือน/ปีปัจจุบัน
        $meters = $this->meterModel->getAllMeters();
        $stats = array(
            'total' => count($meters)
        );
        
        $data = array(
            'title' => t('meter_management.title'),
            'meters' => $meters,
            'stats' => $stats
        );
        
        $this->view('pages/meter-management/index', $data);
    }

    /**
     * ดึงข้อมูลตามเดือน/ปี (AJAX)
     */
    public function getByPeriod() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        }
        
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        
        $meters = $this->meterModel->getAllMeters($month, $year);
        
        // Debug: log ข้อมูลแถวแรก
        if (!empty($meters)) {
            error_log("Controller getByPeriod: First meter - " . json_encode($meters[0]));
        }
        
        echo json_encode(array(
            'success' => true,
            'data' => $meters,
            'count' => count($meters)
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }


    /**
     * ค้นหา (AJAX)
     */
    public function search() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        }
        
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $filters = array();
        
        if (isset($_GET['group_id']) && !empty($_GET['group_id'])) {
            $filters['group_id'] = $_GET['group_id'];
        }
        
        if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
            $filters['category_id'] = $_GET['category_id'];
        }
        
        $products = $this->meterModel->searchMeter($keyword, $filters);
        
        echo json_encode(array(
            'success' => true,
            'data' => $products,
            'count' => count($products)
        ));
        exit;
    }

    /**
     * บันทึกข้อมูลมิเตอร์พร้อมหมายเหตุ (AJAX)
     */
    public function saveMeter() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        } 
        
        try {
            // รับข้อมูลจากฟอร์ม
            $pcode = isset($_POST['pcode']) ? trim($_POST['pcode']) : '';
            $month = isset($_POST['month']) ? (int)$_POST['month'] : 0;
            $year = isset($_POST['year']) ? (int)$_POST['year'] : 0;
            $electricity = isset($_POST['electricity']) ? (float)$_POST['electricity'] : 0;
            $water = isset($_POST['water']) ? (float)$_POST['water'] : 0;
            $garbage = isset($_POST['garbage']) ? (float)$_POST['garbage'] : 0;
            $common_area = isset($_POST['common_area']) ? (float)$_POST['common_area'] : 0;
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';

            
            // ตรวจสอบข้อมูลที่จำเป็น
            if (empty($pcode) || $month === 0 || $year === 0) {
                echo json_encode(array(
                    'success' => false, 
                    'message' => 'ข้อมูลไม่ครบถ้วน: รหัสสินค้า, เดือน, ปี'
                ));
                exit;
            }
            
            // Debug: log ข้อมูลที่ได้รับ
            error_log("saveMeter: pcode=$pcode, month=$month, year=$year, electricity=$electricity, water=$water, garbage=$garbage, common_area=$common_area, remark=$remark");
            
            // ตัวแปรเก็บผลลัพธ์
            $allSuccess = true;
            $errors = array();

            // บันทึกรูปภาพถ้ามี
            $electricityImagePath = $this->saveMeterImage('img_electricity', $pcode, $month, $year, 'electricity');
            $waterImagePath = $this->saveMeterImage('img_water', $pcode, $month, $year, 'water');

            // ในฟังก์ชัน saveMeter เพิ่มการรับค่า current images
            $currentElectricityImage = isset($_POST['current_electricity_image']) ? trim($_POST['current_electricity_image']) : '';
            $currentWaterImage = isset($_POST['current_water_image']) ? trim($_POST['current_water_image']) : '';

            
            
            // บันทึกข้อมูลค่าไฟพร้อมหมายเหตุ
            $electricityData = array(
                'pcode' => $pcode,
                'month' => $month,
                'year' => $year,
                'type' => 'ค่าไฟ',
                'reading_value' => $electricity,
                'remark' => $remark,
                'img' => $electricityImagePath ?: $currentElectricityImage
            );
            
            $electricityResult = $this->meterModel->saveOrUpdateMeterWithImage($electricityData);
            error_log("Electricity save result: " . ($electricityResult ? 'success' : 'failed'));
            if (!$electricityResult) {
                $allSuccess = false;
                $errors[] = 'บันทึกค่าไฟไม่สำเร็จ';
            }
            
            // บันทึกข้อมูลค่าน้ำพร้อมหมายเหตุ
            $waterData = array(
                'pcode' => $pcode,
                'month' => $month,
                'year' => $year,
                'type' => 'ค่าน้ำ',
                'reading_value' => $water,
                'remark' => $remark,
                'img' =>  $waterImagePath ?: $currentWaterImage
            );
            $waterResult = $this->meterModel->saveOrUpdateMeterWithImage($waterData);
            error_log("Water save result: " . ($waterResult ? 'success' : 'failed'));
            if (!$waterResult) {
                $allSuccess = false;
                $errors[] = 'บันทึกค่าน้ำไม่สำเร็จ';
            }
            
            // บันทึกค่าขยะพร้อมหมายเหตุ
            $garbageResult = $this->meterModel->saveOrUpdateOtherCostWithRemark($pcode, $month, $year, 'ค่าขยะ', $garbage, $remark);
            error_log("Garbage save result: " . ($garbageResult ? 'success' : 'failed'));
            if (!$garbageResult) {
                $allSuccess = false;
                $errors[] = 'บันทึกค่าขยะไม่สำเร็จ';
            }
            
            // บันทึกค่าส่วนกลางพร้อมหมายเหตุ
            $commonAreaResult = $this->meterModel->saveOrUpdateOtherCostWithRemark($pcode, $month, $year, 'ค่าส่วนกลาง', $common_area, $remark);
            error_log("Common area save result: " . ($commonAreaResult ? 'success' : 'failed'));
            if (!$commonAreaResult) {
                $allSuccess = false;
                $errors[] = 'บันทึกค่าส่วนกลางไม่สำเร็จ';
            }
            
            // อัพเดทค่า meter ล่าสุดในตาราง ali_product
            $this->updateLatestMeterValues($pcode, $electricity, $water);
            
            if ($allSuccess) {
                echo json_encode(array(
                    'success' => true,
                    'message' => 'บันทึกข้อมูลสำเร็จ'
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'บันทึกข้อมูลไม่สมบูรณ์: ' . implode(', ', $errors)
                ));
            }
            
        } catch (Exception $e) {
            error_log("Error in saveMeter: " . $e->getMessage());
            echo json_encode(array(
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการบันทึก: ' . $e->getMessage()
            ));
        }
        exit;
    }

     /**
     * บันทึกรูปภาพมิเตอร์ (รองรับ PHP 5.6)
     */
    private function saveMeterImage($fieldName, $pcode, $month, $year, $type) {
        // ตรวจสอบว่ามีไฟล์ถูกอัพโหลดหรือไม่
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $file = $_FILES[$fieldName];
        
        // ตรวจสอบประเภทไฟล์แบบเก่าสำหรับ PHP 5.6
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
        $fileType = $file['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            error_log("Invalid file type: " . $fileType);
            return null;
        }
        
        // ตรวจสอบขนาดไฟล์ (จำกัดที่ 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            error_log("File too large: " . $file['size']);
            return null;
        }
        
        // สร้างโฟลเดอร์เก็บรูปภาพ
        $uploadDir = __DIR__ . '/../../public/uploads/meters/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // สร้างชื่อไฟล์ที่ปลอดภัย
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $pcode);
        $filename = $safeFilename . "_" . $month . "_" . $year . "_" . $type . "_" . time() . "." . $fileExtension;
        $filepath = $uploadDir . $filename;
        
        // บันทึกไฟล์
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return "/uploads/meters/" . $filename; // Return relative path
        }
        
        error_log("Failed to save image: " . $filepath);
        return null;
    }

    /**
     * อัพเดทค่า meter ล่าสุดในตาราง ali_product
     */
    private function updateLatestMeterValues($pcode, $electricity, $water) {
        try {
            // อัพเดทเฉพาะถ้ามีค่า (มากกว่า 0)
            if ($electricity > 0) {
                $sql = "UPDATE ali_product SET meter_1_latest = ? WHERE pcode = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$electricity, $pcode]);
                error_log("Updated electricity latest value: pcode=$pcode, value=$electricity");
            }
            
            if ($water > 0) {
                $sql = "UPDATE ali_product SET meter_0_latest = ? WHERE pcode = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$water, $pcode]);
                error_log("Updated water latest value: pcode=$pcode, value=$water");
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Error updating latest meter values: " . $e->getMessage());
            return false;
        }
    }

    /**
     * บันทึกค่าใช้จ่ายอื่นๆ (สำหรับความเข้ากันได้ย้อนหลัง)
     */
    private function saveOtherCost($pcode, $month, $year, $type, $price) {
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
                // อัพเดท (แม้ค่า price เป็น 0)
                $sql = "UPDATE me_meter_ohter 
                        SET price = ?, 
                            updated_at = NOW(), 
                            updated_by = ? 
                        WHERE pcode = ? AND month = ? AND year = ? AND meter_ohter_type = ?";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$price, $userId, $pcode, $month, $year, $type]);
                error_log("Updated $type: pcode=$pcode, month=$month, year=$year, price=$price, result=" . ($result ? 'success' : 'failed'));
            } else {
                // เพิ่มใหม่ (แม้ค่า price เป็น 0)
                $sql = "INSERT INTO me_meter_ohter 
                        (meter_ohter_type, pcode, month, year, price, created_at, created_by, updated_at, updated_by) 
                        VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW(), ?)";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$type, $pcode, $month, $year, $price, $userId, $userId]);
                error_log("Inserted $type: pcode=$pcode, month=$month, year=$year, price=$price, result=" . ($result ? 'success' : 'failed'));
            }
            
            if (!$result) {
                error_log("SQL Error for $type: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error saving other cost ($type): " . $e->getMessage());
            error_log("PDO Error Code: " . $e->getCode());
            return false;
        }
    }

    /**
     * ดึงข้อมูลรูปภาพมิเตอร์ (AJAX)
     */
    public function getMeterImages() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        }
        
        try {
            $pcode = isset($_GET['pcode']) ? trim($_GET['pcode']) : '';
            $month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
            $year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
            
            if (empty($pcode) || $month === 0 || $year === 0) {
                echo json_encode(array('success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน'));
                exit;
            }
            
            // ดึงข้อมูลรูปภาพจากฐานข้อมูล
            $electricityImage = $this->getMeterImage($pcode, $month, $year, 'ค่าไฟ');
            $waterImage = $this->getMeterImage($pcode, $month, $year, 'ค่าน้ำ');
            
            echo json_encode(array(
                'success' => true,
                'data' => array(
                    'electricityImage' => $electricityImage,
                    'waterImage' => $waterImage
                )
            ));
            
        } catch (Exception $e) {
            error_log("Error getting meter images: " . $e->getMessage());
            echo json_encode(array(
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลรูปภาพ'
            ));
        }
        exit;
    }

    /**
     * ดึง path รูปภาพจากฐานข้อมูล
     */
    private function getMeterImage($pcode, $month, $year, $type) {
        try {
            $sql = "SELECT img FROM me_meter WHERE pcode = ? AND month = ? AND year = ? AND meter_type = ? AND img IS NOT NULL AND img != ''";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array($pcode, $month, $year, $type));
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['img'] : null;
            
        } catch (PDOException $e) {
            error_log("Error getting meter image: " . $e->getMessage());
            return null;
        }
    }

    
}

