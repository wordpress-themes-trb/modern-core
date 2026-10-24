import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  RangeControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function ColumnEdit({ attributes, setAttributes }) {
  const { bgColor, padding, radius, borderColor, hideOnMobile } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-column-editor`,
    style: {
      backgroundColor: bgColor,
      padding: `${padding}px`,
      borderRadius: `${radius}px`,
      border:
        borderColor !== "transparent"
          ? `1px solid ${borderColor}`
          : "1px dashed rgba(0,0,0,0.1)",
      minHeight: "100px",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Column Environment", "modern-core")}>
          <RangeControl
            label={__("Inner Padding (px)", "modern-core")}
            value={padding}
            onChange={(val) => setAttributes({ padding: val })}
            min={0}
            max={100}
          />
          <RangeControl
            label={__("Border Radius (px)", "modern-core")}
            value={radius}
            onChange={(val) => setAttributes({ radius: val })}
            min={0}
            max={40}
          />
          <ToggleControl
            label={__("Hide Completely on Mobile", "modern-core")}
            checked={hideOnMobile}
            onChange={(val) => setAttributes({ hideOnMobile: val })}
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
            <strong>{__("Border Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={borderColor}
            onChange={(val) => setAttributes({ borderColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <InnerBlocks />
      </div>
    </>
  );
}
