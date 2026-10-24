import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes, clientId }) {
  const {
    wrapperWidth,
    contentWidth,
    layoutStyle,
    columnCount,
    rowCount,
    columnGap,
    rowGap,
    stackOnMobile,
    paddingTopDesktop,
    paddingBottomDesktop,
    paddingLeftDesktop,
    paddingRightDesktop,
    paddingTopMobile,
    paddingBottomMobile,
    paddingLeftMobile,
    paddingRightMobile,
    bgType,
    bgColor,
    textColor,
    bgImageUrl,
    bgOverlayOpacity,
    topShape,
    bottomShape,
    shapeHeight,
    shapeColor,
    borderType,
    borderColor,
    borderRadius,
    hasShadow,
    overflowHidden,
    hideWhenEmpty,
  } = attributes;

  // 1. CLEANED UP PROPS: Removed all 100vw/negative margin hacks.
  // We rely on Gutenberg's native alignment tools here.
  const blockProps = useBlockProps({
    className: `modern-core-section-wrapper-editor mc-section-${clientId}`,
    style: {
      backgroundColor:
        bgType === "solid" || bgType === "image" ? bgColor : "transparent",
      backgroundImage:
        bgType === "image" && bgImageUrl ? `url(${bgImageUrl})` : "none",
      backgroundSize: "cover",
      backgroundPosition: "center",
      color: textColor,
      paddingTop: `${paddingTopDesktop}px`,
      paddingBottom: `${paddingBottomDesktop}px`,
      paddingLeft: `${paddingLeftDesktop}px`,
      paddingRight: `${paddingRightDesktop}px`,
      borderRadius: `${borderRadius}px`,
      boxShadow: hasShadow ? "0 25px 50px -12px rgba(0,0,0,0.1)" : "none",
      borderTop:
        borderType === "top" || borderType === "all"
          ? `1px solid ${borderColor}`
          : "none",
      borderBottom:
        borderType === "bottom" || borderType === "all"
          ? `1px solid ${borderColor}`
          : "none",
      position: "relative",
      overflow: overflowHidden ? "hidden" : "visible",
      width: "100%",
    },
  });

  const innerContainerStyle = {
    position: "relative",
    zIndex: 10,
    maxWidth: contentWidth === "contained" ? "1200px" : "100%",
    margin: "0 auto",
    width: "100%",
  };

  const shapeOptions = [
    { label: "None", value: "none" },
    { label: "Diagonal Angle", value: "angle" },
    { label: "Straight Line Overlay", value: "straight" },
  ];

  return (
    <>
      {/* 2. THE GUTENBERG DOM PIERCER: 
          This forces Gutenberg's hidden internal wrapper to become the grid, 
          and draws dashed borders around the empty drop zones so you can see them! */}
      <style>
        {`
          .mc-section-${clientId} .mc-sw-content-mock > .block-editor-inner-blocks > .block-editor-block-list__layout {
              display: ${layoutStyle === "multi-column" ? "grid" : "block"};
              grid-template-columns: ${layoutStyle === "multi-column" ? `repeat(${columnCount}, 1fr)` : "none"};
              grid-template-rows: ${layoutStyle === "multi-column" && rowCount > 1 ? `repeat(${rowCount}, 1fr)` : "none"};
              gap: ${layoutStyle === "multi-column" ? `${rowGap}px ${columnGap}px` : "0"};
              min-height: 120px;
          }
          /* Visual Drop Zones for empty grid cells */
          .mc-section-${clientId} .mc-sw-content-mock > .block-editor-inner-blocks > .block-editor-block-list__layout > .wp-block {
              border: 1px dashed rgba(0,0,0,0.2);
              min-height: 60px;
              padding: 10px;
          }
        `}
      </style>

      <InspectorControls>
        {/* Layout Engine with Row/Col Matrix */}
        <PanelBody title={__("Layout Engine", "modern-core")}>
          <SelectControl
            label={__("Content Arrangement", "modern-core")}
            value={layoutStyle}
            options={[
              { label: "Standard Stack (Top to Bottom)", value: "standard" },
              { label: "Multi-Column Layout", value: "multi-column" },
            ]}
            onChange={(val) => setAttributes({ layoutStyle: val })}
          />

          {layoutStyle === "multi-column" && (
            <>
              <p style={{ fontWeight: 600, margin: "15px 0 10px 0" }}>
                Grid Dimensions
              </p>
              <RangeControl
                label={__("Number of Columns", "modern-core")}
                value={columnCount}
                onChange={(val) => setAttributes({ columnCount: val })}
                min={1}
                max={6}
                step={1}
              />
              <RangeControl
                label={__("Number of Rows", "modern-core")}
                value={rowCount}
                onChange={(val) => setAttributes({ rowCount: val })}
                min={1}
                max={6}
                step={1}
              />

              <p style={{ fontWeight: 600, margin: "15px 0 10px 0" }}>
                Grid Spacing (Gaps)
              </p>
              <RangeControl
                label={__("Column Gap (Horizontal px)", "modern-core")}
                value={columnGap}
                onChange={(val) => setAttributes({ columnGap: val })}
                min={0}
                max={100}
                step={5}
              />
              <RangeControl
                label={__("Row Gap (Vertical px)", "modern-core")}
                value={rowGap}
                onChange={(val) => setAttributes({ rowGap: val })}
                min={0}
                max={100}
                step={5}
              />

              <ToggleControl
                label={__("Stack Columns on Mobile", "modern-core")}
                checked={stackOnMobile}
                onChange={(val) => setAttributes({ stackOnMobile: val })}
                help="Automatically collapses grid into a single vertical column on phones."
                style={{ marginTop: "15px" }}
              />
            </>
          )}

          <hr style={{ margin: "20px 0", opacity: 0.2 }} />

          <SelectControl
            label={__("Inner Content Width", "modern-core")}
            value={contentWidth}
            options={[
              {
                label: "Contained (Centered Readable Area)",
                value: "contained",
              },
              { label: "Full Width (Stretches to edges)", value: "full" },
            ]}
            onChange={(val) => setAttributes({ contentWidth: val })}
          />
          <ToggleControl
            label={__("Hide when empty", "modern-core")}
            checked={hideWhenEmpty}
            onChange={(val) => setAttributes({ hideWhenEmpty: val })}
          />
        </PanelBody>

        {/* Omni Padding */}
        <PanelBody
          title={__("Spacing & Padding", "modern-core")}
          initialOpen={false}
        >
          <p style={{ fontWeight: 600, margin: "0 0 10px 0" }}>
            Desktop Spacing
          </p>
          <RangeControl
            label="Top (px)"
            value={paddingTopDesktop}
            onChange={(val) => setAttributes({ paddingTopDesktop: val })}
            min={0}
            max={200}
            step={10}
          />
          <RangeControl
            label="Bottom (px)"
            value={paddingBottomDesktop}
            onChange={(val) => setAttributes({ paddingBottomDesktop: val })}
            min={0}
            max={200}
            step={10}
          />
          <RangeControl
            label="Left (px)"
            value={paddingLeftDesktop}
            onChange={(val) => setAttributes({ paddingLeftDesktop: val })}
            min={0}
            max={200}
            step={10}
          />
          <RangeControl
            label="Right (px)"
            value={paddingRightDesktop}
            onChange={(val) => setAttributes({ paddingRightDesktop: val })}
            min={0}
            max={200}
            step={10}
          />
          <hr style={{ margin: "20px 0", opacity: 0.2 }} />
          <p style={{ fontWeight: 600, margin: "0 0 10px 0" }}>
            Mobile Spacing
          </p>
          <RangeControl
            label="Top (px)"
            value={paddingTopMobile}
            onChange={(val) => setAttributes({ paddingTopMobile: val })}
            min={0}
            max={120}
            step={5}
          />
          <RangeControl
            label="Bottom (px)"
            value={paddingBottomMobile}
            onChange={(val) => setAttributes({ paddingBottomMobile: val })}
            min={0}
            max={120}
            step={5}
          />
          <RangeControl
            label="Left (px)"
            value={paddingLeftMobile}
            onChange={(val) => setAttributes({ paddingLeftMobile: val })}
            min={0}
            max={120}
            step={5}
          />
          <RangeControl
            label="Right (px)"
            value={paddingRightMobile}
            onChange={(val) => setAttributes({ paddingRightMobile: val })}
            min={0}
            max={120}
            step={5}
          />
        </PanelBody>

        <PanelBody
          title={__("Background & Color", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Background Type", "modern-core")}
            value={bgType}
            options={[
              { label: "Transparent", value: "transparent" },
              { label: "Solid Color", value: "solid" },
              { label: "Image", value: "image" },
            ]}
            onChange={(val) => setAttributes({ bgType: val })}
          />
          {(bgType === "solid" || bgType === "image") && (
            <ColorPalette
              value={bgColor}
              onChange={(val) => setAttributes({ bgColor: val })}
            />
          )}
          {bgType === "image" && (
            <>
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => setAttributes({ bgImageUrl: media.url })}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button
                      variant="secondary"
                      onClick={open}
                      style={{
                        width: "100%",
                        marginTop: "15px",
                        justifyContent: "center",
                      }}
                    >
                      {bgImageUrl ? "Replace Image" : "Upload Image"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
              <RangeControl
                label={__("Overlay Opacity (%)", "modern-core")}
                value={bgOverlayOpacity}
                onChange={(val) => setAttributes({ bgOverlayOpacity: val })}
                min={0}
                max={100}
              />
            </>
          )}
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color Mode", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Premium Dividers", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Top Edge Divider", "modern-core")}
            value={topShape}
            options={shapeOptions}
            onChange={(val) => setAttributes({ topShape: val })}
          />
          <SelectControl
            label={__("Bottom Edge Divider", "modern-core")}
            value={bottomShape}
            options={shapeOptions}
            onChange={(val) => setAttributes({ bottomShape: val })}
          />
          {(topShape !== "none" || bottomShape !== "none") && (
            <>
              <RangeControl
                label={__("Divider Height", "modern-core")}
                value={shapeHeight}
                onChange={(val) => setAttributes({ shapeHeight: val })}
                min={10}
                max={150}
              />
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Divider Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={shapeColor}
                onChange={(val) => setAttributes({ shapeColor: val })}
              />
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Container Effects", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Borders", "modern-core")}
            value={borderType}
            options={[
              { label: "None", value: "none" },
              { label: "Top & Bottom", value: "tb" },
              { label: "Top Only", value: "top" },
              { label: "Bottom Only", value: "bottom" },
              { label: "All Sides", value: "all" },
            ]}
            onChange={(val) => setAttributes({ borderType: val })}
          />
          {borderType !== "none" && (
            <ColorPalette
              value={borderColor}
              onChange={(val) => setAttributes({ borderColor: val })}
            />
          )}
          <RangeControl
            label={__("Border Radius", "modern-core")}
            value={borderRadius}
            onChange={(val) => setAttributes({ borderRadius: val })}
            min={0}
            max={50}
          />
          <ToggleControl
            label={__("Container Shadow", "modern-core")}
            checked={hasShadow}
            onChange={(val) => setAttributes({ hasShadow: val })}
          />
          <ToggleControl
            label={__("Overflow Hidden", "modern-core")}
            checked={overflowHidden}
            onChange={(val) => setAttributes({ overflowHidden: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {bgType === "image" && bgOverlayOpacity > 0 && (
          <div
            style={{
              position: "absolute",
              inset: 0,
              backgroundColor: "#000",
              opacity: bgOverlayOpacity / 100,
              zIndex: 0,
              borderRadius: "inherit",
            }}
          ></div>
        )}
        <div
          style={{
            position: "absolute",
            top: 0,
            left: 0,
            background: "rgba(0,0,0,0.5)",
            color: "#fff",
            fontSize: "10px",
            padding: "2px 8px",
            zIndex: 20,
            borderBottomRightRadius: "4px",
          }}
        >
          Layout System{" "}
          {layoutStyle === "multi-column" &&
            `(${columnCount} Col x ${rowCount} Row)`}
        </div>

        <div className="mc-sw-content-mock" style={innerContainerStyle}>
          {/* Default template creates placeholder text if empty to keep the grid open */}
          <InnerBlocks
            template={[
              ["core/paragraph", { placeholder: "Drop content here..." }],
            ]}
          />
        </div>
      </div>
    </>
  );
}
