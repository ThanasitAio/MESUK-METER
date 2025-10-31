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
        
        // ดึงข้อมูลสถิติจากฐานข้อมูล
        $stats = $this->getDashboardStats();
        
        // ดึงกิจกรรมล่าสุด
        $recentActivities = $this->getRecentActivities();
        
        // ดึงข้อมูลสำหรับกราฟ
        $chartData = $this->getChartData();
        
        $data = array(
            'title' => 'Dashboard',
            'message' => 'Welcome to Meter System Management',
            'user' => $user,
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'chartData' => $chartData
        );
        
        $this->view('home', $data);
    }
    
    private function getDashboardStats()
    {
        $db = Database::getInstance();
        
        // นับจำนวนผู้ใช้ทั้งหมด
        $userCount = $db->query("SELECT COUNT(*) as count FROM me_users WHERE status = 'active'")->fetch();
        
        // นับจำนวนมิเตอร์ทั้งหมด
        $meterCount = $db->query("SELECT COUNT(*) as count FROM me_meter")->fetch();
        
        // นับจำนวนใบแจ้งหนี้ทั้งหมด
        $invoiceCount = $db->query("SELECT COUNT(*) as count FROM me_invoice")->fetch();
        
        // นับจำนวนการชำระเงินทั้งหมด
        $paymentCount = $db->query("SELECT COUNT(*) as count FROM me_payment")->fetch();
        
        // ยอดรวมใบแจ้งหนี้
        $totalInvoiceAmount = $db->query("SELECT SUM(price) as total FROM me_invoice")->fetch();
        
        // ยอดรวมการชำระเงิน
        $totalPaymentAmount = $db->query("SELECT SUM(price) as total FROM me_payment")->fetch();
        
        // มิเตอร์ล่าสุดเดือนนี้
        $currentMonthMeters = $db->query("SELECT COUNT(*) as count FROM me_meter WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())")->fetch();
        
        return array(
            'totalUsers' => isset($userCount['count']) ? $userCount['count'] : 0,
            'totalMeters' => isset($meterCount['count']) ? $meterCount['count'] : 0,
            'totalInvoices' => isset($invoiceCount['count']) ? $invoiceCount['count'] : 0,
            'totalPayments' => isset($paymentCount['count']) ? $paymentCount['count'] : 0,
            'totalInvoiceAmount' => isset($totalInvoiceAmount['total']) ? $totalInvoiceAmount['total'] : 0,
            'totalPaymentAmount' => isset($totalPaymentAmount['total']) ? $totalPaymentAmount['total'] : 0,
            'currentMonthMeters' => isset($currentMonthMeters['count']) ? $currentMonthMeters['count'] : 0
        );
    }
    
    private function getChartData()
    {
        $db = Database::getInstance();
        
        // ข้อมูลรายได้รายเดือน
        $monthlyRevenue = $db->query("
            SELECT YEAR(created_at) as year, MONTH(created_at) as month, SUM(price) as total 
            FROM me_invoice 
            WHERE YEAR(created_at) = YEAR(CURDATE())
            GROUP BY YEAR(created_at), MONTH(created_at) 
            ORDER BY year, month
        ")->fetchAll();
        
        // ข้อมูลมิเตอร์รายเดือน
        $monthlyMeters = $db->query("
            SELECT YEAR(created_at) as year, MONTH(created_at) as month, 
                   COUNT(CASE WHEN meter_type = 0 THEN 1 END) as water_meters,
                   COUNT(CASE WHEN meter_type = 1 THEN 1 END) as electricity_meters
            FROM me_meter 
            WHERE YEAR(created_at) = YEAR(CURDATE())
            GROUP BY YEAR(created_at), MONTH(created_at) 
            ORDER BY year, month
        ")->fetchAll();
        
        // ข้อมูลการกระจายตัวผู้ใช้ - เฉพาะ agent และ admin
        $userDistribution = $db->query("
            SELECT role, COUNT(*) as count 
            FROM me_users 
            WHERE status = 'active' AND role IN ('agent', 'admin')
            GROUP BY role
        ")->fetchAll();
        
        // ข้อมูลสถานะใบแจ้งหนี้
        $invoiceStatus = $db->query("
            SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN EXISTS (
                    SELECT 1 FROM me_payment p WHERE p.pcode = i.pcode AND p.month = i.month AND p.year = i.year
                ) THEN 1 END) as paid,
                COUNT(CASE WHEN NOT EXISTS (
                    SELECT 1 FROM me_payment p WHERE p.pcode = i.pcode AND p.month = i.month AND p.year = i.year
                ) THEN 1 END) as unpaid
            FROM me_invoice i
        ")->fetch();
        
        // ข้อมูลเปรียบเทียบเดือนนี้ vs เดือนที่แล้ว
        $currentMonth = date('m');
        $currentYear = date('Y');
        $lastMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $lastMonthYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
        
        $monthlyComparison = $db->query("
            SELECT 
                'current' as period,
                COUNT(*) as meters,
                (SELECT COUNT(*) FROM me_invoice WHERE MONTH(created_at) = $currentMonth AND YEAR(created_at) = $currentYear) as invoices,
                (SELECT COUNT(*) FROM me_payment WHERE MONTH(created_at) = $currentMonth AND YEAR(created_at) = $currentYear) as payments
            FROM me_meter 
            WHERE MONTH(created_at) = $currentMonth AND YEAR(created_at) = $currentYear
            
            UNION ALL
            
            SELECT 
                'previous' as period,
                COUNT(*) as meters,
                (SELECT COUNT(*) FROM me_invoice WHERE MONTH(created_at) = $lastMonth AND YEAR(created_at) = $lastMonthYear) as invoices,
                (SELECT COUNT(*) FROM me_payment WHERE MONTH(created_at) = $lastMonth AND YEAR(created_at) = $lastMonthYear) as payments
            FROM me_meter 
            WHERE MONTH(created_at) = $lastMonth AND YEAR(created_at) = $lastMonthYear
        ")->fetchAll();
        
        return array(
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyMeters' => $monthlyMeters,
            'userDistribution' => $userDistribution,
            'invoiceStatus' => $invoiceStatus,
            'monthlyComparison' => $monthlyComparison
        );
    }
    
    private function getRecentActivities()
    {
        $db = Database::getInstance();
        
        // ดึงผู้ใช้ล่าสุด
        $recentUsers = $db->query("SELECT name, created_date FROM me_users ORDER BY created_date DESC LIMIT 3")->fetchAll();
        
        // ดึงมิเตอร์ล่าสุด
        $recentMeters = $db->query("SELECT pcode, reading_date, created_at FROM me_meter ORDER BY created_at DESC LIMIT 3")->fetchAll();
        
        // ดึงใบแจ้งหนี้ล่าสุด
        $recentInvoices = $db->query("SELECT pcode, month, year, created_at FROM me_invoice ORDER BY created_at DESC LIMIT 3")->fetchAll();
        
        // ดึงการชำระเงินล่าสุด
        $recentPayments = $db->query("SELECT pcode, month, year, created_at FROM me_payment ORDER BY created_at DESC LIMIT 3")->fetchAll();
        
        $activities = array();
        
        // รวมกิจกรรมทั้งหมด
        foreach ($recentUsers as $user) {
            $activities[] = array(
                'type' => 'user',
                'message' => t('notifications.new_user') . ': ' . $user['name'],
                'time' => $this->timeAgo($user['created_date']),
                'icon' => 'bi bi-person-plus',
                'color' => 'text-success'
            );
        }
        
        foreach ($recentMeters as $meter) {
            $activities[] = array(
                'type' => 'meter',
                'message' => t('meter_management.title') . ': ' . $meter['pcode'],
                'time' => $this->timeAgo($meter['created_at']),
                'icon' => 'bi bi-speedometer2',
                'color' => 'text-info'
            );
        }
        
        foreach ($recentInvoices as $invoice) {
            $activities[] = array(
                'type' => 'invoice',
                'message' => t('invoice_management.title') . ': ' . $invoice['pcode'] . ' (' . $invoice['month'] . '/' . $invoice['year'] . ')',
                'time' => $this->timeAgo($invoice['created_at']),
                'icon' => 'bi bi-receipt',
                'color' => 'text-warning'
            );
        }
        
        foreach ($recentPayments as $payment) {
            $activities[] = array(
                'type' => 'payment',
                'message' => t('payment_management.title') . ': ' . $payment['pcode'] . ' (' . $payment['month'] . '/' . $payment['year'] . ')',
                'time' => $this->timeAgo($payment['created_at']),
                'icon' => 'bi bi-credit-card',
                'color' => 'text-primary'
            );
        }
        
        // เรียงลำดับตามเวลา
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($activities, 0, 5);
    }
    
   private function timeAgo($datetime)
    {
        if (empty($datetime)) return t('dashboard.unknown_time');
        
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return t('dashboard.just_now');
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return str_replace('{minutes}', $minutes, t('dashboard.minutes_ago'));
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return str_replace('{hours}', $hours, t('dashboard.hours_ago'));
        } else {
            $days = floor($diff / 86400);
            return str_replace('{days}', $days, t('dashboard.days_ago'));
        }
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