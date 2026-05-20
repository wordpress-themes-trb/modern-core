import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    colsDesktop,
    colsMobile,
    alignment,
    showDividers,
    triggerOnce,
    useGradient,
    color1,
    color2,
    numberColor,
    textColor,
    borderColor,
    stats,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-stats-editor align-${alignment}`,
    style: { width: "100%", padding: "60px 0" },
  });

  const updateStat = (index, key, value) => {
    const newStats = [...stats];
    newStats[index][key] = value;
    setAttributes({ stats: newStats });
  };

  const addStat = () => {
    setAttributes({
      stats: [
        ...stats,
        {
          prefix: "",
          number: "100",
          suffix: "",
          label: "New Stat",
          icon: "star",
          duration: 2000,
        },
      ],
    });
  };

  const removeStat = (index) => {
    setAttributes({ stats: stats.filter((_, i) => i !== index) });
  };

  const renderIcon = (type) => {
    const s = {
      width: 32,
      height: 32,
      fill: "none",
      stroke: "currentColor",
      strokeWidth: 1.5,
      strokeLinecap: "round",
      strokeLinejoin: "round",
      marginBottom: "15px",
      opacity: 0.5,
    };
    if (type === "users")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
      );
    if (type === "award")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <circle cx="12" cy="8" r="7"></circle>
          <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
        </svg>
      );
    if (type === "smile")
      return (
        <svg {...s} viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
          <line x1="9" y1="9" x2="9.01" y2="9"></line>
          <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
      );
    if (type === "none") return null;
    return (
      <svg {...s} viewBox="0 0 24 24">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
      </svg>
    ); // Star
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture & Layout", "modern-core")}>
          <RangeControl
            label={__("Desktop Columns", "modern-core")}
            value={colsDesktop}
            onChange={(val) => setAttributes({ colsDesktop: val })}
            min={2}
            max={6}
          />
          <RangeControl
            label={__("Mobile Columns", "modern-core")}
            value={colsMobile}
            onChange={(val) => setAttributes({ colsMobile: val })}
            min={1}
            max={3}
          />
          <SelectControl
            label={__("Alignment", "modern-core")}
            value={alignment}
            options={[
              { label: "Left", value: "flex-start" },
              { label: "Center", value: "center" },
              { label: "Right", value: "flex-end" },
            ]}
            onChange={(val) => setAttributes({ alignment: val })}
          />
          <ToggleControl
            label={__("Show Divider Lines", "modern-core")}
            checked={showDividers}
            onChange={(val) => setAttributes({ showDividers: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Animation Rules", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Animate Once & Lock", "modern-core")}
            checked={triggerOnce}
            onChange={(val) => setAttributes({ triggerOnce: val })}
            help={__(
              "If off, numbers re-animate every time the user scrolls up and down past the block.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Data Management", "modern-core")}
          initialOpen={false}
        >
          {stats.map((s, index) => (
            <div
              key={index}
              style={{
                marginBottom: "20px",
                padding: "15px",
                border: "1px solid #e2e8f0",
                borderRadius: "8px",
              }}
            >
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  marginBottom: "10px",
                }}
              >
                <strong>Stat {index + 1}</strong>
                <span
                  style={{
                    color: "#ef4444",
                    cursor: "pointer",
                    fontSize: "12px",
                  }}
                  onClick={() => removeReview(index)}
                >
                  Remove
                </span>
              </div>
              <SelectControl
                label="Icon"
                value={s.icon}
                options={[
                  { label: "None", value: "none" },
                  { label: "Users", value: "users" },
                  { label: "Star", value: "star" },
                  { label: "Award", value: "award" },
                  { label: "Smile", value: "smile" },
                ]}
                onChange={(val) => updateStat(index, "icon", val)}
              />
              <TextControl
                label="Prefix (e.g. $)"
                value={s.prefix}
                onChange={(val) => updateStat(index, "prefix", val)}
              />
              <TextControl
                label="Target Number (e.g. 50 or 4.9)"
                value={s.number}
                onChange={(val) => updateStat(index, "number", val)}
              />
              <TextControl
                label="Suffix (e.g. % or K+)"
                value={s.suffix}
                onChange={(val) => updateStat(index, "suffix", val)}
              />
              <TextControl
                label="Label below number"
                value={s.label}
                onChange={(val) => updateStat(index, "label", val)}
              />
              <RangeControl
                label="Animation Duration (ms)"
                value={s.duration}
                onChange={(val) => updateStat(index, "duration", val)}
                min={500}
                max={5000}
                step={100}
              />
            </div>
          ))}
          <Button
            variant="primary"
            onClick={addStat}
            style={{ width: "100%", justifyContent: "center" }}
          >
            + Add Stat
          </Button>
        </PanelBody>

        <PanelBody
          title={__("Typography & Colors", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Apply Gradient to Numbers", "modern-core")}
            checked={useGradient}
            onChange={(val) => setAttributes({ useGradient: val })}
          />
          {useGradient ? (
            <>
              <ColorPalette
                value={color1}
                onChange={(val) => setAttributes({ color1: val })}
              />
              <ColorPalette
                value={color2}
                onChange={(val) => setAttributes({ color2: val })}
              />
            </>
          ) : (
            <>
              <p style={{ marginTop: "5px", marginBottom: "5px" }}>
                <strong>{__("Number Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={numberColor}
                onChange={(val) => setAttributes({ numberColor: val })}
              />
            </>
          )}
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text / Label Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          {showDividers && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Divider Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={borderColor}
                onChange={(val) => setAttributes({ borderColor: val })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-stats-editor {
                        --mc-cols: ${colsDesktop};
                        --mc-txt: ${textColor};
                        --mc-border: ${showDividers ? borderColor : "transparent"};
                    }
                    .mc-stats-grid-mock {
                        display: grid;
                        grid-template-columns: repeat(var(--mc-cols), 1fr);
                        color: var(--mc-txt);
                    }
                    .mc-stat-item-mock {
                        display: flex;
                        flex-direction: column;
                        align-items: ${alignment === "flex-start" ? "flex-start" : alignment === "flex-end" ? "flex-end" : "center"};
                        text-align: ${alignment === "flex-start" ? "left" : alignment === "flex-end" ? "right" : "center"};
                        padding: 20px;
                        border-right: 1px solid var(--mc-border);
                    }
                    .mc-stat-item-mock:nth-child(${colsDesktop}n) { border-right: none; }
                    
                    .mc-stat-num-wrapper {
                        font-size: 56px; font-weight: 900; line-height: 1; margin-bottom: 10px; display: flex; align-items: baseline;
                    }
                    .mc-stat-prefix, .mc-stat-suffix { font-size: 0.5em; opacity: 0.7; font-weight: bold; }
                    .mc-stat-num {
                        ${
                          useGradient
                            ? `background: linear-gradient(135deg, ${color1}, ${color2}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;`
                            : `color: ${numberColor};`
                        }
                    }
                    .mc-stat-label { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; }
                    `}
        </style>

        <div className="mc-stats-grid-mock">
          {stats.map((s, i) => (
            <div key={i} className="mc-stat-item-mock">
              <div style={{ color: useGradient ? color1 : numberColor }}>
                {renderIcon(s.icon)}
              </div>
              <div className="mc-stat-num-wrapper">
                {s.prefix && <span className="mc-stat-prefix">{s.prefix}</span>}
                <span className="mc-stat-num">{s.number}</span>
                {s.suffix && <span className="mc-stat-suffix">{s.suffix}</span>}
              </div>
              <div className="mc-stat-label">{s.label}</div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
