import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
  CheckboxControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    showLabel,
    labelText,
    badgeStyle,
    iconHeight,
    gap,
    alignment,
    activeIcons,
    labelColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-shipping-editor style-${badgeStyle} align-${alignment}`,
    style: {
      width: "100%",
      padding: "30px 0",
      backgroundColor: badgeStyle === "mono-light" ? "#0f172a" : "transparent",
      textAlign: alignment,
    },
  });

  const library = [
    { id: "dhl", name: "DHL" },
    { id: "fedex", name: "FedEx" },
    { id: "ups", name: "UPS" },
    { id: "usps", name: "USPS" },
    { id: "royalmail", name: "Royal Mail" },
    { id: "auspost", name: "Australia Post" },
    { id: "canadapost", name: "Canada Post" },
    { id: "dpd", name: "DPD" },
    { id: "hermes", name: "Hermes / Evri" },
    { id: "aramex", name: "Aramex" },
  ];

  const toggleIcon = (id, checked) => {
    let updated = [...activeIcons];
    if (checked) {
      if (!updated.includes(id)) updated.push(id);
    } else {
      updated = updated.filter((item) => item !== id);
    }
    setAttributes({ activeIcons: updated });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Typography & Label", "modern-core")}>
          <ToggleControl
            label={__("Show Label Text", "modern-core")}
            checked={showLabel}
            onChange={(val) => setAttributes({ showLabel: val })}
          />
          {showLabel && (
            <TextControl
              label={__("Label Text", "modern-core")}
              value={labelText}
              onChange={(val) => setAttributes({ labelText: val })}
            />
          )}
          {showLabel && (
            <>
              <p style={{ marginTop: "10px", marginBottom: "5px" }}>
                <strong>{__("Label Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={labelColor}
                onChange={(val) => setAttributes({ labelColor: val })}
              />
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Layout & Style", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Visual Style Variant", "modern-core")}
            value={badgeStyle}
            options={[
              { label: "Official Brand Colors", value: "color" },
              { label: "Monochrome Dark", value: "mono-dark" },
              { label: "Monochrome Light", value: "mono-light" },
            ]}
            onChange={(val) => setAttributes({ badgeStyle: val })}
          />
          <SelectControl
            label={__("Alignment", "modern-core")}
            value={alignment}
            options={[
              { label: "Left", value: "left" },
              { label: "Center", value: "center" },
              { label: "Right", value: "right" },
            ]}
            onChange={(val) => setAttributes({ alignment: val })}
          />
          <RangeControl
            label={__("Logo Height (px)", "modern-core")}
            value={iconHeight}
            onChange={(val) => setAttributes({ iconHeight: val })}
            min={16}
            max={64}
          />
          <RangeControl
            label={__("Gap Between Logos (px)", "modern-core")}
            value={gap}
            onChange={(val) => setAttributes({ gap: val })}
            min={10}
            max={60}
          />
        </PanelBody>

        <PanelBody
          title={__("Carrier Library", "modern-core")}
          initialOpen={false}
        >
          <p style={{ fontSize: "12px", opacity: 0.6, marginBottom: "15px" }}>
            Select the shipping carriers you actively use for fulfillment.
          </p>
          {library.map((icon) => (
            <CheckboxControl
              key={icon.id}
              label={icon.name}
              checked={activeIcons.includes(icon.id)}
              onChange={(checked) => toggleIcon(icon.id, checked)}
            />
          ))}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-shipping-editor { --mc-gap: ${gap}px; --mc-h: ${iconHeight}px; }
                    .mc-sp-label-mock { font-size: 13px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 15px; text-transform: uppercase; color: ${labelColor}; }
                    .mc-sp-track-mock { display: inline-flex; flex-wrap: wrap; gap: var(--mc-gap); justify-content: ${alignment === "left" ? "flex-start" : alignment === "right" ? "flex-end" : "center"}; align-items: center; }
                    .mc-sp-badge-mock { height: var(--mc-h); min-width: calc(var(--mc-h) * 2.5); background: ${badgeStyle === "mono-light" ? "rgba(255,255,255,0.1)" : "rgba(0,0,0,0.05)"}; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900; color: ${badgeStyle === "mono-light" ? "#fff" : "#0f172a"}; text-transform: uppercase; padding: 0 10px; }
                    `}
        </style>

        {showLabel && labelText && (
          <div className="mc-sp-label-mock">{labelText}</div>
        )}

        <div className="mc-sp-track-mock">
          {activeIcons.map((id) => {
            const matched = library.find((item) => item.id === id);
            return (
              <div key={id} className="mc-sp-badge-mock">
                {matched ? matched.name : id}
              </div>
            );
          })}
        </div>
      </div>
    </>
  );
}
