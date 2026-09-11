<?php
/**
 * Template Name: CirclePress Landing (Minimal)
 * Description: Distraction-free narrow page for lead magnets, freebies and affiliate bridges.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="cp-container" style="max-width:760px">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'cp-single cp-section' ); ?> style="text-align:center">
			<h1 class="cp-single__title"><?php the_title(); ?></h1>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="cp-single__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<div class="cp-entry entry-content" style="text-align:start">
				<?php the_content(); ?>
			</div>
			<p><a class="cp-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Back to homepage', 'circlepress' ); ?></a></p>
		</article>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/components/newsletter' ); ?>
</div>
<?php
get_footer();
