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
  TextControl,
  TextareaControl,
  ColorPalette,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    sectionTitle,
    showAggregate,
    aggregateScore,
    aggregateCount,
    colsDesktop,
    colsMobile,
    showAvatar,
    showRating,
    cardBg,
    cardRadius,
    shadowOpacity,
    textColor,
    starColor,
    testimonials,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-test-editor layout-${layoutMode}`,
    style: { width: "100%", padding: "40px 5%", backgroundColor: "#f8fafc" },
  });

  const updateReview = (index, key, value) => {
    const newTestimonials = [...testimonials];
    newTestimonials[index][key] = value;
    setAttributes({ testimonials: newTestimonials });
  };

  const addReview = () => {
    setAttributes({
      testimonials: [
        ...testimonials,
        {
          name: "New Customer",
          title: "Verified Buyer",
          quote: "A new glowing review.",
          rating: 5,
          avatar: "",
        },
      ],
    });
  };

  const removeReview = (index) => {
    const newTestimonials = testimonials.filter((_, i) => i !== index);
    setAttributes({ testimonials: newTestimonials });
  };

  const renderStars = (count) => {
    return "★".repeat(count) + "☆".repeat(5 - count);
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Section Header", "modern-core")}>
          <TextControl
            label={__("Section Title", "modern-core")}
            value={sectionTitle}
            onChange={(val) => setAttributes({ sectionTitle: val })}
          />
          <ToggleControl
            label={__("Show Aggregate Rating", "modern-core")}
            checked={showAggregate}
            onChange={(val) => setAttributes({ showAggregate: val })}
          />
          {showAggregate && (
            <>
              <TextControl
                label={__("Aggregate Score", "modern-core")}
                value={aggregateScore}
                onChange={(val) => setAttributes({ aggregateScore: val })}
              />
              <TextControl
                label={__("Total Review Count", "modern-core")}
                value={aggregateCount}
                onChange={(val) => setAttributes({ aggregateCount: val })}
              />
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Architecture & Design", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Standard Grid", value: "grid" },
              { label: "Masonry (Staggered)", value: "masonry" },
              { label: "Horizontal Carousel", value: "carousel" },
              { label: "Featured Single", value: "single" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          {layoutMode !== "single" && (
            <RangeControl
              label={__("Desktop Columns", "modern-core")}
              value={colsDesktop}
              onChange={(val) => setAttributes({ colsDesktop: val })}
              min={1}
              max={5}
            />
          )}
          <ToggleControl
            label={__("Show Avatars", "modern-core")}
            checked={showAvatar}
            onChange={(val) => setAttributes({ showAvatar: val })}
          />
          <ToggleControl
            label={__("Show Star Ratings", "modern-core")}
            checked={showRating}
            onChange={(val) => setAttributes({ showRating: val })}
          />
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={30}
          />
          <RangeControl
            label={__("Shadow Opacity (%)", "modern-core")}
            value={shadowOpacity}
            onChange={(val) => setAttributes({ shadowOpacity: val })}
            min={0}
            max={30}
          />
        </PanelBody>

        <PanelBody
          title={__("Review Data Management", "modern-core")}
          initialOpen={false}
        >
          {testimonials.map((t, index) => (
            <div
              key={index}
              style={{
                marginBottom: "20px",
                padding: "15px",
                border: "1px solid #e2e8f0",
                borderRadius: "8px",
              }}
            >
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  marginBottom: "10px",
                }}
              >
                <strong>Review {index + 1}</strong>
                <span
                  style={{
                    color: "#ef4444",
                    cursor: "pointer",
                    fontSize: "12px",
                  }}
                  onClick={() => removeReview(index)}
                >
                  Remove
                </span>
              </div>
              <TextControl
                label="Customer Name"
                value={t.name}
                onChange={(val) => updateReview(index, "name", val)}
              />
              <TextControl
                label="Title / Location"
                value={t.title}
                onChange={(val) => updateReview(index, "title", val)}
              />
              <TextareaControl
                label="Quote"
                value={t.quote}
                onChange={(val) => updateReview(index, "quote", val)}
              />
              <RangeControl
                label="Star Rating"
                value={t.rating}
                onChange={(val) => updateReview(index, "rating", val)}
                min={1}
                max={5}
              />
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => updateReview(index, "avatar", media.url)}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button
                      variant="secondary"
                      onClick={open}
                      style={{ width: "100%" }}
                    >
                      {t.avatar ? "Change Avatar" : "Add Avatar"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
            </div>
          ))}
          <Button
            variant="primary"
            onClick={addReview}
            style={{ width: "100%", justifyContent: "center" }}
          >
            + Add Review
          </Button>
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
            <strong>{__("Star Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={starColor}
            onChange={(val) => setAttributes({ starColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Header */}
        <div
          style={{
            display: "flex",
            justifyContent: "space-between",
            alignItems: "flex-end",
            marginBottom: "40px",
            flexWrap: "wrap",
            gap: "15px",
          }}
        >
          <h2
            style={{
              margin: 0,
              fontSize: "32px",
              fontWeight: "800",
              color: "#0f172a",
            }}
          >
            {sectionTitle}
          </h2>
          {showAggregate && (
            <div style={{ display: "flex", alignItems: "center", gap: "10px" }}>
              <span
                style={{ fontSize: "32px", fontWeight: "800", lineHeight: 1 }}
              >
                {aggregateScore}
              </span>
              <div>
                <div style={{ color: starColor, fontSize: "16px" }}>★★★★★</div>
                <div style={{ fontSize: "12px", opacity: 0.6 }}>
                  based on {aggregateCount} reviews
                </div>
              </div>
            </div>
          )}
        </div>

        {/* Grid / Layout Container */}
        <div
          style={{
            display:
              layoutMode === "carousel"
                ? "flex"
                : layoutMode === "single"
                  ? "block"
                  : "grid",
            gridTemplateColumns:
              layoutMode === "grid" || layoutMode === "masonry"
                ? `repeat(${colsDesktop}, 1fr)`
                : "none",
            alignItems: layoutMode === "masonry" ? "start" : "stretch",
            gap: "30px",
            overflowX: layoutMode === "carousel" ? "auto" : "visible",
          }}
        >
          {testimonials.map((t, i) => (
            <div
              key={i}
              style={{
                background: cardBg,
                padding: layoutMode === "single" ? "60px" : "30px",
                borderRadius: `${cardRadius}px`,
                boxShadow: `0 10px 30px rgba(0,0,0,${shadowOpacity / 100})`,
                color: textColor,
                minWidth: layoutMode === "carousel" ? "300px" : "auto",
                border: "1px solid rgba(0,0,0,0.05)",
                display: "flex",
                flexDirection: "column",
                textAlign: layoutMode === "single" ? "center" : "left",
              }}
            >
              {showRating && (
                <div
                  style={{
                    color: starColor,
                    fontSize: "18px",
                    marginBottom: "15px",
                  }}
                >
                  {renderStars(t.rating)}
                </div>
              )}

              <p
                style={{
                  fontSize: layoutMode === "single" ? "24px" : "16px",
                  lineHeight: 1.6,
                  opacity: 0.9,
                  fontStyle: "italic",
                  margin: "0 0 25px 0",
                  flex: 1,
                }}
              >
                "{t.quote}"
              </p>

              <div
                style={{
                  display: "flex",
                  alignItems: "center",
                  justifyContent:
                    layoutMode === "single" ? "center" : "flex-start",
                  gap: "15px",
                }}
              >
                {showAvatar &&
                  (t.avatar ? (
                    <img
                      src={t.avatar}
                      alt="Avatar"
                      style={{
                        width: "48px",
                        height: "48px",
                        borderRadius: "50%",
                        objectFit: "cover",
                      }}
                    />
                  ) : (
                    <div
                      style={{
                        width: "48px",
                        height: "48px",
                        borderRadius: "50%",
                        background: "#e2e8f0",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "center",
                        fontWeight: "bold",
                        color: "#94a3b8",
                      }}
                    >
                      {t.name.charAt(0)}
                    </div>
                  ))}
                <div>
                  <div style={{ fontWeight: "bold", fontSize: "15px" }}>
                    {t.name}
                  </div>
                  {t.title && (
                    <div style={{ fontSize: "12px", opacity: 0.6 }}>
                      {t.title}
                    </div>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
