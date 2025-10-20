<?php

// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';
?>
<style>
#usersTable thead {
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
            $addButton = '
                <a href="/users/create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> 
                    <span class="d-none d-md-inline">' . t('user_management.add_user') . '</span>
                </a>
            ';
            
            echo pageHeader(t('user_management.title'), '', $addButton, 'fas fa-users-cog'); 
            ?>

            <!-- สถิติ -->
            <div class="row">
                <div class="col-6 col-md-2 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('user_management.total_users'); ?></div>
                            <h4 class="mb-0 text-primary"><?php echo number_format(isset($stats['total']) ? $stats['total'] : 0); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('user_management.active_users'); ?></div>
                            <h4 class="mb-0 text-success"><?php echo number_format(isset($stats['active']) ? $stats['active'] : 0); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('user_management.suspended_users'); ?></div>
                            <h4 class="mb-0 text-danger"><?php echo number_format(isset($stats['suspended']) ? $stats['suspended'] : 0); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('user_management.admin_count'); ?></div>
                            <h4 class="mb-0 text-info"><?php echo number_format(isset($stats['admin']) ? $stats['admin'] : 0); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('user_management.agent_count'); ?></div>
                            <h4 class="mb-0 text-warning"><?php echo number_format(isset($stats['agent']) ? $stats['agent'] : 0); ?></h4>
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
                                    <i class="fas fa-search"></i> <?php echo t('user_management.search'); ?>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="searchUser" 
                                           placeholder="<?php echo t('user_management.user_code'); ?>, <?php echo t('user_management.full_name'); ?>...">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mt-2 mt-md-0">
                            <div class="mb-0">
                                <label for="filterRole" class="form-label fw-bold small">
                                    <i class="fas fa-user-tag"></i> <?php echo t('user_management.role'); ?>
                                </label>
                                <select class="select-beast form-select form-select-sm" id="filterRole">
                                    <option value="" selected><?php echo t('selection.all'); ?></option>
                                    <option value="admin"><?php echo t('user_management.admin'); ?></option>
                                    <option value="agent"><?php echo t('user_management.agent'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mt-2 mt-lg-0" style="position: relative; overflow: visible;">
                            <div class="mb-0">
                                <label for="filterStatus" class="form-label fw-bold small">
                                    <i class="fas fa-check-circle"></i> <?php echo t('user_management.status'); ?>
                                </label>
                                <select class="select-beast form-select form-select-sm"  id="filterStatus">
                                    <option value="" selected><?php echo t('selection.all'); ?></option>
                                    <option value="active"><?php echo t('user_management.active'); ?></option>
                                    <option value="suspended"><?php echo t('user_management.suspended'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตารางผู้ใช้ -->
            <div class="card" style="position: relative; z-index: 1;">
                <?php 
                $userCount = isset($users) && is_array($users) ? count($users) : 0;
                echo cardHeader(
                    t('user_management.user_list'), 
                    $userCount, 
                    'fas fa-users'
                ); 
                ?>
                
                <div class="card-body p-0">
                    <?php if (isset($users) && !empty($users)): ?>
                        <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive']; ?>; position: relative; z-index: 1;">
                            <table class="table table-sm table-hover mb-0" id="usersTable">
                                <thead class="table-light" >
                                    <tr>
                                        <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                        <th width="80" class="text-center d-none d-md-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>">
                                            <i class="fas fa-image"></i>
                                        </th>
                                        <th style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('user_management.user_code'); ?></th>
                                        <th class="d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('user_management.full_name'); ?></th>
                                        <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('user_management.role'); ?></th>
                                        <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('user_management.status'); ?></th>
                                        <th width="150" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('user_management.actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $index => $user): ?>
                                        <tr data-code="<?php echo htmlspecialchars($user['code']); ?>" 
                                            data-name="<?php echo htmlspecialchars(!empty($user['name']) ? $user['name'] : ''); ?>"
                                            data-role="<?php echo htmlspecialchars($user['role']); ?>"
                                            data-status="<?php echo htmlspecialchars($user['status']); ?>">
                                            <td class="text-center text-muted small"><?php echo $index + 1; ?></td>
                                            <td class="text-center d-none d-md-table-cell">
                                                <?php if (!empty($user['img'])): ?>
                                          <img src="<?php echo htmlspecialchars($user['img']); ?>" 
                                              alt="Avatar" 
                                              class="rounded-circle preview-clickable" 
                                              style="width: 40px; height: 40px; object-fit: cover; cursor:pointer;" 
                                              onclick="openImageModal(this.src)">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="fw-bold small"><?php echo htmlspecialchars($user['code']); ?></div>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <div class="small"><?php echo htmlspecialchars(!empty($user['name']) ? $user['name'] : '-'); ?></div>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                $roleClass = 'bg-info';
                                                $roleText = t('user_management.' . $user['role']);
                                                if ($user['role'] === 'admin') {
                                                    $roleClass = 'bg-primary';
                                                } elseif ($user['role'] === 'agent') {
                                                    $roleClass = 'bg-warning text-dark';
                                                }
                                                ?>
                                                <span class="badge <?php echo $roleClass; ?>" style="<?php echo $headerConfig['styles']['badge']; ?>">
                                                    <?php echo $roleText; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($user['status'] === 'active'): ?>
                                                    <span class="badge bg-success" style="<?php echo $headerConfig['styles']['badge']; ?>">
                                                        <i class="fas fa-check-circle"></i> <?php echo t('user_management.active'); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger" style="<?php echo $headerConfig['styles']['badge']; ?>">
                                                        <i class="fas fa-ban"></i> <?php echo t('user_management.suspended'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/users/edit/<?php echo $user['id']; ?>" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="<?php echo t('user_management.edit'); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button onclick="deleteUser('<?php echo $user['id']; ?>', '<?php echo htmlspecialchars(!empty($user['name']) ? $user['name'] : $user['username']); ?>')" 
                                                            class="btn btn-danger btn-sm" 
                                                            title="<?php echo t('user_management.delete'); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
        if (filterRole.tomselect) {
            tomSelectRoleInstance = filterRole.tomselect;
        }
        if (filterStatus.tomselect) {
            tomSelectStatusInstance = filterStatus.tomselect;
        }
    }, 100);

    function filterTable() {
        const searchValue = searchUser.value.toLowerCase().trim();
        // ดึงค่าจาก TomSelect
        const roleValue = tomSelectRoleInstance ? tomSelectRoleInstance.getValue() : filterRole.value;
        const statusValue = tomSelectStatusInstance ? tomSelectStatusInstance.getValue().toLowerCase() : filterStatus.value.toLowerCase();

        document.querySelectorAll('#usersTable tbody tr').forEach(function(row) {
            const code = row.getAttribute('data-code').toLowerCase();
            const name = row.getAttribute('data-name').toLowerCase();
            const role = row.getAttribute('data-role').toLowerCase();
            const status = row.getAttribute('data-status').toLowerCase();

            const matchSearch = !searchValue || 
                              code.includes(searchValue) || 
                              name.includes(searchValue);
            const matchRole = !roleValue || role === roleValue;
            const matchStatus = !statusValue || status === statusValue;

            row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
        });
    }

    searchUser.addEventListener('input', filterTable);
    
    // Listen for TomSelect change event
    if (filterRole) {
        filterRole.addEventListener('change', filterTable);
    }
    
    filterStatus.addEventListener('change', filterTable);

    resetSearch.addEventListener('click', function() {
    searchUser.value = '';
    
    // รีเซตโดยตรงที่ DOM element
    filterRole.value = '';
    filterStatus.value = '';
    
    // สั่งให้ TomSelect sync กับค่าใหม่
    if (filterRole.tomselect) {
        filterRole.tomselect.setValue('');
    }
    if (filterStatus.tomselect) {
        filterStatus.tomselect.setValue('');
    }
    
    filterTable();
});
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

<!-- Image Modal Popup: วางไว้หลังตารางผู้ใช้ เพื่อให้แน่ใจว่าอยู่ใน DOM -->
<div id="imageModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7);">
    <span style="position:absolute; top:20px; right:30px; color:#fff; font-size:2em; cursor:pointer;" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" src="" style="max-width:90vw; max-height:90vh; border-radius:1em; box-shadow:0 0 20px #000; display:block; margin:auto;">
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.openImageModal = function(src) {
        var modal = document.getElementById('imageModal');
        var modalImg = document.getElementById('modalImage');
        modalImg.src = src;
        modal.style.display = 'flex';
    }
    window.closeImageModal = function() {
        var modal = document.getElementById('imageModal');
        modal.style.display = 'none';
    }
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) window.closeImageModal();
    });
});
</script>

