(()=>{const e={name:"modern-core/advanced-header",title:"Header System",icon:"layout",attributes:{isSticky:{type:"boolean",default:!1}}};function o({attributes:r,setAttributes:n}){const{isSticky:t}=r,a=window.wp.blockEditor.useBlockProps({className:`modern-core-header-root ${t?"is-sticky":""}`,style:{background:"#ffffff",width:"100%"}}),i=[["core/group",{className:"mc-header-main-bar",layout:{type:"flex",flexWrap:"nowrap"}},[["core/group",{className:"mc-header-col-left",layout:{type:"flex",flexWrap:"nowrap"}},[["modern-core/hamburger",{iconColor:"#0f172a"}],["modern-core/logo",{brandName:"Modern Core ",alignment:"left"}]]],["core/group",{className:"mc-header-col-center",layout:{type:"flex"}},[["modern-core/search",{width:100,shape:"rounded"}]]],["core/group",{className:"mc-header-col-right",layout:{type:"flex",justifyContent:"right"}},[["modern-core/header-icons",{showSearch:!0,iconColor:"#0f172a"}]]]]]];return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Header Settings","modern-core")},window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Sticky Header","modern-core"),checked:t,onChange:c=>n({isSticky:c})}))),window.wp.element.createElement("header",{...a},window.wp.element.createElement("style",null,`
        /* Editor Desktop Layout */
        .modern-core-header-root .mc-header-main-bar {
            display: grid !important;
            grid-template-columns: 1fr minmax(200px, 600px) 1fr; 
            gap: 30px;
            align-items: center;
            width: 100%;
            position: relative !important;
        }

        .modern-core-header-root .mc-header-col-left {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 20px !important;
            flex-wrap: nowrap !important;
            min-width: 0 !important;
        }

        .modern-core-header-root .mc-header-col-center {
            display: flex !important;
            justify-content: center !important;
            width: 100%;
            min-width: 0 !important;
        }

        /* Prevent blowout without changing inner styles */
        .modern-core-header-root .mc-header-col-center [data-type="modern-core/search"] {
            width: 100% !important;
            min-width: 0 !important;
        }

        .modern-core-header-root .mc-header-col-right {
            display: flex !important;
            justify-content: flex-end !important;
            align-items: center !important;
            min-width: 0 !important;
        }

        /* Editor Mobile Preview overrides */
        @media (max-width: 992px) {
            .modern-core-header-root .mc-header-main-bar {
                display: flex !important;
                flex-wrap: nowrap !important;
                justify-content: space-between !important;
                align-items: center !important;
            }

            .modern-core-header-root .mc-header-col-center {
                display: none !important; 
            }

            .modern-core-header-root .mc-header-col-left {
                flex: 0 0 auto !important;
                position: static !important;
            }

            /* Absolutely center logo inside the main bar */
            .modern-core-header-root .mc-header-col-left [data-type="modern-core/logo"] {
                position: absolute !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                width: auto !important;
                max-width: 50vw !important;
                margin: 0 !important;
                z-index: 10;
            }

            .modern-core-header-root .mc-header-col-right {
                flex: 0 0 auto !important;
            }
        }
    `),window.wp.element.createElement(window.wp.blockEditor.InnerBlocks,{template:i,templateLock:!1})))}window.wp.blocks.registerBlockType(e.name,{apiVersion:3,title:e.title,category:"modern-core",icon:e.icon,attributes:e.attributes,edit:o,save:()=>window.wp.element.createElement(window.wp.blockEditor.InnerBlocks.Content,null)})})();
