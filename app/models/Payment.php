<?php

class Payment extends Model {
    protected $table = 'me_payment';

    /**
     * ดึงสถิติการชำระเงินจาก me_payment
     */
    public function getPaymentStats($month = null, $year = null) {
        try {
            $targetMonth = $month ? (int)$month : (int)date('m');
            $targetYear = $year ? (int)$year : (int)date('Y');

            $currentUser = Auth::user();
            $userRole = $currentUser['role'];
            $chckCode = $currentUser['code'];

            // สร้างเงื่อนไข WHERE สำหรับ agent
            $whrData = "";
            $whrParams = [];
            
            if($userRole == 'agent'){
                $whrData = " AND p.sales_rep_code = ? ";
                $whrParams[] = $chckCode;
            }

            // ดึง pcode ทั้งหมดที่มีใบแจ้งหนี้ (พร้อมเงื่อนไข agent)
            $sqlInvoices = "SELECT DISTINCT i.pcode 
                           FROM me_invoice i 
                           LEFT JOIN ali_product p ON i.pcode = p.pcode 
                           WHERE i.month = ? AND i.year = ? $whrData";
            $stmtInvoices = $this->db->prepare($sqlInvoices);
            $invoiceParams = array_merge([$targetMonth, $targetYear], $whrParams);
            $stmtInvoices->execute($invoiceParams);
            $invoicePcodes = $stmtInvoices->fetchAll(PDO::FETCH_COLUMN, 0);

            if (empty($invoicePcodes)) {
                return array(
                    'paid_count' => 0,
                    'not_paid_count' => 0,
                    'total_paid' => 0,
                    'total_invoice' => 0,
                    'total_invoices' => 0
                );
            }

            // ดึง pcode ที่มีการชำระเงินแล้ว (พร้อมเงื่อนไข agent)
            $placeholders = str_repeat('?,', count($invoicePcodes) - 1) . '?';
            $sqlPaid = "SELECT COUNT(DISTINCT pm.pcode) 
                       FROM me_payment pm 
                       LEFT JOIN ali_product p ON pm.pcode = p.pcode 
                       WHERE pm.pcode IN ($placeholders) AND pm.month = ? AND pm.year = ? $whrData";
            $paidParams = array_merge($invoicePcodes, [$targetMonth, $targetYear], $whrParams);
            $stmtPaid = $this->db->prepare($sqlPaid);
            $stmtPaid->execute($paidParams);
            $paidCount = (int)$stmtPaid->fetchColumn();

            // คำนวณจำนวนที่ยังไม่ชำระ
            $notPaidCount = count($invoicePcodes) - $paidCount;

            // ราคารวมที่ชำระแล้ว (พร้อมเงื่อนไข agent)
            $sqlPrice = "SELECT SUM(pm.price) as total_paid 
                        FROM me_payment pm 
                        LEFT JOIN ali_product p ON pm.pcode = p.pcode 
                        WHERE pm.pcode IN ($placeholders) AND pm.month = ? AND pm.year = ? $whrData";
            $priceParams = array_merge($invoicePcodes, [$targetMonth, $targetYear], $whrParams);
            $stmtPrice = $this->db->prepare($sqlPrice);
            $stmtPrice->execute($priceParams);
            $totalPaid = (float)$stmtPrice->fetchColumn();

            // ราคารวมใบแจ้งหนี้ทั้งหมด (พร้อมเงื่อนไข agent)
            $sqlInvoicePrice = "SELECT SUM(i.price) as total_invoice 
                               FROM me_invoice i 
                               LEFT JOIN ali_product p ON i.pcode = p.pcode 
                               WHERE i.pcode IN ($placeholders) AND i.month = ? AND i.year = ? $whrData";
            $invoicePriceParams = array_merge($invoicePcodes, [$targetMonth, $targetYear], $whrParams);
            $stmtInvoicePrice = $this->db->prepare($sqlInvoicePrice);
            $stmtInvoicePrice->execute($invoicePriceParams);
            $totalInvoice = (float)$stmtInvoicePrice->fetchColumn();

            return array(
                'paid_count' => $paidCount,
                'not_paid_count' => $notPaidCount,
                'total_paid' => $totalPaid,
                'total_invoice' => $totalInvoice,
                'total_invoices' => count($invoicePcodes)
            );
        } catch (PDOException $e) {
            error_log("Error getting payment stats: " . $e->getMessage());
            return array(
                'paid_count' => 0,
                'not_paid_count' => 0,
                'total_paid' => 0,
                'total_invoice' => 0,
                'total_invoices' => 0
            );
        }
    }

