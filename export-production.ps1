# Export MESUK-METER for Production
# Created: 2025-11-03

$ErrorActionPreference = "Continue"

Write-Host "========================================"  -ForegroundColor Cyan
Write-Host "  MESUK-METER Production Export" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Variables
$projectRoot = $PSScriptRoot
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$exportName = "MESUK-METER_$timestamp"
$tempExportPath = Join-Path $projectRoot "temp_export"
$outputZipPath = Join-Path $projectRoot "$exportName.zip"

# Step 1: Create temp folder
Write-Host "[1/5] Creating temporary folder..." -ForegroundColor Yellow
if (Test-Path $tempExportPath) {
    Remove-Item -Recurse -Force $tempExportPath
}
New-Item -ItemType Directory -Path $tempExportPath | Out-Null

# Step 2: Export from Git
Write-Host "[2/5] Exporting code from Git..." -ForegroundColor Yellow
Set-Location $projectRoot
git archive --format=tar HEAD | tar -x -C $tempExportPath

# Step 3: Copy vendor folder if exists
Write-Host "[3/5] Checking vendor folder..." -ForegroundColor Yellow
$vendorPath = Join-Path $projectRoot "vendor"
if (Test-Path $vendorPath) {
    Write-Host "   -> Copying vendor folder..." -ForegroundColor Green
    Copy-Item -Path $vendorPath -Destination (Join-Path $tempExportPath "vendor") -Recurse -Force
} else {
    Write-Host "   -> vendor folder not found (skipped)" -ForegroundColor Gray
}

# Copy .env if exists
$envPath = Join-Path $projectRoot ".env"
if (Test-Path $envPath) {
    Write-Host "   -> Copying .env file..." -ForegroundColor Green
    Copy-Item -Path $envPath -Destination $tempExportPath -Force
}

# Copy uploads folder if exists
$uploadsPath = Join-Path $projectRoot "public\uploads"
if (Test-Path $uploadsPath) {
    Write-Host "   -> Copying uploads folder..." -ForegroundColor Green
    $targetUploads = Join-Path $tempExportPath "public\uploads"
    if (-not (Test-Path $targetUploads)) {
        New-Item -ItemType Directory -Path $targetUploads -Force | Out-Null
    }
    Copy-Item -Path "$uploadsPath\*" -Destination $targetUploads -Recurse -Force -ErrorAction SilentlyContinue
}

# Step 4: Create README
Write-Host "[4/5] Creating installation guide..." -ForegroundColor Yellow
$readme = "MESUK-METER - Production Deployment Package`r`n"
$readme += "Export Date: $(Get-Date -Format 'dd/MM/yyyy HH:mm:ss')`r`n`r`n"
$readme += "Installation Steps:`r`n"
$readme += "1. Extract this ZIP to your server`r`n"
$readme += "2. Edit .env or config/database.php for your server settings`r`n"
$readme += "3. Import database.sql to MySQL`r`n"
$readme += "4. Set permissions: chmod -R 755 . && chmod -R 777 public/uploads`r`n"
$readme += "5. Configure web server to point to project root`r`n"
$readme += "6. Test at http://your-domain.com`r`n`r`n"
$readme += "Repository: https://github.com/ThanasitAio/MESUK-METER`r`n"

Set-Content -Path (Join-Path $tempExportPath "PRODUCTION_README.txt") -Value $readme -Encoding UTF8

# Step 5: Create ZIP
Write-Host "[5/5] Creating ZIP file..." -ForegroundColor Yellow
if (Test-Path $outputZipPath) {
    Remove-Item -Force $outputZipPath
}
Compress-Archive -Path "$tempExportPath\*" -DestinationPath $outputZipPath -CompressionLevel Optimal

# Cleanup
Remove-Item -Recurse -Force $tempExportPath

# Results
Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Export Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "ZIP file created at: " -NoNewline
Write-Host "$outputZipPath" -ForegroundColor Cyan
Write-Host ""

$fileSize = (Get-Item $outputZipPath).Length
if ($fileSize -gt 1MB) {
    $sizeText = "{0:N2} MB" -f ($fileSize / 1MB)
} else {
    $sizeText = "{0:N2} KB" -f ($fileSize / 1KB)
}
Write-Host "File size: $sizeText" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Send this ZIP file to your team" -ForegroundColor White
Write-Host "2. Extract and read PRODUCTION_README.txt" -ForegroundColor White
Write-Host "3. Configure .env before deployment" -ForegroundColor White
Write-Host ""

# Open folder
explorer.exe /select,$outputZipPath
