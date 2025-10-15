<?php
// views/pages/import-users.php

// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <?php
            $importButton = '
                <button type="button" class="btn btn-primary btn-sm" id="importSelectedBtn">
                    <i class="fas fa-download"></i> 
                    <span class="d-none d-md-inline">' . t('import_users.import_selected') . '</span>
                    <span class="d-md-none">' . t('import_users.import_selected') . '</span>
                </button>
            ';
            
            echo pageHeader(t('import_users.title'), '', $importButton, 'fas fa-users-cog'); 
            ?>

            <!-- ตัวกรองข้อมูลแบบกระชับ -->
            <div class="card <?= $headerConfig['classes']['filter_card'] ?>">
                <div class="card-body py-2">
                    <div class="row align-items-end">
                        <div class="col-md-6 col-lg-4">
                            <div class="mb-0">
                                <label for="filterMcode" class="form-label fw-bold small"><?= t('import_users.search_member') ?></label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="filterMcode" placeholder="<?= t('import_users.placeholder_search') ?>">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFilter">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-8 mt-2 mt-md-0 text-md-end">
                            <div class="d-flex justify-content-between justify-content-md-end align-items-center">
                                <span class="text-muted me-3 d-none d-lg-block small">
                                    <span class="badge bg-primary" style="<?= $headerConfig['styles']['badge'] ?>"><?= count($localUsers) ?></span> <?= t('import_users.in_system') ?>
                                    <span class="badge bg-info ms-2" style="<?= $headerConfig['styles']['badge'] ?>"><?= count($uniqueExternalUsers) ?></span> <?= t('import_users.waiting') ?>
                                </span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="selectAllExternal">
                                    <label class="form-check-label small" for="selectAllExternal"><?= t('import_users.select_all') ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตารางเปรียบเทียบแบบ Responsive -->
            <div class="row">
                <!-- ฐานข้อมูลภายใน -->
                <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                    <div class="card h-100">
                        <?php echo cardHeader(
                            t('import_users.internal_db'), 
                            count($localUsers), 
                            'fas fa-database'
                        ); ?>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0" id="localUsersTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40" class="text-center" style="<?= $headerConfig['styles']['table_header'] ?>">#</th>
                                            <th style="<?= $headerConfig['styles']['table_header'] ?>"><?= t('import_users.member_code') ?></th>
                                            <th class="d-none d-sm-table-cell" style="<?= $headerConfig['styles']['table_header'] ?>"><?= t('import_users.fullname_th') ?></th>
                                            <th width="80" class="text-center" style="<?= $headerConfig['styles']['table_header'] ?>"><?= t('import_users.status') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($localUsers as $index => $user): ?>
                                            <tr>
                                                <td class="text-center text-muted small"><?= $index + 1 ?></td>
                                                <td>
                                                    <div class="fw-bold small"><?= $user['mcode'] ?></div>
                                                    <div class="small text-muted d-sm-none"><?= $user['name_f'] ?></div>
                                                </td>
                                                <td class="d-none d-sm-table-cell">
                                                    <div class="small"><?= $user['name_f'] ?></div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success" style="<?= $headerConfig['styles']['badge'] ?>"><?= t('import_users.in_system') ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ฐานข้อมูลภายนอก -->
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <?php echo cardHeader(
                            t('import_users.external_db'), 
                            count($uniqueExternalUsers), 
                            'fas fa-external-link-alt'
                        ); ?>
                
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0" id="externalUsersTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40" class="text-center">
                                                <input type="checkbox" class="form-check-input" id="selectAllHeader">
                                            </th>
                                            <th style="<?= $headerConfig['styles']['table_header'] ?>"><?= t('import_users.member_code') ?></th>
                                            <th class="d-none d-sm-table-cell" style="<?= $headerConfig['styles']['table_header'] ?>"><?= t('import_users.fullname_th') ?></th>
                                            <th width="80" class="text-center" style="<?= $headerConfig['styles']['table_header'] ?>"><?= t('import_users.status') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($uniqueExternalUsers as $user): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input user-checkbox" data-mcode="<?= $user['mcode'] ?>">
                                                </td>
                                                <td>
                                                    <div class="fw-bold small"><?= $user['mcode'] ?></div>
                                                    <div class="small text-muted d-sm-none"><?= isset($user['name_t']) ? $user['name_t'] : '' ?></div>
                                                </td>
                                                <td class="d-none d-sm-table-cell">
                                                    <div class="small"><?= isset($user['name_t']) ? $user['name_t'] : '' ?></div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning" style="<?= $headerConfig['styles']['badge'] ?>"><?= t('import_users.waiting') ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterMcode = document.getElementById('filterMcode');
    const resetFilter = document.getElementById('resetFilter');
    const importSelectedBtn = document.getElementById('importSelectedBtn');
    const selectAllHeader = document.getElementById('selectAllHeader');
    const selectAllExternal = document.getElementById('selectAllExternal');

    function filterTable() {
        const mcodeValue = filterMcode.value.toLowerCase().trim();
        document.querySelectorAll('#externalUsersTable tbody tr, #localUsersTable tbody tr').forEach(row => {
            const mcode = row.cells[1].querySelector('.fw-bold').textContent.toLowerCase();
            row.style.display = mcode.includes(mcodeValue) ? '' : 'none';
        });
    }

    resetFilter.addEventListener('click', function() {
        filterMcode.value = '';
        filterTable();
    });

    filterMcode.addEventListener('input', filterTable);

    function handleSelectAll() {
        const checkboxes = document.querySelectorAll('#externalUsersTable tbody input[type="checkbox"]');
        const isChecked = selectAllHeader.checked || selectAllExternal.checked;
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('tr').style.display !== 'none') checkbox.checked = isChecked;
        });
    }

    selectAllHeader.addEventListener('change', handleSelectAll);
    selectAllExternal.addEventListener('change', handleSelectAll);

    importSelectedBtn.addEventListener('click', function() {
        const selectedUsers = Array.from(document.querySelectorAll('#externalUsersTable tbody input[type="checkbox"]:checked')).map(cb => cb.dataset.mcode);

        if (selectedUsers.length === 0) {
            Swal.fire('<?= t("import_users.select_users_alert") ?>', '', 'warning');
            return;
        }

        Swal.fire({
            title: '<?= t("import_users.confirm_import") ?>'.replace('{count}', selectedUsers.length),
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<?= t("import_users.yes_import") ?>',
            cancelButtonText: '<?= t("import_users.cancel") ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                // แสดง loading
                Swal.fire({
                    title: 'กำลังนำเข้าข้อมูล...',
                    text: 'กรุณารอสักครู่',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch('/import-users/action', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ users: selectedUsers })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // ปิด loading ก่อน
                    
                    if (data.success) {
                        let message = 'นำเข้าข้อมูลสำเร็จ ' + data.imported + ' รายการ';
                        if (data.errors && data.errors.length > 0) {
                            message += '\n\nข้อผิดพลาด:\n' + data.errors.join('\n');
                        }
                        Swal.fire({
                            title: 'สำเร็จ!',
                            html: message.replace(/\n/g, '<br>'),
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                })
                .catch(error => {
                    Swal.close(); // ปิด loading ก่อน
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                });
            }
        });
    });
});
</script>
<style>
/* Badges */
.badge {
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.35rem 0.7rem;
    border-radius: 0.5rem;
    color: #405000;
}

.badge.bg-success {
    background-color: #405000 !important;
    color: #fff;
}

.badge.bg-warning {
    background-color: #A9D654 !important;
    color: #fff;
}

.badge.bg-info {
    background-color: #A9D654 !important;
    color: #fff;
}

.badge.bg-primary {
    background-color: #405000 !important;
    color: #fff;
}

/* Table Scrollable with Sticky Header */
.table-responsive {
    position: relative;
}

.table-responsive thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa !important;
    z-index: 10;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
}

/* Custom Scrollbar */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

