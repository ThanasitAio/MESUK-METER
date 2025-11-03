<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Path Configuration Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
        }
        .test-section {
            background: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }
        .test-item {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }
        .label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 200px;
        }
        .value {
            color: #2196F3;
            font-family: monospace;
            background: #e3f2fd;
            padding: 4px 8px;
            border-radius: 3px;
        }
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #2196F3;
            border-radius: 4px;
        }
        a {
            color: #2196F3;
            text-decoration: none;
            padding: 8px 16px;
            background: #e3f2fd;
            border-radius: 4px;
            display: inline-block;
            margin: 5px;
        }
        a:hover {
            background: #2196F3;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Base Path Configuration Test</h1>
        
        <?php
        // Load configuration
        require_once __DIR__ . '/app/utils/helpers.php';
        define('BASE_PATH', __DIR__);
        
        $config = require __DIR__ . '/config/app.php';
        $basePath = isset($config['app']['base_path']) ? $config['app']['base_path'] : '';
        ?>
        
        <div class="info">
            <strong>📌 คำแนะนำ:</strong> หน้านี้จะแสดงการตั้งค่า Base Path และทดสอบว่า URL helpers ทำงานถูกต้องหรือไม่
        </div>

        <div class="test-section">
            <h2>📋 Current Configuration</h2>
            <div class="test-item">
                <span class="label">Base Path:</span>
                <span class="value"><?php echo !empty($basePath) ? $basePath : '(root - empty string)'; ?></span>
            </div>
            <div class="test-item">
                <span class="label">App Name:</span>
                <span class="value"><?php echo $config['app']['name']; ?></span>
            </div>
            <div class="test-item">
                <span class="label">Environment:</span>
                <span class="value"><?php echo $config['app']['env']; ?></span>
            </div>
            <div class="test-item">
                <span class="label">Debug Mode:</span>
                <span class="value"><?php echo $config['app']['debug'] ? 'Enabled' : 'Disabled'; ?></span>
            </div>
        </div>

        <div class="test-section">
            <h2>🔗 URL Function Tests</h2>
            <div class="test-item">
                <span class="label">url('/'):</span>
                <span class="value"><?php echo url('/'); ?></span>
            </div>
            <div class="test-item">
                <span class="label">url('/login'):</span>
                <span class="value"><?php echo url('/login'); ?></span>
            </div>
            <div class="test-item">
                <span class="label">url('/meters'):</span>
                <span class="value"><?php echo url('/meters'); ?></span>
            </div>
            <div class="test-item">
                <span class="label">url('/users/edit/1'):</span>
                <span class="value"><?php echo url('/users/edit/1'); ?></span>
            </div>
            <div class="test-item">
                <span class="label">url('/invoices'):</span>
                <span class="value"><?php echo url('/invoices'); ?></span>
            </div>
        </div>

        <div class="test-section">
            <h2>📦 Asset Path Tests</h2>
            <div class="test-item">
                <span class="label">basePath('/assets/css/app.css'):</span>
                <span class="value"><?php echo basePath('/assets/css/app.css'); ?></span>
            </div>
            <div class="test-item">
                <span class="label">basePath('/assets/js/app.js'):</span>
                <span class="value"><?php echo basePath('/assets/js/app.js'); ?></span>
            </div>
            <div class="test-item">
                <span class="label">basePath('/assets/images/logo.png'):</span>
                <span class="value"><?php echo basePath('/assets/images/logo.png'); ?></span>
            </div>
        </div>

        <div class="test-section">
            <h2>🌐 Server Information</h2>
            <div class="test-item">
                <span class="label">Document Root:</span>
                <span class="value"><?php echo $_SERVER['DOCUMENT_ROOT']; ?></span>
            </div>
            <div class="test-item">
                <span class="label">Request URI:</span>
                <span class="value"><?php echo $_SERVER['REQUEST_URI']; ?></span>
            </div>
            <div class="test-item">
                <span class="label">PHP Version:</span>
                <span class="value"><?php echo phpversion(); ?></span>
            </div>
            <div class="test-item">
                <span class="label">Server Software:</span>
                <span class="value"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
            </div>
        </div>

        <div class="test-section">
            <h2>🔗 Test Links</h2>
            <p>คลิกลิงก์ด้านล่างเพื่อทดสอบการ routing:</p>
            <div style="margin-top: 15px;">
                <a href="<?php echo url('/'); ?>" target="_blank">🏠 Home</a>
                <a href="<?php echo url('/login'); ?>" target="_blank">🔐 Login</a>
                <a href="<?php echo url('/meters'); ?>" target="_blank">⚡ Meters</a>
                <a href="<?php echo url('/invoices'); ?>" target="_blank">📄 Invoices</a>
                <a href="<?php echo url('/users'); ?>" target="_blank">👥 Users</a>
            </div>
        </div>

        <div class="info" style="margin-top: 30px;">
            <strong>✅ Status:</strong> 
            <span class="success">Configuration loaded successfully!</span>
            <br><br>
            <strong>📝 หมายเหตุ:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>ถ้า Base Path = "" (empty) แปลว่าติดตั้งที่ root domain</li>
                <li>ถ้า Base Path = "/mesuk" แปลว่าติดตั้งใน subdirectory /mesuk</li>
                <li>URL ทั้งหมดควรมี base path นำหน้าอัตโนมัติ</li>
                <li>ทดสอบคลิกลิงก์ด้านบนเพื่อดูว่า routing ทำงานถูกต้อง</li>
            </ul>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
            <strong>⚠️ สำหรับ Production:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>ลบไฟล์ test_base_path.php หลังจากทดสอบเสร็จ</li>
                <li>เปลี่ยน debug เป็น false ใน config/app.php</li>
                <li>เปลี่ยน env เป็น 'production'</li>
                <li>ตรวจสอบ database credentials</li>
            </ul>
        </div>
    </div>
</body>
</html>
