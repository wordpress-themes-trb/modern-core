import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  ToggleControl,
  RangeControl,
  SelectControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    showPriceFilter,
    minPrice,
    maxPrice,
    showColorFilter,
    swatchShape,
    swatchSize,
    showStatusFilter,
    showRatingFilter,
    accentColor,
    headingColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-product-filters-block",
    style: {
      display: "flex",
      flexDirection: "column",
      gap: "30px",
      width: "100%",
      color: "inherit",
    },
  });

  const mockColors = [
    { name: "Black", hex: "#000000" },
    {
      name: "White",
      hex: "#ffffff",
      border: "1px solid rgba(255,255,255,0.2)",
    },
    { name: "Oatmeal", hex: "#e4dccb" },
    { name: "Olive", hex: "#4a5343" },
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Filter Modules", "modern-core")}>
          <ToggleControl
            label={__("Enable Price Slider", "modern-core")}
            checked={showPriceFilter}
            onChange={(val) => setAttributes({ showPriceFilter: val })}
          />
          <ToggleControl
            label={__("Enable Color Swatches", "modern-core")}
            checked={showColorFilter}
            onChange={(val) => setAttributes({ showColorFilter: val })}
          />
          <ToggleControl
            label={__("Enable Stock/Sale Toggles", "modern-core")}
            checked={showStatusFilter}
            onChange={(val) => setAttributes({ showStatusFilter: val })}
          />
          <ToggleControl
            label={__("Enable Rating Filter", "modern-core")}
            checked={showRatingFilter}
            onChange={(val) => setAttributes({ showRatingFilter: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Module Customization", "modern-core")}
          initialOpen={false}
        >
          {showPriceFilter && (
            <RangeControl
              label={__("Max Price Limit ($)", "modern-core")}
              value={maxPrice}
              onChange={(val) => setAttributes({ maxPrice: val })}
              min={50}
              max={1000}
            />
          )}
          {showColorFilter && (
            <>
              <SelectControl
                label={__("Swatch Shape", "modern-core")}
                value={swatchShape}
                options={[
                  { label: "Circular Circle", value: "circle" },
                  { label: "Soft Square", value: "square" },
                ]}
                onChange={(val) => setAttributes({ swatchShape: val })}
              />
              <RangeControl
                label={__("Swatch Size (px)", "modern-core")}
                value={swatchSize}
                onChange={(val) => setAttributes({ swatchSize: val })}
                min={20}
                max={48}
              />
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Colors & Design", "modern-core")}
          initialOpen={false}
        >
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Interactive Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Group Heading Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={headingColor}
            onChange={(val) => setAttributes({ headingColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Visual Header / Active Tags Preview */}
        <div>
          <div
            style={{
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
              marginBottom: "10px",
            }}
          >
            <span
              style={{
                fontSize: "12px",
                fontWeight: "bold",
                textTransform: "uppercase",
                opacity: 0.6,
              }}
            >
              Active Filters
            </span>
            <span
              style={{
                fontSize: "11px",
                textDecoration: "underline",
                cursor: "pointer",
                opacity: 0.5,
              }}
            >
              Clear All
            </span>
          </div>
          <div style={{ display: "flex", gap: "6px", flexWrap: "wrap" }}>
            <span
              style={{
                fontSize: "12px",
                background: "rgba(255,255,255,0.08)",
                padding: "4px 10px",
                borderRadius: "4px",
                display: "flex",
                alignItems: "center",
                gap: "6px",
              }}
            >
              On Sale <span style={{ opacity: 0.5 }}>✕</span>
            </span>
            <span
              style={{
                fontSize: "12px",
                background: "rgba(255,255,255,0.08)",
                padding: "4px 10px",
                borderRadius: "4px",
                display: "flex",
                alignItems: "center",
                gap: "6px",
              }}
            >
              Under ${maxPrice} <span style={{ opacity: 0.5 }}>✕</span>
            </span>
          </div>
        </div>

        {/* 1. PRICE FILTER */}
        {showPriceFilter && (
          <div
            style={{
              borderTop: "1px solid rgba(255,255,255,0.05)",
              paddingTop: "20px",
            }}
          >
            <h4
              style={{
                margin: "0 0 15px 0",
                fontSize: "13px",
                textTransform: "uppercase",
                letterSpacing: "1px",
                color: headingColor,
              }}
            >
              Filter By Price
            </h4>
            <div style={{ display: "flex", gap: "10px", alignItems: "center" }}>
              <div
                style={{
                  flex: 1,
                  background: "rgba(255,255,255,0.05)",
                  padding: "8px",
                  borderRadius: "4px",
                  fontSize: "13px",
                  border: "1px solid rgba(255,255,255,0.1)",
                }}
              >
                ${minPrice}
              </div>
              <span style={{ opacity: 0.4 }}>—</span>
              <div
                style={{
                  flex: 1,
                  background: "rgba(255,255,255,0.05)",
                  padding: "8px",
                  borderRadius: "4px",
                  fontSize: "13px",
                  border: "1px solid rgba(255,255,255,0.1)",
                }}
              >
                ${maxPrice}
              </div>
            </div>
            <div
              style={{
                width: "100%",
                height: "4px",
                background: "rgba(255,255,255,0.1)",
                marginTop: "15px",
                position: "relative",
                borderRadius: "2px",
              }}
            >
              <div
                style={{
                  absolute: "absolute",
                  left: "0",
                  right: "0",
                  top: 0,
                  bottom: 0,
                  backgroundColor: accentColor,
                  borderRadius: "2px",
                  width: "100%",
                }}
              ></div>
            </div>
          </div>
        )}

        {/* 2. COLOR SWATCHES */}
        {showColorFilter && (
          <div
            style={{
              borderTop: "1px solid rgba(255,255,255,0.05)",
              paddingTop: "20px",
            }}
          >
            <h4
              style={{
                margin: "0 0 15px 0",
                fontSize: "13px",
                textTransform: "uppercase",
                letterSpacing: "1px",
                color: headingColor,
              }}
            >
              Color Attribute
            </h4>
            <div style={{ display: "flex", gap: "10px", flexWrap: "wrap" }}>
              {mockColors.map((color, index) => (
                <div
                  key={index}
                  style={{
                    width: `${swatchSize}px`,
                    height: `${swatchSize}px`,
                    backgroundColor: color.hex,
                    borderRadius: swatchShape === "circle" ? "50%" : "6px",
                    border: color.border || "1px solid transparent",
                    cursor: "pointer",
                    position: "relative",
                    boxShadow:
                      index === 0 ? `0 0 0 2px ${accentColor}` : "none", // simulates selected item
                  }}
                  title={color.name}
                />
              ))}
            </div>
          </div>
        )}

        {/* 3. STOCK / BINARY STATUS */}
        {showStatusFilter && (
          <div
            style={{
              borderTop: "1px solid rgba(255,255,255,0.05)",
              paddingTop: "20px",
            }}
          >
            <h4
              style={{
                margin: "0 0 15px 0",
                fontSize: "13px",
                textTransform: "uppercase",
                letterSpacing: "1px",
                color: headingColor,
              }}
            >
              Availability
            </h4>
            <div
              style={{ display: "flex", flexDirection: "column", gap: "12px" }}
            >
              {["In Stock Only", "On Sale"].map((status, i) => (
                <label
                  key={i}
                  style={{
                    display: "flex",
                    alignItems: "center",
                    gap: "10px",
                    fontSize: "14px",
                    cursor: "pointer",
                  }}
                >
                  <input
                    type="checkbox"
                    defaultChecked={i === 1}
                    style={{ accentColor: accentColor }}
                    readOnly
                  />
                  <span>{status}</span>
                </label>
              ))}
            </div>
          </div>
        )}

        {/* 4. RATINGS FILTER */}
        {showRatingFilter && (
          <div
            style={{
              borderTop: "1px solid rgba(255,255,255,0.05)",
              paddingTop: "20px",
              paddingBottom: "10px",
            }}
          >
            <h4
              style={{
                margin: "0 0 15px 0",
                fontSize: "13px",
                textTransform: "uppercase",
                letterSpacing: "1px",
                color: headingColor,
              }}
            >
              Minimum Rating
            </h4>
            <div
              style={{ display: "flex", flexDirection: "column", gap: "10px" }}
            >
              {[5, 4, 3].map((stars) => (
                <div
                  key={stars}
                  style={{
                    display: "flex",
                    alignItems: "center",
                    gap: "10px",
                    fontSize: "14px",
                    cursor: "pointer",
                    opacity: stars === 3 ? 0.5 : 1,
                  }}
                >
                  <span style={{ color: "#f59e0b" }}>
                    {"★".repeat(stars)}
                    {"☆".repeat(5 - stars)}
                  </span>
                  <span style={{ fontSize: "12px" }}>& Up</span>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </>
  );
}
