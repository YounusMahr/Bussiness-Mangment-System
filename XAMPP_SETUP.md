# XAMPP Auto-Start Setup Guide

This guide will help you configure your Laravel project to start automatically when XAMPP starts.

## Method 1: Using XAMPP Control Panel (Recommended)

### Step 1: Configure XAMPP to Run Scripts on Start

1. Open XAMPP Control Panel
2. Click on **Config** button next to Apache
3. Select **httpd.conf**
4. Add this line at the end of the file (before `</IfModule>`):
   ```apache
   # Auto-start Laravel server
   LoadModule cgi_module modules/mod_cgi.so
   ```

### Step 2: Create Startup Script

1. Copy `start-laravel-server.vbs` to your XAMPP installation directory
2. Or use Windows Task Scheduler (see Method 2)

## Method 2: Using Windows Task Scheduler (Best for Auto-Start)

### Step 1: Open Task Scheduler

1. Press `Win + R`
2. Type `taskschd.msc` and press Enter

### Step 2: Create New Task

1. Click **Create Basic Task** in the right panel
2. Name: `Laravel Server Auto-Start`
3. Description: `Starts Laravel development server when XAMPP starts`
4. Trigger: **When the computer starts** or **When I log on**
5. Action: **Start a program**
6. Program/script: Browse and select `start-laravel-server.bat` from your project folder
7. Start in: Your project folder path (e.g., `D:\xampp\htdocs\Bussiness-MS`)
8. Check **Run whether user is logged on or not** (optional)
9. Check **Run with highest privileges** (if needed)
10. Click **Finish**

### Step 3: Add Delay (Optional)

1. Right-click the task you just created
2. Select **Properties**
3. Go to **Triggers** tab
4. Edit the trigger
5. Check **Delay task for**: Set to **30 seconds** (to wait for MySQL to start)
6. Click **OK**

## Method 3: Manual Start Script

Simply double-click `xampp-startup.bat` whenever you want to start both XAMPP services and Laravel server.

## Method 4: Add to XAMPP Control Panel (Advanced)

### Create Custom Button in XAMPP

1. Navigate to your XAMPP installation folder
2. Go to `xampp-control.ini` (usually in the XAMPP root)
3. Add a custom button configuration (this requires XAMPP modification)

## Quick Start Commands

### Start Laravel Server Only
```batch
start-laravel-server.bat
```

### Start Laravel Server (Hidden Window)
```batch
start-laravel-server.vbs
```

### Stop Laravel Server
```batch
stop-laravel-server.bat
```

### Start Everything (XAMPP + Laravel)
```batch
xampp-startup.bat
```

## Configuration

### Change Port (if 8000 is busy)

Edit `start-laravel-server.bat` and change:
```batch
php artisan serve --host=127.0.0.1 --port=8000
```
to:
```batch
php artisan serve --host=127.0.0.1 --port=8001
```

### Change Server Host

To allow access from other devices on your network:
```batch
php artisan serve --host=0.0.0.0 --port=8000
```

## Troubleshooting

### Laravel Server Won't Start

1. **Check PHP is in PATH:**
   - Open Command Prompt
   - Type `php -v`
   - If not found, add XAMPP's PHP to Windows PATH:
     - `C:\xampp\php` (adjust path as needed)

2. **Check Port 8000 is Available:**
   ```batch
   netstat -ano | findstr :8000
   ```
   If something is using it, change the port in the batch file.

3. **Check MySQL is Running:**
   - Open XAMPP Control Panel
   - Ensure MySQL is running (green)

### Server Starts but Can't Connect

1. Check firewall settings
2. Verify Apache is running
3. Check `.env` file database configuration

### Auto-Start Not Working

1. Check Task Scheduler task is enabled
2. Verify the batch file path is correct
3. Check Windows Event Viewer for errors
4. Try running the batch file manually first

## Notes

- The Laravel server runs on port **8000** by default
- Access your application at: `http://127.0.0.1:8000`
- The server will stop when you close the command window
- Use `stop-laravel-server.bat` to stop it programmatically

## Alternative: Use XAMPP Virtual Host (Production-like)

For a more production-like setup, you can configure Apache virtual hosts instead of using `php artisan serve`. This requires additional Apache configuration.

