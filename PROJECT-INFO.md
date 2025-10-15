# ⚙️ ข้อมูลการตั้งค่าโปรเจค MESUK-METER

## 📌 ข้อมูลสำคัญ

### PHP Version
- **PHP 5.6** (ไม่ใช่ 8.2)

### Database
- **Host:** db (หรือ localhost)
- **Port:** 3306
- **Database:** meesuk_db
- **Username:** root
- **Password:** secret

### URLs
- **Web Application:** http://localhost:8000
- **phpMyAdmin:** http://localhost:8080

---

## 🐳 Docker Configuration

ใช้ PHP 5.6-Apache image:
```dockerfile
FROM php:5.6-apache
```

---

## 📝 หมายเหตุ

- โปรเจคนี้ออกแบบมาสำหรับ PHP 5.6
- ถ้าต้องการเปลี่ยน PHP version ให้แก้ไข:
  - `Dockerfile` (บรรทัดที่ 1)
  - `composer.json` (require php version)
  - จากนั้นรัน: `docker-compose down && docker-compose build --no-cache && docker-compose up -d`

---

**อัพเดทล่าสุด:** 15 ตุลาคม 2025
