<?php
/**
 * The footer template for the BidnSteal theme.
 *
 * Displays the closing of the <div id="content"> element and all content after.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<footer class="bs-footer">
    <div class="bs-footer-inner">
        <p>&copy; <?php echo date_i18n( 'Y' ); ?> BidnSteal. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>