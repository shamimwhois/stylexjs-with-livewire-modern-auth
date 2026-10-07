import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import stylex from '@stylexjs/unplugin';

export default defineConfig({
    plugins: [
        stylex.vite({ useCSSLayers: true, devMode: 'full', dev: false, runtimeInjection: false }),
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Teko', {
                    weights: [400, 500, 600, 700],
                }),
                bunny('Plus Jakarta Sans', {
                    weights: [400, 500, 600, 700, 800],
                }),
                bunny('IBM Plex Mono', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
    ],
    server: {
        host: '127.0.0.1',
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});