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
    layoutMode,
    colsDesktop,
    colsMobile,
    imageRatio,
    labelPlacement,
    textPosition,
    showCount,
    hoverEffect,
    cardRadius,
    overlayOpacity,
    accentColor,
    textColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-category-editor layout-${layoutMode} label-${labelPlacement}`,
    style: { width: "100%", padding: "40px 0" },
  });

  const mockCats = Array.from({ length: catCount }).map((_, i) => {
    const names = [
      "Women",
      "Men",
      "Accessories",
      "Footwear",
      "Outerwear",
      "Beauty",
    ];
    return {
      id: i,
      name: names[i % names.length],
      count: (i + 1) * 24,
      image: `https://images.unsplash.com/photo-${i % 2 === 0 ? "1521572163474-6f64f9fa023e" : "1506422745199-563b7e735496"}?q=80&w=600&auto=format&fit=crop`,
    };
  });

  // Text Alignment Logic
  let alignStyles = { alignItems: "flex-start", justifyContent: "flex-end" }; // bottom-left default
  if (textPosition === "center")
    alignStyles = { alignItems: "center", justifyContent: "center" };
  if (textPosition === "bottom-center")
    alignStyles = { alignItems: "center", justifyContent: "flex-end" };
  if (textPosition === "top-left")
    alignStyles = { alignItems: "flex-start", justifyContent: "flex-start" };

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
            label={__("Number of Categories", "modern-core")}
            value={catCount}
            onChange={(val) => setAttributes({ catCount: val })}
            min={2}
            max={12}
          />
        </PanelBody>

        <PanelBody
          title={__("Grid Architecture", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Uniform Grid", value: "grid" },
              { label: "Featured Grid (Bento Box)", value: "featured" },
              { label: "Horizontal Scroll", value: "scroll" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <RangeControl
            label={__("Desktop Columns", "modern-core")}
            value={colsDesktop}
            onChange={(val) => setAttributes({ colsDesktop: val })}
            min={2}
            max={6}
          />
          <RangeControl
            label={__("Mobile Columns", "modern-core")}
            value={colsMobile}
            onChange={(val) => setAttributes({ colsMobile: val })}
            min={1}
            max={3}
          />
        </PanelBody>

        <PanelBody title={__("Tile Design", "modern-core")} initialOpen={false}>
          <SelectControl
            label={__("Label Placement", "modern-core")}
            value={labelPlacement}
            options={[
              { label: "Overlaid on Image", value: "overlay" },
              { label: "Below Image (Caption)", value: "below" },
            ]}
            onChange={(val) => setAttributes({ labelPlacement: val })}
          />
          {labelPlacement === "overlay" && (
            <SelectControl
              label={__("Text Position", "modern-core")}
              value={textPosition}
              options={[
                { label: "Bottom Left", value: "bottom-left" },
                { label: "Bottom Center", value: "bottom-center" },
                { label: "Center", value: "center" },
                { label: "Top Left", value: "top-left" },
              ]}
              onChange={(val) => setAttributes({ textPosition: val })}
            />
          )}
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
            label={__("Show Product Count", "modern-core")}
            checked={showCount}
            onChange={(val) => setAttributes({ showCount: val })}
          />
          <RangeControl
            label={__("Tile Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
          <RangeControl
            label={__("Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={90}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent", "modern-core")}</strong>
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
                    .modern-core-category-editor {
                        --mc-cols: ${colsDesktop};
                        --mc-ratio: ${imageRatio};
                        --mc-radius: ${cardRadius}px;
                        --mc-txt: ${textColor};
                        --mc-opacity: ${overlayOpacity / 100};
                    }
                    .mc-cat-grid-mock {
                        display: grid;
                        grid-template-columns: repeat(var(--mc-cols), 1fr);
                        gap: 20px;
                    }
                    .layout-scroll .mc-cat-grid-mock {
                        display: flex; overflow-x: auto; gap: 20px;
                    }
                    .mc-cat-tile-mock {
                        position: relative;
                        border-radius: var(--mc-radius);
                        overflow: hidden;
                        display: flex;
                        flex-direction: column;
                    }
                    .layout-scroll .mc-cat-tile-mock {
                        flex: 0 0 calc(100% / var(--mc-cols));
                    }
                    
                    /* Featured Grid Bento Logic */
                    .layout-featured .mc-cat-grid-mock .mc-cat-tile-mock:first-child {
                        grid-column: span 2;
                        grid-row: span 2;
                    }
                    
                    .mc-cat-img-wrap {
                        width: 100%;
                        aspect-ratio: var(--mc-ratio);
                        position: relative;
                        overflow: hidden;
                    }
                    /* In featured bento, the first item must fill height */
                    .layout-featured .mc-cat-grid-mock .mc-cat-tile-mock:first-child .mc-cat-img-wrap {
                        height: 100%; aspect-ratio: auto;
                    }
                    `}
        </style>

        <div className="mc-cat-grid-mock">
          {mockCats.map((cat, i) => (
            <div key={i} className="mc-cat-tile-mock">
              <div
                className="mc-cat-img-wrap"
                style={{
                  backgroundImage: `url(${cat.image})`,
                  backgroundSize: "cover",
                  backgroundPosition: "center",
                }}
              >
                {labelPlacement === "overlay" && (
                  <>
                    <div
                      style={{
                        position: "absolute",
                        inset: 0,
                        background: textPosition.includes("bottom")
                          ? `linear-gradient(to top, rgba(0,0,0,calc(var(--mc-opacity) + 0.2)), rgba(0,0,0,0))`
                          : `rgba(0,0,0,var(--mc-opacity))`,
                      }}
                    ></div>
                    <div
                      style={{
                        position: "absolute",
                        inset: 0,
                        padding: "30px",
                        display: "flex",
                        flexDirection: "column",
                        color: textColor,
                        ...alignStyles,
                      }}
                    >
                      <h3
                        style={{
                          margin: 0,
                          fontSize: "24px",
                          fontWeight: "bold",
                        }}
                      >
                        {cat.name}
                      </h3>
                      {showCount && (
                        <span
                          style={{
                            fontSize: "13px",
                            opacity: 0.8,
                            marginTop: "5px",
                          }}
                        >
                          {cat.count} products
                        </span>
                      )}
                    </div>
                  </>
                )}
              </div>

              {labelPlacement === "below" && (
                <div
                  style={{
                    padding: "20px 0",
                    color: accentColor,
                    textAlign: "center",
                  }}
                >
                  <h3
                    style={{ margin: 0, fontSize: "18px", fontWeight: "bold" }}
                  >
                    {cat.name}
                  </h3>
                  {showCount && (
                    <span
                      style={{
                        fontSize: "12px",
                        opacity: 0.6,
                        marginTop: "5px",
                        display: "block",
                      }}
                    >
                      {cat.count} products
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
