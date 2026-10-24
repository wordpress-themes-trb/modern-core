import * as React from "@wordpress/element";
import { useState, useEffect } from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
  CheckboxControl,
  Spinner,
  Notice,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    sourceMode,
    catCount,
    manualCatIds,
    featuredCatId,
    layoutMode,
    colsDesktop,
    colsMobile,
    imageRatio,
    labelPlacement,
    textPosition,
    showCount,
    hoverEffect,
    cardRadius,
    overlayOpacity,
    accentColor,
    textColor,
  } = attributes;

  // ── Fetch all product categories from the REST API ──────────────────────────
  // useSelect keeps this reactive — re-runs when the store updates.
  const { allCategories, isLoading } = useSelect((select) => {
    const store = select("core");
    const cats = store.getEntityRecords("taxonomy", "product_cat", {
      per_page: 100,
      hide_empty: false,
      _fields: "id,name,count,slug",
    });
    const loading = !store.hasFinishedResolution("getEntityRecords", [
      "taxonomy",
      "product_cat",
      { per_page: 100, hide_empty: false, _fields: "id,name,count,slug" },
    ]);
    return { allCategories: cats || [], isLoading: loading };
  }, []);

  // Filter out 'uncategorized'
  const categories = allCategories.filter((c) => c.slug !== "uncategorized");

  // Toggle a category in/out of manualCatIds
  const toggleManualCat = (catId) => {
    const id = Number(catId);
    const ids = manualCatIds.includes(id)
      ? manualCatIds.filter((i) => i !== id)
      : [...manualCatIds, id];
    setAttributes({ manualCatIds: ids });
  };

  // ── Text alignment helper (editor preview) ──────────────────────────────────
  let alignStyles = { alignItems: "flex-start", justifyContent: "flex-end" };
  if (textPosition === "center")
    alignStyles = { alignItems: "center", justifyContent: "center" };
  if (textPosition === "bottom-center")
    alignStyles = { alignItems: "center", justifyContent: "flex-end" };
  if (textPosition === "top-left")
    alignStyles = { alignItems: "flex-start", justifyContent: "flex-start" };

  // ── Determine which cats to preview ─────────────────────────────────────────
  const previewCats =
    sourceMode === "manual" && manualCatIds.length > 0
      ? categories
          .filter((c) => manualCatIds.includes(Number(c.id)))
          .sort(
            (a, b) =>
              manualCatIds.indexOf(Number(a.id)) -
              manualCatIds.indexOf(Number(b.id)),
          )
      : categories.slice(0, catCount);

  // Move featured cat to front for preview
  const orderedPreview = (() => {
    if (layoutMode !== "featured" || !featuredCatId) return previewCats;
    const idx = previewCats.findIndex(
      (c) => Number(c.id) === Number(featuredCatId),
    );
    if (idx <= 0) return previewCats;
    const copy = [...previewCats];
    const [feat] = copy.splice(idx, 1);
    return [feat, ...copy];
  })();

  const blockProps = useBlockProps({
    className: `modern-core-category-editor layout-${layoutMode} label-${labelPlacement}`,
    style: { width: "100%", padding: "40px 0", boxSizing: "border-box" },
  });

  // Mock image per index
  const mockImg = (i) =>
    "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><rect width='4' height='5' fill='%23e5e1da'/></svg>";

  return (
    <>
      <InspectorControls>
        {/* ── DATA SOURCE ─────────────────────────────────────────────── */}
        <PanelBody title={__("Data Source", "modern-core")}>
          <SelectControl
            label={__("Category Source", "modern-core")}
            value={sourceMode}
            options={[
              { label: "Top Categories (Dynamic)", value: "dynamic" },
              { label: "Manual Selection", value: "manual" },
            ]}
            onChange={(val) => setAttributes({ sourceMode: val })}
          />

          {/* Dynamic: just a count slider */}
          {sourceMode === "dynamic" && (
            <RangeControl
              label={__("Number of Categories", "modern-core")}
              value={catCount}
              onChange={(val) => setAttributes({ catCount: val })}
              min={2}
              max={12}
            />
          )}

          {/* FIX 1 — Manual: checkbox list of all product categories */}
          {sourceMode === "manual" && (
            <div style={{ marginTop: "12px" }}>
              <p
                style={{
                  margin: "0 0 8px",
                  fontWeight: 600,
                  fontSize: "11px",
                  textTransform: "uppercase",
                  letterSpacing: "0.5px",
                }}
              >
                {__("Select Categories", "modern-core")}
              </p>
              {isLoading ? (
                <Spinner />
              ) : categories.length === 0 ? (
                <Notice status="warning" isDismissible={false}>
                  {__("No product categories found.", "modern-core")}
                </Notice>
              ) : (
                <div
                  style={{
                    maxHeight: "260px",
                    overflowY: "auto",
                    border: "1px solid #e0e0e0",
                    borderRadius: "4px",
                    padding: "8px",
                  }}
                >
                  {categories.map((cat) => (
                    <div key={cat.id} style={{ marginBottom: "6px" }}>
                      <CheckboxControl
                        label={`${cat.name} (${cat.count})`}
                        checked={manualCatIds.includes(Number(cat.id))}
                        onChange={() => toggleManualCat(cat.id)}
                      />
                    </div>
                  ))}
                </div>
              )}
              {manualCatIds.length > 0 && (
                <p
                  style={{
                    margin: "8px 0 0",
                    fontSize: "11px",
                    color: "#757575",
                  }}
                >
                  {manualCatIds.length}{" "}
                  {__("categories selected", "modern-core")}
                </p>
              )}
            </div>
          )}
        </PanelBody>

        {/* ── GRID ARCHITECTURE ───────────────────────────────────────── */}
        <PanelBody
          title={__("Grid Architecture", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Uniform Grid", value: "grid" },
              { label: "Featured Grid (Bento)", value: "featured" },
              { label: "Horizontal Scroll", value: "scroll" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />

          {/* FIX 2 — Featured category picker — only shown in featured mode */}
          {layoutMode === "featured" && (
            <div style={{ marginTop: "12px" }}>
              <p
                style={{
                  margin: "0 0 6px",
                  fontWeight: 600,
                  fontSize: "11px",
                  textTransform: "uppercase",
                  letterSpacing: "0.5px",
                }}
              >
                {__("Featured (Hero) Category", "modern-core")}
              </p>
              {isLoading ? (
                <Spinner />
              ) : (
                <SelectControl
                  value={String(featuredCatId || 0)}
                  options={[
                    {
                      label: __("— First category (default) —", "modern-core"),
                      value: "0",
                    },
                    ...categories.map((c) => ({
                      label: `${c.name} (${c.count})`,
                      value: String(c.id),
                    })),
                  ]}
                  onChange={(val) =>
                    setAttributes({ featuredCatId: Number(val) })
                  }
                />
              )}
              <p
                style={{
                  margin: "4px 0 0",
                  fontSize: "11px",
                  color: "#757575",
                }}
              >
                {__(
                  "This category will be pinned as the large left tile.",
                  "modern-core",
                )}
              </p>
            </div>
          )}

          <RangeControl
            label={__("Desktop Columns", "modern-core")}
            value={colsDesktop}
            onChange={(val) => setAttributes({ colsDesktop: val })}
            min={2}
            max={6}
          />
          <RangeControl
            label={__("Mobile Columns", "modern-core")}
            value={colsMobile}
            onChange={(val) => setAttributes({ colsMobile: val })}
            min={1}
            max={3}
          />
        </PanelBody>

        {/* ── TILE DESIGN ─────────────────────────────────────────────── */}
        <PanelBody title={__("Tile Design", "modern-core")} initialOpen={false}>
          <SelectControl
            label={__("Label Placement", "modern-core")}
            value={labelPlacement}
            options={[
              { label: "Overlaid on Image", value: "overlay" },
              { label: "Below Image (Caption)", value: "below" },
            ]}
            onChange={(val) => setAttributes({ labelPlacement: val })}
          />
          {labelPlacement === "overlay" && (
            <SelectControl
              label={__("Text Position", "modern-core")}
              value={textPosition}
              options={[
                { label: "Bottom Left", value: "bottom-left" },
                { label: "Bottom Center", value: "bottom-center" },
                { label: "Center", value: "center" },
                { label: "Top Left", value: "top-left" },
              ]}
              onChange={(val) => setAttributes({ textPosition: val })}
            />
          )}
          <SelectControl
            label={__("Image Aspect Ratio", "modern-core")}
            value={imageRatio}
            options={[
              { label: "Portrait (3:4)", value: "3/4" },
              { label: "Square (1:1)", value: "1/1" },
              { label: "Landscape (16:9)", value: "16/9" },
            ]}
            onChange={(val) => setAttributes({ imageRatio: val })}
          />
          <ToggleControl
            label={__("Show Product Count", "modern-core")}
            checked={showCount}
            onChange={(val) => setAttributes({ showCount: val })}
          />
          <RangeControl
            label={__("Tile Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
          <RangeControl
            label={__("Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={90}
          />
        </PanelBody>

        {/* ── COLORS ──────────────────────────────────────────────────── */}
        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ margin: "0 0 5px", fontWeight: 600 }}>
            {__("Text Color", "modern-core")}
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p
            style={{ marginTop: "15px", marginBottom: "5px", fontWeight: 600 }}
          >
            {__("Brand Accent", "modern-core")}
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      {/* ── EDITOR PREVIEW ────────────────────────────────────────────── */}
      <div {...blockProps}>
        <style>{`
          .mc-cat-grid-mock {
            display: grid;
            grid-template-columns: repeat(${colsDesktop}, 1fr);
            gap: 20px;
          }
          .layout-featured .mc-cat-grid-mock {
            grid-template-columns: 2fr 1fr;
          }
          .layout-featured .mc-cat-grid-mock .mc-cat-tile-mock:first-child {
            grid-row: 1 / span 2;
          }
          .layout-featured .mc-cat-grid-mock .mc-cat-tile-mock:first-child .mc-cat-img-wrap {
            height: 100%; aspect-ratio: auto; min-height: 300px;
          }
          .layout-scroll .mc-cat-grid-mock {
            display: flex; overflow-x: auto; gap: 15px;
          }
          .layout-scroll .mc-cat-tile-mock {
            flex: 0 0 calc(100% / ${colsDesktop});
          }
          .mc-cat-tile-mock {
            border-radius: ${cardRadius}px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
          }
          .mc-cat-img-wrap {
            width: 100%;
            aspect-ratio: ${imageRatio};
            position: relative;
            overflow: hidden;
            background: #e2e8f0;
          }
        `}</style>

        {/* Loading state */}
        {isLoading && (
          <div
            style={{
              display: "flex",
              alignItems: "center",
              gap: "10px",
              padding: "20px",
              color: "#757575",
            }}
          >
            <Spinner /> {__("Loading categories…", "modern-core")}
          </div>
        )}

        {/* Manual + no selection warning */}
        {!isLoading && sourceMode === "manual" && manualCatIds.length === 0 && (
          <Notice status="info" isDismissible={false}>
            {__(
              "Select categories from the sidebar to preview.",
              "modern-core",
            )}
          </Notice>
        )}

        {/* Grid preview */}
        {!isLoading && orderedPreview.length > 0 && (
          <div className="mc-cat-grid-mock">
            {orderedPreview.map((cat, i) => (
              <div key={cat.id} className="mc-cat-tile-mock">
                <div
                  className="mc-cat-img-wrap"
                  style={{
                    backgroundImage: `url(${mockImg(i)})`,
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                  }}
                >
                  {labelPlacement === "overlay" && (
                    <>
                      <div
                        style={{
                          position: "absolute",
                          inset: 0,
                          background: textPosition.includes("bottom")
                            ? `linear-gradient(to top, rgba(0,0,0,${overlayOpacity / 100 + 0.2}), rgba(0,0,0,0))`
                            : `rgba(0,0,0,${overlayOpacity / 100})`,
                        }}
                      />
                      <div
                        style={{
                          position: "absolute",
                          inset: 0,
                          padding: "20px",
                          display: "flex",
                          flexDirection: "column",
                          color: textColor,
                          ...alignStyles,
                        }}
                      >
                        <h3
                          style={{
                            margin: 0,
                            fontSize: "20px",
                            fontWeight: 700,
                          }}
                        >
                          {cat.name}
                          {layoutMode === "featured" &&
                            Number(cat.id) === Number(featuredCatId) && (
                              <span
                                style={{
                                  fontSize: "10px",
                                  background: "rgba(255,255,255,0.2)",
                                  padding: "2px 6px",
                                  borderRadius: "3px",
                                  marginLeft: "8px",
                                  verticalAlign: "middle",
                                }}
                              >
                                FEATURED
                              </span>
                            )}
                        </h3>
                        {showCount && (
                          <span
                            style={{
                              fontSize: "13px",
                              opacity: 0.8,
                              marginTop: "5px",
                            }}
                          >
                            {cat.count} products
                          </span>
                        )}
                      </div>
                    </>
                  )}
                </div>
                {labelPlacement === "below" && (
                  <div
                    style={{
                      padding: "15px 0",
                      color: accentColor,
                      textAlign: "center",
                    }}
                  >
                    <h3
                      style={{ margin: 0, fontSize: "16px", fontWeight: 700 }}
                    >
                      {cat.name}
                    </h3>
                    {showCount && (
                      <span
                        style={{
                          fontSize: "12px",
                          opacity: 0.6,
                          display: "block",
                          marginTop: "4px",
                        }}
                      >
                        {cat.count} products
                      </span>
                    )}
                  </div>
                )}
              </div>
            ))}
          </div>
        )}
      </div>
    </>
  );
}
