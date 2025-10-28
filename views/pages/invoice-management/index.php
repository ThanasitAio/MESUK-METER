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
    <div class="col-6 col-md-2 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted"><?php echo t('invoice_management.total_invoices'); ?></div>
                <h4 class="mb-0 text-primary" id="totalInvoices"><?php echo isset($stats['total_saved']) ? $stats['total_saved'] : '0'; ?></h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted"><?php echo t('invoice_management.open_invoices'); ?></div>
                <h4 class="mb-0 text-success" id="openCount"><?php echo isset($stats['open_count']) ? $stats['open_count'] : '0'; ?></h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted"><?php echo t('invoice_management.closed_invoices'); ?></div>
                <h4 class="mb-0 text-warning" id="closedCount"><?php echo isset($stats['not_opened_count']) ? $stats['not_opened_count'] : '0'; ?></h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
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
                                           placeholder="<?php echo t('invoice_management.pcode'); ?>...">
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
                                    <option value=""><?php echo t('invoice_management.all'); ?></option>
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
                                    <th width="120" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.product_code'); ?></th>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.month'); ?>/<?php echo t('meter_management.year'); ?></th>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.status'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.electricity'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.water'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.garbage'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.common_area'); ?></th>
                                    <th width="80" class="text-right" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.total'); ?></th>
                                    <th width="60" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="metersTableBody">
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin"></i> กำลังโหลดข้อมูล...
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
                    <i class="fas fa-file-invoice me-2"></i>เปิดใบแจ้งหนี้
                    <span id="invoiceMonthYear" class="month-year-gradient"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <h6 class="text-muted">รหัสสินค้า</h6>
                        <h7 id="invoicePcode" class="fw-bold text-primary"></h7>
                    </div>
                    <div class="col-md-9">
                        <h6 class="text-muted">คำอธิบาย</h6>
                        <h7 id="invoicePdesc" class="fw-bold"></h7>
                    </div>
                </div>
                
                <!-- แสดงเลขที่เอกสาร (ถ้ามีแล้ว) -->
                <div class="alert alert-info" id="existingInvoiceAlert" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    มีใบแจ้งหนี้แล้ว: <strong id="existingInvNo"></strong>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="40%" class="text-center">รายการ</th>
                                <th width="30%" class="text-center">จำนวนเงิน</th>
                                <th width="30%" class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">ค่าไฟฟ้า</td>
                                <td class="text-end" id="invoiceElectricity">0.00</td>
                                <td class="text-center" id="invoiceElectricityStatus">
                                    <span class="badge bg-success">พร้อมเปิด</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">ค่าน้ำ</td>
                                <td class="text-end" id="invoiceWater">0.00</td>
                                <td class="text-center" id="invoiceWaterStatus">
                                    <span class="badge bg-success">พร้อมเปิด</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">ค่าขยะ</td>
                                <td class="text-end" id="invoiceGarbage">0.00</td>
                                <td class="text-center" id="invoiceGarbageStatus">
                                    <span class="badge bg-success">พร้อมเปิด</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">ค่าส่วนกลาง</td>
                                <td class="text-end" id="invoiceCommonArea">0.00</td>
                                <td class="text-center" id="invoiceCommonAreaStatus">
                                    <span class="badge bg-success">พร้อมเปิด</span>
                                </td>
                            </tr>
                            <tr class="table-primary fw-bold">
                                <td>รวมทั้งสิ้น</td>
                                <td class="text-end" id="invoiceTotal">0.00</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">รวม</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>ปิด
                </button>
                <button type="button" class="btn btn-primary" id="confirmCreateInvoice">
                    <i class="fas fa-file-invoice me-1"></i>เปิดใบแจ้งหนี้
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
    }
};
</script>
<script>
// ประกาศตัวแปร allMeters ในระดับ global
let allMeters = []; // เก็บข้อมูลมิเตอร์ทั้งหมดที่โหลดจาก AJAX
let currentInvoiceData = {};
let createInvoiceModal;
let editMeterModal;
// ฟังก์ชันคำนวณและแสดงสถิติ
function updateStats(meters) {
    if (!meters || meters.length === 0) {
        // รีเซ็ตสถิติทั้งหมดเป็น 0
        document.getElementById('totalInvoices').textContent = '0';
        document.getElementById('openCount').textContent = '0';
        document.getElementById('closedCount').textContent = '0';
        document.getElementById('totalPrice').textContent = '0.00';
        return;
    }

    // คำนวณสถิติ
    let stats = {
        total: meters.length,
        saved: 0,
        unsaved: 0,
        electricity: 0,
        water: 0,
        garbage: 0,
    };

    meters.forEach(meter => {
        console.log(meters)
        // นับสถานะ
        if (meter.status === 'saved' || meter.status === true || meter.status === 1 || meter.status === '1') {
            stats.saved++;
        } else {
            stats.unsaved++;
        }

        // รวมค่าใช้จ่าย
        stats.electricity += parseFloat(meter.electricity) || 0;
        stats.water += parseFloat(meter.water) || 0;
        stats.garbage += parseFloat(meter.garbage) || 0;
        stats.common_area += parseFloat(meter.common_area) || 0;
    });

    // คำนวณรวมทั้งหมด
    stats.grandTotal = stats.electricity + stats.water + stats.garbage + stats.common_area;

    // อัพเดทแสดงผล
    document.getElementById('totalInvoices').textContent = stats.total.toLocaleString();
    document.getElementById('openCount').textContent = stats.saved.toLocaleString();
    document.getElementById('closedCount').textContent = stats.unsaved.toLocaleString();
    document.getElementById('totalPrice').textContent = stats.electricity.toFixed(2);
}

