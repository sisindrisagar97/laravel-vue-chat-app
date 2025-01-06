import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { quasar, transformAssetUrls } from '@quasar/vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls,
            },
        }),
        quasar({
            autoImportComponentCase: "combined",
            sassVariables: '/resources/js/quasar-variables.sass', 
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            'quasar': '/node_modules/quasar', 
        },
    },
    server: {
        host: 'localhost',
        port: 5173,
    },
});
