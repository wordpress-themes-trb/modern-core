import * as React from "@wordpress/element";
import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks } from "@wordpress/block-editor"; // 👈 Import InnerBlocks
import metadata from "./block.json";
import Edit from "./edit";

registerBlockType(metadata, {
  apiVersion: 3,
  edit: Edit,
  // 🚨 Now saves the native WP navigation data correctly
  save: () => {
    return React.createElement(InnerBlocks.Content, null);
  },
});
