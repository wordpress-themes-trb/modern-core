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
    videoUrl,
    posterUrl,
    mobileBehavior,
    mobileVideoUrl,
    mobilePosterUrl,
    playbackSpeed,
    showMuteToggle,
    showPlayToggle,
    overlayType,
    overlayColor,
    overlayOpacity,
    minHeight,
    contentAlign,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-video-hero-editor align-${contentAlign}`,
    style: {
      minHeight: `${minHeight}vh`,
      position: "relative",
      display: "flex",
      alignItems: "center",
      justifyContent:
        contentAlign === "center"
          ? "center"
          : contentAlign === "right"
            ? "flex-end"
            : "flex-start",
      padding: "40px 8%",
      overflow: "hidden",
      backgroundColor: "#0f172a",
    },
  });

  const DEFAULT_TEMPLATE = [
    [
      "core/heading",
      {
        level: 1,
        content: "Cinematic Motion.",
        style: {
          typography: { fontSize: "clamp(40px, 6vw, 72px)", fontWeight: "800" },
          color: { text: "#ffffff" },
        },
      },
    ],
    [
      "core/paragraph",
      {
        content:
          "Immersive storytelling through high-fidelity video backgrounds.",
        style: {
          typography: { fontSize: "18px" },
          color: { text: "rgba(255,255,255,0.8)" },
          spacing: { margin: { bottom: "30px" } },
        },
      },
    ],
    [
      "core/buttons",
      { layout: { type: "flex", justifyContent: contentAlign } },
      [
        [
          "core/button",
          { text: "Discover More", style: { border: { radius: "50px" } } },
        ],
      ],
    ],
  ];

  if (!videoUrl) {
    return (
      <div
        {...blockProps}
        style={{
          ...blockProps.style,
          justifyContent: "center",
          backgroundColor: "#f1f5f9",
          border: "2px dashed #cbd5e1",
        }}
      >
        <div style={{ textAlign: "center", color: "#64748b" }}>
          <svg
            width="48"
            height="48"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.5"
            style={{ marginBottom: "15px" }}
          >
            <polygon points="23 7 16 12 23 17 23 7"></polygon>
            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
          </svg>
          <h3 style={{ margin: "0 0 15px 0", color: "#0f172a" }}>
            Add Video Hero
          </h3>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ videoUrl: media.url })}
              allowedTypes={["video"]}
              render={({ open }) => (
                <Button variant="primary" onClick={open}>
                  Upload or Select Video
                </Button>
              )}
            />
          </MediaUploadCheck>
        </div>
      </div>
    );
  }

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Video Source", "modern-core")}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ videoUrl: media.url })}
              allowedTypes={["video"]}
              render={({ open }) => (
                <Button
                  variant="secondary"
                  onClick={open}
                  style={{
                    width: "100%",
                    justifyContent: "center",
                    marginBottom: "10px",
                  }}
                >
                  Change Main Video
                </Button>
              )}
            />
          </MediaUploadCheck>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ posterUrl: media.url })}
              allowedTypes={["image"]}
              render={({ open }) => (
                <Button
                  variant="secondary"
                  onClick={open}
                  style={{ width: "100%", justifyContent: "center" }}
                >
                  {posterUrl ? "Change Poster Image" : "Add Poster Image"}
                </Button>
              )}
            />
          </MediaUploadCheck>
        </PanelBody>

        <PanelBody
          title={__("Mobile Behavior", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("On Mobile Devices", "modern-core")}
            value={mobileBehavior}
            options={[
              { label: "Play Same Video", value: "play" },
              { label: "Play Mobile-Optimized Video", value: "video" },
              { label: "Show Static Fallback Image", value: "image" },
            ]}
            onChange={(val) => setAttributes({ mobileBehavior: val })}
          />
          {mobileBehavior === "video" && (
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) =>
                  setAttributes({ mobileVideoUrl: media.url })
                }
                allowedTypes={["video"]}
                render={({ open }) => (
                  <Button
                    variant="secondary"
                    onClick={open}
                    style={{
                      width: "100%",
                      justifyContent: "center",
                      marginTop: "10px",
                    }}
                  >
                    Select Mobile Video
                  </Button>
                )}
              />
            </MediaUploadCheck>
          )}
          {(mobileBehavior === "video" || mobileBehavior === "image") && (
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) =>
                  setAttributes({ mobilePosterUrl: media.url })
                }
                allowedTypes={["image"]}
                render={({ open }) => (
                  <Button
                    variant="secondary"
                    onClick={open}
                    style={{
                      width: "100%",
                      justifyContent: "center",
                      marginTop: "10px",
                    }}
                  >
                    Select Mobile Poster/Image
                  </Button>
                )}
              />
            </MediaUploadCheck>
          )}
        </PanelBody>

        <PanelBody
          title={__("Playback & UI", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Playback Speed", "modern-core")}
            value={playbackSpeed}
            onChange={(val) => setAttributes({ playbackSpeed: val })}
            min={0.25}
            max={2.0}
            step={0.25}
          />
          <ToggleControl
            label={__("Show Sound Toggle (Unmute)", "modern-core")}
            checked={showMuteToggle}
            onChange={(val) => setAttributes({ showMuteToggle: val })}
          />
          <ToggleControl
            label={__("Show Play/Pause Toggle", "modern-core")}
            checked={showPlayToggle}
            onChange={(val) => setAttributes({ showPlayToggle: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Layout & Overlay", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Content Alignment", "modern-core")}
            value={contentAlign}
            options={[
              { label: "Left", value: "left" },
              { label: "Center", value: "center" },
              { label: "Right", value: "right" },
            ]}
            onChange={(val) => setAttributes({ contentAlign: val })}
          />
          <RangeControl
            label={__("Min Height (vh)", "modern-core")}
            value={minHeight}
            onChange={(val) => setAttributes({ minHeight: val })}
            min={40}
            max={100}
          />
          <SelectControl
            label={__("Overlay Type", "modern-core")}
            value={overlayType}
            options={[
              {
                label: "Bottom Third Gradient (Text Protection)",
                value: "bottom-gradient",
              },
              { label: "Solid Color Overlay", value: "solid" },
              { label: "No Overlay", value: "none" },
            ]}
            onChange={(val) => setAttributes({ overlayType: val })}
          />
          {overlayType !== "none" && (
            <>
              <p style={{ marginTop: "5px", marginBottom: "5px" }}>
                <strong>{__("Overlay Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={overlayColor}
                onChange={(val) => setAttributes({ overlayColor: val })}
              />
              <RangeControl
                label={__("Opacity (%)", "modern-core")}
                value={overlayOpacity}
                onChange={(val) => setAttributes({ overlayOpacity: val })}
                min={0}
                max={100}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Editor Video Preview */}
        <video
          src={videoUrl}
          autoPlay
          loop
          muted
          playsInline
          style={{
            position: "absolute",
            inset: 0,
            width: "100%",
            height: "100%",
            objectFit: "cover",
            zIndex: 0,
          }}
        />

        {/* Overlay Preview */}
        {overlayType !== "none" && (
          <div
            style={{
              position: "absolute",
              inset: 0,
              zIndex: 1,
              background:
                overlayType === "solid"
                  ? overlayColor
                  : `linear-gradient(to top, ${overlayColor} 0%, rgba(0,0,0,0) 50%)`,
              opacity: overlayOpacity / 100,
            }}
          ></div>
        )}

        <div
          style={{
            position: "relative",
            zIndex: 2,
            width: "100%",
            maxWidth: "800px",
            textAlign: contentAlign,
          }}
        >
          <InnerBlocks template={DEFAULT_TEMPLATE} />
        </div>
      </div>
    </>
  );
}
