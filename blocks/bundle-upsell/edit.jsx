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
    sectionTitle,
    sourceMode,
    manualIds,
    displayMode,
    discountType,
    discountValue,
    lockMainProduct,
    accentColor,
    badgeColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-bundle-editor mode-${displayMode}`,
    style: {
      width: "100%",
      padding: "40px 5%",
      backgroundColor: "#f8fafc",
      border: "1px solid #e2e8f0",
      borderRadius: "12px",
    },
  });

  const mockProducts = [
    {
      id: 1,
      title: "Current Product (Main)",
      price: 100,
      isMain: true,
      img: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E",
    },
    {
      id: 2,
      title: "Complementary Accessory",
      price: 45,
      isMain: false,
      img: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E",
    },
    {
      id: 3,
      title: "Matching Item",
      price: 60,
      isMain: false,
      img: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E",
    },
  ];

  const totalPrice = mockProducts.reduce((acc, p) => acc + p.price, 0);
  const discountAmount =
    discountType === "percent"
      ? totalPrice * (discountValue / 100)
      : discountValue;
  const finalPrice = totalPrice - discountAmount;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Section Header", "modern-core")}>
          <TextControl
            label={__("Title", "modern-core")}
            value={sectionTitle}
            onChange={(val) => setAttributes({ sectionTitle: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Bundle Configuration", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Data Source", "modern-core")}
            value={sourceMode}
            options={[
              { label: "Dynamic (WooCommerce Upsells)", value: "dynamic" },
              { label: "Manual Selection", value: "manual" },
            ]}
            onChange={(val) => setAttributes({ sourceMode: val })}
          />
          {sourceMode === "manual" && (
            <TextControl
              label={__("Product IDs (Comma Separated)", "modern-core")}
              value={manualIds}
              onChange={(val) => setAttributes({ manualIds: val })}
            />
          )}
          <SelectControl
            label={__("Display Mode", "modern-core")}
            value={displayMode}
            options={[
              { label: "Checkbox List (High Conversion)", value: "checkbox" },
              { label: "Visual Stack (Overlapping Images)", value: "stack" },
              { label: "Card Row (Horizontal)", value: "card" },
            ]}
            onChange={(val) => setAttributes({ displayMode: val })}
          />
          <ToggleControl
            label={__("Lock Main Product", "modern-core")}
            checked={lockMainProduct}
            onChange={(val) => setAttributes({ lockMainProduct: val })}
            help={__(
              "Prevents the user from unchecking the primary item.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Discount Engine", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Discount Type", "modern-core")}
            value={discountType}
            options={[
              { label: "Percentage (%)", value: "percent" },
              { label: "Fixed Amount ($)", value: "fixed" },
              { label: "No Discount", value: "none" },
            ]}
            onChange={(val) => setAttributes({ discountType: val })}
          />
          {discountType !== "none" && (
            <RangeControl
              label={__("Discount Value", "modern-core")}
              value={discountValue}
              onChange={(val) => setAttributes({ discountValue: val })}
              min={1}
              max={100}
            />
          )}
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Savings Badge Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={badgeColor}
            onChange={(val) => setAttributes({ badgeColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <h3
          style={{ margin: "0 0 20px 0", fontSize: "20px", color: "#0f172a" }}
        >
          {sectionTitle}
        </h3>

        {/* VISUAL STACK MODE PREVIEW */}
        {displayMode === "stack" && (
          <div
            style={{
              display: "flex",
              alignItems: "center",
              gap: "15px",
              marginBottom: "30px",
            }}
          >
            {mockProducts.map((p, i) => (
              <React.Fragment key={p.id}>
                <div
                  style={{
                    width: "80px",
                    height: "100px",
                    backgroundImage: `url(${p.img})`,
                    backgroundSize: "cover",
                    borderRadius: "8px",
                    border: "2px solid #fff",
                    boxShadow: "0 4px 10px rgba(0,0,0,0.1)",
                  }}
                ></div>
                {i < mockProducts.length - 1 && (
                  <div
                    style={{
                      fontWeight: "bold",
                      color: "#94a3b8",
                      fontSize: "20px",
                    }}
                  >
                    +
                  </div>
                )}
              </React.Fragment>
            ))}
          </div>
        )}

        {/* CARD ROW MODE PREVIEW */}
        {displayMode === "card" && (
          <div style={{ display: "flex", gap: "15px", marginBottom: "30px" }}>
            {mockProducts.map((p) => (
              <div
                key={p.id}
                style={{
                  flex: 1,
                  background: "#fff",
                  padding: "15px",
                  borderRadius: "8px",
                  border: "1px solid #e2e8f0",
                }}
              >
                <div
                  style={{
                    width: "100%",
                    aspectRatio: "1/1",
                    backgroundImage: `url(${p.img})`,
                    backgroundSize: "cover",
                    borderRadius: "4px",
                    marginBottom: "10px",
                  }}
                ></div>
                <div
                  style={{
                    fontSize: "13px",
                    fontWeight: "bold",
                    marginBottom: "5px",
                  }}
                >
                  {p.title}
                </div>
                <div
                  style={{
                    fontSize: "13px",
                    color: "#64748b",
                    marginBottom: "10px",
                  }}
                >
                  ${p.price.toFixed(2)}
                </div>
                <div
                  style={{
                    padding: "8px",
                    background: accentColor,
                    color: "#fff",
                    fontSize: "11px",
                    textAlign: "center",
                    borderRadius: "4px",
                  }}
                >
                  Add
                </div>
              </div>
            ))}
          </div>
        )}

        {/* CHECKBOX MODE PREVIEW */}
        {displayMode === "checkbox" && (
          <div
            style={{
              display: "flex",
              flexDirection: "column",
              gap: "10px",
              marginBottom: "25px",
            }}
          >
            {mockProducts.map((p) => (
              <div
                key={p.id}
                style={{
                  display: "flex",
                  alignItems: "center",
                  gap: "15px",
                  padding: "10px",
                  background: "#fff",
                  borderRadius: "8px",
                  border: "1px solid #e2e8f0",
                }}
              >
                <input
                  type="checkbox"
                  checked={true}
                  readOnly
                  style={{ accentColor: accentColor }}
                />
                <div
                  style={{
                    width: "40px",
                    height: "40px",
                    backgroundImage: `url(${p.img})`,
                    backgroundSize: "cover",
                    borderRadius: "4px",
                  }}
                ></div>
                <div
                  style={{
                    flex: 1,
                    fontSize: "14px",
                    fontWeight: p.isMain ? "bold" : "normal",
                  }}
                >
                  {p.title}{" "}
                  {p.isMain && lockMainProduct && (
                    <span style={{ opacity: 0.5, fontSize: "12px" }}>🔒</span>
                  )}
                </div>
                <div style={{ fontWeight: "bold", fontSize: "14px" }}>
                  ${p.price.toFixed(2)}
                </div>
              </div>
            ))}
          </div>
        )}

        {/* TOTALS & ATC ZONE */}
        <div
          style={{
            display: "flex",
            alignItems: "center",
            justifyContent: "space-between",
            padding: "20px",
            background: "#fff",
            borderRadius: "8px",
            border: "1px solid #e2e8f0",
          }}
        >
          <div>
            <div
              style={{
                fontSize: "13px",
                color: "#64748b",
                marginBottom: "5px",
              }}
            >
              Total Price:
            </div>
            <div style={{ display: "flex", alignItems: "center", gap: "10px" }}>
              <span
                style={{
                  fontSize: "24px",
                  fontWeight: "800",
                  color: "#0f172a",
                }}
              >
                ${finalPrice.toFixed(2)}
              </span>
              {discountType !== "none" && (
                <del style={{ fontSize: "16px", color: "#94a3b8" }}>
                  ${totalPrice.toFixed(2)}
                </del>
              )}
            </div>
            {discountType !== "none" && (
              <div
                style={{
                  marginTop: "5px",
                  display: "inline-block",
                  padding: "4px 8px",
                  background: badgeColor,
                  color: "#fff",
                  fontSize: "11px",
                  fontWeight: "bold",
                  borderRadius: "4px",
                }}
              >
                Save{" "}
                {discountType === "percent"
                  ? `${discountValue}%`
                  : `$${discountValue}`}{" "}
                when bought together
              </div>
            )}
          </div>
          <div
            style={{
              padding: "14px 30px",
              background: accentColor,
              color: "#fff",
              borderRadius: "50px",
              fontWeight: "bold",
              fontSize: "14px",
              cursor: "pointer",
            }}
          >
            Add all to cart
          </div>
        </div>
      </div>
    </>
  );
}
