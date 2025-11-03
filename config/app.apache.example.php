<?php
// Configuration สำหรับ Apache (Production/Subfolder)
date_default_timezone_set('Asia/Bangkok');

return [
    'app' => [
        'name' => 'MESUK',
        'version' => '1.0.0',
        'env' => 'production',  // production mode
        'debug' => false,       // ปิด debug mode
        'url' => 'http://your-domain.com',
        'base_path' => '/mesuk'  // Base path สำหรับ Apache subfolder
    ],
    
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'mesuk_db',
        'username' => 'your_db_user',
        'password' => 'your_db_password',
        'charset' => 'utf8mb4'
    ],
    
    'session' => [
        'name' => 'mesuk_session',
        'lifetime' => 7200,
        'secure' => false
    ],
    
    'upload' => [
        'path' => __DIR__ . '/../public/uploads/',
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']
    ]
];
