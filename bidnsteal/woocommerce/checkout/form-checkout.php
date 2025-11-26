<?php
/**
 * Checkout Form
 *
 * This template overrides the default WooCommerce checkout form to provide a dark, two‑column layout.
 * Customer details are displayed on the left and the order summary appears on the right.  The form
 * fields themselves use the default WooCommerce functions so that all checkout logic is preserved.
 *
 * To override the checkout form further, you could copy templates from WooCommerce and modify
 * individual field markup.  However, the CSS in shop.css applies dark styling to the inputs and
 * labels, so additional changes here are unnecessary.
 *
 * @package BidnSteal
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<div class="bs-checkout-container">
    <h1 class="bs-checkout-title"><?php esc_html_e( 'Checkout', 'bidnsteal' ); ?></h1>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
        <div class="bs-checkout-cols">
            <div class="bs-col-left">
                <?php if ( $checkout->get_checkout_fields() ) : ?>
                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                    <div id="customer_details">
                        <div class="woocommerce-billing-fields">
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                        </div>
                        <div class="woocommerce-shipping-fields">
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>
                    </div>
                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                <?php endif; ?>
            </div><!-- .bs-col-left -->

            <div class="bs-col-right">
                <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                <h3 id="order_review_heading"><?php esc_html_e( 'Your Order', 'bidnsteal' ); ?></h3>
                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>
                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            </div><!-- .bs-col-right -->
        </div><!-- .bs-checkout-cols -->
    </form>
</div><!-- .bs-checkout-container -->

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>