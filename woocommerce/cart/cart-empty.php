<?php
/**
 * Cart Empty Template — Velvet Vogue Fashion Store
 *
 * @package Velvet_Vogue_Fashion_Store
 * @see     https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/templates/cart/cart-empty.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );
?>

<section class="py-10 md:py-20 bg-vvfs-bg w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16">
    <div class="max-w-2xl mx-auto text-center">

      <div class="mb-8">
        <div class="w-24 h-24 rounded-full bg-zinc-800 border border-white/10 flex items-center justify-center mx-auto">
          <i class="fa-solid fa-bag-shopping text-zinc-500 text-3xl"></i>
        </div>
      </div>

      <h1 class="text-3xl sm:text-4xl font-bold text-white font-serif mb-4"><?php esc_html_e( 'Your cart is currently empty', 'velvet-vogue-fashion-store' ); ?></h1>

      <p class="text-zinc-400 text-lg font-light mb-8"><?php esc_html_e( 'Looks like you haven\'t added anything yet. Let\'s find something you\'ll love.', 'velvet-vogue-fashion-store' ); ?></p>

      <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-cart px-8 py-3.5 text-sm">
        <i class="fa-solid fa-bag-shopping"></i> <?php esc_html_e( 'Start Shopping', 'velvet-vogue-fashion-store' ); ?>
      </a>

      <?php if ( wc_get_page_id( 'myaccount' ) > 0 ) : ?>
        <p class="text-zinc-500 text-sm mt-8 font-light">
      <?php
      printf(
          esc_html__( 'or %sreturn to shop%s', 'velvet-vogue-fashion-store' ),
          '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="text-rose-500 hover:text-rose-400 transition">',
          '</a>'
      );
      ?>
        </p>
      <?php endif; ?>

    </div>
  </div>
</section>
