<?php

class MeterManagementController extends Controller {
    private $meterModel;
    
    public function __construct() {
        // ตรวจสอบการ login
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        // ตรวจสอบสิทธิ์ (เฉพาะ admin เท่านั้น)
        $currentUser = Auth::user();
        if ($currentUser['role'] !== 'admin') {
            http_response_code(403);
            die(t('user_management.access_denied'));
        }

        // สร้าง Meter model
        if (!class_exists('Model')) {
            require_once __DIR__ . '/../core/Model.php';
        }
        if (!class_exists('Meter')) {
            require_once __DIR__ . '/../models/Meter.php';
        }
        $this->meterModel = new Meter();
    }
    
    /**
     * แสดง
     */
    public function index() {
        $meters = 0;
       // $meters = $this->meterModel->getAllMeters();
        $stats = array(
            //'total' => $this->meterModel->countMeters()
            'total' => 0,
        );
        
        $data = array(
            'title' => t('meter_management.title'),
            'meters' => $meters,
            'stats' => $stats
        );
        
        $this->view('pages/meter-management/index', $data);
    }
    
    
}
