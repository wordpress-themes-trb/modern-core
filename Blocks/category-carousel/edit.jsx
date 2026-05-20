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
    sourceMode,
    catCount,
    colsDesktop,
    colsTablet,
    colsMobile,
    tileShape,
    mobileMorphCircle,
    labelPlacement,
    showCount,
    overlayOpacity,
    hoverEffect,
    showArrows,
    showDots,
    autoplay,
    delay,
    accentColor,
    textColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-cat-carousel-editor shape-${tileShape} label-${labelPlacement}`,
    style: { width: "100%", padding: "40px 0", overflow: "hidden" },
  });

  const mockCats = Array.from({ length: 8 }).map((_, i) => {
    const names = [
      "Women",
      "Men",
      "Accessories",
      "Footwear",
      "Outerwear",
      "Beauty",
      "Home",
      "Gifts",
    ];
    return {
      id: i,
      name: names[i % names.length],
      count: (i + 1) * 12,
      image: `https://images.unsplash.com/photo-${i % 2 === 0 ? "1521572163474-6f64f9fa023e" : "1506422745199-563b7e735496"}?q=80&w=400&auto=format&fit=crop`,
    };
  });

  let borderRadius = "0";
  let aspectRatio = "1/1";
  if (tileShape === "rounded-square") borderRadius = "12px";
  if (tileShape === "circle") borderRadius = "50%";
  if (tileShape === "pill") {
    borderRadius = "50px";
    aspectRatio = "2/3";
  }
  if (tileShape === "rectangle") {
    borderRadius = "0";
    aspectRatio = "4/5";
  }

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Data Routing", "modern-core")}>
          <SelectControl
            label={__("Category Source", "modern-core")}
            value={sourceMode}
            options={[
              { label: "Top Categories (Dynamic)", value: "dynamic" },
              { label: "Manual Selection", value: "manual" },
            ]}
            onChange={(val) => setAttributes({ sourceMode: val })}
          />
          <RangeControl
            label={__("Total Categories to Show", "modern-core")}
            value={catCount}
            onChange={(val) => setAttributes({ catCount: val })}
            min={4}
            max={16}
          />
        </PanelBody>

        <PanelBody
          title={__("Carousel Physics", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Visible Desktop Tiles", "modern-core")}
            value={colsDesktop}
            onChange={(val) => setAttributes({ colsDesktop: val })}
            min={3}
            max={8}
            step={0.5}
          />
          <RangeControl
            label={__("Visible Tablet Tiles", "modern-core")}
            value={colsTablet}
            onChange={(val) => setAttributes({ colsTablet: val })}
            min={2}
            max={6}
            step={0.5}
          />
          <RangeControl
            label={__("Visible Mobile Tiles (Peek)", "modern-core")}
            value={colsMobile}
            onChange={(val) => setAttributes({ colsMobile: val })}
            min={1.5}
            max={4.5}
            step={0.1}
          />
          <ToggleControl
            label={__("Show Arrows (Desktop)", "modern-core")}
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
        </PanelBody>

        <PanelBody
          title={__("Tile Geometry & Design", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Tile Shape (Desktop)", "modern-core")}
            value={tileShape}
            options={[
              { label: "Rounded Square", value: "rounded-square" },
              { label: "Circle", value: "circle" },
              { label: "Sharp Rectangle", value: "rectangle" },
              { label: "Vertical Pill", value: "pill" },
            ]}
            onChange={(val) => setAttributes({ tileShape: val })}
          />
          <ToggleControl
            label={__("Morph to Circles on Mobile", "modern-core")}
            checked={mobileMorphCircle}
            onChange={(val) => setAttributes({ mobileMorphCircle: val })}
            help={__(
              "Automatically forces circular images with text below on mobile devices for better UX.",
              "modern-core",
            )}
          />
          <SelectControl
            label={__("Label Placement", "modern-core")}
            value={labelPlacement}
            options={[
              { label: "Overlaid on Image", value: "overlay" },
              { label: "Below Image", value: "below" },
            ]}
            onChange={(val) => setAttributes({ labelPlacement: val })}
            disabled={tileShape === "circle"} // Circles usually force text below
          />
          <ToggleControl
            label={__("Show Product Count", "modern-core")}
            checked={showCount}
            onChange={(val) => setAttributes({ showCount: val })}
          />
          {labelPlacement === "overlay" && (
            <RangeControl
              label={__("Overlay Opacity (%)", "modern-core")}
              value={overlayOpacity}
              onChange={(val) => setAttributes({ overlayOpacity: val })}
              min={0}
              max={90}
            />
          )}
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Hover states)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-cat-carousel-editor {
                        --mc-cols: ${colsDesktop};
                        --mc-radius: ${borderRadius};
                        --mc-ratio: ${aspectRatio};
                        --mc-txt: ${textColor};
                        --mc-opacity: ${overlayOpacity / 100};
                    }
                    .mc-cc-track-editor {
                        display: flex; gap: 20px; overflow-x: auto; scroll-snap-type: x mandatory; padding: 0 5% 20px 5%; scrollbar-width: thin;
                    }
                    .mc-cc-tile-editor {
                        flex: 0 0 calc((100% - (var(--mc-cols) - 1) * 20px) / var(--mc-cols));
                        scroll-snap-align: start;
                        position: relative;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }
                    .mc-cc-img-zone {
                        width: 100%; aspect-ratio: var(--mc-ratio); border-radius: var(--mc-radius);
                        background-size: cover; background-position: center; position: relative; overflow: hidden;
                    }
                    `}
        </style>

        <div className="mc-cc-track-editor">
          {mockCats.map((cat) => (
            <div key={cat.id} className="mc-cc-tile-editor">
              <div
                className="mc-cc-img-zone"
                style={{ backgroundImage: `url(${cat.image})` }}
              >
                {labelPlacement === "overlay" && tileShape !== "circle" && (
                  <>
                    <div
                      style={{
                        position: "absolute",
                        inset: 0,
                        background: `linear-gradient(to top, rgba(0,0,0,calc(var(--mc-opacity) + 0.3)), rgba(0,0,0,0))`,
                      }}
                    ></div>
                    <div
                      style={{
                        position: "absolute",
                        inset: 0,
                        padding: "20px",
                        display: "flex",
                        flexDirection: "column",
                        justifyContent: "center",
                        alignItems: "center",
                        textAlign: "center",
                        color: textColor,
                      }}
                    >
                      <h4
                        style={{
                          margin: 0,
                          fontSize: "18px",
                          fontWeight: "bold",
                        }}
                      >
                        {cat.name}
                      </h4>
                      {showCount && (
                        <span
                          style={{
                            fontSize: "12px",
                            opacity: 0.8,
                            marginTop: "5px",
                          }}
                        >
                          {cat.count} items
                        </span>
                      )}
                    </div>
                  </>
                )}
              </div>

              {(labelPlacement === "below" || tileShape === "circle") && (
                <div
                  style={{
                    padding: "15px 0",
                    textAlign: "center",
                    color: accentColor,
                  }}
                >
                  <h4
                    style={{ margin: 0, fontSize: "16px", fontWeight: "bold" }}
                  >
                    {cat.name}
                  </h4>
                  {showCount && (
                    <span
                      style={{
                        fontSize: "12px",
                        opacity: 0.6,
                        marginTop: "2px",
                        display: "block",
                      }}
                    >
                      {cat.count} items
                    </span>
                  )}
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
