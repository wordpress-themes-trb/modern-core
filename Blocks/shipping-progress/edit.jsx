import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    threshold,
    msgPending,
    msgSuccess,
    barHeight,
    barRadius,
    trackColor,
    fillColor,
    successColor,
    textColor,
    useGradient,
  } = attributes;

  const [mockCartTotal, setMockCartTotal] = React.useState(45);

  const blockProps = useBlockProps({
    className: "modern-core-shipping-progress-editor",
    style: {
      width: "100%",
      padding: "30px 20px",
      backgroundColor: "#f8fafc",
      border: "1px dashed #cbd5e1",
      borderRadius: "8px",
    },
  });

  const isSuccess = mockCartTotal >= threshold;
  const progressPercent = Math.min((mockCartTotal / threshold) * 100, 100);
  const amountNeeded = threshold - mockCartTotal;

  // Replace the {amount} token with the mock currency
  const displayMsg = isSuccess
    ? msgSuccess
    : msgPending.replace("{amount}", `$${amountNeeded.toFixed(2)}`);

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Live Preview Tester", "modern-core")}>
          <RangeControl
            label={__("Mock Cart Total ($)", "modern-core")}
            value={mockCartTotal}
            onChange={setMockCartTotal}
            min={0}
            max={threshold + 20}
            help="Drag this to test how your messages look at different cart totals."
          />
        </PanelBody>

        <PanelBody
          title={__("Logic & Messaging", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Free Shipping Threshold ($)", "modern-core")}
            value={threshold}
            onChange={(val) => setAttributes({ threshold: val })}
            min={10}
            max={500}
          />
          <TextControl
            label={__("Pending Message", "modern-core")}
            value={msgPending}
            onChange={(val) => setAttributes({ msgPending: val })}
            help="Use {amount} to show the remaining value needed."
          />
          <TextControl
            label={__("Success Message", "modern-core")}
            value={msgSuccess}
            onChange={(val) => setAttributes({ msgSuccess: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Bar Architecture", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Bar Height (px)", "modern-core")}
            value={barHeight}
            onChange={(val) => setAttributes({ barHeight: val })}
            min={4}
            max={24}
          />
          <RangeControl
            label={__("Border Radius", "modern-core")}
            value={barRadius}
            onChange={(val) => setAttributes({ barRadius: val })}
            min={0}
            max={12}
          />
          <ToggleControl
            label={__("Use Gradient Fill", "modern-core")}
            checked={useGradient}
            onChange={(val) => setAttributes({ useGradient: val })}
            help="Smoothly transitions the fill color from Accent to Success as the bar fills."
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Base Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Track Background (Empty)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={trackColor}
            onChange={(val) => setAttributes({ trackColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Fill Color (Progress)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={fillColor}
            onChange={(val) => setAttributes({ fillColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Success Color (100%)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={successColor}
            onChange={(val) => setAttributes({ successColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .mc-spb-mock-text { font-size: 14px; font-weight: bold; margin-bottom: 12px; text-align: center; transition: color 0.3s; }
                    .mc-spb-mock-track { width: 100%; height: ${barHeight}px; background-color: ${trackColor}; border-radius: ${barRadius}px; overflow: hidden; }
                    .mc-spb-mock-fill { height: 100%; border-radius: ${barRadius}px; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.4s; }
                    `}
        </style>

        <div
          className="mc-spb-mock-text"
          style={{ color: isSuccess ? successColor : textColor }}
        >
          {displayMsg}
        </div>

        <div className="mc-spb-mock-track">
          <div
            className="mc-spb-mock-fill"
            style={{
              width: `${progressPercent}%`,
              background: isSuccess
                ? successColor
                : useGradient
                  ? `linear-gradient(90deg, ${fillColor}, ${successColor})`
                  : fillColor,
            }}
          ></div>
        </div>
      </div>
    </>
  );
}
