import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  ToggleControl,
  ColorPalette,
  Placeholder,
  Button,
} from "@wordpress/components";
import { useDispatch, useSelect } from "@wordpress/data";
import { createBlocksFromInnerBlocksTemplate } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes, clientId }) {
  const { enableReveal, footerBg, textColor } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-footer-wrapper",
    style: {
      backgroundColor: footerBg,
      color: textColor,
      "--mc-footer-text": textColor,
      padding: "40px 0",
      boxSizing: "border-box",
      width: "100%",
    },
  });

  // Check if the block already has content inside it
  const hasInnerBlocks = useSelect(
    (select) =>
      select("core/block-editor").getBlock(clientId)?.innerBlocks?.length > 0,
    [clientId],
  );
  const { replaceInnerBlocks } = useDispatch("core/block-editor");

  // ==========================================
  // PRESET 1: 1 Row Minimal
  // ==========================================
  const TEMPLATE_MINIMAL = [
    [
      "core/group",
      {
        align: "wide",
        style: { spacing: { padding: { top: "20px", bottom: "20px" } } },
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
                  "modern-core/logo",
                  { brandName: "MODERN CORE", logoType: "text" },
                ],
                [
                  "core/paragraph",
                  {
                    content: "© 2026 Modern Core. All rights reserved.",
                    style: { typography: { fontSize: "12px", opacity: "0.7" } },
                  },
                ],
              ],
            ],
            [
              "core/column",
              { width: "50%" },
              [
                [
                  "modern-core/navigation",
                  {
                    hoverEffect: "color",
                    itemSpacing: 20,
                    disableHamburger: true,
                  },
                ],
              ],
            ],
          ],
        ],
      ],
    ],
  ];

  // ==========================================
  // PRESET 2: 2 Rows (Standard)
  // ==========================================
  const TEMPLATE_2_ROW = [
    [
      "core/columns",
      {
        align: "wide",
        style: { spacing: { padding: { top: "40px", bottom: "60px" } } },
      },
      [
        [
          "core/column",
          { width: "25%" },
          [
            ["modern-core/logo", { brandName: "MODERN CORE" }],
            ["core/paragraph", { content: "Elevated minimal essentials." }],
          ],
        ],
        [
          "core/column",
          { width: "25%" },
          [
            ["core/heading", { level: 4, content: "Shop" }],
            ["modern-core/navigation", { disableHamburger: true }],
          ],
        ],
        [
          "core/column",
          { width: "25%" },
          [
            ["core/heading", { level: 4, content: "Company" }],
            ["modern-core/navigation", { disableHamburger: true }],
          ],
        ],
        [
          "core/column",
          { width: "25%" },
          [
            ["core/heading", { level: 4, content: "Contact" }],
            ["core/paragraph", { content: "support@moderncore.com" }],
          ],
        ],
      ],
    ],
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
                    style: { typography: { fontSize: "12px", opacity: "0.7" } },
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
                    style: { typography: { fontSize: "12px", opacity: "0.7" } },
                  },
                ],
              ],
            ],
          ],
        ],
      ],
    ],
  ];

  // ==========================================
  // PRESET 3: 3 Rows (With Newsletter)
  // ==========================================
  const TEMPLATE_3_ROW = [
    [
      "core/group",
      {
        align: "full",
        style: { spacing: { padding: { top: "40px", bottom: "40px" } } },
      },
      [["modern-core/newsletter", {}]],
    ],
    ...TEMPLATE_2_ROW, // Spreads the 2-row layout directly underneath the newsletter
  ];

  // Function to inject the chosen preset
  const applyPreset = (template) => {
    replaceInnerBlocks(clientId, createBlocksFromInnerBlocksTemplate(template));
  };

  // If the block is empty, show the Preset Selection UI
  if (!hasInnerBlocks) {
    return (
      <div {...blockProps}>
        <Placeholder
          icon="layout"
          label={__("Advanced Footer Setup", "modern-core")}
          instructions={__(
            "Choose a starting layout. You can fully edit, add, or remove blocks after selecting.",
            "modern-core",
          )}
        >
          <div
            style={{
              display: "flex",
              gap: "15px",
              justifyContent: "center",
              marginTop: "20px",
            }}
          >
            <Button
              variant="secondary"
              onClick={() => applyPreset(TEMPLATE_MINIMAL)}
            >
              {__("1 Row (Minimal)", "modern-core")}
            </Button>
            <Button
              variant="primary"
              onClick={() => applyPreset(TEMPLATE_2_ROW)}
            >
              {__("2 Rows (Standard)", "modern-core")}
            </Button>
            <Button
              variant="secondary"
              onClick={() => applyPreset(TEMPLATE_3_ROW)}
            >
              {__("3 Rows (+ Newsletter)", "modern-core")}
            </Button>
          </div>
        </Placeholder>
      </div>
    );
  }

  // If a preset was chosen, show the standard editable block canvas
  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Footer Behavior", "modern-core")}>
          <ToggleControl
            label={__("Cinematic Reveal on Scroll", "modern-core")}
            checked={enableReveal}
            onChange={(val) => setAttributes({ enableReveal: val })}
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

      <style>
        {`
          .modern-core-footer-wrapper p, 
          .modern-core-footer-wrapper h1, 
          .modern-core-footer-wrapper h2, 
          .modern-core-footer-wrapper h3, 
          .modern-core-footer-wrapper h4, 
          .modern-core-footer-wrapper h5, 
          .modern-core-footer-wrapper h6 {
            color: var(--mc-footer-text, inherit) !important;
          }
        `}
      </style>

      <footer {...blockProps}>
        <InnerBlocks
          renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
        />
      </footer>
    </>
  );
}
