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
    sectionTitle,
    displayMode,
    maxProducts,
    storageDays,
    emptyBehavior,
    fallbackQuery,
    fallbackTitle,
    colsDesktop,
    colsMobile,
    accentColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-recent-editor mode-${displayMode}`,
    style: {
      width: "100%",
      padding: "40px 5%",
      border: "2px dashed #cbd5e1",
      backgroundColor: "#f8fafc",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Section Header", "modern-core")}>
          <TextControl
            label={__("Section Title", "modern-core")}
            value={sectionTitle}
            onChange={(val) => setAttributes({ sectionTitle: val })}
            help={__(
              "Displayed when the user has browsing history.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Logic & Persistence", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("If History is Empty:", "modern-core")}
            value={emptyBehavior}
            options={[
              { label: "Hide Section Entirely", value: "hide" },
              { label: "Show Fallback Products", value: "fallback" },
            ]}
            onChange={(val) => setAttributes({ emptyBehavior: val })}
          />
          {emptyBehavior === "fallback" && (
            <>
              <TextControl
                label={__("Fallback Title", "modern-core")}
                value={fallbackTitle}
                onChange={(val) => setAttributes({ fallbackTitle: val })}
              />
              <SelectControl
                label={__("Fallback Query", "modern-core")}
                value={fallbackQuery}
                options={[
                  { label: "Best Sellers", value: "popularity" },
                  { label: "New Arrivals", value: "date" },
                  { label: "On Sale", value: "sale" },
                ]}
                onChange={(val) => setAttributes({ fallbackQuery: val })}
              />
            </>
          )}
          <RangeControl
            label={__("Max Products to Store", "modern-core")}
            value={maxProducts}
            onChange={(val) => setAttributes({ maxProducts: val })}
            min={4}
            max={16}
          />
          <RangeControl
            label={__("Keep History For (Days)", "modern-core")}
            value={storageDays}
            onChange={(val) => setAttributes({ storageDays: val })}
            min={1}
            max={30}
          />
        </PanelBody>

        <PanelBody
          title={__("Layout Architecture", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Display Mode", "modern-core")}
            value={displayMode}
            options={[
              { label: "Horizontal Carousel", value: "carousel" },
              { label: "Standard Grid", value: "grid" },
            ]}
            onChange={(val) => setAttributes({ displayMode: val })}
          />
          <RangeControl
            label={__("Columns (Desktop)", "modern-core")}
            value={colsDesktop}
            onChange={(val) => setAttributes({ colsDesktop: val })}
            min={2}
            max={6}
          />
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            display: "flex",
            justifyContent: "space-between",
            alignItems: "flex-end",
            marginBottom: "30px",
          }}
        >
          <h2
            style={{
              margin: 0,
              fontSize: "24px",
              fontWeight: "800",
              color: "#0f172a",
            }}
          >
            {sectionTitle}{" "}
            <span
              style={{ opacity: 0.3, fontSize: "14px", fontWeight: "normal" }}
            >
              (Personalized)
            </span>
          </h2>
        </div>

        <div
          style={{
            display: "flex",
            gap: "20px",
            overflow: "hidden",
            flexWrap: displayMode === "grid" ? "wrap" : "nowrap",
          }}
        >
          {Array.from({ length: colsDesktop }).map((_, i) => (
            <div
              key={i}
              style={{
                flex:
                  displayMode === "grid"
                    ? `0 0 calc((100% - ${(colsDesktop - 1) * 20}px) / ${colsDesktop})`
                    : "1",
                minWidth: displayMode === "carousel" ? "250px" : "auto",
                background: "#fff",
                borderRadius: "8px",
                border: "1px solid #e2e8f0",
                padding: "15px",
              }}
            >
              <div
                style={{
                  width: "100%",
                  aspectRatio: "3/4",
                  background: "#cbd5e1",
                  borderRadius: "4px",
                  marginBottom: "15px",
                }}
              ></div>
              <div
                style={{
                  width: "80%",
                  height: "14px",
                  background: "#e2e8f0",
                  borderRadius: "2px",
                  marginBottom: "10px",
                }}
              ></div>
              <div
                style={{
                  width: "40%",
                  height: "14px",
                  background: "#e2e8f0",
                  borderRadius: "2px",
                }}
              ></div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
