# คู่มือการติดตั้งบน Apache/XAMPP (localhost)

## 📋 ข้อกำหนด

- **Web Server:** Apache 2.4+ (XAMPP, WAMP, หรือ Apache standalone)
- **PHP:** 5.6 ขึ้นไป (แนะนำ 7.4 หรือ 8.x)
- **Database:** MySQL 5.7+ หรือ MariaDB
- **Apache Modules:** mod_rewrite (จำเป็น)

## 🚀 วิธีติดตั้งบน XAMPP

### 1. ติดตั้ง XAMPP
1. ดาวน์โหลด XAMPP จาก https://www.apachefriends.org/
2. ติดตั้งตามขั้นตอน
3. เปิด XAMPP Control Panel
4. Start Apache และ MySQL

### 2. วาง Project ใน htdocs
```
C:\xampp\htdocs\mesuk\     <-- วาง project ที่นี่
```

หรือถ้าใช้ path อื่น:
```
C:\xampp\htdocs\meter\
C:\xampp\htdocs\projects\mesuk\
```

### 3. ตั้งค่า Base Path

แก้ไขไฟล์ `config/app.php`:

```php
// กรณีวางที่ htdocs/mesuk/
'base_path' => '/mesuk'

// กรณีวางที่ htdocs/meter/
'base_path' => '/meter'

// กรณีวางที่ htdocs/ (root)
'base_path' => ''

// กรณีวางที่ htdocs/projects/mesuk/
'base_path' => '/projects/mesuk'
```

### 4. ตั้งค่า .htaccess

แก้ไขไฟล์ `.htaccess` ให้ตรงกับ base_path:

```apache
# ถ้า base_path = '/mesuk'
RewriteBase /mesuk/

# ถ้า base_path = '/meter'
RewriteBase /meter/

# ถ้า base_path = '' (root)
RewriteBase /
```

### 5. ตรวจสอบ mod_rewrite

#### บน Windows (XAMPP):
1. เปิดไฟล์ `C:\xampp\apache\conf\httpd.conf`
2. ค้นหา `#LoadModule rewrite_module modules/mod_rewrite.so`
3. ลบ `#` ออก (uncomment):
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. บันทึกไฟล์
5. Restart Apache

#### ตรวจสอบว่าเปิดใช้งานแล้ว:
สร้างไฟล์ `phpinfo.php`:
```php
<?php phpinfo(); ?>
```
เปิด `http://localhost/mesuk/phpinfo.php` และค้นหา "mod_rewrite"

### 6. ตั้งค่า AllowOverride

เปิดไฟล์ `C:\xampp\apache\conf\httpd.conf` และแก้ไข:

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All    # <-- แก้จาก None เป็น All
    Require all granted
</Directory>
```

Restart Apache

### 7. ตั้งค่า Database

1. เปิด phpMyAdmin: `http://localhost/phpmyadmin`
2. สร้าง database ชื่อ `mesuk_db`
3. Import ไฟล์ `database.sql`
4. แก้ไข `config/database.php`:
   ```php
   'host' => 'localhost',
   'database' => 'mesuk_db',
   'username' => 'root',
   'password' => '',  // XAMPP default ไม่มี password
   ```

## 🧪 ทดสอบ

### 1. เปิดหน้าทดสอบ
```
http://localhost/mesuk/test_base_path.php
```

ควรเห็น:
- ✅ Base Path: `/mesuk`
- ✅ URL functions ทำงานถูกต้อง
- ✅ ไม่มี error

### 2. เข้าระบบ
```
http://localhost/mesuk/login
```

หรือ
```
http://localhost/mesuk/
```

### 3. ทดสอบเมนู
คลิกเมนูต่างๆ และตรวจสอบ URL:
- Meters → `http://localhost/mesuk/meters` ✅
- Invoices → `http://localhost/mesuk/invoices` ✅
- Users → `http://localhost/mesuk/users` ✅

## 📁 โครงสร้างโฟลเดอร์

```
C:\xampp\htdocs\mesuk\
├── .htaccess                 # <-- สำคัญ! ต้องมี
├── index.php
├── config/
│   ├── app.php              # <-- ตั้งค่า base_path
│   └── database.php
├── app/
├── assets/
├── views/
└── ...
```

