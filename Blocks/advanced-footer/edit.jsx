import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import { PanelBody, ToggleControl, ColorPalette } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const { enableReveal, footerBg, textColor } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-footer-wrapper",
    style: {
      backgroundColor: footerBg,
      color: textColor,
      width: "100%",
      outline: "2px dashed rgba(255,255,255,0.2)",
      padding: "40px 0",
    },
  });

  // The massive default starting layout based on your master prompt
  const DEFAULT_TEMPLATE = [
    // 1. Pre-Footer Strip (Newsletter Area Placeholder)
    [
      "core/group",
      {
        align: "full",
        style: { spacing: { padding: { top: "40px", bottom: "40px" } } },
        backgroundColor: "base",
      },
      [
        [
          "core/paragraph",
          {
            align: "center",
            content: "Newsletter Capture Block goes here (Coming Soon)",
          },
        ],
      ],
    ],
    // 2. Main Footer Body (4 Columns)
    [
      "core/columns",
      {
        align: "wide",
        style: { spacing: { padding: { top: "60px", bottom: "60px" } } },
      },
      [
        [
          "core/column",
          { width: "25%" },
          [
            [
              "modern-core/logo",
              {
                brandName: "MODERN CORE",
                logoType: "text",
                tagline: "The ultimate storefront experience.",
              },
            ],
          ],
        ],
        [
          "core/column",
          { width: "25%" },
          [
            [
              "core/heading",
              {
                level: 4,
                content: "Shop",
                style: {
                  typography: {
                    textTransform: "uppercase",
                    letterSpacing: "2px",
                    fontSize: "13px",
                  },
                },
              },
            ],
            [
              "modern-core/navigation",
              { hoverEffect: "color", itemSpacing: 15 },
            ],
          ],
        ],
        [
          "core/column",
          { width: "25%" },
          [
            [
              "core/heading",
              {
                level: 4,
                content: "Company",
                style: {
                  typography: {
                    textTransform: "uppercase",
                    letterSpacing: "2px",
                    fontSize: "13px",
                  },
                },
              },
            ],
            [
              "modern-core/navigation",
              { hoverEffect: "color", itemSpacing: 15 },
            ],
          ],
        ],
        [
          "core/column",
          { width: "25%" },
          [
            [
              "core/heading",
              {
                level: 4,
                content: "Contact",
                style: {
                  typography: {
                    textTransform: "uppercase",
                    letterSpacing: "2px",
                    fontSize: "13px",
                  },
                },
              },
            ],
            [
              "core/paragraph",
              {
                content: "support@moderncore.com<br>1-800-555-0199",
                style: { typography: { fontSize: "14px", lineHeight: "1.8" } },
              },
            ],
          ],
        ],
      ],
    ],
    // 3. Bottom Bar
    [
      "core/group",
      {
        align: "wide",
        style: {
          border: {
            top: {
              color: "rgba(255,255,255,0.1)",
              style: "solid",
              width: "1px",
            },
          },
          spacing: { padding: { top: "20px", bottom: "20px" } },
        },
      },
      [
        [
          "core/columns",
          { verticalAlignment: "center" },
          [
            [
              "core/column",
              { width: "50%" },
              [
                [
                  "core/paragraph",
                  {
                    content: "© 2026 Modern Core. All rights reserved.",
                    style: {
                      typography: { fontSize: "12px", color: "#94a3b8" },
                    },
                  },
                ],
              ],
            ],
            [
              "core/column",
              { width: "50%" },
              [
                [
                  "core/paragraph",
                  {
                    align: "right",
                    content: "Privacy | Terms | Shipping",
                    style: {
                      typography: { fontSize: "12px", color: "#94a3b8" },
                    },
                  },
                ],
              ],
            ],
          ],
        ],
      ],
    ],
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Footer Behavior", "modern-core")}>
          <ToggleControl
            label={__("Cinematic Reveal on Scroll", "modern-core")}
            checked={enableReveal}
            onChange={(val) => setAttributes({ enableReveal: val })}
            help={__(
              "Footer stays fixed at the bottom and is revealed as the user scrolls past the main content.",
              "modern-core",
            )}
          />
        </PanelBody>
        <PanelBody
          title={__("Global Colors", "modern-core")}
          initialOpen={false}
        >
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Background Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={footerBg}
            onChange={(val) => setAttributes({ footerBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <footer {...blockProps}>
        <InnerBlocks template={DEFAULT_TEMPLATE} templateLock={false} />
      </footer>
    </>
  );
}
