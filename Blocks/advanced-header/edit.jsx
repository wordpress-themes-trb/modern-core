import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  ToggleControl,
  Button,
  BaseControl,
} from "@wordpress/components";
import { useDispatch } from "@wordpress/data";
import { createBlock } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes, clientId }) {
 const { isSticky, transparentOnLoad, autoHideOnScroll } = attributes;

  // This allows us to programmatically delete and insert blocks!
  const { replaceInnerBlocks } = useDispatch("core/block-editor");

  const blockProps = useBlockProps({
    className: `modern-core-header-wrapper ${isSticky ? "is-sticky" : ""} ${transparentOnLoad ? "is-transparent" : ""}`,
    style: {
      position: "relative",
      width: "100%",
      zIndex: 99,
      outline: "2px dashed rgba(0,0,0,0.1)",
      minHeight: "80px",
    },
  });

  // ==========================================
  // THE 1-CLICK PRESET ENGINE
  // ==========================================
  const applyPreset = (presetType) => {
    let newBlocks = [];

    if (presetType === "megastore") {
      // Preset 1: The Amazon / Walmart Layout (3 Rows, Big Search)
      newBlocks = [
        createBlock("modern-core/marquee", {
          text: "⚡ MEGA CLEARANCE EVENT: UP TO 70% OFF ⚡",
          bgColor: "#131921",
          speed: 20,
        }),
        createBlock(
          "core/columns",
          {
            verticalAlignment: "center",
            style: {
              spacing: {
                padding: {
                  top: "15px",
                  bottom: "15px",
                  left: "30px",
                  right: "30px",
                },
              },
            },
          },
          [
            createBlock("core/column", { width: "20%" }, [
              createBlock("modern-core/logo", {
                brandName: "MEGASTORE",
                alignment: "left",
              }),
            ]),
            createBlock("core/column", { width: "60%" }, [
              createBlock("modern-core/search", {
                width: 100,
                shape: "rounded",
                bgColor: "#ffffff",
              }),
            ]),
            createBlock("core/column", { width: "20%" }, [
              createBlock("modern-core/header-icons", {
                showSearch: false,
                iconColor: "#0f172a",
              }),
            ]),
          ],
        ),
        createBlock(
          "core/group",
          {
            style: {
              color: { background: "#232f3e" },
              spacing: { padding: { top: "5px", bottom: "5px" } },
            },
          },
          [
            createBlock("modern-core/navigation", {
              linkColor: "#ffffff",
              hoverEffect: "color",
              hoverColor: "#febd69",
              fontSize: 14,
            }),
          ],
        ),
      ];
      setAttributes({ isSticky: false, transparentOnLoad: false });
    } else if (presetType === "minimalist") {
      // Preset 2: The Zara / High-Fashion Layout (1 Row, Transparent)
      newBlocks = [
        createBlock(
          "core/columns",
          {
            verticalAlignment: "center",
            style: {
              spacing: {
                padding: {
                  top: "25px",
                  bottom: "25px",
                  left: "40px",
                  right: "40px",
                },
              },
            },
          },
          [
            createBlock("core/column", { width: "20%" }, [
              createBlock("modern-core/hamburger", {
                drawerType: "fullscreen",
              }),
            ]),
            createBlock("core/column", { width: "60%" }, [
              createBlock("modern-core/logo", {
                brandName: "ATELIER",
                alignment: "center",
              }),
            ]),
            createBlock("core/column", { width: "20%" }, [
              createBlock("modern-core/header-icons", {
                showAccount: false,
                iconSpacing: 25,
              }),
            ]),
          ],
        ),
      ];
      setAttributes({ isSticky: true, transparentOnLoad: true });
    } else if (presetType === "classic") {
      // Preset 3: The Standard Modern Ecommerce Layout (2 Rows)
      newBlocks = [
        createBlock("modern-core/marquee", {
          text: "FREE SHIPPING ON ALL ORDERS",
          bgColor: "#0f172a",
        }),
        createBlock(
          "core/columns",
          {
            verticalAlignment: "center",
            style: {
              spacing: {
                padding: {
                  top: "20px",
                  bottom: "20px",
                  left: "30px",
                  right: "30px",
                },
              },
            },
          },
          [
            createBlock("core/column", { width: "25%" }, [
              createBlock("modern-core/logo", { brandName: "MODERN CORE" }),
            ]),
            createBlock("core/column", { width: "50%" }, [
              createBlock("modern-core/navigation", {
                hoverEffect: "underline",
              }),
            ]),
            createBlock("core/column", { width: "25%" }, [
              createBlock("modern-core/header-icons", {}),
            ]),
          ],
        ),
      ];
      setAttributes({ isSticky: true, transparentOnLoad: false });
    }

    // Wipe the current blocks and inject the newly built tree!
    if (
      confirm("This will replace your current header layout. Are you sure?")
    ) {
      replaceInnerBlocks(clientId, newBlocks, false);
    }
  };

  return (
    <>
      <InspectorControls>
        <PanelBody
          title={__("1-Click Layout Presets", "modern-core")}
          initialOpen={true}
        >
          <BaseControl
            help={__(
              "Warning: Applying a preset will overwrite your current header.",
              "modern-core",
            )}
          >
            <div
              style={{
                display: "flex",
                flexDirection: "column",
                gap: "10px",
                marginTop: "10px",
              }}
            >
              <Button
                variant="secondary"
                onClick={() => applyPreset("megastore")}
                style={{ justifyContent: "center" }}
              >
                Mega Store (3 Rows)
              </Button>
              <Button
                variant="secondary"
                onClick={() => applyPreset("minimalist")}
                style={{ justifyContent: "center" }}
              >
                Minimalist Fashion (1 Row)
              </Button>
              <Button
                variant="secondary"
                onClick={() => applyPreset("classic")}
                style={{ justifyContent: "center" }}
              >
                Classic Modern (2 Rows)
              </Button>
            </div>
          </BaseControl>
        </PanelBody>

        <PanelBody
          title={__("Global Header Behavior", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Sticky Header", "modern-core")}
            checked={isSticky}
            onChange={(val) => setAttributes({ isSticky: val })}
          />
          <ToggleControl
            label={__("Transparent over Hero", "modern-core")}
            checked={transparentOnLoad}
            onChange={(val) => setAttributes({ transparentOnLoad: val })}
          />
          {isSticky && (
            <ToggleControl
              label={__("Auto-Hide on Scroll", "modern-core")}
              checked={autoHideOnScroll}
              onChange={(val) => setAttributes({ autoHideOnScroll: val })}
              help={__(
                "Hides when scrolling down, reappears when scrolling up. (Requires Sticky Header to be active).",
                "modern-core",
              )}
            />
          )}
        </PanelBody>
      </InspectorControls>

      <header {...blockProps}>
        <InnerBlocks
          template={[
            [
              "core/paragraph",
              {
                align: "center",
                content:
                  "Select a Preset Layout from the right sidebar, or start building manually!",
              },
            ],
          ]}
          templateLock={false}
        />
      </header>
    </>
  );
}
