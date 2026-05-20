import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    placementMode,
    filters,
    showCounts,
    showActiveChips,
    chipBgColor,
    chipTextColor,
    headingColor,
    accentColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-filter-editor placement-${placementMode}`,
    style: {
      width: placementMode.includes("sidebar") ? "300px" : "100%",
      padding: "20px",
      backgroundColor: "#ffffff",
      border: "1px dashed #cbd5e1",
      borderRadius: "8px",
    },
  });

  const mockActiveFilters = [
    { label: "Color: Black" },
    { label: "$50 - $150" },
  ];

  const renderFilterGroup = (filter, index) => {
    return (
      <div
        key={index}
        style={{
          marginBottom: "25px",
          borderBottom: "1px solid #e2e8f0",
          paddingBottom: "20px",
        }}
      >
        <div
          style={{
            display: "flex",
            justifyContent: "space-between",
            alignItems: "center",
            marginBottom: "15px",
            cursor: "pointer",
          }}
        >
          <h4
            style={{
              margin: 0,
              fontSize: "13px",
              textTransform: "uppercase",
              letterSpacing: "1px",
              fontWeight: "bold",
              color: headingColor,
            }}
          >
            {filter.label}
          </h4>
          <span
            style={{
              fontSize: "12px",
              transform: filter.expanded ? "rotate(180deg)" : "none",
              transition: "transform 0.2s",
            }}
          >
            ▼
          </span>
        </div>

        {filter.expanded && (
          <div style={{ marginTop: "10px" }}>
            {filter.type === "price" && (
              <div style={{ padding: "10px 0" }}>
                <div
                  style={{
                    width: "100%",
                    height: "4px",
                    backgroundColor: "#e2e8f0",
                    borderRadius: "2px",
                    position: "relative",
                  }}
                >
                  <div
                    style={{
                      position: "absolute",
                      left: "20%",
                      right: "30%",
                      height: "100%",
                      backgroundColor: accentColor,
                      borderRadius: "2px",
                    }}
                  ></div>
                  <div
                    style={{
                      position: "absolute",
                      left: "20%",
                      top: "50%",
                      transform: "translate(-50%, -50%)",
                      width: "16px",
                      height: "16px",
                      backgroundColor: "#fff",
                      border: `2px solid ${accentColor}`,
                      borderRadius: "50%",
                    }}
                  ></div>
                  <div
                    style={{
                      position: "absolute",
                      right: "30%",
                      top: "50%",
                      transform: "translate(50%, -50%)",
                      width: "16px",
                      height: "16px",
                      backgroundColor: "#fff",
                      border: `2px solid ${accentColor}`,
                      borderRadius: "50%",
                    }}
                  ></div>
                </div>
                <div
                  style={{
                    display: "flex",
                    justifyContent: "space-between",
                    marginTop: "15px",
                    fontSize: "13px",
                    color: "#64748b",
                  }}
                >
                  <span>$50</span>
                  <span>$150</span>
                </div>
              </div>
            )}

            {filter.type === "color" && (
              <div style={{ display: "flex", gap: "10px", flexWrap: "wrap" }}>
                {["#000000", "#ffffff", "#3b82f6", "#ef4444", "#10b981"].map(
                  (c, i) => (
                    <div
                      key={i}
                      style={{
                        width: "24px",
                        height: "24px",
                        borderRadius: "50%",
                        backgroundColor: c,
                        border: c === "#ffffff" ? "1px solid #cbd5e1" : "none",
                        boxShadow:
                          i === 0
                            ? `0 0 0 2px #fff, 0 0 0 4px ${accentColor}`
                            : "none",
                      }}
                    ></div>
                  ),
                )}
              </div>
            )}

            {filter.type === "pill" && (
              <div style={{ display: "flex", gap: "8px", flexWrap: "wrap" }}>
                {["S", "M", "L", "XL"].map((s, i) => (
                  <div
                    key={i}
                    style={{
                      padding: "6px 12px",
                      fontSize: "13px",
                      borderRadius: "4px",
                      border:
                        i === 1
                          ? `1px solid ${accentColor}`
                          : "1px solid #e2e8f0",
                      backgroundColor: i === 1 ? accentColor : "transparent",
                      color: i === 1 ? "#fff" : "#0f172a",
                      fontWeight: i === 1 ? "bold" : "normal",
                    }}
                  >
                    {s}
                  </div>
                ))}
              </div>
            )}

            {filter.type === "checkbox" && (
              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  gap: "10px",
                }}
              >
                {["Clothing", "Accessories", "Shoes"].map((c, i) => (
                  <div
                    key={i}
                    style={{
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "space-between",
                    }}
                  >
                    <div
                      style={{
                        display: "flex",
                        alignItems: "center",
                        gap: "10px",
                      }}
                    >
                      <input
                        type="checkbox"
                        checked={i === 0}
                        readOnly
                        style={{ accentColor: accentColor }}
                      />
                      <span style={{ fontSize: "14px", color: "#0f172a" }}>
                        {c}
                      </span>
                    </div>
                    {showCounts && (
                      <span style={{ fontSize: "12px", color: "#94a3b8" }}>
                        (24)
                      </span>
                    )}
                  </div>
                ))}
              </div>
            )}
          </div>
        )}
      </div>
    );
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Placement & Architecture", "modern-core")}>
          <SelectControl
            label={__("Placement Mode", "modern-core")}
            value={placementMode}
            options={[
              { label: "Sidebar Left (Inline)", value: "sidebar-left" },
              { label: "Sidebar Right (Inline)", value: "sidebar-right" },
              { label: "Horizontal Bar (Top)", value: "horizontal" },
            ]}
            onChange={(val) => setAttributes({ placementMode: val })}
          />
          <ToggleControl
            label={__("Show Product Counts", "modern-core")}
            checked={showCounts}
            onChange={(val) => setAttributes({ showCounts: val })}
          />
          <ToggleControl
            label={__("Show Active Filter Chips", "modern-core")}
            checked={showActiveChips}
            onChange={(val) => setAttributes({ showActiveChips: val })}
          />
          <p style={{ fontSize: "12px", opacity: 0.6 }}>
            Filter groups (Price, Color, Size, etc.) are defined in the
            block.json for this demo to ensure stability.
          </p>
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Heading Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={headingColor}
            onChange={(val) => setAttributes({ headingColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Active Chip Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={chipBgColor}
            onChange={(val) => setAttributes({ chipBgColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Active Chip Text", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={chipTextColor}
            onChange={(val) => setAttributes({ chipTextColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {showActiveChips && (
          <div style={{ marginBottom: "25px" }}>
            <div
              style={{
                display: "flex",
                alignItems: "center",
                justifyContent: "space-between",
                marginBottom: "10px",
              }}
            >
              <span
                style={{
                  fontSize: "13px",
                  fontWeight: "bold",
                  color: headingColor,
                }}
              >
                Active Filters
              </span>
              <span
                style={{
                  fontSize: "12px",
                  color: "#ef4444",
                  cursor: "pointer",
                  textDecoration: "underline",
                }}
              >
                Clear All
              </span>
            </div>
            <div style={{ display: "flex", gap: "10px", flexWrap: "wrap" }}>
              {mockActiveFilters.map((f, i) => (
                <div
                  key={i}
                  style={{
                    display: "inline-flex",
                    alignItems: "center",
                    gap: "8px",
                    padding: "6px 12px",
                    backgroundColor: chipBgColor,
                    color: chipTextColor,
                    borderRadius: "50px",
                    fontSize: "12px",
                    fontWeight: "bold",
                  }}
                >
                  {f.label}{" "}
                  <span style={{ opacity: 0.5, cursor: "pointer" }}>✕</span>
                </div>
              ))}
            </div>
          </div>
        )}

        {filters.map((filter, index) => renderFilterGroup(filter, index))}
      </div>
    </>
  );
}
