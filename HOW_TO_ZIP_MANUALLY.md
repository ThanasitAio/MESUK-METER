# 📦 คู่มือการสร้าง ZIP ไฟล์เพื่อส่งโปรเจคเอง

คู่มือนี้แสดงวิธีการสร้าง ZIP ไฟล์สำหรับส่งโปรเจค MESUK-METER ไปยัง Production Server โดยไม่ต้องพึ่งสคริปต์อัตโนมัติ

---

## 🎯 วิธีที่ 1: PowerShell คำสั่งเดียว (แนะนำที่สุด)

```powershell
cd C:\Users\IT\Documents\GitHub\MESUK-METER ; Compress-Archive -Path * -DestinationPath "MESUK-METER_$(Get-Date -Format 'yyyyMMdd_HHmmss').zip" -Force ; explorer .
```

**คำอธิบาย:**
- เข้าโฟลเดอร์โปรเจค
- สร้าง ZIP พร้อมใส่วันที่-เวลาในชื่อไฟล์
- เปิดโฟลเดอร์เพื่อดูไฟล์ที่สร้าง

---

## 🎯 วิธีที่ 2: แบบแยกขั้นตอน (ควบคุมได้มากกว่า)

### ขั้นตอนที่ 1: เปิด PowerShell
กด `Win + X` แล้วเลือก **Windows PowerShell** หรือ **Terminal**

### ขั้นตอนที่ 2: เข้าสู่โฟลเดอร์โปรเจค
```powershell
cd C:\Users\IT\Documents\GitHub\MESUK-METER
```

### ขั้นตอนที่ 3: สร้างชื่อไฟล์มี Timestamp
```powershell
$zipName = "MESUK-METER_" + (Get-Date -Format "yyyyMMdd_HHmmss") + ".zip"
```

### ขั้นตอนที่ 4: สร้างไฟล์ ZIP
```powershell
Compress-Archive -Path * -DestinationPath $zipName -CompressionLevel Optimal -Force
```

### ขั้นตอนที่ 5: ดูข้อมูลไฟล์ที่สร้าง
```powershell
Get-Item $zipName | Format-List Name, Length, LastWriteTime
```

### ขั้นตอนที่ 6: เปิดโฟลเดอร์เพื่อดูไฟล์
```powershell
explorer /select,$zipName
```

---

## 🎯 วิธีที่ 3: เลือกไฟล์เฉพาะ (ไม่เอาไฟล์ขยะ)

```powershell
# เข้าโฟลเดอร์
cd C:\Users\IT\Documents\GitHub\MESUK-METER

# ระบุไฟล์/โฟลเดอร์ที่ต้องการ ZIP
$items = @(
    "app",
    "assets",
    "config",
    "pdf",
    "public",
    "views",
    "*.php",
    "*.md",
    "*.sql",
    "*.json",
    "*.html"
)

# สร้าง ZIP
$zipName = "MESUK-METER_Selected_$(Get-Date -Format 'yyyyMMdd').zip"
Compress-Archive -Path $items -DestinationPath $zipName -Force

# แสดงผล
Write-Host "Created: $zipName" -ForegroundColor Green
explorer .
```

---

## 🎯 วิธีที่ 4: ใช้ Git Export (เฉพาะไฟล์ที่ Git Track)

```powershell
# เข้าโฟลเดอร์
cd C:\Users\IT\Documents\GitHub\MESUK-METER

# สร้างโฟลเดอร์ชั่วคราว
$tempFolder = "temp_export"
New-Item -ItemType Directory -Path $tempFolder -Force

# Export จาก Git
git archive --format=zip HEAD -o temp.zip
Expand-Archive -Path temp.zip -DestinationPath $tempFolder -Force
Remove-Item temp.zip

# คัดลอกโฟลเดอร์ที่ต้องการเพิ่ม (ถ้ามี)
if (Test-Path "public\uploads") {
    Copy-Item -Path "public\uploads" -Destination "$tempFolder\public\uploads" -Recurse -Force
}

# สร้าง ZIP สุดท้าย
$finalZip = "MESUK-METER_Clean_$(Get-Date -Format 'yyyyMMdd_HHmmss').zip"
Compress-Archive -Path "$tempFolder\*" -DestinationPath $finalZip -Force

# ลบโฟลเดอร์ชั่วคราว
Remove-Item -Recurse -Force $tempFolder

# เปิดโฟลเดอร์
Write-Host "ZIP created: $finalZip" -ForegroundColor Green
explorer /select,$finalZip
```

