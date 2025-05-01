import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler'
            }
        }
    },
    server: {
        host: "0.0.0.0",
        port: 49281,
        hmr: {
            host: "localhost",
            protocol: "ws",
        },
        watch: {
            interval: 1000,
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/web/web.scss",
                "resources/css/user/user.scss",
                "resources/css/admin/admin.scss",
                "resources/js/web/web.js",
                "resources/js/admin/admin.js",
            ],
            refresh: true,
        }),
    ],
});
