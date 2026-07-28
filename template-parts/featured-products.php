<?php
/**
 * Template Part: Featured Products
 * Displays a grid of "featured" WooCommerce products.
 *
 * @package Velvet_Vogue_Fashion_Store
 */

if ( ! class_exists( 'WooCommerce' ) ) return;

$products = wc_get_products( array(
    'limit'    => 4,
    'status'   => 'publish',
    'featured' => true,
    'orderby'  => 'date',
    'order'    => 'DESC',
) );

// Fallback if no featured products — get latest 4
if ( empty( $products ) ) {
    $products = wc_get_products( array(
        'limit'    => 4,
        'status'   => 'publish',
        'orderby'  => 'date',
        'order'    => 'DESC',
    ) );
}

if ( empty( $products ) ) return;

$shop_url = wc_get_page_permalink( 'shop' );
?>

<section class="py-20 md:py-28 bg-vvfs-bg w-full border-t border-white/10">
  <div class="w-full px-6 sm:px-10 lg:px-16">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
      <div>
        <span class="text-xs font-bold uppercase tracking-[0.3em] text-rose-500"><?php esc_html_e( 'Curated for You', 'velvet-vogue-fashion-store' ); ?></span>
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mt-3 text-white font-serif"><?php esc_html_e( 'Featured Pieces', 'velvet-vogue-fashion-store' ); ?></h2>
      </div>
      <?php if ( $shop_url ) : ?>
      <a href="<?php echo esc_url( $shop_url ); ?>" class="inline-flex items-center gap-2 text-rose-400 hover:text-rose-300 font-semibold text-sm transition duration-300">
        <?php esc_html_e( 'View All Products', 'velvet-vogue-fashion-store' ); ?> <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
      <?php endif; ?>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
      <?php foreach ( $products as $product ) :
        $img_id  = $product->get_image_id();
        $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'vvfs-card' ) : wc_placeholder_img_src( 'vvfs-card' );
        $on_sale = $product->is_on_sale();
        $rating  = $product->get_average_rating();
        $count   = $product->get_rating_count();
        $link    = get_permalink( $product->get_id() );
      ?>
      <div class="product-card rounded-2xl p-5 md:p-6">
        <div class="relative overflow-hidden rounded-xl bg-zinc-900">
          <a href="<?php echo esc_url( $link ); ?>">
            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"
                 class="w-full h-72 md:h-80 object-cover" loading="lazy" />
          </a>
          <?php if ( $on_sale ) : ?>
            <span class="absolute top-4 left-4 bg-amber-500 text-black text-[0.65rem] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest shadow-md">
              <?php esc_html_e( 'Sale', 'velvet-vogue-fashion-store' ); ?>
            </span>
          <?php elseif ( $product->is_featured() ) : ?>
            <span class="absolute top-4 left-4 bg-purple-600 text-white text-[0.65rem] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-md">
              <?php esc_html_e( 'Featured', 'velvet-vogue-fashion-store' ); ?>
            </span>
          <?php endif; ?>
        </div>
        <div class="mt-5">
          <h3 class="text-base font-semibold text-white font-serif tracking-wide">
            <a href="<?php echo esc_url( $link ); ?>" class="hover:text-rose-400 transition">
              <?php echo esc_html( $product->get_name() ); ?>
            </a>
          </h3>
          <?php if ( $count > 0 ) : ?>
          <div class="flex items-center gap-1 mt-2">
            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
              <?php if ( $i <= $rating ) : ?>
                <span class="star"><i class="fa-solid fa-star text-xs"></i></span>
              <?php else : ?>
                <span class="star-empty"><i class="fa-solid fa-star text-xs"></i></span>
              <?php endif; ?>
            <?php endfor; ?>
            <span class="text-xs text-zinc-400 ml-2 font-medium">(<?php echo esc_html( $count ); ?>)</span>
          </div>
          <?php endif; ?>
          <div class="flex items-center justify-between mt-4">
            <div>
              <span class="text-xl font-bold text-white"><?php echo $product->get_price_html(); ?></span>
            </div>
            <?php if ( $product->is_type( 'simple' ) && $product->is_in_stock() ) : ?>
              <button class="btn-cart text-xs px-4 py-2.5 add_to_cart_button ajax_add_to_cart"
                      data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                      data-quantity="1">
                <i class="fa-solid fa-bag-shopping"></i> <?php esc_html_e( 'Add', 'velvet-vogue-fashion-store' ); ?>
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
