<?php
/**
 * Title: Single Product Layout
 * Slug: modern-core/single-product-layout
 * Categories: modern-core-sections
 * Description: Product details, information tabs and related products, as used by the product page template.
 * Viewport Width: 1280
 */
?>

<!-- wp:modern-core/section-wrapper {"wrapperWidth":"full","contentWidth":"contained","paddingTopDesktop":60,"paddingTopMobile":36,"paddingBottomDesktop":60,"paddingBottomMobile":36} -->
<!-- wp:modern-core/single-product {"layoutMode":"standard","splitRatio":"50-50","galleryThumbs":"bottom","imageRatio":"3/4","enableStickyATC":true,"accentColor":"#0f172a"} /-->
<!-- /wp:modern-core/section-wrapper -->

<!-- wp:modern-core/section-wrapper {"wrapperWidth":"full","contentWidth":"contained","paddingTopDesktop":0,"paddingTopMobile":0,"paddingBottomDesktop":60,"paddingBottomMobile":36} -->
<!-- wp:modern-core/content-tabs {"tabStyle":"underline","layoutMode":"horizontal","animStyle":"fade","accentColor":"#0f172a","inactiveColor":"#94a3b8"} -->
<!-- wp:modern-core/tab-pane {"tabTitle":"Description"} -->
<!-- wp:woocommerce/product-description /-->
<!-- /wp:modern-core/tab-pane -->

<!-- wp:modern-core/tab-pane {"tabTitle":"Details","tabIcon":"list"} -->
<!-- wp:woocommerce/product-specifications /-->
<!-- /wp:modern-core/tab-pane -->

<!-- wp:modern-core/tab-pane {"tabTitle":"Reviews","tabIcon":"star"} -->
<!-- wp:modern-core/reviews-summary {"layoutMode":"horizontal","interactiveRows":true} /-->
<!-- /wp:modern-core/tab-pane -->
<!-- /wp:modern-core/content-tabs -->
<!-- /wp:modern-core/section-wrapper -->

<!-- wp:modern-core/section-wrapper {"wrapperWidth":"full","contentWidth":"contained","bgType":"solid","bgColor":"#f8fafc","paddingTopDesktop":60,"paddingTopMobile":36,"paddingBottomDesktop":80,"paddingBottomMobile":48} -->
<!-- wp:modern-core/product-carousel {"sectionTitle":"You Might Also Like","productCount":8,"querySort":"popularity","colsDesktop":4,"showDots":false,"autoplay":false,"accentColor":"#0f172a"} /-->
<!-- /wp:modern-core/section-wrapper -->
