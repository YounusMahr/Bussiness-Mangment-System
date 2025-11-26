@echo off
REM PWA Launcher - Business Management System
REM Starts Laravel server, Vite dev server, and opens the app

cd /d "D:\Laravel Projects\Bussiness-MS"

REM Check if Laravel server is already running
netstat -ano | findstr ":8000.*LISTENING" >nul
if %errorlevel% == 0 (
    set LARAVEL_RUNNING=1
) else (
    set LARAVEL_RUNNING=0
)

REM Check if Vite dev server is already running
netstat -ano | findstr ":5173.*LISTENING" >nul
if %errorlevel% == 0 (
    set VITE_RUNNING=1
) else (
    set VITE_RUNNING=0
)

REM If both servers are running, just open browser
if %LARAVEL_RUNNING% == 1 if %VITE_RUNNING% == 1 (
    start http://127.0.0.1:8000
    exit /b
)

REM Start Laravel server if not running
if %LARAVEL_RUNNING% == 0 (
    start "Laravel Server - Business MS" cmd /k "cd /d D:\Laravel Projects\Bussiness-MS && php artisan serve --host=127.0.0.1 --port=8000"
)

REM Start Vite dev server if not running
if %VITE_RUNNING% == 0 (
    start "Vite Dev Server - Business MS" cmd /k "cd /d D:\Laravel Projects\Bussiness-MS && npm run dev"
)

REM Wait for servers to start
timeout /t 6 /nobreak >nul

REM Open browser
start http://127.0.0.1:8000

