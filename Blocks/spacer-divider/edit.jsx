import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ColorPalette,
  ToggleControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    mode,
    heightDesktop,
    heightMobile,
    dividerStyle,
    dividerColor,
    dividerThickness,
    dividerWidth,
    dividerAlign,
    centerElement,
    centerText,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-spacer-editor mode-${mode} align-${dividerAlign}`,
    style: {
      width: "100%",
      display: "flex",
      justifyContent:
        dividerAlign === "left"
          ? "flex-start"
          : dividerAlign === "right"
            ? "flex-end"
            : "center",
      alignItems: "center",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Mode & Rhythm", "modern-core")}>
          <SelectControl
            label={__("Block Mode", "modern-core")}
            value={mode}
            options={[
              { label: "Spacer (Invisible Breathing Room)", value: "spacer" },
              { label: "Divider (Visible Line / Break)", value: "divider" },
            ]}
            onChange={(val) => setAttributes({ mode: val })}
          />
          <RangeControl
            label={__("Desktop Height (px)", "modern-core")}
            value={heightDesktop}
            onChange={(val) => setAttributes({ heightDesktop: val })}
            min={0}
            max={200}
            step={5}
          />
          <RangeControl
            label={__("Mobile Height (px)", "modern-core")}
            value={heightMobile}
            onChange={(val) => setAttributes({ heightMobile: val })}
            min={0}
            max={150}
            step={5}
          />
        </PanelBody>

        {mode === "divider" && (
          <PanelBody
            title={__("Divider Architecture", "modern-core")}
            initialOpen={false}
          >
            <SelectControl
              label={__("Line Style", "modern-core")}
              value={dividerStyle}
              options={[
                { label: "Solid", value: "solid" },
                { label: "Dashed", value: "dashed" },
                { label: "Dotted", value: "dotted" },
                { label: "Double", value: "double" },
                { label: "Gradient Fade (Soft)", value: "gradient" },
                { label: "Wave (Organic)", value: "wave" },
              ]}
              onChange={(val) => setAttributes({ dividerStyle: val })}
            />
            <RangeControl
              label={__("Line Width (%)", "modern-core")}
              value={dividerWidth}
              onChange={(val) => setAttributes({ dividerWidth: val })}
              min={10}
              max={100}
              step={5}
            />
            <SelectControl
              label={__("Line Alignment", "modern-core")}
              value={dividerAlign}
              options={[
                { label: "Left", value: "left" },
                { label: "Center", value: "center" },
                { label: "Right", value: "right" },
              ]}
              onChange={(val) => setAttributes({ dividerAlign: val })}
            />
            {dividerStyle !== "gradient" && dividerStyle !== "wave" && (
              <RangeControl
                label={__("Line Thickness (px)", "modern-core")}
                value={dividerThickness}
                onChange={(val) => setAttributes({ dividerThickness: val })}
                min={1}
                max={10}
              />
            )}
            <p style={{ marginTop: "15px", marginBottom: "5px" }}>
              <strong>{__("Divider Color", "modern-core")}</strong>
            </p>
            <ColorPalette
              value={dividerColor}
              onChange={(val) => setAttributes({ dividerColor: val })}
            />
          </PanelBody>
        )}

        {mode === "divider" &&
          (dividerStyle === "solid" ||
            dividerStyle === "dashed" ||
            dividerStyle === "dotted") && (
            <PanelBody
              title={__("Center Element", "modern-core")}
              initialOpen={false}
            >
              <SelectControl
                label={__("Decoration", "modern-core")}
                value={centerElement}
                options={[
                  { label: "None", value: "none" },
                  { label: "Diamond Shape", value: "diamond" },
                  { label: "Custom Text / Symbol", value: "text" },
                ]}
                onChange={(val) => setAttributes({ centerElement: val })}
              />
              {centerElement === "text" && (
                <TextControl
                  label={__("Center Text", "modern-core")}
                  value={centerText}
                  onChange={(val) => setAttributes({ centerText: val })}
                  help="Supports emojis or Unicode symbols (e.g., ✧, ★, ~)."
                />
              )}
            </PanelBody>
          )}
      </InspectorControls>

      <div
        {...blockProps}
        style={{ ...blockProps.style, height: `${heightDesktop}px` }}
      >
        <style>
          {`
                    .modern-core-spacer-editor.mode-spacer { background: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0,0,0,0.03) 10px, rgba(0,0,0,0.03) 20px); border: 1px dashed rgba(0,0,0,0.1); border-radius: 4px; position: relative; }
                    .modern-core-spacer-editor.mode-spacer::after { content: 'Spacer: ${heightDesktop}px (Desktop) / ${heightMobile}px (Mobile)'; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 11px; font-weight: bold; opacity: 0.4; letter-spacing: 1px; text-transform: uppercase; }
                    
                    .mc-div-mock { width: ${dividerWidth}%; display: flex; align-items: center; justify-content: center; gap: 15px; color: ${dividerColor}; }
                    
                    .mc-div-line { flex: 1; height: ${dividerThickness}px; background-color: ${dividerColor}; }
                    .style-dashed .mc-div-line { background: transparent; border-top: ${dividerThickness}px dashed ${dividerColor}; }
                    .style-dotted .mc-div-line { background: transparent; border-top: ${dividerThickness}px dotted ${dividerColor}; }
                    .style-double .mc-div-line { background: transparent; border-top: ${dividerThickness * 3}px double ${dividerColor}; height: 0; }
                    .style-gradient .mc-div-line { background: linear-gradient(90deg, transparent, ${dividerColor}, transparent); height: ${dividerThickness}px; border: none; }
                    
                    .mc-div-diamond { width: 8px; height: 8px; background: ${dividerColor}; transform: rotate(45deg); flex-shrink: 0; }
                    .mc-div-text { font-weight: bold; font-size: 14px; flex-shrink: 0; letter-spacing: 2px; }

                    /* Mock Wave via CSS */
                    .style-wave .mc-div-mock { height: 20px; background-image: radial-gradient(circle at 10px -5px, transparent 12px, ${dividerColor} 13px, ${dividerColor} 14px, transparent 15px), radial-gradient(circle at 10px 25px, transparent 12px, ${dividerColor} 13px, ${dividerColor} 14px, transparent 15px); background-size: 40px 20px; background-position: 0 0, 20px 0; background-repeat: repeat-x; width: ${dividerWidth}%; }
                    .style-wave .mc-div-line { display: none; }
                    `}
        </style>

        {mode === "divider" && (
          <div className={`mc-div-mock style-${dividerStyle}`}>
            {dividerStyle !== "wave" && <div className="mc-div-line"></div>}

            {(dividerStyle === "solid" ||
              dividerStyle === "dashed" ||
              dividerStyle === "dotted") &&
              centerElement !== "none" && (
                <>
                  {centerElement === "diamond" && (
                    <div className="mc-div-diamond"></div>
                  )}
                  {centerElement === "text" && (
                    <div className="mc-div-text">{centerText}</div>
                  )}
                  <div className="mc-div-line"></div>
                </>
              )}
          </div>
        )}
      </div>
    </>
  );
}
