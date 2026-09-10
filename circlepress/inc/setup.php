<?php
/**
 * Theme setup: supports, menus, sidebars, assets, helpers.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Setup ---------- */
function circlepress_setup() {
	load_theme_textdomain( 'circlepress', CIRCLEPRESS_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );

	/* Gutenberg palettes derived from the active niche. */
	$niche  = circlepress_get_niche( circlepress_get_current_niche() );
	$colors = isset( $niche['colors'] ) ? $niche['colors'] : array();
	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => __( 'Primary', 'circlepress' ), 'slug' => 'primary', 'color' => isset( $colors['primary'] ) ? $colors['primary'] : '#d9481c' ),
			array( 'name' => __( 'Secondary', 'circlepress' ), 'slug' => 'secondary', 'color' => isset( $colors['secondary'] ) ? $colors['secondary'] : '#2a9d48' ),
			array( 'name' => __( 'Accent', 'circlepress' ), 'slug' => 'accent', 'color' => isset( $colors['accent'] ) ? $colors['accent'] : '#ffb703' ),
			array( 'name' => __( 'Dark', 'circlepress' ), 'slug' => 'dark', 'color' => '#232323' ),
			array( 'name' => __( 'Light', 'circlepress' ), 'slug' => 'light', 'color' => '#faf3ea' ),
		)
	);

	/* Basic WooCommerce compatibility (shops for Furniture/Beauty/etc). */
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'circlepress' ),
			'footer'  => __( 'Footer Menu', 'circlepress' ),
			'top'     => __( 'Top Bar Menu', 'circlepress' ),
		)
	);

	/* Starter content so a fresh site looks designed instantly. */
	add_theme_support(
		'starter-content',
		array(
			'options'     => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
			),
			'posts'       => array(
				'home' => array(
					'post_type'    => 'page',
					'post_title'   => __( 'Home', 'circlepress' ),
					'post_content' => '',
					'template'     => 'page-templates/template-niche-home.php',
				),
				'blog' => array(
					'post_type'  => 'page',
					'post_title' => __( 'Blog', 'circlepress' ),
				),
				'about' => array(
					'post_type'  => 'page',
					'post_title' => __( 'About', 'circlepress' ),
				),
				'contact' => array(
					'post_type'  => 'page',
					'post_title' => __( 'Contact', 'circlepress' ),
				),
			),
			'nav_menus'   => array(
				'primary' => array(
					'name'  => __( 'Primary Menu', 'circlepress' ),
					'items' => array(
						'page_home',
						'page_blog',
						'page_about',
						'page_contact',
					),
				),
			),
			'widgets'     => array(
				'sidebar-1' => array(
					'search',
					'meta_about' => array(
						'text',
						array(
							'title' => __( 'About', 'circlepress' ),
							'text'  => __( 'Welcome! This is a lightweight CirclePress demo sidebar. Replace me with your own widgets.', 'circlepress' ),
						),
					),
					'recent-posts',
					'categories',
				),
			),
		)
	);
}
add_action( 'after_setup_theme', 'circlepress_setup' );

/* ---------- Content width ---------- */
function circlepress_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'circlepress_content_width', 800 );
}
add_action( 'after_setup_theme', 'circlepress_content_width', 0 );

