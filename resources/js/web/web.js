if ("serviceWorker" in navigator) {
    navigator.serviceWorker.addEventListener("message", (event) => {
        if (event.data.type === "pwa:fetched") {
            window.dispatchEvent(new CustomEvent("pwa:fetched"));
        }
    });
}
