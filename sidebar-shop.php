<?php
/**
 * Shop Sidebar — matches product_listing.html filters
 *
 * @package Velvet_Vogue_Fashion_Store
 */
?>
<aside class="vvfs-shop-sidebar">
  <div class="vvfs-sidebar-inner">

    <!-- Categories -->
    <div class="vvfs-sidebar-card">
      <h4 class="vvfs-sidebar-title"><?php esc_html_e( 'Categories', 'velvet-vogue-fashion-store' ); ?></h4>
      <div class="vvfs-sidebar-categories">
        <?php
        $product_categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => 0,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ) );
        if ( ! is_wp_error( $product_categories ) && ! empty( $product_categories ) ) :
            $current_cat = get_queried_object();
            $is_all = is_shop() || ( is_product_category() && ! $current_cat->parent );
        ?>
          <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
             class="vvfs-filter-btn <?php echo $is_all ? 'active' : ''; ?>">
            <i class="fa-solid fa-border-all text-xs"></i>
            <?php esc_html_e( 'All Products', 'velvet-vogue-fashion-store' ); ?>
          </a>
          <?php foreach ( $product_categories as $cat ) :
              $is_active = is_product_category( $cat->slug );
              $cat_url   = get_term_link( $cat );
              if ( is_wp_error( $cat_url ) ) continue;
              $icon = 'fa-venus';
              if ( strtolower( $cat->name ) === 'men' ) $icon = 'fa-mars';
              elseif ( strtolower( $cat->name ) === 'accessories' ) $icon = 'fa-gem';
              elseif ( strtolower( $cat->name ) === 'sale' ) $icon = 'fa-tag';
          ?>
            <a href="<?php echo esc_url( $cat_url ); ?>"
               class="vvfs-filter-btn <?php echo $is_active ? 'active' : ''; ?>">
              <i class="fa-solid <?php echo esc_attr( $icon ); ?> text-xs"></i>
              <?php echo esc_html( $cat->name ); ?>
              <span class="ml-auto text-zinc-600 text-xs"><?php echo esc_html( $cat->count ); ?></span>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Price Range -->
    <div class="vvfs-sidebar-card">
      <h4 class="vvfs-sidebar-title"><?php esc_html_e( 'Price Range', 'velvet-vogue-fashion-store' ); ?></h4>
      <div class="space-y-4">
        <div class="flex items-center justify-between text-sm">
          <span class="text-zinc-400 font-light">&pound;0</span>
          <span class="text-white font-semibold">&pound;<span id="sidebarPriceVal">300</span></span>
        </div>
        <input type="range" id="sidebarPriceRange" min="0" max="300" value="300" step="10"
               class="w-full accent-rose-500 cursor-pointer" />
        <form class="flex gap-3" method="get" action="" onsubmit="return false;">
          <input type="number" name="min_price" id="priceMin" placeholder="Min" min="0" max="300"
                 class="w-1/2 bg-zinc-900 border border-white/10 text-white text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-rose-500" />
          <input type="number" name="max_price" id="priceMax" placeholder="Max" min="0" max="300" value="300"
                 class="w-1/2 bg-zinc-900 border border-white/10 text-white text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-rose-500" />
        </form>
      </div>
    </div>

    <!-- Sizes -->
    <div class="vvfs-sidebar-card">
      <h4 class="vvfs-sidebar-title"><?php esc_html_e( 'Sizes', 'velvet-vogue-fashion-store' ); ?></h4>
      <div class="flex flex-wrap gap-2">
        <?php foreach ( array( 'XS', 'S', 'M', 'L', 'XL', 'XXL' ) as $size ) : ?>
          <button class="vvfs-size-btn" data-size="<?php echo esc_attr( $size ); ?>"><?php echo esc_html( $size ); ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Colors -->
    <div class="vvfs-sidebar-card">
      <h4 class="vvfs-sidebar-title"><?php esc_html_e( 'Colors', 'velvet-vogue-fashion-store' ); ?></h4>
      <div class="flex flex-wrap gap-3">
        <?php
        $colors = array(
            'black'    => '#000000',
            'white'    => '#ffffff',
            'beige'    => '#d4c5a9',
            'navy'     => '#1e2a5e',
            'burgundy' => '#722f37',
            'olive'    => '#556b2f',
            'camel'    => '#c19a6b',
            'charcoal' => '#36454f',
        );
        foreach ( $colors as $name => $hex ) : ?>
          <button class="vvfs-color-btn" data-color="<?php echo esc_attr( $name ); ?>" title="<?php echo esc_attr( ucfirst( $name ) ); ?>">
            <span style="background:<?php echo esc_attr( $hex ); ?>; <?php echo $name === 'black' ? 'border:1px solid rgba(255,255,255,0.2);' : ''; ?>"></span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Clear Filters -->
    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
       class="vvfs-clear-filters">
      <i class="fa-solid fa-rotate-left mr-2"></i><?php esc_html_e( 'Clear All Filters', 'velvet-vogue-fashion-store' ); ?>
    </a>

  </div>
</aside>
