import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    modalWidth,
    modalRadius,
    overlayColor,
    overlayBlur,
    animStyle,
    splitRatio,
    showRating,
    showDesc,
    showTrust,
    accentColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-quick-view-editor",
    style: {
      padding: "40px",
      backgroundColor: overlayColor,
      backdropFilter: `blur(${overlayBlur}px)`,
      minHeight: "600px",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      border: "2px dashed rgba(255,255,255,0.2)",
    },
  });

  const imgWidth = `${splitRatio}%`;
  const textWidth = `${100 - splitRatio}%`;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Modal Architecture", "modern-core")}>
          <RangeControl
            label={__("Modal Max Width (px)", "modern-core")}
            value={modalWidth}
            onChange={(val) => setAttributes({ modalWidth: val })}
            min={600}
            max={1200}
            step={50}
          />
          <RangeControl
            label={__("Image Column Width (%)", "modern-core")}
            value={splitRatio}
            onChange={(val) => setAttributes({ splitRatio: val })}
            min={30}
            max={70}
          />
          <RangeControl
            label={__("Modal Border Radius", "modern-core")}
            value={modalRadius}
            onChange={(val) => setAttributes({ modalRadius: val })}
            min={0}
            max={30}
          />
          <SelectControl
            label={__("Entrance Animation", "modern-core")}
            value={animStyle}
            options={[
              { label: "Scale Up (Pop)", value: "scale" },
              { label: "Slide Up", value: "slide" },
              { label: "Fade Only", value: "fade" },
            ]}
            onChange={(val) => setAttributes({ animStyle: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Content Elements", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Show Rating", "modern-core")}
            checked={showRating}
            onChange={(val) => setAttributes({ showRating: val })}
          />
          <ToggleControl
            label={__("Show Short Description", "modern-core")}
            checked={showDesc}
            onChange={(val) => setAttributes({ showDesc: val })}
          />
          <ToggleControl
            label={__("Show Trust Indicators", "modern-core")}
            checked={showTrust}
            onChange={(val) => setAttributes({ showTrust: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Colors & Overlays", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Background Blur (px)", "modern-core")}
            value={overlayBlur}
            onChange={(val) => setAttributes({ overlayBlur: val })}
            min={0}
            max={30}
          />
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Overlay Tint Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={overlayColor}
            onChange={(val) => setAttributes({ overlayColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            width: "100%",
            maxWidth: `${modalWidth}px`,
            background: "#fff",
            borderRadius: `${modalRadius}px`,
            display: "flex",
            overflow: "hidden",
            boxShadow: "0 25px 50px -12px rgba(0,0,0,0.5)",
            position: "relative",
          }}
        >
          <div
            style={{
              position: "absolute",
              top: "15px",
              right: "15px",
              width: "30px",
              height: "30px",
              background: "rgba(0,0,0,0.05)",
              borderRadius: "50%",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              fontSize: "12px",
              cursor: "pointer",
              zIndex: 10,
            }}
          >
            ✕
          </div>

          <div
            style={{
              width: imgWidth,
              background: "#f8fafc",
              padding: "40px",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
            }}
          >
            <div
              style={{
                width: "100%",
                aspectRatio: "3/4",
                background: "#cbd5e1",
                borderRadius: "8px",
              }}
            ></div>
          </div>

          <div
            style={{
              width: textWidth,
              padding: "40px",
              display: "flex",
              flexDirection: "column",
              justifyContent: "center",
            }}
          >
            <h3
              style={{
                fontSize: "24px",
                margin: "0 0 10px 0",
                color: "#0f172a",
              }}
            >
              Premium Silhouette Jacket
            </h3>
            {showRating && (
              <div
                style={{
                  color: "#f59e0b",
                  fontSize: "14px",
                  marginBottom: "15px",
                }}
              >
                ★★★★★{" "}
                <span
                  style={{ color: "#0f172a", opacity: 0.5, fontSize: "12px" }}
                >
                  (42)
                </span>
              </div>
            )}
            <div
              style={{
                fontSize: "20px",
                fontWeight: "bold",
                color: "#0f172a",
                marginBottom: "15px",
              }}
            >
              $120.00
            </div>
            {showDesc && (
              <p
                style={{
                  fontSize: "14px",
                  color: "#0f172a",
                  opacity: 0.7,
                  marginBottom: "20px",
                  lineHeight: 1.5,
                }}
              >
                A meticulously crafted piece designed for the modern wardrobe.
                Features tailored precision and high-grade materials.
              </p>
            )}

            <div style={{ marginBottom: "20px" }}>
              <div
                style={{
                  height: "20px",
                  width: "100%",
                  background: "#f1f5f9",
                  borderRadius: "4px",
                  marginBottom: "10px",
                }}
              ></div>
              <div
                style={{
                  height: "40px",
                  width: "100%",
                  background: "#f1f5f9",
                  borderRadius: "4px",
                }}
              ></div>
            </div>

            <div
              style={{
                width: "100%",
                padding: "15px",
                background: accentColor,
                color: "#fff",
                textAlign: "center",
                fontWeight: "bold",
                borderRadius: "8px",
              }}
            >
              Add to Cart
            </div>
            <div
              style={{
                textAlign: "center",
                marginTop: "15px",
                fontSize: "13px",
                color: accentColor,
                textDecoration: "underline",
              }}
            >
              View full details
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
