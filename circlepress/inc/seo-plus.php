<?php
/**
 * SEO boosters: IndexNow instant indexing, ItemList schema for roundups,
 * reviewedBy E-E-A-T merge, LCP fetchpriority. (#2 schema, #8 schema, #9)
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- IndexNow setting ---------- */
function circlepress_seoplus_customize( $wp_customize ) {
	$wp_customize->add_setting( 'circlepress_indexnow', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control(
		'circlepress_indexnow',
		array(
			'label'       => __( 'IndexNow: ping Bing/Yandex on publish', 'circlepress' ),
			'description' => __( 'Instant indexing. The verification key file is served automatically (no FTP needed).', 'circlepress' ),
			'section'     => 'circlepress_seo',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'circlepress_seoplus_customize' );

/* ---------- IndexNow key (served virtually, no root file needed) ---------- */
function circlepress_indexnow_key() {
	$key = get_option( 'circlepress_indexnow_key', '' );
	if ( ! preg_match( '/^[a-f0-9]{32}$/', (string) $key ) ) {
		$key = bin2hex( random_bytes( 16 ) );
		update_option( 'circlepress_indexnow_key', $key, false );
	}
	return $key;
}

function circlepress_indexnow_rewrite() {
	add_rewrite_rule( '^' . circlepress_indexnow_key() . '\.txt$', 'index.php?cp_indexnow=1', 'top' );
}
add_action( 'init', 'circlepress_indexnow_rewrite' );

function circlepress_indexnow_qv( $vars ) {
	$vars[] = 'cp_indexnow';
	return $vars;
}
add_filter( 'query_vars', 'circlepress_indexnow_qv' );

function circlepress_indexnow_serve() {
	if ( get_query_var( 'cp_indexnow' ) ) {
		header( 'Content-Type: text/plain' );
		echo esc_html( circlepress_indexnow_key() );
		exit;
	}
}
add_action( 'template_redirect', 'circlepress_indexnow_serve' );

function circlepress_indexnow_flush() {
	circlepress_indexnow_rewrite();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'circlepress_indexnow_flush' );

function circlepress_indexnow_submit( $post_id ) {
	if ( ! get_theme_mod( 'circlepress_indexnow', true ) ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
		return;
	}
	if ( ! in_array( get_post_type( $post_id ), array( 'post', 'page' ), true ) ) {
		return;
	}
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( ! $host || false === strpos( $host, '.' ) || 'localhost' === $host ) {
		return;
	}
	$key = circlepress_indexnow_key();
	wp_remote_post(
		'https://api.indexnow.org/indexnow',
		array(
			'timeout'  => 4,
			'blocking' => false,
			'headers'  => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body'     => wp_json_encode(
				array(
					'host'       => $host,
					'key'        => $key,
					'keyLocation' => home_url( '/' . $key . '.txt' ),
					'urlList'    => array( get_permalink( $post_id ) ),
				)
			),
		)
	);
}
add_action( 'publish_post', 'circlepress_indexnow_submit' );
add_action( 'publish_page', 'circlepress_indexnow_submit' );

/* ---------- LCP: fetchpriority + async decoding ---------- */
function circlepress_lcp_attrs( $attr, $attachment, $size ) {
	if ( is_admin() || ! is_singular() ) {
		return $attr;
	}
	if ( ! isset( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	static $done = false;
	if ( ! $done && in_array( $size, array( 'large', 'full', 'medium_large' ), true ) ) {
		$attr['fetchpriority'] = 'high';
		$done = true;
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'circlepress_lcp_attrs', 10, 3 );

/* ---------- Extra schema: ItemList (roundups) + reviewedBy (E-E-A-T) ---------- */
function circlepress_seoplus_schema() {
	if ( ! is_singular() || ! circlepress_schema_enabled() ) {
		return;
	}
	$post_id = get_queried_object_id();
	$content = get_post_field( 'post_content', $post_id );

	/* ItemList from [rank_item] / [gift_item] shortcodes. */
	if ( $content && ( false !== strpos( $content, '[rank_item' ) || false !== strpos( $content, '[gift_item' ) ) ) {
		preg_match_all( '/\[(rank_item|gift_item)([^\]]*)\]/', $content, $m, PREG_SET_ORDER );
		if ( $m ) {
			$items = array();
			$pos   = 0;
			$cur   = get_post_meta( $post_id, '_cp_product_currency', true );
			$cur   = $cur ? $cur : 'USD';
			foreach ( $m as $match ) {
				$a = shortcode_parse_atts( $match[2] );
				if ( empty( $a['title'] ) ) {
					continue;
				}
				++$pos;
				$item = array(
					'@type' => 'Product',
					'name'  => $a['title'],
				);
				if ( ! empty( $a['image'] ) ) {
					$item['image'] = $a['image'];
				}
				if ( ! empty( $a['url'] ) ) {
					$item['url'] = $a['url'];
				}
				if ( ! empty( $a['price'] ) ) {
					$num = preg_replace( '/[^0-9.]/', '', $a['price'] );
					if ( '' !== $num ) {
						$item['offers'] = array(
							'@type'         => 'Offer',
							'price'         => $num,
							'priceCurrency' => $cur,
							'availability'  => 'https://schema.org/InStock',
						);
					}
				}
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $pos,
					'item'     => $item,
				);
			}
			if ( $items ) {
				echo '<script type="application/ld+json">' . wp_json_encode(
					array(
						'@context'        => 'https://schema.org',
						'@type'           => 'ItemList',
						'name'            => get_the_title( $post_id ),
						'itemListElement' => $items,
					),
					JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
				) . '</script>' . "\n";
			}
		}
	}

	/* reviewedBy merged onto the main Article node via shared @id. */
	$rname = get_post_meta( $post_id, '_cp_reviewer_name', true );
	if ( $rname ) {
		$reviewer = array(
			'@type' => 'Person',
			'name'  => $rname,
		);
		$rrole = get_post_meta( $post_id, '_cp_reviewer_role', true );
		if ( $rrole ) {
			$reviewer['jobTitle'] = $rrole;
		}
		echo '<script type="application/ld+json">' . wp_json_encode(
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'Article',
				'@id'        => get_permalink( $post_id ) . '#article',
				'reviewedBy' => $reviewer,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'circlepress_seoplus_schema', 21 );
