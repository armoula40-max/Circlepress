<?php
/**
 * Editorial header inspired by askinz-style recipe journals.
 *
 * @package CirclePress
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!DOCTYPE html>
<html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="profile" href="https://gmpg.org/xfn/11"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'circlepress' ); ?></a>
<header class="cp-header cp-header--editorial">
  <div class="cp-container cp-header__inner">
    <a class="cp-logo cp-logo--editorial" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
      <span class="cp-logo__word"><?php bloginfo( 'name' ); ?><span class="cp-logo__tag"> <?php echo esc_html( get_theme_mod( 'circlepress_logo_tagline', false ) && get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : 'kitchen notes' ); ?></span></span>
    </a>
    <nav class="cp-nav" aria-label="<?php esc_attr_e( 'Primary', 'circlepress' ); ?>"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'circlepress_fallback_menu' ) ); ?></nav>
    <div class="cp-header__actions">
      <?php if ( get_theme_mod( 'circlepress_show_search', true ) ) : ?><button class="cp-icon-btn cp-search-toggle" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>"><?php echo circlepress_icon( 'search', 19 ); ?></button><?php endif; ?>
      <button class="cp-icon-btn cp-hamburger" aria-label="<?php esc_attr_e( 'Menu', 'circlepress' ); ?>" aria-expanded="false"><?php echo circlepress_icon( 'menu', 20 ); ?></button>
    </div>
  </div>
  <?php if ( is_singular() && get_theme_mod( 'circlepress_show_progress', true ) ) : ?><div class="cp-progress" aria-hidden="true"><span></span></div><?php endif; ?>
</header>
<div class="cp-search-overlay" role="dialog" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>"><button class="cp-search-overlay__close" aria-label="<?php esc_attr_e( 'Close search', 'circlepress' ); ?>"><?php echo circlepress_icon( 'close', 26 ); ?></button><form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>"><input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search recipes, guides, reviews…', 'circlepress' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>"><button class="cp-btn" type="submit"><?php esc_html_e( 'Search', 'circlepress' ); ?></button></form></div>
<div class="cp-drawer-overlay"></div><aside class="cp-drawer cp-mobile-nav" aria-label="<?php esc_attr_e( 'Mobile', 'circlepress' ); ?>"><div class="cp-drawer__head"><span class="cp-drawer__logo"><?php bloginfo( 'name' ); ?></span><button class="cp-icon-btn cp-drawer__close" aria-label="<?php esc_attr_e( 'Close menu', 'circlepress' ); ?>"><?php echo circlepress_icon( 'close', 20 ); ?></button></div><form class="cp-drawer__search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>"><input type="search" name="s" placeholder="<?php esc_attr_e( 'Search…', 'circlepress' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>"><button type="submit" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>"><?php echo circlepress_icon( 'search', 18 ); ?></button></form><?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'circlepress_fallback_menu' ) ); ?><div class="cp-drawer__social"><?php foreach ( circlepress_social_links() as $s ) : ?><a href="<?php echo esc_url( $s[0] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s[2] ); ?>"><?php echo circlepress_icon( $s[1], 17 ); ?></a><?php endforeach; ?></div></aside>
<?php circlepress_ad( 'header' ); ?><main id="primary">
<?php
function circlepress_fallback_menu() { echo '<ul><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'circlepress' ) . '</a></li><li><a href="' . esc_url( home_url( '/recipes/' ) ) . '">' . esc_html__( 'Recipes', 'circlepress' ) . '</a></li><li><a href="' . esc_url( home_url( '/meal-plans/' ) ) . '">' . esc_html__( 'Meal Plans', 'circlepress' ) . '</a></li><li><a href="' . esc_url( home_url( '/ingredient-guides/' ) ) . '">' . esc_html__( 'Ingredient Guides', 'circlepress' ) . '</a></li><li><a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html__( 'About', 'circlepress' ) . '</a></li></ul>'; }
?>
