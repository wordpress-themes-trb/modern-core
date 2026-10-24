import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ToggleControl,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const { alignment, badgeStyle, badgeSize, gap, mobileBehavior, badges } =
    attributes;

  const blockProps = useBlockProps({
    className: `modern-core-trust-editor align-${alignment} style-${badgeStyle}`,
    style: {
      width: "100%",
      padding: "20px 0",
      backgroundColor: badgeStyle === "mono-light" ? "#0f172a" : "transparent",
    },
  });

  const updateBadge = (index, key, value) => {
    const newBadges = [...badges];
    newBadges[index][key] = value;
    setAttributes({ badges: newBadges });
  };

  const addBadge = () => {
    setAttributes({
      badges: [
        ...badges,
        {
          type: "returns",
          text: "Free Returns",
          showText: true,
          customImage: "",
        },
      ],
    });
  };

  const removeBadge = (index) => {
    setAttributes({ badges: badges.filter((_, i) => i !== index) });
  };

  // Editor SVG Rendering Helper
  const renderBadgeIcon = (type) => {
    const s = {
      width: badgeSize,
      height: badgeSize,
      fill: "none",
      stroke: "currentColor",
      strokeWidth: 1.5,
      strokeLinecap: "round",
      strokeLinejoin: "round",
    };
    if (type === "ssl")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
      );
    if (type === "guarantee")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <circle cx="12" cy="8" r="7"></circle>
          <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
        </svg>
      );
    if (type === "shipping")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <rect x="1" y="3" width="15" height="13"></rect>
          <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
          <circle cx="5.5" cy="18.5" r="2.5"></circle>
          <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
      );
    if (type === "payment")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
          <line x1="1" y1="10" x2="23" y2="10"></line>
        </svg>
      );
    if (type === "returns")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <polyline points="1 4 1 10 7 10"></polyline>
          <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
        </svg>
      );
    return (
      <svg {...s} viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M12 8v4l3 3"></path>
      </svg>
    ); // Default/Fallback
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Visual Style", "modern-core")}>
          <SelectControl
            label={__("Badge Style", "modern-core")}
            value={badgeStyle}
            options={[
              { label: "Monochrome Dark", value: "mono-dark" },
              { label: "Monochrome Light", value: "mono-light" },
              { label: "Filled Colors", value: "filled" },
            ]}
            onChange={(val) => setAttributes({ badgeStyle: val })}
          />
          <SelectControl
            label={__("Alignment", "modern-core")}
            value={alignment}
            options={[
              { label: "Left", value: "flex-start" },
              { label: "Center", value: "center" },
              { label: "Right", value: "flex-end" },
            ]}
            onChange={(val) => setAttributes({ alignment: val })}
          />
          <RangeControl
            label={__("Icon Size", "modern-core")}
            value={badgeSize}
            onChange={(val) => setAttributes({ badgeSize: val })}
            min={24}
            max={80}
          />
          <RangeControl
            label={__("Gap Between Badges", "modern-core")}
            value={gap}
            onChange={(val) => setAttributes({ gap: val })}
            min={10}
            max={60}
          />
        </PanelBody>

        <PanelBody
          title={__("Mobile Behavior", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Mobile Layout", "modern-core")}
            value={mobileBehavior}
            options={[
              { label: "Wrap to Multiple Rows", value: "wrap" },
              { label: "Single Horizontal Scrollable Row", value: "scroll" },
            ]}
            onChange={(val) => setAttributes({ mobileBehavior: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Badges Library", "modern-core")}
          initialOpen={false}
        >
          {badges.map((badge, index) => (
            <div
              key={index}
              style={{
                marginBottom: "20px",
                padding: "15px",
                border: "1px solid #e2e8f0",
                borderRadius: "8px",
              }}
            >
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  marginBottom: "10px",
                }}
              >
                <strong>Badge {index + 1}</strong>
                <span
                  style={{
                    color: "#ef4444",
                    cursor: "pointer",
                    fontSize: "12px",
                  }}
                  onClick={() => removeBadge(index)}
                >
                  Remove
                </span>
              </div>

              <SelectControl
                label="Badge Type"
                value={badge.type}
                options={[
                  { label: "SSL Secure", value: "ssl" },
                  { label: "Money Back Guarantee", value: "guarantee" },
                  { label: "Free Shipping", value: "shipping" },
                  { label: "Secure Payment", value: "payment" },
                  { label: "Free Returns", value: "returns" },
                  { label: "Custom Image Upload", value: "custom" },
                ]}
                onChange={(val) => updateBadge(index, "type", val)}
              />

              {badge.type === "custom" && (
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) =>
                      updateBadge(index, "customImage", media.url)
                    }
                    allowedTypes={["image"]}
                    render={({ open }) => (
                      <Button
                        variant="secondary"
                        onClick={open}
                        style={{ width: "100%", marginBottom: "10px" }}
                      >
                        {badge.customImage ? "Replace Image" : "Upload Image"}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>
              )}

              <TextControl
                label="Badge Text"
                value={badge.text}
                onChange={(val) => updateBadge(index, "text", val)}
              />
              <ToggleControl
                label="Show Text Below Icon"
                checked={badge.showText}
                onChange={(val) => updateBadge(index, "showText", val)}
              />
            </div>
          ))}
          <Button
            variant="primary"
            onClick={addBadge}
            style={{ width: "100%", justifyContent: "center" }}
          >
            + Add Badge
          </Button>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-trust-editor { --mc-gap: ${gap}px; --mc-size: ${badgeSize}px; }
                    .mc-tb-track { display: flex; flex-wrap: wrap; gap: var(--mc-gap); justify-content: ${alignment}; align-items: flex-start; }
                    .mc-tb-item { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; }
                    .mc-tb-icon { display: flex; align-items: center; justify-content: center; width: var(--mc-size); height: var(--mc-size); }
                    .mc-tb-text { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8; max-width: calc(var(--mc-size) + 40px); line-height: 1.2; }
                    
                    /* Styles */
                    .style-mono-dark { color: #0f172a; }
                    .style-mono-light { color: #ffffff; }
                    .style-filled .mc-tb-icon svg { stroke: #10b981; } /* Simulate filled green state in editor */
                    `}
        </style>

        <div className="mc-tb-track">
          {badges.map((b, i) => (
            <div key={i} className="mc-tb-item">
              <div className="mc-tb-icon">
                {b.type === "custom" && b.customImage ? (
                  <img
                    src={b.customImage}
                    style={{
                      width: "100%",
                      height: "100%",
                      objectFit: "contain",
                    }}
                    alt={b.text}
                  />
                ) : (
                  renderBadgeIcon(b.type)
                )}
              </div>
              {b.showText && b.text && (
                <div className="mc-tb-text">{b.text}</div>
              )}
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
