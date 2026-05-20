import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    triggerType,
    mobileFallback,
    suppressionDays,
    headline,
    subhead,
    offerHighlight,
    ctaText,
    dismissText,
    showEmail,
    showCountdown,
    bgColor,
    textColor,
    accentColor,
    overlayOpacity,
    overlayBlur,
    animStyle,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-exit-editor",
    style: {
      padding: "40px 20px",
      backgroundColor: `rgba(0,0,0,${overlayOpacity / 100})`,
      backdropFilter: `blur(${overlayBlur}px)`,
      minHeight: "500px",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Trigger & Suppression", "modern-core")}>
          <SelectControl
            label={__("Primary Trigger (Desktop)", "modern-core")}
            value={triggerType}
            options={[
              { label: "Exit Intent (Mouse leaves top)", value: "exit" },
              { label: "Time Delay", value: "time" },
              { label: "Scroll Depth", value: "scroll" },
            ]}
            onChange={(val) => setAttributes({ triggerType: val })}
          />
          <RangeControl
            label={__("Mobile Fallback Trigger (Seconds)", "modern-core")}
            value={mobileFallback}
            onChange={(val) => setAttributes({ mobileFallback: val })}
            min={3}
            max={60}
            help="Exit intent doesn't work on mobile touch screens. This is the delay before it shows on phones."
          />
          <RangeControl
            label={__("Suppression (Days)", "modern-core")}
            value={suppressionDays}
            onChange={(val) => setAttributes({ suppressionDays: val })}
            min={1}
            max={30}
            help="How long to hide the popup after the user closes it."
          />
        </PanelBody>

        <PanelBody
          title={__("Copy & Content", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Headline", "modern-core")}
            value={headline}
            onChange={(val) => setAttributes({ headline: val })}
          />
          <TextControl
            label={__("Subheadline", "modern-core")}
            value={subhead}
            onChange={(val) => setAttributes({ subhead: val })}
          />
          <TextControl
            label={__("Offer Highlight", "modern-core")}
            value={offerHighlight}
            onChange={(val) => setAttributes({ offerHighlight: val })}
          />
          <TextControl
            label={__("Button Text", "modern-core")}
            value={ctaText}
            onChange={(val) => setAttributes({ ctaText: val })}
          />
          <TextControl
            label={__("Dismiss Link Text", "modern-core")}
            value={dismissText}
            onChange={(val) => setAttributes({ dismissText: val })}
          />
          <ToggleControl
            label={__("Show Email Input", "modern-core")}
            checked={showEmail}
            onChange={(val) => setAttributes({ showEmail: val })}
          />
          <ToggleControl
            label={__("Show Fake Urgency Countdown", "modern-core")}
            checked={showCountdown}
            onChange={(val) => setAttributes({ showCountdown: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Visual Design", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Entrance Animation", "modern-core")}
            value={animStyle}
            options={[
              { label: "Scale Up", value: "scale" },
              { label: "Fade In", value: "fade" },
              { label: "Slide Down", value: "slide" },
            ]}
            onChange={(val) => setAttributes({ animStyle: val })}
          />
          <RangeControl
            label={__("Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={100}
          />
          <RangeControl
            label={__("Overlay Blur (px)", "modern-core")}
            value={overlayBlur}
            onChange={(val) => setAttributes({ overlayBlur: val })}
            min={0}
            max={20}
          />

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Modal Background", "modern-core")}</strong>
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
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Accent Color (Button)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            position: "absolute",
            top: "15px",
            left: "15px",
            color: "#fff",
            fontSize: "11px",
            fontWeight: "bold",
            textTransform: "uppercase",
            letterSpacing: "1px",
            background: "rgba(0,0,0,0.5)",
            padding: "5px 10px",
            borderRadius: "4px",
          }}
        >
          Exit Intent Editor View
        </div>

        <div
          style={{
            background: bgColor,
            color: textColor,
            padding: "50px 40px",
            borderRadius: "16px",
            boxShadow: "0 25px 50px -12px rgba(0,0,0,0.5)",
            width: "100%",
            maxWidth: "480px",
            textAlign: "center",
            position: "relative",
          }}
        >
          <div
            style={{
              position: "absolute",
              top: "15px",
              right: "20px",
              fontSize: "24px",
              opacity: 0.5,
              cursor: "pointer",
            }}
          >
            ✕
          </div>

          <h2
            style={{
              fontSize: "32px",
              fontWeight: "900",
              margin: "0 0 10px 0",
              lineHeight: 1.1,
            }}
          >
            {headline}
          </h2>
          <p style={{ fontSize: "16px", opacity: 0.8, margin: "0 0 25px 0" }}>
            {subhead}
          </p>

          {offerHighlight && (
            <div
              style={{
                background: "rgba(255,255,255,0.1)",
                padding: "12px",
                borderRadius: "8px",
                fontWeight: "bold",
                letterSpacing: "1px",
                marginBottom: "25px",
                border: "1px dashed rgba(255,255,255,0.2)",
              }}
            >
              {offerHighlight}
            </div>
          )}

          {showCountdown && (
            <div
              style={{
                display: "flex",
                justifyContent: "center",
                gap: "15px",
                marginBottom: "25px",
                fontSize: "20px",
                fontWeight: "bold",
              }}
            >
              <div>
                04
                <span
                  style={{
                    fontSize: "11px",
                    display: "block",
                    opacity: 0.6,
                    fontWeight: "normal",
                  }}
                >
                  MIN
                </span>
              </div>
              :
              <div>
                59
                <span
                  style={{
                    fontSize: "11px",
                    display: "block",
                    opacity: 0.6,
                    fontWeight: "normal",
                  }}
                >
                  SEC
                </span>
              </div>
            </div>
          )}

          {showEmail && (
            <input
              type="email"
              placeholder="Enter your email address"
              style={{
                width: "100%",
                padding: "15px",
                borderRadius: "8px",
                border: "none",
                marginBottom: "15px",
                fontSize: "16px",
                outline: "none",
              }}
              readOnly
            />
          )}

          <button
            style={{
              width: "100%",
              padding: "16px",
              borderRadius: "8px",
              border: "none",
              background: accentColor,
              color: "#fff",
              fontSize: "16px",
              fontWeight: "bold",
              cursor: "pointer",
              marginBottom: "15px",
            }}
          >
            {ctaText}
          </button>

          <div
            style={{
              fontSize: "13px",
              opacity: 0.5,
              textDecoration: "underline",
              cursor: "pointer",
            }}
          >
            {dismissText}
          </div>
        </div>
      </div>
    </>
  );
}
