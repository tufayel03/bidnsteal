<?php
/**
 * Cart Page
 *
 * This template overrides the default WooCommerce cart template to provide a dark themed cart
 * with customised headings and layout.  It relies on CSS in shop.css to style the table and
 * cart totals section.
 *
 * @package BidnSteal
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

?>

<div class="bs-cart-container">
    <h1 class="bs-cart-title"><?php esc_html_e( 'Shopping Cart', 'bidnsteal' ); ?></h1>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>
        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents bs-cart-table" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-remove">&nbsp;</th>
                    <th class="product-thumbnail">&nbsp;</th>
                    <th class="product-name"><?php esc_html_e( 'Product', 'bidnsteal' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Price', 'bidnsteal' ); ?></th>
                    <th class="product-quantity"><?php esc_html_e( 'Quantity', 'bidnsteal' ); ?></th>
                    <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'bidnsteal' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php do_action( 'woocommerce_cart_contents' ); ?>
                <tr>
                    <td colspan="6" class="actions">
                        <?php do_action( 'woocommerce_cart_actions' ); ?>
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>

    <div class="cart-collaterals bs-cart-collaterals">
        <?php do_action( 'woocommerce_cart_collaterals' ); ?>
    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>