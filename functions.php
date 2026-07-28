<?php
/**
 * Velvet Vogue Fashion Store — Theme Functions
 *
 * @package Velvet_Vogue_Fashion_Store
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'VVFS_VERSION', '1.0.0' );
define( 'VVFS_DIR', get_template_directory() );
define( 'VVFS_URI', get_template_directory_uri() );

/* ======================================================
   1. Theme Setup
====================================================== */
function vvfs_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 250,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ) );

    register_nav_menus( array(
        'primary'   => __( 'Primary Menu', 'velvet-vogue-fashion-store' ),
        'footer_1'  => __( 'Footer — Shop', 'velvet-vogue-fashion-store' ),
        'footer_2'  => __( 'Footer — About', 'velvet-vogue-fashion-store' ),
        'footer_3'  => __( 'Footer — Support', 'velvet-vogue-fashion-store' ),
    ) );

    add_image_size( 'vvfs-slider', 800, 700, true );
    add_image_size( 'vvfs-card', 600, 700, true );
    add_image_size( 'vvfs-category', 600, 700, true );
}
add_action( 'after_setup_theme', 'vvfs_setup' );

/* ======================================================
   2. Widget Areas
====================================================== */
function vvfs_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Blog Sidebar', 'velvet-vogue-fashion-store' ),
        'id'            => 'sidebar-blog',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title text-white font-serif mb-4 text-lg font-bold">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Shop Sidebar', 'velvet-vogue-fashion-store' ),
        'id'            => 'sidebar-shop',
        'before_widget' => '<div class="vvfs-sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="vvfs-sidebar-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'vvfs_widgets_init' );

/* ======================================================
   3. Enqueue Assets
====================================================== */
function vvfs_assets() {
    // Tailwind CSS (production build)
    wp_enqueue_style( 'vvfs-tailwind', VVFS_URI . '/assets/css/tailwind.css', array(), VVFS_VERSION );

    // Google Fonts — Inter + Playfair Display
    wp_enqueue_style( 'vvfs-fonts', 'https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap', array(), null );

    // Font Awesome 6
    wp_enqueue_style( 'vvfs-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );

    // Theme styles (load after Tailwind so utility classes override)
    wp_enqueue_style( 'vvfs-base', VVFS_URI . '/assets/css/base.css', array( 'vvfs-tailwind' ), VVFS_VERSION );
    wp_enqueue_style( 'vvfs-theme', VVFS_URI . '/assets/css/theme.css', array( 'vvfs-base' ), VVFS_VERSION );
    wp_enqueue_style( 'vvfs-main', VVFS_URI . '/assets/css/main.css', array( 'vvfs-theme' ), VVFS_VERSION );

    // Theme stylesheet (style.css — required by WP but empty for metadata)
    wp_enqueue_style( 'vvfs-style', get_stylesheet_uri(), array( 'vvfs-main' ), VVFS_VERSION );

    // Main JS
    wp_enqueue_script( 'vvfs-main', VVFS_URI . '/assets/js/main.js', array(), VVFS_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'vvfs_assets' );

/* ======================================================
   4. WooCommerce Support
====================================================== */
function vvfs_wc_support() {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 600,
        'gallery_thumbnail_image_width' => 300,
        'single_image_width' => 800,
        'product_grid' => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'default_columns' => 2,
            'min_columns'     => 1,
            'max_columns'     => 2,
        ),
    ) );
    remove_theme_support( 'wc-product-gallery-zoom' );
    remove_theme_support( 'wc-product-gallery-lightbox' );
    remove_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'vvfs_wc_support' );

function vvfs_wc_assets() {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    wp_enqueue_style( 'vvfs-woocommerce', VVFS_URI . '/assets/css/woocommerce.css', array( 'vvfs-main' ), VVFS_VERSION );

    if ( is_product() ) {
        wp_enqueue_script( 'vvfs-product-details', VVFS_URI . '/assets/js/product-details.js', array(), VVFS_VERSION, true );
    }
}
add_action( 'wp_enqueue_scripts', 'vvfs_wc_assets', 20 );

/* ======================================================
   4b. Force Classic Checkout (disable Blocks checkout)
====================================================== */
if ( class_exists( 'WooCommerce' ) ) {
    add_filter( 'woocommerce_blocks_support', '__return_false' );
}

