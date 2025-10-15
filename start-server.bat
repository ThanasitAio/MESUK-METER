@echo off
echo Starting MESUK-METER Server...
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
php -S localhost:8000 -t .