// ฟังก์ชันกรองตาราง (จาก allMeters)
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
    
    // อัพเดทสถิติ
    updateStats(filteredMeters);
    
    // แสดงผลตาราง
    renderTable(filteredMeters);
}

// ฟังก์ชันแสดงผลตาราง
// ฟังก์ชันแสดงผลตาราง
function renderTable(meters) {
    const metersTableBody = document.getElementById('metersTableBody');
    
    if (!metersTableBody) return;
    
    if (!meters || meters.length === 0) {
        metersTableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4"><i class="fa-solid fa-circle-exclamation fa-2x text-muted mb-2"></i><br>ไม่พบข้อมูลมิเตอร์</td></tr>';
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
                        <button class="btn btn-primary btn-sm btn-create-invoice" data-id="${meter.id}" title="เปิดใบแจ้งหนี้">
                            <i class="fas fa-file-invoice"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    metersTableBody.innerHTML = html;
}

// ฟังก์ชัน loadMeters อัพเดท
// ฟังก์ชัน loadMeters อัพเดท
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
        metersTableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> กำลังโหลดข้อมูล...</td></tr>';
    }
    
    // รีเซ็ตสถิติขณะโหลด
    updateStats([]);
    
    // เรียก AJAX
    fetch(`/invoices/get-by-period?month=${month}&year=${year}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                allMeters = result.data;
                filterTable(); // กรองและแสดงผล
            
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
                    metersTableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4 text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
                }
            }
        })
        .catch(error => {
            console.error('Error loading meters:', error);
            if (metersTableBody) {
                metersTableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4 text-danger">เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>';
            }
        });
}

// ฟังก์ชันอัพเดทสถิติจากข้อมูลเซิร์ฟเวอร์
function updateStatsFromServer(stats) {
    if (!stats) return;
    
    // อัพเดทสถิติจากข้อมูลที่คำนวณในเซิร์ฟเวอร์
    document.getElementById('totalInvoices').textContent = (stats.total_saved || 0).toLocaleString();
    document.getElementById('openCount').textContent = (stats.open_count || 0).toLocaleString();
    document.getElementById('closedCount').textContent = (stats.not_opened_count || 0).toLocaleString();
    document.getElementById('totalPrice').textContent = parseFloat(stats.total_price || 0).toFixed(2);
}

// ฟังก์ชันคำนวณและแสดงสถิติ (สำหรับกรองข้อมูลในฝั่ง client)
function updateStats(meters) {
    if (!meters || meters.length === 0) {
        // ไม่ต้องรีเซ็ตสถิติทั้งหมดเป็น 0 เพราะอาจมีข้อมูลจากเซิร์ฟเวอร์
        return;
    }

    // คำนวณสถิติเฉพาะสำหรับข้อมูลที่กรองแล้ว
    let stats = {
        total: meters.length,
        saved: 0,
        unsaved: 0,
        electricity: 0,
        water: 0,
        garbage: 0,
        common_area: 0
    };

    meters.forEach(meter => {
        // นับสถานะ
        if (meter.status === 'saved' || meter.status === true || meter.status === 1 || meter.status === '1') {
            stats.saved++;
        } else {
            stats.unsaved++;
        }

        // รวมค่าใช้จ่าย
        stats.electricity += parseFloat(meter.electricity) || 0;
        stats.water += parseFloat(meter.water) || 0;
        stats.garbage += parseFloat(meter.garbage) || 0;
        stats.common_area += parseFloat(meter.common_area) || 0;
    });

    // คำนวณรวมทั้งหมด
    stats.grandTotal = stats.electricity + stats.water + stats.garbage + stats.common_area;

    // อัพเดทแสดงผลเฉพาะส่วนที่เกี่ยวข้องกับการกรอง
    // หมายเหตุ: เราไม่เปลี่ยน openCount, closedCount, totalPrice หลักจากเซิร์ฟเวอร์
    document.getElementById('totalInvoices').textContent = stats.total.toLocaleString();
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

// ฟังก์ชันยืนยันการสร้างใบแจ้งหนี้ (แยกออกมา)
function confirmCreateInvoice() {
    if (!currentInvoiceData.pcode) {
        showAlert('error', 'เกิดข้อผิดพลาด', 'ไม่พบข้อมูลใบแจ้งหนี้');
        return;
    }
    
    // ใช้ SwitchAlert2 สำหรับการยืนยัน
    Swal.fire({
        title: 'ยืนยันการเปิดใบแจ้งหนี้',
        html: `คุณต้องการเปิดใบแจ้งหนี้สำหรับ<br>
              <strong>${currentInvoiceData.pcode}</strong> - ${currentInvoiceData.pdesc}<br>
              เดือน ${currentInvoiceData.month}/${currentInvoiceData.year}<br>
              <span class="text-primary">รวมทั้งสิ้น ${document.getElementById('invoiceTotal').textContent} บาท</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-file-invoice me-1"></i>เปิดใบแจ้งหนี้',
        cancelButtonText: '<i class="fas fa-times me-1"></i>ยกเลิก'
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
        confirmButtonText: 'ตกลง'
    });
}

