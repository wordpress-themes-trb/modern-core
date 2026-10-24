import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  TextControl,
  TextareaControl,
  ColorPalette,
  Button,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    behavior,
    openFirst,
    iconStyle,
    iconPosition,
    animDuration,
    questionBg,
    answerBg,
    textColor,
    accentColor,
    borderColor,
    faqs,
    widthMode,
    aosAnimation,
  } = attributes;

  // Track open states in the editor for a live preview feel
  const [openItems, setOpenItems] = React.useState(
    openFirst && faqs.length > 0 ? { 0: true } : {},
  );

  const toggleItem = (index) => {
    setOpenItems((prev) => {
      if (behavior === "exclusive") {
        return prev[index] ? {} : { [index]: true };
      }
      return { ...prev, [index]: !prev[index] };
    });
  };

  const blockProps = useBlockProps({
    className: `modern-core-faq-editor layout-${layoutMode} icon-pos-${iconPosition} mc-width-${widthMode}`,
    "data-aos": aosAnimation,
    style: { width: "100%", padding: "40px 0" },
  });

  const updateFaq = (index, key, value) => {
    const newFaqs = [...faqs];
    newFaqs[index][key] = value;
    setAttributes({ faqs: newFaqs });
  };

  const addFaq = () => {
    setAttributes({
      faqs: [
        ...faqs,
        { question: "New Question?", answer: "Answer goes here." },
      ],
    });
  };

  const removeFaq = (index) => {
    setAttributes({ faqs: faqs.filter((_, i) => i !== index) });
  };

  const Icon = ({ isOpen }) => {
    if (iconStyle === "plus-minus") {
      return (
        <div
          style={{
            position: "relative",
            width: "14px",
            height: "14px",
            color: isOpen ? accentColor : textColor,
          }}
        >
          <span
            style={{
              position: "absolute",
              top: "50%",
              left: "50%",
              width: "100%",
              height: "2px",
              background: "currentColor",
              transform: isOpen
                ? "translate(-50%, -50%) rotate(180deg)"
                : "translate(-50%, -50%)",
              transition: `transform ${animDuration}ms ease`,
            }}
          />
          <span
            style={{
              position: "absolute",
              top: "50%",
              left: "50%",
              width: "2px",
              height: "100%",
              background: "currentColor",
              transform: isOpen
                ? "translate(-50%, -50%) rotate(90deg)"
                : "translate(-50%, -50%)",
              opacity: isOpen ? 0 : 1,
              transition: `transform ${animDuration}ms ease, opacity ${animDuration}ms ease`,
            }}
          />
        </div>
      );
    }
    return (
      <svg
        width="20"
        height="20"
        viewBox="0 0 24 24"
        fill="none"
        stroke={isOpen ? accentColor : textColor}
        strokeWidth="2"
        style={{
          transform: isOpen ? "rotate(180deg)" : "rotate(0deg)",
          transition: `transform ${animDuration}ms ease`,
        }}
      >
        <polyline points="6 9 12 15 18 9"></polyline>
      </svg>
    );
  };

  // Editor-specific styles for layouts
  const getWrapperStyle = () => {
    if (layoutMode === "elevated")
      return {
        background: "#fff",
        borderRadius: "12px",
        boxShadow: "0 10px 30px -10px rgba(0,0,0,0.08)",
        overflow: "hidden",
        border: `1px solid ${borderColor}`,
      };
    if (layoutMode === "separated")
      return { display: "flex", flexDirection: "column", gap: "16px" };
    return { borderTop: `1px solid ${borderColor}` }; // flush
  };

  const getItemStyle = (index, length) => {
    if (layoutMode === "separated")
      return {
        background: questionBg,
        borderRadius: "8px",
        border: `1px solid ${borderColor}`,
        overflow: "hidden",
      };
    if (layoutMode === "elevated" && index === length - 1) return {};
    return { borderBottom: `1px solid ${borderColor}` };
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Layout & Animation", "modern-core")}>
          <SelectControl
            label={__("Design Style", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Flush (Traditional borders)", value: "flush" },
              { label: "Elevated Card (Seamless)", value: "elevated" },
              { label: "Separated Blocks", value: "separated" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          <SelectControl
            label={__("Width Mode", "modern-core")}
            value={widthMode}
            options={[
              { label: "Contained", value: "contained" },
              { label: "Wide", value: "wide" },
              { label: "Fullwidth", value: "fullwidth" },
            ]}
            onChange={(val) => setAttributes({ widthMode: val })}
          />
          <SelectControl
            label={__("AOS Animation", "modern-core")}
            value={aosAnimation}
            options={[
              { label: "None", value: "" },
              { label: "Fade Up", value: "fade-up" },
              { label: "Fade Down", value: "fade-down" },
              { label: "Fade In", value: "fade" },
            ]}
            onChange={(val) => setAttributes({ aosAnimation: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Accordion Behavior", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Expand/Collapse Mode", "modern-core")}
            value={behavior}
            options={[
              { label: "Exclusive (One open at a time)", value: "exclusive" },
              {
                label: "Independent (Multiple can open)",
                value: "independent",
              },
            ]}
            onChange={(val) => setAttributes({ behavior: val })}
          />
          <ToggleControl
            label={__("First Item Open by Default", "modern-core")}
            checked={openFirst}
            onChange={(val) => setAttributes({ openFirst: val })}
          />
          <RangeControl
            label={__("Animation Duration (ms)", "modern-core")}
            value={animDuration}
            onChange={(val) => setAttributes({ animDuration: val })}
            min={100}
            max={800}
            step={50}
          />
        </PanelBody>

        <PanelBody
          title={__("Visual Design", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Icon Style", "modern-core")}
            value={iconStyle}
            options={[
              { label: "Rotating Chevron", value: "chevron" },
              { label: "Plus / Minus", value: "plus-minus" },
            ]}
            onChange={(val) => setAttributes({ iconStyle: val })}
          />
          <SelectControl
            label={__("Icon Position", "modern-core")}
            value={iconPosition}
            options={[
              { label: "Right Aligned", value: "right" },
              { label: "Left Aligned", value: "left" },
            ]}
            onChange={(val) => setAttributes({ iconPosition: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("FAQ Content Data", "modern-core")}
          initialOpen={false}
        >
          <p style={{ fontSize: "12px", opacity: 0.6, marginBottom: "15px" }}>
            Generates SEO-friendly JSON-LD Schema.
          </p>
          {faqs.map((faq, index) => (
            <div
              key={index}
              style={{
                marginBottom: "20px",
                padding: "15px",
                border: "1px solid #e2e8f0",
                borderRadius: "8px",
              }}
            >
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  marginBottom: "10px",
                }}
              >
                <strong>Item {index + 1}</strong>
                <span
                  style={{
                    color: "#ef4444",
                    cursor: "pointer",
                    fontSize: "12px",
                  }}
                  onClick={() => removeFaq(index)}
                >
                  Remove
                </span>
              </div>
              <TextControl
                label="Question"
                value={faq.question}
                onChange={(val) => updateFaq(index, "question", val)}
              />
              <TextareaControl
                label="Answer"
                value={faq.answer}
                onChange={(val) => updateFaq(index, "answer", val)}
                rows={4}
              />
            </div>
          ))}
          <Button
            variant="primary"
            onClick={addFaq}
            style={{ width: "100%", justifyContent: "center" }}
          >
            + Add Question
          </Button>
        </PanelBody>

        <PanelBody title={__("Colors", "modern-core")} initialOpen={false}>
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Question Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={questionBg}
            onChange={(val) => setAttributes({ questionBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Answer Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={answerBg}
            onChange={(val) => setAttributes({ answerBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Text Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={textColor}
            onChange={(val) => setAttributes({ textColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Active Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={accentColor}
            onChange={(val) => setAttributes({ accentColor: val })}
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
        <div style={getWrapperStyle()}>
          {faqs.map((faq, i) => {
            const isOpen = !!openItems[i];
            return (
              <div key={i} style={getItemStyle(i, faqs.length)}>
                <div
                  onClick={() => toggleItem(i)}
                  style={{
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "space-between",
                    flexDirection:
                      iconPosition === "left" ? "row-reverse" : "row",
                    padding: layoutMode === "separated" ? "24px" : "20px 24px",
                    background: questionBg,
                    color: isOpen ? accentColor : textColor,
                    cursor: "pointer",
                  }}
                >
                  <h4
                    style={{
                      margin: 0,
                      fontSize: "17px",
                      fontWeight: "600",
                      letterSpacing: "-0.01em",
                    }}
                  >
                    {faq.question}
                  </h4>
                  <Icon isOpen={isOpen} />
                </div>
                {/* Simulated CSS Grid Transition for Editor */}
                <div
                  style={{
                    display: "grid",
                    gridTemplateRows: isOpen ? "1fr" : "0fr",
                    transition: `grid-template-rows ${animDuration}ms ease`,
                    background: answerBg,
                  }}
                >
                  <div style={{ overflow: "hidden" }}>
                    <div
                      style={{
                        padding: "0 24px 24px 24px",
                        color: textColor,
                        fontSize: "16px",
                        lineHeight: 1.6,
                        opacity: isOpen ? 0.9 : 0,
                        transition: `opacity ${animDuration}ms ease`,
                      }}
                    >
                      {faq.answer}
                    </div>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </>
  );
}
