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
            echo pageHeader(t('meter_management.title'), '', '', 'fa-solid fa-jug-detergent'); 
            ?>

                        <!-- สถิติ -->
            <div class="row" id="statsContainer">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.total_meters'); ?></div>
                            <h4 class="mb-0 text-primary" id="totalMeters">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.stats.saved_count'); ?></div>
                            <h4 class="mb-0 text-success" id="savedCount">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.stats.unsaved_count'); ?></div>
                            <h4 class="mb-0 text-warning" id="unsavedCount">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.stats.total_electricity'); ?></div>
                            <h4 class="mb-0 text-info" id="totalElectricity">0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.stats.total_water'); ?></div>
                            <h4 class="mb-0 text-info" id="totalWater">0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.stats.total_garbage'); ?></div>
                            <h4 class="mb-0 text-secondary" id="totalGarbage">0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.stats.total_common_area'); ?></div>
                            <h4 class="mb-0 text-secondary" id="totalCommonArea">0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('meter_management.stats.grand_total'); ?></div>
                            <h4 class="mb-0 text-danger" id="grandTotal">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตัวกรองข้อมูล -->
            <div class="card <?php echo $headerConfig['classes']['filter_card']; ?>" style="z-index: 100; overflow: visible;">
                <div class="card-body py-2" style="overflow: visible;">
                    <div class="row align-items-end" style="overflow: visible;">
                        <!-- ช่องค้นหา - ใช้ความกว้างเต็มในมือถือ -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="mb-0">
                                <label for="searchUser" class="form-label fw-bold small mb-1">
                                    <i class="fas fa-search"></i> <?php echo t('meter_management.search'); ?>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm mb-1" id="searchUser" 
                                        placeholder="<?php echo t('meter_management.meter_doc'); ?>...">
                                    <button type="button" class="btn btn-outline-secondary btn-sm mb-1" id="resetSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- กลุ่มเดือน/ปี/สถานะ - ใช้ flex และ grid ร่วมกัน -->
                        <div class="col-12 col-md-6 col-lg-8">
                            <div class="row g-2">
                                <!-- เดือน -->
                                <div class="col-4 col-md-4 col-lg-2">
                                    <div class="mb-0">
                                        <label for="filterMonth" class="form-label fw-bold small mb-1"><?php echo t('meter_management.month'); ?></label>
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
                                        <label for="filterYear" class="form-label fw-bold small mb-1"><?php echo t('meter_management.year'); ?></label>
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
                                        <label for="filterStatus" class="form-label fw-bold small mb-1"><?php echo t('meter_management.status'); ?></label>
                                        <select class="select-beast form-select form-select-sm mb-1" id="filterStatus">
                                            <option value=""><?php echo t('meter_management.all'); ?></option>
                                            <option value="saved"><?php echo t('meter_management.saved'); ?></option>
                                            <option value="unsaved"><?php echo t('meter_management.unsaved'); ?></option>
                                        </select>
                                    </div>
                                </div>
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
                    <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive_row_2']; ?>; position: relative; z-index: 1;">
                        <table class="table table-sm table-hover mb-0" id="MetersTable">
                            <thead class="table-light" >
                                <tr>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                    <th width="120" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.product_code'); ?></th>
                                    <th width="40" class="text-center d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.month'); ?>/<?php echo t('meter_management.year'); ?></th>
                                    <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.status'); ?></th>
                                    <th width="100" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.electricityData'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.waterData'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.electricity'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.water'); ?></th>
                                    <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.garbage'); ?></th>
                                    <th width="100" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.common_area'); ?></th>
                                    <th width="80" class="text-right" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.total'); ?></th>
                                    <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.actions'); ?></th>
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


