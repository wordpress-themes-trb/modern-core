(()=>{const x={$schema:"https://schemas.wp.org/trunk/block.json",apiVersion:3,name:"modern-core/pricing-table",title:"Pricing Table",category:"modern-core",icon:"columns",description:"High-conversion pricing comparison with interactive billing toggles.",supports:{align:["wide","full"],html:!1},attributes:{showToggle:{type:"boolean",default:!0},discountLabel:{type:"string",default:"Save 20%"},showOriginalPrice:{type:"boolean",default:!0},cardRadius:{type:"number",default:12},accentColor:{type:"string",default:"#000000"},textColor:{type:"string",default:"#000000"},bgBase:{type:"string",default:"#ffffff"},bgFeatured:{type:"string",default:"#fafafa"},tiers:{type:"array",default:[{name:"Basic",badge:"",priceMonthly:"19",priceAnnual:"15",priceAnnualOriginal:"19",desc:"Perfect for individuals getting started.",button:"Start Basic",isFeatured:!1,features:[{text:"1 User Account",included:!0},{text:"Basic Analytics",included:!0},{text:"Priority Support",included:!1}]},{name:"Pro",badge:"Most Popular",priceMonthly:"49",priceAnnual:"39",priceAnnualOriginal:"49",desc:"Everything you need to grow your business.",button:"Start Pro",isFeatured:!0,features:[{text:"5 User Accounts",included:!0},{text:"Advanced Analytics",included:!0},{text:"Priority Support",included:!0}]},{name:"Enterprise",badge:"",priceMonthly:"99",priceAnnual:"79",priceAnnualOriginal:"99",desc:"For large scale organizations.",button:"Contact Sales",isFeatured:!1,features:[{text:"Unlimited Users",included:!0},{text:"Custom Reporting",included:!0},{text:"24/7 Phone Support",included:!0}]}]}},textdomain:"modern-core",editorScript:"modern-core-pricing-table-js",render:"file:./render.php"};function h({attributes:b,setAttributes:o}){const{showToggle:r,discountLabel:m,showOriginalPrice:p,cardRadius:s,accentColor:c,textColor:d,bgBase:g,bgFeatured:u,tiers:w}=b,[l,f]=window.wp.element.useState(!1),y=window.wp.blockEditor.useBlockProps({className:"modern-core-pricing-editor",style:{width:"100%",maxWidth:"100%",padding:"40px 0",backgroundColor:"transparent"}}),a=(e,t,n)=>{const i=[...w];i[e][t]=n,o({tiers:i})},E=()=>window.wp.element.createElement("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"3",strokeLinecap:"round",strokeLinejoin:"round",style:{flexShrink:0}},window.wp.element.createElement("polyline",{points:"20 6 9 17 4 12"})),v=()=>window.wp.element.createElement("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"#cbd5e1",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round",style:{flexShrink:0}},window.wp.element.createElement("line",{x1:"18",y1:"6",x2:"6",y2:"18"}),window.wp.element.createElement("line",{x1:"6",y1:"6",x2:"18",y2:"18"}));return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Architecture & Toggles","modern-core")},window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Monthly/Annual Toggle","modern-core"),checked:r,onChange:e=>o({showToggle:e})}),r&&window.wp.element.createElement(window.wp.components.TextControl,{label:window.wp.i18n.__("Discount Label","modern-core"),value:m,onChange:e=>o({discountLabel:e})}),window.wp.element.createElement("hr",{style:{margin:"20px 0",opacity:.2}}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Original Price Strikethrough","modern-core"),checked:p,onChange:e=>o({showOriginalPrice:e}),help:"Displays the crossed-out original price next to the discounted annual price."}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Card Border Radius","modern-core"),value:s,onChange:e=>o({cardRadius:e}),min:0,max:24})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Minimalist Colors","modern-core"),initialOpen:!1},window.wp.element.createElement("p",{style:{marginTop:"5px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Brand Accent (Buttons & Badges)","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:c,onChange:e=>o({accentColor:e})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Standard Card Background","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:g,onChange:e=>o({bgBase:e})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Featured Card Background","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:u,onChange:e=>o({bgFeatured:e})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Text Color","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:d,onChange:e=>o({textColor:e})})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Tiers Management","modern-core"),initialOpen:!1},w.map((e,t)=>window.wp.element.createElement("div",{key:t,style:{marginBottom:"20px",padding:"15px",border:"1px solid #e2e8f0",borderRadius:"8px"}},window.wp.element.createElement("div",{style:{fontWeight:"bold",marginBottom:"10px"}},"Tier: ",e.name),window.wp.element.createElement(window.wp.components.ToggleControl,{label:"Featured / Elevated Tier",checked:e.isFeatured,onChange:n=>a(t,"isFeatured",n)}),window.wp.element.createElement(window.wp.components.TextControl,{label:"Tier Name",value:e.name,onChange:n=>a(t,"name",n)}),window.wp.element.createElement(window.wp.components.TextControl,{label:"Badge (e.g., Most Popular)",value:e.badge,onChange:n=>a(t,"badge",n)}),window.wp.element.createElement("div",{style:{display:"flex",gap:"10px",flexWrap:"wrap"}},window.wp.element.createElement(window.wp.components.TextControl,{label:"Monthly $",value:e.priceMonthly,onChange:n=>a(t,"priceMonthly",n),style:{flex:1}}),window.wp.element.createElement(window.wp.components.TextControl,{label:"Annual $",value:e.priceAnnual,onChange:n=>a(t,"priceAnnual",n),style:{flex:1}}),p&&window.wp.element.createElement(window.wp.components.TextControl,{label:"Orig. Annual $",value:e.priceAnnualOriginal||"",onChange:n=>a(t,"priceAnnualOriginal",n),style:{flex:1},help:"Price to cross out"})),window.wp.element.createElement(window.wp.components.TextControl,{label:"Button Text",value:e.button,onChange:n=>a(t,"button",n)}))))),window.wp.element.createElement("div",{...y},window.wp.element.createElement("style",null,`
            /* Hard lock the box model in the editor so buttons don't blow out */
            .modern-core-pricing-editor *, 
            .modern-core-pricing-editor *::before, 
            .modern-core-pricing-editor *::after { 
                box-sizing: border-box !important; 
            }
            .modern-core-pricing-editor { --mc-rad: ${s}px; --mc-acc: ${c}; --mc-txt: ${d}; }
            
            .mc-pt-toggle-wrap { display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 50px; color: var(--mc-txt); }
            .mc-pt-switch { width: 64px; height: 34px; background: rgba(0,0,0,0.1); border-radius: 50px; position: relative; cursor: pointer; }
            .mc-pt-switch.is-annual { background: var(--mc-acc); }
            .mc-pt-knob { width: 28px; height: 28px; background: #fff; border-radius: 50%; position: absolute; top: 3px; left: ${l?"33px":"3px"}; transition: left 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
            .mc-pt-badge { background: var(--mc-acc); color: #fff; font-size: 11px; padding: 4px 10px; border-radius: 50px; font-weight: bold; margin-left: 8px; }
            
            /* Responsive Grid tailored to survive Gutenberg's narrow canvas */
            .mc-pt-grid { 
                display: grid; 
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
                gap: 20px; 
                width: 100%;
                max-width: 1200px; 
                margin: 0 auto; 
                align-items: stretch; 
            }
            
            .mc-pt-card { 
                height: 100%;
                width: 100%;
                background: ${g}; 
                padding: 30px 20px; 
                border-radius: var(--mc-rad); 
                box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
                border: 1px solid rgba(0,0,0,0.08); 
                display: flex; 
                flex-direction: column; 
                position: relative; 
                color: var(--mc-txt); 
                text-align: left; 
            }
            .mc-pt-card.is-featured { 
                background: ${u}; 
                transform: scale(1.02); 
                box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
                border: 2px solid var(--mc-acc); 
                z-index: 10; 
            }
            
            .mc-pt-price-wrap { display: flex; align-items: baseline; gap: 5px; margin-bottom: 30px; flex-wrap: wrap; }
            
            .mc-pt-btn { 
                width: 100%; 
                padding: 16px; 
                text-align: center; 
                border-radius: 8px; 
                font-weight: bold; 
                margin-top: auto; /* Pushes button to the bottom */
                display: block; 
            }
            .mc-pt-card.is-featured .mc-pt-btn { background: var(--mc-acc); color: #fff; border: none; }
            .mc-pt-card:not(.is-featured) .mc-pt-btn { background: transparent; border: 1px solid var(--mc-acc); color: var(--mc-acc); }
          `),r&&window.wp.element.createElement("div",{className:"mc-pt-toggle-wrap"},window.wp.element.createElement("span",{style:{fontWeight:l?"normal":"bold",opacity:l?.6:1}},"Monthly"),window.wp.element.createElement("div",{className:`mc-pt-switch ${l?"is-annual":""}`,onClick:()=>f(!l)},window.wp.element.createElement("div",{className:"mc-pt-knob"})),window.wp.element.createElement("span",{style:{fontWeight:l?"bold":"normal",opacity:l?1:.6,display:"flex",alignItems:"center"}},"Annually ",window.wp.element.createElement("span",{className:"mc-pt-badge"},m))),window.wp.element.createElement("div",{className:"mc-pt-grid"},w.map((e,t)=>window.wp.element.createElement("div",{key:t,className:`mc-pt-card ${e.isFeatured?"is-featured":""}`},e.isFeatured&&e.badge&&window.wp.element.createElement("div",{style:{position:"absolute",top:"-14px",left:"50%",transform:"translateX(-50%)",background:c,color:"#fff",fontSize:"12px",fontWeight:"bold",padding:"6px 20px",borderRadius:"50px",whiteSpace:"nowrap",letterSpacing:"1px",textTransform:"uppercase"}},e.badge),window.wp.element.createElement("h3",{style:{margin:"0 0 10px 0",fontSize:"24px",fontWeight:"800"}},e.name),window.wp.element.createElement("p",{style:{fontSize:"15px",opacity:.7,margin:"0 0 25px 0",lineHeight:"1.5",minHeight:"45px"}},e.desc),window.wp.element.createElement("div",{className:"mc-pt-price-wrap"},window.wp.element.createElement("span",{style:{fontSize:"24px",fontWeight:"bold"}},"$"),window.wp.element.createElement("span",{style:{fontSize:"48px",fontWeight:"900",lineHeight:1,letterSpacing:"-1px"}},l?e.priceAnnual:e.priceMonthly),window.wp.element.createElement("span",{style:{opacity:.6,fontWeight:"bold"}},"/mo"),p&&l&&e.priceAnnualOriginal&&window.wp.element.createElement("del",{style:{fontSize:"16px",opacity:.5,marginLeft:"4px",fontWeight:"bold",color:d}},"$",e.priceAnnualOriginal)),window.wp.element.createElement("div",{style:{display:"flex",flexDirection:"column",gap:"12px",flex:1,marginBottom:"35px"}},e.features.map((n,i)=>window.wp.element.createElement("div",{key:i,style:{display:"flex",alignItems:"flex-start",gap:"10px",fontSize:"15px",opacity:n.included?1:.4,color:n.included?"inherit":"#64748b"}},n.included?window.wp.element.createElement(E,null):window.wp.element.createElement(v,null),window.wp.element.createElement("span",{style:{lineHeight:"1.4"}},n.text)))),window.wp.element.createElement("div",{className:"mc-pt-btn"},e.button))))))}window.wp.blocks.registerBlockType(x,{apiVersion:3,edit:h,save:()=>null})})();
