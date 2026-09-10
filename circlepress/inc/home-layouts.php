<?php
/**
 * Homepage layout shapes: Magazine (default niche sections), Grid, List, Showcase.
 * Global choice: Customizer → Homepage → Layout.
 * Per-page override: Page Options meta box → Homepage layout.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Available homepage layouts.
 *
 * @return array id => label
 */
function circlepress_home_layouts() {
	return array(
		'magazine' => __( 'Magazine (niche sections)', 'circlepress' ),
		'grid'     => __( 'Grid (clean post grid)', 'circlepress' ),
		'list'     => __( 'List (blog rows + sidebar)', 'circlepress' ),
		'showcase' => __( 'Showcase (visual feature rows)', 'circlepress' ),
	);
}

/**
 * Active homepage layout (per-page override wins over global setting).
 *
 * @return string
 */
function circlepress_home_layout() {
	if ( is_page() ) {
		$meta = get_post_meta( get_queried_object_id(), '_cp_home_layout', true );
		if ( $meta && array_key_exists( $meta, circlepress_home_layouts() ) ) {
			return $meta;
		}
	}
	$layout = get_theme_mod( 'circlepress_home_layout', 'magazine' );
	if ( ! array_key_exists( (string) $layout, circlepress_home_layouts() ) ) {
		$layout = 'magazine';
	}
	return apply_filters( 'circlepress_home_layout', $layout );
}

function circlepress_sanitize_home_layout( $v ) {
	return array_key_exists( (string) $v, circlepress_home_layouts() ) ? (string) $v : 'magazine';
}
