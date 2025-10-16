-- สร้างตาราง me_users
CREATE TABLE IF NOT EXISTS `me_users` (
  `id` char(36) NOT NULL PRIMARY KEY,
  `code` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `facebook_name` varchar(100) DEFAULT NULL,
  `line_id` varchar(100) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` enum('active','suspended') DEFAULT 'active',
  `role` enum('admin','agent','user') DEFAULT 'user',
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(50) DEFAULT NULL,
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เพิ่มข้อมูลทดสอบ 2 รายการ
INSERT INTO `me_users` 
(`id`, `code`, `username`, `password`, `name`, `role`, `status`, `created_by`) 
VALUES
(UUID(), '0000999', '0000999', MD5('999'), 'ธนสิทธิ์ อิ๋วสกุล', 'admin', 'active', 'system'),
(UUID(), '0000998', '0000998', MD5('998'), 'ตัวแทน', 'agent', 'active', 'system');
