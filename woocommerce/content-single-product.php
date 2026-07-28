<?php
/**
 * Custom Single Product Template — matches product_details.html
 *
 * @package Velvet_Vogue_Fashion_Store
 */
defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) ) {
    wc_get_template_part( 'content', 'product' );
    return;
}

$categories = get_the_terms( $product->get_id(), 'product_cat' );
$cat_name = '';
$cat_url  = '';
if ( $categories && ! is_wp_error( $categories ) ) {
    foreach ( $categories as $cat ) {
        if ( $cat->parent === 0 ) {
            $cat_name = $cat->name;
            $cat_url  = get_term_link( $cat );
            break;
        }
    }
    if ( ! $cat_name ) {
        $cat_name = $categories[0]->name;
        $cat_url  = get_term_link( $categories[0] );
    }
}

$gallery_ids = $product->get_gallery_image_ids();
$main_id     = $product->get_image_id();
$all_images  = array_merge( array( $main_id ), $gallery_ids );

$rating_count = $product->get_rating_count();
$average      = (float) $product->get_average_rating();
$review_count = $product->get_review_count();

$is_on_sale = $product->is_on_sale();
$regular    = (float) $product->get_regular_price();
$sale       = (float) $product->get_sale_price();
$current    = (float) $product->get_price();
$discount   = $is_on_sale && $regular > 0 ? round( ( ( $regular - $sale ) / $regular ) * 100 ) : 0;

$stock_status = $product->get_stock_status();
$sku          = $product->get_sku();

$attributes = $product->get_attributes();

$color_attr = isset( $attributes['pa_color'] ) ? $attributes['pa_color'] : ( isset( $attributes['color'] ) ? $attributes['color'] : null );
$size_attr  = isset( $attributes['pa_size'] ) ? $attributes['pa_size'] : ( isset( $attributes['size'] ) ? $attributes['size'] : null );

$color_options = array();
if ( $color_attr && is_a( $color_attr, 'WC_Product_Attribute' ) ) {
    $color_terms = $color_attr->get_terms();
    if ( $color_terms && ! is_wp_error( $color_terms ) ) {
        foreach ( $color_terms as $term ) {
            $color_options[] = array(
                'name' => $term->name,
                'slug' => $term->slug,
                'hex'  => get_term_meta( $term->term_id, 'color_hex', true ) ?: '#888888',
            );
        }
    }
}

$size_options = array();
if ( $size_attr && is_a( $size_attr, 'WC_Product_Attribute' ) ) {
    $size_terms = $size_attr->get_terms();
    if ( $size_terms && ! is_wp_error( $size_terms ) ) {
        foreach ( $size_terms as $term ) {
            $in_stock = true;
            if ( is_a( $product, 'WC_Product_Variable' ) ) {
                $in_stock = false;
                foreach ( $product->get_children() as $child_id ) {
                    $child = wc_get_product( $child_id );
                    if ( $child && $child->is_in_stock() ) {
                        $child_attrs = $child->get_attributes();
                        $attr_val = isset( $child_attrs['pa_size'] ) ? $child_attrs['pa_size'] : ( isset( $child_attrs['size'] ) ? $child_attrs['size'] : '' );
                        if ( is_array( $attr_val ) ) $attr_val = reset( $attr_val );
                        if ( strtolower( $attr_val ) === strtolower( $term->slug ) || strtolower( $attr_val ) === strtolower( $term->name ) ) {
                            $in_stock = true;
                            break;
                        }
                    }
                }
            }
            $size_options[] = array(
                'name'     => strtoupper( $term->name ),
                'slug'     => $term->slug,
                'in_stock' => $in_stock,
            );
        }
    }
}

function vvfs_product_image_html( $attachment_id, $product_id, $is_main = false ) {
    $img = wp_get_attachment_image( $attachment_id, 'woocommerce_single', false, array(
        'class'   => $is_main ? 'vvfs-pd-main-img' : '',
        'loading' => $is_main ? '' : 'lazy',
    ) );
    $full_url = wp_get_attachment_url( $attachment_id );
    $thumb_url = wp_get_attachment_image_url( $attachment_id, 'woocommerce_thumbnail' );
    $alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
    if ( ! $alt ) $alt = get_the_title( $product_id );
    return array(
        'full'   => $full_url,
        'thumb'  => $thumb_url,
        'alt'    => $alt,
        'img'    => $img,
    );
}
?>

