<?php
/**
 * The header template for the BidnSteal theme.
 *
 * Displays all of the <head> section and everything up until the main content.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Site Header -->
<?php
$search_icon  = get_theme_mod( 'bs_icon_search_class', 'fas fa-search' );
$cart_icon    = get_theme_mod( 'bs_icon_cart_class', 'fas fa-shopping-cart' );
$account_icon = get_theme_mod( 'bs_icon_account_class', 'fas fa-user' );

$search_link  = get_theme_mod( 'bs_icon_search_link', '' );
$cart_link    = get_theme_mod( 'bs_icon_cart_link', bidnsteal_get_wc_page_url( 'cart' ) );
$account_link = get_theme_mod( 'bs_icon_account_link', bidnsteal_get_wc_page_url( 'my-account' ) );

if ( empty( $cart_link ) ) {
    $cart_link = bidnsteal_get_wc_page_url( 'cart' );
}

if ( empty( $account_link ) ) {
    $account_link = bidnsteal_get_wc_page_url( 'my-account' );
}
?>

<header class="bs-header">
    <div class="bs-nav">

        <!-- Logo section -->
        <div class="bs-left">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bs-logo">BidnSteal</a>
        </div>

        <!-- Primary navigation menu -->
        <nav class="bs-center">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary_menu',
                'menu_class'     => 'bs-menu',
                'container'      => false,
                'fallback_cb'    => '__return_false',
                'depth'          => 2,
            ] );
            ?>
        </nav>

        <!-- Right icons: search, cart and user (my account) -->
        <div class="bs-right">
            <!-- Search icon: opens AJAX search overlay or links to custom URL -->
            <?php if ( $search_link ) : ?>
                <a href="<?php echo esc_url( $search_link ); ?>" class="bs-icon bs-search-trigger" aria-label="Search">
                    <i class="<?php echo esc_attr( $search_icon ); ?>"></i>
                </a>
            <?php else : ?>
                <button type="button" class="bs-icon bs-search-trigger" aria-label="Search">
                    <i class="<?php echo esc_attr( $search_icon ); ?>"></i>
                </button>
            <?php endif; ?>

            <!-- Cart icon: links to the WooCommerce cart page -->
            <a href="<?php echo esc_url( $cart_link ); ?>" class="bs-icon" aria-label="View cart">
                <i class="<?php echo esc_attr( $cart_icon ); ?>"></i>
            </a>

            <!-- Account icon: links to the WooCommerce My Account page -->
            <a href="<?php echo esc_url( $account_link ); ?>" class="bs-icon" aria-label="My account">
                <i class="<?php echo esc_attr( $account_icon ); ?>"></i>
            </a>

            <!-- Mobile menu toggle button -->
            <button id="bs-toggle" class="bs-icon mobile-only" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div><!-- .bs-nav -->

    <!-- Mobile navigation menu -->
    <div class="bs-mobile-menu" id="bs-mobile-menu">
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary_menu',
            'menu_class'     => 'bs-mobile-list',
            'container'      => false,
            'fallback_cb'    => '__return_false',
            'depth'          => 1,
        ] );
        ?>
    </div>

    <div class="bs-mobile-overlay" id="bs-mobile-overlay"></div>

    <!-- AJAX product search overlay -->
    <div class="bs-search-overlay" id="bs-search-overlay" aria-hidden="true">
        <div class="bs-search-panel">
            <button type="button" class="bs-search-close" aria-label="Close search">&times;</button>
            <div class="bs-search-field">
                <i class="fas fa-search"></i>
                <input type="search" id="bs-search-input" placeholder="<?php esc_attr_e( 'Search products…', 'bidnsteal' ); ?>" autocomplete="off" />
            </div>
            <div class="bs-search-results" id="bs-search-results"></div>
        </div>
    </div>
</header>