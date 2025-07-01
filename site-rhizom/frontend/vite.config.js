import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',      // ← écoute sur toutes les interfaces
    port: 5173,           // optionnel si tu veux forcer le port
    proxy: {
      '/api': {
        target: 'http://backend:3001',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '')
      }
    }
  }
})