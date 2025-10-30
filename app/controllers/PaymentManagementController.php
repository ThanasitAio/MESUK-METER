<?php

class PaymentManagementController extends Controller {
    private $paymentModel;
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

        // สร้าง Payment model
        if (!class_exists('Model')) {
            require_once __DIR__ . '/../core/Model.php';
        }
        if (!class_exists('Payment')) {
            require_once __DIR__ . '/../models/Payment.php';
        }
        $this->paymentModel = new Payment();
    }
    
    /**
     * แสดงหน้าจัดการการชำระเงิน
     */
    public function index() {
        // ดึงข้อมูลของเดือน/ปีปัจจุบัน
        $month = (int)date('m');
        $year = (int)date('Y');
        $payments = $this->paymentModel->getAllPayments($month, $year);
        $stats = $this->paymentModel->getPaymentStats($month, $year);

        $data = array(
            'title' => t('payment_management.title'),
            'payments' => $payments,
            'stats' => $stats
        );

        $this->view('pages/payment-management/index', $data);
    }

    /**
     * ดึงข้อมูลตามเดือน/ปี (AJAX)
     */
    public function getByPeriod() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Method not allowed');
            }
            
            $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
            $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
            
            $payments = $this->paymentModel->getAllPayments($month, $year);
            $stats = $this->paymentModel->getPaymentStats($month, $year);
            
            $response = array(
                'success' => true,
                'data' => $payments,
                'stats' => $stats,
                'count' => count($payments)
            );
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            $errorResponse = array(
                'success' => false,
                'message' => $e->getMessage()
            );
            echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    /**
     * ตรวจสอบว่ามีการชำระเงินแล้วหรือไม่ (AJAX)
     */
    public function checkPayment() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        }
        
        $pcode = isset($_GET['pcode']) ? trim($_GET['pcode']) : '';
        $month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
        $year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
        
        if (empty($pcode) || $month < 1 || $month > 12 || $year < 2000) {
            echo json_encode(array('success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน'));
            exit;
        }
        
        $payment = $this->paymentModel->getPaymentByPcode($pcode, $month, $year);
        
        if ($payment) {
            echo json_encode(array(
                'success' => true,
                'exists' => true,
                'payment' => $payment
            ));
        } else {
            echo json_encode(array(
                'success' => true,
                'exists' => false
            ));
        }
        exit;
    }

    /**
     * บันทึกการชำระเงิน (AJAX)
     */
    public function createPayment() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }
            
            $pcode = isset($_POST['pcode']) ? trim($_POST['pcode']) : '';
            $month = isset($_POST['month']) ? (int)$_POST['month'] : 0;
            $year = isset($_POST['year']) ? (int)$_POST['year'] : 0;
            $electricity = isset($_POST['electricity']) ? (float)$_POST['electricity'] : 0;
            $water = isset($_POST['water']) ? (float)$_POST['water'] : 0;
            $garbage = isset($_POST['garbage']) ? (float)$_POST['garbage'] : 0;
            $common_area = isset($_POST['common_area']) ? (float)$_POST['common_area'] : 0;
            
            // ตรวจสอบข้อมูล
            if (empty($pcode) || $month < 1 || $month > 12 || $year < 2000) {
                throw new Exception('ข้อมูลไม่ครบถ้วน');
            }
            
            // เรียกใช้ model เพื่อบันทึกการชำระเงิน
            $result = $this->paymentModel->createPayment(
                $pcode, 
                $month, 
                $year, 
                $electricity, 
                $water, 
                $garbage, 
                $common_area
            );
            
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            $errorResponse = array(
                'success' => false,
                'message' => $e->getMessage()
            );
            echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
}