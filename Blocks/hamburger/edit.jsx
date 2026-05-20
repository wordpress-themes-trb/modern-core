import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    drawerType,
    drawerWidth,
    drawerBg,
    overlayBlur,
    iconColor,
    iconSize,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-hamburger-block",
    style: {
      width: "100%",
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Drawer Behavior", "modern-core")}>
          <SelectControl
            label={__("Opening Style", "modern-core")}
            value={drawerType}
            options={[
              { label: "Slide in from Right", value: "slide-right" },
              { label: "Slide in from Left", value: "slide-left" },
              { label: "Fullscreen Overlay", value: "fullscreen" },
            ]}
            onChange={(val) => setAttributes({ drawerType: val })}
          />
          {drawerType !== "fullscreen" && (
            <RangeControl
              label={__("Drawer Width (px)", "modern-core")}
              value={drawerWidth}
              onChange={(val) => setAttributes({ drawerWidth: val })}
              min={250}
              max={600}
            />
          )}
          <RangeControl
            label={__("Background Overlay Blur (px)", "modern-core")}
            value={overlayBlur}
            onChange={(val) => setAttributes({ overlayBlur: val })}
            min={0}
            max={20}
          />
        </PanelBody>

        <PanelBody
          title={__("Colors & Styling", "modern-core")}
          initialOpen={false}
        >
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Icon Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={iconColor}
            onChange={(val) => setAttributes({ iconColor: val })}
          />

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Drawer Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={drawerBg}
            onChange={(val) => setAttributes({ drawerBg: val })}
          />

          <RangeControl
            label={__("Icon Size (px)", "modern-core")}
            value={iconSize}
            onChange={(val) => setAttributes({ iconSize: val })}
            min={20}
            max={50}
            style={{ marginTop: "15px" }}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* 1. The Trigger Icon */}
        <div
          style={{ color: iconColor, cursor: "pointer", marginBottom: "15px" }}
        >
          <svg
            width={iconSize}
            height={iconSize}
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
          >
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </div>

        {/* 2. The Drawer Content Dropzone (Only styled this way in the Editor) */}
        <div
          style={{
            width: drawerType === "fullscreen" ? "100%" : `${drawerWidth}px`,
            maxWidth: "100%",
            backgroundColor: drawerBg,
            border: "2px dashed #94a3b8",
            borderRadius: "8px",
            padding: "20px",
            position: "relative",
          }}
        >
          <div
            style={{
              position: "absolute",
              top: "-12px",
              left: "10px",
              background: "#fff",
              padding: "0 10px",
              fontSize: "11px",
              fontWeight: "bold",
              color: "#64748b",
              textTransform: "uppercase",
            }}
          >
            Drawer Content Area
          </div>

          {/* The magic InnerBlocks! Let the user build the menu here. */}
          <InnerBlocks
            template={[
              ["core/search", { showLabel: false, width: 100, widthUnit: "%" }],
              ["modern-core/navigation", {}], // Automatically drops your styled nav inside!
            ]}
          />
        </div>
      </div>
    </>
  );
}
