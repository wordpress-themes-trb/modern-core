import * as React from "@wordpress/element";
import { useState, useEffect } from "@wordpress/element";
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
  TextControl,
  Modal,
  Notice,
} from "@wordpress/components";
import { useDispatch, useSelect } from "@wordpress/data";
import { createBlock } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import apiFetch from "@wordpress/api-fetch";

export default function Edit({ attributes, setAttributes, clientId }) {
  const {
    isSticky,
    transparentOnLoad,
    autoHideOnScroll,
    preset,
    presetName,
    isLocal,
  } = attributes;
  const [showSaveModal, setShowSaveModal] = useState(false);
  const [newPresetName, setNewPresetName] = useState("");
  const [savedPresets, setSavedPresets] = useState([]);
  const [saveMessage, setSaveMessage] = useState("");

  const { replaceInnerBlocks } = useDispatch("core/block-editor");

  const innerBlocks = useSelect(
    (select) => {
      return select("core/block-editor").getBlocks(clientId);
    },
    [clientId],
  );

  useEffect(() => {
    apiFetch({ path: "/modern-core/v1/header-presets" })
      .then((data) => {
        if (data && data.presets) {
          setSavedPresets(data.presets);
        }
      })
      .catch(() => {});
  }, []);

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

  const getPresetBlocks = (presetType) => {
    switch (presetType) {
      case "megastore":
        return [
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

      case "minimalist":
        return [
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

      case "classic":
        return [
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

      default:
        return [];
    }
  };

  const applyPreset = (presetType) => {
    const newBlocks = getPresetBlocks(presetType);
    if (newBlocks.length === 0) return;

    const behaviorMap = {
      megastore: { isSticky: false, transparentOnLoad: false },
      minimalist: { isSticky: true, transparentOnLoad: true },
      classic: { isSticky: true, transparentOnLoad: false },
    };

    const confirmMsg =
      innerBlocks.length > 1
        ? "This will replace your current header layout. Are you sure?"
        : `Apply the "${presetType}" preset?`;

    if (confirm(confirmMsg)) {
      replaceInnerBlocks(clientId, newBlocks, false);
      setAttributes({ preset: presetType, ...behaviorMap[presetType] });
    }
  };

  const saveAsPreset = async () => {
    if (!newPresetName.trim()) {
      setSaveMessage("Please enter a preset name.");
      return;
    }

    const blocks = wp.blocks.serialize(innerBlocks);

    try {
      await apiFetch({
        path: "/modern-core/v1/header-presets",
        method: "POST",
        data: {
          name: newPresetName.trim(),
          blocks: blocks,
          settings: { isSticky, transparentOnLoad, autoHideOnScroll },
        },
      });

      setSavedPresets([
        ...savedPresets,
        {
          name: newPresetName.trim(),
          slug: newPresetName.trim().toLowerCase().replace(/\s+/g, "-"),
        },
      ]);
      setSaveMessage(`Preset "${newPresetName}" saved successfully!`);
      setNewPresetName("");
      setTimeout(() => setSaveMessage(""), 3000);
    } catch (err) {
      setSaveMessage("Failed to save preset. Please try again.");
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
              "Select a preset to quickly build your header layout.",
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
                variant={preset === "megastore" ? "primary" : "secondary"}
                onClick={() => applyPreset("megastore")}
                style={{ justifyContent: "center" }}
              >
                🏪 Mega Store (3 Rows)
              </Button>
              <Button
                variant={preset === "minimalist" ? "primary" : "secondary"}
                onClick={() => applyPreset("minimalist")}
                style={{ justifyContent: "center" }}
              >
                👗 Minimalist Fashion (1 Row)
              </Button>
              <Button
                variant={preset === "classic" ? "primary" : "secondary"}
                onClick={() => applyPreset("classic")}
                style={{ justifyContent: "center" }}
              >
                🛍️ Classic Modern (2 Rows)
              </Button>
            </div>
          </BaseControl>

          {savedPresets.length > 0 && (
            <BaseControl
              label={__("Your Saved Presets", "modern-core")}
              style={{ marginTop: "20px" }}
            >
              <div
                style={{ display: "flex", flexDirection: "column", gap: "8px" }}
              >
                {savedPresets.map((p) => (
                  <Button
                    key={p.slug}
                    variant="secondary"
                    onClick={() => applyPreset(p.slug)}
                    style={{ justifyContent: "center" }}
                  >
                    💾 {p.name}
                  </Button>
                ))}
              </div>
            </BaseControl>
          )}

          <BaseControl style={{ marginTop: "20px" }}>
            <Button
              variant="tertiary"
              onClick={() => setShowSaveModal(true)}
              style={{ width: "100%" }}
            >
              💾 Save Current as Preset
            </Button>
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

      {showSaveModal && (
        <Modal
          title={__("Save as Preset", "modern-core")}
          onRequestClose={() => {
            setShowSaveModal(false);
            setSaveMessage("");
          }}
        >
          <TextControl
            label={__("Preset Name", "modern-core")}
            value={newPresetName}
            onChange={setNewPresetName}
            placeholder={__("e.g. Black Friday Header", "modern-core")}
          />
          {saveMessage && (
            <Notice
              status={saveMessage.includes("success") ? "success" : "error"}
              isDismissible={false}
            >
              {saveMessage}
            </Notice>
          )}
          <Button
            isPrimary
            onClick={saveAsPreset}
            style={{ marginTop: "10px" }}
          >
            {__("Save Preset", "modern-core")}
          </Button>
        </Modal>
      )}

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
