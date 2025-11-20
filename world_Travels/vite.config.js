import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        host: '127.0.0.1',
        port: 5173,
        cors: {
            origin: ['http://127.0.0.1:8000', 'http://localhost:8000', 'http://127.0.0.1:5174', 'http://localhost:5174'],
            credentials: true,
        },
        hmr: false,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    'chart-js': ['chart.js'],
                    'axios': ['axios']
                }
            }
        }
    },
    optimizeDeps: {
        include: ['chart.js', 'axios']
    },
    esbuild: {
        target: 'es2020'
    }
});
