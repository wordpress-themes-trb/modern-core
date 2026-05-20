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
    layoutType,
    splitRatio,
    dividerStyle,
    dividerColor,
    mobileStack,
    offsetX,
    offsetY,
    contentSide,
  } = attributes;

  // Calculate Flexbox widths based on the chosen ratio
  const [leftWidth, rightWidth] = splitRatio.split("-").map((num) => `${num}%`);
  const isContentLeft = contentSide === "left";

  const blockProps = useBlockProps({
    className: `modern-core-hero-layout-editor type-${layoutType}`,
    style: {
      display: "flex",
      flexDirection: "row",
      width: "100%",
      minHeight: "500px",
      position: "relative",
      overflow: "hidden",
      border: "2px dashed rgba(255,255,255,0.2)",
    },
  });

  const TEMPLATE = [
    [
      "core/group",
      {
        className: "mc-layout-zone mc-media-zone",
        style: { backgroundColor: "rgba(0,0,0,0.2)", padding: "20px" },
      },
      [
        [
          "core/paragraph",
          { content: "📸 Drop Image/Video Here", align: "center" },
        ],
      ],
    ],
    [
      "core/group",
      {
        className: "mc-layout-zone mc-text-zone",
        style: { padding: "40px" },
      },
      [
        ["core/heading", { content: "Editorial Headline" }],
        [
          "core/paragraph",
          { content: "Secondary description text goes here." },
        ],
        ["core/buttons", {}, [["core/button", { text: "Shop Now" }]]],
      ],
    ],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture", "modern-core")}>
          <SelectControl
            label={__("Layout Type", "modern-core")}
            value={layoutType}
            options={[
              { label: "Standard Split", value: "split" },
              { label: "Editorial Asymmetric", value: "asymmetric" },
            ]}
            onChange={(val) => setAttributes({ layoutType: val })}
          />
          <SelectControl
            label={__("Content Position", "modern-core")}
            value={contentSide}
            options={[
              { label: "Content on Left", value: "left" },
              { label: "Content on Right", value: "right" },
            ]}
            onChange={(val) => setAttributes({ contentSide: val })}
          />
          {layoutType === "split" && (
            <>
              <SelectControl
                label={__("Split Ratio", "modern-core")}
                value={splitRatio}
                options={[
                  { label: "30 / 70", value: "30-70" },
                  { label: "40 / 60", value: "40-60" },
                  { label: "50 / 50", value: "50-50" },
                  { label: "60 / 40", value: "60-40" },
                  { label: "70 / 30", value: "70-30" },
                ]}
                onChange={(val) => setAttributes({ splitRatio: val })}
              />
              <SelectControl
                label={__("Mobile Stacking Order", "modern-core")}
                value={mobileStack}
                options={[
                  { label: "Content on Top", value: "content-first" },
                  { label: "Media on Top", value: "media-first" },
                ]}
                onChange={(val) => setAttributes({ mobileStack: val })}
              />
              <SelectControl
                label={__("Divider Style", "modern-core")}
                value={dividerStyle}
                options={[
                  { label: "Straight Cut", value: "straight" },
                  { label: "Diagonal Slash", value: "diagonal" },
                  { label: "Soft Wave", value: "wave" },
                ]}
                onChange={(val) => setAttributes({ dividerStyle: val })}
              />
              {dividerStyle !== "straight" && (
                <ColorPalette
                  value={dividerColor}
                  onChange={(val) => setAttributes({ dividerColor: val })}
                />
              )}
            </>
          )}
          {layoutType === "asymmetric" && (
            <>
              <RangeControl
                label={__("X-Axis Offset (%)", "modern-core")}
                value={offsetX}
                onChange={(val) => setAttributes({ offsetX: val })}
                min={-50}
                max={50}
              />
              <RangeControl
                label={__("Y-Axis Offset (%)", "modern-core")}
                value={offsetY}
                onChange={(val) => setAttributes({ offsetY: val })}
                min={-50}
                max={50}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .modern-core-hero-layout-editor .mc-layout-zone {
                        flex-shrink: 0;
                        transition: all 0.3s ease;
                    }
                    .modern-core-hero-layout-editor.type-split .mc-media-zone {
                        width: ${isContentLeft ? rightWidth : leftWidth};
                        order: ${isContentLeft ? 2 : 1};
                    }
                    .modern-core-hero-layout-editor.type-split .mc-text-zone {
                        width: ${isContentLeft ? leftWidth : rightWidth};
                        order: ${isContentLeft ? 1 : 2};
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                    }
                    .modern-core-hero-layout-editor.type-asymmetric .mc-media-zone {
                        width: 100%;
                        position: absolute;
                        inset: 0;
                    }
                    .modern-core-hero-layout-editor.type-asymmetric .mc-text-zone {
                        position: relative;
                        z-index: 10;
                        width: 60%;
                        transform: translate(${offsetX}%, ${offsetY}%);
                        background: rgba(0,0,0,0.4);
                        backdrop-filter: blur(10px);
                    }
                    `}
        </style>
        <InnerBlocks
          template={isContentLeft ? [TEMPLATE[1], TEMPLATE[0]] : TEMPLATE}
          allowedBlocks={[
            "core/group",
            "core/image",
            "core/video",
            "core/heading",
            "core/paragraph",
            "core/buttons",
            "modern-core/countdown",
            "modern-core/trust-strip",
          ]}
        />
      </div>
    </>
  );
}
