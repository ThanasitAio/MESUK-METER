<?php
session_start();

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

// Handle the request
$router = new Router();
$router->route();