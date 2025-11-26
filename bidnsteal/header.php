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
            <!-- Search icon: this button could toggle a search bar via JavaScript if desired -->
            <button type="button" class="bs-icon" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>

            <!-- Cart icon: links to the WooCommerce cart page -->
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="bs-icon" aria-label="View cart">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <!-- Account icon: links to the WooCommerce My Account page -->
            <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="bs-icon" aria-label="My account">
                <i class="fas fa-user"></i>
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
</header>