<?php
/**
 * Footer template — Velvet Vogue Fashion Store
 *
 * Data priority:
 * 1. Plugin CPT (if VVFS Core active) — managed via admin UI
 * 2. Customizer settings — theme standalone
 * 3. Hardcoded defaults — final fallback
 *
 * @package Velvet_Vogue_Fashion_Store
 */

$footer_tagline   = '';
$footer_copyright = '';
$footer_social    = array();
$footer_links     = array();

$has_plugin = defined( 'VVFS_CORE_VERSION' );

/* ------------------------------------------------------------------
   1. Plugin CPT override (optional enhancement)
------------------------------------------------------------------ */
if ( $has_plugin ) {
    $footer_id = get_option( 'vvfs_footer_post_id' );
    if ( ! $footer_id ) {
        $footer_query = get_posts( array(
            'post_type'      => 'vvfs_footer',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
        ) );
        $footer_id = ! empty( $footer_query ) ? $footer_query[0] : 0;
    }
    if ( $footer_id ) {
        $footer_tagline   = get_post_meta( $footer_id, 'vvfs_footer_tagline', true );
        $footer_social    = get_post_meta( $footer_id, 'vvfs_footer_social', true );
        $footer_links     = get_post_meta( $footer_id, 'vvfs_footer_links', true );
        $footer_copyright = get_post_meta( $footer_id, 'vvfs_footer_copyright', true );
    }
}

/* ------------------------------------------------------------------
   2. Customizer fallback (theme standalone)
------------------------------------------------------------------ */
if ( empty( $footer_tagline ) ) {
    $footer_tagline = get_theme_mod( 'vvfs_footer_tagline', 'Haute couture & modern luxury for the discerning individual.' );
}
if ( empty( $footer_copyright ) ) {
    $footer_copyright = get_theme_mod( 'vvfs_footer_copyright', 'Velvet Vogue Fashion. All rights reserved.' );
}

// Social — Customizer only used if plugin didn't provide data
if ( empty( $footer_social ) || ! is_array( $footer_social ) ) {
    $footer_social = array();
    $social_keys   = array( 'instagram', 'pinterest', 'youtube', 'tiktok' );
    foreach ( $social_keys as $key ) {
        $val = get_theme_mod( 'vvfs_social_' . $key, '' );
        if ( $val ) {
            $footer_social[ $key ] = $val;
        }
    }
}

// Quick links — Customizer only used if plugin didn't provide data
if ( empty( $footer_links ) || ! is_array( $footer_links ) ) {
    $footer_links = array();
    for ( $i = 1; $i <= 5; $i++ ) {
        $text = get_theme_mod( 'vvfs_link_' . $i . '_text', '' );
        $url  = get_theme_mod( 'vvfs_link_' . $i . '_url', '' );
        if ( $text ) {
            $footer_links[] = array( 'text' => $text, 'url' => $url ?: '#' );
        }
    }
}

