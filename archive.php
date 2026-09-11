<?php
/**
 * Archive.
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
	<header class="cp-section" style="margin-bottom:10px">
		<h1 style="font-size:1.7rem;margin:0"><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<p style="color:var(--cp-muted)">', '</p>' ); ?>
	</header>
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
