<?php

require_once __DIR__ . '/../core/Database.php';

class AuthController {

    public function showLoginForm()
    {
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
            // vvvvvv  แก้ไขตรงนี้  vvvvvv
            header('Location: login?error=1');
            // ^^^^^^  แก้ไขตรงนี้  ^^^^^^
            exit();
        }

        try {
            $db = Database::getInstance();

            $sql = "SELECT * FROM ali_member WHERE mcode = :username LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            // TODO: เปลี่ยนเงื่อนไขการตรวจสอบรหัสผ่านให้ปลอดภัย
            $user = "mesuk";
            $password = "123456";
            if ($user && $password === '123456') { 

                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['mcode'];
                $_SESSION['name'] = $user['name_t'];

                // --- ถ้าล็อกอินสำเร็จ ---
                // vvvvvv  แก้ไขตรงนี้  vvvvvv
                header('Location: home');
                // ^^^^^^  แก้ไขตรงนี้  ^^^^^^
                exit();

            } else {
                
                // --- ถ้าล็อกอินไม่สำเร็จ ---
                // vvvvvv  แก้ไขตรงนี้  vvvvvv
                header('Location: login?error=1');
                // ^^^^^^  แก้ไขตรงนี้  ^^^^^^
                exit();
            }

        } catch (PDOException $e) {
            die("Query failed: ". $e->getMessage());
        }
    }
}