---

## 🎯 วิธีที่ 5: คลิกขวา Windows (ไม่ใช้คำสั่ง - ง่ายที่สุด)

### ขั้นตอน:

1. **เปิด File Explorer** (กด `Win + E`)

2. **ไปที่โฟลเดอร์โปรเจค:**
   ```
   C:\Users\IT\Documents\GitHub\MESUK-METER
   ```

3. **เลือกไฟล์/โฟลเดอร์ที่ต้องการ:**
   - กด `Ctrl + A` เพื่อเลือกทั้งหมด
   - หรือกด `Ctrl` ค้างแล้วคลิกเลือกเฉพาะที่ต้องการ

4. **คลิกขวาที่ไฟล์ที่เลือก:**
   - เลือก **Send to** > **Compressed (zipped) folder**

5. **เปลี่ยนชื่อไฟล์:**
   - เช่น: `MESUK-METER_20251103.zip`

6. **เสร็จสิ้น!** 🎉

---

## ⚠️ ข้อควรระวัง

### ❌ ไฟล์/โฟลเดอร์ที่ไม่ควร ZIP (จะทำให้ไฟล์ใหญ่โดยเปล่าประโยชน์):

```
.git/              -> โฟลเดอร์ Git (ใหญ่มาก ไม่จำเป็น)
vendor/            -> ถ้าไม่ได้ใช้ Composer (โปรเจคนี้ไม่มี)
node_modules/      -> ถ้ามี Node.js dependencies
temp/              -> ไฟล์ชั่วคราว
tmp/               -> ไฟล์ชั่วคราว
.vscode/           -> การตั้งค่า VS Code
.idea/             -> การตั้งค่า IDE
*.log              -> Log files
.env               -> อาจมีข้อมูลลับ (ควรแก้ไขก่อนส่ง)
```

### ✅ ไฟล์ที่ควร ZIP (สำคัญสำหรับ Production):

```
app/               -> โค้ดหลักของระบบ
assets/            -> CSS, JavaScript, รูปภาพ
config/            -> ไฟล์ตั้งค่า
pdf/               -> ไลบรารี FPDF และฟอนต์
public/            -> ไฟล์สาธารณะ
views/             -> Template หน้าเว็บ
*.php              -> ไฟล์ PHP ทั้งหมดในระดับบนสุด
*.sql              -> ไฟล์ database schema
*.md               -> ไฟล์เอกสาร
composer.json      -> ข้อมูล dependencies
.htaccess          -> การตั้งค่า Apache (ถ้าใช้)
```

---

## 📋 เทมเพลต PowerShell สำหรับ Copy-Paste

### แบบง่าย - คำสั่งเดียว:
```powershell
cd C:\Users\IT\Documents\GitHub\MESUK-METER ; Compress-Archive -Path * -DestinationPath "MESUK-METER_$(Get-Date -Format 'yyyyMMdd_HHmmss').zip" -Force ; explorer .
```

### แบบมีคำอธิบาย:
```powershell
# เข้าโฟลเดอร์โปรเจค
cd C:\Users\IT\Documents\GitHub\MESUK-METER

# สร้าง ZIP พร้อม timestamp
Compress-Archive -Path * -DestinationPath "MESUK-METER_$(Get-Date -Format 'yyyyMMdd_HHmmss').zip" -Force

# เปิดโฟลเดอร์เพื่อดูไฟล์
explorer .
```

### แบบควบคุมเต็มที่:
```powershell
# ตั้งค่า
cd C:\Users\IT\Documents\GitHub\MESUK-METER
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$zipName = "MESUK-METER_Production_$timestamp.zip"

# สร้าง ZIP
Compress-Archive -Path * -DestinationPath $zipName -CompressionLevel Optimal -Force

# แสดงผล
Write-Host "ZIP file created successfully!" -ForegroundColor Green
Write-Host "File: $zipName" -ForegroundColor Cyan
Write-Host "Size: $((Get-Item $zipName).Length / 1MB) MB" -ForegroundColor Cyan

# เปิดโฟลเดอร์
explorer /select,$zipName
```

---

## 🚀 การใช้งานจริง

### สถานการณ์ที่ 1: ส่งให้ทีมผ่าน LINE
```powershell
# สร้าง ZIP ขนาดเล็ก (เลือกเฉพาะไฟล์จำเป็น)
cd C:\Users\IT\Documents\GitHub\MESUK-METER
$items = "app", "assets", "config", "pdf", "public", "views", "*.php", "database.sql"
Compress-Archive -Path $items -DestinationPath "MESUK-METER_ForLINE.zip" -Force
explorer .
```

