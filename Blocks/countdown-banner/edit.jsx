import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  TextControl,
  ColorPalette,
  ToggleControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    targetDate,
    layoutMode,
    timerStyle,
    bgMode,
    color1,
    color2,
    digitColor,
    boxColor,
    expireAction,
    expireMessage,
    expireUrl,
    showDays,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-countdown-banner-editor layout-${layoutMode} style-${timerStyle}`,
    style: {
      width: "100%",
      background:
        bgMode === "gradient"
          ? `linear-gradient(135deg, ${color1}, ${color2})`
          : color1,
      padding: layoutMode === "strip" ? "20px 5%" : "60px 5%",
      display: "flex",
      flexDirection: layoutMode === "split" ? "row" : "column",
      alignItems: "center",
      justifyContent: layoutMode === "split" ? "space-between" : "center",
      gap: "40px",
      borderRadius: layoutMode === "card" ? "16px" : "0",
      boxShadow:
        layoutMode === "card" ? "0 25px 50px -12px rgba(0,0,0,0.5)" : "none",
      color: "#fff",
      margin: layoutMode === "card" ? "40px auto" : "0",
      maxWidth: layoutMode === "card" ? "1000px" : "none",
    },
  });

  const DEFAULT_TEMPLATE = [
    [
      "core/paragraph",
      {
        content: "⚡ FLASH EVENT",
        style: {
          typography: {
            fontSize: "12px",
            fontWeight: "bold",
            letterSpacing: "2px",
          },
          color: { text: "#fca5a5" },
        },
      },
    ],
    [
      "core/heading",
      {
        level: 2,
        content: "Time is running out.",
        style: { typography: { fontSize: "36px", fontWeight: "800" } },
      },
    ],
    [
      "core/paragraph",
      {
        content: "Secure your items before the vault closes permanently.",
        style: {
          typography: { fontSize: "16px" },
          spacing: { margin: { bottom: "20px" } },
        },
      },
    ],
    [
      "core/buttons",
      {},
      [
        [
          "core/button",
          { text: "Shop Now", style: { border: { radius: "50px" } } },
        ],
      ],
    ],
  ];

  // Mock Timer UI
  const TimerMock = () => (
    <div
      style={{
        display: "flex",
        gap: "15px",
        textShadow:
          timerStyle === "neon"
            ? `0 0 10px ${digitColor}, 0 0 20px ${digitColor}`
            : "none",
      }}
    >
      {showDays && <Unit digit="02" label="Days" />}
      <Unit digit="14" label="Hours" />
      <Unit digit="30" label="Mins" />
      <Unit digit="45" label="Secs" />
    </div>
  );

  const Unit = ({ digit, label }) => (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        background:
          timerStyle === "boxed" || timerStyle === "flip"
            ? boxColor
            : "transparent",
        padding:
          timerStyle === "boxed" || timerStyle === "flip" ? "15px 20px" : "0",
        borderRadius: "8px",
        border:
          timerStyle === "flip" ? "1px solid rgba(255,255,255,0.1)" : "none",
        position: "relative",
        minWidth: "80px",
      }}
    >
      <span
        style={{
          fontSize: "42px",
          fontWeight: "800",
          color: digitColor,
          lineHeight: 1,
        }}
      >
        {digit}
      </span>
      <span
        style={{
          fontSize: "11px",
          textTransform: "uppercase",
          letterSpacing: "1px",
          marginTop: "5px",
          opacity: 0.8,
        }}
      >
        {label}
      </span>
      {timerStyle === "flip" && (
        <div
          style={{
            position: "absolute",
            top: "50%",
            left: 0,
            right: 0,
            height: "1px",
            background: "rgba(0,0,0,0.5)",
          }}
        ></div>
      )}
    </div>
  );

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Banner Architecture", "modern-core")}>
          <SelectControl
            label={__("Macro Layout", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Centered Card (Elevated)", value: "card" },
              { label: "Split (Content Left, Timer Right)", value: "split" },
              { label: "Full-Width Strip", value: "strip" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <SelectControl
            label={__("Advanced Timer Style", "modern-core")}
            value={timerStyle}
            options={[
              { label: "Solid Boxed", value: "boxed" },
              { label: "Mechanical Flip Card", value: "flip" },
              { label: "Glowing Neon", value: "neon" },
              { label: "Minimal Text", value: "minimal" },
            ]}
            onChange={(val) => setAttributes({ timerStyle: val })}
          />
          <ToggleControl
            label={__("Show Days Unit", "modern-core")}
            checked={showDays}
            onChange={(val) => setAttributes({ showDays: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Time & Actions", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            type="datetime-local"
            label={__("Target Date & Time", "modern-core")}
            value={targetDate}
            onChange={(val) => setAttributes({ targetDate: val })}
          />
          <SelectControl
            label={__("On Expiration:", "modern-core")}
            value={expireAction}
            options={[
              { label: "Hide Entire Banner", value: "hide" },
              { label: "Show Custom Message", value: "message" },
              { label: "Redirect Page", value: "redirect" },
              { label: "Stop at Zeros", value: "stop" },
            ]}
            onChange={(val) => setAttributes({ expireAction: val })}
          />
          {expireAction === "message" && (
            <TextControl
              label={__("Expiration Message", "modern-core")}
              value={expireMessage}
              onChange={(val) => setAttributes({ expireMessage: val })}
            />
          )}
          {expireAction === "redirect" && (
            <TextControl
              label={__("Redirect URL", "modern-core")}
              value={expireUrl}
              onChange={(val) => setAttributes({ expireUrl: val })}
            />
          )}
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <SelectControl
            label={__("Background Type", "modern-core")}
            value={bgMode}
            options={[
              { label: "Vivid Gradient", value: "gradient" },
              { label: "Solid Color", value: "solid" },
            ]}
            onChange={(val) => setAttributes({ bgMode: val })}
          />
          <ColorPalette
            value={color1}
            onChange={(val) => setAttributes({ color1: val })}
          />
          {bgMode === "gradient" && (
            <ColorPalette
              value={color2}
              onChange={(val) => setAttributes({ color2: val })}
            />
          )}

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Digit / Neon Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={digitColor}
            onChange={(val) => setAttributes({ digitColor: val })}
          />
          {(timerStyle === "boxed" || timerStyle === "flip") && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Box Background", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={boxColor}
                onChange={(val) => setAttributes({ boxColor: val })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            flex: layoutMode === "split" ? "1" : "none",
            textAlign: layoutMode === "split" ? "left" : "center",
            width: "100%",
            maxWidth: "600px",
          }}
        >
          <InnerBlocks template={DEFAULT_TEMPLATE} />
        </div>
        <div
          style={{
            flex: layoutMode === "split" ? "1" : "none",
            display: "flex",
            justifyContent: layoutMode === "split" ? "flex-end" : "center",
          }}
        >
          <TimerMock />
        </div>
      </div>
    </>
  );
}
