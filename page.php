<?php
/**
 * Page.
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
	<div class="<?php echo esc_attr( circlepress_layout_class() ); ?>" style="margin-top:18px">
		<div>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cp-single' ); ?>>
					<h1 class="cp-single__title"><?php the_title(); ?></h1>
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="cp-single__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
					<?php endif; ?>
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
					<?php circlepress_share_buttons(); ?>
				</article>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
			?>
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
