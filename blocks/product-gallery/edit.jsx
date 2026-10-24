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
    thumbPosition,
    aspectRatio,
    gap,
    radius,
    activeBorder,
    enableZoom,
    zoomLevel,
    enableLightbox,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-gallery-editor layout-${thumbPosition} ratio-${aspectRatio.replace("/", "-")}`,
    style: { width: "100%", padding: "20px 0", backgroundColor: "transparent" },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Gallery Layout", "modern-core")}>
          <SelectControl
            label={__("Thumbnail Position", "modern-core")}
            value={thumbPosition}
            options={[
              { label: "Bottom Horizontal Strip", value: "bottom" },
              { label: "Left Vertical Strip", value: "left" },
              { label: "Dots Only (Minimal)", value: "dots" },
            ]}
            onChange={(val) => setAttributes({ thumbPosition: val })}
          />
          <SelectControl
            label={__("Main Image Aspect Ratio", "modern-core")}
            value={aspectRatio}
            options={[
              { label: "Square (1:1)", value: "1/1" },
              { label: "Portrait (3:4)", value: "3/4" },
              { label: "Landscape (4:3)", value: "4/3" },
              { label: "Natural (Auto)", value: "auto" },
            ]}
            onChange={(val) => setAttributes({ aspectRatio: val })}
          />
          <RangeControl
            label={__("Gap Between Images (px)", "modern-core")}
            value={gap}
            onChange={(val) => setAttributes({ gap: val })}
            min={0}
            max={40}
          />
        </PanelBody>

        <PanelBody
          title={__("Interactions & Features", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Enable Hover Zoom", "modern-core")}
            checked={enableZoom}
            onChange={(val) => setAttributes({ enableZoom: val })}
          />
          {enableZoom && (
            <RangeControl
              label={__("Zoom Magnification Level", "modern-core")}
              value={zoomLevel}
              onChange={(val) => setAttributes({ zoomLevel: val })}
              min={1.5}
              max={4}
              step={0.5}
            />
          )}
          <ToggleControl
            label={__("Enable Click-to-Fullscreen Lightbox", "modern-core")}
            checked={enableLightbox}
            onChange={(val) => setAttributes({ enableLightbox: val })}
          />
        </PanelBody>

        <PanelBody title={__("Styling", "modern-core")} initialOpen={false}>
          <RangeControl
            label={__("Border Radius", "modern-core")}
            value={radius}
            onChange={(val) => setAttributes({ radius: val })}
            min={0}
            max={24}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Active Thumbnail Border", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={activeBorder}
            onChange={(val) => setAttributes({ activeBorder: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-gallery-editor { --pg-gap: ${gap}px; --pg-rad: ${radius}px; --pg-active: ${activeBorder}; --pg-ratio: ${aspectRatio}; }
                    .mc-pg-mock-wrap { display: flex; gap: var(--pg-gap); flex-direction: ${thumbPosition === "left" ? "row-reverse" : "column"}; }
                    .mc-pg-mock-main { flex: 1; background: #e2e8f0; border-radius: var(--pg-rad); aspect-ratio: var(--pg-ratio); display: flex; align-items: center; justify-content: center; color: #94a3b8; font-weight: bold; font-size: 20px; position: relative; overflow: hidden; }
                    .mc-pg-mock-main::after { content: ''; position: absolute; inset: 0; border: 1px dashed rgba(0,0,0,0.1); border-radius: var(--pg-rad); pointer-events: none; }
                    
                    .mc-pg-mock-thumbs { display: ${thumbPosition === "dots" ? "none" : "flex"}; gap: var(--pg-gap); flex-direction: ${thumbPosition === "left" ? "column" : "row"}; }
                    .mc-pg-mock-thumb { width: ${thumbPosition === "left" ? "80px" : "calc(25% - (var(--pg-gap) * 3 / 4))"}; aspect-ratio: var(--pg-ratio); background: #f1f5f9; border-radius: calc(var(--pg-rad) / 2); border: 2px solid transparent; }
                    .mc-pg-mock-thumb.is-active { border-color: var(--pg-active); }
                    
                    .mc-pg-mock-dots { display: ${thumbPosition === "dots" ? "flex" : "none"}; justify-content: center; gap: 8px; margin-top: 10px; }
                    .mc-pg-mock-dot { width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; }
                    .mc-pg-mock-dot.is-active { background: var(--pg-active); }
                    `}
        </style>

        <div className="mc-pg-mock-wrap">
          <div className="mc-pg-mock-main">
            Main Product Image
            {enableZoom && (
              <div
                style={{
                  position: "absolute",
                  top: "15px",
                  right: "15px",
                  background: "rgba(255,255,255,0.8)",
                  borderRadius: "50%",
                  width: "30px",
                  height: "30px",
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                }}
              >
                🔍
              </div>
            )}
          </div>

          <div className="mc-pg-mock-thumbs">
            <div className="mc-pg-mock-thumb is-active"></div>
            <div className="mc-pg-mock-thumb"></div>
            <div className="mc-pg-mock-thumb"></div>
            <div className="mc-pg-mock-thumb"></div>
          </div>

          <div className="mc-pg-mock-dots">
            <div className="mc-pg-mock-dot is-active"></div>
            <div className="mc-pg-mock-dot"></div>
            <div className="mc-pg-mock-dot"></div>
          </div>
        </div>
      </div>
    </>
  );
}
