# สรุปการแก้ไข Base Path สำหรับ Apache และ Docker

## การเปลี่ยนแปลงหลัก

### 1. Configuration Files

#### config/app.php
- เพิ่ม `base_path` configuration
- สำหรับ Apache: `'base_path' => '/mesuk'`
- สำหรับ Docker: `'base_path' => ''`

#### .htaccess
- แก้ไข `RewriteBase` ให้รองรับ subfolder
- สำหรับ Apache: `RewriteBase /mesuk/`
- สำหรับ Docker: `RewriteBase /`

### 2. Helper Functions (app/utils/helpers.php)

เพิ่มฟังก์ชัน:
```php
function basePath($path = '') {
    // อ่าน base_path จาก config
    // คืนค่า path ที่มี base path prefix
}

function url($path = '') {
    // เรียกใช้ basePath()
    // ใช้ในการสร้าง URL ทั้งหมด
}
```

### 3. View Files แก้ไขแล้ว

#### views/pages/login.php
- แก้ favicon และ logo paths ใช้ `basePath()`
- แก้ form action ใช้ `url('/login')`

#### views/home.php
- แก้ quick actions links ใช้ `url()`

#### views/pages/product-management/form.php
- แก้ form action ใช้ `url()`
- แก้ cancel button ใช้ `url()`
- แก้ window.location.replace ใช้ PHP `url()`

#### views/pages/product-management/index.php
- แก้ edit links ใช้ `url()`

#### views/pages/user-management/form.php
- แก้ form action ใช้ `url()`
- แก้ cancel button ใช้ `url()`
- แก้ window.location.replace ใช้ PHP `url()`

#### views/errors/404.php
- แก้ redirect ใช้ `url('/')`

#### views/layouts/main.php
- มีการใช้ `basePath()` อยู่แล้ว
- เพิ่ม `window.APP_BASE_PATH` สำหรับ JavaScript

#### views/partials/navbar.php
- ใช้ `url()` สำหรับ links ทั้งหมด
- ใช้ `basePath()` สำหรับ images

#### views/partials/sidebar.php
- ใช้ `url()` สำหรับ navigation links ทั้งหมด

### 4. JavaScript Files แก้ไขแล้ว

#### assets/js/app.js
- เพิ่ม `this.basePath = window.APP_BASE_PATH || ''`
- แก้ `this.baseUrl` ให้รวม basePath
- แก้ redirect ใช้ `this.basePath + '/login'`

#### assets/js/sidebar.js
- แก้ฟังก์ชัน `setActiveMenu()` ให้รองรับ base path
- ใช้ `window.APP_BASE_PATH` ในการตรวจสอบ active menu

### 5. Core Files แก้ไขแล้ว

#### app/core/Router.php
- มีการอ่าน base_path จาก config อยู่แล้ว
- ตัด base_path ออกจาก URL ก่อนจับคู่ routes

#### index.php
- มีการจัดการ base_path สำหรับ static files อยู่แล้ว

### 6. Controllers

Controllers ทั้งหมดใช้ `url()` function แล้ว:
- AuthController.php
- UserManagementController.php
- ProductManagementController.php
- MeterManagementController.php
- InvoiceManagementController.php
- PaymentManagementController.php
- LanguageController.php

### 7. Files สำหรับ Reference

สร้างไฟล์ตัวอย่าง:
- `config/app.apache.example.php` - Config สำหรับ Apache
- `config/app.docker.example.php` - Config สำหรับ Docker
- `.htaccess.apache.example` - .htaccess สำหรับ Apache
- `.htaccess.docker.example` - .htaccess สำหรับ Docker
- `DEPLOYMENT_APACHE.md` - คู่มือการ deploy

## วิธีการใช้งาน

### สำหรับ Apache (Production)

1. คัดลอกไฟล์ไปยัง `/var/www/html/mesuk/`
2. แก้ไข `config/app.php`:
   ```php
   'base_path' => '/mesuk'
   ```
3. แก้ไข `.htaccess`:
   ```apache
   RewriteBase /mesuk/
   ```
4. เข้าถึง: `http://localhost/mesuk/`

### สำหรับ Docker (Development)

1. แก้ไข `config/app.php`:
   ```php
   'base_path' => ''
   ```
2. แก้ไข `.htaccess`:
   ```apache
   RewriteBase /
   ```
3. รัน: `docker-compose up -d`
4. เข้าถึง: `http://localhost:8000/`

## การทดสอบ

- [x] Login page แสดงถูกต้อง
- [x] Static files (CSS, JS, images) โหลดได้
- [x] Navigation links ทำงานถูกต้อง
- [x] Form submissions ทำงานถูกต้อง
- [x] Redirects ทำงานถูกต้อง
- [x] Active menu highlighting ทำงานถูกต้อง
- [x] Language switching ทำงานถูกต้อง

## สรุป

ระบบถูกแก้ไขให้รองรับ base path แบบ dynamic แล้ว สามารถใช้งานได้ทั้ง Apache (subfolder) และ Docker (root) โดยแค่แก้ไข config file และ .htaccess เท่านั้น ไม่ต้องแก้ code อื่นๆ
