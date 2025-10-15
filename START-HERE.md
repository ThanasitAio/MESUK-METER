# 🎯 เริ่มต้นใช้งาน MESUK-METER (ฉบับย่อ)

## 🚀 เลือกวิธีที่เหมาะกับคุณ

### 🐳 วิธีที่ 1: Docker (แนะนำ - ไม่ต้องติดตั้งอะไร)

```powershell
# 1. ติดตั้ง Docker Desktop
# ดาวน์โหลด: https://www.docker.com/products/docker-desktop

# 2. เปิด Docker Desktop

# 3. รันคำสั่งนี้
.\start-docker.ps1

# 4. เปิดเบราว์เซอร์
# http://localhost:8000 - เว็บไซต์
# http://localhost:8080 - phpMyAdmin
```

**Database Info:**
- Host: `db` (หรือ `localhost` จากเครื่องคุณ)
- User: `root`
- Pass: `secret`
- DB: `meesuk_db`

---

### 📦 วิธีที่ 2: PHP Built-in Server

```powershell
# 1. ติดตั้ง PHP
# ดาวน์โหลด: https://windows.php.net/download/

# 2. ติดตั้ง MySQL
# ดาวน์โหลด: https://dev.mysql.com/downloads/installer/

# 3. Setup โปรเจค
.\setup.ps1

# 4. แก้ไข .env (ใส่รหัส MySQL)
notepad .env

# 5. สร้าง Database
# CREATE DATABASE meesuk_db;

# 6. รันเซิร์ฟเวอร์
.\start-server.ps1

# 7. เปิดเบราว์เซอร์
# http://localhost:8000
```

---

### 🎓 วิธีที่ 3: Composer (สำหรับผู้เชี่ยวชาญ)

```bash
composer serve
```

---

## 📚 อ่านเพิ่มเติม

- [QUICKSTART.md](QUICKSTART.md) - เริ่มต้นอย่างรวดเร็ว
- [GUIDE-TH.md](GUIDE-TH.md) - คู่มือแบบละเอียด (ภาษาไทย)
- [INSTALL-GUIDE.md](INSTALL-GUIDE.md) - วิธีติดตั้ง PHP/MySQL
- [README.md](README.md) - Documentation (English)

---

## ⚡ คำสั่งด่วน

### Docker:
```powershell
.\start-docker.ps1    # เริ่ม
.\stop-docker.ps1     # หยุด
docker-compose logs -f # ดู logs
```

### PHP Server:
```powershell
.\start-server.ps1    # เริ่ม
# กด Ctrl+C เพื่อหยุด
```

---

## 🆘 เจอปัญหา?

1. อ่าน [GUIDE-TH.md](GUIDE-TH.md) ส่วน "การแก้ปัญหา"
2. เปิด Issue: https://github.com/ThanasitAio/MESUK-METER/issues

---

**ขอให้สนุกกับการพัฒนา! 🎉**
