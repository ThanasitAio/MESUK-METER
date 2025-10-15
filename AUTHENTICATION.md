# 🔐 ระบบ Authentication และ Session Management

## ภาพรวม

ระบบความปลอดภัยของ MESUK-METER ใช้ Session-based Authentication เพื่อควบคุมการเข้าถึงและป้องกันผู้ใช้ที่ไม่ได้รับอนุญาต

---

## 📁 ไฟล์ที่เกี่ยวข้อง

### Core Files:
- `app/utils/Auth.php` - Class สำหรับจัดการ Authentication
- `app/controllers/AuthController.php` - Controller สำหรับ Login/Logout
- `views/pages/login.php` - หน้า Login

---

## 🔑 การใช้งาน Auth Class

### 1. ตรวจสอบว่า Login แล้วหรือยัง

```php
if (Auth::check()) {
    echo "User is logged in";
} else {
    echo "User is guest";
}
```

### 2. บังคับให้ต้อง Login

```php
// ถ้าไม่ได้ login จะ redirect ไปหน้า login อัตโนมัติ
Auth::requireLogin();
```

### 3. ดึงข้อมูล User ที่ Login

```php
$user = Auth::user();
echo $user['username'];  // username
echo $user['full_name']; // ชื่อเต็ม
echo $user['role'];      // role (admin, user, etc.)
```

### 4. Login

```php
Auth::login(array(
    'id' => 1,
    'username' => 'admin',
    'email' => 'admin@example.com',
    'full_name' => 'Administrator',
    'role' => 'admin'
));
```

### 5. Logout

```php
Auth::logout();
```

### 6. ตรวจสอบ Role/Permission

```php
// ตรวจสอบ role เดียว
if (Auth::hasRole('admin')) {
    echo "User is admin";
}

// ตรวจสอบหลาย role
if (Auth::hasRole(array('admin', 'editor'))) {
    echo "User is admin or editor";
}

// บังคับให้ต้องมี role ที่กำหนด
Auth::requireRole('admin'); // ถ้าไม่ใช่ admin จะแสดง 403 Forbidden
```

### 7. ป้องกันผู้ที่ Login แล้วเข้าหน้า Login ซ้ำ

```php
// ใช้ในหน้า login
Auth::guest(); // ถ้า login แล้ว จะ redirect ไปหน้าหลัก
```

---

## 🛡️ ฟีเจอร์ความปลอดภัย

### 1. Session Timeout
- Session จะหมดอายุใน **30 นาที** (1800 วินาที)
- ถ้า inactive เกินเวลา จะถูก logout อัตโนมัติ

### 2. Session Regeneration
- ใช้ `session_regenerate_id()` เมื่อ login
- ป้องกัน Session Fixation Attack

### 3. Remember Intended URL
- เก็บ URL ที่ user พยายามเข้าถึง
- Redirect กลับไปหลัง login สำเร็จ

### 4. Secure Session Handling
- ลบ session อย่างถูกต้องเมื่อ logout
- ลบ session cookie ด้วย

---

## 📝 ตัวอย่างการใช้งานใน Controller

### HomeController.php

```php
<?php
require_once __DIR__ . '/../utils/Auth.php';

class HomeController extends Controller
{
    public function index()
    {
        // ✅ ตรวจสอบว่า login แล้วหรือยัง
        Auth::requireLogin();
        
        // ดึงข้อมูล user
        $user = Auth::user();
        
        $data = array(
            'title' => 'Dashboard',
            'user' => $user
        );
        
        $this->view('home', $data);
    }
    
    public function adminOnly()
    {
        // ✅ เฉพาะ admin เท่านั้น
        Auth::requireRole('admin');
        
        // code ที่เฉพาะ admin ทำได้
    }
}
```

### AuthController.php

```php
<?php
require_once __DIR__ . '/../utils/Auth.php';

class AuthController
{
    public function showLoginForm()
    {
        // ถ้า login แล้ว redirect ไปหน้าหลัก
        Auth::guest();
        
        require_once __DIR__ . '/../../views/pages/login.php';
    }
    
    public function login()
    {
        // รับข้อมูลจากฟอร์ม
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // ตรวจสอบ username/password จาก database
        $user = $this->validateUser($username, $password);
        
        if ($user) {
            // Login สำเร็จ - เก็บ session
            Auth::login(array(
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role']
            ));
            
            // Redirect ไปหน้าหลัก
            header('Location: /');
            exit();
        } else {
            // Login ไม่สำเร็จ
            $_SESSION['error'] = 'Username หรือ Password ไม่ถูกต้อง';
            header('Location: /login');
            exit();
        }
    }
    
    public function logout()
    {
        Auth::logout();
        $_SESSION['success'] = 'ออกจากระบบสำเร็จ';
        header('Location: /login');
        exit();
    }
}
```

