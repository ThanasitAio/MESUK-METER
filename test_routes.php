<?php
// test_routes.php - ทดสอบ Routes

define('BASE_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');
define('VIEW_PATH', __DIR__ . '/views');

require_once __DIR__ . '/app/core/Router.php';

echo "<h1>ทดสอบ Routes</h1>";
echo "<hr>";

// โหลด routes
$routesConfig = require __DIR__ . '/config/routes.php';

echo "<h2>Routes ที่มีอยู่</h2>";

echo "<h3>GET Routes:</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #ddd;'><th>Pattern</th><th>Handler</th><th>Example URL</th></tr>";
foreach ($routesConfig['GET'] as $pattern => $handler) {
    $exampleUrl = str_replace('{id}', '123', $pattern);
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($pattern) . "</code></td>";
    echo "<td>" . htmlspecialchars($handler) . "</td>";
    echo "<td><a href='" . htmlspecialchars($exampleUrl) . "' target='_blank'>" . htmlspecialchars($exampleUrl) . "</a></td>";
    echo "</tr>";
}
echo "</table><br>";

echo "<h3>POST Routes:</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #ddd;'><th>Pattern</th><th>Handler</th><th>Example URL</th></tr>";
foreach ($routesConfig['POST'] as $pattern => $handler) {
    $exampleUrl = str_replace('{id}', '123', $pattern);
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($pattern) . "</code></td>";
    echo "<td>" . htmlspecialchars($handler) . "</td>";
    echo "<td>" . htmlspecialchars($exampleUrl) . "</td>";
    echo "</tr>";
}
echo "</table><br>";

echo "<h2>ทดสอบ Pattern Matching</h2>";

$router = new Router();

// ทดสอบ patterns
$testPaths = array(
    array('GET', '/users', 'ควรไปที่ UserManagementController@index'),
    array('GET', '/users/create', 'ควรไปที่ UserManagementController@create'),
    array('GET', '/users/edit/123', 'ควรไปที่ UserManagementController@edit'),
    array('POST', '/users/delete/456', 'ควรไปที่ UserManagementController@delete'),
    array('POST', '/users/change-status/789', 'ควรไปที่ UserManagementController@changeStatus')
);

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #ddd;'><th>Method</th><th>Path</th><th>Expected</th><th>Result</th></tr>";

foreach ($testPaths as $test) {
    list($method, $path, $expected) = $test;
    
    // แปลง pattern เป็น regex เหมือน Router
    $found = false;
    $matchedRoute = '';
    
    foreach ($routesConfig[$method] as $route => $handler) {
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $path, $matches)) {
            $found = true;
            $matchedRoute = $handler;
            if (count($matches) > 1) {
                $matchedRoute .= ' (params: ' . implode(', ', array_slice($matches, 1)) . ')';
            }
            break;
        }
    }
    
    $result = $found ? '✅ ' . $matchedRoute : '❌ Not Found';
    $resultColor = $found ? 'green' : 'red';
    
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($method) . "</strong></td>";
    echo "<td><code>" . htmlspecialchars($path) . "</code></td>";
    echo "<td>" . htmlspecialchars($expected) . "</td>";
    echo "<td style='color: $resultColor;'>" . $result . "</td>";
    echo "</tr>";
}

echo "</table><br>";

echo "<hr>";
echo "<h2>Quick Links</h2>";
echo "<ul>";
echo "<li><a href='/users'>ไปหน้าจัดการผู้ใช้</a></li>";
echo "<li><a href='/users/create'>ไปหน้าเพิ่มผู้ใช้</a></li>";
echo "<li><a href='/test_db_users.php'>ทดสอบฐานข้อมูล</a></li>";
echo "</ul>";
?>
