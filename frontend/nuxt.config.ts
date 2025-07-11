import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: true },
  
  // Dev server configuration for Docker
  devServer: {
    host: '0.0.0.0',
    port: 3000
  },
  
  // Runtime config for Docker environment variables
  runtimeConfig: {
    public: {
      // Use localhost for browser requests (client-side)
      apiUrl: process.env.NUXT_PUBLIC_API_URL || 'http://localhost:8000',
      socketUrl: process.env.NUXT_PUBLIC_SOCKET_URL || 'http://localhost:8080'
    }
  },
  
  css: [
    'vuetify/styles',
    '@mdi/font/css/materialdesignicons.css'
  ],
  
  build: {
    transpile: ['vuetify']
  },
  
  vite: {
    define: {
      'process.env.DEBUG': false,
    },
  },
  
  plugins: [
    { src: '~/plugins/apexcharts.client', mode: 'client' }
  ]
})