// เปิด Modal เมื่อคลิกปุ่มเปิดใบแจ้งหนี้
document.addEventListener('click', function(event) {
    // ตรวจสอบว่าคลิกที่ปุ่มเปิดใบแจ้งหนี้
    if (event.target.closest('.btn-create-invoice')) {
        const meterId = event.target.closest('.btn-create-invoice').dataset.id;
        const meter = allMeters.find(m => m.id == meterId);

        if (meter) {
            openCreateInvoiceModal(meter);
        }
    }
});

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
                document.getElementById('existingInvNo').textContent = result.invoice.inv_no;
                document.getElementById('existingInvoiceAlert').style.display = 'block';
                
                // ปิดปุ่มสร้างใบแจ้งหนี้
                document.getElementById('confirmCreateInvoice').disabled = true;
                document.getElementById('confirmCreateInvoice').innerHTML = '<i class="fas fa-ban me-1"></i>มีใบแจ้งหนี้แล้ว';
                
                // อัพเดทสถานะ
                document.getElementById('invoiceElectricityStatus').innerHTML = '<span class="badge bg-secondary">เปิดแล้ว</span>';
                document.getElementById('invoiceWaterStatus').innerHTML = '<span class="badge bg-secondary">เปิดแล้ว</span>';
                document.getElementById('invoiceGarbageStatus').innerHTML = '<span class="badge bg-secondary">เปิดแล้ว</span>';
                document.getElementById('invoiceCommonAreaStatus').innerHTML = '<span class="badge bg-secondary">เปิดแล้ว</span>';
            } else {
                // ยังไม่มีใบแจ้งหนี้
                document.getElementById('confirmCreateInvoice').disabled = false;
                document.getElementById('confirmCreateInvoice').innerHTML = '<i class="fas fa-file-invoice me-1"></i>เปิดใบแจ้งหนี้';
                
                // อัพเดทสถานะ
                document.getElementById('invoiceElectricityStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
                document.getElementById('invoiceWaterStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
                document.getElementById('invoiceGarbageStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
                document.getElementById('invoiceCommonAreaStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
            }
        })
        .catch(error => {
            console.error('Error checking invoice status:', error);
            // หากตรวจสอบไม่ได้ ให้แสดงสถานะพร้อมเปิด
            document.getElementById('confirmCreateInvoice').disabled = false;
        });
}

// สร้างใบแจ้งหนี้
function createInvoice() {
    // แสดง loading
    Swal.fire({
        title: 'กำลังเปิดใบแจ้งหนี้...',
        text: 'กรุณารอสักครู่',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // ส่งข้อมูลไปยัง server
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
                title: 'เปิดใบแจ้งหนี้สำเร็จ!',
                html: `สร้างใบแจ้งหนี้สำหรับ <strong>${currentInvoiceData.pcode}</strong> เรียบร้อยแล้ว<br>
                      <strong>เลขที่เอกสาร: ${result.inv_no}</strong><br>
                      จำนวนเงินรวม: <span class="text-success">${parseFloat(result.total_price).toFixed(2)} บาท</span>`,
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                if (createInvoiceModal) {
                    createInvoiceModal.hide();
                }
                loadMeters(); // โหลดข้อมูลใหม่เพื่ออัพเดทสถานะ
            });
        } else {
            Swal.fire({
                title: 'เปิดใบแจ้งหนี้ไม่สำเร็จ',
                text: result.message || 'เกิดข้อผิดพลาดในการสร้างใบแจ้งหนี้',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    })
    .catch(error => {
        console.error('Error creating invoice:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด',
            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message,
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
}

// สร้างใบแจ้งหนี้
function createInvoice() {
    // แสดง loading
    Swal.fire({
        title: 'กำลังเปิดใบแจ้งหนี้...',
        text: 'กรุณารอสักครู่',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // ส่งข้อมูลไปยัง server
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
            title: 'เปิดใบแจ้งหนี้สำเร็จ!',
            html: `สร้างใบแจ้งหนี้สำหรับ <strong>${currentInvoiceData.pcode}</strong> เรียบร้อยแล้ว<br>
                  <strong>เลขที่เอกสาร: ${result.inv_no}</strong><br>
                  จำนวนเงินรวม: <span class="text-success">${parseFloat(result.total_price).toFixed(2)} บาท</span>`,
            icon: 'success',
            confirmButtonText: 'ตกลง'
        }).then(function() {
            if (createInvoiceModal) {
                createInvoiceModal.hide();
            }
            loadMeters(); // โหลดข้อมูลใหม่เพื่ออัพเดทสถานะ
        });
    } else {
        Swal.fire({
            title: 'เปิดใบแจ้งหนี้ไม่สำเร็จ',
            text: result.message || 'เกิดข้อผิดพลาดในการสร้างใบแจ้งหนี้',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    }
})
    .catch(error => {
        console.error('Error creating invoice:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด',
            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message,
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
}

const editMeterForm = document.getElementById('editMeterForm');
const saveMeterChanges = document.getElementById('saveMeterChanges');

let currentMeterData = {};
// เปิด Modal เมื่อคลิกปุ่มแก้ไข
// เปิด Modal เมื่อคลิกปุ่มเปิดใบแจ้งหนี้
document.addEventListener('click', function(event) {
    if (event.target.closest('.btn-create-invoice')) {
        const meterId = event.target.closest('.btn-create-invoice').dataset.id;
        const meter = allMeters.find(m => m.id == meterId);

        if (meter) {
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
            
            // ตรวจสอบสถานะ (ถ้ามีใบแจ้งหนี้แล้วให้แสดงสถานะ)
            checkInvoiceStatus(meter);
            
            // แสดง Modal
            createInvoiceModal.show();
        }
    }
});

// ยืนยันการสร้างใบแจ้งหนี้
document.getElementById('confirmCreateInvoice').addEventListener('click', function() {
    if (!currentInvoiceData.pcode) {
        showAlert('error', 'เกิดข้อผิดพลาด', 'ไม่พบข้อมูลใบแจ้งหนี้');
        return;
    }
    
    // ใช้ SwitchAlert2 สำหรับการยืนยัน
    Swal.fire({
        title: 'ยืนยันการเปิดใบแจ้งหนี้',
        html: `คุณต้องการเปิดใบแจ้งหนี้สำหรับ<br>
              <strong>${currentInvoiceData.pcode}</strong> - ${currentInvoiceData.pdesc}<br>
              เดือน ${currentInvoiceData.month}/${currentInvoiceData.year}<br>
              <span class="text-primary">รวมทั้งสิ้น ${document.getElementById('invoiceTotal').textContent} บาท</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-file-invoice me-1"></i>เปิดใบแจ้งหนี้',
        cancelButtonText: '<i class="fas fa-times me-1"></i>ยกเลิก'
    }).then(function(result) {
        if (result.isConfirmed) {
            createInvoice();
        }
    });
});

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
                document.getElementById('confirmCreateInvoice').innerHTML = '<i class="fas fa-ban me-1"></i>มีใบแจ้งหนี้แล้ว';
                
                // อัพเดทสถานะในตาราง
                updateInvoiceStatusInTable(invoice.all_invoices);
            } else {
                // ยังไม่มีใบแจ้งหนี้
                document.getElementById('confirmCreateInvoice').disabled = false;
                document.getElementById('confirmCreateInvoice').innerHTML = '<i class="fas fa-file-invoice me-1"></i>เปิดใบแจ้งหนี้';
                
                // อัพเดทสถานะเป็นพร้อมเปิด
                document.getElementById('invoiceElectricityStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
                document.getElementById('invoiceWaterStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
                document.getElementById('invoiceGarbageStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
                document.getElementById('invoiceCommonAreaStatus').innerHTML = '<span class="badge bg-success">พร้อมเปิด</span>';
            }
        })
        .catch(error => {
            console.error('Error checking invoice status:', error);
            // หากตรวจสอบไม่ได้ ให้แสดงสถานะพร้อมเปิด
            document.getElementById('confirmCreateInvoice').disabled = false;
        });
}

// อัพเดทสถานะในตารางตามข้อมูลใบแจ้งหนี้ที่มี
function updateInvoiceStatusInTable(invoices) {
    // สร้าง mapping ระหว่าง type และสถานะ
    const statusMap = {};
    invoices.forEach(inv => {
        statusMap[inv.type] = '<span class="badge bg-secondary">เปิดแล้ว</span>';
    });
    
    // อัพเดทสถานะแต่ละรายการ
    document.getElementById('invoiceElectricityStatus').innerHTML = statusMap['ค่าไฟ'] || '<span class="badge bg-success">พร้อมเปิด</span>';
    document.getElementById('invoiceWaterStatus').innerHTML = statusMap['ค่าน้ำ'] || '<span class="badge bg-success">พร้อมเปิด</span>';
    document.getElementById('invoiceGarbageStatus').innerHTML = statusMap['ค่าขยะ'] || '<span class="badge bg-success">พร้อมเปิด</span>';
    document.getElementById('invoiceCommonAreaStatus').innerHTML = statusMap['ค่าส่วนกลาง'] || '<span class="badge bg-success">พร้อมเปิด</span>';
}

// สร้างใบแจ้งหนี้
function createInvoice() {
    // แสดง loading
    Swal.fire({
        title: 'กำลังเปิดใบแจ้งหนี้...',
        text: 'กรุณารอสักครู่',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // ส่งข้อมูลไปยัง server
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
                title: 'เปิดใบแจ้งหนี้สำเร็จ!',
                html: `สร้างใบแจ้งหนี้สำหรับ <strong>${currentInvoiceData.pcode}</strong> เรียบร้อยแล้ว<br>
                      <strong>เลขที่เอกสาร: ${result.inv_no}</strong><br>
                      จำนวน ${result.created_count} รายการ<br>
                      จำนวนเงินรวม: <span class="text-success">${parseFloat(result.total_price).toFixed(2)} บาท</span>`,
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                if (createInvoiceModal) {
                    createInvoiceModal.hide();
                }
                loadMeters(); // โหลดข้อมูลใหม่เพื่ออัพเดทสถานะ
            });
        } else {
            Swal.fire({
                title: 'เปิดใบแจ้งหนี้ไม่สำเร็จ',
                text: result.message || 'เกิดข้อผิดพลาดในการสร้างใบแจ้งหนี้',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    })
    .catch(error => {
        console.error('Error creating invoice:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด',
            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message,
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
}

// ฟังก์ชันโหลดรูปภาพจาก API
function loadMeterImages(pcode, month, year) {
    // รีเซ็ต preview
    resetImagePreviews();
    
    // เรียก API เพื่อดึงข้อมูลรูปภาพ
    fetch(`/meter-management/get-meter-images?pcode=${pcode}&month=${month}&year=${year}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // แสดงรูปภาพไฟฟ้า
                if (result.data.electricityImage) {
                    displayExistingImage('electricityImagePreview', result.data.electricityImage, 'electricity');
                    document.getElementById('currentElectricityImage').value = result.data.electricityImage;
                    document.getElementById('clearElectricityBtn').style.display = 'block';
                }
                
                // แสดงรูปภาพน้ำ
                if (result.data.waterImage) {
                    displayExistingImage('waterImagePreview', result.data.waterImage, 'water');
                    document.getElementById('currentWaterImage').value = result.data.waterImage;
                    document.getElementById('clearWaterBtn').style.display = 'block';
                }
            }
        })
        .catch(error => {
            console.error('Error loading meter images:', error);
        });
}

// ฟังก์ชันรีเซ็ต preview
function resetImagePreviews() {
    const electricityPreview = document.getElementById('electricityImagePreview');
    const waterPreview = document.getElementById('waterImagePreview');
    
    electricityPreview.innerHTML = '<div class="placeholder text-center text-muted"><i class="fas fa-image fa-2x mb-1"></i><div style="font-size: 0.9em;">ไม่มีรูปภาพ</div></div>';
    waterPreview.innerHTML = '<div class="placeholder text-center text-muted"><i class="fas fa-image fa-2x mb-1"></i><div style="font-size: 0.9em;">ไม่มีรูปภาพ</div></div>';
    
    document.getElementById('currentElectricityImage').value = '';
    document.getElementById('currentWaterImage').value = '';
    document.getElementById('clearElectricityBtn').style.display = 'none';
    document.getElementById('clearWaterBtn').style.display = 'none';
    
    // รีเซ็ต input file
    document.getElementById('img_electricity').value = '';
    document.getElementById('img_water').value = '';
}

// ฟังก์ชันแสดงรูปภาพที่มีอยู่
function displayExistingImage(previewId, imagePath, type) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = `
        <img src="${imagePath}" alt="รูปมิเตอร์ ${type}" 
             class="img-fluid preview-clickable" 
             style="max-width: 100%; max-height: 100%; cursor:pointer; object-fit: contain;"
             onclick="openImageModal('${imagePath}')">
    `;
}

// ฟังก์ชัน preview รูปภาพไฟฟ้า
function previewElectricityImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('electricityImagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" 
                     class="img-fluid preview-clickable" 
                     style="max-width: 100%; max-height: 100%; cursor:pointer; object-fit: contain;"
                     onclick="openImageModal('${e.target.result}')">
            `;
            document.getElementById('clearElectricityBtn').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        // ถ้าไม่มีไฟล์ใหม่ แต่มีรูปเดิม ให้แสดงรูปเดิม
        const currentImage = document.getElementById('currentElectricityImage').value;
        if (currentImage) {
            displayExistingImage('electricityImagePreview', currentImage, 'electricity');
        } else {
            preview.innerHTML = '<div class="placeholder text-center text-muted"><i class="fas fa-image fa-2x mb-1"></i><div style="font-size: 0.9em;">ไม่มีรูปภาพ</div></div>';
            document.getElementById('clearElectricityBtn').style.display = 'none';
        }
    }
}

// ฟังก์ชัน preview รูปภาพน้ำ
function previewWaterImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('waterImagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" 
                     class="img-fluid preview-clickable" 
                     style="max-width: 100%; max-height: 100%; cursor:pointer; object-fit: contain;"
                     onclick="openImageModal('${e.target.result}')">
            `;
            document.getElementById('clearWaterBtn').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        // ถ้าไม่มีไฟล์ใหม่ แต่มีรูปเดิม ให้แสดงรูปเดิม
        const currentImage = document.getElementById('currentWaterImage').value;
        if (currentImage) {
            displayExistingImage('waterImagePreview', currentImage, 'water');
        } else {
            preview.innerHTML = '<div class="placeholder text-center text-muted"><i class="fas fa-image fa-2x mb-1"></i><div style="font-size: 0.9em;">ไม่มีรูปภาพ</div></div>';
            document.getElementById('clearWaterBtn').style.display = 'none';
        }
    }
}

// ฟังก์ชันลบรูปภาพไฟฟ้า
function clearElectricityImage() {
    document.getElementById('electricityImagePreview').innerHTML = '<div class="placeholder text-center text-muted"><i class="fas fa-image fa-2x mb-1"></i><div style="font-size: 0.9em;">ไม่มีรูปภาพ</div></div>';
    document.getElementById('img_electricity').value = '';
    document.getElementById('currentElectricityImage').value = '';
    document.getElementById('clearElectricityBtn').style.display = 'none';
}

// ฟังก์ชันลบรูปภาพน้ำ
function clearWaterImage() {
    document.getElementById('waterImagePreview').innerHTML = '<div class="placeholder text-center text-muted"><i class="fas fa-image fa-2x mb-1"></i><div style="font-size: 0.9em;">ไม่มีรูปภาพ</div></div>';
    document.getElementById('img_water').value = '';
    document.getElementById('currentWaterImage').value = '';
    document.getElementById('clearWaterBtn').style.display = 'none';
}

// Modal popup functions
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modalImg.src = src;
    modal.style.display = 'flex';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
}

// ปิด modal เมื่อคลิกนอกภาพ
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) closeImageModal();
});

