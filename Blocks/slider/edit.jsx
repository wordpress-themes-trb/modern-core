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
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const { minHeight, transition, autoplay, delay, showArrows, showDots } =
    attributes;

  const blockProps = useBlockProps({
    className: "modern-core-slider-editor",
    style: {
      width: "100%",
      overflowX: "auto", // Allows scrolling horizontally to edit slides
      display: "flex",
      gap: "20px",
      padding: "20px",
      backgroundColor: "#f1f5f9",
      border: "2px dashed #cbd5e1",
    },
  });

  // Default template: Automatically spawns 3 identical cinematic slides
  const DEFAULT_TEMPLATE = [
    ["modern-core/slide", {}],
    ["modern-core/slide", {}],
    ["modern-core/slide", {}],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Slider Physics", "modern-core")}>
          <SelectControl
            label={__("Transition Style", "modern-core")}
            value={transition}
            options={[
              { label: "Cinematic Fade", value: "fade" },
              { label: "Horizontal Slide", value: "slide" },
            ]}
            onChange={(val) => setAttributes({ transition: val })}
          />
          <RangeControl
            label={__("Height (vh)", "modern-core")}
            value={minHeight}
            onChange={(val) => setAttributes({ minHeight: val })}
            min={40}
            max={100}
          />
        </PanelBody>

        <PanelBody
          title={__("Autoplay & Navigation", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Enable Autoplay", "modern-core")}
            checked={autoplay}
            onChange={(val) => setAttributes({ autoplay: val })}
          />
          {autoplay && (
            <RangeControl
              label={__("Slide Duration (ms)", "modern-core")}
              value={delay}
              onChange={(val) => setAttributes({ delay: val })}
              min={2000}
              max={10000}
              step={500}
            />
          )}
          <ToggleControl
            label={__("Show Navigation Arrows", "modern-core")}
            checked={showArrows}
            onChange={(val) => setAttributes({ showArrows: val })}
          />
          <ToggleControl
            label={__("Show Dot Indicators", "modern-core")}
            checked={showDots}
            onChange={(val) => setAttributes({ showDots: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <InnerBlocks
          allowedBlocks={["modern-core/slide"]}
          template={DEFAULT_TEMPLATE}
          orientation="horizontal"
        />
      </div>
    </>
  );
}
