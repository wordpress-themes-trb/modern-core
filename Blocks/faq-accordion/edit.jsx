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
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-faq-editor icon-pos-${iconPosition}`,
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
            fontSize: "18px",
            fontWeight: "bold",
            lineHeight: 1,
            color: isOpen ? accentColor : textColor,
          }}
        >
          {isOpen ? "−" : "+"}
        </div>
      );
    }
    // Default Chevron
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
          transition: "transform 0.3s",
        }}
      >
        <polyline points="6 9 12 15 18 9"></polyline>
      </svg>
    );
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Accordion Behavior", "modern-core")}>
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
            min={0}
            max={1000}
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
            This data automatically generates SEO-friendly JSON-LD Schema on the
            frontend.
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
            <strong>{__("Divider/Border Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={borderColor}
            onChange={(val) => setAttributes({ borderColor: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div style={{ borderTop: `1px solid ${borderColor}` }}>
          {faqs.map((faq, i) => {
            const isOpen = openFirst && i === 0;
            return (
              <div key={i} style={{ borderBottom: `1px solid ${borderColor}` }}>
                <div
                  style={{
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "space-between",
                    flexDirection:
                      iconPosition === "left" ? "row-reverse" : "row",
                    padding: "20px",
                    background: questionBg,
                    color: isOpen ? accentColor : textColor,
                    cursor: "pointer",
                  }}
                >
                  <h4
                    style={{ margin: 0, fontSize: "16px", fontWeight: "bold" }}
                  >
                    {faq.question}
                  </h4>
                  <Icon isOpen={isOpen} />
                </div>
                {isOpen && (
                  <div
                    style={{
                      padding: "0 20px 20px 20px",
                      background: answerBg,
                      color: textColor,
                      fontSize: "15px",
                      lineHeight: 1.6,
                    }}
                  >
                    {faq.answer}
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </div>
    </>
  );
}
