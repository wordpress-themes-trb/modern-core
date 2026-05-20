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
    consentMode,
    placementStyle,
    message,
    policyLink,
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
      minHeight: "300px",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture & Compliance", "modern-core")}>
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
          <RangeControl
            label={__("Cookie Expiration (Days)", "modern-core")}
            value={cookieDays}
            onChange={(val) => setAttributes({ cookieDays: val })}
            min={1}
            max={365}
          />
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
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Background Color", "modern-core")}</strong>
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
            <strong>
              {__("Primary Accent (Accept Button)", "modern-core")}
            </strong>
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
                    .modern-core-cookie-editor { --mc-bg: ${bgColor}; --mc-text: ${textColor}; --mc-acc: ${accentColor}; --mc-shad: ${shadowOpacity / 100}; }
                    .mc-cookie-mock { background: var(--mc-bg); color: var(--mc-text); box-shadow: 0 10px 30px rgba(0,0,0,var(--mc-shad)); display: flex; gap: 20px; align-items: center; position: relative; }
                    
                    /* Style Mocks */
                    .style-bar .mc-cookie-mock { width: 100%; max-width: 1200px; padding: 20px 5%; border-radius: 8px; justify-content: space-between; }
                    .style-card .mc-cookie-mock { width: 360px; padding: 25px; border-radius: 12px; flex-direction: column; align-items: flex-start; }
                    .style-modal .mc-cookie-mock { width: 480px; padding: 40px; border-radius: 16px; flex-direction: column; text-align: center; }

                    .mc-cookie-text { font-size: 14px; line-height: 1.5; margin: 0; flex: 1; }
                    .mc-cookie-link { color: var(--mc-acc); text-decoration: underline; }
                    
                    .mc-cookie-actions { display: flex; gap: 10px; flex-wrap: wrap; }
                    .style-card .mc-cookie-actions, .style-modal .mc-cookie-actions { width: 100%; }
                    .style-modal .mc-cookie-actions { justify-content: center; }
                    
                    .mc-btn { padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; border: none; white-space: nowrap; }
                    .mc-btn-accept { background: var(--mc-acc); color: #fff; }
                    .mc-btn-decline { background: transparent; color: var(--mc-text); border: 1px solid rgba(0,0,0,0.1); }
                    .mc-btn-manage { background: transparent; color: var(--mc-text); text-decoration: underline; padding: 10px 5px; opacity: 0.7; }
                    `}
        </style>

        <div className="mc-cookie-mock">
          <p className="mc-cookie-text">
            {message}{" "}
            {policyLink && (
              <a href="#" className="mc-cookie-link">
                Privacy Policy
              </a>
            )}
          </p>

          <div className="mc-cookie-actions">
            {consentMode === "category" && (
              <button className="mc-btn mc-btn-manage">{btnManage}</button>
            )}
            <button className="mc-btn mc-btn-decline">{btnDecline}</button>
            <button className="mc-btn mc-btn-accept">{btnAccept}</button>
          </div>
        </div>
      </div>
    </>
  );
}
