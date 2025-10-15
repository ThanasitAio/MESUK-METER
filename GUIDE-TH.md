# คู่มือการใช้งาน MESUK-METER

## 🚀 เริ่มต้นใช้งาน

MESUK-METER สามารถรันได้ **3 วิธี** โดยไม่ต้องพึ่ง XAMPP อีกต่อไป:

---

## 📋 วิธีที่ 1: PHP Built-in Server (ง่ายที่สุด)

### ขั้นตอน:

1. **ติดตั้ง PHP** (ถ้ายังไม่มี)
   - ดาวน์โหลด: https://windows.php.net/download/
   - เลือก PHP 7.4 ขึ้นไป (แนะนำ 8.2)
   - เพิ่ม PHP เข้า PATH ของ Windows

2. **ติดตั้ง MySQL** (ถ้ายังไม่มี)
   - ดาวน์โหลด: https://dev.mysql.com/downloads/installer/
   - หรือใช้ MariaDB: https://mariadb.org/download/

3. **ตั้งค่าโปรเจค:**
   ```powershell
   # เปิด PowerShell ในโฟลเดอร์โปรเจค
   .\setup.ps1
   ```

4. **แก้ไขไฟล์ .env:**
   ```
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=meesuk_db
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```

5. **สร้าง Database:**
   ```sql
   CREATE DATABASE meesuk_db;
   ```

6. **รันเซิร์ฟเวอร์:**
   ```powershell
   .\start-server.ps1
   ```

7. **เปิดเบราว์เซอร์:** http://localhost:8000

---

## 🐳 วิธีที่ 2: Docker (แนะนำที่สุด - ไม่ต้องติดตั้งอะไรเลย)

### ข้อดี:
- ไม่ต้องติดตั้ง PHP, MySQL, phpMyAdmin แยก
- รันได้เลยทันที
- มี Database และ phpMyAdmin ให้อัตโนมัติ
- สามารถลบทิ้งได้ง่ายโดยไม่กระทบเครื่อง

### ขั้นตอน:

1. **ติดตั้ง Docker Desktop:**
   - ดาวน์โหลด: https://www.docker.com/products/docker-desktop
   - ติดตั้งและเปิด Docker Desktop

2. **รัน Docker:**
   ```powershell
   .\start-docker.ps1
   ```

3. **เข้าใช้งาน:**
   - เว็บไซต์: http://localhost:8000
   - phpMyAdmin: http://localhost:8080

4. **หยุดการทำงาน:**
   ```powershell
   .\stop-docker.ps1
   ```

### ข้อมูล Database (Docker):
```
Host: db (หรือ localhost จากเครื่องคุณ)
Username: root
Password: secret
Database: meesuk_db
```

---

## 🔧 วิธีที่ 3: Composer Script

### ถ้ามี Composer ติดตั้งอยู่แล้ว:

```bash
composer serve
```

---

## 📝 คำสั่งที่ใช้บ่อย

### PHP Built-in Server:
```powershell
# เริ่มเซิร์ฟเวอร์
.\start-server.ps1

# หรือ
php -S localhost:8000

# เปลี่ยน Port
php -S localhost:8001
```

### Docker:
```powershell
# เริ่ม Docker
docker-compose up -d

# หยุด Docker
docker-compose down

# ดู logs
docker-compose logs -f

# ดู logs เฉพาะ web
docker-compose logs -f web

# เข้าไปใน container
docker exec -it mesuk-meter-web bash

# รีสตาร์ท
docker-compose restart
```

---

## 🔍 การแก้ปัญหา

### ❌ Port 8000 ถูกใช้งานอยู่แล้ว

**PHP Built-in Server:**
```powershell
php -S localhost:8001 -t .
```

**Docker:**
แก้ไฟล์ `docker-compose.yml`:
```yaml
ports:
  - "8001:80"  # เปลี่ยนจาก 8000 เป็น 8001
```

### ❌ ไม่สามารถเชื่อมต่อ Database

**ตรวจสอบ:**
1. MySQL กำลังรันอยู่หรือไม่?
2. Username/Password ถูกต้องหรือไม่?
3. Database `meesuk_db` ถูกสร้างแล้วหรือยัง?

**สร้าง Database:**
```sql
CREATE DATABASE meesuk_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### ❌ Docker ไม่ทำงาน

**ตรวจสอบ:**
1. Docker Desktop เปิดอยู่หรือไม่?
2. WSL 2 ติดตั้งแล้วหรือยัง? (สำหรับ Windows)

**แก้ไข:**
```powershell
# รีสตาร์ท Docker
docker-compose down
docker-compose up -d --build

# ลบและสร้างใหม่
docker-compose down -v
docker-compose up -d --build
```

### ❌ PHP ไม่ทำงาน

**ตรวจสอบ:**
```powershell
php -v
```

**ถ้าไม่เจอ:** เพิ่ม PHP เข้า PATH
1. Control Panel → System → Advanced System Settings
2. Environment Variables
3. เพิ่ม path ของ PHP เข้าไปใน PATH

---

## 🎯 การใช้งานกับ Git

### Push โค้ดขึ้น GitHub:
```bash
git add .
git commit -m "Update: สามารถรันได้โดยไม่ต้องใช้ XAMPP"
git push origin main
```

### Clone โปรเจคบนเครื่องใหม่:
```bash
git clone https://github.com/ThanasitAio/MESUK-METER.git
cd MESUK-METER
copy .env.example .env
# แก้ไข .env ตามต้องการ
.\start-server.ps1  # หรือ .\start-docker.ps1
```

---

## 💡 เคล็ดลับ

### 1. ใช้ Docker สำหรับการพัฒนา
- ไม่ต้องกังวลเรื่องการติดตั้ง
- สามารถลบทิ้งได้ง่าย
- เหมือนกันทุกเครื่อง

### 2. ใช้ .env สำหรับ Config
- แยก config แต่ละ environment
- ไม่ต้อง hardcode ข้อมูล
- ปลอดภัยกว่า

### 3. ใช้ Git อย่างถูกต้อง
- อย่า commit ไฟล์ .env
- ใช้ .gitignore
- Commit บ่อยๆ

---

## 🌟 คำแนะนำ

### สำหรับการพัฒนา (Development):
✅ **ใช้ Docker** - สะดวก รวดเร็ว ไม่ยุ่งยาก

### สำหรับการ Deploy (Production):
- ใช้ Hosting ที่รองรับ PHP + MySQL
- ตั้งค่า Virtual Host อย่างถูกต้อง
- ใช้ HTTPS
- ตั้งค่า Security

---

## 📞 ต้องการความช่วยเหลือ?

เปิด Issue ที่: https://github.com/ThanasitAio/MESUK-METER/issues

---

**สร้างโดย:** ThanasitAio  
**อัพเดทล่าสุด:** 15 ตุลาคม 2025
