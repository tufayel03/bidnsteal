<?php
/**
 * The Template for displaying all single products.
 *
 * This template has been customised to provide a hero‑style layout for single products.  It places
 * the product image on the left and the summary (title, rating, price, short description and add
 * to cart button) on the right.  Below the hero section the standard WooCommerce content (tabs,
 * upsells, related products) is displayed.  The CSS classes defined here are styled in the
 * theme's stylesheet.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product/single-product.php
 *
 * @package BidnSteal
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );

while ( have_posts() ) :
    the_post();

    $product = wc_get_product( get_the_ID() );
    ?>

    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'bs-single-product', $product ); ?>>
        <div class="bs-single-container">
            <!-- Product images -->
            <div class="bs-single-image">
                <?php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                ?>
            </div>

            <!-- Product summary -->
            <div class="bs-single-summary">
                <?php the_title( '<h1 class="bs-single-title">', '</h1>' ); ?>
                <div class="bs-single-rating">
                    <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                    <?php if ( $product->get_review_count() ) : ?>
                        <span class="bs-rating-count">(<?php echo esc_html( $product->get_review_count() ); ?> <?php esc_html_e( 'reviews', 'bidnsteal' ); ?>)</span>
                    <?php endif; ?>
                </div>
                <div class="bs-single-price">
                    <?php echo $product->get_price_html(); ?>
                </div>
                <div class="bs-single-short">
                    <?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ); ?>
                </div>
                <div class="bs-single-cart">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>
        </div>
        <!-- Description and additional information -->
        <div class="bs-single-description">
            <?php
            /**
             * Hook: woocommerce_after_single_product_summary.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */
            do_action( 'woocommerce_after_single_product_summary' );
            ?>
        </div>
    </div>

<?php endwhile; // end of the loop. ?>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs)
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );