(()=>{const s={$schema:"https://schemas.wp.org/trunk/block.json",apiVersion:3,name:"modern-core/hero-layout",title:"Hero Split / Asymmetric",category:"modern-core",icon:"columns",description:"Editorial layout engine for Split and Asymmetric hero designs.",supports:{html:!1,align:["full","wide"]},attributes:{layoutType:{type:"string",default:"split"},splitRatio:{type:"string",default:"50-50"},dividerStyle:{type:"string",default:"straight"},dividerColor:{type:"string",default:"#0f172a"},mobileStack:{type:"string",default:"content-first"},offsetX:{type:"number",default:10},offsetY:{type:"number",default:15},contentSide:{type:"string",default:"left"}},textdomain:"modern-core",editorScript:"modern-core-hero-layout-js",render:"file:./render.php"};function m({attributes:u,setAttributes:o}){const{layoutType:n,splitRatio:i,dividerStyle:r,dividerColor:g,mobileStack:y,offsetX:a,offsetY:d,contentSide:c}=u,[p,w]=i.split("-").map(e=>`${e}%`),t=c==="left",h=window.wp.blockEditor.useBlockProps({className:`modern-core-hero-layout-editor type-${n}`,style:{display:"flex",flexDirection:"row",width:"100%",minHeight:"500px",position:"relative",overflow:"hidden",border:"2px dashed rgba(255,255,255,0.2)"}}),l=[["core/group",{className:"mc-layout-zone mc-media-zone",style:{backgroundColor:"rgba(0,0,0,0.2)",padding:"20px"}},[["core/paragraph",{content:"📸 Drop Image/Video Here",align:"center"}]]],["core/group",{className:"mc-layout-zone mc-text-zone",style:{padding:"40px"}},[["core/heading",{content:"Editorial Headline"}],["core/paragraph",{content:"Secondary description text goes here."}],["core/buttons",{},[["core/button",{text:"Shop Now"}]]]]]];return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Architecture","modern-core")},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Layout Type","modern-core"),value:n,options:[{label:"Standard Split",value:"split"},{label:"Editorial Asymmetric",value:"asymmetric"}],onChange:e=>o({layoutType:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Content Position","modern-core"),value:c,options:[{label:"Content on Left",value:"left"},{label:"Content on Right",value:"right"}],onChange:e=>o({contentSide:e})}),n==="split"&&window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Split Ratio","modern-core"),value:i,options:[{label:"30 / 70",value:"30-70"},{label:"40 / 60",value:"40-60"},{label:"50 / 50",value:"50-50"},{label:"60 / 40",value:"60-40"},{label:"70 / 30",value:"70-30"}],onChange:e=>o({splitRatio:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Mobile Stacking Order","modern-core"),value:y,options:[{label:"Content on Top",value:"content-first"},{label:"Media on Top",value:"media-first"}],onChange:e=>o({mobileStack:e})}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Divider Style","modern-core"),value:r,options:[{label:"Straight Cut",value:"straight"},{label:"Diagonal Slash",value:"diagonal"},{label:"Soft Wave",value:"wave"}],onChange:e=>o({dividerStyle:e})}),r!=="straight"&&window.wp.element.createElement(window.wp.components.ColorPalette,{value:g,onChange:e=>o({dividerColor:e})})),n==="asymmetric"&&window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("X-Axis Offset (%)","modern-core"),value:a,onChange:e=>o({offsetX:e}),min:-50,max:50}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Y-Axis Offset (%)","modern-core"),value:d,onChange:e=>o({offsetY:e}),min:-50,max:50})))),window.wp.element.createElement("div",{...h},window.wp.element.createElement("style",null,`
                    .modern-core-hero-layout-editor .mc-layout-zone {
                        flex-shrink: 0;
                        transition: all 0.3s ease;
                    }
                    .modern-core-hero-layout-editor.type-split .mc-media-zone {
                        width: ${t?w:p};
                        order: ${t?2:1};
                    }
                    .modern-core-hero-layout-editor.type-split .mc-text-zone {
                        width: ${t?p:w};
                        order: ${t?1:2};
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                    }
                    .modern-core-hero-layout-editor.type-asymmetric .mc-media-zone {
                        width: 100%;
                        position: absolute;
                        inset: 0;
                    }
                    .modern-core-hero-layout-editor.type-asymmetric .mc-text-zone {
                        position: relative;
                        z-index: 10;
                        width: 60%;
                        transform: translate(${a}%, ${d}%);
                        background: rgba(0,0,0,0.4);
                        backdrop-filter: blur(10px);
                    }
                    `),window.wp.element.createElement(window.wp.blockEditor.InnerBlocks,{template:t?[l[1],l[0]]:l,allowedBlocks:["core/group","core/image","core/video","core/heading","core/paragraph","core/buttons","modern-core/countdown","modern-core/trust-strip"]})))}window.wp.blocks.registerBlockType(s,{apiVersion:3,edit:m,save:()=>window.wp.element.createElement(window.wp.blockEditor.InnerBlocks.Content,null)})})();
