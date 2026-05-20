import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody,
  TextControl,
  RangeControl,
  SelectControl,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    logoType,
    brandName,
    mediaUrl,
    mediaId,
    logoWidth,
    tagline,
    alignment,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-logo-block",
    style: {
      display: "flex",
      flexDirection: "column",
      alignItems:
        alignment === "center"
          ? "center"
          : alignment === "right"
            ? "flex-end"
            : "flex-start",
      justifyContent: "center",
      width: "100%",
    },
  });

  const onSelectMedia = (media) => {
    setAttributes({ mediaUrl: media.url, mediaId: media.id });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Logo Settings", "modern-core")}>
          <SelectControl
            label={__("Alignment", "modern-core")}
            value={alignment}
            options={[
              { label: "Left", value: "left" },
              { label: "Center", value: "center" },
              { label: "Right", value: "right" },
            ]}
            onChange={(val) => setAttributes({ alignment: val })}
          />
          <SelectControl
            label={__("Logo Type", "modern-core")}
            value={logoType}
            options={[
              { label: "Text Only", value: "text" },
              { label: "Image Only", value: "image" },
              { label: "Image + Text", value: "both" },
            ]}
            onChange={(val) => setAttributes({ logoType: val })}
          />

          {(logoType === "text" || logoType === "both") && (
            <TextControl
              label={__("Brand Name", "modern-core")}
              value={brandName}
              onChange={(val) => setAttributes({ brandName: val })}
            />
          )}

          {(logoType === "image" || logoType === "both") && (
            <div style={{ marginBottom: "20px" }}>
              <p style={{ marginBottom: "8px", fontSize: "13px" }}>
                <strong>{__("Image Logo", "modern-core")}</strong>
              </p>
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={onSelectMedia}
                  allowedTypes={["image"]}
                  value={mediaId}
                  render={({ open }) => (
                    <Button
                      variant={mediaUrl ? "secondary" : "primary"}
                      onClick={open}
                      style={{ width: "100%", justifyContent: "center" }}
                    >
                      {mediaUrl
                        ? __("Change Image", "modern-core")
                        : __("Upload Logo", "modern-core")}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
              {mediaUrl && (
                <Button
                  variant="link"
                  isDestructive
                  onClick={() => setAttributes({ mediaUrl: "", mediaId: 0 })}
                  style={{
                    marginTop: "10px",
                    display: "block",
                    textAlign: "center",
                    width: "100%",
                  }}
                >
                  {__("Remove Image", "modern-core")}
                </Button>
              )}
            </div>
          )}

          {(logoType === "image" || logoType === "both") && (
            <RangeControl
              label={__("Image Width (px)", "modern-core")}
              value={logoWidth}
              onChange={(val) => setAttributes({ logoWidth: val })}
              min={50}
              max={400}
            />
          )}

          <TextControl
            label={__("Tagline (Optional)", "modern-core")}
            value={tagline}
            onChange={(val) => setAttributes({ tagline: val })}
            help={__("Appears below the logo.", "modern-core")}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div style={{ display: "flex", alignItems: "center", gap: "15px" }}>
          {(logoType === "image" || logoType === "both") &&
            (mediaUrl ? (
              <img
                src={mediaUrl}
                alt={brandName}
                style={{
                  width: `${logoWidth}px`,
                  height: "auto",
                  display: "block",
                }}
              />
            ) : (
              <div
                style={{
                  width: `${logoWidth}px`,
                  height: "60px",
                  backgroundColor: "#f1f5f9",
                  border: "1px dashed #cbd5e1",
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                  color: "#64748b",
                  fontSize: "12px",
                }}
              >
                Upload Image
              </div>
            ))}

          {(logoType === "text" || logoType === "both") && (
            <h2
              style={{
                margin: 0,
                fontWeight: 800,
                letterSpacing: "-1px",
                fontSize: "1.75rem",
                color: "#0f172a",
              }}
            >
              {brandName}
            </h2>
          )}
        </div>

        {tagline && (
          <p
            style={{
              margin: "5px 0 0 0",
              fontSize: "0.85rem",
              color: "#64748b",
              letterSpacing: "0.5px",
            }}
          >
            {tagline}
          </p>
        )}
      </div>
    </>
  );
}
