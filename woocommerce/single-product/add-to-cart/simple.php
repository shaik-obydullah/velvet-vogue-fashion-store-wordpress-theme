<?php
/**
 * Simple Product Add to Cart — Velvet Vogue Fashion Store
 *
 * @package Velvet_Vogue_Fashion_Store
 * @see     https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/templates/single-product/add-to-cart/simple.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_add_to_cart_form' );

$product = $product ? $product : wc_get_product( $product_id );

if ( ! $product ) return;

$quantity         = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : $product->get_min_purchase_quantity();
$maximum_quantity = $product->get_max_purchase_quantity();
$min_quantity     = $product->get_min_purchase_quantity();
$purchase_min     = $product->get_min_purchase_quantity();

do_action( 'woocommerce_before_add_to_cart_button' );
?>

<div class="woocommerce-add-to-cart-variable" style="margin-bottom: 1.5rem;">
  <?php
  // Quantity
  if ( $product->is_sold_individually() ) {
      $product_quantity = 1;
  } else {
      $product_quantity = $quantity ? $quantity : $min_quantity;
  }
  ?>
  <div class="quantity" style="margin-bottom: 1rem;">
    <label for="quantity_<?php echo esc_attr( $product->get_id() ); ?>" class="screen-reader-text"><?php esc_html_e( 'Quantity', 'velvet-vogue-fashion-store' ); ?></label>
    <input type="number"
           id="quantity_<?php echo esc_attr( $product->get_id() ); ?>"
           class="input-text qty text"
           step="<?php echo esc_attr( $product->get_quantity_input_step() ); ?>"
           min="<?php echo esc_attr( $min_quantity ); ?>"
           max="<?php echo $maximum_quantity ? esc_attr( $maximum_quantity ) : ''; ?>"
           name="quantity"
           value="<?php echo esc_attr( $product_quantity ); ?>"
           inputmode="numeric"
           autocomplete="off"
           style="width: 4.5rem; background: #18181b; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.5rem; padding: 0.65rem; text-align: center; font-size: 1rem;" />
  </div>

  <button type="submit"
          name="add-to-cart"
          value="<?php echo esc_attr( $product->get_id() ); ?>"
          class="single_add_to_cart_button btn-cart"
          style="width: 100%; justify-content: center; padding: 0.95rem 2rem; font-size: 0.95rem;">
    <i class="fa-solid fa-bag-shopping"></i>
    <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
  </button>
</div>

<?php
do_action( 'woocommerce_after_add_to_cart_button' );
do_action( 'woocommerce_after_add_to_cart_form' );
