<?php
// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';

$filterMonth = isset($_POST['filter_month']) ? $_POST['filter_month'] : (isset($_GET['filter_month']) ? $_GET['filter_month'] : date('m'));
$filterYear = isset($_POST['filter_year']) ? $_POST['filter_year'] : (isset($_GET['filter_year']) ? $_GET['filter_year'] : (date('Y')));
$filterStatus = isset($_POST['filter_status']) ? $_POST['filter_status'] : (isset($_GET['filter_status']) ? $_GET['filter_status'] : 'all');

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
            <form method="post" action="" id="filterForm">
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
                                               name="search_user" 
                                               value="<?php echo htmlspecialchars($searchValue, ENT_QUOTES, 'UTF-8'); ?>"
                                               placeholder="<?php echo t('meter_management.meter_doc'); ?>...">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-2 p-0">
                                <div class="mb-0">
                                    <label for="filterMonth" class="form-label fw-bold small"><?php echo t('meter_management.month'); ?></label>
                                    <select class="select-beast form-select form-select-sm" id="filterMonth" name="filter_month">
           
                                        <option value="01" <?php echo $filterMonth == '01' ? 'selected' : ''; ?>><?php echo t('month.january'); ?></option>
                                        <option value="02" <?php echo $filterMonth == '02' ? 'selected' : ''; ?>><?php echo t('month.february'); ?></option>
                                        <option value="03" <?php echo $filterMonth == '03' ? 'selected' : ''; ?>><?php echo t('month.march'); ?></option>
                                        <option value="04" <?php echo $filterMonth == '04' ? 'selected' : ''; ?>><?php echo t('month.april'); ?></option>
                                        <option value="05" <?php echo $filterMonth == '05' ? 'selected' : ''; ?>><?php echo t('month.may'); ?></option>
                                        <option value="06" <?php echo $filterMonth == '06' ? 'selected' : ''; ?>><?php echo t('month.june'); ?></option>
                                        <option value="07" <?php echo $filterMonth == '07' ? 'selected' : ''; ?>><?php echo t('month.july'); ?></option>
                                        <option value="08" <?php echo $filterMonth == '08' ? 'selected' : ''; ?>><?php echo t('month.august'); ?></option>
                                        <option value="09" <?php echo $filterMonth == '09' ? 'selected' : ''; ?>><?php echo t('month.september'); ?></option>
                                        <option value="10" <?php echo $filterMonth == '10' ? 'selected' : ''; ?>><?php echo t('month.october'); ?></option>
                                        <option value="11" <?php echo $filterMonth == '11' ? 'selected' : ''; ?>><?php echo t('month.november'); ?></option>
                                        <option value="12" <?php echo $filterMonth == '12' ? 'selected' : ''; ?>><?php echo t('month.december'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-2 p-0">
                                <div class="mb-0">
                                    <label for="filterYear" class="form-label fw-bold small"><?php echo t('meter_management.year'); ?></label>
                                    <select class="select-beast form-select form-select-sm" id="filterYear" name="filter_year">
                                 
                                        <?php foreach ($years as $year): ?>
                                            <option value="<?php echo $year; ?>" <?php echo $filterYear == $year ? 'selected' : ''; ?>>
                                                <?php echo $year; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-2 p-0">
                                <div class="mb-0">
                                    <label for="filterStatus" class="form-label fw-bold small"><?php echo t('meter_management.status'); ?></label>
                                    <select class="select-beast form-select form-select-sm" id="filterStatus" name="filter_status">
                                        <option value="all" <?php echo $filterStatus == 'all' ? 'selected' : ''; ?>><?php echo t('meter_management.all'); ?></option>
                                        <option value="saved" <?php echo $filterStatus == 'saved' ? 'selected' : ''; ?>><?php echo t('meter_management.saved'); ?></option>
                                        <option value="unsaved" <?php echo $filterStatus == 'unsaved' ? 'selected' : ''; ?>><?php echo t('meter_management.unsaved'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- ตารางผู้ใช้ -->
            <div class="card" style="position: relative; z-index: 1;">
                <?php 
                $meterCount = isset($meters) && is_array($meters) ? count($meters) : 0;
                echo cardHeader(
                    t('meter_management.meter_list'), 
                    $meterCount, 
                    'fa-solid fa-jug-detergent'
                ); 
                ?>
                
                <div class="card-body p-0">
                    <?php if (isset($meters) && !empty($meters)): ?>
                        <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive']; ?>; position: relative; z-index: 1;">
                            <table class="table table-sm table-hover mb-0" id="MetersTable">
                                <thead class="table-light" >
                                    <tr>
                                        <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                        <th width="120" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.product_code'); ?></th>
                                        <th class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.status'); ?></th>
                                        <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.electricity'); ?></th>
                                        <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.water'); ?></th>
                                        <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.garbage'); ?></th>
                                        <th width="80" class="text-right d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.common_area'); ?></th>
                                        <th width="80" class="text-right" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.total'); ?></th>
                                        <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('meter_management.actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($meters as $index => $meter): ?>
                                        
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-circle-exclamation fa-3x text-muted mb-3"></i>
                            <p class="text-muted"><?php echo t('meter_management.meter_not_found'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript เบื้องต้นสำหรับการรีเซ็ตฟอร์ม
document.addEventListener('DOMContentLoaded', function() {
    const resetSearch = document.getElementById('resetSearch');
    const resetAll = document.getElementById('resetAll');
    const filterForm = document.getElementById('filterForm');
    
    if (resetSearch) {
        resetSearch.addEventListener('click', function() {
            document.getElementById('searchUser').value = '';
        });
    }
    
    if (resetAll) {
        resetAll.addEventListener('click', function() {
            // รีเซ็ตฟอร์มทั้งหมด
            filterForm.reset();
            // ส่งฟอร์มเพื่อรีเฟรชหน้า
            filterForm.submit();
        });
    }
});
</script>