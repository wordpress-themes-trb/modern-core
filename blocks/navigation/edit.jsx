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
    mobileMenuDisplay,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-nav-block modern-core-nav-${clientId}`,
    style: { width: "100%", display: "flex", justifyContent: "center" },
  });

  // Dynamically configure the core navigation block's native mobile setting
  const innerNavConfig =
    mobileMenuDisplay === "expanded"
      ? { overlayMenu: "never" }
      : { overlayMenu: "mobile" };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Mobile Settings", "modern-core")}>
          <SelectControl
            label={__("Mobile Menu Display", "modern-core")}
            value={mobileMenuDisplay}
            options={[
              { label: "Always Expanded (Stacked Links)", value: "expanded" },
              { label: "Standard (Hamburger Icon)", value: "collapsed" },
            ]}
            onChange={(val) => setAttributes({ mobileMenuDisplay: val })}
            help={__(
              "Choose 'Always Expanded' for footer menus or secondary links where a hamburger icon feels unnecessary.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Typography & Spacing", "modern-core")}
          initialOpen={false}
        >
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

          /* Force Mobile Stacking in Editor Preview if Expanded is chosen */
          ${
            mobileMenuDisplay === "expanded"
              ? `
              @media (max-width: 767px) {
                  .modern-core-nav-${clientId} .wp-block-navigation__container { 
                      display: flex !important; flex-direction: column !important; align-items: center !important; gap: 15px !important; 
                  }
              }
          `
              : ""
          }
        `}
      </style>

      <div {...blockProps}>
        <InnerBlocks
          allowedBlocks={["core/navigation"]}
          template={[["core/navigation", innerNavConfig]]}
        />
      </div>
    </>
  );
}
