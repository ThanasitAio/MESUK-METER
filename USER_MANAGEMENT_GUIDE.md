# คู่มือการใช้งานระบบจัดการผู้ใช้

## ภาพรวม
ระบบจัดการผู้ใช้ (User Management System) สำหรับจัดการข้อมูลผู้ใช้ในระบบ MESUK โดยมีฟีเจอร์หลักดังนี้:

## ฟีเจอร์หลัก
- ✅ แสดงรายการผู้ใช้ทั้งหมดในรูปแบบตาราง
- ✅ เพิ่มผู้ใช้ใหม่
- ✅ แก้ไขข้อมูลผู้ใช้
- ✅ ลบผู้ใช้
- ✅ เปลี่ยนสถานะผู้ใช้ (ใช้งาน/ระงับ)
- ✅ อัพโหลดรูปภาพผู้ใช้
- ✅ จัดการ Role (Admin, Agent, User)
- ✅ แสดงสถิติผู้ใช้

## การติดตั้ง

### 1. อัพเดทฐานข้อมูล

#### สำหรับตารางใหม่
```sql
-- รันไฟล์ create_me_users.sql
source create_me_users.sql;
```

#### สำหรับตารางเดิมที่ต้องการเพิ่มฟิลด์
```sql
-- รันไฟล์ update_me_users_table.sql
source update_me_users_table.sql;
```

### 2. สร้างโฟลเดอร์สำหรับเก็บรูปภาพ
```powershell
# โฟลเดอร์จะถูกสร้างอัตโนมัติเมื่ออัพโหลดรูปภาพครั้งแรก
# หรือสร้างด้วยตนเอง:
mkdir public/uploads/users
```

### 3. ตั้งค่าสิทธิ์โฟลเดอร์ (สำหรับ Linux/Mac)
```bash
chmod -R 755 public/uploads
```

## โครงสร้างไฟล์ที่เพิ่มมา

```
MESUK-METER/
├── app/
│   ├── controllers/
│   │   └── UserManagementController.php    # Controller สำหรับจัดการผู้ใช้
│   └── models/
│       └── User.php                         # Model สำหรับเข้าถึงข้อมูล me_users
├── views/
│   └── pages/
│       └── user-management/
│           ├── index.php                    # หน้ารายการผู้ใช้
│           └── form.php                     # หน้าฟอร์มเพิ่ม/แก้ไข
├── public/
│   └── uploads/
│       └── users/                           # โฟลเดอร์เก็บรูปภาพผู้ใช้
├── create_me_users.sql                      # SQL สร้างตารางใหม่
├── update_me_users_table.sql                # SQL อัพเดทตารางเดิม
└── USER_MANAGEMENT_GUIDE.md                 # คู่มือนี้
```

## การใช้งาน

### เข้าถึงระบบ
- URL: `http://localhost:8000/users`
- ต้อง Login เป็น Admin เท่านั้น

### 1. แสดงรายการผู้ใช้
- เข้าไปที่ `/users`
- จะแสดงสถิติและตารางผู้ใช้ทั้งหมด
- แสดงข้อมูล: รูปภาพ, รหัส, Username, ชื่อ, เบอร์โทร, Role, สถานะ, วันที่สร้าง

### 2. เพิ่มผู้ใช้ใหม่
1. คลิกปุ่ม "เพิ่มผู้ใช้"
2. กรอกข้อมูล:
   - **รหัสผู้ใช้*** (ต้องไม่ซ้ำ)
   - **Username*** (ต้องไม่ซ้ำ, ใช้สำหรับ Login)
   - **รหัสผ่าน*** (อย่างน้อย 3 ตัวอักษร)
   - ชื่อ-นามสกุล
   - เบอร์โทร
   - วันเกิด
   - Facebook Name
   - LINE ID
   - **Role*** (User/Agent/Admin)
   - **สถานะ*** (ใช้งาน/ระงับ)
   - รูปภาพ (JPG, PNG, GIF, ไม่เกิน 5MB)
3. คลิก "บันทึก"

