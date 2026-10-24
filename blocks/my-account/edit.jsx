import * as React from "@wordpress/element";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  ToggleControl,
  TextControl,
  ColorPalette,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

export default function Edit({ attributes, setAttributes }) {
  const {
    layoutMode,
    navWidth,
    welcomeFormat,
    showRecentOrders,
    showWishlist,
    navBg,
    contentBg,
    accentColor,
    textColor,
    borderColor,
    cardRadius,
  } = attributes;

  const blockProps = useBlockProps({
    className: `modern-core-account-editor layout-${layoutMode}`,
    style: { width: "100%", padding: "40px 0", backgroundColor: "#f1f5f9" },
  });

  const navItems = [
    "Dashboard",
    "Orders",
    "Downloads",
    "Addresses",
    "Account details",
    "Wishlist",
    "Logout",
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Architecture & Layout", "modern-core")}>
          <SelectControl
            label={__("Layout Mode", "modern-core")}
            value={layoutMode}
            options={[
              { label: "Sidebar Navigation (Vertical)", value: "sidebar" },
              { label: "Tab Navigation (Horizontal)", value: "tabs" },
              { label: "Card Dashboard (Grid Overview)", value: "cards" },
            ]}
            onChange={(val) => setAttributes({ layoutMode: val })}
          />
          {layoutMode === "sidebar" && (
            <RangeControl
              label={__("Sidebar Width (px)", "modern-core")}
              value={navWidth}
              onChange={(val) => setAttributes({ navWidth: val })}
              min={200}
              max={400}
            />
          )}
        </PanelBody>

        <PanelBody
          title={__("Dashboard Content", "modern-core")}
          initialOpen={false}
        >
          <TextControl
            label={__("Welcome Message Format", "modern-core")}
            value={welcomeFormat}
            onChange={(val) => setAttributes({ welcomeFormat: val })}
            help="Use {name} to insert the customer's first name."
          />
          <ToggleControl
            label={__("Show Recent Orders Summary", "modern-core")}
            checked={showRecentOrders}
            onChange={(val) => setAttributes({ showRecentOrders: val })}
          />
          <ToggleControl
            label={__("Show Wishlist Link/Summary", "modern-core")}
            checked={showWishlist}
            onChange={(val) => setAttributes({ showWishlist: val })}
          />
        </PanelBody>

        <PanelBody
          title={__("Colors & Styling", "modern-core")}
          initialOpen={false}
        >
          <RangeControl
            label={__("Card & Field Border Radius", "modern-core")}
            value={cardRadius}
            onChange={(val) => setAttributes({ cardRadius: val })}
            min={0}
            max={24}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Navigation Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={navBg}
            onChange={(val) => setAttributes({ navBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Content Background", "modern-core")}</strong>
          </p>
          <ColorPalette
            value={contentBg}
            onChange={(val) => setAttributes({ contentBg: val })}
          />
          <p style={{ marginTop: "15px", marginBottom: "5px" }}>
            <strong>{__("Brand Accent (Active State)", "modern-core")}</strong>
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
        <style>
          {`
                    .modern-core-account-editor { --ma-bg-nav: ${navBg}; --ma-bg-content: ${contentBg}; --ma-acc: ${accentColor}; --ma-txt: ${textColor}; --ma-border: ${borderColor}; --ma-rad: ${cardRadius}px; --ma-nav-w: ${navWidth}px; }
                    
                    .mc-acct-mock-wrapper { max-width: 1200px; margin: 0 auto; padding: 0 5%; display: flex; gap: 40px; color: var(--ma-txt); }
                    
                    /* Sidebar Layout */
                    .layout-sidebar .mc-acct-mock-wrapper { flex-direction: row; }
                    .layout-sidebar .mc-acct-mock-nav { width: var(--ma-nav-w); flex-shrink: 0; background: var(--ma-bg-nav); border-radius: var(--ma-rad); border: 1px solid var(--ma-border); overflow: hidden; display: flex; flex-direction: column; }
                    .layout-sidebar .mc-acct-mock-nav-item { padding: 18px 24px; border-bottom: 1px solid var(--ma-border); font-weight: bold; opacity: 0.7; }
                    .layout-sidebar .mc-acct-mock-nav-item.is-active { opacity: 1; border-left: 4px solid var(--ma-acc); background: #fff; color: var(--ma-acc); }

                    /* Tabs Layout */
                    .layout-tabs .mc-acct-mock-wrapper { flex-direction: column; }
                    .layout-tabs .mc-acct-mock-nav { display: flex; gap: 10px; border-bottom: 2px solid var(--ma-border); padding-bottom: 10px; overflow-x: auto; }
                    .layout-tabs .mc-acct-mock-nav-item { padding: 12px 24px; font-weight: bold; opacity: 0.6; white-space: nowrap; border-radius: var(--ma-rad) var(--ma-rad) 0 0; }
                    .layout-tabs .mc-acct-mock-nav-item.is-active { opacity: 1; color: var(--ma-acc); border-bottom: 3px solid var(--ma-acc); margin-bottom: -12px; }

                    /* Cards Dashboard Layout */
                    .layout-cards .mc-acct-mock-wrapper { flex-direction: column; }
                    .layout-cards .mc-acct-mock-nav { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
                    .layout-cards .mc-acct-mock-nav-item { background: var(--ma-bg-content); border: 1px solid var(--ma-border); padding: 30px; border-radius: var(--ma-rad); font-weight: bold; font-size: 18px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; align-items: center; justify-content: space-between; }
                    .layout-cards .mc-acct-mock-nav-item.is-active { border-color: var(--ma-acc); color: var(--ma-acc); }

                    /* Content Area */
                    .mc-acct-mock-content { flex: 1; background: var(--ma-bg-content); border: 1px solid var(--ma-border); border-radius: var(--ma-rad); padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
                    .layout-cards .mc-acct-mock-content { display: none; } /* Content is hidden in root card view */

                    .mc-acct-welcome { font-size: 28px; font-weight: 800; margin: 0 0 30px 0; }
                    .mc-acct-card { border: 1px solid var(--ma-border); border-radius: var(--ma-rad); padding: 25px; margin-bottom: 20px; }
                    .mc-acct-card-header { display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--ma-border); font-weight: bold; }
                    `}
        </style>

        <div className="mc-acct-mock-wrapper">
          <div className="mc-acct-mock-nav">
            {navItems.map((item, i) => (
              <div
                key={i}
                className={`mc-acct-mock-nav-item ${i === 0 ? "is-active" : ""}`}
              >
                {item}{" "}
                {layoutMode === "cards" && (
                  <span style={{ opacity: 0.3 }}>→</span>
                )}
              </div>
            ))}
          </div>

          <div className="mc-acct-mock-content">
            <h2 className="mc-acct-welcome">
              {welcomeFormat.replace("{name}", "Sarah")}
            </h2>
            <p style={{ opacity: 0.8, marginBottom: "40px" }}>
              From your account dashboard you can view your recent orders,
              manage your shipping and billing addresses, and edit your password
              and account details.
            </p>

            {showRecentOrders && (
              <>
                <h3
                  style={{
                    fontSize: "20px",
                    fontWeight: "bold",
                    margin: "0 0 20px 0",
                  }}
                >
                  Recent Orders
                </h3>
                <div className="mc-acct-card">
                  <div className="mc-acct-card-header">
                    <span>Order #4829</span>
                    <span
                      style={{
                        background: "#d1fae5",
                        color: "#059669",
                        padding: "4px 10px",
                        borderRadius: "50px",
                        fontSize: "12px",
                      }}
                    >
                      Completed
                    </span>
                  </div>
                  <div
                    style={{
                      display: "flex",
                      justifyContent: "space-between",
                      opacity: 0.8,
                      fontSize: "14px",
                    }}
                  >
                    <span>October 12, 2025</span>
                    <span>$120.00 for 2 items</span>
                  </div>
                  <div style={{ marginTop: "20px" }}>
                    <button
                      style={{
                        background: "transparent",
                        border: `1px solid ${accentColor}`,
                        color: accentColor,
                        padding: "10px 20px",
                        borderRadius: "4px",
                        fontWeight: "bold",
                      }}
                    >
                      View Order
                    </button>
                  </div>
                </div>
              </>
            )}
          </div>
        </div>
      </div>
    </>
  );
}
