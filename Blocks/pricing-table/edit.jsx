import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    showToggle,
    discountLabel,
    showOriginalPrice,
    cardRadius,
    accentColor,
    textColor,
    bgBase,
    bgFeatured,
    tiers,
  } = attributes;

  const [isAnnual, setIsAnnual] = React.useState(false);

  const blockProps = useBlockProps({
    className: "modern-core-pricing-editor",
    style: {
      width: "100%",
      maxWidth: "100%",
      padding: "40px 0",
      backgroundColor: "transparent",
    },
  });

  const updateTier = (index, key, value) => {
    const newTiers = [...tiers];
    newTiers[index][key] = value;
    setAttributes({ tiers: newTiers });
  };

  const CheckIcon = () => (
    <svg
      width="18"
      height="18"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="3"
      strokeLinecap="round"
      strokeLinejoin="round"
      style={{ flexShrink: 0 }}
    >
      <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
  );
  const CrossIcon = () => (
    <svg
      width="18"
      height="18"
      viewBox="0 0 24 24"
      fill="none"
      stroke="#cbd5e1"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
      style={{ flexShrink: 0 }}
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

          <hr style={{ margin: "20px 0", opacity: 0.2 }} />

          <ToggleControl
            label={__("Show Original Price Strikethrough", "modern-core")}
            checked={showOriginalPrice}
            onChange={(val) => setAttributes({ showOriginalPrice: val })}
            help="Displays the crossed-out original price next to the discounted annual price."
          />
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
        </PanelBody>

        <PanelBody
          title={__("Minimalist Colors", "modern-core")}
          initialOpen={false}
        >
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
              <div style={{ display: "flex", gap: "10px", flexWrap: "wrap" }}>
                <TextControl
                  label="Monthly $"
                  value={tier.priceMonthly}
                  onChange={(val) => updateTier(index, "priceMonthly", val)}
                  style={{ flex: 1 }}
                />
                <TextControl
                  label="Annual $"
                  value={tier.priceAnnual}
                  onChange={(val) => updateTier(index, "priceAnnual", val)}
                  style={{ flex: 1 }}
                />
                {showOriginalPrice && (
                  <TextControl
                    label="Orig. Annual $"
                    value={tier.priceAnnualOriginal || ""}
                    onChange={(val) =>
                      updateTier(index, "priceAnnualOriginal", val)
                    }
                    style={{ flex: 1 }}
                    help="Price to cross out"
                  />
                )}
              </div>
              <TextControl
                label="Button Text"
                value={tier.button}
                onChange={(val) => updateTier(index, "button", val)}
              />
            </div>
          ))}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
            /* Hard lock the box model in the editor so buttons don't blow out */
            .modern-core-pricing-editor *, 
            .modern-core-pricing-editor *::before, 
            .modern-core-pricing-editor *::after { 
                box-sizing: border-box !important; 
            }
            .modern-core-pricing-editor { --mc-rad: ${cardRadius}px; --mc-acc: ${accentColor}; --mc-txt: ${textColor}; }
            
            .mc-pt-toggle-wrap { display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 50px; color: var(--mc-txt); }
            .mc-pt-switch { width: 64px; height: 34px; background: rgba(0,0,0,0.1); border-radius: 50px; position: relative; cursor: pointer; }
            .mc-pt-switch.is-annual { background: var(--mc-acc); }
            .mc-pt-knob { width: 28px; height: 28px; background: #fff; border-radius: 50%; position: absolute; top: 3px; left: ${isAnnual ? "33px" : "3px"}; transition: left 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
            .mc-pt-badge { background: var(--mc-acc); color: #fff; font-size: 11px; padding: 4px 10px; border-radius: 50px; font-weight: bold; margin-left: 8px; }
            
            /* Responsive Grid tailored to survive Gutenberg's narrow canvas */
            .mc-pt-grid { 
                display: grid; 
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
                gap: 20px; 
                width: 100%;
                max-width: 1200px; 
                margin: 0 auto; 
                align-items: stretch; 
            }
            
            .mc-pt-card { 
                height: 100%;
                width: 100%;
                background: ${bgBase}; 
                padding: 30px 20px; 
                border-radius: var(--mc-rad); 
                box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
                border: 1px solid rgba(0,0,0,0.08); 
                display: flex; 
                flex-direction: column; 
                position: relative; 
                color: var(--mc-txt); 
                text-align: left; 
            }
            .mc-pt-card.is-featured { 
                background: ${bgFeatured}; 
                transform: scale(1.02); 
                box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
                border: 2px solid var(--mc-acc); 
                z-index: 10; 
            }
            
            .mc-pt-price-wrap { display: flex; align-items: baseline; gap: 5px; margin-bottom: 30px; flex-wrap: wrap; }
            
            .mc-pt-btn { 
                width: 100%; 
                padding: 16px; 
                text-align: center; 
                border-radius: 8px; 
                font-weight: bold; 
                margin-top: auto; /* Pushes button to the bottom */
                display: block; 
            }
            .mc-pt-card.is-featured .mc-pt-btn { background: var(--mc-acc); color: #fff; border: none; }
            .mc-pt-card:not(.is-featured) .mc-pt-btn { background: transparent; border: 1px solid var(--mc-acc); color: var(--mc-acc); }
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
              className={`mc-pt-switch ${isAnnual ? "is-annual" : ""}`}
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
                    top: "-14px",
                    left: "50%",
                    transform: "translateX(-50%)",
                    background: accentColor,
                    color: "#fff",
                    fontSize: "12px",
                    fontWeight: "bold",
                    padding: "6px 20px",
                    borderRadius: "50px",
                    whiteSpace: "nowrap",
                    letterSpacing: "1px",
                    textTransform: "uppercase",
                  }}
                >
                  {tier.badge}
                </div>
              )}

              <h3
                style={{
                  margin: "0 0 10px 0",
                  fontSize: "24px",
                  fontWeight: "800",
                }}
              >
                {tier.name}
              </h3>
              <p
                style={{
                  fontSize: "15px",
                  opacity: 0.7,
                  margin: "0 0 25px 0",
                  lineHeight: "1.5",
                  minHeight: "45px",
                }}
              >
                {tier.desc}
              </p>

              <div className="mc-pt-price-wrap">
                <span style={{ fontSize: "24px", fontWeight: "bold" }}>$</span>
                <span
                  style={{
                    fontSize: "48px",
                    fontWeight: "900",
                    lineHeight: 1,
                    letterSpacing: "-1px",
                  }}
                >
                  {isAnnual ? tier.priceAnnual : tier.priceMonthly}
                </span>
                <span style={{ opacity: 0.6, fontWeight: "bold" }}>/mo</span>

                {/* Fixed Editor Strikethrough Logic */}
                {showOriginalPrice && isAnnual && tier.priceAnnualOriginal && (
                  <del
                    style={{
                      fontSize: "16px",
                      opacity: 0.5,
                      marginLeft: "4px",
                      fontWeight: "bold",
                      color: textColor,
                    }}
                  >
                    ${tier.priceAnnualOriginal}
                  </del>
                )}
              </div>

              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  gap: "12px",
                  flex: 1,
                  marginBottom: "35px",
                }}
              >
                {tier.features.map((feat, fi) => (
                  <div
                    key={fi}
                    style={{
                      display: "flex",
                      alignItems: "flex-start",
                      gap: "10px",
                      fontSize: "15px",
                      opacity: feat.included ? 1 : 0.4,
                      color: feat.included ? "inherit" : "#64748b",
                    }}
                  >
                    {feat.included ? <CheckIcon /> : <CrossIcon />}
                    <span style={{ lineHeight: "1.4" }}>{feat.text}</span>
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
