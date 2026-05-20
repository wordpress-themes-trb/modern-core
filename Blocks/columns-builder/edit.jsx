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
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes, clientId }) {
  const {
    layout,
    gap,
    vAlign,
    mobileStack,
    reverseMobile,
    showDividers,
    dividerColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-columns-editor align-${vAlign}`,
    style: {
      display: "grid",
      gridTemplateColumns: layout,
      gap: `${gap}px`,
      alignItems: vAlign,
      width: "100%",
      padding: "20px 0",
    },
  });

  // Default template: Two columns
  const template = [
    ["modern-core/column", {}],
    ["modern-core/column", {}],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Grid Architecture", "modern-core")}>
          <SelectControl
            label={__("Column Layout", "modern-core")}
            value={layout}
            options={[
              { label: "50 / 50 (2 Cols)", value: "1fr 1fr" },
              { label: "33 / 66 (2 Cols)", value: "1fr 2fr" },
              { label: "66 / 33 (2 Cols)", value: "2fr 1fr" },
              { label: "25 / 75 (2 Cols)", value: "1fr 3fr" },
              { label: "33 / 33 / 33 (3 Cols)", value: "1fr 1fr 1fr" },
              { label: "25 / 50 / 25 (3 Cols)", value: "1fr 2fr 1fr" },
              { label: "25 / 25 / 25 / 25 (4 Cols)", value: "1fr 1fr 1fr 1fr" },
            ]}
            onChange={(val) => setAttributes({ layout: val })}
            help="Note: Changing this does not automatically add/delete inner columns. Add or remove columns manually inside the editor to match your grid choice."
          />
          <RangeControl
            label={__("Gap Between Columns (px)", "modern-core")}
            value={gap}
            onChange={(val) => setAttributes({ gap: val })}
            min={0}
            max={100}
          />
          <SelectControl
            label={__("Vertical Alignment", "modern-core")}
            value={vAlign}
            options={[
              { label: "Top", value: "start" },
              { label: "Middle", value: "center" },
              { label: "Bottom", value: "end" },
              { label: "Stretch", value: "stretch" },
            ]}
            onChange={(val) => setAttributes({ vAlign: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Responsive & Dividers", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Mobile Behavior", "modern-core")}
            value={mobileStack}
            options={[
              { label: "Stack Vertically (1 Column)", value: "stack" },
              { label: "Keep Grid (Scroll/Shrink)", value: "keep" },
            ]}
            onChange={(val) => setAttributes({ mobileStack: val })}
          />
          {mobileStack === "stack" && (
            <ToggleControl
              label={__("Reverse Stacking Order", "modern-core")}
              checked={reverseMobile}
              onChange={(val) => setAttributes({ reverseMobile: val })}
              help="Puts the right column on top when stacked."
            />
          )}
          <ToggleControl
            label={__("Show Column Dividers", "modern-core")}
            checked={showDividers}
            onChange={(val) => setAttributes({ showDividers: val })}
          />
          {showDividers && (
            <ColorPalette
              value={dividerColor}
              onChange={(val) => setAttributes({ dividerColor: val })}
            />
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <InnerBlocks
          allowedBlocks={["modern-core/column"]}
          template={template}
          renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
        />
      </div>
    </>
  );
}
