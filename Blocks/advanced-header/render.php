<?php
$is_sticky = $attributes['isSticky'] ?? false;
$classes   = 'modern-core-header-root alignfull';

if ( $is_sticky ) {
    $classes .= ' is-sticky';
}

$wrapper_attributes = get_block_wrapper_attributes( [
    'class' => $classes,
] );
?>

<header <?php echo $wrapper_attributes; ?>>
    <style>
        /* Base Container Styles */
        .modern-core-header-root {
            position: relative;
            z-index: 999;
            background: #ffffff;
            width: 100%;
            border-bottom: 1px solid #f3f4f6;
            box-sizing: border-box;
        }
        
        .modern-core-header-root.is-sticky {
            position: sticky;
            top: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* * ==========================================
         * DESKTOP LAYOUT
         * ========================================== */
        .modern-core-header-root .mc-header-main-bar {
            display: grid !important;
            grid-template-columns: 1fr minmax(200px, 600px) 1fr; 
            gap: 30px;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px 20px; 
            box-sizing: border-box;
            width: 100%;
            position: relative !important; /* Crucial anchor for mobile logo */
        }

        /* Left Column: Hamburger + Logo */
        .modern-core-header-root .mc-header-col-left {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 20px !important;
            flex-wrap: nowrap !important;
            min-width: 0 !important;
        }

        /* Center Column: Search Bar Container */
        .modern-core-header-root .mc-header-col-center {
            display: flex !important;
            justify-content: center !important;
            width: 100% !important;
            min-width: 0 !important;
        }
        
        /* STRICTLY layout wrapper only. Zero styling changes to the actual input/button */
        .modern-core-header-root .mc-header-col-center .modern-core-search,
        .modern-core-header-root .mc-header-col-center .wp-block-search {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            min-width: 0 !important; /* This alone prevents the flex container blowout */
        }

        /* Right Column: Icons */
        .modern-core-header-root .mc-header-col-right {
            display: flex !important;
            justify-content: flex-end !important;
            align-items: center !important;
            min-width: 0 !important;
        }

        /* * ==========================================
         * MOBILE LAYOUT 
         * ========================================== */
        @media (max-width: 992px) {
            .modern-core-header-root .mc-header-main-bar {
                display: flex !important;
                flex-wrap: nowrap !important; 
                justify-content: space-between !important;
                align-items: center !important;
                padding: 12px 15px !important;
            }

            .modern-core-header-root .mc-header-col-center {
                display: none !important; 
            }

            .modern-core-header-root .mc-header-col-left {
                flex: 0 0 auto !important;
                position: static !important; /* CRITICAL: Allows absolute logo to escape this column */
            }

            /* Anchor the logo to the main bar, not the left column */
            .modern-core-header-root .mc-header-col-left .modern-core-logo,
            .modern-core-header-root .mc-header-col-left .wp-block-site-logo {
                position: absolute !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                margin: 0 !important;
                width: auto !important;
                max-width: 50vw !important; /* Safe clamp, won't overlap edge icons */
                z-index: 10;
            }

            .modern-core-header-root .mc-header-col-right {
                flex: 0 0 auto !important;
            }
        }
    </style>
    
    <?php echo $content; ?>
</header>