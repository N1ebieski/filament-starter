const version = "1.0.1";
const staticCacheName = "pwa-v" + new Date().getTime();

const staticFilesToCache = [
    "/",
    "/test",
    "/offline",
    "/pwa-manifest.json",
    "/images/logo.svg",
    "/favicon.ico",
    "/images/icons/pwa-64x64.png",
    "/images/icons/pwa-192x192.png",
    "/images/icons/pwa-512x512.png",
    "/images/icons/maskable-icon-512x512.png",
    "/images/splash/apple-splash-landscape-1136x640.png",
    "/images/splash/apple-splash-landscape-1334x750.png",
    "/images/splash/apple-splash-landscape-1792x828.png",
    "/images/splash/apple-splash-landscape-2048x1536.png",
    "/images/splash/apple-splash-landscape-2160x1620.png",
    "/images/splash/apple-splash-landscape-2208x1242.png",
    "/images/splash/apple-splash-landscape-2224x1668.png",
    "/images/splash/apple-splash-landscape-2388x1668.png",
    "/images/splash/apple-splash-landscape-2436x1125.png",
    "/images/splash/apple-splash-landscape-2532x1170.png",
    "/images/splash/apple-splash-landscape-2556x1179.png",
    "/images/splash/apple-splash-landscape-2688x1242.png",
    "/images/splash/apple-splash-landscape-2732x2048.png",
    "/images/splash/apple-splash-landscape-2778x1284.png",
    "/images/splash/apple-splash-landscape-2796x1290.png",
    "/images/splash/apple-splash-portrait-640x1136.png",
    "/images/splash/apple-splash-portrait-750x1334.png",
    "/images/splash/apple-splash-portrait-828x1792.png",
    "/images/splash/apple-splash-portrait-1125x2436.png",
    "/images/splash/apple-splash-portrait-1170x2532.png",
    "/images/splash/apple-splash-portrait-1179x2556.png",
    "/images/splash/apple-splash-portrait-1242x2208.png",
    "/images/splash/apple-splash-portrait-1242x2688.png",
    "/images/splash/apple-splash-portrait-1284x2778.png",
    "/images/splash/apple-splash-portrait-1290x2796.png",
    "/images/splash/apple-splash-portrait-1536x2048.png",
    "/images/splash/apple-splash-portrait-1620x2160.png",
    "/images/splash/apple-splash-portrait-1668x2224.png",
    "/images/splash/apple-splash-portrait-1668x2388.png",
    "/images/splash/apple-splash-portrait-2048x2732.png",
    "/images/screenshots/screenshot-720x540.png",
    "/images/screenshots/screenshot-540x720.png",
    "https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap",
    "https://fonts.bunny.net/inter/files/inter-latin-700-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-500-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-400-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-600-normal.woff2",
    "https://fonts.bunny.net/inter/files/inter-latin-ext-700-normal.woff2",
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
    const url = event.request.url;

    const response = caches.match(event.request).then((cacheResponse) => {
        if (cacheResponse) {
            return cacheResponse;
        }

        const match =
            staticFilesToCache.some((file) => {
                const realUrl = new URL(url);
                const path = realUrl.pathname;

                return file === path;
            }) || regexToCache.test(url);

        if (match) {
            if (event.clientId) {
                self.clients.get(event.clientId).then((client) => {
                    client.postMessage({
                        type: "pwa:fetching",
                        url: url,
                    });
                });
            }

            return fetch(event.request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();

                    caches.open(staticCacheName).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }

                return networkResponse;
            });
        }

        return (
            caches.match("offline") ||
            new Response("Offline", {
                status: 503,
                statusText: "Offline",
            })
        );
    });

    if (!online) {
        event.respondWith(response);

        if (event.clientId) {
            self.clients.get(event.clientId).then((client) => {
                setTimeout(() => {
                    client.postMessage({
                        type: "pwa:fetched",
                        url: url,
                    });
                }, 50);
            });
        }
    }
});
