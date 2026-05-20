import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  TextControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const { text, speed, isScrolling, bgColor, textColor } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-marquee-block",
    style: {
      backgroundColor: bgColor,
      color: textColor,
      overflow: "hidden",
      whiteSpace: "nowrap",
      display: "flex",
      alignItems: "center",
      padding: "10px 0",
      fontSize: "0.85rem",
      textTransform: "uppercase",
      letterSpacing: "1px",
      fontWeight: "600",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Marquee Settings", "modern-core")}>
          <TextControl
            label={__("Announcement Text", "modern-core")}
            value={text}
            onChange={(val) => setAttributes({ text: val })}
          />
          <ToggleControl
            label={__("Enable Scrolling", "modern-core")}
            checked={isScrolling}
            onChange={(val) => setAttributes({ isScrolling: val })}
          />
          {isScrolling && (
            <RangeControl
              label={__("Animation Speed (Seconds)", "modern-core")}
              value={speed}
              onChange={(val) => setAttributes({ speed: val })}
              min={5}
              max={40}
              help={__("Lower is faster. Higher is slower.", "modern-core")}
            />
          )}
        </PanelBody>
        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginBottom: "5px" }}>
            <strong>Background Color</strong>
          </p>
          <ColorPalette
            value={bgColor}
            onChange={(val) => setAttributes({ bgColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>Text Color</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      {/* Dynamic Animation CSS */}
      <style>
        {`
                @keyframes modernCoreMarquee {
                    0% { transform: translateX(100%); }
                    100% { transform: translateX(-100%); }
                }
                .is-scrolling-editor {
                    animation: modernCoreMarquee ${speed}s linear infinite;
                    min-width: 100%;
                }
                `}
      </style>

      <div {...blockProps}>
        <div
          className={isScrolling ? "is-scrolling-editor" : ""}
          style={{ width: "100%", textAlign: isScrolling ? "left" : "center" }}
        >
          {text}
        </div>
      </div>
    </>
  );
}
