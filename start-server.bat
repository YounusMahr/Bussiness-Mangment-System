@echo off
REM Laravel Server Launcher
REM This script starts the Laravel development server

title Laravel Server - Business MS

REM Change to project directory
cd /d "D:\Laravel Projects\Bussiness-MS"

REM Check if Laravel server is already running
netstat -ano | findstr ":8000.*LISTENING" >nul
if %errorlevel% == 0 (
    echo Laravel server is already running on port 8000
    echo.
    echo Server URL: http://127.0.0.1:8000
    echo.
    echo Closing this window will stop the server.
    pause
    exit /b
)

REM Start Laravel development server
echo ========================================
echo   Laravel Development Server
echo   Business Management System
echo ========================================
echo.
echo Starting server on: http://127.0.0.1:8000
echo.
echo IMPORTANT: Keep this window open!
echo Closing this window will stop the server.
echo.
echo Press Ctrl+C to stop the server manually
echo ========================================
echo.

REM Start the server
php artisan serve --host=127.0.0.1 --port=8000

REM If server stops, show message
echo.
echo Server stopped.
pause