<!-- Modal สำหรับแก้ไขข้อมูล -->
<div class="modal fade" id="editMeterModal" tabindex="-1" aria-labelledby="editMeterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMeterModalLabel"><?php echo t('meter_management.meterData'); ?><span id="modalMonthYear" class="month-year-gradient"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMeterForm">
                    <!-- ข้อมูลมิเตอร์ -->
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-3 mb-3">
                            <label for="meterPcode" class="form-label"><?php echo t('meter_management.productCode'); ?></label>
                            <input type="text" class="form-control form-control-sm" id="meterPcode" name="pcode" readonly disabled>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2 mb-3">
                            <label for="meterMonth" class="form-label"><?php echo t('meter_management.month'); ?></label>
                            <input type="number" class="form-control form-control-sm" id="meterMonth" name="month" readonly disabled>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2 mb-3">
                            <label for="meterYear" class="form-label"><?php echo t('meter_management.year'); ?></label>
                            <input type="number" class="form-control form-control-sm" id="meterYear" name="year" readonly disabled>
                        </div>
                        <div class="col-6 col-sm-6 col-md-2 mb-3">
                            <label for="meterMonth" class="form-label"><?php echo t('meter_management.electricity_ppu'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="electricity_ppu" name="electricity_ppu" readonly disabled>
                        </div>
                        <div class="col-6 col-sm-6 col-md-2 mb-3">
                            <label for="meterYear" class="form-label"><?php echo t('meter_management.water_ppu'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="water_ppu" name="water_ppu" readonly disabled>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="previousMeterElectricity" class="form-label"><?php echo t('meter_management.previous_electricity_reading'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="previousMeterElectricity" name="previous_meter_electricity" readonly disabled>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="previousMeterWater" class="form-label"><?php echo t('meter_management.previous_water_reading'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="previousMeterWater" name="previous_meter_water" readonly disabled>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="dateMeterElectricity" class="form-label"><?php echo t('meter_management.date_electricity'); ?></label>
                            <input type="date" class="form-control form-control-sm" style="text-align: center;" id="dateMeterElectricity" name="dateMeterElectricity" >
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="dateMeterWater" class="form-label"><?php echo t('meter_management.date_water'); ?></label>
                            <input type="date" class="form-control form-control-sm" style="text-align: center;" id="dateMeterWater" name="dateMeterWater" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueElectricity" class="form-label"><?php echo t('meter_management.latest_electricity_reading'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="readingValueElectricity" name="electricity">
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueWater" class="form-label"><?php echo t('meter_management.latest_water_reading'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="readingValueWater" name="water">
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueGarbage" class="form-label"><?php echo t('meter_management.garbage'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="readingValueGarbage" name="garbage">
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label for="readingValueCommonArea" class="form-label"><?php echo t('meter_management.common_area'); ?></label>
                            <input type="number" class="form-control form-control-sm" style="text-align: right;" id="readingValueCommonArea" name="common_area">
                        </div>
                    </div>
                    
                    <!-- ส่วนแสดงรูปภาพ -->
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label class="form-label"><?php echo t('meter_management.img_electricity'); ?></label>
                            <div class="image-preview-container mb-2" style="width: 100%; height: 120px; border: 1px solid #dee2e6; border-radius: 0.5rem; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <div class="image-preview w-100 h-100 d-flex align-items-center justify-content-center" id="electricityImagePreview">
                                    <div class="placeholder text-center text-muted">
                                        <i class="fas fa-image fa-2x mb-1"></i>
                                        <div style="font-size: 0.9em;"><?php echo t('meter_management.img_not'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <input type="file" class="form-control form-control-sm" id="img_electricity" name="img_electricity" accept="image/*" onchange="previewElectricityImage(event)">
                            <input type="hidden" id="currentElectricityImage" name="current_electricity_image">
                            <div class="form-text small">
                                <button type="button" class="btn btn-outline-danger btn-sm mt-1" onclick="clearElectricityImage()" style="display:none;" id="clearElectricityBtn">
                                    <i class="fas fa-times"></i> <?php echo t('meter_management.img_delete'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <label class="form-label"><?php echo t('meter_management.img_water'); ?></label>
                            <div class="image-preview-container mb-2" style="width: 100%; height: 120px; border: 1px solid #dee2e6; border-radius: 0.5rem; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <div class="image-preview w-100 h-100 d-flex align-items-center justify-content-center" id="waterImagePreview">
                                    <div class="placeholder text-center text-muted">
                                        <i class="fas fa-image fa-2x mb-1"></i>
                                        <div style="font-size: 0.9em;"><?php echo t('meter_management.img_not'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <input type="file" class="form-control form-control-sm" id="img_water" name="img_water" accept="image/*" onchange="previewWaterImage(event)">
                            <input type="hidden" id="currentWaterImage" name="current_water_image">
                            <div class="form-text small">
                                <button type="button" class="btn btn-outline-danger btn-sm mt-1" onclick="clearWaterImage()" style="display:none;" id="clearWaterBtn">
                                    <i class="fas fa-times"></i> <?php echo t('meter_management.img_delete'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 mb-3">
                            <label for="meterRemark" class="form-label"><?php echo t('meter_management.remark'); ?></label>
                            <textarea class="form-control" id="meterRemark" name="remark" rows="5"></textarea>
                        </div>
                    </div>
                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo t('btns.cancel'); ?></button>
                <button type="button" class="btn btn-primary" id="saveMeterChanges"><?php echo t('btns.save'); ?></button>
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
    saved: '<?php echo t('meter_management.saved'); ?>',
    unsaved: '<?php echo t('meter_management.unsaved'); ?>',
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

// ฟังก์ชันคำนวณและแสดงสถิติ
function updateStats(meters) {
    if (!meters || meters.length === 0) {
        // รีเซ็ตสถิติทั้งหมดเป็น 0
        document.getElementById('totalMeters').textContent = '0';
        document.getElementById('savedCount').textContent = '0';
        document.getElementById('unsavedCount').textContent = '0';
        document.getElementById('totalElectricity').textContent = '0.00';
        document.getElementById('totalWater').textContent = '0.00';
        document.getElementById('totalGarbage').textContent = '0.00';
        document.getElementById('totalCommonArea').textContent = '0.00';
        document.getElementById('grandTotal').textContent = '0.00';
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
        common_area: 0,
        grandTotal: 0
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

    // อัพเดทแสดงผล
    document.getElementById('totalMeters').textContent = stats.total.toLocaleString();
    document.getElementById('savedCount').textContent = stats.saved.toLocaleString();
    document.getElementById('unsavedCount').textContent = stats.unsaved.toLocaleString();
    document.getElementById('totalElectricity').textContent = stats.electricity.toFixed(2);
    document.getElementById('totalWater').textContent = stats.water.toFixed(2);
    document.getElementById('totalGarbage').textContent = stats.garbage.toFixed(2);
    document.getElementById('totalCommonArea').textContent = stats.common_area.toFixed(2);
    document.getElementById('grandTotal').textContent = stats.grandTotal.toFixed(2);
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
        if (meter.status === 'saved' || meter.status === true || meter.status === 1 || meter.status === '1') {
            statusBadge = '<span class="badge bg-success">' + translations.saved + '</span>';
        } else {
            statusBadge = '<span class="badge bg-warning text-dark">' + translations.unsaved + '</span>';
        }
        
        html += `
            <tr>
                <td class="text-center text-muted small">${index + 1}</td>
                <td><div class="fw-bold small">${meter.pcode || ''}</div></td>
                <td class="text-center d-none d-lg-table-cell"><div class="small">${String(meter.month).padStart(2, '0')}/${meter.year}</div></td>
                <td class="text-center">${statusBadge}</td>
                <td class="text-end d-none d-lg-table-cell"><div class="small">${parseFloat(meter.meterelectricity).toFixed(0)}</div></td>
                <td class="text-end d-none d-lg-table-cell"><div class="small">${parseFloat(meter.meterwater).toFixed(0)}</div></td>
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

let currentMeterData = {};

// เปิด Modal เมื่อคลิกปุ่มแก้ไข
document.addEventListener('click', function(event) {
    if (event.target.closest('.btn-edit-meter')) {
        const meterId = event.target.closest('.btn-edit-meter').dataset.id;
        const meter = allMeters.find(m => m.id == meterId);

        if (meter) {

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
            document.getElementById('modalMonthYear').textContent = `${monthNames[month]} ${year}`;
            
            // เติมข้อมูลในฟอร์ม
            document.getElementById('meterPcode').value = meter.pcode || '';
            document.getElementById('meterMonth').value = meter.month || '';
            document.getElementById('meterYear').value = meter.year || '';
            document.getElementById('previousMeterElectricity').value = meter.electricityMeterNumberBefore || '0';
            document.getElementById('previousMeterWater').value = meter.waterMeterNumberBefore || '0';
            document.getElementById('readingValueElectricity').value = meter.meterelectricity || '';
            document.getElementById('readingValueWater').value = meter.meterwater || '';
            document.getElementById('readingValueGarbage').value = meter.garbage || '';
            document.getElementById('readingValueCommonArea').value = meter.common_area || '';
            document.getElementById('meterRemark').value = meter.remark || '';
            document.getElementById('electricity_ppu').value = meter.electricity_ppu || 0;
            document.getElementById('water_ppu').value = meter.water_ppu || 0;

            // เติมข้อมูลวันที่
            document.getElementById('dateMeterElectricity').value = meter.dateMeterElectricity || '';
            document.getElementById('dateMeterWater').value = meter.dateMeterWater || '';

            // เก็บข้อมูลปัจจุบัน
            currentMeterData = meter;

            // โหลดรูปภาพจาก API
            loadMeterImages(meter.pcode, meter.month, meter.year);

            // แสดง Modal
            editMeterModal.show();
        }
    }
});

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
    const dateMeterElectricity = document.getElementById('dateMeterElectricity').value;
    const dateMeterWater = document.getElementById('dateMeterWater').value;

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
            formData.append('dateMeterElectricity', dateMeterElectricity || '');
            formData.append('dateMeterWater', dateMeterWater || '');

            // เพิ่มไฟล์รูปภาพถ้ามี
            if (imgElectricity) {
                formData.append('img_electricity', imgElectricity);
            }
            if (imgWater) {
                formData.append('img_water', imgWater);
            }

            // แสดง loading ใน SweetAlert
            Swal.fire({
                title: translations.swal.saving_title,
                text: translations.swal.saving_text,
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // ส่งข้อมูลไปยัง server
            const basePath = window.APP_BASE_PATH || '';
            fetch(basePath + '/meter-management/save-meter', {
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