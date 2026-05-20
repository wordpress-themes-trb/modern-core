import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  ToggleControl,
  RangeControl,
  ColorPalette,
  TextControl,
  SelectControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    bgColor,
    iconColor,
    activeColor,
    centerBtnBg,
    centerBtnIcon,
    blurIntensity,
    autoHide,
    showOnDesktop,
    cartDrawerId,
    enableProductMode,
    enableCartMode,
    checkoutBehavior,
    searchHeading,
    searchTrending,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-sticky-bar-editor",
    style: {
      backgroundColor: bgColor,
      borderRadius: "24px 24px 0 0",
      borderTop: "1px solid rgba(255,255,255,0.1)",
      padding: "15px 20px 25px 20px",
      display: "flex",
      justifyContent: "space-between",
      alignItems: "flex-end",
      position: "relative",
      maxWidth: "500px",
      margin: "40px auto 0 auto",
      boxShadow: "0 -10px 40px rgba(0,0,0,0.2)",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Contextual Page Modes", "modern-core")}>
          <ToggleControl
            label={__("Product Page Mode", "modern-core")}
            checked={enableProductMode}
            onChange={(val) => setAttributes({ enableProductMode: val })}
            help={__(
              "Slides up a product summary and Add to Cart button when scrolling past the main product image.",
              "modern-core",
            )}
          />
          <ToggleControl
            label={__("Cart Page Mode", "modern-core")}
            checked={enableCartMode}
            onChange={(val) => setAttributes({ enableCartMode: val })}
            help={__(
              "Morphs the bar into a massive Proceed to Checkout button when on the cart page.",
              "modern-core",
            )}
          />
          <SelectControl
            label={__("Checkout Page Behavior", "modern-core")}
            value={checkoutBehavior}
            options={[
              { label: "Hide Bar Entirely (Distraction Free)", value: "hide" },
              { label: "Show Order Summary Toggle", value: "summary" },
              { label: "Keep Standard Bar", value: "standard" },
            ]}
            onChange={(val) => setAttributes({ checkoutBehavior: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Search Overlay", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Trending Heading", "modern-core")}
            value={searchHeading}
            onChange={(val) => setAttributes({ searchHeading: val })}
          />
          <TextControl
            label={__("Trending Terms (Comma separated)", "modern-core")}
            value={searchTrending}
            onChange={(val) => setAttributes({ searchTrending: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Display Rules & Integration", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Auto-Hide on Scroll Down", "modern-core")}
            checked={autoHide}
            onChange={(val) => setAttributes({ autoHide: val })}
          />
          <ToggleControl
            label={__("Show on Desktop", "modern-core")}
            checked={showOnDesktop}
            onChange={(val) => setAttributes({ showOnDesktop: val })}
          />
          <RangeControl
            label={__("Frosted Glass Blur (px)", "modern-core")}
            value={blurIntensity}
            onChange={(val) => setAttributes({ blurIntensity: val })}
            min={0}
            max={30}
          />
          <TextControl
            label={__("Cart Drawer ID", "modern-core")}
            value={cartDrawerId}
            onChange={(val) => setAttributes({ cartDrawerId: val })}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Background Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={bgColor}
            onChange={(val) => setAttributes({ bgColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Inactive Icon Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={iconColor}
            onChange={(val) => setAttributes({ iconColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Active Icon Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={activeColor}
            onChange={(val) => setAttributes({ activeColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Center Button Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={centerBtnBg}
            onChange={(val) => setAttributes({ centerBtnBg: val })}
          />
        </PanelBody>
      </InspectorControls>

      {/* Visual Editor Layout (Kept Simple for the Editor Canvas) */}
      <div style={{ textAlign: "center", width: "100%", marginBottom: "20px" }}>
        <p
          style={{
            fontSize: "12px",
            opacity: 0.5,
            textTransform: "uppercase",
            letterSpacing: "1px",
          }}
        >
          Mobile Sticky Bar Preview
        </p>
      </div>
      <div {...blockProps}>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            gap: "4px",
            color: activeColor,
            width: "20%",
          }}
        >
          <svg
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
          >
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
          <span style={{ fontSize: "10px", fontWeight: "600" }}>Home</span>
        </div>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            gap: "4px",
            color: iconColor,
            width: "20%",
          }}
        >
          <svg
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
          >
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <span style={{ fontSize: "10px", fontWeight: "500" }}>Search</span>
        </div>
        <div
          style={{
            width: "20%",
            display: "flex",
            justifyContent: "center",
            position: "relative",
          }}
        >
          <div
            style={{
              position: "absolute",
              bottom: "-5px",
              width: "56px",
              height: "56px",
              backgroundColor: centerBtnBg,
              borderRadius: "50%",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              color: centerBtnIcon,
            }}
          >
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            </svg>
          </div>
        </div>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            gap: "4px",
            color: iconColor,
            width: "20%",
          }}
        >
          <svg
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
          >
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
          <span style={{ fontSize: "10px", fontWeight: "500" }}>Saved</span>
        </div>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            gap: "4px",
            color: iconColor,
            width: "20%",
          }}
        >
          <svg
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
          >
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          </svg>
          <span style={{ fontSize: "10px", fontWeight: "500" }}>Account</span>
        </div>
      </div>
    </>
  );
}
