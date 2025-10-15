# PowerShell script for project setup
Write-Host "==================================" -ForegroundColor Cyan
Write-Host "  MESUK-METER Setup Script" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Check PHP installation
Write-Host "Checking PHP installation..." -ForegroundColor Yellow
try {
    $phpVersion = php -v 2>&1 | Select-Object -First 1
    Write-Host "✓ PHP found: $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ PHP not found. Please install PHP first." -ForegroundColor Red
    Write-Host "  Download: https://windows.php.net/download/" -ForegroundColor Yellow
    exit 1
}

Write-Host ""

# Check .env file
Write-Host "Checking environment configuration..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Write-Host "✓ .env file exists" -ForegroundColor Green
} else {
    if (Test-Path ".env.example") {
        Write-Host "Creating .env from .env.example..." -ForegroundColor Yellow
        Copy-Item ".env.example" ".env"
        Write-Host "✓ .env file created" -ForegroundColor Green
        Write-Host "⚠ Please edit .env file with your database credentials" -ForegroundColor Yellow
    } else {
        Write-Host "✗ .env.example not found" -ForegroundColor Red
        exit 1
    }
}

Write-Host ""

# Check MySQL connection
Write-Host "⚠ Make sure MySQL is running before starting the server" -ForegroundColor Yellow
Write-Host ""

# Success message
Write-Host "==================================" -ForegroundColor Cyan
Write-Host "  Setup Complete!" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "To start the server, run:" -ForegroundColor Cyan
Write-Host "  .\start-server.ps1" -ForegroundColor White
Write-Host ""
Write-Host "Or use Docker:" -ForegroundColor Cyan
Write-Host "  docker-compose up -d" -ForegroundColor White
Write-Host ""
