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
    layoutMode,
    barHeight,
    barRadius,
    interactiveRows,
    countFormat,
    starColor,
    trackColor,
    textColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-rev-sum-editor layout-${layoutMode}`,
    style: { width: "100%", padding: "40px 0", color: textColor },
  });

  const mockStats = {
    average: "4.8",
    total: 2847,
    breakdown: [
      { stars: 5, count: 2420, percent: 85 },
      { stars: 4, count: 284, percent: 10 },
      { stars: 3, count: 85, percent: 3 },
      { stars: 2, count: 28, percent: 1 },
      { stars: 1, count: 30, percent: 1 },
    ],
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture", "modern-core")}>
          <SelectControl
            label={__("Layout Orientation", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Horizontal (Side by Side)", value: "horizontal" },
              { label: "Vertical (Stacked)", value: "vertical" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <SelectControl
            label={__("Row Count Display", "modern-core")}
            value={countFormat}
            options={[
              { label: "Show Percentage (%)", value: "percentage" },
              { label: "Show Absolute Number", value: "number" },
            ]}
            onChange={(val) => setAttributes({ countFormat: val })}
          />
          <ToggleControl
            label={__("Enable Clickable Filter Rows", "modern-core")}
            checked={interactiveRows}
            onChange={(val) => setAttributes({ interactiveRows: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Visual Design", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Bar Height (px)", "modern-core")}
            value={barHeight}
            onChange={(val) => setAttributes({ barHeight: val })}
            min={4}
            max={20}
          />
          <RangeControl
            label={__("Bar Border Radius", "modern-core")}
            value={barRadius}
            onChange={(val) => setAttributes({ barRadius: val })}
            min={0}
            max={10}
          />

          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Star & Fill Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={starColor}
            onChange={(val) => setAttributes({ starColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Bar Track Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={trackColor}
            onChange={(val) => setAttributes({ trackColor: val })}
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
            display: "flex",
            flexDirection: layoutMode === "horizontal" ? "row" : "column",
            gap: layoutMode === "horizontal" ? "60px" : "30px",
            alignItems: layoutMode === "horizontal" ? "center" : "flex-start",
          }}
        >
          {/* Left: Aggregate */}
          <div
            style={{
              display: "flex",
              flexDirection: "column",
              alignItems: "center",
              minWidth: "200px",
            }}
          >
            <div
              style={{
                fontSize: "64px",
                fontWeight: "800",
                lineHeight: 1,
                marginBottom: "10px",
              }}
            >
              {mockStats.average}
            </div>
            <div
              style={{
                color: starColor,
                fontSize: "24px",
                letterSpacing: "2px",
                marginBottom: "10px",
              }}
            >
              ★★★★★
            </div>
            <div style={{ fontSize: "14px", opacity: 0.7 }}>
              Based on {mockStats.total.toLocaleString()} reviews
            </div>
          </div>

          {/* Right: Breakdown Matrix */}
          <div
            style={{
              flex: 1,
              width: "100%",
              display: "flex",
              flexDirection: "column",
              gap: "12px",
            }}
          >
            {mockStats.breakdown.map((row) => (
              <div
                key={row.stars}
                style={{
                  display: "flex",
                  alignItems: "center",
                  gap: "15px",
                  cursor: interactiveRows ? "pointer" : "default",
                }}
              >
                <div
                  style={{
                    display: "flex",
                    alignItems: "center",
                    gap: "5px",
                    width: "60px",
                    fontSize: "13px",
                    fontWeight: "bold",
                  }}
                >
                  {row.stars} <span style={{ color: starColor }}>★</span>
                </div>
                <div
                  style={{
                    flex: 1,
                    height: `${barHeight}px`,
                    backgroundColor: trackColor,
                    borderRadius: `${barRadius}px`,
                    overflow: "hidden",
                  }}
                >
                  <div
                    style={{
                      width: `${row.percent}%`,
                      height: "100%",
                      backgroundColor: starColor,
                      borderRadius: `${barRadius}px`,
                    }}
                  ></div>
                </div>
                <div
                  style={{
                    width: "40px",
                    textAlign: "right",
                    fontSize: "13px",
                    opacity: 0.7,
                  }}
                >
                  {countFormat === "percentage" ? `${row.percent}%` : row.count}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </>
  );
}
