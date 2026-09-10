<?php
/**
 * Single post — niche-aware (recipe/howto/product cards, jump buttons, TOC, share…).
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
				$post_id  = get_the_ID();
				$subtitle = get_post_meta( $post_id, '_cp_subtitle', true );
				$rating   = get_post_meta( $post_id, '_cp_rating', true );
				$has_recipe = (bool) circlepress_parse_lines( get_post_meta( $post_id, '_cp_recipe_ingredients', true ) );
				$has_howto  = (bool) circlepress_parse_lines( get_post_meta( $post_id, '_cp_howto_steps', true ) );
				$niche      = circlepress_get_niche( circlepress_get_current_niche() );
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cp-single' ); ?>>
					<div class="cp-single__cat">
						<?php
						$cats = get_the_category();
						foreach ( array_slice( $cats, 0, 3 ) as $cat ) {
							echo '<a class="cp-chip" href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
						}
						?>
					</div>
					<h1 class="cp-single__title"><?php the_title(); ?></h1>
					<?php if ( $subtitle ) : ?><p class="cp-single__subtitle"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
					<div class="cp-single__meta">
						<span><?php echo get_avatar( get_the_author_meta( 'ID' ), 28 ); ?> <?php the_author_posts_link(); ?></span>
						<span><?php echo circlepress_icon( 'calendar', 14 ); ?> <?php echo esc_html( get_the_date() ); ?></span>
						<?php if ( get_theme_mod( 'circlepress_show_reading_time', true ) ) : ?>
							<span><?php echo circlepress_icon( 'clock', 14 ); ?> <?php echo esc_html( circlepress_reading_time() ); ?></span>
						<?php endif; ?>
						<?php if ( $rating ) : ?>
							<span><?php echo circlepress_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <strong><?php echo esc_html( $rating ); ?>/5</strong></span>
						<?php endif; ?>
					</div>

					<?php circlepress_action_bar(); ?>

					<?php circlepress_ad( 'below_title' ); ?>

					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="cp-single__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
					<?php endif; ?>

					<div class="cp-entry entry-content">
						<?php
						$content = get_the_content();
						$content = apply_filters( 'the_content', $content );
						/* TOC is injected before content; niche boxes/ads already filtered via the_content. */
						list( $toc, $content ) = circlepress_toc( $content );
						echo $toc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>

					<?php
					wp_link_pages(
						array(
							'before' => '<div class="cp-pagination"><span>' . esc_html__( 'Pages:', 'circlepress' ) . '</span> ',
							'after'  => '</div>',
						)
					);
					$tags = get_the_tags();
					if ( $tags ) :
						?>
						<div class="cp-tags">
							<?php foreach ( $tags as $tag ) : ?>
								<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php circlepress_share_buttons(); ?>
					<?php circlepress_author_box(); ?>
				</article>

				<?php circlepress_related_posts(); ?>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			<?php endwhile; ?>
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
