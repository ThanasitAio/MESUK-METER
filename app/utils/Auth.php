<?php
// app/utils/Auth.php
class Auth {
    
    /**
     * ตรวจสอบว่ามี session login หรือไม่
     */
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * ดึงข้อมูล user ที่ login อยู่
     */
    public static function user() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (self::check()) {
            return array(
                'id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
                'username' => isset($_SESSION['username']) ? $_SESSION['username'] : null,
                'email' => isset($_SESSION['email']) ? $_SESSION['email'] : null,
                'role' => isset($_SESSION['role']) ? $_SESSION['role'] : 'user',
                'full_name' => isset($_SESSION['full_name']) ? $_SESSION['full_name'] : null,
                'tel' => isset($_SESSION['tel']) ? $_SESSION['tel'] : null,
                'birthday' => isset($_SESSION['birthday']) ? $_SESSION['birthday'] : null,
                'facebook_name' => isset($_SESSION['facebook_name']) ? $_SESSION['facebook_name'] : null,
                'line_id' => isset($_SESSION['line_id']) ? $_SESSION['line_id'] : null,
                'img' => isset($_SESSION['img']) ? $_SESSION['img'] : null,
                'code' => isset($_SESSION['code']) ? $_SESSION['code'] : null,
            );
        }
        
        return null;
    }
    
    /**
     * ดึง user ID
     */
    public static function id() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Login - บันทึก session
     */
    public static function login($userData) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID เพื่อป้องกัน session fixation
        session_regenerate_id(true);
        
        // บันทึกข้อมูลใน session
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = isset($userData['email']) ? $userData['email'] : null;
        $_SESSION['role'] = isset($userData['role']) ? $userData['role'] : 'user';
        $_SESSION['full_name'] = isset($userData['full_name']) ? $userData['full_name'] : $userData['username'];
        $_SESSION['tel'] = isset($userData['tel']) ? $userData['tel'] : null;
        $_SESSION['birthday'] = isset($userData['birthday']) ? $userData['birthday'] : null;
        $_SESSION['facebook_name'] = isset($userData['facebook_name']) ? $userData['facebook_name'] : null;
        $_SESSION['line_id'] = isset($userData['line_id']) ? $userData['line_id'] : null;
        $_SESSION['img'] = isset($userData['img']) ? $userData['img'] : null;
        $_SESSION['code'] = isset($userData['code']) ? $userData['code'] : null;
        $_SESSION['logged_in_at'] = time();
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Logout - ลบ session
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // ลบข้อมูล session
        $_SESSION = [];
        
        // ลบ session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // ทำลาย session
        session_destroy();
        
        return true;
    }
    
    /**
     * บังคับให้ต้อง login
     * ถ้าไม่มี session ให้ redirect ไปหน้า login
     */
    public static function requireLogin() {
        if (!self::check()) {
            // บันทึก URL ที่ต้องการเข้าถึง เพื่อ redirect กลับหลัง login
            $_SESSION['intended_url'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
            
            // Redirect ไปหน้า login
            header('Location: /login');
            exit();
        }
        
        // ตรวจสอบ session timeout (30 นาที)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            self::logout();
            $_SESSION['error'] = 'Session หมดอายุ กรุณา login อีกครั้ง';
            header('Location: /login');
            exit();
        }
        
        // อัพเดท last activity
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * ตรวจสอบว่า login แล้ว ถ้าใช่ให้ redirect ไปหน้าหลัก
     * ใช้ในหน้า login เพื่อไม่ให้เข้าหน้า login ซ้ำ
     */
    public static function guest() {
        if (self::check()) {
            header('Location: /');
            exit();
        }
    }
    
    /**
     * ตรวจสอบ role/permission
     */
    public static function hasRole($role) {
        if (!self::check()) {
            return false;
        }
        
        $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
        
        if (is_array($role)) {
            return in_array($userRole, $role);
        }
        
        return $userRole === $role;
    }
    
    /**
     * บังคับให้ต้องมี role ที่กำหนด
     */
    public static function requireRole($role) {
        self::requireLogin();
        
        if (!self::hasRole($role)) {
            http_response_code(403);
            die('Access Denied: You do not have permission to access this page.');
        }
    }
}
