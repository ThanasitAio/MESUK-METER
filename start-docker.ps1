# PowerShell script to run with Docker# PowerShell script to run with Docker

Write-Host "Starting MESUK-METER with Docker..." -ForegroundColor GreenWrite-Host "Starting MESUK-METER with Docker..." -ForegroundColor Green

Write-Host ""Write-Host ""



# Check if Docker is installed# Check if Docker is installed

try {try {

    docker --version | Out-Null    docker --version | Out-Null

    Write-Host "Docker found" -ForegroundColor Green    Write-Host "✓ Docker found" -ForegroundColor Green

} catch {} catch {

    Write-Host "Docker not found. Please install Docker Desktop first." -ForegroundColor Red    Write-Host "✗ Docker not found. Please install Docker Desktop first." -ForegroundColor Red

    Write-Host "Download: https://www.docker.com/products/docker-desktop" -ForegroundColor Yellow    Write-Host "  Download: https://www.docker.com/products/docker-desktop" -ForegroundColor Yellow

    exit 1    exit 1

}}



Write-Host ""Write-Host ""

Write-Host "Starting containers..." -ForegroundColor YellowWrite-Host "Starting containers..." -ForegroundColor Yellow

docker-compose up -ddocker-compose up -d



Write-Host ""Write-Host ""

Write-Host "==================================" -ForegroundColor CyanWrite-Host "==================================" -ForegroundColor Cyan

Write-Host "  MESUK-METER is now running!" -ForegroundColor GreenWrite-Host "  MESUK-METER is now running!" -ForegroundColor Green

Write-Host "==================================" -ForegroundColor CyanWrite-Host "==================================" -ForegroundColor Cyan

Write-Host ""Write-Host ""

Write-Host "Web Application: http://localhost:8000" -ForegroundColor CyanWrite-Host "Web Application: http://localhost:8000" -ForegroundColor Cyan

Write-Host "phpMyAdmin:     http://localhost:8080" -ForegroundColor CyanWrite-Host "phpMyAdmin:     http://localhost:8080" -ForegroundColor Cyan

Write-Host ""Write-Host ""

Write-Host "Database Credentials:" -ForegroundColor YellowWrite-Host "Database Credentials:" -ForegroundColor Yellow

Write-Host "  Host:     db (or localhost from host machine)" -ForegroundColor WhiteWrite-Host "  Host:     db (or localhost from host machine)" -ForegroundColor White

Write-Host "  Username: root" -ForegroundColor WhiteWrite-Host "  Username: root" -ForegroundColor White

Write-Host "  Password: secret" -ForegroundColor WhiteWrite-Host "  Password: secret" -ForegroundColor White

Write-Host "  Database: meesuk_db" -ForegroundColor WhiteWrite-Host "  Database: meesuk_db" -ForegroundColor White

Write-Host ""Write-Host ""

Write-Host "To stop: docker-compose down" -ForegroundColor YellowWrite-Host "To stop: docker-compose down" -ForegroundColor Yellow

Write-Host "To view logs: docker-compose logs -f" -ForegroundColor YellowWrite-Host "To view logs: docker-compose logs -f" -ForegroundColor Yellow

Write-Host ""Write-Host ""

