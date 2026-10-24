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
    displayMode,
    maxStock,
    showThreshold,
    cautionThreshold,
    urgentThreshold,
    neutralColor,
    cautionColor,
    urgentColor,
    trackColor,
    textColor,
    enablePulse,
  } = attributes;

  // Local state to simulate stock changes in the editor
  const [mockStock, setMockStock] = React.useState(12);

  const blockProps = useBlockProps({
    className: `modern-core-stock-editor mode-${displayMode}`,
    style: {
      width: "100%",
      padding: "20px",
      backgroundColor: "#fff",
      border: "1px dashed #cbd5e1",
    },
  });

  // Determine Status Level
  let status = "neutral";
  let currentColor = neutralColor;
  if (mockStock <= urgentThreshold) {
    status = "urgent";
    currentColor = urgentColor;
  } else if (mockStock <= cautionThreshold) {
    status = "caution";
    currentColor = cautionColor;
  }

  const isPulsing = enablePulse && status === "urgent";
  const fillPercent = Math.min(100, Math.max(0, (mockStock / maxStock) * 100));

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Editor Simulator", "modern-core")}>
          <RangeControl
            label={__("Simulate Stock Level", "modern-core")}
            value={mockStock}
            onChange={setMockStock}
            min={0}
            max={showThreshold + 10}
            help={__(
              "Drag to test color changes. This does not affect live data.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Scarcity Rules", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Display Style", "modern-core")}
            value={displayMode}
            options={[
              { label: "Progress Bar", value: "bar" },
              { label: "Bold Number", value: "number" },
              { label: "Text Statement", value: "text" },
            ]}
            onChange={(val) => setAttributes({ displayMode: val })}
          />
          <RangeControl
            label={__("Baseline Total Stock", "modern-core")}
            value={maxStock}
            onChange={(val) => setAttributes({ maxStock: val })}
            min={10}
            max={500}
            help={__(
              "Used to calculate the progress bar percentage.",
              "modern-core",
            )}
          />
          <RangeControl
            label={__("Visibility Threshold", "modern-core")}
            value={showThreshold}
            onChange={(val) => setAttributes({ showThreshold: val })}
            min={1}
            max={100}
            help={__(
              "Block hides automatically if stock is above this number.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Status Thresholds", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Caution Threshold (Amber)", "modern-core")}
            value={cautionThreshold}
            onChange={(val) => setAttributes({ cautionThreshold: val })}
            min={1}
            max={showThreshold}
          />
          <RangeControl
            label={__("Urgent Threshold (Red)", "modern-core")}
            value={urgentThreshold}
            onChange={(val) => setAttributes({ urgentThreshold: val })}
            min={1}
            max={cautionThreshold}
          />
          <ToggleControl
            label={__("Pulse Animation on Urgent", "modern-core")}
            checked={enablePulse}
            onChange={(val) => setAttributes({ enablePulse: val })}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Neutral Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={neutralColor}
            onChange={(val) => setAttributes({ neutralColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Caution Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={cautionColor}
            onChange={(val) => setAttributes({ cautionColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Urgent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={urgentColor}
            onChange={(val) => setAttributes({ urgentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          {displayMode === "bar" && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Bar Track Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={trackColor}
                onChange={(val) => setAttributes({ trackColor: val })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    @keyframes mcEditorUrgentPulse {
                        0% { opacity: 1; }
                        50% { opacity: 0.5; }
                        100% { opacity: 1; }
                    }
                    .mc-editor-pulse { animation: mcEditorUrgentPulse 1.5s infinite; }
                    `}
        </style>

        {mockStock > showThreshold ? (
          <div
            style={{
              padding: "20px",
              textAlign: "center",
              background: "#f8fafc",
              color: "#94a3b8",
              borderRadius: "8px",
            }}
          >
            Stock ({mockStock}) is above Visibility Threshold ({showThreshold}).
            <br />
            <strong>Block is currently INVISIBLE on frontend.</strong>
          </div>
        ) : (
          <div>
            {displayMode === "bar" && (
              <div>
                <div
                  style={{
                    display: "flex",
                    alignItems: "center",
                    gap: "8px",
                    marginBottom: "8px",
                    color: textColor,
                    fontWeight: "bold",
                    fontSize: "13px",
                  }}
                >
                  <span
                    style={{
                      display: "inline-block",
                      width: "8px",
                      height: "8px",
                      borderRadius: "50%",
                      backgroundColor: currentColor,
                      className: isPulsing ? "mc-editor-pulse" : "",
                    }}
                  ></span>
                  Only {mockStock} left in stock
                </div>
                <div
                  style={{
                    width: "100%",
                    height: "8px",
                    backgroundColor: trackColor,
                    borderRadius: "4px",
                    overflow: "hidden",
                  }}
                >
                  <div
                    className={isPulsing ? "mc-editor-pulse" : ""}
                    style={{
                      width: `${fillPercent}%`,
                      height: "100%",
                      backgroundColor: currentColor,
                      borderRadius: "4px",
                      transition: "all 0.3s ease",
                    }}
                  ></div>
                </div>
              </div>
            )}

            {displayMode === "number" && (
              <div style={{ color: textColor, fontSize: "15px" }}>
                Only{" "}
                <span
                  className={isPulsing ? "mc-editor-pulse" : ""}
                  style={{
                    color: currentColor,
                    fontWeight: "900",
                    fontSize: "18px",
                  }}
                >
                  {mockStock}
                </span>{" "}
                left!
              </div>
            )}

            {displayMode === "text" && (
              <div
                className={isPulsing ? "mc-editor-pulse" : ""}
                style={{
                  color: currentColor,
                  fontSize: "14px",
                  fontWeight: "bold",
                }}
              >
                {status === "urgent"
                  ? "Almost gone — selling fast"
                  : "Limited stock available"}
              </div>
            )}
          </div>
        )}
      </div>
    </>
  );
}
