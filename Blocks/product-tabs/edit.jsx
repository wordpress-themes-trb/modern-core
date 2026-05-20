import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
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
    sectionTitle,
    sectionSubtitle,
    tabStyle,
    tabAlignment,
    animationStyle,
    tabsData,
    colsDesktop,
    colsMobile,
    imageRatio,
    cardBg,
    cardRadius,
    accentColor,
    inactiveColor,
  } = attributes;

  // Editor State to mimic active tab preview
  const [activeTab, setActiveTab] = React.useState(0);

  const blockProps = useBlockProps({
    className: `modern-core-tabs-editor style-${tabStyle} align-${tabAlignment}`,
    style: { width: "100%", padding: "40px 0" },
  });

  const updateTab = (index, key, value) => {
    const newTabs = [...tabsData];
    newTabs[index][key] = value;
    setAttributes({ tabsData: newTabs });
  };

  // Mock products for visual grid
  const mockProducts = Array.from({
    length: tabsData[activeTab]?.count || 4,
  }).map((_, i) => ({
    id: i,
    title: `Mock Product ${i + 1}`,
    price: "$85.00",
    image: `https://images.unsplash.com/photo-1521572163474-6f64f9fa023e?q=80&w=400&auto=format&fit=crop`,
  }));

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Section Header", "modern-core")}>
          <TextControl
            label={__("Title", "modern-core")}
            value={sectionTitle}
            onChange={(val) => setAttributes({ sectionTitle: val })}
          />
          <TextControl
            label={__("Subtitle", "modern-core")}
            value={sectionSubtitle}
            onChange={(val) => setAttributes({ sectionSubtitle: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Tabs Configuration", "modern-core")}
          initialOpen={false}
        >
          {tabsData.map((tab, index) => (
            <div
              key={index}
              style={{
                marginBottom: "20px",
                padding: "15px",
                border: "1px solid #e2e8f0",
                borderRadius: "8px",
              }}
            >
              <TextControl
                label={`Tab ${index + 1} Label`}
                value={tab.label}
                onChange={(val) => updateTab(index, "label", val)}
              />
              <SelectControl
                label="Query Source"
                value={tab.query}
                options={[
                  { label: "Recent / New Arrivals", value: "recent" },
                  { label: "Best Sellers", value: "popularity" },
                  { label: "On Sale", value: "sale" },
                  { label: "Top Rated", value: "rating" },
                ]}
                onChange={(val) => updateTab(index, "query", val)}
              />
              <RangeControl
                label="Product Count"
                value={tab.count}
                onChange={(val) => updateTab(index, "count", val)}
                min={4}
                max={16}
              />
            </div>
          ))}
          <p style={{ fontSize: "12px", opacity: 0.6 }}>
            *Note: Arrays are fixed to 4 in this demo component for editor
            stability.
          </p>
        </PanelBody>

        <PanelBody
          title={__("Design & Animation", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Tab Style", "modern-core")}
            value={tabStyle}
            options={[
              { label: "Pill Buttons", value: "pill" },
              { label: "Minimal Underline", value: "underline" },
              { label: "Boxed", value: "box" },
            ]}
            onChange={(val) => setAttributes({ tabStyle: val })}
          />
          <SelectControl
            label={__("Tab Alignment", "modern-core")}
            value={tabAlignment}
            options={[
              { label: "Left", value: "flex-start" },
              { label: "Center", value: "center" },
              { label: "Right", value: "flex-end" },
            ]}
            onChange={(val) => setAttributes({ tabAlignment: val })}
          />
          <SelectControl
            label={__("Switch Animation", "modern-core")}
            value={animationStyle}
            options={[
              { label: "Smooth Fade", value: "fade" },
              { label: "Slide Horizontal", value: "slide" },
              { label: "Scale Pop", value: "scale" },
              { label: "Instant", value: "none" },
            ]}
            onChange={(val) => setAttributes({ animationStyle: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Grid & Colors", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Desktop Columns", "modern-core")}
            value={colsDesktop}
            onChange={(val) => setAttributes({ colsDesktop: val })}
            min={2}
            max={6}
          />
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Active Brand Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Inactive / Outline Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={inactiveColor}
            onChange={(val) => setAttributes({ inactiveColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-tabs-editor {
                        --mc-cols: ${colsDesktop};
                        --mc-accent: ${accentColor};
                        --mc-inactive: ${inactiveColor};
                    }
                    .mc-tab-btn-mock {
                        padding: 10px 24px; font-size: 14px; font-weight: bold; cursor: pointer; transition: all 0.3s;
                    }
                    .style-pill .mc-tab-btn-mock {
                        border-radius: 50px; border: 2px solid var(--mc-inactive); color: var(--mc-inactive);
                    }
                    .style-pill .mc-tab-btn-mock.is-active {
                        background: var(--mc-accent); border-color: var(--mc-accent); color: #fff;
                    }
                    .style-underline .mc-tab-btn-mock {
                        border-bottom: 2px solid transparent; color: var(--mc-inactive); padding: 10px 15px;
                    }
                    .style-underline .mc-tab-btn-mock.is-active {
                        border-bottom: 2px solid var(--mc-accent); color: var(--mc-accent);
                    }
                    .mc-tabs-grid-mock {
                        display: grid; grid-template-columns: repeat(var(--mc-cols), 1fr); gap: 30px; margin-top: 40px;
                    }
                    `}
        </style>

        {/* Header */}
        <div style={{ textAlign: "center", marginBottom: "30px" }}>
          {sectionSubtitle && (
            <p
              style={{
                fontSize: "12px",
                textTransform: "uppercase",
                letterSpacing: "1px",
                color: accentColor,
                margin: "0 0 10px 0",
              }}
            >
              {sectionSubtitle}
            </p>
          )}
          <h2 style={{ fontSize: "32px", fontWeight: "800", margin: 0 }}>
            {sectionTitle}
          </h2>
        </div>

        {/* Tab Navigation */}
        <div
          style={{
            display: "flex",
            justifyContent: tabAlignment,
            gap: "15px",
            borderBottom:
              tabStyle === "underline" ? "1px solid rgba(0,0,0,0.1)" : "none",
          }}
        >
          {tabsData.map((tab, index) => (
            <div
              key={index}
              className={`mc-tab-btn-mock ${activeTab === index ? "is-active" : ""}`}
              onClick={() => setActiveTab(index)}
            >
              {tab.label}
            </div>
          ))}
        </div>

        {/* Grid Output */}
        <div className="mc-tabs-grid-mock">
          {mockProducts.map((p) => (
            <div
              key={p.id}
              style={{
                background: cardBg,
                borderRadius: `${cardRadius}px`,
                overflow: "hidden",
                border: "1px solid rgba(0,0,0,0.05)",
              }}
            >
              <div
                style={{
                  aspectRatio: imageRatio,
                  backgroundImage: `url(${p.image})`,
                  backgroundSize: "cover",
                }}
              ></div>
              <div style={{ padding: "20px" }}>
                <h4 style={{ margin: "0 0 10px 0", fontSize: "14px" }}>
                  {p.title}
                </h4>
                <div style={{ fontWeight: "bold" }}>{p.price}</div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
