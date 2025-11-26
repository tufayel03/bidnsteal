<?php
/**
 * The template for displaying product content within loops for the Protech style card.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-protech.php.
 * It produces a custom card featuring an image, sale badge, brand, title, rating, price and an add
 * to cart button overlaying the image.  The markup closely follows the design provided by the user.
 *
 * @package BidnSteal
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Determine the discount percentage if the product is on sale.
$sale_badge = '';
if ( $product->is_on_sale() ) {
    $regular_price = floatval( $product->get_regular_price() );
    $sale_price    = floatval( $product->get_sale_price() );
    if ( $regular_price > 0 && $sale_price > 0 && $sale_price < $regular_price ) {
        $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
        $sale_badge = sprintf( /* translators: %s: percentage discount */ esc_html__( 'Sale %s%%', 'bidnsteal' ), $discount );
    }
}

// Fetch brand attribute if available.  Adjust the attribute slug to match your site (e.g. 'pa_brand' if
// using a custom attribute).  Here we assume an attribute with slug 'brand'.
$brand = $product->get_attribute( 'brand' );

?>

<li <?php wc_product_class( 'bs-product', $product ); ?>>
    <div class="bs-card-image">
        <a href="<?php the_permalink(); ?>">
            <?php echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' ); ?>
        </a>
        <?php if ( $sale_badge ) : ?>
            <span class="bs-sale-badge"><?php echo esc_html( $sale_badge ); ?></span>
        <?php endif; ?>
        <!-- Add to cart button uses WooCommerce AJAX classes for proper behaviour -->
        <?php
        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" class="bs-cart-btn add_to_cart_button ajax_add_to_cart product_type_%s" aria-label="%s" rel="nofollow"><i class="fas fa-shopping-cart"></i></a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product->get_id() ),
                esc_attr( $product->get_sku() ),
                esc_attr( $product->get_type() ),
                esc_attr( $product->add_to_cart_description() )
            ),
        $product );
        ?>
    </div>
    <div class="bs-card-body">
        <?php if ( $brand ) : ?>
            <span class="bs-brand"><?php echo esc_html( $brand ); ?></span>
        <?php endif; ?>
        <h3 class="bs-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <div class="bs-rating">
            <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
            <?php if ( $product->get_review_count() ) : ?>
                <span class="rating-value"><?php echo esc_html( number_format( $product->get_average_rating(), 1 ) ); ?></span>
            <?php endif; ?>
        </div>
        <div class="bs-price">
            <?php echo $product->get_price_html(); ?>
        </div>
    </div>
</li>