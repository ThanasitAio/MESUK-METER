# ✅ Quick Fix - Constructor Error

## ปัญหา
```
Fatal error: Cannot call constructor in UserManagementController.php on line 7
```

## แก้ไขแล้ว! ✅

### สิ่งที่แก้
1. ✅ ลบ `parent::__construct()` (บรรทัด 7)
2. ✅ เปลี่ยนจาก `$this->model('User')` เป็น `new User()`
3. ✅ เพิ่ม `require_once` สำหรับ Model และ User class

### ไฟล์ที่แก้
- `app/controllers/UserManagementController.php`

## ทดสอบ

### 1. เปิดหน้าจัดการผู้ใช้
```
http://localhost:8000/users
```

### 2. ควรแสดง
- ✅ หน้าจัดการผู้ใช้
- ✅ ตารางผู้ใช้
- ✅ สถิติ
- ❌ ไม่มี error

## หากยังมีปัญหา

### Error: Class 'Database' not found
```php
// ตรวจสอบว่า Database class ถูก autoload หรือไม่
// เพิ่มใน index.php:
require_once __DIR__ . '/app/core/Database.php';
```

### Error: Auth::check() not defined
```php
// ตรวจสอบว่า Auth class ถูก load
// เพิ่มใน index.php:
require_once __DIR__ . '/app/utils/Auth.php';
```

### Error: t() function not found
```php
// ตรวจสอบว่า Language helper ถูก load
// เพิ่มใน index.php:
require_once __DIR__ . '/app/utils/Language.php';
```

## เสร็จแล้ว!
ระบบจัดการผู้ใช้พร้อมใช้งานบน PHP 5.6 แล้ว! 🎉
