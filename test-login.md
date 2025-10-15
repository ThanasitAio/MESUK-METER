# ข้อมูลสำหรับทดสอบ Login

## ตาราง: ali_member

### โครงสร้างที่เกี่ยวข้อง:
- **mcode** = username (รหัสสมาชิก)
- **pass_encode** = password (MD5 hash)
- **name_t** = ชื่อไทย
- **email** = อีเมล
- **posid** = ตำแหน่ง ('' = member ทั่วไป, '99' = admin)

### ตัวอย่างข้อมูล:
- User ID 1: mcode = `0000001`, email = `cs@me-suk.com`

## วิธีทดสอบ:

### 1. สร้างรหัสผ่านใหม่สำหรับ user ที่ต้องการทดสอบ:

```sql
-- ตั้งรหัสผ่านเป็น "123456" สำหรับ user 0000001
UPDATE ali_member 
SET pass_encode = MD5('123456') 
WHERE mcode = '0000001';
```

### 2. Login:
- URL: http://localhost:8000/login
- Username: `0000001`
- Password: `123456`

## คำสั่งที่เป็นประโยชน์:

```powershell
# ตั้งรหัสผ่านใหม่สำหรับ user
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; UPDATE ali_member SET pass_encode = MD5('123456') WHERE mcode = '0000001';"

# ตรวจสอบข้อมูล user
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT id, mcode, name_t, email, posid FROM ali_member WHERE mcode = '0000001';"
```
