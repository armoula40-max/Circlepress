<?php
/**
 * Alternative homepage layouts: grid / list / showcase.
 * Magazine layout stays in home-sections.php.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout   = circlepress_home_layout();
    if ( in_array( $layout, array( 'slider', 'bento', 'masonry', 'minimal', 'portal', 'editorial', 'calm' ), true ) ) {
	get_template_part( 'template-parts/home/home-shapes' );
	return;
}
$niche_id = circlepress_get_current_niche();
$niche    = circlepress_get_niche( $niche_id );
$title    = get_theme_mod( 'circlepress_hero_title', '' ) ? get_theme_mod( 'circlepress_hero_title', '' ) : $niche['hero_title'];
$sub      = get_theme_mod( 'circlepress_hero_sub', '' ) ? get_theme_mod( 'circlepress_hero_sub', '' ) : $niche['hero_sub'];
$blog_url = get_permalink( get_option( 'page_for_posts' ) );

/* ============ GRID: minimal hero + clean post grid ============ */
if ( 'grid' === $layout ) :
	?>
	<section class="cp-hero cp-hero--minimal">
		<span class="cp-kicker"><?php echo esc_html( $niche['label'] ); ?></span>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p style="color:var(--cp-muted)"><?php echo esc_html( $sub ); ?></p>
	</section>
	<?php
	$cats = get_categories( array( 'number' => 8, 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );
	if ( $cats ) :
		?>
		<section class="cp-section" style="text-align:center">
			<div class="cp-chips" style="justify-content:center">
				<?php foreach ( $cats as $cat ) : ?>
					<a class="cp-chip" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
	<section class="cp-section" id="cp-latest">
		<div class="cp-section__head"><div><span class="cp-section__kicker"><?php esc_html_e( 'Fresh from the blog', 'circlepress' ); ?></span><h2><?php esc_html_e( 'Latest articles', 'circlepress' ); ?></h2></div><a class="cp-more" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'View all', 'circlepress' ); ?></a></div>
		<div class="cp-grid cp-grid--3">
			<?php
			$gq = new WP_Query( array( 'posts_per_page' => 9, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
			while ( $gq->have_posts() ) :
				$gq->the_post();
				get_template_part( 'template-parts/components/post-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
	get_template_part( 'template-parts/components/newsletter' );
	return;
endif;

/* ============ LIST: classic blog rows + sidebar ============ */
if ( 'list' === $layout ) :
	?>
	<section class="cp-hero cp-hero--minimal" style="text-align:start">
		<span class="cp-kicker"><?php echo esc_html( $niche['label'] ); ?></span>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p style="color:var(--cp-muted)"><?php echo esc_html( $sub ); ?></p>
	</section>
	<div class="cp-layout" style="margin-top:10px">
		<div class="cp-list">
			<?php
			$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
			$lq    = new WP_Query(
				array(
					'posts_per_page'      => 6,
					'paged'               => $paged,
					'ignore_sticky_posts' => true,
				)
			);
			while ( $lq->have_posts() ) :
				$lq->the_post();
				get_template_part( 'template-parts/components/post-card-horizontal' );
			endwhile;
			?>
			<?php
			$links = paginate_links( array( 'type' => 'array', 'total' => $lq->max_num_pages, 'prev_text' => '←', 'next_text' => '→' ) );
			if ( $links ) {
				echo '<nav class="cp-pagination" aria-label="' . esc_attr__( 'Posts pagination', 'circlepress' ) . '">';
				foreach ( $links as $link ) {
					echo wp_kses_post( $link );
				}
				echo '</nav>';
			}
			wp_reset_postdata();
			?>
		</div>
		<?php get_sidebar(); ?>
	</div>
	<?php
	get_template_part( 'template-parts/components/newsletter' );
	return;
endif;

/* ============ SHOWCASE: big visual feature rows ============ */
$sq = circlepress_featured_query( 3 );
if ( $sq->have_posts() ) :
	$first = true;
	echo '<section class="cp-section">';
	while ( $sq->have_posts() ) :
		$sq->the_post();
		$bg = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
		?>
		<div class="cp-show-row">
			<div class="cp-show__media" <?php echo $bg ? 'style="background-image:url(' . esc_url( $bg ) . ')"' : ''; ?> role="img" aria-label="<?php the_title_attribute(); ?>"></div>
			<div class="cp-show__text">
				<?php if ( $first ) : ?><span class="cp-kicker"><?php echo esc_html( $niche['label'] ); ?></span><?php endif; ?>
				<h2><a href="<?php the_permalink(); ?>" style="color:var(--cp-text)"><?php the_title(); ?></a></h2>
				<p style="color:var(--cp-muted)"><?php echo esc_html( circlepress_clean_excerpt( null, 22 ) ); ?></p>
				<p><a class="cp-btn cp-btn--sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'circlepress' ); ?></a></p>
			</div>
		</div>
		<?php
		$first = false;
	endwhile;
	wp_reset_postdata();
	echo '</section>';
endif;
?>
<section class="cp-section" id="cp-latest">
	<div class="cp-section__head"><div><span class="cp-section__kicker"><?php esc_html_e( 'Fresh from the blog', 'circlepress' ); ?></span><h2><?php esc_html_e( 'Latest articles', 'circlepress' ); ?></h2></div><a class="cp-more" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'View all', 'circlepress' ); ?></a></div>
	<div class="cp-grid cp-grid--4">
		<?php
		$lq2 = new WP_Query( array( 'posts_per_page' => 4, 'offset' => 3, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		if ( ! $lq2->have_posts() ) {
			$lq2 = new WP_Query( array( 'posts_per_page' => 4, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		}
		while ( $lq2->have_posts() ) :
			$lq2->the_post();
			get_template_part( 'template-parts/components/post-card' );
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</section>
<?php get_template_part( 'template-parts/components/newsletter' ); ?>
