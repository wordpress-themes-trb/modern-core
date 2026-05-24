import * as React from "@wordpress/element";
import { useState } from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import { PanelBody, ToggleControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const { isSticky, transparentOnLoad, autoHideOnScroll } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-header-wrapper ${isSticky ? "is-sticky" : ""} ${transparentOnLoad ? "is-transparent" : ""}`,
    style: {
      position: "relative",
      width: "100%",
      zIndex: 99,
      outline: "2px dashed rgba(0,0,0,0.1)",
      minHeight: "80px",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody
          title={__("Header Behavior", "modern-core")}
          initialOpen={true}
        >
          <ToggleControl
            label={__("Sticky Header", "modern-core")}
            checked={isSticky}
            onChange={(val) => setAttributes({ isSticky: val })}
          />
          <ToggleControl
            label={__("Transparent over Hero", "modern-core")}
            checked={transparentOnLoad}
            onChange={(val) => setAttributes({ transparentOnLoad: val })}
          />
          {isSticky && (
            <ToggleControl
              label={__("Auto-Hide on Scroll", "modern-core")}
              checked={autoHideOnScroll}
              onChange={(val) => setAttributes({ autoHideOnScroll: val })}
              help={__(
                "Hides when scrolling down, reappears when scrolling up. (Requires Sticky Header to be active).",
                "modern-core",
              )}
            />
          )}
        </PanelBody>
      </InspectorControls>

      <header {...blockProps}>
        <InnerBlocks
          template={[
            [
              "core/paragraph",
              {
                align: "center",
                content:
                  "Add your header content here — logo, navigation, icons, etc.",
              },
            ],
          ]}
          templateLock={false}
        />
      </header>
    </>
  );
}
