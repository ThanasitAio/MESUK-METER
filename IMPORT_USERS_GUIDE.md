# 📥 คู่มือการ Import ข้อมูล ali_member → me_users

## ✨ ฟีเจอร์

ระบบนำเข้าข้อมูลจากตาราง `ali_member` (ฐานข้อมูลเดิม) เข้าสู่ตาราง `me_users` (ระบบใหม่) อัตโนมัติ

---

## 📊 การแปลงข้อมูล (Mapping)

| ali_member | → | me_users | หมายเหตุ |
|------------|---|----------|----------|
| `mcode` | → | `code` | รหัสสมาชิก |
| `mcode` | → | `username` | Username สำหรับ Login |
| `sv_code` | → | `password` | รหัสผ่าน (MD5) |
| `name_t` | → | `name` | ชื่อ-นามสกุล (ใช้ name_t แทน name_f) |
| `mobile` | → | `tel` | เบอร์โทรศัพท์ |
| `birthday` | → | `birthday` | วันเกิด |
| `facebook_name` | → | `facebook_name` | ชื่อ Facebook |
| `line_id` | → | `line_id` | Line ID |
| - | → | `role` | ตั้งค่าเป็น **"agent"** ทั้งหมด |
| - | → | `id` | สร้าง UUID อัตโนมัติ |
| - | → | `created_by` | "import" |

---

## 🔐 การตั้งรหัสผ่าน

### กรณีที่ 1: มี `sv_code`
```php
password = MD5(sv_code)
```

### กรณีที่ 2: ไม่มี `sv_code` หรือว่างเปล่า
```php
password = MD5('123456')
```

### ตัวอย่าง:
- ถ้า `sv_code = "ABC123"` → password = `MD5("ABC123")`
- ถ้า `sv_code = ""` → password = `MD5("123456")`

---

## 🚀 วิธีใช้งาน

### 1. เข้าหน้า Import Users
```
http://localhost:8000/import-users
```

### 2. ดูข้อมูล
- **ฝั่งซ้าย (ฐานข้อมูลภายใน)**: แสดงข้อมูลที่มีอยู่ใน `me_users` แล้ว
- **ฝั่งขวา (ฐานข้อมูลภายนอก)**: แสดงข้อมูลจาก `ali_member` ที่**ยังไม่ได้** import

### 3. เลือกผู้ใช้ที่ต้องการ Import
- ✅ Checkbox แต่ละรายการ
- ✅ หรือใช้ "เลือกทั้งหมด"

### 4. กด "นำเข้าผู้ใช้ที่เลือก"
- ระบบจะแสดง Confirm Dialog
- กด "ยืนยัน" เพื่อเริ่ม import

### 5. รอผลลัพธ์
- แสดงจำนวนที่ import สำเร็จ
- แสดงข้อผิดพลาด (ถ้ามี)
- Reload หน้าอัตโนมัติ

---

## 🔍 ฟีเจอร์เพิ่มเติม

### ค้นหา/กรอง
- กล่องค้นหา: พิมพ์รหัสสมาชิก (mcode) เพื่อกรอง
- ปุ่ม Reset: ล้างการค้นหา

### เลือกทั้งหมด
- Checkbox หัวตาราง: เลือกทุกรายการที่แสดงอยู่
- Switch "เลือกทั้งหมด": เลือกทุกรายการ

### สถิติ
- Badge สีน้ำเงิน: จำนวนที่อยู่ในระบบ
- Badge สีเขียว: จำนวนที่รอ import

---

## ⚙️ การตรวจสอบ

### ป้องกันข้อมูลซ้ำ
ระบบจะเช็คก่อน import:
1. ✅ เช็ค `code` ซ้ำหรือไม่
2. ✅ เช็ค `username` ซ้ำหรือไม่
3. ❌ ถ้าซ้ำจะ skip และแจ้ง error

### ข้อมูลที่จำเป็น
- ✅ `mcode` ต้องไม่ว่าง
- ⚠️ `sv_code` ถ้าว่างจะใช้ "123456"
- ⚠️ ฟิลด์อื่นๆ สามารถว่างได้

---

## 💻 ตัวอย่าง SQL

### ดูข้อมูลที่ Import แล้ว
```sql
SELECT 
    id, code, username, name, role, created_date 
FROM me_users 
WHERE created_by = 'import'
ORDER BY created_date DESC;
```

### นับจำนวนที่ Import มา
```sql
SELECT COUNT(*) as total 
FROM me_users 
WHERE created_by = 'import';
```

### ดูข้อมูล ali_member ที่ยังไม่ได้ Import
```sql
SELECT a.mcode, a.name_f, a.mobile
FROM ali_member a
LEFT JOIN me_users m ON a.mcode = m.code
WHERE m.id IS NULL
AND a.mcode IS NOT NULL
AND a.mcode != ''
LIMIT 20;
```

