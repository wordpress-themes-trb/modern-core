import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
  TextControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    sourceMode,
    staticProducts,
    maxColumns,
    labelWidth,
    isSticky,
    alternateRows,
    showImage,
    showPrice,
    showRating,
    showAtc,
    imageRatio,
    tableRadius,
    headerBg,
    rowOddBg,
    rowEvenBg,
    borderColor,
    accentColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-compare-editor",
    style: {
      width: "100%",
      padding: "40px 0",
      overflowX: "auto",
      boxSizing: "border-box",
    },
  });

  const colCount = Math.min(maxColumns, 4);

  const mockProducts = Array.from({ length: colCount }).map((_, i) => ({
    id: i,
    title:
      i % 2 === 0
        ? "Premium Cotton Silhouette"
        : "Minimalist Architecture Chair",
    price: i === 1 ? "$245.00" : "$120.00",
    image: `https://images.unsplash.com/photo-${
      i % 2 === 0 ? "1521572163474-6f64f9fa023e" : "1506422745199-563b7e735496"
    }?q=80&w=300&auto=format&fit=crop`,
    specs: {
      Material: i % 2 === 0 ? "100% Cotton" : "Oak Wood",
      Weight: i === 1 ? "12 lbs" : "1.2 lbs",
      Waterproof: i === 2 ? "Yes" : "No",
      Warranty: "2 Years",
    },
  }));

  const specKeys = ["Material", "Weight", "Waterproof", "Warranty"];

  /* Shared cell style */
  const cellBase = {
    flex: 1,
    padding: "16px 12px",
    borderRight: `1px solid ${borderColor}`,
    boxSizing: "border-box",
    fontSize: "14px",
  };

  /* Label cell style */
  const labelCell = {
    width: `${labelWidth}%`,
    minWidth: "120px",
    padding: "16px 20px",
    fontWeight: 600,
    fontSize: "13px",
    color: "#64748b",
    borderRight: `1px solid ${borderColor}`,
    background: headerBg,
    boxSizing: "border-box",
    whiteSpace: "nowrap",
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Data Routing", "modern-core")}>
          <SelectControl
            label={__("Compare Data Source", "modern-core")}
            value={sourceMode}
            options={[
              { label: "Dynamic (Visitor Session)", value: "dynamic" },
              { label: "Static (Editorial Selection)", value: "static" },
            ]}
            onChange={(val) => setAttributes({ sourceMode: val })}
          />
          {sourceMode === "static" && (
            <TextControl
              label={__("Product IDs (Comma Separated)", "modern-core")}
              value={staticProducts}
              onChange={(val) => setAttributes({ staticProducts: val })}
              help={__("Example: 12, 45, 89", "modern-core")}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Matrix Architecture", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Max Columns (Products)", "modern-core")}
            value={maxColumns}
            onChange={(val) => setAttributes({ maxColumns: val })}
            min={2}
            max={6}
          />
          <RangeControl
            label={__("Row Label Column Width (%)", "modern-core")}
            value={labelWidth}
            onChange={(val) => setAttributes({ labelWidth: val })}
            min={15}
            max={40}
            help={__(
              "The left column showing attribute names like 'Material', 'Weight', etc.",
              "modern-core",
            )}
          />
          <ToggleControl
            label={__("Enable Sticky Header", "modern-core")}
            checked={isSticky}
            onChange={(val) => setAttributes({ isSticky: val })}
            help={__(
              "Pins the product header row while scrolling through attributes.",
              "modern-core",
            )}
          />
          <RangeControl
            label={__("Table Border Radius", "modern-core")}
            value={tableRadius}
            onChange={(val) => setAttributes({ tableRadius: val })}
            min={0}
            max={24}
          />
        </PanelBody>

        <PanelBody
          title={__("Header Elements", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Image", "modern-core")}
            checked={showImage}
            onChange={(v) => setAttributes({ showImage: v })}
          />
          {showImage && (
            <SelectControl
              label={__("Image Aspect Ratio", "modern-core")}
              value={imageRatio}
              options={[
                { label: "Square (1:1)", value: "1/1" },
                { label: "Portrait (3:4)", value: "3/4" },
                { label: "Landscape (16:9)", value: "16/9" },
              ]}
              onChange={(val) => setAttributes({ imageRatio: val })}
            />
          )}
          <ToggleControl
            label={__("Show Price", "modern-core")}
            checked={showPrice}
            onChange={(v) => setAttributes({ showPrice: v })}
          />
          <ToggleControl
            label={__("Show Rating", "modern-core")}
            checked={showRating}
            onChange={(v) => setAttributes({ showRating: v })}
          />
          <ToggleControl
            label={__("Show Add to Cart", "modern-core")}
            checked={showAtc}
            onChange={(v) => setAttributes({ showAtc: v })}
          />
        </PanelBody>

        <PanelBody
          title={__("Colors & Styling", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Alternate Row Colors", "modern-core")}
            checked={alternateRows}
            onChange={(val) => setAttributes({ alternateRows: val })}
          />
          {[
            ["Header Background", "headerBg", headerBg],
            ["Odd Row Background", "rowOddBg", rowOddBg],
            ["Even Row Background", "rowEvenBg", rowEvenBg],
            ["Border Color", "borderColor", borderColor],
            ["Brand Accent Color", "accentColor", accentColor],
          ].map(([label, key, val]) => (
            <div key={key}>
              <p style={{ margin: "15px 0 5px", fontWeight: 600 }}>
                {__(label, "modern-core")}
              </p>
              <ColorPalette
                value={val}
                onChange={(v) => setAttributes({ [key]: v })}
              />
            </div>
          ))}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            border: `1px solid ${borderColor}`,
            borderRadius: `${tableRadius}px`,
            boxShadow: "0 10px 30px rgba(0,0,0,0.05)",
            overflow: "hidden",
            background: "#fff",
          }}
        >
          {/* ── HEADER ROW ───────────────────────────────────── */}
          <div
            style={{
              display: "flex",
              backgroundColor: headerBg,
              borderBottom: `2px solid ${borderColor}`,
              position: isSticky ? "sticky" : "relative",
              top: isSticky ? 0 : "auto",
              zIndex: isSticky ? 10 : "auto",
              boxShadow: isSticky ? "0 4px 12px rgba(0,0,0,0.06)" : "none",
            }}
          >
            {/* Top-left empty cell */}
            <div style={{ ...labelCell, borderBottom: "none" }} />

            {mockProducts.map((p, i) => (
              <div
                key={p.id}
                style={{
                  ...cellBase,
                  display: "flex",
                  flexDirection: "column",
                  alignItems: "center",
                  padding: "20px 12px",
                  background: headerBg,
                  position: "relative",
                  borderRight:
                    i < mockProducts.length - 1
                      ? `1px solid ${borderColor}`
                      : "none",
                  /* FIX: min-height keeps all cards same height */
                  minHeight: "280px",
                }}
              >
                {/* Remove X */}
                <span
                  style={{
                    position: "absolute",
                    top: 8,
                    right: 8,
                    fontSize: "12px",
                    opacity: 0.3,
                    cursor: "pointer",
                  }}
                >
                  ✕
                </span>

                {/* Image */}
                {showImage && (
                  <div
                    style={{
                      width: "100%",
                      maxWidth: "120px",
                      aspectRatio: imageRatio,
                      backgroundImage: `url(${p.image})`,
                      backgroundSize: "cover",
                      backgroundPosition: "center",
                      borderRadius: "6px",
                      marginBottom: "12px",
                      flexShrink: 0,
                    }}
                  />
                )}

                {/* Title — 2-line clamp */}
                <h4
                  style={{
                    margin: "0 0 8px",
                    fontSize: "14px",
                    fontWeight: 700,
                    lineHeight: 1.35,
                    textAlign: "center",
                    display: "-webkit-box",
                    WebkitLineClamp: 2,
                    WebkitBoxOrient: "vertical",
                    overflow: "hidden",
                    width: "100%",
                  }}
                >
                  {p.title}
                </h4>

                {/* Rating */}
                {showRating && (
                  <div
                    style={{
                      color: "#f59e0b",
                      fontSize: "11px",
                      marginBottom: "8px",
                    }}
                  >
                    ★★★★★
                  </div>
                )}

                {/* Price — margin-top:auto pushes it toward ATC */}
                {showPrice && (
                  <div
                    style={{
                      fontWeight: 700,
                      fontSize: "15px",
                      marginTop: "auto",
                      marginBottom: "10px",
                    }}
                  >
                    {p.price}
                  </div>
                )}

                {/* ATC */}
                {showAtc && (
                  <div
                    style={{
                      padding: "10px 12px",
                      background: accentColor,
                      color: "#fff",
                      fontSize: "12px",
                      fontWeight: 700,
                      borderRadius: "4px",
                      width: "100%",
                      textAlign: "center",
                      boxSizing: "border-box",
                      marginTop: showPrice ? 0 : "auto",
                    }}
                  >
                    Add to Cart
                  </div>
                )}
              </div>
            ))}
          </div>

          {/* ── SPEC ROWS ─────────────────────────────────────── */}
          {specKeys.map((key, rowIdx) => {
            const isEven = rowIdx % 2 !== 0;
            const rowBg = alternateRows
              ? isEven
                ? rowEvenBg
                : rowOddBg
              : rowOddBg;
            return (
              <div
                key={key}
                style={{
                  display: "flex",
                  background: rowBg,
                  borderBottom: `1px solid ${borderColor}`,
                }}
              >
                {/* Row label — this is what "Row Label Column Width" controls */}
                <div
                  style={{
                    ...labelCell,
                    background: rowBg,
                    borderBottom: "none",
                  }}
                >
                  {key}
                </div>
                {mockProducts.map((p, i) => (
                  <div
                    key={i}
                    style={{
                      ...cellBase,
                      textAlign: "center",
                      background: rowBg,
                      borderRight:
                        i < mockProducts.length - 1
                          ? `1px solid ${borderColor}`
                          : "none",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                    }}
                  >
                    {p.specs[key]}
                  </div>
                ))}
              </div>
            );
          })}
        </div>
      </div>
    </>
  );
}
