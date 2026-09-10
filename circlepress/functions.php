<?php
/**
 * CirclePress — lightweight multi-niche magazine theme.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CIRCLEPRESS_VERSION', '1.0.0' );
define( 'CIRCLEPRESS_DIR', get_template_directory() );
define( 'CIRCLEPRESS_URI', get_template_directory_uri() );

/* Core modules (order matters). */
require CIRCLEPRESS_DIR . '/inc/setup.php';
require CIRCLEPRESS_DIR . '/inc/niches.php';
require CIRCLEPRESS_DIR . '/inc/customizer.php';
require CIRCLEPRESS_DIR . '/inc/template-tags.php';
require CIRCLEPRESS_DIR . '/inc/seo.php';
require CIRCLEPRESS_DIR . '/inc/schema.php';
require CIRCLEPRESS_DIR . '/inc/ads.php';
require CIRCLEPRESS_DIR . '/inc/meta-boxes.php';
require CIRCLEPRESS_DIR . '/inc/shortcodes.php';
require CIRCLEPRESS_DIR . '/inc/performance.php';
