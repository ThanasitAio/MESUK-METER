<?php
// test_db_users.php - ทดสอบดึงข้อมูลผู้ใช้จากฐานข้อมูล

define('BASE_PATH', __DIR__);

// โหลด config และ classes
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/models/User.php';

echo "<h1>ทดสอบการดึงข้อมูลผู้ใช้</h1>";
echo "<hr>";

try {
    // ทดสอบการเชื่อมต่อฐานข้อมูล
    echo "<h2>1. ทดสอบการเชื่อมต่อฐานข้อมูล</h2>";
    $pdo = Database::getInstance();
    echo "✅ เชื่อมต่อฐานข้อมูลสำเร็จ<br><br>";
    
    // ทดสอบ query โดยตรง
    echo "<h2>2. ทดสอบ Query โดยตรง (PDO)</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM me_users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "จำนวนผู้ใช้ทั้งหมดในฐานข้อมูล: <strong>" . $result['total'] . "</strong><br><br>";
    
    // ทดสอบดึงข้อมูลผู้ใช้ทั้งหมด
    echo "<h2>3. ทดสอบดึงข้อมูลผู้ใช้ (SELECT *)</h2>";
    $stmt = $pdo->query("SELECT id, code, username, name, role, status FROM me_users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "จำนวนผู้ใช้ที่ดึงได้: <strong>" . count($users) . "</strong><br>";
    
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin-top: 10px;'>";
        echo "<tr style='background: #ddd;'>";
        echo "<th>ID</th><th>Code</th><th>Username</th><th>Name</th><th>Role</th><th>Status</th>";
        echo "</tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['code']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
            echo "<td>" . htmlspecialchars($user['status']) . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "<p style='color: red;'>❌ ไม่พบข้อมูลผู้ใช้ในฐานข้อมูล</p><br>";
    }
    
    // ทดสอบใช้ User Model
    echo "<h2>4. ทดสอบใช้ User Model</h2>";
    $userModel = new User();
    $modelUsers = $userModel->getAllUsers();
    echo "จำนวนผู้ใช้ที่ได้จาก Model: <strong>" . count($modelUsers) . "</strong><br>";
    
    if (count($modelUsers) > 0) {
        echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ccc;'>";
        print_r($modelUsers[0]);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Model ไม่สามารถดึงข้อมูลได้</p>";
    }
    
    // ทดสอบ countUsers
    echo "<h2>5. ทดสอบนับจำนวนผู้ใช้</h2>";
    $totalCount = $userModel->countUsers();
    $activeCount = $userModel->countUsers(array('status' => 'active'));
    $adminCount = $userModel->countUsers(array('role' => 'admin'));
    
    echo "Total: <strong>$totalCount</strong><br>";
    echo "Active: <strong>$activeCount</strong><br>";
    echo "Admin: <strong>$adminCount</strong><br>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ เกิดข้อผิดพลาด: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='/users'>← กลับไปหน้าจัดการผู้ใช้</a></p>";
?>
