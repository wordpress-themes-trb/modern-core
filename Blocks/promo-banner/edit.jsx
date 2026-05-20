import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  ColorPalette,
  TextControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    bannerId,
    layoutMode,
    bgMode,
    color1,
    color2,
    textColor,
    isSticky,
    attentionPulse,
    enableDismiss,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-promo-editor layout-${layoutMode}`,
    style: {
      width: layoutMode === "full" ? "100%" : "90%",
      maxWidth: layoutMode === "full" ? "none" : "1200px",
      margin: layoutMode === "full" ? "0" : "20px auto",
      borderRadius:
        layoutMode === "contained"
          ? "8px"
          : layoutMode === "floating"
            ? "16px"
            : "0",
      boxShadow:
        layoutMode === "floating" ? "0 15px 35px rgba(0,0,0,0.15)" : "none",
      background:
        bgMode === "solid"
          ? color1
          : `linear-gradient(90deg, ${color1}, ${color2})`,
      color: textColor,
      position: "relative",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      padding: "12px 20px",
      overflow: "hidden",
    },
  });

  // The Master Default Template for the Promo Row
  const DEFAULT_TEMPLATE = [
    [
      "core/group",
      {
        layout: {
          type: "flex",
          flexWrap: "wrap",
          justifyContent: "center",
          orientation: "horizontal",
        },
        style: { spacing: { blockGap: "15px" } },
      },
      [
        [
          "core/paragraph",
          { content: "⚡", style: { typography: { fontSize: "18px" } } },
        ],
        [
          "core/paragraph",
          {
            content: "FLASH SALE:",
            style: {
              typography: {
                fontWeight: "700",
                fontSize: "14px",
                letterSpacing: "1px",
              },
            },
          },
        ],
        [
          "core/paragraph",
          {
            content: "Take 20% off all modern architecture collections.",
            style: { typography: { fontSize: "14px" } },
          },
        ],
        [
          "modern-core/countdown",
          {
            styleMode: "minimal",
            digitSize: 16,
            labelDays: "d",
            labelHours: "h",
            labelMins: "m",
            labelSecs: "s",
          },
        ],
        [
          "core/buttons",
          {},
          [
            [
              "core/button",
              {
                text: "Shop Sale",
                className: "is-style-outline",
                style: {
                  border: { radius: "50px" },
                  typography: { fontSize: "12px", fontWeight: "bold" },
                },
              },
            ],
          ],
        ],
      ],
    ],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Banner Physics & Layout", "modern-core")}>
          <TextControl
            label={__("Unique Banner ID", "modern-core")}
            value={bannerId}
            onChange={(val) => setAttributes({ bannerId: val })}
            help={__(
              "Used to track dismissal cookies so it stays hidden for returning users.",
              "modern-core",
            )}
          />
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Full Width Strip", value: "full" },
              { label: "Contained Card", value: "contained" },
              { label: "Floating Elevated Card", value: "floating" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <ToggleControl
            label={__("Sticky (Stays at top on scroll)", "modern-core")}
            checked={isSticky}
            onChange={(val) => setAttributes({ isSticky: val })}
          />
          <ToggleControl
            label={__("Enable Dismiss Button (X)", "modern-core")}
            checked={enableDismiss}
            onChange={(val) => setAttributes({ enableDismiss: val })}
          />
          <ToggleControl
            label={__("Attention Pulse Animation", "modern-core")}
            checked={attentionPulse}
            onChange={(val) => setAttributes({ attentionPulse: val })}
            help={__(
              "A gentle breathing effect to draw the eye.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Background Engine", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Background Style", "modern-core")}
            value={bgMode}
            options={[
              { label: "Animated Gradient Shift", value: "animated-gradient" },
              { label: "Static Gradient", value: "gradient" },
              { label: "Solid Color", value: "solid" },
            ]}
            onChange={(val) => setAttributes({ bgMode: val })}
          />
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Primary Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={color1}
            onChange={(val) => setAttributes({ color1: val })}
          />
          {bgMode !== "solid" && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>
                  {__("Secondary Color (Gradient)", "modern-core")}
                </strong>
              </p>
              <ColorPalette
                value={color2}
                onChange={(val) => setAttributes({ color2: val })}
              />
            </>
          )}
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
        <InnerBlocks
          template={DEFAULT_TEMPLATE}
          allowedBlocks={[
            "core/group",
            "core/paragraph",
            "core/heading",
            "core/button",
            "core/buttons",
            "modern-core/countdown",
          ]}
        />
        {enableDismiss && (
          <span
            style={{
              position: "absolute",
              right: "15px",
              top: "50%",
              transform: "translateY(-50%)",
              opacity: 0.5,
              cursor: "pointer",
            }}
          >
            ✕
          </span>
        )}
      </div>
    </>
  );
}
