# PWA Setup Guide

This application has been configured as a Progressive Web App (PWA). Follow these steps to complete the setup:

## 1. Generate PWA Icons

You need to create icon files in multiple sizes. You can use an online tool like:
- https://realfavicongenerator.net/
- https://www.pwabuilder.com/imageGenerator

Required icon sizes:
- 72x72px
- 96x96px
- 128x128px
- 144x144px
- 152x152px
- 192x192px
- 384x384px
- 512x512px

Place all icons in: `public/assets/img/`

## 2. Update Manifest

Edit `public/manifest.json` to customize:
- `name`: Your app's full name
- `short_name`: Short name for app launcher
- `description`: App description
- `theme_color`: Your brand color (currently purple #9333ea)
- `background_color`: Background color for splash screen

## 3. Service Worker

The service worker (`public/sw.js`) is already configured to:
- Cache essential resources
- Provide offline functionality
- Handle background sync
- Support push notifications

You can customize the cache name and URLs in `public/sw.js`.

## 4. Testing

### Local Testing
1. Serve your app over HTTPS (required for PWA)
2. Open Chrome DevTools > Application > Service Workers
3. Check "Offline" to test offline functionality
4. Use "Add to homescreen" to test installation

### Production Testing
1. Deploy to a server with HTTPS
2. Test on mobile devices
3. Verify installation prompts work
4. Test offline functionality

## 5. Features Included

✅ Service Worker for offline support
✅ Web App Manifest
✅ Install prompt handling
✅ Network status detection
✅ Update notifications
✅ Cache management
✅ Background sync support
✅ Push notification support

## 6. Browser Support

- Chrome/Edge: Full support
- Firefox: Full support
- Safari (iOS): Full support (iOS 11.3+)
- Safari (macOS): Full support (macOS 10.13+)

## 7. Customization

### Change Theme Color
Update in `public/manifest.json`:
```json
"theme_color": "#your-color"
```

### Add More Cache URLs
Edit `public/sw.js`:
```javascript
const urlsToCache = [
  '/',
  '/your-page',
  // ... more URLs
];
```

### Customize Install Button
Edit `resources/views/components/pwa-install-button.blade.php`

## 8. Troubleshooting

### Service Worker Not Registering
- Ensure you're using HTTPS (or localhost)
- Check browser console for errors
- Verify `sw.js` is accessible at `/sw.js`

### Icons Not Showing
- Verify icon files exist in `public/assets/img/`
- Check file names match manifest.json
- Ensure icons are PNG format

### Install Prompt Not Showing
- Must be served over HTTPS
- User must visit site multiple times
- Check `beforeinstallprompt` event in console

## 9. Next Steps

1. Generate and add icon files
2. Customize manifest.json
3. Test on mobile devices
4. Deploy to production with HTTPS
5. Monitor service worker performance

For more information, visit: https://web.dev/progressive-web-apps/

