import { defineConfig } from 'vite'

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    outDir: 'extension-dist',
    rollupOptions: {
      input: {
        popup: 'src/extension/popup.html',
        background: 'src/extension/background.js',
        content: 'src/extension/content.js',
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: 'assets/[name].js',
        assetFileNames: '[name].[ext]',
      },
    },
  },
}) 