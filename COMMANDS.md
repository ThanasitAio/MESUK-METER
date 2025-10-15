# ⚡ คำสั่งที่ใช้บ่อย - MESUK-METER

## 🐳 Docker Commands

### เริ่มและหยุด
```powershell
# เริ่ม Docker (ใช้สคริปต์)
.\start-docker.ps1

# หยุด Docker (ใช้สคริปต์)
.\stop-docker.ps1

# เริ่ม Docker (คำสั่งตรง)
docker-compose up -d

# หยุด Docker (คำสั่งตรง)
docker-compose down

# หยุดและลบ volumes
docker-compose down -v
```

### ดูสถานะและ Logs
```powershell
# ดูสถานะ containers
docker-compose ps

# ดู logs ทั้งหมด
docker-compose logs -f

# ดู logs เฉพาะ web
docker-compose logs -f web

# ดู logs เฉพาะ database
docker-compose logs -f db

# ดู logs จำนวนจำกัด
docker-compose logs --tail=100
```

### รีสตาร์ทและรีบิวด์
```powershell
# รีสตาร์ท services
docker-compose restart

# รีสตาร์ทเฉพาะ web
docker-compose restart web

# รีบิวด์และรัน
docker-compose up -d --build

# บังคับรีบิวด์
docker-compose build --no-cache
docker-compose up -d
```

### เข้าไปใน Container
```powershell
# เข้าไปใน web container
docker exec -it mesuk-meter-web bash

# เข้าไปใน db container
docker exec -it mesuk-meter-db bash

# รันคำสั่ง PHP ใน container
docker exec -it mesuk-meter-web php -v

# รันคำสั่ง MySQL
docker exec -it mesuk-meter-db mysql -uroot -psecret
```

### ดูข้อมูลและจัดการ
```powershell
# ดู disk usage
docker system df

# ล้าง Docker cache
docker system prune -a

# ดู volumes
docker volume ls

# ลบ volume ที่ไม่ใช้
docker volume prune
```

---

## 📦 PHP Built-in Server

### รันเซิร์ฟเวอร์
```powershell
# ใช้สคริปต์
.\start-server.ps1

# หรือใช้ bat file
.\start-server.bat

# หรือคำสั่งตรง
php -S localhost:8000 -t .

# เปลี่ยน port
php -S localhost:8001 -t .

# ระบุ host อื่น
php -S 0.0.0.0:8000 -t .
```

### ตรวจสอบ PHP
```powershell
# ดู version
php -v

# ดู extensions
php -m

# ดูข้อมูล PHP
php -i

# ตรวจสอบ syntax
php -l index.php

# รันไฟล์ PHP
php index.php
```

---

## 🗄️ MySQL/Database Commands

### เข้าใช้งาน MySQL
```powershell
# เข้า MySQL (ถ้าติดตั้งใน Windows)
mysql -uroot -p

# เข้า MySQL ใน Docker
docker exec -it mesuk-meter-db mysql -uroot -psecret
```

### คำสั่ง SQL พื้นฐาน
```sql
-- ดู databases ทั้งหมด
SHOW DATABASES;

-- เลือก database
USE meesuk_db;

-- ดู tables
SHOW TABLES;

-- ดูโครงสร้าง table
DESCRIBE table_name;

-- Export database
mysqldump -uroot -p meesuk_db > backup.sql

-- Import database
mysql -uroot -p meesuk_db < backup.sql
```

### Docker MySQL
```powershell
# Export database จาก Docker
docker exec mesuk-meter-db mysqldump -uroot -psecret meesuk_db > backup.sql

# Import database เข้า Docker
docker exec -i mesuk-meter-db mysql -uroot -psecret meesuk_db < backup.sql

# สร้าง database ใหม่
docker exec -it mesuk-meter-db mysql -uroot -psecret -e "CREATE DATABASE meesuk_db;"
```

---

## 🎨 Composer Commands

```powershell
# ติดตั้ง dependencies
composer install

# อัพเดท dependencies
composer update

# รันเซิร์ฟเวอร์
composer serve

# ดูข้อมูลแพ็คเกจ
composer show

# ค้นหาแพ็คเกจ
composer search keyword
```

---

## 🔧 Git Commands

### พื้นฐาน
```bash
# ดูสถานะ
git status

# ดู branch
git branch

# สร้าง branch ใหม่
git checkout -b feature-name

# เปลี่ยน branch
git checkout main

# ลบ branch
git branch -d feature-name
```

