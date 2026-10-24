import * as React from "@wordpress/element";
import { useState, useEffect, useCallback } from "@wordpress/element";
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

/* ── FIX 2a: Define sub-components OUTSIDE the Edit function ────────────────
 * Defining Unit/TimerMock inside Edit means React treats them as new
 * component types on every render, unmounting + remounting them and
 * discarding live state. Moved outside so they are stable references.
 */
const Unit = ({ digit, label, timerStyle, boxColor, digitColor }) => (
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
        color: "#fff",
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
      />
    )}
  </div>
);

/* ── FIX 2b: Reliable local-time parser (same fix as render.php) ────────────
 * new Date("2025-06-01T18:00") is parsed as UTC in Safari → wrong countdown.
 * Manual split + Date constructor uses local timezone correctly everywhere.
 */
function parseLocalDate(str) {
  if (!str) return NaN;
  const s = str.trim().replace(" ", "T");
  const parts = s.split("T");
  const d = (parts[0] || "").split("-").map(Number);
  const t = (parts[1] || "00:00").split(":").map(Number);
  if (!d[0] || !d[1] || !d[2]) return NaN;
  return new Date(
    d[0],
    d[1] - 1,
    d[2],
    t[0] || 0,
    t[1] || 0,
    t[2] || 0,
  ).getTime();
}

