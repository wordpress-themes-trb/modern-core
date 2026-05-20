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
    style: { width: "100%", padding: "40px 0", overflowX: "auto" },
  });

  // Mock Data for the Matrix
  const mockProducts = Array.from({ length: Math.min(maxColumns, 4) }).map(
    (_, i) => ({
      id: i,
      title:
        i % 2 === 0
          ? "Premium Cotton Silhouette"
          : "Minimalist Architecture Chair",
      price: i === 1 ? "$245.00" : "$120.00",
      image: `https://images.unsplash.com/photo-${i % 2 === 0 ? "1521572163474-6f64f9fa023e" : "1506422745199-563b7e735496"}?q=80&w=300&auto=format&fit=crop`,
      specs: {
        material: i % 2 === 0 ? "100% Cotton" : "Oak Wood",
        weight: i === 1 ? "12 lbs" : "1.2 lbs",
        waterproof: i === 2,
      },
    }),
  );

  const renderRow = (label, key, isEven) => (
    <div
      style={{
        display: "flex",
        backgroundColor: alternateRows && isEven ? rowEvenBg : rowOddBg,
        borderBottom: `1px solid ${borderColor}`,
      }}
    >
      <div
        style={{
          width: `${labelWidth}%`,
          padding: "15px 20px",
          fontWeight: "bold",
          fontSize: "13px",
          color: "#64748b",
          borderRight: `1px solid ${borderColor}`,
          display: "flex",
          alignItems: "center",
        }}
      >
        {label}
      </div>
      {mockProducts.map((p, i) => (
        <div
          key={i}
          style={{
            flex: 1,
            padding: "15px 20px",
            fontSize: "14px",
            borderRight:
              i < mockProducts.length - 1 ? `1px solid ${borderColor}` : "none",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            textAlign: "center",
          }}
        >
          {typeof p.specs[key] === "boolean" ? (
            p.specs[key] ? (
              <span style={{ color: "#10b981", fontWeight: "bold" }}>✓</span>
            ) : (
              <span style={{ color: "#ef4444", fontWeight: "bold" }}>✕</span>
            )
          ) : (
            p.specs[key]
          )}
        </div>
      ))}
    </div>
  );

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
          />
          <ToggleControl
            label={__("Enable Sticky Header", "modern-core")}
            checked={isSticky}
            onChange={(val) => setAttributes({ isSticky: val })}
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
            onChange={(val) => setAttributes({ showImage: val })}
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
            onChange={(val) => setAttributes({ showPrice: val })}
          />
          <ToggleControl
            label={__("Show Rating", "modern-core")}
            checked={showRating}
            onChange={(val) => setAttributes({ showRating: val })}
          />
          <ToggleControl
            label={__("Show Add to Cart", "modern-core")}
            checked={showAtc}
            onChange={(val) => setAttributes({ showAtc: val })}
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
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Header Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={headerBg}
            onChange={(val) => setAttributes({ headerBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Odd Row Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={rowOddBg}
            onChange={(val) => setAttributes({ rowOddBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Even Row Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={rowEvenBg}
            onChange={(val) => setAttributes({ rowEvenBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Border Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={borderColor}
            onChange={(val) => setAttributes({ borderColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
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
          {/* Sticky Header Row */}
          <div
            style={{
              display: "flex",
              backgroundColor: headerBg,
              borderBottom: `2px solid ${borderColor}`,
            }}
          >
            <div
              style={{
                width: `${labelWidth}%`,
                borderRight: `1px solid ${borderColor}`,
              }}
            ></div>
            {mockProducts.map((p, i) => (
              <div
                key={p.id}
                style={{
                  flex: 1,
                  padding: "20px",
                  borderRight:
                    i < mockProducts.length - 1
                      ? `1px solid ${borderColor}`
                      : "none",
                  position: "relative",
                  display: "flex",
                  flexDirection: "column",
                  alignItems: "center",
                  textAlign: "center",
                }}
              >
                <span
                  style={{
                    position: "absolute",
                    top: "10px",
                    right: "10px",
                    fontSize: "12px",
                    opacity: 0.5,
                    cursor: "pointer",
                  }}
                >
                  ✕
                </span>
                {showImage && (
                  <div
                    style={{
                      width: "100%",
                      maxWidth: "120px",
                      aspectRatio: imageRatio,
                      backgroundImage: `url(${p.image})`,
                      backgroundSize: "cover",
                      backgroundPosition: "center",
                      borderRadius: "4px",
                      marginBottom: "15px",
                    }}
                  ></div>
                )}
                <h4
                  style={{
                    margin: "0 0 10px 0",
                    fontSize: "14px",
                    lineHeight: 1.3,
                  }}
                >
                  {p.title}
                </h4>
                {showRating && (
                  <div
                    style={{
                      color: "#f59e0b",
                      fontSize: "11px",
                      marginBottom: "10px",
                    }}
                  >
                    ★★★★★
                  </div>
                )}
                {showPrice && (
                  <div
                    style={{
                      fontWeight: "bold",
                      fontSize: "15px",
                      marginBottom: "15px",
                    }}
                  >
                    {p.price}
                  </div>
                )}
                {showAtc && (
                  <div
                    style={{
                      padding: "10px 15px",
                      background: accentColor,
                      color: "#fff",
                      fontSize: "12px",
                      fontWeight: "bold",
                      borderRadius: "4px",
                      width: "100%",
                    }}
                  >
                    Add to Cart
                  </div>
                )}
              </div>
            ))}
          </div>

          {/* Specification Rows */}
          {renderRow("Material", "material", false)}
          {renderRow("Item Weight", "weight", true)}
          {renderRow("Waterproof", "waterproof", false)}
        </div>
      </div>
    </>
  );
}
