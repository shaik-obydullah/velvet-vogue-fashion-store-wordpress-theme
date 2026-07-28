<?php
/**
 * Archive Template
 *
 * @package Velvet_Vogue_Fashion_Store
 */

get_header();
?>

<section class="py-10 md:py-16 bg-vvfs-bg w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16">

    <div class="vvfs-archive-header mb-8">
      <?php the_archive_title( '<h1>', '</h1>' ); ?>
      <?php the_archive_description( '<p class="text-zinc-400 font-light mt-2">', '</p>' ); ?>
    </div>

    <?php if ( have_posts() ) : ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <?php while ( have_posts() ) : the_post(); ?>
      <div class="vvfs-post-card">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-64 object-cover' ) ); ?>
          </a>
        <?php endif; ?>
        <div class="p-6">
          <p class="meta">
            <i class="fa-regular fa-calendar mr-1"></i> <?php echo esc_html( get_the_date() ); ?>
          </p>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <p class="excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
          <a href="<?php the_permalink(); ?>" class="btn-cart text-xs px-4 py-2 mt-4 inline-flex">
            <?php esc_html_e( 'Read More', 'velvet-vogue-fashion-store' ); ?> <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

    <div class="mt-10 flex justify-center">
      <?php the_posts_pagination( array(
          'prev_text' => '<i class="fa-solid fa-chevron-left text-xs"></i>',
          'next_text' => '<i class="fa-solid fa-chevron-right text-xs"></i>',
      ) ); ?>
    </div>

    <?php else : ?>
      <p class="text-zinc-400 font-light"><?php esc_html_e( 'No posts found.', 'velvet-vogue-fashion-store' ); ?></p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
