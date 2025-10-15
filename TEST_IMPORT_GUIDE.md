# 🧪 สคริปต์ทดสอบระบบ Import Users

## ทดสอบการ Import แบบ Manual

### 1. ทดสอบ Import 1 รายการ (mcode: 0000001)
```powershell
# ตรวจสอบข้อมูลใน ali_member
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT mcode, name_t, mobile, sv_code, birthday, facebook_name, line_id FROM ali_member WHERE mcode = '0000001';"

# Import ผ่านหน้าเว็บ
# - เปิด http://localhost:8000/import-users
# - เลือก mcode 0000001
# - กด Import

# ตรวจสอบว่า import สำเร็จ
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT code, username, name, tel, role, created_by FROM me_users WHERE code = '0000001';"
```

### 2. ทดสอบ Login ด้วยข้อมูลที่ Import
```
URL: http://localhost:8000/login

Test Case 1:
- Username: 0000001
- Password: Chanjira21 (sv_code จาก ali_member)

Test Case 2:
- Username: 1 (ไม่ใส่ 0)
- Password: Chanjira21
```

### 3. ทดสอบ Import หลายรายการ
```powershell
# ตรวจสอบข้อมูล 5 รายการแรก
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT mcode, name_t, sv_code FROM ali_member WHERE mcode IN ('0000001','0000002','0000003','0000004','0000005');"

# Import ผ่านหน้าเว็บ
# - เลือกทั้ง 5 รายการ
# - กด Import

# ตรวจสอบผลลัพธ์
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT code, username, name, role FROM me_users WHERE code IN ('0000001','0000002','0000003','0000004','0000005');"
```

---

## ทดสอบการป้องกันข้อมูลซ้ำ

### 4. ทดสอบ Import ซ้ำ
```
1. Import mcode 0000001 ครั้งแรก (ควรสำเร็จ)
2. Import mcode 0000001 อีกครั้ง (ควร skip และแจ้ง error)
```

### 5. ตรวจสอบ Error Message
```
Expected: "มีข้อมูลอยู่แล้ว: 0000001"
```

---

## ทดสอบ Password

### 6. ทดสอบ Password จาก sv_code
```powershell
# ดู sv_code จริง
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT mcode, sv_code FROM ali_member WHERE mcode = '0000002';"

# คำนวณ MD5
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT MD5('5150') as password_hash;"

# เปรียบเทียบกับ password ใน me_users
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT username, password FROM me_users WHERE code = '0000002';"
```

### 7. ทดสอบกรณีไม่มี sv_code
```sql
-- สร้างข้อมูลทดสอบที่ไม่มี sv_code
INSERT INTO ali_member (mcode, name_f, sv_code) VALUES ('TEST999', 'Test User', '');

-- Import ผ่านหน้าเว็บ
-- Password ควรเป็น MD5('123456') = e10adc3949ba59abbe56e057f20f883e
```

---

## ทดสอบ Performance

### 8. Import 10 รายการพร้อมกัน
```powershell
# เลือก 10 รายการแรกที่ยังไม่ Import
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT a.mcode, a.name_t FROM ali_member a LEFT JOIN me_users m ON a.mcode = m.code WHERE m.id IS NULL AND a.mcode IS NOT NULL AND a.mcode != '' ORDER BY a.mcode LIMIT 10;"

# Import ทั้งหมดผ่านหน้าเว็บ
# - เลือกทั้ง 10 รายการ
# - กด Import
# - สังเกตเวลาที่ใช้
```

### 9. Import 50 รายการพร้อมกัน
```
# ทำซ้ำขั้นตอนเดียวกับข้อ 8 แต่เลือก 50 รายการ
# เปรียบเทียบเวลาที่ใช้
```

---

## ตรวจสอบข้อมูลหลัง Import

### 10. เช็คความสมบูรณ์ของข้อมูล
```sql
-- ตรวจสอบว่าทุก field ถูก map ถูกต้อง
SELECT 
    code,
    username,
    name,
    tel,
    birthday,
    facebook_name,
    line_id,
    role,
    created_by,
    created_date
FROM me_users
WHERE created_by = 'import'
ORDER BY created_date DESC
LIMIT 5;
```

