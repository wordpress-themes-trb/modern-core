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

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    minHeight,
    bgType,
    bgColor,
    bgImageUrl,
    overlayOpacity,
    enableKenBurns,
    enableNoise,
    contentCard,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-hero-editor layout-${layoutMode}`,
    style: {
      minHeight: `${minHeight}vh`,
      backgroundColor: bgColor,
      backgroundImage:
        bgType === "image" && bgImageUrl
          ? `url(${bgImageUrl})`
          : bgType === "gradient"
            ? "radial-gradient(circle at center, #1e293b 0%, #0f172a 100%)"
            : "none",
      backgroundSize: "cover",
      backgroundPosition: "center",
      position: "relative",
      display: "flex",
      alignItems: layoutMode.includes("center")
        ? "center"
        : layoutMode.includes("bottom")
          ? "flex-end"
          : "flex-start",
      justifyContent: layoutMode.includes("center")
        ? "center"
        : layoutMode.includes("split")
          ? "space-between"
          : "flex-start",
      padding: "40px 5%",
      overflow: "hidden",
    },
  });

  // The Cinematic Default Template based on your prompt
  const DEFAULT_TEMPLATE = [
    [
      "core/group",
      {
        className: "mc-hero-content-wrapper mc-stagger-group",
        style: {
          textAlign: layoutMode === "full-center" ? "center" : "left",
          width: "100%",
          maxWidth: layoutMode === "full-center" ? "800px" : "600px",
          backgroundColor: contentCard
            ? "rgba(255,255,255,0.05)"
            : "transparent",
          backdropFilter: contentCard ? "blur(20px)" : "none",
          padding: contentCard ? "40px" : "0",
          borderRadius: contentCard ? "20px" : "0",
          zIndex: 2,
        },
      },
      [
        // 1. Pre-headline Pill Badge
        [
          "core/paragraph",
          {
            content: "NEW COLLECTION",
            style: {
              typography: {
                fontSize: "11px",
                letterSpacing: "2px",
                textTransform: "uppercase",
              },
              color: { text: "#ffffff" },
              elements: { link: { color: "#ffffff" } },
              border: {
                radius: "50px",
                color: "rgba(255,255,255,0.2)",
                width: "1px",
                style: "solid",
              },
              spacing: {
                padding: {
                  top: "6px",
                  bottom: "6px",
                  left: "16px",
                  right: "16px",
                },
                margin: { bottom: "20px" },
              },
            },
            className: "mc-stagger-item",
          },
        ],

        // 2. Display Headline (Simulating the accented word with bold/italic for now)
        [
          "core/heading",
          {
            level: 1,
            content: "Redefine your <br><em>standard</em> of living.",
            style: {
              typography: {
                fontSize: "clamp(40px, 6vw, 80px)",
                lineHeight: "1.1",
                fontWeight: "800",
              },
              color: { text: "#ffffff" },
            },
            className: "mc-stagger-item",
          },
        ],

        // 3. Subheadline
        [
          "core/paragraph",
          {
            content:
              "Discover the uncompromising quality and cinematic design of our newest arrivals. Engineered for the modern aesthetic.",
            style: {
              typography: { fontSize: "18px", lineHeight: "1.6" },
              color: { text: "rgba(255,255,255,0.7)" },
              spacing: { margin: { top: "20px", bottom: "40px" } },
            },
            className: "mc-stagger-item",
          },
        ],

        // 4. Button Group
        [
          "core/buttons",
          {
            layout: {
              type: "flex",
              justifyContent: layoutMode === "full-center" ? "center" : "left",
            },
            className: "mc-stagger-item",
          },
          [
            [
              "core/button",
              {
                text: "Shop the Collection",
                style: {
                  color: { background: "#ffffff", text: "#0f172a" },
                  border: { radius: "50px" },
                },
              },
            ],
            [
              "core/button",
              {
                text: "Watch the Film",
                className: "is-style-outline",
                style: {
                  color: { text: "#ffffff" },
                  border: { radius: "50px" },
                },
              },
            ],
          ],
        ],
      ],
    ],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Layout & Sizing", "modern-core")}>
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Full Bleed (Centered)", value: "full-center" },
              { label: "Full Bleed (Left)", value: "full-left" },
              { label: "Split (Content Left)", value: "split-left" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <RangeControl
            label={__("Minimum Height (vh)", "modern-core")}
            value={minHeight}
            onChange={(val) => setAttributes({ minHeight: val })}
            min={40}
            max={100}
          />
          <ToggleControl
            label={__("Use Glassmorphism Content Card", "modern-core")}
            checked={contentCard}
            onChange={(val) => setAttributes({ contentCard: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Background Engine", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Background Type", "modern-core")}
            value={bgType}
            options={[
              { label: "Solid Color", value: "solid" },
              { label: "Cinematic Gradient", value: "gradient" },
              { label: "Image", value: "image" },
            ]}
            onChange={(val) => setAttributes({ bgType: val })}
          />
          {bgType === "image" && (
            <div style={{ marginBottom: "20px" }}>
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) => setAttributes({ bgImageUrl: media.url })}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button variant="secondary" onClick={open}>
                      {bgImageUrl ? "Change Image" : "Select Image"}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
            </div>
          )}
          <ColorPalette
            value={bgColor}
            onChange={(val) => setAttributes({ bgColor: val })}
          />
          <RangeControl
            label={__("Dark Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={100}
          />
        </PanelBody>

        <PanelBody
          title={__("Cinematic Effects", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Ken Burns Background Zoom", "modern-core")}
            checked={enableKenBurns}
            onChange={(val) => setAttributes({ enableKenBurns: val })}
            help={__(
              "Slowly zooms the background image for a living, cinematic feel.",
              "modern-core",
            )}
          />
          <ToggleControl
            label={__("Film Grain / Noise Overlay", "modern-core")}
            checked={enableNoise}
            onChange={(val) => setAttributes({ enableNoise: val })}
            help={__(
              "Adds organic texture over the entire banner.",
              "modern-core",
            )}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Editor Dark Overlay */}
        <div
          style={{
            position: "absolute",
            inset: 0,
            backgroundColor: "#000",
            opacity: overlayOpacity / 100,
            zIndex: 1,
          }}
        ></div>
        {/* InnerBlocks Dropzone */}
        <InnerBlocks template={DEFAULT_TEMPLATE} templateLock={false} />
      </div>
    </>
  );
}
