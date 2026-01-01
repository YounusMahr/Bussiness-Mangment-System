// Minimal Service Worker - No Caching
// This service worker is registered for PWA functionality but does not cache any data

// List of authentication routes that should NEVER be intercepted
const AUTH_ROUTES = [
  '/login',
  '/logout',
  '/register',
  '/password/reset',
  '/password/confirm',
  '/email/verify',
  '/auth/',
  '/en/login',
  '/en/logout',
  '/en/register',
  '/en/password/reset',
  '/en/password/confirm',
  '/en/email/verify',
  '/en/auth/',
  '/ar/login',
  '/ar/logout',
  '/ar/register',
  '/ar/password/reset',
  '/ar/password/confirm',
  '/ar/email/verify',
  '/ar/auth/',
];

// Check if a URL is an authentication route
function isAuthRoute(url) {
  const urlPath = new URL(url).pathname;
  return AUTH_ROUTES.some(route => urlPath.includes(route));
}

// Install event - just activate immediately
self.addEventListener('install', (event) => {
  self.skipWaiting();
});

// Activate event - take control immediately
self.addEventListener('activate', (event) => {
  event.waitUntil(
    // Clear any existing caches
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          return caches.delete(cacheName);
        })
      );
    }).then(() => {
      return self.clients.claim();
    })
  );
});

// Fetch event - pass through all requests without caching
self.addEventListener('fetch', (event) => {
  const requestUrl = event.request.url;
  
  // CRITICAL: Never intercept authentication routes
  if (isAuthRoute(requestUrl)) {
    // Let the browser handle auth routes directly - don't intercept at all
    return;
  }

  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  // Skip cross-origin requests
  if (!requestUrl.startsWith(self.location.origin)) {
    return;
  }

  // Skip service worker and manifest requests
  if (requestUrl.includes('/sw.js') || requestUrl.includes('/manifest.json')) {
    return;
  }

  // Pass through all requests to network without caching
  event.respondWith(
    fetch(event.request, { 
      cache: 'no-store',
      credentials: 'same-origin'
    })
      .catch((error) => {
        console.warn('Fetch failed:', error);
        // Let browser handle the error
        throw error;
      })
  );
});
