import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  TextControl,
  RangeControl,
  ToggleControl,
  SelectControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    item1,
    item2,
    item3,
    item4,
    iconSize,
    fontSize,
    gap,
    showDividers,
    textColor,
    iconColor,
    alignment,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-trust-strip-editor",
    style: {
      display: "flex",
      flexWrap: "wrap",
      justifyContent:
        alignment === "center"
          ? "center"
          : alignment === "right"
            ? "flex-end"
            : "flex-start",
      gap: `${gap}px`,
      color: textColor !== "inherit" ? textColor : undefined,
      opacity: 0.8,
      width: "100%",
    },
  });

  // Helper to render the inline divider
  const Divider = () =>
    showDividers ? (
      <span style={{ opacity: 0.3, userSelect: "none" }}>|</span>
    ) : null;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Trust Signals (Labels)", "modern-core")}>
          <TextControl
            label={__("Signal 1 (Shipping)", "modern-core")}
            value={item1}
            onChange={(val) => setAttributes({ item1: val })}
          />
          <TextControl
            label={__("Signal 2 (Returns)", "modern-core")}
            value={item2}
            onChange={(val) => setAttributes({ item2: val })}
          />
          <TextControl
            label={__("Signal 3 (Security)", "modern-core")}
            value={item3}
            onChange={(val) => setAttributes({ item3: val })}
          />
          <TextControl
            label={__("Signal 4 (Support)", "modern-core")}
            value={item4}
            onChange={(val) => setAttributes({ item4: val })}
          />
          <p style={{ fontSize: "12px", opacity: 0.6 }}>
            Leave any input blank to hide that specific signal.
          </p>
        </PanelBody>

        <PanelBody
          title={__("Design & Layout", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Alignment", "modern-core")}
            value={alignment}
            options={[
              { label: "Left", value: "left" },
              { label: "Center", value: "center" },
              { label: "Right", value: "right" },
            ]}
            onChange={(val) => setAttributes({ alignment: val })}
          />
          <ToggleControl
            label={__("Show Separator Lines", "modern-core")}
            checked={showDividers}
            onChange={(val) => setAttributes({ showDividers: val })}
          />
          <RangeControl
            label={__("Gap Between Items (px)", "modern-core")}
            value={gap}
            onChange={(val) => setAttributes({ gap: val })}
            min={10}
            max={60}
          />
          <RangeControl
            label={__("Icon Size (px)", "modern-core")}
            value={iconSize}
            onChange={(val) => setAttributes({ iconSize: val })}
            min={12}
            max={32}
          />
          <RangeControl
            label={__("Text Size (px)", "modern-core")}
            value={fontSize}
            onChange={(val) => setAttributes({ fontSize: val })}
            min={10}
            max={18}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Icon Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={iconColor}
            onChange={(val) => setAttributes({ iconColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {item1 && (
          <div style={{ display: "flex", alignItems: "center", gap: "8px" }}>
            <svg
              style={{ color: iconColor }}
              width={iconSize}
              height={iconSize}
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
              <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
              <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span style={{ fontSize: `${fontSize}px`, fontWeight: "500" }}>
              {item1}
            </span>
            {showDividers && (item2 || item3 || item4) && <Divider />}
          </div>
        )}
        {item2 && (
          <div style={{ display: "flex", alignItems: "center", gap: "8px" }}>
            <svg
              style={{ color: iconColor }}
              width={iconSize}
              height={iconSize}
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <polyline points="1 4 1 10 7 10"></polyline>
              <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
            </svg>
            <span style={{ fontSize: `${fontSize}px`, fontWeight: "500" }}>
              {item2}
            </span>
            {showDividers && (item3 || item4) && <Divider />}
          </div>
        )}
        {item3 && (
          <div style={{ display: "flex", alignItems: "center", gap: "8px" }}>
            <svg
              style={{ color: iconColor }}
              width={iconSize}
              height={iconSize}
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span style={{ fontSize: `${fontSize}px`, fontWeight: "500" }}>
              {item3}
            </span>
            {showDividers && item4 && <Divider />}
          </div>
        )}
        {item4 && (
          <div style={{ display: "flex", alignItems: "center", gap: "8px" }}>
            <svg
              style={{ color: iconColor }}
              width={iconSize}
              height={iconSize}
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            <span style={{ fontSize: `${fontSize}px`, fontWeight: "500" }}>
              {item4}
            </span>
          </div>
        )}
      </div>
    </>
  );
}
