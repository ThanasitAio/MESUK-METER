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
        
        // ตรวจสอบสิทธิ์ (เฉพาะ admin เท่านั้น)
        $currentUser = Auth::user();
        if ($currentUser['role'] !== 'admin') {
            http_response_code(403);
            die(t('user_management.access_denied'));
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
        $invoices = $this->invoiceModel->getAllInvoices();
        $stats = array(
            'total' => count($invoices)
        );
        
        $data = array(
            'title' => t('invoice_management.title'),
            'meters' => $invoices,
            'stats' => $stats
        );
        
        $this->view('pages/invoice-management/index', $data);
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
        
        $invoices = $this->invoiceModel->getAllInvoices($month, $year);
        
        // Debug: log ข้อมูลแถวแรก
        if (!empty($invoices)) {
            error_log("Controller getByPeriod: First invoice - " . json_encode($invoices[0]));
        }
        
        echo json_encode(array(
            'success' => true,
            'data' => $invoices,
            'count' => count($invoices)
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
        
        $products = $this->invoiceModel->searchMeter($keyword, $filters);
        
        echo json_encode(array(
            'success' => true,
            'data' => $products,
            'count' => count($products)
        ));
        exit;
    }


    
}

