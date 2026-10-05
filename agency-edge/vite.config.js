import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: { base: null, includeAbsolute: false },
            },
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                // Keep GSAP + Lenis in their own long-cached chunk.
                manualChunks(id) {
                    if (id.includes('node_modules/gsap') || id.includes('node_modules/lenis')) return 'motion';
                },
            },
        },
    },
});
