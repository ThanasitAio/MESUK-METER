<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apache Configuration Check - MESUK</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .check-section {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-left: 5px solid #667eea;
            border-radius: 8px;
        }
        .check-item {
            margin: 15px 0;
            padding: 15px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }
        .value {
            color: #2196F3;
            font-family: 'Courier New', monospace;
            background: #e3f2fd;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-left: 10px;
        }
        .status-ok {
            background: #4CAF50;
            color: white;
        }
        .status-warning {
            background: #FF9800;
            color: white;
        }
        .status-error {
            background: #f44336;
            color: white;
        }
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #2196F3;
            border-radius: 4px;
        }
        .warning-box {
            background: #fff3cd;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
        }
        .error-box {
            background: #f8d7da;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #f44336;
            border-radius: 4px;
        }
        .success-box {
            background: #d4edda;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }
        ul {
            margin: 10px 0;
            padding-left: 25px;
        }
        li {
            margin: 5px 0;
        }
        .code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Apache Configuration Check</h1>
        
        <?php
        // Check if running on Apache
        $isApache = strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false;
        $isXAMPP = strpos($_SERVER['SERVER_SOFTWARE'], 'XAMPP') !== false;
        
        // Load configuration
        require_once __DIR__ . '/app/utils/helpers.php';
        define('BASE_PATH', __DIR__);
        $config = require __DIR__ . '/config/app.php';
        $basePath = isset($config['app']['base_path']) ? $config['app']['base_path'] : '';
        
        // Check mod_rewrite
        $modRewriteEnabled = function_exists('apache_get_modules') 
            ? in_array('mod_rewrite', apache_get_modules()) 
            : 'Unknown';
        
        // Get current URL info
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        
        // Check .htaccess
        $htaccessExists = file_exists(__DIR__ . '/.htaccess');
        $htaccessReadable = $htaccessExists && is_readable(__DIR__ . '/.htaccess');
        
        if ($htaccessReadable) {
            $htaccessContent = file_get_contents(__DIR__ . '/.htaccess');
            preg_match('/RewriteBase\s+(.+)/', $htaccessContent, $matches);
            $rewriteBase = isset($matches[1]) ? trim($matches[1]) : 'Not set';
        } else {
            $rewriteBase = 'Cannot read';
        }
        
        // Overall status
        $allGood = $isApache && $modRewriteEnabled === true && $htaccessExists;
        ?>
        
        <?php if ($allGood): ?>
            <div class="success-box">
                <strong>✅ ทุกอย่างพร้อมใช้งาน!</strong> Apache config ถูกต้องและพร้อมทำงาน
            </div>
        <?php else: ?>
            <div class="warning-box">
                <strong>⚠️ พบปัญหาบางอย่าง</strong> กรุณาตรวจสอบรายละเอียดด้านล่าง
            </div>
        <?php endif; ?>

        <div class="check-section">
            <h2>🌐 Web Server Information</h2>
            
            <div class="check-item">
                <span class="label">Server Software:</span>
                <span class="value"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
                <?php if ($isApache): ?>
                    <span class="status status-ok">✓ Apache</span>
                <?php else: ?>
                    <span class="status status-warning">⚠ Not Apache</span>
                <?php endif; ?>
            </div>
            
            <div class="check-item">
                <span class="label">PHP Version:</span>
                <span class="value"><?php echo phpversion(); ?></span>
                <?php if (version_compare(phpversion(), '5.6.0', '>=')): ?>
                    <span class="status status-ok">✓ OK</span>
                <?php else: ?>
                    <span class="status status-error">✗ Too Old</span>
                <?php endif; ?>
            </div>
            
            <div class="check-item">
                <span class="label">Current URL:</span>
                <span class="value"><?php echo $protocol . '://' . $host . $requestUri; ?></span>
            </div>
            
            <div class="check-item">
                <span class="label">Document Root:</span>
                <span class="value"><?php echo $documentRoot; ?></span>
            </div>
        </div>

        <div class="check-section">
            <h2>⚙️ Configuration Check</h2>
            
            <div class="check-item">
                <span class="label">Config Base Path:</span>
                <span class="value"><?php echo !empty($basePath) ? $basePath : '(empty - root)'; ?></span>
                <span class="status status-ok">✓</span>
            </div>
            
            <div class="check-item">
                <span class="label">.htaccess File:</span>
                <?php if ($htaccessExists): ?>
                    <span class="value">Found</span>
                    <span class="status status-ok">✓ Exists</span>
                <?php else: ?>
                    <span class="value">Not Found</span>
                    <span class="status status-error">✗ Missing</span>
                <?php endif; ?>
            </div>
            
            <div class="check-item">
                <span class="label">.htaccess RewriteBase:</span>
                <span class="value"><?php echo $rewriteBase; ?></span>
                <?php if ($rewriteBase === $basePath . '/'): ?>
                    <span class="status status-ok">✓ Match</span>
                <?php else: ?>
                    <span class="status status-warning">⚠ Mismatch</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="check-section">
            <h2>🔧 Apache Modules</h2>
            
            <div class="check-item">
                <span class="label">mod_rewrite:</span>
                <?php if ($modRewriteEnabled === true): ?>
                    <span class="value">Enabled</span>
                    <span class="status status-ok">✓ Active</span>
                <?php elseif ($modRewriteEnabled === false): ?>
                    <span class="value">Disabled</span>
                    <span class="status status-error">✗ Inactive</span>
                <?php else: ?>
                    <span class="value">Unknown</span>
                    <span class="status status-warning">? Cannot Check</span>
                <?php endif; ?>
            </div>
            
            <?php if (function_exists('apache_get_modules')): ?>
                <div class="check-item">
                    <span class="label">Loaded Modules:</span>
                    <div class="info-box" style="margin-top: 10px;">
                        <?php
                        $modules = apache_get_modules();
                        $important = ['mod_rewrite', 'mod_headers', 'mod_deflate', 'mod_expires'];
                        echo '<strong>Important modules:</strong><br>';
                        foreach ($important as $mod) {
                            $status = in_array($mod, $modules) ? '✓' : '✗';
                            echo "$status $mod<br>";
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$htaccessExists): ?>
            <div class="error-box">
                <strong>❌ ไม่พบไฟล์ .htaccess!</strong>
                <p>สร้างไฟล์ <code>.htaccess</code> ที่ root directory พร้อมเนื้อหา:</p>
                <div class="code">
&lt;IfModule mod_rewrite.c&gt;
    RewriteEngine On
    RewriteBase <?php echo $basePath; ?>/
    
    # อนุญาตให้เข้าถึง static files
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]
    
    # ส่ง request อื่นไปที่ index.php
    RewriteRule ^ index.php [L]
