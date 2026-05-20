import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  RangeControl,
  ToggleControl,
  TextControl,
  TextareaControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    heading,
    message,
    btnText,
    showName,
    privacyNote,
    successHeading,
    successMsg,
    emailSubject,
    emailBody,
    bgColor,
    textColor,
    accentColor,
    inputRadius,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-bis-editor",
    style: { width: "100%", padding: "20px 0" },
  });

  const BellIcon = () => (
    <svg
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="none"
      stroke={accentColor}
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
      style={{ marginBottom: "10px" }}
    >
      <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
      <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
    </svg>
  );

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Copy & Form Architecture", "modern-core")}>
          <TextControl
            label={__("Heading", "modern-core")}
            value={heading}
            onChange={(val) => setAttributes({ heading: val })}
          />
          <TextareaControl
            label={__("Explanation Message", "modern-core")}
            value={message}
            onChange={(val) => setAttributes({ message: val })}
            rows={2}
          />
          <ToggleControl
            label={__("Include First Name Field", "modern-core")}
            checked={showName}
            onChange={(val) => setAttributes({ showName: val })}
          />
          <TextControl
            label={__("Button Text", "modern-core")}
            value={btnText}
            onChange={(val) => setAttributes({ btnText: val })}
          />
          <TextControl
            label={__("Privacy Note", "modern-core")}
            value={privacyNote}
            onChange={(val) => setAttributes({ privacyNote: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Success State", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Success Heading", "modern-core")}
            value={successHeading}
            onChange={(val) => setAttributes({ successHeading: val })}
          />
          <TextControl
            label={__("Success Message", "modern-core")}
            value={successMsg}
            onChange={(val) => setAttributes({ successMsg: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Automated Email Setup", "modern-core")}
          initialOpen={false}
        >
          <p style={{ fontSize: "12px", opacity: 0.6, marginBottom: "15px" }}>
            Use <code>{"{product_name}"}</code> and{" "}
            <code>{"{product_url}"}</code> as dynamic tags.
          </p>
          <TextControl
            label={__("Email Subject", "modern-core")}
            value={emailSubject}
            onChange={(val) => setAttributes({ emailSubject: val })}
          />
          <TextareaControl
            label={__("Email Body", "modern-core")}
            value={emailBody}
            onChange={(val) => setAttributes({ emailBody: val })}
            rows={4}
          />
        </PanelBody>

        <PanelBody
          title={__("Design & Colors", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Input/Button Radius", "modern-core")}
            value={inputRadius}
            onChange={(val) => setAttributes({ inputRadius: val })}
            min={0}
            max={24}
          />
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
            <strong>{__("Accent Color", "modern-core")}</strong>
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
            background: bgColor,
            color: textColor,
            padding: "30px",
            borderRadius: "12px",
            border: "1px solid rgba(0,0,0,0.05)",
            maxWidth: "400px",
          }}
        >
          <BellIcon />
          <h3
            style={{
              margin: "0 0 8px 0",
              fontSize: "20px",
              fontWeight: "bold",
            }}
          >
            {heading}
          </h3>
          <p
            style={{
              margin: "0 0 20px 0",
              fontSize: "14px",
              opacity: 0.8,
              lineHeight: 1.5,
            }}
          >
            {message}
          </p>

          <div
            style={{ display: "flex", flexDirection: "column", gap: "10px" }}
          >
            {showName && (
              <input
                type="text"
                placeholder="Your First Name"
                style={{
                  padding: "12px 15px",
                  borderRadius: `${inputRadius}px`,
                  border: "1px solid rgba(0,0,0,0.1)",
                  width: "100%",
                  boxSizing: "border-box",
                }}
                readOnly
              />
            )}
            <input
              type="email"
              placeholder="Your Email Address"
              style={{
                padding: "12px 15px",
                borderRadius: `${inputRadius}px`,
                border: "1px solid rgba(0,0,0,0.1)",
                width: "100%",
                boxSizing: "border-box",
              }}
              readOnly
            />
            <button
              style={{
                padding: "12px 15px",
                borderRadius: `${inputRadius}px`,
                border: "none",
                background: accentColor,
                color: "#fff",
                fontWeight: "bold",
                width: "100%",
                cursor: "pointer",
              }}
            >
              {btnText}
            </button>
          </div>

          {privacyNote && (
            <div
              style={{
                fontSize: "11px",
                opacity: 0.6,
                marginTop: "12px",
                textAlign: "center",
              }}
            >
              {privacyNote}
            </div>
          )}
        </div>
      </div>
    </>
  );
}
