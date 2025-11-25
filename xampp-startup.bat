@echo off
REM XAMPP Auto-Start Script
REM This script starts Laravel server after XAMPP services are ready
REM Place this in XAMPP's control panel or run it manually

title XAMPP + Laravel Auto-Start

echo Waiting for XAMPP services to start...
timeout /t 10 /nobreak

echo Starting Laravel Development Server...
start "" "%~dp0start-laravel-server.bat"

echo.
echo Laravel server is starting...
echo You can access it at: http://127.0.0.1:6000
echo.
echo To stop the server, close the Laravel Server window or press Ctrl+C
pause

