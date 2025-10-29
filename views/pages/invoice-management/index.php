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
#MetersTable thead {
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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header -->
            <?php       
            echo pageHeader(t('invoice_management.title'), '', '', 'fas fa-users-cog'); 
            ?>

            <!-- สถิติ -->
<div class="row" id="statsContainer">
    <div class="col-6 col-md-4 col-xl-2 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted"><?php echo t('invoice_management.total_invoices'); ?></div>
                <h4 class="mb-0 text-primary" id="totalInvoices"><?php echo isset($stats['total_saved']) ? $stats['total_saved'] : '0'; ?></h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted"><?php echo t('invoice_management.open_invoices'); ?></div>
                <h4 class="mb-0 text-success" id="openCount"><?php echo isset($stats['open_count']) ? $stats['open_count'] : '0'; ?></h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted"><?php echo t('invoice_management.closed_invoices'); ?></div>
                <h4 class="mb-0 text-warning" id="closedCount"><?php echo isset($stats['not_opened_count']) ? $stats['not_opened_count'] : '0'; ?></h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted"><?php echo t('invoice_management.open_invoices_price'); ?></div>
                <h4 class="mb-0 text-info" id="totalPrice"><?php echo isset($stats['total_price']) ? number_format($stats['total_price'], 2) : '0.00'; ?></h4>
            </div>
        </div>
    </div>
