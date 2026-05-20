import * as React from "@wordpress/element";
import {
  useBlockProps,
  InnerBlocks,
  InspectorControls,
} from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const { tabTitle, tabIcon } = attributes;
  const blockProps = useBlockProps({
    className: "mc-tab-pane-editor",
    style: {
      border: "1px solid #e2e8f0",
      padding: "20px",
      marginBottom: "20px",
      borderRadius: "8px",
      background: "#fff",
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Tab Identity", "modern-core")}>
          <TextControl
            label={__("Tab Label", "modern-core")}
            value={tabTitle}
            onChange={(val) => setAttributes({ tabTitle: val })}
          />
          <SelectControl
            label={__("Tab Icon", "modern-core")}
            value={tabIcon}
            options={[
              { label: "None", value: "" },
              { label: "Star", value: "star" },
              { label: "Info", value: "info" },
              { label: "List", value: "list" },
            ]}
            onChange={(val) => setAttributes({ tabIcon: val })}
          />
        </PanelBody>
      </InspectorControls>
      <div {...blockProps}>
        <div
          style={{
            backgroundColor: "#f1f5f9",
            padding: "10px 15px",
            marginBottom: "20px",
            borderRadius: "4px",
            fontWeight: "bold",
            color: "#0f172a",
            borderLeft: "4px solid #3b82f6",
          }}
        >
          Editing Tab: {tabTitle}
        </div>
        <InnerBlocks />
      </div>
    </>
  );
}
