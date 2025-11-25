@echo off
REM Stop Laravel Server Script
REM This script stops the Laravel development server

title Stop Laravel Server

echo Stopping Laravel Development Server...

REM Find and kill PHP processes running on port 6000
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :6000 ^| findstr LISTENING') do (
    echo Killing process %%a
    taskkill /F /PID %%a >nul 2>&1
)

REM Also kill any php artisan serve processes
taskkill /F /IM php.exe /FI "WINDOWTITLE eq Laravel Server*" >nul 2>&1

echo Laravel server stopped.
timeout /t 2 /nobreak >nul

