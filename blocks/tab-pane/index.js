import * as React from "@wordpress/element";
import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks } from "@wordpress/block-editor";
import metadata from "./block.json";
import Edit from "./edit";

registerBlockType(metadata, {
  apiVersion: 3,
  edit: Edit,
  // Tab panes hold inner blocks, so their content must be saved.
  save: () => React.createElement(InnerBlocks.Content, null),
});
