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
│   ├── core/          # Core classes
│   └── utils/         # Utilities
├── assets/
│   ├── css/          # CSS files
│   └── js/           # JavaScript files
├── config/           # Configuration files
├── views/            # View templates
├── index.php         # Entry point
└── .env              # Environment variables
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
