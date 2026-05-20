import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  RangeControl,
  ColorPalette,
  TextControl,
  TextareaControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    animType,
    headline,
    message,
    showTimeline,
    showCrossSells,
    showReferral,
    referralText,
    cardBg,
    cardRadius,
    accentColor,
    textColor,
    borderColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-order-conf-editor",
    style: {
      width: "100%",
      padding: "60px 0",
      backgroundColor: "#f8fafc",
      color: textColor,
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Celebration & Copy", "modern-core")}>
          <SelectControl
            label={__("Success Animation", "modern-core")}
            value={animType}
            options={[
              { label: "Animated Checkmark", value: "checkmark" },
              { label: "Confetti Burst", value: "confetti" },
              { label: "None", value: "none" },
            ]}
            onChange={(val) => setAttributes({ animType: val })}
          />
          <TextControl
            label={__("Headline", "modern-core")}
            value={headline}
            onChange={(val) => setAttributes({ headline: val })}
          />
          <TextareaControl
            label={__("Confirmation Message", "modern-core")}
            value={message}
            onChange={(val) => setAttributes({ message: val })}
            rows={3}
          />
        </PanelBody>

        <PanelBody
          title={__("Layout & Elements", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Fulfillment Timeline", "modern-core")}
            checked={showTimeline}
            onChange={(val) => setAttributes({ showTimeline: val })}
          />
          <ToggleControl
            label={__("Show Post-Purchase Cross-sells", "modern-core")}
            checked={showCrossSells}
            onChange={(val) => setAttributes({ showCrossSells: val })}
          />
          <ToggleControl
            label={__("Show Referral Section", "modern-core")}
            checked={showReferral}
            onChange={(val) => setAttributes({ showReferral: val })}
          />
          {showReferral && (
            <TextControl
              label={__("Referral Offer Text", "modern-core")}
              value={referralText}
              onChange={(val) => setAttributes({ referralText: val })}
            />
          )}
        </PanelBody>

        <PanelBody title={__("Styling", "modern-core")} initialOpen={false}>
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Card Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={cardBg}
            onChange={(val) => setAttributes({ cardBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Success / Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Border Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={borderColor}
            onChange={(val) => setAttributes({ borderColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-order-conf-editor { --oc-bg: ${cardBg}; --oc-acc: ${accentColor}; --oc-txt: ${textColor}; --oc-border: ${borderColor}; --oc-rad: ${cardRadius}px; }
                    .mc-oc-header { text-align: center; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto; }
                    .mc-oc-icon-mock { width: 80px; height: 80px; border-radius: 50%; background: var(--oc-acc); color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 40px; }
                    
                    .mc-oc-card { background: var(--oc-bg); border: 1px solid var(--oc-border); border-radius: var(--oc-rad); padding: 40px; max-width: 800px; margin: 0 auto 40px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
                    .mc-oc-row { display: flex; justify-content: space-between; border-bottom: 1px solid var(--oc-border); padding: 15px 0; }
                    .mc-oc-row:last-child { border-bottom: none; padding-bottom: 0; }
                    
                    .mc-oc-timeline { display: flex; justify-content: space-between; position: relative; margin-bottom: 40px; padding: 0 40px; max-width: 800px; margin: 0 auto 40px auto; }
                    .mc-oc-timeline::before { content: ''; position: absolute; top: 15px; left: 60px; right: 60px; height: 2px; background: var(--oc-border); z-index: 0; }
                    .mc-oc-step { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; gap: 10px; font-size: 13px; font-weight: bold; }
                    .mc-oc-dot { width: 30px; height: 30px; border-radius: 50%; background: var(--oc-bg); border: 2px solid var(--oc-border); display: flex; align-items: center; justify-content: center; }
                    .mc-oc-step.is-active .mc-oc-dot { border-color: var(--oc-acc); background: var(--oc-acc); color: #fff; }

                    .mc-oc-promo { background: var(--oc-bg); border: 1px dashed var(--oc-acc); border-radius: var(--oc-rad); padding: 30px; text-align: center; max-width: 800px; margin: 0 auto; }
                    `}
        </style>

        <div className="mc-oc-header">
          <div className="mc-oc-icon-mock">✓</div>
          <h1
            style={{
              fontSize: "36px",
              fontWeight: "900",
              margin: "0 0 10px 0",
            }}
          >
            {headline}
          </h1>
          <div
            style={{
              fontSize: "18px",
              opacity: 0.6,
              fontWeight: "bold",
              marginBottom: "15px",
            }}
          >
            Order #12345
          </div>
          <p style={{ opacity: 0.8, lineHeight: 1.6 }}>{message}</p>
        </div>

        {showTimeline && (
          <div className="mc-oc-timeline">
            <div className="mc-oc-step is-active">
              <div className="mc-oc-dot">✓</div> Order Confirmed
            </div>
            <div className="mc-oc-step">
              <div className="mc-oc-dot">2</div> Processing
            </div>
            <div className="mc-oc-step">
              <div className="mc-oc-dot">3</div> Shipped
            </div>
          </div>
        )}

        <div className="mc-oc-card">
          <h3 style={{ margin: "0 0 20px 0", fontSize: "20px" }}>
            Order Summary
          </h3>
          <div className="mc-oc-row">
            <div style={{ display: "flex", gap: "15px", alignItems: "center" }}>
              <div
                style={{
                  width: "60px",
                  height: "60px",
                  background: "#e2e8f0",
                  borderRadius: "4px",
                }}
              ></div>
              <div>
                <div style={{ fontWeight: "bold" }}>
                  Classic Oxford Shirt x 1
                </div>
                <div style={{ fontSize: "12px", opacity: 0.6 }}>
                  Color: White, Size: M
                </div>
              </div>
            </div>
            <div style={{ fontWeight: "bold" }}>$45.00</div>
          </div>
          <div className="mc-oc-row" style={{ opacity: 0.8 }}>
            <span>Subtotal</span>
            <span>$45.00</span>
          </div>
          <div className="mc-oc-row" style={{ opacity: 0.8 }}>
            <span>Shipping</span>
            <span>Free</span>
          </div>
          <div
            className="mc-oc-row"
            style={{ fontSize: "20px", fontWeight: "bold" }}
          >
            <span>Total</span>
            <span>$45.00</span>
          </div>
        </div>

        {showReferral && (
          <div className="mc-oc-promo">
            <h3 style={{ margin: "0 0 10px 0", color: accentColor }}>
              {referralText}
            </h3>
            <div
              style={{ display: "flex", gap: "10px", justifyContent: "center" }}
            >
              <input
                type="text"
                value="https://store.com/ref/abc123"
                readOnly
                style={{
                  padding: "10px",
                  borderRadius: "4px",
                  border: `1px solid ${borderColor}`,
                  width: "250px",
                }}
              />
              <button
                style={{
                  padding: "10px 20px",
                  background: accentColor,
                  color: "#fff",
                  border: "none",
                  borderRadius: "4px",
                  fontWeight: "bold",
                }}
              >
                Copy Link
              </button>
            </div>
          </div>
        )}
      </div>
    </>
  );
}
