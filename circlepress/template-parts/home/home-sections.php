<?php
/**
 * Dynamic niche homepage: renders the active niche's sections in order.
 * Each niche gets its own layout via inc/niches.php (+ Customizer toggles).
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_layout = function_exists( 'circlepress_home_layout' ) ? circlepress_home_layout() : 'magazine';
if ( 'magazine' !== $home_layout ) {
	get_template_part( 'template-parts/home/home-alt' );
	return;
}

$niche_id = circlepress_get_current_niche();
$niche    = circlepress_get_niche( $niche_id );
$sections = isset( $niche['sections'] ) ? $niche['sections'] : array( 'hero', 'featured', 'latest' );

/* Special section titles per niche. */
$special_titles = array(
	'recipe_of_day' => __( "Today's Recipe", 'circlepress' ),
	'patterns'      => __( 'Popular Patterns', 'circlepress' ),
	'guides'        => __( 'Care Guides', 'circlepress' ),
	'shop_picks'    => __( 'Shop Our Picks', 'circlepress' ),
	'tutorials'     => __( 'Step-by-Step Tutorials', 'circlepress' ),
	'comparison'    => __( 'Top Comparisons', 'circlepress' ),
	'makeovers'     => __( 'Room Makeovers', 'circlepress' ),
	'projects'      => __( 'Weekend Projects', 'circlepress' ),
	'routines'      => __( 'Routines That Work', 'circlepress' ),
	'reviews'       => __( 'Latest Reviews', 'circlepress' ),
	'planting'      => __( 'Planting Guides', 'circlepress' ),
	'seasonal'      => __( 'This Season', 'circlepress' ),
);

foreach ( $sections as $section ) {
	switch ( $section ) {

		case 'hero':
			if ( get_theme_mod( 'circlepress_home_hero', true ) ) {
				get_template_part( 'template-parts/components/hero' );
				if ( is_active_sidebar( 'home-top' ) ) {
					echo '<div class="cp-section">';
					dynamic_sidebar( 'home-top' );
					echo '</div>';
				}
			}
			break;

		case 'categories':
			if ( ! get_theme_mod( 'circlepress_home_categories', true ) ) {
				break;
			}
			$cats = get_categories( array( 'number' => 8, 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );
			if ( ! $cats ) {
				break;
			}
			echo '<section class="cp-section"><div class="cp-section__head"><h2>' . esc_html__( 'Explore topics', 'circlepress' ) . '</h2></div><div class="cp-chips">';
			foreach ( $cats as $cat ) {
				echo '<a class="cp-chip" href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . ' <span>(' . esc_html( $cat->count ) . ')</span></a>';
			}
			echo '</div></section>';
			break;

		case 'featured':
			if ( ! get_theme_mod( 'circlepress_home_featured', true ) ) {
				break;
			}
			$q = circlepress_featured_query( 3 );
			if ( ! $q->have_posts() ) {
				break;
			}
			echo '<section class="cp-section"><div class="cp-section__head"><h2>' . esc_html( $niche['icon'] . ' ' . __( 'Featured', 'circlepress' ) ) . '</h2><a class="cp-more" href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'View all →', 'circlepress' ) . '</a></div><div class="cp-grid cp-grid--3">';
			while ( $q->have_posts() ) {
				$q->the_post();
				get_template_part( 'template-parts/components/post-card' );
			}
			wp_reset_postdata();
			echo '</div></section>';
			break;

		case 'trending':
			if ( ! get_theme_mod( 'circlepress_home_trending', true ) ) {
				break;
			}
			$tq = new WP_Query(
				array(
					'posts_per_page'      => 4,
					'orderby'             => 'comment_count',
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);
			if ( ! $tq->have_posts() ) {
				break;
			}
			echo '<section class="cp-section"><div class="cp-section__head"><h2>🔥 ' . esc_html__( 'Trending now', 'circlepress' ) . '</h2></div><div class="cp-grid cp-grid--4">';
			while ( $tq->have_posts() ) {
				$tq->the_post();
				get_template_part( 'template-parts/components/post-card' );
			}
			wp_reset_postdata();
			echo '</div></section>';
			break;

		case 'latest':
			if ( ! get_theme_mod( 'circlepress_home_latest', true ) ) {
				break;
			}
			$lq = new WP_Query(
				array(
					'posts_per_page'      => 6,
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);
			if ( ! $lq->have_posts() ) {
				break;
			}
			echo '<section class="cp-section" id="cp-latest"><div class="cp-section__head"><h2>📰 ' . esc_html__( 'Latest articles', 'circlepress' ) . '</h2><a class="cp-more" href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'View all →', 'circlepress' ) . '</a></div><div class="cp-grid cp-grid--3">';
			while ( $lq->have_posts() ) {
				$lq->the_post();
				get_template_part( 'template-parts/components/post-card' );
			}
			wp_reset_postdata();
			echo '</div></section>';
			break;

		case 'newsletter':
			if ( get_theme_mod( 'circlepress_home_newsletter', true ) ) {
				get_template_part( 'template-parts/components/newsletter' );
			}
			break;

		case 'faq':
			if ( ! get_theme_mod( 'circlepress_home_faq', true ) ) {
				break;
			}
			$items = circlepress_parse_faq( get_theme_mod( 'circlepress_home_faq', '' ) );
			if ( ! $items ) {
				break;
			}
			echo '<section class="cp-section"><div class="cp-section__head"><h2>❓ ' . esc_html__( 'Common questions', 'circlepress' ) . '</h2></div><div class="cp-faq">';
			foreach ( $items as $f ) {
				echo '<details><summary>' . esc_html( $f['q'] ) . '</summary><div>' . esc_html( $f['a'] ) . '</div></details>';
			}
			echo '</div></section>';
			break;

		default:
			/* Niche-special spotlight sections (offset query + niche title). */
			if ( ! isset( $special_titles[ $section ] ) ) {
				break;
			}
			$sq = new WP_Query(
				array(
					'posts_per_page'      => 3,
					'offset'              => 3,
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);
			if ( ! $sq->have_posts() ) {
				$sq = new WP_Query(
					array(
						'posts_per_page'      => 3,
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);
			}
			if ( ! $sq->have_posts() ) {
				break;
			}
			echo '<section class="cp-section"><div class="cp-section__head"><h2>' . esc_html( $niche['icon'] . ' ' . $special_titles[ $section ] ) . '</h2></div><div class="cp-grid cp-grid--3">';
			while ( $sq->have_posts() ) {
				$sq->the_post();
				get_template_part( 'template-parts/components/post-card' );
			}
			wp_reset_postdata();
			echo '</div></section>';
			break;
	}
}
