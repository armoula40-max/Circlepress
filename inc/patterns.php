<?php
/**
 * Gutenberg block patterns: ready homepage sections users can insert & edit.
 * Find them in the editor under the "CirclePress" category.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function circlepress_register_patterns() {
	if ( ! function_exists( 'register_block_pattern_category' ) || ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category(
		'circlepress',
		array( 'label' => __( 'CirclePress', 'circlepress' ) )
	);

	/* 1. Split hero. */
	register_block_pattern(
		'circlepress/hero-split',
		array(
			'title'       => __( 'CirclePress: Split Hero', 'circlepress' ),
			'categories'  => array( 'circlepress', 'featured' ),
			'description' => __( 'Two-column hero with headline, text and buttons.', 'circlepress' ),
			'content'     => '<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"}}}} -->'
				. '<div class="wp-block-columns are-vertically-aligned-center" style="padding-top:2rem;padding-bottom:2rem">'
				. '<!-- wp:column {"verticalAlignment":"center"} --><div class="wp-block-column is-vertically-aligned-center">'
				. '<!-- wp:heading {"level":1} --><h1>' . esc_html__( 'Delicious recipes, tested in a real kitchen', 'circlepress' ) . '</h1><!-- /wp:heading -->'
				. '<!-- wp:paragraph --><p>' . esc_html__( 'Weeknight dinners, cozy baking and 30-minute meals your family will ask for again.', 'circlepress' ) . '</p><!-- /wp:paragraph -->'
				. '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html__( 'Start exploring', 'circlepress' ) . '</a></div><!-- /wp:button -->'
				. '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link">' . esc_html__( 'All articles', 'circlepress' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->'
				. '</div><!-- /wp:column -->'
				. '<!-- wp:column {"verticalAlignment":"center"} --><div class="wp-block-column is-vertically-aligned-center">'
				. '<!-- wp:cover {"dimRatio":40,"overlayColor":"primary","minHeight":320} --><div class="wp-block-cover" style="min-height:320px"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-40 has-background-dim"></span><div class="wp-block-cover__inner-container">'
				. '<!-- wp:paragraph {"align":"center","fontSize":"large"} --><p class="has-text-align-center has-large-font-size">🍲</p><!-- /wp:paragraph -->'
				. '<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">' . esc_html__( 'Replace with your hero photo', 'circlepress' ) . '</p><!-- /wp:paragraph -->'
				. '</div></div><!-- /wp:cover -->'
				. '</div><!-- /wp:column --></div><!-- /wp:columns -->',
		)
	);

	/* 2. Three-column post teaser grid. */
	register_block_pattern(
		'circlepress/post-grid',
		array(
			'title'       => __( 'CirclePress: Post Teaser Grid', 'circlepress' ),
			'categories'  => array( 'circlepress', 'columns' ),
			'description' => __( 'Three teasers for featured posts or categories.', 'circlepress' ),
			'content'     => '<!-- wp:heading --><h2>🔥 ' . esc_html__( 'Trending now', 'circlepress' ) . '</h2><!-- /wp:heading -->'
				. '<!-- wp:columns -->'
				. '<div class="wp-block-columns">'
				. circlepress_pattern_teaser( '🍝', __( '30-Minute Dinners', 'circlepress' ) )
				. circlepress_pattern_teaser( '🧁', __( 'Cozy Baking', 'circlepress' ) )
				. circlepress_pattern_teaser( '🥗', __( 'Fresh & Healthy', 'circlepress' ) )
				. '</div><!-- /wp:columns -->',
		)
	);

	/* 3. Category pills. */
	register_block_pattern(
		'circlepress/category-pills',
		array(
			'title'       => __( 'CirclePress: Category Pills', 'circlepress' ),
			'categories'  => array( 'circlepress', 'buttons' ),
			'description' => __( 'Row of topic links styled as pills.', 'circlepress' ),
			'content'     => '<!-- wp:heading --><h2>' . esc_html__( 'Explore topics', 'circlepress' ) . '</h2><!-- /wp:heading -->'
				. '<!-- wp:buttons --><div class="wp-block-buttons">'
				. '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link">' . esc_html__( 'Dinner', 'circlepress' ) . '</a></div><!-- /wp:button -->'
				. '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link">' . esc_html__( 'Desserts', 'circlepress' ) . '</a></div><!-- /wp:button -->'
				. '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link">' . esc_html__( 'Healthy', 'circlepress' ) . '</a></div><!-- /wp:button -->'
				. '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link">' . esc_html__( 'Quick Meals', 'circlepress' ) . '</a></div><!-- /wp:button -->'
				. '</div><!-- /wp:buttons -->',
		)
	);

	/* 4. Newsletter CTA. */
	register_block_pattern(
		'circlepress/newsletter-cta',
		array(
			'title'       => __( 'CirclePress: Newsletter CTA', 'circlepress' ),
			'categories'  => array( 'circlepress', 'call-to-action' ),
			'description' => __( 'Call-to-action banner linking to your signup form.', 'circlepress' ),
			'content'     => '<!-- wp:cover {"dimRatio":70,"overlayColor":"dark","minHeight":260} --><div class="wp-block-cover" style="min-height:260px"><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container">'
				. '<!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">💌 ' . esc_html__( 'Get our best ideas weekly', 'circlepress' ) . '</h2><!-- /wp:heading -->'
				. '<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">' . esc_html__( 'One useful email per week — no spam, unsubscribe anytime.', 'circlepress' ) . '</p><!-- /wp:paragraph -->'
				. '<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons">'
				. '<!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html__( 'Subscribe', 'circlepress' ) . '</a></div><!-- /wp:button -->'
				. '</div><!-- /wp:buttons -->'
				. '</div></div><!-- /wp:cover -->',
		)
	);

	/* 5. FAQ Q&A. */
	register_block_pattern(
		'circlepress/faq-qa',
		array(
			'title'       => __( 'CirclePress: FAQ Section', 'circlepress' ),
			'categories'  => array( 'circlepress', 'text' ),
			'description' => __( 'Question & answer section (pair with the FAQ meta box for schema).', 'circlepress' ),
			'content'     => '<!-- wp:heading --><h2>❓ ' . esc_html__( 'Common questions', 'circlepress' ) . '</h2><!-- /wp:heading -->'
				. '<!-- wp:heading {"level":3} --><h3>' . esc_html__( 'How long does it take?', 'circlepress' ) . '</h3><!-- /wp:heading -->'
				. '<!-- wp:paragraph --><p>' . esc_html__( 'Most readers finish in under 30 minutes, including preparation.', 'circlepress' ) . '</p><!-- /wp:paragraph -->'
				. '<!-- wp:heading {"level":3} --><h3>' . esc_html__( 'Is it beginner friendly?', 'circlepress' ) . '</h3><!-- /wp:heading -->'
				. '<!-- wp:paragraph --><p>' . esc_html__( 'Yes! Every step is explained with photos and simple instructions.', 'circlepress' ) . '</p><!-- /wp:paragraph -->',
		)
	);

	/* 6. Feature rows. */
	register_block_pattern(
		'circlepress/feature-rows',
		array(
			'title'       => __( 'CirclePress: Feature Rows', 'circlepress' ),
			'categories'  => array( 'circlepress', 'featured' ),
			'description' => __( 'Alternating image/text rows for showcasing content.', 'circlepress' ),
			'content'     => '<!-- wp:columns {"verticalAlignment":"center"} --><div class="wp-block-columns are-vertically-aligned-center">'
				. '<!-- wp:column --><div class="wp-block-column">'
				. '<!-- wp:cover {"dimRatio":30,"overlayColor":"secondary","minHeight":240} --><div class="wp-block-cover" style="min-height:240px"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container">'
				. '<!-- wp:paragraph {"align":"center","fontSize":"large"} --><p class="has-text-align-center has-large-font-size">✨</p><!-- /wp:paragraph -->'
				. '</div></div><!-- /wp:cover --></div><!-- /wp:column -->'
				. '<!-- wp:column --><div class="wp-block-column">'
				. '<!-- wp:heading --><h2>' . esc_html__( 'Made for real life', 'circlepress' ) . '</h2><!-- /wp:heading -->'
				. '<!-- wp:paragraph --><p>' . esc_html__( 'Practical guides with honest difficulty ratings, costs and time estimates.', 'circlepress' ) . '</p><!-- /wp:paragraph -->'
				. '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link">' . esc_html__( 'Learn more', 'circlepress' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->'
				. '</div><!-- /wp:column --></div><!-- /wp:columns -->',
		)
	);
}
add_action( 'init', 'circlepress_register_patterns' );

/**
 * One teaser column for the post-grid pattern.
 *
 * @param string $emoji Emoji placeholder.
 * @param string $title Teaser title.
 * @return string
 */
function circlepress_pattern_teaser( $emoji, $title ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:cover {"dimRatio":20,"overlayColor":"light","minHeight":180} --><div class="wp-block-cover" style="min-height:180px"><span aria-hidden="true" class="wp-block-cover__background has-light-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container">'
		. '<!-- wp:paragraph {"align":"center","fontSize":"large"} --><p class="has-text-align-center has-large-font-size">' . $emoji . '</p><!-- /wp:paragraph -->'
		. '</div></div><!-- /wp:cover -->'
		. '<!-- wp:heading {"level":3} --><h3>' . esc_html( $title ) . '</h3><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . esc_html__( 'Short description of this topic or post.', 'circlepress' ) . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:column -->';
}
