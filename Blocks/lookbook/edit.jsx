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
  ColorPalette,
  Button,
  TextControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    imageUrl,
    imageHeight,
    pins,
    pinStyle,
    pinColor,
    pulseColor,
    cardBg,
    cardRadius,
    overlayOpacity,
  } = attributes;

  // State to track which pin the user is currently editing
  const [activePinIndex, setActivePinIndex] = React.useState(null);

  const blockProps = useBlockProps({
    className: `modern-core-lookbook-editor style-${pinStyle}`,
    style: { width: "100%", position: "relative", backgroundColor: "#0f172a" },
  });

  // Calculate click coordinates and add a new pin
  const handleImageClick = (e) => {
    const rect = e.target.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;

    const newPins = [...pins, { id: Date.now(), x, y, productId: 0 }];
    setAttributes({ pins: newPins });
    setActivePinIndex(newPins.length - 1);
  };

  const updateActivePin = (productId) => {
    if (activePinIndex === null) return;
    const newPins = [...pins];
    newPins[activePinIndex].productId = parseInt(productId);
    setAttributes({ pins: newPins });
  };

  const removeActivePin = () => {
    if (activePinIndex === null) return;
    const newPins = pins.filter((_, i) => i !== activePinIndex);
    setAttributes({ pins: newPins });
    setActivePinIndex(null);
  };

  if (!imageUrl) {
    return (
      <div
        {...blockProps}
        style={{
          ...blockProps.style,
          padding: "100px 20px",
          textAlign: "center",
          backgroundColor: "#f1f5f9",
          border: "2px dashed #cbd5e1",
        }}
      >
        <h3 style={{ margin: "0 0 10px 0", color: "#0f172a" }}>
          Shoppable Lookbook
        </h3>
        <p style={{ opacity: 0.6, marginBottom: "20px" }}>
          Upload a lifestyle image to start placing shoppable hotspots.
        </p>
        <MediaUploadCheck>
          <MediaUpload
            onSelect={(media) => setAttributes({ imageUrl: media.url })}
            allowedTypes={["image"]}
            render={({ open }) => (
              <Button variant="primary" onClick={open}>
                Select Image
              </Button>
            )}
          />
        </MediaUploadCheck>
      </div>
    );
  }

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Lookbook Configuration", "modern-core")}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ imageUrl: media.url })}
              allowedTypes={["image"]}
              render={({ open }) => (
                <Button
                  variant="secondary"
                  onClick={open}
                  style={{ width: "100%", marginBottom: "15px" }}
                >
                  Change Background Image
                </Button>
              )}
            />
          </MediaUploadCheck>
          <RangeControl
            label={__("Canvas Height (vh)", "modern-core")}
            value={imageHeight}
            onChange={(val) => setAttributes({ imageHeight: val })}
            min={40}
            max={100}
          />
          <RangeControl
            label={__("Dark Overlay Opacity (%)", "modern-core")}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={100}
          />
        </PanelBody>

        <PanelBody
          title={__("Pin Settings", "modern-core")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Pin Visual Style", "modern-core")}
            value={pinStyle}
            options={[
              { label: "Pulsing Ring", value: "pulse" },
              { label: "Solid Plus Icon", value: "plus" },
            ]}
            onChange={(val) => setAttributes({ pinStyle: val })}
          />
          <p style={{ marginTop: "5px", marginBottom: "5px" }}>
            <strong>{__("Pin Core Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={pinColor}
            onChange={(val) => setAttributes({ pinColor: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Pulse/Accent Color", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={pulseColor}
            onChange={(val) => setAttributes({ pulseColor: val })}
          />
        </PanelBody>

        <PanelBody title={__("Active Pin Routing", "modern-core")}>
          {activePinIndex !== null ? (
            <div
              style={{
                padding: "15px",
                backgroundColor: "#f8fafc",
                border: "1px solid #e2e8f0",
                borderRadius: "8px",
              }}
            >
              <p style={{ margin: "0 0 10px 0", fontWeight: "bold" }}>
                Editing Pin {activePinIndex + 1}
              </p>
              <TextControl
                type="number"
                label={__("WooCommerce Product ID", "modern-core")}
                value={pins[activePinIndex].productId || ""}
                onChange={updateActivePin}
                help={__(
                  "Enter the ID of the product to show on hover.",
                  "modern-core",
                )}
              />
              <Button
                isDestructive
                onClick={removeActivePin}
                style={{ marginTop: "10px" }}
              >
                Delete Pin
              </Button>
            </div>
          ) : (
            <p style={{ opacity: 0.6, fontSize: "13px" }}>
              Click any pin on the image to edit its product data, or click the
              image to add a new pin.
            </p>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <style>
          {`
                    .mc-lookbook-canvas { height: ${imageHeight}vh; position: relative; overflow: hidden; background-image: url(${imageUrl}); background-size: cover; background-position: center; cursor: crosshair; }
                    .mc-lookbook-overlay { position: absolute; inset: 0; background: rgba(0,0,0,${overlayOpacity / 100}); pointer-events: none; }
                    .mc-pin-mock { position: absolute; width: 24px; height: 24px; background: ${pinColor}; border-radius: 50%; transform: translate(-50%, -50%); cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 10; border: 2px solid #fff; transition: transform 0.2s; }
                    .mc-pin-mock.is-active { transform: translate(-50%, -50%) scale(1.3); border-color: ${pulseColor}; }
                    .style-pulse .mc-pin-mock::after { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; background: ${pulseColor}; z-index: -1; animation: mcEditorPulse 2s infinite; }
                    @keyframes mcEditorPulse { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(2.5); opacity: 0; } }
                    `}
        </style>

        <div className="mc-lookbook-canvas" onClick={handleImageClick}>
          <div className="mc-lookbook-overlay"></div>

          {pins.map((pin, index) => (
            <div
              key={pin.id}
              className={`mc-pin-mock ${activePinIndex === index ? "is-active" : ""}`}
              style={{ left: `${pin.x}%`, top: `${pin.y}%` }}
              onClick={(e) => {
                e.stopPropagation();
                setActivePinIndex(index);
              }}
            >
              {/* Visual center dot */}
              <div
                style={{
                  width: "6px",
                  height: "6px",
                  backgroundColor: pulseColor,
                  borderRadius: "50%",
                }}
              ></div>
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
