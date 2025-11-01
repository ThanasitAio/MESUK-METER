-- แก้ไขให้ reading_value และ reading_date สามารถเป็น NULL ได้
-- เพื่อให้สามารถบันทึกข้อมูลได้โดยไม่ต้องกรอกค่ามิเตอร์หรือวันที่

USE `meesuk_db`;

-- แสดงโครงสร้างตารางปัจจุบัน
DESCRIBE `me_meter`;

-- แก้ไข reading_value ให้เป็น NULL ได้
ALTER TABLE `me_meter` 
MODIFY `reading_value` decimal(10,2) DEFAULT NULL COMMENT 'ค่าที่อ่านได้';

-- แก้ไข reading_date ให้เป็น NULL ได้ (ถ้ายังไม่เป็น NULL)
ALTER TABLE `me_meter` 
MODIFY `reading_date` date DEFAULT NULL COMMENT 'วันที่จด';

-- แสดงโครงสร้างตารางหลังแก้ไข
DESCRIBE `me_meter`;

SELECT 'Database schema updated successfully!' as status;
