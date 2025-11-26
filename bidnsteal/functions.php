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

    wp_localize_script(
        'bidnsteal-main-js',
        'bsThemeData',
        [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'bs_search_nonce' ),
        ]
    );

    // Ensure core WooCommerce add to cart behaviour is available for custom product buttons.
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'wc-add-to-cart' );
        wp_enqueue_script( 'wc-add-to-cart-variation' );
        wp_enqueue_script( 'wc-cart-fragments' );
    }
}
add_action( 'wp_enqueue_scripts', 'bidnsteal_scripts' );

/**
 * Add inline CSS variables for customisable header settings.
 */
function bidnsteal_inline_styles() {
    $radius   = absint( get_theme_mod( 'bs_nav_radius', 34 ) );
    $nav_glow = sprintf( 'rgba(255,255,255,0.08)' );

    $css = sprintf(
        '.bs-nav{border-radius:%1$spx;} .bs-icon{background:%2$s;} .bs-header{padding:12px 0 6px;}',
        $radius,
        $nav_glow
    );

    wp_add_inline_style( 'bidnsteal-main-style', $css );
}
add_action( 'wp_enqueue_scripts', 'bidnsteal_inline_styles', 20 );

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

/**
 * Register Customizer settings for header icons and navbar rounding.
 *
 * Allows site owners to change the icon classes and links for search, cart and account
 * icons directly from the Customizer. Also exposes navbar corner radius.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function bidnsteal_customize_register( $wp_customize ) {
    $wp_customize->add_section(
        'bs_header_options',
        [
            'title'       => __( 'Header Options', 'bidnsteal' ),
            'priority'    => 30,
            'description' => __( 'Control navbar rounding and header icons.', 'bidnsteal' ),
        ]
    );

    $wp_customize->add_setting(
        'bs_nav_radius',
        [
            'default'           => 34,
            'sanitize_callback' => 'absint',
        ]
    );

    $wp_customize->add_control(
        'bs_nav_radius',
        [
            'type'        => 'number',
            'section'     => 'bs_header_options',
            'label'       => __( 'Navbar Corner Radius (px)', 'bidnsteal' ),
            'input_attrs' => [ 'min' => 0, 'max' => 120, 'step' => 1 ],
        ]
    );

    $icons = [
        'search'  => [
            'label'      => __( 'Search Icon', 'bidnsteal' ),
            'icon_class' => 'fas fa-search',
            'link'       => '',
        ],
        'cart'    => [
            'label'      => __( 'Cart Icon', 'bidnsteal' ),
            'icon_class' => 'fas fa-shopping-cart',
            'link'       => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
        ],
        'account' => [
            'label'      => __( 'Account Icon', 'bidnsteal' ),
            'icon_class' => 'fas fa-user',
            'link'       => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ),
        ],
    ];

    foreach ( $icons as $key => $data ) {
        $wp_customize->add_setting(
            "bs_icon_{$key}_class",
            [
                'default'           => $data['icon_class'],
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            "bs_icon_{$key}_class",
            [
                'section' => 'bs_header_options',
                'label'   => sprintf( __( '%s CSS class', 'bidnsteal' ), $data['label'] ),
                'type'    => 'text',
            ]
        );

        $wp_customize->add_setting(
            "bs_icon_{$key}_link",
            [
                'default'           => is_callable( $data['link'] ) ? call_user_func( $data['link'] ) : $data['link'],
                'sanitize_callback' => 'esc_url_raw',
            ]
        );

        $wp_customize->add_control(
            "bs_icon_{$key}_link",
            [
                'section' => 'bs_header_options',
                'label'   => sprintf( __( '%s URL', 'bidnsteal' ), $data['label'] ),
                'type'    => 'url',
            ]
        );
    }
}
add_action( 'customize_register', 'bidnsteal_customize_register' );

/**
 * AJAX search handler for product quick search.
 */
function bidnsteal_ajax_search_products() {
    check_ajax_referer( 'bs_search_nonce', 'nonce' );

    $term = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';

    if ( '' === $term ) {
        wp_send_json_success( [ 'items' => [] ] );
    }

    $query = new WP_Query(
        [
            's'              => $term,
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 6,
        ]
    );

    $items = [];

    while ( $query->have_posts() ) {
        $query->the_post();
        $product = wc_get_product( get_the_ID() );

        $items[] = [
            'title' => get_the_title(),
            'url'   => get_permalink(),
            'price' => $product ? $product->get_price_html() : '',
            'image' => get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' ) ?: wc_placeholder_img_src(),
        ];
    }

    wp_reset_postdata();

    wp_send_json_success( [ 'items' => $items ] );
}
add_action( 'wp_ajax_bs_search_products', 'bidnsteal_ajax_search_products' );
add_action( 'wp_ajax_nopriv_bs_search_products', 'bidnsteal_ajax_search_products' );

/**
 * Ensure core WooCommerce pages exist when the theme is activated.
 */
function bidnsteal_ensure_wc_pages() {
    if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_create_page' ) ) {
        return;
    }

    $pages = [
        'cart'     => [ 'option' => 'woocommerce_cart_page_id', 'title' => __( 'Cart', 'woocommerce' ), 'shortcode' => '[woocommerce_cart]' ],
        'checkout' => [ 'option' => 'woocommerce_checkout_page_id', 'title' => __( 'Checkout', 'woocommerce' ), 'shortcode' => '[woocommerce_checkout]' ],
        'my-account' => [ 'option' => 'woocommerce_myaccount_page_id', 'title' => __( 'My Account', 'woocommerce' ), 'shortcode' => '[woocommerce_my_account]' ],
    ];

    foreach ( $pages as $slug => $page ) {
        if ( -1 === wc_get_page_id( str_replace( '-', '', $slug ) ) ) {
            wc_create_page( sanitize_title( $slug ), $page['option'], $page['title'], $page['shortcode'] );
        }
    }
}
add_action( 'after_switch_theme', 'bidnsteal_ensure_wc_pages' );

?>
