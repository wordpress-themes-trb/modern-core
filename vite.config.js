import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import externalGlobals from "rollup-plugin-external-globals";
import fs from "fs";
import path from "path";

const blocksDir = path.resolve(__dirname, "blocks");

// Child blocks are bundled with their parent (content-tabs imports tab-pane,
// columns-builder imports column), so they don't get their own entry.
const bundledWithParent = ["tab-pane", "column"];

// Every folder in blocks/ with an index.js becomes assets/dist/blocks/<name>/index.js.
const blockEntries = Object.fromEntries(
  fs
    .readdirSync(blocksDir)
    .filter(
      (name) =>
        !bundledWithParent.includes(name) &&
        fs.existsSync(path.join(blocksDir, name, "index.js"))
    )
    .map((name) => [`${name}/index`, path.join(blocksDir, name, "index.js")])
);

export default defineConfig({
  plugins: [
    react({
      jsxRuntime: "classic",
      jsxFactory: "window.wp.element.createElement",
      jsxFragment: "window.wp.element.Fragment",
    }),
  ],
  build: {
    outDir: path.resolve(__dirname, "assets/dist"),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, "assets/src/js/main.js"),
        // Editor-only: the "Entrance Animation" panel added to every block.
        editorJs: path.resolve(__dirname, "assets/src/js/editor.jsx"),
        style: path.resolve(__dirname, "assets/src/scss/main.scss"),
        editor: path.resolve(__dirname, "assets/src/scss/editor.scss"),
        ...blockEntries,
      },
      output: {
        // Each bundle runs in its own scope so block globals never collide.
        banner: "(() => {",
        footer: "})();",
        // Fixed file names: PHP enqueues these paths and versions them by file time.
        entryFileNames: (chunk) =>
          chunk.name.includes("/index") ? "blocks/[name].js" : "js/[name].js",
        chunkFileNames: "js/[name].js",
        assetFileNames: "css/[name].[ext]",
      },
      plugins: [
        externalGlobals({
          "@wordpress/blocks": "window.wp.blocks",
          "@wordpress/block-editor": "window.wp.blockEditor",
          "@wordpress/components": "window.wp.components",
          "@wordpress/i18n": "window.wp.i18n",
          "@wordpress/element": "window.wp.element",
          "@wordpress/data": "window.wp.data",
          "@wordpress/api-fetch": "window.wp.apiFetch",
          "@wordpress/hooks": "window.wp.hooks",
          "@wordpress/compose": "window.wp.compose",
          "@wordpress/html-entities": "window.wp.htmlEntities",
        }),
      ],
    },
  },
});
