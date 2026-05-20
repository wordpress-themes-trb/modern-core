import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    sectionTitle,
    sectionLink,
    queryCount,
    postType,
    layoutMode,
    colsDesktop,
    imageRatio,
    showCategory,
    showDate,
    showAuthor,
    showExcerpt,
    showReadTime,
    excerptLength,
    cardRadius,
    accentColor,
    textColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-posts-editor layout-${layoutMode}`,
    style: { width: "100%", padding: "40px 0" },
  });

  // Mock Editorial Data
  const mockPosts = Array.from({ length: queryCount }).map((_, i) => ({
    id: i,
    title:
      i === 0
        ? "The Ultimate Guide to Transitional Wardrobe Staples"
        : `Editorial Post Example ${i + 1}`,
    excerpt:
      "Discover the essential pieces that will effortlessly take your style from late summer warmth to crisp autumn evenings.",
    category: i % 2 === 0 ? "Style Guide" : "News",
    author: "Jane Doe",
    date: "Oct 12, 2025",
    readTime: "4 min read",
    image: `https://images.unsplash.com/photo-${i % 2 === 0 ? "1490481651871-ab68de25d43d" : "1445205170230-053b83016050"}?q=80&w=800&auto=format&fit=crop`,
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
            label={__("View All Link (URL)", "modern-core")}
            value={sectionLink}
            onChange={(val) => setAttributes({ sectionLink: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Query Engine", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Post Type", "modern-core")}
            value={postType}
            options={[
              { label: "Standard Blog Posts", value: "post" },
              { label: "Custom Post Types", value: "custom" },
            ]}
            onChange={(val) => setAttributes({ postType: val })}
          />
          <RangeControl
            label={__("Number of Posts", "modern-core")}
            value={queryCount}
            onChange={(val) => setAttributes({ queryCount: val })}
            min={1}
            max={12}
          />
        </PanelBody>

        <PanelBody
          title={__("Layout Architecture", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Standard Grid", value: "grid" },
              {
                label: "Featured Magazine (First post large)",
                value: "featured",
              },
              { label: "Vertical List", value: "list" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          {layoutMode !== "list" && (
            <RangeControl
              label={__("Desktop Columns", "modern-core")}
              value={colsDesktop}
              onChange={(val) => setAttributes({ colsDesktop: val })}
              min={2}
              max={4}
            />
          )}
          <SelectControl
            label={__("Image Aspect Ratio", "modern-core")}
            value={imageRatio}
            options={[
              { label: "Landscape (16:9)", value: "16/9" },
              { label: "Standard (4:3)", value: "4/3" },
              { label: "Square (1:1)", value: "1/1" },
            ]}
            onChange={(val) => setAttributes({ imageRatio: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Card Elements", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Category Pill", "modern-core")}
            checked={showCategory}
            onChange={(val) => setAttributes({ showCategory: val })}
          />
          <ToggleControl
            label={__("Show Date", "modern-core")}
            checked={showDate}
            onChange={(val) => setAttributes({ showDate: val })}
          />
          <ToggleControl
            label={__("Show Author", "modern-core")}
            checked={showAuthor}
            onChange={(val) => setAttributes({ showAuthor: val })}
          />
          <ToggleControl
            label={__("Show Excerpt", "modern-core")}
            checked={showExcerpt}
            onChange={(val) => setAttributes({ showExcerpt: val })}
          />
          <ToggleControl
            label={__("Show Read Time", "modern-core")}
            checked={showReadTime}
            onChange={(val) => setAttributes({ showReadTime: val })}
          />
          <RangeControl
            label={__("Card Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Pills & Links)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-posts-editor {
                        --mc-cols: ${colsDesktop};
                        --mc-ratio: ${imageRatio};
                        --mc-radius: ${cardRadius}px;
                        --mc-accent: ${accentColor};
                        --mc-text: ${textColor};
                    }
                    .mc-post-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
                    .mc-post-track { display: grid; gap: 30px; }
                    
                    /* Grid & Featured Mode */
                    .layout-grid .mc-post-track, .layout-featured .mc-post-track { grid-template-columns: repeat(var(--mc-cols), 1fr); }
                    .layout-featured .mc-post-track .mc-post-card:first-child { grid-column: span 2; grid-row: span 2; }
                    .layout-featured .mc-post-track .mc-post-card:first-child .mc-post-img-wrap { height: 100%; aspect-ratio: auto; min-height: 300px; }
                    .layout-featured .mc-post-track .mc-post-card:first-child .mc-post-title { font-size: 32px; }
                    
                    /* List Mode */
                    .layout-list .mc-post-track { display: flex; flex-direction: column; gap: 30px; }
                    .layout-list .mc-post-card { display: flex; gap: 30px; align-items: center; }
                    .layout-list .mc-post-img-wrap { width: 300px; flex-shrink: 0; }
                    
                    .mc-post-card { display: flex; flex-direction: column; }
                    .mc-post-img-wrap { width: 100%; aspect-ratio: var(--mc-ratio); border-radius: var(--mc-radius); overflow: hidden; position: relative; background: #f1f5f9; margin-bottom: 20px; }
                    .mc-post-pill { position: absolute; top: 15px; left: 15px; background: var(--mc-accent); color: #fff; font-size: 11px; font-weight: bold; padding: 4px 10px; border-radius: 50px; text-transform: uppercase; }
                    .mc-post-title { font-size: 20px; font-weight: bold; color: #0f172a; margin: 0 0 10px 0; line-height: 1.3; }
                    .mc-post-meta { font-size: 12px; color: var(--mc-text); opacity: 0.8; display: flex; gap: 15px; margin-bottom: 10px; }
                    `}
        </style>

        <div className="mc-post-header">
          <h2
            style={{
              margin: 0,
              fontSize: "32px",
              fontWeight: "800",
              color: "#0f172a",
            }}
          >
            {sectionTitle}
          </h2>
          {sectionLink && (
            <span
              style={{
                color: accentColor,
                fontWeight: "bold",
                fontSize: "14px",
              }}
            >
              View all articles →
            </span>
          )}
        </div>

        <div className="mc-post-track">
          {mockPosts.map((post) => (
            <div key={post.id} className="mc-post-card">
              <div className="mc-post-img-wrap">
                <img
                  src={post.image}
                  style={{ width: "100%", height: "100%", objectFit: "cover" }}
                />
                {showCategory && (
                  <div className="mc-post-pill">{post.category}</div>
                )}
              </div>
              <div className="mc-post-content">
                <div className="mc-post-meta">
                  {showDate && <span>{post.date}</span>}
                  {showAuthor && <span>By {post.author}</span>}
                  {showReadTime && <span>{post.readTime}</span>}
                </div>
                <h3 className="mc-post-title">{post.title}</h3>
                {showExcerpt && (
                  <p
                    style={{
                      fontSize: "14px",
                      color: textColor,
                      opacity: 0.8,
                      lineHeight: 1.6,
                      margin: 0,
                    }}
                  >
                    {post.excerpt}
                  </p>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
