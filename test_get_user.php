<?php
// test_get_user.php - ทดสอบการดึงข้อมูลผู้ใช้ตาม ID

define('BASE_PATH', __DIR__);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/models/User.php';

echo "<h1>ทดสอบการดึงข้อมูลผู้ใช้ตาม ID</h1>";
echo "<hr>";

try {
    $pdo = Database::getInstance();
    $userModel = new User();
    
    // 1. แสดงรายการผู้ใช้ทั้งหมดพร้อม ID
    echo "<h2>1. รายการผู้ใช้ทั้งหมด (แสดง ID)</h2>";
    $stmt = $pdo->query("SELECT id, code, username, name, role, status FROM me_users LIMIT 10");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr style='background: #ddd;'>";
        echo "<th>ID (UUID)</th><th>Code</th><th>Username</th><th>Name</th><th>Test Link</th>";
        echo "</tr>";
        
        foreach ($users as $user) {
            $testUrl = "?test_id=" . urlencode($user['id']);
            echo "<tr>";
            echo "<td><code style='font-size: 10px;'>" . htmlspecialchars($user['id']) . "</code></td>";
            echo "<td>" . htmlspecialchars($user['code']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td><a href='" . $testUrl . "'>ทดสอบ</a></td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
    
    // 2. ทดสอบดึงข้อมูลตาม ID
    if (isset($_GET['test_id'])) {
        $testId = $_GET['test_id'];
        
        echo "<h2>2. ทดสอบดึงข้อมูลด้วย ID: <code>" . htmlspecialchars($testId) . "</code></h2>";
        
        echo "<h3>ใช้ Model getUserById():</h3>";
        $user = $userModel->getUserById($testId);
        
        if ($user) {
            echo "<div style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb;'>";
            echo "✅ <strong>พบข้อมูลผู้ใช้</strong><br>";
            echo "Username: " . htmlspecialchars($user['username']) . "<br>";
            echo "Name: " . htmlspecialchars($user['name']) . "<br>";
            echo "Role: " . htmlspecialchars($user['role']) . "<br>";
            echo "Status: " . htmlspecialchars($user['status']) . "<br>";
            echo "</div>";
            
            echo "<h4>ข้อมูลทั้งหมด:</h4>";
            echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ccc;'>";
            print_r($user);
            echo "</pre>";
        } else {
            echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;'>";
            echo "❌ <strong>ไม่พบข้อมูลผู้ใช้</strong><br>";
            echo "ID ที่ค้นหา: " . htmlspecialchars($testId) . "<br>";
            echo "</div>";
            
            // ลองค้นหาด้วย query โดยตรง
            echo "<h4>ลอง Query โดยตรง:</h4>";
            $stmt = $pdo->prepare("SELECT * FROM me_users WHERE id = ?");
            $stmt->execute(array($testId));
            $directResult = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($directResult) {
                echo "<div style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb;'>";
                echo "✅ Query โดยตรงเจอข้อมูล<br>";
                echo "<pre>" . print_r($directResult, true) . "</pre>";
                echo "</div>";
            } else {
                echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;'>";
                echo "❌ Query โดยตรงก็ไม่เจอ<br>";
                echo "SQL: SELECT * FROM me_users WHERE id = '" . htmlspecialchars($testId) . "'";
                echo "</div>";
            }
        }
    } else {
        echo "<p><em>คลิก 'ทดสอบ' ในตารางด้านบนเพื่อทดสอบการดึงข้อมูลด้วย ID</em></p>";
    }
    
    // 3. ทดสอบ URL สำหรับ Edit
    echo "<h2>3. URL ตัวอย่างสำหรับการจัดการ</h2>";
    if (count($users) > 0) {
        $firstUser = $users[0];
        echo "<ul>";
        echo "<li>Edit: <a href='/users/edit/" . htmlspecialchars($firstUser['id']) . "' target='_blank'>/users/edit/" . htmlspecialchars($firstUser['id']) . "</a></li>";
        echo "<li>Delete: <code>/users/delete/" . htmlspecialchars($firstUser['id']) . "</code> (POST)</li>";
        echo "<li>Change Status: <code>/users/change-status/" . htmlspecialchars($firstUser['id']) . "</code> (POST)</li>";
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;'>";
    echo "❌ <strong>เกิดข้อผิดพลาด:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='/users'>← กลับไปหน้าจัดการผู้ใช้</a></p>";
?>
