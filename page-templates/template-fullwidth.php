<?php
/**
 * Template Name: CirclePress Full Width
 * Description: Page without sidebar (great for Elementor/Gutenberg landing content).
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'cp-single cp-section' ); ?>>
			<h1 class="cp-single__title"><?php the_title(); ?></h1>
			<div class="cp-entry entry-content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="cp-pagination"><span>' . esc_html__( 'Pages:', 'circlepress' ) . '</span> ',
						'after'  => '</div>',
					)
				);
				?>
			</div>
		</article>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>
</div>
<?php
get_footer();
