<?php

class ProductManagementController extends Controller {
    private $productModel;
    
    public function __construct() {
        // ตรวจสอบการ login
        if (!Auth::check()) {
            header('Location: ' . url('/login'));
            exit;
        }
        
        // สร้าง Product model
        if (!class_exists('Model')) {
            require_once __DIR__ . '/../core/Model.php';
        }
        if (!class_exists('Product')) {
            require_once __DIR__ . '/../models/Product.php';
        }
        $this->productModel = new Product();
    }
    
    /**
     * แสดงหน้ารายการสินค้า
     */
    public function index() {
        $products = $this->productModel->getAllProducts();
        $stats = array(
            'total' => $this->productModel->countProducts()
        );
        
        $data = array(
            'title' => t('product_management.title'),
            'products' => $products,
            'stats' => $stats
        );
        
        $this->view('pages/product-management/index', $data);
    }
    
    /**
     * แสดงฟอร์มแก้ไขสินค้า
     */
    public function edit($id) {
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            http_response_code(404);
            die(t('product_management.product_not_found'));
        }
        
        $data = array(
            'title' => t('product_management.edit_product'),
            'action' => 'edit',
            'product' => $product
        );
        
        $this->view('pages/product-management/form', $data);
    }
    
    /**
     * อัพเดทข้อมูลสินค้า (AJAX)
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        }

        $ProductId = $this->productModel->getProductById($id);
        if (!$ProductId) {
            http_response_code(404);
            echo json_encode(array('success' => false, 'errors' => array(t('product_management.product_not_found'))));
            exit;
        }

        
        // เตรียมข้อมูลสินค้า
        $productData = array(
            'investor_owner' => isset($_POST['investor_owner']) ? $_POST['investor_owner'] : null,
            'investor_address' => isset($_POST['investor_address']) ? $_POST['investor_address'] : null,
            'investor_phone' => isset($_POST['investor_phone']) ? $_POST['investor_phone'] : null,
            'tenant' => isset($_POST['tenant']) ? $_POST['tenant'] : null,
            'tenant_phone' => isset($_POST['tenant_phone']) ? $_POST['tenant_phone'] : null,
            'tenant_tax_id' => isset($_POST['tenant_tax_id']) ? $_POST['tenant_tax_id'] : null,
            'bank_acc_name' => isset($_POST['bank_acc_name']) ? $_POST['bank_acc_name'] : null,
            'bank_name' => isset($_POST['bank_name']) ? $_POST['bank_name'] : null,
            'bank_branch' => isset($_POST['bank_branch']) ? $_POST['bank_branch'] : null,
            'bank_acc_no' => isset($_POST['bank_acc_no']) ? $_POST['bank_acc_no'] : null,
            'meter_0_ppu' => isset($_POST['meter_0_ppu']) ? $_POST['meter_0_ppu'] : null,
            'meter_1_ppu' => isset($_POST['meter_1_ppu']) ? $_POST['meter_1_ppu'] : null,
            'sales_rep_code' => isset($_POST['sales_rep_code']) ? str_pad($_POST['sales_rep_code'], 7, '0', STR_PAD_LEFT) : null,
        );
        // อัพเดทข้อมูลสินค้า
        if ($this->productModel->updateProduct($id, $productData)) {
            echo json_encode(array('success' => true, 'message' => t('product_management.update_success')));
        } else {
            http_response_code(500);
            echo json_encode(array('success' => false, 'errors' => array(t('product_management.operation_failed'))));
        }
        exit;
    }
    
    /**
     * ค้นหาสินค้า (AJAX)
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
        
        $products = $this->productModel->searchProducts($keyword, $filters);
        
        echo json_encode(array(
            'success' => true,
            'data' => $products,
            'count' => count($products)
        ));
        exit;
    }
    
}