const DEFAULT_TEMPLATE = [
  [
    "core/paragraph",
    {
      content: "FLASH EVENT",
      style: {
        typography: {
          fontSize: "12px",
          fontWeight: "700",
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
      style: {
        typography: { fontSize: "36px", fontWeight: "800" },
        color: { text: "#ffffff" },
      },
      textAlign: "center",
    },
  ],
  [
    "core/paragraph",
    {
      content: "Secure your items before the vault closes permanently.",
      style: {
        typography: { fontSize: "16px" },
        color: { text: "#ffffff" },
        spacing: { margin: { bottom: "20px" } },
      },
      align: "center",
    },
  ],
  [
    "core/buttons",
    {
      /* FIX 1: layout type + justifyContent centers the button */
      layout: { type: "flex", justifyContent: "center" },
      style: { spacing: { margin: { top: "8px" } } },
    },
    [
      [
        "core/button",
        {
          text: "Shop Now",
          style: { border: { radius: "50px" } },
        },
      ],
    ],
  ],
];

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
    widthMode,
  } = attributes;

  /* ── FIX 2c: Timer state lives here, updated by a stable interval ───────── */
  const [timeLeft, setTimeLeft] = useState({
    d: "00",
    h: "00",
    m: "00",
    s: "00",
  });

  useEffect(() => {
    const pad = (n) => String(Math.max(0, n)).padStart(2, "0");

    function tick() {
      const target = parseLocalDate(targetDate);
      if (!targetDate || isNaN(target)) {
        setTimeLeft({ d: "00", h: "00", m: "00", s: "00" });
        return;
      }
      const diff = target - Date.now();
      if (diff <= 0) {
        setTimeLeft({ d: "00", h: "00", m: "00", s: "00" });
        return;
      }
      const totalSec = Math.floor(diff / 1000);
      setTimeLeft({
        d: pad(Math.floor(totalSec / 86400)),
        h: pad(Math.floor(totalSec / 3600) % 24),
        m: pad(Math.floor(totalSec / 60) % 60),
        s: pad(totalSec % 60),
      });
    }

    tick(); // run immediately so display isn't blank for 1 second
    const interval = setInterval(tick, 1000);
    return () => clearInterval(interval);
  }, [targetDate]); // only restarts when the date setting changes

  /* ── Layout-dependent styles ───────────────────────────────────────────── */
  const isCard = layoutMode === "card";
  const isSplit = layoutMode === "split";
  const isStrip = layoutMode === "strip";

  const blockProps = useBlockProps({
    className: `modern-core-countdown-banner-editor layout-${layoutMode} style-${timerStyle} mc-width-${widthMode}`,
    style: {
      boxSizing: "border-box",
      width: "100%",
      background:
        bgMode === "gradient"
          ? `linear-gradient(135deg, ${color1}, ${color2})`
          : color1,
      padding: isStrip
        ? "20px clamp(16px,5%,60px)"
        : "60px clamp(16px,5%,60px)",
      display: "flex",
      flexDirection: isSplit ? "row" : "column",
      alignItems: "center",
      justifyContent: isSplit ? "space-between" : "center",
      gap: "40px",
      borderRadius: isCard ? "16px" : "0",
      boxShadow: isCard ? "0 25px 50px -12px rgba(0,0,0,0.5)" : "none",
      color: "#fff",
      margin: isCard ? "40px auto" : "0",
      maxWidth: isCard ? "1000px" : "none",
      overflow: "hidden" /* prevents editor horizontal scroll */,
    },
  });

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
            label={__("Timer Style", "modern-core")}
            value={timerStyle}
            options={[
              { label: "Solid Boxed", value: "boxed" },
              { label: "Mechanical Flip", value: "flip" },
              { label: "Glowing Neon", value: "neon" },
              { label: "Minimal Text", value: "minimal" },
            ]}
            onChange={(val) => setAttributes({ timerStyle: val })}
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
          <p style={{ margin: "8px 0 5px", fontWeight: 600, fontSize: "12px" }}>
            {bgMode === "gradient"
              ? __("Gradient Start", "modern-core")
              : __("Background Color", "modern-core")}
          </p>
          <ColorPalette
            value={color1}
            onChange={(val) => setAttributes({ color1: val })}
          />
          {bgMode === "gradient" && (
            <>
              <p
                style={{
                  margin: "14px 0 5px",
                  fontWeight: 600,
                  fontSize: "12px",
                }}
              >
                {__("Gradient End", "modern-core")}
              </p>
              <ColorPalette
                value={color2}
                onChange={(val) => setAttributes({ color2: val })}
              />
            </>
          )}
          <p
            style={{ margin: "14px 0 5px", fontWeight: 600, fontSize: "12px" }}
          >
            {__("Digit Color", "modern-core")}
          </p>
          <ColorPalette
            value={digitColor}
            onChange={(val) => setAttributes({ digitColor: val })}
          />
          {(timerStyle === "boxed" || timerStyle === "flip") && (
            <>
              <p
                style={{
                  margin: "14px 0 5px",
                  fontWeight: 600,
                  fontSize: "12px",
                }}
              >
                {__("Box Background", "modern-core")}
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
        {/* ── CONTENT ZONE ─────────────────────────────────────────────────── */}
        {/* FIX 1: width:100% + textAlign:center on the wrapper ensures
            InnerBlocks content (including core/buttons) inherits centering  */}
        <div
          style={{
            flex: isSplit ? "1" : "none",
            width: "100%",
            maxWidth: isSplit ? "none" : "600px",
            display: "flex",
            flexDirection: "column",
            alignItems: isSplit ? "flex-start" : "center",
            textAlign: isSplit ? "left" : "center",
          }}
        >
          <InnerBlocks template={DEFAULT_TEMPLATE} templateLock={false} />
        </div>

        {/* ── TIMER ZONE ───────────────────────────────────────────────────── */}
        <div
          style={{
            flex: isSplit ? "1" : "none",
            display: "flex",
            justifyContent: isSplit ? "flex-end" : "center",
            textShadow:
              timerStyle === "neon"
                ? `0 0 10px ${digitColor}, 0 0 20px ${digitColor}`
                : "none",
          }}
        >
          <div
            style={{
              display: "flex",
              gap: "15px",
              flexWrap: "wrap",
              justifyContent: "center",
            }}
          >
            {showDays && (
              <Unit
                digit={timeLeft.d}
                label="Days"
                timerStyle={timerStyle}
                boxColor={boxColor}
                digitColor={digitColor}
              />
            )}
            <Unit
              digit={timeLeft.h}
              label="Hours"
              timerStyle={timerStyle}
              boxColor={boxColor}
              digitColor={digitColor}
            />
            <Unit
              digit={timeLeft.m}
              label="Mins"
              timerStyle={timerStyle}
              boxColor={boxColor}
              digitColor={digitColor}
            />
            <Unit
              digit={timeLeft.s}
              label="Secs"
              timerStyle={timerStyle}
              boxColor={boxColor}
              digitColor={digitColor}
            />
          </div>
        </div>
      </div>
    </>
  );
}
