<?php
/**
 * Template Part: Testimonials
 * Pulls from the VVFS Testimonial CPT (if plugin active),
 * or shows hardcoded fallback testimonials.
 *
 * @package Velvet_Vogue_Fashion_Store
 */

$has_cpt     = defined( 'VVFS_CORE_VERSION' );
$testimonials = array();

if ( $has_cpt ) {
    $testimonials = get_posts( array(
        'post_type'      => 'vvfs_testimonial',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );
}

// Fallback testimonials
$fallback = array(
    array(
        'name'   => 'Sophie M.',
        'role'   => 'Fashion Enthusiast',
        'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face&auto=format',
        'quote'  => '"Absolutely in love with my new blazer. The quality is exceptional and it fits like a dream. Velvet Vogue Fashion has become my go-to for timeless pieces."',
        'rating' => 5,
    ),
    array(
        'name'   => 'James K.',
        'role'   => 'Style Connoisseur',
        'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face&auto=format',
        'quote'  => '"The cashmere sweater is hands-down the softest I\'ve ever worn. Shipping was fast and the packaging was breathtaking. Highly recommend!"',
        'rating' => 5,
    ),
    array(
        'name'   => 'Emma R.',
        'role'   => 'Loyal Customer',
        'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face&auto=format',
        'quote'  => '"I bought the leather crossbody and it\'s perfect for everyday. Minimalist, functional, and beautifully crafted. Will definitely order again."',
        'rating' => 4,
    ),
);
?>

<section class="py-20 md:py-28 bg-vvfs-bg w-full border-t border-white/10">
  <div class="w-full px-6 sm:px-10 lg:px-16">

    <!-- Section Header -->
    <div class="text-center mb-16 md:mb-20">
      <span class="text-xs font-bold uppercase tracking-[0.3em] text-rose-500"><?php esc_html_e( 'Testimonials', 'velvet-vogue-fashion-store' ); ?></span>
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mt-3 text-white font-serif"><?php esc_html_e( 'What Our Clients Say', 'velvet-vogue-fashion-store' ); ?></h2>
    </div>

    <!-- Testimonial Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
      <?php if ( $has_cpt && ! empty( $testimonials ) ) : ?>
        <?php foreach ( $testimonials as $t ) :
          $quote  = get_post_meta( $t->ID, 'vvfs_testimonial_quote', true );
          $rating = intval( get_post_meta( $t->ID, 'vvfs_testimonial_rating', true ) );
          $avatar = get_post_meta( $t->ID, 'vvfs_testimonial_avatar', true );
          $role   = get_post_meta( $t->ID, 'vvfs_testimonial_role', true );
          if ( ! $avatar ) {
              $avatar = 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face&auto=format';
          }
          if ( $rating < 1 ) $rating = 5;
        ?>
        <div class="testimonial-card">
          <div class="flex items-center gap-4 mb-6">
            <img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $t->post_title ); ?>"
                 class="w-14 h-14 rounded-full object-cover border-2 border-rose-500/30" />
            <div>
              <h4 class="font-bold text-white text-base font-serif"><?php echo esc_html( $t->post_title ); ?></h4>
              <?php if ( $role ) : ?>
                <p class="text-zinc-500 text-xs mt-0.5"><?php echo esc_html( $role ); ?></p>
              <?php endif; ?>
              <div class="flex text-xs text-amber-400 mt-1 gap-1">
                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                  <?php if ( $i <= $rating ) : ?>
                    <i class="fa-solid fa-star"></i>
                  <?php else : ?>
                    <i class="fa-regular fa-star text-zinc-600"></i>
                  <?php endif; ?>
                <?php endfor; ?>
              </div>
            </div>
          </div>
          <?php if ( $quote ) : ?>
            <p class="text-zinc-300 text-base leading-relaxed font-light italic">
              <?php echo esc_html( $quote ); ?>
            </p>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>

      <?php else : ?>
        <?php foreach ( $fallback as $f ) : ?>
        <div class="testimonial-card">
          <div class="flex items-center gap-4 mb-6">
            <img src="<?php echo esc_url( $f['avatar'] ); ?>" alt="<?php echo esc_attr( $f['name'] ); ?>"
                 class="w-14 h-14 rounded-full object-cover border-2 border-rose-500/30" />
            <div>
              <h4 class="font-bold text-white text-base font-serif"><?php echo esc_html( $f['name'] ); ?></h4>
              <?php if ( $f['role'] ) : ?>
                <p class="text-zinc-500 text-xs mt-0.5"><?php echo esc_html( $f['role'] ); ?></p>
              <?php endif; ?>
              <div class="flex text-xs text-amber-400 mt-1 gap-1">
                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                  <?php if ( $i <= $f['rating'] ) : ?>
                    <i class="fa-solid fa-star"></i>
                  <?php else : ?>
                    <i class="fa-regular fa-star text-zinc-600"></i>
                  <?php endif; ?>
                <?php endfor; ?>
              </div>
            </div>
          </div>
          <p class="text-zinc-300 text-base leading-relaxed font-light italic">
            <?php echo esc_html( $f['quote'] ); ?>
          </p>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</section>
