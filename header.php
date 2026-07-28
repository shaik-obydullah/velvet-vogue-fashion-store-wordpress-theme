<?php
/**
 * Header template — Velvet Vogue Fashion Store
 * @package Velvet_Vogue_Fashion_Store
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ============================================================
     NAVIGATION
============================================================ -->
<header class="bg-vvfs-nav sticky top-0 z-50 w-full">
  <div class="w-full px-4 sm:px-6">
    <div class="flex items-center justify-between h-20 md:h-24">

      <!-- Logo -->
      <div class="flex-shrink-0">
        <?php if ( has_custom_logo() ) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-3xl font-extrabold tracking-widest text-white font-serif uppercase">
            VELVET VOGUE<span class="text-rose-500">.</span>
          </a>
        <?php endif; ?>
      </div>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center gap-10 text-sm font-medium tracking-wide">
        <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'flex items-center gap-10',
          'fallback_cb'    => false,
          'depth'          => 1,
          'link_before'    => '',
          'link_after'     => '',
          'items_wrap'     => '%3$s',
        ) );
        ?>
      </nav>

      <!-- Right icons (desktop) -->
      <div class="hidden md:flex items-center gap-6 text-zinc-300">
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
          <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="hover:text-rose-500 transition text-base" aria-label="<?php esc_attr_e( 'Cart', 'velvet-vogue-fashion-store' ); ?>">
            <i class="fa-solid fa-bag-shopping"></i>
          </a>
          <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="hover:text-rose-500 transition text-base" aria-label="<?php esc_attr_e( 'My Account', 'velvet-vogue-fashion-store' ); ?>">
            <i class="fa-solid fa-user"></i>
          </a>
        <?php endif; ?>
      </div>

      <!-- Mobile hamburger -->
      <button id="hamburger" class="md:hidden text-2xl text-zinc-300 hover:text-rose-500 transition" aria-label="<?php esc_attr_e( 'Toggle menu', 'velvet-vogue-fashion-store' ); ?>">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden border-t border-white/15 bg-[#18181b]/98 backdrop-blur-xl shadow-2xl">
      <div class="px-6 py-8 space-y-4 text-lg font-semibold">
        <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => '',
          'fallback_cb'    => false,
          'depth'          => 1,
          'link_before'    => '',
          'link_after'     => '',
          'items_wrap'     => '%3$s',
          'li_class'       => 'block px-4 py-3 rounded-xl bg-white/5 text-white hover:bg-rose-500 hover:text-white transition duration-300',
        ) );
        ?>
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <hr class="my-4 border-white/10" />
        <div class="flex items-center justify-around px-2 py-3 text-base text-white">
          <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="hover:text-rose-500 transition flex flex-col items-center gap-1 text-sm font-normal text-zinc-300">
            <i class="fa-solid fa-bag-shopping text-lg text-rose-500"></i> <?php esc_html_e( 'Cart', 'velvet-vogue-fashion-store' ); ?>
          </a>
          <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="hover:text-rose-500 transition flex flex-col items-center gap-1 text-sm font-normal text-zinc-300">
            <i class="fa-solid fa-user text-lg text-rose-500"></i> <?php esc_html_e( 'Account', 'velvet-vogue-fashion-store' ); ?>
          </a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
