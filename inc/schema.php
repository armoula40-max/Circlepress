<?php
/**
 * Schema.org JSON-LD per niche:
 * Food=Recipe · Crochet/DIY/Nails/Gardening=HowTo · Furniture/Beauty=Product+Review
 * + Article, FAQPage, BreadcrumbList, WebSite, Organization everywhere relevant.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function circlepress_schema_enabled() {
	if ( ! get_theme_mod( 'circlepress_seo_schema', true ) ) {
		return false;
	}
	if ( circlepress_seo_plugin_active() ) {
		return false; /* Avoid duplicate schema with RankMath/Yoast. */
	}
	return true;
}

function circlepress_schema_output() {
	if ( ! circlepress_schema_enabled() ) {
		return;
	}
	$graph  = array();
	$niche  = circlepress_get_current_niche();
	$org_id = home_url( '/#organization' );
	$site_id = home_url( '/#website' );

	/* Publisher. */
	$logo = '';
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $src ) {
			$logo = $src[0];
		}
	}
	$org_type = get_theme_mod( 'circlepress_org_type', 'Organization' );
	$org      = array(
		'@type' => $org_type,
		'@id'   => $org_id,
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);
	if ( 'Organization' === $org_type && $logo ) {
		$org['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo,
		);
	}
	$social = array_values(
		array_filter(
			array(
				get_theme_mod( 'circlepress_social_facebook', '' ),
				get_theme_mod( 'circlepress_social_instagram', '' ),
				get_theme_mod( 'circlepress_social_pinterest', '' ),
				get_theme_mod( 'circlepress_social_youtube', '' ),
				get_theme_mod( 'circlepress_social_x', '' ),
			)
		)
	);
	if ( $social ) {
		$org['sameAs'] = $social;
	}
	$graph[] = $org;

	/* WebSite + search. */
	$graph[] = array(
		'@type' => 'WebSite',
		'@id'   => $site_id,
		'url'   => home_url( '/' ),
		'name'  => get_bloginfo( 'name' ),
		'publisher' => array( '@id' => $org_id ),
		'inLanguage' => get_locale(),
		'potentialAction' => array(
			'@type'  => 'SearchAction',
			'target' => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	if ( is_singular( 'post' ) ) {
		$post_id = get_queried_object_id();
		$img     = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'large' ) : '';

		/* Base article. */
		$article = array(
			'@type'         => in_array( $niche, array( 'food', 'diy', 'gardening', 'crochet', 'nails' ), true ) ? 'BlogPosting' : 'Article',
			'@id'           => get_permalink( $post_id ) . '#article',
			'mainEntityOfPage' => get_permalink( $post_id ),
			'headline'      => get_the_title( $post_id ),
			'description'   => circlepress_meta_description(),
			'datePublished' => get_the_date( DATE_W3C, $post_id ),
			'dateModified'  => get_the_modified_date( DATE_W3C, $post_id ),
			'author'        => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
				'url'   => get_author_posts_url( get_post_field( 'post_author', $post_id ) ),
			),
			'publisher'     => array( '@id' => $org_id ),
			'inLanguage'    => get_locale(),
		);
		if ( $img ) {
			$article['image'] = $img;
		}
		$rating = get_post_meta( $post_id, '_cp_rating', true );
		if ( $rating ) {
			$article['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => (string) $rating,
				'bestRating'  => '5',
				'ratingCount' => (string) max( 1, (int) get_post_meta( $post_id, '_cp_rating_count', true ) ),
			);
		}
		$graph[] = $article;

		/* Niche entity: Recipe / HowTo / Product. */
		$recipe = circlepress_schema_recipe( $post_id );
		if ( $recipe ) {
			$graph[] = $recipe;
		} else {
			$howto = circlepress_schema_howto( $post_id );
			if ( $howto ) {
				$graph[] = $howto;
			}
			$product = circlepress_schema_product( $post_id );
			if ( $product ) {
				$graph[] = $product;
			}
		}

		/* FAQ. */
		$faq = circlepress_schema_faq( $post_id );
		if ( $faq ) {
			$graph[] = $faq;
		}
	}

	if ( is_front_page() ) {
		$home_faq = circlepress_parse_faq( get_theme_mod( 'circlepress_home_faq', '' ) );
		if ( $home_faq ) {
			$graph[] = array(
				'@type'      => 'FAQPage',
				'@id'        => home_url( '/#faq' ),
				'mainEntity' => array_map(
					function ( $f ) {
						return array(
							'@type'          => 'Question',
							'name'           => $f['q'],
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text'  => $f['a'],
							),
						);
					},
					$home_faq
				),
			);
		}
	}

	/* Breadcrumbs. */
	if ( get_theme_mod( 'circlepress_seo_crumbs', true ) ) {
		$items = circlepress_breadcrumb_items();
		if ( count( $items ) >= 2 ) {
			$elements = array();
			$pos      = 0;
			foreach ( $items as $item ) {
				++$pos;
				$el = array(
					'@type'    => 'ListItem',
					'position' => $pos,
					'name'     => $item[0],
				);
				if ( ! empty( $item[1] ) ) {
					$el['item'] = $item[1];
				}
				$elements[] = $el;
			}
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'@id'             => circlepress_canonical_url() . '#breadcrumbs',
				'itemListElement' => $elements,
			);
		}
	}

	if ( ! $graph ) {
		return;
	}
	echo '<script type="application/ld+json">' . wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	) . '</script>' . "\n";
}
add_action( 'wp_head', 'circlepress_schema_output', 20 );

