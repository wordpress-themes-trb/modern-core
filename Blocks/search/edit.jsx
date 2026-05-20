import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  TextControl,
  SelectControl,
  ToggleControl,
  RangeControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    placeholder,
    shape,
    showIcon,
    iconPosition,
    bgColor,
    textColor,
    width,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-search-block",
    style: { width: `${width}%`, position: "relative" },
  });

  const borderRadius =
    shape === "pill" ? "50px" : shape === "rounded" ? "8px" : "0px";
  const isIconLeft = iconPosition === "left";

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Search Settings", "modern-core")}>
          <TextControl
            label={__("Placeholder Text", "modern-core")}
            value={placeholder}
            onChange={(val) => setAttributes({ placeholder: val })}
          />
          <SelectControl
            label={__("Input Shape", "modern-core")}
            value={shape}
            options={[
              { label: "Rounded Pill", value: "pill" },
              { label: "Soft Rectangle", value: "rounded" },
              { label: "Sharp Rectangle", value: "sharp" },
            ]}
            onChange={(val) => setAttributes({ shape: val })}
          />
          <RangeControl
            label={__("Width (%)", "modern-core")}
            value={width}
            onChange={(val) => setAttributes({ width: val })}
            min={20}
            max={100}
          />
        </PanelBody>

        <PanelBody
          title={__("Icon & Colors", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Search Icon", "modern-core")}
            checked={showIcon}
            onChange={(val) => setAttributes({ showIcon: val })}
          />
          {showIcon && (
            <SelectControl
              label={__("Icon Position", "modern-core")}
              value={iconPosition}
              options={[
                { label: "Left", value: "left" },
                { label: "Right", value: "right" },
              ]}
              onChange={(val) => setAttributes({ iconPosition: val })}
            />
          )}
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Background Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={bgColor}
            onChange={(val) => setAttributes({ bgColor: val })}
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
        <div
          style={{
            display: "flex",
            alignItems: "center",
            backgroundColor: bgColor,
            borderRadius: borderRadius,
            padding: "8px 16px",
            border: "1px solid transparent",
          }}
        >
          {showIcon && isIconLeft && (
            <span
              style={{
                color: textColor,
                opacity: 0.6,
                marginRight: "10px",
                display: "flex",
              }}
            >
              🔍
            </span>
          )}

          <input
            type="text"
            placeholder={placeholder}
            readOnly
            style={{
              border: "none",
              background: "transparent",
              outline: "none",
              width: "100%",
              color: textColor,
              fontSize: "14px",
              cursor: "default",
            }}
          />

          {showIcon && !isIconLeft && (
            <span
              style={{
                color: textColor,
                opacity: 0.6,
                marginLeft: "10px",
                display: "flex",
              }}
            >
              🔍
            </span>
          )}
        </div>

        {/* Simulated Live Suggestion Dropdown for Editor Preview */}
        <div
          style={{
            position: "absolute",
            top: "100%",
            left: 0,
            right: 0,
            marginTop: "8px",
            backgroundColor: "#fff",
            borderRadius: "8px",
            boxShadow: "0 10px 25px rgba(0,0,0,0.1)",
            padding: "15px",
            zIndex: 10,
            display: "none", // Hidden by default in editor to avoid clutter
          }}
          className="mc-search-dropdown-preview"
        >
          <p
            style={{
              fontSize: "11px",
              textTransform: "uppercase",
              color: "#94a3b8",
              margin: "0 0 10px 0",
            }}
          >
            Trending Searches
          </p>
          <div style={{ display: "flex", gap: "8px", flexWrap: "wrap" }}>
            <span
              style={{
                background: "#f1f5f9",
                padding: "4px 10px",
                borderRadius: "20px",
                fontSize: "12px",
              }}
            >
              Summer Dress
            </span>
            <span
              style={{
                background: "#f1f5f9",
                padding: "4px 10px",
                borderRadius: "20px",
                fontSize: "12px",
              }}
            >
              Leather Jacket
            </span>
          </div>
        </div>
      </div>
    </>
  );
}
