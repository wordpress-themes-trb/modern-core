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
  ColorPalette,
  Button,
  ToggleControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    mediaWidth,
    mediaSide,
    mediaUrl,
    overlayOpacity,
    contentBg,
    contentColor,
    dividerShape,
    vAlign,
    minHeight,
    mobileStack,
  } = attributes;

  const isLeft = mediaSide === "left";
  const contentWidth = 100 - mediaWidth;

  const blockProps = useBlockProps({
    className: `modern-core-split-editor shape-${dividerShape} side-${mediaSide}`,
    style: {
      display: "flex",
      flexDirection: isLeft ? "row" : "row-reverse",
      width: "100%",
      minHeight: `${minHeight}px`,
      backgroundColor: contentBg,
      color: contentColor,
      position: "relative",
      overflow: "hidden",
    },
  });

  const DEFAULT_TEMPLATE = [
    [
      "core/paragraph",
      {
        content: "ESSENTIALS",
        style: {
          typography: {
            fontSize: "11px",
            letterSpacing: "2px",
            fontWeight: "bold",
          },
          color: { text: "#3b82f6" },
        },
      },
    ],
    [
      "core/heading",
      {
        level: 2,
        content: "Form meets function.",
        style: {
          typography: {
            fontSize: "42px",
            fontWeight: "800",
            lineHeight: "1.1",
          },
          spacing: { margin: { top: "10px", bottom: "20px" } },
        },
      },
    ],
    [
      "core/paragraph",
      {
        content:
          "Engineered for the modern aesthetic. Discover the uncompromising quality of our newest arrivals.",
        style: {
          typography: { fontSize: "16px", lineHeight: "1.6" },
          spacing: { margin: { bottom: "30px" } },
        },
      },
    ],
    [
      "core/buttons",
      {},
      [
        [
          "core/button",
          { text: "Shop Collection", style: { border: { radius: "50px" } } },
        ],
      ],
    ],
  ];

  // Placeholder image if none is selected
  const bgImage =
    mediaUrl ||
    "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E";

  // Simulated Diagonal Clip Path for Editor Preview
  let clipPath = "none";
  if (dividerShape === "diagonal") {
    clipPath = isLeft
      ? "polygon(0 0, 100% 0, calc(100% - 8vw) 100%, 0 100%)"
      : "polygon(8vw 0, 100% 0, 100% 100%, 0 100%)";
  }

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Layout & Ratio", "modern-core")}>
          <SelectControl
            label={__("Visual Position", "modern-core")}
            value={mediaSide}
            options={[
              { label: "Image Left | Content Right", value: "left" },
              { label: "Content Left | Image Right", value: "right" },
            ]}
            onChange={(val) => setAttributes({ mediaSide: val })}
          />
          <RangeControl
            label={__("Image Width (%)", "modern-core")}
            value={mediaWidth}
            onChange={(val) => setAttributes({ mediaWidth: val })}
            min={30}
            max={70}
          />
          <RangeControl
            label={__("Minimum Height (px)", "modern-core")}
            value={minHeight}
            onChange={(val) => setAttributes({ minHeight: val })}
            min={400}
            max={1000}
            step={50}
          />
          <SelectControl
            label={__("Divider Shape", "modern-core")}
            value={dividerShape}
            options={[
              { label: "Straight Cut", value: "straight" },
              { label: "15° Diagonal Slash", value: "diagonal" },
            ]}
            onChange={(val) => setAttributes({ dividerShape: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Visual Zone (Image)", "modern-core")}
          initialOpen={false}
        >
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ mediaUrl: media.url })}
              allowedTypes={["image"]}
              render={({ open }) => (
                <Button
                  variant="secondary"
                  onClick={open}
                  style={{
                    width: "100%",
                    justifyContent: "center",
                    marginBottom: "15px",
                  }}
                >
                  {mediaUrl ? "Change Image" : "Select Image"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          <RangeControl
            label={__("Gradient Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={100}
          />
        </PanelBody>

        <PanelBody
          title={__("Content Zone (Text)", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Vertical Alignment", "modern-core")}
            value={vAlign}
            options={[
              { label: "Top", value: "flex-start" },
              { label: "Center", value: "center" },
              { label: "Bottom", value: "flex-end" },
            ]}
            onChange={(val) => setAttributes({ vAlign: val })}
          />
          <SelectControl
            label={__("Mobile Stacking Order", "modern-core")}
            value={mobileStack}
            options={[
              { label: "Image on Top", value: "media-first" },
              { label: "Content on Top", value: "content-first" },
            ]}
            onChange={(val) => setAttributes({ mobileStack: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Content Background Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={contentBg}
            onChange={(val) => setAttributes({ contentBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Content Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={contentColor}
            onChange={(val) => setAttributes({ contentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* 1. Visual Zone */}
        <div
          style={{
            width: `${mediaWidth}%`,
            position: "relative",
            backgroundImage: `url(${bgImage})`,
            backgroundSize: "cover",
            backgroundPosition: "center",
            clipPath: clipPath,
            zIndex: 2,
          }}
        >
          <div
            style={{
              position: "absolute",
              inset: 0,
              background: `linear-gradient(${isLeft ? "to right" : "to left"}, rgba(0,0,0,0) 0%, rgba(0,0,0,${overlayOpacity / 100}) 100%)`,
            }}
          ></div>
        </div>

        {/* 2. Content Zone */}
        <div
          style={{
            width: `${contentWidth}%`,
            display: "flex",
            flexDirection: "column",
            justifyContent: vAlign,
            padding: "60px 8%",
            zIndex: 1,
          }}
        >
          <InnerBlocks
            template={DEFAULT_TEMPLATE}
            allowedBlocks={[
              "core/heading",
              "core/paragraph",
              "core/buttons",
              "core/button",
              "modern-core/countdown",
              "modern-core/trust-strip",
            ]}
          />
        </div>
      </div>
    </>
  );
}