/* ---------- Builders ---------- */

/**
 * Recipe schema (Food niche).
 *
 * @param int $post_id Post id.
 * @return array|false
 */
function circlepress_schema_recipe( $post_id ) {
	$ingredients  = circlepress_parse_lines( get_post_meta( $post_id, '_cp_recipe_ingredients', true ) );
	$instructions = circlepress_parse_lines( get_post_meta( $post_id, '_cp_recipe_instructions', true ) );
	if ( ! $ingredients || ! $instructions ) {
		return false;
	}
	$steps = array();
	$pos   = 0;
	foreach ( $instructions as $text ) {
		++$pos;
		$steps[] = array(
			'@type'    => 'HowToStep',
			'position' => $pos,
			'text'     => $text,
		);
	}
	$recipe = array(
		'@type'              => 'Recipe',
		'@id'                => get_permalink( $post_id ) . '#recipe',
		'name'               => get_post_meta( $post_id, '_cp_recipe_name', true ) ? get_post_meta( $post_id, '_cp_recipe_name', true ) : get_the_title( $post_id ),
		'description'        => circlepress_meta_description(),
		'recipeIngredient'   => $ingredients,
		'recipeInstructions' => $steps,
		'inLanguage'         => get_locale(),
	);
	$map    = array(
		'prepTime'           => '_cp_recipe_prep',
		'cookTime'           => '_cp_recipe_cook',
		'totalTime'          => '_cp_recipe_total',
		'recipeYield'        => '_cp_recipe_servings',
		'recipeCategory'     => '_cp_recipe_course',
		'recipeCuisine'      => '_cp_recipe_cuisine',
		'keywords'           => '_cp_recipe_keywords',
	);
	foreach ( $map as $schema_key => $meta_key ) {
		$val = get_post_meta( $post_id, $meta_key, true );
		if ( $val ) {
			if ( in_array( $schema_key, array( 'prepTime', 'cookTime', 'totalTime' ), true ) ) {
				$val = circlepress_to_iso8601_duration( $val );
			}
			$recipe[ $schema_key ] = $val;
		}
	}
	$calories = get_post_meta( $post_id, '_cp_recipe_calories', true );
	if ( $calories ) {
		$recipe['nutrition'] = array(
			'@type'    => 'NutritionInformation',
			'calories' => $calories . ' calories',
		);
	}
	if ( has_post_thumbnail( $post_id ) ) {
		$recipe['image'] = get_the_post_thumbnail_url( $post_id, 'large' );
	}
	$rating = get_post_meta( $post_id, '_cp_rating', true );
	if ( $rating ) {
		$recipe['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => (string) $rating,
			'bestRating'  => '5',
			'ratingCount' => (string) max( 1, (int) get_post_meta( $post_id, '_cp_rating_count', true ) ),
		);
	}
	return $recipe;
}

/**
 * HowTo schema (Crochet, DIY, Nails, Gardening, Beauty routines, Home Decor).
 *
 * @param int $post_id Post id.
 * @return array|false
 */
function circlepress_schema_howto( $post_id ) {
	$steps = circlepress_parse_lines( get_post_meta( $post_id, '_cp_howto_steps', true ) );
	if ( ! $steps ) {
		return false;
	}
	$items = array();
	$pos   = 0;
	foreach ( $steps as $text ) {
		++$pos;
		$items[] = array(
			'@type'    => 'HowToStep',
			'position' => $pos,
			'name'     => mb_substr( $text, 0, 80 ),
			'text'     => $text,
		);
	}
	$howto = array(
		'@type'              => 'HowTo',
		'@id'                => get_permalink( $post_id ) . '#howto',
		'name'               => get_post_meta( $post_id, '_cp_howto_name', true ) ? get_post_meta( $post_id, '_cp_howto_name', true ) : get_the_title( $post_id ),
		'description'        => circlepress_meta_description(),
		'step'               => $items,
		'inLanguage'         => get_locale(),
	);
	$time = get_post_meta( $post_id, '_cp_howto_time', true );
	if ( $time ) {
		$howto['totalTime'] = circlepress_to_iso8601_duration( $time );
	}
	$cost = get_post_meta( $post_id, '_cp_howto_cost', true );
	if ( $cost ) {
		$howto['estimatedCost'] = array(
			'@type'    => 'MonetaryAmount',
			'currency' => get_post_meta( $post_id, '_cp_product_currency', true ) ? get_post_meta( $post_id, '_cp_product_currency', true ) : 'USD',
			'value'    => $cost,
		);
	}
	foreach ( array( 'tool' => '_cp_howto_tools', 'supply' => '_cp_howto_materials' ) as $schema_key => $meta_key ) {
		$lines = circlepress_parse_lines( get_post_meta( $post_id, $meta_key, true ) );
		if ( $lines ) {
			$howto[ $schema_key ] = array_map(
				function ( $name ) {
					return array(
						'@type' => 'HowToSupply',
						'name'  => $name,
					);
				},
				$lines
			);
		}
	}
	if ( has_post_thumbnail( $post_id ) ) {
		$howto['image'] = get_the_post_thumbnail_url( $post_id, 'large' );
	}
	return $howto;
}

