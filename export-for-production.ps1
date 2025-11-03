# สคริปต์สำหรับ Export โปรเจคเพื่อส่งให้ทีม Production
# สร้างโดย: GitHub Copilot
# วันที่: 3 พฤศจิกายน 2568

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  MESUK-METER Production Export Tool" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# ตั้งค่าตัวแปร
$projectRoot = $PSScriptRoot
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$exportName = "MESUK-METER_$timestamp"
$tempExportPath = Join-Path $projectRoot "temp_export"
$outputZipPath = Join-Path $projectRoot "$exportName.zip"

# สร้างโฟลเดอร์ชั่วคราว
Write-Host "[1/5] กำลังสร้างโฟลเดอร์ชั่วคราว..." -ForegroundColor Yellow
if (Test-Path $tempExportPath) {
    Remove-Item -Recurse -Force $tempExportPath
}
New-Item -ItemType Directory -Path $tempExportPath | Out-Null

# Export จาก Git (ไม่รวม .git folder)
Write-Host "[2/5] กำลัง Export โค้ดจาก Git..." -ForegroundColor Yellow
git archive --format=tar HEAD | tar -x -C $tempExportPath

# คัดลอก vendor/ ถ้ามี (ถ้าไม่มีจะข้ามไป)
Write-Host "[3/5] กำลังตรวจสอบและคัดลอก vendor/..." -ForegroundColor Yellow
$vendorPath = Join-Path $projectRoot "vendor"
if (Test-Path $vendorPath) {
    Write-Host "   -> พบโฟลเดอร์ vendor/ กำลังคัดลอก..." -ForegroundColor Green
    Copy-Item -Path $vendorPath -Destination (Join-Path $tempExportPath "vendor") -Recurse -Force
} else {
    Write-Host "   -> ไม่พบโฟลเดอร์ vendor/ (ข้ามขั้นตอนนี้)" -ForegroundColor Gray
}

# คัดลอก .env ถ้ามี (สำหรับการตั้งค่า production)
$envPath = Join-Path $projectRoot ".env"
if (Test-Path $envPath) {
    Write-Host "   -> พบไฟล์ .env กำลังคัดลอก..." -ForegroundColor Green
    Copy-Item -Path $envPath -Destination $tempExportPath -Force
}

# คัดลอกไฟล์อื่นๆ ที่ถูก ignore แต่จำเป็นสำหรับ production
$uploadsPath = Join-Path $projectRoot "public\uploads"
if (Test-Path $uploadsPath) {
    Write-Host "   -> พบโฟลเดอร์ uploads/ กำลังคัดลอก..." -ForegroundColor Green
    $targetUploads = Join-Path $tempExportPath "public\uploads"
    if (-not (Test-Path $targetUploads)) {
        New-Item -ItemType Directory -Path $targetUploads -Force | Out-Null
    }
    Copy-Item -Path "$uploadsPath\*" -Destination $targetUploads -Recurse -Force -ErrorAction SilentlyContinue
}

# สร้างไฟล์ README สำหรับ Production
Write-Host "[4/5] กำลังสร้างไฟล์คำแนะนำการติดตั้ง..." -ForegroundColor Yellow
$readmeContent = "# MESUK-METER - Production Deployment Package`r`n" +
"Export Date: $(Get-Date -Format 'dd/MM/yyyy HH:mm:ss')`r`n`r`n" +
"## Files Included:`r`n" +
"- All code from Git repository`r`n" +
"- vendor/ folder (PHP dependencies)`r`n" +
"- .env file (need to configure for production server)`r`n" +
"- uploads/ folder (if exists)`r`n`r`n" +
"## Installation Steps on Production Server:`r`n`r`n" +
"### 1. Upload Files`r`n" +
"- Extract this ZIP and upload all files to server`r`n`r`n" +
"### 2. Database Setup`r`n" +
"- Edit .env or config/database.php to match your server`r`n" +
"- Import database.sql into MySQL`r`n`r`n" +
"### 3. Set Permissions (Important!)`r`n" +
"chmod -R 755 .`r`n" +
"chmod -R 777 public/uploads`r`n`r`n" +
"### 4. Web Server Configuration`r`n" +
"- Set Document Root to project root folder (not public/)`r`n" +
"- Set PHP version to >= 5.6`r`n`r`n" +
"### 5. Testing`r`n" +
"- Access http://your-domain.com`r`n" +
"- Try to login`r`n`r`n" +
"## Notes:`r`n" +
"- Remember to edit .env config before deploy`r`n" +
"- Check PHP version on server`r`n" +
"- Backup existing database before update`r`n`r`n" +
"## Contact:`r`n" +
"- Repository: https://github.com/ThanasitAio/MESUK-METER`r`n"

Set-Content -Path (Join-Path $tempExportPath "PRODUCTION_README.txt") -Value $readmeContent -Encoding UTF8

# สร้างไฟล์ ZIP
Write-Host "[5/5] กำลังสร้างไฟล์ ZIP..." -ForegroundColor Yellow

# ลบไฟล์ ZIP เก่าถ้ามี
if (Test-Path $outputZipPath) {
    Remove-Item -Force $outputZipPath
}

# สร้าง ZIP
Compress-Archive -Path "$tempExportPath\*" -DestinationPath $outputZipPath -CompressionLevel Optimal

# ลบโฟลเดอร์ชั่วคราว
Remove-Item -Recurse -Force $tempExportPath

# แสดงผลลัพธ์
Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  ✅ Export เสร็จสมบูรณ์!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "📦 ไฟล์ ZIP ถูกสร้างที่: " -NoNewline
Write-Host "$outputZipPath" -ForegroundColor Cyan
Write-Host ""
Write-Host "📊 ขนาดไฟล์: " -NoNewline
$fileSize = (Get-Item $outputZipPath).Length
if ($fileSize -gt 1MB) {
    Write-Host ("{0:N2} MB" -f ($fileSize / 1MB)) -ForegroundColor Cyan
} else {
    Write-Host ("{0:N2} KB" -f ($fileSize / 1KB)) -ForegroundColor Cyan
}
Write-Host ""
Write-Host "📝 คำแนะนำ:" -ForegroundColor Yellow
Write-Host "   1. ส่งไฟล์ ZIP นี้ให้ทีม Production" -ForegroundColor White
Write-Host "   2. ให้แตกไฟล์และอ่าน PRODUCTION_README.txt" -ForegroundColor White
Write-Host "   3. แก้ไขค่า config ใน .env ก่อน deploy" -ForegroundColor White
Write-Host ""
Write-Host "กด Enter เพื่อเปิดโฟลเดอร์..." -ForegroundColor Gray
$null = Read-Host

# เปิดโฟลเดอร์
explorer.exe /select,$outputZipPath
