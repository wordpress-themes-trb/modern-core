(()=>{const y={$schema:"https://schemas.wp.org/trunk/block.json",apiVersion:3,name:"modern-core/product-grid",title:"Advanced Product Grid",category:"modern-core",icon:"grid-view",description:"High-conversion product grid with dynamic queries and custom card designs.",supports:{align:["wide","full"],html:!1},attributes:{productCount:{type:"number",default:8},querySort:{type:"string",default:"date"},layoutMode:{type:"string",default:"grid"},columnsDesktop:{type:"number",default:4},columnsTablet:{type:"number",default:3},columnsMobile:{type:"number",default:2},imageRatio:{type:"string",default:"3/4"},imageHover:{type:"string",default:"zoom"},cardRadius:{type:"number",default:8},cardBg:{type:"string",default:"#ffffff"},cardHoverLift:{type:"boolean",default:!0},showCategory:{type:"boolean",default:!1},showRating:{type:"boolean",default:!0},saleBadgeStyle:{type:"string",default:"pill"},atcStyle:{type:"string",default:"solid"},atcPosition:{type:"string",default:"below"},showToolbar:{type:"boolean",default:!0},paginationType:{type:"string",default:"load-more"},accentColor:{type:"string",default:"#000000"},inheritQuery:{type:"boolean",default:!0}},textdomain:"modern-core",editorScript:"modern-core-product-grid-js",render:"file:./render.php"};function h({attributes:r,setAttributes:o}){const{productCount:d,querySort:x,layoutMode:w,columnsDesktop:c,columnsMobile:v,imageRatio:p,imageHover:C,cardRadius:l,cardBg:m,cardHoverLift:s,showCategory:E,showRating:g,saleBadgeStyle:_,atcStyle:a,atcPosition:i,showToolbar:u,paginationType:f,accentColor:t}=r,S=window.wp.blockEditor.useBlockProps({className:`modern-core-grid-editor layout-${w} atc-${i}`,style:{width:"100%",maxWidth:"1200px",margin:"0 auto",padding:"40px 5%",boxSizing:"border-box"}}),b=Array.from({length:Math.min(d,8)}).map((e,n)=>({id:n,title:n%2===0?"Premium Cotton Silhouette":"Minimalist Architecture Chair",price:n%3===0?"$120.00":"$85.00",sale:n===1||n===4?"$65.00":null,category:n%2===0?"Apparel":"Furniture",image:"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 750'%3E%3Crect width='600' height='750' fill='#e9e5df'/%3E%3Cpath d='M210 455l70-90 55 70 40-50 65 70z' fill='#cfc8bd'/%3E%3Ccircle cx='380' cy='300' r='34' fill='#cfc8bd'/%3E%3C/svg%3E"}));return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Query Builder","modern-core")},window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Use the page's products on shop pages","modern-core"),help:window.wp.i18n.__("On the shop, category and search pages, show the products for that page so sorting, filters and pagination work. Turn off to always show the list below.","modern-core"),checked:r.inheritQuery,onChange:e=>o({inheritQuery:e})}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Products to Show","modern-core"),value:d,onChange:e=>o({productCount:e}),min:2,max:24}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Sort By","modern-core"),value:x,options:[{label:window.wp.i18n.__("Newest arrivals","modern-core"),value:"date"},{label:window.wp.i18n.__("Best selling","modern-core"),value:"popularity"},{label:window.wp.i18n.__("Top rated","modern-core"),value:"rating"},{label:window.wp.i18n.__("On sale","modern-core"),value:"sale"},{label:window.wp.i18n.__("Featured","modern-core"),value:"featured"},{label:window.wp.i18n.__("Price: high to low","modern-core"),value:"price-desc"},{label:window.wp.i18n.__("Price: low to high","modern-core"),value:"price"}],onChange:e=>o({querySort:e})})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Grid Layout","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Macro Layout","modern-core"),value:w,options:[{label:"Standard Grid",value:"grid"},{label:"List View",value:"list"}],onChange:e=>o({layoutMode:e})}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Columns (Desktop)","modern-core"),value:c,onChange:e=>o({columnsDesktop:e}),min:2,max:6}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Columns (Mobile)","modern-core"),value:v,onChange:e=>o({columnsMobile:e}),min:1,max:3})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Product Card Design","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Image Aspect Ratio","modern-core"),value:p,options:[{label:"Portrait (3:4)",value:"3/4"},{label:"Square (1:1)",value:"1/1"},{label:"Landscape (16:9)",value:"16/9"},{label:"Uncropped (Auto)",value:"auto"}],onChange:e=>o({imageRatio:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Image Hover Effect","modern-core"),value:C,options:[{label:"Cinematic Zoom",value:"zoom"},{label:"Second Image Swap",value:"swap"},{label:"None",value:"none"}],onChange:e=>o({imageHover:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Add to Cart Style","modern-core"),value:a,options:[{label:"Solid Button",value:"solid"},{label:"Outlined Button",value:"outline"},{label:"Text Link",value:"text"}],onChange:e=>o({atcStyle:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Add to Cart Position","modern-core"),value:i,options:[{label:"Below Product Info",value:"below"},{label:"Overlay on Image Hover",value:"overlay"}],onChange:e=>o({atcPosition:e})}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Hover Card Lift/Shadow","modern-core"),checked:s,onChange:e=>o({cardHoverLift:e})}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Star Rating","modern-core"),checked:g,onChange:e=>o({showRating:e})}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Card Border Radius","modern-core"),value:l,onChange:e=>o({cardRadius:e}),min:0,max:24}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Brand Accent Color","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:t,onChange:e=>o({accentColor:e})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Card Background","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:m,onChange:e=>o({cardBg:e})})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Filtering & Pagination","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Top Toolbar (Sort/Filter)","modern-core"),checked:u,onChange:e=>o({showToolbar:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Pagination Style","modern-core"),value:f,options:[{label:"Load More Button",value:"load-more"},{label:"Standard Page Numbers",value:"numbers"},{label:"Infinite Scroll",value:"infinite"}],onChange:e=>o({paginationType:e})}))),window.wp.element.createElement("div",{...S},window.wp.element.createElement("style",null,`
            .modern-core-grid-editor *, .modern-core-grid-editor *::before, .modern-core-grid-editor *::after { box-sizing: border-box; }
            .modern-core-grid-editor {
                --mc-cols: ${c};
                --mc-ratio: ${p};
                --mc-radius: ${l}px;
                --mc-bg: ${m};
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
                border: 1px solid rgba(0,0,0,0.08);
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
                padding: 4px 10px; border-radius: ${_==="pill"?"50px":"4px"}; z-index: 2;
            }
            .mc-atc-btn {
                margin-top: auto; padding: 12px; text-align: center; font-size: 13px; font-weight: bold;
                background: ${a==="solid"?t:"transparent"};
                color: ${a==="solid"?"#fff":t};
                border: ${a==="outline"?`2px solid ${t}`:"none"};
                border-radius: ${l}px;
            }
            .atc-overlay .mc-atc-btn { display: none; }
          `),u&&window.wp.element.createElement("div",{style:{display:"flex",justifyContent:"space-between",marginBottom:"30px",paddingBottom:"15px",borderBottom:"1px solid #e2e8f0",fontSize:"13px"}},window.wp.element.createElement("span",{style:{opacity:.6}},"Showing ",b.length," results"),window.wp.element.createElement("span",{style:{fontWeight:"bold"}},"Sort by: Newest ↓")),window.wp.element.createElement("div",{className:"mc-grid-container"},b.map(e=>window.wp.element.createElement("div",{key:e.id,className:"mc-product-card",style:{boxShadow:s?"0 10px 30px rgba(0,0,0,0.05)":"none"}},window.wp.element.createElement("div",{className:"mc-card-image",style:{backgroundImage:`url(${e.image})`}},e.sale&&window.wp.element.createElement("span",{className:"mc-badge"},"SALE"),i==="overlay"&&window.wp.element.createElement("div",{style:{position:"absolute",bottom:"10px",left:"10px",right:"10px",background:"#fff",color:"#000",padding:"12px",textAlign:"center",borderRadius:l,fontSize:"12px",fontWeight:"bold"}},"+ Quick Add")),window.wp.element.createElement("div",{className:"mc-card-info"},E&&window.wp.element.createElement("span",{style:{fontSize:"11px",textTransform:"uppercase",opacity:.5,marginBottom:"5px"}},e.category),window.wp.element.createElement("h3",{style:{fontSize:"15px",margin:"0 0 10px 0",lineHeight:1.3}},e.title),g&&window.wp.element.createElement("div",{style:{color:"#f59e0b",fontSize:"12px",marginBottom:"10px"}},"★★★★★"," ",window.wp.element.createElement("span",{style:{opacity:.5,color:"#000"}},"(24)")),window.wp.element.createElement("div",{style:{marginTop:"auto",display:"flex",gap:"10px",alignItems:"center",marginBottom:"15px"}},e.sale?window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement("span",{style:{fontSize:"15px",fontWeight:"bold",color:"#ef4444"}},e.sale),window.wp.element.createElement("span",{style:{fontSize:"13px",textDecoration:"line-through",opacity:.5}},e.price)):window.wp.element.createElement("span",{style:{fontSize:"15px",fontWeight:"bold"}},e.price)),i==="below"&&window.wp.element.createElement("div",{className:"mc-atc-btn"},"Add to Cart"))))),f==="load-more"&&window.wp.element.createElement("div",{style:{textAlign:"center",marginTop:"40px"}},window.wp.element.createElement("button",{style:{padding:"14px 40px",background:"transparent",border:"2px solid #cbd5e1",borderRadius:"50px",fontSize:"14px",fontWeight:"bold",cursor:"pointer"}},"Load More Products"))))}window.wp.blocks.registerBlockType(y,{apiVersion:3,edit:h,save:()=>null})})();
