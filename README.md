# MESUK-METER

ระบบจัดการมิเตอร์น้ำ MESUK พร้อม Multi-path Support

## 🚀 วิธีการรันโปรเจค

### 🎯 แนะนำสำหรับคุณ: ใช้ Apache/XAMPP

เหมาะสำหรับ:
- ✅ มีหลายโปรเจ็คในเครื่อง
- ✅ ต้องการ URL แบบ `http://localhost/mesuk/`
- ✅ พร้อม deploy production
- ✅ จำลองสภาพแวดล้อมจริง

#### 📖 คู่มือด่วน 3 ขั้นตอน → [QUICKSTART_APACHE.md](./QUICKSTART_APACHE.md)
#### 📚 คู่มือละเอียด → [APACHE_SETUP_GUIDE.md](./APACHE_SETUP_GUIDE.md)

**TL;DR:**
1. วางโปรเจ็คใน `C:\xampp\htdocs\mesuk\`
2. ตั้งค่า `base_path = '/mesuk'` ใน `config/app.php`
3. แก้ `RewriteBase /mesuk/` ใน `.htaccess`
4. เปิด mod_rewrite และ restart Apache
5. เข้า `http://localhost/mesuk/`

---

### วิธีอื่นๆ (สำหรับพัฒนา)

#### วิธีที่ 1: PHP Built-in Server

**ข้อกำหนด:**
- PHP 5.6+ 
- MySQL/MariaDB

**วิธีรัน:**
```bash
php -S localhost:8000
```
เข้า: `http://localhost:8000`

**หมายเหตุ:** ไม่เหมาะกับหลายโปรเจ็ค
```bash
docker-compose down
```

4. **ดู logs:**
```bash
docker-compose logs -f
```

---

## ⚙️ การตั้งค่า

### 1. Base Path Configuration

ระบบรองรับการติดตั้งทั้ง root domain และ subdirectory:

```php
// config/app.php

// สำหรับ http://localhost/mesuk/
'base_path' => '/mesuk'

// สำหรับ http://localhost/ (root)
'base_path' => ''

// สำหรับ http://localhost/projects/mesuk/
'base_path' => '/projects/mesuk'
```

**📖 อ่านเพิ่มเติม:** [BASE_PATH_GUIDE.md](./BASE_PATH_GUIDE.md)

### 2. Database

แก้ไขไฟล์ `config/database.php`:
```php
'host' => 'localhost',
'database' => 'mesuk_db',
'username' => 'root',
'password' => '',
```

### 3. Apache (ถ้าใช้)

แก้ไขไฟล์ `.htaccess` ให้ตรงกับ base_path:
```apache
# ตรงกับ config/app.php
RewriteBase /mesuk/
```

**📖 อ่านเพิ่มเติม:** [APACHE_SETUP_GUIDE.md](./APACHE_SETUP_GUIDE.md)

---

## 🧪 การทดสอบระบบ

### ตรวจสอบ Configuration
```
http://localhost/mesuk/test_base_path.php      # ทดสอบ base path
http://localhost/mesuk/check_apache.php        # ตรวจสอบ Apache config
```

### เข้าสู่ระบบ
```
URL: http://localhost/mesuk/login
Username: 0000999
Password: 999
Role: admin
```

---

## 📁 โครงสร้างโปรเจค

```
MESUK-METER/
├── .htaccess              # Apache rewrite rules
├── index.php              # Entry point
├── config/
│   ├── app.php           # App config (base_path here!)
│   └── database.php      # Database config
├── app/
│   ├── controllers/      # Controllers
│   ├── models/          # Models
│   ├── core/            # Core classes (Router, Database)
│   └── utils/           # Utilities (helpers.php, Auth.php)
├── assets/
│   ├── css/
│   └── js/
├── views/
│   ├── layouts/
│   ├── partials/
│   └── pages/
└── public/
    └── uploads/
```

---

## 📚 เอกสารทั้งหมด

### 🚀 Quick Start
- **[QUICKSTART_APACHE.md](./QUICKSTART_APACHE.md)** - เริ่มใช้ Apache ใน 3 ขั้นตอน ⭐
- **[QUICKSTART_USER_MANAGEMENT.md](./QUICKSTART_USER_MANAGEMENT.md)** - ใช้งานระบบผู้ใช้

### 📖 Guides
- **[APACHE_SETUP_GUIDE.md](./APACHE_SETUP_GUIDE.md)** - คู่มือติดตั้ง Apache/XAMPP
- **[BASE_PATH_GUIDE.md](./BASE_PATH_GUIDE.md)** - เกี่ยวกับ Base Path Configuration
- **[USER_MANAGEMENT_GUIDE.md](./USER_MANAGEMENT_GUIDE.md)** - คู่มือระบบผู้ใช้

### 📋 Documentation
- **[DEPLOYMENT_SUMMARY.md](./DEPLOYMENT_SUMMARY.md)** - สรุปการ deploy
- **[APACHE_READY.md](./APACHE_READY.md)** - สรุปการตั้งค่า Apache
- **[USER_MANAGEMENT_SUMMARY.md](./USER_MANAGEMENT_SUMMARY.md)** - สรุประบบผู้ใช้

---

## 🆕 ระบบจัดการผู้ใช้ (User Management System)

### ฟีเจอร์หลัก
- ✅ แสดงรายการผู้ใช้พร้อมสถิติ
- ✅ เพิ่ม/แก้ไข/ลบผู้ใช้
- ✅ อัพโหลดรูปภาพโปรไฟล์
- ✅ จัดการ Role (Admin, Agent, User)
- ✅ เปลี่ยนสถานะ (Active/Suspended)

### เข้าใช้งาน
```
URL: http://localhost/mesuk/users
Admin: username=0000999, password=999
```

---

## การใช้งาน Git

```bash
# Clone โปรเจค
git clone https://github.com/ThanasitAio/MESUK-METER.git
cd MESUK-METER

# สร้าง .env file
copy .env.example .env

# รันโปรเจค (เลือกวิธีใดวิธีหนึ่งด้านบน)
```

---

## Troubleshooting

### ปัญหา: Port 8000 ถูกใช้งานอยู่
**วิธีแก้:** เปลี่ยน port ในคำสั่งรัน
```bash
php -S localhost:8001 -t .
```

### ปัญหา: ไม่สามารถเชื่อมต่อ Database
**วิธีแก้:** 
1. ตรวจสอบว่า MySQL กำลังรันอยู่
2. ตรวจสอบ credentials ใน `.env`
3. ตรวจสอบว่าได้สร้าง database `meesuk_db` แล้ว

### ปัญหา: Docker ไม่ทำงาน
**วิธีแก้:**
1. ตรวจสอบว่า Docker Desktop เปิดอยู่
2. รัน `docker-compose down` แล้ว `docker-compose up -d` ใหม่

---

## License
All rights reserved.
