-- สร้างตาราง login_history สำหรับเก็บประวัติการเข้าระบบ
CREATE TABLE IF NOT EXISTS `login_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` char(36) NOT NULL,
  `username` varchar(50) NOT NULL,
  `login_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success',
  KEY `user_id` (`user_id`),
  KEY `username` (`username`),
  KEY `login_time` (`login_time`),
  FOREIGN KEY (`user_id`) REFERENCES `me_users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- สร้าง index สำหรับ query ที่ใช้บ่อย
CREATE INDEX idx_login_time_status ON login_history(login_time, status);
