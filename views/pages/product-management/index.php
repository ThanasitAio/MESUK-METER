<?php

// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';
?>
<style>
#productsTable thead {
  position: sticky;
  top: 0;
  z-index: 2;
  background: #f8f9fa; /* หรือใช้ theme สีที่ต้องการ */
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header -->
            <?php
           
            
            echo pageHeader(t('product_management.title'), '', '', 'fas fa-users-cog'); 
            ?>

            <!-- สถิติ -->
            <div class="row">
                <div class="col-6 col-md-2 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('product_management.total_products'); ?></div>
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
                                    <i class="fas fa-search"></i> <?php echo t('product_management.search'); ?>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="searchUser" 
                                           placeholder="<?php echo t('product_management.product_code'); ?>...">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                  
                    </div>
                </div>
            </div>

            <!-- ตารางผู้ใช้ -->
            <div class="card" style="position: relative; z-index: 1;">
                <?php 
                $productCount = isset($products) && is_array($products) ? count($products) : 0;
                echo cardHeader(
                    t('product_management.product_list'), 
                    $productCount, 
                    'fas fa-warehouse'
                ); 
                ?>
                
                <div class="card-body p-0">
                    <?php if (isset($products) && !empty($products)): ?>
                        <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive']; ?>; position: relative; z-index: 1;">
                            <table class="table table-sm table-hover mb-0" id="productsTable">
                                <thead class="table-light" >
                                    <tr>
                                        <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                        <th style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.product_code'); ?></th>
                                        <th class="d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.description'); ?></th>
                                        <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.group'); ?></th>
                                        <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.category'); ?></th>
                                        <th width="150" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $index => $product): ?>
                                        <tr data-code="<?php echo htmlspecialchars($product['code']); ?>" 
                                            data-name="<?php echo htmlspecialchars(!empty($product['name']) ? $product['name'] : ''); ?>"
                                            data-role="<?php echo htmlspecialchars($product['role']); ?>"
                                            data-status="<?php echo htmlspecialchars($product['status']); ?>">
                                            <td class="text-center text-muted small"><?php echo $index + 1; ?></td>
                                         
                                            <td>
                                                <div class="fw-bold small"><?php echo htmlspecialchars($product['pcode']); ?></div>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <div class="small"><?php echo htmlspecialchars(!empty($product['pdesc']) ? $product['pdesc'] : '-'); ?></div>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <div class="small"><?php echo htmlspecialchars(!empty($product['group_id']) ? $product['group_id'] : '-'); ?></div>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <div class="small"><?php echo htmlspecialchars(!empty($product['unit']) ? $product['unit'] : '-'); ?></div>
                                            </td>
                                         
                                         
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/users/edit/<?php echo $user['id']; ?>" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="<?php echo t('user_management.edit'); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted"><?php echo t('user_management.user_not_found'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// รอให้ DOM โหลดเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    const searchUser = document.getElementById('searchUser');
    const resetSearch = document.getElementById('resetSearch');
    const filterRole = document.getElementById('filterRole');
    const filterStatus = document.getElementById('filterStatus');

    // เก็บ TomSelect instances
    let tomSelectRoleInstance = null;
    let tomSelectStatusInstance = null;

    // รอให้ TomSelect initialize เสร็จ
    setTimeout(function() {
        if (filterRole && filterRole.tomselect) {
            tomSelectRoleInstance = filterRole.tomselect;
        }
        if (filterStatus && filterStatus.tomselect) {
            tomSelectStatusInstance = filterStatus.tomselect;
        }
    }, 100);

    function filterTable() {
        const searchValue = searchUser ? searchUser.value.toLowerCase().trim() : '';
        // ดึงค่าจาก TomSelect
        const roleValue = tomSelectRoleInstance ? tomSelectRoleInstance.getValue() : (filterRole ? filterRole.value : '');
        const statusValue = tomSelectStatusInstance ? tomSelectStatusInstance.getValue().toLowerCase() : (filterStatus ? filterStatus.value.toLowerCase() : '');

        document.querySelectorAll('#productsTable tbody tr').forEach(function(row) {
            const code = (row.getAttribute('data-code') || '').toLowerCase();
            const name = (row.getAttribute('data-name') || '').toLowerCase();
            const role = (row.getAttribute('data-role') || '').toLowerCase();
            const status = (row.getAttribute('data-status') || '').toLowerCase();

            const matchSearch = !searchValue || 
                              code.includes(searchValue) || 
                              name.includes(searchValue);
            const matchRole = !roleValue || role === roleValue;
            const matchStatus = !statusValue || status === statusValue;

            row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
        });
    }

    if (searchUser) {
        searchUser.addEventListener('input', filterTable);
    }

    // Listen for TomSelect change event
    if (filterRole) {
        filterRole.addEventListener('change', filterTable);
    }

    if (filterStatus) {
        filterStatus.addEventListener('change', filterTable);
    }

    if (resetSearch) {
        resetSearch.addEventListener('click', function() {
            if (searchUser) searchUser.value = '';

            // รีเซตโดยตรงที่ DOM element
            if (filterRole) filterRole.value = '';
            if (filterStatus) filterStatus.value = '';

            // สั่งให้ TomSelect sync กับค่าใหม่
            if (filterRole && filterRole.tomselect) {
                filterRole.tomselect.setValue('');
            }
            if (filterStatus && filterStatus.tomselect) {
                filterStatus.tomselect.setValue('');
            }

            filterTable();
        });
    }
});

function toggleStatus(userId) {
    Swal.fire({
        title: '<?php echo t('user_management.confirm_status_title'); ?>',
        text: '<?php echo t('user_management.confirm_status_text'); ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<?php echo t('user_management.confirm'); ?>',
        cancelButtonText: '<?php echo t('user_management.cancel'); ?>'
    }).then(function(result) {
        if (result.isConfirmed) {
            fetch('/users/change-status/' + userId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({
                        title: '<?php echo t('user_management.status_change_success'); ?>',
                        text: data.message,
                        icon: 'success'
                    }).then(function() { 
                        location.reload(); 
                    });
                } else {
                    Swal.fire('<?php echo t('user_management.operation_failed'); ?>', data.message, 'error');
                }
            })
            .catch(function(error) {
                Swal.fire('<?php echo t('user_management.operation_failed'); ?>', '<?php echo t('user_management.operation_failed'); ?>', 'error');
            });
        }
    });
}

function deleteUser(userId, userName) {
    var confirmText = '<?php echo t('user_management.confirm_delete_text'); ?>';
    confirmText = confirmText.replace('{name}', userName);
    
    Swal.fire({
        title: '<?php echo t('user_management.confirm_delete_title'); ?>',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?php echo t('user_management.delete'); ?>',
        cancelButtonText: '<?php echo t('user_management.cancel'); ?>'
    }).then(function(result) {
        if (result.isConfirmed) {
            fetch('/users/delete/' + userId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.message,
                        icon: 'success'
                    }).then(function() { 
                        location.reload(); 
                    });
                } else {
                    var errorMsg = data.errors ? data.errors.join('<br>') : data.message;
                    Swal.fire('<?php echo t('user_management.operation_failed'); ?>', errorMsg, 'error');
                }
            })
            .catch(function(error) {
                Swal.fire('<?php echo t('user_management.operation_failed'); ?>', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            });
        }
    });
}
</script>

