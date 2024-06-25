import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        watch: {
            usePolling: true, // Utiliza polling en lugar de eventos del sistema de archivos
            interval: 1000, // Intervalo de polling en milisegundos
            ignored: ["**/node_modules/**", "**/.git/**"],
        },
    },
});
