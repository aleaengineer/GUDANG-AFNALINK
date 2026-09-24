const CACHE_NAME = 'gudang-afnalink-v1';
const urlsToCache = [
  '/dashboard',
  '/barang',
  '/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(names => Promise.all(names.filter(n => n !== CACHE_NAME).map(n => caches.delete(n))))
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  // Network-first for API and dynamic pages, cache fallback for assets
  if (event.request.method !== 'GET') return;
  if (event.request.url.includes('/storage/') || event.request.url.includes('/build/')) {
    event.respondWith(
      caches.match(event.request).then(cached => cached || fetch(event.request).then(res => {
        const copy = res.clone();
        caches.open(CACHE_NAME).then(c => c.put(event.request, copy));
        return res;
      }))
    );
    return;
  }
  // For pages, try network first
  event.respondWith(
    fetch(event.request).catch(() => caches.match(event.request))
  );
});