### ทดสอบ Login หลัง Import
```sql
-- ดูข้อมูล user ที่ import มา
SELECT username, name, role 
FROM me_users 
WHERE created_by = 'import'
LIMIT 5;

-- ตรวจสอบ password (MD5)
-- ถ้า sv_code = "ABC123"
SELECT username, password, MD5('ABC123') as expected_password
FROM me_users 
WHERE username = '0000001';
```

---

## 🧪 การทดสอบ

### 1. Import ข้อมูลทดสอบ 1 รายการ
```
1. เข้าหน้า import-users
2. เลือก 1 รายการจาก ali_member
3. กด "นำเข้าผู้ใช้ที่เลือก"
4. ตรวจสอบผลลัพธ์
```

### 2. ทดสอบ Login ด้วยข้อมูลที่ Import
```
1. หา mcode จาก me_users (เช่น 0000001)
2. หา sv_code จาก ali_member ตัวเดิม
3. Login ที่ http://localhost:8000/login
   - Username: mcode (เช่น 0000001 หรือ 1)
   - Password: sv_code (ถ้าไม่มีใช้ 123456)
```

### 3. ตรวจสอบข้อมูล
```powershell
# ดูข้อมูลที่ import ล่าสุด
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT code, username, name, role, created_date FROM me_users WHERE created_by='import' ORDER BY created_date DESC LIMIT 10;"

# นับจำนวน
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT COUNT(*) as total FROM me_users WHERE created_by='import';"
```

---

## 🔧 แก้ปัญหา

### ปัญหา: Import ไม่สำเร็จ
**วิธีแก้:**
1. ตรวจสอบ Console (F12) ดู error
2. ตรวจสอบว่า `mcode` ไม่ซ้ำกับข้อมูลเดิม
3. ตรวจสอบว่า Database connection ทำงาน

### ปัญหา: Login ไม่ได้หลัง Import
**วิธีแก้:**
1. ตรวจสอบว่า `sv_code` ถูกต้อง
2. ลอง Login ด้วย password `123456` (ถ้า sv_code ว่าง)
3. เช็ค password hash ในฐานข้อมูล:
```sql
SELECT username, password, MD5('sv_code_value') as check_password 
FROM me_users 
WHERE username = 'YOUR_USERNAME';
```

### ปัญหา: มีข้อมูลซ้ำ
**วิธีแก้:**
1. ระบบจะ skip อัตโนมัติ
2. ดูข้อความ error ที่แสดง
3. ถ้าต้องการลบข้อมูลเดิม:
```sql
-- ระวัง! จะลบข้อมูล
DELETE FROM me_users WHERE code = 'CODE_TO_DELETE';
```

---

## 📊 สถิติและรายงาน

### Dashboard แนะนำ
```sql
-- สรุปการ Import
SELECT 
    DATE(created_date) as import_date,
    COUNT(*) as total_imported
FROM me_users
WHERE created_by = 'import'
GROUP BY DATE(created_date)
ORDER BY import_date DESC;

-- แยกตาม Role
SELECT 
    role,
    COUNT(*) as total
FROM me_users
GROUP BY role;

-- ข้อมูลล่าสุด
SELECT 
    code,
    username,
    name,
    tel,
    created_date
FROM me_users
WHERE created_by = 'import'
ORDER BY created_date DESC
LIMIT 10;
```

---

## 📌 หมายเหตุสำคัญ

1. ✅ ทุกข้อมูลที่ Import จะมี `role = 'agent'`
2. ✅ Password จะเข้ารหัสด้วย MD5 ตาม `sv_code`
3. ✅ ถ้าไม่มี `sv_code` จะใช้ "123456" เป็น default
4. ✅ ระบบป้องกันข้อมูลซ้ำอัตโนมัติ
5. ✅ รองรับ Username แบบ "999" และ "0000999"
6. ⚠️ ข้อมูลจาก `ali_member` จะแสดงเฉพาะ 100 รายการแรก (ปรับได้ที่ LIMIT)
7. ⚠️ ควร Backup ฐานข้อมูลก่อน Import ข้อมูลจำนวนมาก

---

## 🎉 ผลลัพธ์

หลังจาก Import สำเร็จ:
- ✅ ข้อมูลจาก `ali_member` ถูกคัดลอกไปยัง `me_users`
- ✅ สามารถ Login ด้วย mcode และ sv_code
- ✅ Role ถูกตั้งเป็น "agent" ทั้งหมด
- ✅ บันทึกประวัติการ Login อัตโนมัติ
- ✅ แสดงในสถิติผู้ใช้งานที่ Navbar
