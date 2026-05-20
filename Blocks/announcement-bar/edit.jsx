import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  TextControl,
  ToggleControl,
  ColorPalette,
  RangeControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    barId,
    displayMode,
    message1,
    message2,
    message3,
    couponCode,
    animationSpeed,
    bgColor,
    textColor,
    isSticky,
    enableDismiss,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-announcement-editor mode-${displayMode}`,
    style: {
      backgroundColor: bgColor,
      color: textColor,
      width: "100%",
      padding: "10px 40px",
      position: "relative",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      fontSize: "12px",
      fontWeight: "bold",
      letterSpacing: "1px",
      overflow: "hidden",
      whiteSpace: "nowrap",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Display Engine", "modern-core")}>
          <SelectControl
            label={__("Display Mode", "modern-core")}
            value={displayMode}
            options={[
              { label: "Single Static Message", value: "static" },
              { label: "Crossfade Rotator", value: "rotate" },
              { label: "Infinite Marquee Ticker", value: "marquee" },
            ]}
            onChange={(val) => setAttributes({ displayMode: val })}
          />
          <RangeControl
            label={__("Animation Speed / Delay", "modern-core")}
            value={animationSpeed}
            onChange={(val) => setAttributes({ animationSpeed: val })}
            min={2}
            max={30}
            help={__(
              "For Marquee: Lower is faster. For Rotate: Delay in seconds.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Messages & Content", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Message 1", "modern-core")}
            value={message1}
            onChange={(val) => setAttributes({ message1: val })}
          />
          {displayMode !== "static" && (
            <>
              <TextControl
                label={__("Message 2", "modern-core")}
                value={message2}
                onChange={(val) => setAttributes({ message2: val })}
              />
              <TextControl
                label={__("Message 3", "modern-core")}
                value={message3}
                onChange={(val) => setAttributes({ message3: val })}
              />
            </>
          )}
          <TextControl
            label={__("Coupon Code (Click to Copy)", "modern-core")}
            value={couponCode}
            onChange={(val) => setAttributes({ couponCode: val })}
            help={__("Leave blank to disable coupon.", "modern-core")}
          />
        </PanelBody>

        <PanelBody
          title={__("Settings & Colors", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Sticky on Scroll", "modern-core")}
            checked={isSticky}
            onChange={(val) => setAttributes({ isSticky: val })}
          />
          <ToggleControl
            label={__("Enable Dismiss (X)", "modern-core")}
            checked={enableDismiss}
            onChange={(val) => setAttributes({ enableDismiss: val })}
          />
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
        <div style={{ display: "flex", gap: "30px", alignItems: "center" }}>
          <span>{message1}</span>
          {displayMode !== "static" && message2 && <span>{message2}</span>}
          {displayMode !== "static" && message3 && <span>{message3}</span>}

          {couponCode && (
            <span
              style={{
                background: "rgba(255,255,255,0.15)",
                padding: "4px 10px",
                borderRadius: "4px",
                cursor: "pointer",
                border: "1px dashed rgba(255,255,255,0.4)",
              }}
            >
              {couponCode}
            </span>
          )}
        </div>
        {enableDismiss && (
          <span style={{ position: "absolute", right: "15px", opacity: 0.5 }}>
            ✕
          </span>
        )}
      </div>
    </>
  );
}
