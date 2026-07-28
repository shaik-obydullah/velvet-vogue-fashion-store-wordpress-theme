<?php
/**
 * Thank You / Order Received Template — matches thankyou.html design
 *
 * @package Velvet_Vogue_Fashion_Store
 */

defined( 'ABSPATH' ) || exit;

if ( ! $order ) {
    echo '<p>Invalid order.</p>';
    exit;
}

$order_id      = $order->get_id();
$customer_note = $order->get_customer_note();
$needs_payment = $order->needs_payment();
$completed     = 'wc-completed' === $order->get_status();
$email         = $order->get_billing_email();
?>

<!-- Progress Bar — all steps done -->
<section class="vvfs-checkout-progress">
  <div class="vvfs-container">
    <div class="vvfs-progress-bar">
      <div class="vvfs-progress-step">
        <span class="vvfs-step-num done"><i class="fa-solid fa-check"></i></span>
        <span class="vvfs-step-label done">Cart</span>
      </div>
      <div class="vvfs-progress-line active"></div>
      <div class="vvfs-progress-step">
        <span class="vvfs-step-num done"><i class="fa-solid fa-check"></i></span>
        <span class="vvfs-step-label done">Checkout</span>
      </div>
      <div class="vvfs-progress-line active"></div>
      <div class="vvfs-progress-step">
        <span class="vvfs-step-num done"><i class="fa-solid fa-check"></i></span>
        <span class="vvfs-step-label done">Confirmed</span>
    </div>
  </div>
</section>

