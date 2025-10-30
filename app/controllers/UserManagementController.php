<?php

class UserManagementController extends Controller {
    private $userModel;
    
    public function __construct() {
        // ตรวจสอบการ login
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        // สร้าง User model
        if (!class_exists('Model')) {
            require_once __DIR__ . '/../core/Model.php';
        }
        if (!class_exists('User')) {
            require_once __DIR__ . '/../models/User.php';
        }
        $this->userModel = new User();
    }
    
    /**
     * แสดงหน้ารายการผู้ใช้
     */
    public function index() {
        $users = $this->userModel->getAllUsers();
        $stats = array(
            'total' => $this->userModel->countUsers(),
            'active' => $this->userModel->countUsers(array('status' => 'active')),
            'suspended' => $this->userModel->countUsers(array('status' => 'suspended')),
            'admin' => $this->userModel->countUsers(array('role' => 'admin')),
            'agent' => $this->userModel->countUsers(array('role' => 'agent'))
        );
        
        $data = array(
            'title' => t('user_management.title'),
            'users' => $users,
            'stats' => $stats
        );
        
        $this->view('pages/user-management/index', $data);
    }
    
    /**
     * แสดงฟอร์มเพิ่มผู้ใช้
     */
    public function create() {
        $data = array(
            'title' => t('user_management.add_user'),
            'action' => 'create'
        );
        
        $this->view('pages/user-management/form', $data);
    }
    
    /**
     * บันทึกผู้ใช้ใหม่
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
            exit;
        }
        
        $errors = array();
        
        // Validation
        if (empty($_POST['code'])) {
            $errors[] = t('user_management.code_required');
        } elseif ($this->userModel->isCodeExists($_POST['code'])) {
            $errors[] = t('user_management.code_exists');
        }
        
        if (empty($_POST['username'])) {
            $errors[] = t('user_management.username_required');
        } elseif ($this->userModel->isUsernameExists($_POST['username'])) {
            $errors[] = t('user_management.username_exists');
        }
        
        if (empty($_POST['password'])) {
            $errors[] = t('user_management.password_required');
        } elseif (strlen($_POST['password']) < 3) {
            $errors[] = t('user_management.password_min_length');
        }
        
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(array('success' => false, 'errors' => $errors));
            exit;
        }
        
        // จัดการอัพโหลดรูปภาพ
        $imagePath = null;
        if (!empty($_FILES['img']['name'])) {
            $imagePath = $this->handleImageUpload($_FILES['img']);
            if ($imagePath === false) {
                http_response_code(400);
                echo json_encode(array('success' => false, 'errors' => array(t('user_management.image_upload_failed'))));
                exit;
            }
        }
        
        // เตรียมข้อมูล
        $userData = array(
            'code' => $_POST['code'],
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'name' => isset($_POST['name']) ? $_POST['name'] : null,
            'tel' => isset($_POST['tel']) ? $_POST['tel'] : null,
            'birthday' => !empty($_POST['birthday']) ? $_POST['birthday'] : null,
            'facebook_name' => isset($_POST['facebook_name']) ? $_POST['facebook_name'] : null,
            'line_id' => isset($_POST['line_id']) ? $_POST['line_id'] : null,
            'img' => $imagePath,
            'status' => isset($_POST['status']) ? $_POST['status'] : 'active',
            'role' => isset($_POST['role']) ? $_POST['role'] : 'user',
            'created_by' => Auth::user()['username']
        );
        
        // บันทึกข้อมูล
        if ($this->userModel->createUser($userData)) {
            echo json_encode(array('success' => true, 'message' => t('user_management.add_success')));
        } else {
            http_response_code(500);
            echo json_encode(array('success' => false, 'errors' => array(t('user_management.operation_failed'))));
        }
        exit;
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
    
    /**
     * ลบผู้ใช้
     */
    public function delete($id) {
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
        
        // ป้องกันการลบตัวเอง (ใช้ username เปรียบเทียบ)
        $currentUser = Auth::user();
        if ($user['username'] === $currentUser['username']) {
            http_response_code(403);
            echo json_encode(array('success' => false, 'errors' => array(t('user_management.cannot_delete_self'))));
            exit;
        }
        
        // ลบข้อมูล
        if ($this->userModel->deleteUser($id)) {
            // ลบรูปภาพ
            if ($user['img'] && file_exists(__DIR__ . '/../../public' . $user['img'])) {
                unlink(__DIR__ . '/../../public' . $user['img']);
            }
            
            echo json_encode(array('success' => true, 'message' => t('user_management.delete_success')));
        } else {
            http_response_code(500);
            echo json_encode(array('success' => false, 'errors' => array(t('user_management.operation_failed'))));
        }
        
        exit;
    }
    
    /**
     * เปลี่ยนสถานะผู้ใช้
     */
    public function changeStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
            exit;
        }
        
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            echo json_encode(array('success' => false, 'message' => t('user_management.user_not_found')));
            exit;
        }
        
        // ป้องกันการเปลี่ยนสถานะตัวเอง (ใช้ username เปรียบเทียบ)
        $currentUser = Auth::user();
        if ($user['username'] === $currentUser['username']) {
            echo json_encode(array('success' => false, 'message' => t('user_management.cannot_change_self_status')));
            exit;
        }
        
        $newStatus = $user['status'] === 'active' ? 'suspended' : 'active';
        
        if ($this->userModel->changeStatus($id, $newStatus, Auth::user()['username'])) {
            echo json_encode(array(
                'success' => true, 
                'message' => t('user_management.status_change_success'),
                'status' => $newStatus
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => t('user_management.operation_failed')));
        }
        exit;
    }
    
    /**
     * จัดการการอัพโหลดรูปภาพ
     */
    private function handleImageUpload($file) {
        $uploadDir = __DIR__ . '/../../public/uploads/users/';
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // ตรวจสอบไฟล์
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        // ตรวจสอบขนาดไฟล์ (max 5MB)
        if ($file['size'] > 5242880) {
            return false;
        }
        
        // สร้างชื่อไฟล์ใหม่
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // อัพโหลดไฟล์
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/uploads/users/' . $filename;
        }
        
        return false;
    }
}
