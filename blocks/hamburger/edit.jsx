import * as React from "@wordpress/element";
import { useState } from "@wordpress/element";
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
  ToggleControl,
  Button,
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

  // The state that prevents the modal from destroying your editor layout
  const [isEditingDrawer, setIsEditingDrawer] = useState(false);

  const blockProps = useBlockProps({
    className: "modern-core-hamburger-block",
    style: {
      position: "relative", // CRITICAL: Anchors the floating editor
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
    },
  });

  // Dynamic CSS to float the editor cleanly over the header
  const editorStyles = `
    .mc-drawer-editor-wrapper {
        display: none;
    }
    .mc-drawer-editor-wrapper.is-open {
        display: block;
        position: absolute;
        top: calc(100% + 15px);
        left: -10px;
        width: ${drawerType === "fullscreen" ? "350px" : `${Math.min(drawerWidth, 400)}px`};
        min-height: 200px;
        background: ${drawerBg};
        border: 2px dashed #3b82f6;
        padding: 20px;
        z-index: 999999;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        border-radius: 8px;
    }
    .mc-drawer-editor-wrapper.is-open::before {
        content: '';
        position: absolute;
        top: -12px;
        left: 20px;
        border-left: 12px solid transparent;
        border-right: 12px solid transparent;
        border-bottom: 12px solid #3b82f6;
    }
  `;

  return (
    <>
      <InspectorControls>
        <PanelBody
          title={__("Drawer Content", "modern-core")}
          initialOpen={true}
        >
          <ToggleControl
            label={__("✏️ Edit Drawer Content", "modern-core")}
            checked={isEditingDrawer}
            onChange={(val) => setIsEditingDrawer(val)}
            help={__(
              "Toggle this on to float the drawer editor over your header.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Drawer Behavior", "modern-core")}
          initialOpen={false}
        >
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
        <style>{editorStyles}</style>

        {/* 1. The Trigger Icon */}
        <div
          onClick={() => setIsEditingDrawer(!isEditingDrawer)}
          style={{
            color: iconColor,
            cursor: "pointer",
            display: "flex",
            padding: "5px",
            background: isEditingDrawer ? "rgba(0,0,0,0.05)" : "transparent",
            borderRadius: "4px",
          }}
          title="Click to edit drawer content"
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

        {/* 2. The Isolated Drawer Content Dropzone */}
        <div
          className={`mc-drawer-editor-wrapper ${isEditingDrawer ? "is-open" : ""}`}
        >
          <div
            style={{
              background: "#eff6ff",
              color: "#1d4ed8",
              padding: "8px 12px",
              marginBottom: "20px",
              borderRadius: "4px",
              fontSize: "11px",
              fontWeight: "bold",
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
            }}
          >
            <span>📱 DRAWER CONTENT AREA</span>
            <Button
              isSmall
              variant="tertiary"
              onClick={() => setIsEditingDrawer(false)}
              style={{ color: "#ef4444", minWidth: "auto", padding: "0" }}
            >
              Close
            </Button>
          </div>

          <InnerBlocks
            template={[
              ["core/search", { showLabel: false, width: 100, widthUnit: "%" }],
              ["modern-core/navigation", {}],
            ]}
            templateLock={false}
          />
        </div>
      </div>
    </>
  );
}
