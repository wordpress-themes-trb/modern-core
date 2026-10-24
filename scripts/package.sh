#!/usr/bin/env bash
# Build the theme and create modern-core.zip ready for Appearance > Themes > Add New > Upload.
# Usage: npm run package   (or: bash scripts/package.sh)
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/modern-core.zip"
STAGE="$(mktemp -d)"
trap 'rm -rf "$STAGE"' EXIT

cd "$ROOT"
npm run build --silent
python3 scripts/validate-templates.py

# Files the theme needs at runtime. Sources (.jsx, .scss) are left out; the built
# assets/dist files are what WordPress loads.
mkdir -p "$STAGE/modern-core"
for path in style.css functions.php theme.json screenshot.png readme.txt README.md \
            inc parts patterns templates woocommerce languages assets/dist assets/fonts assets/images; do
  [ -e "$path" ] && cp -R "$path" "$STAGE/modern-core/"
done
# Block folders without their editor sources (block.json + render.php are what WordPress reads).
mkdir -p "$STAGE/modern-core/blocks"
for dir in blocks/*/; do
  name="$(basename "$dir")"
  mkdir -p "$STAGE/modern-core/blocks/$name"
  cp "$dir"block.json "$STAGE/modern-core/blocks/$name/"
  [ -f "$dir"render.php ] && cp "$dir"render.php "$STAGE/modern-core/blocks/$name/"
done
# assets/dist must sit where the PHP expects it.
mkdir -p "$STAGE/modern-core/assets"
[ -d "$STAGE/modern-core/dist" ] && mv "$STAGE/modern-core/dist" "$STAGE/modern-core/assets/dist"
[ -d "$STAGE/modern-core/fonts" ] && mv "$STAGE/modern-core/fonts" "$STAGE/modern-core/assets/fonts"
[ -d "$STAGE/modern-core/images" ] && mv "$STAGE/modern-core/images" "$STAGE/modern-core/assets/images"
rm -f "$STAGE/modern-core/assets/dist/.vite/manifest.json"; rmdir "$STAGE/modern-core/assets/dist/.vite" 2>/dev/null || true

rm -f "$OUT"
(cd "$STAGE" && zip -rq "$OUT" modern-core -x '*.DS_Store')
echo "Created $OUT ($(du -h "$OUT" | cut -f1))"
