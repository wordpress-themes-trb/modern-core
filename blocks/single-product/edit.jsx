import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  RangeControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    splitRatio,
    galleryThumbs,
    imageRatio,
    enableStickyATC,
    elements,
    panelBg,
    panelRadius,
    accentColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-pdp-editor layout-${layoutMode} split-${splitRatio}`,
    style: { width: "100%", padding: "40px 0", backgroundColor: "#f8fafc" },
  });

  const toggleElement = (index) => {
    const newElements = [...elements];
    newElements[index].visible = !newElements[index].visible;
    setAttributes({ elements: newElements });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Macro Layout", "modern-core")}>
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Standard (Two Column)", value: "standard" },
              { label: "Cinematic (Overlay Panel)", value: "cinematic" },
              { label: "Full Width (Stacked)", value: "stacked" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          {layoutMode === "standard" && (
            <SelectControl
              label={__("Column Split (Gallery / Info)", "modern-core")}
              value={splitRatio}
              options={[
                { label: "50% / 50%", value: "50-50" },
                { label: "60% / 40%", value: "60-40" },
                { label: "40% / 60%", value: "40-60" },
              ]}
              onChange={(val) => setAttributes({ splitRatio: val })}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Gallery & Sticky Bar", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Main Image Ratio", "modern-core")}
            value={imageRatio}
            options={[
              { label: "Portrait (3:4)", value: "3/4" },
              { label: "Square (1:1)", value: "1/1" },
              { label: "Landscape (4:3)", value: "4/3" },
            ]}
            onChange={(val) => setAttributes({ imageRatio: val })}
          />
          <SelectControl
            label={__("Thumbnails Position", "modern-core")}
            value={galleryThumbs}
            options={[
              { label: "Bottom Strip", value: "bottom" },
              { label: "Left Side Strip", value: "left" },
            ]}
            onChange={(val) => setAttributes({ galleryThumbs: val })}
          />
          <ToggleControl
            label={__("Enable Sticky Add-to-Cart Bar", "modern-core")}
            checked={enableStickyATC}
            onChange={(val) => setAttributes({ enableStickyATC: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Product Info Elements", "modern-core")}
          initialOpen={false}
        >
          <p style={{ fontSize: "12px", opacity: 0.6, marginBottom: "15px" }}>
            Toggle the visibility of specific product data blocks.
          </p>
          {elements.map((el, index) => (
            <ToggleControl
              key={el.id}
              label={el.label}
              checked={el.visible}
              onChange={() => toggleElement(index)}
            />
          ))}
        </PanelBody>

        <PanelBody title={__("Styling", "modern-core")} initialOpen={false}>
          {layoutMode === "cinematic" && (
            <RangeControl
              label={__("Panel Border Radius", "modern-core")}
              value={panelRadius}
              onChange={(val) => setAttributes({ panelRadius: val })}
              min={0}
              max={30}
            />
          )}
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Information Panel Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={panelBg}
            onChange={(val) => setAttributes({ panelBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Buttons)", "modern-core")}</strong>
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
                    .modern-core-pdp-editor { --mc-acc: ${accentColor}; --mc-bg: ${panelBg}; --mc-rad: ${panelRadius}px; --mc-ratio: ${imageRatio}; }
                    
                    /* Grid Mathematics */
                    .mc-pdp-mock-grid { display: grid; gap: 50px; max-width: 1400px; margin: 0 auto; padding: 0 5%; }
                    .layout-standard.split-50-50 .mc-pdp-mock-grid { grid-template-columns: 1fr 1fr; }
                    .layout-standard.split-60-40 .mc-pdp-mock-grid { grid-template-columns: 6fr 4fr; }
                    .layout-standard.split-40-60 .mc-pdp-mock-grid { grid-template-columns: 4fr 6fr; }
                    .layout-stacked .mc-pdp-mock-grid { grid-template-columns: 1fr; max-width: 800px; }
                    
                    /* Cinematic Overlay Logic */
                    .layout-cinematic .mc-pdp-mock-grid { grid-template-columns: 1fr; position: relative; padding: 0; max-width: 100%; }
                    .layout-cinematic .mc-pdp-mock-gallery { grid-column: 1 / -1; grid-row: 1; aspect-ratio: auto; height: 80vh; }
                    .layout-cinematic .mc-pdp-mock-info { grid-column: 1 / -1; grid-row: 1; justify-self: end; align-self: center; background: var(--mc-bg); padding: 40px; margin-right: 5%; width: 400px; border-radius: var(--mc-rad); box-shadow: 0 25px 50px rgba(0,0,0,0.15); z-index: 10; }

                    /* Gallery Mock */
                    .mc-pdp-mock-gallery { display: flex; flex-direction: ${galleryThumbs === "left" ? "row-reverse" : "column"}; gap: 15px; }
                    .mc-pdp-mock-main-img { flex: 1; background: #e2e8f0; border-radius: 8px; aspect-ratio: var(--mc-ratio); display: flex; align-items: center; justify-content: center; color: #94a3b8; }
                    .mc-pdp-mock-thumbs { display: flex; flex-direction: ${galleryThumbs === "left" ? "column" : "row"}; gap: 10px; }
                    .mc-pdp-mock-thumb { width: 80px; height: 80px; background: #cbd5e1; border-radius: 4px; }

                    /* Info Elements */
                    .mc-pdp-mock-info { display: flex; flex-direction: column; gap: 20px; }
                    .mc-mock-el { padding: 10px; border: 1px dashed #cbd5e1; border-radius: 4px; font-size: 13px; color: #64748b; background: rgba(255,255,255,0.5); }
                    .mc-mock-btn { background: var(--mc-acc); color: #fff; padding: 15px; text-align: center; border-radius: 4px; font-weight: bold; }
                    `}
        </style>

        <div className="mc-pdp-mock-grid">
          <div className="mc-pdp-mock-gallery">
            <div className="mc-pdp-mock-main-img">Product Gallery View</div>
            <div className="mc-pdp-mock-thumbs">
              <div className="mc-pdp-mock-thumb"></div>
              <div className="mc-pdp-mock-thumb"></div>
              <div className="mc-pdp-mock-thumb"></div>
            </div>
          </div>
          <div className="mc-pdp-mock-info">
            {elements
              .filter((el) => el.visible)
              .map((el) => (
                <div
                  key={el.id}
                  className={
                    el.id === "add_to_cart" ? "mc-mock-btn" : "mc-mock-el"
                  }
                >
                  {el.label}
                </div>
              ))}
          </div>
        </div>
      </div>
    </>
  );
}