### 3. แก้ไขข้อมูลผู้ใช้
1. คลิกปุ่ม <i class="fas fa-edit"></i> (สีเหลือง) ในแถวที่ต้องการแก้ไข
2. แก้ไขข้อมูลที่ต้องการ
3. รหัสผ่าน: เว้นว่างไว้หากไม่ต้องการเปลี่ยน
4. คลิก "บันทึก"

### 4. เปลี่ยนสถานะผู้ใช้
1. คลิกปุ่ม <i class="fas fa-ban"></i> หรือ <i class="fas fa-check"></i> ในแถวที่ต้องการ
2. ยืนยันการเปลี่ยนสถานะ
3. สถานะจะเปลี่ยนระหว่าง "ใช้งาน" และ "ระงับ"

### 5. ลบผู้ใช้
1. คลิกปุ่ม <i class="fas fa-trash"></i> (สีแดง) ในแถวที่ต้องการลบ
2. ยืนยันการลบ
3. ข้อมูลและรูปภาพจะถูกลบถาวร

## ข้อมูลตาราง me_users

### ฟิลด์ทั้งหมด
| ฟิลด์ | ชื่อเต็ม | ประเภท | คำอธิบาย |
|-------|----------|--------|----------|
| id | ID | CHAR(36) | UUID หลัก |
| code | รหัสผู้ใช้ | VARCHAR(20) | รหัสประจำตัว (UNIQUE) |
| username | Username | VARCHAR(50) | ชื่อผู้ใช้สำหรับ Login (UNIQUE) |
| password | รหัสผ่าน | VARCHAR(255) | เข้ารหัสด้วย MD5 |
| name | ชื่อ-นามสกุล | VARCHAR(100) | ชื่อเต็ม |
| tel | เบอร์โทร | VARCHAR(20) | เบอร์โทรศัพท์ |
| birthday | วันเกิด | DATE | วัน/เดือน/ปีเกิด |
| facebook_name | Facebook | VARCHAR(100) | ชื่อ Facebook |
| line_id | LINE ID | VARCHAR(100) | LINE ID |
| **img** | **รูปภาพ** | **VARCHAR(255)** | **Path รูปภาพ (ใหม่)** |
| **status** | **สถานะ** | **ENUM** | **active/suspended (ใหม่)** |
| role | บทบาท | ENUM | admin/agent/user |
| created_date | วันที่สร้าง | DATETIME | วันเวลาที่สร้างข้อมูล |
| created_by | สร้างโดย | VARCHAR(50) | ผู้สร้างข้อมูล |
| updated_date | วันที่แก้ไข | DATETIME | วันเวลาที่แก้ไขล่าสุด |
| updated_by | แก้ไขโดย | VARCHAR(50) | ผู้แก้ไขล่าสุด |

### สถานะ (Status)
- **active**: ใช้งานปกติ
- **suspended**: ระงับการใช้งาน (ไม่สามารถ Login ได้)

### บทบาท (Role)
- **admin**: ผู้ดูแลระบบ (เข้าถึงทุกฟีเจอร์)
- **agent**: ตัวแทน
- **user**: ผู้ใช้ทั่วไป

## การตรวจสอบสิทธิ์

### การเข้าถึงหน้าจัดการผู้ใช้
- ต้อง Login เข้าสู่ระบบ
- Role ต้องเป็น `admin` เท่านั้น
- หากไม่ใช่ admin จะแสดง "Access Denied: Admin only"

### การป้องกัน
- ป้องกันการลบหรือเปลี่ยนสถานะตัวเอง
- ตรวจสอบความซ้ำซ้อนของ code และ username
- Validate ข้อมูลก่อนบันทึก

## การอัพโหลดรูปภาพ

### ข้อกำหนด
- ประเภทไฟล์: JPG, JPEG, PNG, GIF
- ขนาดสูงสุด: 5MB
- เก็บที่: `public/uploads/users/`
- รูปภาพจะถูกเปลี่ยนชื่อเป็น: `{uniqid}_{timestamp}.{ext}`

