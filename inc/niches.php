<?php
/**
 * The 9 niche presets: style, fonts, homepage layout, schema & features.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All niche configurations.
 *
 * @return array
 */
function circlepress_get_niches() {
	return array(
		'food'       => array(
			'label'       => __( 'Food & Recipes', 'circlepress' ),
			'description' => __( 'Warm tasty magazine with recipe cards, jump-to-recipe, print button and nutrition facts.', 'circlepress' ),
			'icon'        => '🍲',
			'colors'      => array(
				'primary'      => '#52604e',
				'primary_dark' => '#3f4b3c',
				'secondary'    => '#c87952',
				'accent'       => '#dfe8dc',
				'background'   => '#f7f5ef',
				'surface_2'    => '#eef2eb',
				'border'       => '#dce1d8',
			),
			'fonts'       => array( 'heading' => 'DM Serif Display', 'body' => 'Manrope' ),
			'hero_style'  => 'split',
			'sections'    => array( 'hero', 'categories', 'featured', 'recipe_of_day', 'trending', 'latest', 'newsletter', 'faq' ),
			'schema'      => array( 'Recipe', 'FAQPage', 'HowTo' ),
			'features'    => array( 'recipe_card', 'jump_button', 'print', 'nutrition', 'star_rating', 'video' ),
			'demo_cats'   => array( 'Dinner', 'Desserts', 'Healthy', 'Quick Meals', 'Baking' ),
			'hero_title'  => __( 'Good food starts with a little curiosity.', 'circlepress' ),
			 'hero_sub'    => __( 'Comforting recipes, practical kitchen notes, and fresh inspiration for everyday meals worth sharing.', 'circlepress' ),
		),
		'crochet'    => array(
			'label'       => __( 'Crochet', 'circlepress' ),
			'description' => __( 'Cozy handmade look with pattern cards: difficulty, hook size, yarn and finished size.', 'circlepress' ),
			'icon'        => '🧶',
			'colors'      => array(
				'primary'      => '#9b5de5',
				'primary_dark' => '#7d3fd1',
				'secondary'    => '#f15bb5',
				'accent'       => '#fee440',
				'background'   => '#fdf7ff',
				'surface_2'    => '#f7ecff',
				'border'       => '#e7d5fb',
			),
			'fonts'       => array( 'heading' => 'Fraunces', 'body' => 'Nunito' ),
			'hero_style'  => 'split',
			'sections'    => array( 'hero', 'categories', 'patterns', 'featured', 'trending', 'newsletter', 'latest', 'faq' ),
			'schema'      => array( 'HowTo', 'CreativeWork', 'FAQPage' ),
			'features'    => array( 'howto_card', 'difficulty_badge', 'materials_checklist', 'print', 'star_rating', 'etsy_cta' ),
			'demo_cats'   => array( 'Amigurumi', 'Blankets', 'Wearables', 'Free Patterns', 'Beginner' ),
			'hero_title'  => __( 'Free crochet patterns you will actually finish', 'circlepress' ),
			'hero_sub'    => __( 'Step-by-step patterns with photos, stitch counts and printable instructions.', 'circlepress' ),
		),
		'pets'       => array(
			'label'       => __( 'Pets', 'circlepress' ),
			'description' => __( 'Friendly playful design with pet profile boxes, care guides and product picks.', 'circlepress' ),
			'icon'        => '🐾',
			'colors'      => array(
				'primary'      => '#0ea5a0',
				'primary_dark' => '#0b7f7b',
				'secondary'    => '#ff9f1c',
				'accent'       => '#ffd166',
				'background'   => '#f7fffe',
				'surface_2'    => '#e9faf6',
				'border'       => '#cdeee7',
			),
			'fonts'       => array( 'heading' => 'Baloo 2', 'body' => 'Nunito' ),
			'hero_style'  => 'bold',
			'sections'    => array( 'hero', 'categories', 'featured', 'guides', 'shop_picks', 'latest', 'newsletter', 'faq' ),
			'schema'      => array( 'Article', 'FAQPage', 'HowTo', 'Product' ),
			'features'    => array( 'pet_profile', 'care_table', 'product_box', 'vet_disclaimer', 'faq' ),
			'demo_cats'   => array( 'Dogs', 'Cats', 'Training', 'Nutrition', 'Grooming' ),
			'hero_title'  => __( 'Happy pets start with great advice', 'circlepress' ),
			'hero_sub'    => __( 'Training tips, nutrition guides and vet-reviewed care routines for dogs and cats.', 'circlepress' ),
		),
		'nails'      => array(
			'label'       => __( 'Nails', 'circlepress' ),
			'description' => __( 'Glam pink design with tutorial steps, shade galleries and salon price tables.', 'circlepress' ),
			'icon'        => '💅',
			'colors'      => array(
				'primary'      => '#ff4d8d',
				'primary_dark' => '#d63270',
				'secondary'    => '#7b2ff7',
				'accent'       => '#ffc6dd',
				'background'   => '#fff7fa',
				'surface_2'    => '#ffeaf2',
				'border'       => '#f7c9dd',
			),
			'fonts'       => array( 'heading' => 'Marcellus', 'body' => 'Outfit' ),
			'hero_style'  => 'split',
			'sections'    => array( 'hero', 'categories', 'trending', 'featured', 'tutorials', 'latest', 'newsletter', 'faq' ),
			'schema'      => array( 'HowTo', 'ImageObject', 'FAQPage' ),
			'features'    => array( 'howto_card', 'shade_swatches', 'step_gallery', 'price_table', 'faq' ),
			'demo_cats'   => array( 'Acrylic', 'Gel', 'Nail Art', 'Care', 'Trends' ),
			'hero_title'  => __( 'Nail inspo & tutorials, fresh every week', 'circlepress' ),
			'hero_sub'    => __( 'From clean girl nails to bold 3D art — looks, shades and step-by-step guides.', 'circlepress' ),
		),
		'furniture'  => array(
			'label'       => __( 'Furniture', 'circlepress' ),
			'description' => __( 'Premium showroom style with product cards, dimensions tables and comparison charts.', 'circlepress' ),
			'icon'        => '🛋️',
			'colors'      => array(
				'primary'      => '#1b3a4b',
				'primary_dark' => '#122a36',
				'secondary'    => '#c9a227',
				'accent'       => '#e8dcc6',
				'background'   => '#faf8f4',
				'surface_2'    => '#f1ece1',
				'border'       => '#e2d8c6',
			),
			'fonts'       => array( 'heading' => 'Cormorant Garamond', 'body' => 'Inter' ),
			'hero_style'  => 'split',
			'sections'    => array( 'hero', 'categories', 'featured', 'comparison', 'shop_picks', 'trending', 'newsletter', 'latest' ),
			'schema'      => array( 'Product', 'Review', 'FAQPage', 'Article' ),
			'features'    => array( 'product_box', 'comparison_table', 'pros_cons', 'star_rating', 'dimensions_table', 'affiliate_cta' ),
			'demo_cats'   => array( 'Sofas', 'Bedroom', 'Office', 'Outdoor', 'Small Spaces' ),
			'hero_title'  => __( 'Furniture worth keeping for decades', 'circlepress' ),
			'hero_sub'    => __( 'Honest reviews, buying guides and space-saving picks for every room and budget.', 'circlepress' ),
		),
		'homedecor'  => array(
			'label'       => __( 'Home Decor', 'circlepress' ),
			'description' => __( 'Elegant editorial look with room palettes, makeovers and shop-the-look picks.', 'circlepress' ),
			'icon'        => '🏡',
			'colors'      => array(
				'primary'      => '#5c6b57',
				'primary_dark' => '#48553f',
				'secondary'    => '#d4a373',
				'accent'       => '#faedcd',
				'background'   => '#fefdf8',
				'surface_2'    => '#f6f1e4',
				'border'       => '#e7ddc6',
			),
			'fonts'       => array( 'heading' => 'Lora', 'body' => 'Jost' ),
			'hero_style'  => 'split',
			'sections'    => array( 'hero', 'categories', 'featured', 'makeovers', 'shop_picks', 'latest', 'newsletter', 'faq' ),
			'schema'      => array( 'Article', 'HowTo', 'Product', 'FAQPage' ),
			'features'    => array( 'howto_card', 'palette_swatches', 'product_box', 'pros_cons', 'before_after' ),
			'demo_cats'   => array( 'Living Room', 'Bedroom', 'Kitchen', 'Small Spaces', 'Seasonal' ),
			'hero_title'  => __( 'Rooms that feel like you', 'circlepress' ),
			'hero_sub'    => __( 'Decor ideas, paint colors and budget makeovers — room by room.', 'circlepress' ),
		),
		'diy'        => array(
			'label'       => __( 'DIY & Crafts', 'circlepress' ),
			'description' => __( 'Bold maker style with tools & materials checklists, cost/time badges and steps.', 'circlepress' ),
			'icon'        => '🛠️',
			'colors'      => array(
				'primary'      => '#f77f00',
				'primary_dark' => '#c96600',
				'secondary'    => '#003049',
				'accent'       => '#fcbf49',
				'background'   => '#fffcf7',
				'surface_2'    => '#fff3dd',
				'border'       => '#f0dfba',
			),
			'fonts'       => array( 'heading' => 'Archivo', 'body' => 'Inter' ),
			'hero_style'  => 'bold',
			'sections'    => array( 'hero', 'categories', 'featured', 'projects', 'trending', 'latest', 'newsletter', 'faq' ),
			'schema'      => array( 'HowTo', 'FAQPage', 'Article' ),
			'features'    => array( 'howto_card', 'tools_checklist', 'cost_badge', 'print', 'star_rating', 'video' ),
			'demo_cats'   => array( 'Woodworking', 'Upcycling', 'Paper Crafts', 'Kids Crafts', 'Weekend Projects' ),
			'hero_title'  => __( 'Make it yourself — weekend by weekend', 'circlepress' ),
			'hero_sub'    => __( 'Practical builds and crafts with tools lists, costs and honest difficulty ratings.', 'circlepress' ),
		),
		'beauty'     => array(
			'label'       => __( 'Beauty', 'circlepress' ),
			'description' => __( 'Chic feminine design with routine steps, review cards and ingredient highlights.', 'circlepress' ),
			'icon'        => '💄',
			'colors'      => array(
				'primary'      => '#b5179e',
				'primary_dark' => '#8f117d',
				'secondary'    => '#ff87ab',
				'accent'       => '#ffc8dd',
				'background'   => '#fff9fb',
				'surface_2'    => '#ffeff5',
				'border'       => '#f5c9dc',
			),
			'fonts'       => array( 'heading' => 'Playfair Display', 'body' => 'Plus Jakarta Sans' ),
			'hero_style'  => 'split',
			'sections'    => array( 'hero', 'categories', 'featured', 'routines', 'reviews', 'latest', 'newsletter', 'faq' ),
			'schema'      => array( 'Product', 'Review', 'HowTo', 'FAQPage' ),
			'features'    => array( 'product_box', 'pros_cons', 'star_rating', 'routine_steps', 'shade_swatches', 'affiliate_cta' ),
			'demo_cats'   => array( 'Skincare', 'Makeup', 'Hair', 'Fragrance', 'Reviews' ),
			'hero_title'  => __( 'Skincare & beauty, minus the guesswork', 'circlepress' ),
			'hero_sub'    => __( 'Routines for every skin type plus honest product reviews with swatches.', 'circlepress' ),
		),
		'gardening'  => array(
			'label'       => __( 'Gardening', 'circlepress' ),
			'description' => __( 'Fresh green design with plant cards: sun, water, season and hardiness zone.', 'circlepress' ),
			'icon'        => '🌱',
			'colors'      => array(
				'primary'      => '#2d6a4f',
				'primary_dark' => '#1f4d38',
				'secondary'    => '#95d5b2',
				'accent'       => '#f4a259',
				'background'   => '#f7fbf4',
				'surface_2'    => '#e9f4e6',
				'border'       => '#cfe6cf',
			),
			'fonts'       => array( 'heading' => 'Bitter', 'body' => 'Work Sans' ),
			'hero_style'  => 'split',
			'sections'    => array( 'hero', 'categories', 'featured', 'planting', 'seasonal', 'latest', 'newsletter', 'faq' ),
			'schema'      => array( 'HowTo', 'Article', 'FAQPage' ),
			'features'    => array( 'howto_card', 'plant_card', 'season_badges', 'print', 'star_rating' ),
			'demo_cats'   => array( 'Vegetables', 'Flowers', 'Indoor Plants', 'Herbs', 'Beginners' ),
			'hero_title'  => __( 'Grow more, guess less', 'circlepress' ),
			'hero_sub'    => __( 'Planting calendars, soil guides and beginner-proof growing plans.', 'circlepress' ),
		),
	);
}

