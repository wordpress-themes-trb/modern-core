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
    trendingTags,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-search-block-editor",
    style: { width: `${width}%`, position: "relative", margin: "0 auto" },
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
          <TextControl
            label={__("Trending Search Tags (Comma separated)", "modern-core")}
            value={trendingTags}
            onChange={(val) => setAttributes({ trendingTags: val })}
            help="Displays as clickable suggestion chips when the search bar is focused."
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
            help="Set to 100% for fluid layouts (recommended)."
          />
        </PanelBody>

        <PanelBody
          title={__("Icon & Minimalist Colors", "modern-core")}
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
            padding: "10px 20px",
            border: "1px solid transparent",
          }}
        >
          {showIcon && isIconLeft && (
            <span
              style={{
                color: textColor,
                opacity: 0.6,
                marginRight: "12px",
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
              fontSize: "15px",
              cursor: "default",
            }}
          />

          {showIcon && !isIconLeft && (
            <span
              style={{
                color: textColor,
                opacity: 0.6,
                marginLeft: "12px",
                display: "flex",
              }}
            >
              🔍
            </span>
          )}
        </div>

        {/* Editor Preview Dropdown */}
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
            display: "none", // Hidden by default in the editor to avoid cluttering the view
          }}
        >
          <p
            style={{
              fontSize: "11px",
              textTransform: "uppercase",
              color: "#64748b",
              margin: "0 0 10px 0",
            }}
          >
            Trending Searches
          </p>
          <div style={{ display: "flex", gap: "8px", flexWrap: "wrap" }}>
            {trendingTags.split(",").map((tag, index) => {
              const cleanTag = tag.trim();
              if (!cleanTag) return null;
              return (
                <span
                  key={index}
                  style={{
                    background: "#f4f4f5",
                    padding: "4px 10px",
                    borderRadius: "20px",
                    fontSize: "12px",
                  }}
                >
                  {cleanTag}
                </span>
              );
            })}
          </div>
        </div>
      </div>
    </>
  );
}
