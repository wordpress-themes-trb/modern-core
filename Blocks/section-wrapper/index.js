import * as React from "@wordpress/element";
import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks } from "@wordpress/block-editor";
import metadata from "./block.json";
import Edit from "./edit";

registerBlockType(metadata, {
  apiVersion: 3,
  edit: Edit,
  save: () => {
    // Render purely nested children, letting PHP handle the layout shell
    return React.createElement(InnerBlocks.Content, null);
  },
});
