@echo off
REM Laravel Server Auto-Start Script for XAMPP
REM This script starts the Laravel development server when XAMPP starts

title Laravel Server - Business MS

REM Change to project directory
cd /d "%~dp0"

REM Wait for MySQL to be ready (optional - adjust delay if needed)
timeout /t 5 /nobreak >nul

REM Check if Laravel server is already running
netstat -ano | findstr :6000 >nul
if %errorlevel% == 0 (
    echo Laravel server is already running on port 6000
    pause
    exit /b
)

REM Start Laravel development server
echo Starting Laravel Development Server...
echo Project: Business Management System
echo URL: http://127.0.0.1:6000
echo.
echo Press Ctrl+C to stop the server
echo.

php artisan serve --host=127.0.0.1 --port=6000

pause

