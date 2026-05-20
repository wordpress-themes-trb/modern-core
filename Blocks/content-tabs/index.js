import * as React from "@wordpress/element";
import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks } from "@wordpress/block-editor";
import metadata from "./block.json";
import Edit from "./edit";

// Import Child block to ensure it registers simultaneously
import "../tab-pane/index.js";

registerBlockType(metadata, {
  apiVersion: 3,
  edit: Edit,
  save: () => {
    // Using pure JS instead of JSX to prevent Vite parsing errors in .js files
    return React.createElement(InnerBlocks.Content, null);
  },
});
