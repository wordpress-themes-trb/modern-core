(()=>{const l={$schema:"https://schemas.wp.org/trunk/block.json",apiVersion:3,name:"modern-core/cart-page",title:"Cart Page Layout",category:"modern-core",icon:"cart",description:"Conversion-optimized cart environment with real-time auto-updates.",supports:{align:["wide","full"],html:!1},attributes:{layoutMode:{type:"string",default:"two-column"},showHeaders:{type:"boolean",default:!0},autoUpdate:{type:"boolean",default:!0},summaryBg:{type:"string",default:"#f8fafc"},summaryRadius:{type:"number",default:12},accentColor:{type:"string",default:"#3b82f6"},textColor:{type:"string",default:"#0f172a"},borderColor:{type:"string",default:"#e2e8f0"},widthMode:{type:"string",default:"contained",enum:["contained","wide","fullwidth"]}},textdomain:"modern-core",editorScript:"modern-core-cart-page-js",render:"file:./render.php"};function d({attributes:w,setAttributes:t}){const{layoutMode:r,showHeaders:c,autoUpdate:p,summaryBg:a,summaryRadius:i,accentColor:o,textColor:s,borderColor:n,widthMode:m}=w,u=window.wp.blockEditor.useBlockProps({className:`modern-core-cart-editor layout-${r} width-${m}`,style:{width:"100%",padding:"40px 0",backgroundColor:"#ffffff"}});return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Macro Layout","modern-core")},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Layout Architecture","modern-core"),value:r,options:[{label:"Two Column (65% / 35%)",value:"two-column"},{label:"Single Column (Stacked)",value:"stacked"}],onChange:e=>t({layoutMode:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Width Mode","modern-core"),value:m,options:[{label:"Contained",value:"contained"},{label:"Wide",value:"wide"},{label:"Full Width",value:"fullwidth"}],onChange:e=>t({widthMode:e})})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Table & Interactions","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Table Column Headers","modern-core"),checked:c,onChange:e=>t({showHeaders:e})}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Auto-Update on Quantity Change","modern-core"),checked:p,onChange:e=>t({autoUpdate:e}),help:"Instantly recalculates totals when a user changes item quantity, hiding the default 'Update Cart' button."})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Summary Card Styling","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Card Border Radius","modern-core"),value:i,onChange:e=>t({summaryRadius:e}),min:0,max:24}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Summary Background","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:a,onChange:e=>t({summaryBg:e})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Brand Accent (Checkout Button)","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:o,onChange:e=>t({accentColor:e})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Border & Separator Color","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:n,onChange:e=>t({borderColor:e})}))),window.wp.element.createElement("div",{...u},window.wp.element.createElement("style",null,`
            .modern-core-cart-editor { 
              --mc-bg: ${a}; 
              --mc-acc: ${o}; 
              --mc-txt: ${s}; 
              --mc-border: ${n}; 
              --mc-rad: ${i}px; 
            }
            .modern-core-cart-editor.width-contained .mc-cart-mock-grid { 
              max-width: 1200px; 
              margin: 0 auto; 
              padding: 0 5%; 
            }
            .modern-core-cart-editor.width-wide .mc-cart-mock-grid { 
              max-width: 1400px; 
              margin: 0 auto; 
              padding: 0 5%; 
            }
            .modern-core-cart-editor.width-fullwidth .mc-cart-mock-grid { 
              max-width: 100%; 
              padding: 0 5%; 
            }
            .mc-cart-mock-grid { 
              display: grid; 
              gap: 40px; 
              align-items: start; 
            }
            .layout-two-column .mc-cart-mock-grid { 
              grid-template-columns: 2fr 1fr; 
            }
            .layout-stacked .mc-cart-mock-grid { 
              grid-template-columns: 1fr; 
              max-width: 800px; 
              margin: 0 auto; 
            }

            /* ===== DESKTOP TABLE ===== */
            .mc-cart-mock-table { 
              width: 100%; 
              color: var(--mc-txt); 
            }
            .mc-cart-mock-th { 
              display: ${c?"grid":"none"}; 
              grid-template-columns: 40px 2fr 1fr 1fr 1fr; 
              padding-bottom: 15px; 
              border-bottom: 1px solid var(--mc-border); 
              font-size: 12px; 
              text-transform: uppercase; 
              opacity: 0.6; 
              font-weight: 600; 
              letter-spacing: 0.5px;
            }
            .mc-cart-mock-th > div:nth-child(1) { /* remove header */ }
            .mc-cart-mock-th > div:nth-child(3) { text-align: left; }
            .mc-cart-mock-th > div:nth-child(4) { text-align: center; }
            .mc-cart-mock-th > div:nth-child(5) { text-align: right; }
            
            .mc-cart-mock-row { 
              display: grid; 
              grid-template-columns: 40px 2fr 1fr 1fr 1fr; 
              padding: 25px 0; 
              border-bottom: 1px solid var(--mc-border); 
              align-items: center; 
              position: relative;
            }
            .mc-cart-mock-row > div:nth-child(2) { /* product */ }
            .mc-cart-mock-row > div:nth-child(3) { text-align: left; font-weight: 600; }
            .mc-cart-mock-row > div:nth-child(4) { text-align: center; }
            .mc-cart-mock-row > div:nth-child(5) { text-align: right; font-weight: 700; }

            /* Desktop Remove Button */
            .mc-cart-mock-remove-desktop {
              display: flex;
              align-items: center;
              justify-content: center;
            }
            .mc-cart-mock-remove-desktop span {
              opacity: 0.5;
              font-size: 20px;
              cursor: pointer;
              transition: opacity 0.2s, color 0.2s;
              line-height: 1;
            }
            .mc-cart-mock-remove-desktop span:hover {
              opacity: 1;
              color: #ef4444;
            }

            /* Product cell */
            .mc-cart-mock-product { 
              display: flex; 
              gap: 15px; 
              align-items: center; 
              min-width: 0;
            }
            .mc-cart-mock-img { 
              width: 80px; 
              height: 100px; 
              background: #f1f5f9; 
              border-radius: 4px; 
              flex-shrink: 0; 
            }
            .mc-cart-mock-product-info {
              min-width: 0;
              overflow: hidden;
            }
            .mc-cart-mock-qty { 
              border: 1px solid var(--mc-border); 
              padding: 8px 15px; 
              border-radius: 4px; 
              display: inline-block; 
              font-weight: 600;
              font-size: 15px;
            }

            /* Mobile Remove Button (trash icon) - hidden on desktop */
            .mc-cart-mock-remove-mobile {
              display: none;
            }

            /* Coupon */
            .mc-cart-mock-coupon-row { 
              display: flex; 
              gap: 10px; 
              margin-top: 30px; 
            }
            .mc-cart-mock-coupon-input {
              padding: 12px;
              border: 1px solid ${n};
              border-radius: 4px;
              flex: 1;
              max-width: 250px;
              font-size: 14px;
            }
            .mc-cart-mock-coupon-btn {
              padding: 12px 20px;
              background: transparent;
              border: 1px solid ${o};
              color: ${o};
              border-radius: 4px;
              font-weight: bold;
              cursor: pointer;
              font-size: 14px;
            }

            /* Summary */
            .mc-cart-mock-summary { 
              background: var(--mc-bg); 
              padding: 30px; 
              border-radius: var(--mc-rad); 
              color: var(--mc-txt); 
              border: 1px solid var(--mc-border); 
            }
            .mc-cart-mock-sum-row { 
              display: flex; 
              justify-content: space-between; 
              padding: 15px 0; 
              border-bottom: 1px solid var(--mc-border); 
              font-size: 14px;
            }
            .mc-cart-mock-sum-row strong { font-weight: 600; }
            .mc-cart-mock-total { 
              display: flex; 
              justify-content: space-between; 
              padding: 20px 0; 
              font-size: 20px; 
              font-weight: bold; 
            }
            .mc-cart-mock-btn { 
              background: var(--mc-acc); 
              color: #fff; 
              padding: 18px; 
              text-align: center; 
              border-radius: 6px; 
              font-weight: bold; 
              margin-top: 10px; 
              cursor: pointer;
            }

            /* ===== MOBILE RESPONSIVE ===== */
            @media (max-width: 768px) {
              .layout-two-column .mc-cart-mock-grid { 
                grid-template-columns: 1fr; 
              }
              
              /* Hide desktop table headers */
              .mc-cart-mock-th { 
                display: none !important; 
              }
              
              /* Mobile row: full-width card layout */
              .mc-cart-mock-row { 
                display: grid;
                grid-template-columns: 80px 1fr 40px;
                grid-template-rows: auto auto auto;
                grid-template-areas: 
                  "img info delete"
                  "img price delete"
                  "img qty delete";
                padding: 20px 0;
                gap: 8px 15px;
                align-items: start;
              }
              
              /* Hide desktop remove */
              .mc-cart-mock-remove-desktop {
                display: none !important;
              }
              
              /* Show mobile trash */
              .mc-cart-mock-remove-mobile {
                display: flex !important;
                grid-area: delete;
                align-items: center;
                justify-content: center;
                align-self: center;
              }
              .mc-cart-mock-remove-mobile svg {
                width: 20px;
                height: 20px;
                color: #9ca3af;
                cursor: pointer;
                transition: color 0.2s;
              }
              .mc-cart-mock-remove-mobile svg:hover {
                color: #ef4444;
              }
              
              /* Product image */
              .mc-cart-mock-row .mc-cart-mock-product {
                grid-area: img;
                flex-direction: column;
                gap: 0;
              }
              .mc-cart-mock-row .mc-cart-mock-img {
                width: 80px;
                height: 100px;
              }
              
              /* Product info */
              .mc-cart-mock-row .mc-cart-mock-product-info {
                grid-area: info;
                min-width: 0;
              }
              
              /* Price */
              .mc-cart-mock-row > div:nth-child(3) {
                grid-area: price;
                text-align: left;
                font-size: 16px;
                font-weight: 700;
              }
              
              /* Quantity */
              .mc-cart-mock-row > div:nth-child(4) {
                grid-area: qty;
                text-align: left;
              }
              .mc-cart-mock-row .mc-cart-mock-qty {
                padding: 6px 12px;
                font-size: 14px;
              }
              
              /* Subtotal - hide on mobile (matches design) */
              .mc-cart-mock-row > div:nth-child(5) {
                display: none !important;
              }
            }
          `),window.wp.element.createElement("div",{className:"mc-cart-mock-grid"},window.wp.element.createElement("div",{className:"mc-cart-mock-left"},window.wp.element.createElement("div",{className:"mc-cart-mock-table"},window.wp.element.createElement("div",{className:"mc-cart-mock-th"},window.wp.element.createElement("div",null),window.wp.element.createElement("div",null,"Product"),window.wp.element.createElement("div",null,"Price"),window.wp.element.createElement("div",null,"Quantity"),window.wp.element.createElement("div",null,"Total")),window.wp.element.createElement("div",{className:"mc-cart-mock-row"},window.wp.element.createElement("div",{className:"mc-cart-mock-remove-desktop"},window.wp.element.createElement("span",null,"✕")),window.wp.element.createElement("div",{className:"mc-cart-mock-product"},window.wp.element.createElement("div",{className:"mc-cart-mock-img"}),window.wp.element.createElement("div",{className:"mc-cart-mock-product-info"},window.wp.element.createElement("div",{style:{fontWeight:"bold",fontSize:"16px",whiteSpace:"nowrap",overflow:"hidden",textOverflow:"ellipsis"}},"Classic Oxford Shirt"),window.wp.element.createElement("div",{style:{fontSize:"13px",opacity:.6}},"Color: White, Size: M"))),window.wp.element.createElement("div",null,"$45.00"),window.wp.element.createElement("div",null,window.wp.element.createElement("span",{className:"mc-cart-mock-qty"},"- 1 +")),window.wp.element.createElement("div",null,"$45.00"),window.wp.element.createElement("div",{className:"mc-cart-mock-remove-mobile"},window.wp.element.createElement("svg",{viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"},window.wp.element.createElement("polyline",{points:"3 6 5 6 21 6"}),window.wp.element.createElement("path",{d:"M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"}))))),window.wp.element.createElement("div",{className:"mc-cart-mock-coupon-row"},window.wp.element.createElement("input",{type:"text",placeholder:"Coupon code",className:"mc-cart-mock-coupon-input",readOnly:!0}),window.wp.element.createElement("button",{className:"mc-cart-mock-coupon-btn"},"Apply"))),window.wp.element.createElement("div",{className:"mc-cart-mock-right"},window.wp.element.createElement("div",{className:"mc-cart-mock-summary"},window.wp.element.createElement("h3",{style:{margin:"0 0 20px 0",fontSize:"20px",fontWeight:"bold"}},"Order Summary"),window.wp.element.createElement("div",{className:"mc-cart-mock-sum-row"},window.wp.element.createElement("span",null,"Subtotal"),window.wp.element.createElement("strong",null,"$45.00")),window.wp.element.createElement("div",{className:"mc-cart-mock-sum-row"},window.wp.element.createElement("span",null,"Shipping"),window.wp.element.createElement("span",null,"Calculated at checkout")),window.wp.element.createElement("div",{className:"mc-cart-mock-total"},window.wp.element.createElement("span",null,"Total"),window.wp.element.createElement("span",null,"$45.00")),window.wp.element.createElement("div",{className:"mc-cart-mock-btn"},"Proceed to checkout"))))))}window.wp.blocks.registerBlockType(l,{apiVersion:3,edit:d,save:()=>null})})();
