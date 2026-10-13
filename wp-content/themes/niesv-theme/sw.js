/**
 * NIESV PWA Service Worker
 * Handles caching, offline functionality and notifications
 */

// Get the WordPress site URL from the current location
const SITE_URL = self.location.origin;

// Create environment-specific cache name based on hostname
const getEnvironmentCacheName = () => {
    const hostname = self.location.hostname;
    let environment = 'production';

    // Detect local development environments
    if (hostname === 'localhost' ||
        hostname === '127.0.0.1' ||
        hostname.includes('.local') ||
        hostname.includes('.test') ||
        hostname.includes('.dev') ||
        hostname.includes('staging') ||
        hostname.includes('dev.')) {
        environment = 'development';
    }

    // NIESV_CACHE_VERSION is prepended by WordPress when it serves /sw.js
    const version = typeof NIESV_CACHE_VERSION !== 'undefined' ? NIESV_CACHE_VERSION : 'v1';
    return `niesv-pwa-${environment}-${version}`;
};

const CACHE_NAME = getEnvironmentCacheName();
const OFFLINE_URL = '/offline/';

// Assets to cache immediately
const STATIC_CACHE_URLS = [
    '/',
    '/about/',
    '/membership/',
    '/contact/',
    OFFLINE_URL,
    '/wp-content/themes/niesv-theme/style.css',
    '/wp-content/themes/niesv-theme/assets/js/theme.js',
    '/wp-content/themes/niesv-theme/assets/js/pwa.js',
    '/manifest.json'
];

// Runtime cache patterns
const RUNTIME_CACHE_PATTERNS = [
    // WordPress content
    /\/wp-content\/themes\/niesv-theme\/.+\.(css|js|png|jpg|jpeg|gif|svg|webp)$/,
    // WordPress uploads
    /\/wp-content\/uploads\/.+\.(png|jpg|jpeg|gif|svg|webp)$/,
    // External fonts and CDN resources
    /^https:\/\/fonts\.googleapis\.com\/.*/,
    /^https:\/\/fonts\.gstatic\.com\/.*/
];

