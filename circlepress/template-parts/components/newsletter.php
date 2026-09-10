<?php
/**
 * Newsletter box.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$title  = get_theme_mod( 'circlepress_newsletter_title', '' ) ? get_theme_mod( 'circlepress_newsletter_title', '' ) : __( 'Get our best ideas weekly', 'circlepress' );
$text   = get_theme_mod( 'circlepress_newsletter_text', '' ) ? get_theme_mod( 'circlepress_newsletter_text', '' ) : __( 'Join thousands of readers. One useful email per week — no spam, unsubscribe anytime.', 'circlepress' );
$action = get_theme_mod( 'circlepress_newsletter_action', '' );
?>
<section class="cp-newsletter cp-section">
	<h2><?php echo esc_html( $title ); ?></h2>
	<p><?php echo esc_html( $text ); ?></p>
	<?php if ( $action ) : ?>
		<form method="post" action="<?php echo esc_url( $action ); ?>" target="_blank">
			<input type="email" name="EMAIL" required placeholder="<?php esc_attr_e( 'Your email address', 'circlepress' ); ?>" aria-label="<?php esc_attr_e( 'Email address', 'circlepress' ); ?>">
			<button class="cp-btn cp-btn--secondary" type="submit"><?php esc_html_e( 'Subscribe', 'circlepress' ); ?></button>
		</form>
	<?php else : ?>
		<form onsubmit="return false;">
			<input type="email" required placeholder="<?php esc_attr_e( 'Your email address', 'circlepress' ); ?>" aria-label="<?php esc_attr_e( 'Email address', 'circlepress' ); ?>">
			<button class="cp-btn cp-btn--secondary" type="submit"><?php esc_html_e( 'Subscribe', 'circlepress' ); ?></button>
		</form>
		<p class="cp-note"><?php esc_html_e( 'Tip: connect Mailchimp / ConvertKit / Brevo in Customizer → Newsletter.', 'circlepress' ); ?></p>
	<?php endif; ?>
</section>
