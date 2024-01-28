import path from "path";
import { defineConfig } from "vite";

export default defineConfig({
    build: {
        manifest: false,
        outDir: "public",
        emptyOutDir: false,
        rollupOptions: {
            input: {
                "pxlrbt-filament-spotlight": path.resolve(
                    __dirname,
                    "resources/js/spotlight/spotlight.js"
                ),
            },
            output: {
                entryFileNames: "js/pxlrbt/filament-spotlight/spotlight-js.js",
            },
        },
    },
});
