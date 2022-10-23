/////////////////////////////////////////////////////////////////////////////
// You can find dozens of practical, detailed, and working examples of 
// service worker usage on https://github.com/mozilla/serviceworker-cookbook
/////////////////////////////////////////////////////////////////////////////

// Cache name
var CACHE_NAME = 'cache-version-a2';

// Files required to make this app work offline
var REQUIRED_FILES = [
  'icon/damkar.ico',
  'login/logo_dki.png',
  'img/logo-eapd.png',
  'login/logo_damkar_dki.png',
  '/',
  'https://fonts.googleapis.com/css?family=Inter:400,500,700&display=swap',
  'https://unpkg.com/ionicons@5.0.0/dist/ionicons.js',
  'vendor/mobilekit/js/lib/jquery-3.4.1.min.js',
  'vendor/mobilekit/js/lib/popper.min.js',
  'vendor/mobilekit/js/lib/bootstrap.min.js',
  'vendor/mobilekit/js/plugins/owl-carousel/owl.carousel.min.js',
  'vendor/mobilekit/js/base.js',
  'vendor/mobilekit/css/inc/owl-carousel/owl.carousel.min.css',
  'vendor/mobilekit/css/inc/owl-carousel/owl.theme.default.css',
  'vendor/mobilekit/css/inc/bootstrap/bootstrap.min.css',
  'vendor/mobilekit/css/style.css'
];

self.addEventListener('install', function(event) {
  // Perform install step:  loading each required file into cache
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        // Add all offline dependencies to the cache
        return cache.addAll(REQUIRED_FILES);
      })
      .then(function() {
        return self.skipWaiting();
      })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Cache hit - return the response from the cached version
        if (response) {
          return response;
        }
        // Not in cache - return the result from the live server
        // `fetch` is essentially a "fallback"
        return fetch(event.request);
      }
    )
  );
});

self.addEventListener('activate', function(event) {
  // Calling claim() to force a "controllerchange" event on navigator.serviceWorker
  event.waitUntil(self.clients.claim());
});