import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/cover-page.css',
                'resources/css/filament/app/theme.css'
            ],
            refresh: true,
        }),
    ],
});
