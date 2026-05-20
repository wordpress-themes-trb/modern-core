(()=>{const c={$schema:"https://schemas.wp.org/trunk/block.json",apiVersion:3,name:"modern-core/navigation",title:"Advanced Navigation",category:"modern-core",icon:"menu",description:"Customizable horizontal navigation menu with hover effects.",attributes:{linkColor:{type:"string",default:"#0f172a"},hoverColor:{type:"string",default:"#3b82f6"},hoverEffect:{type:"string",default:"underline"},fontSize:{type:"number",default:15},fontWeight:{type:"string",default:"500"},itemSpacing:{type:"number",default:32}},textdomain:"modern-core",editorScript:"modern-core-navigation-js",render:"file:./render.php"};function m({attributes:p,setAttributes:o,clientId:e}){const{linkColor:r,hoverColor:t,hoverEffect:i,fontSize:a,fontWeight:l,itemSpacing:w}=p,d=window.wp.blockEditor.useBlockProps({className:`modern-core-nav-block modern-core-nav-${e}`,style:{width:"100%",display:"flex",justifyContent:"center"}});return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Typography & Spacing","modern-core")},window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Font Size (px)","modern-core"),value:a,onChange:n=>o({fontSize:n}),min:12,max:24}),window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Font Weight","modern-core"),value:l,options:[{label:"Normal (400)",value:"400"},{label:"Medium (500)",value:"500"},{label:"Semibold (600)",value:"600"},{label:"Bold (700)",value:"700"}],onChange:n=>o({fontWeight:n})}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Spacing Between Links (px)","modern-core"),value:w,onChange:n=>o({itemSpacing:n}),min:10,max:60})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Colors & Hover Effects","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Hover Effect","modern-core"),value:i,options:[{label:"Animated Underline",value:"underline"},{label:"Color Shift",value:"color"},{label:"None",value:"none"}],onChange:n=>o({hoverEffect:n})}),window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Link Color","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:r,onChange:n=>o({linkColor:n})}),i!=="none"&&window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement("p",{style:{marginTop:"15px",marginBottom:"5px"}},window.wp.element.createElement("strong",null,window.wp.i18n.__("Hover Color","modern-core"))),window.wp.element.createElement(window.wp.components.ColorPalette,{value:t,onChange:n=>o({hoverColor:n})})))),window.wp.element.createElement("style",null,`
                .modern-core-nav-${e} .wp-block-navigation {
                    gap: ${w}px;
                }
                .modern-core-nav-${e} .wp-block-navigation-item__content,
                .modern-core-nav-${e} .wp-block-navigation-item a {
                    color: ${r} !important;
                    font-size: ${a}px !important;
                    font-weight: ${l} !important;
                    position: relative;
                    transition: color 0.3s ease;
                    text-decoration: none !important;
                }
                
                ${i==="color"?`
                    .modern-core-nav-${e} .wp-block-navigation-item__content:hover,
                    .modern-core-nav-${e} .wp-block-navigation-item a:hover {
                        color: ${t} !important;
                    }
                `:""}
                
                ${i==="underline"?`
                    .modern-core-nav-${e} .wp-block-navigation-item {
                        position: relative;
                    }
                    .modern-core-nav-${e} .wp-block-navigation-item::after {
                        content: '';
                        position: absolute;
                        width: 0;
                        height: 2px;
                        bottom: -4px;
                        left: 0;
                        background-color: ${t};
                        transition: width 0.3s ease;
                    }
                    .modern-core-nav-${e} .wp-block-navigation-item:hover .wp-block-navigation-item__content,
                    .modern-core-nav-${e} .wp-block-navigation-item:hover a {
                        color: ${t} !important;
                    }
                    .modern-core-nav-${e} .wp-block-navigation-item:hover::after {
                        width: 100%;
                    }
                `:""}
                `),window.wp.element.createElement("div",{...d},window.wp.element.createElement(window.wp.blockEditor.InnerBlocks,{allowedBlocks:["core/navigation"],template:[["core/navigation",{}]]})))}window.wp.blocks.registerBlockType(c,{apiVersion:3,edit:m,save:()=>window.wp.element.createElement(window.wp.blockEditor.InnerBlocks.Content,null)})})();