<!-- Breadcrumb -->
<section class="bg-[#0b0b0d] border-b border-white/10">
  <div class="w-full px-6 sm:px-10 lg:px-16 py-4">
    <nav class="flex items-center gap-2 text-sm text-zinc-500 font-light flex-wrap">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-500 transition">Home</a>
      <i class="fa-solid fa-chevron-right text-[0.6rem] text-zinc-700"></i>
      <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="hover:text-rose-500 transition">Shop</a>
      <?php if ( $cat_name ) : ?>
        <i class="fa-solid fa-chevron-right text-[0.6rem] text-zinc-700"></i>
        <a href="<?php echo esc_url( $cat_url ); ?>" class="hover:text-rose-500 transition"><?php echo esc_html( $cat_name ); ?></a>
      <?php endif; ?>
      <i class="fa-solid fa-chevron-right text-[0.6rem] text-zinc-700"></i>
      <span class="text-zinc-300"><?php echo esc_html( get_the_title() ); ?></span>
    </nav>
  </div>
</section>

<!-- Product Details -->
<section class="py-10 md:py-16 bg-[#0f0f11] w-full">
  <div class="w-full px-6 sm:px-10 lg:px-16">
    <div class="flex flex-col lg:flex-row gap-10 lg:gap-16">

      <!-- Gallery (Left) -->
      <div class="lg:w-1/2">
        <div class="sticky top-28">
          <!-- Main Image -->
          <div class="relative overflow-hidden rounded-2xl bg-zinc-900 border border-white/10">
            <?php
            $main_img = vvfs_product_image_html( $main_id, $product->get_id(), true );
            ?>
            <img
              id="vvfsPdMainImg"
              src="<?php echo esc_url( $main_img['full'] ); ?>"
              alt="<?php echo esc_attr( $main_img['alt'] ); ?>"
              class="w-full h-[400px] sm:h-[500px] lg:h-[600px] object-cover transition-opacity duration-300"
            />
            <?php if ( $is_on_sale ) : ?>
              <span class="absolute top-4 left-4 bg-rose-500 text-white text-[0.65rem] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-md">Sale</span>
            <?php endif; ?>
            <button class="absolute top-4 right-4 bg-black/60 backdrop-blur-md hover:bg-rose-500 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all text-base border border-white/10" id="vvfsPdWishlistBtn"><i class="fa-regular fa-heart"></i></button>
          </div>

          <!-- Thumbnails -->
          <?php if ( count( $all_images ) > 1 ) : ?>
          <div class="flex gap-3 mt-4" id="vvfsPdThumbs">
            <?php foreach ( $all_images as $i => $img_id ) :
              $thumb = vvfs_product_image_html( $img_id, $product->get_id() );
              $active = $i === 0 ? 'border-rose-500 opacity-100' : 'border-transparent opacity-60 hover:opacity-100';
            ?>
              <button class="vvfs-pd-thumb relative overflow-hidden rounded-xl border-2 <?php echo $active; ?> transition-all"
                      data-img="<?php echo esc_url( $thumb['full'] ); ?>">
                <img src="<?php echo esc_url( $thumb['thumb'] ); ?>" alt="<?php echo esc_attr( $thumb['alt'] ); ?>" class="w-20 h-20 sm:w-24 sm:h-24 object-cover" loading="lazy" />
              </button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Product Info (Right) -->
      <div class="lg:w-1/2">
        <?php if ( $cat_name ) : ?>
          <span class="text-xs font-bold uppercase tracking-[0.25em] text-rose-500 bg-rose-500/10 px-3 py-1.5 rounded-full border border-rose-500/20"><?php echo esc_html( $cat_name ); ?></span>
        <?php endif; ?>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white font-serif mt-4 leading-tight"><?php the_title(); ?></h1>

        <!-- Rating -->
        <?php if ( $rating_count > 0 ) : ?>
        <div class="flex items-center gap-3 mt-4">
          <div class="flex items-center gap-1">
            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
              <?php if ( $i <= floor( $average ) ) : ?>
                <span class="star"><i class="fa-solid fa-star text-sm"></i></span>
              <?php elseif ( $i - $average < 1 && $i - $average > 0 ) : ?>
                <span class="star"><i class="fa-solid fa-star-half-stroke text-sm"></i></span>
              <?php else : ?>
                <span class="star-empty"><i class="fa-regular fa-star text-sm"></i></span>
              <?php endif; ?>
            <?php endfor; ?>
          </div>
          <span class="text-sm text-zinc-400 font-medium"><?php echo esc_html( $average ); ?></span>
          <span class="text-sm text-zinc-500">|</span>
          <a href="#vvfsPdTabs" class="text-sm text-zinc-400 hover:text-rose-500 transition font-light"><?php echo esc_html( $review_count ); ?> Reviews</a>
        </div>
        <?php endif; ?>

        <!-- Price -->
        <div class="mt-6">
          <div class="flex items-baseline gap-3">
            <?php if ( $is_on_sale && $sale > 0 ) : ?>
              <span class="text-3xl font-bold text-white">&pound;<?php echo esc_html( number_format( $sale, 2, '.', '' ) ); ?></span>
              <span class="text-lg text-zinc-500 line-through">&pound;<?php echo esc_html( number_format( $regular, 2, '.', '' ) ); ?></span>
              <span class="bg-rose-500/10 text-rose-400 text-xs font-bold px-2.5 py-1 rounded-full border border-rose-500/20"><?php echo $discount; ?>% OFF</span>
            <?php else : ?>
              <span class="text-3xl font-bold text-white">&pound;<?php echo esc_html( number_format( $current, 2, '.', '' ) ); ?></span>
            <?php endif; ?>
          </div>
          <p class="text-sm text-zinc-500 mt-2 font-light">Inclusive of all taxes. Free shipping on orders over &pound;150.</p>
        </div>

        <hr class="border-white/10 my-8" />

        <!-- Short Description -->
        <?php if ( $product->get_short_description() ) : ?>
          <div class="text-zinc-300 leading-relaxed font-light"><?php echo wp_kses_post( $product->get_short_description() ); ?></div>
        <?php endif; ?>

        <form class="variations_form cart" action="" method="post" enctype="multipart/form-data"
              data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
              data-product_variations="<?php echo esc_attr( $product->is_type( 'variable' ) ? wp_json_encode( $product->get_available_variations() ) : 'false' ); ?>">

        <?php if ( ! empty( $color_options ) ) : ?>
        <!-- Color Selector -->
        <div class="mt-8">
          <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-white">Color: <span id="vvfsPdSelectedColor" class="font-normal text-zinc-400"><?php echo esc_html( $color_options[0]['name'] ); ?></span></span>
          </div>
          <div class="flex gap-3" id="vvfsPdColorSelector">
            <?php foreach ( $color_options as $i => $opt ) : ?>
              <button type="button" class="vvfs-pd-color-opt w-10 h-10 rounded-full border-2 <?php echo $i === 0 ? 'border-white shadow-lg' : 'border-transparent hover:border-white transition'; ?>"
                      data-color="<?php echo esc_attr( $opt['name'] ); ?>"
                      data-value="<?php echo esc_attr( $opt['slug'] ); ?>"
                      style="background:<?php echo esc_attr( $opt['hex'] ); ?>;"
                      title="<?php echo esc_attr( $opt['name'] ); ?>"></button>
            <?php endforeach; ?>
          </div>
          <input type="hidden" name="attribute_pa_color" class="vvfs-pd-attr-input" data-attribute="attribute_pa_color" value="<?php echo esc_attr( $color_options[0]['slug'] ); ?>" />
        </div>
        <?php endif; ?>

        <?php if ( ! empty( $size_options ) ) : ?>
        <!-- Size Selector -->
        <div class="mt-8">
          <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-white">Size: <span id="vvfsPdSelectedSize" class="font-normal text-zinc-400">Select a size</span></span>
            <a href="#" class="text-xs text-rose-400 hover:text-rose-300 transition font-medium">Size Guide</a>
          </div>
          <div class="flex flex-wrap gap-3" id="vvfsPdSizeSelector">
            <?php foreach ( $size_options as $opt ) : ?>
              <button type="button" class="vvfs-pd-size-opt px-5 py-2.5 rounded-xl text-sm font-medium border border-white/10 <?php echo $opt['in_stock'] ? 'bg-white/5 text-zinc-300 hover:bg-white/10 transition-all' : 'bg-white/5 text-zinc-300 line-through opacity-40 cursor-not-allowed'; ?>"
                      data-size="<?php echo esc_attr( $opt['name'] ); ?>"
                      data-value="<?php echo esc_attr( $opt['slug'] ); ?>"
                      <?php echo ! $opt['in_stock'] ? 'disabled' : ''; ?>><?php echo esc_html( $opt['name'] ); ?></button>
            <?php endforeach; ?>
          </div>
          <input type="hidden" name="attribute_pa_size" class="vvfs-pd-attr-input" data-attribute="attribute_pa_size" value="" />
        </div>
        <?php endif; ?>

        <input type="hidden" name="variation_id" class="vvfs-pd-variation-id" value="" />

        <!-- Quantity + Add to Cart -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
          <div class="flex items-center bg-[#18181b] border border-white/10 rounded-xl overflow-hidden">
            <button type="button" id="vvfsPdQtyMinus" class="px-4 py-3 text-zinc-400 hover:text-white hover:bg-white/5 transition text-sm"><i class="fa-solid fa-minus"></i></button>
            <input type="number" id="vvfsPdQtyValue" name="quantity" value="1" min="1" class="px-5 py-3 text-white font-semibold text-sm min-w-[3rem] text-center bg-transparent border-none outline-none" />
            <button type="button" id="vvfsPdQtyPlus" class="px-4 py-3 text-zinc-400 hover:text-white hover:bg-white/5 transition text-sm"><i class="fa-solid fa-plus"></i></button>
          </div>
          <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
                  class="btn-cart flex-1 justify-center py-4 text-base vvfs-pd-add-to-cart">
            <i class="fa-solid fa-bag-shopping"></i> Add to Cart — &pound;<?php echo esc_html( number_format( $current, 2, '.', '' ) ); ?>
          </button>
        </div>

        </form>

        <!-- Wishlist + Buy Now -->
        <div class="mt-4 flex gap-4">
          <button class="btn-cart-outline flex-1 justify-center py-3.5" id="vvfsPdWishlist">
            <i class="fa-regular fa-heart"></i> Add to Wishlist
          </button>
          <button class="btn-cart-outline flex-1 justify-center py-3.5" id="vvfsPdBuyNow">
            <i class="fa-solid fa-bolt"></i> Buy Now
          </button>
        </div>

        <!-- Stock & Shipping Info -->
        <div class="mt-8 space-y-3">
          <div class="flex items-center gap-3 text-sm">
            <?php if ( $stock_status === 'instock' ) : ?>
              <i class="fa-solid fa-check-circle text-emerald-400"></i>
              <span class="text-zinc-300 font-light">In Stock — Ready to ship</span>
            <?php else : ?>
              <i class="fa-solid fa-clock text-amber-400"></i>
              <span class="text-zinc-300 font-light">Available on backorder</span>
            <?php endif; ?>
          </div>
          <div class="flex items-center gap-3 text-sm">
            <i class="fa-solid fa-truck text-zinc-500"></i>
            <span class="text-zinc-300 font-light">Free shipping on orders over &pound;150</span>
          </div>
          <div class="flex items-center gap-3 text-sm">
            <i class="fa-solid fa-rotate-left text-zinc-500"></i>
            <span class="text-zinc-300 font-light">30-day free returns &amp; exchanges</span>
          </div>
          <div class="flex items-center gap-3 text-sm">
            <i class="fa-solid fa-shield-halved text-zinc-500"></i>
            <span class="text-zinc-300 font-light">Authenticity guaranteed</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Tabs -->
