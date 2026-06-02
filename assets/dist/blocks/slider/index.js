(()=>{const c={$schema:"https://schemas.wp.org/trunk/block.json",apiVersion:3,name:"modern-core/slider",title:"Advanced Slider",category:"modern-core",icon:"slides",description:"Cinematic multi-slide storytelling engine.",supports:{align:["full","wide"],html:!1},attributes:{minHeight:{type:"number",default:80},transition:{type:"string",default:"fade"},autoplay:{type:"boolean",default:!0},delay:{type:"number",default:5e3},showArrows:{type:"boolean",default:!0},showDots:{type:"boolean",default:!0}},allowedBlocks:["modern-core/slide"],textdomain:"modern-core",editorScript:"modern-core-slider-js",render:"file:./render.php"};function s({attributes:p,setAttributes:l,clientId:i}){const{minHeight:m,transition:u,autoplay:r,delay:g,showArrows:k,showDots:h}=p,[n,d]=window.wp.element.useState(0),{innerBlocks:t,selectedBlockId:w}=window.wp.data.useSelect(e=>{var a;const{getBlock:o,getSelectedBlockClientId:x}=e("core/block-editor");return{innerBlocks:((a=o(i))==null?void 0:a.innerBlocks)||[],selectedBlockId:x()}},[i]),{insertBlock:f,removeBlock:b,selectBlock:y}=window.wp.data.useDispatch("core/block-editor");window.wp.element.useEffect(()=>{const e=t.findIndex(o=>o.clientId===w);e>-1&&e!==n&&d(e)},[w,t]);const E=()=>{const e=window.wp.blocks.createBlock("modern-core/slide");f(e,t.length,i),d(t.length)},_=(e,o)=>{b(o),n>=t.length-1&&d(Math.max(0,t.length-2))},B=(e,o)=>{d(e),y(o)},S=window.wp.blockEditor.useBlockProps({className:`modern-core-slider-editor slider-${i}`,style:{width:"100%",position:"relative",backgroundColor:"#f8fafc",boxSizing:"border-box"}}),v=[["modern-core/slide",{}],["modern-core/slide",{}],["modern-core/slide",{}]];return window.wp.element.createElement(window.wp.element.Fragment,null,window.wp.element.createElement(window.wp.blockEditor.InspectorControls,null,window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Slide Manager","modern-core")},window.wp.element.createElement("p",{style:{fontSize:"12px",opacity:.6,marginBottom:"15px"}},"Select a slide to isolate it on the canvas and edit its content."),t.map((e,o)=>window.wp.element.createElement("div",{key:e.clientId,style:{display:"flex",justifyContent:"space-between",alignItems:"center",marginBottom:"8px",padding:"8px 12px",background:n===o?"#e2e8f0":"#ffffff",border:"1px solid",borderColor:n===o?"#94a3b8":"#e2e8f0",borderRadius:"6px"}},window.wp.element.createElement(window.wp.components.Button,{variant:"link",onClick:()=>B(o,e.clientId),style:{fontWeight:n===o?"bold":"normal",textDecoration:"none",color:n===o?"#0f172a":"#64748b"}},window.wp.i18n.__(`Slide ${o+1}`)),window.wp.element.createElement(window.wp.components.Button,{isDestructive:!0,variant:"link",onClick:()=>_(o,e.clientId),style:{textDecoration:"none"}},window.wp.i18n.__("Delete")))),window.wp.element.createElement(window.wp.components.Button,{variant:"secondary",onClick:E,style:{width:"100%",justifyContent:"center",marginTop:"15px"}},window.wp.i18n.__("+ Add New Slide"))),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Slider Physics","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.SelectControl,{label:window.wp.i18n.__("Transition Style","modern-core"),value:u,options:[{label:"Cinematic Fade",value:"fade"},{label:"Horizontal Slide",value:"slide"}],onChange:e=>l({transition:e})}),window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Height (vh)","modern-core"),value:m,onChange:e=>l({minHeight:e}),min:40,max:100})),window.wp.element.createElement(window.wp.components.PanelBody,{title:window.wp.i18n.__("Autoplay & Navigation","modern-core"),initialOpen:!1},window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Enable Autoplay","modern-core"),checked:r,onChange:e=>l({autoplay:e})}),r&&window.wp.element.createElement(window.wp.components.RangeControl,{label:window.wp.i18n.__("Slide Duration (ms)","modern-core"),value:g,onChange:e=>l({delay:e}),min:2e3,max:1e4,step:500}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Navigation Arrows","modern-core"),checked:k,onChange:e=>l({showArrows:e})}),window.wp.element.createElement(window.wp.components.ToggleControl,{label:window.wp.i18n.__("Show Dot Indicators","modern-core"),checked:h,onChange:e=>l({showDots:e})}))),window.wp.element.createElement("div",{...S},window.wp.element.createElement("style",null,`
            /* THE ISOLATION FIX */
            /* We completely hide all inner blocks inside this specific slider */
            .slider-${i} .block-editor-block-list__layout > .wp-block {
                display: none !important;
            }
            
            /* We ONLY show the block that corresponds to the activeSlide index */
            .slider-${i} .block-editor-block-list__layout > .wp-block:nth-child(${n+1}) {
                display: block !important;
                animation: mcFadeIn 0.3s ease;
            }
            
            @keyframes mcFadeIn { from { opacity: 0; } to { opacity: 1; } }

            /* Editor visual indicators */
            .slider-${i}::after {
                content: 'Slide ${n+1} of ${t.length}';
                position: absolute;
                top: 10px;
                left: 10px;
                background: rgba(0,0,0,0.5);
                color: #fff;
                padding: 4px 10px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: bold;
                pointer-events: none;
                z-index: 99;
            }
          `),window.wp.element.createElement(window.wp.blockEditor.InnerBlocks,{allowedBlocks:["modern-core/slide"],template:v})))}window.wp.blocks.registerBlockType(c,{apiVersion:3,edit:s,save:()=>window.wp.element.createElement(window.wp.blockEditor.InnerBlocks.Content,null)})})();
