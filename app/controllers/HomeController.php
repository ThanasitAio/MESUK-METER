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
        
        // ดึงข้อมูลสถิติจากฐานข้อมูล (ปรับให้รองรับ agent)
        $stats = $this->getDashboardStats();
        
        // ดึงกิจกรรมล่าสุด (ปรับให้รองรับ agent)
        $recentActivities = $this->getRecentActivities();
        
        // ดึงข้อมูลสำหรับกราฟ (ปรับให้รองรับ agent)
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
        $currentUser = Auth::user();
        $userRole = $currentUser['role'];
        $chckCode = $currentUser['code'];
        
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        // นับจำนวนผู้ใช้ทั้งหมด (เฉพาะ agent และ admin)
        $userSql = "SELECT COUNT(*) as count FROM me_users WHERE status = 'active' AND role IN ('agent', 'admin')";
        $userCount = $db->query($userSql)->fetch();
        
        // นับจำนวนมิเตอร์ทั้งหมด (ปรับให้รองรับ agent) - GROUP BY pcode, month, year
        $meterSql = "SELECT COUNT(DISTINCT CONCAT(pcode, '-', month, '-', year)) as count FROM me_meter WHERE 1=1";
        if($userRole == 'agent') {
            $meterSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
            $meterStmt = $db->prepare($meterSql);
            $meterStmt->execute([$chckCode]);
            $meterCount = $meterStmt->fetch();
        } else {
            $meterCount = $db->query($meterSql)->fetch();
        }
        
        // นับจำนวนใบแจ้งหนี้ทั้งหมด (ปรับให้รองรับ agent) - GROUP BY inv_no
        $invoiceSql = "SELECT COUNT(DISTINCT inv_no) as count FROM me_invoice WHERE 1=1";
        if($userRole == 'agent') {
            $invoiceSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
            $invoiceStmt = $db->prepare($invoiceSql);
            $invoiceStmt->execute([$chckCode]);
            $invoiceCount = $invoiceStmt->fetch();
        } else {
            $invoiceCount = $db->query($invoiceSql)->fetch();
        }
        
        // นับจำนวนการชำระเงินทั้งหมด (ปรับให้รองรับ agent) - GROUP BY payment_no
        $paymentSql = "SELECT COUNT(DISTINCT payment_no) as count FROM me_payment WHERE 1=1";
        if($userRole == 'agent') {
            $paymentSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
            $paymentStmt = $db->prepare($paymentSql);
            $paymentStmt->execute([$chckCode]);
            $paymentCount = $paymentStmt->fetch();
        } else {
            $paymentCount = $db->query($paymentSql)->fetch();
        }
        
        // ยอดรวมใบแจ้งหนี้ (ปรับให้รองรับ agent) - รวมราคาจากทุก type แต่ละใบ (ทศนิยม 2 ตำแหน่ง)
        $totalInvoiceSql = "SELECT ROUND(SUM(price), 2) as total FROM me_invoice WHERE 1=1";
        if($userRole == 'agent') {
            $totalInvoiceSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
            $totalInvoiceStmt = $db->prepare($totalInvoiceSql);
            $totalInvoiceStmt->execute([$chckCode]);
            $totalInvoiceAmount = $totalInvoiceStmt->fetch();
        } else {
            $totalInvoiceAmount = $db->query($totalInvoiceSql)->fetch();
        }
        
        // ยอดรวมการชำระเงิน (ปรับให้รองรับ agent) - รวมราคาจากทุก type แต่ละใบ (ทศนิยม 2 ตำแหน่ง)
        $totalPaymentSql = "SELECT ROUND(SUM(price), 2) as total FROM me_payment WHERE 1=1";
        if($userRole == 'agent') {
            $totalPaymentSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
            $totalPaymentStmt = $db->prepare($totalPaymentSql);
            $totalPaymentStmt->execute([$chckCode]);
            $totalPaymentAmount = $totalPaymentStmt->fetch();
        } else {
            $totalPaymentAmount = $db->query($totalPaymentSql)->fetch();
        }
        
        // มิเตอร์ล่าสุดเดือนนี้ (ปรับให้รองรับ agent) - ใช้ month และ year แทน created_at
        $currentMonthSql = "SELECT COUNT(DISTINCT CONCAT(pcode, '-', month, '-', year)) as count FROM me_meter 
                           WHERE month = ? AND year = ?";
        
        if($userRole == 'agent') {
            $currentMonthSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
            $currentMonthStmt = $db->prepare($currentMonthSql);
            $currentMonthStmt->execute([$currentMonth, $currentYear, $chckCode]);
            $currentMonthMeters = $currentMonthStmt->fetch();
        } else {
            $currentMonthStmt = $db->prepare($currentMonthSql);
            $currentMonthStmt->execute([$currentMonth, $currentYear]);
            $currentMonthMeters = $currentMonthStmt->fetch();
        }
        
        return array(
            'totalUsers' => isset($userCount['count']) ? $userCount['count'] : 0,
            'totalMeters' => isset($meterCount['count']) ? $meterCount['count'] : 0,
            'totalInvoices' => isset($invoiceCount['count']) ? $invoiceCount['count'] : 0,
            'totalPayments' => isset($paymentCount['count']) ? $paymentCount['count'] : 0,
            'totalInvoiceAmount' => isset($totalInvoiceAmount['total']) ? number_format($totalInvoiceAmount['total'], 2) : '0.00',
            'totalPaymentAmount' => isset($totalPaymentAmount['total']) ? number_format($totalPaymentAmount['total'], 2) : '0.00',
            'currentMonthMeters' => isset($currentMonthMeters['count']) ? $currentMonthMeters['count'] : 0
        );
    }
    
    private function getChartData()
    {
        $db = Database::getInstance();
        $currentUser = Auth::user();
        $userRole = $currentUser['role'];
        $chckCode = $currentUser['code'];
        
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        // ข้อมูลรายได้รายเดือน (ปรับให้รองรับ agent) - ใช้ month และ year แทน created_at (ทศนิยม 2 ตำแหน่ง)
        $monthlyRevenueSql = "
            SELECT year, month, 
                   ROUND(SUM(price), 2) as total,
                   COUNT(DISTINCT inv_no) as invoice_count
            FROM me_invoice 
            WHERE year = ?
        ";
        
        if($userRole == 'agent') {
            $monthlyRevenueSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
        }
        
        $monthlyRevenueSql .= " GROUP BY year, month ORDER BY year, month";
        
        if($userRole == 'agent') {
            $monthlyRevenueStmt = $db->prepare($monthlyRevenueSql);
            $monthlyRevenueStmt->execute([$currentYear, $chckCode]);
        } else {
            $monthlyRevenueStmt = $db->prepare($monthlyRevenueSql);
            $monthlyRevenueStmt->execute([$currentYear]);
        }
        
        $monthlyRevenue = $monthlyRevenueStmt->fetchAll();
        
        // Format ข้อมูลรายได้ให้เป็นทศนิยม 2 ตำแหน่ง
        foreach ($monthlyRevenue as &$revenue) {
            $revenue['total'] = number_format($revenue['total'], 2);
        }
        
        // ข้อมูลมิเตอร์รายเดือน (ปรับให้รองรับ agent) - ใช้ month และ year แทน created_at
        $monthlyMetersSql = "
            SELECT year, month, 
                   COUNT(DISTINCT CONCAT(pcode, '-', month, '-', year)) as total_meters,
                   COUNT(DISTINCT CASE WHEN meter_type = 0 THEN CONCAT(pcode, '-', month, '-', year) END) as water_meters,
                   COUNT(DISTINCT CASE WHEN meter_type = 1 THEN CONCAT(pcode, '-', month, '-', year) END) as electricity_meters
            FROM me_meter 
            WHERE year = ?
        ";
        
        if($userRole == 'agent') {
            $monthlyMetersSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
        }
        
        $monthlyMetersSql .= " GROUP BY year, month ORDER BY year, month";
        
        if($userRole == 'agent') {
            $monthlyMetersStmt = $db->prepare($monthlyMetersSql);
            $monthlyMetersStmt->execute([$currentYear, $chckCode]);
        } else {
            $monthlyMetersStmt = $db->prepare($monthlyMetersSql);
            $monthlyMetersStmt->execute([$currentYear]);
        }
        
        $monthlyMeters = $monthlyMetersStmt->fetchAll();
        
        // ข้อมูลการกระจายตัวผู้ใช้ - เฉพาะ agent และ admin (admin เท่านั้นที่เห็น)
        $userDistribution = [];
        if($userRole == 'admin') {
            $userDistribution = $db->query("
                SELECT role, COUNT(*) as count 
                FROM me_users 
                WHERE status = 'active' AND role IN ('agent', 'admin')
                GROUP BY role
            ")->fetchAll();
        }
        
        // ข้อมูลสถานะใบแจ้งหนี้ (ปรับให้รองรับ agent) - ใช้ pcode, month, year ในการจับคู่
        $invoiceStatusSql = "
            SELECT 
                COUNT(DISTINCT inv_no) as total,
                COUNT(DISTINCT CASE WHEN EXISTS (
                    SELECT 1 FROM me_payment p 
                    WHERE p.pcode = i.pcode AND p.month = i.month AND p.year = i.year
                ) THEN inv_no END) as paid,
                COUNT(DISTINCT CASE WHEN NOT EXISTS (
                    SELECT 1 FROM me_payment p 
                    WHERE p.pcode = i.pcode AND p.month = i.month AND p.year = i.year
                ) THEN inv_no END) as unpaid
            FROM me_invoice i
            WHERE 1=1
        ";
        
        if($userRole == 'agent') {
            $invoiceStatusSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
            $invoiceStatusStmt = $db->prepare($invoiceStatusSql);
            $invoiceStatusStmt->execute([$chckCode]);
        } else {
            $invoiceStatusStmt = $db->prepare($invoiceStatusSql);
            $invoiceStatusStmt->execute();
        }
        
        $invoiceStatus = $invoiceStatusStmt->fetch();
        
        // ข้อมูลเปรียบเทียบเดือนนี้ vs เดือนที่แล้ว (ปรับให้รองรับ agent)
        $lastMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $lastMonthYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
        
        // สร้าง SQL สำหรับ monthly comparison - ใช้ month และ year แทน created_at
        if($userRole == 'agent') {
            $monthlyComparisonSql = "
                SELECT 
                    'current' as period,
                    COUNT(DISTINCT CONCAT(pcode, '-', month, '-', year)) as meters,
                    (SELECT COUNT(DISTINCT inv_no) FROM me_invoice WHERE month = ? AND year = ? AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)) as invoices,
                    (SELECT COUNT(DISTINCT payment_no) FROM me_payment WHERE month = ? AND year = ? AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)) as payments
                FROM me_meter 
                WHERE month = ? AND year = ? AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)
                
                UNION ALL
                
                SELECT 
                    'previous' as period,
                    COUNT(DISTINCT CONCAT(pcode, '-', month, '-', year)) as meters,
                    (SELECT COUNT(DISTINCT inv_no) FROM me_invoice WHERE month = ? AND year = ? AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)) as invoices,
                    (SELECT COUNT(DISTINCT payment_no) FROM me_payment WHERE month = ? AND year = ? AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)) as payments
                FROM me_meter 
                WHERE month = ? AND year = ? AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)
            ";
            
            $monthlyComparisonStmt = $db->prepare($monthlyComparisonSql);
            $monthlyComparisonStmt->execute([
                $currentMonth, $currentYear, $chckCode,
                $currentMonth, $currentYear, $chckCode,
                $currentMonth, $currentYear, $chckCode,
                $lastMonth, $lastMonthYear, $chckCode,
                $lastMonth, $lastMonthYear, $chckCode,
                $lastMonth, $lastMonthYear, $chckCode
            ]);
        } else {
            $monthlyComparisonSql = "
                SELECT 
                    'current' as period,
                    COUNT(DISTINCT CONCAT(pcode, '-', month, '-', year)) as meters,
                    (SELECT COUNT(DISTINCT inv_no) FROM me_invoice WHERE month = ? AND year = ?) as invoices,
                    (SELECT COUNT(DISTINCT payment_no) FROM me_payment WHERE month = ? AND year = ?) as payments
                FROM me_meter 
                WHERE month = ? AND year = ?
                
                UNION ALL
                
                SELECT 
                    'previous' as period,
                    COUNT(DISTINCT CONCAT(pcode, '-', month, '-', year)) as meters,
                    (SELECT COUNT(DISTINCT inv_no) FROM me_invoice WHERE month = ? AND year = ?) as invoices,
                    (SELECT COUNT(DISTINCT payment_no) FROM me_payment WHERE month = ? AND year = ?) as payments
                FROM me_meter 
                WHERE month = ? AND year = ?
            ";
            
            $monthlyComparisonStmt = $db->prepare($monthlyComparisonSql);
            $monthlyComparisonStmt->execute([
                $currentMonth, $currentYear,
                $currentMonth, $currentYear,
                $currentMonth, $currentYear,
                $lastMonth, $lastMonthYear,
                $lastMonth, $lastMonthYear,
                $lastMonth, $lastMonthYear
            ]);
        }
        
        $monthlyComparison = $monthlyComparisonStmt->fetchAll();
        
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
        $currentUser = Auth::user();
        $userRole = $currentUser['role'];
        $chckCode = $currentUser['code'];
        
        // ดึงผู้ใช้ล่าสุด (เฉพาะ admin เท่านั้นที่เห็น)
        $recentUsers = [];
        if($userRole == 'admin') {
            $recentUsers = $db->query("SELECT name, created_date FROM me_users ORDER BY created_date DESC LIMIT 3")->fetchAll();
        }
        
        // ดึงมิเตอร์ล่าสุด (ปรับให้รองรับ agent) - GROUP BY pcode, month, year
        $recentMetersSql = "
            SELECT pcode, month, year, MAX(created_at) as created_at 
            FROM me_meter 
            WHERE 1=1
        ";
        if($userRole == 'agent') {
            $recentMetersSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
        }
        $recentMetersSql .= " GROUP BY pcode, month, year ORDER BY created_at DESC LIMIT 3";
        
        if($userRole == 'agent') {
            $recentMetersStmt = $db->prepare($recentMetersSql);
            $recentMetersStmt->execute([$chckCode]);
        } else {
            $recentMetersStmt = $db->prepare($recentMetersSql);
            $recentMetersStmt->execute();
        }
        $recentMeters = $recentMetersStmt->fetchAll();
        
        // ดึงใบแจ้งหนี้ล่าสุด (ปรับให้รองรับ agent) - GROUP BY inv_no (รวมยอดเงินด้วย)
        $recentInvoicesSql = "
            SELECT inv_no, pcode, month, year, ROUND(SUM(price), 2) as total_amount, MAX(created_at) as created_at 
            FROM me_invoice 
            WHERE 1=1
        ";
        if($userRole == 'agent') {
            $recentInvoicesSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
        }
        $recentInvoicesSql .= " GROUP BY inv_no, pcode, month, year ORDER BY created_at DESC LIMIT 3";
        
        if($userRole == 'agent') {
            $recentInvoicesStmt = $db->prepare($recentInvoicesSql);
            $recentInvoicesStmt->execute([$chckCode]);
        } else {
            $recentInvoicesStmt = $db->prepare($recentInvoicesSql);
            $recentInvoicesStmt->execute();
        }
        $recentInvoices = $recentInvoicesStmt->fetchAll();
        
        // ดึงการชำระเงินล่าสุด (ปรับให้รองรับ agent) - GROUP BY payment_no (รวมยอดเงินด้วย)
        $recentPaymentsSql = "
            SELECT payment_no, pcode, month, year, ROUND(SUM(price), 2) as total_amount, MAX(created_at) as created_at 
            FROM me_payment 
            WHERE 1=1
        ";
        if($userRole == 'agent') {
            $recentPaymentsSql .= " AND pcode IN (SELECT pcode FROM ali_product WHERE sales_rep_code = ?)";
        }
        $recentPaymentsSql .= " GROUP BY payment_no, pcode, month, year ORDER BY created_at DESC LIMIT 3";
        
        if($userRole == 'agent') {
            $recentPaymentsStmt = $db->prepare($recentPaymentsSql);
            $recentPaymentsStmt->execute([$chckCode]);
        } else {
            $recentPaymentsStmt = $db->prepare($recentPaymentsSql);
            $recentPaymentsStmt->execute();
        }
        $recentPayments = $recentPaymentsStmt->fetchAll();
        
        $activities = array();
        
        // รวมกิจกรรมทั้งหมด (ผู้ใช้เฉพาะ admin)
        foreach ($recentUsers as $user) {
            $activities[] = array(
                'type' => 'user',
                'message' => t('notifications.new_user') . ': ' . $user['name'],
                'time' => $this->timeAgo($user['created_date']),
                'icon' => 'bi bi-person-plus',
                'color' => 'text-success'
            );
        }
        
        // กิจกรรมมิเตอร์
        foreach ($recentMeters as $meter) {
            $activities[] = array(
                'type' => 'meter',
                'message' => t('meter_management.title') . ': ' . $meter['pcode'] . ' (' . $meter['month'] . '/' . $meter['year'] . ')',
                'time' => $this->timeAgo($meter['created_at']),
                'icon' => 'bi bi-speedometer2',
                'color' => 'text-info'
            );
        }
        
        // กิจกรรมใบแจ้งหนี้ (แสดงยอดเงินด้วย)
        foreach ($recentInvoices as $invoice) {
            $activities[] = array(
                'type' => 'invoice',
                'message' => t('invoice_management.title') . ': ' . $invoice['pcode'] . ' (' . $invoice['month'] . '/' . $invoice['year'] . ') - ฿' . number_format($invoice['total_amount'], 2),
                'time' => $this->timeAgo($invoice['created_at']),
                'icon' => 'bi bi-receipt',
                'color' => 'text-warning'
            );
        }
        
        // กิจกรรมการชำระเงิน (แสดงยอดเงินด้วย)
        foreach ($recentPayments as $payment) {
            $activities[] = array(
                'type' => 'payment',
                'message' => t('payment_management.title') . ': ' . $payment['pcode'] . ' (' . $payment['month'] . '/' . $payment['year'] . ') - ฿' . number_format($payment['total_amount'], 2),
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