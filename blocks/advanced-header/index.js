import * as React from "@wordpress/element";
import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks } from "@wordpress/block-editor";
import metadata from "./block.json";
import Edit from "./edit";

registerBlockType(metadata.name, {
  apiVersion: 3,
  title: metadata.title,
  category: "modern-core",
  icon: metadata.icon,
  attributes: metadata.attributes,
  edit: Edit,
  // CRITICAL: Tells WP to save the blocks dropped inside this container
  save: () => {
    return React.createElement(InnerBlocks.Content, null);
  },
});