<!-- Thank You Content -->
<section class="vvfs-checkout-section vvfs-thankyou">
  <div class="vvfs-container">
    <div class="max-w-2xl mx-auto text-center">

      <!-- Success Icon -->
      <div class="relative inline-block mb-8">
        <div class="w-24 h-24 rounded-full bg-emerald-500/10 border-2 border-emerald-500/30 flex items-center justify-center mx-auto animate-check">
          <i class="fa-solid fa-check text-emerald-400 text-4xl"></i>
        </div>
      </div>

      <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white font-serif animate-fade-up" style="animation-delay: 0.2s; opacity: 0;">
        <?php esc_html_e( 'Thank You!', 'velvet-vogue-fashion-store' ); ?>
      </h1>
      <p class="text-zinc-400 text-lg mt-4 font-light animate-fade-up" style="animation-delay: 0.3s; opacity: 0;">
        <?php esc_html_e( 'Your order has been placed successfully.', 'velvet-vogue-fashion-store' ); ?>
      </p>

      <!-- Order Number -->
      <div class="mt-8 inline-block bg-[#18181b] border border-white/10 rounded-2xl px-8 py-5 animate-fade-up" style="animation-delay: 0.4s; opacity: 0;">
        <span class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-500 block mb-1"><?php esc_html_e( 'Order Number', 'velvet-vogue-fashion-store' ); ?></span>
        <span class="text-2xl font-bold text-white font-serif tracking-wider">#<?php echo esc_html( $order->get_order_number() ); ?></span>
      </div>

      <?php if ( $email ) : ?>
      <p class="text-zinc-500 text-sm mt-6 font-light max-w-md mx-auto animate-fade-up" style="animation-delay: 0.5s; opacity: 0;">
        <?php printf( esc_html__( 'A confirmation email has been sent to %s with your order details and tracking information.', 'velvet-vogue-fashion-store' ), '<span class="text-zinc-300 font-medium">' . esc_html( $email ) . '</span>' ); ?>
      </p>
      <?php endif; ?>

      <?php if ( $customer_note ) : ?>
        <div class="mt-6 bg-[#18181b] border border-white/10 rounded-2xl px-6 py-4 text-left animate-fade-up" style="animation-delay: 0.55s; opacity: 0;">
          <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block mb-1"><?php esc_html_e( 'Note', 'velvet-vogue-fashion-store' ); ?></span>
          <p class="text-sm text-zinc-300 font-light"><?php echo esc_html( $customer_note ); ?></p>
        </div>
      <?php endif; ?>

      <?php if ( $needs_payment ) : ?>
        <div class="mt-8 animate-fade-up" style="animation-delay: 0.55s; opacity: 0;">
          <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn-cart px-8 py-3.5 text-sm">
            <i class="fa-solid fa-credit-card"></i> <?php esc_html_e( 'Pay Now', 'velvet-vogue-fashion-store' ); ?>
          </a>
        </div>
      <?php endif; ?>

      <!-- Order Details Card -->
      <div class="mt-10 bg-[#18181b] rounded-2xl border border-white/10 p-6 md:p-8 text-left animate-fade-up" style="animation-delay: 0.6s; opacity: 0;">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

          <!-- Left: Info -->
          <div class="space-y-4">
            <div>
              <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block mb-1"><?php esc_html_e( 'Payment Method', 'velvet-vogue-fashion-store' ); ?></span>
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-zinc-400"></i>
                <span class="text-sm text-white"><?php echo esc_html( $order->get_payment_method_title() ); ?></span>
              </div>
            </div>
            <div>
              <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block mb-1"><?php esc_html_e( 'Shipping Address', 'velvet-vogue-fashion-store' ); ?></span>
              <p class="text-sm text-zinc-300 font-light leading-relaxed">
                <?php echo esc_html( $order->get_formatted_shipping_address() ); ?>
              </p>
            </div>
            <div>
              <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block mb-1"><?php esc_html_e( 'Shipping Method', 'velvet-vogue-fashion-store' ); ?></span>
              <p class="text-sm text-zinc-300 font-light"><?php echo esc_html( $order->get_shipping_method() ); ?></p>
            </div>
          </div>

          <!-- Right: Items -->
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block mb-3"><?php esc_html_e( 'Items Ordered', 'velvet-vogue-fashion-store' ); ?></span>
            <div class="space-y-3">
              <?php foreach ( $order->get_items() as $item ) :
                $product = $item->get_product();
                $img_url = '';
                if ( $product ) {
                    $img_id = $product->get_image_id();
                    $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src( 'woocommerce_thumbnail' );
                }
              ?>
              <div class="flex items-center gap-3">
                <?php if ( $img_url ) : ?>
                <div class="w-12 h-12 rounded-lg bg-zinc-900 border border-white/10 overflow-hidden flex-shrink-0">
                  <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $item->get_name() ); ?>" class="w-full h-full object-cover" />
                </div>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                  <h4 class="text-xs font-semibold text-white truncate"><?php echo esc_html( $item->get_name() ); ?></h4>
                  <p class="text-[0.65rem] text-zinc-500">&times; <?php echo esc_html( $item->get_quantity() ); ?></p>
                </div>
                <span class="text-xs text-white font-medium"><?php echo wp_strip_all_tags( $order->get_formatted_line_subtotal( $item ) ); ?></span>
              </div>
              <?php endforeach; ?>
            </div>

            <hr class="border-white/10 my-4" />

            <div class="space-y-2 text-xs">
              <div class="flex justify-between">
                <span class="text-zinc-500"><?php esc_html_e( 'Subtotal', 'velvet-vogue-fashion-store' ); ?></span>
                <span class="text-zinc-300"><?php echo wp_strip_all_tags( wc_price( $order->get_subtotal() ) ); ?></span>
              </div>
              <?php if ( $order->get_shipping_total() > 0 ) : ?>
              <div class="flex justify-between">
                <span class="text-zinc-500"><?php esc_html_e( 'Shipping', 'velvet-vogue-fashion-store' ); ?></span>
                <span class="text-zinc-300"><?php echo wp_strip_all_tags( wc_price( $order->get_shipping_total() ) ); ?></span>
              </div>
              <?php else : ?>
              <div class="flex justify-between">
                <span class="text-zinc-500"><?php esc_html_e( 'Shipping', 'velvet-vogue-fashion-store' ); ?></span>
                <span class="text-emerald-400"><?php esc_html_e( 'Free', 'velvet-vogue-fashion-store' ); ?></span>
              </div>
              <?php endif; ?>
              <?php if ( $order->get_total_tax() > 0 ) : ?>
              <div class="flex justify-between">
                <span class="text-zinc-500"><?php esc_html_e( 'Tax', 'velvet-vogue-fashion-store' ); ?></span>
                <span class="text-zinc-300"><?php echo wp_strip_all_tags( wc_price( $order->get_total_tax() ) ); ?></span>
              </div>
              <?php endif; ?>
              <div class="flex justify-between pt-2 border-t border-white/10">
                <span class="text-white font-bold text-sm"><?php esc_html_e( 'Total', 'velvet-vogue-fashion-store' ); ?></span>
                <span class="text-white font-bold text-sm"><?php echo wp_strip_all_tags( $order->get_formatted_order_total() ); ?></span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Estimated Delivery -->
      <div class="mt-8 bg-[#18181b] rounded-2xl border border-white/10 p-6 animate-fade-up" style="animation-delay: 0.7s; opacity: 0;">
        <div class="flex items-center justify-center gap-4">
          <i class="fa-solid fa-truck text-rose-500 text-xl"></i>
          <div class="text-left">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 block"><?php esc_html_e( 'Estimated Delivery', 'velvet-vogue-fashion-store' ); ?></span>
            <span class="text-white font-semibold">
              <?php
              $start = gmdate( 'F j', strtotime( '+5 days' ) );
              $end   = gmdate( 'F j, Y', strtotime( '+8 days' ) );
              echo esc_html( $start . ' - ' . $end );
              ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center animate-fade-up" style="animation-delay: 0.8s; opacity: 0;">
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-cart px-8 py-3.5 text-sm">
          <i class="fa-solid fa-bag-shopping"></i> <?php esc_html_e( 'Continue Shopping', 'velvet-vogue-fashion-store' ); ?>
        </a>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-cart-outline px-8 py-3.5 text-sm">
          <i class="fa-solid fa-house"></i> <?php esc_html_e( 'Back to Home', 'velvet-vogue-fashion-store' ); ?>
        </a>
      </div>

    </div>
  </div>
</section>

<?php
do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
do_action( 'woocommerce_thankyou', $order->get_id() );
?>
