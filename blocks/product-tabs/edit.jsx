import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ColorPalette,
  ComboboxControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes, clientId }) {
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

  const addTab = () => {
    if (tabsData.length >= 6) return;
    setAttributes({
      tabsData: [
        ...tabsData,
        { label: "New Tab", query: "recent", categoryId: 0, count: 4 },
      ],
    });
  };

  const removeTab = (index) => {
    const newTabs = tabsData.filter((_, i) => i !== index);
    setAttributes({ tabsData: newTabs });
    if (activeTab >= newTabs.length)
      setActiveTab(Math.max(0, newTabs.length - 1));
  };

  // Fetch WooCommerce Categories for the dropdown
  const { productCategories, isResolvingCats } = useSelect((select) => {
    const { getEntityRecords, isResolving } = select("core");
    const query = { per_page: 100, hide_empty: true };
    return {
      productCategories: getEntityRecords("taxonomy", "product_cat", query),
      isResolvingCats: isResolving("getEntityRecords", [
        "taxonomy",
        "product_cat",
        query,
      ]),
    };
  }, []);

  const catOptions = React.useMemo(() => {
    if (!productCategories)
      return [{ label: "Loading categories...", value: 0 }];
    return [
      { label: "Select a Category", value: 0 },
      ...productCategories.map((c) => ({
        label: c.name,
        value: c.id,
      })),
    ];
  }, [productCategories]);

  // Mock products for visual grid
  const mockProducts = Array.from({
    length: tabsData[activeTab]?.count || 4,
  }).map((_, i) => ({
    id: i,
    title: `Mock Product ${i + 1}`,
    price: "$85.00",
    image: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E",
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
                position: "relative",
              }}
            >
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  marginBottom: "10px",
                }}
              >
                <strong>Tab {index + 1}</strong>
                <button
                  onClick={() => removeTab(index)}
                  style={{
                    color: "#ef4444",
                    background: "none",
                    border: "none",
                    cursor: "pointer",
                    fontSize: "12px",
                    fontWeight: "bold",
                  }}
                >
                  Remove
                </button>
              </div>

              <TextControl
                label="Tab Label"
                value={tab.label}
                onChange={(val) => updateTab(index, "label", val)}
              />
              <SelectControl
                label="Query Source"
                value={tab.query}
                options={[
                  { label: "Specific Category", value: "category" },
                  { label: "Recent / New Arrivals", value: "recent" },
                  { label: "Best Sellers", value: "popularity" },
                  { label: "On Sale", value: "sale" },
                  { label: "Top Rated", value: "rating" },
                ]}
                onChange={(val) => updateTab(index, "query", val)}
              />

              {/* Dynamic Category Selector */}
              {tab.query === "category" && (
                <ComboboxControl
                  label="Select Category"
                  value={tab.categoryId}
                  options={catOptions}
                  onChange={(val) =>
                    updateTab(index, "categoryId", parseInt(val, 10) || 0)
                  }
                  help={isResolvingCats ? "Fetching categories..." : ""}
                />
              )}

              <RangeControl
                label="Product Count"
                value={tab.count}
                onChange={(val) => updateTab(index, "count", val)}
                min={2}
                max={16}
              />
            </div>
          ))}
          {tabsData.length < 6 && (
            <button
              onClick={addTab}
              style={{
                width: "100%",
                padding: "10px",
                background: "#f8fafc",
                border: "1px dashed #cbd5e1",
                borderRadius: "4px",
                cursor: "pointer",
                fontWeight: "bold",
              }}
            >
              + Add Tab
            </button>
          )}
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
          title={__("Minimalist Colors", "modern-core")}
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
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Card Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={cardBg}
            onChange={(val) => setAttributes({ cardBg: val })}
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
            .style-box .mc-tab-btn-mock {
                border: 1px solid rgba(0,0,0,0.1); color: var(--mc-inactive); border-radius: 4px;
            }
            .style-box .mc-tab-btn-mock.is-active {
                border-color: var(--mc-accent); color: var(--mc-accent); box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            }
            
            .mc-tabs-grid-mock {
                display: grid; grid-template-columns: repeat(var(--mc-cols), 1fr); gap: 30px; margin-top: 40px;
            }
          `}
        </style>

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

        <div
          style={{
            display: "flex",
            justifyContent: tabAlignment,
            gap: "15px",
            borderBottom:
              tabStyle === "underline" ? "1px solid rgba(0,0,0,0.1)" : "none",
            flexWrap: "wrap",
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

        <div className="mc-tabs-grid-mock">
          {mockProducts.map((p) => (
            <div
              key={p.id}
              style={{
                background: cardBg,
                borderRadius: `${cardRadius}px`,
                overflow: "hidden",
                border: "1px solid rgba(0,0,0,0.08)",
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
