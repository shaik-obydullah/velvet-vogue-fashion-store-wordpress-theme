<?php
/**
 * 404 Template
 *
 * @package Velvet_Vogue_Fashion_Store
 */

get_header();
?>

<section class="bg-vvfs-bg w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16">
    <div class="vvfs-404">
      <h1>404</h1>
      <h2><?php esc_html_e( 'Page Not Found', 'velvet-vogue-fashion-store' ); ?></h2>
      <p class="mb-8">
        <?php esc_html_e( 'The page you\'re looking for doesn\'t exist or has been moved.', 'velvet-vogue-fashion-store' ); ?>
      </p>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-cart px-8 py-3.5 text-sm">
        <i class="fa-solid fa-house"></i> <?php esc_html_e( 'Back to Home', 'velvet-vogue-fashion-store' ); ?>
      </a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
