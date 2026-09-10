<?php
/**
 * Template tags & small helpers.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post meta line (date, reading time, comments) with SVG icons.
 */
function circlepress_posted_on() {
	$time = sprintf(
		'<time datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
	echo '<span class="cp-card__date">' . circlepress_icon( 'calendar', 13 ) . ' ' . $time . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	if ( get_theme_mod( 'circlepress_show_reading_time', true ) ) {
		echo '<span class="cp-card__read">' . circlepress_icon( 'clock', 13 ) . ' ' . esc_html( circlepress_reading_time() ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '<span class="cp-card__comments">' . circlepress_icon( 'comment', 13 ) . ' ' . esc_html( sprintf( _n( '%s comment', '%s comments', get_comments_number(), 'circlepress' ), number_format_i18n( get_comments_number() ) ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Star rating row for cards (only when the post has a rating).
 *
 * @param int|null $post_id Post id.
 */
function circlepress_card_rating( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$rating  = get_post_meta( $post_id, '_cp_rating', true );
	if ( '' === $rating ) {
		return;
	}
	echo '<div class="cp-card__stars">' . circlepress_stars( $rating ) . ' <span>' . esc_html( $rating ) . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Estimated reading time.
 *
 * @param int|null $post_id Post id.
 * @return string
 */
function circlepress_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$words   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
	$mins    = max( 1, (int) round( $words / 200 ) );
	/* translators: %d: minutes. */
	return sprintf( __( '%d min read', 'circlepress' ), $mins );
}

/**
 * Numeric pagination.
 */
function circlepress_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'array',
			'prev_text' => '←',
			'next_text' => '→',
		)
	);
	if ( ! $links ) {
		return;
	}
	echo '<nav class="cp-pagination" aria-label="' . esc_attr__( 'Posts pagination', 'circlepress' ) . '">';
	foreach ( $links as $link ) {
		echo wp_kses_post( $link );
	}
	echo '</nav>';
}

/**
 * Social share buttons (SVG icons).
 */
function circlepress_share_buttons() {
	if ( ! get_theme_mod( 'circlepress_show_share', true ) || ! is_singular() ) {
		return;
	}
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$links = array(
		'pin'  => array( 'https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $title, 'pinterest', 'Pinterest' ),
		'fb'   => array( 'https://www.facebook.com/sharer/sharer.php?u=' . $url, 'facebook', 'Facebook' ),
		'x'    => array( 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title, 'x', 'X' ),
		'mail' => array( 'mailto:?subject=' . $title . '&body=' . $url, 'mail', __( 'Email', 'circlepress' ) ),
	);
	echo '<div class="cp-share"><strong>' . esc_html__( 'Share this:', 'circlepress' ) . '</strong> ';
	foreach ( $links as $key => $l ) {
		$target = 'mail' === $key ? '' : ' target="_blank" rel="noopener"';
		printf(
			'<a class="cp-share--%1$s" href="%2$s"%3$s aria-label="%4$s" title="%4$s">%5$s</a>',
			esc_attr( $key ),
			esc_url( $l[0] ),
			$target, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $l[2] ),
			circlepress_icon( $l[1], 16 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
	echo '<a class="cp-share--link cp-copy-link" href="' . esc_url( get_permalink() ) . '" aria-label="' . esc_attr__( 'Copy link', 'circlepress' ) . '" title="' . esc_attr__( 'Copy link', 'circlepress' ) . '">' . circlepress_icon( 'link', 16 ) . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Author box.
 */
function circlepress_author_box() {
	if ( ! get_theme_mod( 'circlepress_show_author', true ) || ! is_singular( 'post' ) ) {
		return;
	}
	$author_id = get_the_author_meta( 'ID' );
	$desc      = get_the_author_meta( 'description' );
	if ( ! $desc ) {
		return;
	}
	echo '<div class="cp-author">';
	echo get_avatar( $author_id, 96 );
	echo '<div><span class="cp-author__label">' . esc_html__( 'About the author', 'circlepress' ) . '</span><strong>' . esc_html( get_the_author() ) . '</strong><p>' . esc_html( $desc ) . '</p></div>';
	echo '</div>';
}

/**
 * Related posts (same categories, fallback same tags).
 */
function circlepress_related_posts() {
	if ( ! get_theme_mod( 'circlepress_show_related', true ) || ! is_singular( 'post' ) ) {
		return;
	}
	$cats = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );
	$q    = new WP_Query(
		array(
			'category__in'        => $cats ? $cats : array( 0 ),
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( ! $q->have_posts() ) {
		return;
	}
	echo '<section class="cp-related cp-section"><div class="cp-section__head"><h2>' . esc_html__( 'You may also like', 'circlepress' ) . '</h2></div><div class="cp-related__grid">';
	while ( $q->have_posts() ) {
		$q->the_post();
		get_template_part( 'template-parts/components/post-card' );
	}
	wp_reset_postdata();
	echo '</div></section>';
}

/**
 * Auto table of contents from H2/H3 in content.
 * Returns array( toc_html, filtered_content ).
 *
 * @param string $content Post content.
 * @return array
 */
function circlepress_toc( $content ) {
	if ( ! get_theme_mod( 'circlepress_show_toc', true ) || ! is_singular() ) {
		return array( '', $content );
	}
	if ( get_post_meta( get_the_ID(), '_cp_hide_toc', true ) ) {
		return array( '', $content );
	}
	if ( ! preg_match_all( '/<h([23])([^>]*)>(.*?)<\/h\1>/i', $content, $m, PREG_SET_ORDER ) ) {
		return array( '', $content );
	}
	if ( count( $m ) < 2 ) {
		return array( '', $content );
	}
	$items = array();
	$i     = 0;
	foreach ( $m as $match ) {
		++$i;
		$id     = 'cp-toc-' . $i;
		$text   = wp_strip_all_tags( $match[3] );
		$items[] = '<li class="cp-toc--h' . $match[1] . '"><a href="#' . $id . '">' . esc_html( $text ) . '</a></li>';
		$new    = '<h' . $match[1] . $match[2] . ' id="' . $id . '">' . $match[3] . '</h' . $match[1] . '>';
		$content = preg_replace( '/' . preg_quote( $match[0], '/' ) . '/', $new, $content, 1 );
	}
	$toc = '<nav class="cp-toc" aria-label="' . esc_attr__( 'Table of contents', 'circlepress' ) . '"><strong>' . esc_html__( 'In this article', 'circlepress' ) . '</strong><ol>' . implode( '', $items ) . '</ol></nav>';
	return array( $toc, $content );
}

/**
 * Turn a textarea (one "Q | A" per line) into FAQ items.
 *
 * @param string $raw Raw text.
 * @return array
 */
function circlepress_parse_faq( $raw ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line || false === strpos( $line, '|' ) ) {
			continue;
		}
		list( $q, $a ) = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( $q && $a ) {
			$out[] = array( 'q' => $q, 'a' => $a );
		}
	}
	return $out;
}

/**
 * Turn a textarea (one item per line) into a list.
 *
 * @param string $raw Raw text.
 * @return array
 */
function circlepress_parse_lines( $raw ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$out[] = $line;
		}
	}
	return $out;
}

/**
 * Star rating HTML (0-5, supports halves via ★/⯪/☆).
 *
 * @param float $rating Rating value.
 * @return string
 */
function circlepress_stars( $rating ) {
	$rating = max( 0, min( 5, (float) $rating ) );
	$full   = (int) floor( $rating );
	$half   = ( $rating - $full ) >= 0.5 ? 1 : 0;
	$empty  = 5 - $full - $half;
	return '<span class="cp-stars" aria-label="' . esc_attr( sprintf( __( '%s out of 5 stars', 'circlepress' ), $rating ) ) . '">' . str_repeat( '★', $full ) . ( $half ? '⯪' : '' ) . str_repeat( '☆', $empty ) . '</span>';
}

/**
 * Affiliate rel attribute.
 *
 * @return string
 */
function circlepress_affiliate_rel() {
	return get_theme_mod( 'circlepress_affiliate_nofollow', true ) ? ' rel="nofollow sponsored noopener" target="_blank"' : ' target="_blank" rel="noopener"';
}

/**
 * Social links: url + SVG icon name + label.
 *
 * @return array
 */
function circlepress_social_links() {
	$nets = array(
		'facebook'  => array( 'facebook', 'Facebook' ),
		'instagram' => array( 'instagram', 'Instagram' ),
		'pinterest' => array( 'pinterest', 'Pinterest' ),
		'youtube'   => array( 'youtube', 'YouTube' ),
		'tiktok'    => array( 'tiktok', 'TikTok' ),
		'x'         => array( 'x', 'X' ),
	);
	$out  = array();
	foreach ( $nets as $key => $meta ) {
		$url = get_theme_mod( 'circlepress_social_' . $key, '' );
		if ( $url ) {
			$out[ $key ] = array( $url, $meta[0], $meta[1] );
		}
	}
	return $out;
}

/**
 * Featured posts query helper.
 *
 * @param int $count Number of posts.
 * @return WP_Query
 */
function circlepress_featured_query( $count = 4 ) {
	$tag = get_theme_mod( 'circlepress_featured_tag', 'featured' );
	$args = array(
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	if ( $tag && get_term_by( 'slug', $tag, 'post_tag' ) ) {
		$args['tag_slug__in'] = array( $tag );
	}
	return new WP_Query( $args );
}
