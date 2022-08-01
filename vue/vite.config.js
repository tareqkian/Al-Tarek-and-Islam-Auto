import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import i18nResources from "vite-plugin-i18n-resources";
import { resolve } from "path";

export default defineConfig({
  plugins: [
    vue(),
    i18nResources({
      path: resolve(__dirname, "src/locales"),
    }),
  ],
})
