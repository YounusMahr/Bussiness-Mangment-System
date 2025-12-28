# Storage Link Fix for Hostinger Shared Hosting

Since `exec()` function is disabled on Hostinger, you need to create the storage symlink manually.

## Solution 1: Create Symlink Manually (Recommended)

Run these commands in your SSH terminal:

```bash
# Navigate to your project root
cd ~/public_html/bussiness  # or wherever your project is located

# Remove existing storage link if it exists
rm -rf public/storage

# Create the symlink manually
ln -s ../storage/app/public public/storage

# Verify the symlink was created (should show an arrow ->)
ls -la public/storage

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Find your username
whoami

# Set ownership (replace YOUR_USERNAME with your actual username from whoami)
chown -R YOUR_USERNAME:YOUR_USERNAME storage bootstrap/cache
```

**Important:** 
- The symlink path `../storage/app/public` is relative to the `public` directory
- Make sure you're in the project root when running these commands
- If you get "Permission denied", you might need to contact Hostinger support

## Solution 2: Route-Based Solution (Already Implemented)

If symlinks don't work on your hosting, a route has been added to serve storage files through Laravel.

The route `/storage/{path}` will automatically serve files from `storage/app/public/` directory.

**This solution is already active** - your images should work now even without the symlink!

## Verify It's Working

1. Upload an image through your application
2. Check if the image displays correctly
3. If images still don't show:
   - Check browser console for 404 errors
   - Verify file permissions: `ls -la storage/app/public`
   - Check Laravel logs: `storage/logs/laravel.log`

