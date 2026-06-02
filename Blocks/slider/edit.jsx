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
  Button,
} from "@wordpress/components";
import { useSelect, useDispatch } from "@wordpress/data";
import { createBlock } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes, clientId }) {
  const { minHeight, transition, autoplay, delay, showArrows, showDots } =
    attributes;

  // Track the active slide locally
  const [activeSlide, setActiveSlide] = React.useState(0);

  // Hook into Gutenberg's data store
  const { innerBlocks, selectedBlockId } = useSelect(
    (select) => {
      const { getBlock, getSelectedBlockClientId } =
        select("core/block-editor");
      return {
        innerBlocks: getBlock(clientId)?.innerBlocks || [],
        selectedBlockId: getSelectedBlockClientId(),
      };
    },
    [clientId],
  );

  const { insertBlock, removeBlock, selectBlock } =
    useDispatch("core/block-editor");

  // Keep canvas synced if user clicks a slide via List View
  React.useEffect(() => {
    const index = innerBlocks.findIndex((b) => b.clientId === selectedBlockId);
    if (index > -1 && index !== activeSlide) setActiveSlide(index);
  }, [selectedBlockId, innerBlocks]);

  const addSlide = () => {
    const newBlock = createBlock("modern-core/slide");
    insertBlock(newBlock, innerBlocks.length, clientId);
    setActiveSlide(innerBlocks.length);
  };

  const deleteSlide = (index, blockId) => {
    removeBlock(blockId);
    if (activeSlide >= innerBlocks.length - 1) {
      setActiveSlide(Math.max(0, innerBlocks.length - 2));
    }
  };

  const goToSlide = (index, blockId) => {
    setActiveSlide(index);
    selectBlock(blockId); // Switches sidebar context to the child slide
  };

  const blockProps = useBlockProps({
    className: `modern-core-slider-editor slider-${clientId}`,
    style: {
      width: "100%",
      position: "relative",
      backgroundColor: "#f8fafc",
      boxSizing: "border-box",
    },
  });

  const DEFAULT_TEMPLATE = [
    ["modern-core/slide", {}],
    ["modern-core/slide", {}],
    ["modern-core/slide", {}],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Slide Manager", "modern-core")}>
          <p style={{ fontSize: "12px", opacity: 0.6, marginBottom: "15px" }}>
            Select a slide to isolate it on the canvas and edit its content.
          </p>
          {innerBlocks.map((block, index) => (
            <div
              key={block.clientId}
              style={{
                display: "flex",
                justifyContent: "space-between",
                alignItems: "center",
                marginBottom: "8px",
                padding: "8px 12px",
                background: activeSlide === index ? "#e2e8f0" : "#ffffff",
                border: "1px solid",
                borderColor: activeSlide === index ? "#94a3b8" : "#e2e8f0",
                borderRadius: "6px",
              }}
            >
              <Button
                variant="link"
                onClick={() => goToSlide(index, block.clientId)}
                style={{
                  fontWeight: activeSlide === index ? "bold" : "normal",
                  textDecoration: "none",
                  color: activeSlide === index ? "#0f172a" : "#64748b",
                }}
              >
                {__(`Slide ${index + 1}`)}
              </Button>
              <Button
                isDestructive
                variant="link"
                onClick={() => deleteSlide(index, block.clientId)}
                style={{ textDecoration: "none" }}
              >
                {__("Delete")}
              </Button>
            </div>
          ))}
          <Button
            variant="secondary"
            onClick={addSlide}
            style={{
              width: "100%",
              justifyContent: "center",
              marginTop: "15px",
            }}
          >
            {__("+ Add New Slide")}
          </Button>
        </PanelBody>

        <PanelBody
          title={__("Slider Physics", "modern-core")}
          initialOpen={false}
        >
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
        <style>
          {`
            /* THE ISOLATION FIX */
            /* We completely hide all inner blocks inside this specific slider */
            .slider-${clientId} .block-editor-block-list__layout > .wp-block {
                display: none !important;
            }
            
            /* We ONLY show the block that corresponds to the activeSlide index */
            .slider-${clientId} .block-editor-block-list__layout > .wp-block:nth-child(${activeSlide + 1}) {
                display: block !important;
                animation: mcFadeIn 0.3s ease;
            }
            
            @keyframes mcFadeIn { from { opacity: 0; } to { opacity: 1; } }

            /* Editor visual indicators */
            .slider-${clientId}::after {
                content: 'Slide ${activeSlide + 1} of ${innerBlocks.length}';
                position: absolute;
                top: 10px;
                left: 10px;
                background: rgba(0,0,0,0.5);
                color: #fff;
                padding: 4px 10px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: bold;
                pointer-events: none;
                z-index: 99;
            }
          `}
        </style>

        <InnerBlocks
          allowedBlocks={["modern-core/slide"]}
          template={DEFAULT_TEMPLATE}
        />
      </div>
    </>
  );
}
