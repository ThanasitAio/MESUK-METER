<?php
// Configuration สำหรับ Docker (Development)
date_default_timezone_set('Asia/Bangkok');

return [
    'app' => [
        'name' => 'MESUK',
        'version' => '1.0.0',
        'env' => 'development',  // development mode
        'debug' => true,         // เปิด debug mode
        'url' => 'http://localhost:8000',
        'base_path' => ''  // Base path ว่างสำหรับ Docker/Root
    ],
    
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'mesuk_db',
        'username' => 'root',
        'password' => '',
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
