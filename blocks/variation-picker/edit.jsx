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
    swatchShape,
    swatchSize,
    btnRadius,
    gap,
    showLiveLabel,
    accentColor,
    textColor,
    borderColor,
    disabledStyle,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-var-editor shape-${swatchShape} disabled-${disabledStyle}`,
    style: { width: "100%", padding: "20px 0", backgroundColor: "transparent" },
  });

  const [mockColor, setMockColor] = React.useState("Navy Blue");
  const [mockSize, setMockSize] = React.useState("Medium");

  return (
    <>
      <InspectorControls>
        <PanelBody
          title={__("Swatch Architecture (Colors/Images)", "modern-core")}
        >
          <SelectControl
            label={__("Swatch Shape", "modern-core")}
            value={swatchShape}
            options={[
              { label: "Circle", value: "circle" },
              { label: "Square", value: "square" },
              { label: "Rounded Square", value: "rounded" },
            ]}
            onChange={(val) => setAttributes({ swatchShape: val })}
          />
          <RangeControl
            label={__("Swatch Size (px)", "modern-core")}
            value={swatchSize}
            onChange={(val) => setAttributes({ swatchSize: val })}
            min={20}
            max={60}
          />
        </PanelBody>

        <PanelBody
          title={__("Button Architecture (Text/Sizes)", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Button Border Radius (px)", "modern-core")}
            value={btnRadius}
            onChange={(val) => setAttributes({ btnRadius: val })}
            min={0}
            max={24}
          />
          <SelectControl
            label={__("Disabled / Out of Stock Style", "modern-core")}
            value={disabledStyle}
            options={[
              { label: "Diagonal Strikethrough", value: "strikethrough" },
              { label: "Faded / Greyed Out", value: "faded" },
              { label: "Hidden entirely", value: "hidden" },
            ]}
            onChange={(val) => setAttributes({ disabledStyle: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Labels & Layout", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Live Selection in Label", "modern-core")}
            checked={showLiveLabel}
            onChange={(val) => setAttributes({ showLiveLabel: val })}
          />
          <RangeControl
            label={__("Gap Between Items (px)", "modern-core")}
            value={gap}
            onChange={(val) => setAttributes({ gap: val })}
            min={4}
            max={24}
          />
        </PanelBody>

        <PanelBody title={__("Styling", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Active Glow)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Base Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Inactive Border Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={borderColor}
            onChange={(val) => setAttributes({ borderColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-var-editor { --var-gap: ${gap}px; --var-size: ${swatchSize}px; --var-rad: ${btnRadius}px; --var-acc: ${accentColor}; --var-txt: ${textColor}; --var-border: ${borderColor}; color: var(--var-txt); }
                    .mc-var-group { margin-bottom: 25px; }
                    .mc-var-label { font-size: 14px; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
                    .mc-var-live { font-weight: normal; opacity: 0.7; text-transform: none; margin-left: 5px; }
                    .mc-var-track { display: flex; flex-wrap: wrap; gap: var(--var-gap); }
                    
                    /* Swatch Mocks */
                    .mc-var-swatch { width: var(--var-size); height: var(--var-size); border: 1px solid var(--var-border); padding: 2px; cursor: pointer; transition: all 0.2s; position: relative; }
                    .shape-circle .mc-var-swatch { border-radius: 50%; } .shape-square .mc-var-swatch { border-radius: 0; } .shape-rounded .mc-var-swatch { border-radius: 6px; }
                    .mc-var-swatch-inner { width: 100%; height: 100%; border-radius: inherit; }
                    .mc-var-swatch.is-active { border-color: var(--var-acc); box-shadow: 0 0 0 1px var(--var-acc); }

                    /* Button Mocks */
                    .mc-var-btn { padding: 10px 20px; border: 1px solid var(--var-border); border-radius: var(--var-rad); font-size: 14px; font-weight: bold; cursor: pointer; transition: all 0.2s; }
                    .mc-var-btn.is-active { border-color: var(--var-acc); background: var(--var-acc); color: #fff; }
                    
                    /* Disabled Mocks */
                    .disabled-strikethrough .mc-var-btn.is-disabled { opacity: 0.5; position: relative; overflow: hidden; pointer-events: none; }
                    .disabled-strikethrough .mc-var-btn.is-disabled::before { content: ''; position: absolute; top: 50%; left: -10%; width: 120%; height: 1px; background: currentColor; transform: rotate(-25deg); }
                    .disabled-faded .mc-var-btn.is-disabled { opacity: 0.3; pointer-events: none; }
                    .disabled-hidden .mc-var-btn.is-disabled { display: none; }
                    `}
        </style>

        {/* Colors Mock */}
        <div className="mc-var-group">
          <div className="mc-var-label">
            Color{" "}
            {showLiveLabel && <span className="mc-var-live">{mockColor}</span>}
          </div>
          <div className="mc-var-track">
            <div
              className={`mc-var-swatch ${mockColor === "Navy Blue" ? "is-active" : ""}`}
              onClick={() => setMockColor("Navy Blue")}
            >
              <div
                className="mc-var-swatch-inner"
                style={{ background: "#1e3a8a" }}
              ></div>
            </div>
            <div
              className={`mc-var-swatch ${mockColor === "Charcoal" ? "is-active" : ""}`}
              onClick={() => setMockColor("Charcoal")}
            >
              <div
                className="mc-var-swatch-inner"
                style={{ background: "#334155" }}
              ></div>
            </div>
            <div
              className={`mc-var-swatch ${mockColor === "Ivory" ? "is-active" : ""}`}
              onClick={() => setMockColor("Ivory")}
            >
              <div
                className="mc-var-swatch-inner"
                style={{ background: "#f8fafc", border: "1px solid #e2e8f0" }}
              ></div>
            </div>
          </div>
        </div>

        {/* Sizes Mock */}
        <div className="mc-var-group">
          <div className="mc-var-label">
            Size{" "}
            {showLiveLabel && <span className="mc-var-live">{mockSize}</span>}
          </div>
          <div className="mc-var-track">
            <div
              className={`mc-var-btn ${mockSize === "Small" ? "is-active" : ""}`}
              onClick={() => setMockSize("Small")}
            >
              S
            </div>
            <div
              className={`mc-var-btn ${mockSize === "Medium" ? "is-active" : ""}`}
              onClick={() => setMockSize("Medium")}
            >
              M
            </div>
            <div
              className={`mc-var-btn ${mockSize === "Large" ? "is-active" : ""}`}
              onClick={() => setMockSize("Large")}
            >
              L
            </div>
            <div className="mc-var-btn is-disabled">XL</div>
          </div>
        </div>
      </div>
    </>
  );
}
