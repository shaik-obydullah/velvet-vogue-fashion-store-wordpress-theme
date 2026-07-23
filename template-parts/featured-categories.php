<?php
/**
 * Template Part: Featured Categories
 * Displays WooCommerce product categories as a grid of cards.
 *
 * @package Velvet_Vogue_Fashion_Store
 */

if ( ! class_exists( 'WooCommerce' ) ) return;

$terms = get_terms( array(
    'taxonomy'   => 'product_cat',
    'number'     => 4,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'hide_empty' => true,
    'exclude'    => array( get_option( 'default_product_cat' ) ),
) );

if ( is_wp_error( $terms ) || empty( $terms ) ) return;

$defaults = array(
    'women'      => array(
        'img' => 'https://images.unsplash.com/photo-1534126511673-b6899657816a?w=600&h=700&fit=crop&crop=center&auto=format',
    ),
    'men'        => array(
        'img' => 'https://images.unsplash.com/photo-1617137968427-85924c800a22?w=600&h=700&fit=crop&crop=center&auto=format',
    ),
    'accessories' => array(
        'img' => 'https://images.unsplash.com/photo-1525966222134-0b9a446cf7ce?w=600&h=700&fit=crop&crop=center&auto=format',
    ),
    'sale'       => array(
        'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&h=700&fit=crop&crop=center&auto=format',
    ),
);
?>

<section class="py-20 md:py-28 bg-vvfs-bg w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16">

    <!-- Section Header -->
    <div class="text-center mb-16 md:mb-20">
      <span class="text-xs font-bold uppercase tracking-[0.3em] text-rose-500"><?php esc_html_e( 'Collections', 'velvet-vogue-fashion-store' ); ?></span>
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mt-3 text-white font-serif"><?php esc_html_e( 'Curated Categories', 'velvet-vogue-fashion-store' ); ?></h2>
      <p class="text-zinc-400 mt-3 max-w-md mx-auto text-base font-light"><?php esc_html_e( 'Explore bespoke categories crafted for every occasion and persona.', 'velvet-vogue-fashion-store' ); ?></p>
    </div>

    <!-- Category Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
      <?php foreach ( $terms as $term ) :
        $slug    = $term->slug;
        $count   = $term->count;
        $img_url = '';
        // Try to get an image from the term
        $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
        if ( $thumb_id ) {
            $img_url = wp_get_attachment_image_url( $thumb_id, 'vvfs-category' );
        }
        // Fallback to curated defaults
        if ( ! $img_url ) {
            foreach ( $defaults as $key => $def ) {
                if ( strpos( $slug, $key ) !== false ) {
                    $img_url = $def['img'];
                    break;
                }
            }
        }
        // Ultimate fallback
        if ( ! $img_url ) {
            $img_url = 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600&h=700&fit=crop&crop=center&auto=format';
        }
      ?>
      <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="category-card group block">
        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>"
             class="w-full h-80 object-cover" loading="lazy" />
        <div class="overlay">
          <div>
            <h3 class="text-white text-2xl font-bold font-serif"><?php echo esc_html( $term->name ); ?></h3>
            <span class="text-rose-400 text-sm font-medium mt-1 inline-block">
              <?php
              /* translators: %d: number of products */
              printf( esc_html( _n( '%d Product', '%d Products', $count, 'velvet-vogue-fashion-store' ) ), $count );
              ?>
            </span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>
