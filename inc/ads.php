<?php
/**
 * Ad slots: header, below-title, in-content (auto), sidebar, footer, sticky.
 * Compatible with AdSense, Ezoic placeholders and Mediavine.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Should ads show on this view?
 *
 * @return bool
 */
function circlepress_ads_should_show() {
	if ( ! get_theme_mod( 'circlepress_ads_enabled', true ) ) {
		return false;
	}
	if ( is_singular() && get_post_meta( get_queried_object_id(), '_cp_hide_ads', true ) ) {
		return false;
	}
	/* Never on 404 / search-empty utility views. */
	if ( is_404() ) {
		return false;
	}
	return true;
}

/**
 * Raw ad code for a slot.
 *
 * @param string $slot Slot id.
 * @return string
 */
function circlepress_ad_code( $slot ) {
	$code = get_theme_mod( 'circlepress_ad_' . $slot, '' );
	return apply_filters( 'circlepress_ad_code', $code, $slot );
}

/**
 * Render an ad slot.
 *
 * @param string $slot Slot id.
 */
function circlepress_ad( $slot ) {
	if ( ! circlepress_ads_should_show() ) {
		return;
	}
	$code = circlepress_ad_code( $slot );
	if ( '' === trim( (string) $code ) ) {
		return;
	}
	$lazy  = get_theme_mod( 'circlepress_ads_lazy', true ) ? ' cp-ad--lazy' : '';
	$label = in_array( $slot, array( 'sticky_footer' ), true ) ? '' : '<div class="cp-ad__label">' . esc_html__( 'Advertisement', 'circlepress' ) . '</div>';
	echo '<div class="cp-ad cp-ad--' . esc_attr( $slot ) . $lazy . '" role="complementary" aria-label="' . esc_attr__( 'Advertisement', 'circlepress' ) . '">';
	echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	if ( 'sticky_footer' === $slot ) {
		echo '<button class="cp-ad__close" aria-label="' . esc_attr__( 'Close ad', 'circlepress' ) . '">✕</button>';
	}
	/* Code already sanitized via circlepress_sanitize_ad on save. */
	echo '<div class="cp-ad__code">' . $code . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
}

/* ---------- Auto in-content injection ---------- */
function circlepress_inject_content_ads( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( ! circlepress_ads_should_show() ) {
		return $content;
	}
	$code = circlepress_ad_code( 'in_content' );
	if ( '' === trim( (string) $code ) ) {
		return $content;
	}
	$raw = get_theme_mod( 'circlepress_ad_paragraphs', '2,5,8' );
	$targets = array();
	foreach ( explode( ',', (string) $raw ) as $n ) {
		$n = (int) trim( $n );
		if ( $n > 0 ) {
			$targets[] = $n;
		}
	}
	if ( ! $targets ) {
		return $content;
	}
	$parts = preg_split( '/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( ! $parts || count( $parts ) < 3 ) {
		return $content;
	}
	$para = 0;
	$out  = '';
	foreach ( $parts as $chunk ) {
		$out .= $chunk;
		if ( '</p>' === strtolower( trim( $chunk ) ) ) {
			++$para;
			if ( in_array( $para, $targets, true ) ) {
				$out .= '<div class="cp-ad cp-ad--in-content" role="complementary" aria-label="' . esc_attr__( 'Advertisement', 'circlepress' ) . '"><div class="cp-ad__label">' . esc_html__( 'Advertisement', 'circlepress' ) . '</div><div class="cp-ad__code">' . $code . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
	return $out;
}
add_filter( 'the_content', 'circlepress_inject_content_ads', 20 );

/* ---------- Shortcode: [circlepress_ad slot="sidebar"] ---------- */
function circlepress_ad_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'slot' => 'in_content' ), $atts, 'circlepress_ad' );
	ob_start();
	circlepress_ad( sanitize_key( $atts['slot'] ) );
	return ob_get_clean();
}
add_shortcode( 'circlepress_ad', 'circlepress_ad_shortcode' );
add_shortcode( 'cp_ad', 'circlepress_ad_shortcode' );
