import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ColorPalette,
  TextareaControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    iconName,
    customSvg,
    presentation,
    shape,
    iconSize,
    bgPadding,
    borderWidth,
    text,
    textPosition,
    link,
    hoverEffect,
    iconColor,
    bgColor,
    borderColor,
    textColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-icon-editor p-${presentation} s-${shape} t-${textPosition} h-${hoverEffect}`,
    style: {
      width: "100%",
      padding: "20px",
      display: "flex",
      justifyContent: "center",
    },
  });

  const library = [
    { label: "Star", value: "star" },
    { label: "Shopping Cart", value: "cart" },
    { label: "Shipping Truck", value: "truck" },
    { label: "Padlock (Secure)", value: "lock" },
    { label: "Phone", value: "phone" },
    { label: "Email", value: "email" },
    { label: "User / Account", value: "user" },
    { label: "Checkmark", value: "check" },
    { label: "Arrow Right", value: "arrow-right" },
    { label: "Custom SVG", value: "custom" },
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Icon Library", "modern-core")}>
          <SelectControl
            label={__("Select Icon", "modern-core")}
            value={iconName}
            options={library}
            onChange={(val) => setAttributes({ iconName: val })}
          />
          {iconName === "custom" && (
            <TextareaControl
              label={__("Raw SVG Code", "modern-core")}
              value={customSvg}
              onChange={(val) => setAttributes({ customSvg: val })}
              help="Paste raw <svg>...</svg> code here."
              rows={6}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Presentation & Shape", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Display Style", "modern-core")}
            value={presentation}
            options={[
              { label: "Standalone (No Background)", value: "standalone" },
              { label: "Filled Badge", value: "filled" },
              { label: "Outlined Badge", value: "outlined" },
            ]}
            onChange={(val) => setAttributes({ presentation: val })}
          />
          {presentation !== "standalone" && (
            <SelectControl
              label={__("Badge Shape", "modern-core")}
              value={shape}
              options={[
                { label: "Circle", value: "circle" },
                { label: "Rounded Square", value: "rounded" },
                { label: "Square", value: "square" },
              ]}
              onChange={(val) => setAttributes({ shape: val })}
            />
          )}
          <RangeControl
            label={__("Icon Size (px)", "modern-core")}
            value={iconSize}
            onChange={(val) => setAttributes({ iconSize: val })}
            min={16}
            max={120}
          />
          {presentation !== "standalone" && (
            <>
              <RangeControl
                label={__("Background Padding (px)", "modern-core")}
                value={bgPadding}
                onChange={(val) => setAttributes({ bgPadding: val })}
                min={4}
                max={40}
              />
              {presentation === "outlined" && (
                <RangeControl
                  label={__("Border Thickness (px)", "modern-core")}
                  value={borderWidth}
                  onChange={(val) => setAttributes({ borderWidth: val })}
                  min={1}
                  max={8}
                />
              )}
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Text & Interactivity", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Optional Text Label", "modern-core")}
            value={text}
            onChange={(val) => setAttributes({ text: val })}
          />
          {text && (
            <SelectControl
              label={__("Text Position", "modern-core")}
              value={textPosition}
              options={[
                { label: "Right of Icon", value: "right" },
                { label: "Left of Icon", value: "left" },
                { label: "Below Centered", value: "below" },
              ]}
              onChange={(val) => setAttributes({ textPosition: val })}
            />
          )}
          <TextControl
            label={__("Link URL", "modern-core")}
            value={link}
            onChange={(val) => setAttributes({ link: val })}
          />
          <SelectControl
            label={__("Hover Effect", "modern-core")}
            value={hoverEffect}
            options={[
              { label: "Scale Up", value: "scale" },
              { label: "Rotate Slightly", value: "rotate" },
              { label: "None", value: "none" },
            ]}
            onChange={(val) => setAttributes({ hoverEffect: val })}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Icon Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={iconColor}
            onChange={(val) => setAttributes({ iconColor: val })}
          />
          {presentation === "filled" && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Background Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={bgColor}
                onChange={(val) => setAttributes({ bgColor: val })}
              />
            </>
          )}
          {presentation === "outlined" && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Border Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={borderColor}
                onChange={(val) => setAttributes({ borderColor: val })}
              />
            </>
          )}
          {text && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Text Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={textColor}
                onChange={(val) => setAttributes({ textColor: val })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-icon-editor { --ic-size: ${iconSize}px; --ic-pad: ${bgPadding}px; --ic-bw: ${borderWidth}px; --ic-col: ${iconColor}; --ic-bg: ${bgColor}; --ic-border: ${borderColor}; --ic-txt: ${textColor}; }
                    .mc-ic-wrap { display: flex; align-items: center; gap: 15px; cursor: pointer; }
                    .t-below .mc-ic-wrap { flex-direction: column; text-align: center; gap: 10px; }
                    .t-left .mc-ic-wrap { flex-direction: row-reverse; }
                    
                    .mc-ic-graphic { width: calc(var(--ic-size) + (var(--ic-pad) * 2)); height: calc(var(--ic-size) + (var(--ic-pad) * 2)); display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
                    .mc-ic-graphic svg { width: var(--ic-size); height: var(--ic-size); color: var(--ic-col); stroke: currentColor; fill: none; }
                    
                    /* Presentation Rules */
                    .p-filled .mc-ic-graphic { background: var(--ic-bg); }
                    .p-outlined .mc-ic-graphic { border: var(--ic-bw) solid var(--ic-border); }
                    
                    /* Shape Rules */
                    .s-circle .mc-ic-graphic { border-radius: 50%; }
                    .s-rounded .mc-ic-graphic { border-radius: 8px; }
                    .s-square .mc-ic-graphic { border-radius: 0; }

                    /* Text Rules */
                    .mc-ic-text { color: var(--ic-txt); font-weight: bold; font-size: 15px; }

                    /* Hover Mocks */
                    .modern-core-icon-editor:hover.h-scale .mc-ic-graphic { transform: scale(1.1); }
                    .modern-core-icon-editor:hover.h-rotate .mc-ic-graphic { transform: rotate(15deg); }
                    `}
        </style>

        <div className="mc-ic-wrap">
          <div className="mc-ic-graphic">
            <svg
              viewBox="0 0 24 24"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              {iconName === "star" && (
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
              )}
              {iconName === "cart" && (
                <>
                  <circle cx="9" cy="21" r="1"></circle>
                  <circle cx="20" cy="21" r="1"></circle>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </>
              )}
              {iconName === "truck" && (
                <>
                  <rect x="1" y="3" width="15" height="13"></rect>
                  <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                  <circle cx="5.5" cy="18.5" r="2.5"></circle>
                  <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </>
              )}
              {/* Fallback mock for UI */}
              {iconName !== "star" &&
                iconName !== "cart" &&
                iconName !== "truck" && (
                  <circle cx="12" cy="12" r="10"></circle>
                )}
            </svg>
          </div>
          {text && <div className="mc-ic-text">{text}</div>}
        </div>
      </div>
    </>
  );
}
