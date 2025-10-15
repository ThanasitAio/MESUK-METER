<?php
class Controller
{
    protected function view($view, $data = [])
    {
        // ตรวจสอบ path ของไฟล์ view
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception('View not found: ' . $view . ' at path: ' . $viewPath);
        }

        extract($data);
        
        // Include layout
        require_once __DIR__ . '/../../views/layouts/main.php';
    }
}
?>