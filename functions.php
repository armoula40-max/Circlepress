<?php
/**
 * CirclePress: full feature engine with the calm Askinz-inspired presentation layer.
 * @package CirclePress
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'CIRCLEPRESS_VERSION', '2.3.0' );
define( 'CIRCLEPRESS_DIR', get_template_directory() );
define( 'CIRCLEPRESS_URI', get_template_directory_uri() );

/* Core modules from the original CirclePress theme. */
require CIRCLEPRESS_DIR . '/inc/setup.php';
require CIRCLEPRESS_DIR . '/inc/niches.php';
require CIRCLEPRESS_DIR . '/inc/customizer.php';
require CIRCLEPRESS_DIR . '/inc/template-tags.php';
require CIRCLEPRESS_DIR . '/inc/icons.php';
require CIRCLEPRESS_DIR . '/inc/seo.php';
require CIRCLEPRESS_DIR . '/inc/schema.php';
require CIRCLEPRESS_DIR . '/inc/ads.php';
require CIRCLEPRESS_DIR . '/inc/meta-boxes.php';
require CIRCLEPRESS_DIR . '/inc/shortcodes.php';
require CIRCLEPRESS_DIR . '/inc/performance.php';
require CIRCLEPRESS_DIR . '/inc/privacy.php';
require CIRCLEPRESS_DIR . '/inc/home-layouts.php';
require CIRCLEPRESS_DIR . '/inc/patterns.php';
require CIRCLEPRESS_DIR . '/inc/conversion.php';
require CIRCLEPRESS_DIR . '/inc/engagement.php';
require CIRCLEPRESS_DIR . '/inc/seo-plus.php';

/* Presentation helpers used by the first, calm homepage templates. */
function circlepress_default_image( $size = 'large' ) {
    $images = array(
        'https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&w=900&q=85',
        'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=85',
        'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=900&q=85',
        'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=900&q=85',
    );
    return esc_url( $images[ abs( get_the_ID() ) % count( $images ) ] );
}
function circlepress_post_image( $size = 'large' ) {
    if ( has_post_thumbnail() ) { the_post_thumbnail( $size ); }
    else { echo '<img src="' . circlepress_default_image( $size ) . '" alt="' . esc_attr( get_the_title() ) . '">'; }
}
function circlepress_primary_menu_fallback() {
    echo '<ul><li><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li><li><a href="' . esc_url( home_url( '/recipes/' ) ) . '">Recipes</a></li><li><a href="' . esc_url( home_url( '/meal-plans/' ) ) . '">Meal Plans</a></li><li><a href="' . esc_url( home_url( '/ingredient-guides/' ) ) . '">Ingredient Guides</a></li><li><a href="' . esc_url( home_url( '/about/' ) ) . '">About</a></li></ul>';
}
function circlepress_categories() {
    $categories = get_categories( array( 'number' => 10, 'hide_empty' => true ) );
    foreach ( $categories as $category ) { echo '<li><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></li>'; }
}
function circlepress_clean_excerpt( $post = null, $words = 22 ) {
    $text = get_the_excerpt( $post );
    $text = strip_shortcodes( $text );
    $text = preg_replace( '/\b(?:jump\s*to\s*recipe|print\s*recipe|continue\s*reading|read\s*more)\b/i', '', $text );
    $text = wp_strip_all_tags( $text );
    return wp_trim_words( trim( preg_replace( '/\s+/', ' ', $text ) ), $words );
}
function askinz_recipe_studio_page_url( $slug, $fallback = '' ) {
    $page = get_page_by_path( $slug );
    return $page ? get_permalink( $page ) : ( $fallback ? $fallback : home_url( '/' ) );
}
function askinz_recipe_studio_primary_menu() {
    wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_id' => 'primary-menu', 'menu_class' => 'primary-menu', 'fallback_cb' => 'circlepress_primary_menu_fallback' ) );
}
function askinz_recipe_studio_menu_fallback() { circlepress_primary_menu_fallback(); }
function askinz_recipe_studio_pagination( $query = null ) {
    $max_pages = $query instanceof WP_Query ? (int) $query->max_num_pages : (int) $GLOBALS['wp_query']->max_num_pages;
    if ( $max_pages < 2 ) { return; }
    echo '<nav class="pagination" aria-label="Recipe pages">' . wp_kses_post( paginate_links( array( 'total' => $max_pages, 'current' => max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) ), 'type' => 'list', 'prev_text' => '← Previous', 'next_text' => 'Next →' ) ) ) . '</nav>';
}
function askinz_recipe_studio_newsletter_handler() {
    if ( ! isset( $_POST['askinz_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['askinz_newsletter_nonce'] ) ), 'askinz_newsletter' ) ) { wp_die( esc_html__( 'Your form session expired. Please return and try again.', 'circlepress' ) ); }
    $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $return_url = wp_get_referer() ?: home_url( '/' );
    if ( ! is_email( $email ) || empty( $_POST['newsletter_consent'] ) ) { wp_safe_redirect( add_query_arg( 'newsletter', 'invalid', $return_url ) ); exit; }
    $subject = sprintf( '[%s] Kitchen Notes subscription request', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
    $sent = wp_mail( get_option( 'admin_email' ), $subject, 'Email: ' . $email . "\nConsent: opted in via the Kitchen Notes form\nSource: " . $return_url, array( 'Reply-To: ' . $email ) );
    wp_safe_redirect( add_query_arg( 'newsletter', $sent ? 'sent' : 'failed', $return_url ) ); exit;
}
add_action( 'admin_post_nopriv_askinz_newsletter', 'askinz_recipe_studio_newsletter_handler' );
add_action( 'admin_post_askinz_newsletter', 'askinz_recipe_studio_newsletter_handler' );
?>
