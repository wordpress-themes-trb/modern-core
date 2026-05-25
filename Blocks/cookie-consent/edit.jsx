import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ColorPalette,
  ToggleControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    consentMode,
    placementStyle,
    gateMode,
    gateModeStyle,
    showModalOverlay,
    message,
    policyLink,
    policyLinkText,
    btnAccept,
    btnDecline,
    btnManage,
    cookieDays,
    bgColor,
    textColor,
    accentColor,
    shadowOpacity,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-cookie-editor style-${placementStyle}`,
    style: {
      padding: "40px 20px",
      backgroundColor: "#f1f5f9",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      minHeight: "260px",
      boxSizing: "border-box",
    },
  });

  // Shared styles
  const btnBase = {
    padding: "11px 22px",
    borderRadius: "8px",
    fontSize: "13px",
    fontWeight: 700,
    cursor: "pointer",
    border: "none",
    whiteSpace: "nowrap",
    boxSizing: "border-box",
  };
  const mockContainerStyle = {
    background: bgColor,
    color: textColor,
    boxShadow: `0 10px 30px rgba(0,0,0,${shadowOpacity / 100})`,
    borderRadius:
      placementStyle === "bar"
        ? "10px"
        : placementStyle === "card"
          ? "16px"
          : "20px",
    position: "relative",
    boxSizing: "border-box",
    ...(placementStyle === "bar" && {
      width: "100%",
      maxWidth: "900px",
      padding: "18px 28px",
      display: "flex",
      alignItems: "center",
      gap: "24px",
      justifyContent: "space-between",
      flexWrap: "wrap",
    }),
    ...(placementStyle === "card" && {
      width: "320px",
      padding: "28px",
      display: "flex",
      flexDirection: "column",
      gap: "18px",
    }),
    ...(placementStyle === "modal" && {
      width: "460px",
      maxWidth: "100%",
      padding: "40px",
      display: "flex",
      flexDirection: "column",
      gap: "20px",
    }),
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Placement & Compliance", "modern-core")}>
          <SelectControl
            label={__("Placement Style", "modern-core")}
            value={placementStyle}
            options={[
              { label: "Bottom Bar (Slim)", value: "bar" },
              { label: "Floating Card (Bottom Left)", value: "card" },
              { label: "Centered Modal (Strict)", value: "modal" },
            ]}
            onChange={(val) => setAttributes({ placementStyle: val })}
          />
          <SelectControl
            label={__("Consent Mode", "modern-core")}
            value={consentMode}
            options={[
              { label: "Simple (Accept / Decline)", value: "simple" },
              { label: "Category Preferences", value: "category" },
            ]}
            onChange={(val) => setAttributes({ consentMode: val })}
          />
          {placementStyle === "modal" && (
            <ToggleControl
              label={__("Show Backdrop Overlay", "modern-core")}
              checked={showModalOverlay}
              onChange={(val) => setAttributes({ showModalOverlay: val })}
              help={__("Darkens the page behind the modal.", "modern-core")}
            />
          )}
          <RangeControl
            label={__("Cookie Expiration (Days)", "modern-core")}
            value={cookieDays}
            onChange={(val) => setAttributes({ cookieDays: val })}
            min={1}
            max={365}
          />
        </PanelBody>

        <PanelBody title={__("Gate Mode", "modern-core")} initialOpen={false}>
          <ToggleControl
            label={__("Enable Gate Mode", "modern-core")}
            checked={gateMode}
            onChange={(val) => setAttributes({ gateMode: val })}
            help={__(
              "Blocks access to the site until the visitor accepts cookies.",
              "modern-core",
            )}
          />
          {gateMode && (
            <SelectControl
              label={__("Gate Style", "modern-core")}
              value={gateModeStyle}
              options={[
                { label: "Blur page content", value: "blur" },
                { label: "Blackout (full block)", value: "blackout" },
              ]}
              onChange={(val) => setAttributes({ gateModeStyle: val })}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Copy & Content", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Main Message", "modern-core")}
            value={message}
            onChange={(val) => setAttributes({ message: val })}
          />
          <TextControl
            label={__("Privacy Policy URL", "modern-core")}
            value={policyLink}
            onChange={(val) => setAttributes({ policyLink: val })}
          />
          <TextControl
            label={__("Privacy Policy Link Text", "modern-core")}
            value={policyLinkText}
            onChange={(val) => setAttributes({ policyLinkText: val })}
          />
          <TextControl
            label={__("Accept Button Text", "modern-core")}
            value={btnAccept}
            onChange={(val) => setAttributes({ btnAccept: val })}
          />
          <TextControl
            label={__("Decline Button Text", "modern-core")}
            value={btnDecline}
            onChange={(val) => setAttributes({ btnDecline: val })}
          />
          {consentMode === "category" && (
            <TextControl
              label={__("Manage Preferences Text", "modern-core")}
              value={btnManage}
              onChange={(val) => setAttributes({ btnManage: val })}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Visual Design", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Shadow Opacity (%)", "modern-core")}
            value={shadowOpacity}
            onChange={(val) => setAttributes({ shadowOpacity: val })}
            min={0}
            max={30}
          />
          {[
            ["Background Color", "bgColor", bgColor],
            ["Text Color", "textColor", textColor],
            ["Accent / Accept Button", "accentColor", accentColor],
          ].map(([label, key, val]) => (
            <div key={key}>
              <p
                style={{
                  margin: "14px 0 5px",
                  fontWeight: 600,
                  fontSize: "12px",
                }}
              >
                {__(label, "modern-core")}
              </p>
              <ColorPalette
                value={val}
                onChange={(v) => setAttributes({ [key]: v })}
              />
            </div>
          ))}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div style={mockContainerStyle}>
          {/* Close button */}
          <button
            style={{
              position: "absolute",
              top: "12px",
              right: "12px",
              width: "28px",
              height: "28px",
              borderRadius: "50%",
              border: "none",
              background: "rgba(0,0,0,0.07)",
              cursor: "pointer",
              fontSize: "14px",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              color: textColor,
              padding: 0,
              lineHeight: 1,
            }}
          >
            ✕
          </button>

          {/* Header */}
          <div
            style={{
              display: "flex",
              alignItems: "flex-start",
              gap: "12px",
              flex: placementStyle === "bar" ? 1 : "none",
            }}
          >
            <span style={{ fontSize: "22px", flexShrink: 0 }}>🍪</span>
            <div>
              <p
                style={{
                  margin: "0 0 4px",
                  fontWeight: 700,
                  fontSize: "15px",
                  color: textColor,
                }}
              >
                We value your privacy
              </p>
              <p
                style={{
                  margin: 0,
                  fontSize: "13px",
                  color: textColor,
                  opacity: 0.75,
                  lineHeight: 1.5,
                }}
              >
                {message}{" "}
                {policyLink && (
                  <a
                    href="#"
                    style={{
                      color: accentColor,
                      textDecoration: "underline",
                      fontWeight: 600,
                    }}
                  >
                    {policyLinkText}
                  </a>
                )}
              </p>
            </div>
          </div>

          {/* Actions */}
          <div
            style={{
              display: "flex",
              gap: "10px",
              flexDirection: placementStyle === "bar" ? "row" : "column",
              flexShrink: 0,
              width: placementStyle === "bar" ? "auto" : "100%",
            }}
          >
            {consentMode === "category" && (
              <button
                style={{
                  ...btnBase,
                  background: "transparent",
                  color: accentColor,
                  textDecoration: "underline",
                  padding: "8px 0",
                  border: "none",
                }}
              >
                {btnManage}
              </button>
            )}
            <button
              style={{
                ...btnBase,
                background: "transparent",
                color: textColor,
                border: `1px solid rgba(0,0,0,0.15)`,
              }}
            >
              {btnDecline}
            </button>
            <button
              style={{ ...btnBase, background: accentColor, color: "#fff" }}
            >
              {btnAccept}
            </button>
          </div>

          {/* Gate mode badge */}
          {gateMode && (
            <div
              style={{
                position: "absolute",
                top: "-10px",
                left: "20px",
                background: "#f59e0b",
                color: "#fff",
                fontSize: "10px",
                fontWeight: 700,
                padding: "3px 8px",
                borderRadius: "99px",
                letterSpacing: "0.5px",
                textTransform: "uppercase",
              }}
            >
              Gate Mode: {gateModeStyle}
            </div>
          )}
        </div>
      </div>
    </>
  );
}
