# 📊 ระบบบันทึกประวัติการเข้าใช้งาน (Login History)

## ✨ ฟีเจอร์ที่เพิ่มเข้ามา

### 1. ตาราง `login_history`
เก็บประวัติการเข้าระบบของผู้ใช้ทุกครั้ง

**โครงสร้างตาราง:**
- `id` - Primary Key
- `user_id` - UUID ของผู้ใช้ (Foreign Key → me_users.id)
- `username` - Username ที่ใช้ login
- `login_time` - เวลาที่เข้าระบบ
- `ip_address` - IP Address ของผู้ใช้
- `user_agent` - Browser/Device ที่ใช้
- `status` - สถานะ ('success' หรือ 'failed')

### 2. Class `LoginHistory`
Helper Class สำหรับจัดการประวัติการเข้าระบบ

**Methods:**
- `record($userId, $username, $status)` - บันทึกประวัติการ Login
- `getActiveUsersCount($days)` - นับจำนวนผู้ใช้ที่ใช้งานใน N วันล่าสุด
- `getActiveUsers($days, $limit)` - ดึงรายชื่อผู้ใช้ที่ใช้งานล่าสุด
- `getUserHistory($userId, $limit)` - ดึงประวัติการ Login ของผู้ใช้คนหนึ่ง
- `cleanOldRecords($days)` - ลบประวัติเก่าเกินกว่า N วัน

### 3. แสดงผลที่ Navbar
แสดงจำนวนผู้ใช้ที่ใช้งานใน **3 วันล่าสุด** แบบ Real-time

---

## 🎯 การทำงาน

### เมื่อ Login สำเร็จ:
1. ระบบบันทึกข้อมูลลง `login_history` พร้อม `status = 'success'`
2. บันทึก IP Address และ User Agent
3. บันทึกเวลาที่เข้าระบบ

### เมื่อ Login ไม่สำเร็จ:
1. ระบบบันทึกความพยายาม Login พร้อม `status = 'failed'`
2. ใช้สำหรับตรวจสอบการโจมตี Brute Force

### ที่ Navbar:
- แสดงจำนวนผู้ใช้ที่ Login สำเร็จใน **3 วัน**ล่าสุด
- นับแบบ DISTINCT user_id (ผู้ใช้คนเดียวนับครั้งเดียว)
- อัพเดทแบบ Real-time ทุกครั้งที่โหลดหน้า

---

## 📋 ตัวอย่างการใช้งาน

### ดูจำนวนผู้ใช้งานใน 7 วันล่าสุด:
```php
$activeUsers = LoginHistory::getActiveUsersCount(7);
echo "มีผู้ใช้งาน {$activeUsers} คน ใน 7 วันที่ผ่านมา";
```

### ดูรายชื่อผู้ใช้งานล่าสุด:
```php
$users = LoginHistory::getActiveUsers(3, 10); // 3 วัน, แสดง 10 คน
foreach ($users as $user) {
    echo "{$user['name']} - Login ล่าสุด: {$user['last_login']}";
}
```

### ดูประวัติ Login ของผู้ใช้:
```php
$history = LoginHistory::getUserHistory($userId, 20);
foreach ($history as $log) {
    echo "เวลา: {$log['login_time']} - IP: {$log['ip_address']}";
}
```

### ทำความสะอาดข้อมูลเก่า:
```php
// ลบประวัติเก่ากว่า 90 วัน
$deleted = LoginHistory::cleanOldRecords(90);
echo "ลบประวัติเก่า {$deleted} รายการ";
```

---

## 💻 SQL Queries ที่เป็นประโยชน์

### ดูประวัติ Login ทั้งหมด:
```sql
SELECT 
    h.*, 
    u.name, 
    u.role 
FROM login_history h
JOIN me_users u ON h.user_id = u.id
ORDER BY h.login_time DESC
LIMIT 50;
```

### นับจำนวนการ Login ของแต่ละคน:
```sql
SELECT 
    u.username,
    u.name,
    COUNT(h.id) as login_count,
    MAX(h.login_time) as last_login
FROM me_users u
LEFT JOIN login_history h ON u.id = h.user_id AND h.status = 'success'
GROUP BY u.id
ORDER BY login_count DESC;
```

