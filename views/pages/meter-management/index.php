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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header -->
            <?php       
            echo pageHeader(t('meter_management.title'), '', '', 'fas fa-users-cog'); 
            ?>

            <!-- สถิติ -->
            <div class="row">
                <div class="col-6 col-md-2 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.total_meters'); ?></div>
                            <h4 class="mb-0 text-primary"><?php echo number_format(isset($stats['total']) ? $stats['total'] : 0); ?></h4>
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
                                    <i class="fas fa-search"></i> <?php echo t('meter_management.search'); ?>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="searchUser" 
                                           placeholder="<?php echo t('meter_management.meter_doc'); ?>...">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2">
                            <div class="mb-0">
                                <label for="filterMonth" class="form-label fw-bold small"><?php echo t('meter_management.month'); ?></label>
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
                                <label for="filterYear" class="form-label fw-bold small"><?php echo t('meter_management.year'); ?></label>
                                <select class="select-beast form-select form-select-sm" id="filterYear">
                                    <?php foreach ($years as $year): ?>
                                        <option value="<?php echo $year; ?>" <?php echo date('Y') == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2">
                            <div class="mb-0">
                                <label for="filterStatus" class="form-label fw-bold small"><?php echo t('meter_management.status'); ?></label>
                                <select class="select-beast form-select form-select-sm" id="filterStatus">
                                    <option value=""><?php echo t('meter_management.all'); ?></option>
                                    <option value="saved"><?php echo t('meter_management.saved'); ?></option>
                                    <option value="unsaved"><?php echo t('meter_management.unsaved'); ?></option>
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
                    t('meter_management.meter_list'), 
                    0, 
                    'fa-solid fa-jug-detergent'
                ); 
                ?>
                
                <div class="card-body p-0">
                    <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive']; ?>; position: relative; z-index: 1;">
                        <table class="table table-sm table-hover mb-0" id="MetersTable">
                            <thead class="table-light" >
                                <tr>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                    <th width="120" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.product_code'); ?></th>
                                    <th width="80" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.month'); ?>/<?php echo t('meter_management.year'); ?></th>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.status'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.electricity'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.water'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.garbage'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.common_area'); ?></th>
                                    <th width="80" class="text-right" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.total'); ?></th>
                                    <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="metersTableBody">
                                <tr>
                                    <td colspan="10" class="text-center py-4">
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

<!-- Modal สำหรับแก้ไขข้อมูล -->
<div class="modal fade" id="editMeterModal" tabindex="-1" aria-labelledby="editMeterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMeterModalLabel">แก้ไขข้อมูลมิเตอร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMeterForm">
                    <!-- ข้อมูลมิเตอร์ -->
                
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-3 mb-3">
                            <label for="meterPcode" class="form-label">รหัสสินค้า</label>
                            <input type="text" class="form-control form-control-sm" id="meterPcode" name="pcode" readonly disabled>
                        </div>
                        <div class="col-4 col-sm-4 col-md-3 mb-3">
                            <label for="meterMonth" class="form-label">เดือน</label>
                            <input type="number" class="form-control form-control-sm" id="meterMonth" name="month" readonly disabled>
                        </div>
                        <div class="col-4 col-sm-4 col-md-3 mb-3">
                            <label for="meterYear" class="form-label">ปี</label>
                            <input type="number" class="form-control form-control-sm" id="meterYear" name="year" readonly disabled>
                        </div>
                        
                        
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="previousMeterWater" class="form-label">เลขมิเตอร์ครั้งก่อน - ไฟ</label>
                            <input type="text" class="form-control form-control-sm" id="previousMeterElectricity" name="previous_meter_electricity" readonly disabled>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="previousMeterElectricity" class="form-label">เลขมิเตอร์ครั้งก่อน - น้ำ</label>
                            <input type="text" class="form-control form-control-sm" id="previousMeterWater" name="previous_meter_water" readonly disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueElectricity" class="form-label">เลขมิเตอร์ล่าสุด - ไฟ</label>
                            <input type="number" class="form-control form-control-sm" id="readingValueElectricity" name="electricity">
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueWater" class="form-label">เลขมิเตอร์ล่าสุด - น้ำ</label>
                            <input type="number" class="form-control form-control-sm" id="readingValueWater" name="water">
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueGarbage" class="form-label">ค่าขยะ</label>
                            <input type="number" class="form-control form-control-sm" id="readingValueGarbage" name="garbage">
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueCommonArea" class="form-label">ค่าส่วนกลาง</label>
                            <input type="number" class="form-control form-control-sm" id="readingValueCommonArea" name="common_area">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="meterImgElectricity" class="form-label">รูปมิเตอร์ - ไฟ</label>
                            <input type="file" class="form-control form-control-sm" id="meterImgElectricity" name="img_electricity">
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="meterImgWater" class="form-label">รูปมิเตอร์ - น้ำ</label>
                            <input type="file" class="form-control form-control-sm" id="meterImgWater" name="img_water">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="meterRemark" class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" id="meterRemark" name="remark" rows="3"></textarea>
                        </div>
                    </div>
                    <hr>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="saveMeterChanges">บันทึก</button>
            </div>
        </div>
    </div>
</div>


