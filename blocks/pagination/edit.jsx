import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  TextControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    mode,
    alignment,
    prevNextStyle,
    loadMoreText,
    loadingText,
    buttonRadius,
    accentColor,
    textColor,
    borderColor,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-pagination-editor mode-${mode}`,
    style: {
      width: "100%",
      padding: "40px 0",
      display: "flex",
      justifyContent: alignment,
    },
  });

  const PrevIcon = () => (
    <svg
      width="16"
      height="16"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      style={{ marginRight: "5px" }}
    >
      <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
  );
  const NextIcon = () => (
    <svg
      width="16"
      height="16"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      style={{ marginLeft: "5px" }}
    >
      <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
  );

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Navigation Philosophy", "modern-core")}>
          <SelectControl
            label={__("Pagination Mode", "modern-core")}
            value={mode}
            options={[
              { label: "Numbered Pages (Standard)", value: "numbers" },
              { label: "AJAX Load More Button", value: "load-more" },
              { label: "AJAX Infinite Scroll", value: "infinite" },
            ]}
            onChange={(val) => setAttributes({ mode: val })}
          />
          <SelectControl
            label={__("Alignment", "modern-core")}
            value={alignment}
            options={[
              { label: "Left", value: "flex-start" },
              { label: "Center", value: "center" },
              { label: "Right", value: "flex-end" },
            ]}
            onChange={(val) => setAttributes({ alignment: val })}
          />
        </PanelBody>

        {mode === "numbers" && (
          <PanelBody
            title={__("Number Controls", "modern-core")}
            initialOpen={false}
          >
            <SelectControl
              label={__("Prev/Next Style", "modern-core")}
              value={prevNextStyle}
              options={[
                { label: "Text & Icon", value: "both" },
                { label: "Text Only", value: "text" },
                { label: "Icon Only", value: "icon" },
              ]}
              onChange={(val) => setAttributes({ prevNextStyle: val })}
            />
          </PanelBody>
        )}

        {(mode === "load-more" || mode === "infinite") && (
          <PanelBody
            title={__("AJAX Controls", "modern-core")}
            initialOpen={false}
          >
            {mode === "load-more" && (
              <TextControl
                label={__("Button Text", "modern-core")}
                value={loadMoreText}
                onChange={(val) => setAttributes({ loadMoreText: val })}
              />
            )}
            <TextControl
              label={__("Loading State Text", "modern-core")}
              value={loadingText}
              onChange={(val) => setAttributes({ loadingText: val })}
            />
          </PanelBody>
        )}

        <PanelBody
          title={__("Design & Colors", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Button Border Radius", "modern-core")}
            value={buttonRadius}
            onChange={(val) => setAttributes({ buttonRadius: val })}
            min={0}
            max={50}
          />
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Active Page)", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Border Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={borderColor}
            onChange={(val) => setAttributes({ borderColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .mc-page-mock {
                        display: flex; align-items: center; justify-content: center;
                        min-width: 40px; height: 40px; padding: 0 15px; margin: 0 5px;
                        border: 1px solid ${borderColor}; border-radius: ${buttonRadius}px;
                        color: ${textColor}; font-size: 14px; font-weight: bold; background: #fff;
                    }
                    .mc-page-mock.is-active { background: ${accentColor}; border-color: ${accentColor}; color: #fff; }
                    .mc-page-mock-ellipsis { color: ${textColor}; opacity: 0.5; margin: 0 10px; font-weight: bold; letter-spacing: 2px; }
                    .mc-load-mock {
                        padding: 15px 40px; border: 2px solid ${accentColor}; color: ${accentColor};
                        border-radius: ${buttonRadius}px; font-weight: bold; font-size: 15px; cursor: pointer;
                    }
                    .mc-inf-mock { color: ${textColor}; opacity: 0.6; font-size: 14px; font-style: italic; }
                    `}
        </style>

        {mode === "numbers" && (
          <div style={{ display: "flex", alignItems: "center" }}>
            <div className="mc-page-mock" style={{ padding: "0 20px" }}>
              {(prevNextStyle === "icon" || prevNextStyle === "both") && (
                <PrevIcon />
              )}
              {(prevNextStyle === "text" || prevNextStyle === "both") &&
                "Previous"}
            </div>
            <div className="mc-page-mock">1</div>
            <div className="mc-page-mock is-active">2</div>
            <div className="mc-page-mock">3</div>
            <span className="mc-page-mock-ellipsis">...</span>
            <div className="mc-page-mock">12</div>
            <div className="mc-page-mock" style={{ padding: "0 20px" }}>
              {(prevNextStyle === "text" || prevNextStyle === "both") && "Next"}
              {(prevNextStyle === "icon" || prevNextStyle === "both") && (
                <NextIcon />
              )}
            </div>
          </div>
        )}

        {mode === "load-more" && (
          <div className="mc-load-mock">{loadMoreText}</div>
        )}

        {mode === "infinite" && (
          <div className="mc-inf-mock">
            ↓ Scroll to load more (Infinite active)
          </div>
        )}
      </div>
    </>
  );
}