<section class="py-10 md:py-16 bg-[#0f0f11] w-full border-t border-white/10" id="vvfsPdTabs">
  <div class="w-full px-6 sm:px-10 lg:px-16">
    <div class="flex gap-1 border-b border-white/10 overflow-x-auto" id="vvfsPdTabHeaders">
      <button class="vvfs-pd-tab-btn px-6 py-4 text-sm font-semibold text-rose-500 border-b-2 border-rose-500 transition whitespace-nowrap" data-tab="description">Description</button>
      <?php if ( $product->get_short_description() || $product->get_description() ) : ?>
        <button class="vvfs-pd-tab-btn px-6 py-4 text-sm font-medium text-zinc-500 border-b-2 border-transparent hover:text-zinc-300 transition whitespace-nowrap" data-tab="specifications">Specifications</button>
      <?php endif; ?>
      <button class="vvfs-pd-tab-btn px-6 py-4 text-sm font-medium text-zinc-500 border-b-2 border-transparent hover:text-zinc-300 transition whitespace-nowrap" data-tab="reviews">Reviews (<?php echo esc_html( $review_count ); ?>)</button>
    </div>

    <!-- Tab: Description -->
    <div class="vvfs-pd-tab-content py-10" id="vvfsPdTab-description">
      <div class="max-w-3xl">
        <h3 class="text-xl font-bold text-white font-serif mb-4">About This Piece</h3>
        <div class="space-y-4 text-zinc-300 font-light leading-relaxed">
          <?php echo wp_kses_post( $product->get_description() ); ?>
        </div>
      </div>
    </div>

    <!-- Tab: Specifications -->
    <?php if ( $product->get_short_description() || $product->get_description() ) : ?>
    <div class="vvfs-pd-tab-content py-10 hidden" id="vvfsPdTab-specifications">
      <div class="max-w-3xl">
        <h3 class="text-xl font-bold text-white font-serif mb-6">Product Specifications</h3>
        <div class="space-y-0">
          <?php if ( $sku ) : ?>
          <div class="flex justify-between py-4 border-b border-white/5">
            <span class="text-zinc-500 text-sm">SKU</span>
            <span class="text-white text-sm font-medium"><?php echo esc_html( $sku ); ?></span>
          </div>
          <?php endif; ?>
          <?php foreach ( $attributes as $attr ) :
            if ( $attr->is_taxonomy() ) {
                $terms = wp_get_post_terms( $product->get_id(), $attr->get_name() );
                $val = ! is_wp_error( $terms ) ? implode( ', ', wp_list_pluck( $terms, 'name' ) ) : '';
            } else {
                $val = $attr->get_options();
                $val = is_array( $val ) ? implode( ', ', $val ) : $val;
            }
          ?>
          <div class="flex justify-between py-4 border-b border-white/5">
            <span class="text-zinc-500 text-sm"><?php echo esc_html( wc_attribute_label( $attr->get_name() ) ); ?></span>
            <span class="text-white text-sm font-medium"><?php echo esc_html( $val ); ?></span>
          </div>
          <?php endforeach; ?>
          <div class="flex justify-between py-4">
            <span class="text-zinc-500 text-sm">Category</span>
            <span class="text-white text-sm font-medium"><?php echo esc_html( $cat_name ); ?></span>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Tab: Reviews -->
    <div class="vvfs-pd-tab-content py-10 hidden" id="vvfsPdTab-reviews">
      <div class="max-w-3xl">
        <?php
        if ( comments_open() ) {
            comments_template();
        } else {
            echo '<p class="text-zinc-400">Reviews are not available for this product.</p>';
        }
        ?>
      </div>
    </div>
  </div>
