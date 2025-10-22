-- ตรวจสอบข้อมูลที่บันทึกล่าสุดในตาราง me_meter
SELECT 
    meter_id,
    meter_type,
    pcode,
    month,
    year,
    reading_value,
    created_at,
    created_by,
    updated_at,
    updated_by
FROM me_meter
ORDER BY created_at DESC
LIMIT 10;

-- ตรวจสอบข้อมูลที่บันทึกล่าสุดในตาราง me_meter_ohter
SELECT 
    meter_ohter_id,
    meter_ohter_type,
    pcode,
    month,
    year,
    price,
    created_at,
    created_by,
    updated_at,
    updated_by
FROM me_meter_ohter
ORDER BY created_at DESC
LIMIT 10;

-- นับจำนวนข้อมูลทั้งหมด
SELECT 
    'me_meter' as table_name,
    COUNT(*) as total_records
FROM me_meter
UNION ALL
SELECT 
    'me_meter_ohter' as table_name,
    COUNT(*) as total_records
FROM me_meter_ohter;
