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
    sectionTitle,
    sectionSubtitle,
    viewAllText,
    viewAllLink,
    productCount,
    querySort,
    colsDesktop,
    colsTablet,
    colsMobile,
    imageRatio,
    cardBg,
    cardRadius,
    cardHoverLift,
    showRating,
    atcPosition,
    showArrows,
    showDots,
    autoplay,
    delay,
    accentColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-carousel-editor",
    style: { width: "100%", padding: "40px 0", overflow: "hidden" },
  });

  const mockProducts = Array.from({ length: 8 }).map((_, i) => ({
    id: i,
    title:
      i % 2 === 0
        ? "Premium Cotton Silhouette"
        : "Minimalist Architecture Chair",
    price: i % 3 === 0 ? "$120.00" : "$85.00",
    image: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E",
  }));

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Section Header", "modern-core")}>
          <TextControl
            label={__("Title", "modern-core")}
            value={sectionTitle}
            onChange={(val) => setAttributes({ sectionTitle: val })}
          />
          <TextControl
            label={__("Subtitle", "modern-core")}
            value={sectionSubtitle}
            onChange={(val) => setAttributes({ sectionSubtitle: val })}
          />
          <TextControl
            label={__("View All Text", "modern-core")}
            value={viewAllText}
            onChange={(val) => setAttributes({ viewAllText: val })}
          />
          <TextControl
            label={__("View All URL", "modern-core")}
            value={viewAllLink}
            onChange={(val) => setAttributes({ viewAllLink: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Carousel Physics", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Visible Desktop Cards", "modern-core")}
            value={colsDesktop}
            onChange={(val) => setAttributes({ colsDesktop: val })}
            min={2}
            max={6}
            step={0.5}
          />
          <RangeControl
            label={__("Visible Tablet Cards", "modern-core")}
            value={colsTablet}
            onChange={(val) => setAttributes({ colsTablet: val })}
            min={1}
            max={4}
            step={0.5}
          />
          <RangeControl
            label={__("Visible Mobile Cards (Peek)", "modern-core")}
            value={colsMobile}
            onChange={(val) => setAttributes({ colsMobile: val })}
            min={1}
            max={3}
            step={0.1}
            help="Decimals (e.g. 1.2) allow the next card to peek onto the screen."
          />
          <ToggleControl
            label={__("Show Arrows", "modern-core")}
            checked={showArrows}
            onChange={(val) => setAttributes({ showArrows: val })}
          />
          <ToggleControl
            label={__("Show Dots", "modern-core")}
            checked={showDots}
            onChange={(val) => setAttributes({ showDots: val })}
          />
          <ToggleControl
            label={__("Enable Autoplay", "modern-core")}
            checked={autoplay}
            onChange={(val) => setAttributes({ autoplay: val })}
          />
          {autoplay && (
            <RangeControl
              label={__("Autoplay Delay (ms)", "modern-core")}
              value={delay}
              onChange={(val) => setAttributes({ delay: val })}
              min={2000}
              max={8000}
              step={500}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Card Design & Query", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Total Products to Query", "modern-core")}
            value={productCount}
            onChange={(val) => setAttributes({ productCount: val })}
            min={4}
            max={24}
          />
          <SelectControl
            label={__("Image Ratio", "modern-core")}
            value={imageRatio}
            options={[
              { label: "Portrait (3:4)", value: "3/4" },
              { label: "Square (1:1)", value: "1/1" },
            ]}
            onChange={(val) => setAttributes({ imageRatio: val })}
          />
          <ToggleControl
            label={__("Hover Card Lift", "modern-core")}
            checked={cardHoverLift}
            onChange={(val) => setAttributes({ cardHoverLift: val })}
          />
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-carousel-editor {
                        --mc-cols: ${colsDesktop};
                        --mc-ratio: ${imageRatio};
                        --mc-radius: ${cardRadius}px;
                        --mc-bg: ${cardBg};
                        --mc-accent: ${accentColor};
                    }
                    .mc-carousel-track-editor {
                        display: flex;
                        gap: 20px;
                        overflow-x: auto;
                        scroll-snap-type: x mandatory;
                        padding-bottom: 20px;
                        scrollbar-width: thin;
                    }
                    .mc-carousel-card-editor {
                        flex: 0 0 calc((100% - (var(--mc-cols) - 1) * 20px) / var(--mc-cols));
                        scroll-snap-align: start;
                        background: var(--mc-bg);
                        border-radius: var(--mc-radius);
                        border: 1px solid rgba(0,0,0,0.05);
                        display: flex;
                        flex-direction: column;
                    }
                    `}
        </style>

        {/* Section Header */}
        <div
          style={{
            display: "flex",
            justifyContent: "space-between",
            alignItems: "flex-end",
            marginBottom: "30px",
            padding: "0 5%",
          }}
        >
          <div>
            {sectionSubtitle && (
              <p
                style={{
                  margin: "0 0 5px 0",
                  fontSize: "12px",
                  textTransform: "uppercase",
                  letterSpacing: "1px",
                  color: accentColor,
                }}
              >
                {sectionSubtitle}
              </p>
            )}
            <h2 style={{ margin: 0, fontSize: "28px", fontWeight: "800" }}>
              {sectionTitle}
            </h2>
          </div>
          {viewAllText && (
            <a
              href="#"
              style={{
                fontSize: "14px",
                fontWeight: "bold",
                color: accentColor,
                textDecoration: "none",
              }}
            >
              {viewAllText}
            </a>
          )}
        </div>

        {/* Track */}
        <div className="mc-carousel-track-editor" style={{ padding: "0 5%" }}>
          {mockProducts.map((p) => (
            <div
              key={p.id}
              className="mc-carousel-card-editor"
              style={{
                boxShadow: cardHoverLift
                  ? "0 10px 30px rgba(0,0,0,0.05)"
                  : "none",
              }}
            >
              <div
                style={{
                  aspectRatio: imageRatio,
                  backgroundImage: `url(${p.image})`,
                  backgroundSize: "cover",
                  backgroundPosition: "center",
                  borderRadius: `${cardRadius}px ${cardRadius}px 0 0`,
                }}
              ></div>
              <div style={{ padding: "20px" }}>
                <h3 style={{ fontSize: "15px", margin: "0 0 10px 0" }}>
                  {p.title}
                </h3>
                <div style={{ fontSize: "15px", fontWeight: "bold" }}>
                  {p.price}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
