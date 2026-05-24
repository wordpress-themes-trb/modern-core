(()=>{const y={$schema:"https://schemas.wp.org/trunk/block.json",apiVersion:3,name:"modern-core/product-grid",title:"Advanced Product Grid",category:"modern-core",icon:"grid-view",description:"High-conversion product grid with dynamic queries and custom card designs.",supports:{align:["wide","full"],html:!1},attributes:{productCount:{type:"number",default:8},querySort:{type:"string",default:"date"},layoutMode:{type:"string",default:"grid"},columnsDesktop:{type:"number",default:4},columnsTablet:{type:"number",default:3},columnsMobile:{type:"number",default:2},imageRatio:{type:"string",default:"3/4"},imageHover:{type:"string",default:"zoom"},cardRadius:{type:"number",default:8},cardBg:{type:"string",default:"#ffffff"},cardHoverLift:{type:"boolean",default:!0},showCategory:{type:"boolean",default:!1},showRating:{type:"boolean",default:!0},saleBadgeStyle:{type:"string",default:"pill"},atcStyle:{type:"string",default:"solid"},atcPosition:{type:"string",default:"below"},showToolbar:{type:"boolean",default:!0},paginationType:{type:"string",default:"load-more"},accentColor:{type:"string",default:"#0f172a"},widthMode:{type:"string",default:"contained",enum:["contained","wide","fullwidth"]}},textdomain:"modern-core",editorScript:"modern-core-product-grid-js",render:"file:./render.php"};function b({attributes:x,setAttributes:o}){const{productCount:r,querySort:h,layoutMode:d,columnsDesktop:w,columnsMobile:v,imageRatio:c,imageHover:C,cardRadius:l,cardBg:p,cardHoverLift:m,showCategory:E,showRating:s,saleBadgeStyle:S,atcStyle:a,atcPosition:i,showToolbar:u,paginationType:g,accentColor:t}=x,_=window.wp.blockEditor.useBlockProps({className:`modern-core-grid-editor layout-${d} atc-${i}`,style:{width:"100%",padding:"20px"}}),f=Array.from({length:Math.min(r,8)}).map((e,n)=>({id:n,title:n%2===0?"Premium Cotton Silhouette":"Minimalist Architecture Chair",price:n%3===0?"$120.00":"$85.00",sale:n===1||n===4?"$65.00":null,category:n%2===0?"Apparel":"Furniture",image:`https://images.unsplash.com/photo-${n%2===0?"1521572163474-6f64f9fa023e":"1506422745199-563b7e735496"}?q=80&w=600&auto=format&fit=crop`}));return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Query Builder","modern-core")},window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Products to Show","modern-core"),value:r,onChange:e=>o({productCount:e}),min:2,max:24}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Sort By","modern-core"),value:h,options:[{label:"Newest Arrivals",value:"date"},{label:"Best Selling",value:"popularity"},{label:"Price: High to Low",value:"price-desc"},{label:"Price: Low to High",value:"price"}],onChange:e=>o({querySort:e})})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Grid Layout","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Macro Layout","modern-core"),value:d,options:[{label:"Standard Grid",value:"grid"},{label:"List View",value:"list"}],onChange:e=>o({layoutMode:e})}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Columns (Desktop)","modern-core"),value:w,onChange:e=>o({columnsDesktop:e}),min:2,max:6}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Columns (Mobile)","modern-core"),value:v,onChange:e=>o({columnsMobile:e}),min:1,max:3})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Product Card Design","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Image Aspect Ratio","modern-core"),value:c,options:[{label:"Portrait (3:4)",value:"3/4"},{label:"Square (1:1)",value:"1/1"},{label:"Landscape (16:9)",value:"16/9"},{label:"Uncropped (Auto)",value:"auto"}],onChange:e=>o({imageRatio:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Image Hover Effect","modern-core"),value:C,options:[{label:"Cinematic Zoom",value:"zoom"},{label:"Second Image Swap",value:"swap"},{label:"None",value:"none"}],onChange:e=>o({imageHover:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Add to Cart Style","modern-core"),value:a,options:[{label:"Solid Button",value:"solid"},{label:"Outlined Button",value:"outline"},{label:"Text Link",value:"text"}],onChange:e=>o({atcStyle:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Add to Cart Position","modern-core"),value:i,options:[{label:"Below Product Info",value:"below"},{label:"Overlay on Image Hover",value:"overlay"}],onChange:e=>o({atcPosition:e})}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Hover Card Lift/Shadow","modern-core"),checked:m,onChange:e=>o({cardHoverLift:e})}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Star Rating","modern-core"),checked:s,onChange:e=>o({showRating:e})}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Card Border Radius","modern-core"),value:l,onChange:e=>o({cardRadius:e}),min:0,max:24}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Brand Accent Color","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:t,onChange:e=>o({accentColor:e})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Card Background","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:p,onChange:e=>o({cardBg:e})})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Filtering & Pagination","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Top Toolbar (Sort/Filter)","modern-core"),checked:u,onChange:e=>o({showToolbar:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Pagination Style","modern-core"),value:g,options:[{label:"Load More Button",value:"load-more"},{label:"Standard Page Numbers",value:"numbers"},{label:"Infinite Scroll",value:"infinite"}],onChange:e=>o({paginationType:e})}))),window.wp.element.createElement("div",{..._},window.wp.element.createElement("style",null,`
                    .modern-core-grid-editor {
                        --mc-cols: ${w};
                        --mc-ratio: ${c};
                        --mc-radius: ${l}px;
                        --mc-bg: ${p};
                        --mc-accent: ${t};
                    }
                    .mc-grid-container {
                        display: grid;
                        grid-template-columns: repeat(var(--mc-cols), 1fr);
                        gap: 30px;
                    }
                    .layout-list .mc-grid-container {
                        grid-template-columns: 1fr;
                        gap: 20px;
                    }
                    .mc-product-card {
                        background: var(--mc-bg);
                        border-radius: var(--mc-radius);
                        overflow: hidden;
                        display: flex;
                        flex-direction: column;
                        border: 1px solid rgba(0,0,0,0.05);
                        position: relative;
                    }
                    .layout-list .mc-product-card {
                        flex-direction: row;
                        align-items: center;
                    }
                    .mc-card-image {
                        aspect-ratio: var(--mc-ratio);
                        width: 100%;
                        background-size: cover;
                        background-position: center;
                        position: relative;
                        overflow: hidden;
                    }
                    .layout-list .mc-card-image {
                        width: 300px;
                        flex-shrink: 0;
                    }
                    .mc-card-info {
                        padding: 20px;
                        display: flex;
                        flex-direction: column;
                        flex: 1;
                    }
                    .mc-badge {
                        position: absolute; top: 10px; left: 10px;
                        background: #ef4444; color: #fff; font-size: 10px; font-weight: bold;
                        padding: 4px 10px; border-radius: ${S==="pill"?"50px":"4px"}; z-index: 2;
                    }
                    .mc-atc-btn {
                        margin-top: 15px; padding: 12px; text-align: center; font-size: 13px; font-weight: bold;
                        background: ${a==="solid"?t:"transparent"};
                        color: ${a==="solid"?"#fff":t};
                        border: ${a==="outline"?`2px solid ${t}`:"none"};
                        border-radius: ${l}px;
                    }
                    .atc-overlay .mc-atc-btn { display: none; }
                    `),u&&window.wp.element.createElement("div",{style:{display:"flex",justifyContent:"space-between",marginBottom:"30px",paddingBottom:"15px",borderBottom:"1px solid #e2e8f0",fontSize:"13px"}},window.wp.element.createElement("span",{style:{opacity:.6}},"Showing ",f.length," results"),window.wp.element.createElement("span",{style:{fontWeight:"bold"}},"Sort by: Newest ↓")),window.wp.element.createElement("div",{className:"mc-grid-container"},f.map(e=>window.wp.element.createElement("div",{key:e.id,className:"mc-product-card",style:{boxShadow:m?"0 10px 30px rgba(0,0,0,0.05)":"none"}},window.wp.element.createElement("div",{className:"mc-card-image",style:{backgroundImage:`url(${e.image})`}},e.sale&&window.wp.element.createElement("span",{className:"mc-badge"},"SALE"),i==="overlay"&&window.wp.element.createElement("div",{style:{position:"absolute",bottom:"10px",left:"10px",right:"10px",background:"#fff",color:"#000",padding:"12px",textAlign:"center",borderRadius:l,fontSize:"12px",fontWeight:"bold"}},"+ Quick Add")),window.wp.element.createElement("div",{className:"mc-card-info"},E&&window.wp.element.createElement("span",{style:{fontSize:"11px",textTransform:"uppercase",opacity:.5,marginBottom:"5px"}},e.category),window.wp.element.createElement("h3",{style:{fontSize:"15px",margin:"0 0 10px 0",lineHeight:1.3}},e.title),s&&window.wp.element.createElement("div",{style:{color:"#f59e0b",fontSize:"12px",marginBottom:"10px"}},"★★★★★"," ",window.wp.element.createElement("span",{style:{opacity:.5,color:"#000"}},"(24)")),window.wp.element.createElement("div",{style:{marginTop:"auto",display:"flex",gap:"10px",alignItems:"center"}},e.sale?window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement("span",{style:{fontSize:"15px",fontWeight:"bold",color:"#ef4444"}},e.sale),window.wp.element.createElement("span",{style:{fontSize:"13px",textDecoration:"line-through",opacity:.5}},e.price)):window.wp.element.createElement("span",{style:{fontSize:"15px",fontWeight:"bold"}},e.price)),i==="below"&&window.wp.element.createElement("div",{className:"mc-atc-btn"},"Add to Cart"))))),g==="load-more"&&window.wp.element.createElement("div",{style:{textAlign:"center",marginTop:"40px"}},window.wp.element.createElement("button",{style:{padding:"14px 40px",background:"transparent",border:"2px solid #cbd5e1",borderRadius:"50px",fontSize:"14px",fontWeight:"bold",cursor:"pointer"}},"Load More Products"))))}window.wp.blocks.registerBlockType(y,{apiVersion:3,edit:b,save:()=>null})})();