### ดูความพยายาม Login ที่ไม่สำเร็จ:
```sql
SELECT 
    username,
    ip_address,
    login_time,
    user_agent
FROM login_history
WHERE status = 'failed'
ORDER BY login_time DESC
LIMIT 20;
```

### ดูผู้ใช้ที่ใช้งานวันนี้:
```sql
SELECT DISTINCT
    u.username,
    u.name,
    MAX(h.login_time) as last_seen
FROM login_history h
JOIN me_users u ON h.user_id = u.id
WHERE DATE(h.login_time) = CURDATE()
AND h.status = 'success'
GROUP BY u.id;
```

---

## 🚀 การทดสอบ

### 1. Login ด้วย user ทดสอบ:
- Username: `999` / Password: `999`
- Username: `998` / Password: `998`

### 2. ตรวจสอบที่ Navbar:
- ดูตัวเลขจำนวนผู้ใช้งาน (เริ่มต้นจะเป็น 0)
- หลัง Login สำเร็จ รีเฟรชหน้าจะเห็นตัวเลขเพิ่มขึ้น

### 3. ตรวจสอบฐานข้อมูล:
```powershell
# ดูประวัติ Login ทั้งหมด
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT * FROM login_history ORDER BY login_time DESC LIMIT 10;"

# นับจำนวนผู้ใช้งานใน 3 วัน
docker exec -it mesuk-meter-db mysql -uroot -proot -e "USE meesuk_db; SELECT COUNT(DISTINCT user_id) as active_users FROM login_history WHERE status='success' AND login_time >= DATE_SUB(NOW(), INTERVAL 3 DAY);"
```

---

## ⚙️ Configuration

### เปลี่ยนจำนวนวันที่แสดงที่ Navbar:

แก้ไขไฟล์ `views/partials/navbar.php`:
```php
// เปลี่ยนจาก 3 วันเป็น 7 วัน
$activeUsersCount = LoginHistory::getActiveUsersCount(7);
```

### ตั้งค่าการลบข้อมูลเก่าอัตโนมัติ:

สร้าง Cron Job หรือเรียกใช้เป็นระยะ:
```php
// ลบประวัติเก่ากว่า 90 วัน
LoginHistory::cleanOldRecords(90);
```

---

## 📊 ข้อมูลสถิติที่สามารถดึงได้

1. **Active Users** - ผู้ใช้ที่ใช้งานจริงในช่วงเวลาที่กำหนด
2. **Login Frequency** - ความถี่การเข้าระบบของแต่ละคน
3. **Peak Hours** - ช่วงเวลาที่มีการใช้งานมากที่สุด
4. **Failed Attempts** - ความพยายาม Login ที่ไม่สำเร็จ (Security)
5. **User Activity** - Timeline การใช้งานของแต่ละคน
6. **Device/Browser Stats** - สถิติ Device และ Browser ที่ใช้

---

## 🔒 Security Benefits

1. **Audit Trail** - ติดตามว่าใครเข้าระบบเมื่อไหร่
2. **Brute Force Detection** - ตรวจจับการพยายาม Login หลายครั้ง
3. **Suspicious Activity** - ตรวจสอบการเข้าจาก IP แปลก
4. **Compliance** - เก็บ Log ตามมาตรฐาน Security

---

## 📌 หมายเหตุ

- ✅ ข้อมูลทุกการ Login จะถูกบันทึกอัตโนมัติ
- ✅ นับแบบ DISTINCT (ผู้ใช้คนเดียวนับ 1 ครั้ง)
- ✅ แสดงเฉพาะ status = 'success'
- ✅ Real-time update (อัพเดทเมื่อโหลดหน้า)
- ⚠️ ควรทำความสะอาดข้อมูลเก่าเป็นระยะ (cleanOldRecords)

---

## 🎉 ผลลัพธ์

ตอนนี้ระบบมีความสามารถ:
- 📝 บันทึกประวัติการเข้าระบบอัตโนมัติ
- 📊 แสดงสถิติผู้ใช้งานแบบ Real-time
- 🔍 ตรวจสอบ Activity ของผู้ใช้แต่ละคน
- 🛡️ เพิ่มความปลอดภัยด้วยการ Track การเข้าระบบ
- 📈 วิเคราะห์พฤติกรรมการใช้งาน
