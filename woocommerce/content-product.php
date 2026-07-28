<?php
/**
 * Content Product — div-based card matching product_listing.html
 */
defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

$categories = get_the_terms( $product->get_id(), 'product_cat' );
$cat_name = '';
$cat_slugs = array();
if ( $categories && ! is_wp_error( $categories ) ) {
    foreach ( $categories as $cat ) {
        $cat_slugs[] = $cat->slug;
        if ( $cat->parent === 0 ) {
            $cat_name = $cat->name;
            break;
        }
    }
    if ( ! $cat_name ) {
        $cat_name = $categories[0]->name;
    }
}

$price = (float) $product->get_price();

$sizes_arr = array();
$size_attr = $product->get_attribute( 'pa_size' );
if ( ! $size_attr ) $size_attr = $product->get_attribute( 'size' );
if ( $size_attr ) {
    $size_terms = get_the_terms( $product->get_id(), 'pa_size' );
    if ( ! $size_terms || is_wp_error( $size_terms ) ) {
        $size_terms = get_the_terms( $product->get_id(), 'size' );
    }
    if ( $size_terms && ! is_wp_error( $size_terms ) ) {
        foreach ( $size_terms as $term ) {
            $sizes_arr[] = strtoupper( $term->name );
        }
    } elseif ( is_string( $size_attr ) ) {
        $sizes_arr = array_map( 'trim', explode( ',', $size_attr ) );
    }
}

$color_name = '';
$color_attr = $product->get_attribute( 'pa_color' );
if ( ! $color_attr ) $color_attr = $product->get_attribute( 'color' );
if ( $color_attr ) {
    $color_terms = get_the_terms( $product->get_id(), 'pa_color' );
    if ( ! $color_terms || is_wp_error( $color_terms ) ) {
        $color_terms = get_the_terms( $product->get_id(), 'color' );
    }
    if ( $color_terms && ! is_wp_error( $color_terms ) ) {
        $color_name = strtolower( $color_terms[0]->name );
    } elseif ( is_string( $color_attr ) ) {
        $color_name = strtolower( trim( $color_attr ) );
    }
}
?>
<div <?php wc_product_class( 'vvfs-product-card', $product ); ?>
     data-price="<?php echo esc_attr( $price ); ?>"
     data-sizes="<?php echo esc_attr( implode( ',', $sizes_arr ) ); ?>"
     data-color="<?php echo esc_attr( $color_name ); ?>"
     data-category="<?php echo esc_attr( implode( ' ', $cat_slugs ) ); ?>">
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<div class="vvfs-product-img-wrap">
		<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
	</div>

	<div class="vvfs-product-info">
		<?php if ( $cat_name ) : ?>
			<span class="vvfs-product-cat"><?php echo esc_html( $cat_name ); ?></span>
		<?php endif; ?>

		<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>

		<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>

		<div class="vvfs-product-bottom">
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>
	</div>
</div>
