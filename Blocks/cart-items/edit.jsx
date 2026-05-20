import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
  TextControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    thumbSize,
    thumbRadius,
    qtyStyle,
    removeStyle,
    titleColor,
    showFreeShipping,
    shippingThreshold,
    accentColor,
    checkoutBg,
    checkoutText,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-cart-items-block",
    style: {
      display: "flex",
      flexDirection: "column",
      height: "100%",
      color: "inherit",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Product Item Styling", "modern-core")}>
          <RangeControl
            label={__("Thumbnail Size (px)", "modern-core")}
            value={thumbSize}
            onChange={(val) => setAttributes({ thumbSize: val })}
            min={50}
            max={120}
          />
          <RangeControl
            label={__("Thumbnail Border Radius (px)", "modern-core")}
            value={thumbRadius}
            onChange={(val) => setAttributes({ thumbRadius: val })}
            min={0}
            max={50}
          />
          <SelectControl
            label={__("Quantity Selector Style", "modern-core")}
            value={qtyStyle}
            options={[
              { label: "Plus / Minus Buttons", value: "plus-minus" },
              { label: "Compact Input", value: "input" },
            ]}
            onChange={(val) => setAttributes({ qtyStyle: val })}
          />
          <SelectControl
            label={__("Remove Button Style", "modern-core")}
            value={removeStyle}
            options={[
              { label: "Text Link (Remove)", value: "text" },
              { label: "Trash Icon", value: "trash" },
              { label: "X Icon", value: "x" },
            ]}
            onChange={(val) => setAttributes({ removeStyle: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Product Title Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={titleColor}
            onChange={(val) => setAttributes({ titleColor: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Shipping & Checkout", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Enable Free Shipping Bar", "modern-core")}
            checked={showFreeShipping}
            onChange={(val) => setAttributes({ showFreeShipping: val })}
          />
          {showFreeShipping && (
            <RangeControl
              label={__("Free Shipping Threshold ($)", "modern-core")}
              value={shippingThreshold}
              onChange={(val) => setAttributes({ shippingThreshold: val })}
              min={10}
              max={500}
            />
          )}
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>
              {__("Brand Accent Color (Progress Bar)", "modern-core")}
            </strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Checkout Button Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={checkoutBg}
            onChange={(val) => setAttributes({ checkoutBg: val })}
          />
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Checkout Button Text", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={checkoutText}
            onChange={(val) => setAttributes({ checkoutText: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* 1. Free Shipping Bar */}
        {showFreeShipping && (
          <div
            style={{
              marginBottom: "25px",
              paddingBottom: "20px",
              borderBottom: "1px solid rgba(255,255,255,0.1)",
            }}
          >
            <p
              style={{
                fontSize: "13px",
                margin: "0 0 10px 0",
                fontWeight: "bold",
              }}
            >
              You are <span style={{ color: accentColor }}>$35.00</span> away
              from Free Shipping!
            </p>
            <div
              style={{
                width: "100%",
                height: "6px",
                backgroundColor: "rgba(255,255,255,0.1)",
                borderRadius: "10px",
                overflow: "hidden",
              }}
            >
              <div
                style={{
                  width: "75%",
                  height: "100%",
                  backgroundColor: accentColor,
                  borderRadius: "10px",
                }}
              ></div>
            </div>
          </div>
        )}

        {/* 2. Cart Items List (Mock Data for Editor) */}
        <div
          style={{
            flex: 1,
            display: "flex",
            flexDirection: "column",
            gap: "20px",
          }}
        >
          {[1, 2].map((item) => (
            <div
              key={item}
              style={{
                display: "flex",
                gap: "15px",
                paddingBottom: "20px",
                borderBottom: "1px dashed rgba(255,255,255,0.1)",
              }}
            >
              <div
                style={{
                  width: thumbSize,
                  height: thumbSize * 1.2,
                  backgroundColor: "rgba(255,255,255,0.1)",
                  borderRadius: thumbRadius,
                }}
              ></div>
              <div
                style={{
                  flex: 1,
                  display: "flex",
                  flexDirection: "column",
                  justifyContent: "space-between",
                }}
              >
                <div>
                  <h4
                    style={{
                      margin: "0 0 5px 0",
                      fontSize: "15px",
                      color: titleColor,
                    }}
                  >
                    Premium Cotton T-Shirt
                  </h4>
                  <p style={{ margin: 0, fontSize: "12px", opacity: 0.6 }}>
                    Color: Black | Size: M
                  </p>
                </div>
                <div
                  style={{
                    display: "flex",
                    justifyContent: "space-between",
                    alignItems: "center",
                    marginTop: "10px",
                  }}
                >
                  {/* Qty Selector Preview */}
                  <div
                    style={{
                      display: "flex",
                      alignItems: "center",
                      border: "1px solid rgba(255,255,255,0.2)",
                      borderRadius: "4px",
                      overflow: "hidden",
                    }}
                  >
                    {qtyStyle === "plus-minus" && (
                      <span
                        style={{
                          padding: "4px 10px",
                          cursor: "pointer",
                          opacity: 0.7,
                        }}
                      >
                        -
                      </span>
                    )}
                    <span
                      style={{
                        padding: "4px 12px",
                        fontSize: "13px",
                        borderLeft:
                          qtyStyle === "plus-minus"
                            ? "1px solid rgba(255,255,255,0.2)"
                            : "none",
                        borderRight:
                          qtyStyle === "plus-minus"
                            ? "1px solid rgba(255,255,255,0.2)"
                            : "none",
                      }}
                    >
                      1
                    </span>
                    {qtyStyle === "plus-minus" && (
                      <span
                        style={{
                          padding: "4px 10px",
                          cursor: "pointer",
                          opacity: 0.7,
                        }}
                      >
                        +
                      </span>
                    )}
                  </div>

                  {/* Remove Button Preview */}
                  <span
                    style={{
                      fontSize: "12px",
                      cursor: "pointer",
                      opacity: 0.6,
                      textDecoration:
                        removeStyle === "text" ? "underline" : "none",
                    }}
                  >
                    {removeStyle === "text"
                      ? "Remove"
                      : removeStyle === "trash"
                        ? "🗑️"
                        : "✕"}
                  </span>
                </div>
              </div>
              <div style={{ fontWeight: "bold", fontSize: "15px" }}>$45.00</div>
            </div>
          ))}
        </div>

        {/* 3. Subtotal & Checkout (Fixed at bottom natively via flexbox) */}
        <div
          style={{
            marginTop: "30px",
            paddingTop: "20px",
            borderTop: "1px solid rgba(255,255,255,0.1)",
          }}
        >
          <div
            style={{
              display: "flex",
              justifyContent: "space-between",
              marginBottom: "20px",
              fontSize: "18px",
              fontWeight: "bold",
            }}
          >
            <span>Subtotal</span>
            <span>$90.00</span>
          </div>
          <button
            style={{
              width: "100%",
              padding: "16px",
              backgroundColor: checkoutBg,
              color: checkoutText,
              border: "none",
              borderRadius: "8px",
              fontSize: "16px",
              fontWeight: "bold",
              cursor: "pointer",
              textTransform: "uppercase",
              letterSpacing: "1px",
            }}
          >
            Proceed to Checkout
          </button>
        </div>
      </div>
    </>
  );
}
