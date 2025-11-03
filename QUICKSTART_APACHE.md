# 🚀 Quick Start - Apache/XAMPP Setup

## สำหรับผู้ที่ใช้ Apache บน localhost (มีหลายโปรเจ็ค)

### ⚡ 3 ขั้นตอนเริ่มต้นด่วน

#### 1️⃣ วาง Project
```
C:\xampp\htdocs\mesuk\     <-- วาง project ที่นี่
```

#### 2️⃣ ตั้งค่า 2 ไฟล์

**ไฟล์ที่ 1:** `config/app.php`
```php
'base_path' => '/mesuk'  // ตรงกับชื่อโฟลเดอร์
```

**ไฟล์ที่ 2:** `.htaccess`
```apache
RewriteBase /mesuk/  // ตรงกับ base_path + /
```

#### 3️⃣ เปิดใช้ mod_rewrite

แก้ไข `C:\xampp\apache\conf\httpd.conf`:
```apache
# ค้นหาบรรทัดนี้และลบ # ออก
LoadModule rewrite_module modules/mod_rewrite.so

# และแก้ AllowOverride
<Directory "C:/xampp/htdocs">
    AllowOverride All    # แก้จาก None
</Directory>
```

**Restart Apache** จากนั้นเข้า:
```
http://localhost/mesuk/
```

---

## 🔍 ตรวจสอบ Configuration

เปิด browser:
```
http://localhost/mesuk/check_apache.php
```

ควรเห็น:
- ✅ Apache: Active
- ✅ mod_rewrite: Enabled
- ✅ .htaccess: Found
- ✅ RewriteBase: Match

---

## 📝 ตัวอย่างการตั้งค่าต่างๆ

### กรณีที่ 1: วางที่ htdocs/mesuk/
```php
// config/app.php
'base_path' => '/mesuk'

// .htaccess
RewriteBase /mesuk/

// URL
http://localhost/mesuk/
```

### กรณีที่ 2: วางที่ htdocs/meter/
```php
// config/app.php
'base_path' => '/meter'

// .htaccess
RewriteBase /meter/

// URL
http://localhost/meter/
```

### กรณีที่ 3: วางที่ htdocs/ (root)
```php
// config/app.php
'base_path' => ''

// .htaccess
RewriteBase /

// URL
http://localhost/
```

### กรณีที่ 4: วางที่ htdocs/projects/mesuk/
```php
// config/app.php
'base_path' => '/projects/mesuk'

// .htaccess
RewriteBase /projects/mesuk/

// URL
http://localhost/projects/mesuk/
```

---

## ⚠️ สิ่งที่ต้องระวัง

### ❌ ผิด
```php
// config/app.php
'base_path' => '/mesuk'

// .htaccess
RewriteBase /meter/    # ไม่ตรงกัน!
```

### ✅ ถูกต้อง
```php
// config/app.php
'base_path' => '/mesuk'

// .htaccess
RewriteBase /mesuk/    # ตรงกัน + มี / ท้าย
```

---

## 🐛 แก้ปัญหาด่วน

### 404 Not Found
```
เช็ค: mod_rewrite เปิดใช้งานแล้วหรือยัง?
→ เปิด http://localhost/mesuk/check_apache.php
```

### Internal Server Error
```
เช็ค: .htaccess syntax ถูกต้องหรือไม่?
→ ดู C:\xampp\apache\logs\error.log
```

### CSS/JS ไม่โหลด
```
เช็ค: base_path ตรงกับโฟลเดอร์จริงหรือไม่?
→ กด F12 ดู Console และ Network tab
```

---

## ✅ Checklist ก่อนใช้งาน

- [ ] วาง project ใน htdocs แล้ว
- [ ] ตั้งค่า base_path ใน config/app.php
- [ ] แก้ไข RewriteBase ใน .htaccess
- [ ] เปิด mod_rewrite แล้ว
- [ ] AllowOverride = All แล้ว
- [ ] Restart Apache แล้ว
- [ ] เปิด http://localhost/mesuk/check_apache.php ดูแล้ว
- [ ] ทดสอบ login ได้แล้ว

---

## 📚 เอกสารเพิ่มเติม

- **ละเอียด:** `APACHE_SETUP_GUIDE.md`
- **Base Path:** `BASE_PATH_GUIDE.md`
- **Deploy:** `DEPLOYMENT_SUMMARY.md`

---

**พร้อมใช้งาน!** 🎉

หลังจากทำตาม 3 ขั้นตอนแล้ว เข้าได้ที่:
```
http://localhost/mesuk/
```
