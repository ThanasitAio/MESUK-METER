<?php

class ProductManagementController extends Controller {
    private $productModel;
    
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
     * แสดง
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
    
    
}
