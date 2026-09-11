<?php
/**
 * Lightweight built-in SEO: meta description, canonical, robots, OG/Twitter, breadcrumbs.
 * Disable any overlapping part if you use RankMath / Yoast.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function circlepress_seo_enabled( $key ) {
	return (bool) get_theme_mod( 'circlepress_' . $key, true );
}

/**
 * Is a major SEO plugin active? If so, step back to avoid duplicates.
 *
 * @return bool
 */
function circlepress_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'RankMath' );
}

/* ---------- Head meta ---------- */
function circlepress_head_meta() {
	if ( ! circlepress_seo_enabled( 'seo_meta' ) && ! circlepress_seo_enabled( 'seo_og' ) ) {
		return;
	}
	if ( circlepress_seo_plugin_active() ) {
		return; /* Let the SEO plugin handle it. */
	}

	$desc = circlepress_meta_description();
	if ( circlepress_seo_enabled( 'seo_meta' ) ) {
		if ( $desc ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
		echo '<link rel="canonical" href="' . esc_url( circlepress_canonical_url() ) . '">' . "\n";
		if ( is_search() || is_404() || is_paged() || is_attachment() ) {
			echo '<meta name="robots" content="noindex,follow,max-image-preview:large">' . "\n";
		} else {
			echo '<meta name="robots" content="max-image-preview:large">' . "\n";
		}
	}

	if ( ! circlepress_seo_enabled( 'seo_og' ) ) {
		return;
	}

	$title = wp_get_document_title();
	$type  = is_singular() ? 'article' : 'website';
	$url   = circlepress_canonical_url();
	$image = circlepress_og_image();

	echo '<meta property="og:locale" content="' . esc_attr( str_replace( '-', '_', get_locale() ) ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
		echo '<meta property="og:image:alt" content="' . esc_attr( $title ) . '">' . "\n";
	}
	if ( is_singular( 'post' ) ) {
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( DATE_W3C ) ) . '">' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( DATE_W3C ) ) . '">' . "\n";
		echo '<meta property="article:author" content="' . esc_attr( get_the_author() ) . '">' . "\n";
		foreach ( get_the_category() as $cat ) {
			echo '<meta property="article:section" content="' . esc_attr( $cat->name ) . '">' . "\n";
		}
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'circlepress_head_meta', 5 );

/**
 * Meta description per context.
 *
 * @return string
 */
function circlepress_meta_description() {
	if ( is_singular() ) {
		$id = get_queried_object_id();
		$custom = get_post_meta( $id, '_cp_meta_description', true );
		if ( $custom ) {
			return mb_substr( wp_strip_all_tags( $custom ), 0, 160 );
		}
		if ( has_excerpt( $id ) ) {
			return mb_substr( wp_strip_all_tags( get_the_excerpt( $id ) ), 0, 160 );
		}
		$content = wp_strip_all_tags( get_post_field( 'post_content', $id ) );
		$content = preg_replace( '/\s+/', ' ', trim( $content ) );
		return mb_substr( $content, 0, 160 );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$desc = term_description();
		if ( $desc ) {
			return mb_substr( wp_strip_all_tags( $desc ), 0, 160 );
		}
		/* translators: %s: term name. */
		return sprintf( __( 'Latest articles about %s.', 'circlepress' ), single_term_title( '', false ) );
	}
	if ( is_author() ) {
		/* translators: %s: author name. */
		return sprintf( __( 'Articles by %s.', 'circlepress' ), get_the_author() );
	}
	if ( is_search() ) {
		/* translators: %s: search query. */
		return sprintf( __( 'Search results for %s.', 'circlepress' ), get_search_query() );
	}
	$tagline = get_bloginfo( 'description' );
	if ( $tagline ) {
		return mb_substr( $tagline, 0, 160 );
	}
	return mb_substr( get_bloginfo( 'name' ), 0, 160 );
}

/**
 * Canonical URL.
 *
 * @return string
 */
function circlepress_canonical_url() {
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	$link = '';
	if ( is_category() || is_tag() || is_tax() ) {
		$link = get_term_link( get_queried_object() );
	} elseif ( is_author() ) {
		$link = get_author_posts_url( get_queried_object_id() );
	} elseif ( is_post_type_archive() ) {
		$link = get_post_type_archive_link( get_query_var( 'post_type' ) );
	} elseif ( is_search() ) {
		$link = get_search_link();
	} elseif ( is_home() ) {
		$link = get_permalink( get_option( 'page_for_posts' ) );
	}
	if ( ! $link || is_wp_error( $link ) ) {
		global $wp;
		$link = home_url( add_query_arg( array(), $wp->request ) );
	}
	return $link;
}

/**
 * OG/Twitter image.
 *
 * @return string
 */
function circlepress_og_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		return get_the_post_thumbnail_url( null, 'large' );
	}
	$logo = get_theme_mod( 'custom_logo' );
	if ( $logo ) {
		$src = wp_get_attachment_image_src( $logo, 'full' );
		if ( $src ) {
			return $src[0];
		}
	}
	return '';
}

/* ---------- Breadcrumbs ---------- */

/**
 * Breadcrumb items for the current view.
 *
 * @return array of [label, url|null]
 */
function circlepress_breadcrumb_items() {
	$items = array( array( __( 'Home', 'circlepress' ), home_url( '/' ) ) );
	if ( is_front_page() ) {
		return array();
	}
	if ( is_home() ) {
		$items[] = array( __( 'Blog', 'circlepress' ), null );
	} elseif ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( $cats ) {
			$cat = $cats[0];
			$ancestors = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
			foreach ( $ancestors as $anc ) {
				$t = get_term( $anc, 'category' );
				if ( $t && ! is_wp_error( $t ) ) {
					$items[] = array( $t->name, get_term_link( $t ) );
				}
			}
			$items[] = array( $cat->name, get_category_link( $cat ) );
		}
		$items[] = array( get_the_title(), null );
	} elseif ( is_page() ) {
		$post = get_queried_object();
		if ( $post && $post->post_parent ) {
			$ancestors = array_reverse( get_ancestors( $post->ID, 'page' ) );
			foreach ( $ancestors as $anc ) {
				$items[] = array( get_the_title( $anc ), get_permalink( $anc ) );
			}
		}
		$items[] = array( get_the_title(), null );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$items[] = array( single_term_title( '', false ), null );
	} elseif ( is_author() ) {
		$items[] = array( get_the_author(), null );
	} elseif ( is_search() ) {
		/* translators: %s: query. */
		$items[] = array( sprintf( __( 'Search: %s', 'circlepress' ), get_search_query() ), null );
	} elseif ( is_404() ) {
		$items[] = array( __( 'Page not found', 'circlepress' ), null );
	}
	return $items;
}

/**
 * Render breadcrumbs nav.
 */
function circlepress_breadcrumbs() {
	if ( ! get_theme_mod( 'circlepress_show_breadcrumbs', true ) ) {
		return;
	}
	$items = circlepress_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}
	echo '<nav class="cp-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'circlepress' ) . '"><ol>';
	$pos = 0;
	foreach ( $items as $item ) {
		++$pos;
		echo '<li>';
		if ( ! empty( $item[1] ) && $pos < count( $items ) ) {
			echo '<a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item[0] ) . '</span>';
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}
