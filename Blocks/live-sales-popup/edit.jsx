import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    dataSource,
    lookbackDays,
    maxOrders,
    privacyMode,
    position,
    animStyle,
    durationMs,
    intervalMs,
    cardBg,
    textColor,
    accentColor,
    cardRadius,
    shadowOpacity,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-sales-popup-editor pos-${position}`,
    style: {
      padding: "40px",
      backgroundColor: "#f1f5f9",
      position: "relative",
      minHeight: "300px",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      border: "1px dashed #cbd5e1",
    },
  });

  const mockName =
    privacyMode === "full_name"
      ? "Sarah Jenkins"
      : privacyMode === "first_name"
        ? "Sarah"
        : "A customer";

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Data Engine", "modern-core")}>
          <SelectControl
            label={__("Data Source", "modern-core")}
            value={dataSource}
            options={[
              { label: "Dummy / Demo Data", value: "dummy" },
              { label: "Live WooCommerce Orders", value: "live" },
            ]}
            onChange={(val) => setAttributes({ dataSource: val })}
            help={
              dataSource === "dummy"
                ? "Simulates sales using placeholder data. Great for new stores."
                : "Pulls real orders from WooCommerce."
            }
          />

          {dataSource === "live" && (
            <>
              <RangeControl
                label={__("Lookback Window (Days)", "modern-core")}
                value={lookbackDays}
                onChange={(val) => setAttributes({ lookbackDays: val })}
                min={1}
                max={30}
                help="How far back to look for recent orders."
              />
              <RangeControl
                label={__("Max Notifications to Queue", "modern-core")}
                value={maxOrders}
                onChange={(val) => setAttributes({ maxOrders: val })}
                min={3}
                max={20}
              />
              <SelectControl
                label={__("Customer Privacy Mode", "modern-core")}
                value={privacyMode}
                options={[
                  {
                    label: "First Name Only (e.g., Sarah)",
                    value: "first_name",
                  },
                  {
                    label: "Full Name (e.g., Sarah Jenkins)",
                    value: "full_name",
                  },
                  { label: "Anonymous (e.g., A customer)", value: "anonymous" },
                ]}
                onChange={(val) => setAttributes({ privacyMode: val })}
              />
            </>
          )}
        </PanelBody>

        <PanelBody
          title={__("Timing & Animation", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Screen Position", "modern-core")}
            value={position}
            options={[
              { label: "Bottom Left", value: "bottom-left" },
              { label: "Bottom Right", value: "bottom-right" },
              { label: "Top Left", value: "top-left" },
              { label: "Top Right", value: "top-right" },
            ]}
            onChange={(val) => setAttributes({ position: val })}
          />
          <SelectControl
            label={__("Entrance Animation", "modern-core")}
            value={animStyle}
            options={[
              { label: "Slide In", value: "slide" },
              { label: "Fade In", value: "fade" },
              { label: "Scale Up", value: "scale" },
            ]}
            onChange={(val) => setAttributes({ animStyle: val })}
          />
          <RangeControl
            label={__("Visible Duration (ms)", "modern-core")}
            value={durationMs}
            onChange={(val) => setAttributes({ durationMs: val })}
            min={2000}
            max={10000}
            step={500}
          />
          <RangeControl
            label={__("Interval Between Popups (ms)", "modern-core")}
            value={intervalMs}
            onChange={(val) => setAttributes({ intervalMs: val })}
            min={1000}
            max={15000}
            step={500}
          />
        </PanelBody>

        <PanelBody title={__("Card Design", "modern-core")} initialOpen={false}>
          <RangeControl
            label={__("Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
          <RangeControl
            label={__("Shadow Opacity (%)", "modern-core")}
            value={shadowOpacity}
            onChange={(val) => setAttributes({ shadowOpacity: val })}
            min={0}
            max={40}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Card Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={cardBg}
            onChange={(val) => setAttributes({ cardBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Accent Color (Verified Icon)", "modern-core")}</strong>
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
            position: "absolute",
            top: "10px",
            left: "10px",
            fontSize: "11px",
            fontWeight: "bold",
            color: "#64748b",
            textTransform: "uppercase",
          }}
        >
          Live Sales Popup Preview (
          {dataSource === "live" ? "Live Mode" : "Dummy Mode"})
        </div>

        <div
          style={{
            background: cardBg,
            color: textColor,
            padding: "15px",
            borderRadius: `${cardRadius}px`,
            boxShadow: `0 10px 30px rgba(0,0,0,${shadowOpacity / 100})`,
            width: "320px",
            display: "flex",
            gap: "15px",
            alignItems: "center",
          }}
        >
          <div
            style={{
              width: "60px",
              height: "60px",
              borderRadius: "4px",
              background: "#e2e8f0",
              flexShrink: 0,
              overflow: "hidden",
            }}
          >
            <img
              src="https://images.unsplash.com/photo-1521572163474-6f64f9fa023e?q=80&w=150&auto=format&fit=crop"
              alt="Product"
              style={{ width: "100%", height: "100%", objectFit: "cover" }}
            />
          </div>
          <div style={{ flex: 1 }}>
            <div
              style={{ fontSize: "12px", opacity: 0.8, marginBottom: "4px" }}
            >
              {mockName} from Chicago just bought
            </div>
            <div
              style={{
                fontSize: "14px",
                fontWeight: "bold",
                lineHeight: 1.3,
                marginBottom: "4px",
              }}
            >
              Classic Oxford Cotton Shirt
            </div>
            <div
              style={{
                fontSize: "11px",
                display: "flex",
                alignItems: "center",
                gap: "5px",
                opacity: 0.7,
              }}
            >
              <span>2 hours ago</span>
              <span
                style={{
                  display: "flex",
                  alignItems: "center",
                  gap: "2px",
                  color: accentColor,
                  fontWeight: "bold",
                  marginLeft: "auto",
                  opacity: 1,
                }}
              >
                <svg
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  stroke="currentColor"
                  strokeWidth="2"
                >
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Verified
              </span>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
