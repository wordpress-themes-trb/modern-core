<?php
$width_mode  = $attributes['widthMode']      ?? 'contained';
if ( ! class_exists( 'WooCommerce' ) ) return;

$source_mode = $attributes['sourceMode']     ?? 'dynamic';
$static_ids  = $attributes['staticProducts'] ?? '';
$max_cols    = $attributes['maxColumns']     ?? 4;
$label_w     = $attributes['labelWidth']     ?? 20;
$sticky      = $attributes['isSticky']       ?? true;
$alt_rows    = $attributes['alternateRows']  ?? true;
$show_img    = $attributes['showImage']      ?? true;
$show_price  = $attributes['showPrice']      ?? true;
$show_rating = $attributes['showRating']     ?? true;
$show_atc    = $attributes['showAtc']        ?? true;
$ratio       = $attributes['imageRatio']     ?? '1/1';
$radius      = $attributes['tableRadius']    ?? 12;
$head_bg     = $attributes['headerBg']       ?? '#ffffff';
$row_odd     = $attributes['rowOddBg']       ?? '#ffffff';
$row_even    = $attributes['rowEvenBg']      ?? '#f8fafc';
$border_c    = $attributes['borderColor']    ?? '#e2e8f0';
$accent      = $attributes['accentColor']    ?? '#3b82f6';

$uid = 'mc-compare-' . uniqid();