// รีเซ็ตฟอร์มเมื่อปิด modal
document.getElementById('editMeterModal').addEventListener('hidden.bs.modal', function () {
    resetImagePreviews();
    currentMeterData = {};
});




// บันทึกข้อมูลเมื่อคลิกปุ่มบันทึก
saveMeterChanges.addEventListener('click', function() {
    // รวบรวมข้อมูลจากฟอร์ม
    const pcode = document.getElementById('meterPcode').value;
    const month = document.getElementById('meterMonth').value;
    const year = document.getElementById('meterYear').value;
    const electricity = document.getElementById('readingValueElectricity').value;
    const water = document.getElementById('readingValueWater').value;
    const garbage = document.getElementById('readingValueGarbage').value;
    const common_area = document.getElementById('readingValueCommonArea').value;
    const remark = document.getElementById('meterRemark').value;
    const currentElectricityImage = document.getElementById('currentElectricityImage').value;
    const currentWaterImage = document.getElementById('currentWaterImage').value;

    // รับไฟล์รูปภาพ
    const imgElectricity = document.getElementById('img_electricity').files[0];
    const imgWater = document.getElementById('img_water').files[0];

    // ตรวจสอบข้อมูลที่จำเป็น
    if (!pcode || !month || !year) {
        Swal.fire({
            title: translations.swal.incomplete_data,
            text: translations.swal.incomplete_data_text,
            icon: 'warning'
        });
        return;
    }

    // แสดงการยืนยันการบันทึก
    Swal.fire({
        title: translations.swal.confirm_save_title,
        text: translations.swal.confirm_save_text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: translations.swal.save_button,
        cancelButtonText: translations.swal.cancel_button
    }).then(function(result) {
        if (result.isConfirmed) {
            // สร้าง FormData
            const formData = new FormData();
            formData.append('pcode', pcode);
            formData.append('month', month);
            formData.append('year', year);
            formData.append('electricity', electricity || '0');
            formData.append('water', water || '0');
            formData.append('garbage', garbage || '0');
            formData.append('common_area', common_area || '0');
            formData.append('remark', remark || '');
            formData.append('current_electricity_image', currentElectricityImage || '');
            formData.append('current_water_image', currentWaterImage || '');

            // เพิ่มไฟล์รูปภาพถ้ามี
            if (imgElectricity) {
                formData.append('img_electricity', imgElectricity);
            }
            if (imgWater) {
                formData.append('img_water', imgWater);
            }

            // แสดง loading ใน SweetAlert
            Swal.fire({
                title: translations.swal.saving_data_title,
                text: translations.swal.saving_data_text,
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // ส่งข้อมูลไปยัง server
            fetch('/meter-management/save-meter', {
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
                        title: translations.swal.save_success_title,
                        text: translations.swal.save_success_text,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        editMeterModal.hide();
                        loadMeters();
                    });
                } else {
                    var errorMsg = result.errors ? result.errors.join('<br>') : (result.message || translations.swal.save_error_text);
                    Swal.fire({
                        title: translations.swal.save_error_title,
                        html: errorMsg,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error saving meter:', error);
                Swal.fire({
                    title: translations.swal.connection_error_title,
                    text: translations.swal.connection_error_text + error.message,
                    icon: 'error'
                });
            });
        }
    });
});
</script>