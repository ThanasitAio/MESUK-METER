<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>

    <!-- logo -->
    <?php 
    require_once __DIR__ . '/../../app/utils/helpers.php';
    $logoPath = basePath('/assets/images/meters_logo.png');
    ?>
    <!-- Favicon สำหรับทุกขนาด -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="64x64" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="128x128" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="256x256" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="384x384" href="<?php echo $logoPath; ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo $logoPath; ?>">

    <!-- สำหรับ Apple Devices -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $logoPath; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $logoPath; ?>">
    
    <!-- 1. เพิ่มลิงก์สำหรับ Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* ตั้งค่าฟอนต์และพื้นฐาน */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            background-color: #f0f2f5; /* สีพื้นหลังอ่อนๆ */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 15px; /* เพิ่ม padding เพื่อไม่ให้ชิดขอบจอบนมือถือ */
            box-sizing: border-box;
        }

        /* กล่องฟอร์มล็อกอิน */
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            text-align: center;
        }

        /* 2. สไตล์สำหรับส่วนหัว (โลโก้ + ชื่อ) */
        .login-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px; /* ระยะห่างระหว่างโลโก้กับตัวอักษร */
            margin-bottom: 10px;
        }

        .logo-icon-wrapper {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            /* background-color: #D3EE98; ใช้สีธีมเป็นพื้นหลังโลโก้ */
            border-radius: 10px;
        }

        .logo-icon-wrapper .bi {
            font-size: 26px; /* ขนาดไอคอน */
            color: #333;
        }

        .login-container h1 {
            color: #333;
            margin: 0; /* นำ margin เดิมออก */
            font-size: 28px;
        }

        .login-container p {
            color: #666;
            margin-bottom: 30px;
        }

        /* กลุ่มของ input */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #D3EE98;
            box-shadow: 0 0 0 3px rgba(211, 238, 152, 0.5);
        }

        /* ปุ่ม Submit */
        .login-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background-color: #D3EE98;
            color: #212529;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #c2db86;
        }
        
        /* ส่วนแสดงข้อความผิดพลาด */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* 3. โค้ดสำหรับปรับปรุงการแสดงผลบนมือถือ */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px; /* ลด padding ด้านข้างลงเมื่อจอมือถือ */
            }
        }

    </style>
</head>
<body>

    <div class="login-container">
        <!-- 4. เพิ่มโครงสร้าง HTML สำหรับโลโก้ -->
        <div class="login-header">
            <div class="logo-icon-wrapper">
                 <img src="<?php echo $logoPath; ?>" alt="Description of the image" style="width: 100%; height: 100%; object-fit: cover;">
                <!-- <i class="bi bi-grid-3x3-gap-fill"></i> -->
            </div>
            <h1>METER</h1>
        </div>

        <p>กรุณาเข้าสู่ระบบเพื่อจัดการระบบ</p>

        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // แสดง error message
        if (isset($_SESSION['error'])):
        ?>
            <div class="error-message">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
        <?php
            unset($_SESSION['error']);
        endif;
        
        // แสดง success message
        if (isset($_SESSION['success'])):
        ?>
            <div class="success-message">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
        <?php
            unset($_SESSION['success']);
        endif;
        ?>

        <form action="<?php echo url('/login'); ?>" method="POST">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" autocomplete="off" placeholder="กรอกชื่อผู้ใช้" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" required>
            </div>
            <button type="submit" class="login-button">เข้าสู่ระบบ</button>
        </form>
    </div>

</body>
</html>