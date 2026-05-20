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
    layoutMode,
    gridCols,
    showStock,
    showPriceDrop,
    showShare,
    emptyHeading,
    emptyMessage,
    emptyButton,
    emptyLink,
    accentColor,
  } = attributes;

  // Local state to toggle editor preview mode
  const [previewEmpty, setPreviewEmpty] = React.useState(false);

  const blockProps = useBlockProps({
    className: `modern-core-wishlist-editor layout-${layoutMode}`,
    style: {
      width: "100%",
      padding: "40px 5%",
      backgroundColor: "#f8fafc",
      border: "2px dashed #cbd5e1",
    },
  });

  const mockItems = Array.from({ length: 4 }).map((_, i) => ({
    id: i,
    title:
      i % 2 === 0
        ? "Premium Cotton Silhouette"
        : "Minimalist Architecture Chair",
    price: i === 1 ? "$245.00" : "$120.00",
    oldPrice: i === 1 && showPriceDrop ? "$295.00" : null,
    outOfStock: i === 2 && showStock,
    image: `https://images.unsplash.com/photo-${i % 2 === 0 ? "1521572163474-6f64f9fa023e" : "1506422745199-563b7e735496"}?q=80&w=300&auto=format&fit=crop`,
  }));

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Display Architecture", "modern-core")}>
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Standard Grid", value: "grid" },
              { label: "Horizontal List", value: "list" },
              { label: "Compact Table", value: "table" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          {layoutMode === "grid" && (
            <RangeControl
              label={__("Grid Columns", "modern-core")}
              value={gridCols}
              onChange={(val) => setAttributes({ gridCols: val })}
              min={2}
              max={6}
            />
          )}
          <ToggleControl
            label={__("Preview Empty State in Editor", "modern-core")}
            checked={previewEmpty}
            onChange={setPreviewEmpty}
          />
        </PanelBody>

        <PanelBody
          title={__("Item Intelligence", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Flag Out of Stock Items", "modern-core")}
            checked={showStock}
            onChange={(val) => setAttributes({ showStock: val })}
          />
          <ToggleControl
            label={__("Flag Price Drops", "modern-core")}
            checked={showPriceDrop}
            onChange={(val) => setAttributes({ showPriceDrop: val })}
          />
          <ToggleControl
            label={__("Enable Social Sharing", "modern-core")}
            checked={showShare}
            onChange={(val) => setAttributes({ showShare: val })}
          />
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Empty State Design", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Heading", "modern-core")}
            value={emptyHeading}
            onChange={(val) => setAttributes({ emptyHeading: val })}
          />
          <TextControl
            label={__("Message", "modern-core")}
            value={emptyMessage}
            onChange={(val) => setAttributes({ emptyMessage: val })}
          />
          <TextControl
            label={__("Button Text", "modern-core")}
            value={emptyButton}
            onChange={(val) => setAttributes({ emptyButton: val })}
          />
          <TextControl
            label={__("Button Link", "modern-core")}
            value={emptyLink}
            onChange={(val) => setAttributes({ emptyLink: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {previewEmpty ? (
          <div
            style={{
              textAlign: "center",
              padding: "60px 20px",
              background: "#fff",
              borderRadius: "12px",
            }}
          >
            <div
              style={{
                fontSize: "48px",
                color: "#cbd5e1",
                marginBottom: "20px",
              }}
            >
              ♡
            </div>
            <h3
              style={{
                margin: "0 0 10px 0",
                fontSize: "24px",
                color: "#0f172a",
              }}
            >
              {emptyHeading}
            </h3>
            <p style={{ opacity: 0.6, marginBottom: "30px" }}>{emptyMessage}</p>
            <span
              style={{
                padding: "12px 30px",
                background: accentColor,
                color: "#fff",
                borderRadius: "50px",
                fontWeight: "bold",
              }}
            >
              {emptyButton}
            </span>
          </div>
        ) : (
          <div>
            <div
              style={{
                display: "flex",
                justifyContent: "space-between",
                marginBottom: "30px",
              }}
            >
              <h2 style={{ margin: 0 }}>Your Wishlist</h2>
              <div style={{ display: "flex", gap: "15px" }}>
                {showShare && (
                  <span
                    style={{
                      padding: "10px 20px",
                      border: "1px solid #cbd5e1",
                      borderRadius: "4px",
                      fontSize: "13px",
                      fontWeight: "bold",
                      cursor: "pointer",
                    }}
                  >
                    Share Wishlist
                  </span>
                )}
                <span
                  style={{
                    padding: "10px 20px",
                    background: accentColor,
                    color: "#fff",
                    borderRadius: "4px",
                    fontSize: "13px",
                    fontWeight: "bold",
                    cursor: "pointer",
                  }}
                >
                  Move All to Cart
                </span>
              </div>
            </div>

            <div
              style={{
                display: layoutMode === "grid" ? "grid" : "flex",
                flexDirection: "column",
                gridTemplateColumns:
                  layoutMode === "grid" ? `repeat(${gridCols}, 1fr)` : "none",
                gap: "30px",
              }}
            >
              {mockItems.map((p, i) => (
                <div
                  key={i}
                  style={{
                    background: "#fff",
                    borderRadius: "8px",
                    border: "1px solid #e2e8f0",
                    padding: "15px",
                    position: "relative",
                    display: layoutMode !== "grid" ? "flex" : "block",
                    alignItems: "center",
                    gap: "20px",
                    opacity: p.outOfStock ? 0.6 : 1,
                  }}
                >
                  <div
                    style={{
                      position: "absolute",
                      top: "10px",
                      right: "10px",
                      fontSize: "16px",
                      color: "#94a3b8",
                      cursor: "pointer",
                      zIndex: 10,
                    }}
                  >
                    ✕
                  </div>
                  <div
                    style={{
                      width: layoutMode !== "grid" ? "120px" : "100%",
                      aspectRatio: "3/4",
                      backgroundImage: `url(${p.image})`,
                      backgroundSize: "cover",
                      borderRadius: "4px",
                      marginBottom: layoutMode === "grid" ? "15px" : "0",
                      position: "relative",
                    }}
                  >
                    {p.oldPrice && (
                      <span
                        style={{
                          position: "absolute",
                          top: "10px",
                          left: "10px",
                          background: "#10b981",
                          color: "#fff",
                          fontSize: "10px",
                          padding: "4px 8px",
                          borderRadius: "4px",
                          fontWeight: "bold",
                        }}
                      >
                        PRICE DROP
                      </span>
                    )}
                    {p.outOfStock && (
                      <span
                        style={{
                          position: "absolute",
                          top: "50%",
                          left: "50%",
                          transform: "translate(-50%, -50%)",
                          background: "#000",
                          color: "#fff",
                          fontSize: "11px",
                          padding: "6px 12px",
                          fontWeight: "bold",
                        }}
                      >
                        OUT OF STOCK
                      </span>
                    )}
                  </div>
                  <div style={{ flex: 1 }}>
                    <h4 style={{ margin: "0 0 10px 0", fontSize: "14px" }}>
                      {p.title}
                    </h4>
                    <div style={{ fontWeight: "bold", marginBottom: "15px" }}>
                      {p.oldPrice && (
                        <del
                          style={{
                            opacity: 0.5,
                            marginRight: "8px",
                            fontSize: "12px",
                            fontWeight: "normal",
                          }}
                        >
                          {p.oldPrice}
                        </del>
                      )}
                      {p.price}
                    </div>
                    <div
                      style={{
                        width: "100%",
                        padding: "10px",
                        background: p.outOfStock ? "#e2e8f0" : accentColor,
                        color: p.outOfStock ? "#94a3b8" : "#fff",
                        textAlign: "center",
                        fontSize: "12px",
                        fontWeight: "bold",
                        borderRadius: "4px",
                      }}
                    >
                      {p.outOfStock ? "Unavailable" : "Move to Cart"}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </>
  );
}
