<?php
require_once __DIR__ . '/../utils/Auth.php';

class HomeController extends Controller
{
    public function index()
    {
        // ✅ ตรวจสอบว่า login แล้วหรือยัง ถ้ายังให้ไปหน้า login
        Auth::requireLogin();
        
        // ดึงข้อมูล user ที่ login
        $user = Auth::user();
        
        $data = array(
            'title' => 'Dashboard',
            'message' => 'Welcome to MESUK System Management',
            'user' => $user
        );
        
        $this->view('home', $data);
    }
    
    public function setup()
    {
        // ตรวจสอบว่าต้อง login และต้องเป็น admin
        Auth::requireRole('admin');
        
        $data = array(
            'title' => 'Database Setup', 
            'message' => 'Setup your database configuration'
        );
        
        $this->view('setup', $data);
    }
}