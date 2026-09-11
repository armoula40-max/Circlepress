<?php
/**
 * Sidebar.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="cp-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'circlepress' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php circlepress_ad( 'sidebar' ); ?>
</aside>
