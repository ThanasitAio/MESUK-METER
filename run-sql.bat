@echo off
REM Script สำหรับรัน SQL ไฟล์อัตโนมัติ (Windows)
REM ต้องตั้งค่า MySQL Path และข้อมูล Database ก่อนใช้งาน

echo ========================================
echo   MESUK User Management - SQL Runner
echo ========================================
echo.

REM ตั้งค่า MySQL
SET MYSQL_PATH=C:\xampp\mysql\bin
SET DB_HOST=localhost
SET DB_USER=root
SET DB_PASS=
SET DB_NAME=mesuk_db

echo เลือกไฟล์ SQL ที่ต้องการรัน:
echo [1] create_me_users.sql - สร้างตารางใหม่
echo [2] update_me_users_table.sql - อัพเดทตารางเดิม
echo [3] test_user_management.sql - เพิ่มข้อมูลทดสอบ
echo [4] ออก
echo.

SET /P choice="เลือก (1-4): "

IF "%choice%"=="1" (
    echo.
    echo รัน create_me_users.sql...
    "%MYSQL_PATH%\mysql.exe" -h %DB_HOST% -u %DB_USER% -p%DB_PASS% %DB_NAME% < create_me_users.sql
    IF %ERRORLEVEL% EQU 0 (
        echo สำเร็จ! ตาราง me_users ถูกสร้างเรียบร้อยแล้ว
    ) ELSE (
        echo เกิดข้อผิดพลาด! กรุณาตรวจสอบ
    )
) ELSE IF "%choice%"=="2" (
    echo.
    echo รัน update_me_users_table.sql...
    "%MYSQL_PATH%\mysql.exe" -h %DB_HOST% -u %DB_USER% -p%DB_PASS% %DB_NAME% < update_me_users_table.sql
    IF %ERRORLEVEL% EQU 0 (
        echo สำเร็จ! ตาราง me_users ถูกอัพเดทเรียบร้อยแล้ว
    ) ELSE (
        echo เกิดข้อผิดพลาด! กรุณาตรวจสอบ
    )
) ELSE IF "%choice%"=="3" (
    echo.
    echo รัน test_user_management.sql...
    "%MYSQL_PATH%\mysql.exe" -h %DB_HOST% -u %DB_USER% -p%DB_PASS% %DB_NAME% < test_user_management.sql
    IF %ERRORLEVEL% EQU 0 (
        echo สำเร็จ! ข้อมูลทดสอบถูกเพิ่มเรียบร้อยแล้ว
    ) ELSE (
        echo เกิดข้อผิดพลาด! กรุณาตรวจสอบ
    )
) ELSE IF "%choice%"=="4" (
    echo ออกจากโปรแกรม
    exit /b
) ELSE (
    echo ตัวเลือกไม่ถูกต้อง!
)

echo.
echo กด Enter เพื่อปิดหน้าต่าง...
pause > nul
