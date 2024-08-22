import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
    server: {
        host: "0.0.0.0",
        port: 49281,
        hmr: {
            host: "localhost",
            protocol: "ws",
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/web/web.scss",
                "resources/css/user/user.scss",
                "resources/css/admin/admin.scss",
                "resources/js/admin/admin.js",
            ],
            refresh: true,
        }),
    ],
});
