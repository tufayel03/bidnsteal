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

        <!-- Price filter -->
        <div class="bs-filter-block">
            <h4 class="filter-heading"><?php esc_html_e( 'Price', 'bidnsteal' ); ?></h4>
            <div class="bs-price-slider">
                <?php the_widget( 'WC_Widget_Price_Filter', [], [
                    'before_widget' => '',
                    'after_widget'  => '',
                    'before_title'  => '',
                    'after_title'   => '',
                ] ); ?>
            </div>
        </div>

        <!-- Category filter -->
        <div class="bs-filter-block">
            <h4 class="filter-heading"><?php esc_html_e( 'Category', 'bidnsteal' ); ?></h4>
            <?php
            $selected_cats = isset( $_GET['bs_categories'] ) && is_array( $_GET['bs_categories'] ) ? array_map( 'sanitize_title', (array) wp_unslash( $_GET['bs_categories'] ) ) : [];
            $product_cats  = get_terms(
                [
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                    'orderby'    => 'name',
                ]
            );
            ?>

            <?php if ( $product_cats && ! is_wp_error( $product_cats ) ) : ?>
                <form class="bs-category-filter" method="get">
                    <div class="bs-category-list">
                        <?php foreach ( $product_cats as $cat ) : ?>
                            <label class="bs-checkbox">
                                <input type="checkbox" name="bs_categories[]" value="<?php echo esc_attr( $cat->slug ); ?>" <?php checked( in_array( $cat->slug, $selected_cats, true ) ); ?> />
                                <span><?php echo esc_html( $cat->name ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <?php
                    // Preserve other query string arguments like sorting and pagination.
                    foreach ( $_GET as $key => $value ) {
                        if ( 'bs_categories' === $key ) {
                            continue;
                        }

                        if ( is_array( $value ) ) {
                            foreach ( $value as $val ) {
                                printf( '<input type="hidden" name="%s[]" value="%s" />', esc_attr( $key ), esc_attr( wp_unslash( $val ) ) );
                            }
                        } else {
                            printf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $key ), esc_attr( wp_unslash( $value ) ) );
                        }
                    }
                    ?>

                    <button type="submit" class="bs-apply-filters"><?php esc_html_e( 'Apply Filters', 'bidnsteal' ); ?></button>
                </form>
            <?php else : ?>
                <p class="bs-empty-filter"><?php esc_html_e( 'No categories available.', 'bidnsteal' ); ?></p>
            <?php endif; ?>
        </div>
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
            <?php
            $shop_url   = wc_get_page_permalink( 'shop' );
            $current    = get_queried_object();
            $active_cat = ( $current instanceof WP_Term && 'product_cat' === $current->taxonomy ) ? $current->slug : '';

            echo '<a class="' . ( $active_cat ? '' : 'active' ) . '" href="' . esc_url( $shop_url ) . '">' . esc_html__( 'All Categories', 'bidnsteal' ) . '</a>';

            $category_tabs = get_terms(
                [
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                    'parent'     => 0,
                    'orderby'    => 'name',
                ]
            );

            if ( $category_tabs && ! is_wp_error( $category_tabs ) ) {
                foreach ( $category_tabs as $cat ) {
                    $active = $active_cat === $cat->slug ? 'active' : '';
                    echo '<a class="' . esc_attr( $active ) . '" href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
                }
            }
            ?>
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