/**
 * Product (+Review when rating exists) schema — Furniture, Beauty, Pets, Home Decor.
 *
 * @param int $post_id Post id.
 * @return array|false
 */
function circlepress_schema_product( $post_id ) {
	$name  = get_post_meta( $post_id, '_cp_product_name', true );
	$price = get_post_meta( $post_id, '_cp_product_price', true );
	if ( ! $name && ! $price ) {
		return false;
	}
	$product = array(
		'@type' => 'Product',
		'@id'   => get_permalink( $post_id ) . '#product',
		'name'  => $name ? $name : get_the_title( $post_id ),
		'description' => circlepress_meta_description(),
	);
	if ( has_post_thumbnail( $post_id ) ) {
		$product['image'] = get_the_post_thumbnail_url( $post_id, 'large' );
	}
	$brand = get_post_meta( $post_id, '_cp_product_brand', true );
	if ( $brand ) {
		$product['brand'] = array(
			'@type' => 'Brand',
			'name'  => $brand,
		);
	}
	if ( $price ) {
		$availability = get_post_meta( $post_id, '_cp_product_availability', true );
		$product['offers'] = array(
			'@type'         => 'Offer',
			'price'         => $price,
			'priceCurrency' => get_post_meta( $post_id, '_cp_product_currency', true ) ? get_post_meta( $post_id, '_cp_product_currency', true ) : 'USD',
			'availability'  => $availability ? 'https://schema.org/' . $availability : 'https://schema.org/InStock',
			'url'           => get_permalink( $post_id ),
		);
	}
	$rating = get_post_meta( $post_id, '_cp_rating', true );
	if ( $rating ) {
		$product['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => (string) $rating,
			'bestRating'  => '5',
			'reviewCount' => (string) max( 1, (int) get_post_meta( $post_id, '_cp_rating_count', true ) ),
		);
		$verdict = get_post_meta( $post_id, '_cp_verdict', true );
		$product['review'] = array(
			'@type'        => 'Review',
			'author'       => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
			),
			'reviewRating' => array(
				'@type'       => 'Rating',
				'ratingValue' => (string) $rating,
				'bestRating'  => '5',
			),
			'reviewBody'   => $verdict ? $verdict : circlepress_meta_description(),
		);
	}
	return $product;
}

/**
 * FAQPage schema from post meta.
 *
 * @param int $post_id Post id.
 * @return array|false
 */
function circlepress_schema_faq( $post_id ) {
	$items = circlepress_parse_faq( get_post_meta( $post_id, '_cp_faq', true ) );
	if ( ! $items ) {
		return false;
	}
	return array(
		'@type'      => 'FAQPage',
		'@id'        => get_permalink( $post_id ) . '#faq',
		'mainEntity' => array_map(
			function ( $f ) {
				return array(
					'@type'          => 'Question',
					'name'           => $f['q'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $f['a'],
					),
				);
			},
			$items
		),
	);
}

/**
 * Convert human durations ("30 min", "1h 20m", "PT30M") to ISO 8601.
 *
 * @param string $raw Raw duration.
 * @return string
 */
function circlepress_to_iso8601_duration( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return '';
	}
	if ( 0 === stripos( $raw, 'PT' ) ) {
		return strtoupper( $raw );
	}
	$hours = 0;
	$mins  = 0;
	if ( preg_match( '/(\d+)\s*h/i', $raw, $m ) ) {
		$hours = (int) $m[1];
	}
	if ( preg_match( '/(\d+)\s*(m|min|mins|minutes)/i', $raw, $m ) ) {
		$mins = (int) $m[1];
	}
	if ( ! $hours && ! $mins && is_numeric( $raw ) ) {
		$mins = (int) $raw;
	}
	if ( ! $hours && ! $mins ) {
		return $raw;
	}
	$out = 'PT';
	if ( $hours ) {
		$out .= $hours . 'H';
	}
	if ( $mins ) {
		$out .= $mins . 'M';
	}
	return $out;
}
