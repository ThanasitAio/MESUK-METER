# MESUK-METER

ระบบ MESUK-METER ที่รันได้โดยไม่ต้องพึ่ง XAMPP

## วิธีการรันโปรเจค

### วิธีที่ 1: ใช้ PHP Built-in Server (แนะนำสำหรับการพัฒนา)

#### ข้อกำหนด:
- PHP 7.4 ขึ้นไป (ติดตั้งแยกต่างหาก)
- MySQL หรือ MariaDB (ติดตั้งแยกต่างหาก)

#### วิธีรัน:

**สำหรับ Windows (PowerShell):**
```powershell
.\start-server.ps1
```

**หรือใช้ Command Prompt:**
```cmd
start-server.bat
```

**หรือรันด้วยคำสั่ง PHP โดยตรง:**
```bash
php -S localhost:8000 -t .
```

เปิดเบราว์เซอร์ที่: `http://localhost:8000`

---

### วิธีที่ 2: ใช้ Docker (แนะนำที่สุด - ไม่ต้องติดตั้งอะไรเลย)

#### ข้อกำหนด:
- Docker Desktop (ดาวน์โหลดได้ที่ https://www.docker.com/products/docker-desktop)

#### วิธีรัน:

1. **เริ่มต้น Docker:**
```bash
docker-compose up -d
```

2. **เข้าใช้งานระบบ:**
- เว็บไซต์: `http://localhost:8000`
- phpMyAdmin: `http://localhost:8080`

3. **หยุด Docker:**
```bash
docker-compose down
```

4. **ดู logs:**
```bash
docker-compose logs -f
```

---

## การตั้งค่า Database

### สำหรับ PHP Built-in Server:
แก้ไขไฟล์ `.env`:
```
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=meesuk_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### สำหรับ Docker:
แก้ไขไฟล์ `docker-compose.yml` (ค่าเริ่มต้น: root/secret)

---

## โครงสร้างโปรเจค

```
MESUK-METER/
├── app/
│   ├── controllers/    # Controllers
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── UserManagementController.php  # 🆕 จัดการผู้ใช้
│   │   └── ...
│   ├── models/        # 🆕 Models
│   │   └── User.php   # 🆕 Model สำหรับจัดการข้อมูลผู้ใช้
│   ├── core/          # Core classes
│   └── utils/         # Utilities
├── assets/
│   ├── css/          # CSS files
│   └── js/           # JavaScript files
├── config/           # Configuration files
├── views/            # View templates
│   └── pages/
│       ├── user-management/  # 🆕 หน้าจัดการผู้ใช้
│       │   ├── index.php     # 🆕 รายการผู้ใช้
│       │   └── form.php      # 🆕 ฟอร์มเพิ่ม/แก้ไข
│       └── ...
├── public/
│   └── uploads/
│       └── users/    # 🆕 รูปภาพผู้ใช้
├── index.php         # Entry point
└── .env              # Environment variables
```

---

## 🆕 ระบบจัดการผู้ใช้ (User Management System)

### ฟีเจอร์หลัก
- ✅ แสดงรายการผู้ใช้ทั้งหมดในรูปแบบตาราง
- ✅ เพิ่มผู้ใช้ใหม่ (พร้อมอัพโหลดรูปภาพ)
- ✅ แก้ไขข้อมูลผู้ใช้
- ✅ ลบผู้ใช้
- ✅ เปลี่ยนสถานะผู้ใช้ (ใช้งาน/ระงับ)
- ✅ จัดการ Role (Admin, Agent, User)
- ✅ แสดงสถิติผู้ใช้

### เริ่มใช้งาน
1. **อัพเดทฐานข้อมูล:**
   ```sql
   source update_me_users_table.sql;
   ```

2. **ตรวจสอบความพร้อม:**
   ```
   http://localhost:8000/check_user_system.php
   ```

3. **เข้าสู่ระบบ:**
   - URL: `http://localhost:8000/users`
   - Username: `0000999`
   - Password: `999`
   - Role: `admin`

### 📚 เอกสารเพิ่มเติม
- [Quick Start Guide](QUICKSTART_USER_MANAGEMENT.md) - เริ่มใช้งานใน 3 ขั้นตอน
- [User Management Guide](USER_MANAGEMENT_GUIDE.md) - คู่มือใช้งานฉบับเต็ม
- [Summary](USER_MANAGEMENT_SUMMARY.md) - สรุประบบแบบย่อ
- [File List](USER_MANAGEMENT_FILES.md) - รายการไฟล์ทั้งหมด

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
