<?php
/**
 * Single Post Template
 *
 * @package Velvet_Vogue_Fashion_Store
 */

get_header();
?>

<section class="py-10 md:py-16 bg-vvfs-bg w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16 max-w-4xl mx-auto">

    <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <div class="vvfs-single-header mb-8">
        <p class="meta text-sm text-zinc-500 mb-4">
          <i class="fa-regular fa-calendar mr-1"></i> <?php echo esc_html( get_the_date() ); ?>
          &nbsp;&middot;&nbsp;
          <i class="fa-regular fa-folder mr-1"></i> <?php the_category( ', ' ); ?>
          &nbsp;&middot;&nbsp;
          <i class="fa-regular fa-user mr-1"></i> <?php the_author(); ?>
        </p>
        <h1><?php the_title(); ?></h1>
      </div>

      <?php if ( has_post_thumbnail() ) : ?>
        <div class="mb-8">
          <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto rounded-2xl object-cover', 'style' => 'max-height: 500px;' ) ); ?>
        </div>
      <?php endif; ?>

      <div class="vvfs-single-content">
        <?php the_content(); ?>
      </div>

      <!-- Post navigation -->
      <div class="flex flex-col sm:flex-row justify-between gap-4 mt-12 pt-8 border-t border-white/10">
        <?php
        $prev = get_previous_post();
        $next = get_next_post();
        ?>
        <?php if ( $prev ) : ?>
          <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="flex items-center gap-2 text-zinc-400 hover:text-rose-500 transition text-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span><?php echo esc_html( get_the_title( $prev ) ); ?></span>
          </a>
        <?php else : ?>
          <div></div>
        <?php endif; ?>
        <?php if ( $next ) : ?>
          <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="flex items-center gap-2 text-zinc-400 hover:text-rose-500 transition text-sm text-right">
            <span><?php echo esc_html( get_the_title( $next ) ); ?></span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        <?php endif; ?>
      </div>

    </article>
    <?php endwhile; ?>

    <!-- Comments -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
      <div class="mt-12">
        <?php comments_template(); ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
