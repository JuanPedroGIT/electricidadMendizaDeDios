import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  // AÑADE ESTA SECCIÓN:
  server: {
    host: '0.0.0.0',      // Permite conexiones externas al contenedor
    port: 5173,           // Aseguramos el puerto que ya usa Nginx
    allowedHosts: true,   // <--- ESTO ELIMINA EL ERROR 403
    strictPort: true,
  }
})