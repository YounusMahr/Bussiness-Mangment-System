// Minimal Service Worker - No Caching
// This service worker is registered for PWA functionality but does not cache any data

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
  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  // Skip cross-origin requests
  if (!event.request.url.startsWith(self.location.origin)) {
    return;
  }

  // Skip service worker and manifest requests
  if (event.request.url.includes('/sw.js') || event.request.url.includes('/manifest.json')) {
    return;
  }

  // Pass through all requests to network without caching
  event.respondWith(
    fetch(event.request, { cache: 'no-store' })
      .catch((error) => {
        console.warn('Fetch failed:', error);
        // Let browser handle the error
        throw error;
      })
  );
});
