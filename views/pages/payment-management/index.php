<?php
// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';

// ตั้งค่าปีสำหรับ dropdown
$currentYear = date('Y');
$years = array();
for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
    $years[] = $y;
}

?>
<style>
#PaymentsTable thead {
  position: sticky;
  top: 0;
  z-index: 2;
  background: #f8f9fa;
}

.month-year-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 2px 10px;
    border-radius: 8px;
    display: inline-block;
    font-size: 1.1em;
}

.balance-positive {
    color: #28a745;
    font-weight: bold;
}

.balance-zero {
    color: #6c757d;
    font-weight: bold;
}

.balance-negative {
    color: #dc3545;
    font-weight: bold;
}

.payment-input {
    font-weight: bold;
    text-align: right;
    background-color: #f8f9fa;
}

.payment-input {
    font-weight: bold;
    text-align: right;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
}

.payment-input:focus {
    background-color: #fff;
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.accordion-button:not(.collapsed) {
    background-color: #e7f1ff;
    color: #0c63e4;
}

.accordion-button:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}


/* การตกแต่งเพิ่มเติม */
.month-year-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 2px 10px;
    border-radius: 8px;
    display: inline-block;
    font-size: 1.1em;
}

.balance-positive {
    color: #28a745;
    font-weight: bold;
}

.balance-zero {
    color: #6c757d;
    font-weight: bold;
}

.balance-negative {
    color: #dc3545;
    font-weight: bold;
}

.summary-table th {
    background-color: #f8f9fa !important;
    font-weight: 600;
}

