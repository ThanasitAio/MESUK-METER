<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> - MESUK</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-container {
            padding: 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .form-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group label.required::after {
            content: ' *';
            color: #dc3545;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .image-preview-container {
            margin-top: 15px;
        }
        
        .image-preview {
            width: 150px;
            height: 150px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-preview .placeholder {
            color: #6c757d;
            text-align: center;
        }
        
        .image-preview .placeholder i {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
        }
        
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            position: relative;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-close {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .form-footer {
            padding: 20px 30px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
    <?php include __DIR__ . '/../../partials/sidebar.php'; ?>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <i class="fas fa-<?php echo $data['action'] === 'create' ? 'plus' : 'edit'; ?>"></i>
                <h1><?php echo htmlspecialchars($data['title']); ?></h1>
            </div>

            <form method="POST" id="userForm" 
                  action="<?php echo $data['action'] === 'create' ? '/users/store' : '/users/update/' . $data['user']['id']; ?>" 
                  enctype="multipart/form-data">
                
                <div class="form-body">
                    <!-- รหัสผู้ใช้และ Username -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="code" class="required">รหัสผู้ใช้</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="code" 
                                   name="code" 
                                   value="<?php echo htmlspecialchars(isset($_SESSION['old']['code']) ? $_SESSION['old']['code'] : (isset($data['user']['code']) ? $data['user']['code'] : '')); ?>" 
                                   required>
                            <div class="help-text">รหัสประจำตัวผู้ใช้ (ไม่ซ้ำ)</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="username" class="required">ชื่อผู้ใช้</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo htmlspecialchars(isset($_SESSION['old']['username']) ? $_SESSION['old']['username'] : (isset($data['user']['username']) ? $data['user']['username'] : '')); ?>" 
                                   required>
                            <div class="help-text">ใช้สำหรับเข้าสู่ระบบ (ไม่ซ้ำ)</div>
                        </div>
                    </div>

                    <!-- รหัสผ่าน -->
                    <div class="form-group">
                        <label for="password" <?php echo $data['action'] === 'create' ? 'class="required"' : ''; ?>>
                            รหัสผ่าน
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               <?php echo $data['action'] === 'create' ? 'required' : ''; ?>>
                        <div class="help-text">
                            <?php echo $data['action'] === 'create' ? 'รหัสผ่านอย่างน้อย 3 ตัวอักษร' : 'เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน'; ?>
                        </div>
                    </div>

                    <!-- ชื่อ-นามสกุล -->
                    <div class="form-group">
                        <label for="name">ชื่อ-นามสกุล</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               value="<?php echo htmlspecialchars(isset($_SESSION['old']['name']) ? $_SESSION['old']['name'] : (isset($data['user']['name']) ? $data['user']['name'] : '')); ?>">
                    </div>

                    <!-- เบอร์โทรและวันเกิด -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tel">เบอร์โทร</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="tel" 
                                   name="tel" 
                                   value="<?php echo htmlspecialchars(isset($_SESSION['old']['tel']) ? $_SESSION['old']['tel'] : (isset($data['user']['tel']) ? $data['user']['tel'] : '')); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="birthday">วันเกิด</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="birthday" 
                                   name="birthday" 
                                   value="<?php echo htmlspecialchars(isset($_SESSION['old']['birthday']) ? $_SESSION['old']['birthday'] : (isset($data['user']['birthday']) ? $data['user']['birthday'] : '')); ?>">
                        </div>
                    </div>

                    <!-- Facebook และ LINE ID -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="facebook_name">Facebook Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="facebook_name" 
                                   name="facebook_name" 
                                   value="<?php echo htmlspecialchars(isset($_SESSION['old']['facebook_name']) ? $_SESSION['old']['facebook_name'] : (isset($data['user']['facebook_name']) ? $data['user']['facebook_name'] : '')); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="line_id">LINE ID</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="line_id" 
                                   name="line_id" 
                                   value="<?php echo htmlspecialchars(isset($_SESSION['old']['line_id']) ? $_SESSION['old']['line_id'] : (isset($data['user']['line_id']) ? $data['user']['line_id'] : '')); ?>">
                        </div>
                    </div>

                    <!-- Role และ Status -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="role" class="required">บทบาท</label>
                            <select class="form-control" id="role" name="role" required>
                                <?php
                                $currentRole = isset($_SESSION['old']['role']) ? $_SESSION['old']['role'] : (isset($data['user']['role']) ? $data['user']['role'] : 'agent');
                                ?>
                                <option value="agent" <?php echo $currentRole === 'agent' ? 'selected' : ''; ?>>Agent</option>
                                <option value="admin" <?php echo $currentRole === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="required">สถานะ</label>
                            <select class="form-control" id="status" name="status" required>
                                <?php
                                $currentStatus = isset($_SESSION['old']['status']) ? $_SESSION['old']['status'] : (isset($data['user']['status']) ? $data['user']['status'] : 'active');
                                ?>
                                <option value="active" <?php echo $currentStatus === 'active' ? 'selected' : ''; ?>>ใช้งาน</option>
                                <option value="suspended" <?php echo $currentStatus === 'suspended' ? 'selected' : ''; ?>>ระงับ</option>
                            </select>
                        </div>
                    </div>

                    <!-- รูปภาพ -->
                    <div class="form-group">
                        <label for="img">รูปภาพ</label>
                        <input type="file" 
                               class="form-control" 
                               id="img" 
                               name="img" 
                               accept="image/*"
                               onchange="previewImage(event)">
                        <div class="help-text">รองรับไฟล์ JPG, PNG, GIF ขนาดไม่เกิน 5MB</div>
                        
                        <div class="image-preview-container">
                            <div class="image-preview" id="imagePreview">
                                <?php if (isset($data['user']['img']) && !empty($data['user']['img'])): ?>
                                    <img src="<?php echo htmlspecialchars($data['user']['img']); ?>" alt="Preview">
                                <?php else: ?>
                                    <div class="placeholder">
                                        <i class="fas fa-image"></i>
                                        <div>ดูตัวอย่างภาพ</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="/users" class="btn btn-secondary">
                        <i class="fas fa-times"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php unset($_SESSION['old']); ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
    <script>
        // Handle form submission
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(function(response) { 
                return response.json(); 
            })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.message,
                        icon: 'success'
                    }).then(function() {
                        window.location.href = '/users';
                    });
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> บันทึก';
                    
                    var errorMsg = data.errors ? data.errors.join('<br>') : data.message;
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด',
                        html: errorMsg,
                        icon: 'error'
                    });
                }
            })
            .catch(function(error) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> บันทึก';
                
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
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
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
