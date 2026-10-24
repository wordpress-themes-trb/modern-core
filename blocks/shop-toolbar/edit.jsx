import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  RangeControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    showCount,
    showSort,
    showViewToggles,
    showFilterTrigger,
    borderBottom,
    paddingVert,
    textColor,
    accentColor,
    borderColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-toolbar-editor",
    style: {
      width: "100%",
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
      padding: `${paddingVert}px 0`,
      borderBottom: borderBottom ? `1px solid ${borderColor}` : "none",
      color: textColor,
      fontSize: "13px",
      backgroundColor: "#ffffff",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Toolbar Elements", "modern-core")}>
          <ToggleControl
            label={__("Show Product Count", "modern-core")}
            checked={showCount}
            onChange={(val) => setAttributes({ showCount: val })}
          />
          <ToggleControl
            label={__("Show Sort Dropdown", "modern-core")}
            checked={showSort}
            onChange={(val) => setAttributes({ showSort: val })}
          />
          <ToggleControl
            label={__("Show Grid/List Toggles", "modern-core")}
            checked={showViewToggles}
            onChange={(val) => setAttributes({ showViewToggles: val })}
            help={__(
              "Allows visitors to switch between grid and list layouts.",
              "modern-core",
            )}
          />
          <ToggleControl
            label={__("Show Filter Trigger (Mobile)", "modern-core")}
            checked={showFilterTrigger}
            onChange={(val) => setAttributes({ showFilterTrigger: val })}
            help={__("Opens the off-canvas filter drawer.", "modern-core")}
          />
        </PanelBody>

        <PanelBody
          title={__("Architecture & Colors", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Bottom Border", "modern-core")}
            checked={borderBottom}
            onChange={(val) => setAttributes({ borderBottom: val })}
          />
          <RangeControl
            label={__("Vertical Padding (px)", "modern-core")}
            value={paddingVert}
            onChange={(val) => setAttributes({ paddingVert: val })}
            min={0}
            max={40}
          />

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>
              {__("Brand Accent (Active Elements)", "modern-core")}
            </strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Border Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={borderColor}
            onChange={(val) => setAttributes({ borderColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Left Group */}
        <div style={{ display: "flex", alignItems: "center", gap: "20px" }}>
          {showFilterTrigger && (
            <div
              style={{
                display: "flex",
                alignItems: "center",
                gap: "5px",
                fontWeight: "bold",
                color: accentColor,
                cursor: "pointer",
              }}
            >
              <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
              >
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
              </svg>
              Filter
            </div>
          )}
          {showCount && <span>Showing 1–24 of 148 results</span>}
        </div>

        {/* Right Group */}
        <div style={{ display: "flex", alignItems: "center", gap: "20px" }}>
          {showSort && (
            <div style={{ display: "flex", alignItems: "center", gap: "10px" }}>
              <span style={{ fontWeight: "bold" }}>Sort by:</span>
              <div
                style={{
                  border: `1px solid ${borderColor}`,
                  padding: "6px 12px",
                  borderRadius: "4px",
                  display: "flex",
                  alignItems: "center",
                  gap: "10px",
                }}
              >
                Default sorting <span style={{ fontSize: "10px" }}>▼</span>
              </div>
            </div>
          )}

          {showViewToggles && (
            <div style={{ display: "flex", gap: "5px" }}>
              {/* Grid Icon (Active) */}
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill={accentColor}
                stroke={accentColor}
                strokeWidth="2"
              >
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
              </svg>
              {/* List Icon (Inactive) */}
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke={textColor}
                strokeWidth="2"
                style={{ opacity: 0.5 }}
              >
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                <line x1="3" y1="18" x2="3.01" y2="18"></line>
              </svg>
            </div>
          )}
        </div>
      </div>
    </>
  );
}