<script>
// ส่งค่าภาษาจาก PHP ไปยัง JavaScript
const translations = {
    saved: '<?php echo t('meter_management.saved'); ?>',
    unsaved: '<?php echo t('meter_management.unsaved'); ?>'
};
</script>
<script>
// ประกาศตัวแปร allMeters ในระดับ global
let allMeters = []; // เก็บข้อมูลมิเตอร์ทั้งหมดที่โหลดจาก AJAX

// ประกาศฟังก์ชัน loadMeters เป็น global function
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
        metersTableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> กำลังโหลดข้อมูล...</td></tr>';
    }
    
    // เรียก AJAX
    fetch(`/meters/get-by-period?month=${month}&year=${year}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                allMeters = result.data;
                filterTable(); // กรองและแสดงผล
            
                const cardHeader = document.querySelector('.card .card-header .badge');
                if (cardHeader) {
                    cardHeader.textContent = result.count;
                }
            } else {
                if (metersTableBody) {
                    metersTableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
                }
            }
        })
        .catch(error => {
            console.error('Error loading meters:', error);
            if (metersTableBody) {
                metersTableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-danger">เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>';
            }
        });
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
    
    // แสดงผล
    renderTable(filteredMeters);
}

// ฟังก์ชันแสดงผลตาราง
function renderTable(meters) {
    const metersTableBody = document.getElementById('metersTableBody');
    
    if (!metersTableBody) return;
    
    if (!meters || meters.length === 0) {
        metersTableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4"><i class="fa-solid fa-circle-exclamation fa-2x text-muted mb-2"></i><br>ไม่พบข้อมูลมิเตอร์</td></tr>';
        return;
    }
    
    let html = '';

    meters.forEach((meter, index) => {
        // ตรวจสอบและแปลงค่า status
        let statusBadge;
        if (meter.status === 'saved' || meter.status === true || meter.status === 1 || meter.status === '1') {
            statusBadge = '<span class="badge bg-success">' + translations.saved + '</span>';
        } else {
            statusBadge = '<span class="badge bg-warning text-dark">' + translations.unsaved + '</span>';
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
                        <button class="btn btn-warning btn-sm btn-edit-meter" data-id="${meter.id}" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    metersTableBody.innerHTML = html;
}

// JavaScript สำหรับการกรองแบบ AJAX
document.addEventListener('DOMContentLoaded', function() {

    editMeterModal = new bootstrap.Modal(document.getElementById('editMeterModal'));
    const searchUser = document.getElementById('searchUser');
    const resetSearch = document.getElementById('resetSearch');
    const filterMonth = document.getElementById('filterMonth');
    const filterYear = document.getElementById('filterYear');
    const filterStatus = document.getElementById('filterStatus');

    // Event listeners
    if (searchUser) {
        searchUser.addEventListener('input', filterTable);
        searchUser.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterTable();
            }
        });
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
    
    // โหลดข้อมูลครั้งแรก
    loadMeters();
});

let editMeterModal;
const editMeterForm = document.getElementById('editMeterForm');
const saveMeterChanges = document.getElementById('saveMeterChanges');

// เปิด Modal เมื่อคลิกปุ่มแก้ไข
document.addEventListener('click', function(event) {
    if (event.target.closest('.btn-edit-meter')) {
        const meterId = event.target.closest('.btn-edit-meter').dataset.id;
        const meter = allMeters.find(m => m.id == meterId);

        if (meter) {
            // เติมข้อมูลในฟอร์ม
            document.getElementById('meterPcode').value = meter.pcode || '';
            document.getElementById('meterMonth').value = meter.month || '';
            document.getElementById('meterYear').value = meter.year || '';
            document.getElementById('previousMeterWater').value = meter.waterMeterNumberBefore || '0';
            document.getElementById('previousMeterElectricity').value = meter.electricityMeterNumberBefore || '0';
            document.getElementById('readingValueElectricity').value = meter.electricity || '';
            document.getElementById('readingValueWater').value = meter.water || '';
            document.getElementById('readingValueGarbage').value = meter.garbage || '';
            document.getElementById('readingValueCommonArea').value = meter.common_area || '';
            document.getElementById('meterRemark').value = meter.remark || '';
            
            // ล้างค่าเดิม
            document.getElementById('readingValueElectricity').value = '';
            document.getElementById('readingValueWater').value = '';
            document.getElementById('readingValueGarbage').value = '';
            document.getElementById('readingValueCommonArea').value = '';

            // แสดง Modal
            editMeterModal.show();
        }
    }
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

    // ตรวจสอบข้อมูลที่จำเป็น
    if (!pcode || !month || !year) {
        alert('กรุณากรอกข้อมูล รหัสสินค้า, เดือน, และปี');
        return;
    }

    // สร้าง FormData
    const formData = new FormData();
    formData.append('pcode', pcode);
    formData.append('month', month);
    formData.append('year', year);
    formData.append('electricity', electricity || '0');
    formData.append('water', water || '0');
    formData.append('garbage', garbage || '0');
    formData.append('common_area', common_area || '0');

    // ส่งข้อมูลไปยัง server
    fetch('/meter-management/save-meter', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(result => {
        console.log('Server response:', result);
        if (result.success) {
            alert('บันทึกข้อมูลสำเร็จ');
            editMeterModal.hide();
            loadMeters(); // โหลดข้อมูลใหม่ - ตอนนี้สามารถเรียกใช้ได้แล้ว
        } else {
            alert('เกิดข้อผิดพลาด: ' + (result.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error saving meter:', error);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
    });
});
</script>