<?php
/**
 * Custom Checkout Form — matches checkout.html design
 *
 * @package Velvet_Vogue_Fashion_Store
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$checkout = WC()->checkout();
?>

<section class="vvfs-checkout-progress">
  <div class="vvfs-container">
    <div class="vvfs-progress-bar">
      <div class="vvfs-progress-step">
        <span class="vvfs-step-num done"><i class="fa-solid fa-check"></i></span>
        <span class="vvfs-step-label done">Cart</span>
      </div>
      <div class="vvfs-progress-line active"></div>
      <div class="vvfs-progress-step">
        <span class="vvfs-step-num active">2</span>
        <span class="vvfs-step-label active">Checkout</span>
      </div>
      <div class="vvfs-progress-line"></div>
      <div class="vvfs-progress-step">
        <span class="vvfs-step-num">3</span>
        <span class="vvfs-step-label">Confirmation</span>
      </div>
    </div>
  </div>
</section>

<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

<?php if ( WC()->cart->is_empty() ) : ?>
<section class="vvfs-checkout-section">
  <div class="vvfs-container">
    <div class="max-w-2xl mx-auto text-center py-20">
      <h2 class="text-2xl font-bold text-white font-serif mb-4">Your cart is empty</h2>
      <p class="text-zinc-400 mb-8">Add some items to your cart before checking out.</p>
      <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-cart px-8 py-3.5 text-sm">
        <i class="fa-solid fa-bag-shopping"></i> Continue Shopping
      </a>
    </div>
  </div>
</section>
<?php return; endif; ?>

<section class="vvfs-checkout-section">
  <div class="vvfs-container">
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

      <div class="woocommerce-notices-wrapper"><?php wc_print_notices(); ?></div>

      <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

      <div class="vvfs-checkout-grid">

        <!-- LEFT COLUMN: Forms -->
        <div class="vvfs-checkout-forms" id="customer_details">

          <!-- Contact Information -->
          <div class="vvfs-checkout-block">
            <h2 class="vvfs-block-title">
              <span class="vvfs-step-num active">1</span>
              Contact Information
            </h2>
            <div class="vvfs-form-grid">
              <?php
              woocommerce_form_field( 'billing_first_name', array(
                'type'        => 'text',
                'label'       => __( 'First Name', 'woocommerce' ),
                'placeholder' => __( 'John', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-first' ),
                'autocomplete' => 'given-name',
              ) );

              woocommerce_form_field( 'billing_last_name', array(
                'type'        => 'text',
                'label'       => __( 'Last Name', 'woocommerce' ),
                'placeholder' => __( 'Doe', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-last' ),
                'autocomplete' => 'family-name',
              ) );

              woocommerce_form_field( 'billing_email', array(
                'type'        => 'email',
                'label'       => __( 'Email Address', 'woocommerce' ),
                'placeholder' => __( 'john@example.com', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-wide' ),
                'autocomplete' => 'email',
              ) );

              woocommerce_form_field( 'billing_phone', array(
                'type'        => 'tel',
                'label'       => __( 'Phone Number', 'woocommerce' ),
                'placeholder' => __( '+1 (555) 000-0000', 'woocommerce' ),
                'required'    => false,
                'class'       => array( 'form-row-wide' ),
                'autocomplete' => 'tel',
              ) );
              ?>
            </div>
          </div>

          <!-- Shipping Address -->
          <div class="vvfs-checkout-block">
            <h2 class="vvfs-block-title">
              <span class="vvfs-step-num active">2</span>
              Shipping Address
            </h2>
            <div class="vvfs-form-grid">
              <?php
              woocommerce_form_field( 'billing_address_1', array(
                'type'        => 'text',
                'label'       => __( 'Street Address', 'woocommerce' ),
                'placeholder' => __( '123 Fashion Avenue', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-wide' ),
                'autocomplete' => 'address-line1',
              ) );

              woocommerce_form_field( 'billing_address_2', array(
                'type'        => 'text',
                'label'       => __( 'Apartment, suite, etc. (optional)', 'woocommerce' ),
                'placeholder' => __( 'Apt 4B', 'woocommerce' ),
                'required'    => false,
                'class'       => array( 'form-row-wide' ),
                'autocomplete' => 'address-line2',
              ) );

              woocommerce_form_field( 'billing_city', array(
                'type'        => 'text',
                'label'       => __( 'City', 'woocommerce' ),
                'placeholder' => __( 'New York', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-first' ),
                'autocomplete' => 'address-level2',
              ) );

              woocommerce_form_field( 'billing_state', array(
                'type'        => 'state',
                'label'       => __( 'State / Province', 'woocommerce' ),
                'placeholder' => __( 'NY', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-last' ),
                'autocomplete' => 'address-level1',
              ) );

              woocommerce_form_field( 'billing_postcode', array(
                'type'        => 'text',
                'label'       => __( 'ZIP / Postal Code', 'woocommerce' ),
                'placeholder' => __( '10001', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-first' ),
                'autocomplete' => 'postal-code',
              ) );

              woocommerce_form_field( 'billing_country', array(
                'type'        => 'country',
                'label'       => __( 'Country', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-last' ),
                'autocomplete' => 'country',
              ) );
              ?>
            </div>
          </div>

          <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>

        </div>

        <!-- RIGHT COLUMN: Order Summary -->
        <div class="vvfs-checkout-sidebar">
          <div class="vvfs-order-summary">
            <h3 class="vvfs-summary-title">Order Summary</h3>

            <div class="vvfs-summary-items">
              <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
              ?>
                <div class="vvfs-summary-item">
                  <div class="vvfs-item-thumb">
                    <?php echo wp_kses_post( $_product->get_image( 'woocommerce_thumbnail' ) ); ?>
                  </div>
                  <div class="vvfs-item-info">
                    <h4 class="vvfs-item-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></h4>
                    <p class="vvfs-item-meta">&times; <?php echo esc_html( $cart_item['quantity'] ); ?></p>
                  </div>
                  <span class="vvfs-item-price"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) ); ?></span>
                </div>
              <?php endif; endforeach; ?>
            </div>

            <hr class="vvfs-divider" />

            <div id="order_review" class="woocommerce-checkout-review-order">
              <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
              <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
              <?php do_action( 'woocommerce_checkout_order_review' ); ?>
              <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            </div>

          </div>
        </div>

      </div>

      <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
      <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

    </form>
  </div>
</section>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