// Install event - cache static assets
self.addEventListener('install', event => {
    console.log(`NIESV SW: Installing service worker for ${self.location.hostname}`);
    console.log(`NIESV SW: Using cache name: ${CACHE_NAME}`);

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('NIESV SW: Caching static assets');
                return cache.addAll(STATIC_CACHE_URLS);
            })
            .then(() => {
                console.log('NIESV SW: Static assets cached successfully');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('NIESV SW: Failed to cache static assets:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log(`NIESV SW: Activating service worker for ${self.location.hostname}`);
    console.log(`NIESV SW: Current cache name: ${CACHE_NAME}`);

    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                console.log('NIESV SW: Found existing caches:', cacheNames);
                return Promise.all(
                    cacheNames.map(cacheName => {
                        // Only delete caches that don't match current environment
                        if (cacheName !== CACHE_NAME && cacheName.startsWith('niesv-pwa-')) {
                            console.log('NIESV SW: Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('NIESV SW: Service worker activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - handle requests with caching strategies
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip WordPress admin and login pages
    if (url.pathname.includes('/wp-admin/') ||
        url.pathname.includes('/wp-login.php') ||
        url.pathname.includes('/wp-json/')) {
        return;
    }

    event.respondWith(handleFetch(request, event));
});

/**
 * Handle fetch requests with appropriate caching strategy
 */
async function handleFetch(request, event) {
    const url = new URL(request.url);

    // Check user's caching preference from localStorage
    const cachingMode = await getCachingPreference();

    try {
        // Apply caching strategy based on user preference
        if (cachingMode === 'minimal') {
            return await networkFirstMinimal(request);
        } else if (cachingMode === 'full') {
            // Full PWA caching strategies
            // Strategy 1: Cache First for static assets
            if (isStaticAsset(request)) {
                return await cacheFirst(request);
            }

            // Strategy 2: Network First for HTML pages
            if (isHTMLPage(request)) {
                return await networkFirst(request);
            }

            // Strategy 3: Stale While Revalidate for API calls
            if (isAPICall(request)) {
                return await staleWhileRevalidate(request);
            }

            // Default: Network First
            return await networkFirst(request);
        } else {
            // Auto mode: use minimal caching by default for better browser experience
            return await networkFirstMinimal(request);
        }

    } catch (error) {
        console.error('NIESV SW: Fetch failed:', error);

        // Return offline page for navigation requests (full caching mode only)
        if (cachingMode === 'full' && request.mode === 'navigate') {
            return caches.match(OFFLINE_URL);
        }

        // Return cached version if available
        return caches.match(request);
    }
}

// Pages with a member's personal data. Never stored, so nothing is readable
// offline on a shared device after logging out.
const PRIVATE_PATHS = ['/member-dashboard/', '/property-dashboard/', '/add-property/', '/edit-property/', '/login/', '/register/', '/pwa-test/'];

/**
 * Whether a network response may be stored in the cache
 */
function shouldCache(request, response) {
    if (!response.ok) {
        return false;
    }
    const path = new URL(request.url).pathname;
    if (PRIVATE_PATHS.some(privatePath => path.startsWith(privatePath))) {
        return false;
    }
    const cacheControl = response.headers.get('Cache-Control') || '';
    return !/no-store|private/i.test(cacheControl);
}

/**
 * Cache First strategy - good for static assets
 */
async function cacheFirst(request) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
        return cachedResponse;
    }
    
    const networkResponse = await fetch(request);
    if (shouldCache(request, networkResponse)) {
        const cache = await caches.open(CACHE_NAME);
        cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
}

/**
 * Network First strategy - good for HTML pages
 */
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        if (shouldCache(request, networkResponse)) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        throw error;
    }
}

/**
 * Stale While Revalidate strategy - good for API calls
 */
async function staleWhileRevalidate(request) {
    const cachedResponse = await caches.match(request);
    
    const fetchPromise = fetch(request).then(networkResponse => {
        if (shouldCache(request, networkResponse)) {
            const cache = caches.open(CACHE_NAME);
            cache.then(c => c.put(request, networkResponse.clone()));
        }
        return networkResponse;
    });
    
    return cachedResponse || fetchPromise;
}

/**
 * User's caching preference ('minimal' or 'full').
 *
 * Pages send it on load and when it changes (CACHING_MODE_CHANGED). It is kept in
 * memory and persisted in Cache Storage, because the browser stops idle workers.
 * Reading it never waits on a page, so it adds no delay to requests.
 */
const PREFERENCE_CACHE = 'niesv-settings'; // outside the 'niesv-pwa-' prefix that activate() cleans up
const PREFERENCE_KEY = '/__niesv-caching-mode';
let cachingModeMemo = null;

async function getCachingPreference() {
    if (cachingModeMemo) {
        return cachingModeMemo;
    }
    try {
        const stored = await caches.match(PREFERENCE_KEY, { cacheName: PREFERENCE_CACHE });
        cachingModeMemo = stored ? await stored.text() : 'minimal';
    } catch (error) {
        cachingModeMemo = 'minimal';
    }
    return cachingModeMemo;
}

async function setCachingPreference(mode) {
    if (mode !== 'minimal' && mode !== 'full') {
        return;
    }
    cachingModeMemo = mode;
    const cache = await caches.open(PREFERENCE_CACHE);
    await cache.put(PREFERENCE_KEY, new Response(mode));
}

/**
 * Network First strategy with minimal caching for regular browsers
 */
async function networkFirstMinimal(request) {
    try {
        // Always try network first
        const networkResponse = await fetch(request);

        // Only cache critical resources for regular browsers
        if (isCriticalResource(request) && shouldCache(request, networkResponse)) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        // Only return cached version for critical resources
        if (isCriticalResource(request)) {
            const cachedResponse = await caches.match(request);
            if (cachedResponse) {
                return cachedResponse;
            }
        }

        // For non-critical resources, let the request fail naturally
        throw error;
    }
}

/**
 * Check if a resource is critical (should be cached even for regular browsers)
 */
function isCriticalResource(request) {
    const url = request.url;

    // Only cache essential CSS and JS files for regular browsers
    return url.includes('/wp-content/themes/niesv-theme/style.css') ||
           url.includes('/wp-content/themes/niesv-theme/assets/js/theme.js') ||
           url.includes('/manifest.json');
}

/**
 * Check if request is for a static asset
 */
function isStaticAsset(request) {
    return RUNTIME_CACHE_PATTERNS.some(pattern => pattern.test(request.url));
}

/**
 * Check if request is for an HTML page
 */
function isHTMLPage(request) {
    return request.mode === 'navigate' || 
           request.headers.get('accept')?.includes('text/html');
}

/**
 * Check if request is an API call
 */
function isAPICall(request) {
    return request.url.includes('/wp-json/') || 
           request.url.includes('/api/');
}

self.addEventListener('push', event => {
    console.log('NIESV SW: Push notification received');
    
    const options = {
        body: 'You have new updates from NIESV',
        icon: '/wp-content/themes/niesv-theme/assets/icons/icon-192x192.png',
        badge: '/wp-content/themes/niesv-theme/assets/icons/badge-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'View Details',
                icon: '/wp-content/themes/niesv-theme/assets/icons/view-icon.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/wp-content/themes/niesv-theme/assets/icons/close-icon.png'
            }
        ]
    };
    
    if (event.data) {
        const data = event.data.json();
        options.body = data.body || options.body;
        options.title = data.title || 'NIESV Update';
    }
    
    event.waitUntil(
        self.registration.showNotification('NIESV', options)
    );
});

// Notification click handling
self.addEventListener('notificationclick', event => {
    console.log('NIESV SW: Notification clicked');
    
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    } else if (event.action === 'close') {
        // Just close the notification
        return;
    } else {
        // Default action - open the app
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Helper functions for IndexedDB operations (simplified)
self.addEventListener('message', event => {
    console.log('NIESV SW: Message received:', event.data);
    
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'CACHING_MODE_CHANGED') {
        event.waitUntil(setCachingPreference(event.data.mode));
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
});

console.log(`NIESV SW: Service worker script loaded for ${self.location.hostname}`);
console.log(`NIESV SW: Environment cache: ${CACHE_NAME}`);
