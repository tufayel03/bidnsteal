<?php
/*
 * Theme functions and definitions for the BidnSteal theme.
 *
 * This file sets up theme defaults and registers support for WordPress features. It also loads
 * external stylesheets and scripts and registers the primary navigation menu used throughout the theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Set up theme defaults and register support for various WordPress features.
 */
function bidnsteal_setup() {
    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Enable WooCommerce support for product pages and shop functionality.
    add_theme_support( 'woocommerce' );

    // Register navigation menus.
    register_nav_menus( [
        'primary_menu' => __( 'Primary Menu', 'bidnsteal' ),
    ] );
}
add_action( 'after_setup_theme', 'bidnsteal_setup' );

/**
 * Enqueue theme stylesheets and scripts.
 */
function bidnsteal_scripts() {
    $theme_uri = get_template_directory_uri();

    // Google Fonts fallback (Inter mimics Neue Haas Grotesk fairly well).
    wp_enqueue_style( 'bidnsteal-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap', [], null );

    // Font Awesome for icons.
    wp_enqueue_style( 'bidnsteal-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0' );

    // Main stylesheet containing global styles and layout.
    wp_enqueue_style( 'bidnsteal-main-style', $theme_uri . '/assets/css/main.css', [], '1.0' );

    // Glass styling for translucent elements.
    wp_enqueue_style( 'bidnsteal-glass-style', $theme_uri . '/assets/css/glass.css', [], '1.0' );

    // Shop specific stylesheet loaded on shop, category, tag, single product, cart and checkout pages.
    if ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) || is_cart() || is_checkout() || is_account_page() ) {
        wp_enqueue_style( 'bidnsteal-shop-style', $theme_uri . '/assets/css/shop.css', [], '1.0' );
    }

    // Load our custom JavaScript file. It depends on jQuery to handle the mobile menu toggle.
    wp_enqueue_script( 'bidnsteal-main-js', $theme_uri . '/assets/js/main.js', [ 'jquery' ], '1.0', true );

    // Ensure core WooCommerce add to cart behaviour is available for custom product buttons.
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'wc-add-to-cart' );
        wp_enqueue_script( 'wc-add-to-cart-variation' );
        wp_enqueue_script( 'wc-cart-fragments' );
    }
}
add_action( 'wp_enqueue_scripts', 'bidnsteal_scripts' );

/**
 * Disable the default WooCommerce stylesheets so our own CSS can take precedence.  WooCommerce normally
 * enqueues several style sheets on the front end which conflict with our dark design.  Removing these
 * stylesheets ensures a cleaner starting point and makes it easier to control the appearance via
 * our own CSS files.
 *
 * @param array $enqueue_styles The default list of WooCommerce styles to enqueue.
 * @return array The filtered list of styles.
 */
function bidnsteal_dequeue_woocommerce_styles( $enqueue_styles ) {
    unset( $enqueue_styles['woocommerce-general'] );
    unset( $enqueue_styles['woocommerce-layout'] );
    unset( $enqueue_styles['woocommerce-smallscreen'] );
    return $enqueue_styles;
}
add_filter( 'woocommerce_enqueue_styles', 'bidnsteal_dequeue_woocommerce_styles' );

/**
 * Add a body class to help with custom styling when WooCommerce is active.  This allows us to
 * target pages that contain WooCommerce content with a single selector.
 *
 * @param array $classes Existing body classes.
 * @return array Filtered array of body classes.
 */
function bidnsteal_body_classes( $classes ) {
    if ( class_exists( 'WooCommerce' ) ) {
        $classes[] = 'woocommerce-active';
    }
    return $classes;
}
add_filter( 'body_class', 'bidnsteal_body_classes' );

/**
 * Allow filtering shop archives via custom category checkboxes in the sidebar.
 *
 * Reads the `bs_categories` GET parameter and applies an `IN` tax query for the
 * selected slugs.
 *
 * @param WP_Query $query The current query instance.
 */
function bidnsteal_filter_shop_categories( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( ! ( is_shop() || is_product_taxonomy() ) ) {
        return;
    }

    if ( empty( $_GET['bs_categories'] ) || ! is_array( $_GET['bs_categories'] ) ) {
        return;
    }

    $slugs = array_filter( array_map( 'sanitize_title', (array) wp_unslash( $_GET['bs_categories'] ) ) );

    if ( empty( $slugs ) ) {
        return;
    }

    $tax_query   = (array) $query->get( 'tax_query' );
    $tax_query[] = [
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $slugs,
        'operator' => 'IN',
    ];

    $query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'bidnsteal_filter_shop_categories' );

/**
 * Preserve selected category filters when submitting the WooCommerce price slider.
 *
 * @param string $form The original form HTML.
 * @return string Modified form HTML including hidden inputs for selected categories.
 */
function bidnsteal_price_filter_form( $form ) {
    if ( empty( $_GET['bs_categories'] ) || ! is_array( $_GET['bs_categories'] ) ) {
        return $form;
    }

    $hidden_inputs = '';
    foreach ( (array) wp_unslash( $_GET['bs_categories'] ) as $slug ) {
        $hidden_inputs .= '<input type="hidden" name="bs_categories[]" value="' . esc_attr( sanitize_title( $slug ) ) . '" />';
    }

    return str_replace( '</form>', $hidden_inputs . '</form>', $form );
}
add_filter( 'woocommerce_price_filter_form', 'bidnsteal_price_filter_form' );

?>
