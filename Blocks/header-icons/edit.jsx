import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  ToggleControl,
  RangeControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    showSearch,
    showAccount,
    showCart,
    showCartBadge,
    iconSize,
    iconSpacing,
    iconColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: "modern-core-header-icons-block",
    style: {
      display: "flex",
      alignItems: "center",
      gap: `${iconSpacing}px`,
      color: iconColor,
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Icon Visibility", "modern-core")}>
          <ToggleControl
            label={__("Show Search", "modern-core")}
            checked={showSearch}
            onChange={(val) => setAttributes({ showSearch: val })}
          />
          <ToggleControl
            label={__("Show Account", "modern-core")}
            checked={showAccount}
            onChange={(val) => setAttributes({ showAccount: val })}
          />
          <ToggleControl
            label={__("Show Cart", "modern-core")}
            checked={showCart}
            onChange={(val) => setAttributes({ showCart: val })}
          />
          {showCart && (
            <ToggleControl
              label={__("Show Cart Item Badge", "modern-core")}
              checked={showCartBadge}
              onChange={(val) => setAttributes({ showCartBadge: val })}
            />
          )}
        </PanelBody>
        <PanelBody
          title={__("Icon Styling", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Icon Size (px)", "modern-core")}
            value={iconSize}
            onChange={(val) => setAttributes({ iconSize: val })}
            min={16}
            max={40}
          />
          <RangeControl
            label={__("Spacing Between Icons (px)", "modern-core")}
            value={iconSpacing}
            onChange={(val) => setAttributes({ iconSpacing: val })}
            min={5}
            max={50}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Icon Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={iconColor}
            onChange={(val) => setAttributes({ iconColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {showSearch && (
          <svg
            width={iconSize}
            height={iconSize}
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
            style={{ cursor: "pointer" }}
          >
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        )}

        {showAccount && (
          <svg
            width={iconSize}
            height={iconSize}
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
            style={{ cursor: "pointer" }}
          >
            <path d="M20 21v-2a4 4出 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
        )}

        {showCart && (
          <div
            style={{ position: "relative", cursor: "pointer", display: "flex" }}
          >
            <svg
              width={iconSize}
              height={iconSize}
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            {showCartBadge && (
              <span
                style={{
                  position: "absolute",
                  top: "-5px",
                  right: "-8px",
                  backgroundColor: iconColor,
                  color: "#ffffff",
                  fontSize: `${Math.max(10, iconSize * 0.4)}px`,
                  padding: "2px 6px",
                  borderRadius: "12px",
                  fontWeight: "bold",
                  lineHeight: "1",
                }}
              >
                3
              </span>
            )}
          </div>
        )}
      </div>
    </>
  );
}
