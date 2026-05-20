import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  ColorPalette,
  TextControl,
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
    imageBg,
    contentBg,
    textColor,
    accentColor,
    buttonStyle,
  } = attributes;

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

  // Mock Data for Editor
  const mockImage =
    "https://images.unsplash.com/photo-1521572163474-6f64f9fa023e?q=80&w=800&auto=format&fit=crop";
  const mockThumb1 =
    "https://images.unsplash.com/photo-1506422745199-563b7e735496?q=80&w=150&auto=format&fit=crop";
  const mockThumb2 =
    "https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=150&auto=format&fit=crop";

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
          Enter a WooCommerce Product ID in the sidebar to display it here.
        </p>
        <TextControl
          type="number"
          value={productId}
          onChange={(val) => setAttributes({ productId: parseInt(val) })}
          style={{ width: "200px", textAlign: "center" }}
        />
      </div>
    );
  }

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Data Routing", "modern-core")}>
          <TextControl
            type="number"
            label={__("WooCommerce Product ID", "modern-core")}
            value={productId}
            onChange={(val) => setAttributes({ productId: parseInt(val) })}
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
          <SelectControl
            label={__("Add to Cart Style", "modern-core")}
            value={buttonStyle}
            options={[
              { label: "Solid Fill", value: "solid" },
              { label: "Ghost Outline", value: "outline" },
            ]}
            onChange={(val) => setAttributes({ buttonStyle: val })}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Image Area Background", "modern-core")}</strong>
          </p>
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
        {/* Visual Zone (Image & Gallery) */}
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
              backgroundImage: `url(${mockImage})`,
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
                  backgroundImage: `url(${mockImage})`,
                  backgroundSize: "cover",
                }}
              ></div>
              <div
                style={{
                  width: "60px",
                  height: "60px",
                  borderRadius: "4px",
                  border: "2px solid transparent",
                  backgroundImage: `url(${mockThumb1})`,
                  backgroundSize: "cover",
                  opacity: 0.6,
                }}
              ></div>
              <div
                style={{
                  width: "60px",
                  height: "60px",
                  borderRadius: "4px",
                  border: "2px solid transparent",
                  backgroundImage: `url(${mockThumb2})`,
                  backgroundSize: "cover",
                  opacity: 0.6,
                }}
              ></div>
            </div>
          )}
        </div>

        {/* Content Zone (Data & Add to Cart) */}
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
            Cinematic Overcoat
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
              Tailored in Italy from lightweight virgin wool, this overcoat
              features an unstructured shoulder and a relaxed drape. The perfect
              layer for transitional weather.
            </p>
          )}

          {/* Mock Variations */}
          <div style={{ marginBottom: "30px" }}>
            <p
              style={{
                fontSize: "12px",
                fontWeight: "bold",
                textTransform: "uppercase",
                marginBottom: "10px",
              }}
            >
              Color: <span style={{ opacity: 0.6 }}>Charcoal</span>
            </p>
            <div style={{ display: "flex", gap: "10px" }}>
              <div
                style={{
                  width: "32px",
                  height: "32px",
                  borderRadius: "50%",
                  backgroundColor: "#334155",
                  outline: `2px solid ${accentColor}`,
                  outlineOffset: "2px",
                }}
              ></div>
              <div
                style={{
                  width: "32px",
                  height: "32px",
                  borderRadius: "50%",
                  backgroundColor: "#94a3b8",
                }}
              ></div>
            </div>
          </div>

          <div
            style={{
              padding: "16px",
              textAlign: "center",
              fontSize: "16px",
              fontWeight: "bold",
              borderRadius: "8px",
              cursor: "pointer",
              background: buttonStyle === "solid" ? accentColor : "transparent",
              color: buttonStyle === "solid" ? "#fff" : accentColor,
              border:
                buttonStyle === "outline" ? `2px solid ${accentColor}` : "none",
            }}
          >
            Add to Cart
          </div>
        </div>
      </div>
    </>
  );
}
