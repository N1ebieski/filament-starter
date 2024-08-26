const staticCacheName = "pwa-v" + new Date().getTime();

const staticFilesToCache = [
    "/",
    "/test",
    "/pwa-manifest.json",
    "/images/logo.svg",
    "/images/icons/icon-72x72.png",
    "/images/icons/icon-96x96.png",
    "/images/icons/icon-128x128.png",
    "/images/icons/icon-144x144.png",
    "/images/icons/icon-152x152.png",
    "/images/icons/icon-192x192.png",
    "/images/icons/icon-384x384.png",
    "/images/icons/icon-512x512.png",
    "/images/icons/splash-640x1136.png",
    "/images/icons/splash-750x1334.png",
    "/images/icons/splash-828x1792.png",
    "/images/icons/splash-1125x2436.png",
    "/images/icons/splash-1242x2208.png",
    "/images/icons/splash-1242x2688.png",
    "/images/icons/splash-1536x2048.png",
    "/images/icons/splash-1668x2224.png",
    "/images/icons/splash-1668x2388.png",
    "/images/icons/splash-2048x2732.png",
    "/images/screenshots/screenshot-720x540.png",
    "/images/screenshots/screenshot-540x720.png",
    "https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap",
    "https://fonts.bunny.net/inter/files/inter-latin-700-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-500-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-400-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-600-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-ext-600-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-ext-400-normal.woff2",
];

const regexToCache = /\.(js|css|png|svg|woff2|json)(?:\?v=.*)?$/;

async function downloadCache() {
    await fetch("/api/pwa/files").then(async (response) => {
        const json = await response.json();

        const files = json.data;

        const filesToCache = staticFilesToCache.concat(files);

        caches
            .open(staticCacheName)
            .then((cache) => cache.addAll(filesToCache));
    });
}

async function clearCache() {
    await caches.keys().then((cacheNames) => {
        return Promise.all(
            cacheNames
                .filter((cacheName) => cacheName.startsWith("pwa-"))
                .filter((cacheName) => cacheName !== staticCacheName)
                .map((cacheName) => caches.delete(cacheName))
        );
    });
}

self.addEventListener("install", (event) => {
    self.skipWaiting();

    event.waitUntil(downloadCache());
});

self.addEventListener("activate", (event) => {
    const online = navigator.onLine;

    if (!online) return;

    event.waitUntil(clearCache().then(() => downloadCache()));
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
                    staticFilesToCache.some((file) => {
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
