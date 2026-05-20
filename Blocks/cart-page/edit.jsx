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
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-cart-editor layout-${layoutMode}`,
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
                    .modern-core-cart-editor { --mc-bg: ${summaryBg}; --mc-acc: ${accentColor}; --mc-txt: ${textColor}; --mc-border: ${borderColor}; --mc-rad: ${summaryRadius}px; }
                    .mc-cart-mock-grid { display: grid; gap: 40px; max-width: 1200px; margin: 0 auto; padding: 0 5%; align-items: start; }
                    .layout-two-column .mc-cart-mock-grid { grid-template-columns: 2fr 1fr; }
                    .layout-stacked .mc-cart-mock-grid { grid-template-columns: 1fr; max-width: 800px; }

                    /* Table Mocks */
                    .mc-cart-mock-table { width: 100%; color: var(--mc-txt); }
                    .mc-cart-mock-th { display: ${showHeaders ? "grid" : "none"}; grid-template-columns: 4fr 1fr 1fr 1fr; padding-bottom: 15px; border-bottom: 1px solid var(--mc-border); font-size: 13px; text-transform: uppercase; opacity: 0.6; font-weight: bold; }
                    .mc-cart-mock-row { display: grid; grid-template-columns: 4fr 1fr 1fr 1fr; padding: 25px 0; border-bottom: 1px solid var(--mc-border); align-items: center; }
                    .mc-cart-mock-product { display: flex; gap: 15px; align-items: center; }
                    .mc-cart-mock-img { width: 80px; height: 100px; background: #f1f5f9; border-radius: 4px; }
                    .mc-cart-mock-qty { border: 1px solid var(--mc-border); padding: 8px 15px; border-radius: 4px; display: inline-block; }

                    /* Summary Mock */
                    .mc-cart-mock-summary { background: var(--mc-bg); padding: 30px; border-radius: var(--mc-rad); color: var(--mc-txt); border: 1px solid var(--mc-border); }
                    .mc-cart-mock-sum-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid var(--mc-border); }
                    .mc-cart-mock-total { display: flex; justify-content: space-between; padding: 20px 0; font-size: 20px; font-weight: bold; }
                    .mc-cart-mock-btn { background: var(--mc-acc); color: #fff; padding: 18px; text-align: center; border-radius: 6px; font-weight: bold; margin-top: 10px; }
                    `}
        </style>

        <div className="mc-cart-mock-grid">
          <div className="mc-cart-mock-left">
            <div className="mc-cart-mock-table">
              <div className="mc-cart-mock-th">
                <div>Product</div>
                <div>Price</div>
                <div>Quantity</div>
                <div style={{ textAlign: "right" }}>Total</div>
              </div>
              {/* Item 1 */}
              <div className="mc-cart-mock-row">
                <div className="mc-cart-mock-product">
                  <span style={{ opacity: 0.5, fontSize: "20px" }}>✕</span>
                  <div className="mc-cart-mock-img"></div>
                  <div>
                    <div style={{ fontWeight: "bold", fontSize: "16px" }}>
                      Classic Oxford Shirt
                    </div>
                    <div style={{ fontSize: "13px", opacity: 0.6 }}>
                      Color: White, Size: M
                    </div>
                  </div>
                </div>
                <div style={{ fontWeight: "bold" }}>$45.00</div>
                <div>
                  <span className="mc-cart-mock-qty">- 1 +</span>
                </div>
                <div style={{ textAlign: "right", fontWeight: "bold" }}>
                  $45.00
                </div>
              </div>
              {/* Coupon Row */}
              <div style={{ display: "flex", gap: "10px", marginTop: "30px" }}>
                <input
                  type="text"
                  placeholder="Coupon code"
                  style={{
                    padding: "12px",
                    border: `1px solid ${borderColor}`,
                    borderRadius: "4px",
                    flex: 1,
                    maxWidth: "250px",
                  }}
                  readOnly
                />
                <button
                  style={{
                    padding: "12px 20px",
                    background: "transparent",
                    border: `1px solid ${accentColor}`,
                    color: accentColor,
                    borderRadius: "4px",
                    fontWeight: "bold",
                  }}
                >
                  Apply
                </button>
              </div>
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
