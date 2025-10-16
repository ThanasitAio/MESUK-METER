# PowerShell Script สำหรับรัน SQL ไฟล์อัตโนมัติ
# ต้องตั้งค่า MySQL Path และข้อมูล Database ก่อนใช้งาน

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  MESUK User Management - SQL Runner" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# ตั้งค่า MySQL
$MYSQL_PATH = "C:\xampp\mysql\bin\mysql.exe"
$DB_HOST = "localhost"
$DB_USER = "root"
$DB_PASS = ""
$DB_NAME = "mesuk_db"

# ตรวจสอบว่า MySQL มีอยู่หรือไม่
if (!(Test-Path $MYSQL_PATH)) {
    Write-Host "ไม่พบ MySQL ที่ $MYSQL_PATH" -ForegroundColor Red
    Write-Host "กรุณาแก้ไขตัวแปร MYSQL_PATH ในไฟล์นี้" -ForegroundColor Yellow
    pause
    exit
}

Write-Host "เลือกไฟล์ SQL ที่ต้องการรัน:" -ForegroundColor Yellow
Write-Host "[1] create_me_users.sql - สร้างตารางใหม่"
Write-Host "[2] update_me_users_table.sql - อัพเดทตารางเดิม"
Write-Host "[3] test_user_management.sql - เพิ่มข้อมูลทดสอบ"
Write-Host "[4] ออก"
Write-Host ""

$choice = Read-Host "เลือก (1-4)"

switch ($choice) {
    "1" {
        Write-Host ""
        Write-Host "รัน create_me_users.sql..." -ForegroundColor Cyan
        
        if ($DB_PASS -eq "") {
            Get-Content "create_me_users.sql" | & $MYSQL_PATH -h $DB_HOST -u $DB_USER $DB_NAME
        } else {
            Get-Content "create_me_users.sql" | & $MYSQL_PATH -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME
        }
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "สำเร็จ! ตาราง me_users ถูกสร้างเรียบร้อยแล้ว" -ForegroundColor Green
        } else {
            Write-Host "เกิดข้อผิดพลาด! กรุณาตรวจสอบ" -ForegroundColor Red
        }
    }
    "2" {
        Write-Host ""
        Write-Host "รัน update_me_users_table.sql..." -ForegroundColor Cyan
        
        if ($DB_PASS -eq "") {
            Get-Content "update_me_users_table.sql" | & $MYSQL_PATH -h $DB_HOST -u $DB_USER $DB_NAME
        } else {
            Get-Content "update_me_users_table.sql" | & $MYSQL_PATH -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME
        }
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "สำเร็จ! ตาราง me_users ถูกอัพเดทเรียบร้อยแล้ว" -ForegroundColor Green
        } else {
            Write-Host "เกิดข้อผิดพลาด! กรุณาตรวจสอบ" -ForegroundColor Red
        }
    }
    "3" {
        Write-Host ""
        Write-Host "รัน test_user_management.sql..." -ForegroundColor Cyan
        
        if ($DB_PASS -eq "") {
            Get-Content "test_user_management.sql" | & $MYSQL_PATH -h $DB_HOST -u $DB_USER $DB_NAME
        } else {
            Get-Content "test_user_management.sql" | & $MYSQL_PATH -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME
        }
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "สำเร็จ! ข้อมูลทดสอบถูกเพิ่มเรียบร้อยแล้ว" -ForegroundColor Green
        } else {
            Write-Host "เกิดข้อผิดพลาด! กรุณาตรวจสอบ" -ForegroundColor Red
        }
    }
    "4" {
        Write-Host "ออกจากโปรแกรม" -ForegroundColor Yellow
        exit
    }
    default {
        Write-Host "ตัวเลือกไม่ถูกต้อง!" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "กด Enter เพื่อปิดหน้าต่าง..." -ForegroundColor Gray
pause
