import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ColorPalette,
  ToggleControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes, clientId }) {
  const {
    linkColor,
    hoverColor,
    hoverEffect,
    fontSize,
    fontWeight,
    itemSpacing,
    disableHamburger,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-nav-block modern-core-nav-${clientId}`,
    style: { width: "100%", display: "flex", justifyContent: "center" },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Mobile Settings", "modern-core")}>
          <ToggleControl
            label={__("Disable Hamburger (Force Stack)", "modern-core")}
            checked={disableHamburger}
            onChange={(val) => setAttributes({ disableHamburger: val })}
            help={__(
              "Turn this on for footers to show links vertically on mobile instead of a menu icon.",
              "modern-core",
            )}
          />
        </PanelBody>
        <PanelBody title={__("Typography & Spacing", "modern-core")}>
          <RangeControl
            label={__("Font Size (px)", "modern-core")}
            value={fontSize}
            onChange={(val) => setAttributes({ fontSize: val })}
            min={12}
            max={24}
          />
          <SelectControl
            label={__("Font Weight", "modern-core")}
            value={fontWeight}
            options={[
              { label: "Normal (400)", value: "400" },
              { label: "Medium (500)", value: "500" },
              { label: "Semibold (600)", value: "600" },
              { label: "Bold (700)", value: "700" },
            ]}
            onChange={(val) => setAttributes({ fontWeight: val })}
          />
          <RangeControl
            label={__("Spacing Between Links (px)", "modern-core")}
            value={itemSpacing}
            onChange={(val) => setAttributes({ itemSpacing: val })}
            min={10}
            max={60}
          />
        </PanelBody>

        <PanelBody
          title={__("Colors & Hover Effects", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Hover Effect", "modern-core")}
            value={hoverEffect}
            options={[
              { label: "Animated Underline", value: "underline" },
              { label: "Color Shift", value: "color" },
              { label: "None", value: "none" },
            ]}
            onChange={(val) => setAttributes({ hoverEffect: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Link Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={linkColor}
            onChange={(val) => setAttributes({ linkColor: val })}
          />
          {hoverEffect !== "none" && (
            <>
              <p style={{ marginTop: "15px", marginBottom: "5px" }}>
                <strong>{__("Hover Color", "modern-core")}</strong>
              </p>
              <ColorPalette
                value={hoverColor}
                onChange={(val) => setAttributes({ hoverColor: val })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <style>
        {`
          .modern-core-nav-${clientId} .wp-block-navigation { gap: ${itemSpacing}px; }
          .modern-core-nav-${clientId} .wp-block-navigation-item__content,
          .modern-core-nav-${clientId} .wp-block-navigation-item a {
              color: ${linkColor} !important; font-size: ${fontSize}px !important; font-weight: ${fontWeight} !important; position: relative; transition: color 0.3s ease; text-decoration: none !important;
          }
          ${hoverEffect === "color" ? ` .modern-core-nav-${clientId} .wp-block-navigation-item__content:hover, .modern-core-nav-${clientId} .wp-block-navigation-item a:hover { color: ${hoverColor} !important; } ` : ""}
          ${
            hoverEffect === "underline"
              ? `
              .modern-core-nav-${clientId} .wp-block-navigation-item { position: relative; }
              .modern-core-nav-${clientId} .wp-block-navigation-item::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0; background-color: ${hoverColor}; transition: width 0.3s ease; }
              .modern-core-nav-${clientId} .wp-block-navigation-item:hover .wp-block-navigation-item__content, .modern-core-nav-${clientId} .wp-block-navigation-item:hover a { color: ${hoverColor} !important; }
              .modern-core-nav-${clientId} .wp-block-navigation-item:hover::after { width: 100%; }
          `
              : ""
          }

          /* THE HAMBURGER KILLER */
          ${
            disableHamburger
              ? `
              @media (max-width: 767px) {
                  .modern-core-nav-${clientId} .wp-block-navigation__responsive-container-open { display: none !important; }
                  .modern-core-nav-${clientId} .wp-block-navigation__responsive-container-close { display: none !important; }
                  .modern-core-nav-${clientId} .wp-block-navigation__responsive-container { display: block !important; position: static !important; width: 100% !important; height: auto !important; background: transparent !important; visibility: visible !important; opacity: 1 !important; transform: none !important; padding: 0 !important; }
                  .modern-core-nav-${clientId} .wp-block-navigation__responsive-container-content { display: flex !important; flex-direction: column !important; align-items: center !important; gap: 15px !important; }
              }
          `
              : ""
          }
        `}
      </style>

      <div {...blockProps}>
        <InnerBlocks
          allowedBlocks={["core/navigation"]}
          template={[["core/navigation", {}]]}
        />
      </div>
    </>
  );
}