### 11. เปรียบเทียบข้อมูลต้นทางและปลายทาง
```sql
-- เปรียบเทียบ ali_member และ me_users
SELECT 
    a.mcode as original_mcode,
    m.code as imported_code,
    a.name_t as original_name,
    m.name as imported_name,
    a.mobile as original_tel,
    m.tel as imported_tel
FROM ali_member a
JOIN me_users m ON a.mcode = m.code
WHERE m.created_by = 'import'
LIMIT 5;
```

---

## ทดสอบ UI/UX

### 12. ทดสอบการค้นหา
```
1. เปิด http://localhost:8000/import-users
2. พิมพ์ "0000001" ในช่องค้นหา
3. ตรวจสอบว่ากรองได้ถูกต้อง
4. กด Reset
5. ตรวจสอบว่าแสดงทั้งหมดอีกครั้ง
```

### 13. ทดสอบ Select All
```
1. กด Checkbox "เลือกทั้งหมด"
2. ตรวจสอบว่าทุกรายการถูกเลือก
3. กด Checkbox อีกครั้ง
4. ตรวจสอบว่ายกเลิกการเลือกทั้งหมด
```

### 14. ทดสอบ Mobile Responsive
```
1. เปิด Developer Tools (F12)
2. เปลี่ยนเป็น Mobile View
3. ตรวจสอบว่า UI แสดงผลถูกต้อง
4. ทดสอบการเลือกและ Import
```

---

## ทดสอบความปลอดภัย

### 15. ทดสอบ SQL Injection
```
ลองใส่ค่าแปลกๆ ในช่องค้นหา:
- ' OR '1'='1
- '; DROP TABLE me_users; --
- <script>alert('XSS')</script>

Expected: ระบบควรจัดการได้โดยไม่เกิด error
```

### 16. ทดสอบการเข้าถึงโดยไม่ Login
```
1. Logout ออกจากระบบ
2. พยายามเข้า http://localhost:8000/import-users
3. Expected: ควร redirect ไปหน้า login
```

---

## ตรวจสอบ Login History

### 17. เช็ค Login History หลัง Import
```sql
-- ตรวจสอบว่า Login History ทำงาน
SELECT 
    u.code,
    u.username,
    u.name,
    COUNT(h.id) as login_count,
    MAX(h.login_time) as last_login
FROM me_users u
LEFT JOIN login_history h ON u.id = h.user_id
WHERE u.created_by = 'import'
GROUP BY u.id
ORDER BY login_count DESC
LIMIT 10;
```

---

## สรุปผล Test Cases

### ✅ ควรผ่าน:
- [ ] Import 1 รายการสำเร็จ
- [ ] Import หลายรายการสำเร็จ
- [ ] Login ด้วยข้อมูลที่ Import ได้
- [ ] ป้องกันข้อมูลซ้ำ
- [ ] Password ถูกต้องตาม sv_code
- [ ] การค้นหาทำงานถูกต้อง
- [ ] Select All ทำงานถูกต้อง
- [ ] Mobile Responsive ใช้งานได้
- [ ] ป้องกัน SQL Injection
- [ ] ป้องกันการเข้าถึงโดยไม่ Login
- [ ] Login History บันทึกถูกต้อง

### 📊 ข้อมูลสถิติที่ควรบันทึก:
- จำนวนรายการที่ Import สำเร็จ: _____
- จำนวนรายการที่ Skip (ซ้ำ): _____
- เวลาที่ใช้ Import 10 รายการ: _____ วินาที
- เวลาที่ใช้ Import 50 รายการ: _____ วินาที

---

## 🔧 คำสั่งที่ใช้บ่อย

```powershell
# นับจำนวน ali_member ทั้งหมด
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT COUNT(*) as total FROM ali_member WHERE mcode IS NOT NULL AND mcode != '';"

# นับจำนวนที่ Import แล้ว
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT COUNT(*) as total FROM me_users WHERE created_by = 'import';"

# นับจำนวนที่ยังไม่ได้ Import
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT COUNT(*) as total FROM ali_member a LEFT JOIN me_users m ON a.mcode = m.code WHERE m.id IS NULL AND a.mcode IS NOT NULL AND a.mcode != '';"

# ลบข้อมูล Import ทั้งหมด (ใช้เมื่อต้องการทดสอบใหม่)
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; DELETE FROM me_users WHERE created_by = 'import';"
```
