<?php
/**
 * สคริปต์ทดสอบการบันทึกข้อมูลมิเตอร์
 * ใช้สำหรับทดสอบว่าข้อมูลถูกบันทึกลงฐานข้อมูลได้หรือไม่
 */

// Load required files
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/config/database.php';

try {
    // เชื่อมต่อฐานข้อมูล
    $db = Database::getInstance();
    echo "✓ เชื่อมต่อฐานข้อมูลสำเร็จ\n\n";
    
    // ข้อมูลทดสอบ
    $testPcode = 'TEST001';
    $testMonth = (int)date('m');
    $testYear = (int)date('Y');
    $testUserId = 1; // ID ของ admin
    
    echo "=== ทดสอบบันทึกข้อมูลมิเตอร์ ===\n";
    echo "pcode: $testPcode\n";
    echo "month: $testMonth\n";
    echo "year: $testYear\n\n";
    
    // 1. ทดสอบบันทึกค่าไฟ
    echo "1. ทดสอบบันทึกค่าไฟ...\n";
    $sql = "INSERT INTO me_meter 
            (meter_type, pcode, month, year, reading_value, reading_date, created_at, created_by, updated_at, updated_by) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?, NOW(), ?)";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute(['ค่าไฟ', $testPcode, $testMonth, $testYear, 100.50, $testUserId, $testUserId]);
    
    if ($result) {
        echo "   ✓ บันทึกค่าไฟสำเร็จ (ID: " . $db->lastInsertId() . ")\n";
    } else {
        echo "   ✗ บันทึกค่าไฟล้มเหลว\n";
        print_r($stmt->errorInfo());
    }
    
    // 2. ทดสอบบันทึกค่าน้ำ
    echo "\n2. ทดสอบบันทึกค่าน้ำ...\n";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute(['ค่าน้ำ', $testPcode, $testMonth, $testYear, 50.25, $testUserId, $testUserId]);
    
    if ($result) {
        echo "   ✓ บันทึกค่าน้ำสำเร็จ (ID: " . $db->lastInsertId() . ")\n";
    } else {
        echo "   ✗ บันทึกค่าน้ำล้มเหลว\n";
        print_r($stmt->errorInfo());
    }
    
    // 3. ทดสอบบันทึกค่าขยะ
    echo "\n3. ทดสอบบันทึกค่าขยะ...\n";
    $sql2 = "INSERT INTO me_meter_ohter 
            (meter_ohter_type, pcode, month, year, price, created_at, created_by, updated_at, updated_by) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW(), ?)";
    $stmt2 = $db->prepare($sql2);
    $result = $stmt2->execute(['ค่าขยะ', $testPcode, $testMonth, $testYear, 30.00, $testUserId, $testUserId]);
    
    if ($result) {
        echo "   ✓ บันทึกค่าขยะสำเร็จ (ID: " . $db->lastInsertId() . ")\n";
    } else {
        echo "   ✗ บันทึกค่าขยะล้มเหลว\n";
        print_r($stmt2->errorInfo());
    }
    
    // 4. ทดสอบบันทึกค่าส่วนกลาง
    echo "\n4. ทดสอบบันทึกค่าส่วนกลาง...\n";
    $stmt2 = $db->prepare($sql2);
    $result = $stmt2->execute(['ค่าส่วนกลาง', $testPcode, $testMonth, $testYear, 200.00, $testUserId, $testUserId]);
    
    if ($result) {
        echo "   ✓ บันทึกค่าส่วนกลางสำเร็จ (ID: " . $db->lastInsertId() . ")\n";
    } else {
        echo "   ✗ บันทึกค่าส่วนกลางล้มเหลว\n";
        print_r($stmt2->errorInfo());
    }
    
    // 5. ตรวจสอบข้อมูลที่บันทึก
    echo "\n=== ตรวจสอบข้อมูลที่บันทึก ===\n\n";
    
    echo "ข้อมูลจากตาราง me_meter:\n";
    $sql3 = "SELECT * FROM me_meter WHERE pcode = ? AND month = ? AND year = ?";
    $stmt3 = $db->prepare($sql3);
    $stmt3->execute([$testPcode, $testMonth, $testYear]);
    $meters = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    print_r($meters);
    
    echo "\nข้อมูลจากตาราง me_meter_ohter:\n";
    $sql4 = "SELECT * FROM me_meter_ohter WHERE pcode = ? AND month = ? AND year = ?";
    $stmt4 = $db->prepare($sql4);
    $stmt4->execute([$testPcode, $testMonth, $testYear]);
    $others = $stmt4->fetchAll(PDO::FETCH_ASSOC);
    print_r($others);
    
    // 6. ลบข้อมูลทดสอบ
    echo "\n=== ลบข้อมูลทดสอบ ===\n";
    $sql5 = "DELETE FROM me_meter WHERE pcode = ?";
    $stmt5 = $db->prepare($sql5);
    $stmt5->execute([$testPcode]);
    echo "ลบข้อมูลจาก me_meter: " . $stmt5->rowCount() . " แถว\n";
    
    $sql6 = "DELETE FROM me_meter_ohter WHERE pcode = ?";
    $stmt6 = $db->prepare($sql6);
    $stmt6->execute([$testPcode]);
    echo "ลบข้อมูลจาก me_meter_ohter: " . $stmt6->rowCount() . " แถว\n";
    
    echo "\n✓ ทดสอบเสร็จสมบูรณ์\n";
    
} catch (PDOException $e) {
    echo "✗ เกิดข้อผิดพลาด: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
} catch (Exception $e) {
    echo "✗ เกิดข้อผิดพลาด: " . $e->getMessage() . "\n";
}
