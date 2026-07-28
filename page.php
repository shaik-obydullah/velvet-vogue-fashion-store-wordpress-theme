<?php
/**
 * Page Template — Default static page
 *
 * @package Velvet_Vogue_Fashion_Store
 */

get_header();

$is_order_received = function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' );
?>

<?php if ( $is_order_received ) : ?>

  <?php while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; ?>

<?php else : ?>

<section class="py-10 md:py-16 bg-vvfs-bg w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16 max-w-4xl mx-auto">

    <?php while ( have_posts() ) : the_post(); ?>
    <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

      <h1 class="text-3xl sm:text-4xl font-bold text-white font-serif mb-8"><?php the_title(); ?></h1>

      <div class="vvfs-page-content">
        <?php the_content(); ?>
      </div>

    </article>
    <?php endwhile; ?>

  </div>
</section>

<?php endif; ?>

<?php get_footer(); ?>
