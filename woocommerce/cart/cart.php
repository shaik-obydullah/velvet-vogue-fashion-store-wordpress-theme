<?php
/**
 * Cart Template — Velvet Vogue Fashion Store
 *
 * @package Velvet_Vogue_Fashion_Store
 * @see     https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/templates/cart/cart.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<section class="py-10 md:py-16 bg-vvfs-bg w-full">
    <div class="w-full px-6 sm:px-10 lg:px-16">

        <h1 class="text-3xl sm:text-4xl font-bold text-white font-serif mb-10">
            <?php esc_html_e( 'Shopping Cart', 'velvet-vogue-fashion-store' ); ?></h1>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="flex flex-col lg:flex-row gap-10">

                <!-- Cart Items -->
                <div class="lg:w-2/3" id="cartItems">
                    <!-- Header row (desktop) -->
                    <div
                        class="hidden md:grid grid-cols-12 gap-4 pb-4 border-b border-white/10 text-xs font-semibold text-zinc-500 uppercase tracking-wider">
                        <div class="col-span-6"><?php esc_html_e( 'Product', 'velvet-vogue-fashion-store' ); ?></div>
                        <div class="col-span-2 text-center">
                            <?php esc_html_e( 'Price', 'velvet-vogue-fashion-store' ); ?></div>
                        <div class="col-span-2 text-center">
                            <?php esc_html_e( 'Quantity', 'velvet-vogue-fashion-store' ); ?></div>
                        <div class="col-span-2 text-right"><?php esc_html_e( 'Total', 'velvet-vogue-fashion-store' ); ?>
                        </div>
                    </div>

                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                    <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product   = $cart_item['data'];
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
            $quantity   = $cart_item['quantity'];
            $product_status = apply_filters( 'woocommerce_stock_status', $_product->get_stock_status() );
            ?>
                    <div
                        class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> py-5 border-b border-white/5">
                        <div class="grid grid-cols-12 gap-4 items-center">

                            <!-- Product image + name -->
                            <div class="col-span-6 flex items-center gap-4">
                                <div
                                    class="w-20 h-20 rounded-xl bg-zinc-900 border border-white/10 overflow-hidden flex-shrink-0">
                                    <?php
                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
                    if ( ! wc_cart_empty_product_link_url() ) {
                        echo '<a href="' . esc_url( $cart_item['data']->get_permalink() ) . '">' . $thumbnail . '</a>';
                    } else {
                        echo $thumbnail;
                    }
                    ?>
                                </div>
                                <div class="min-w-0">
                                    <?php
                    if ( ! wc_cart_empty_product_link_url() ) {
                        $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                        echo '<h4 class="text-sm font-semibold text-white truncate"><a href="' . esc_url( $cart_item['data']->get_permalink() ) . '">' . esc_html( $product_name ) . '</a></h4>';
                    } else {
                        echo '<h4 class="text-sm font-semibold text-white truncate">' . esc_html( $_product->get_name() ) . '</h4>';
                    }
                    ?>
                                    <?php
                    // Variation data
                    if ( $cart_item['variation'] ) {
                        $formatted_variation = wc_get_formatted_cart_item_data( $cart_item );
                        if ( $formatted_variation ) {
                            echo '<div class="text-xs text-zinc-500 mt-1">' . $formatted_variation . '</div>';
                        }
                    }
                    ?>
                                    <!-- Remove -->
                                    <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
                                        class="text-xs text-rose-500 hover:text-rose-400 transition mt-1 inline-block">
                                        <i class="fa-solid fa-trash-can text-[0.6rem]"></i>
                                        <?php esc_html_e( 'Remove', 'velvet-vogue-fashion-store' ); ?>
                                    </a>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-span-2 text-center">
                                <span class="text-sm text-white font-medium">
                                    <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                                </span>
                            </div>

                            <!-- Quantity -->
                            <div class="col-span-2 text-center">
                                <?php
                  $qv = array(
                      'input_name'  => "cart[{$cart_item_key}][qty]",
                      'input_value' => $cart_item['quantity'],
                      'max_value'   => $_product->get_max_purchase_quantity(),
                      'min_value'   => '0',
                      'product_name' => $_product->get_name(),
                  );
                  echo '<div class="woocommerce-cart-form__quantity">';
                  woocommerce_quantity_input( $qv, $_product );
                  echo '</div>';
                  ?>
                            </div>

                            <!-- Total -->
                            <div class="col-span-2 text-right">
                                <span class="text-sm font-bold text-white">
                                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                                </span>
                            </div>

                        </div>
                    </div>
                    <?php
        }
        ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>

                </div>

                <!-- Continue Shopping -->
                <div class="lg:hidden mt-4">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                        class="text-rose-500 hover:text-rose-400 transition text-sm font-medium">
                        <i
                            class="fa-solid fa-arrow-left mr-2"></i><?php esc_html_e( 'Continue Shopping', 'velvet-vogue-fashion-store' ); ?>
                    </a>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-[#18181b] rounded-2xl border border-white/10 p-6 md:p-8 sticky top-28">
                        <h3 class="text-lg font-bold text-white font-serif mb-6">
                            <?php esc_html_e( 'Order Summary', 'velvet-vogue-fashion-store' ); ?></h3>

                        <?php do_action( 'woocommerce_cart_before_totals' ); ?>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <span
                                    class="text-zinc-400 font-light"><?php esc_html_e( 'Subtotal', 'velvet-vogue-fashion-store' ); ?></span>
                                <span class="text-white font-medium"><?php wc_cart_totals_subtotal_html(); ?></span>
                            </div>

                            <?php foreach ( WC()->cart->get_shipping_methods() as $method ) : ?>
                            <div class="flex justify-between">
                                <span
                                    class="text-zinc-400 font-light"><?php echo esc_html( $method->get_label() ); ?></span>
                                <span
                                    class="text-emerald-400 font-medium"><?php echo wp_kses_post( WC()->cart->get_package_shipping_total( $method ) ); ?></span>
                            </div>
                            <?php endforeach; ?>

                            <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                            <div class="flex justify-between">
                                <span
                                    class="text-zinc-400 font-light"><?php esc_html_e( 'Tax', 'velvet-vogue-fashion-store' ); ?></span>
                                <span class="text-white font-medium"><?php wc_cart_totals_taxes_html(); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ( WC()->cart->has_discount() ) : ?>
                            <div class="flex justify-between">
                                <span
                                    class="text-zinc-400 font-light"><?php esc_html_e( 'Discount', 'velvet-vogue-fashion-store' ); ?></span>
                                <span class="text-rose-400 font-medium"><?php wc_cart_totals_discounts_html(); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <hr class="border-white/10 my-6" />

                        <div class="flex justify-between items-baseline">
                            <span
                                class="text-base font-bold text-white"><?php esc_html_e( 'Total', 'velvet-vogue-fashion-store' ); ?></span>
                            <span
                                class="text-2xl font-bold text-white"><?php wc_cart_totals_order_total_html(); ?></span>
                        </div>

                        <!-- Coupon -->
                        <?php if ( wc_coupons_enabled() ) : ?>
                        <div class="mt-6">
                            <div class="flex gap-2">
                                <input type="text" name="coupon_code" id="coupon_code"
                                    placeholder="<?php esc_attr_e( 'Promo code', 'velvet-vogue-fashion-store' ); ?>"
                                    class="flex-1 bg-zinc-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none focus:ring-2 focus:ring-rose-500" />
                                <button type="submit" name="apply_coupon"
                                    value="<?php esc_attr_e( 'Apply', 'velvet-vogue-fashion-store' ); ?>"
                                    class="bg-white/5 border border-white/10 text-zinc-300 text-sm font-semibold px-4 py-3 rounded-xl hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all">
                                    <?php esc_html_e( 'Apply', 'velvet-vogue-fashion-store' ); ?>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                            class="btn-cart w-full justify-center py-4 text-base mt-6">
                            <?php esc_html_e( 'Proceed to Checkout', 'velvet-vogue-fashion-store' ); ?> <i
                                class="fa-solid fa-arrow-right"></i>
                        </a>

                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                            class="flex items-center justify-center gap-2 text-sm text-zinc-400 hover:text-rose-500 transition mt-4 font-light">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            <?php esc_html_e( 'Continue Shopping', 'velvet-vogue-fashion-store' ); ?>
                        </a>

                        <!-- Trust badges -->
                        <div class="mt-6 pt-6 border-t border-white/10 space-y-3">
                            <div class="flex items-center gap-3 text-xs text-zinc-500">
                                <i class="fa-solid fa-shield-halved text-zinc-600"></i>
                                <span
                                    class="font-light"><?php esc_html_e( 'Secure SSL Encrypted Checkout', 'velvet-vogue-fashion-store' ); ?></span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-zinc-500">
                                <i class="fa-solid fa-truck text-zinc-600"></i>
                                <span
                                    class="font-light"><?php esc_html_e( 'Free shipping on orders over $150', 'velvet-vogue-fashion-store' ); ?></span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-zinc-500">
                                <i class="fa-solid fa-rotate-left text-zinc-600"></i>
                                <span
                                    class="font-light"><?php esc_html_e( '30-day free returns & exchanges', 'velvet-vogue-fashion-store' ); ?></span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

        <?php do_action( 'woocommerce_after_cart' ); ?>

    </div>
</section>