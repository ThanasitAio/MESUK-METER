# คู่มือการตั้งค่า Base Path สำหรับ MESUK System

## 📋 ภาพรวม

ระบบได้รับการปรับปรุงให้รองรับการติดตั้งใน subdirectory โดยไม่ต้องแก้ไข code ทุกครั้ง เพียงแค่ตั้งค่าใน config file เดียว

## ⚙️ วิธีการตั้งค่า

### 1. แก้ไขไฟล์ `config/app.php`

```php
return [
    'app' => [
        'name' => 'MESUK',
        'version' => '1.0.0',
        'env' => 'development',
        'debug' => true,
        'url' => 'http://localhost:8000',
        'base_path' => '/mesuk'  // <-- แก้ตรงนี้
    ],
    // ...
];
```

### 2. ตัวอย่างการตั้งค่า Base Path

#### กรณีติดตั้งที่ Root Domain
```php
'base_path' => ''
```
- URL: `http://yourdomain.com/`
- URL: `http://yourdomain.com/meters`
- URL: `http://yourdomain.com/invoices`

#### กรณีติดตั้งใน Subdirectory
```php
'base_path' => '/mesuk'
```
- URL: `http://yourdomain.com/mesuk/`
- URL: `http://yourdomain.com/mesuk/meters`
- URL: `http://yourdomain.com/mesuk/invoices`

#### กรณีติดตั้งใน Nested Subdirectory
```php
'base_path' => '/projects/mesuk'
```
- URL: `http://yourdomain.com/projects/mesuk/`
- URL: `http://yourdomain.com/projects/mesuk/meters`

## 🔧 Helper Functions ที่ใช้งานใน Code

### 1. `url($path)`
สร้าง URL พร้อม base path

```php
// ใน Views
<a href="<?php echo url('/meters'); ?>">มิเตอร์</a>
<a href="<?php echo url('/users/edit/' . $id); ?>">แก้ไข</a>

// ใน Controllers
header('Location: ' . url('/login'));
```

### 2. `basePath($path)`
เหมือน url() แต่เป็น alias

```php
// สำหรับ assets
<link href="<?php echo basePath('/assets/css/app.css'); ?>" rel="stylesheet">
<script src="<?php echo basePath('/assets/js/app.js'); ?>"></script>
<img src="<?php echo basePath('/assets/images/logo.png'); ?>">
```

## 📁 ไฟล์ที่ได้รับการปรับปรุง

### Controllers
- ✅ `app/controllers/AuthController.php`
- ✅ `app/controllers/LanguageController.php`
- ✅ `app/controllers/UserManagementController.php`
- ✅ `app/controllers/MeterManagementController.php`
- ✅ `app/controllers/InvoiceManagementController.php`
- ✅ `app/controllers/PaymentManagementController.php`
- ✅ `app/controllers/ProductManagementController.php`

### Core
- ✅ `app/core/Router.php` - อ่าน base_path จาก config
- ✅ `app/utils/helpers.php` - เพิ่ม url() และ basePath() functions
- ✅ `app/utils/Auth.php` - ใช้ url() ใน redirects

### Views
- ✅ `views/layouts/main.php` - CSS, JS, และ assets
- ✅ `views/partials/sidebar.php` - เมนูทั้งหมด
- ✅ `views/partials/navbar.php` - navbar และ user menu
- ✅ `views/pages/user-management/index.php` - links ต่างๆ
- ✅ `views/errors/404.php` - error page

## 🚀 การ Deploy

### สำหรับ Development (localhost)
```php
// config/app.php
'base_path' => '/mesuk'
```

### สำหรับ Production Server
```php
// config/app.php
'base_path' => ''  // ถ้าอยู่ที่ root domain
// หรือ
'base_path' => '/app'  // ถ้าอยู่ใน subdirectory
```

## ⚠️ สิ่งที่ต้องระวัง

### ❌ ไม่ถูกต้อง
```php
<a href="/meters">มิเตอร์</a>
<a href="/users/edit/<?php echo $id; ?>">แก้ไข</a>
header('Location: /login');
```

### ✅ ถูกต้อง
```php
<a href="<?php echo url('/meters'); ?>">มิเตอร์</a>
<a href="<?php echo url('/users/edit/' . $id); ?>">แก้ไข</a>
header('Location: ' . url('/login'));
```

## 🔍 การตรวจสอบ

### 1. ตรวจสอบการตั้งค่า
```php
// สร้างไฟล์ test.php ที่ root
<?php
require_once __DIR__ . '/app/utils/helpers.php';
define('BASE_PATH', __DIR__);

echo "Base Path: " . basePath() . "<br>";
echo "URL /meters: " . url('/meters') . "<br>";
echo "URL /users/1: " . url('/users/1') . "<br>";
```

### 2. ทดสอบการทำงาน
1. เปิด browser ไปที่ `http://localhost/mesuk/` (หรือ path ที่ตั้งค่า)
2. ทดสอบ login
3. คลิกเมนูต่างๆ ในระบบ
4. ตรวจสอบ URL บน address bar ว่าถูกต้อง

## 📝 หมายเหตุ

1. **ไม่ต้องแก้ไข .htaccess** - Router จะจัดการให้อัตโนมัติ
2. **Assets ยังใช้ path เดิม** - PHP built-in server จัดการ static files
3. **Compatible กับ PHP 5.6+** - ใช้ syntax ที่รองรับ PHP เวอร์ชันเก่า

## 🐛 Troubleshooting

### ปัญหา: เมนูไม่ทำงาน / 404 Error
**วิธีแก้:**
1. ตรวจสอบ `base_path` ใน `config/app.php`
2. ตรวจสอบ error log ใน `error.log`
3. เปิด browser console ดู JavaScript errors

### ปัญหา: CSS/JS ไม่โหลด
**วิธีแก้:**
1. ตรวจสอบว่าใช้ `basePath()` ใน layout
2. ตรวจสอบ path ของ assets folder
3. Clear browser cache

### ปัญหา: Redirect ไปผิดที่
**วิธีแก้:**
1. ตรวจสอบว่าทุก `header('Location:')` ใช้ `url()`
2. ตรวจสอบ intended_url ใน session

## ✅ Checklist การ Deploy

- [ ] แก้ `base_path` ใน `config/app.php`
- [ ] แก้ `database` config สำหรับ production
- [ ] เปลี่ยน `debug` เป็น `false`
- [ ] เปลี่ยน `env` เป็น `'production'`
- [ ] ทดสอบ login/logout
- [ ] ทดสอบเมนูทุกตัว
- [ ] ทดสอบ language switching
- [ ] Backup database ก่อน deploy

## 📞 Support

หากพบปัญหาในการใช้งาน:
1. ตรวจสอบ `error.log` ที่ root directory
2. เปิด browser console ดู JavaScript errors
3. ตรวจสอบว่าใช้ `url()` และ `basePath()` ครบถ้วน

---

**สร้างเมื่อ:** 3 พฤศจิกายน 2025  
**เวอร์ชัน:** 1.0.0  
**อัพเดทล่าสุด:** 3 พฤศจิกายน 2025
