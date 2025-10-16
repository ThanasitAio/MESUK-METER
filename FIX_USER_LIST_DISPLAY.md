# แก้ไขปัญหาการแสดงรายการผู้ใช้และลบบทบาท User

## วันที่: 16 ตุลาคม 2025

## ปัญหาที่พบ
1. รายการผู้ใช้ไม่แสดง (แม้ว่าจะมีข้อมูลในฐานข้อมูล)
2. ต้องการลบบทบาท "User" ออก เหลือเฉพาะ Admin และ Agent

## สาเหตุ
1. ใช้ `$data['users']` และ `$data['stats']` แทนที่จะใช้ `$users` และ `$stats` โดยตรง
   - เนื่องจาก Controller ใช้ `extract($data)` ใน `view()` method
   - ทำให้ตัวแปร `$users` และ `$stats` ถูกสร้างขึ้นโดยอัตโนมัติ
2. ยังมีตัวเลือก "User" role ในหน้าฟอร์มและตัวกรอง

## การแก้ไข

### 1. แก้ไขไฟล์ `views/pages/user-management/index.php`

#### เปลี่ยนการเข้าถึงข้อมูล:
```php
// เดิม
<?php echo number_format($data['stats']['total']); ?>
<?php foreach ($data['users'] as $index => $user): ?>

// แก้เป็น
<?php echo number_format(isset($stats['total']) ? $stats['total'] : 0); ?>
<?php foreach ($users as $index => $user): ?>
```

#### ลดจำนวนการ์ดสถิติจาก 6 เป็น 5:
- ลบการ์ด "User Count" ออก
- เหลือเฉพาะ: Total, Active, Suspended, Admin, Agent
- ปรับ grid layout จาก `col-lg-2` เป็น `col-md-3` สำหรับการแสดงผลที่ดีขึ้น

#### ลบตัวเลือก "User" จากตัวกรองบทบาท:
```php
<select class="form-select form-select-sm" id="filterRole">
    <option value="">ทั้งหมด</option>
    <option value="admin">Admin</option>
    <option value="agent">Agent</option>
    <!-- ลบบรรทัดนี้: <option value="user">User</option> -->
</select>
```

#### เพิ่มการตรวจสอบข้อมูลก่อนแสดง:
```php
<?php 
$userCount = isset($users) && is_array($users) ? count($users) : 0;
echo cardHeader(
    t('user_management.user_list'), 
    $userCount, 
    'fas fa-users'
); 
?>

<?php if (isset($users) && !empty($users)): ?>
    <!-- แสดงตาราง -->
<?php endif; ?>
```

### 2. แก้ไขไฟล์ `views/pages/user-management/form.php`

#### ลบตัวเลือก "User" จาก dropdown บทบาท:
```php
<select class="form-control" id="role" name="role" required>
    <?php
    $currentRole = isset($_SESSION['old']['role']) ? $_SESSION['old']['role'] : (isset($data['user']['role']) ? $data['user']['role'] : 'agent');
    ?>
    <option value="agent" <?php echo $currentRole === 'agent' ? 'selected' : ''; ?>>Agent</option>
    <option value="admin" <?php echo $currentRole === 'admin' ? 'selected' : ''; ?>>Admin</option>
    <!-- ลบบรรทัดนี้: <option value="user">User</option> -->
</select>
```

#### เปลี่ยน default role จาก 'user' เป็น 'agent':
```php
// เดิม
$currentRole = ... : 'user');

// แก้เป็น
$currentRole = ... : 'agent');
```

### 3. แก้ไขไฟล์ `app/controllers/UserManagementController.php`

#### ลบการนับ "user" role ออกจาก stats:
```php
public function index() {
    $users = $this->userModel->getAllUsers();
    $stats = array(
        'total' => $this->userModel->countUsers(),
        'active' => $this->userModel->countUsers(array('status' => 'active')),
        'suspended' => $this->userModel->countUsers(array('status' => 'suspended')),
        'admin' => $this->userModel->countUsers(array('role' => 'admin')),
        'agent' => $this->userModel->countUsers(array('role' => 'agent'))
        // ลบบรรทัดนี้: 'user' => $this->userModel->countUsers(array('role' => 'user'))
    );
    
    $data = array(
        'title' => t('user_management.title'),
        'users' => $users,
        'stats' => $stats
    );
    
    $this->view('pages/user-management/index', $data);
}
```

## ผลลัพธ์

### ✅ สิ่งที่แก้ไขเสร็จแล้ว:
1. รายการผู้ใช้จะแสดงผลอย่างถูกต้อง (ใช้ `$users` และ `$stats` โดยตรง)
2. การ์ดสถิติแสดง 5 รายการ (ไม่มี User count)
3. ตัวกรองบทบาทมีเฉพาะ Admin และ Agent
4. ฟอร์มเพิ่ม/แก้ไขผู้ใช้มีเฉพาะบทบาท Admin และ Agent
5. ค่าเริ่มต้นของบทบาทเป็น Agent แทน User

### 📋 การตรวจสอบ:
- [x] รายการผู้ใช้แสดงผลทั้งหมด
- [x] การ์ดสถิติแสดงตัวเลขถูกต้อง (5 การ์ด)
- [x] ตัวกรองค้นหา/บทบาท/สถานะทำงานได้
- [x] ปุ่ม Edit, Change Status, Delete ทำงานได้
- [x] ฟอร์มเพิ่มผู้ใช้แสดงเฉพาะ Admin/Agent
- [x] ฟอร์มแก้ไขผู้ใช้แสดงเฉพาะ Admin/Agent

## วิธีทดสอบ
1. เข้าหน้าจัดการผู้ใช้: `/users`
2. ตรวจสอบว่ารายการผู้ใช้แสดงครบถ้วน
3. ตรวจสอบการ์ดสถิติมี 5 รายการ (ไม่มี User)
4. ทดสอบตัวกรองค้นหา, บทบาท (Admin/Agent), สถานะ
5. กดปุ่ม "เพิ่มผู้ใช้" และตรวจสอบว่ามีเฉพาะ Admin/Agent ในดรอปดาวน์
6. แก้ไขผู้ใช้และตรวจสอบดรอปดาวน์บทบาทเช่นกัน

## หมายเหตุ
- โค้ดยังคงเป็น PHP 5.6 compatible (ใช้ `isset()` แทน `??`)
- รองรับภาษาไทยและอังกฤษผ่าน `t()` function
- UI ยังคงตาม Bootstrap pattern เดียวกับหน้า Import Users
