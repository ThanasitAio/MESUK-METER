-- ฐานข้อมูล MESUK-METER
-- สร้างเมื่อ: October 15, 2025

-- สร้างตาราง ali_member (Users)
CREATE TABLE IF NOT EXISTS `ali_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เพิ่มข้อมูลผู้ใช้ทดสอบ (รหัสผ่านทั้งหมดเป็น: 123456)
INSERT INTO `ali_member` (`username`, `password`, `name`, `email`, `role`) VALUES
('mesuk', '123456', 'MESUK Admin', 'mesuk@example.com', 'admin'),
('admin', '123456', 'Administrator', 'admin@example.com', 'admin'),
('user1', '123456', 'Test User 1', 'user1@example.com', 'user'),
('user2', '123456', 'Test User 2', 'user2@example.com', 'user');
