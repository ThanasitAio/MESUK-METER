<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/LoginHistory.php';

class AuthController {

    public function showLoginForm()
    {
        // ถ้า login แล้ว redirect ไปหน้าหลัก
        Auth::guest();
        
        require_once __DIR__ . '/../../views/pages/login.php';
    }

    public function login()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'กรุณากรอก Username และ Password';
            header('Location: /login');
            exit();
        }

        try {
            $db = Database::getInstance();

            // ลบ leading zeros จาก username (999 จะกลายเป็น 0000999)
            $usernameSearch = str_pad($username, 7, '0', STR_PAD_LEFT);
            
            // Query ตาราง me_users โดยค้นหาทั้ง username ที่กรอกมาและแบบเติม 0
            $sql = "SELECT * FROM me_users WHERE username = :username OR username = :username_padded LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(
                ':username' => $username,
                ':username_padded' => $usernameSearch
            ));
            $user = $stmt->fetch();

            // ตรวจสอบ username และ password
            if ($user) {
                $isPasswordValid = false;
                
                // ลองตรวจสอบ password หลายแบบ
                if (!empty($user['password'])) {
                    // ตรวจสอบด้วย MD5 (รหัสผ่านใน me_users ใช้ MD5)
                    if (md5($password) === $user['password']) {
                        $isPasswordValid = true;
                    }
                    // หรือลอง bcrypt (ถ้าเปลี่ยนมาใช้ใหม่)
                    elseif (password_verify($password, $user['password'])) {
                        $isPasswordValid = true;
                    }
                }
                
                if ($isPasswordValid) {
                    // เก็บข้อมูลลง session ผ่าน Auth class
                    Auth::login(array(
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'code' => $user['code'],
                        'full_name' => isset($user['name']) ? $user['name'] : $user['username'],
                        'email' => isset($user['email']) && !empty($user['email']) ? $user['email'] : null,
                        'role' => isset($user['role']) ? $user['role'] : 'user'
                    ));
                    
                    // บันทึกประวัติการ Login สำเร็จ
                    LoginHistory::record($user['id'], $user['username'], 'success');
                    
                    // ถ้ามี intended URL ให้ redirect กลับไป
                    $redirectTo = isset($_SESSION['intended_url']) ? $_SESSION['intended_url'] : '/';
                    unset($_SESSION['intended_url']);
                    
                    $_SESSION['success'] = 'เข้าสู่ระบบสำเร็จ';
                    header('Location: ' . $redirectTo);
                    exit();
                } else {
                    // บันทึกประวัติการ Login ไม่สำเร็จ
                    if (isset($user['id'])) {
                        LoginHistory::record($user['id'], $user['username'], 'failed');
                    }
                    $_SESSION['error'] = 'Username หรือ Password ไม่ถูกต้อง';
                    header('Location: /login');
                    exit();
                }
            } else {
                $_SESSION['error'] = 'ไม่พบ Username นี้ในระบบ';
                header('Location: /login');
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            header('Location: /login');
            exit();
        }
    }

    public function logout()
    {
        Auth::logout();
        $_SESSION['success'] = 'ออกจากระบบสำเร็จ';
        header('Location: /login');
        exit();
    }
}