import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    panelId,
    direction,
    animationMode,
    panelWidth,
    overlayBlur,
    overlayOpacity,
    bgColor,
    textColor,
    headerText,
    triggerDelay,
    triggerScroll,
    triggerExit,
    sessionMemory,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-offcanvas-editor",
    style: {
      backgroundColor: bgColor,
      color: textColor,
      width:
        direction === "top" || direction === "bottom"
          ? "100%"
          : `${panelWidth}px`,
      maxWidth: "100%",
      minHeight: "400px",
      border: "1px solid rgba(255,255,255,0.1)",
      borderRadius: "12px",
      margin: "20px auto",
      display: "flex",
      flexDirection: "column",
      overflow: "hidden",
      boxShadow: "0 25px 50px -12px rgba(0, 0, 0, 0.25)",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Engine & Physics", "modern-core")}>
          <TextControl
            label={__("Unique Panel ID", "modern-core")}
            value={panelId}
            onChange={(val) => setAttributes({ panelId: val })}
          />
          <SelectControl
            label={__("Slide Direction", "modern-core")}
            value={direction}
            options={[
              { label: "Slide from Right", value: "right" },
              { label: "Slide from Left", value: "left" },
              { label: "Slide from Top (Curtain)", value: "top" },
              { label: "Slide from Bottom", value: "bottom" },
            ]}
            onChange={(val) => setAttributes({ direction: val })}
          />
          <SelectControl
            label={__("Interaction Physics", "modern-core")}
            value={animationMode}
            options={[
              { label: "Overlay (Slides over site)", value: "overlay" },
              { label: "Push (Pushes site aside)", value: "push" },
              { label: "Reveal (Site slides to reveal it)", value: "reveal" },
            ]}
            onChange={(val) => setAttributes({ animationMode: val })}
            help={__(
              "Push and Reveal modes only work for Left/Right directions on Desktop.",
              "modern-core",
            )}
          />
          {(direction === "left" || direction === "right") && (
            <RangeControl
              label={__("Panel Width (px)", "modern-core")}
              value={panelWidth}
              onChange={(val) => setAttributes({ panelWidth: val })}
              min={280}
              max={800}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Automatic Triggers", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Exit Intent (Desktop)", "modern-core")}
            checked={triggerExit}
            onChange={(val) => setAttributes({ triggerExit: val })}
            help={__(
              "Opens when the mouse moves to leave the browser window.",
              "modern-core",
            )}
          />
          <RangeControl
            label={__("Time Delay (Seconds)", "modern-core")}
            value={triggerDelay}
            onChange={(val) => setAttributes({ triggerDelay: val })}
            min={0}
            max={60}
            help={__("0 = Disabled", "modern-core")}
          />
          <RangeControl
            label={__("Scroll Depth (%)", "modern-core")}
            value={triggerScroll}
            onChange={(val) => setAttributes({ triggerScroll: val })}
            min={0}
            max={100}
            help={__("0 = Disabled", "modern-core")}
          />

          <div
            style={{
              marginTop: "20px",
              paddingTop: "15px",
              borderTop: "1px solid #ccc",
            }}
          >
            <ToggleControl
              label={__("Use Session Memory", "modern-core")}
              checked={sessionMemory}
              onChange={(val) => setAttributes({ sessionMemory: val })}
              help={__(
                "If enabled, automatic triggers will only fire once per user session.",
                "modern-core",
              )}
            />
          </div>
        </PanelBody>

        <PanelBody
          title={__("Design & Colors", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Header Title", "modern-core")}
            value={headerText}
            onChange={(val) => setAttributes({ headerText: val })}
          />
          <RangeControl
            label={__("Background Blur (px)", "modern-core")}
            value={overlayBlur}
            onChange={(val) => setAttributes({ overlayBlur: val })}
            min={0}
            max={30}
          />
          <RangeControl
            label={__("Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={100}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Background Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={bgColor}
            onChange={(val) => setAttributes({ bgColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            padding: "25px 30px",
            borderBottom: "1px solid rgba(255,255,255,0.05)",
            display: "flex",
            justifyContent: "space-between",
            alignItems: "center",
          }}
        >
          <h3
            style={{
              margin: 0,
              fontSize: "16px",
              fontWeight: 700,
              textTransform: "uppercase",
              letterSpacing: "1.5px",
              color: textColor,
            }}
          >
            {headerText}
          </h3>
          <span style={{ opacity: 0.5 }}>✕</span>
        </div>
        <div
          style={{
            padding: "30px",
            flex: 1,
            backgroundColor: "rgba(0,0,0,0.1)",
          }}
        >
          <InnerBlocks
            template={[
              [
                "core/paragraph",
                {
                  content:
                    "Drop your cart items, navigation menu, or filter blocks here.",
                },
              ],
            ]}
          />
        </div>
      </div>
    </>
  );
}
