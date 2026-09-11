<?php
/**
 * Performance for small shared hosting: fewer requests, less JS, smarter loading.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Emojis ---------- */
function circlepress_maybe_disable_emojis() {
	if ( ! get_theme_mod( 'circlepress_perf_emoji', true ) ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'circlepress_disable_emoji_tinymce' );
	add_filter( 'wp_resource_hints', 'circlepress_remove_emoji_dns', 10, 2 );
}
add_action( 'init', 'circlepress_maybe_disable_emojis' );

function circlepress_disable_emoji_tinymce( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

function circlepress_remove_emoji_dns( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/15.0.3/svg/' );
		$urls          = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

/* ---------- oEmbeds ---------- */
function circlepress_maybe_disable_embeds() {
	if ( ! get_theme_mod( 'circlepress_perf_embeds', true ) ) {
		return;
	}
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'oembed_dataparse', '__return_false', 999 );
	wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'circlepress_maybe_disable_embeds', 9999 );

/* ---------- jQuery Migrate ---------- */
function circlepress_maybe_drop_jquery_migrate( $scripts ) {
	if ( is_admin() || ! get_theme_mod( 'circlepress_perf_jquery', true ) ) {
		return;
	}
	if ( isset( $scripts->registered['jquery'] ) ) {
		$jquery = $scripts->registered['jquery'];
		if ( $jquery->deps ) {
			$jquery->deps = array_diff( $jquery->deps, array( 'jquery-migrate' ) );
		}
	}
}
add_action( 'wp_default_scripts', 'circlepress_maybe_drop_jquery_migrate' );

/* ---------- Heartbeat ---------- */
function circlepress_heartbeat_slow( $settings ) {
	if ( get_theme_mod( 'circlepress_perf_heartbeat', true ) ) {
		$settings['interval'] = 60;
	}
	return $settings;
}
add_filter( 'heartbeat_settings', 'circlepress_heartbeat_slow' );

/* ---------- Preconnect / preload ---------- */
function circlepress_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && get_theme_mod( 'circlepress_google_fonts', false ) ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = 'https://fonts.gstatic.com';
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'circlepress_resource_hints', 10, 2 );

function circlepress_preload_featured() {
	if ( ! get_theme_mod( 'circlepress_perf_preload', true ) ) {
		return;
	}
	if ( is_singular() && has_post_thumbnail() ) {
		$src = get_the_post_thumbnail_url( null, 'large' );
		if ( $src ) {
			echo '<link rel="preload" as="image" href="' . esc_url( $src ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'circlepress_preload_featured', 2 );

/* ---------- Defer non-critical theme JS is already footer-loaded; add async to comment-reply ---------- */
function circlepress_script_loading( $tag, $handle ) {
	if ( 'comment-reply' === $handle ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'circlepress_script_loading', 10, 2 );

/* ---------- Lazy-load iframes (video embeds) ---------- */
function circlepress_lazy_iframes( $content ) {
	if ( is_admin() || false !== stripos( $content, 'loading=' ) ) {
		return $content;
	}
	return preg_replace( '/<iframe/i', '<iframe loading="lazy"', $content );
}
add_filter( 'the_content', 'circlepress_lazy_iframes', 99 );
add_filter( 'widget_text', 'circlepress_lazy_iframes', 99 );
