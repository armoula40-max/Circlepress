<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?><!doctype html>
<html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="<?php bloginfo( 'description' ); ?>"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<header class="site-header">
  <div class="container header-main" id="site-header-main">
    <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false">☰</button>
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?> home"><span class="brand-mark">C</span><span><?php bloginfo( 'name' ); ?></span></a>
    <nav class="primary-nav" aria-label="Primary navigation"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'circlepress_primary_menu_fallback' ) ); ?></nav>
    <div class="header-actions"><button class="search-toggle" aria-label="Open search" aria-expanded="false"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 5 5"/></svg></button></div>
  </div>
  <div class="search-panel" id="search-panel"><div class="container"><?php get_search_form(); ?></div></div>
</header>
<main id="content">
