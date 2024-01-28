window.Livewire.on("update-meta", ({ meta }) => {
    document.title = meta.title;
});
