import * as React from "@wordpress/element";
import { registerBlockType } from "@wordpress/blocks";
import metadata from "./block.json";
import Edit from "./edit";

// Passing the ENTIRE metadata object blocks Vite from deleting required data
registerBlockType(metadata, {
  apiVersion: 3,
  edit: Edit,
  save: () => null,
});
