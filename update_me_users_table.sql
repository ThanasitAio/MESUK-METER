-- อัพเดทตาราง me_users เพื่อเพิ่มฟิลด์ img และ status
ALTER TABLE `me_users` 
ADD COLUMN `img` varchar(255) DEFAULT NULL AFTER `line_id`,
ADD COLUMN `status` enum('active','suspended') DEFAULT 'active' AFTER `img`;

-- อัพเดทข้อมูลเก่าให้มี status เป็น active
UPDATE `me_users` SET `status` = 'active' WHERE `status` IS NULL;
