<?php
/**
 * Circlepress theme functions.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function circlepress_setup() {
    load_theme_textdomain( 'circlepress', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
    register_nav_menus( array( 'primary' => __( 'Primary Menu', 'circlepress' ) ) );
}
add_action( 'after_setup_theme', 'circlepress_setup' );

function circlepress_assets() {
    wp_enqueue_style( 'circlepress-fonts', 'https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'circlepress-style', get_stylesheet_uri(), array( 'circlepress-fonts' ), '1.0.0' );
    wp_enqueue_script( 'circlepress-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'circlepress_assets' );

function circlepress_excerpt_length() { return 22; }
add_filter( 'excerpt_length', 'circlepress_excerpt_length' );
function circlepress_excerpt_more() { return '…'; }
add_filter( 'excerpt_more', 'circlepress_excerpt_more' );

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
    if ( empty( $categories ) ) { $categories = array(); }
    foreach ( $categories as $category ) {
        echo '<li><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></li>';
    }
}
?>

