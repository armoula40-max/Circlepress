<?php
/**
 * New homepage shapes: slider / bento / masonry / minimal / portal / editorial.
 * Magazine stays in home-sections.php; grid/list/showcase in home-alt.php.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout   = circlepress_home_layout();
$niche_id = circlepress_get_current_niche();
$niche    = circlepress_get_niche( $niche_id );
$title    = get_theme_mod( 'circlepress_hero_title', '' ) ? get_theme_mod( 'circlepress_hero_title', '' ) : $niche['hero_title'];
$sub      = get_theme_mod( 'circlepress_hero_sub', '' ) ? get_theme_mod( 'circlepress_hero_sub', '' ) : $niche['hero_sub'];
$blog_url = get_permalink( get_option( 'page_for_posts' ) );

/* ============ CALM: soft editorial recipe journal ============ */
if ( 'calm' === $layout ) :
	$calm_q = circlepress_featured_query( 3 );
	if ( ! $calm_q->have_posts() ) {
		$calm_q = new WP_Query( array( 'posts_per_page' => 3, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
	}
	$calm_posts = array();
	while ( $calm_q->have_posts() ) { $calm_q->the_post(); $calm_posts[] = get_post(); }
	wp_reset_postdata();
	?>
	<section class="cp-calm-hero">
		<div class="cp-calm-hero__copy"><span class="cp-calm-kicker"><?php echo esc_html( $niche['label'] ); ?> · <?php esc_html_e( 'Kitchen notes', 'circlepress' ); ?></span><h1><?php echo esc_html( $title ); ?></h1><p><?php echo esc_html( $sub ); ?></p><a class="cp-calm-link" href="#cp-latest"><?php esc_html_e( 'Explore the latest', 'circlepress' ); ?> ↓</a></div>
		<div class="cp-calm-hero__shape" aria-hidden="true"></div>
	</section>
	<?php if ( $calm_posts ) : ?><section class="cp-calm-featured"><div class="cp-calm-section-head"><div><span><?php esc_html_e( 'Fresh from the kitchen', 'circlepress' ); ?></span><h2><?php esc_html_e( 'Latest recipes & ideas', 'circlepress' ); ?></h2></div><a href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Browse all recipes', 'circlepress' ); ?> →</a></div><div class="cp-calm-featured__grid">
		<?php foreach ( $calm_posts as $index => $calm_post ) : setup_postdata( $calm_post ); $cats = get_the_category( $calm_post->ID ); ?><article class="cp-calm-story<?php echo 0 === $index ? ' cp-calm-story--main' : ''; ?>"><a class="cp-calm-story__media" href="<?php echo esc_url( get_permalink( $calm_post ) ); ?>"><?php echo get_the_post_thumbnail( $calm_post, 0 === $index ? 'large' : 'medium_large' ); ?></a><div class="cp-calm-story__body"><?php if ( $cats ) : ?><span><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?><h3><a href="<?php echo esc_url( get_permalink( $calm_post ) ); ?>"><?php echo esc_html( get_the_title( $calm_post ) ); ?></a></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt( $calm_post ), 22 ) ); ?></p></div></article><?php endforeach; wp_reset_postdata(); ?></div></section><?php endif; ?>
	<section class="cp-section cp-calm-latest" id="cp-latest"><div class="cp-calm-section-head"><div><span><?php esc_html_e( 'Simple recipes. Generous flavor.', 'circlepress' ); ?></span><h2><?php esc_html_e( 'Made for real life', 'circlepress' ); ?></h2></div><a href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'View all', 'circlepress' ); ?> →</a></div><div class="cp-grid cp-grid--3"><?php $calm_latest = new WP_Query( array( 'posts_per_page' => 6, 'offset' => 3, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) ); if ( ! $calm_latest->have_posts() ) { $calm_latest = new WP_Query( array( 'posts_per_page' => 6, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) ); } while ( $calm_latest->have_posts() ) : $calm_latest->the_post(); get_template_part( 'template-parts/components/post-card' ); endwhile; wp_reset_postdata(); ?></div></section>
	<?php get_template_part( 'template-parts/components/newsletter' ); return;
endif;

