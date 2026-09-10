<?php
/**
 * Comments template.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( post_password_required() ) {
	return;
}
?>
<div class="cp-comments" id="comments">
	<?php if ( have_comments() ) : ?>
		<h2>
			<?php
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s Comment', '%s Comments', get_comments_number(), 'circlepress' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol>
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>
	<?php
	comment_form(
		array(
			'title_reply' => esc_html__( 'Leave a reply', 'circlepress' ),
		)
	);
	?>
</div>
