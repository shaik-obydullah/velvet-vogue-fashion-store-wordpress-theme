<?php
/**
 * Template Part: Hero Slider
 * Pulls slides from the VVFS Hero Slider CPT (if plugin active),
 * or falls back to Customizer settings for a single static hero.
 *
 * @package Velvet_Vogue_Fashion_Store
 */

$has_cpt = defined( 'VVFS_CORE_VERSION' );
$slides  = array();

if ( $has_cpt ) {
    $slides = get_posts( array(
        'post_type'      => 'vvfs_hero_slide',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );
}

// Customizer fallback values
$fb_kicker  = get_theme_mod( 'vvfs_hero_kicker',  'Autumn / Winter 2026' );
$fb_head    = get_theme_mod( 'vvfs_hero_headline', 'Timeless' );
$fb_sub     = get_theme_mod( 'vvfs_hero_subheadline', 'Sophistication' );
$fb_desc    = get_theme_mod( 'vvfs_hero_description', 'Immerse yourself in haute couture defined by immaculate silhouettes, rich textures, and bold modern palettes.' );
$fb_img     = get_theme_mod( 'vvfs_hero_image', 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=800&h=700&fit=crop&crop=center&auto=format' );
$default_shop = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );

$fb_cta1_txt = get_theme_mod( 'vvfs_hero_cta1_text', 'Discover Collection' );
$fb_cta1_url = get_theme_mod( 'vvfs_hero_cta1_url',  $default_shop );
$fb_cta2_txt = get_theme_mod( 'vvfs_hero_cta2_text', 'Lookbook' );
$fb_cta2_url = get_theme_mod( 'vvfs_hero_cta2_url',  $default_shop );

$shop_url = $default_shop;
?>

<section class="relative overflow-hidden bg-vvfs-bg w-full">
  <div class="w-full px-0 py-0">
    <div class="relative overflow-hidden bg-[#18181b] border-y border-white/10">

      <!-- Slider Track -->
      <div id="sliderTrack" class="slider-track flex">
        <?php if ( $has_cpt && ! empty( $slides ) ) : ?>
          <?php foreach ( $slides as $slide ) :
            $kicker  = get_post_meta( $slide->ID, 'vvfs_kicker', true );
            $subtitle = get_post_meta( $slide->ID, 'vvfs_subtitle', true );
            $img_url = get_the_post_thumbnail_url( $slide->ID, 'vvfs-slider' )
                       ?: 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=800&h=700&fit=crop&crop=center&auto=format';
          ?>
          <div class="min-w-full flex flex-col md:flex-row items-center justify-between p-8 sm:p-12 md:p-20 lg:p-28">
            <div class="md:w-1/2 text-center md:text-left order-2 md:order-1 mt-8 md:mt-0 z-10">
              <?php if ( $kicker ) : ?>
                <span class="inline-block text-xs font-bold uppercase tracking-[0.25em] text-rose-500 bg-rose-500/10 px-4 py-2 rounded-full mb-6 border border-rose-500/20">
                  <?php echo esc_html( $kicker ); ?>
                </span>
              <?php endif; ?>
              <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold leading-[1.05] tracking-tight text-white font-serif">
                <?php echo esc_html( $slide->post_title ); ?>
              </h2>
              <?php if ( $subtitle ) : ?>
                <p class="mt-6 text-base sm:text-lg text-zinc-400 max-w-lg mx-auto md:mx-0 font-light">
                  <?php echo esc_html( $subtitle ); ?>
                </p>
              <?php endif; ?>
              <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-cart">
                  <?php esc_html_e( 'Shop Now', 'velvet-vogue-fashion-store' ); ?> <i class="fa-solid fa-arrow-right"></i>
                </a>
              </div>
            </div>
            <div class="md:w-1/2 order-1 md:order-2 flex justify-center relative">
              <div class="absolute -inset-4 bg-gradient-to-r from-rose-500/20 to-purple-500/20 rounded-3xl blur-2xl opacity-50"></div>
              <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $slide->post_title ); ?>"
                   class="w-full max-w-sm md:max-w-md lg:max-w-xl rounded-2xl object-cover shadow-2xl relative z-10 border border-white/10" />
            </div>
          </div>
          <?php endforeach; ?>

        <?php else : ?>
          <!-- Fallback: single static hero -->
          <div class="min-w-full flex flex-col md:flex-row items-center justify-between p-8 sm:p-12 md:p-20 lg:p-28">
            <div class="md:w-1/2 text-center md:text-left order-2 md:order-1 mt-8 md:mt-0 z-10">
              <span class="inline-block text-xs font-bold uppercase tracking-[0.25em] text-rose-500 bg-rose-500/10 px-4 py-2 rounded-full mb-6 border border-rose-500/20">
                <?php echo esc_html( $fb_kicker ); ?>
              </span>
              <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold leading-[1.05] tracking-tight text-white font-serif">
                <?php echo esc_html( $fb_head ); ?><br/>
                <span class="italic font-normal text-rose-400"><?php echo esc_html( $fb_sub ); ?></span>
              </h2>
              <p class="mt-6 text-base sm:text-lg text-zinc-400 max-w-lg mx-auto md:mx-0 font-light">
                <?php echo esc_html( $fb_desc ); ?>
              </p>
              <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                <?php if ( $fb_cta1_url ) : ?>
                  <a href="<?php echo esc_url( $fb_cta1_url ); ?>" class="btn-cart">
                    <?php echo esc_html( $fb_cta1_txt ); ?> <i class="fa-solid fa-arrow-right"></i>
                  </a>
                <?php endif; ?>
                <?php if ( $fb_cta2_url ) : ?>
                  <a href="<?php echo esc_url( $fb_cta2_url ); ?>" class="btn-cart-outline">
                    <?php echo esc_html( $fb_cta2_txt ); ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="md:w-1/2 order-1 md:order-2 flex justify-center relative">
              <div class="absolute -inset-4 bg-gradient-to-r from-rose-500/20 to-purple-500/20 rounded-3xl blur-2xl opacity-50"></div>
              <img src="<?php echo esc_url( $fb_img ); ?>" alt="<?php echo esc_attr( $fb_head . ' ' . $fb_sub ); ?>"
                   class="w-full max-w-sm md:max-w-md lg:max-w-xl rounded-2xl object-cover shadow-2xl relative z-10 border border-white/10" />
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Dots -->
      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3 z-20">
        <?php
        $slide_count = ! empty( $slides ) ? count( $slides ) : 1;
        for ( $i = 0; $i < $slide_count; $i++ ) :
        ?>
        <button class="slider-dot w-3 h-3 rounded-full bg-white/30 <?php echo $i === 0 ? 'active' : ''; ?>"
                data-index="<?php echo esc_attr( $i ); ?>"
                aria-label="<?php printf( esc_attr__( 'Slide %d', 'velvet-vogue-fashion-store' ), $i + 1 ); ?>"></button>
        <?php endfor; ?>
      </div>

      <!-- Arrows -->
      <button id="prevSlide"
              class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-black/60 hover:bg-rose-600 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-xl transition-all duration-300 text-base backdrop-blur-md border border-white/10"
              aria-label="<?php esc_attr_e( 'Previous', 'velvet-vogue-fashion-store' ); ?>">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button id="nextSlide"
              class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-black/60 hover:bg-rose-600 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-xl transition-all duration-300 text-base backdrop-blur-md border border-white/10"
              aria-label="<?php esc_attr_e( 'Next', 'velvet-vogue-fashion-store' ); ?>">
        <i class="fa-solid fa-chevron-right"></i>
      </button>

    </div>
  </div>
</section>
