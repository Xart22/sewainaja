import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/dashboard.js",
                "resources/js/customer.js",
                "resources/js/users.js",
                "resources/js/tracking.js",
                "resources/js/hardware.js",
                "resources/js/data-permohonan.js",
                "resources/js/form-laporan.js",
            ],
            refresh: true,
        }),
    ],
});