### สถานการณ์ที่ 2: Upload ไปยัง Server
```powershell
# สร้าง ZIP ครบทุกอย่าง พร้อม timestamp
cd C:\Users\IT\Documents\GitHub\MESUK-METER
Compress-Archive -Path * -DestinationPath "MESUK-METER_Production_$(Get-Date -Format 'yyyyMMdd_HHmmss').zip" -Force
explorer .
```

### สถานการณ์ที่ 3: Backup โปรเจค
```powershell
# สร้าง ZIP แบบ Full Backup รวม .git
cd C:\Users\IT\Documents\GitHub\MESUK-METER
$backupName = "MESUK-METER_Backup_$(Get-Date -Format 'yyyyMMdd').zip"
Compress-Archive -Path * -DestinationPath $backupName -Force
Write-Host "Backup created: $backupName" -ForegroundColor Green
```

---

## 🔧 แก้ปัญหาที่พบบ่อย

### ปัญหา 1: ไฟล์ ZIP ใหญ่เกินไป
**วิธีแก้:**
```powershell
# ตรวจสอบขนาดโฟลเดอร์
Get-ChildItem -Recurse | Measure-Object -Property Length -Sum

# ZIP เฉพาะไฟล์สำคัญ (ดูตัวอย่างวิธีที่ 3)
```

### ปัญหา 2: คำสั่ง Compress-Archive ไม่ทำงาน
**วิธีแก้:**
```powershell
# ตรวจสอบเวอร์ชัน PowerShell
$PSVersionTable.PSVersion

# ต้องเป็น PowerShell 5.0 ขึ้นไป
# ถ้าเวอร์ชันเก่า ใช้วิธีคลิกขวา Windows แทน
```

### ปัญหา 3: ไม่มีสิทธิ์เขียนไฟล์
**วิธีแก้:**
```powershell
# เปิด PowerShell แบบ Administrator
# คลิกขวาที่ PowerShell > Run as Administrator
```

---

## 📚 เอกสารเพิ่มเติม

- [QUICKSTART.md](QUICKSTART.md) - เริ่มต้นใช้งานโปรเจค
- [INSTALL-GUIDE.md](INSTALL-GUIDE.md) - คู่มือติดตั้งระบบ
- [README.md](README.md) - ข้อมูลทั่วไปของโปรเจค

---

## 💡 Tips & Tricks

### 1. สร้างชื่อไฟล์แบบกำหนดเอง:
```powershell
$zipName = "MESUK-METER_v1.0_Final.zip"
Compress-Archive -Path * -DestinationPath $zipName -Force
```

### 2. ตรวจสอบขนาดก่อนส่ง:
```powershell
$zip = Get-Item "MESUK-METER_*.zip"
Write-Host "Size: $([math]::Round($zip.Length / 1MB, 2)) MB"
```

### 3. สร้าง Checksum สำหรับตรวจสอบ:
```powershell
Get-FileHash "MESUK-METER_*.zip" -Algorithm SHA256
```

### 4. แยก ZIP เพื่อทดสอบ:
```powershell
Expand-Archive -Path "MESUK-METER_*.zip" -DestinationPath "test_extract" -Force
```

---

## ✅ Checklist ก่อนส่ง ZIP

- [ ] ตรวจสอบว่ามีไฟล์ครบทุกโฟลเดอร์สำคัญ (app, assets, config, views, etc.)
- [ ] ตรวจสอบว่ามีไฟล์ `database.sql`
- [ ] ตรวจสอบขนาดไฟล์ว่าไม่ใหญ่เกินไป (ควรไม่เกิน 50 MB)
- [ ] ตรวจสอบว่าไม่มีโฟลเดอร์ `.git` (ถ้าใช้วิธีที่ 4)
- [ ] เพิ่มไฟล์ README หรือคำแนะนำการติดตั้ง
- [ ] ทดสอบแยก ZIP ในโฟลเดอร์อื่นเพื่อยืนยัน

---

## 📞 ติดต่อสอบถาม

หากมีปัญหาหรือข้อสงสัย:
- Repository: https://github.com/ThanasitAio/MESUK-METER
- Documentation: ดูไฟล์ `.md` ในโปรเจค

---

**สร้างเมื่อ:** 3 พฤศจิกายน 2568  
**อัพเดทล่าสุด:** 3 พฤศจิกายน 2568  
**เวอร์ชัน:** 1.0
