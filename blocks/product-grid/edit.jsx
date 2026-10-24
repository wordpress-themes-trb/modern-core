import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    productCount,
    querySort,
    layoutMode,
    columnsDesktop,
    columnsMobile,
    imageRatio,
    imageHover,
    cardRadius,
    cardBg,
    cardHoverLift,
    showCategory,
    showRating,
    saleBadgeStyle,
    atcStyle,
    atcPosition,
    showToolbar,
    paginationType,
    accentColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-grid-editor layout-${layoutMode} atc-${atcPosition}`,
    style: {
      width: "100%",
      maxWidth: "1200px",
      margin: "0 auto",
      padding: "40px 5%",
      boxSizing: "border-box",
    },
  });

  const mockProducts = Array.from({ length: Math.min(productCount, 8) }).map(
    (_, i) => ({
      id: i,
      title:
        i % 2 === 0
          ? "Premium Cotton Silhouette"
          : "Minimalist Architecture Chair",
      price: i % 3 === 0 ? "$120.00" : "$85.00",
      sale: i === 1 || i === 4 ? "$65.00" : null,
      category: i % 2 === 0 ? "Apparel" : "Furniture",
      image: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E",
    }),
  );

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Query Builder", "modern-core")}>
          <ToggleControl
            label={__("Use the page's products on shop pages", "modern-core")}
            help={__(
              "On the shop, category and search pages, show the products for that page so sorting, filters and pagination work. Turn off to always show the list below.",
              "modern-core",
            )}
            checked={attributes.inheritQuery}
            onChange={(val) => setAttributes({ inheritQuery: val })}
          />
          <RangeControl
            label={__("Products to Show", "modern-core")}
            value={productCount}
            onChange={(val) => setAttributes({ productCount: val })}
            min={2}
            max={24}
          />
          <SelectControl
            label={__("Sort By", "modern-core")}
            value={querySort}
            options={[
              { label: __("Newest arrivals", "modern-core"), value: "date" },
              { label: __("Best selling", "modern-core"), value: "popularity" },
              { label: __("Top rated", "modern-core"), value: "rating" },
              { label: __("On sale", "modern-core"), value: "sale" },
              { label: __("Featured", "modern-core"), value: "featured" },
              { label: __("Price: high to low", "modern-core"), value: "price-desc" },
              { label: __("Price: low to high", "modern-core"), value: "price" },
            ]}
            onChange={(val) => setAttributes({ querySort: val })}
          />
        </PanelBody>

        <PanelBody title={__("Grid Layout", "modern-core")} initialOpen={false}>
          <SelectControl
            label={__("Macro Layout", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Standard Grid", value: "grid" },
              { label: "List View", value: "list" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <RangeControl
            label={__("Columns (Desktop)", "modern-core")}
            value={columnsDesktop}
            onChange={(val) => setAttributes({ columnsDesktop: val })}
            min={2}
            max={6}
          />
          <RangeControl
            label={__("Columns (Mobile)", "modern-core")}
            value={columnsMobile}
            onChange={(val) => setAttributes({ columnsMobile: val })}
            min={1}
            max={3}
          />
        </PanelBody>

        <PanelBody
          title={__("Product Card Design", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Image Aspect Ratio", "modern-core")}
            value={imageRatio}
            options={[
              { label: "Portrait (3:4)", value: "3/4" },
              { label: "Square (1:1)", value: "1/1" },
              { label: "Landscape (16:9)", value: "16/9" },
              { label: "Uncropped (Auto)", value: "auto" },
            ]}
            onChange={(val) => setAttributes({ imageRatio: val })}
          />
          <SelectControl
            label={__("Image Hover Effect", "modern-core")}
            value={imageHover}
            options={[
              { label: "Cinematic Zoom", value: "zoom" },
              { label: "Second Image Swap", value: "swap" },
              { label: "None", value: "none" },
            ]}
            onChange={(val) => setAttributes({ imageHover: val })}
          />
          <SelectControl
            label={__("Add to Cart Style", "modern-core")}
            value={atcStyle}
            options={[
              { label: "Solid Button", value: "solid" },
              { label: "Outlined Button", value: "outline" },
              { label: "Text Link", value: "text" },
            ]}
            onChange={(val) => setAttributes({ atcStyle: val })}
          />
          <SelectControl
            label={__("Add to Cart Position", "modern-core")}
            value={atcPosition}
            options={[
              { label: "Below Product Info", value: "below" },
              { label: "Overlay on Image Hover", value: "overlay" },
            ]}
            onChange={(val) => setAttributes({ atcPosition: val })}
          />
          <ToggleControl
            label={__("Hover Card Lift/Shadow", "modern-core")}
            checked={cardHoverLift}
            onChange={(val) => setAttributes({ cardHoverLift: val })}
          />
          <ToggleControl
            label={__("Show Star Rating", "modern-core")}
            checked={showRating}
            onChange={(val) => setAttributes({ showRating: val })}
          />
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Card Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={cardBg}
            onChange={(val) => setAttributes({ cardBg: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Filtering & Pagination", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Top Toolbar (Sort/Filter)", "modern-core")}
            checked={showToolbar}
            onChange={(val) => setAttributes({ showToolbar: val })}
          />
          <SelectControl
            label={__("Pagination Style", "modern-core")}
            value={paginationType}
            options={[
              { label: "Load More Button", value: "load-more" },
              { label: "Standard Page Numbers", value: "numbers" },
              { label: "Infinite Scroll", value: "infinite" },
            ]}
            onChange={(val) => setAttributes({ paginationType: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
            .modern-core-grid-editor *, .modern-core-grid-editor *::before, .modern-core-grid-editor *::after { box-sizing: border-box; }
            .modern-core-grid-editor {
                --mc-cols: ${columnsDesktop};
                --mc-ratio: ${imageRatio};
                --mc-radius: ${cardRadius}px;
                --mc-bg: ${cardBg};
                --mc-accent: ${accentColor};
            }
            .mc-grid-container {
                display: grid;
                grid-template-columns: repeat(var(--mc-cols), 1fr);
                gap: 30px;
            }
            .layout-list .mc-grid-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .mc-product-card {
                background: var(--mc-bg);
                border-radius: var(--mc-radius);
                overflow: hidden;
                display: flex;
                flex-direction: column;
                border: 1px solid rgba(0,0,0,0.08);
                position: relative;
            }
            .layout-list .mc-product-card {
                flex-direction: row;
                align-items: center;
            }
            .mc-card-image {
                aspect-ratio: var(--mc-ratio);
                width: 100%;
                background-size: cover;
                background-position: center;
                position: relative;
                overflow: hidden;
            }
            .layout-list .mc-card-image {
                width: 300px;
                flex-shrink: 0;
            }
            .mc-card-info {
                padding: 20px;
                display: flex;
                flex-direction: column;
                flex: 1;
            }
            .mc-badge {
                position: absolute; top: 10px; left: 10px;
                background: #ef4444; color: #fff; font-size: 10px; font-weight: bold;
                padding: 4px 10px; border-radius: ${saleBadgeStyle === "pill" ? "50px" : "4px"}; z-index: 2;
            }
            .mc-atc-btn {
                margin-top: auto; padding: 12px; text-align: center; font-size: 13px; font-weight: bold;
                background: ${atcStyle === "solid" ? accentColor : "transparent"};
                color: ${atcStyle === "solid" ? "#fff" : accentColor};
                border: ${atcStyle === "outline" ? `2px solid ${accentColor}` : "none"};
                border-radius: ${cardRadius}px;
            }
            .atc-overlay .mc-atc-btn { display: none; }
          `}
        </style>

        {showToolbar && (
          <div
            style={{
              display: "flex",
              justifyContent: "space-between",
              marginBottom: "30px",
              paddingBottom: "15px",
              borderBottom: "1px solid #e2e8f0",
              fontSize: "13px",
            }}
          >
            <span style={{ opacity: 0.6 }}>
              Showing {mockProducts.length} results
            </span>
            <span style={{ fontWeight: "bold" }}>Sort by: Newest ↓</span>
          </div>
        )}

        <div className="mc-grid-container">
          {mockProducts.map((p) => (
            <div
              key={p.id}
              className="mc-product-card"
              style={{
                boxShadow: cardHoverLift
                  ? "0 10px 30px rgba(0,0,0,0.05)"
                  : "none",
              }}
            >
              <div
                className="mc-card-image"
                style={{ backgroundImage: `url(${p.image})` }}
              >
                {p.sale && <span className="mc-badge">SALE</span>}
                {atcPosition === "overlay" && (
                  <div
                    style={{
                      position: "absolute",
                      bottom: "10px",
                      left: "10px",
                      right: "10px",
                      background: "#fff",
                      color: "#000",
                      padding: "12px",
                      textAlign: "center",
                      borderRadius: cardRadius,
                      fontSize: "12px",
                      fontWeight: "bold",
                    }}
                  >
                    + Quick Add
                  </div>
                )}
              </div>
              <div className="mc-card-info">
                {showCategory && (
                  <span
                    style={{
                      fontSize: "11px",
                      textTransform: "uppercase",
                      opacity: 0.5,
                      marginBottom: "5px",
                    }}
                  >
                    {p.category}
                  </span>
                )}
                <h3
                  style={{
                    fontSize: "15px",
                    margin: "0 0 10px 0",
                    lineHeight: 1.3,
                  }}
                >
                  {p.title}
                </h3>
                {showRating && (
                  <div
                    style={{
                      color: "#f59e0b",
                      fontSize: "12px",
                      marginBottom: "10px",
                    }}
                  >
                    ★★★★★{" "}
                    <span style={{ opacity: 0.5, color: "#000" }}>(24)</span>
                  </div>
                )}

                <div
                  style={{
                    marginTop: "auto",
                    display: "flex",
                    gap: "10px",
                    alignItems: "center",
                    marginBottom: "15px",
                  }}
                >
                  {p.sale ? (
                    <>
                      <span
                        style={{
                          fontSize: "15px",
                          fontWeight: "bold",
                          color: "#ef4444",
                        }}
                      >
                        {p.sale}
                      </span>
                      <span
                        style={{
                          fontSize: "13px",
                          textDecoration: "line-through",
                          opacity: 0.5,
                        }}
                      >
                        {p.price}
                      </span>
                    </>
                  ) : (
                    <span style={{ fontSize: "15px", fontWeight: "bold" }}>
                      {p.price}
                    </span>
                  )}
                </div>

                {atcPosition === "below" && (
                  <div className="mc-atc-btn">Add to Cart</div>
                )}
              </div>
            </div>
          ))}
        </div>

        {paginationType === "load-more" && (
          <div style={{ textAlign: "center", marginTop: "40px" }}>
            <button
              style={{
                padding: "14px 40px",
                background: "transparent",
                border: "2px solid #cbd5e1",
                borderRadius: "50px",
                fontSize: "14px",
                fontWeight: "bold",
                cursor: "pointer",
              }}
            >
              Load More Products
            </button>
          </div>
        )}
      </div>
    </>
  );
}