.total-row {
    background-color: #e3f2fd !important;
    font-weight: bold;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header -->
            <?php       
            echo pageHeader(t('payment_management.title'), '', '', 'fa-brands fa-paypal'); 
            ?>

            <!-- สถิติ -->
            <div class="row" id="statsContainer">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('payment_management.total_invoices'); ?></div>
                            <h4 class="mb-0 text-primary" id="totalInvoices"><?php echo isset($stats['total_invoices']) ? $stats['total_invoices'] : '0'; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('payment_management.paid_invoices'); ?></div>
                            <h4 class="mb-0 text-success" id="paidCount"><?php echo isset($stats['paid_count']) ? $stats['paid_count'] : '0'; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('payment_management.unpaid_invoices'); ?></div>
                            <h4 class="mb-0 text-warning" id="unpaidCount"><?php echo isset($stats['not_paid_count']) ? $stats['not_paid_count'] : '0'; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('payment_management.total_paid'); ?></div>
                            <h4 class="mb-0 text-info" id="totalPaid"><?php echo isset($stats['total_paid']) ? number_format($stats['total_paid'], 2) : '0.00'; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('payment_management.total_invoice_amount'); ?></div>
                            <h4 class="mb-0 text-secondary" id="totalInvoiceAmount"><?php echo isset($stats['total_invoice']) ? number_format($stats['total_invoice'], 2) : '0.00'; ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตัวกรองข้อมูล -->
            <div class="card <?php echo $headerConfig['classes']['filter_card']; ?>" style="position: relative; z-index: 100; overflow: visible;">
                <div class="card-body py-2" style="overflow: visible;">
                    <div class="row align-items-end" style="overflow: visible;">
                        <!-- ช่องค้นหา - ใช้ความกว้างเต็มในมือถือ -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="mb-0">
                                <label for="searchPayment" class="form-label fw-bold small mb-1">
                                    <i class="fas fa-search"></i> <?php echo t('payment_management.search'); ?>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm mb-1" id="searchPayment" 
                                        placeholder="<?php echo t('payment_management.pcode_placeholder'); ?>">
                                    <button type="button" class="btn btn-outline-secondary btn-sm mb-1" id="resetSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- กลุ่มเดือน/ปี/สถานะ - ใช้ grid system สำหรับจัดเรียง -->
                        <div class="col-12 col-md-6 col-lg-8">
                            <div class="row g-2">
                                <!-- เดือน -->
                                <div class="col-4 col-md-4 col-lg-2">
                                    <div class="mb-0">
                                        <label for="filterMonth" class="form-label fw-bold small mb-1"><?php echo t('payment_management.month'); ?></label>
                                        <select class="select-beast form-select form-select-sm mb-1" id="filterMonth">
                                            <option value="01" <?php echo date('m') == '01' ? 'selected' : ''; ?>><?php echo t('month.january'); ?></option>
                                            <option value="02" <?php echo date('m') == '02' ? 'selected' : ''; ?>><?php echo t('month.february'); ?></option>
                                            <option value="03" <?php echo date('m') == '03' ? 'selected' : ''; ?>><?php echo t('month.march'); ?></option>
                                            <option value="04" <?php echo date('m') == '04' ? 'selected' : ''; ?>><?php echo t('month.april'); ?></option>
                                            <option value="05" <?php echo date('m') == '05' ? 'selected' : ''; ?>><?php echo t('month.may'); ?></option>
                                            <option value="06" <?php echo date('m') == '06' ? 'selected' : ''; ?>><?php echo t('month.june'); ?></option>
                                            <option value="07" <?php echo date('m') == '07' ? 'selected' : ''; ?>><?php echo t('month.july'); ?></option>
                                            <option value="08" <?php echo date('m') == '08' ? 'selected' : ''; ?>><?php echo t('month.august'); ?></option>
                                            <option value="09" <?php echo date('m') == '09' ? 'selected' : ''; ?>><?php echo t('month.september'); ?></option>
                                            <option value="10" <?php echo date('m') == '10' ? 'selected' : ''; ?>><?php echo t('month.october'); ?></option>
                                            <option value="11" <?php echo date('m') == '11' ? 'selected' : ''; ?>><?php echo t('month.november'); ?></option>
                                            <option value="12" <?php echo date('m') == '12' ? 'selected' : ''; ?>><?php echo t('month.december'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- ปี -->
                                <div class="col-4 col-md-4 col-lg-2">
                                    <div class="mb-0">
                                        <label for="filterYear" class="form-label fw-bold small mb-1"><?php echo t('payment_management.year'); ?></label>
                                        <select class="select-beast form-select form-select-sm mb-1" id="filterYear">
                                            <?php foreach ($years as $year): ?>
                                                <option value="<?php echo $year; ?>" <?php echo date('Y') == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- สถานะ -->
                                <div class="col-4 col-md-4 col-lg-2">
                                    <div class="mb-0">
                                        <label for="filterStatus" class="form-label fw-bold small mb-1"><?php echo t('payment_management.status'); ?></label>
                                        <select class="select-beast form-select form-select-sm mb-1" id="filterStatus">
                                            <option value=""><?php echo t('selection.all'); ?></option>
                                            <option value="paid"><?php echo t('payment_management.paid'); ?></option>
                                            <option value="unpaid"><?php echo t('payment_management.unpaid'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตารางการชำระเงิน -->
            <div class="card" style="position: relative; z-index: 1;">
                <?php 
                // จำนวนจะถูกอัพเดทโดย JavaScript
                echo cardHeader(
                    t('payment_management.payment_list'), 
                    0, 
                    'fa-brands fa-paypal'
                ); 
                ?>
                
                <div class="card-body p-0">
                    <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive']; ?>; position: relative; z-index: 1;">
                        <table class="table table-sm table-hover mb-0" id="PaymentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                    <th width="130" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.invoice_no'); ?></th>
                                    <th width="90" class="text-center d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.pcode'); ?></th>
                                    <th width="80" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.month'); ?>/<?php echo t('payment_management.year'); ?></th>
                                    <th width="30" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.status'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.total_invoice'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.total_paid'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.balance'); ?></th>
                                    <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('payment_management.actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="paymentsTableBody">
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin"></i> <?php echo t('payment_management.loading'); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับบันทึกการชำระเงิน -->
<div class="modal fade" id="createPaymentModal" tabindex="-1" aria-labelledby="createPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPaymentModalLabel">
                    <i class="fa-brands fa-paypal me-2"></i><?php echo t('payment_management.create_payment'); ?>
                    <span id="paymentMonthYear" class="month-year-gradient"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ส่วนข้อมูลพื้นฐานแบบกะทัดรัด -->
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center">
                            <h6 class="text-muted mb-0 me-2"><?php echo t('payment_management.pcode'); ?> : </h6>
                            <span class="fw-bold text-primary" id="paymentPcode"></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center">
                            <h6 class="text-muted mb-0 me-2"><?php echo t('payment_management.invoice_no'); ?> : </h6>
                            <span class="fw-bold text-info" id="paymentInvNo"></span>
                        </div>
                    </div>
                </div>
                
                <!-- แสดงเลขที่เอกสารการชำระเงิน (ถ้ามีแล้ว) -->
                <div class="alert alert-info" id="existingPaymentAlert" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo t('payment_management.existing_payment'); ?>: <strong id="existingPaymentNo"></strong>
                </div>
                
                <!-- สรุปยอดเงิน (เปิดหน้าเสมอ) -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i><?php echo t('payment_management.payment_summary'); ?>
                            &nbsp;&nbsp;<span class="badge bg-primary float-end" id="totalBalanceAmount">0.00</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 summary-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%" class="text-center"><?php echo t('payment_management.item'); ?></th>
                                        <th width="20%" class="text-center"><?php echo t('payment_management.total_invoice'); ?></th>
                                        <th width="20%" class="text-center"><?php echo t('payment_management.total_paid'); ?></th>
                                        <th width="20%" class="text-center"><?php echo t('payment_management.balance'); ?></th>
                                        <th width="10%" class="text-center"><?php echo t('payment_management.status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold"><?php echo t('payment_management.electricity'); ?></td>
                                        <td class="text-end" id="paymentElectricityInvoice">0.00</td>
                                        <td class="text-end" id="paymentElectricityPaid">0.00</td>
                                        <td class="text-end" id="paymentElectricityBalance">0.00</td>
                                        <td class="text-center" id="paymentElectricityStatus">
                                            <span class="badge bg-success"><?php echo t('payment_management.ready_to_pay'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?php echo t('payment_management.water'); ?></td>
                                        <td class="text-end" id="paymentWaterInvoice">0.00</td>
                                        <td class="text-end" id="paymentWaterPaid">0.00</td>
                                        <td class="text-end" id="paymentWaterBalance">0.00</td>
                                        <td class="text-center" id="paymentWaterStatus">
                                            <span class="badge bg-success"><?php echo t('payment_management.ready_to_pay'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?php echo t('payment_management.garbage'); ?></td>
                                        <td class="text-end" id="paymentGarbageInvoice">0.00</td>
                                        <td class="text-end" id="paymentGarbagePaid">0.00</td>
                                        <td class="text-end" id="paymentGarbageBalance">0.00</td>
                                        <td class="text-center" id="paymentGarbageStatus">
                                            <span class="badge bg-success"><?php echo t('payment_management.ready_to_pay'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?php echo t('payment_management.common_area'); ?></td>
                                        <td class="text-end" id="paymentCommonAreaInvoice">0.00</td>
                                        <td class="text-end" id="paymentCommonAreaPaid">0.00</td>
                                        <td class="text-end" id="paymentCommonAreaBalance">0.00</td>
                                        <td class="text-center" id="paymentCommonAreaStatus">
                                            <span class="badge bg-success"><?php echo t('payment_management.ready_to_pay'); ?></span>
                                        </td>
                                    </tr>
                                    <tr class="table-primary fw-bold total-row">
                                        <td><?php echo t('payment_management.total'); ?></td>
                                        <td class="text-end" id="paymentTotalInvoice">0.00</td>
                                        <td class="text-end" id="paymentTotalPaid">0.00</td>
                                        <td class="text-end" id="paymentTotalBalance">0.00</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?php echo t('payment_management.total'); ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i><?php echo t('btns.cancel'); ?>
                </button>
                <button type="button" class="btn btn-success" id="confirmCreatePayment">
                    <i class="fa-brands fa-paypal me-1"></i><?php echo t('payment_management.create_payment'); ?>
                    (<span id="confirmTotalAmount">0.00</span>)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ส่งค่าภาษาจาก PHP ไปยัง JavaScript
var translations = {
    paid: '<?php echo t('payment_management.paid'); ?>',
    unpaid: '<?php echo t('payment_management.unpaid'); ?>',
    create_payment: '<?php echo t('payment_management.create_payment'); ?>',
    existing_payment: '<?php echo t('payment_management.existing_payment'); ?>',
    ready_to_pay: '<?php echo t('payment_management.ready_to_pay'); ?>',
    already_paid: '<?php echo t('payment_management.already_paid'); ?>',
    item: '<?php echo t('payment_management.item'); ?>',
    amount: '<?php echo t('payment_management.amount'); ?>',
    description: '<?php echo t('payment_management.description'); ?>',
    no_data: '<?php echo t('payment_management.no_data'); ?>',
    loading: '<?php echo t('payment_management.loading'); ?>',
    connection_error: '<?php echo t('payment_management.connection_error'); ?>',
    load_error: '<?php echo t('payment_management.load_error'); ?>',
    payment_no: '<?php echo t('payment_management.payment_no'); ?>',
    invoice_no: '<?php echo t('payment_management.invoice_no'); ?>',
    total_invoice: '<?php echo t('payment_management.total_invoice'); ?>',
    total_paid: '<?php echo t('payment_management.total_paid'); ?>',
    balance: '<?php echo t('payment_management.balance'); ?>',
    
    // เพิ่ม key ที่ขาดไป
    month: '<?php echo t('payment_management.month'); ?>',
    total: '<?php echo t('payment_management.total'); ?>',
    total_amount: '<?php echo t('payment_management.total_amount'); ?>',
    
    swal: {
        incomplete_data: '<?php echo t('swal.incomplete_data'); ?>',
        missing_required_fields: '<?php echo t('swal.missing_required_fields'); ?>',
        confirm_save_title: '<?php echo t('swal.confirm_save_title'); ?>',
        confirm_save_text: '<?php echo t('swal.confirm_save_text'); ?>',
        save_button: '<?php echo t('swal.save_button'); ?>',
        cancel_button: '<?php echo t('swal.cancel_button'); ?>',
        saving_title: '<?php echo t('swal.saving_title'); ?>',
        saving_text: '<?php echo t('swal.saving_text'); ?>',
        save_success_title: '<?php echo t('swal.save_success_title'); ?>',
        save_success_text: '<?php echo t('swal.save_success_text'); ?>',
        save_error_title: '<?php echo t('swal.save_error_title'); ?>',
        save_error_text: '<?php echo t('swal.save_error_text'); ?>',
        connection_error_title: '<?php echo t('swal.connection_error_title'); ?>',
        connection_error_text: '<?php echo t('swal.connection_error_text'); ?>',
        
        // เพิ่มสำหรับ payment
        confirm_create_payment_title: '<?php echo t('swal.confirm_create_payment_title'); ?>',
        confirm_create_payment_text: '<?php echo t('swal.confirm_create_payment_text'); ?>',
        create_payment_button: '<?php echo t('swal.create_payment_button'); ?>',
        creating_payment_title: '<?php echo t('swal.creating_payment_title'); ?>',
        creating_payment_text: '<?php echo t('swal.creating_payment_text'); ?>',
        create_payment_success_title: '<?php echo t('swal.create_payment_success_title'); ?>',
        create_payment_success_text: '<?php echo t('swal.create_payment_success_text'); ?>',
        create_payment_error_title: '<?php echo t('swal.create_payment_error_title'); ?>',
        create_payment_error_text: '<?php echo t('swal.create_payment_error_text'); ?>',
        payment_exists_title: '<?php echo t('swal.payment_exists_title'); ?>',
        payment_exists_text: '<?php echo t('swal.payment_exists_text'); ?>',
        payment_exceed_balance_title: '<?php echo t('swal.payment_exceed_balance_title'); ?>',
        payment_exceed_balance_text: '<?php echo t('swal.payment_exceed_balance_text'); ?>',
    }
};
</script>

<script>
// ประกาศตัวแปร global
var allPayments = [];
var currentPaymentData = {};
var createPaymentModal;

// ฟังก์ชันแสดงผลตาราง
function renderTable(payments) {
    var paymentsTableBody = document.getElementById('paymentsTableBody');
    
    if (!paymentsTableBody) return;
    
    if (!payments || payments.length === 0) {
        paymentsTableBody.innerHTML = 
            '<tr>' +
            '<td colspan="9" class="text-center py-4">' +
            '<i class="fa-solid fa-circle-exclamation fa-2x text-muted mb-2"></i><br>' +
            translations.no_data +
            '</td>' +
            '</tr>';
        return;
    }
    
    var html = '';

    for (var i = 0; i < payments.length; i++) {
        var payment = payments[i];
        
        // ตรวจสอบและแปลงค่า status
        var statusBadge;
        if (payment.status === 'paid' || payment.status === true) {
            statusBadge = '<span class="badge bg-success">' + translations.paid + '</span>';
        } else {
            statusBadge = '<span class="badge bg-warning text-dark">' + translations.unpaid + '</span>';
        }
        
        // กำหนดคลาสสำหรับยอดคงเหลือ
        var balanceClass = 'balance-zero';
        if (payment.balance > 0) {
            balanceClass = 'balance-positive';
        } else if (payment.balance < 0) {
            balanceClass = 'balance-negative';
        }
        
        html += 
            '<tr>' +
            '<td class="text-center text-muted small">' + (i + 1) + '</td>' +
            '<td><div class="fw-bold small">' + (payment.inv_no || '') + '</div></td>' +
            '<td class="text-start d-none d-lg-table-cell"><div class="small">' + (payment.pcode || '-') + '</div></td>' +
            '<td class="text-center"><div class="small">' + String(payment.month).padStart(2, '0') + '/' + payment.year + '</div></td>' +
            '<td class="text-center">' + statusBadge + '</td>' +
            '<td class="text-end d-none d-lg-table-cell"><div class="small">' + parseFloat(payment.total_invoice).toFixed(2) + '</div></td>' +
            '<td class="text-end d-none d-lg-table-cell"><div class="small">' + parseFloat(payment.total_paid).toFixed(2) + '</div></td>' +
            '<td class="text-end d-none d-lg-table-cell"><div class="small">' + parseFloat(payment.balance).toFixed(2) + '</div></td>' +
            '<td class="text-center">' +
            '<div class="btn-group btn-group-sm" role="group">' +
            '<button class="btn btn-success btn-sm btn-create-payment" data-id="' + payment.id + '" title="' + translations.create_payment + '">' +
            '<i class="fa-brands fa-paypal"></i>' +
            '</button>' +
            '</div>' +
            '</td>' +
            '</tr>';
    }
    
    paymentsTableBody.innerHTML = html;
}

// ฟังก์ชัน loadPayments
function loadPayments() {
    var filterMonth = document.getElementById('filterMonth');
    var filterYear = document.getElementById('filterYear');
    var paymentsTableBody = document.getElementById('paymentsTableBody');
    
    var month = filterMonth ? filterMonth.value : '';
    var year = filterYear ? filterYear.value : '';
    
    if (!month || !year) {
        console.error('Month and year are required');
        return;
    }
    
    // แสดง loading
    if (paymentsTableBody) {
        paymentsTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> ' + translations.loading + '</td></tr>';
    }
    
    // เรียก AJAX
    fetch('/payments/get-by-period?month=' + month + '&year=' + year)
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            if (result.success) {
                allPayments = result.data;
                filterTable();
            
                // อัพเดทสถิติจากข้อมูลที่ได้จากเซิร์ฟเวอร์
                if (result.stats) {
                    updateStatsFromServer(result.stats);
                }
                
                var cardHeader = document.querySelector('.card .card-header .badge');
                if (cardHeader) {
                    cardHeader.textContent = result.count;
                }
            } else {
                if (paymentsTableBody) {
                    paymentsTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-danger">' + translations.load_error + '</td></tr>';
                }
            }
        })
        .catch(function(error) {
            console.error('Error loading payments:', error);
            if (paymentsTableBody) {
                paymentsTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-danger">' + translations.connection_error + '</td></tr>';
            }
        });
}

// ฟังก์ชันอัพเดทสถิติจากข้อมูลเซิร์ฟเวอร์
function updateStatsFromServer(stats) {
    if (!stats) return;
    
    var totalInvoicesEl = document.getElementById('totalInvoices');
    var paidCountEl = document.getElementById('paidCount');
    var unpaidCountEl = document.getElementById('unpaidCount');
    var totalPaidEl = document.getElementById('totalPaid');
    var totalInvoiceAmountEl = document.getElementById('totalInvoiceAmount');
    
    if (totalInvoicesEl) totalInvoicesEl.textContent = (stats.total_invoices || 0).toLocaleString();
    if (paidCountEl) paidCountEl.textContent = (stats.paid_count || 0).toLocaleString();
    if (unpaidCountEl) unpaidCountEl.textContent = (stats.not_paid_count || 0).toLocaleString();
    if (totalPaidEl) totalPaidEl.textContent = parseFloat(stats.total_paid || 0).toFixed(2);
    if (totalInvoiceAmountEl) totalInvoiceAmountEl.textContent = parseFloat(stats.total_invoice || 0).toFixed(2);
}

// Event listener เมื่อ DOM โหลดเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    // กำหนด modal
    var createPaymentModalElement = document.getElementById('createPaymentModal');
    if (createPaymentModalElement) {
        createPaymentModal = new bootstrap.Modal(createPaymentModalElement);
        
        // เพิ่ม event listener สำหรับเมื่อ modal ถูกซ่อน
        createPaymentModalElement.addEventListener('hidden.bs.modal', function() {
            // รีเซ็ตข้อมูลเมื่อ modal ถูกปิด
            currentPaymentData = {};
        });
    }
    
    var confirmCreatePaymentBtn = document.getElementById('confirmCreatePayment');
    if (confirmCreatePaymentBtn) {
        confirmCreatePaymentBtn.addEventListener('click', function() {
            confirmCreatePayment();
        });
    }
    
    // Event listener สำหรับปุ่มบันทึกการชำระเงิน
    document.addEventListener('click', function(event) {
        if (event.target.closest('.btn-create-payment')) {
            var paymentId = event.target.closest('.btn-create-payment').getAttribute('data-id');
            var payment = allPayments.find(function(p) {
                return p.id == paymentId;
            });

            if (payment) {
                openCreatePaymentModal(payment);
            }
        }
    });

    // โหลดข้อมูลครั้งแรก
    loadPayments();
});

// ฟังก์ชันยืนยันการบันทึกการชำระเงิน
function confirmCreatePayment() {
    if (!currentPaymentData.pcode) {
        showAlert('error', translations.swal.incomplete_data, translations.swal.missing_required_fields);
        return;
    }
    
    // ใช้ยอดคงเหลือทั้งหมดเป็นจำนวนเงินที่ต้องชำระ
    var totalBalance = parseFloat(document.getElementById('paymentTotalBalance').textContent) || 0;
    
    // อนุญาตให้บันทึกได้แม้ยอดเป็น 0
    Swal.fire({
        title: translations.swal.confirm_create_payment_title,
        html: translations.swal.confirm_create_payment_text + '<br>' +
              '<strong>' + currentPaymentData.pcode + '</strong> - ' + (currentPaymentData.pdesc || '') + '<br>' +
              translations.month + ' ' + currentPaymentData.month + '/' + currentPaymentData.year + '<br>' +
              '<span class="text-success">' + translations.total_amount + ': ' + totalBalance.toFixed(2) + ' บาท</span>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-brands fa-paypal me-1"></i>' + translations.swal.create_payment_button,
        cancelButtonText: '<i class="fas fa-times me-1"></i>' + translations.swal.cancel_button
    }).then(function(result) {
        if (result.isConfirmed) {
            createPayment(totalBalance);
        }
    });
}

// ฟังก์ชันแสดง Alert
function showAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonText: '<?php echo t('btns.ok'); ?>'
    });
}