/**
 * Get one niche config.
 *
 * @param string $id Niche id.
 * @return array
 */
function circlepress_get_niche( $id ) {
	$niches = circlepress_get_niches();
	if ( isset( $niches[ $id ] ) ) {
		return $niches[ $id ];
	}
	return $niches['food'];
}

/**
 * Current active niche id.
 * Priority: per-page override > Customizer setting > default.
 *
 * @return string
 */
function circlepress_get_current_niche() {
	/* Per-page override via page template slug or post meta. */
	if ( is_page() || is_singular() ) {
		$post_id = get_queried_object_id();
		if ( $post_id ) {
			$override = get_post_meta( $post_id, '_cp_niche_override', true );
			if ( $override && array_key_exists( $override, circlepress_get_niches() ) ) {
				return $override;
			}
			$template = get_page_template_slug( $post_id );
			if ( $template && preg_match( '/template-home-([a-z]+)\.php$/', $template, $m ) ) {
				if ( array_key_exists( $m[1], circlepress_get_niches() ) ) {
					return $m[1];
				}
			}
		}
	}
	$niche = get_theme_mod( 'circlepress_niche', 'food' );
	if ( ! array_key_exists( (string) $niche, circlepress_get_niches() ) ) {
		$niche = 'food';
	}
	return apply_filters( 'circlepress_current_niche', $niche );
}

/**
 * Niche choices for the Customizer select.
 *
 * @return array id => label
 */
function circlepress_niche_choices() {
	$out = array();
	foreach ( circlepress_get_niches() as $id => $n ) {
		$out[ $id ] = $n['icon'] . ' ' . $n['label'];
	}
	return $out;
}

/**
 * Whether the active niche has a given feature.
 *
 * @param string $feature Feature slug.
 * @return bool
 */
function circlepress_niche_has( $feature ) {
	$niche = circlepress_get_niche( circlepress_get_current_niche() );
	return in_array( $feature, isset( $niche['features'] ) ? $niche['features'] : array(), true );
}