</section>

<!-- Related Products -->
<?php
$related_ids = wc_get_related_products( $product->get_id(), 4 );
if ( ! empty( $related_ids ) ) :
$related_products = new WP_Query( array(
    'post_type'      => 'product',
    'posts_per_page' => 4,
    'post__in'       => $related_ids,
    'orderby'        => 'rand',
) );
?>
<section class="py-10 md:py-16 bg-[#0f0f11] w-full border-t border-white/10">
  <div class="w-full px-6 sm:px-10 lg:px-16">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
      <div>
        <span class="text-xs font-bold uppercase tracking-[0.3em] text-rose-500">You May Also Like</span>
        <h2 class="text-3xl sm:text-4xl font-bold mt-3 text-white font-serif">Related Products</h2>
      </div>
      <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center gap-2 text-rose-400 hover:text-rose-300 font-semibold text-sm transition duration-300">
        View All <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
      <?php while ( $related_products->have_posts() ) : $related_products->the_post();
        $rel_product = wc_get_product( get_the_ID() );
        if ( ! $rel_product || ! $rel_product->is_visible() ) continue;

        $rel_cats = get_the_terms( get_the_ID(), 'product_cat' );
        $rel_cat_label = '';
        if ( $rel_cats && ! is_wp_error( $rel_cats ) ) {
            foreach ( $rel_cats as $rc ) {
                if ( $rc->parent === 0 ) { $rel_cat_label = $rc->name; break; }
            }
        }
        $rel_rating = $rel_product->get_average_rating();
        $rel_reviews = $rel_product->get_review_count();
        $rel_sale = $rel_product->is_on_sale();
        $rel_regular = (float) $rel_product->get_regular_price();
        $rel_sale_price = (float) $rel_product->get_sale_price();
        $rel_current = (float) $rel_product->get_price();
      ?>
        <div class="product-card rounded-2xl p-5 md:p-6">
          <div class="relative overflow-hidden rounded-xl bg-zinc-900">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( 'woocommerce_single', array(
                  'class' => 'w-full h-72 md:h-80 object-cover',
              ) ); ?>
            </a>
            <?php if ( $rel_sale ) : ?>
              <span class="absolute top-4 left-4 bg-amber-500 text-black text-[0.65rem] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest shadow-md">Sale</span>
            <?php endif; ?>
            <button class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-md hover:bg-rose-500 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all text-base border border-white/10"><i class="fa-solid fa-heart"></i></button>
          </div>
          <div class="mt-5">
            <a href="<?php the_permalink(); ?>">
              <h3 class="text-base font-semibold text-white font-serif tracking-wide"><?php the_title(); ?></h3>
            </a>
            <?php if ( $rel_rating > 0 ) : ?>
            <div class="flex items-center gap-1 mt-2">
              <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                <span class="<?php echo $i <= $rel_rating ? 'star' : 'star-empty'; ?>"><i class="fa-<?php echo $i <= $rel_rating ? 'solid' : 'regular'; ?> fa-star text-xs"></i></span>
              <?php endfor; ?>
              <span class="text-xs text-zinc-400 ml-2 font-medium">(<?php echo esc_html( $rel_reviews ); ?>)</span>
            </div>
            <?php endif; ?>
            <div class="flex items-center justify-between mt-4">
              <div>
                <?php if ( $rel_sale && $rel_sale_price > 0 ) : ?>
                  <span class="text-xl font-bold text-white">&pound;<?php echo esc_html( number_format( $rel_sale_price, 2, '.', '' ) ); ?></span>
                  <span class="text-sm text-zinc-500 line-through ml-2">&pound;<?php echo esc_html( number_format( $rel_regular, 2, '.', '' ) ); ?></span>
                <?php else : ?>
                  <span class="text-xl font-bold text-white">&pound;<?php echo esc_html( number_format( $rel_current, 2, '.', '' ) ); ?></span>
                <?php endif; ?>
              </div>
              <a href="<?php echo esc_url( $rel_product->add_to_cart_url() ); ?>" class="btn-cart text-xs px-4 py-2.5 ajax_add_to_cart" data-product_id="<?php echo esc_attr( get_the_ID() ); ?>" data-quantity="1"><i class="fa-solid fa-bag-shopping"></i> Add</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); endif; ?>