/* ---------- Sidebars ---------- */
function circlepress_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Main Sidebar', 'circlepress' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Appears next to posts and archives.', 'circlepress' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Column %d', 'circlepress' ), $i ),
				'id'            => 'footer-' . $i,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			)
		);
	}
	register_sidebar(
		array(
			'name'          => __( 'Homepage: Below Hero', 'circlepress' ),
			'id'            => 'home-top',
			'description'   => __( 'Optional widget area under the homepage hero (great for an ad or category pills).', 'circlepress' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="cp-sr">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'circlepress_widgets_init' );

/* ---------- Assets (kept tiny on purpose) ---------- */
function circlepress_assets() {
	$ver = CIRCLEPRESS_VERSION;

	/* Main stylesheet with per-niche CSS variables. */
	wp_enqueue_style( 'circlepress-style', get_stylesheet_uri(), array(), $ver );
	$vars = circlepress_css_variables();
	if ( $vars ) {
		wp_add_inline_style( 'circlepress-style', $vars );
	}

	/* Optional Google Fonts (OFF by default for speed; enable in Customizer). */
	if ( get_theme_mod( 'circlepress_google_fonts', false ) ) {
		$fonts = circlepress_google_fonts_url();
		if ( $fonts ) {
			wp_enqueue_style( 'circlepress-fonts', $fonts, array(), $ver );
		}
	}

	/* Single small vanilla-JS file, footer-loaded. No jQuery on frontend. */
	wp_enqueue_script( 'circlepress-main', CIRCLEPRESS_URI . '/assets/js/main.js', array(), $ver, true );
	wp_localize_script(
		'circlepress-main',
		'circlepressData',
		array(
			'copied'      => __( 'Link copied!', 'circlepress' ),
			'readingTime' => (bool) get_theme_mod( 'circlepress_show_progress', true ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'circlepress_assets' );

/**
 * Build the :root CSS variables (niche palette + customizer overrides).
 *
 * @return string
 */
function circlepress_css_variables() {
	$niche_id = circlepress_get_current_niche();
	$niche    = circlepress_get_niche( $niche_id );
	$colors   = isset( $niche['colors'] ) ? $niche['colors'] : array();

	$get = function ( $key, $fallback ) use ( $niche_id, $colors ) {
		$override = get_theme_mod( 'circlepress_color_' . $key, '' );
		if ( $override ) {
			return $override;
		}
		return isset( $colors[ $key ] ) ? $colors[ $key ] : $fallback;
	};

	$css  = ':root{';
	$css .= '--cp-primary:' . esc_attr( $get( 'primary', '#d9481c' ) ) . ';';
	$css .= '--cp-primary-dark:' . esc_attr( $get( 'primary_dark', '#b23a15' ) ) . ';';
	$css .= '--cp-secondary:' . esc_attr( $get( 'secondary', '#2a9d48' ) ) . ';';
	$css .= '--cp-accent:' . esc_attr( $get( 'accent', '#ffb703' ) ) . ';';
	$css .= '--cp-bg:' . esc_attr( $get( 'background', '#fffbf5' ) ) . ';';
	$css .= '--cp-surface-2:' . esc_attr( $get( 'surface_2', '#faf3ea' ) ) . ';';
	$css .= '--cp-border:' . esc_attr( $get( 'border', '#eee2d3' ) ) . ';';

	if ( get_theme_mod( 'circlepress_google_fonts', false ) && ! empty( $niche['fonts'] ) ) {
		$heading = isset( $niche['fonts']['heading'] ) ? $niche['fonts']['heading'] : '';
		$body    = isset( $niche['fonts']['body'] ) ? $niche['fonts']['body'] : '';
		if ( $heading ) {
			$css .= '--cp-font-heading:"' . esc_attr( $heading ) . '",Georgia,serif;';
		}
		if ( $body ) {
			$css .= '--cp-font-body:"' . esc_attr( $body ) . '",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;';
		}
	}
	$css .= '}';

	return $css;
}

/**
 * Google Fonts URL for the active niche (only when enabled).
 *
 * @return string
 */
function circlepress_google_fonts_url() {
	$niche = circlepress_get_niche( circlepress_get_current_niche() );
	if ( empty( $niche['fonts'] ) ) {
		return '';
	}
	$families = array();
	foreach ( array( 'heading', 'body' ) as $key ) {
		if ( ! empty( $niche['fonts'][ $key ] ) ) {
			$families[] = str_replace( ' ', '+', $niche['fonts'][ $key ] ) . ':wght@400;600;700;800';
		}
	}
	if ( ! $families ) {
		return '';
	}
	return 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', array_unique( $families ) ) . '&display=swap';
}

/* ---------- Body classes ---------- */
function circlepress_body_classes( $classes ) {
	$classes[] = 'niche-' . sanitize_html_class( circlepress_get_current_niche() );
	if ( ! is_active_sidebar( 'sidebar-1' ) || circlepress_is_fullwidth() ) {
		$classes[] = 'no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'circlepress_body_classes' );

/**
 * Whether the current view should be full width (no sidebar).
 *
 * @return bool
 */
function circlepress_is_fullwidth() {
	if ( is_page_template( array( 'page-templates/template-fullwidth.php', 'page-templates/template-landing.php' ) ) ) {
		return true;
	}
	if ( is_front_page() || is_page_template( 'page-templates/template-niche-home.php' ) ) {
		return 'no-sidebar' === get_theme_mod( 'circlepress_home_sidebar', 'no-sidebar' );
	}
	if ( is_singular( 'post' ) ) {
		return 'no-sidebar' === get_theme_mod( 'circlepress_single_sidebar', 'right' );
	}
	if ( is_archive() || is_home() || is_search() ) {
		return 'no-sidebar' === get_theme_mod( 'circlepress_archive_sidebar', 'right' );
	}
	return false;
}

/**
 * Sidebar position class for grid layouts.
 *
 * @return string
 */
function circlepress_layout_class() {
	if ( circlepress_is_fullwidth() || ! is_active_sidebar( 'sidebar-1' ) ) {
		return 'cp-layout cp-layout--no-sidebar';
	}
	$pos = 'right';
	if ( is_singular( 'post' ) ) {
		$pos = get_theme_mod( 'circlepress_single_sidebar', 'right' );
	} elseif ( is_archive() || is_home() || is_search() ) {
		$pos = get_theme_mod( 'circlepress_archive_sidebar', 'right' );
	}
	return 'left' === $pos ? 'cp-layout cp-layout--left' : 'cp-layout';
}
