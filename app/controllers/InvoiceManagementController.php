<?php

class InvoiceManagementController extends Controller {
    private $invoiceModel;
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
        if (!class_exists('Invoice')) {
            require_once __DIR__ . '/../models/Invoice.php';
        }
        $this->invoiceModel = new Invoice();
    }
    
    /**
     * แสดง
     */
    public function index() {
        // ดึงข้อมูลของเดือน/ปีปัจจุบัน
        $month = (int)date('m');
        $year = (int)date('Y');
        $invoices = $this->invoiceModel->getAllInvoices($month, $year);
        $stats = $this->invoiceModel->getInvoiceStats($month, $year);

        $data = array(
            'title' => t('invoice_management.title'),
            'meters' => $invoices,
            'stats' => $stats
        );

        $this->view('pages/invoice-management/index', $data);
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
        
        $products = $this->invoiceModel->searchMeter($keyword, $filters);
        
        echo json_encode(array(
            'success' => true,
            'data' => $products,
            'count' => count($products)
        ));
        exit;
    }

    
    /**
 * ตรวจสอบว่ามีใบแจ้งหนี้แล้วหรือไม่ (AJAX)
 */
public function checkInvoice() {
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
    
    $invoice = $this->invoiceModel->getInvoiceByPcode($pcode, $month, $year);
    
    if ($invoice) {
        echo json_encode(array(
            'success' => true,
            'exists' => true,
            'invoice' => $invoice
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
 * ดึงข้อมูลตามเดือน/ปี (AJAX)
 */
public function getByPeriod() {
    // ตั้ง header ก่อน anything else
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            throw new Exception('Method not allowed');
        }
        
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        
        $invoices = $this->invoiceModel->getAllInvoices($month, $year);
        $stats = $this->invoiceModel->getInvoiceStats($month, $year);
        
        $response = array(
            'success' => true,
            'data' => $invoices,
            'stats' => $stats,
            'count' => count($invoices)
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
 * สร้างใบแจ้งหนี้ (AJAX)
 */
public function createInvoice() {
    // ตั้ง header ก่อน anything else
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
        
        // เรียกใช้ model เพื่อสร้างใบแจ้งหนี้
        $result = $this->invoiceModel->createInvoice(
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

