<?php

// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card <?php echo isset($headerConfig['classes']['filter_card']) ? $headerConfig['classes']['filter_card'] : ''; ?>">
                <div class="card-body py-2">
                    <div class="form-header mb-3">
                        <i class="fas fa-<?php echo $data['action'] === 'create' ? 'plus' : 'edit'; ?>"></i>
                        <h1 class="h4 d-inline-block ms-2 mb-0"><?php echo htmlspecialchars($data['title']); ?></h1>
                    </div>
                    <form method="POST" id="userForm" action="<?php echo $data['action'] === 'create' ? '/users/store' : '/users/update/' . $data['user']['id']; ?>" enctype="multipart/form-data">
                        <div class="form-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="code" class="required"><?php echo t('user_management.user_code'); ?></label>
                                    <input type="text" autocomplete="off" class="form-control form-control-sm" id="code" name="code" value="<?php echo htmlspecialchars(isset($_SESSION['old']['code']) ? $_SESSION['old']['code'] : (isset($data['user']['code']) ? $data['user']['code'] : '')); ?>" required>                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="username" class="required"><?php echo t('user_management.username'); ?></label>
                                    <input type="text" autocomplete="off" class="form-control form-control-sm" id="username" name="username" value="<?php echo htmlspecialchars(isset($_SESSION['old']['username']) ? $_SESSION['old']['username'] : (isset($data['user']['username']) ? $data['user']['username'] : '')); ?>" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="password" <?php echo $data['action'] === 'create' ? 'class="required"' : ''; ?>><?php echo t('user_management.password'); ?></label>
                                    <input type="password" autocomplete="off" class="form-control form-control-sm" id="password" name="password" <?php echo $data['action'] === 'create' ? 'required' : ''; ?> >
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="name"><?php echo t('user_management.full_name'); ?></label>
                                    <input type="text" autocomplete="off" class="form-control form-control-sm" id="name" name="name" value="<?php echo htmlspecialchars(isset($_SESSION['old']['name']) ? $_SESSION['old']['name'] : (isset($data['user']['name']) ? $data['user']['name'] : '')); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="tel"><?php echo t('user_management.phone'); ?></label>
                                    <input type="text" autocomplete="off" class="form-control form-control-sm" id="tel" name="tel" value="<?php echo htmlspecialchars(isset($_SESSION['old']['tel']) ? $_SESSION['old']['tel'] : (isset($data['user']['tel']) ? $data['user']['tel'] : '')); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="birthday"><?php echo t('user_management.birthday'); ?></label>
                                    <input type="date" autocomplete="off" class="form-control form-control-sm" id="birthday" name="birthday" value="<?php echo htmlspecialchars(isset($_SESSION['old']['birthday']) ? $_SESSION['old']['birthday'] : (isset($data['user']['birthday']) ? $data['user']['birthday'] : '')); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="facebook_name"><?php echo t('user_management.facebook'); ?></label>
                                    <input type="text" autocomplete="off" class="form-control form-control-sm" id="facebook_name" name="facebook_name" value="<?php echo htmlspecialchars(isset($_SESSION['old']['facebook_name']) ? $_SESSION['old']['facebook_name'] : (isset($data['user']['facebook_name']) ? $data['user']['facebook_name'] : '')); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="line_id"><?php echo t('user_management.line_id'); ?></label>
                                    <input type="text" autocomplete="off" class="form-control form-control-sm" id="line_id" name="line_id" value="<?php echo htmlspecialchars(isset($_SESSION['old']['line_id']) ? $_SESSION['old']['line_id'] : (isset($data['user']['line_id']) ? $data['user']['line_id'] : '')); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="role" class="required"><?php echo t('user_management.role'); ?></label>
                                    <?php $currentRole = isset($_SESSION['old']['role']) ? $_SESSION['old']['role'] : (isset($data['user']['role']) ? $data['user']['role'] : 'agent'); ?>
                                    <select class="select-beast form-select form-select-sm" id="role" name="role" required>
                                        <option value="agent" <?php echo $currentRole === 'agent' ? 'selected' : ''; ?>><?php echo t('user_management.agent'); ?></option>
                                        <option value="admin" <?php echo $currentRole === 'admin' ? 'selected' : ''; ?>><?php echo t('user_management.admin'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="status" class="required"><?php echo t('user_management.status'); ?></label>
                                    <?php $currentStatus = isset($_SESSION['old']['status']) ? $_SESSION['old']['status'] : (isset($data['user']['status']) ? $data['user']['status'] : 'active'); ?>
                                    <select class="select-beast form-select form-select-sm" id="status" name="status" required>
                                        <option value="active" <?php echo $currentStatus === 'active' ? 'selected' : ''; ?>><?php echo t('user_management.active'); ?></option>
                                        <option value="suspended" <?php echo $currentStatus === 'suspended' ? 'selected' : ''; ?>><?php echo t('user_management.suspended'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="img" ><?php echo t('user_management.image'); ?></label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="image-preview-container flex-shrink-0" style="width: 90px; height: 90px; border: 1px solid #dee2e6; border-radius: 0.5rem; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <div class="image-preview w-100 h-100 d-flex align-items-center justify-content-center" id="imagePreview">
                                                <?php if (isset($data['user']['img']) && !empty($data['user']['img'])): ?>
                                                    <img src="<?php echo htmlspecialchars($data['user']['img']); ?>" alt="Preview" class="img-fluid rounded preview-clickable" style="max-width: 80px; max-height: 80px; cursor:pointer;" onclick="openImageModal(this.src)">
                                                <?php else: ?>
                                                    <div class="placeholder text-center text-muted">
                                                        <i class="fas fa-image fa-2x mb-1"></i>
                                                        <div style="font-size: 0.9em;"><?php echo t('user_management.image_preview'); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" class="form-control form-control-sm mb-1" id="img" name="img" accept="image/*" onchange="previewImage(event)">
                                            <div class="help-text small text-muted"><?php echo t('user_management.image_help'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-footer">
                        <a href="/users" class="btn btn-secondary">
                            <i class="fas fa-times"></i> <?php echo t('user_management.cancel'); ?>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo t('user_management.save'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php unset($_SESSION['old']); ?>


<script>
    // Handle form submission
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + <?php echo json_encode(t('user_management.save')); ?> + '...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(function(response) { 
            return response.json(); 
        })
        .then(function(data) {
            if (data.success) {
                // ตรวจสอบว่าเป็น action อะไร
                const isCreate = <?php echo $data['action'] === 'create' ? 'true' : 'false'; ?>;
                const successTitle = isCreate ? 
                    <?php echo json_encode(t('user_management.add_success')); ?> : 
                    <?php echo json_encode(t('user_management.update_success')); ?>;
                
                Swal.fire({
                    title: successTitle,
                    text: data.message,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false
                }).then(function() {
                    window.location.replace('/users');
                });
            } else {
                var errorMsg = data.errors ? data.errors.join('<br>') : data.message;
                Swal.fire({
                    title: 'Error',
                    html: errorMsg,
                    icon: 'error'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(function(error) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            Swal.fire({
                title: 'Error',
                text: 'Could not connect to server',
                icon: 'error'
            });
        });
    });
    
    function previewImage(event) {

        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="img-fluid rounded preview-clickable" style="max-width: 80px; max-height: 80px; cursor:pointer;" onclick="openImageModal(this.src)">';
            }
            reader.readAsDataURL(file);
        }
    }

    // Modal popup functions
    function openImageModal(src) {
        var modal = document.getElementById('imageModal');
        var modalImg = document.getElementById('modalImage');
        modalImg.src = src;
        modal.style.display = 'flex';
    }
    function closeImageModal() {
        var modal = document.getElementById('imageModal');
        modal.style.display = 'none';
    }
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) closeImageModal();
    });
</script>

<div id="imageModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
    <span style="position:absolute; top:20px; right:30px; color:#fff; font-size:2em; cursor:pointer;" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" src="" style="max-width:90vw; max-height:90vh; border-radius:1em; box-shadow:0 0 20px #000; display:block; margin:auto;">
</div>