// ฟังก์ชันเปิด Modal บันทึกการชำระเงิน
function openCreatePaymentModal(payment) {
    currentPaymentData = payment;
    
    // อัพเดทหัวข้อ modal พร้อมเดือน/ปี
    var monthNames = {
        '01': '<?php echo t('month.january'); ?>',
        '02': '<?php echo t('month.february'); ?>',
        '03': '<?php echo t('month.march'); ?>',
        '04': '<?php echo t('month.april'); ?>',
        '05': '<?php echo t('month.may'); ?>',
        '06': '<?php echo t('month.june'); ?>',
        '07': '<?php echo t('month.july'); ?>',
        '08': '<?php echo t('month.august'); ?>',
        '09': '<?php echo t('month.september'); ?>',
        '10': '<?php echo t('month.october'); ?>',
        '11': '<?php echo t('month.november'); ?>',
        '12': '<?php echo t('month.december'); ?>'
    };
    
    var month = String(payment.month).padStart(2, '0');
    var year = payment.year;
    
    // ตรวจสอบว่า element ยังอยู่ใน DOM ก่อนตั้งค่า
    var paymentMonthYearEl = document.getElementById('paymentMonthYear');
    if (paymentMonthYearEl) {
        paymentMonthYearEl.textContent = monthNames[month] + ' ' + year;
    }
    
    // เติมข้อมูลในฟอร์ม - ตรวจสอบ element ก่อนทุกครั้ง
    var paymentPcodeEl = document.getElementById('paymentPcode');
    var paymentInvNoEl = document.getElementById('paymentInvNo');
    
    if (paymentPcodeEl) paymentPcodeEl.textContent = payment.pcode || '';
    if (paymentInvNoEl) paymentInvNoEl.textContent = payment.inv_no || '-';
    
    // เติมข้อมูลใบแจ้งหนี้และยอดชำระแล้ว
    var invoiceElements = [
        'paymentElectricityInvoice', 'paymentWaterInvoice', 'paymentGarbageInvoice', 
        'paymentCommonAreaInvoice', 'paymentTotalInvoice',
        'paymentElectricityPaid', 'paymentWaterPaid', 'paymentGarbagePaid', 
        'paymentCommonAreaPaid', 'paymentTotalPaid'
    ];
    
    // ตั้งค่าค่าเริ่มต้นก่อน
    for (var i = 0; i < invoiceElements.length; i++) {
        var el = document.getElementById(invoiceElements[i]);
        if (el) el.textContent = '0.00';
    }
    
    // เติมข้อมูลจริง
    setElementText('paymentElectricityInvoice', parseFloat(payment.electricity || 0).toFixed(2));
    setElementText('paymentWaterInvoice', parseFloat(payment.water || 0).toFixed(2));
    setElementText('paymentGarbageInvoice', parseFloat(payment.garbage || 0).toFixed(2));
    setElementText('paymentCommonAreaInvoice', parseFloat(payment.common_area || 0).toFixed(2));
    setElementText('paymentTotalInvoice', parseFloat(payment.total_invoice || 0).toFixed(2));
    
    setElementText('paymentElectricityPaid', parseFloat(payment.paid_electricity || 0).toFixed(2));
    setElementText('paymentWaterPaid', parseFloat(payment.paid_water || 0).toFixed(2));
    setElementText('paymentGarbagePaid', parseFloat(payment.paid_garbage || 0).toFixed(2));
    setElementText('paymentCommonAreaPaid', parseFloat(payment.paid_common_area || 0).toFixed(2));
    setElementText('paymentTotalPaid', parseFloat(payment.total_paid || 0).toFixed(2));
    
    // คำนวณและแสดงยอดคงเหลือ (จำนวนที่ต้องชำระ)
    var electricityBalance = Math.max(0, parseFloat(payment.electricity || 0) - parseFloat(payment.paid_electricity || 0));
    var waterBalance = Math.max(0, parseFloat(payment.water || 0) - parseFloat(payment.paid_water || 0));
    var garbageBalance = Math.max(0, parseFloat(payment.garbage || 0) - parseFloat(payment.paid_garbage || 0));
    var commonAreaBalance = Math.max(0, parseFloat(payment.common_area || 0) - parseFloat(payment.paid_common_area || 0));
    var totalBalance = Math.max(0, parseFloat(payment.total_invoice || 0) - parseFloat(payment.total_paid || 0));
    
    setElementText('paymentElectricityBalance', electricityBalance.toFixed(2));
    setElementText('paymentWaterBalance', waterBalance.toFixed(2));
    setElementText('paymentGarbageBalance', garbageBalance.toFixed(2));
    setElementText('paymentCommonAreaBalance', commonAreaBalance.toFixed(2));
    setElementText('paymentTotalBalance', totalBalance.toFixed(2));
    
    // อัพเดทยอดรวมใน badge
    setElementText('totalBalanceAmount', totalBalance.toFixed(2));
    setElementText('confirmTotalAmount', totalBalance.toFixed(2));
    
    // ตรวจสอบสถานะการชำระเงิน
    checkPaymentStatus(payment);
    
    // แสดง Modal
    if (createPaymentModal) {
        createPaymentModal.show();
    }
}

