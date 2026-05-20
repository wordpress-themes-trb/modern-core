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
    displayMode,
    triggerType,
    triggerValue,
    provider,
    layout,
    heading,
    subhead,
    btnText,
    showName,
    privacyText,
    successMsg,
    bgColor,
    textColor,
    accentColor,
    inputRadius,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-newsletter-editor display-${displayMode} layout-${layout}`,
    style: {
      width: "100%",
      padding: displayMode === "inline" ? "60px 5%" : "20px",
      backgroundColor: displayMode === "inline" ? bgColor : "transparent",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Display & Triggers", "modern-core")}>
          <SelectControl
            label={__("Display Mode", "modern-core")}
            value={displayMode}
            options={[
              { label: "Inline Section (Standard)", value: "inline" },
              { label: "Modal Popup", value: "popup" },
              { label: "Bottom Slide-in", value: "slide" },
              { label: "Sticky Bar", value: "sticky" },
            ]}
            onChange={(val) => setAttributes({ displayMode: val })}
          />
          {displayMode !== "inline" && (
            <>
              <SelectControl
                label={__("Trigger Event", "modern-core")}
                value={triggerType}
                options={[
                  { label: "Time on Page", value: "time" },
                  { label: "Scroll Depth", value: "scroll" },
                  { label: "Exit Intent (Desktop only)", value: "exit" },
                ]}
                onChange={(val) => setAttributes({ triggerType: val })}
              />
              {triggerType === "time" && (
                <RangeControl
                  label={__("Delay (Seconds)", "modern-core")}
                  value={triggerValue}
                  onChange={(val) => setAttributes({ triggerValue: val })}
                  min={0}
                  max={60}
                />
              )}
              {triggerType === "scroll" && (
                <RangeControl
                  label={__("Scroll Depth (%)", "modern-core")}
                  value={triggerValue}
                  onChange={(val) => setAttributes({ triggerValue: val })}
                  min={10}
                  max={100}
                />
              )}
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Form Architecture", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Layout", "modern-core")}
            value={layout}
            options={[
              { label: "Side-by-Side Split", value: "split" },
              { label: "Centered Stack", value: "stack" },
            ]}
            onChange={(val) => setAttributes({ layout: val })}
          />
          <ToggleControl
            label={__("Include First Name Field", "modern-core")}
            checked={showName}
            onChange={(val) => setAttributes({ showName: val })}
          />
          <RangeControl
            label={__("Input/Button Radius", "modern-core")}
            value={inputRadius}
            onChange={(val) => setAttributes({ inputRadius: val })}
            min={0}
            max={50}
          />
        </PanelBody>

        <PanelBody
          title={__("Copy & Messaging", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Heading", "modern-core")}
            value={heading}
            onChange={(val) => setAttributes({ heading: val })}
          />
          <TextControl
            label={__("Subheadline", "modern-core")}
            value={subhead}
            onChange={(val) => setAttributes({ subhead: val })}
          />
          <TextControl
            label={__("Button Text", "modern-core")}
            value={btnText}
            onChange={(val) => setAttributes({ btnText: val })}
          />
          <TextControl
            label={__("Privacy Note", "modern-core")}
            value={privacyText}
            onChange={(val) => setAttributes({ privacyText: val })}
          />
          <TextControl
            label={__("Success Message", "modern-core")}
            value={successMsg}
            onChange={(val) => setAttributes({ successMsg: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Integrations & Colors", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Email Service Provider", "modern-core")}
            value={provider}
            options={[
              { label: "Klaviyo", value: "klaviyo" },
              { label: "Mailchimp", value: "mailchimp" },
              { label: "ConvertKit", value: "convertkit" },
              { label: "WordPress Native", value: "native" },
            ]}
            onChange={(val) => setAttributes({ provider: val })}
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
            <strong>{__("Button & Accent", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-newsletter-editor {
                        --mc-bg: ${bgColor};
                        --mc-text: ${textColor};
                        --mc-accent: ${accentColor};
                        --mc-rad: ${inputRadius}px;
                    }
                    
                    /* Shell Mockups */
                    .shell-popup { background: rgba(0,0,0,0.5); padding: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
                    .shell-popup-inner { background: var(--mc-bg); padding: 50px; border-radius: 16px; width: 100%; max-width: 600px; position: relative; }
                    .shell-slide { background: transparent; padding: 40px; display: flex; justify-content: flex-end; align-items: flex-end; }
                    .shell-slide-inner { background: var(--mc-bg); padding: 40px; border-radius: 16px; width: 100%; max-width: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
                    .shell-sticky { background: transparent; padding: 40px 0; }
                    .shell-sticky-inner { background: var(--mc-bg); padding: 20px 5%; width: 100%; box-shadow: 0 -10px 30px rgba(0,0,0,0.1); }

                    /* Form Layouts */
                    .mc-nl-form-wrapper { color: var(--mc-text); }
                    .layout-split .mc-nl-form-wrapper { display: flex; gap: 40px; align-items: center; }
                    .layout-split .mc-nl-text { flex: 1; }
                    .layout-split .mc-nl-form { flex: 1; }
                    
                    .layout-stack .mc-nl-form-wrapper { text-align: center; max-width: 600px; margin: 0 auto; }
                    .layout-stack .mc-nl-text { margin-bottom: 30px; }

                    /* Display Mode Overrides */
                    .display-popup .mc-nl-form-wrapper, .display-slide .mc-nl-form-wrapper { flex-direction: column; text-align: center; }
                    .display-sticky .mc-nl-form-wrapper { flex-direction: row; align-items: center; justify-content: space-between; gap: 20px; text-align: left; }
                    .display-sticky .mc-nl-text { margin: 0; display: flex; align-items: center; gap: 15px; }

                    /* Form Elements */
                    .mc-nl-input-group { display: flex; gap: 10px; margin-bottom: 10px; }
                    .display-slide .mc-nl-input-group { flex-direction: column; }
                    .mc-nl-input { flex: 1; padding: 14px 20px; border: 1px solid rgba(0,0,0,0.1); border-radius: var(--mc-rad); outline: none; }
                    .mc-nl-btn { padding: 14px 30px; background: var(--mc-accent); color: #fff; border: none; border-radius: var(--mc-rad); font-weight: bold; cursor: pointer; }
                    .mc-nl-privacy { font-size: 11px; opacity: 0.6; margin-top: 10px; }
                    `}
        </style>

        {displayMode !== "inline" && (
          <div
            style={{
              marginBottom: "15px",
              fontSize: "12px",
              color: accentColor,
              fontWeight: "bold",
              textTransform: "uppercase",
              letterSpacing: "1px",
            }}
          >
            Previewing Triggered Mode: {displayMode} (Trigger: {triggerType})
          </div>
        )}

        <div
          className={`shell-${displayMode === "inline" ? "none" : displayMode}`}
        >
          <div
            className={`shell-${displayMode === "inline" ? "none" : displayMode}-inner`}
          >
            {displayMode !== "inline" && displayMode !== "sticky" && (
              <div
                style={{
                  position: "absolute",
                  top: "15px",
                  right: "20px",
                  fontSize: "20px",
                  opacity: 0.5,
                }}
              >
                ✕
              </div>
            )}

            <div className="mc-nl-form-wrapper">
              <div className="mc-nl-text">
                <h3
                  style={{
                    margin: "0 0 10px 0",
                    fontSize: displayMode === "sticky" ? "18px" : "28px",
                    fontWeight: "800",
                  }}
                >
                  {heading}
                </h3>
                {subhead && (
                  <p style={{ margin: 0, fontSize: "15px", opacity: 0.8 }}>
                    {subhead}
                  </p>
                )}
              </div>

              <div className="mc-nl-form">
                <div className="mc-nl-input-group">
                  {showName && (
                    <input
                      type="text"
                      className="mc-nl-input"
                      placeholder="First Name"
                      readOnly
                    />
                  )}
                  <input
                    type="email"
                    className="mc-nl-input"
                    placeholder="Email Address"
                    readOnly
                  />
                  <button className="mc-nl-btn">{btnText}</button>
                </div>
                {privacyText && (
                  <div className="mc-nl-privacy">{privacyText}</div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