/* ============ SLIDER: featured carousel (Jannah/Newspaper style) ============ */
if ( 'slider' === $layout ) :
	$sq = circlepress_featured_query( 5 );
	if ( ! $sq->have_posts() ) {
		$sq = new WP_Query( array( 'posts_per_page' => 5, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
	}
	if ( $sq->have_posts() ) :
		?>
		<section class="cp-slider" data-cp-slider aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Featured posts', 'circlepress' ); ?>">
			<div class="cp-slider__track">
				<?php
				$i = 0;
				while ( $sq->have_posts() ) :
					$sq->the_post();
					$bg   = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
					$cats = get_the_category();
					?>
					<article class="cp-slide<?php echo 0 === $i ? ' is-active' : ''; ?>"<?php echo $bg ? ' style="background-image:url(' . esc_url( $bg ) . ')"' : ''; ?> aria-label="<?php the_title_attribute(); ?>">
						<div class="cp-slide__shade"></div>
						<div class="cp-slide__content">
							<?php if ( $cats ) : ?><span class="cp-kicker cp-kicker--light"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
							<p><a class="cp-btn" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'circlepress' ); ?></a></p>
						</div>
					</article>
					<?php
					$i++;
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<button class="cp-slider__arrow cp-slider__prev" type="button" aria-label="<?php esc_attr_e( 'Previous slide', 'circlepress' ); ?>">←</button>
			<button class="cp-slider__arrow cp-slider__next" type="button" aria-label="<?php esc_attr_e( 'Next slide', 'circlepress' ); ?>">→</button>
			<div class="cp-slider__dots">
				<?php for ( $d = 0; $d < $i; $d++ ) : ?>
					<button type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'circlepress' ), $d + 1 ) ); ?>"<?php echo 0 === $d ? ' class="is-active"' : ''; ?>></button>
				<?php endfor; ?>
			</div>
		</section>
	<?php endif; ?>
	<section class="cp-section" id="cp-latest">
		<div class="cp-section__head"><div><span class="cp-section__kicker"><?php esc_html_e( 'Fresh from the blog', 'circlepress' ); ?></span><h2><?php esc_html_e( 'Latest articles', 'circlepress' ); ?></h2></div><a class="cp-more" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'View all', 'circlepress' ); ?></a></div>
		<div class="cp-grid cp-grid--3">
			<?php
			$gq = new WP_Query( array( 'posts_per_page' => 6, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
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

/* ============ BENTO: hero mosaic (modern news portals) ============ */
if ( 'bento' === $layout ) :
	$bq = new WP_Query( array( 'posts_per_page' => 5, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
	if ( $bq->have_posts() ) :
		?>
		<section class="cp-bento" aria-label="<?php esc_attr_e( 'Top stories', 'circlepress' ); ?>">
			<?php
			$bi = 0;
			while ( $bq->have_posts() ) :
				$bq->the_post();
				$bg   = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
				$cats = get_the_category();
				?>
				<a class="cp-bento__tile<?php echo 0 === $bi ? ' cp-bento__tile--big' : ''; ?>" href="<?php the_permalink(); ?>"<?php echo $bg ? ' style="background-image:url(' . esc_url( $bg ) . ')"' : ''; ?>>
					<span class="cp-bento__shade"></span>
					<span class="cp-bento__text">
						<?php if ( $cats ) : ?><span class="cp-bento__cat"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
						<span class="cp-bento__title"><?php the_title(); ?></span>
					</span>
				</a>
				<?php
				$bi++;
			endwhile;
			wp_reset_postdata();
			?>
		</section>
	<?php endif; ?>
	<section class="cp-section" id="cp-latest">
		<div class="cp-section__head"><div><span class="cp-section__kicker"><?php esc_html_e( 'Fresh from the blog', 'circlepress' ); ?></span><h2><?php esc_html_e( 'Latest articles', 'circlepress' ); ?></h2></div><a class="cp-more" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'View all', 'circlepress' ); ?></a></div>
		<div class="cp-grid cp-grid--3">
			<?php
			$gq = new WP_Query( array( 'posts_per_page' => 6, 'offset' => 5, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
			if ( ! $gq->have_posts() ) {
				$gq = new WP_Query( array( 'posts_per_page' => 6, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
			}
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

/* ============ MASONRY: Pinterest-style columns ============ */
if ( ! function_exists( 'circlepress_masonry_variant_class' ) ) {
	function circlepress_masonry_variant_class( $classes ) {
		global $cp_masonry_variant;
		if ( isset( $cp_masonry_variant ) ) {
			$classes[] = 0 === $cp_masonry_variant ? 'cp-card--tall' : ( 1 === $cp_masonry_variant ? 'cp-card--mid' : 'cp-card--short' );
		}
		return $classes;
	}
}
if ( 'masonry' === $layout ) :
	?>
	<section class="cp-hero cp-hero--minimal">
		<span class="cp-kicker"><?php echo esc_html( $niche['label'] ); ?></span>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p style="color:var(--cp-muted)"><?php echo esc_html( $sub ); ?></p>
	</section>
	<section class="cp-section">
		<div class="cp-masonry">
			<?php
			add_filter( 'post_class', 'circlepress_masonry_variant_class' );
			global $cp_masonry_variant;
			$mq = new WP_Query( array( 'posts_per_page' => 12, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
			$mi = 0;
			while ( $mq->have_posts() ) :
				$mq->the_post();
				$cp_masonry_variant = $mi % 3;
				get_template_part( 'template-parts/components/post-card' );
				$mi++;
			endwhile;
			unset( $cp_masonry_variant );
			remove_filter( 'post_class', 'circlepress_masonry_variant_class' );
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
	get_template_part( 'template-parts/components/newsletter' );
	return;
endif;

/* ============ MINIMAL: centered reading (minimalist blogs) ============ */
if ( 'minimal' === $layout ) :
	?>
	<section class="cp-hero cp-hero--minimal">
		<span class="cp-kicker"><?php echo esc_html( $niche['label'] ); ?></span>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p style="color:var(--cp-muted)"><?php echo esc_html( $sub ); ?></p>
	</section>
	<div class="cp-minimal">
		<?php
		$minq = new WP_Query( array( 'posts_per_page' => 7, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		while ( $minq->have_posts() ) :
			$minq->the_post();
			$cats = get_the_category();
			?>
			<article class="cp-minimal__item">
				<?php if ( $cats ) : ?><a class="cp-card__kicker" href="<?php echo esc_url( get_category_link( $cats[0] ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a><?php endif; ?>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="cp-card__meta"><?php circlepress_posted_on(); ?></div>
				<?php if ( has_post_thumbnail() ) : ?>
					<a class="cp-minimal__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>" tabindex="-1"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?></a>
				<?php endif; ?>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
				<p><a class="cp-minimal__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continue reading →', 'circlepress' ); ?></a></p>
			</article>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<?php
	get_template_part( 'template-parts/components/newsletter' );
	return;
endif;

/* ============ PORTAL: sections per category (news sites) ============ */
if ( 'portal' === $layout ) :
	?>
	<section class="cp-hero cp-hero--minimal">
		<span class="cp-kicker"><?php echo esc_html( $niche['label'] ); ?></span>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p style="color:var(--cp-muted)"><?php echo esc_html( $sub ); ?></p>
	</section>
	<?php
	$portal_cats = get_categories( array( 'number' => 4, 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );
	foreach ( $portal_cats as $pcat ) :
		?>
		<section class="cp-section">
			<div class="cp-section__head"><div><span class="cp-section__kicker"><?php esc_html_e( 'Section', 'circlepress' ); ?></span><h2><?php echo esc_html( $pcat->name ); ?></h2></div><a class="cp-more" href="<?php echo esc_url( get_category_link( $pcat ) ); ?>"><?php esc_html_e( 'View all', 'circlepress' ); ?></a></div>
			<div class="cp-grid cp-grid--4">
				<?php
				$pq = new WP_Query( array( 'cat' => $pcat->term_id, 'posts_per_page' => 4, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
				while ( $pq->have_posts() ) :
					$pq->the_post();
					get_template_part( 'template-parts/components/post-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>
	<?php endforeach; ?>
	<?php
	get_template_part( 'template-parts/components/newsletter' );
	return;
endif;

/* ============ EDITORIAL: large alternating rows (premium magazines) ============ */
if ( 'editorial' === $layout ) :
	$eq = circlepress_featured_query( 5 );
	if ( ! $eq->have_posts() ) {
		$eq = new WP_Query( array( 'posts_per_page' => 5, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
	}
	if ( $eq->have_posts() ) :
		?>
		<section class="cp-section">
			<?php
			$en = 1;
			while ( $eq->have_posts() ) :
				$eq->the_post();
				$bg    = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
				$cats  = get_the_category();
				$flip  = ( $en % 2 ) ? '' : ' cp-ed__row--flip';
				?>
				<div class="cp-ed__row<?php echo esc_attr( $flip ); ?>">
					<a class="cp-ed__media" href="<?php the_permalink(); ?>"<?php echo $bg ? ' style="background-image:url(' . esc_url( $bg ) . ')"' : ''; ?> aria-label="<?php the_title_attribute(); ?>" tabindex="-1"><span class="cp-ed__num"><?php echo esc_html( sprintf( '%02d', $en ) ); ?></span></a>
					<div class="cp-ed__text">
						<?php if ( $cats ) : ?><a class="cp-card__kicker" href="<?php echo esc_url( get_category_link( $cats[0] ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a><?php endif; ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="cp-card__meta"><?php circlepress_posted_on(); ?></div>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
						<p><a class="cp-btn cp-btn--outline" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read the story', 'circlepress' ); ?></a></p>
					</div>
				</div>
				<?php
				$en++;
			endwhile;
			wp_reset_postdata();
			?>
		</section>
	<?php endif; ?>
	<section class="cp-section" id="cp-latest">
		<div class="cp-section__head"><div><span class="cp-section__kicker"><?php esc_html_e( 'Fresh from the blog', 'circlepress' ); ?></span><h2><?php esc_html_e( 'Latest articles', 'circlepress' ); ?></h2></div><a class="cp-more" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'View all', 'circlepress' ); ?></a></div>
		<div class="cp-grid cp-grid--3">
			<?php
			$gq = new WP_Query( array( 'posts_per_page' => 6, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
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
