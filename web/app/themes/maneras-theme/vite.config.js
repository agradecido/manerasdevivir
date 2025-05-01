import { defineConfig } from 'vite';
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'
import { resolve } from 'path';
import svgo from 'rollup-plugin-svgo'

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
  },
  plugins: [
    svgo({
      multipass: true,
      plugins: [
        { removeViewBox: false },
      ],
    }),
  ],
  css: {
    postcss: {
      plugins: [
        tailwindcss(),
        autoprefixer(),
      ],
    },
  },
});