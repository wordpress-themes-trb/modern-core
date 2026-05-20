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
  RangeControl,
  ColorPalette,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const { bgColor, bgImageUrl, overlayOpacity } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-slide-editor",
    style: {
      minWidth: "100%",
      flexShrink: 0,
      position: "relative",
      backgroundColor: bgColor,
      backgroundImage: bgImageUrl ? `url(${bgImageUrl})` : "none",
      backgroundSize: "cover",
      backgroundPosition: "center",
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      padding: "40px",
      border: "1px solid rgba(255,255,255,0.2)",
    },
  });

  const DEFAULT_CONTENT = [
    [
      "core/paragraph",
      {
        content: "NEW ARRIVALS",
        style: {
          typography: { fontSize: "11px", letterSpacing: "2px" },
          color: { text: "#ffffff" },
        },
      },
    ],
    [
      "core/heading",
      {
        level: 2,
        content: "Cinematic Storytelling.",
        style: {
          typography: { fontSize: "48px", fontWeight: "800" },
          color: { text: "#ffffff" },
        },
      },
    ],
    [
      "core/buttons",
      {},
      [
        [
          "core/button",
          { text: "Explore Now", style: { border: { radius: "50px" } } },
        ],
      ],
    ],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Slide Background", "modern-core")}>
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
                    justifyContent: "center",
                    marginBottom: "15px",
                  }}
                >
                  {bgImageUrl ? "Change Image" : "Select Image"}
                </Button>
              )}
            />
          </MediaUploadCheck>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Background Color", "modern-core")}</strong>
          </p>
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
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            position: "absolute",
            inset: 0,
            backgroundColor: "#000",
            opacity: overlayOpacity / 100,
            zIndex: 1,
          }}
        ></div>
        <div style={{ position: "relative", zIndex: 2, width: "100%" }}>
          <InnerBlocks template={DEFAULT_CONTENT} templateLock={false} />
        </div>
      </div>
    </>
  );
}