    /**
     * ดึงข้อมูลใบแจ้งหนี้ที่พร้อมชำระเงิน
     */
    public function getAllPayments($month = null, $year = null) {
        try {
            // ใช้เดือน/ปีที่ระบุ หรือเดือน/ปีปัจจุบัน
            $targetMonth = $month ? (int)$month : (int)date('m');
            $targetYear = $year ? (int)$year : (int)date('Y');
            
            $currentUser = Auth::user();
            $userRole = $currentUser['role'];
            $chckCode = $currentUser['code'];

            // สร้างเงื่อนไข WHERE สำหรับ agent
            $whrData = "";
            $whrParams = [];
            
            if($userRole == 'agent'){
                $whrData = " AND p.sales_rep_code = ? ";
                $whrParams[] = $chckCode;
            }

            // ดึงเฉพาะ pcode ที่มีใบแจ้งหนี้ (พร้อมเงื่อนไข agent)
            $sqlInvoices = "SELECT DISTINCT i.pcode 
                           FROM me_invoice i 
                           LEFT JOIN ali_product p ON i.pcode = p.pcode 
                           WHERE i.month = ? AND i.year = ? $whrData";
            $invoiceParams = array_merge([$targetMonth, $targetYear], $whrParams);
            $stmtInvoices = $this->db->prepare($sqlInvoices);
            $stmtInvoices->execute($invoiceParams);
            $invoicePcodes = $stmtInvoices->fetchAll(PDO::FETCH_COLUMN, 0);
            
            if (empty($invoicePcodes)) {
                return array(); // ไม่มีใบแจ้งหนี้
            }
            
            // ดึงข้อมูลสินค้า
            $placeholders = str_repeat('?,', count($invoicePcodes) - 1) . '?';
            $sql = "SELECT 
                    p.pcode,
                    p.pdesc,
                    pc.cate_name,
                    COALESCE(pg1.groupname, pg2.groupname) as groupname
                FROM ali_productcategory pc
                LEFT JOIN ali_productgroup pg1 ON pc.id = pg1.id_cate
                LEFT JOIN ali_product p ON pg1.id = p.group_id
                LEFT JOIN ali_productgroup pg2 ON p.group_id = pg2.id
                WHERE (pc.id = 34 OR pc.id = 54) AND p.sh = 1 
                AND p.pcode IN ($placeholders)  
                ORDER BY pc.cate_name, groupname, p.pcode";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($invoicePcodes);
            
            if (!$result) {
                error_log("getAllPayments: Execute failed - " . print_r($stmt->errorInfo(), true));
                return array();
            }
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($products)) {
                return array();
            }
            
            // ดึงข้อมูลใบแจ้งหนี้แบบ batch
            $allInvoices = $this->getAllInvoicesBatch($invoicePcodes, $targetMonth, $targetYear);
            
            // ดึงข้อมูลการชำระเงินแบบ batch
            $allPayments = $this->getAllPaymentsBatch($invoicePcodes, $targetMonth, $targetYear);
            
            $results = array();
            
            foreach ($products as $product) {
                $pcode = $product['pcode'];
                
                if (!isset($allInvoices[$pcode])) {
                    continue; // ข้ามถ้าไม่มีใบแจ้งหนี้
                }
                
                $invoices = $allInvoices[$pcode];
                $payments = isset($allPayments[$pcode]) ? $allPayments[$pcode] : array();
                
                // คำนวณยอดรวมจากใบแจ้งหนี้
                $electricity = isset($invoices['ค่าไฟ']) ? $invoices['ค่าไฟ'] : 0;
                $water = isset($invoices['ค่าน้ำ']) ? $invoices['ค่าน้ำ'] : 0;
                $garbage = isset($invoices['ค่าขยะ']) ? $invoices['ค่าขยะ'] : 0;
                $common_area = isset($invoices['ค่าส่วนกลาง']) ? $invoices['ค่าส่วนกลาง'] : 0;
                $totalInvoice = $electricity + $water + $garbage + $common_area;
                
                // คำนวณยอดชำระแล้ว
                $paidElectricity = isset($payments['ค่าไฟ']) ? $payments['ค่าไฟ'] : 0;
                $paidWater = isset($payments['ค่าน้ำ']) ? $payments['ค่าน้ำ'] : 0;
                $paidGarbage = isset($payments['ค่าขยะ']) ? $payments['ค่าขยะ'] : 0;
                $paidCommonArea = isset($payments['ค่าส่วนกลาง']) ? $payments['ค่าส่วนกลาง'] : 0;
                $totalPaid = $paidElectricity + $paidWater + $paidGarbage + $paidCommonArea;
                
                // คำนวณยอดคงเหลือ
                $balance = $totalInvoice - $totalPaid;
                
                // ตรวจสอบสถานะ
                $isPaid = $this->isFullyPaid($pcode, $targetMonth, $targetYear);
                
                $results[] = array(
                    'id' => $pcode . '_' . $targetMonth . '_' . $targetYear,
                    'pcode' => (string)$pcode,
                    'pdesc' => (string)$product['pdesc'],
                    'cate_name' => (string)$product['cate_name'],
                    'groupname' => (string)$product['groupname'],
                    'electricity' => (float)$electricity,
                    'water' => (float)$water,
                    'garbage' => (float)$garbage,
                    'common_area' => (float)$common_area,
                    'total_invoice' => (float)$totalInvoice,
                    'paid_electricity' => (float)$paidElectricity,
                    'paid_water' => (float)$paidWater,
                    'paid_garbage' => (float)$paidGarbage,
                    'paid_common_area' => (float)$paidCommonArea,
                    'total_paid' => (float)$totalPaid,
                    'balance' => (float)$balance,
                    'status' => $isPaid ? 'paid' : 'unpaid',
                    'month' => (int)$targetMonth,
                    'year' => (int)$targetYear,
                    'inv_no' => isset($invoices['_inv_no']) ? $invoices['_inv_no'] : ''
                );
            }
            
            error_log("getAllPayments: Found " . count($results) . " invoice records");
            return $results;
            
        } catch (PDOException $e) {
            error_log("Error getting all payments: " . $e->getMessage());
            return array();
        }
    }

    /**
     * ดึงข้อมูลใบแจ้งหนี้แบบ batch
     */
    private function getAllInvoicesBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            $placeholders = str_repeat('?,', count($pcodes) - 1) . '?';
            $sql = "SELECT pcode, type, price, inv_no 
                    FROM me_invoice 
                    WHERE pcode IN ($placeholders) AND month = ? AND year = ?";
            
            $params = array_merge($pcodes, [$month, $year]);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['pcode']][$row['type']] = $row['price'];
                // เก็บเลขที่เอกสาร (ใช้ตัวแรกที่พบ)
                if (!isset($results[$row['pcode']]['_inv_no'])) {
                    $results[$row['pcode']]['_inv_no'] = $row['inv_no'];
                }
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting batch invoices: " . $e->getMessage());
            return array();
        }
    }

    /**
     * ดึงข้อมูลการชำระเงินแบบ batch
     */
    private function getAllPaymentsBatch($pcodes, $month, $year) {
        if (empty($pcodes)) return array();
        
        try {
            $placeholders = str_repeat('?,', count($pcodes) - 1) . '?';
            $sql = "SELECT pcode, type, price 
                    FROM me_payment 
                    WHERE pcode IN ($placeholders) AND month = ? AND year = ?";
            
            $params = array_merge($pcodes, [$month, $year]);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['pcode']][$row['type']] = $row['price'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting batch payments: " . $e->getMessage());
            return array();
        }
    }

    /**
     * ตรวจสอบว่าชำระครบแล้วหรือไม่
     */
    private function isFullyPaid($pcode, $month, $year) {
        try {
            // ดึงยอดรวมใบแจ้งหนี้
            $sqlInvoice = "SELECT SUM(price) as total_invoice FROM me_invoice WHERE pcode = ? AND month = ? AND year = ?";
            $stmtInvoice = $this->db->prepare($sqlInvoice);
            $stmtInvoice->execute([$pcode, $month, $year]);
            $totalInvoice = (float)$stmtInvoice->fetchColumn();

            // ดึงยอดชำระแล้ว
            $sqlPayment = "SELECT SUM(price) as total_paid FROM me_payment WHERE pcode = ? AND month = ? AND year = ?";
            $stmtPayment = $this->db->prepare($sqlPayment);
            $stmtPayment->execute([$pcode, $month, $year]);
            $totalPaid = (float)$stmtPayment->fetchColumn();

            return $totalPaid >= $totalInvoice;
        } catch (PDOException $e) {
            error_log("Error checking payment status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * บันทึกการชำระเงิน
     */
    public function createPayment($pcode, $month, $year, $electricity, $water, $garbage, $common_area, $inv_no) {
        try {
            // ตรวจสอบว่ามียอดชำระอย่างน้อย 1 รายการที่มากกว่า 0
            $totalAmount = $electricity + $water + $garbage + $common_area;
            
            // อนุญาตให้บันทึกแม้ยอดเป็น 0
            // ลบการตรวจสอบ if ($totalAmount <= 0) 
            
            // ดึง user ID ของผู้ใช้ปัจจุบัน
            $currentUser = Auth::user();
            $userId = isset($currentUser['id']) ? $currentUser['id'] : null;
            
            // สร้างเลขที่เอกสารการชำระเงิน
            $paymentNo = $this->generatePaymentNumber($month, $year);
            
            // บันทึกการชำระเงิน
            $paymentTypes = [
                'ค่าไฟ' => $electricity,
                'ค่าน้ำ' => $water,
                'ค่าขยะ' => $garbage,
                'ค่าส่วนกลาง' => $common_area
            ];
            
            $createdCount = 0;
            $totalPaid = 0;
            $datetime = date('Y-m-d H:i:s');
            foreach ($paymentTypes as $type => $amount) {
                // อนุญาตให้บันทึกแม้ยอดเป็น 0
                // ลบการตรวจสอบ if ($amount > 0) 
                
                $sql = "INSERT INTO me_payment 
                        (payment_id, payment_no, pcode, month, year, type, price, created_at, created_by, updated_at, updated_by, inv_no) 
                        VALUES (UUID(), ?, ?, ?, ?, ?, ?, '$datetime', ?, '$datetime', ?, ?)";
                
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    $paymentNo,
                    $pcode,
                    $month,
                    $year,
                    $type,
                    $amount,
                    $userId,
                    $userId,
                    $inv_no
                ]);
                
                if ($result) {
                    $createdCount++;
                    $totalPaid += $amount;
                    error_log("Payment created: $paymentNo - pcode=$pcode, type=$type, amount=$amount");
                }
            }
            
            if ($createdCount > 0) {
                return array(
                    'success' => true, 
                    'message' => 'บันทึกการชำระเงินสำเร็จ (' . $createdCount . ' รายการ)',
                    'payment_no' => $paymentNo,
                    'total_paid' => $totalPaid,
                    'created_count' => $createdCount
                );
            } else {
                return array('success' => false, 'message' => 'ไม่สามารถบันทึกการชำระเงินได้');
            }
        } catch (PDOException $e) {
            error_log("Error creating payment: " . $e->getMessage());
            return array('success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * สร้างเลขที่เอกสารการชำระเงิน
     */
    private function generatePaymentNumber($month, $year) {
        try {
            // นับจำนวนเอกสารในเดือน/ปีนี้
            $sql = "SELECT COUNT(DISTINCT payment_no) FROM me_payment WHERE month = ? AND year = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$month, $year]);
            $count = $stmt->fetchColumn() + 1;
            
            // รูปแบบ: RE2025100001 (PAY + YYYY + MM + XXXX)
            return sprintf('RE%s%02d%04d', $year, $month, $count);
        } catch (PDOException $e) {
            error_log("Error generating payment number: " . $e->getMessage());
            return sprintf('RE%s%02d%04d', $year, $month, 1);
        }
    }

    /**
     * ตรวจสอบว่ามีการชำระเงินแล้วหรือไม่
     */
    public function paymentExists($pcode, $month, $year) {
        try {
            $sql = "SELECT COUNT(*) FROM me_payment WHERE pcode = ? AND month = ? AND year = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pcode, $month, $year]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking payment existence: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ดึงข้อมูลการชำระเงินตาม pcode
     */
    public function getPaymentByPcode($pcode, $month, $year) {
        try {
            $sql = "SELECT * FROM me_payment WHERE pcode = ? AND month = ? AND year = ? ORDER BY type";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pcode, $month, $year]);
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($payments)) {
                $firstPayment = $payments[0];
                $totalPaid = 0;
                $types = [];
                
                foreach ($payments as $payment) {
                    $totalPaid += $payment['price'];
                    $types[] = $payment['type'] . ' (' . number_format($payment['price'], 2) . ')';
                }
                
                return [
                    'payment_no' => $firstPayment['payment_no'],
                    'total_paid' => $totalPaid,
                    'types' => implode(', ', $types),
                    'all_payments' => $payments
                ];
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Error getting payment by pcode: " . $e->getMessage());
            return null;
        }
    }
}