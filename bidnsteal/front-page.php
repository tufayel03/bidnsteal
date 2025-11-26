<?php
/**
 * Template for the home page.
 *
 * This template builds a bespoke landing page featuring a hero section, category icons, and lists of
 * featured products and new arrivals.  It uses WooCommerce shortcodes to output product lists.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<main class="bs-home">
    <!-- Hero Section -->
    <section class="bs-hero">
        <div class="hero-content">
            <h1><?php esc_html_e( 'Experience Premium Collectibles', 'bidnsteal' ); ?></h1>
            <p><?php esc_html_e( 'Bid, buy and explore the finest Hot Wheels and tech products at unbeatable prices.', 'bidnsteal' ); ?></p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="hero-btn">
                <?php esc_html_e( 'Shop Now', 'bidnsteal' ); ?>
            </a>
        </div>
        <div class="hero-image">
            <?php
            // If a hero image exists in the assets directory, display it. Otherwise show a placeholder.
            $hero_path = get_template_directory_uri() . '/assets/img/hero.png';
            if ( file_exists( get_template_directory() . '/assets/img/hero.png' ) ) {
                echo '<img src="' . esc_url( $hero_path ) . '" alt="Hero Image">';
            }
            ?>
        </div>
    </section>

    <!-- Category Icons Section -->
    <section class="bs-categories">
        <h2 class="section-title"><?php esc_html_e( 'Shop by Category', 'bidnsteal' ); ?></h2>
        <div class="cat-grid">
            <a href="<?php echo esc_url( get_term_link( 'hot-wheels', 'product_cat' ) ); ?>" class="cat-box glass-box">
                <i class="fas fa-car"></i>
                <span><?php esc_html_e( 'Hot Wheels', 'bidnsteal' ); ?></span>
            </a>
            <a href="<?php echo esc_url( get_term_link( 'electronics', 'product_cat' ) ); ?>" class="cat-box glass-box">
                <i class="fas fa-headphones"></i>
                <span><?php esc_html_e( 'Electronics', 'bidnsteal' ); ?></span>
            </a>
            <a href="<?php echo esc_url( get_term_link( 'toys', 'product_cat' ) ); ?>" class="cat-box glass-box">
                <i class="fas fa-rocket"></i>
                <span><?php esc_html_e( 'Toys & Models', 'bidnsteal' ); ?></span>
            </a>
            <a href="<?php echo esc_url( get_term_link( 'accessories', 'product_cat' ) ); ?>" class="cat-box glass-box">
                <i class="fas fa-layer-group"></i>
                <span><?php esc_html_e( 'Accessories', 'bidnsteal' ); ?></span>
            </a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="bs-products">
        <h2 class="section-title"><?php esc_html_e( 'Featured', 'bidnsteal' ); ?></h2>
        <div class="bs-product-list">
            <?php echo do_shortcode( '[products limit="8" columns="4" visibility="featured"]' ); ?>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="bs-products">
        <h2 class="section-title"><?php esc_html_e( 'New Arrivals', 'bidnsteal' ); ?></h2>
        <div class="bs-product-list">
            <?php echo do_shortcode( '[products limit="8" columns="4" orderby="date"]' ); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>