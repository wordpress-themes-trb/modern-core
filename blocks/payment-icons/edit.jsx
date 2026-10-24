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
    className: `modern-core-payment-icons-editor style-${badgeStyle} align-${alignment}`,
    style: {
      width: "100%",
      padding: "30px 0",
      backgroundColor: badgeStyle === "mono-light" ? "#0f172a" : "transparent",
      textAlign: alignment,
    },
  });

  const library = [
    { id: "visa", name: "Visa" },
    { id: "mastercard", name: "Mastercard" },
    { id: "amex", name: "American Express" },
    { id: "paypal", name: "PayPal" },
    { id: "applepay", name: "Apple Pay" },
    { id: "googlepay", name: "Google Pay" },
    { id: "stripe", name: "Stripe" },
    { id: "klarna", name: "Klarna" },
    { id: "afterpay", name: "Afterpay" },
    { id: "clearpay", name: "Clearpay" },
    { id: "zip", name: "Zip" },
    { id: "discover", name: "Discover" },
    { id: "jcb", name: "JCB" },
    { id: "unionpay", name: "UnionPay" },
    { id: "bitcoin", name: "Bitcoin" },
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
            label={__("Icon Height (px)", "modern-core")}
            value={iconHeight}
            onChange={(val) => setAttributes({ iconHeight: val })}
            min={16}
            max={64}
          />
          <RangeControl
            label={__("Gap Between Icons (px)", "modern-core")}
            value={gap}
            onChange={(val) => setAttributes({ gap: val })}
            min={4}
            max={40}
          />
        </PanelBody>

        <PanelBody
          title={__("Accepted Gateways Library", "modern-core")}
          initialOpen={false}
        >
          <p style={{ fontSize: "12px", opacity: 0.6, marginBottom: "15px" }}>
            Select only the payment networks currently enabled on your
            storefront processor.
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
                    .modern-core-payment-icons-editor { --mc-gap: ${gap}px; --mc-h: ${iconHeight}px; }
                    .mc-pi-label-mock { font-size: 13px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 12px; text-transform: uppercase; color: ${labelColor}; }
                    .mc-pi-track-mock { display: inline-flex; flex-wrap: wrap; gap: var(--mc-gap); justify-content: ${alignment === "left" ? "flex-start" : alignment === "right" ? "flex-end" : "center"}; align-items: center; }
                    .mc-pi-badge-mock { height: var(--mc-h); width: calc(var(--mc-h) * 1.5); background: ${badgeStyle === "mono-light" ? "rgba(255,255,255,0.1)" : "rgba(0,0,0,0.05)"}; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: ${badgeStyle === "mono-light" ? "#fff" : "#475569"}; text-transform: uppercase; }
                    `}
        </style>

        {showLabel && labelText && (
          <div className="mc-pi-label-mock">{labelText}</div>
        )}

        <div className="mc-pi-track-mock">
          {activeIcons.map((id) => {
            const matched = library.find((item) => item.id === id);
            return (
              <div key={id} className="mc-pi-badge-mock">
                {matched ? matched.name.substring(0, 4) : id}
              </div>
            );
          })}
        </div>
      </div>
    </>
  );
}
