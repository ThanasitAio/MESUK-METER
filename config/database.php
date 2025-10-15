<?php
// config/database.php
return array(
    'default' => 'mysql',
    
    'connections' => array(
        'mysql' => array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'database' => 'meesuk_db', // ตรวจสอบชื่อ database
            'username' => 'root',              // ตรวจสอบ username
            'password' => '',                  // ตรวจสอบ password (ว่างถ้าไม่มี)
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'options' => array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        )
    )
);