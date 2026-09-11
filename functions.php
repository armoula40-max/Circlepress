<?php
/**
 * CirclePress: full feature engine with the calm Askinz-inspired presentation layer.
 * @package CirclePress
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'CIRCLEPRESS_VERSION', '2.2.0' );
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
?>
