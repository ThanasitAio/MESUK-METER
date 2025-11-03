<?php
class Router {
    private $routes = array(
        'GET' => array(),
        'POST' => array()
    );

    public function __construct() {
        // Load routes from config
        $routesConfig = require BASE_PATH . '/config/routes.php';
        $this->routes = array_merge($this->routes, $routesConfig);
    }

    public function route() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Debug: แสดง path ที่ได้รับ
        error_log("Request Path: " . $path);
        
        // Load base path from config
        $config = require BASE_PATH . '/config/app.php';
        $basePath = isset($config['app']['base_path']) ? $config['app']['base_path'] : '';
        
        // Remove base path from URL if exists
        if (!empty($basePath) && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        $path = $path ? $path : '/';
        
        // Debug: แสดง path หลังตัด basePath
        error_log("Processed Path: " . $path);
        error_log("Request Method: " . $method);
        
        // Find matching route
        $handler = $this->findRoute($method, $path);
        
        if ($handler) {
            error_log("Route Found: " . $handler['handler']);
            $this->callHandler($handler);
        } else {
            error_log("Route Not Found - 404");
            $this->handle404();
        }
    }

    private function findRoute($method, $path) {
        // แก้ไข Null Coalescing Operator
        $routes = isset($this->routes[$method]) ? $this->routes[$method] : array();
        
        foreach ($routes as $route => $handler) {
            // Convert route pattern to regex
            $pattern = $this->routeToPattern($route);
            
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove full match
                return array(
                    'handler' => $handler,
                    'params' => $matches
                );
            }
        }
        
        return null;
    }

    private function routeToPattern($route) {
        // Convert {param} to regex capture group
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    private function callHandler($routeInfo) {
        list($controllerName, $methodName) = explode('@', $routeInfo['handler']);
        
        // Load controller file
        $controllerFile = APP_PATH . "/controllers/{$controllerName}.php";
        
        if (!file_exists($controllerFile)) {
            throw new Exception("Controller not found: {$controllerName}");
        }
        
        require_once $controllerFile;
        
        // Create controller instance
        $controller = new $controllerName();
        
        if (!method_exists($controller, $methodName)) {
            throw new Exception("Method not found: {$methodName}");
        }
        
        // Call method with parameters
        call_user_func_array(array($controller, $methodName), $routeInfo['params']);
    }

    private function handle404() {
        http_response_code(404);
        
        if (file_exists(VIEW_PATH . '/errors/404.php')) {
            include VIEW_PATH . '/errors/404.php';
        } else {
            echo "<h1>404 - Page Not Found</h1>";
            echo "<p>The requested URL was not found on this server.</p>";
            echo "<p>Requested: " . $_SERVER['REQUEST_URI'] . "</p>";
        }
        exit;
    }
}