function vvfs_force_classic_checkout() {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    if ( ! is_checkout() ) return;
    global $post;
    if ( ! $post || has_blocks( $post->post_content ) ) {
        remove_filter( 'the_content', 'do_blocks', 9 );
        $post->post_content = '[woocommerce_checkout]';
        unset( $GLOBALS['wp_registered_blocks'] );
    }
}
add_action( 'wp', 'vvfs_force_classic_checkout', 1 );

function vvfs_disable_order_confirmation_block_template( $templates ) {
    if ( ! function_exists( 'is_wc_endpoint_url' ) ) return $templates;
    if ( is_wc_endpoint_url( 'order-received' ) ) {
        $order_templates = array();
        foreach ( $templates as $t ) {
            if ( strpos( $t, 'order-confirmation' ) === false ) {
                $order_templates[] = $t;
            }
        }
        return $order_templates;
    }
    return $templates;
}
add_filter( 'page_template_hierarchy', 'vvfs_disable_order_confirmation_block_template', 2 );

/* ======================================================
   5. WooCommerce Wrappers
====================================================== */
if ( class_exists( 'WooCommerce' ) ) {

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );

function vvfs_wc_wrapper_before() {
    if ( is_product() ) return;
    echo '<section class="py-10 md:py-16 bg-vvfs-bg w-full"><div class="w-full px-6 sm:px-10 lg:px-16">';
}
add_action( 'woocommerce_before_main_content', 'vvfs_wc_wrapper_before' );

function vvfs_wc_wrapper_after() {
    if ( is_product() ) return;
    echo '</div></section>';
}
add_action( 'woocommerce_after_main_content', 'vvfs_wc_wrapper_after' );

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_action( 'woocommerce_sidebar', function() {
    if ( is_product() ) return;
    woocommerce_get_sidebar();
});

/* ======================================================
   6. WooCommerce: Shop Sidebar Layout
====================================================== */
function vvfs_wc_sidebar_before() {
    $is_shop = is_shop() || is_product_category() || is_product_tag();
    if ( ! $is_shop ) return;
    echo '<div class="vvfs-shop-layout">';
    get_template_part( 'sidebar-shop' );
    echo '<main class="vvfs-shop-main">';
}
add_action( 'woocommerce_before_main_content', 'vvfs_wc_sidebar_before', 5 );

function vvfs_wc_sidebar_after() {
    $is_shop = is_shop() || is_product_category() || is_product_tag();
    if ( ! $is_shop ) return;
    echo '</main></div>';
}
add_action( 'woocommerce_after_main_content', 'vvfs_wc_sidebar_after', 20 );

/* ======================================================
   7. WooCommerce: Add to Cart Button
====================================================== */
function vvfs_wc_add_to_cart_button_class( $classes ) {
    $classes = str_replace( 'button', 'btn-cart', $classes );
    return $classes;
}
add_filter( 'woocommerce_product_add_to_cart_class', 'vvfs_wc_add_to_cart_button_class' );

/* ======================================================
   8. WooCommerce: Product Loop Header (result count + ordering)
====================================================== */
function vvfs_wc_products_header() {
    echo '<div class="vvfs-shop-header">';
}
add_action( 'woocommerce_before_shop_loop', 'vvfs_wc_products_header', 5 );

function vvfs_wc_products_header_end() {
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'vvfs_wc_products_header_end', 35 );

/* ======================================================
   10. Breadcrumb Separator
====================================================== */
function vvfs_breadcrumb_defaults( $defaults ) {
    $defaults['delimiter']   = ' <i class="fa-solid fa-chevron-right text-[0.6rem] text-zinc-700"></i> ';
    $defaults['wrap_before'] = '<nav class="flex items-center gap-2 text-sm text-zinc-500 font-light">';
    $defaults['wrap_after']  = '</nav>';
    $defaults['before']      = '';
    $defaults['after']       = '';
    $defaults['home']        = __( 'Home', 'velvet-vogue-fashion-store' );
    return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'vvfs_breadcrumb_defaults' );