// ── Product Query ─────────────────────────────────────────────────────────────
$product_ids = [];
if ( $source_mode === 'static' && ! empty( $static_ids ) ) {
    $product_ids = array_map( 'intval', explode( ',', $static_ids ) );
} else {
    // The visitor's own list, saved by the "Compare" buttons (assets/src/js/main.js writes this cookie).
    $raw         = isset( $_COOKIE['mc_compare'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mc_compare'] ) ) : '';
    $product_ids = array_values( array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );
    // This page differs per visitor, so page caches must not store it.
    if ( ! is_admin() && ! defined( 'DONOTCACHEPAGE' ) ) {
        define( 'DONOTCACHEPAGE', true );
    }
    nocache_headers();
}

// Only published, visible products.
$product_ids = array_values( array_filter( $product_ids, static function ( $id ) {
    $p = wc_get_product( $id );
    return $p && 'publish' === $p->get_status() && $p->is_visible();
} ) );

if (empty($product_ids)) {
    echo '<div class="mc-compare-empty" style="padding: 48px 20px; text-align: center; background: #f8fafc; border-radius: 12px;">'
        . '<h3 style="margin: 0 0 8px;">' . esc_html__( 'Nothing to compare yet', 'modern-core' ) . '</h3>'
        . '<p style="margin: 0 0 20px; opacity: .7;">' . esc_html__( 'Tap "Compare" on up to 4 products to see them side by side here.', 'modern-core' ) . '</p>'
        . '<a class="button" href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">' . esc_html__( 'Browse products', 'modern-core' ) . '</a></div>';
    return;
}
$product_ids = array_slice( $product_ids, 0, $max_cols );
$products    = array_filter( array_map( 'wc_get_product', $product_ids ) );
if ( empty( $products ) ) return;

// Collect all unique attribute slugs across products
$all_attributes = [];
foreach ( $products as $p ) {
    foreach ( $p->get_attributes() as $slug => $attr ) {
        if ( ! isset( $all_attributes[ $slug ] ) ) {
            $all_attributes[ $slug ] = wc_attribute_label( $slug );
        }
    }
}

$wrapper_attributes = get_block_wrapper_attributes( [
    'class' => "mc-block width-{$width_mode} modern-core-compare-table {$uid}",
] );
?>

<div <?php echo $wrapper_attributes; ?>>
<style>
/* ── OUTER WRAPPER ──────────────────────────────────────────
   FIX: overflow-x on the WRAPPER not on the table itself.
   Sticky needs no overflow:hidden/auto on any ancestor,
   so we separate the scroll container from the sticky target.
──────────────────────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> {
    width:      100%;
    box-sizing: border-box;
    overflow-x: auto;                  /* scroll lives HERE         */
    -webkit-overflow-scrolling: touch;
    border:        1px solid <?php echo esc_attr($border_c); ?>;
    border-radius: <?php echo esc_attr($radius); ?>px;
    box-shadow:    0 10px 30px rgba(0,0,0,0.05);
    background:    #fff;
    position:      relative;
}

/* ── INNER TABLE — no overflow so sticky works ────────────── */
.<?php echo esc_attr($uid); ?> .mc-cmp-table {
    min-width:  700px;     /* forces scroll on mobile               */
    width:      100%;
    table-layout: fixed;   /* FIX: equal columns regardless of content */
    border-collapse: collapse;
}

/* ── COLUMN WIDTHS ────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-cmp-table colgroup .col-label {
    width: <?php echo esc_attr($label_w); ?>%;
}
/* Product columns split the remaining width equally */

/* ── STICKY HEADER ────────────────────────────────────────── */
/*
 * FIX: sticky works now because overflow-x is on the outer
 * wrapper div, NOT on this table or any ancestor of thead.
 * top: 0 sticks to the nearest scroll container that has
 * position — which is the page, not the wrapper.
 */
<?php if ( $sticky ) : ?>
.<?php echo esc_attr($uid); ?> thead th {
    position: sticky;
    top:       0;
    z-index:   20;
}
<?php endif; ?>

/* ── TH / TD BASE ─────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> th,
.<?php echo esc_attr($uid); ?> td {
    padding:       0;
    border-right:  1px solid <?php echo esc_attr($border_c); ?>;
    border-bottom: 1px solid <?php echo esc_attr($border_c); ?>;
    vertical-align: top;
    box-sizing:    border-box;
}
.<?php echo esc_attr($uid); ?> th:last-child,
.<?php echo esc_attr($uid); ?> td:last-child {
    border-right: none;
}
.<?php echo esc_attr($uid); ?> tr:last-child td {
    border-bottom: none;
}

/* ── HEADER ROW ───────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> thead tr {
    background: <?php echo esc_attr($head_bg); ?>;
    border-bottom: 2px solid <?php echo esc_attr($border_c); ?>;
}
<?php if ( $sticky ) : ?>
.<?php echo esc_attr($uid); ?> thead tr {
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
<?php endif; ?>

/* ── HEADER LABEL CELL (top-left) ─────────────────────────── */
.<?php echo esc_attr($uid); ?> thead .mc-cmp-label-cell {
    background: <?php echo esc_attr($head_bg); ?>;
    <?php if ( $sticky ) : ?>
    position: sticky;
    left:     0;
    z-index:  30;   /* above both row sticky and col sticky */
    <?php endif; ?>
}

/* ── PRODUCT HEADER CELL ──────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-cmp-product-cell {
    padding:        20px 15px;
    text-align:     center;
    vertical-align: top;
    background:     <?php echo esc_attr($head_bg); ?>;
    position:       relative;
}

/* FIX: uniform card height — all product cells use flex column
   so content stacks consistently regardless of title length  */
.<?php echo esc_attr($uid); ?> .mc-cmp-product-inner {
    display:        flex;
    flex-direction: column;
    align-items:    center;
    height:         100%;
    min-height:     280px;  /* consistent minimum card height     */
}

/* Title: fixed 2-line clamp so long titles don't push ATC down */
.<?php echo esc_attr($uid); ?> .mc-cmp-title {
    font-size:         14px;
    font-weight:       700;
    line-height:       1.35;
    margin:            0 0 8px;
    text-decoration:   none;
    color:             inherit;
    display:           -webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:          hidden;
    width:             100%;
    text-align:        center;
}

/* Image */
.<?php echo esc_attr($uid); ?> .mc-cmp-img {
    width:         100%;
    max-width:     130px;
    aspect-ratio:  <?php echo esc_attr($ratio); ?>;
    object-fit:    cover;
    border-radius: 6px;
    margin-bottom: 12px;
    display:       block;
}

/* Rating */
.<?php echo esc_attr($uid); ?> .mc-cmp-rating {
    color:         #f59e0b;
    font-size:     12px;
    margin-bottom: 8px;
    width:         100%;
    text-align:    center;
}
.<?php echo esc_attr($uid); ?> .mc-cmp-rating .star-rating { margin: 0 auto; }

/* Price — pushed to bottom with margin-top: auto */
.<?php echo esc_attr($uid); ?> .mc-cmp-price {
    font-weight:   700;
    font-size:     16px;
    margin:        auto 0 12px;   /* auto top pushes it toward ATC */
    width:         100%;
    text-align:    center;
}

/* ATC button — always at bottom */
.<?php echo esc_attr($uid); ?> .mc-cmp-atc,
.<?php echo esc_attr($uid); ?> .ajax_add_to_cart {
    display:       block;
    width:         100%;
    padding:       10px 12px;
    background:    <?php echo esc_attr($accent); ?>;
    color:         #fff;
    text-align:    center;
    font-size:     12px;
    font-weight:   700;
    border-radius: 4px;
    text-decoration:none;
    border:        none;
    cursor:        pointer;
    box-sizing:    border-box;
    margin-top:    auto;
}

/* Remove button */
.<?php echo esc_attr($uid); ?> .mc-cmp-remove {
    position:   absolute;
    top:        8px;
    right:      8px;
    font-size:  14px;
    opacity:    0.3;
    cursor:     pointer;
    border:     none;
    background: none;
    padding:    4px;
    line-height:1;
    color:      inherit;
    z-index:    2;
}
.<?php echo esc_attr($uid); ?> .mc-cmp-remove:hover { opacity: 1; color: #ef4444; }

/* ── ROW LABEL COLUMN ─────────────────────────────────────── */
/*
 * The row label is the left column of every data row — it shows
 * the WooCommerce attribute name (e.g. "Color", "Material").
 * FIX: sticky + solid background so it never goes transparent
 * over product cells when scrolling horizontally on mobile.
 */
.<?php echo esc_attr($uid); ?> td.mc-cmp-label {
    padding:     16px 20px;
    font-weight: 600;
    font-size:   13px;
    color:       #64748b;
    white-space: nowrap;
    position:    sticky;
    left:        0;
    z-index:     10;
    /* FIX: explicit solid background — no more white ghost overlay */
    background:  inherit;
}
/* Odd rows need the background set explicitly on the sticky cell
   because 'background: inherit' doesn't work across stacking contexts */
<?php if ( $alt_rows ) : ?>
.<?php echo esc_attr($uid); ?> tr.row-odd  td.mc-cmp-label { background: <?php echo esc_attr($row_odd); ?>; }
.<?php echo esc_attr($uid); ?> tr.row-even td.mc-cmp-label { background: <?php echo esc_attr($row_even); ?>; }
<?php endif; ?>

/* ── DATA ROWS ────────────────────────────────────────────── */
<?php if ( $alt_rows ) : ?>
.<?php echo esc_attr($uid); ?> tr.row-odd  { background: <?php echo esc_attr($row_odd); ?>; }
.<?php echo esc_attr($uid); ?> tr.row-even { background: <?php echo esc_attr($row_even); ?>; }
<?php endif; ?>

.<?php echo esc_attr($uid); ?> td.mc-cmp-cell {
    padding:        16px 20px;
    font-size:      14px;
    text-align:     center;
    vertical-align: middle;
}

/* Boolean indicators */
.<?php echo esc_attr($uid); ?> .mc-yes { color: #10b981; font-weight: 700; font-size: 16px; }
.<?php echo esc_attr($uid); ?> .mc-no  { color: #ef4444; font-weight: 700; font-size: 16px; }
.<?php echo esc_attr($uid); ?> .mc-na  { opacity: 0.25; }

/* ── MOBILE ───────────────────────────────────────────────── */
@media (max-width: 768px) {
    .<?php echo esc_attr($uid); ?> .mc-cmp-product-inner { min-height: 220px; }
    .<?php echo esc_attr($uid); ?> .mc-cmp-img           { max-width: 90px; }
    .<?php echo esc_attr($uid); ?> .mc-cmp-product-cell  { padding: 12px 8px; }
    .<?php echo esc_attr($uid); ?> td.mc-cmp-label       { padding: 12px 14px; font-size: 12px; }
    .<?php echo esc_attr($uid); ?> td.mc-cmp-cell        { padding: 12px 8px; font-size: 13px; }
}
</style>

    <table class="mc-cmp-table" role="table">
        <colgroup>
            <col class="col-label">
            <?php foreach ( $products as $p ) : ?>
                <col>
            <?php endforeach; ?>
        </colgroup>

        <!-- ── STICKY HEADER ROW ─────────────────────────────── -->
        <thead>
            <tr>
                <th class="mc-cmp-label-cell"></th>
                <?php foreach ( $products as $p ) :
                    $img_url = modern_core_product_image_url( $p, 'woocommerce_thumbnail' );
                ?>
                    <th class="mc-cmp-product-cell">
                        <?php if ( $source_mode === 'dynamic' ) : ?>
                            <button type="button" class="mc-cmp-remove" data-mc-compare="<?php echo (int) $p->get_id(); ?>" data-mc-reload aria-label="<?php echo esc_attr( sprintf( /* translators: %s: product name */ __( 'Remove %s from compare', 'modern-core' ), $p->get_name() ) ); ?>">&#10005;</button>
                        <?php endif; ?>

                        <div class="mc-cmp-product-inner">
                            <?php if ( $show_img ) : ?>
                                <a href="<?php echo esc_url( $p->get_permalink() ); ?>">
                                    <img
                                        src="<?php echo esc_url( $img_url ); ?>"
                                        alt="<?php echo esc_attr( $p->get_name() ); ?>"
                                        class="mc-cmp-img"
                                        loading="lazy"
                                    />
                                </a>
                            <?php endif; ?>

                            <a href="<?php echo esc_url( $p->get_permalink() ); ?>" class="mc-cmp-title">
                                <?php echo esc_html( $p->get_name() ); ?>
                            </a>

                            <?php if ( $show_rating && $p->get_rating_count() > 0 ) : ?>
                                <div class="mc-cmp-rating">
                                    <?php echo wp_kses_post( wc_get_rating_html( (float) $p->get_average_rating() ) ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $show_price ) : ?>
                                <div class="mc-cmp-price"><?php echo wp_kses_post( $p->get_price_html() ); ?></div>
                            <?php endif; ?>

                            <?php if ( $show_atc ) :
                                global $post;
                                $post = get_post( $p->get_id() );
                                setup_postdata( $post );
                                woocommerce_template_loop_add_to_cart();
                                wp_reset_postdata();
                            endif; ?>
                        </div>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <!-- ── ATTRIBUTE ROWS ──────────────────────────────────── -->
        <tbody>
            <?php
            $row_index = 0;
            foreach ( $all_attributes as $slug => $label ) :
                $row_class = $alt_rows ? ( $row_index % 2 === 0 ? 'row-odd' : 'row-even' ) : '';
                $row_index++;
            ?>
                <tr class="<?php echo esc_attr( $row_class ); ?>">
                    <td class="mc-cmp-label"><?php echo esc_html( $label ); ?></td>
                    <?php foreach ( $products as $p ) :
                        $val = $p->get_attribute( $slug );
                    ?>
                        <td class="mc-cmp-cell">
                            <?php if ( $val === '' || $val === null ) : ?>
                                <span class="mc-na">—</span>
                            <?php else : ?>
                                <?php echo esc_html( $val ); ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>