// ฟังก์ชันช่วยสำหรับตั้งค่า text content อย่างปลอดภัย
function setElementText(elementId, text) {
    var element = document.getElementById(elementId);
    if (element) {
        element.textContent = text;
    }
}

// ตรวจสอบสถานะการชำระเงิน
function checkPaymentStatus(payment) {
    // ซ่อน alert เก่า
    var existingPaymentAlert = document.getElementById('existingPaymentAlert');
    if (existingPaymentAlert) {
        existingPaymentAlert.style.display = 'none';
    }
    
    // เรียก API เพื่อตรวจสอบว่ามีการชำระเงินแล้วหรือไม่
    fetch('/payments/check-payment?pcode=' + payment.pcode + '&month=' + payment.month + '&year=' + payment.year)
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            if (result.success && result.exists) {
                // มีการชำระเงินแล้ว
                var paymentData = result.payment;
                setElementText('existingPaymentNo', paymentData.payment_no + ' (' + paymentData.types + ')');
                
                var existingPaymentAlert = document.getElementById('existingPaymentAlert');
                if (existingPaymentAlert) {
                    existingPaymentAlert.style.display = 'block';
                }
                
                // ปิดปุ่มบันทึกการชำระเงินถ้าชำระครบแล้ว
                var confirmCreatePaymentBtn = document.getElementById('confirmCreatePayment');
                if (confirmCreatePaymentBtn && payment.status === 'paid') {
                    confirmCreatePaymentBtn.disabled = true;
                    confirmCreatePaymentBtn.innerHTML = '<i class="fas fa-ban me-1"></i>' + translations.existing_payment;
                }
                
                // อัพเดทสถานะในตาราง
                updatePaymentStatusInTable(paymentData.all_payments);
            } else {
                // ยังไม่มีการชำระเงิน
                var confirmCreatePaymentBtn = document.getElementById('confirmCreatePayment');
                if (confirmCreatePaymentBtn) {
                    confirmCreatePaymentBtn.disabled = false;
                    confirmCreatePaymentBtn.innerHTML = '<i class="fa-brands fa-paypal me-1"></i>' + translations.create_payment;
                }
                
                // อัพเดทสถานะเป็นพร้อมชำระ
                setElementHTML('paymentElectricityStatus', '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
                setElementHTML('paymentWaterStatus', '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
                setElementHTML('paymentGarbageStatus', '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
                setElementHTML('paymentCommonAreaStatus', '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
            }
        })
        .catch(function(error) {
            console.error('Error checking payment status:', error);
            var confirmCreatePaymentBtn = document.getElementById('confirmCreatePayment');
            if (confirmCreatePaymentBtn) {
                confirmCreatePaymentBtn.disabled = false;
            }
        });
}

// ฟังก์ชันช่วยสำหรับตั้งค่า HTML อย่างปลอดภัย
function setElementHTML(elementId, html) {
    var element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = html;
    }
}

// อัพเดทสถานะในตารางตามข้อมูลการชำระเงินที่มี
function updatePaymentStatusInTable(payments) {
    var statusMap = {};
    for (var i = 0; i < payments.length; i++) {
        var pay = payments[i];
        statusMap[pay.type] = '<span class="badge bg-secondary">' + translations.already_paid + '</span>';
    }
    
    setElementHTML('paymentElectricityStatus', statusMap['ค่าไฟ'] || '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
    setElementHTML('paymentWaterStatus', statusMap['ค่าน้ำ'] || '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
    setElementHTML('paymentGarbageStatus', statusMap['ค่าขยะ'] || '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
    setElementHTML('paymentCommonAreaStatus', statusMap['ค่าส่วนกลาง'] || '<span class="badge bg-success">' + translations.ready_to_pay + '</span>');
}

// บันทึกการชำระเงิน
function createPayment(totalAmount) {
    Swal.fire({
        title: translations.swal.creating_payment_title,
        text: translations.swal.creating_payment_text,
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: function() {
            Swal.showLoading();
        }
    });
    
    // คำนวณยอดคงเหลือแต่ละรายการ
    var electricityBalance = parseFloat(document.getElementById('paymentElectricityBalance').textContent) || 0;
    var waterBalance = parseFloat(document.getElementById('paymentWaterBalance').textContent) || 0;
    var garbageBalance = parseFloat(document.getElementById('paymentGarbageBalance').textContent) || 0;
    var commonAreaBalance = parseFloat(document.getElementById('paymentCommonAreaBalance').textContent) || 0;
    
    var formData = new FormData();
    formData.append('pcode', currentPaymentData.pcode);
    formData.append('month', currentPaymentData.month);
    formData.append('year', currentPaymentData.year);
    formData.append('electricity', electricityBalance);
    formData.append('water', waterBalance);
    formData.append('garbage', garbageBalance);
    formData.append('common_area', commonAreaBalance);
    formData.append('inv_no', currentPaymentData.inv_no);
    
    fetch('/payments/create-payment', {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(function(result) {
        if (result.success) {
            Swal.fire({
                title: translations.swal.create_payment_success_title,
                html: translations.swal.create_payment_success_text + ' <strong>' + currentPaymentData.pcode + '</strong><br>' +
                      '<strong><?php echo t('payment_management.payment_no'); ?>: ' + result.payment_no + '</strong><br>' +
                      result.created_count + ' <?php echo t('payment_management.items'); ?><br>' +
                      '<?php echo t('payment_management.total_amount'); ?>: <span class="text-success">' + parseFloat(result.total_paid).toFixed(2) + ' บาท</span>',
                icon: 'success',
                confirmButtonText: '<?php echo t('btns.ok'); ?>'
            }).then(function() {
                if (createPaymentModal) {
                    createPaymentModal.hide();
                }
                loadPayments();
            });
        } else {
            Swal.fire({
                title: translations.swal.create_payment_error_title,
                text: result.message || translations.swal.create_payment_error_text,
                icon: 'error',
                confirmButtonText: '<?php echo t('btns.ok'); ?>'
            });
        }
    })
    .catch(function(error) {
        console.error('Error creating payment:', error);
        Swal.fire({
            title: translations.swal.connection_error_title,
            text: translations.swal.connection_error_text + error.message,
            icon: 'error',
            confirmButtonText: '<?php echo t('btns.ok'); ?>'
        });
    });
}

// ฟังก์ชันกรองตาราง
function filterTable() {
    var searchPayment = document.getElementById('searchPayment');
    var filterStatus = document.getElementById('filterStatus');
    var paymentsTableBody = document.getElementById('paymentsTableBody');
    
    var searchValue = searchPayment ? searchPayment.value.toLowerCase().trim() : '';
    var statusValue = filterStatus ? filterStatus.value : '';
    
    var filteredPayments = allPayments;
    
    // กรองตามการค้นหา
    if (searchValue) {
        filteredPayments = filteredPayments.filter(function(payment) {
            var pcode = (payment.pcode || '').toLowerCase();
            var invNo = (payment.inv_no || '').toLowerCase();
            return pcode.indexOf(searchValue) !== -1 || invNo.indexOf(searchValue) !== -1;
        });
    }
    
    // กรองตามสถานะ
    if (statusValue) {
        filteredPayments = filteredPayments.filter(function(payment) {
            return payment.status === statusValue;
        });
    }
    
    // แสดงผลตาราง
    renderTable(filteredPayments);
}

// Event listeners สำหรับการกรอง
document.addEventListener('DOMContentLoaded', function() {
    var searchPayment = document.getElementById('searchPayment');
    var resetSearch = document.getElementById('resetSearch');
    var filterMonth = document.getElementById('filterMonth');
    var filterYear = document.getElementById('filterYear');
    var filterStatus = document.getElementById('filterStatus');

    if (searchPayment) {
        searchPayment.addEventListener('input', filterTable);
    }
    
    if (resetSearch) {
        resetSearch.addEventListener('click', function() {
            if (searchPayment) searchPayment.value = '';
            filterTable();
        });
    }
    
    if (filterMonth) {
        filterMonth.addEventListener('change', loadPayments);
    }
    
    if (filterYear) {
        filterYear.addEventListener('change', loadPayments);
    }
    
    if (filterStatus) {
        filterStatus.addEventListener('change', filterTable);
    }
});
</script>