## ⚙️ การตั้งค่าเพิ่มเติม

### Virtual Host (สำหรับ Custom Domain)

ถ้าต้องการใช้ `http://mesuk.local` แทน `http://localhost/mesuk`:

1. แก้ไข `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/mesuk"
    ServerName mesuk.local
    
    <Directory "C:/xampp/htdocs/mesuk">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

2. แก้ไข `C:\Windows\System32\drivers\etc\hosts` (Run as Administrator):

```
127.0.0.1    mesuk.local
```

3. แก้ไข `config/app.php`:

```php
'url' => 'http://mesuk.local',
'base_path' => ''  # เพราะอยู่ที่ root ของ domain แล้ว
```

4. แก้ไข `.htaccess`:

```apache
RewriteBase /
```

5. Restart Apache

6. เข้าได้ที่: `http://mesuk.local`

## 🐛 Troubleshooting

### ปัญหา: 404 Not Found
**สาเหตุ:** mod_rewrite ไม่ทำงาน
**แก้ไข:**
1. ตรวจสอบว่า mod_rewrite เปิดใช้งานแล้ว
2. ตรวจสอบ AllowOverride = All
3. ตรวจสอบว่ามีไฟล์ .htaccess
4. Restart Apache

### ปัญหา: Internal Server Error (500)
**สาเหตุ:** .htaccess มีปัญหา
**แก้ไข:**
1. ตรวจสอบ syntax ใน .htaccess
2. ตรวจสอบ RewriteBase ตรงกับ path จริง
3. ดู error log: `C:\xampp\apache\logs\error.log`

### ปัญหา: CSS/JS ไม่โหลด
**สาเหตุ:** path ไม่ถูกต้อง
**แก้ไข:**
1. ตรวจสอบ base_path ใน config/app.php
2. ตรวจสอบว่าใช้ basePath() ในไฟล์ layout
3. เปิด DevTools (F12) ดู Network tab

### ปัญหา: Database Connection Failed
**แก้ไข:**
1. ตรวจสอบว่า MySQL running
2. ตรวจสอบ username/password ใน config/database.php
3. ตรวจสอบว่าสร้าง database แล้ว

## 📊 สรุป URL Structure

### กรณี: วางที่ htdocs/mesuk/ (แนะนำ)
```
Config:
  'base_path' => '/mesuk'

URLs:
  http://localhost/mesuk/
  http://localhost/mesuk/login
  http://localhost/mesuk/meters
  http://localhost/mesuk/invoices
```

### กรณี: วางที่ htdocs/ (root)
```
Config:
  'base_path' => ''

URLs:
  http://localhost/
  http://localhost/login
  http://localhost/meters
  http://localhost/invoices
```

### กรณี: ใช้ Virtual Host
```
Config:
  'base_path' => ''

URLs:
  http://mesuk.local/
  http://mesuk.local/login
  http://mesuk.local/meters
  http://mesuk.local/invoices
```

## ✅ Checklist การติดตั้ง

- [ ] ติดตั้ง XAMPP
- [ ] Start Apache และ MySQL
- [ ] วาง project ใน htdocs
- [ ] ตั้งค่า base_path ใน config/app.php
- [ ] แก้ไข RewriteBase ใน .htaccess
- [ ] เปิดใช้ mod_rewrite
- [ ] ตั้งค่า AllowOverride = All
- [ ] สร้าง database และ import ข้อมูล
- [ ] ตั้งค่า database credentials
- [ ] ทดสอบที่ http://localhost/mesuk/test_base_path.php
- [ ] ทดสอบ login
- [ ] ทดสอบเมนูทั้งหมด
- [ ] ลบไฟล์ phpinfo.php และ test_base_path.php

## 📞 Support

หากพบปัญหา:
1. ตรวจสอบ Apache error log
2. ตรวจสอบ PHP error log (error.log ใน project)
3. เปิด browser DevTools (F12)
4. อ่าน BASE_PATH_GUIDE.md

---

**เวอร์ชัน:** 1.0.0  
**สำหรับ:** Apache/XAMPP บน Windows  
**อัพเดท:** 3 พฤศจิกายน 2025
