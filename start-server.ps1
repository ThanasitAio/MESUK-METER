# PowerShell script to start PHP built-in server
Write-Host "Starting MESUK-METER Server..." -ForegroundColor Green
Write-Host "Server will be available at: http://localhost:8000" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""

# Start PHP built-in server
php -S localhost:8000 -t .