### Commit และ Push
```bash
# Add ไฟล์
git add .

# Commit
git commit -m "commit message"

# Push
git push origin main

# Push branch ใหม่
git push -u origin feature-name
```

### Pull และ Merge
```bash
# Pull โค้ดล่าสุด
git pull

# Pull และ rebase
git pull --rebase

# Merge branch
git checkout main
git merge feature-name
```

### ดู History
```bash
# ดู log
git log

# ดู log แบบสั้น
git log --oneline

# ดู log แบบกราฟ
git log --graph --oneline --all

# ดู changes
git diff
```

### Undo Changes
```bash
# ยกเลิกการแก้ไขไฟล์
git checkout -- filename

# ยกเลิก staged files
git reset HEAD filename

# ยกเลิก commit ล่าสุด (เก็บการแก้ไข)
git reset --soft HEAD~1

# ยกเลิก commit ล่าสุด (ลบการแก้ไข)
git reset --hard HEAD~1
```

---

## 🛠️ VS Code Tasks

### รัน Tasks
```
1. กด Ctrl+Shift+P
2. พิมพ์ "Tasks: Run Task"
3. เลือก task ที่ต้องการ:
   - Start PHP Server
   - Start Docker
   - Stop Docker
   - Docker Logs
   - Setup Project
```

### รัน Task โดยตรง
```
Ctrl+Shift+B = รัน build task
```

---

## 🔍 Debugging

### PHP Debugging
```powershell
# ดู errors
php -d display_errors=on index.php

# เปิด error reporting
php -d error_reporting=E_ALL index.php
```

### Docker Debugging
```powershell
# ดู Docker daemon
docker info

# ดู container details
docker inspect mesuk-meter-web

# ดู network
docker network ls
docker network inspect mesuk-meter_default
```

---

## 📝 File Operations

### สร้างไฟล์
```powershell
# สร้างไฟล์ว่าง
New-Item -Path "filename.php" -ItemType File

# สร้างไฟล์พร้อม content
echo "<?php" > filename.php
```

### Copy และ Move
```powershell
# Copy file
Copy-Item source.php destination.php

# Move file
Move-Item source.php destination.php

# Copy folder
Copy-Item -Recurse source_folder destination_folder
```

### ลบไฟล์
```powershell
# ลบไฟล์
Remove-Item filename.php

# ลบ folder
Remove-Item -Recurse folder_name

# ลบด้วยการยืนยัน
Remove-Item -Confirm filename.php
```

---

## 🧹 Maintenance

### ทำความสะอาด
```powershell
# ลบ Docker containers และ volumes
docker-compose down -v

# ล้าง Docker system
docker system prune -a

# ลบ vendor folder
Remove-Item -Recurse vendor

# ลบ node_modules (ถ้ามี)
Remove-Item -Recurse node_modules
```

### Reinstall
```powershell
# Reinstall Composer
composer install --no-cache

# Reinstall และ rebuild Docker
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

---

## 🎯 Quick Shortcuts

```powershell
# Setup โปรเจคใหม่
.\setup.ps1

# รัน Docker ด่วน
.\start-docker.ps1

# รัน PHP ด่วน
.\start-server.ps1

# หยุด Docker
.\stop-docker.ps1

# ดู logs
docker-compose logs -f

# เข้า container
docker exec -it mesuk-meter-web bash
```

---

## 🔗 URLs ที่สำคัญ

### Local Development
```
Web Application: http://localhost:8000
phpMyAdmin:     http://localhost:8080
```

### Documentation
```
PHP:      https://www.php.net/docs.php
MySQL:    https://dev.mysql.com/doc/
Docker:   https://docs.docker.com/
Git:      https://git-scm.com/doc
```

---

## 💡 Tips

1. **ใช้ Tab completion** - พิมพ์บางส่วนแล้วกด Tab
2. **ใช้ history** - กด ↑ เพื่อดูคำสั่งที่เคยใช้
3. **ใช้ alias** - สร้าง shortcut สำหรับคำสั่งที่ใช้บ่อย
4. **เปิด multiple terminals** - แยก terminal สำหรับแต่ละงาน
5. **ใช้ VS Code tasks** - สะดวกกว่าพิมพ์คำสั่ง

---

**บันทึกไฟล์นี้ไว้เป็นอ้างอิง! 📌**
