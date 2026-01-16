// PWA Registration and Installation
(function() {
  'use strict';

  console.log('PWA: Script loaded, initializing...');

  // Check if service worker is disabled (for troubleshooting)
  const swDisabled = localStorage.getItem('sw-disabled') === 'true';
  
  // Check if we're on an authentication page - don't register SW on auth pages
  const isAuthPage = window.location.pathname.includes('/login') || 
                     window.location.pathname.includes('/logout') ||
                     window.location.pathname.includes('/register') ||
                     window.location.pathname.includes('/password/') ||
                     window.location.pathname.includes('/email/verify') ||
                     window.location.pathname.includes('/auth/');

  // If on auth page and service worker is already registered, unregister it
  if (isAuthPage && 'serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then((registrations) => {
      registrations.forEach((registration) => {
        registration.unregister().then((success) => {
          if (success) {
            console.log('Service worker unregistered on auth page');
            // Clear all caches
            if ('caches' in window) {
              caches.keys().then((cacheNames) => {
                cacheNames.forEach((cacheName) => {
                  caches.delete(cacheName);
                });
              });
            }
          }
        });
      });
    });
  }
  
  // Check if browser supports service workers
  if ('serviceWorker' in navigator && !swDisabled && !isAuthPage) {
    window.addEventListener('load', () => {
      // First check if server is available before registering SW
      fetch('/')
        .then(() => {
          // Server is available, register service worker
          return navigator.serviceWorker.register('/sw.js');
        })
        .then((registration) => {
          console.log('ServiceWorker registration successful:', registration.scope);

          // Check for updates every hour
          setInterval(() => {
            registration.update();
          }, 3600000);

          // Listen for updates
          registration.addEventListener('updatefound', () => {
            const newWorker = registration.installing;
            newWorker.addEventListener('statechange', () => {
              if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                // New service worker available
                showUpdateNotification();
              }
            });
          });
        })
        .catch((error) => {
          console.log('ServiceWorker registration failed or server unavailable:', error);
          // If server is not available, don't register SW to avoid blocking
          if (error.message && error.message.includes('Failed to fetch')) {
            console.log('Server appears to be down. Service worker not registered.');
          }
        });
    });
  }

  // Install prompt handling
  let deferredPrompt;
  const installButton = document.getElementById('install-pwa-btn');

  // Check if app is already installed (running in standalone mode)
  function isAppInstalled() {
    // Check if running in standalone mode
    if (window.matchMedia('(display-mode: standalone)').matches) {
      return true;
    }
    // Check if navigator.standalone is true (iOS Safari)
    if (window.navigator.standalone === true) {
      return true;
    }
    return false;
  }

  // Check if app is installable
  function checkInstallability() {
    // Don't show button if already installed
    if (isAppInstalled()) {
      console.log('PWA: App is already installed');
      if (installButton) {
        installButton.style.display = 'none';
      }
      return false;
    }

    // Check if we have a deferred prompt
    if (deferredPrompt) {
      console.log('PWA: App is installable, showing install button');
      if (installButton) {
        installButton.style.display = 'block';
        installButton.classList.remove('hidden');
      }
      return true;
    }

    // Check service worker registration
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.ready.then((registration) => {
        if (registration.active) {
          console.log('PWA: Service worker is active');
          // Check manifest
          fetch('/manifest.json')
            .then(response => response.json())
            .then(manifest => {
              console.log('PWA: Manifest loaded successfully', manifest);
              // If we have service worker and manifest, try to show button
              // (browser might not fire beforeinstallprompt immediately)
              setTimeout(() => {
                if (!deferredPrompt && !isAppInstalled() && installButton) {
                  console.log('PWA: Attempting to show install button (manual check)');
                  // Only show if we're sure it's installable
                  // The button will be shown when beforeinstallprompt fires
                }
              }, 2000);
            })
            .catch(error => {
              console.error('PWA: Error loading manifest:', error);
            });
        }
      });
    }

    return false;
  }

  // Set up install button click handler once
  if (installButton) {
    installButton.addEventListener('click', installApp);
  }

  window.addEventListener('beforeinstallprompt', (e) => {
    console.log('PWA: beforeinstallprompt event fired');
    // Prevent the mini-infobar from appearing
    e.preventDefault();
    // Stash the event so it can be triggered later
    deferredPrompt = e;
    // Show install button if it exists
    if (installButton && !isAppInstalled()) {
      installButton.style.display = 'block';
      installButton.classList.remove('hidden');
      console.log('PWA: Install button shown');
    }
  });

  function installApp() {
    if (!deferredPrompt) {
      console.log('PWA: No deferred prompt available');
      // Try alternative installation method
      if (confirm('Would you like to install this app? You can also install it from your browser menu.')) {
        // On some browsers, we can guide the user
        showNotification('To install: Click the menu (⋮) and select "Install app" or "Add to Home Screen"', 'info');
      }
      return;
    }

    // Show the install prompt
    deferredPrompt.prompt();

    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('PWA: User accepted the install prompt');
        showNotification('App installation started!', 'success');
      } else {
        console.log('PWA: User dismissed the install prompt');
      }
      deferredPrompt = null;
      if (installButton) {
        installButton.style.display = 'none';
      }
    });
  }

  // Check if app is already installed
  window.addEventListener('appinstalled', () => {
    console.log('PWA: App was installed');
    deferredPrompt = null;
    if (installButton) {
      installButton.style.display = 'none';
    }
    showNotification('App installed successfully!', 'success');
  });

  // Check installability on page load
  window.addEventListener('load', () => {
    setTimeout(() => {
      checkInstallability();
    }, 1000);
  });

  // Periodically check installability (in case beforeinstallprompt fires late)
  setInterval(() => {
    if (!isAppInstalled() && deferredPrompt && installButton) {
      if (installButton.style.display === 'none' || installButton.classList.contains('hidden')) {
        installButton.style.display = 'block';
        installButton.classList.remove('hidden');
      }
    }
  }, 3000);

  // Show update notification
  function showUpdateNotification() {
    // You can customize this notification
    if (confirm('A new version of the app is available. Would you like to update?')) {
      window.location.reload();
    }
  }

  // Network status detection
  window.addEventListener('online', () => {
    console.log('App is online');
    // You can show a toast notification here
    showNotification('You are back online', 'success');
  });

  window.addEventListener('offline', () => {
    console.log('App is offline');
    // You can show a toast notification here
    showNotification('You are offline. Some features may be limited.', 'warning');
  });

  // Request notification permission
  function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
      Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
          console.log('Notification permission granted');
        } else {
          console.log('Notification permission denied');
        }
      });
    }
  }

  
  // Simple notification function (you can replace with your own toast system)
  function showNotification(message, type) {
    // Create a simple notification element
    const notification = document.createElement('div');
    notification.className = `pwa-notification pwa-notification-${type}`;
    notification.textContent = message;
    
    let bgColor = '#f59e0b'; // default warning
    if (type === 'success') {
      bgColor = '#10b981';
    } else if (type === 'info') {
      bgColor = '#3b82f6';
    }
    
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: ${bgColor};
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 10000;
      animation: slideIn 0.3s ease-out;
      max-width: 400px;
      word-wrap: break-word;
    `;
    document.body.appendChild(notification);

    const duration = type === 'info' ? 5000 : 3000;
    setTimeout(() => {
      notification.style.animation = 'slideOut 0.3s ease-out';
      setTimeout(() => {
        if (document.body.contains(notification)) {
        document.body.removeChild(notification);
        }
      }, 300);
    }, duration);
  }

  // Add CSS for animations
  if (!document.getElementById('pwa-styles')) {
    const style = document.createElement('style');
    style.id = 'pwa-styles';
    style.textContent = `
      @keyframes slideIn {
        from {
          transform: translateX(100%);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
      @keyframes slideOut {
        from {
          transform: translateX(0);
          opacity: 1;
        }
        to {
          transform: translateX(100%);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  }

  // Expose PWA status checker to window for debugging
  window.checkPWAStatus = function() {
    console.log('=== PWA Status Check ===');
    console.log('Service Worker Support:', 'serviceWorker' in navigator);
    console.log('Is App Installed:', isAppInstalled());
    console.log('Has Deferred Prompt:', !!deferredPrompt);
    console.log('Install Button Found:', !!installButton);
    console.log('Install Button Visible:', installButton ? (installButton.style.display !== 'none' && !installButton.classList.contains('hidden')) : 'N/A');
    
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistration().then(registration => {
        console.log('Service Worker Registration:', registration ? 'Active' : 'Not registered');
        if (registration) {
          console.log('Service Worker Scope:', registration.scope);
          console.log('Service Worker State:', registration.active ? registration.active.state : 'No active worker');
        }
      });
    }
    
    fetch('/manifest.json')
      .then(response => {
        console.log('Manifest Status:', response.status, response.statusText);
        return response.json();
      })
      .then(manifest => {
        console.log('Manifest Content:', manifest);
      })
      .catch(error => {
        console.error('Manifest Error:', error);
      });
    
    console.log('=== End PWA Status Check ===');
  };

  console.log('PWA: Initialization complete. Use checkPWAStatus() in console to debug.');
})();

