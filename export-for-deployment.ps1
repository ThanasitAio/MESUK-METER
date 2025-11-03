# Export MESUK-METER for Production (Simple Version)
# This script creates a complete ZIP package ready for production deployment

$ErrorActionPreference = "Stop"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  MESUK-METER Production Exporter" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Configuration
$projectRoot = $PSScriptRoot
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$zipFileName = "MESUK-METER_Production_$timestamp.zip"
$outputZipPath = Join-Path $projectRoot $zipFileName
$tempFolder = Join-Path $projectRoot "temp_export_$timestamp"

try {
    # Step 1: Create temporary export folder
    Write-Host "[Step 1/6] Creating temporary folder..." -ForegroundColor Yellow
    if (Test-Path $tempFolder) {
        Remove-Item -Recurse -Force $tempFolder
    }
    New-Item -ItemType Directory -Path $tempFolder | Out-Null
    Write-Host "   Success!" -ForegroundColor Green

    # Step 2: Copy all files (respecting .gitignore)
    Write-Host "[Step 2/6] Copying project files..." -ForegroundColor Yellow
    
    # Get list of files tracked by git
    $gitFiles = git ls-files
    $totalFiles = $gitFiles.Count
    $counter = 0
    
    foreach ($file in $gitFiles) {
        $counter++
        $sourcePath = Join-Path $projectRoot $file
        $destPath = Join-Path $tempFolder $file
        $destDir = Split-Path -Parent $destPath
        
        if (-not (Test-Path $destDir)) {
            New-Item -ItemType Directory -Path $destDir -Force | Out-Null
        }
        
        Copy-Item -Path $sourcePath -Destination $destPath -Force
        
        if ($counter % 50 -eq 0) {
            Write-Host "   Copied $counter / $totalFiles files..." -ForegroundColor Gray
        }
    }
    Write-Host "   Copied $totalFiles files!" -ForegroundColor Green

    # Step 3: Copy vendor folder if exists (important for production!)
    Write-Host "[Step 3/6] Checking additional folders..." -ForegroundColor Yellow
    $vendorPath = Join-Path $projectRoot "vendor"
    if (Test-Path $vendorPath) {
        Write-Host "   Copying vendor/ folder..." -ForegroundColor Cyan
        Copy-Item -Path $vendorPath -Destination (Join-Path $tempFolder "vendor") -Recurse -Force
        Write-Host "   vendor/ copied!" -ForegroundColor Green
    } else {
        Write-Host "   No vendor/ folder found (OK if not using Composer)" -ForegroundColor Gray
    }

    # Copy .env if exists
    $envPath = Join-Path $projectRoot ".env"
    if (Test-Path $envPath) {
        Write-Host "   Copying .env file..." -ForegroundColor Cyan
        Copy-Item -Path $envPath -Destination $tempFolder -Force
        Write-Host "   .env copied! (Remember to edit for production)" -ForegroundColor Green
    }

    # Copy uploads folder if exists
    $uploadsPath = Join-Path $projectRoot "public\uploads"
    if (Test-Path $uploadsPath) {
        Write-Host "   Copying uploads/ folder..." -ForegroundColor Cyan
        $targetUploads = Join-Path $tempFolder "public\uploads"
        if (-not (Test-Path $targetUploads)) {
            New-Item -ItemType Directory -Path $targetUploads -Force | Out-Null
        }
        Copy-Item -Path "$uploadsPath\*" -Destination $targetUploads -Recurse -Force -ErrorAction SilentlyContinue
        Write-Host "   uploads/ copied!" -ForegroundColor Green
    }

    # Step 4: Create deployment guide
    Write-Host "[Step 4/6] Creating deployment guide..." -ForegroundColor Yellow
    
    $deployGuide = @"
================================================================================
MESUK-METER - Production Deployment Package
================================================================================
Export Date: $(Get-Date -Format 'dd MMMM yyyy HH:mm:ss')
Exported By: $env:USERNAME
Repository: https://github.com/ThanasitAio/MESUK-METER

================================================================================
WHAT'S INCLUDED IN THIS PACKAGE
================================================================================
[x] All source code files from Git repository
[x] vendor/ folder (if using Composer dependencies)
[x] .env configuration file (MUST EDIT BEFORE USE!)
[x] public/uploads/ folder with uploaded files
[x] Complete database schema (database.sql)

================================================================================
INSTALLATION INSTRUCTIONS
================================================================================

Step 1: EXTRACT FILES
----------------------
- Extract this entire ZIP file to your production server
- Recommended path: /var/www/html/mesuk-meter or C:\inetpub\wwwroot\mesuk-meter

Step 2: CONFIGURE DATABASE
---------------------------
Option A: Using .env file
   1. Open .env file
   2. Update these settings:
      DB_HOST=your_server
      DB_NAME=your_database
      DB_USER=your_username
      DB_PASS=your_password

Option B: Using config/database.php
   1. Open config/database.php
   2. Edit database connection settings

Step 3: IMPORT DATABASE
------------------------
   1. Create a new MySQL database
   2. Import database.sql file:
      mysql -u username -p database_name < database.sql

Step 4: SET FILE PERMISSIONS (Linux/Unix servers)
--------------------------------------------------
   chmod -R 755 /path/to/project
   chmod -R 777 /path/to/project/public/uploads
   chown -R www-data:www-data /path/to/project

Step 5: CONFIGURE WEB SERVER
-----------------------------
Apache (.htaccess already included):
   - Point DocumentRoot to project root folder
   - Enable mod_rewrite
   - Restart Apache

Nginx:
   - Configure root to project folder
   - Set up PHP-FPM
   - Restart Nginx

Step 6: TEST YOUR DEPLOYMENT
-----------------------------
   1. Visit: http://your-domain.com
   2. Try to login with existing user
   3. Check all features are working
   4. Verify file uploads work

================================================================================
IMPORTANT SECURITY NOTES
================================================================================
[!] Change .env settings for production environment
[!] Use strong database passwords
[!] Set proper file permissions
[!] Enable HTTPS if handling sensitive data
[!] Backup database before any updates

================================================================================
TROUBLESHOOTING
================================================================================

Problem: White screen / 500 error
Solution: Check PHP error logs and verify PHP version >= 5.6

Problem: Database connection failed
Solution: Verify database credentials in .env or config/database.php

Problem: Cannot upload files
Solution: Check folder permissions on public/uploads (should be 777)

Problem: Missing vendor folder
Solution: Run 'composer install' on the server (if using Composer)

================================================================================
SUPPORT & CONTACT
================================================================================
Project Repository: https://github.com/ThanasitAio/MESUK-METER
Created: $(Get-Date -Format 'yyyy-MM-dd')

For issues, please check the repository documentation or contact your
development team.

================================================================================
"@

    Set-Content -Path (Join-Path $tempFolder "DEPLOY_GUIDE.txt") -Value $deployGuide -Encoding UTF8
    Write-Host "   Deployment guide created!" -ForegroundColor Green

    # Step 5: Create ZIP file
    Write-Host "[Step 5/6] Creating ZIP archive..." -ForegroundColor Yellow
    if (Test-Path $outputZipPath) {
        Remove-Item -Force $outputZipPath
    }
    
    Compress-Archive -Path "$tempFolder\*" -DestinationPath $outputZipPath -CompressionLevel Optimal
    Write-Host "   ZIP file created!" -ForegroundColor Green

    # Step 6: Cleanup
    Write-Host "[Step 6/6] Cleaning up..." -ForegroundColor Yellow
    Remove-Item -Recurse -Force $tempFolder
    Write-Host "   Cleanup complete!" -ForegroundColor Green

    # Display results
    Write-Host ""
    Write-Host "================================================================================" -ForegroundColor Green
    Write-Host "                   EXPORT COMPLETED SUCCESSFULLY!" -ForegroundColor Green
    Write-Host "================================================================================" -ForegroundColor Green
    Write-Host ""
    
    $zipFile = Get-Item $outputZipPath
    $fileSize = $zipFile.Length
    $sizeDisplay = if ($fileSize -gt 1MB) { "{0:N2} MB" -f ($fileSize / 1MB) } else { "{0:N2} KB" -f ($fileSize / 1KB) }
    
    Write-Host "ZIP File: " -NoNewline
    Write-Host $zipFile.Name -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Location: " -NoNewline
    Write-Host $zipFile.FullName -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Size: " -NoNewline
    Write-Host $sizeDisplay -ForegroundColor Cyan
    Write-Host ""
    Write-Host "================================================================================" -ForegroundColor Yellow
    Write-Host "                         NEXT STEPS" -ForegroundColor Yellow
    Write-Host "================================================================================" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "  1. " -NoNewline -ForegroundColor White
    Write-Host "Send the ZIP file via LINE or upload to server" -ForegroundColor White
    Write-Host ""
    Write-Host "  2. " -NoNewline -ForegroundColor White
    Write-Host "Recipient should extract and read DEPLOY_GUIDE.txt" -ForegroundColor White
    Write-Host ""
    Write-Host "  3. " -NoNewline -ForegroundColor White
    Write-Host "Configure .env file with production database settings" -ForegroundColor White
    Write-Host ""
    Write-Host "  4. " -NoNewline -ForegroundColor White
    Write-Host "Import database.sql to production database" -ForegroundColor White
    Write-Host ""
    Write-Host "  5. " -NoNewline -ForegroundColor White
    Write-Host "Set proper file permissions (on Linux servers)" -ForegroundColor White
    Write-Host ""
    Write-Host "  6. " -NoNewline -ForegroundColor White
    Write-Host "Test the deployment thoroughly" -ForegroundColor White
    Write-Host ""
    Write-Host "================================================================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Opening folder..." -ForegroundColor Gray
    
    # Open Explorer to show the file
    Start-Sleep -Seconds 2
    explorer.exe /select,$outputZipPath
    
} catch {
    Write-Host ""
    Write-Host "ERROR: Export failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    
    # Cleanup on error
    if (Test-Path $tempFolder) {
        Remove-Item -Recurse -Force $tempFolder -ErrorAction SilentlyContinue
    }
    
    exit 1
}
