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
    widthMode,
    aosAnimation,
    contentAlign,
  } = attributes;

  // Live Timer State for Editor Preview
  const [timeLeft, setTimeLeft] = React.useState({
    d: "00",
    h: "00",
    m: "00",
    s: "00",
  });

  React.useEffect(() => {
    if (!targetDate) {
      setTimeLeft({ d: "00", h: "00", m: "00", s: "00" });
      return;
    }

    const updateTimer = () => {
      let iso = targetDate.trim().replace(" ", "T");
      if (iso.split("T")[1] && iso.split("T")[1].length === 5) {
        iso += ":00";
      }

      const target = new Date(iso).getTime();
      const diff = target - Date.now();

      if (diff <= 0 || isNaN(target)) {
        setTimeLeft({ d: "00", h: "00", m: "00", s: "00" });
        return;
      }

      const d = Math.floor(diff / 86400000);
      const h = Math.floor((diff / 3600000) % 24);
      const m = Math.floor((diff / 60000) % 60);
      const s = Math.floor((diff / 1000) % 60);
      const pad = (n) => String(Math.max(0, n)).padStart(2, "0");

      setTimeLeft({ d: pad(d), h: pad(h), m: pad(m), s: pad(s) });
    };

    updateTimer(); // Run once immediately
    const interval = setInterval(updateTimer, 1000);
    return () => clearInterval(interval);
  }, [targetDate]);

  const blockProps = useBlockProps({
    className: `modern-core-countdown-editor style-${styleMode} mc-width-${widthMode}`,
    "data-aos": aosAnimation,
    style: {
      width: "100%",
      display: "flex",
      justifyContent: contentAlign,
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
        <PanelBody title={__("Layout & Animation", "modern-core")}>
          <SelectControl
            label={__("Block Placement", "modern-core")}
            value={contentAlign}
            options={[
              { label: "Left Aligned", value: "flex-start" },
              { label: "Center Aligned", value: "center" },
              { label: "Right Aligned", value: "flex-end" },
            ]}
            onChange={(val) => setAttributes({ contentAlign: val })}
          />
          <SelectControl
            label={__("Width Mode", "modern-core")}
            value={widthMode}
            options={[
              { label: "Contained", value: "contained" },
              { label: "Wide", value: "wide" },
              { label: "Fullwidth", value: "fullwidth" },
            ]}
            onChange={(val) => setAttributes({ widthMode: val })}
          />
          <SelectControl
            label={__("AOS Animation", "modern-core")}
            value={aosAnimation}
            options={[
              { label: "None", value: "" },
              { label: "Fade Up", value: "fade-up" },
              { label: "Fade Down", value: "fade-down" },
              { label: "Fade In", value: "fade" },
              { label: "Zoom In", value: "zoom-in" },
            ]}
            onChange={(val) => setAttributes({ aosAnimation: val })}
          />
        </PanelBody>

        <PanelBody title={__("Timer Settings", "modern-core")}>
          <TextControl
            type="datetime-local"
            label={__("End Date & Time", "modern-core")}
            value={targetDate}
            onChange={(val) => setAttributes({ targetDate: val })}
            help={__(
              "Ensure the time selected is in the future.",
              "modern-core",
            )}
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

      <div
        {...blockProps}
        style={{
          ...blockProps.style,
          gap: styleMode === "minimal" ? "15px" : "10px",
        }}
      >
        <BlockUnit digit={targetDate ? timeLeft.d : "12"} label={labelDays} />
        <Separator />
        <BlockUnit digit={targetDate ? timeLeft.h : "04"} label={labelHours} />
        <Separator />
        <BlockUnit digit={targetDate ? timeLeft.m : "30"} label={labelMins} />
        <Separator />
        <BlockUnit digit={targetDate ? timeLeft.s : "15"} label={labelSecs} />
      </div>
    </>
  );
}
