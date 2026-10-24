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
  ColorPalette,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    colsDesktop,
    colsMobile,
    iconPosition,
    iconStyle,
    iconSize,
    headingColor,
    textColor,
    accentColor,
    cardBg,
    features,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-feature-editor layout-${layoutMode} pos-${iconPosition} style-${iconStyle}`,
    style: { width: "100%", padding: "40px 0" },
  });

  const updateFeature = (index, key, value) => {
    const newFeatures = [...features];
    newFeatures[index][key] = value;
    setAttributes({ features: newFeatures });
  };

  const addFeature = () => {
    setAttributes({
      features: [
        ...features,
        {
          title: "New Feature",
          desc: "Description goes here",
          icon: "star",
          image: "",
          link: "",
        },
      ],
    });
  };

  const removeFeature = (index) => {
    const newFeatures = features.filter((_, i) => i !== index);
    setAttributes({ features: newFeatures });
  };

  // Generic placeholder SVGs for the editor visual
  const renderIcon = (iconType, size, color) => {
    const style = {
      width: size,
      height: size,
      fill: "none",
      stroke: color,
      strokeWidth: 2,
      strokeLinecap: "round",
      strokeLinejoin: "round",
    };
    if (iconType === "truck")
      return (
        <svg {...style} viewBox="0 0 24 24">
          <rect x="1" y="3" width="15" height="13"></rect>
          <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
          <circle cx="5.5" cy="18.5" r="2.5"></circle>
          <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
      );
    if (iconType === "shield")
      return (
        <svg {...style} viewBox="0 0 24 24">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
      );
    if (iconType === "refresh")
      return (
        <svg {...style} viewBox="0 0 24 24">
          <polyline points="23 4 23 10 17 10"></polyline>
          <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
        </svg>
      );
    if (iconType === "chat")
      return (
        <svg {...style} viewBox="0 0 24 24">
          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
        </svg>
      );
    return (
      <svg {...style} viewBox="0 0 24 24">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
      </svg>
    ); // Star fallback
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture", "modern-core")}>
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Horizontal Row (Grid)", value: "row" },
              { label: "Vertical List", value: "list" },
              { label: "Card Grid", value: "card" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          {layoutMode !== "list" && (
            <>
              <RangeControl
                label={__("Columns (Desktop)", "modern-core")}
                value={colsDesktop}
                onChange={(val) => setAttributes({ colsDesktop: val })}
                min={1}
                max={6}
              />
              <RangeControl
                label={__("Columns (Mobile)", "modern-core")}
                value={colsMobile}
                onChange={(val) => setAttributes({ colsMobile: val })}
                min={1}
                max={3}
              />
            </>
          )}
        </PanelBody>

        <PanelBody title={__("Icon Design", "modern-core")} initialOpen={false}>
          <SelectControl
            label={__("Icon Position", "modern-core")}
            value={iconPosition}
            options={[
              { label: "Above Heading (Centered)", value: "top" },
              { label: "Left of Heading", value: "left-heading" },
              { label: "Left (Spans Heading & Text)", value: "left-span" },
            ]}
            onChange={(val) => setAttributes({ iconPosition: val })}
          />
          <SelectControl
            label={__("Icon Background Style", "modern-core")}
            value={iconStyle}
            options={[
              { label: "No Background", value: "none" },
              { label: "Solid Circle", value: "circle" },
              { label: "Rounded Square", value: "square" },
              { label: "Outlined Circle", value: "outline" },
            ]}
            onChange={(val) => setAttributes({ iconStyle: val })}
          />
          <RangeControl
            label={__("Icon Size", "modern-core")}
            value={iconSize}
            onChange={(val) => setAttributes({ iconSize: val })}
            min={16}
            max={80}
          />
        </PanelBody>

        <PanelBody
          title={__("Features Content", "modern-core")}
          initialOpen={false}
        >
          {features.map((f, index) => (
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
                <strong>Feature {index + 1}</strong>
                <span
                  style={{
                    color: "#ef4444",
                    cursor: "pointer",
                    fontSize: "12px",
                  }}
                  onClick={() => removeFeature(index)}
                >
                  Remove
                </span>
              </div>
              <TextControl
                label="Heading"
                value={f.title}
                onChange={(val) => updateFeature(index, "title", val)}
              />
              <TextControl
                label="Description"
                value={f.desc}
                onChange={(val) => updateFeature(index, "desc", val)}
              />

              <SelectControl
                label="Default Icon"
                value={f.icon}
                options={[
                  { label: "Truck", value: "truck" },
                  { label: "Shield", value: "shield" },
                  { label: "Refresh", value: "refresh" },
                  { label: "Chat", value: "chat" },
                  { label: "Star", value: "star" },
                ]}
                onChange={(val) => updateFeature(index, "icon", val)}
              />

              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => updateFeature(index, "image", media.url)}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button
                      variant="secondary"
                      onClick={open}
                      style={{ width: "100%" }}
                    >
                      {f.image ? "Replace Custom Image" : "Upload Custom Image"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
              {f.image && (
                <Button
                  isDestructive
                  isLink
                  onClick={() => updateFeature(index, "image", "")}
                >
                  Remove Custom Image
                </Button>
              )}
            </div>
          ))}
          <Button
            variant="primary"
            onClick={addFeature}
            style={{ width: "100%", justifyContent: "center" }}
          >
            + Add Feature
          </Button>
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Icons)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Heading Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={headingColor}
            onChange={(val) => setAttributes({ headingColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Description Text", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          {layoutMode === "card" && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Card Background", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={cardBg}
                onChange={(val) => setAttributes({ cardBg: val })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-feature-editor {
                        --mc-cols: ${colsDesktop};
                        --mc-accent: ${accentColor};
                        --mc-head: ${headingColor};
                        --mc-text: ${textColor};
                        --mc-size: ${iconSize}px;
                    }
                    .mc-fl-wrapper {
                        display: grid;
                        grid-template-columns: repeat(var(--mc-cols), 1fr);
                        gap: 30px;
                    }
                    .layout-list .mc-fl-wrapper {
                        display: flex; flex-direction: column; gap: 20px;
                    }
                    
                    /* Item Structural Styles */
                    .mc-fl-item {
                        display: flex; flex-direction: column; text-decoration: none; color: inherit;
                    }
                    .layout-card .mc-fl-item {
                        background: ${cardBg}; padding: 30px; border-radius: 12px;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);
                    }
                    
                    /* Position Logic */
                    .pos-top .mc-fl-item { align-items: center; text-align: center; }
                    .pos-left-span .mc-fl-item { flex-direction: row; align-items: flex-start; gap: 15px; }
                    .pos-left-heading .mc-fl-header { display: flex; align-items: center; gap: 12px; margin-bottom: 5px; }
                    
                    /* Icon Background Styles */
                    .mc-fl-icon-box {
                        display: flex; align-items: center; justify-content: center;
                        width: calc(var(--mc-size) * 1.8); height: calc(var(--mc-size) * 1.8);
                        margin-bottom: 15px; flex-shrink: 0;
                    }
                    .pos-left-heading .mc-fl-icon-box { margin-bottom: 0; }
                    
                    .style-circle .mc-fl-icon-box { background: var(--mc-accent); border-radius: 50%; color: #fff; stroke: #fff; }
                    .style-circle .mc-fl-icon-box svg { stroke: #fff; }
                    .style-square .mc-fl-icon-box { background: var(--mc-accent); border-radius: 12px; color: #fff; stroke: #fff; }
                    .style-square .mc-fl-icon-box svg { stroke: #fff; }
                    .style-outline .mc-fl-icon-box { border: 2px solid var(--mc-accent); border-radius: 50%; color: var(--mc-accent); }
                    .style-none .mc-fl-icon-box { width: var(--mc-size); height: var(--mc-size); color: var(--mc-accent); }
                    `}
        </style>

        <div className="mc-fl-wrapper">
          {features.map((f, i) => (
            <div key={i} className="mc-fl-item">
              {iconPosition === "left-heading" ? (
                <>
                  <div className="mc-fl-header">
                    <div className="mc-fl-icon-box">
                      {f.image ? (
                        <img
                          src={f.image}
                          style={{
                            width: iconSize,
                            height: iconSize,
                            objectFit: "contain",
                          }}
                        />
                      ) : (
                        renderIcon(
                          f.icon,
                          iconSize,
                          iconStyle === "circle" || iconStyle === "square"
                            ? "#ffffff"
                            : accentColor,
                        )
                      )}
                    </div>
                    <h4
                      style={{
                        margin: 0,
                        fontSize: "18px",
                        fontWeight: "bold",
                        color: headingColor,
                      }}
                    >
                      {f.title}
                    </h4>
                  </div>
                  <div
                    style={{
                      color: textColor,
                      fontSize: "14px",
                      lineHeight: 1.5,
                      opacity: 0.8,
                    }}
                  >
                    {f.desc}
                  </div>
                </>
              ) : (
                <>
                  <div className="mc-fl-icon-box">
                    {f.image ? (
                      <img
                        src={f.image}
                        style={{
                          width: iconSize,
                          height: iconSize,
                          objectFit: "contain",
                        }}
                      />
                    ) : (
                      renderIcon(
                        f.icon,
                        iconSize,
                        iconStyle === "circle" || iconStyle === "square"
                          ? "#ffffff"
                          : accentColor,
                      )
                    )}
                  </div>
                  <div className="mc-fl-content">
                    <h4
                      style={{
                        margin: "0 0 5px 0",
                        fontSize: "18px",
                        fontWeight: "bold",
                        color: headingColor,
                      }}
                    >
                      {f.title}
                    </h4>
                    <div
                      style={{
                        color: textColor,
                        fontSize: "14px",
                        lineHeight: 1.5,
                        opacity: 0.8,
                      }}
                    >
                      {f.desc}
                    </div>
                  </div>
                </>
              )}
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
