# 📥 คู่มือการติดตั้ง PHP บน Windows

## ⚠️ หมายเหตุ
คุณไม่จำเป็นต้องติดตั้ง PHP ถ้าคุณใช้ Docker! 
👉 แนะนำให้ใช้ Docker แทน: [QUICKSTART.md](QUICKSTART.md)

---

## 📦 วิธีที่ 1: ติดตั้ง PHP แบบ Manual

### ขั้นตอนที่ 1: ดาวน์โหลด PHP

1. ไปที่: https://windows.php.net/download/
2. เลือก **PHP 8.2** (หรือใหม่กว่า)
3. ดาวน์โหลด **Thread Safe** version (แนะนำ VS16 x64)
   - ตัวอย่าง: `php-8.2.x-Win32-vs16-x64.zip`

### ขั้นตอนที่ 2: แตกไฟล์

1. แตกไฟล์ zip ไปที่ `C:\php`
2. ตรวจสอบว่ามีไฟล์ `php.exe` ใน `C:\php`

### ขั้นตอนที่ 3: เพิ่ม PHP เข้า System PATH

1. กด `Windows + X` และเลือก **System**
2. คลิก **Advanced system settings**
3. คลิก **Environment Variables**
4. ในส่วน **System variables** หา **Path** แล้วคลิก **Edit**
5. คลิก **New** แล้วใส่ `C:\php`
6. คลิก **OK** ทุกหน้าต่าง

### ขั้นตอนที่ 4: ตั้งค่า php.ini

1. ไปที่โฟลเดอร์ `C:\php`
2. Copy `php.ini-development` เป็น `php.ini`
3. เปิด `php.ini` ด้วย Notepad
4. ค้นหาและแก้ไขบรรทัดเหล่านี้ (ลบ `;` หน้าบรรทัด):

```ini
extension_dir = "ext"
extension=mysqli
extension=pdo_mysql
extension=mbstring
extension=curl
extension=openssl
```

### ขั้นตอนที่ 5: ทดสอบ

1. เปิด PowerShell ใหม่
2. พิมพ์:
   ```powershell
   php -v
   ```
3. ถ้าเห็น PHP version แสดงว่าติดตั้งสำเร็จ!

---

## 🍫 วิธีที่ 2: ติดตั้งด้วย Chocolatey (ง่ายกว่า)

### ขั้นตอนที่ 1: ติดตั้ง Chocolatey

1. เปิด PowerShell แบบ **Run as Administrator**
2. รันคำสั่ง:
   ```powershell
   Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
   ```

### ขั้นตอนที่ 2: ติดตั้ง PHP

```powershell
choco install php -y
```

### ขั้นตอนที่ 3: ทดสอบ

```powershell
php -v
```

---

## 🗄️ การติดตั้ง MySQL

### วิธีที่ 1: MySQL Installer (แนะนำ)

1. ดาวน์โหลด: https://dev.mysql.com/downloads/installer/
2. เลือก **mysql-installer-community**
3. รันและเลือก **Developer Default**
4. ตั้งรหัสผ่าน root (จำไว้ให้ดี!)
5. เสร็จแล้วเปิด MySQL Workbench

### วิธีที่ 2: Chocolatey

```powershell
choco install mysql -y
```

### สร้าง Database:

1. เปิด MySQL Workbench หรือ Command Line
2. รันคำสั่ง:
   ```sql
   CREATE DATABASE meesuk_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

---

## 🐳 วิธีที่ 3: ใช้ Docker แทน (แนะนำที่สุด!)

### ทำไมต้อง Docker?
- ✅ ไม่ต้องติดตั้ง PHP, MySQL, phpMyAdmin แยก
- ✅ รันได้ทันที
- ✅ ลบง่าย ไม่กระทบเครื่อง
- ✅ เหมือนกันทุกเครื่อง

### วิธีติดตั้ง Docker:

1. ดาวน์โหลด: https://www.docker.com/products/docker-desktop
2. ติดตั้ง Docker Desktop
3. เปิด Docker Desktop
4. เปิด PowerShell ในโฟลเดอร์โปรเจค
5. รัน:
   ```powershell
   .\start-docker.ps1
   ```
6. เสร็จ! เปิด http://localhost:8000

---

## ✅ ตรวจสอบการติดตั้ง

### ตรวจสอบ PHP:
```powershell
php -v
php -m  # ดู extensions ที่ติดตั้ง
```

### ตรวจสอบ MySQL:
```powershell
mysql --version
```

---

## 🔧 แก้ปัญหา

### PHP ไม่ทำงาน
- ตรวจสอบ PATH อีกครั้ง
- เปิด PowerShell ใหม่
- รีสตาร์ทเครื่อง

### MySQL ไม่เชื่อมต่อ
- ตรวจสอบว่า MySQL Service กำลังรัน
- ตรวจสอบ username/password ใน `.env`
- ตรวจสอบว่าสร้าง database แล้ว

### Extensions ไม่ทำงาน
- แก้ไข `php.ini`
- ลบ `;` หน้า extension ที่ต้องการ
- รีสตาร์ท server

---

## 🎯 ขั้นตอนถัดไป

หลังจากติดตั้งเสร็จแล้ว:

1. กลับไปอ่าน [QUICKSTART.md](QUICKSTART.md)
2. รัน `.\setup.ps1`
3. แก้ไข `.env`
4. รัน `.\start-server.ps1`
5. เริ่มพัฒนาได้เลย!

---

## 💡 คำแนะนำ

- ถ้ายังไม่ได้ติดตั้ง → **ใช้ Docker**
- ถ้าติดตั้งแล้ว → ใช้ PHP Built-in Server
- ถ้าต้องการความเร็ว → ใช้ Chocolatey

**Happy Coding! 🚀**