/* ======================================================
   11. WooCommerce: Default Catalog Ordering
====================================================== */
function vvfs_default_catalog_orderby( $sort ) {
    return 'menu_order';
}
add_filter( 'woocommerce_default_catalog_orderby', 'vvfs_default_catalog_orderby' );

function vvfs_hide_empty_products( $q ) {
    if ( is_admin() ) return;
    $q->set( 'meta_key', '_thumbnail_id' );
}
add_action( 'woocommerce_product_query', 'vvfs_hide_empty_products' );

} // end class_exists('WooCommerce')

/* ======================================================
   9. Excerpt Length
====================================================== */
function vvfs_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'vvfs_excerpt_length' );

/* ======================================================
   12. WooCommerce: AJAX Cart Fragments
====================================================== */
function vvfs_enqueue_ajax_cart() {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    wp_localize_script( 'vvfs-main', 'vvfsAjax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'vvfs_ajax_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'vvfs_enqueue_ajax_cart' );

/* ======================================================
   12. Customizer: Hero / Branding Settings
====================================================== */
function vvfs_customize_register( $wp_customize ) {

    // Section
    $wp_customize->add_section( 'vvfs_hero_section', array(
        'title'    => __( 'Homepage Hero', 'velvet-vogue-fashion-store' ),
        'priority' => 30,
    ) );

    // Kicker text
    $wp_customize->add_setting( 'vvfs_hero_kicker', array(
        'default'           => 'Autumn / Winter 2026',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_hero_kicker', array(
        'label'   => __( 'Hero Kicker Text', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'text',
    ) );

    // Hero headline
    $wp_customize->add_setting( 'vvfs_hero_headline', array(
        'default'           => 'Timeless',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_hero_headline', array(
        'label'   => __( 'Hero Headline', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'text',
    ) );

    // Hero subheadline
    $wp_customize->add_setting( 'vvfs_hero_subheadline', array(
        'default'           => 'Sophistication',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_hero_subheadline', array(
        'label'   => __( 'Hero Sub-Headline (italic)', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'text',
    ) );

    // Hero description
    $wp_customize->add_setting( 'vvfs_hero_description', array(
        'default'           => 'Immerse yourself in haute couture defined by immaculate silhouettes, rich textures, and bold modern palettes.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'vvfs_hero_description', array(
        'label'   => __( 'Hero Description', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'textarea',
    ) );

    // Hero image
    $wp_customize->add_setting( 'vvfs_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'vvfs_hero_image', array(
        'label'   => __( 'Hero Image', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
    ) ) );

    // CTA 1
    $wp_customize->add_setting( 'vvfs_hero_cta1_text', array(
        'default'           => 'Discover Collection',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_hero_cta1_text', array(
        'label'   => __( 'CTA 1 Text', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vvfs_hero_cta1_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'vvfs_hero_cta1_url', array(
        'label'   => __( 'CTA 1 URL', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'url',
    ) );

    // CTA 2
    $wp_customize->add_setting( 'vvfs_hero_cta2_text', array(
        'default'           => 'Lookbook',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_hero_cta2_text', array(
        'label'   => __( 'CTA 2 Text', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vvfs_hero_cta2_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'vvfs_hero_cta2_url', array(
        'label'   => __( 'CTA 2 URL', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_hero_section',
        'type'    => 'url',
    ) );

    // Newsletter section
    $wp_customize->add_section( 'vvfs_newsletter_section', array(
        'title'    => __( 'Footer Newsletter', 'velvet-vogue-fashion-store' ),
        'priority' => 35,
    ) );

    $wp_customize->add_setting( 'vvfs_newsletter_text', array(
        'default'           => 'Subscribe for exclusive offers & runway previews.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_newsletter_text', array(
        'label'   => __( 'Newsletter Description', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_newsletter_section',
        'type'    => 'text',
    ) );

    /* =====================================================
       Footer — Branding Section
    ===================================================== */
    $wp_customize->add_section( 'vvfs_footer_branding', array(
        'title'    => __( 'Footer — Branding', 'velvet-vogue-fashion-store' ),
        'priority' => 40,
    ) );

    $wp_customize->add_setting( 'vvfs_footer_tagline', array(
        'default'           => 'Haute couture & modern luxury for the discerning individual.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_footer_tagline', array(
        'label'   => __( 'Footer Tagline', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_footer_branding',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vvfs_footer_copyright', array(
        'default'           => 'Velvet Vogue Fashion. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vvfs_footer_copyright', array(
        'label'   => __( 'Copyright Text', 'velvet-vogue-fashion-store' ),
        'section' => 'vvfs_footer_branding',
        'type'    => 'text',
    ) );

    /* =====================================================
       Footer — Social URLs Section
    ===================================================== */
    $wp_customize->add_section( 'vvfs_footer_social', array(
        'title'    => __( 'Footer — Social Links', 'velvet-vogue-fashion-store' ),
        'priority' => 41,
    ) );

    $social_platforms = array(
        'instagram' => 'Instagram URL',
        'pinterest' => 'Pinterest URL',
        'youtube'   => 'YouTube URL',
        'tiktok'    => 'TikTok URL',
    );
    foreach ( $social_platforms as $key => $label ) {
        $wp_customize->add_setting( 'vvfs_social_' . $key, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'vvfs_social_' . $key, array(
            'label'   => __( $label, 'velvet-vogue-fashion-store' ),
            'section' => 'vvfs_footer_social',
            'type'    => 'url',
        ) );
    }

    /* =====================================================
       Footer — Quick Links Section
    ===================================================== */
    $wp_customize->add_section( 'vvfs_footer_links', array(
        'title'    => __( 'Footer — Quick Links', 'velvet-vogue-fashion-store' ),
        'priority' => 42,
    ) );

    for ( $i = 1; $i <= 5; $i++ ) {
        $wp_customize->add_setting( 'vvfs_link_' . $i . '_text', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'vvfs_link_' . $i . '_text', array(
            'label'   => sprintf( __( 'Link %d Text', 'velvet-vogue-fashion-store' ), $i ),
            'section' => 'vvfs_footer_links',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'vvfs_link_' . $i . '_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'vvfs_link_' . $i . '_url', array(
            'label'   => sprintf( __( 'Link %d URL', 'velvet-vogue-fashion-store' ), $i ),
            'section' => 'vvfs_footer_links',
            'type'    => 'url',
        ) );
    }
}
add_action( 'customize_register', 'vvfs_customize_register' );

/* ======================================================
   98. Enable COD gateway (hidden via CSS, not disabled)
====================================================== */
if ( class_exists( 'WooCommerce' ) ) {

function vvfs_enable_cod_gateway( $gateways ) {
    return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'vvfs_enable_cod_gateway' );

/* ======================================================
   98b. Skip guest verification on order-received page
====================================================== */
add_filter( 'woocommerce_order_received_verify_known_shoppers', '__return_false' );
add_filter( 'woocommerce_order_email_verification_required', '__return_false' );

/* ======================================================
   99. Buy Now — redirect to checkout
====================================================== */
function vvfs_buy_now_redirect( $url ) {
    if ( ! empty( $_POST['buy_now'] ) ) {
        $checkout_url = wc_get_checkout_url();
        if ( $checkout_url ) {
            return $checkout_url;
        }
    }
    return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'vvfs_buy_now_redirect' );

} // end class_exists('WooCommerce')

/* ======================================================
   100. Comment Template Callback
====================================================== */
function vvfs_comment_template( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'vvfs-comment' ); ?>>
      <div class="flex items-start gap-4">
        <?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full border-2 border-rose-500/30 flex-shrink-0' ) ); ?>
        <div class="flex-1">
          <div class="flex items-center gap-3 mb-1">
            <span class="comment-author font-bold text-white font-serif"><?php comment_author(); ?></span>
            <span class="comment-meta text-zinc-500 text-xs">
              <i class="fa-regular fa-clock mr-1"></i><?php comment_date(); ?>
            </span>
          </div>
          <div class="comment-content text-zinc-300 text-sm leading-relaxed mt-2">
            <?php comment_text(); ?>
          </div>
          <?php
          comment_reply_link( array_merge( $args, array(
              'depth'     => $depth,
              'max_depth' => $args['max_depth'],
              'before'    => '<div class="mt-2">',
              'after'     => '</div>',
          ) ) );
          ?>
        </div>
      </div>
    <?php
}