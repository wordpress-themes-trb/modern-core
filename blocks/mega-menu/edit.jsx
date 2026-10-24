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
  TextControl,
  ToggleControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    menuLabel,
    menuLink,
    panelWidth,
    animStyle,
    openDelay,
    closeDelay,
    panelBg,
    panelRadius,
    padding,
    shadowOpacity,
  } = attributes;

  // Local state to keep the panel open while editing in Gutenberg
  const [isEditingPanel, setIsEditingPanel] = React.useState(false);

  const blockProps = useBlockProps({
    className: `modern-core-mega-menu-editor width-${panelWidth}`,
    style: { position: "relative", display: "inline-block" },
  });

  // The precise 4-column default layout you requested
  const DEFAULT_TEMPLATE = [
    [
      "core/columns",
      { style: { spacing: { blockGap: "40px" } } },
      [
        [
          "core/column",
          {},
          [
            [
              "core/heading",
              {
                level: 4,
                content: "CLOTHING",
                style: {
                  typography: {
                    fontSize: "13px",
                    textTransform: "uppercase",
                    letterSpacing: "1px",
                  },
                },
              },
            ],
            [
              "core/paragraph",
              {
                content:
                  '<a href="#">Dresses</a><br/><a href="#">Tops & Blouses</a><br/><a href="#">Knitwear</a><br/><a href="#">Outerwear</a>',
              },
            ],
          ],
        ],
        [
          "core/column",
          {},
          [
            [
              "core/heading",
              {
                level: 4,
                content: "ACCESSORIES",
                style: {
                  typography: {
                    fontSize: "13px",
                    textTransform: "uppercase",
                    letterSpacing: "1px",
                  },
                },
              },
            ],
            [
              "core/paragraph",
              {
                content:
                  '<a href="#">Bags & Purses</a><br/><a href="#">Jewelry</a><br/><a href="#">Sunglasses</a><br/><a href="#">Belts</a>',
              },
            ],
          ],
        ],
        [
          "core/column",
          {},
          [
            [
              "core/heading",
              {
                level: 4,
                content: "FEATURED",
                style: {
                  typography: {
                    fontSize: "13px",
                    textTransform: "uppercase",
                    letterSpacing: "1px",
                  },
                },
              },
            ],
            [
              "core/paragraph",
              {
                content:
                  '<a href="#">New Arrivals</a><br/><a href="#">Best Sellers</a><br/><a href="#">Sustainable Edit</a><br/><a href="#" style="color:#ef4444;">Sale</a>',
              },
            ],
          ],
        ],
        [
          "core/column",
          {},
          [
            [
              "modern-core/promo-banner",
              { layoutMode: "contained", bgMode: "gradient", minHeight: 200 },
            ],
          ],
        ],
      ],
    ],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Navigation Item", "modern-core")}>
          <TextControl
            label={__("Top-Level Label", "modern-core")}
            value={menuLabel}
            onChange={(val) => setAttributes({ menuLabel: val })}
          />
          <TextControl
            label={__("Top-Level Link", "modern-core")}
            value={menuLink}
            onChange={(val) => setAttributes({ menuLink: val })}
          />
          <ToggleControl
            label={__("Unlock Panel for Editing", "modern-core")}
            checked={isEditingPanel}
            onChange={setIsEditingPanel}
            help={__(
              "Toggle this on to keep the mega panel open so you can drag and drop blocks into it.",
              "modern-core",
            )}
          />
        </PanelBody>

        <PanelBody
          title={__("Panel Architecture", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Panel Width Behavior", "modern-core")}
            value={panelWidth}
            options={[
              { label: "Full Viewport Width (Edge to Edge)", value: "full" },
              {
                label: "Container Width (Aligned to Content)",
                value: "container",
              },
              { label: "Navigation Width (Dropdown style)", value: "nav" },
            ]}
            onChange={(val) => setAttributes({ panelWidth: val })}
          />
          <SelectControl
            label={__("Entrance Animation", "modern-core")}
            value={animStyle}
            options={[
              { label: "Smooth Slide Down", value: "slide" },
              { label: "Fade In", value: "fade" },
              { label: "Reveal (Expand Height)", value: "reveal" },
            ]}
            onChange={(val) => setAttributes({ animStyle: val })}
          />
          <RangeControl
            label={__("Open Delay (ms)", "modern-core")}
            value={openDelay}
            onChange={(val) => setAttributes({ openDelay: val })}
            min={0}
            max={500}
            step={50}
          />
          <RangeControl
            label={__("Close Delay (ms)", "modern-core")}
            value={closeDelay}
            onChange={(val) => setAttributes({ closeDelay: val })}
            min={0}
            max={1000}
            step={50}
          />
        </PanelBody>

        <PanelBody
          title={__("Panel Styling", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Internal Padding", "modern-core")}
            value={padding}
            onChange={(val) => setAttributes({ padding: val })}
            help="e.g. 40px"
          />
          <RangeControl
            label={__("Border Radius (Bottom Corners)", "modern-core")}
            value={panelRadius}
            onChange={(val) => setAttributes({ panelRadius: val })}
            min={0}
            max={24}
          />
          <RangeControl
            label={__("Shadow Opacity (%)", "modern-core")}
            value={shadowOpacity}
            onChange={(val) => setAttributes({ shadowOpacity: val })}
            min={0}
            max={50}
          />
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Panel Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={panelBg}
            onChange={(val) => setAttributes({ panelBg: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* The Top-Level Trigger */}
        <div
          style={{
            padding: "10px 15px",
            fontWeight: "bold",
            cursor: "pointer",
            background: isEditingPanel ? "#f1f5f9" : "transparent",
            borderRadius: "4px",
          }}
          onClick={() => setIsEditingPanel(!isEditingPanel)}
        >
          {menuLabel} <span style={{ fontSize: "10px", opacity: 0.5 }}>▼</span>
        </div>

        {/* The InnerBlocks Panel (Only visible in editor when toggled) */}
        {isEditingPanel && (
          <div
            style={{
              position: "absolute",
              top: "100%",
              left: panelWidth === "nav" ? "0" : "-200px", // Approximated for editor visual
              width:
                panelWidth === "full"
                  ? "1200px"
                  : panelWidth === "container"
                    ? "1000px"
                    : "600px",
              background: panelBg,
              padding: padding,
              zIndex: 9999,
              border: "1px solid #e2e8f0",
              boxShadow: `0 15px 35px rgba(0,0,0,${shadowOpacity / 100})`,
              borderRadius: `0 0 ${panelRadius}px ${panelRadius}px`,
            }}
          >
            <div
              style={{
                position: "absolute",
                top: "-30px",
                right: "0",
                background: "#3b82f6",
                color: "#fff",
                fontSize: "11px",
                padding: "4px 8px",
                borderRadius: "4px",
              }}
            >
              Editing Mega Panel
            </div>
            <InnerBlocks template={DEFAULT_TEMPLATE} />
          </div>
        )}
      </div>
    </>
  );
}
