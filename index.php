<?php
// Handle static files when running with PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // ตรวจสอบและให้บริการ static files จาก public folder
    $publicFiles = array('/uploads/', '/assets/', '/css/', '/js/');
    foreach ($publicFiles as $publicPath) {
        if (strpos($uri, $publicPath) === 0) {
            $file = __DIR__ . '/public' . $uri;
            if (is_file($file)) {
                return false; // Let PHP serve the static file
            }
        }
    }
    
    // ตรวจสอบ static files จาก root folders
    $rootFiles = array('/uploads/', '/assets/', '/css/', '/js/');
    foreach ($rootFiles as $rootPath) {
        if (strpos($uri, $rootPath) === 0) {
            $file = __DIR__ . $uri;
            if (is_file($file)) {
                return false; // Let PHP serve the static file
            }
        }
    }
}

// สำหรับ Apache - จัดการ static files
if (php_sapi_name() !== 'cli-server') {
    $uri = $_SERVER['REQUEST_URI'];
    
    // ตรวจสอบ path ที่ขึ้นต้นด้วย /uploads/, /assets/, /css/, /js/
    if (preg_match('#^/(uploads|assets|css|js)/#', $uri)) {
        
        // ลบ query string ออกถ้ามี
        $uri = parse_url($uri, PHP_URL_PATH);
        
        $filePath = __DIR__ . '/public' . $uri;
        
        // ถ้าไฟล์มีอยู่ใน public folder
        if (file_exists($filePath) && is_file($filePath)) {
            $mimeTypes = array(
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'pdf' => 'application/pdf',
                'ico' => 'image/x-icon'
            );
            
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $mimeType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
            
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        }
        
        // ถ้าไฟล์มีอยู่ใน root folder (backward compatibility)
        $rootFilePath = __DIR__ . $uri;
        if (file_exists($rootFilePath) && is_file($rootFilePath)) {
            $mimeTypes = array(
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'pdf' => 'application/pdf',
                'ico' => 'image/x-icon'
            );
            
            $extension = strtolower(pathinfo($rootFilePath, PATHINFO_EXTENSION));
            $mimeType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
            
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($rootFilePath));
            readfile($rootFilePath);
            exit;
        }
    }
}

session_start();

// Set timezone for PHP
date_default_timezone_set('Asia/Bangkok');

// Define base paths
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', BASE_PATH . '/views');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Load Language FIRST
require_once APP_PATH . '/utils/Language.php';
Language::init();

// Debug before loading helpers
error_log("=== BEFORE HELPERS ===");
error_log("Language class: " . (class_exists('Language') ? 'YES' : 'NO'));

// Load helpers
$helpersPath = APP_PATH . '/utils/helpers.php';
error_log("Helpers path: " . $helpersPath);
error_log("Helpers exists: " . (file_exists($helpersPath) ? 'YES' : 'NO'));

if (file_exists($helpersPath)) {
    require_once $helpersPath;
    error_log("Helpers loaded successfully");
} else {
    error_log("ERROR: Helpers file not found!");
}

// Debug after loading helpers
error_log("=== AFTER HELPERS ===");
error_log("t() function: " . (function_exists('t') ? 'YES' : 'NO'));
error_log("currentLang() function: " . (function_exists('currentLang') ? 'YES' : 'NO'));

// Load configuration
require_once 'config/app.php';
require_once 'config/database.php';

// Load core classes
require_once 'app/core/Database.php';
require_once 'app/core/Router.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Model.php';

// Load utility classes
require_once 'app/utils/DatabaseSetup.php';
require_once 'app/utils/Auth.php';

// Handle the request
$router = new Router();
$router->route();