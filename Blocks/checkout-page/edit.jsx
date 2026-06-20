import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  RangeControl,
  ColorPalette,
  TextControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    progressStyle,
    animStyle,
    labelStyle,
    showTrustStrip,
    trustText,
    summaryBg,
    summaryRadius,
    inputRadius,
    accentColor,
    textColor,
    borderColor,
    widthMode,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-checkout-editor layout-${layoutMode} label-${labelStyle} width-${widthMode}`,
    style: { width: "100%", padding: "40px 0", backgroundColor: "#ffffff" },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture & Flow", "modern-core")}>
          <SelectControl
            label={__("Checkout Flow", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Single Page (All Fields Visible)", value: "single" },
              {
                label: "Multi-Step (Contact -> Shipping -> Payment)",
                value: "multi",
              },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <SelectControl
            label={__("Width Mode", "modern-core")}
            value={widthMode}
            options={[
              { label: "Contained", value: "contained" },
              { label: "Wide", value: "wide" },
              { label: "Full Width", value: "fullwidth" },
            ]}
            onChange={(val) => setAttributes({ widthMode: val })}
          />
          {layoutMode === "multi" && (
            <>
              <SelectControl
                label={__("Progress Indicator Style", "modern-core")}
                value={progressStyle}
                options={[
                  { label: "Numbered Steps", value: "numbered" },
                  { label: "Minimal Text", value: "text" },
                ]}
                onChange={(val) => setAttributes({ progressStyle: val })}
              />
              <SelectControl
                label={__("Step Transition Animation", "modern-core")}
                value={animStyle}
                options={[
                  { label: "Slide Horizontal", value: "slide" },
                  { label: "Fade", value: "fade" },
                  { label: "Instant", value: "none" },
                ]}
                onChange={(val) => setAttributes({ animStyle: val })}
              />
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Forms & Trust Elements", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Input Label Style", "modern-core")}
            value={labelStyle}
            options={[
              { label: "Labels Above Inputs", value: "above" },
              { label: "Floating Placeholder Labels", value: "floating" },
            ]}
            onChange={(val) => setAttributes({ labelStyle: val })}
          />
          <ToggleControl
            label={__("Show Security Strip", "modern-core")}
            checked={showTrustStrip}
            onChange={(val) => setAttributes({ showTrustStrip: val })}
          />
          {showTrustStrip && (
            <TextControl
              label={__("Security Text", "modern-core")}
              value={trustText}
              onChange={(val) => setAttributes({ trustText: val })}
            />
          )}
          <RangeControl
            label={__("Input Border Radius", "modern-core")}
            value={inputRadius}
            onChange={(val) => setAttributes({ inputRadius: val })}
            min={0}
            max={20}
          />
        </PanelBody>

        <PanelBody
          title={__("Order Summary Styling", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Summary Border Radius", "modern-core")}
            value={summaryRadius}
            onChange={(val) => setAttributes({ summaryRadius: val })}
            min={0}
            max={24}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Summary Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={summaryBg}
            onChange={(val) => setAttributes({ summaryBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Buttons)", "modern-core")}</strong>
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
            .modern-core-checkout-editor { 
              --mc-bg: ${summaryBg}; 
              --mc-acc: ${accentColor}; 
              --mc-txt: ${textColor}; 
              --mc-border: ${borderColor}; 
              --mc-rad: ${summaryRadius}px; 
              --mc-inp-rad: ${inputRadius}px; 
            }
            
            /* Width modes */
            .modern-core-checkout-editor.width-contained .mc-chk-grid { 
              max-width: 1200px; 
              margin: 0 auto; 
              padding: 0 5%; 
            }
            .modern-core-checkout-editor.width-wide .mc-chk-grid { 
              max-width: 1400px; 
              margin: 0 auto; 
              padding: 0 5%; 
            }
            .modern-core-checkout-editor.width-fullwidth .mc-chk-grid { 
              max-width: 100%; 
              padding: 0 5%; 
            }
            
            .mc-chk-trust { 
              background: #f8fafc; 
              padding: 12px; 
              text-align: center; 
              font-size: 13px; 
              font-weight: bold; 
              color: #64748b; 
              margin-bottom: 30px; 
              display: flex; 
              align-items: center; 
              justify-content: center; 
              gap: 8px; 
              border-bottom: 1px solid var(--mc-border); 
            }
            
            .mc-chk-grid { 
              display: grid; 
              grid-template-columns: 1.2fr 1fr; 
              gap: 60px; 
              align-items: start; 
            }
            
            /* Form Mocks */
            .mc-chk-step-nav { 
              display: flex; 
              gap: 30px; 
              border-bottom: 2px solid rgba(0,0,0,0.05); 
              padding-bottom: 15px; 
              margin-bottom: 30px; 
            }
            .mc-chk-step { 
              font-weight: bold; 
              color: rgba(0,0,0,0.3); 
              display: flex; 
              align-items: center; 
              gap: 10px; 
              padding-bottom: 15px;
              margin-bottom: -17px;
            }
            .mc-chk-step.is-active { 
              color: var(--mc-acc); 
              border-bottom: 2px solid var(--mc-acc); 
            }
            .mc-chk-step-num { 
              width: 24px; 
              height: 24px; 
              border-radius: 50%; 
              background: currentColor; 
              color: #fff; 
              display: flex; 
              align-items: center; 
              justify-content: center; 
              font-size: 12px; 
            }
            
            .mc-chk-form-group { 
              display: flex; 
              flex-direction: column; 
              gap: 20px; 
            }
            .mc-chk-input-wrap { 
              display: flex; 
              flex-direction: column; 
              gap: 8px; 
            }
            .label-floating .mc-chk-input-wrap label { 
              display: none; 
            }
            .mc-chk-input { 
              border: 1px solid var(--mc-border); 
              padding: 15px; 
              border-radius: var(--mc-inp-rad); 
              background: transparent; 
              font-size: 15px;
              color: var(--mc-txt);
            }
            
            /* Two-column form row */
            .mc-chk-form-row-2 {
              display: grid;
              grid-template-columns: 1fr 1fr;
              gap: 20px;
            }
            
            /* Summary Mocks */
            .mc-chk-summary { 
              background: var(--mc-bg); 
              padding: 35px; 
              border-radius: var(--mc-rad); 
              color: var(--mc-txt); 
              border: 1px solid var(--mc-border); 
            }
            .mc-chk-sum-row { 
              display: flex; 
              justify-content: space-between; 
              padding: 15px 0; 
              border-bottom: 1px solid var(--mc-border); 
              font-size: 14px;
            }
            .mc-chk-sum-row strong { 
              font-weight: 600; 
            }
            .mc-chk-total { 
              display: flex; 
              justify-content: space-between; 
              padding: 20px 0; 
              font-size: 20px; 
              font-weight: bold; 
            }
            .mc-chk-btn { 
              background: var(--mc-acc); 
              color: #fff; 
              padding: 18px; 
              text-align: center; 
              border-radius: 6px; 
              font-weight: bold; 
              margin-top: 10px; 
              cursor: pointer;
            }
            
            /* ===== MOBILE ===== */
            @media (max-width: 768px) {
              .mc-chk-grid {
                grid-template-columns: 1fr;
                gap: 40px;
              }
              .mc-chk-form-row-2 {
                grid-template-columns: 1fr;
              }
            }
          `}
        </style>

        {showTrustStrip && (
          <div className="mc-chk-trust">
            <svg
              width="16"
              height="16"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            {trustText}
          </div>
        )}

        <div className="mc-chk-grid">
          <div className="mc-chk-left">
            {layoutMode === "multi" && (
              <div className="mc-chk-step-nav">
                <div className="mc-chk-step is-active">
                  <div className="mc-chk-step-num">1</div> Shipping
                </div>
                <div className="mc-chk-step">
                  <div className="mc-chk-step-num">2</div> Payment
                </div>
              </div>
            )}

            <h3
              style={{
                margin: "0 0 20px 0",
                fontSize: "20px",
                fontWeight: "bold",
              }}
            >
              Contact Information
            </h3>
            <div className="mc-chk-form-group" style={{ marginBottom: "30px" }}>
              <div className="mc-chk-input-wrap">
                <label>Email address</label>
                <div className="mc-chk-input" style={{ color: "#94a3b8" }}>
                  Email address
                </div>
              </div>
            </div>

            <h3
              style={{
                margin: "0 0 20px 0",
                fontSize: "20px",
                fontWeight: "bold",
              }}
            >
              Shipping Address
            </h3>
            <div className="mc-chk-form-group">
              <div className="mc-chk-form-row-2">
                <div className="mc-chk-input-wrap">
                  <label>First name</label>
                  <div className="mc-chk-input" style={{ color: "#94a3b8" }}>
                    First name
                  </div>
                </div>
                <div className="mc-chk-input-wrap">
                  <label>Last name</label>
                  <div className="mc-chk-input" style={{ color: "#94a3b8" }}>
                    Last name
                  </div>
                </div>
              </div>
              <div className="mc-chk-input-wrap">
                <label>Address</label>
                <div className="mc-chk-input" style={{ color: "#94a3b8" }}>
                  Address
                </div>
              </div>
            </div>
          </div>

          <div className="mc-chk-right">
            <div className="mc-chk-summary">
              <h3
                style={{
                  margin: "0 0 20px 0",
                  fontSize: "20px",
                  fontWeight: "bold",
                }}
              >
                Order Summary
              </h3>

              {/* Mock Product */}
              <div
                style={{
                  display: "flex",
                  gap: "15px",
                  alignItems: "center",
                  marginBottom: "20px",
                  paddingBottom: "20px",
                  borderBottom: `1px solid ${borderColor}`,
                }}
              >
                <div
                  style={{
                    width: "60px",
                    height: "80px",
                    background: "#e2e8f0",
                    borderRadius: "4px",
                    position: "relative",
                    flexShrink: 0,
                  }}
                >
                  <div
                    style={{
                      position: "absolute",
                      top: "-8px",
                      right: "-8px",
                      background: "rgba(0,0,0,0.5)",
                      color: "#fff",
                      width: "20px",
                      height: "20px",
                      borderRadius: "50%",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      fontSize: "11px",
                      fontWeight: "bold",
                    }}
                  >
                    1
                  </div>
                </div>
                <div style={{ flex: 1, minWidth: 0 }}>
                  <div
                    style={{
                      fontWeight: "bold",
                      fontSize: "14px",
                      whiteSpace: "nowrap",
                      overflow: "hidden",
                      textOverflow: "ellipsis",
                    }}
                  >
                    Classic Oxford Shirt
                  </div>
                  <div style={{ fontSize: "12px", opacity: 0.6 }}>
                    White / Medium
                  </div>
                </div>
                <div style={{ fontWeight: "bold", flexShrink: 0 }}>$45.00</div>
              </div>

              <div className="mc-chk-sum-row">
                <span>Subtotal</span>
                <strong>$45.00</strong>
              </div>
              <div className="mc-chk-sum-row">
                <span>Shipping</span>
                <span>Free</span>
              </div>
              <div className="mc-chk-total">
                <span>Total</span>
                <span>$45.00</span>
              </div>
              <div className="mc-chk-btn">Place order</div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
