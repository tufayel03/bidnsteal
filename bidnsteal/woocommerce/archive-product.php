<?php
/**
 * The template for displaying product archive pages (shop page).
 *
 * This template has been customised to match the Protech style shown by the user.  It includes a
 * sidebar with filters, a header with breadcrumbs and sorting options, category tabs and a custom
 * product grid.  The individual product items are output via the 'content-product-protech.php' template.
 *
 * @package BidnSteal
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );
?>

<div class="bs-shop-container">

    <!-- Sidebar with active filters and filtering widgets -->
    <aside class="bs-shop-sidebar">
        <h3 class="sidebar-title"><?php esc_html_e( 'Filters', 'bidnsteal' ); ?></h3>

        <!-- Active filters displayed as chips -->
        <div class="bs-active-filters">
            <?php echo do_shortcode( '[woocommerce_product_filter_active]' ); ?>
        </div>

        <!-- Price filter -->
        <h4 class="filter-heading"><?php esc_html_e( 'Price', 'bidnsteal' ); ?></h4>
        <?php echo do_shortcode( '[woocommerce_product_filter_price]' ); ?>

        <!-- Attribute filters (example for brand).  Adjust the attribute slug to match your site. -->
        <h4 class="filter-heading"><?php esc_html_e( 'Brand', 'bidnsteal' ); ?></h4>
        <?php echo do_shortcode( '[woocommerce_product_filter_attribute attribute="brand"]' ); ?>

        <!-- Category filter -->
        <h4 class="filter-heading"><?php esc_html_e( 'Category', 'bidnsteal' ); ?></h4>
        <?php echo do_shortcode( '[woocommerce_product_filter_category]' ); ?>
    </aside>

    <!-- Main content area -->
    <main class="bs-shop-main">
        <!-- Shop header: page title, breadcrumb and sort dropdown -->
        <div class="bs-shop-header">
            <div>
                <h1 class="shop-title"><?php woocommerce_page_title(); ?></h1>
                <nav class="bs-breadcrumb">
                    <?php woocommerce_breadcrumb(); ?>
                </nav>
            </div>
            <div class="bs-shop-sort">
                <?php woocommerce_catalog_ordering(); ?>
            </div>
        </div>

        <!-- Category tabs (static examples; adapt to your product categories if needed) -->
        <div class="bs-category-tabs">
            <a href="#" class="active"><?php esc_html_e( 'All items', 'bidnsteal' ); ?></a>
            <a href="#">Smartphones</a>
            <a href="#">Kitchen</a>
            <a href="#">Game Console</a>
        </div>

        <?php if ( woocommerce_product_loop() ) : ?>

            <ul class="products bs-product-grid">
            <?php
            if ( wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();
                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part( 'content', 'product-protech' );
                }
            }
            ?>
            </ul>

            <?php
            /**
             * Hook: woocommerce_after_shop_loop.
             *
             * @hooked woocommerce_pagination - 10
             */
            do_action( 'woocommerce_after_shop_loop' );
            ?>

        <?php else : ?>

            <?php wc_get_template( 'loop/no-products-found.php' ); ?>

        <?php endif; ?>
    </main>

</div><!-- .bs-shop-container -->

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs)
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );