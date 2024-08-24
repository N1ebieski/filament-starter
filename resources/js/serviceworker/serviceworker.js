const staticCacheName = "pwa-v" + new Date().getTime();
const filesToCache = [
    // '/offline',
    // '/css/app.css',
    // '/js/app.js',
    "/",
    "/test",
    "/images/icons/icon-72x72.png",
    "/images/icons/icon-96x96.png",
    "/images/icons/icon-128x128.png",
    "/images/icons/icon-144x144.png",
    "/images/icons/icon-152x152.png",
    "/images/icons/icon-192x192.png",
    "/images/icons/icon-384x384.png",
    "/images/icons/icon-512x512.png",
];
const regexToCache = /\.(js|css|png|svg|woff2|json)$/;

// Cache on install
self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then((cache) => {
            return cache.addAll(filesToCache);
        })
    );
});

// Clear cache on activate
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName.startsWith("pwa-"))
                    .filter((cacheName) => cacheName !== staticCacheName)
                    .map((cacheName) => caches.delete(cacheName))
            );
        })
    );
});

self.addEventListener("fetch", async (event) => {
    const online = navigator.onLine;

    const response = caches.match(event.request).then((cacheResponse) => {
        if (cacheResponse) {
            return cacheResponse;
        }

        return fetch(event.request)
            .then((networkResponse) => {
                const match =
                    filesToCache.some((file) => {
                        const url = new URL(event.request.url);
                        const path = url.pathname;

                        if (typeof file === "string") {
                            return file === path;
                        }

                        if (file instanceof RegExp) {
                            return file.test(path);
                        }

                        return false;
                    }) || regexToCache.test(event.request.url);

                if (
                    match &&
                    networkResponse &&
                    networkResponse.status === 200
                ) {
                    const responseClone = networkResponse.clone();

                    caches.open(staticCacheName).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }

                return networkResponse;
            })
            .catch(() => {
                return new Response("Offline", {
                    status: 503,
                    statusText: "Offline",
                });
            });
    });

    if (!online) {
        event.respondWith(response);

        if (!event.clientId) return;

        const client = await self.clients.get(event.clientId);

        if (!client) return;

        setTimeout(() => {
            client.postMessage({
                type: "pwa:fetched",
                url: event.request.url,
            });
        }, 51);
    }
});
