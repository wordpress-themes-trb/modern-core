import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, SelectControl, RangeControl } from "@wordpress/components";
import { Fragment } from "@wordpress/element";

// 1. Globally register the animation attributes
addFilter(
  "blocks.registerBlockType",
  "modern-core/add-animation-attrs",
  (settings, name) => {
    if (name.startsWith("modern-core/")) {
      settings.attributes = {
        ...settings.attributes,
        mcAnimation: { type: "string", default: "none" },
        mcAnimDuration: { type: "number", default: 800 },
        mcAnimDelay: { type: "number", default: 0 },
        mcAnimOffset: { type: "number", default: 50 },
      };
    }
    return settings;
  },
);

// 2. Globally inject the UI panel
const withAnimationControl = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    if (!props.name.startsWith("modern-core/")) {
      return <BlockEdit {...props} />;
    }

    const { attributes, setAttributes } = props;

    return (
      <Fragment>
        <BlockEdit {...props} />
        <InspectorControls>
          <PanelBody title="Entrance Animation" initialOpen={false}>
            <SelectControl
              label="Animate On Scroll"
              value={attributes.mcAnimation}
              options={[
                { label: "None", value: "none" },
                { label: "Fade Up", value: "fade-up" },
                { label: "Fade Down", value: "fade-down" },
                { label: "Fade Left", value: "fade-left" },
                { label: "Fade Right", value: "fade-right" },
                { label: "Zoom In", value: "zoom-in" },
              ]}
              onChange={(val) => setAttributes({ mcAnimation: val })}
            />

            {/* Only show these sliders if an animation is actually selected! */}
            {attributes.mcAnimation !== "none" && (
              <Fragment>
                <RangeControl
                  label="Duration (Speed in ms)"
                  value={attributes.mcAnimDuration}
                  onChange={(val) => setAttributes({ mcAnimDuration: val })}
                  min={200}
                  max={3000}
                  step={100}
                />
                <RangeControl
                  label="Delay (Wait time in ms)"
                  help="Useful for staggering columns."
                  value={attributes.mcAnimDelay}
                  onChange={(val) => setAttributes({ mcAnimDelay: val })}
                  min={0}
                  max={3000}
                  step={50}
                />
                <RangeControl
                  label="Trigger Offset"
                  help="Pixels past the bottom of screen before triggering."
                  value={attributes.mcAnimOffset}
                  onChange={(val) => setAttributes({ mcAnimOffset: val })}
                  min={0}
                  max={300}
                  step={10}
                />
              </Fragment>
            )}
          </PanelBody>
        </InspectorControls>
      </Fragment>
    );
  };
}, "withAnimationControl");

addFilter(
  "editor.BlockEdit",
  "modern-core/add-animation-ui",
  withAnimationControl,
);