### การลบรูปภาพ
- เมื่อลบผู้ใช้ รูปภาพจะถูกลบด้วย
- เมื่ออัพโหลดรูปใหม่ รูปเก่าจะถูกลบอัตโนมัติ

## Routes API

### GET Routes
```
/users                  - แสดงรายการผู้ใช้
/users/create          - แสดงฟอร์มเพิ่มผู้ใช้
/users/edit/{id}       - แสดงฟอร์มแก้ไขผู้ใช้
```

### POST Routes
```
/users/store            - บันทึกผู้ใช้ใหม่
/users/update/{id}      - อัพเดทข้อมูลผู้ใช้
/users/delete/{id}      - ลบผู้ใช้
/users/change-status/{id} - เปลี่ยนสถานะผู้ใช้ (AJAX)
```

## การแก้ไขปัญหา

### 1. ไม่สามารถอัพโหลดรูปภาพได้
- ตรวจสอบว่าโฟลเดอร์ `public/uploads/users/` มีอยู่
- ตรวจสอบสิทธิ์การเขียนไฟล์ (755 หรือ 777)
- ตรวจสอบขนาดไฟล์และประเภทไฟล์

### 2. แสดง "Access Denied"
- ตรวจสอบว่า Login แล้ว
- ตรวจสอบว่า Role เป็น admin
- เช็ค session ว่ายังใช้งานได้

### 3. ข้อมูลไม่ถูกบันทึก
- เช็ค error log ใน PHP
- ตรวจสอบการเชื่อมต่อฐานข้อมูล
- ดู $_SESSION['errors'] ที่แสดงบนหน้าจอ

### 4. รูปภาพไม่แสดง
- ตรวจสอบ path ในฐานข้อมูล
- ตรวจสอบว่าไฟล์มีอยู่จริงใน public/uploads/users/
- ตรวจสอบ URL base ในการแสดงผล

## ตัวอย่างการใช้งาน Model

```php
// ในไฟล์ Controller อื่นๆ
$userModel = new User();

// ดึงข้อมูลผู้ใช้ทั้งหมด
$users = $userModel->getAllUsers();

// ดึงข้อมูลผู้ใช้ตาม ID
$user = $userModel->getUserById($userId);

// สร้างผู้ใช้ใหม่
$result = $userModel->createUser([
    'code' => '0001234',
    'username' => 'newuser',
    'password' => 'password123',
    'name' => 'ชื่อผู้ใช้',
    'role' => 'user',
    'status' => 'active'
]);

// อัพเดทข้อมูล
$result = $userModel->updateUser($userId, $data);

// ลบผู้ใช้
$result = $userModel->deleteUser($userId);

// เปลี่ยนสถานะ
$result = $userModel->changeStatus($userId, 'suspended', 'admin_username');

// ค้นหาผู้ใช้
$users = $userModel->searchUsers('keyword');

// นับจำนวนผู้ใช้
$total = $userModel->countUsers();
$activeUsers = $userModel->countUsers(['status' => 'active']);
```

## หมายเหตุ

- ระบบใช้ MD5 ในการเข้ารหัสรหัสผ่านตามโครงสร้างเดิม
- รองรับ PHP 7.0+ (ใช้ null coalescing operator ??)
- ใช้ SweetAlert2 สำหรับ confirmation dialogs
- รองรับ Responsive Design

## การพัฒนาต่อ

### ฟีเจอร์ที่แนะนำเพิ่มเติม
- [ ] ระบบค้นหาและกรองข้อมูล
- [ ] Pagination สำหรับข้อมูลจำนวนมาก
- [ ] Export ข้อมูลเป็น Excel/PDF
- [ ] Import ผู้ใช้จาก CSV
- [ ] ประวัติการแก้ไขข้อมูล (Audit Log)
- [ ] การเปลี่ยนรหัสผ่านด้วยตัวเอง
- [ ] อัพโหลดรูปภาพหลายรูป
- [ ] Crop/Resize รูปภาพอัตโนมัติ

## ผู้พัฒนา
GitHub Copilot - October 16, 2025

## License
MIT License - สามารถใช้งานและแก้ไขได้ตามต้องการ
