import { defineConfig } from "vite";
import laravel, {refreshPaths} from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/app.js",
                "resources/css/tailwindcss.css",
                "resources/css/filament/admin/admin.css",
                "resources/css/filament/midas/midas.css",
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
});
