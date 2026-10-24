import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  RangeControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    showHeaders,
    autoUpdate,
    summaryBg,
    summaryRadius,
    accentColor,
    textColor,
    borderColor,
    widthMode,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-cart-editor layout-${layoutMode} width-${widthMode}`,
    style: { width: "100%", padding: "40px 0", backgroundColor: "#ffffff" },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Macro Layout", "modern-core")}>
          <SelectControl
            label={__("Layout Architecture", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Two Column (65% / 35%)", value: "two-column" },
              { label: "Single Column (Stacked)", value: "stacked" },
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
        </PanelBody>

        <PanelBody
          title={__("Table & Interactions", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Table Column Headers", "modern-core")}
            checked={showHeaders}
            onChange={(val) => setAttributes({ showHeaders: val })}
          />
          <ToggleControl
            label={__("Auto-Update on Quantity Change", "modern-core")}
            checked={autoUpdate}
            onChange={(val) => setAttributes({ autoUpdate: val })}
            help="Instantly recalculates totals when a user changes item quantity, hiding the default 'Update Cart' button."
          />
        </PanelBody>

        <PanelBody
          title={__("Summary Card Styling", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
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
            <strong>
              {__("Brand Accent (Checkout Button)", "modern-core")}
            </strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Border & Separator Color", "modern-core")}</strong>
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
            .modern-core-cart-editor { 
              --mc-bg: ${summaryBg}; 
              --mc-acc: ${accentColor}; 
              --mc-txt: ${textColor}; 
              --mc-border: ${borderColor}; 
              --mc-rad: ${summaryRadius}px; 
            }
            .modern-core-cart-editor.width-contained .mc-cart-mock-grid { 
              max-width: 1200px; 
              margin: 0 auto; 
              padding: 0 5%; 
            }
            .modern-core-cart-editor.width-wide .mc-cart-mock-grid { 
              max-width: 1400px; 
              margin: 0 auto; 
              padding: 0 5%; 
            }
            .modern-core-cart-editor.width-fullwidth .mc-cart-mock-grid { 
              max-width: 100%; 
              padding: 0 5%; 
            }
            .mc-cart-mock-grid { 
              display: grid; 
              gap: 40px; 
              align-items: start; 
            }
            .layout-two-column .mc-cart-mock-grid { 
              grid-template-columns: 2fr 1fr; 
            }
            .layout-stacked .mc-cart-mock-grid { 
              grid-template-columns: 1fr; 
              max-width: 800px; 
              margin: 0 auto; 
            }

            /* ===== DESKTOP TABLE ===== */
            .mc-cart-mock-table { 
              width: 100%; 
              color: var(--mc-txt); 
            }
            .mc-cart-mock-th { 
              display: ${showHeaders ? "grid" : "none"}; 
              grid-template-columns: 40px 2fr 1fr 1fr 1fr; 
              padding-bottom: 15px; 
              border-bottom: 1px solid var(--mc-border); 
              font-size: 12px; 
              text-transform: uppercase; 
              opacity: 0.6; 
              font-weight: 600; 
              letter-spacing: 0.5px;
            }
            .mc-cart-mock-th > div:nth-child(1) { /* remove header */ }
            .mc-cart-mock-th > div:nth-child(3) { text-align: left; }
            .mc-cart-mock-th > div:nth-child(4) { text-align: center; }
            .mc-cart-mock-th > div:nth-child(5) { text-align: right; }
            
            .mc-cart-mock-row { 
              display: grid; 
              grid-template-columns: 40px 2fr 1fr 1fr 1fr; 
              padding: 25px 0; 
              border-bottom: 1px solid var(--mc-border); 
              align-items: center; 
              position: relative;
            }
            .mc-cart-mock-row > div:nth-child(2) { /* product */ }
            .mc-cart-mock-row > div:nth-child(3) { text-align: left; font-weight: 600; }
            .mc-cart-mock-row > div:nth-child(4) { text-align: center; }
            .mc-cart-mock-row > div:nth-child(5) { text-align: right; font-weight: 700; }

            /* Desktop Remove Button */
            .mc-cart-mock-remove-desktop {
              display: flex;
              align-items: center;
              justify-content: center;
            }
            .mc-cart-mock-remove-desktop span {
              opacity: 0.5;
              font-size: 20px;
              cursor: pointer;
              transition: opacity 0.2s, color 0.2s;
              line-height: 1;
            }
            .mc-cart-mock-remove-desktop span:hover {
              opacity: 1;
              color: #ef4444;
            }

            /* Product cell */
            .mc-cart-mock-product { 
              display: flex; 
              gap: 15px; 
              align-items: center; 
              min-width: 0;
            }
            .mc-cart-mock-img { 
              width: 80px; 
              height: 100px; 
              background: #f1f5f9; 
              border-radius: 4px; 
              flex-shrink: 0; 
            }
            .mc-cart-mock-product-info {
              min-width: 0;
              overflow: hidden;
            }
            .mc-cart-mock-qty { 
              border: 1px solid var(--mc-border); 
              padding: 8px 15px; 
              border-radius: 4px; 
              display: inline-block; 
              font-weight: 600;
              font-size: 15px;
            }

            /* Mobile Remove Button (trash icon) - hidden on desktop */
            .mc-cart-mock-remove-mobile {
              display: none;
            }

            /* Coupon */
            .mc-cart-mock-coupon-row { 
              display: flex; 
              gap: 10px; 
              margin-top: 30px; 
            }
            .mc-cart-mock-coupon-input {
              padding: 12px;
              border: 1px solid ${borderColor};
              border-radius: 4px;
              flex: 1;
              max-width: 250px;
              font-size: 14px;
            }
            .mc-cart-mock-coupon-btn {
              padding: 12px 20px;
              background: transparent;
              border: 1px solid ${accentColor};
              color: ${accentColor};
              border-radius: 4px;
              font-weight: bold;
              cursor: pointer;
              font-size: 14px;
            }

            /* Summary */
            .mc-cart-mock-summary { 
              background: var(--mc-bg); 
              padding: 30px; 
              border-radius: var(--mc-rad); 
              color: var(--mc-txt); 
              border: 1px solid var(--mc-border); 
            }
            .mc-cart-mock-sum-row { 
              display: flex; 
              justify-content: space-between; 
              padding: 15px 0; 
              border-bottom: 1px solid var(--mc-border); 
              font-size: 14px;
            }
            .mc-cart-mock-sum-row strong { font-weight: 600; }
            .mc-cart-mock-total { 
              display: flex; 
              justify-content: space-between; 
              padding: 20px 0; 
              font-size: 20px; 
              font-weight: bold; 
            }
            .mc-cart-mock-btn { 
              background: var(--mc-acc); 
              color: #fff; 
              padding: 18px; 
              text-align: center; 
              border-radius: 6px; 
              font-weight: bold; 
              margin-top: 10px; 
              cursor: pointer;
            }

            /* ===== MOBILE RESPONSIVE ===== */
            @media (max-width: 768px) {
              .layout-two-column .mc-cart-mock-grid { 
                grid-template-columns: 1fr; 
              }
              
              /* Hide desktop table headers */
              .mc-cart-mock-th { 
                display: none !important; 
              }
              
              /* Mobile row: full-width card layout */
              .mc-cart-mock-row { 
                display: grid;
                grid-template-columns: 80px 1fr 40px;
                grid-template-rows: auto auto auto;
                grid-template-areas: 
                  "img info delete"
                  "img price delete"
                  "img qty delete";
                padding: 20px 0;
                gap: 8px 15px;
                align-items: start;
              }
              
              /* Hide desktop remove */
              .mc-cart-mock-remove-desktop {
                display: none !important;
              }
              
              /* Show mobile trash */
              .mc-cart-mock-remove-mobile {
                display: flex !important;
                grid-area: delete;
                align-items: center;
                justify-content: center;
                align-self: center;
              }
              .mc-cart-mock-remove-mobile svg {
                width: 20px;
                height: 20px;
                color: #9ca3af;
                cursor: pointer;
                transition: color 0.2s;
              }
              .mc-cart-mock-remove-mobile svg:hover {
                color: #ef4444;
              }
              
              /* Product image */
              .mc-cart-mock-row .mc-cart-mock-product {
                grid-area: img;
                flex-direction: column;
                gap: 0;
              }
              .mc-cart-mock-row .mc-cart-mock-img {
                width: 80px;
                height: 100px;
              }
              
              /* Product info */
              .mc-cart-mock-row .mc-cart-mock-product-info {
                grid-area: info;
                min-width: 0;
              }
              
              /* Price */
              .mc-cart-mock-row > div:nth-child(3) {
                grid-area: price;
                text-align: left;
                font-size: 16px;
                font-weight: 700;
              }
              
              /* Quantity */
              .mc-cart-mock-row > div:nth-child(4) {
                grid-area: qty;
                text-align: left;
              }
              .mc-cart-mock-row .mc-cart-mock-qty {
                padding: 6px 12px;
                font-size: 14px;
              }
              
              /* Subtotal - hide on mobile (matches design) */
              .mc-cart-mock-row > div:nth-child(5) {
                display: none !important;
              }
            }
          `}
        </style>

        <div className="mc-cart-mock-grid">
          <div className="mc-cart-mock-left">
            <div className="mc-cart-mock-table">
              <div className="mc-cart-mock-th">
                <div></div>
                <div>Product</div>
                <div>Price</div>
                <div>Quantity</div>
                <div>Total</div>
              </div>

              {/* Item 1 */}
              <div className="mc-cart-mock-row">
                {/* Desktop Remove */}
                <div className="mc-cart-mock-remove-desktop">
                  <span>✕</span>
                </div>

                {/* Product */}
                <div className="mc-cart-mock-product">
                  <div className="mc-cart-mock-img"></div>
                  <div className="mc-cart-mock-product-info">
                    <div
                      style={{
                        fontWeight: "bold",
                        fontSize: "16px",
                        whiteSpace: "nowrap",
                        overflow: "hidden",
                        textOverflow: "ellipsis",
                      }}
                    >
                      Classic Oxford Shirt
                    </div>
                    <div style={{ fontSize: "13px", opacity: 0.6 }}>
                      Color: White, Size: M
                    </div>
                  </div>
                </div>

                {/* Price */}
                <div>$45.00</div>

                {/* Quantity */}
                <div>
                  <span className="mc-cart-mock-qty">- 1 +</span>
                </div>

                {/* Subtotal */}
                <div>$45.00</div>

                {/* Mobile Trash Icon */}
                <div className="mc-cart-mock-remove-mobile">
                  <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                </div>
              </div>
            </div>

            {/* Coupon */}
            <div className="mc-cart-mock-coupon-row">
              <input
                type="text"
                placeholder="Coupon code"
                className="mc-cart-mock-coupon-input"
                readOnly
              />
              <button className="mc-cart-mock-coupon-btn">Apply</button>
            </div>
          </div>

          <div className="mc-cart-mock-right">
            <div className="mc-cart-mock-summary">
              <h3
                style={{
                  margin: "0 0 20px 0",
                  fontSize: "20px",
                  fontWeight: "bold",
                }}
              >
                Order Summary
              </h3>
              <div className="mc-cart-mock-sum-row">
                <span>Subtotal</span>
                <strong>$45.00</strong>
              </div>
              <div className="mc-cart-mock-sum-row">
                <span>Shipping</span>
                <span>Calculated at checkout</span>
              </div>
              <div className="mc-cart-mock-total">
                <span>Total</span>
                <span>$45.00</span>
              </div>
              <div className="mc-cart-mock-btn">Proceed to checkout</div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
