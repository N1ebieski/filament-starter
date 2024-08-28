if ("serviceWorker" in navigator) {
    navigator.serviceWorker.addEventListener("message", (event) => {
        window.dispatchEvent(new CustomEvent(event.data.type));
    });
}
