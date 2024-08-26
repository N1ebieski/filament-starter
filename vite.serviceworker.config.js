import path from "path";
import { defineConfig } from "vite";

export default defineConfig({
    build: {
        manifest: false,
        outDir: "public",
        emptyOutDir: false,
        rollupOptions: {
            input: {
                laravelpwa: path.resolve(
                    __dirname,
                    "resources/js/serviceworker/serviceworker.js"
                ),
            },
            output: {
                entryFileNames: "serviceworker.js",
            },
        },
    },
});
