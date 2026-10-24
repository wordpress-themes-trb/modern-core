# Modern Core

A WooCommerce block theme (Full Site Editing) with 72 custom store blocks: header and footer, hero and banners, product grids and carousels, filters, quick view, wishlist, compare, mini cart, cart, checkout, order confirmation and My Account.

Requires WordPress 6.6+, PHP 8.2+ and WooCommerce. Tested with WordPress 7.1.3 and WooCommerce 11.2.1.

## Install on a site

1. Download `modern-core.zip` (or run `npm run package`, see below).
2. In WordPress go to **Appearance → Themes → Add New → Upload Theme**, upload the zip and activate it.
3. On activation the theme sets a static homepage, creates any missing WooCommerce pages (Shop, Cart, Checkout, My Account) plus **Journal**, **Wishlist** and **Compare** pages. It adds two sample products only if the store has none.
4. Edit the header, footer and templates in **Appearance → Editor**.
5. Optional: **Appearance → Theme Settings** for Mailchimp/Klaviyo newsletter delivery and the back-in-stock email text.

## What the theme does

| Area | Where |
| --- | --- |
| Templates: home, shop, category, product search, product, cart, checkout, order received, My Account, blog, single post, page, full-width page, search, 404 | `templates/` |
| Header and footer | `parts/` |
| Blocks (editor UI in `edit.jsx`, front end in `render.php`) | `blocks/<name>/` |
| PHP services (setup, blocks, WooCommerce, AJAX, newsletter, back-in-stock, settings) | `inc/` |
| Global styles and scripts | `assets/src/` → built to `assets/dist/` |

Shop features that work out of the box:

- **Filters** (price, colour, size, category, in stock, on sale, rating) use WooCommerce's own URL parameters, so sorting, pagination and filters all combine, and they work without JavaScript.
- **Wishlist and compare** are stored in the visitor's browser, so they work for guests. Wishlists can be shared by link.
- **Newsletter** signups are saved under **Tools → Subscribers** (CSV export under **Tools → Export subscribers**) and can also be sent to Mailchimp or Klaviyo.
- **Back in stock** waitlists email everyone automatically when a product or variation is restocked.
- **Live search** suggests products from the WooCommerce Store API as you type.

## Elementor

The theme works with Elementor (and Elementor Pro) without extra plugins:

- **Theme blocks as widgets.** Every theme block (product grids and carousels, hero, FAQ, pricing, testimonials, trust badges, cart, checkout, wishlist, compare and more) appears in Elementor's panel under **Modern Core** and **Modern Core Shop**. Each widget has the same settings as the block's sidebar and renders through the block's own `render.php`, so a widget and its block always look the same.
- **Pages built with Elementor** automatically use the full-width template inside the store header and footer. Pick another template in the page settings to override this. Elementor's own **Canvas** and **Full Width** templates work too (`header.php` and `footer.php` print the theme header and footer).
- **Elementor Pro Theme Builder** headers and footers replace the theme's when you publish them.
- **Global colours and fonts.** On first use, the theme's palette, fonts and 1200px content width are copied into Elementor's site settings, unless you have already set them. The theme's fonts appear under **Theme fonts** in Elementor's font list and are served locally, not from Google.

Widget settings come from `inc/Elementor/controls.json`, which `npm run build` generates from each block's `edit.jsx` (`scripts/build-elementor-map.mjs`). A new block becomes a widget automatically.

## Development

```bash
npm install          # build tools
npm run build        # compile blocks, CSS and JS into assets/dist/ and refresh the Elementor control map
npm run dev          # watch mode
npm run validate     # check every block in templates/, parts/ and patterns/ against its block.json
npm run package      # build + validate + create modern-core.zip
composer install     # PHPUnit / PHPCS (development only)
```

Adding a block: create `blocks/<name>/` with `block.json`, `index.js`, `edit.jsx` and `render.php`, then run `npm run build`. The block is registered automatically, with the editor script handle `modern-core-<name>-js`.

Commit `assets/dist/` after building: WordPress loads the built files, and the zip is made from them.

### Notes for block authors

- Inline `<script>` tags in a block's `render.php` are moved to the page footer automatically (WordPress's text formatting otherwise corrupts `&&` inside them).
- Use `modern_core_product_query_args()` for product queries: it hides catalogue-excluded products and the product being viewed.
- `window.mc_ajax` gives scripts the AJAX URL and a nonce. Endpoints that change data (bundle add-to-cart, newsletter, back-in-stock) require the nonce.
