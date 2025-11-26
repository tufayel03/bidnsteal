<?php
/**
 * Main index template file.
 *
 * If no more specific template matches a query, WordPress will load this file.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<main class="bs-container">
    <div class="bs-content">
        <h1><?php esc_html_e( 'Welcome to BidnSteal', 'bidnsteal' ); ?></h1>
        <p><?php esc_html_e( 'This is the default template. Please create pages and assign templates as needed.', 'bidnsteal' ); ?></p>
    </div>
</main>

<?php
get_footer();