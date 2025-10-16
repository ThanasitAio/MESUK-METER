<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบระบบจัดการผู้ใช้ - MESUK</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 20px; 
            background: #f5f5f5; 
        }
        .container { max-width: 900px; margin: 0 auto; }
        .header { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 { color: #333; margin-bottom: 10px; }
        .header p { color: #666; }
        .check-section { 
            background: white; 
            padding: 25px; 
            border-radius: 8px; 
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .check-section h2 { 
            color: #333; 
            margin-bottom: 15px; 
            font-size: 18px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .check-item { 
            display: flex; 
            align-items: center; 
            padding: 12px; 
            margin: 8px 0;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .status { 
            width: 25px; 
            height: 25px; 
            border-radius: 50%; 
            margin-right: 15px;
            flex-shrink: 0;
        }
        .status.ok { background: #28a745; }
        .status.error { background: #dc3545; }
        .status.warning { background: #ffc107; }
        .check-item span { flex-grow: 1; }
        .code { 
            background: #e9ecef; 
            padding: 2px 6px; 
            border-radius: 3px; 
            font-family: monospace; 
            font-size: 13px;
        }
        .btn { 
            display: inline-block;
            padding: 12px 24px; 
            background: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            margin: 10px 5px 0 0;
            transition: background 0.3s;
        }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .info-box strong { color: #007bff; }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 ตรวจสอบระบบจัดการผู้ใช้</h1>
            <p>ตรวจสอบความพร้อมของระบบก่อนเริ่มใช้งาน</p>
        </div>

        <?php
        // เริ่มต้นการตรวจสอบ
        $errors = [];
        $warnings = [];
        $checks = [];

        // 1. ตรวจสอบไฟล์ Controller
        $controllerPath = __DIR__ . '/app/controllers/UserManagementController.php';
        if (file_exists($controllerPath)) {
            $checks['controller'] = ['status' => 'ok', 'message' => 'UserManagementController.php พร้อมใช้งาน'];
        } else {
            $checks['controller'] = ['status' => 'error', 'message' => 'ไม่พบไฟล์ UserManagementController.php'];
            $errors[] = 'Controller';
        }

        // 2. ตรวจสอบไฟล์ Model
        $modelPath = __DIR__ . '/app/models/User.php';
        if (file_exists($modelPath)) {
            $checks['model'] = ['status' => 'ok', 'message' => 'User.php Model พร้อมใช้งาน'];
        } else {
            $checks['model'] = ['status' => 'error', 'message' => 'ไม่พบไฟล์ User.php Model'];
            $errors[] = 'Model';
        }

        // 3. ตรวจสอบไฟล์ View - Index
        $viewIndexPath = __DIR__ . '/views/pages/user-management/index.php';
        if (file_exists($viewIndexPath)) {
            $checks['view_index'] = ['status' => 'ok', 'message' => 'View: index.php พร้อมใช้งาน'];
        } else {
            $checks['view_index'] = ['status' => 'error', 'message' => 'ไม่พบไฟล์ View: index.php'];
            $errors[] = 'View Index';
        }

        // 4. ตรวจสอบไฟล์ View - Form
        $viewFormPath = __DIR__ . '/views/pages/user-management/form.php';
        if (file_exists($viewFormPath)) {
            $checks['view_form'] = ['status' => 'ok', 'message' => 'View: form.php พร้อมใช้งาน'];
        } else {
            $checks['view_form'] = ['status' => 'error', 'message' => 'ไม่พบไฟล์ View: form.php'];
            $errors[] = 'View Form';
        }

        // 5. ตรวจสอบโฟลเดอร์อัพโหลด
        $uploadPath = __DIR__ . '/public/uploads/users';
        if (file_exists($uploadPath)) {
            if (is_writable($uploadPath)) {
                $checks['upload'] = ['status' => 'ok', 'message' => 'โฟลเดอร์ uploads/users พร้อมและเขียนได้'];
            } else {
                $checks['upload'] = ['status' => 'warning', 'message' => 'โฟลเดอร์ uploads/users ไม่สามารถเขียนได้ (ตั้งค่า permissions)'];
                $warnings[] = 'Upload Permission';
            }
        } else {
            $checks['upload'] = ['status' => 'warning', 'message' => 'ยังไม่มีโฟลเดอร์ uploads/users (จะสร้างอัตโนมัติ)'];
            $warnings[] = 'Upload Folder';
        }

        // 6. ตรวจสอบไฟล์ Routes
        $routesPath = __DIR__ . '/config/routes.php';
        if (file_exists($routesPath)) {
            $routesContent = file_get_contents($routesPath);
            if (strpos($routesContent, 'UserManagementController') !== false) {
                $checks['routes'] = ['status' => 'ok', 'message' => 'Routes ถูกเพิ่มแล้ว'];
            } else {
                $checks['routes'] = ['status' => 'error', 'message' => 'Routes ยังไม่ได้เพิ่ม UserManagementController'];
                $errors[] = 'Routes';
            }
        } else {
            $checks['routes'] = ['status' => 'error', 'message' => 'ไม่พบไฟล์ routes.php'];
            $errors[] = 'Routes File';
        }

        // 7. ตรวจสอบการเชื่อมต่อฐานข้อมูล
        try {
            require_once __DIR__ . '/config/database.php';
            $dbConfig = require __DIR__ . '/config/database.php';
            
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $checks['database'] = ['status' => 'ok', 'message' => 'เชื่อมต่อฐานข้อมูลสำเร็จ'];
            
            // ตรวจสอบตาราง me_users
            $stmt = $pdo->query("SHOW TABLES LIKE 'me_users'");
            if ($stmt->rowCount() > 0) {
                // ตรวจสอบ columns
                $stmt = $pdo->query("SHOW COLUMNS FROM me_users");
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $requiredColumns = ['id', 'code', 'username', 'password', 'name', 'tel', 'birthday', 
                                   'facebook_name', 'line_id', 'img', 'status', 'role', 
                                   'created_date', 'created_by', 'updated_date', 'updated_by'];
                
                $missingColumns = array_diff($requiredColumns, $columns);
                
                if (empty($missingColumns)) {
                    $checks['table'] = ['status' => 'ok', 'message' => 'ตาราง me_users พร้อมใช้งาน (ครบทุก columns)'];
                    
                    // นับจำนวนผู้ใช้
                    $stmt = $pdo->query("SELECT COUNT(*) FROM me_users");
                    $userCount = $stmt->fetchColumn();
                    $checks['data'] = ['status' => 'ok', 'message' => "มีข้อมูลผู้ใช้ {$userCount} รายการ"];
                } else {
                    $checks['table'] = ['status' => 'error', 'message' => 'ตาราง me_users ขาด columns: ' . implode(', ', $missingColumns)];
                    $errors[] = 'Table Structure';
                }
            } else {
                $checks['table'] = ['status' => 'error', 'message' => 'ยังไม่มีตาราง me_users (รัน create_me_users.sql)'];
                $errors[] = 'Table';
            }
            
        } catch (Exception $e) {
            $checks['database'] = ['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()];
            $errors[] = 'Database Connection';
        }
        ?>

        <!-- แสดงผลการตรวจสอบไฟล์ -->
        <div class="check-section">
            <h2>📁 ไฟล์และโฟลเดอร์</h2>
            <?php foreach (['controller', 'model', 'view_index', 'view_form', 'upload', 'routes'] as $key): ?>
                <?php if (isset($checks[$key])): ?>
                    <div class="check-item">
                        <div class="status <?php echo $checks[$key]['status']; ?>"></div>
                        <span><?php echo $checks[$key]['message']; ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- แสดงผลการตรวจสอบฐานข้อมูล -->
        <div class="check-section">
            <h2>🗄️ ฐานข้อมูล</h2>
            <?php foreach (['database', 'table', 'data'] as $key): ?>
                <?php if (isset($checks[$key])): ?>
                    <div class="check-item">
                        <div class="status <?php echo $checks[$key]['status']; ?>"></div>
                        <span><?php echo $checks[$key]['message']; ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- สรุปผล -->
        <div class="check-section">
            <h2>📊 สรุปผล</h2>
            <?php if (empty($errors)): ?>
                <div class="info-box">
                    <strong>✅ ระบบพร้อมใช้งาน 100%!</strong><br>
                    ไม่พบข้อผิดพลาดใดๆ คุณสามารถเริ่มใช้งานระบบจัดการผู้ใช้ได้ทันที
                    <?php if (!empty($warnings)): ?>
                        <br><br>⚠️ คำเตือน: <?php echo implode(', ', $warnings); ?>
                    <?php endif; ?>
                </div>
                <div style="margin-top: 20px;">
                    <a href="/users" class="btn btn-success">🚀 เข้าสู่ระบบจัดการผู้ใช้</a>
                    <a href="/" class="btn">🏠 กลับหน้าหลัก</a>
                    <a href="/login" class="btn btn-secondary">🔐 เข้าสู่ระบบ</a>
                </div>
            <?php else: ?>
                <div class="info-box" style="background: #ffe7e7; border-left-color: #dc3545;">
                    <strong style="color: #dc3545;">❌ พบข้อผิดพลาด!</strong><br>
                    กรุณาแก้ไขปัญหาต่อไปนี้: <?php echo implode(', ', $errors); ?>
                    <?php if (!empty($warnings)): ?>
                        <br>⚠️ คำเตือน: <?php echo implode(', ', $warnings); ?>
                    <?php endif; ?>
                </div>
                <div style="margin-top: 20px;">
                    <a href="javascript:location.reload()" class="btn">🔄 ตรวจสอบอีกครั้ง</a>
                    <a href="/USER_MANAGEMENT_GUIDE.md" class="btn btn-secondary">📖 อ่านคู่มือ</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- ข้อมูล Login -->
        <div class="check-section">
            <h2>🔑 ข้อมูลสำหรับ Login (Admin)</h2>
            <div class="info-box">
                <strong>Username:</strong> <span class="code">0000999</span><br>
                <strong>Password:</strong> <span class="code">999</span><br>
                <strong>Role:</strong> <span class="code">admin</span>
            </div>
        </div>

        <!-- คำแนะนำเพิ่มเติม -->
        <div class="check-section">
            <h2>📚 เอกสารและคำแนะนำ</h2>
            <div class="check-item">
                <div class="status ok"></div>
                <span>
                    <strong>คู่มือการใช้งาน:</strong> 
                    <a href="/USER_MANAGEMENT_GUIDE.md" target="_blank">USER_MANAGEMENT_GUIDE.md</a>
                </span>
            </div>
            <div class="check-item">
                <div class="status ok"></div>
                <span>
                    <strong>สรุประบบ:</strong> 
                    <a href="/USER_MANAGEMENT_SUMMARY.md" target="_blank">USER_MANAGEMENT_SUMMARY.md</a>
                </span>
            </div>
            <div class="check-item">
                <div class="status ok"></div>
                <span>
                    <strong>SQL สำหรับทดสอบ:</strong> 
                    <a href="/test_user_management.sql" target="_blank">test_user_management.sql</a>
                </span>
            </div>
        </div>

        <div class="footer">
            <p>MESUK User Management System v1.0 | สร้างโดย GitHub Copilot</p>
        </div>
    </div>
</body>
</html>
