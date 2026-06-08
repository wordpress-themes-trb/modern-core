import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import externalGlobals from "rollup-plugin-external-globals";
import path from "path";

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
      // 1. Mark WordPress packages as external so Vite doesn't bundle them
      external: [
        "@wordpress/blocks",
        "@wordpress/block-editor",
        "@wordpress/components",
        "@wordpress/i18n",
        "@wordpress/element",
        "@wordpress/data",
        "@wordpress/api-fetch",
        "@wordpress/plugins",
        "@wordpress/edit-post",
        "@wordpress/hooks",
        "@wordpress/compose",
      ],
      input: {
        main: path.resolve(__dirname, "assets/src/js/main.js"),
        editorJs: path.resolve(__dirname, "assets/src/js/editor.jsx"), // Updated to .jsx
        style: path.resolve(__dirname, "assets/src/scss/main.scss"),
        editor: path.resolve(__dirname, "assets/src/scss/editor.scss"),
        "advanced-header/index": path.resolve(
          __dirname,
          "blocks/advanced-header/index.js",
        ),
        "marquee/index": path.resolve(__dirname, "blocks/marquee/index.js"),
        "logo/index": path.resolve(__dirname, "blocks/logo/index.js"),
        "header-icons/index": path.resolve(
          __dirname,
          "blocks/header-icons/index.js",
        ),
        "navigation/index": path.resolve(
          __dirname,
          "blocks/navigation/index.js",
        ),
        "hamburger/index": path.resolve(__dirname, "blocks/hamburger/index.js"),
        "search/index": path.resolve(__dirname, "blocks/search/index.js"),
        "advanced-footer/index": path.resolve(
          __dirname,
          "blocks/advanced-footer/index.js",
        ),
        "newsletter/index": path.resolve(
          __dirname,
          "blocks/newsletter/index.js",
        ),
        "payment-icons/index": path.resolve(
          __dirname,
          "blocks/payment-icons/index.js",
        ),
        "off-canvas/index": path.resolve(
          __dirname,
          "blocks/off-canvas/index.js",
        ),
        "cart-items/index": path.resolve(
          __dirname,
          "blocks/cart-items/index.js",
        ),
        "product-filters/index": path.resolve(
          __dirname,
          "blocks/product-filters/index.js",
        ),
        "mobile-sticky-bar/index": path.resolve(
          __dirname,
          "blocks/mobile-sticky-bar/index.js",
        ),
        "hero/index": path.resolve(__dirname, "blocks/hero/index.js"),
        "hero-layout/index": path.resolve(
          __dirname,
          "blocks/hero-layout/index.js",
        ),
        "trust-strip/index": path.resolve(
          __dirname,
          "blocks/trust-strip/index.js",
        ),
        "countdown/index": path.resolve(__dirname, "blocks/countdown/index.js"),
        "slider/index": path.resolve(__dirname, "blocks/slider/index.js"),
        "slide/index": path.resolve(__dirname, "blocks/slide/index.js"),
        "promo-banner/index": path.resolve(
          __dirname,
          "blocks/promo-banner/index.js",
        ),
        "split-banner/index": path.resolve(
          __dirname,
          "blocks/split-banner/index.js",
        ),
        "video-hero/index": path.resolve(
          __dirname,
          "blocks/video-hero/index.js",
        ),
        "announcement-bar/index": path.resolve(
          __dirname,
          "blocks/announcement-bar/index.js",
        ),
        "countdown-banner/index": path.resolve(
          __dirname,
          "blocks/countdown-banner/index.js",
        ),
        "product-grid/index": path.resolve(
          __dirname,
          "blocks/product-grid/index.js",
        ),
        "product-carousel/index": path.resolve(
          __dirname,
          "blocks/product-carousel/index.js",
        ),
        "product-card/index": path.resolve(
          __dirname,
          "blocks/product-card/index.js",
        ),
        "featured-product/index": path.resolve(
          __dirname,
          "blocks/featured-product/index.js",
        ),
        "product-tabs/index": path.resolve(
          __dirname,
          "blocks/product-tabs/index.js",
        ),
        "lookbook/index": path.resolve(__dirname, "blocks/lookbook/index.js"),
        "quick-view/index": path.resolve(
          __dirname,
          "blocks/quick-view/index.js",
        ),
        "recently-viewed/index": path.resolve(
          __dirname,
          "blocks/recently-viewed/index.js",
        ),
        "compare-table/index": path.resolve(
          __dirname,
          "blocks/compare-table/index.js",
        ),
        "wishlist/index": path.resolve(__dirname, "blocks/wishlist/index.js"),
        "bundle-upsell/index": path.resolve(
          __dirname,
          "blocks/bundle-upsell/index.js",
        ),
        "stock-countdown/index": path.resolve(
          __dirname,
          "blocks/stock-countdown/index.js",
        ),
        "category-grid/index": path.resolve(
          __dirname,
          "blocks/category-grid/index.js",
        ),
        "category-carousel/index": path.resolve(
          __dirname,
          "blocks/category-carousel/index.js",
        ),
        "mega-menu/index": path.resolve(__dirname, "blocks/mega-menu/index.js"),
        "filter-sidebar/index": path.resolve(
          __dirname,
          "blocks/filter-sidebar/index.js",
        ),
        "shop-toolbar/index": path.resolve(
          __dirname,
          "blocks/shop-toolbar/index.js",
        ),
        "pagination/index": path.resolve(
          __dirname,
          "blocks/pagination/index.js",
        ),
        "testimonials/index": path.resolve(
          __dirname,
          "blocks/testimonials/index.js",
        ),
        "reviews-summary/index": path.resolve(
          __dirname,
          "blocks/reviews-summary/index.js",
        ),
        "icon-feature-list/index": path.resolve(
          __dirname,
          "blocks/icon-feature-list/index.js",
        ),
        "stats-counter/index": path.resolve(
          __dirname,
          "blocks/stats-counter/index.js",
        ),
        "posts-grid/index": path.resolve(
          __dirname,
          "blocks/posts-grid/index.js",
        ),
        "faq-accordion/index": path.resolve(
          __dirname,
          "blocks/faq-accordion/index.js",
        ),
        "content-tabs/index": path.resolve(
          __dirname,
          "blocks/content-tabs/index.js",
        ),
        "pricing-table/index": path.resolve(
          __dirname,
          "blocks/pricing-table/index.js",
        ),
        "trust-badges/index": path.resolve(
          __dirname,
          "blocks/trust-badges/index.js",
        ),
        "shipping-partners/index": path.resolve(
          __dirname,
          "blocks/shipping-partners/index.js",
        ),
        "live-sales-popup/index": path.resolve(
          __dirname,
          "blocks/live-sales-popup/index.js",
        ),
        "shipping-progress/index": path.resolve(
          __dirname,
          "blocks/shipping-progress/index.js",
        ),
        "exit-popup/index": path.resolve(
          __dirname,
          "blocks/exit-popup/index.js",
        ),
        "cookie-consent/index": path.resolve(
          __dirname,
          "blocks/cookie-consent/index.js",
        ),
        "back-in-stock/index": path.resolve(
          __dirname,
          "blocks/back-in-stock/index.js",
        ),
        "single-product/index": path.resolve(
          __dirname,
          "blocks/single-product/index.js",
        ),
        "mini-cart/index": path.resolve(__dirname, "blocks/mini-cart/index.js"),
        "cart-page/index": path.resolve(__dirname, "blocks/cart-page/index.js"),
        "checkout-page/index": path.resolve(
          __dirname,
          "blocks/checkout-page/index.js",
        ),
        "order-confirmation/index": path.resolve(
          __dirname,
          "blocks/order-confirmation/index.js",
        ),
        "my-account/index": path.resolve(
          __dirname,
          "blocks/my-account/index.js",
        ),
        "product-gallery/index": path.resolve(
          __dirname,
          "blocks/product-gallery/index.js",
        ),
        "variation-picker/index": path.resolve(
          __dirname,
          "blocks/variation-picker/index.js",
        ),
        "section-wrapper/index": path.resolve(
          __dirname,
          "blocks/section-wrapper/index.js",
        ),
        "columns-builder/index": path.resolve(
          __dirname,
          "blocks/columns-builder/index.js",
        ),
        "spacer-divider/index": path.resolve(
          __dirname,
          "blocks/spacer-divider/index.js",
        ),
        "icon/index": path.resolve(__dirname, "blocks/icon/index.js"),
      },
      output: {
        banner: "(() => {",
        footer: "})();",
        entryFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.includes("/index")) {
            return `blocks/[name].js`;
          }
          // Outputs clean filenames like 'js/main.js' or 'js/editorJs.js'
          return "js/[name].js";
        },
        // Outputs clean filenames like 'css/main.css'
        assetFileNames: "css/[name].[ext]",
      },
      plugins: [
        // 2. Map the external packages to the global `window.wp` object
        externalGlobals({
          "@wordpress/blocks": "window.wp.blocks",
          "@wordpress/block-editor": "window.wp.blockEditor",
          "@wordpress/components": "window.wp.components",
          "@wordpress/i18n": "window.wp.i18n",
          "@wordpress/element": "window.wp.element",
          "@wordpress/data": "window.wp.data",
          "@wordpress/api-fetch": "window.wp.apiFetch",
          "@wordpress/plugins": "window.wp.plugins",
          "@wordpress/edit-post": "window.wp.editPost",
          "@wordpress/hooks": "window.wp.hooks",
          "@wordpress/compose": "window.wp.compose",
        }),
      ],
    },
  },
});
