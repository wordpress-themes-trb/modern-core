import * as React from "@wordpress/element";
import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks } from "@wordpress/block-editor";

// Import Metadata
import parentMeta from "./block.json";
import childMeta from "../column/block.json";

// Import Edits
import Edit from "./edit";
import ColumnEdit from "../column/edit";

// 1. Register Parent (Columns Builder)
registerBlockType(parentMeta, {
  apiVersion: 3,
  edit: Edit,
  save: () => React.createElement(InnerBlocks.Content, null),
});

// 2. Register Child (Single Column)
registerBlockType(childMeta, {
  apiVersion: 3,
  edit: ColumnEdit,
  save: () => React.createElement(InnerBlocks.Content, null),
});
