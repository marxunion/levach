import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    host: true,
    port: 8000,
    proxy: {
      '/api': {
        target: process.env.VITE_API_URL,
        rewrite: (path) => path.replace(/^\/api/, ''),
      }
    },
    watch: {
      usePolling: true,
    },
  },
  build: {
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes('locales')) {
            return 'locales';
          }
        },
      },
    },
  },
  json: {
    namedExports: false,
    stringify: false,
  }
});