import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    showToggle,
    discountLabel,
    cardRadius,
    accentColor,
    textColor,
    bgBase,
    bgFeatured,
    tiers,
  } = attributes;

  // Editor local state for previewing the toggle
  const [isAnnual, setIsAnnual] = React.useState(false);

  const blockProps = useBlockProps({
    className: "modern-core-pricing-editor",
    style: { width: "100%", padding: "60px 0", backgroundColor: "#f1f5f9" },
  });

  // Tier Management Functions
  const updateTier = (index, key, value) => {
    const newTiers = [...tiers];
    newTiers[index][key] = value;
    setAttributes({ tiers: newTiers });
  };

  const CheckIcon = () => (
    <svg
      width="16"
      height="16"
      viewBox="0 0 24 24"
      fill="none"
      stroke="#10b981"
      strokeWidth="3"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
  );
  const CrossIcon = () => (
    <svg
      width="16"
      height="16"
      viewBox="0 0 24 24"
      fill="none"
      stroke="#94a3b8"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
  );

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture & Toggles", "modern-core")}>
          <ToggleControl
            label={__("Show Monthly/Annual Toggle", "modern-core")}
            checked={showToggle}
            onChange={(val) => setAttributes({ showToggle: val })}
          />
          {showToggle && (
            <TextControl
              label={__("Discount Label", "modern-core")}
              value={discountLabel}
              onChange={(val) => setAttributes({ discountLabel: val })}
            />
          )}
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>
              {__("Brand Accent (Buttons & Badges)", "modern-core")}
            </strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Standard Card Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={bgBase}
            onChange={(val) => setAttributes({ bgBase: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Featured Card Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={bgFeatured}
            onChange={(val) => setAttributes({ bgFeatured: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Tiers Management", "modern-core")}
          initialOpen={false}
        >
          <p style={{ fontSize: "12px", opacity: 0.6 }}>
            Edit names, pricing, and feature arrays via the sidebar or define
            them in your block configuration.
          </p>
          {tiers.map((tier, index) => (
            <div
              key={index}
              style={{
                marginBottom: "20px",
                padding: "15px",
                border: "1px solid #e2e8f0",
                borderRadius: "8px",
              }}
            >
              <div style={{ fontWeight: "bold", marginBottom: "10px" }}>
                Tier: {tier.name}
              </div>
              <ToggleControl
                label="Featured / Elevated Tier"
                checked={tier.isFeatured}
                onChange={(val) => updateTier(index, "isFeatured", val)}
              />
              <TextControl
                label="Tier Name"
                value={tier.name}
                onChange={(val) => updateTier(index, "name", val)}
              />
              <TextControl
                label="Badge (e.g., Most Popular)"
                value={tier.badge}
                onChange={(val) => updateTier(index, "badge", val)}
              />
              <div style={{ display: "flex", gap: "10px" }}>
                <TextControl
                  label="Monthly $"
                  value={tier.priceMonthly}
                  onChange={(val) => updateTier(index, "priceMonthly", val)}
                />
                <TextControl
                  label="Annual $"
                  value={tier.priceAnnual}
                  onChange={(val) => updateTier(index, "priceAnnual", val)}
                />
              </div>
            </div>
          ))}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-pricing-editor { --mc-rad: ${cardRadius}px; --mc-acc: ${accentColor}; --mc-txt: ${textColor}; }
                    .mc-pt-toggle-wrap { display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 50px; }
                    .mc-pt-switch { width: 60px; height: 32px; background: rgba(0,0,0,0.1); border-radius: 50px; position: relative; cursor: pointer; }
                    .mc-pt-knob { width: 26px; height: 26px; background: #fff; border-radius: 50%; position: absolute; top: 3px; left: ${isAnnual ? "31px" : "3px"}; transition: left 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
                    .mc-pt-badge { background: var(--mc-acc); color: #fff; font-size: 11px; padding: 4px 8px; border-radius: 50px; font-weight: bold; }
                    
                    .mc-pt-grid { display: grid; grid-template-columns: repeat(${tiers.length}, 1fr); gap: 30px; max-width: 1200px; margin: 0 auto; padding: 0 5%; align-items: center; }
                    .mc-pt-card { background: ${bgBase}; padding: 40px 30px; border-radius: var(--mc-rad); box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column; position: relative; color: var(--mc-txt); }
                    .mc-pt-card.is-featured { background: ${bgFeatured}; transform: scale(1.05); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 2px solid var(--mc-acc); z-index: 10; }
                    
                    .mc-pt-btn { width: 100%; padding: 15px; text-align: center; border-radius: 8px; font-weight: bold; margin-top: 30px; cursor: pointer; }
                    .mc-pt-card.is-featured .mc-pt-btn { background: var(--mc-acc); color: #fff; border: none; }
                    .mc-pt-card:not(.is-featured) .mc-pt-btn { background: transparent; border: 2px solid var(--mc-acc); color: var(--mc-acc); }
                    `}
        </style>

        {showToggle && (
          <div className="mc-pt-toggle-wrap">
            <span
              style={{
                fontWeight: !isAnnual ? "bold" : "normal",
                opacity: !isAnnual ? 1 : 0.6,
              }}
            >
              Monthly
            </span>
            <div
              className="mc-pt-switch"
              onClick={() => setIsAnnual(!isAnnual)}
            >
              <div className="mc-pt-knob"></div>
            </div>
            <span
              style={{
                fontWeight: isAnnual ? "bold" : "normal",
                opacity: isAnnual ? 1 : 0.6,
                display: "flex",
                alignItems: "center",
                gap: "8px",
              }}
            >
              Annually <span className="mc-pt-badge">{discountLabel}</span>
            </span>
          </div>
        )}

        <div className="mc-pt-grid">
          {tiers.map((tier, i) => (
            <div
              key={i}
              className={`mc-pt-card ${tier.isFeatured ? "is-featured" : ""}`}
            >
              {tier.isFeatured && tier.badge && (
                <div
                  style={{
                    position: "absolute",
                    top: "-12px",
                    left: "50%",
                    transform: "translateX(-50%)",
                    background: accentColor,
                    color: "#fff",
                    fontSize: "12px",
                    fontWeight: "bold",
                    padding: "6px 16px",
                    borderRadius: "50px",
                    whiteSpace: "nowrap",
                  }}
                >
                  {tier.badge}
                </div>
              )}

              <h3
                style={{
                  margin: "0 0 10px 0",
                  fontSize: "24px",
                  fontWeight: "bold",
                }}
              >
                {tier.name}
              </h3>
              <p
                style={{
                  fontSize: "14px",
                  opacity: 0.8,
                  margin: "0 0 20px 0",
                  minHeight: "40px",
                }}
              >
                {tier.desc}
              </p>

              <div
                style={{
                  display: "flex",
                  alignItems: "baseline",
                  gap: "5px",
                  marginBottom: "30px",
                }}
              >
                <span style={{ fontSize: "24px", fontWeight: "bold" }}>$</span>
                <span
                  style={{ fontSize: "56px", fontWeight: "900", lineHeight: 1 }}
                >
                  {isAnnual ? tier.priceAnnual : tier.priceMonthly}
                </span>
                <span style={{ opacity: 0.6 }}>/mo</span>
              </div>

              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  gap: "15px",
                  flex: 1,
                }}
              >
                {tier.features.map((feat, fi) => (
                  <div
                    key={fi}
                    style={{
                      display: "flex",
                      alignItems: "center",
                      gap: "10px",
                      fontSize: "15px",
                      opacity: feat.included ? 1 : 0.5,
                      textDecoration: feat.included ? "none" : "line-through",
                    }}
                  >
                    {feat.included ? <CheckIcon /> : <CrossIcon />}
                    {feat.text}
                  </div>
                ))}
              </div>

              <div className="mc-pt-btn">{tier.button}</div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
