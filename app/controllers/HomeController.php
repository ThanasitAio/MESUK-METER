<?php
class HomeController extends Controller
{
    public function index()
    {
        
        $data = [
            'title' => 'Dashboard',
            'message' => 'Welcome to MESUK System Management'
        ];
        
        $this->view('home', $data);  // เปลี่ยนจาก 'pages/home' เป็น 'home'
    }
    
    public function setup()
    {
        $data = [
            'title' => 'Database Setup', 
            'message' => 'Setup your database configuration'
        ];
        
        $this->view('setup', $data);
    }
}
?>