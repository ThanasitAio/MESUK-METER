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
     * แสดงหน้ารายการสินค้า
     */
    public function index() {
        $products = $this->productModel->getAllProducts();
        $stats = array(
            'total' => $this->productModel->countProducts(),
            'active' => $this->productModel->countProducts(array('status' => 'active')),
            'suspended' => $this->productModel->countProducts(array('status' => 'suspended')),
            'admin' => $this->productModel->countProducts(array('role' => 'admin')),
            'agent' => $this->productModel->countProducts(array('role' => 'agent'))
        );
        
        $data = array(
            'title' => t('product_management.title'),
            'products' => $products,
            'stats' => $stats
        );
        
        $this->view('pages/product-management/index', $data);
    }
    
    /**
     * แสดงฟอร์มแก้ไขผู้ใช้
     */
    public function edit($id) {
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            http_response_code(404);
            die(t('user_management.user_not_found'));
        }
        
        $data = array(
            'title' => t('user_management.edit_user'),
            'action' => 'edit',
            'user' => $user
        );
        
        $this->view('pages/user-management/form', $data);
    }
    
    /**
     * อัพเดทข้อมูลผู้ใช้
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        }
        
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(array('success' => false, 'errors' => array(t('user_management.user_not_found'))));
            exit;
        }
        
        $errors = array();
        
        // Validation
        if (empty($_POST['code'])) {
            $errors[] = t('user_management.code_required');
        } elseif ($this->userModel->isCodeExists($_POST['code'], $id)) {
            $errors[] = t('user_management.code_exists');
        }
        
        if (empty($_POST['username'])) {
            $errors[] = t('user_management.username_required');
        } elseif ($this->userModel->isUsernameExists($_POST['username'], $id)) {
            $errors[] = t('user_management.username_exists');
        }
        
        if (!empty($_POST['password']) && strlen($_POST['password']) < 3) {
            $errors[] = t('user_management.password_min_length');
        }
        
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        
        // จัดการอัพโหลดรูปภาพ
        $imagePath = $user['img']; // ใช้รูปเดิม
        if (!empty($_FILES['img']['name'])) {
            $newImagePath = $this->handleImageUpload($_FILES['img']);
            if ($newImagePath !== false) {
                // ลบรูปเก่า
                if ($user['img'] && file_exists(__DIR__ . '/../../public' . $user['img'])) {
                    unlink(__DIR__ . '/../../public' . $user['img']);
                }
                $imagePath = $newImagePath;
            }
        }
        
        // เตรียมข้อมูล
        $userData = array(
            'code' => $_POST['code'],
            'username' => $_POST['username'],
            'name' => isset($_POST['name']) ? $_POST['name'] : null,
            'tel' => isset($_POST['tel']) ? $_POST['tel'] : null,
            'birthday' => !empty($_POST['birthday']) ? $_POST['birthday'] : null,
            'facebook_name' => isset($_POST['facebook_name']) ? $_POST['facebook_name'] : null,
            'line_id' => isset($_POST['line_id']) ? $_POST['line_id'] : null,
            'img' => $imagePath,
            'status' => isset($_POST['status']) ? $_POST['status'] : 'active',
            'role' => isset($_POST['role']) ? $_POST['role'] : 'user',
            'updated_by' => Auth::user()['username']
        );
        
        // อัพเดทรหัสผ่านถ้ามีการกรอก
        if (!empty($_POST['password'])) {
            $userData['password'] = $_POST['password'];
        }
        
        // อัพเดทข้อมูล
        if ($this->userModel->updateUser($id, $userData)) {
            echo json_encode(array('success' => true, 'message' => t('user_management.update_success')));
        } else {
            http_response_code(500);
            echo json_encode(array('success' => false, 'errors' => array(t('user_management.operation_failed'))));
        }
        exit;
    }
    
}
