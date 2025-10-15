# PowerShell script to stop Docker containers
Write-Host "Stopping MESUK-METER Docker containers..." -ForegroundColor Yellow
docker-compose down

Write-Host ""
Write-Host "✓ Containers stopped successfully" -ForegroundColor Green
Write-Host ""
