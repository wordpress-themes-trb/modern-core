import * as React from "@wordpress/element";
import {
  InspectorControls,
  useBlockProps,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  ColorPalette,
  Button,
  TextControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    minHeight,
    transition,
    autoplay,
    delay,
    showArrows,
    showDots,
    slides,
  } = attributes;

  const [activeSlide, setActiveSlide] = React.useState(0);

  const blockProps = useBlockProps({
    className: "modern-core-slider-editor",
    style: {
      width: "100%",
      position: "relative",
      boxSizing: "border-box",
      overflow: "hidden",
    },
  });

  const updateSlide = (index, key, value) => {
    const newSlides = [...slides];
    newSlides[index][key] = value;
    setAttributes({ slides: newSlides });
  };

  const addSlide = () => {
    setAttributes({
      slides: [
        ...slides,
        {
          bgImage: "",
          bgColor: "#000000",
          opacity: 50,
          subhead: "SUBHEADING",
          heading: "New Slide Heading",
          btnText: "Click Here",
        },
      ],
    });
    setActiveSlide(slides.length);
  };

  const removeSlide = (index) => {
    const newSlides = slides.filter((_, i) => i !== index);
    setAttributes({ slides: newSlides });
    if (activeSlide >= newSlides.length) {
      setActiveSlide(Math.max(0, newSlides.length - 1));
    }
  };

  // Safely grab the data for the canvas preview
  const currentSlide = slides[activeSlide] || slides[0];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Slide Manager", "modern-core")}>
          <p style={{ fontSize: "12px", opacity: 0.6, marginBottom: "15px" }}>
            Select a slide below to edit its content and background.
          </p>

          {slides.map((slide, index) => (
            <div
              key={index}
              style={{
                marginBottom: "10px",
                border: "1px solid",
                borderColor: activeSlide === index ? "#0f172a" : "#e2e8f0",
                borderRadius: "6px",
                overflow: "hidden",
              }}
            >
              {/* Slide Header Toggle */}
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                  alignItems: "center",
                  padding: "10px 15px",
                  background: activeSlide === index ? "#f8fafc" : "#ffffff",
                  cursor: "pointer",
                }}
                onClick={() => setActiveSlide(index)}
              >
                <strong
                  style={{ color: activeSlide === index ? "#000" : "#64748b" }}
                >
                  Slide {index + 1}
                </strong>
                {slides.length > 1 && (
                  <Button
                    isDestructive
                    variant="link"
                    onClick={(e) => {
                      e.stopPropagation();
                      removeSlide(index);
                    }}
                    style={{ textDecoration: "none" }}
                  >
                    Remove
                  </Button>
                )}
              </div>

              {/* Slide Edit Panel (Only visible if active) */}
              {activeSlide === index && (
                <div
                  style={{
                    padding: "15px",
                    borderTop: "1px solid #e2e8f0",
                    background: "#fff",
                  }}
                >
                  <TextControl
                    label="Subheading"
                    value={slide.subhead}
                    onChange={(val) => updateSlide(index, "subhead", val)}
                  />
                  <TextControl
                    label="Main Heading"
                    value={slide.heading}
                    onChange={(val) => updateSlide(index, "heading", val)}
                  />
                  <TextControl
                    label="Button Text"
                    value={slide.btnText}
                    onChange={(val) => updateSlide(index, "btnText", val)}
                  />

                  <hr style={{ margin: "20px 0", opacity: 0.2 }} />

                  <MediaUploadCheck>
                    <MediaUpload
                      onSelect={(media) =>
                        updateSlide(index, "bgImage", media.url)
                      }
                      allowedTypes={["image"]}
                      render={({ open }) => (
                        <Button
                          variant="secondary"
                          onClick={open}
                          style={{
                            width: "100%",
                            justifyContent: "center",
                            marginBottom: "15px",
                          }}
                        >
                          {slide.bgImage ? "Change Image" : "Upload Image"}
                        </Button>
                      )}
                    />
                  </MediaUploadCheck>
                  {slide.bgImage && (
                    <Button
                      variant="link"
                      isDestructive
                      onClick={() => updateSlide(index, "bgImage", "")}
                      style={{
                        marginBottom: "15px",
                        display: "block",
                        textAlign: "center",
                        width: "100%",
                      }}
                    >
                      Clear Image
                    </Button>
                  )}

                  <p style={{ marginTop: "5px", marginBottom: "5px" }}>
                    <strong>Background Color</strong>
                  </p>
                  <ColorPalette
                    value={slide.bgColor}
                    onChange={(val) => updateSlide(index, "bgColor", val)}
                  />
                  <RangeControl
                    label="Overlay Opacity (%)"
                    value={slide.opacity}
                    onChange={(val) => updateSlide(index, "opacity", val)}
                    min={0}
                    max={100}
                  />
                </div>
              )}
            </div>
          ))}

          <Button
            variant="secondary"
            onClick={addSlide}
            style={{
              width: "100%",
              justifyContent: "center",
              marginTop: "10px",
            }}
          >
            {__("+ Add New Slide")}
          </Button>
        </PanelBody>

        <PanelBody
          title={__("Slider Physics", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Transition Style", "modern-core")}
            value={transition}
            options={[
              { label: "Cinematic Fade", value: "fade" },
              { label: "Horizontal Slide", value: "slide" },
            ]}
            onChange={(val) => setAttributes({ transition: val })}
          />
          <RangeControl
            label={__("Height (vh)", "modern-core")}
            value={minHeight}
            onChange={(val) => setAttributes({ minHeight: val })}
            min={40}
            max={100}
          />
        </PanelBody>

        <PanelBody
          title={__("Autoplay & Navigation", "modern-core")}
          initialOpen={false}
        >
          <ToggleControl
            label={__("Enable Autoplay", "modern-core")}
            checked={autoplay}
            onChange={(val) => setAttributes({ autoplay: val })}
          />
          {autoplay && (
            <RangeControl
              label={__("Slide Duration (ms)", "modern-core")}
              value={delay}
              onChange={(val) => setAttributes({ delay: val })}
              min={2000}
              max={10000}
              step={500}
            />
          )}
          <ToggleControl
            label={__("Show Navigation Arrows", "modern-core")}
            checked={showArrows}
            onChange={(val) => setAttributes({ showArrows: val })}
          />
          <ToggleControl
            label={__("Show Dot Indicators", "modern-core")}
            checked={showDots}
            onChange={(val) => setAttributes({ showDots: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Editor Visual Indicator */}
        <div
          style={{
            position: "absolute",
            top: "10px",
            left: "10px",
            background: "rgba(0,0,0,0.5)",
            color: "#fff",
            padding: "4px 10px",
            borderRadius: "4px",
            fontSize: "11px",
            fontWeight: "bold",
            zIndex: 99,
          }}
        >
          Previewing Slide {activeSlide + 1} of {slides.length}
        </div>

        {/* The Isolated Slide Canvas Preview */}
        {currentSlide && (
          <div
            style={{
              position: "relative",
              width: "100%",
              minHeight: `${minHeight}vh`,
              backgroundColor: currentSlide.bgColor,
              backgroundImage: currentSlide.bgImage
                ? `url(${currentSlide.bgImage})`
                : "none",
              backgroundSize: "cover",
              backgroundPosition: "center",
              display: "flex",
              flexDirection: "column",
              justifyContent: "center",
              padding: "80px 5%",
              boxSizing: "border-box",
            }}
          >
            {/* Overlay */}
            <div
              style={{
                position: "absolute",
                inset: 0,
                backgroundColor: "#000",
                opacity: currentSlide.opacity / 100,
                zIndex: 1,
              }}
            ></div>

            {/* Content */}
            <div
              style={{
                position: "relative",
                zIndex: 2,
                width: "100%",
                maxWidth: "1200px",
                margin: "0 auto",
              }}
            >
              {currentSlide.subhead && (
                <p
                  style={{
                    fontSize: "12px",
                    letterSpacing: "2px",
                    textTransform: "uppercase",
                    color: "#fff",
                    margin: "0 0 15px 0",
                  }}
                >
                  {currentSlide.subhead}
                </p>
              )}
              {currentSlide.heading && (
                <h2
                  style={{
                    fontSize: "clamp(36px, 5vw, 64px)",
                    fontWeight: "800",
                    lineHeight: "1.1",
                    color: "#fff",
                    margin: "0 0 30px 0",
                  }}
                >
                  {currentSlide.heading}
                </h2>
              )}
              {currentSlide.btnText && (
                <div
                  style={{
                    display: "inline-block",
                    padding: "12px 30px",
                    background: "#fff",
                    color: "#000",
                    fontWeight: "bold",
                    fontSize: "14px",
                    borderRadius: "0px",
                    cursor: "pointer",
                  }}
                >
                  {currentSlide.btnText}
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </>
  );
}
