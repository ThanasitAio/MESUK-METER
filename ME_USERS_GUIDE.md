# คู่มือการใช้งาน ME_USERS - ระบบ Login ใหม่

## 📋 ข้อมูลตาราง me_users

### โครงสร้างตาราง:
- **id** (UUID) - Primary Key
- **code** - รหัสสมาชิก (Unique)
- **username** - ชื่อผู้ใช้ (Unique)
- **password** - รหัสผ่าน (MD5 hash)
- **name** - ชื่อ-นามสกุล
- **tel** - เบอร์โทรศัพท์
- **birthday** - วันเกิด
- **facebook_name** - ชื่อ Facebook
- **line_id** - Line ID
- **role** - บทบาท (admin, agent, user)
- **created_date** - วันที่สร้าง
- **created_by** - ผู้สร้าง
- **updated_date** - วันที่แก้ไขล่าสุด
- **updated_by** - ผู้แก้ไข

---

## 🔐 ข้อมูล Login ทดสอบ

### Admin:
- **Username**: `0000999` หรือ `999` (ไม่ต้องใส่ 0 ข้างหน้าก็ได้)
- **Password**: `999`
- **ชื่อ**: ธนสิทธิ์ อิ๋วสกุล
- **Role**: admin

### Agent:
- **Username**: `0000998` หรือ `998`
- **Password**: `998`
- **ชื่อ**: ตัวแทน
- **Role**: agent

---

## 🎯 การทำงานของระบบ Login

### 1. รองรับการกรอก Username แบบยืดหยุ่น:
- กรอก `999` → ระบบจะค้นหา `0000999` อัตโนมัติ
- กรอก `0000999` → ค้นหาตรงๆ
- ทั้งสองแบบใช้งานได้

### 2. การเข้ารหัส Password:
- ใช้ MD5 สำหรับ backward compatibility
- สามารถอัพเกรดเป็น bcrypt ได้ในอนาคต

### 3. Role-based Access:
- **admin** - สิทธิ์สูงสุด
- **agent** - ตัวแทนจำหน่าย
- **user** - ผู้ใช้ทั่วไป

---

## 💻 คำสั่งที่ใช้บ่อย

### เพิ่มผู้ใช้ใหม่:
```sql
INSERT INTO me_users 
(id, code, username, password, name, role, created_by) 
VALUES
(UUID(), '0000997', '0000997', MD5('password123'), 'ชื่อผู้ใช้', 'user', 'admin');
```

### ตั้งรหัสผ่านใหม่:
```sql
UPDATE me_users 
SET password = MD5('รหัสผ่านใหม่'), updated_by = 'admin', updated_date = NOW() 
WHERE username = '0000999';
```

### ดูข้อมูลผู้ใช้:
```sql
SELECT id, code, username, name, role, created_date 
FROM me_users 
ORDER BY created_date DESC;
```

---

## 🚀 ทดสอบการใช้งาน

1. **เปิดเบราว์เซอร์**: http://localhost:8000/login

2. **ทดสอบ Login แบบต่างๆ**:
   - Username: `999` / Password: `999` ✅
   - Username: `0000999` / Password: `999` ✅
   - Username: `998` / Password: `998` ✅
   - Username: `0000998` / Password: `998` ✅

3. **ตรวจสอบ Session**:
   - หลัง Login สำเร็จจะ redirect ไปหน้าหลัก
   - ข้อมูลผู้ใช้จะถูกเก็บใน Session
   - Session timeout 30 นาที

---

## 🔧 PowerShell Commands

### เพิ่มผู้ใช้ผ่าน Docker:
```powershell
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; INSERT INTO me_users (id, code, username, password, name, role, created_by) VALUES (UUID(), '0000997', '0000997', MD5('password'), 'Test User', 'user', 'system');"
```

### ดูรายชื่อผู้ใช้:
```powershell
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT code, username, name, role FROM me_users;"
```

### ตั้งรหัสผ่านใหม่:
```powershell
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; UPDATE me_users SET password = MD5('newpass') WHERE username = '0000999';"
```

---

## 📌 หมายเหตุ

1. ✅ ระบบใช้ตาราง `me_users` แทน `ali_member` แล้ว
2. ✅ รองรับการกรอก Username ทั้งแบบมี 0 และไม่มี 0
3. ✅ รหัสผ่านเข้ารหัสด้วย MD5
4. ✅ มี 3 ระดับ Role: admin, agent, user
5. ⚠️ ตาราง `ali_member` เดิมยังอยู่ สามารถใช้งานได้ถ้าต้องการ

---

## 🎉 การเปลี่ยนแปลง

### Before (ali_member):
- ใช้ `mcode` เป็น username
- ใช้ `pass_encode` เป็น password
- ใช้ `posid = '99'` แยก admin
- โครงสร้างซับซ้อน มีฟิลด์มากมาย

### After (me_users):
- ใช้ `username` ชัดเจน
- ใช้ `password` เป็น field password
- ใช้ `role` enum แยกสิทธิ์ชัดเจน
- โครงสร้างเรียบง่าย เข้าใจง่าย
- รองรับ UUID เป็น Primary Key
- มี tracking fields (created_by, updated_by)
