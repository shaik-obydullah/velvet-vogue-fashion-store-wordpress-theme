<?php
/**
 * Front Page Template — Homepage
 *
 * @package Velvet_Vogue_Fashion_Store
 */

get_header();
?>

<!-- Hero Slider -->
<?php get_template_part( 'template-parts/hero', 'slider' ); ?>

<!-- Featured Categories -->
<?php get_template_part( 'template-parts/featured', 'categories' ); ?>

<!-- Featured Products -->
<?php get_template_part( 'template-parts/featured', 'products' ); ?>

<!-- Testimonials -->
<?php get_template_part( 'template-parts/testimonials' ); ?>

<?php get_footer(); ?>
