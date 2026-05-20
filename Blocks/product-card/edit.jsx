import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
  TextControl,
  Button,
  TextareaControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    sourceMode,
    productId,
    manualImage,
    manualTitle,
    manualPrice,
    manualDesc,
    orientation,
    imageRatio,
    cardRadius,
    cardBg,
    textColor,
    accentColor,
    hoverLift,
    showCategory,
    showRating,
    showDesc,
    showWishlist,
    atcStyle,
  } = attributes;

  const isHorizontal = orientation === "horizontal";
  const isManual = sourceMode === "manual";

  const blockProps = useBlockProps({
    className: `modern-core-standalone-card-editor orient-${orientation}`,
    style: {
      display: "flex",
      flexDirection: isHorizontal ? "row" : "column",
      backgroundColor: cardBg,
      color: textColor,
      borderRadius: `${cardRadius}px`,
      overflow: "hidden",
      boxShadow: hoverLift ? "0 10px 30px rgba(0,0,0,0.05)" : "none",
      border: "1px solid rgba(0,0,0,0.05)",
      maxWidth: isHorizontal ? "800px" : "400px",
      margin: "20px auto",
    },
  });

  // Mock Data for Dynamic Preview
  const previewImage =
    isManual && manualImage
      ? manualImage
      : "https://images.unsplash.com/photo-1521572163474-6f64f9fa023e?q=80&w=600&auto=format&fit=crop";
  const previewTitle = isManual ? manualTitle : "Cinematic Wool Overcoat";
  const previewPrice = isManual ? manualPrice : "$245.00";
  const previewDesc = isManual
    ? manualDesc
    : "Tailored in Italy from lightweight virgin wool, this overcoat features an unstructured shoulder and a relaxed drape.";

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Data Routing", "modern-core")}>
          <SelectControl
            label={__("Product Data Source", "modern-core")}
            value={sourceMode}
            options={[
              { label: "Dynamic (WooCommerce)", value: "dynamic" },
              { label: "Manual (Custom Content)", value: "manual" },
            ]}
            onChange={(val) => setAttributes({ sourceMode: val })}
          />
          {sourceMode === "dynamic" ? (
            <TextControl
              type="number"
              label={__("WooCommerce Product ID", "modern-core")}
              value={productId}
              onChange={(val) => setAttributes({ productId: parseInt(val) })}
              help={__(
                "Enter the ID of the product to pull live data.",
                "modern-core",
              )}
            />
          ) : (
            <>
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) =>
                    setAttributes({ manualImage: media.url })
                  }
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button
                      variant="secondary"
                      onClick={open}
                      style={{ width: "100%", marginBottom: "10px" }}
                    >
                      {manualImage ? "Change Image" : "Upload Image"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
              <TextControl
                label={__("Title", "modern-core")}
                value={manualTitle}
                onChange={(val) => setAttributes({ manualTitle: val })}
              />
              <TextControl
                label={__("Price", "modern-core")}
                value={manualPrice}
                onChange={(val) => setAttributes({ manualPrice: val })}
              />
              <TextareaControl
                label={__("Short Description", "modern-core")}
                value={manualDesc}
                onChange={(val) => setAttributes({ manualDesc: val })}
              />
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Architecture & Layout", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Orientation", "modern-core")}
            value={orientation}
            options={[
              { label: "Vertical (Standard)", value: "vertical" },
              { label: "Horizontal (Editorial)", value: "horizontal" },
            ]}
            onChange={(val) => setAttributes({ orientation: val })}
          />
          <SelectControl
            label={__("Image Aspect Ratio", "modern-core")}
            value={imageRatio}
            options={[
              { label: "Portrait (3:4)", value: "3/4" },
              { label: "Square (1:1)", value: "1/1" },
              { label: "Landscape (16:9)", value: "16/9" },
            ]}
            onChange={(val) => setAttributes({ imageRatio: val })}
          />
          <ToggleControl
            label={__("Hover Scale & Shadow Lift", "modern-core")}
            checked={hoverLift}
            onChange={(val) => setAttributes({ hoverLift: val })}
          />
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
        </PanelBody>

        <PanelBody
          title={__("Visibility Toggles", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Category Label", "modern-core")}
            checked={showCategory}
            onChange={(val) => setAttributes({ showCategory: val })}
          />
          <ToggleControl
            label={__("Show Star Rating", "modern-core")}
            checked={showRating}
            onChange={(val) => setAttributes({ showRating: val })}
          />
          <ToggleControl
            label={__("Show Excerpt / Description", "modern-core")}
            checked={showDesc}
            onChange={(val) => setAttributes({ showDesc: val })}
          />
          <ToggleControl
            label={__("Show Wishlist Icon", "modern-core")}
            checked={showWishlist}
            onChange={(val) => setAttributes({ showWishlist: val })}
          />
          <SelectControl
            label={__("Button Style", "modern-core")}
            value={atcStyle}
            options={[
              { label: "Solid Fill", value: "solid" },
              { label: "Ghost Outline", value: "outline" },
              { label: "Text Link", value: "text" },
            ]}
            onChange={(val) => setAttributes({ atcStyle: val })}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Card Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={cardBg}
            onChange={(val) => setAttributes({ cardBg: val })}
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
        {/* Image Zone */}
        <div
          style={{
            width: isHorizontal ? "45%" : "100%",
            aspectRatio: isHorizontal ? "auto" : imageRatio,
            backgroundImage: `url(${previewImage})`,
            backgroundSize: "cover",
            backgroundPosition: "center",
            position: "relative",
          }}
        >
          {showWishlist && (
            <div
              style={{
                position: "absolute",
                top: "15px",
                right: "15px",
                width: "32px",
                height: "32px",
                background: "#fff",
                borderRadius: "50%",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                boxShadow: "0 2px 10px rgba(0,0,0,0.1)",
              }}
            >
              ♡
            </div>
          )}
        </div>

        {/* Content Zone */}
        <div
          style={{
            width: isHorizontal ? "55%" : "100%",
            padding: "30px",
            display: "flex",
            flexDirection: "column",
            justifyContent: "center",
          }}
        >
          {showCategory && (
            <span
              style={{
                fontSize: "11px",
                textTransform: "uppercase",
                letterSpacing: "1px",
                opacity: 0.5,
                marginBottom: "8px",
              }}
            >
              Outerwear
            </span>
          )}
          <h3
            style={{
              margin: "0 0 10px 0",
              fontSize: isHorizontal ? "24px" : "18px",
              fontWeight: "bold",
            }}
          >
            {previewTitle}
          </h3>

          {showRating && (
            <div
              style={{
                color: "#f59e0b",
                fontSize: "12px",
                marginBottom: "15px",
              }}
            >
              ★★★★★ <span style={{ color: textColor, opacity: 0.5 }}>(42)</span>
            </div>
          )}

          {showDesc && (
            <p
              style={{
                fontSize: "14px",
                opacity: 0.7,
                marginBottom: "20px",
                lineHeight: 1.6,
              }}
            >
              {previewDesc}
            </p>
          )}

          <div
            style={{
              marginTop: "auto",
              display: "flex",
              alignItems: "center",
              justifyContent: "space-between",
            }}
          >
            <span style={{ fontSize: "18px", fontWeight: "bold" }}>
              {previewPrice}
            </span>
          </div>

          <div
            style={{
              marginTop: "20px",
              padding: "14px",
              textAlign: "center",
              fontSize: "14px",
              fontWeight: "bold",
              borderRadius: `${cardRadius}px`,
              background: atcStyle === "solid" ? accentColor : "transparent",
              color: atcStyle === "solid" ? "#fff" : accentColor,
              border:
                atcStyle === "outline" ? `2px solid ${accentColor}` : "none",
            }}
          >
            Add to Cart
          </div>
        </div>
      </div>
    </>
  );
}
