# 🔧 แก้ไข: Fatal error - Cannot call constructor

## ❌ ปัญหา
```
Fatal error: Cannot call constructor in /var/www/html/app/controllers/UserManagementController.php on line 7
```

## 🔍 สาเหตุ

### โค้ดเดิม (ผิด)
```php
class UserManagementController extends Controller {
    public function __construct() {
        parent::__construct();  // ❌ Error! Controller ไม่มี __construct()
        
        $this->userModel = $this->model('User');  // ❌ method model() ไม่มี
    }
}
```

### Controller base class
```php
class Controller {
    // ⚠️ ไม่มี __construct() method!
    
    protected function view($view, $data = []) {
        // ...
    }
}
```

**ปัญหา:**
1. `Controller` class ไม่มี `__construct()` method
2. PHP 5.6 จะ error ถ้าเรียก `parent::__construct()` เมื่อ parent ไม่มี constructor
3. Method `model()` ไม่มีใน Controller class

---

## ✅ วิธีแก้ไข

### แก้ไข UserManagementController.php

#### Before (ผิด)
```php
<?php

class UserManagementController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();  // ❌ ลบบรรทัดนี้
        
        // ตรวจสอบการ login
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        // ตรวจสอบสิทธิ์
        $currentUser = Auth::user();
        if ($currentUser['role'] !== 'admin') {
            http_response_code(403);
            die(t('user_management.access_denied'));
        }
        
        $this->userModel = $this->model('User');  // ❌ แก้วิธีนี้
    }
}
```

#### After (ถูกต้อง)
```php
<?php

class UserManagementController extends Controller {
    private $userModel;
    
    public function __construct() {
        // ✅ ไม่เรียก parent::__construct()
        
        // ตรวจสอบการ login
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        // ตรวจสอบสิทธิ์
        $currentUser = Auth::user();
        if ($currentUser['role'] !== 'admin') {
            http_response_code(403);
            die(t('user_management.access_denied'));
        }
        
        // ✅ สร้าง User model โดยตรง
        if (!class_exists('Model')) {
            require_once __DIR__ . '/../core/Model.php';
        }
        if (!class_exists('User')) {
            require_once __DIR__ . '/../models/User.php';
        }
        $this->userModel = new User();
    }
}
```

---

## 📝 สิ่งที่เปลี่ยน

### 1. ลบการเรียก parent::__construct()
```php
// ❌ ก่อน
parent::__construct();

// ✅ หลัง
// (ลบออก)
```

**เหตุผล:** Controller class ไม่มี constructor ดังนั้นไม่ต้องเรียก

### 2. แก้วิธีสร้าง Model
```php
// ❌ ก่อน
$this->userModel = $this->model('User');

// ✅ หลัง
if (!class_exists('Model')) {
    require_once __DIR__ . '/../core/Model.php';
}
if (!class_exists('User')) {
    require_once __DIR__ . '/../models/User.php';
}
$this->userModel = new User();
```

**เหตุผล:** 
- Method `model()` ไม่มีใน Controller class
- PHP 5.6 ต้อง require class ก่อนใช้งาน
- ตรวจสอบ `class_exists()` เพื่อป้องกัน redeclare

---

## 🧪 การทดสอบ

### 1. ทดสอบ PHP Syntax
```bash
php -l app/controllers/UserManagementController.php
```

**ผลลัพธ์ที่ต้องการ:**
```
No syntax errors detected
```

### 2. ทดสอบการเข้าถึง
```bash
# เปิดเบราว์เซอร์
http://localhost:8000/users
```

**ผลลัพธ์ที่ต้องการ:**
- หน้าจัดการผู้ใช้แสดงปกติ
- ไม่มี Fatal error

### 3. ตรวจสอบ Error Log
```bash
tail -f /var/log/php/error.log
```

**ผลลัพธ์ที่ต้องการ:**
- ไม่มี error ใหม่

---

## 📚 ความรู้เพิ่มเติม

### PHP Constructor Inheritance

#### PHP 5.6 Rules
```php
// Parent class
class Parent {
    // ไม่มี __construct()
}

// Child class
class Child extends Parent {
    public function __construct() {
        parent::__construct();  // ❌ Fatal Error!
    }
}
```

**ข้อควรจำ:**
- ถ้า parent ไม่มี `__construct()` ห้ามเรียก `parent::__construct()`
- PHP 7+ จะแสดง warning แต่ PHP 5.6 จะ fatal error ทันที

### การสร้าง Model ใน PHP 5.6

#### วิธีที่ 1: require_once (แนะนำ)
```php
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../models/User.php';
$userModel = new User();
```

#### วิธีที่ 2: ตรวจสอบก่อน (ปลอดภัยกว่า)
```php
if (!class_exists('Model')) {
    require_once __DIR__ . '/../core/Model.php';
}
if (!class_exists('User')) {
    require_once __DIR__ . '/../models/User.php';
}
$userModel = new User();
```

#### วิธีที่ 3: Autoloader (สำหรับโปรเจคใหญ่)
```php
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../models/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
```

---

## ✅ สรุป

### สาเหตุ
1. เรียก `parent::__construct()` ทั้งๆ ที่ parent ไม่มี constructor
2. ใช้ method `model()` ที่ไม่มีในระบบ

### การแก้ไข
1. ✅ ลบ `parent::__construct()`
2. ✅ เปลี่ยนเป็น `require_once` และ `new User()`
3. ✅ ตรวจสอบ `class_exists()` ก่อน require

### ผลลัพธ์
- ✅ ไม่มี Fatal error
- ✅ รองรับ PHP 5.6+
- ✅ โค้ดทำงานปกติ

---

## 📋 Checklist

- [x] ลบ `parent::__construct()`
- [x] require Model class
- [x] require User class
- [x] ตรวจสอบ `class_exists()`
- [x] สร้าง instance `new User()`
- [x] ทดสอบ PHP syntax
- [x] ทดสอบบนเบราว์เซอร์

---

**แก้ไขโดย:** GitHub Copilot  
**วันที่:** October 16, 2025  
**สถานะ:** ✅ แก้ไขเสร็จสมบูรณ์