&lt;/IfModule&gt;
                </div>
            </div>
        <?php elseif ($rewriteBase !== $basePath . '/'): ?>
            <div class="warning-box">
                <strong>⚠️ RewriteBase ไม่ตรงกับ Config!</strong>
                <p>แก้ไขไฟล์ <code>.htaccess</code>:</p>
                <div class="code">
# ปัจจุบัน
RewriteBase <?php echo $rewriteBase; ?>

# ควรเป็น
RewriteBase <?php echo $basePath; ?>/
                </div>
            </div>
        <?php endif; ?>

        <?php if ($modRewriteEnabled === false): ?>
            <div class="error-box">
                <strong>❌ mod_rewrite ยังไม่เปิดใช้งาน!</strong>
                <p><strong>วิธีแก้:</strong></p>
                <ol>
                    <li>เปิดไฟล์ <code>C:\xampp\apache\conf\httpd.conf</code></li>
                    <li>ค้นหา <code>#LoadModule rewrite_module modules/mod_rewrite.so</code></li>
                    <li>ลบ <code>#</code> ออก (uncomment)</li>
                    <li>บันทึกไฟล์</li>
                    <li>Restart Apache</li>
                </ol>
            </div>
        <?php endif; ?>

        <div class="check-section">
            <h2>🧪 Test URL Functions</h2>
            
            <div class="check-item">
                <span class="label">url('/'):</span>
                <span class="value"><?php echo url('/'); ?></span>
            </div>
            
            <div class="check-item">
                <span class="label">url('/login'):</span>
                <span class="value"><?php echo url('/login'); ?></span>
            </div>
            
            <div class="check-item">
                <span class="label">url('/meters'):</span>
                <span class="value"><?php echo url('/meters'); ?></span>
            </div>
            
            <div class="check-item">
                <span class="label">basePath('/assets/css/app.css'):</span>
                <span class="value"><?php echo basePath('/assets/css/app.css'); ?></span>
            </div>
        </div>

        <div class="info-box" style="margin-top: 30px;">
            <strong>📚 คำแนะนำเพิ่มเติม:</strong>
            <ul>
                <li>อ่านคู่มือฉบับสมบูรณ์ใน <code>APACHE_SETUP_GUIDE.md</code></li>
                <li>ตรวจสอบว่า AllowOverride = All ใน httpd.conf</li>
                <li>หลังแก้ไข config ต้อง Restart Apache</li>
                <li>ถ้าทุกอย่างเรียบร้อย ลบไฟล์นี้ออกก่อน production</li>
            </ul>
        </div>
    </div>
</body>
</html>
