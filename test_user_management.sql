-- SQL สำหรับทดสอบระบบจัดการผู้ใช้
-- เพิ่มข้อมูลทดสอบหลายบทบาทและสถานะ

-- ลบข้อมูลเก่า (ถ้ามี)
-- TRUNCATE TABLE me_users;

-- เพิ่มผู้ใช้ทดสอบ

-- Admin (ใช้งาน)
INSERT INTO `me_users` 
(`id`, `code`, `username`, `password`, `name`, `tel`, `birthday`, `facebook_name`, `line_id`, `img`, `status`, `role`, `created_by`) 
VALUES
(UUID(), '0000999', '0000999', MD5('999'), 'ธนสิทธิ์ อิ๋วสกุล', '0812345678', '1990-01-15', 'Thanasit.Aio', 'thanasit_line', NULL, 'active', 'admin', 'system');

-- Agent (ใช้งาน)
INSERT INTO `me_users` 
(`id`, `code`, `username`, `password`, `name`, `tel`, `birthday`, `facebook_name`, `line_id`, `img`, `status`, `role`, `created_by`) 
VALUES
(UUID(), '0000998', '0000998', MD5('998'), 'สมชาย ใจดี', '0823456789', '1985-05-20', 'Somchai.Agent', 'somchai_line', NULL, 'active', 'agent', 'system');

-- Agent (ระงับ)
INSERT INTO `me_users` 
(`id`, `code`, `username`, `password`, `name`, `tel`, `birthday`, `facebook_name`, `line_id`, `img`, `status`, `role`, `created_by`) 
VALUES
(UUID(), '0000997', '0000997', MD5('997'), 'สมหญิง รักงาน', '0834567890', '1988-03-10', 'Somying.Agent', 'somying_line', NULL, 'suspended', 'agent', 'system');

-- User (ใช้งาน)
INSERT INTO `me_users` 
(`id`, `code`, `username`, `password`, `name`, `tel`, `birthday`, `facebook_name`, `line_id`, `img`, `status`, `role`, `created_by`) 
VALUES
(UUID(), '0000101', '0000101', MD5('101'), 'ประชา มั่นคง', '0845678901', '1992-07-25', 'Pracha.User', 'pracha_line', NULL, 'active', 'user', 'system'),
(UUID(), '0000102', '0000102', MD5('102'), 'สุดา สวยงาม', '0856789012', '1995-11-30', 'Suda.User', 'suda_line', NULL, 'active', 'user', 'system'),
(UUID(), '0000103', '0000103', MD5('103'), 'วิชัย เก่งดี', '0867890123', '1987-02-14', 'Wichai.User', 'wichai_line', NULL, 'active', 'user', 'system');

-- User (ระงับ)
INSERT INTO `me_users` 
(`id`, `code`, `username`, `password`, `name`, `tel`, `birthday`, `facebook_name`, `line_id`, `img`, `status`, `role`, `created_by`) 
VALUES
(UUID(), '0000104', '0000104', MD5('104'), 'นพดล ผิดพลาด', '0878901234', '1993-09-05', 'Nopadon.User', 'nopadon_line', NULL, 'suspended', 'user', 'system');

-- ตรวจสอบข้อมูล
SELECT 
    code, username, name, role, status, 
    DATE_FORMAT(created_date, '%d/%m/%Y %H:%i') as created 
FROM me_users 
ORDER BY role DESC, status ASC, code ASC;

-- สถิติ
SELECT 
    role, 
    status, 
    COUNT(*) as total 
FROM me_users 
GROUP BY role, status 
ORDER BY role DESC, status ASC;
