import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { useState, useEffect } from "@wordpress/element";
import {
  SelectControl,
  ToggleControl,
  Button,
  Notice,
} from "@wordpress/components";
import apiFetch from "@wordpress/api-fetch";


const HeaderFooterPanel = () => {
  const postId = mcHeaderData.postId;
  const [hasLocalHeader, setHasLocalHeader] = useState(false);
  const [selectedPreset, setSelectedPreset] = useState("");
  const [presets, setPresets] = useState([]);
  const [message, setMessage] = useState("");
  const [isSaving, setIsSaving] = useState(false);

  useEffect(() => {
    apiFetch({
      path: `modern-core/v1/page-header?post_id=${postId}`,
      headers: { "X-WP-Nonce": mcHeaderData.nonce },
    }).then((data) => {
      setHasLocalHeader(data.has_local_header);
    });

    apiFetch({
      path: "modern-core/v1/header-presets",
      headers: { "X-WP-Nonce": mcHeaderData.nonce },
    }).then((data) => {
      if (data.presets) {
        setPresets(data.presets);
      }
    });
  }, [postId]);

  const toggleLocalHeader = async (enabled) => {
    setIsSaving(true);

    if (!enabled) {
      await apiFetch({
        path: "modern-core/v1/page-header",
        method: "POST",
        headers: { "X-WP-Nonce": mcHeaderData.nonce },
        data: {
          post_id: postId,
          header_data: null,
        },
      });
      setHasLocalHeader(false);
      setMessage("Reverted to global header. Save the page to apply.");
    } else {
      setHasLocalHeader(true);
      setMessage(
        "Local header enabled. Add an Advanced Header block to the page.",
      );
    }

    setIsSaving(false);
    setTimeout(() => setMessage(""), 5000);
  };

  const applyPresetToPage = async () => {
    if (!selectedPreset) return;

    setIsSaving(true);

    const presetData = await apiFetch({
      path: "modern-core/v1/header-presets",
      headers: { "X-WP-Nonce": mcHeaderData.nonce },
    });

    const preset = presetData.presets.find((p) => p.slug === selectedPreset);

    if (preset) {
      await apiFetch({
        path: "modern-core/v1/page-header",
        method: "POST",
        headers: { "X-WP-Nonce": mcHeaderData.nonce },
        data: {
          post_id: postId,
          header_data: {
            preset: selectedPreset,
            blocks: preset.blocks,
            settings: preset.settings,
          },
        },
      });

      setHasLocalHeader(true);
      setMessage(`Applied "${preset.name}" preset. Refresh to see changes.`);
    }

    setIsSaving(false);
    setTimeout(() => setMessage(""), 5000);
  };

  return (
    <PluginDocumentSettingPanel
      name="mc-header-footer-panel"
      title="Header & Footer"
      icon="layout"
    >
      {message && (
        <Notice
          status="info"
          isDismissible={false}
          style={{ marginBottom: "10px" }}
        >
          {message}
        </Notice>
      )}

      <ToggleControl
        label="Use Custom Header for This Page"
        checked={hasLocalHeader}
        onChange={toggleLocalHeader}
        help={
          hasLocalHeader
            ? "This page has its own header. Edit it in the block editor."
            : "Using the global header from Site Editor > Template Parts."
        }
      />

      {hasLocalHeader && (
        <>
          <SelectControl
            label="Apply Preset"
            value={selectedPreset}
            options={[
              { label: "Select a preset...", value: "" },
              { label: "Mega Store", value: "megastore" },
              { label: "Minimalist", value: "minimalist" },
              { label: "Classic", value: "classic" },
              ...presets.map((p) => ({ label: p.name, value: p.slug })),
            ]}
            onChange={setSelectedPreset}
          />

          <Button
            isSecondary
            onClick={applyPresetToPage}
            disabled={!selectedPreset || isSaving}
            style={{ width: "100%", marginTop: "8px" }}
          >
            {isSaving ? "Applying..." : "Apply Preset"}
          </Button>

          <hr style={{ margin: "16px 0", borderColor: "#e2e8f0" }} />

          <Button
            isLink
            href={`${mcHeaderData.restUrl.replace("wp-json/", "")}site-editor.php?path=%2Ftemplate-part`}
            target="_blank"
          >
            Edit Global Header →
          </Button>
        </>
      )}

      {!hasLocalHeader && (
        <Button
          isLink
          href={`${mcHeaderData.restUrl.replace("wp-json/", "")}site-editor.php?path=%2Ftemplate-part`}
          target="_blank"
          style={{ marginTop: "8px" }}
        >
          Edit Global Header in Site Editor →
        </Button>
      )}
    </PluginDocumentSettingPanel>
  );
};

registerPlugin("mc-header-footer-panel", {
  render: HeaderFooterPanel,
});
