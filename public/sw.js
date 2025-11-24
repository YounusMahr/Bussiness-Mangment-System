const CACHE_NAME = 'business-ms-v1';
// Reduced cache list to avoid blocking - only cache essential resources
const urlsToCache = [
  '/',
  '/assets/css/soft-ui-dashboard-tailwind.css'
];

// Install event - cache resources
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('Opened cache');
        // Use addAll but catch individual failures
        return Promise.allSettled(
          urlsToCache.map(url => 
            cache.add(url).catch(err => {
              console.warn(`Failed to cache ${url}:`, err);
              return null;
            })
          )
        );
      })
      .catch((error) => {
        console.error('Cache failed:', error);
      })
  );
  self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch event - serve from cache, fallback to network
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

  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Return cached version or fetch from network
        if (response) {
          return response;
        }

        return fetch(event.request)
          .then((response) => {
            // Don't cache if not a valid response
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Clone the response
            const responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(event.request, responseToCache).catch(err => {
                  console.warn('Failed to cache response:', err);
                });
              })
              .catch(err => {
                console.warn('Failed to open cache:', err);
              });

            return response;
          })
          .catch((error) => {
            console.warn('Fetch failed:', error);
            // If fetch fails and it's a document request, try to return cached home page
            if (event.request.destination === 'document') {
              return caches.match('/').catch(() => {
                // If even that fails, let the browser handle it
                return new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
              });
            }
            // For other requests, let the browser handle the error
            throw error;
          });
      })
      .catch((error) => {
        console.warn('Cache match failed:', error);
        // Fallback to network
        return fetch(event.request).catch(() => {
          // If everything fails, return a basic offline response
          if (event.request.destination === 'document') {
            return new Response('You are offline. Please check your connection.', {
              status: 503,
              statusText: 'Service Unavailable',
              headers: { 'Content-Type': 'text/html' }
            });
          }
        });
      })
  );
});

// Background sync for offline actions (optional)
self.addEventListener('sync', (event) => {
  if (event.tag === 'background-sync') {
    event.waitUntil(
      // Perform background sync operations here
      console.log('Background sync triggered')
    );
  }
});

