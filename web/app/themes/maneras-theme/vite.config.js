// filepath: /home/javi/sites/sxxi.mdv.red/bedrock/web/app/themes/maneras-theme/vite.config.js
import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  // Ruta base para el servidor de desarrollo
  base: '',
  
  // Ajuste crítico para resolver correctamente rutas @fs
  server: {
    cors: true,
    strictPort: true,
    port: 5173,
    hmr: {
      host: 'localhost'
    },
  },
  fs: {
    allow: ['.']
  }, 
  build: {
    // Directorio de salida para los archivos compilados
    outDir: 'public',
    
    // Genera un manifest.json para mapeo de archivos
    manifest: true,
    
    rollupOptions: {
      input: resolve(__dirname, 'resources/js/app.js')
    }
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources')
    }
  }
});