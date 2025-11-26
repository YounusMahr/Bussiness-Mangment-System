# PWA Launcher Setup

This guide will help you set up a desktop shortcut that starts the Laravel server and opens the PWA when clicked.

## Quick Setup

### Option 1: Create Desktop Shortcut (Recommended)

1. **Right-click** on `launch-app.vbs` in your project folder
2. Select **Create shortcut**
3. **Rename** the shortcut to "Business MS" (or any name you prefer)
4. **Right-click** the shortcut and select **Properties**
5. Click **Change Icon** and browse to select an icon (you can use any `.ico` file or extract from `public/assets/img/icon-192x192.png`)
6. **Drag** the shortcut to your Desktop or Start Menu

### Option 2: Use Batch File Directly

1. **Right-click** on `launch-app.bat`
2. Select **Create shortcut**
3. **Rename** and **drag** to Desktop

## How It Works

When you click the shortcut:
1. ✅ Checks if the Laravel server (port 8000) and Vite dev server (port 5173) are already running
2. ✅ If not running, starts both servers in separate windows
3. ✅ Opens your browser to `http://127.0.0.1:8000`
4. ✅ Server windows stay open (close them to stop the servers)

## Files

- **`launch-app.vbs`** - Silent launcher (no console window) - **Recommended for desktop shortcut**
- **`launch-app.bat`** - Batch launcher (starts both Laravel and Vite servers)
- **`start-server.bat`** - Laravel server starter only (for manual server start)

## Port Configuration

- **Laravel server** runs on **port 8000** by default
- **Vite dev server** runs on **port 5173** by default

To change Laravel port:
1. Edit `launch-app.bat` and change `:8000` to your desired port
2. Edit `start-server.bat` and change `--port=8000` to your desired port
3. Update the URL in both files from `http://127.0.0.1:8000` to your new port

To change Vite port:
1. Edit `vite.config.js` and add `server: { port: YOUR_PORT }`
2. Update `launch-app.bat` to check for the new port instead of `:5173`

## Troubleshooting

### Server Won't Start
- Make sure PHP is in your system PATH
- Make sure Node.js and npm are installed and in your system PATH
- Check if ports are already in use:
  - Laravel: `netstat -ano | findstr :8000`
  - Vite: `netstat -ano | findstr :5173`
- Verify XAMPP MySQL is running
- Run `npm install` if you haven't installed dependencies

### Browser Opens But Shows Error
- Wait a few seconds for the server to fully start
- Check the server window for error messages
- Verify `.env` file has correct database configuration

### Shortcut Not Working
- Make sure the file paths in the scripts match your project location
- Try running `launch-app.bat` directly to see error messages
- Check Windows security settings (may block VBS scripts)