---

## 🔐 ตัวอย่างการใช้งานใน View

### navbar.php

```php
<?php
$user = Auth::user();

if ($user) {
    echo "สวัสดี, " . htmlspecialchars($user['full_name']);
} else {
    echo '<a href="/login">เข้าสู่ระบบ</a>';
}
?>
```

### แสดงเมนูตาม Role

```php
<?php if (Auth::hasRole('admin')): ?>
    <li><a href="/admin/users">จัดการผู้ใช้</a></li>
    <li><a href="/admin/settings">ตั้งค่าระบบ</a></li>
<?php endif; ?>
```

---

## 📊 Session Data Structure

เมื่อ login สำเร็จ จะเก็บข้อมูลเหล่านี้ใน session:

```php
$_SESSION = array(
    'user_id' => 1,
    'username' => 'admin',
    'email' => 'admin@example.com',
    'full_name' => 'Administrator',
    'role' => 'admin',
    'logged_in_at' => 1697356800,   // timestamp เมื่อ login
    'last_activity' => 1697356850    // timestamp ล่าสุดที่ใช้งาน
);
```

---

## 🚀 Routes ที่เกี่ยวข้อง

```php
'GET' => array(
    '/login' => 'AuthController@showLoginForm',
    '/logout' => 'AuthController@logout',
    '/' => 'HomeController@index', // ต้อง login
),
'POST' => array(
    '/login' => 'AuthController@login',
)
```

---

## ✅ Checklist การปรับใช้

### ใน Controller:
- [ ] เพิ่ม `require_once __DIR__ . '/../utils/Auth.php';`
- [ ] ใช้ `Auth::requireLogin()` ในทุก method ที่ต้องการความปลอดภัย
- [ ] ใช้ `Auth::requireRole()` สำหรับ role-based access
- [ ] ใช้ `Auth::guest()` ในหน้า login

### ใน View:
- [ ] ใช้ `Auth::user()` เพื่อดึงข้อมูล user
- [ ] ใช้ `Auth::check()` เพื่อตรวจสอบว่า login หรือไม่
- [ ] ใช้ `Auth::hasRole()` เพื่อแสดง/ซ่อนเมนูตาม permission

### Routes:
- [ ] เพิ่ม route `/logout`
- [ ] ตรวจสอบว่า route ที่ต้องการความปลอดภัยมี `Auth::requireLogin()`

---

## 🔒 Best Practices

1. **อย่า hardcode password** - ใช้ `password_hash()` และ `password_verify()`
2. **ใช้ HTTPS** - ในการ production
3. **Timeout ที่เหมาะสม** - ปรับได้ใน `Auth::requireLogin()`
4. **Validate input** - ทุกครั้งที่รับข้อมูลจาก user
5. **Log การ login** - เพื่อตรวจสอบการเข้าถึง
6. **XSS Protection** - ใช้ `htmlspecialchars()` เมื่อแสดงข้อมูล user

---

## 🐛 การแก้ปัญหา

### ปัญหา: Session หมดอายุเร็วเกินไป
**วิธีแก้:** แก้ไขค่า timeout ใน `Auth::requireLogin()`
```php
// จาก 1800 (30 นาที) เป็น 3600 (1 ชั่วโมง)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
```

### ปัญหา: ไม่สามารถ logout ได้
**วิธีแก้:** ตรวจสอบว่ามี route `/logout` และเรียก `Auth::logout()` ถูกต้อง

### ปัญหา: Redirect loop
**วิธีแก้:** ตรวจสอบว่าหน้า login ใช้ `Auth::guest()` และไม่มี `Auth::requireLogin()`

---

## 📚 อ่านเพิ่มเติม

- [PHP Session Security](https://www.php.net/manual/en/session.security.php)
- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)

---

**สร้างโดย:** MESUK-METER Development Team  
**อัพเดทล่าสุด:** 15 ตุลาคม 2025
