<?php
/**
 * Template Name: CirclePress Gift Guide / Deals
 * Description: Seasonal gift/deals page. Put [gift_grid][gift_item ...] shortcodes in the content.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="cp-container">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="cp-hero cp-hero--bold cp-section">
			<span class="cp-kicker">🎁 <?php esc_html_e( 'Gift Guide', 'circlepress' ); ?></span>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</section>
		<?php echo circlepress_sc_disclosure(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="cp-entry entry-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/components/newsletter' ); ?>
</div>
<?php
get_footer();
