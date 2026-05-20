import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    targetDate,
    styleMode,
    digitSize,
    digitColor,
    labelColor,
    boxBg,
    boxRadius,
    labelDays,
    labelHours,
    labelMins,
    labelSecs,
    expireAction,
    expireMessage,
    expireUrl,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-countdown-editor style-${styleMode}`,
    style: {
      width: "100%",
      display: "flex",
      gap: styleMode === "minimal" ? "15px" : "10px",
    },
  });

  const isBoxed = styleMode === "boxed" || styleMode === "pill";
  const borderRadius = styleMode === "pill" ? "50px" : `${boxRadius}px`;

  const BlockUnit = ({ digit, label }) => (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "center",
        backgroundColor: isBoxed ? boxBg : "transparent",
        padding: isBoxed ? "15px 20px" : "0",
        borderRadius: isBoxed ? borderRadius : "0",
        minWidth: isBoxed ? "80px" : "auto",
        border: isBoxed ? "1px solid rgba(255,255,255,0.05)" : "none",
        backdropFilter: isBoxed ? "blur(10px)" : "none",
      }}
    >
      <span
        style={{
          fontSize: `${digitSize}px`,
          fontWeight: "700",
          lineHeight: 1,
          color: digitColor,
        }}
      >
        {digit}
      </span>
      <span
        style={{
          fontSize: `${Math.max(10, digitSize * 0.3)}px`,
          textTransform: "uppercase",
          letterSpacing: "1px",
          marginTop: "6px",
          color: labelColor,
        }}
      >
        {label}
      </span>
    </div>
  );

  const Separator = () =>
    styleMode === "minimal" ? (
      <span
        style={{
          fontSize: `${digitSize}px`,
          color: digitColor,
          opacity: 0.5,
          lineHeight: 1,
        }}
      >
        :
      </span>
    ) : null;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Timer Settings", "modern-core")}>
          <TextControl
            type="datetime-local"
            label={__("End Date & Time", "modern-core")}
            value={targetDate}
            onChange={(val) => setAttributes({ targetDate: val })}
            help={__("Leave blank to show 00:00:00:00.", "modern-core")}
          />
          <SelectControl
            label={__("Visual Style", "modern-core")}
            value={styleMode}
            options={[
              { label: "Solid Boxed", value: "boxed" },
              { label: "Rounded Pill", value: "pill" },
              { label: "Minimal (No Box, Colons)", value: "minimal" },
            ]}
            onChange={(val) => setAttributes({ styleMode: val })}
          />
          {isBoxed && (
            <RangeControl
              label={__("Box Border Radius", "modern-core")}
              value={boxRadius}
              onChange={(val) => setAttributes({ boxRadius: val })}
              min={0}
              max={30}
            />
          )}
          <RangeControl
            label={__("Digit Size (px)", "modern-core")}
            value={digitSize}
            onChange={(val) => setAttributes({ digitSize: val })}
            min={16}
            max={80}
          />
        </PanelBody>

        <PanelBody
          title={__("Expiration Action", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("When timer hits zero:", "modern-core")}
            value={expireAction}
            options={[
              { label: "Stop at Zero (00:00)", value: "stop" },
              { label: "Hide Timer & Show Message", value: "message" },
              { label: "Redirect to URL", value: "redirect" },
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

        <PanelBody
          title={__("Labels & Colors", "modern-core")}
          initialOpen={false}
        >
          <div style={{ display: "flex", gap: "10px", marginBottom: "15px" }}>
            <TextControl
              value={labelDays}
              onChange={(val) => setAttributes({ labelDays: val })}
              style={{ width: "100%" }}
            />
            <TextControl
              value={labelHours}
              onChange={(val) => setAttributes({ labelHours: val })}
              style={{ width: "100%" }}
            />
            <TextControl
              value={labelMins}
              onChange={(val) => setAttributes({ labelMins: val })}
              style={{ width: "100%" }}
            />
            <TextControl
              value={labelSecs}
              onChange={(val) => setAttributes({ labelSecs: val })}
              style={{ width: "100%" }}
            />
          </div>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Digit Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={digitColor}
            onChange={(val) => setAttributes({ digitColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Label Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={labelColor}
            onChange={(val) => setAttributes({ labelColor: val })}
          />
          {isBoxed && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Box Background", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={boxBg}
                onChange={(val) => setAttributes({ boxBg: val })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      {/* Editor Visual Mockup */}
      <div {...blockProps}>
        <BlockUnit digit="12" label={labelDays} />
        <Separator />
        <BlockUnit digit="04" label={labelHours} />
        <Separator />
        <BlockUnit digit="30" label={labelMins} />
        <Separator />
        <BlockUnit digit="15" label={labelSecs} />
      </div>
    </>
  );
}
