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
    autoOpen,
    openDelay,
    showUndo,
    undoDuration,
    drawerWidth,
    drawerTitle,
    emptyHeading,
    emptyMessage,
    emptyBtnText,
    emptyBtnLink,
    bgColor,
    textColor,
    accentColor,
    overlayOpacity,
  } = attributes;

  const [previewState, setPreviewState] = React.useState("filled");

  const blockProps = useBlockProps({
    className: "modern-core-minicart-editor",
    style: {
      padding: "40px 20px",
      background: `rgba(0,0,0,${overlayOpacity / 100})`,
      minHeight: "600px",
      display: "flex",
      justifyContent: "flex-end",
      overflow: "hidden",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Editor Preview", "modern-core")}>
          <SelectControl
            label={__("Preview State", "modern-core")}
            value={previewState}
            options={[
              { label: "Filled Cart View", value: "filled" },
              { label: "Empty Cart View", value: "empty" },
            ]}
            onChange={setPreviewState}
          />
        </PanelBody>

        <PanelBody
          title={__("Behavior & Triggers", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Auto-Open on Add to Cart", "modern-core")}
            checked={autoOpen}
            onChange={(val) => setAttributes({ autoOpen: val })}
          />
          {autoOpen && (
            <RangeControl
              label={__("Open Delay (ms)", "modern-core")}
              value={openDelay}
              onChange={(val) => setAttributes({ openDelay: val })}
              min={0}
              max={2000}
              step={100}
            />
          )}
          <ToggleControl
            label={__("Enable Undo on Remove", "modern-core")}
            checked={showUndo}
            onChange={(val) => setAttributes({ showUndo: val })}
          />
          {showUndo && (
            <RangeControl
              label={__("Undo Display Duration (ms)", "modern-core")}
              value={undoDuration}
              onChange={(val) => setAttributes({ undoDuration: val })}
              min={2000}
              max={8000}
              step={500}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Typography & Content", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Drawer Title", "modern-core")}
            value={drawerTitle}
            onChange={(val) => setAttributes({ drawerTitle: val })}
          />
          <TextControl
            label={__("Empty State Heading", "modern-core")}
            value={emptyHeading}
            onChange={(val) => setAttributes({ emptyHeading: val })}
          />
          <TextControl
            label={__("Empty State Message", "modern-core")}
            value={emptyMessage}
            onChange={(val) => setAttributes({ emptyMessage: val })}
          />
          <TextControl
            label={__("Empty State Button", "modern-core")}
            value={emptyBtnText}
            onChange={(val) => setAttributes({ emptyBtnText: val })}
          />
          <TextControl
            label={__("Empty Button URL", "modern-core")}
            value={emptyBtnLink}
            onChange={(val) => setAttributes({ emptyBtnLink: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Design Layout", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Drawer Width (Desktop px)", "modern-core")}
            value={drawerWidth}
            onChange={(val) => setAttributes({ drawerWidth: val })}
            min={300}
            max={600}
          />
          <RangeControl
            label={__("Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={10}
            max={90}
          />

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Drawer Background", "modern-core")}</strong>
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
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Buttons)", "modern-core")}</strong>
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
            width: `${drawerWidth}px`,
            maxWidth: "100%",
            background: bgColor,
            color: textColor,
            display: "flex",
            flexDirection: "column",
            boxShadow: "-10px 0 30px rgba(0,0,0,0.1)",
            height: "100%",
          }}
        >
          {/* Header */}
          <div
            style={{
              padding: "20px",
              borderBottom: "1px solid rgba(0,0,0,0.1)",
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
            }}
          >
            <h3 style={{ margin: 0, fontSize: "18px", fontWeight: "bold" }}>
              {drawerTitle}{" "}
              {previewState === "filled" && (
                <span
                  style={{ opacity: 0.5, fontSize: "14px", marginLeft: "8px" }}
                >
                  (2)
                </span>
              )}
            </h3>
            <span style={{ fontSize: "20px", opacity: 0.5 }}>✕</span>
          </div>

          {/* Middle Section */}
          <div style={{ flex: 1, overflowY: "auto", padding: "20px" }}>
            {previewState === "empty" ? (
              <div style={{ textAlign: "center", paddingTop: "40px" }}>
                <svg
                  width="48"
                  height="48"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="1"
                  style={{ opacity: 0.2, marginBottom: "20px" }}
                >
                  <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                  <line x1="3" y1="6" x2="21" y2="6"></line>
                  <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <h4 style={{ margin: "0 0 10px 0", fontSize: "18px" }}>
                  {emptyHeading}
                </h4>
                <p
                  style={{
                    margin: "0 0 25px 0",
                    opacity: 0.7,
                    fontSize: "14px",
                  }}
                >
                  {emptyMessage}
                </p>
                <div
                  style={{
                    background: accentColor,
                    color: "#fff",
                    padding: "15px",
                    borderRadius: "6px",
                    fontWeight: "bold",
                  }}
                >
                  {emptyBtnText}
                </div>
              </div>
            ) : (
              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  gap: "20px",
                }}
              >
                {/* Mock Item */}
                <div style={{ display: "flex", gap: "15px" }}>
                  <div
                    style={{
                      width: "80px",
                      height: "100px",
                      background: "#f1f5f9",
                      borderRadius: "4px",
                    }}
                  ></div>
                  <div style={{ flex: 1 }}>
                    <div
                      style={{
                        display: "flex",
                        justifyContent: "space-between",
                      }}
                    >
                      <h5 style={{ margin: "0 0 5px 0", fontSize: "14px" }}>
                        Classic Oxford Shirt
                      </h5>
                      <span style={{ opacity: 0.5 }}>✕</span>
                    </div>
                    <div
                      style={{
                        fontSize: "12px",
                        opacity: 0.6,
                        marginBottom: "10px",
                      }}
                    >
                      Color: White, Size: M
                    </div>
                    <div
                      style={{
                        display: "flex",
                        justifyContent: "space-between",
                        alignItems: "center",
                      }}
                    >
                      <div
                        style={{
                          border: "1px solid rgba(0,0,0,0.1)",
                          borderRadius: "4px",
                          padding: "5px 10px",
                          fontSize: "14px",
                        }}
                      >
                        - 1 +
                      </div>
                      <div style={{ fontWeight: "bold" }}>$45.00</div>
                    </div>
                  </div>
                </div>
              </div>
            )}
          </div>

          {/* Footer */}
          {previewState === "filled" && (
            <div
              style={{
                padding: "20px",
                borderTop: "1px solid rgba(0,0,0,0.1)",
                background: "#fafafa",
              }}
            >
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  marginBottom: "20px",
                  fontSize: "18px",
                  fontWeight: "bold",
                }}
              >
                <span>Subtotal</span>
                <span>$90.00</span>
              </div>
              <div
                style={{
                  background: accentColor,
                  color: "#fff",
                  padding: "16px",
                  borderRadius: "6px",
                  textAlign: "center",
                  fontWeight: "bold",
                }}
              >
                Checkout Now
              </div>
            </div>
          )}
        </div>
      </div>
    </>
  );
}
