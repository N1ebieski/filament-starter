const staticCacheName = "pwa-v" + new Date().getTime();

const staticFilesToCache = [
    "/pwa-manifest.json",
    "/images/logo.svg",
    "/favicon.ico",
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
