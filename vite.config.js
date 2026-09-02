import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  plugins: [
    laravel({
      input: [
        "assets/css/index.css",
        "assets/css/lightbox.css",
        "assets/css/prism.css",
        "assets/css/templates/about.css",
        "assets/css/templates/archive.css",
        "assets/css/templates/album.css",
        "assets/css/templates/home.css",
        "assets/css/templates/pre-flight.css",
        "assets/css/templates/note.css",
        "assets/js/index.js",
        "assets/js/lightbox.js",
        "assets/js/prism.js",
      ],
      refresh: ["site/templates/**", "site/snippets/**"],
      publicDirectory: "./",
    }),
  ],
});
