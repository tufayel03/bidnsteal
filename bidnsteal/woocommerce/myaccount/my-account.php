<?php
/**
 * My Account page
 *
 * Provides a dark-themed wrapper around WooCommerce account navigation and content to
 * match the BidnSteal shop styling.
 *
 * @package BidnSteal
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>
<div class="bs-account-page">
    <aside class="bs-account-sidebar">
        <?php do_action( 'woocommerce_before_account_navigation' ); ?>
        <?php woocommerce_account_navigation(); ?>
        <?php do_action( 'woocommerce_after_account_navigation' ); ?>
    </aside>

    <section class="bs-account-content">
        <?php do_action( 'woocommerce_before_account_content' ); ?>
        <?php woocommerce_account_content(); ?>
        <?php do_action( 'woocommerce_after_account_content' ); ?>
    </section>
</div>
