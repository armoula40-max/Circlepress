<?php
/**
 * Fallback index.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="cp-container">
	<?php circlepress_breadcrumbs(); ?>
	<div class="<?php echo esc_attr( circlepress_layout_class() ); ?>">
		<div>
			<?php if ( have_posts() ) : ?>
				<div class="cp-grid cp-grid--2">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/components/post-card' );
					endwhile;
					?>
				</div>
				<?php circlepress_pagination(); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
			<?php endif; ?>
		</div>
		<?php
		if ( ! circlepress_is_fullwidth() ) {
			get_sidebar();
		}
		?>
	</div>
</div>
<?php
get_footer();
