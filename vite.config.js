import { defineConfig } from "vite";
export default defineConfig({
  build: {
    outDir: "assets/dist",
    rollupOptions: {
      input: {
        frontend: "assets/css/frontend/main.scss",
        admin: "assets/css/admin/main.scss",
        app: "assets/js/frontend/app.js",
      },
    },
  },
});
