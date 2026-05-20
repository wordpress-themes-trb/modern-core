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
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    tabStyle,
    layoutMode,
    animStyle,
    animDuration,
    accentColor,
    inactiveColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-content-tabs-editor style-${tabStyle} layout-${layoutMode}`,
    style: {
      width: "100%",
      padding: "40px 0",
      backgroundColor: "#f8fafc",
      border: "2px dashed #cbd5e1",
    },
  });

  const TEMPLATE = [
    ["modern-core/tab-pane", { tabTitle: "Description" }],
    ["modern-core/tab-pane", { tabTitle: "Specifications" }],
    ["modern-core/tab-pane", { tabTitle: "Reviews" }],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture & Animation", "modern-core")}>
          <SelectControl
            label={__("Layout Direction", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Horizontal (Tabs Top)", value: "horizontal" },
              { label: "Vertical (Tabs Left)", value: "vertical" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <SelectControl
            label={__("Tab Nav Style", "modern-core")}
            value={tabStyle}
            options={[
              { label: "Editorial Underline", value: "underline" },
              { label: "Pill Buttons", value: "pill" },
              { label: "Boxed", value: "box" },
            ]}
            onChange={(val) => setAttributes({ tabStyle: val })}
          />
          <SelectControl
            label={__("Switch Animation", "modern-core")}
            value={animStyle}
            options={[
              { label: "Smooth Fade", value: "fade" },
              { label: "Slide Horizontal", value: "slide-h" },
              { label: "Slide Vertical", value: "slide-v" },
              { label: "Instant", value: "none" },
            ]}
            onChange={(val) => setAttributes({ animStyle: val })}
          />
          <RangeControl
            label={__("Animation Duration (ms)", "modern-core")}
            value={animDuration}
            onChange={(val) => setAttributes({ animDuration: val })}
            min={0}
            max={1000}
            step={50}
          />
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Active Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Inactive / Outline Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={inactiveColor}
            onChange={(val) => setAttributes({ inactiveColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          style={{
            padding: "0 20px",
            marginBottom: "20px",
            color: "#64748b",
            fontSize: "13px",
            textTransform: "uppercase",
            letterSpacing: "1px",
            fontWeight: "bold",
          }}
        >
          Content Tabs Container (Add/Edit Tab Panes Below)
        </div>
        <div style={{ padding: "0 20px" }}>
          <InnerBlocks
            allowedBlocks={["modern-core/tab-pane"]}
            template={TEMPLATE}
          />
        </div>
      </div>
    </>
  );
}
