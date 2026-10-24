import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  ColorPalette,
  TextControl,
  ComboboxControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    productId,
    layoutMode,
    preHeadline,
    showRating,
    showPrice,
    showExcerpt,
    showGallery,
    showQuantity,
    showBuyNow,
    imageBg,
    contentBg,
    textColor,
    accentColor,
    buttonStyle,
    buyNowStyle,
  } = attributes;

  const { products, isResolving } = useSelect((select) => {
    const { getEntityRecords, isResolving } = select("core");
    const query = { per_page: 100, status: "publish", _embed: true };
    return {
      products: getEntityRecords("postType", "product", query),
      isResolving: isResolving("getEntityRecords", [
        "postType",
        "product",
        query,
      ]),
    };
  }, []);

  const decodeEntities = (html) => {
    if (!html) return "";
    const txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
  };

  const productOptions = React.useMemo(() => {
    if (!products) return [];
    return products.map((p) => ({
      label: decodeEntities(p.title.rendered) || `Product ID: ${p.id}`,
      value: p.id,
    }));
  }, [products]);

  const selectedProduct = React.useMemo(() => {
    if (!products || !productId) return null;
    return products.find((p) => p.id === productId);
  }, [products, productId]);

  const blockProps = useBlockProps({
    className: `modern-core-featured-editor layout-${layoutMode}`,
    style: {
      display: "flex",
      flexDirection: layoutMode === "stacked" ? "column" : "row",
      backgroundColor: contentBg,
      color: textColor,
      borderRadius: "16px",
      overflow: "hidden",
      boxShadow: "0 20px 40px rgba(0,0,0,0.05)",
      border: "1px solid rgba(0,0,0,0.05)",
      margin: "40px auto",
      maxWidth: layoutMode === "stacked" ? "600px" : "1200px",
      position: "relative",
    },
  });

  const displayTitle = selectedProduct
    ? decodeEntities(selectedProduct.title.rendered)
    : "Cinematic Overcoat";

  const rawExcerpt = selectedProduct?.excerpt?.rendered || "";
  const displayExcerpt = rawExcerpt
    ? decodeEntities(rawExcerpt.replace(/(<([^>]+)>)/gi, ""))
    : "Tailored in Italy from lightweight virgin wool, this overcoat features an unstructured shoulder and a relaxed drape.";

  const displayImage =
    selectedProduct?._embedded?.["wp:featuredmedia"]?.[0]?.source_url ||
    "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E";

  if (!productId || productId === 0) {
    return (
      <div
        {...blockProps}
        style={{
          ...blockProps.style,
          flexDirection: "column",
          alignItems: "center",
          padding: "60px",
          backgroundColor: "#f1f5f9",
        }}
      >
        <h3 style={{ margin: "0 0 15px 0" }}>Setup Featured Product</h3>
        <p style={{ opacity: 0.6, marginBottom: "20px" }}>
          Select a WooCommerce product to feature in this block.
        </p>
        <div style={{ width: "300px" }}>
          <ComboboxControl
            value={productId}
            options={productOptions}
            onChange={(val) =>
              setAttributes({ productId: parseInt(val, 10) || 0 })
            }
            help={isResolving ? "Loading products..." : ""}
          />
        </div>
      </div>
    );
  }

  const getBtnStyle = (styleType) => ({
    padding: "16px",
    textAlign: "center",
    fontSize: "16px",
    fontWeight: "bold",
    borderRadius: "8px",
    cursor: "pointer",
    flex: 1,
    background: styleType === "solid" ? accentColor : "transparent",
    color: styleType === "solid" ? "#fff" : accentColor,
    border:
      styleType === "outline"
        ? `2px solid ${accentColor}`
        : "2px solid transparent",
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Data Routing", "modern-core")}>
          <ComboboxControl
            label={__("Select Product", "modern-core")}
            value={productId}
            options={productOptions}
            onChange={(val) =>
              setAttributes({ productId: parseInt(val, 10) || 0 })
            }
            help={
              isResolving ? "Loading inventory..." : "Search by product name"
            }
          />
        </PanelBody>

        <PanelBody
          title={__("Architecture", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Side-by-Side (Split)", value: "side-by-side" },
              { label: "Stacked (Narrow)", value: "stacked" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Content Elements", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Pre-Headline Badge", "modern-core")}
            value={preHeadline}
            onChange={(val) => setAttributes({ preHeadline: val })}
          />
          <ToggleControl
            label={__("Show Rating", "modern-core")}
            checked={showRating}
            onChange={(val) => setAttributes({ showRating: val })}
          />
          <ToggleControl
            label={__("Show Price", "modern-core")}
            checked={showPrice}
            onChange={(val) => setAttributes({ showPrice: val })}
          />
          <ToggleControl
            label={__("Show Description", "modern-core")}
            checked={showExcerpt}
            onChange={(val) => setAttributes({ showExcerpt: val })}
          />
          <ToggleControl
            label={__("Show Image Gallery", "modern-core")}
            checked={showGallery}
            onChange={(val) => setAttributes({ showGallery: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Commerce Actions", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Quantity Selector", "modern-core")}
            checked={showQuantity}
            onChange={(val) => setAttributes({ showQuantity: val })}
          />
          <SelectControl
            label={__("Add to Cart Style", "modern-core")}
            value={buttonStyle}
            options={[
              { label: "Solid Fill", value: "solid" },
              { label: "Ghost Outline", value: "outline" },
            ]}
            onChange={(val) => setAttributes({ buttonStyle: val })}
          />
          <ToggleControl
            label={__("Enable 'Buy Now' Redirect", "modern-core")}
            checked={showBuyNow}
            onChange={(val) => setAttributes({ showBuyNow: val })}
          />
          {showBuyNow && (
            <SelectControl
              label={__("Buy Now Style", "modern-core")}
              value={buyNowStyle}
              options={[
                { label: "Solid Fill", value: "solid" },
                { label: "Ghost Outline", value: "outline" },
              ]}
              onChange={(val) => setAttributes({ buyNowStyle: val })}
            />
          )}
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <ColorPalette
            value={imageBg}
            onChange={(val) => setAttributes({ imageBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Content Area Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={contentBg}
            onChange={(val) => setAttributes({ contentBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            flex: 1,
            backgroundColor: imageBg,
            padding: "40px",
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <div
            style={{
              width: "100%",
              aspectRatio: "3/4",
              backgroundImage: `url(${displayImage})`,
              backgroundSize: "cover",
              backgroundPosition: "center",
              borderRadius: "8px",
              boxShadow: "0 10px 30px rgba(0,0,0,0.1)",
            }}
          ></div>
          {showGallery && (
            <div
              style={{
                display: "flex",
                gap: "15px",
                marginTop: "20px",
                width: "100%",
                justifyContent: "center",
              }}
            >
              <div
                style={{
                  width: "60px",
                  height: "60px",
                  borderRadius: "4px",
                  border: `2px solid ${accentColor}`,
                  backgroundImage: `url(${displayImage})`,
                  backgroundSize: "cover",
                }}
              ></div>
              <div
                style={{
                  width: "60px",
                  height: "60px",
                  borderRadius: "4px",
                  backgroundColor: "rgba(0,0,0,0.1)",
                  opacity: 0.6,
                }}
              ></div>
              <div
                style={{
                  width: "60px",
                  height: "60px",
                  borderRadius: "4px",
                  backgroundColor: "rgba(0,0,0,0.1)",
                  opacity: 0.6,
                }}
              ></div>
            </div>
          )}
        </div>

        <div
          style={{
            flex: 1,
            padding: "60px 8%",
            display: "flex",
            flexDirection: "column",
            justifyContent: "center",
          }}
        >
          {preHeadline && (
            <span
              style={{
                fontSize: "11px",
                textTransform: "uppercase",
                letterSpacing: "2px",
                color: accentColor,
                fontWeight: "bold",
                marginBottom: "15px",
              }}
            >
              {preHeadline}
            </span>
          )}
          <h2
            style={{
              fontSize: "36px",
              fontWeight: "800",
              lineHeight: 1.1,
              margin: "0 0 15px 0",
            }}
          >
            {displayTitle}
          </h2>
          {showRating && (
            <div
              style={{
                color: "#f59e0b",
                fontSize: "14px",
                marginBottom: "20px",
              }}
            >
              ★★★★★{" "}
              <span
                style={{ color: textColor, opacity: 0.5, fontSize: "12px" }}
              >
                (128 Reviews)
              </span>
            </div>
          )}
          {showPrice && (
            <div
              style={{
                fontSize: "24px",
                fontWeight: "bold",
                marginBottom: "20px",
              }}
            >
              $245.00
            </div>
          )}
          {showExcerpt && (
            <p
              style={{
                fontSize: "16px",
                opacity: 0.7,
                lineHeight: 1.6,
                marginBottom: "30px",
              }}
            >
              {displayExcerpt}
            </p>
          )}

          {showQuantity && (
            <div
              style={{
                display: "flex",
                alignItems: "center",
                border: "1px solid rgba(0,0,0,0.1)",
                borderRadius: "8px",
                overflow: "hidden",
                width: "120px",
                marginBottom: "20px",
              }}
            >
              <div
                style={{
                  flex: 1,
                  textAlign: "center",
                  padding: "10px",
                  background: imageBg,
                  cursor: "pointer",
                  fontWeight: "bold",
                }}
              >
                −
              </div>
              <div
                style={{
                  flex: 1,
                  textAlign: "center",
                  padding: "10px",
                  fontWeight: "bold",
                }}
              >
                1
              </div>
              <div
                style={{
                  flex: 1,
                  textAlign: "center",
                  padding: "10px",
                  background: imageBg,
                  cursor: "pointer",
                  fontWeight: "bold",
                }}
              >
                +
              </div>
            </div>
          )}

          <div style={{ display: "flex", gap: "15px", flexWrap: "wrap" }}>
            <div style={getBtnStyle(buttonStyle)}>Add to Cart</div>
            {showBuyNow && <div style={getBtnStyle(buyNowStyle)}>Buy Now</div>}
          </div>
        </div>
      </div>
    </>
  );
}
