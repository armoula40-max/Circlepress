<?php
/**
 * No results.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cp-single" style="text-align:center">
	<h2><?php esc_html_e( 'Nothing found', 'circlepress' ); ?></h2>
	<p style="color:var(--cp-muted)"><?php esc_html_e( 'Try a different search or browse the latest posts.', 'circlepress' ); ?></p>
	<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:flex;gap:10px;max-width:440px;margin:16px auto">
		<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'circlepress' ); ?>" style="flex:1;border:1px solid var(--cp-border);border-radius:999px;padding:12px 20px">
		<button class="cp-btn" type="submit"><?php esc_html_e( 'Search', 'circlepress' ); ?></button>
	</form>
</div>
