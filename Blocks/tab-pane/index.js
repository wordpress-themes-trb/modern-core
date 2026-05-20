import { registerBlockType } from "@wordpress/blocks";
import metadata from "./block.json";
import Edit from "./edit";
registerBlockType(metadata, { apiVersion: 3, edit: Edit, save: () => null });