</div>


            <!-- ตัวกรองข้อมูล -->
            <div class="card <?php echo $headerConfig['classes']['filter_card']; ?>" style="position: relative; z-index: 100; overflow: visible;">
                <div class="card-body py-2" style="overflow: visible;">
                    <div class="row align-items-end" style="overflow: visible;">
                        <div class="col-md-6 col-lg-4">
                            <div class="mb-0">
                                <label for="searchUser" class="form-label fw-bold small">
                                    <i class="fas fa-search"></i> <?php echo t('invoice_management.search'); ?>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="searchUser" 
                                           placeholder="<?php echo t('invoice_management.pcode_placeholder'); ?>">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2">
                            <div class="mb-0">
                                <label for="filterMonth" class="form-label fw-bold small"><?php echo t('invoice_management.month'); ?></label>
                                <select class="select-beast form-select form-select-sm" id="filterMonth">
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
                        <div class="col-md-3 col-lg-2">
                            <div class="mb-0">
                                <label for="filterYear" class="form-label fw-bold small"><?php echo t('invoice_management.year'); ?></label>
                                <select class="select-beast form-select form-select-sm" id="filterYear">
                                    <?php foreach ($years as $year): ?>
                                        <option value="<?php echo $year; ?>" <?php echo date('Y') == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2">
                            <div class="mb-0">
                                <label for="filterStatus" class="form-label fw-bold small"><?php echo t('invoice_management.status'); ?></label>
                                <select class="select-beast form-select form-select-sm" id="filterStatus">
                                    <option value=""><?php echo t('selection.all'); ?></option>
                                    <option value="open"><?php echo t('invoice_management.open'); ?></option>
                                    <option value="closed"><?php echo t('invoice_management.closed'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตารางผู้ใช้ -->
            <div class="card" style="position: relative; z-index: 1;">
                <?php 
                // จำนวนจะถูกอัพเดทโดย JavaScript
                echo cardHeader(
                    t('invoice_management.invoice_list'), 
                    0, 
                    'fa-solid fa-file-invoice'
                ); 
                ?>
                
                <div class="card-body p-0">
                    <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive']; ?>; position: relative; z-index: 1;">
                        <table class="table table-sm table-hover mb-0" id="MetersTable">
                            <thead class="table-light" >
                                <tr>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                    <th width="120" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.pcode'); ?></th>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.month'); ?>/<?php echo t('invoice_management.year'); ?></th>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.status'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.electricity'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.water'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.garbage'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.common_area'); ?></th>
                                    <th width="80" class="text-right" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.total'); ?></th>
                                    <th width="60" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('invoice_management.actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="metersTableBody">
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin"></i> <?php echo t('invoice_management.loading'); ?>
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

<!-- Modal สำหรับเปิดใบแจ้งหนี้ -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createInvoiceModalLabel">
                    <i class="fas fa-file-invoice me-2"></i><?php echo t('invoice_management.create_invoice'); ?>
                    <span id="invoiceMonthYear" class="month-year-gradient"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <h6 class="text-muted"><?php echo t('invoice_management.pcode'); ?></h6>
                        <h7 id="invoicePcode" class="fw-bold text-primary"></h7>
                    </div>
                    <div class="col-md-9">
                        <h6 class="text-muted"><?php echo t('invoice_management.description'); ?></h6>
                        <h7 id="invoicePdesc" class="fw-bold"></h7>
                    </div>
                </div>
                
                <!-- แสดงเลขที่เอกสาร (ถ้ามีแล้ว) -->
                <div class="alert alert-info" id="existingInvoiceAlert" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo t('invoice_management.existing_invoice'); ?>: <strong id="existingInvNo"></strong>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="40%" class="text-center"><?php echo t('invoice_management.item'); ?></th>
                                <th width="30%" class="text-center"><?php echo t('invoice_management.amount'); ?></th>
                                <th width="30%" class="text-center"><?php echo t('invoice_management.status'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold"><?php echo t('invoice_management.electricity'); ?></td>
                                <td class="text-end" id="invoiceElectricity">0.00</td>
                                <td class="text-center" id="invoiceElectricityStatus">
                                    <span class="badge bg-success"><?php echo t('invoice_management.ready_to_open'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><?php echo t('invoice_management.water'); ?></td>
                                <td class="text-end" id="invoiceWater">0.00</td>
                                <td class="text-center" id="invoiceWaterStatus">
                                    <span class="badge bg-success"><?php echo t('invoice_management.ready_to_open'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><?php echo t('invoice_management.garbage'); ?></td>
                                <td class="text-end" id="invoiceGarbage">0.00</td>
                                <td class="text-center" id="invoiceGarbageStatus">
                                    <span class="badge bg-success"><?php echo t('invoice_management.ready_to_open'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><?php echo t('invoice_management.common_area'); ?></td>
                                <td class="text-end" id="invoiceCommonArea">0.00</td>
                                <td class="text-center" id="invoiceCommonAreaStatus">
                                    <span class="badge bg-success"><?php echo t('invoice_management.ready_to_open'); ?></span>
                                </td>
                            </tr>
                            <tr class="table-primary fw-bold">
                                <td><?php echo t('invoice_management.total'); ?></td>
                                <td class="text-end" id="invoiceTotal">0.00</td>
                                <td class="text-center">
                                    <span class="badge bg-primary"><?php echo t('invoice_management.total'); ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i><?php echo t('btns.cancel'); ?>
                </button>
                <button type="button" class="btn btn-primary" id="confirmCreateInvoice">
                    <i class="fas fa-file-invoice me-1"></i><?php echo t('invoice_management.create_invoice'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแสดงรูปภาพเต็มหน้าจอ -->
<div id="imageModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.9); align-items:center; justify-content:center;">
    <span style="position:absolute; top:20px; right:30px; color:#fff; font-size:2em; cursor:pointer; z-index:10000;" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" src="" style="max-width:90vw; max-height:90vh; border-radius:1em; box-shadow:0 0 20px #000; display:block; margin:auto;">
</div>


<script>
// ส่งค่าภาษาจาก PHP ไปยัง JavaScript
const translations = {
    open: '<?php echo t('invoice_management.open'); ?>',
    closed: '<?php echo t('invoice_management.closed'); ?>',
    create_invoice: '<?php echo t('invoice_management.create_invoice'); ?>',
    existing_invoice: '<?php echo t('invoice_management.existing_invoice'); ?>',
    ready_to_open: '<?php echo t('invoice_management.ready_to_open'); ?>',
    already_opened: '<?php echo t('invoice_management.already_opened'); ?>',
    item: '<?php echo t('invoice_management.item'); ?>',
    amount: '<?php echo t('invoice_management.amount'); ?>',
    description: '<?php echo t('invoice_management.description'); ?>',
    no_data: '<?php echo t('invoice_management.no_data'); ?>',
    loading: '<?php echo t('invoice_management.loading'); ?>',
    connection_error: '<?php echo t('invoice_management.connection_error'); ?>',
    load_error: '<?php echo t('invoice_management.load_error'); ?>',
    
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
        
        // เพิ่มสำหรับ invoice
        confirm_create_invoice_title: '<?php echo t('swal.confirm_create_invoice_title'); ?>',
        confirm_create_invoice_text: '<?php echo t('swal.confirm_create_invoice_text'); ?>',
        create_invoice_button: '<?php echo t('swal.create_invoice_button'); ?>',
        creating_invoice_title: '<?php echo t('swal.creating_invoice_title'); ?>',
        creating_invoice_text: '<?php echo t('swal.creating_invoice_text'); ?>',
        create_invoice_success_title: '<?php echo t('swal.create_invoice_success_title'); ?>',
        create_invoice_success_text: '<?php echo t('swal.create_invoice_success_text'); ?>',
        create_invoice_error_title: '<?php echo t('swal.create_invoice_error_title'); ?>',
        create_invoice_error_text: '<?php echo t('swal.create_invoice_error_text'); ?>',
        invoice_exists_title: '<?php echo t('swal.invoice_exists_title'); ?>',
        invoice_exists_text: '<?php echo t('swal.invoice_exists_text'); ?>',
    }
};
</script>

<script>
// ประกาศตัวแปร allMeters ในระดับ global
let allMeters = []; // เก็บข้อมูลมิเตอร์ทั้งหมดที่โหลดจาก AJAX
let currentInvoiceData = {};
let createInvoiceModal;

// ฟังก์ชันแสดงผลตาราง
function renderTable(meters) {
    const metersTableBody = document.getElementById('metersTableBody');
    
    if (!metersTableBody) return;
    
    if (!meters || meters.length === 0) {
        metersTableBody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center py-4">
                    <i class="fa-solid fa-circle-exclamation fa-2x text-muted mb-2"></i><br>
                    ${translations.no_data}
                </td>
            </tr>`;
        return;
    }
    
    let html = '';

    meters.forEach((meter, index) => {
        // ตรวจสอบและแปลงค่า status
        let statusBadge;
        if (meter.status === 'open' || meter.status === true || meter.status === 1 || meter.status === '1') {
            statusBadge = '<span class="badge bg-success">' + translations.open + '</span>';
        } else {
            statusBadge = '<span class="badge bg-warning text-dark">' + translations.closed + '</span>';
        }
        
        html += `
            <tr>
                <td class="text-center text-muted small">${index + 1}</td>
                <td><div class="fw-bold small">${meter.pcode || ''}</div></td>
                <td class="text-center"><div class="small">${String(meter.month).padStart(2, '0')}/${meter.year}</div></td>
                <td class="text-center">${statusBadge}</td>
                <td class="text-end d-none d-lg-table-cell"><div class="small">${parseFloat(meter.electricity).toFixed(2)}</div></td>
                <td class="text-end d-none d-lg-table-cell"><div class="small">${parseFloat(meter.water).toFixed(2)}</div></td>
                <td class="text-end d-none d-lg-table-cell"><div class="small">${parseFloat(meter.garbage).toFixed(2)}</div></td>
                <td class="text-end d-none d-lg-table-cell"><div class="small">${parseFloat(meter.common_area).toFixed(2)}</div></td>
                <td class="text-end">${parseFloat(meter.total).toFixed(2)}</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-primary btn-sm btn-create-invoice" data-id="${meter.id}" title="${translations.create_invoice}">
                            <i class="fas fa-file-invoice"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    metersTableBody.innerHTML = html;
}

// ฟังก์ชัน loadMeters
function loadMeters() {
    const filterMonth = document.getElementById('filterMonth');
    const filterYear = document.getElementById('filterYear');
    const metersTableBody = document.getElementById('metersTableBody');
    
    const month = filterMonth ? filterMonth.value : '';
    const year = filterYear ? filterYear.value : '';
    
    if (!month || !year) {
        console.error('Month and year are required');
        return;
    }
    
    // แสดง loading
    if (metersTableBody) {
        metersTableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> ' + translations.loading + '</td></tr>';
    }
    
    // เรียก AJAX
    fetch(`/invoices/get-by-period?month=${month}&year=${year}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                allMeters = result.data;
                filterTable();
            
                // อัพเดทสถิติจากข้อมูลที่ได้จากเซิร์ฟเวอร์
                if (result.stats) {
                    updateStatsFromServer(result.stats);
                }
                
                const cardHeader = document.querySelector('.card .card-header .badge');
                if (cardHeader) {
                    cardHeader.textContent = result.count;
                }
            } else {
                if (metersTableBody) {
                    metersTableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4 text-danger">' + translations.load_error + '</td></tr>';
                }
            }
        })
        .catch(error => {
            console.error('Error loading meters:', error);
            if (metersTableBody) {
                metersTableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4 text-danger">' + translations.connection_error + '</td></tr>';
            }
        });
}

// ฟังก์ชันอัพเดทสถิติจากข้อมูลเซิร์ฟเวอร์
function updateStatsFromServer(stats) {
    if (!stats) return;
    
    document.getElementById('totalInvoices').textContent = (stats.total_saved || 0).toLocaleString();
    document.getElementById('openCount').textContent = (stats.open_count || 0).toLocaleString();
    document.getElementById('closedCount').textContent = (stats.not_opened_count || 0).toLocaleString();
    document.getElementById('totalPrice').textContent = parseFloat(stats.total_price || 0).toFixed(2);
}

// แทนที่โค้ดเดิมในส่วน event listener
document.addEventListener('DOMContentLoaded', function() {
    // กำหนด modal
    const createInvoiceModalElement = document.getElementById('createInvoiceModal');
    if (createInvoiceModalElement) {
        createInvoiceModal = new bootstrap.Modal(createInvoiceModalElement);
    }
    
    const confirmCreateInvoiceBtn = document.getElementById('confirmCreateInvoice');
    if (confirmCreateInvoiceBtn) {
        confirmCreateInvoiceBtn.addEventListener('click', function() {
            confirmCreateInvoice();
        });
    }
    
    // Event listener สำหรับปุ่มเปิดใบแจ้งหนี้ (ใช้ event delegation)
    document.addEventListener('click', function(event) {
        if (event.target.closest('.btn-create-invoice')) {
            const meterId = event.target.closest('.btn-create-invoice').dataset.id;
            const meter = allMeters.find(m => m.id == meterId);

            if (meter) {
                openCreateInvoiceModal(meter);
            }
        }
    });

    // โหลดข้อมูลครั้งแรก
    loadMeters();
});

// ฟังก์ชันยืนยันการสร้างใบแจ้งหนี้
function confirmCreateInvoice() {
    if (!currentInvoiceData.pcode) {
        showAlert('error', translations.swal.incomplete_data, translations.swal.missing_required_fields);
        return;
    }
    
    Swal.fire({
        title: translations.swal.confirm_create_invoice_title,
        html: `${translations.swal.confirm_create_invoice_text}<br>
              <strong>${currentInvoiceData.pcode}</strong> - ${currentInvoiceData.pdesc}<br>
              ${translations.month} ${currentInvoiceData.month}/${currentInvoiceData.year}<br>
              <span class="text-primary">${translations.total} ${document.getElementById('invoiceTotal').textContent} <?php echo t('currency.baht'); ?></span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-file-invoice me-1"></i>' + translations.swal.create_invoice_button,
        cancelButtonText: '<i class="fas fa-times me-1"></i>' + translations.swal.cancel_button
    }).then(function(result) {
        if (result.isConfirmed) {
            createInvoice();
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

// ฟังก์ชันเปิด Modal สร้างใบแจ้งหนี้
function openCreateInvoiceModal(meter) {
    currentInvoiceData = meter;
    
    // อัพเดทหัวข้อ modal พร้อมเดือน/ปี
    const monthNames = {
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
    
    const month = String(meter.month).padStart(2, '0');
    const year = meter.year;
    document.getElementById('invoiceMonthYear').textContent = `${monthNames[month]} ${year}`;
    
    // เติมข้อมูลในฟอร์ม
    document.getElementById('invoicePcode').textContent = meter.pcode || '';
    document.getElementById('invoicePdesc').textContent = meter.pdesc || '';
    document.getElementById('invoiceElectricity').textContent = parseFloat(meter.electricity || 0).toFixed(2);
    document.getElementById('invoiceWater').textContent = parseFloat(meter.water || 0).toFixed(2);
    document.getElementById('invoiceGarbage').textContent = parseFloat(meter.garbage || 0).toFixed(2);
    document.getElementById('invoiceCommonArea').textContent = parseFloat(meter.common_area || 0).toFixed(2);
    
    // คำนวณรวม
    const total = parseFloat(meter.electricity || 0) + 
                 parseFloat(meter.water || 0) + 
                 parseFloat(meter.garbage || 0) + 
                 parseFloat(meter.common_area || 0);
    document.getElementById('invoiceTotal').textContent = total.toFixed(2);
    
    // ตรวจสอบสถานะใบแจ้งหนี้
    checkInvoiceStatus(meter);
    
    // แสดง Modal
    if (createInvoiceModal) {
        createInvoiceModal.show();
    }
}

// ตรวจสอบสถานะใบแจ้งหนี้
function checkInvoiceStatus(meter) {
    // ซ่อน alert เก่า
    document.getElementById('existingInvoiceAlert').style.display = 'none';
    
    // เรียก API เพื่อตรวจสอบว่ามีใบแจ้งหนี้แล้วหรือไม่
    fetch(`/invoices/check-invoice?pcode=${meter.pcode}&month=${meter.month}&year=${meter.year}`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.exists) {
                // มีใบแจ้งหนี้แล้ว
                const invoice = result.invoice;
                document.getElementById('existingInvNo').textContent = invoice.inv_no + ' (' + invoice.types + ')';
                document.getElementById('existingInvoiceAlert').style.display = 'block';
                
                // ปิดปุ่มสร้างใบแจ้งหนี้
                document.getElementById('confirmCreateInvoice').disabled = true;
                document.getElementById('confirmCreateInvoice').innerHTML = '<i class="fas fa-ban me-1"></i>' + translations.existing_invoice;
                
                // อัพเดทสถานะในตาราง
                updateInvoiceStatusInTable(invoice.all_invoices);
            } else {
                // ยังไม่มีใบแจ้งหนี้
                document.getElementById('confirmCreateInvoice').disabled = false;
                document.getElementById('confirmCreateInvoice').innerHTML = '<i class="fas fa-file-invoice me-1"></i>' + translations.create_invoice;
                
                // อัพเดทสถานะเป็นพร้อมเปิด
                document.getElementById('invoiceElectricityStatus').innerHTML = '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
                document.getElementById('invoiceWaterStatus').innerHTML = '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
                document.getElementById('invoiceGarbageStatus').innerHTML = '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
                document.getElementById('invoiceCommonAreaStatus').innerHTML = '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
            }
        })
        .catch(error => {
            console.error('Error checking invoice status:', error);
            document.getElementById('confirmCreateInvoice').disabled = false;
        });
}

// อัพเดทสถานะในตารางตามข้อมูลใบแจ้งหนี้ที่มี
function updateInvoiceStatusInTable(invoices) {
    const statusMap = {};
    invoices.forEach(inv => {
        statusMap[inv.type] = '<span class="badge bg-secondary">' + translations.already_opened + '</span>';
    });
    
    document.getElementById('invoiceElectricityStatus').innerHTML = statusMap['ค่าไฟ'] || '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
    document.getElementById('invoiceWaterStatus').innerHTML = statusMap['ค่าน้ำ'] || '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
    document.getElementById('invoiceGarbageStatus').innerHTML = statusMap['ค่าขยะ'] || '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
    document.getElementById('invoiceCommonAreaStatus').innerHTML = statusMap['ค่าส่วนกลาง'] || '<span class="badge bg-success">' + translations.ready_to_open + '</span>';
}

// สร้างใบแจ้งหนี้
function createInvoice() {
    Swal.fire({
        title: translations.swal.creating_invoice_title,
        text: translations.swal.creating_invoice_text,
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const formData = new FormData();
    formData.append('pcode', currentInvoiceData.pcode);
    formData.append('month', currentInvoiceData.month);
    formData.append('year', currentInvoiceData.year);
    formData.append('electricity', currentInvoiceData.electricity || 0);
    formData.append('water', currentInvoiceData.water || 0);
    formData.append('garbage', currentInvoiceData.garbage || 0);
    formData.append('common_area', currentInvoiceData.common_area || 0);
    
    fetch('/invoices/create-invoice', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            Swal.fire({
                title: translations.swal.create_invoice_success_title,
                html: `${translations.swal.create_invoice_success_text} <strong>${currentInvoiceData.pcode}</strong><br>
                      <strong><?php echo t('invoice_management.invoice_no'); ?>: ${result.inv_no}</strong><br>
                      ${result.created_count} <?php echo t('invoice_management.items'); ?><br>
                      <?php echo t('invoice_management.total_amount'); ?>: <span class="text-success">${parseFloat(result.total_price).toFixed(2)} <?php echo t('currency.baht'); ?></span>`,
                icon: 'success',
                confirmButtonText: '<?php echo t('btns.ok'); ?>'
            }).then(function() {
                if (createInvoiceModal) {
                    createInvoiceModal.hide();
                }
                loadMeters();
            });
        } else {
            Swal.fire({
                title: translations.swal.create_invoice_error_title,
                text: result.message || translations.swal.create_invoice_error_text,
                icon: 'error',
                confirmButtonText: '<?php echo t('btns.ok'); ?>'
            });
        }
    })
    .catch(error => {
        console.error('Error creating invoice:', error);
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
    const searchUser = document.getElementById('searchUser');
    const filterStatus = document.getElementById('filterStatus');
    const metersTableBody = document.getElementById('metersTableBody');
    
    const searchValue = searchUser ? searchUser.value.toLowerCase().trim() : '';
    const statusValue = filterStatus ? filterStatus.value : '';
    
    let filteredMeters = allMeters;
    
    // กรองตามการค้นหา
    if (searchValue) {
        filteredMeters = filteredMeters.filter(meter => {
            const pcode = (meter.pcode || '').toLowerCase();
            const pdesc = (meter.pdesc || '').toLowerCase();
            return pcode.includes(searchValue) || pdesc.includes(searchValue);
        });
    }
    
    // กรองตามสถานะ
    if (statusValue) {
        filteredMeters = filteredMeters.filter(meter => meter.status === statusValue);
    }
    
    // แสดงผลตาราง
    renderTable(filteredMeters);
}

// Event listeners สำหรับการกรอง
document.addEventListener('DOMContentLoaded', function() {
    const searchUser = document.getElementById('searchUser');
    const resetSearch = document.getElementById('resetSearch');
    const filterMonth = document.getElementById('filterMonth');
    const filterYear = document.getElementById('filterYear');
    const filterStatus = document.getElementById('filterStatus');

    if (searchUser) {
        searchUser.addEventListener('input', filterTable);
    }
    
    if (resetSearch) {
        resetSearch.addEventListener('click', function() {
            if (searchUser) searchUser.value = '';
            filterTable();
        });
    }
    
    if (filterMonth) {
        filterMonth.addEventListener('change', loadMeters);
    }
    
    if (filterYear) {
        filterYear.addEventListener('change', loadMeters);
    }
    
    if (filterStatus) {
        filterStatus.addEventListener('change', filterTable);
    }
});
</script>