$newsletter_text = get_theme_mod( 'vvfs_newsletter_text', 'Subscribe for exclusive offers & runway previews.' );
if ( ! is_array( $footer_social ) ) $footer_social = array();
if ( ! is_array( $footer_links ) )  $footer_links  = array();
?>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="bg-[#0b0b0d] text-zinc-400 border-t border-white/10 w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16 py-16 md:py-20">

    <!-- Top: brand + social -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-white/10 pb-10">
      <div>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-3xl font-extrabold tracking-widest text-white font-serif uppercase">
          VELVET VOGUE<span class="text-rose-500">.</span>
        </a>
        <?php if ( $footer_tagline ) : ?>
          <p class="text-sm text-zinc-500 mt-2 max-w-sm font-light"><?php echo esc_html( $footer_tagline ); ?></p>
        <?php endif; ?>
      </div>
      <div class="flex items-center gap-4 mt-6 md:mt-0">
        <?php
        $social_icons = array(
            'instagram' => 'fa-brands fa-instagram',
            'pinterest' => 'fa-brands fa-pinterest-p',
            'youtube'   => 'fa-brands fa-youtube',
            'tiktok'    => 'fa-brands fa-tiktok',
        );
        foreach ( $social_icons as $key => $icon ) :
            if ( ! empty( $footer_social[ $key ] ) ) :
        ?>
          <a href="<?php echo esc_url( $footer_social[ $key ] ); ?>" class="vvfs-social-icon" aria-label="<?php echo esc_attr( ucfirst( $key ) ); ?>" target="_blank" rel="noopener">
            <i class="<?php echo esc_attr( $icon ); ?>"></i>
          </a>
        <?php
            endif;
        endforeach;
        ?>
      </div>
    </div>

    <!-- Middle: footer columns -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-10 py-12 border-b border-white/10">

      <!-- Col 1: Shop -->
      <div>
        <h5 class="text-white font-bold text-sm mb-6 uppercase tracking-widest font-serif"><?php esc_html_e( 'Shop', 'velvet-vogue-fashion-store' ); ?></h5>
        <?php if ( has_nav_menu( 'footer_1' ) ) : ?>
          <?php wp_nav_menu( array(
            'theme_location' => 'footer_1',
            'container'      => false,
            'menu_class'     => 'space-y-3 text-sm',
            'fallback_cb'    => false,
            'depth'          => 1,
            'link_before'    => '<span class="footer-link">',
            'link_after'     => '</span>',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
          ) ); ?>
        <?php else : ?>
          <ul class="space-y-3 text-sm">
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
              <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="footer-link"><?php esc_html_e( 'Shop All', 'velvet-vogue-fashion-store' ); ?></a></li>
            <?php endif; ?>
            <?php foreach ( $footer_links as $fl ) : ?>
              <li><a href="<?php echo esc_url( $fl['url'] ); ?>" class="footer-link"><?php echo esc_html( $fl['text'] ); ?></a></li>
            <?php endforeach; ?>
            <?php if ( empty( $footer_links ) && ! class_exists( 'WooCommerce' ) ) : ?>
              <li><span class="footer-link"><?php esc_html_e( 'New Arrivals', 'velvet-vogue-fashion-store' ); ?></span></li>
              <li><span class="footer-link"><?php esc_html_e( 'Women', 'velvet-vogue-fashion-store' ); ?></span></li>
              <li><span class="footer-link"><?php esc_html_e( 'Men', 'velvet-vogue-fashion-store' ); ?></span></li>
              <li><span class="footer-link"><?php esc_html_e( 'Accessories', 'velvet-vogue-fashion-store' ); ?></span></li>
              <li><span class="footer-link"><?php esc_html_e( 'Sale', 'velvet-vogue-fashion-store' ); ?></span></li>
            <?php endif; ?>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Col 2: About -->
      <div>
        <h5 class="text-white font-bold text-sm mb-6 uppercase tracking-widest font-serif"><?php esc_html_e( 'About', 'velvet-vogue-fashion-store' ); ?></h5>
        <?php if ( has_nav_menu( 'footer_2' ) ) : ?>
          <?php wp_nav_menu( array(
            'theme_location' => 'footer_2',
            'container'      => false,
            'menu_class'     => 'space-y-3 text-sm',
            'fallback_cb'    => false,
            'depth'          => 1,
            'link_before'    => '<span class="footer-link">',
            'link_after'     => '</span>',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
          ) ); ?>
        <?php else : ?>
          <ul class="space-y-3 text-sm">
            <li><span class="footer-link"><?php esc_html_e( 'Our Story', 'velvet-vogue-fashion-store' ); ?></span></li>
            <li><span class="footer-link"><?php esc_html_e( 'Sustainability', 'velvet-vogue-fashion-store' ); ?></span></li>
            <li><span class="footer-link"><?php esc_html_e( 'Careers', 'velvet-vogue-fashion-store' ); ?></span></li>
            <li><span class="footer-link"><?php esc_html_e( 'Press', 'velvet-vogue-fashion-store' ); ?></span></li>
            <li><span class="footer-link"><?php esc_html_e( 'Contact', 'velvet-vogue-fashion-store' ); ?></span></li>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Col 3: Support -->
      <div>
        <h5 class="text-white font-bold text-sm mb-6 uppercase tracking-widest font-serif"><?php esc_html_e( 'Support', 'velvet-vogue-fashion-store' ); ?></h5>
        <?php if ( has_nav_menu( 'footer_3' ) ) : ?>
          <?php wp_nav_menu( array(
            'theme_location' => 'footer_3',
            'container'      => false,
            'menu_class'     => 'space-y-3 text-sm',
            'fallback_cb'    => false,
            'depth'          => 1,
            'link_before'    => '<span class="footer-link">',
            'link_after'     => '</span>',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
          ) ); ?>
        <?php else : ?>
          <ul class="space-y-3 text-sm">
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
              <li><a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="footer-link"><?php esc_html_e( 'My Account', 'velvet-vogue-fashion-store' ); ?></a></li>
              <li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="footer-link"><?php esc_html_e( 'Cart', 'velvet-vogue-fashion-store' ); ?></a></li>
              <li><a href="<?php echo esc_url( wc_get_page_permalink( 'checkout' ) ); ?>" class="footer-link"><?php esc_html_e( 'Checkout', 'velvet-vogue-fashion-store' ); ?></a></li>
            <?php endif; ?>
            <li><span class="footer-link"><?php esc_html_e( 'Help Center', 'velvet-vogue-fashion-store' ); ?></span></li>
            <li><span class="footer-link"><?php esc_html_e( 'Size Guide', 'velvet-vogue-fashion-store' ); ?></span></li>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Col 4: Newsletter -->
      <div class="col-span-2 md:col-span-1">
        <h5 class="text-white font-bold text-sm mb-6 uppercase tracking-widest font-serif"><?php esc_html_e( 'Stay in Touch', 'velvet-vogue-fashion-store' ); ?></h5>
        <?php if ( $newsletter_text ) : ?>
          <p class="text-sm text-zinc-500 mb-4 font-light"><?php echo esc_html( $newsletter_text ); ?></p>
        <?php endif; ?>
        <form class="flex flex-col sm:flex-row gap-3" id="vvfs-newsletter-form">
          <input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'velvet-vogue-fashion-store' ); ?>" class="flex-1 bg-zinc-900 border border-white/10 rounded-full px-5 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent" required />
          <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold px-6 py-3 rounded-full transition-all duration-300 whitespace-nowrap shadow-lg shadow-rose-500/30">
            <?php esc_html_e( 'Subscribe', 'velvet-vogue-fashion-store' ); ?>
          </button>
        </form>
        <p class="text-[0.65rem] text-zinc-600 mt-3 font-light"><?php esc_html_e( 'We respect your privacy. Unsubscribe anytime.', 'velvet-vogue-fashion-store' ); ?></p>
      </div>
    </div>

    <!-- Bottom: copyright -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-8 text-xs text-zinc-600 font-light">
      <span>
        &copy; <?php echo esc_html( date( 'Y' ) ); ?>
        <?php echo esc_html( $footer_copyright ); ?>
      </span>
      <div class="flex gap-6 mt-3 sm:mt-0">
        <a href="#" class="hover:text-white transition"><?php esc_html_e( 'Privacy Policy', 'velvet-vogue-fashion-store' ); ?></a>
        <a href="#" class="hover:text-white transition"><?php esc_html_e( 'Terms of Service', 'velvet-vogue-fashion-store' ); ?></a>
        <a href="#" class="hover:text-white transition"><?php esc_html_e( 'Cookies', 'velvet-vogue-fashion-store' ); ?></a>
      </div>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
