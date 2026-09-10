<?php
/**
 * Shortcodes: recipe / howto / product / pros-cons / rating / faq / cta / compare / disclosure.
 * They read the post meta boxes by default, so authors fill data once.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Recipe card ---------- */
function circlepress_sc_recipe( $atts ) {
	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$ingredients  = circlepress_parse_lines( get_post_meta( $post_id, '_cp_recipe_ingredients', true ) );
	$instructions = circlepress_parse_lines( get_post_meta( $post_id, '_cp_recipe_instructions', true ) );
	if ( ! $ingredients ) {
		return '';
	}
	$name     = get_post_meta( $post_id, '_cp_recipe_name', true ) ? get_post_meta( $post_id, '_cp_recipe_name', true ) : get_the_title( $post_id );
	$prep     = get_post_meta( $post_id, '_cp_recipe_prep', true );
	$cook     = get_post_meta( $post_id, '_cp_recipe_cook', true );
	$total    = get_post_meta( $post_id, '_cp_recipe_total', true );
	$servings = get_post_meta( $post_id, '_cp_recipe_servings', true );
	$calories = get_post_meta( $post_id, '_cp_recipe_calories', true );
	$course   = get_post_meta( $post_id, '_cp_recipe_course', true );
	$cuisine  = get_post_meta( $post_id, '_cp_recipe_cuisine', true );
	$notes    = get_post_meta( $post_id, '_cp_recipe_notes', true );
	$rating   = get_post_meta( $post_id, '_cp_rating', true );
	$desc     = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : '';
	$img      = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'medium' ) : '';
	$pin      = 'https://pinterest.com/pin/create/button/?url=' . rawurlencode( get_permalink( $post_id ) ) . '&description=' . rawurlencode( $name );
	if ( $img ) {
		$pin .= '&media=' . rawurlencode( get_the_post_thumbnail_url( $post_id, 'large' ) );
	}

	ob_start();
	?>
	<div class="cp-box cp-recipe" id="cp-recipe">
		<div class="cp-recipe__head">
			<?php if ( $img ) : ?><img class="cp-recipe__img" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy"><?php endif; ?>
			<div class="cp-recipe__intro">
				<span class="cp-section__kicker"><?php esc_html_e( 'Recipe', 'circlepress' ); ?></span>
				<h2 class="cp-box__title"><?php echo esc_html( $name ); ?></h2>
				<?php if ( $rating ) : ?>
					<div class="cp-rating"><?php echo circlepress_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <strong><?php echo esc_html( $rating ); ?>/5</strong></div>
				<?php endif; ?>
				<?php if ( $desc ) : ?><p class="cp-recipe__desc"><?php echo esc_html( $desc ); ?></p><?php endif; ?>
				<div class="cp-recipe__actions">
					<?php if ( get_theme_mod( 'circlepress_bookmarks', true ) ) : ?>
						<button type="button" class="cp-btn cp-btn--sm cp-btn--outline" data-cp-save data-id="<?php echo esc_attr( $post_id ); ?>" data-title="<?php echo esc_attr( $name ); ?>" data-url="<?php echo esc_url( get_permalink( $post_id ) ); ?>" data-img="<?php echo esc_url( $img ); ?>"><?php echo circlepress_icon( 'bookmark', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <span><?php esc_html_e( 'Save', 'circlepress' ); ?></span></button>
					<?php endif; ?>
					<button type="button" class="cp-btn cp-btn--sm cp-btn--outline" onclick="window.print();return false;"><?php echo circlepress_icon( 'print', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <span><?php esc_html_e( 'Print', 'circlepress' ); ?></span></button>
					<a class="cp-btn cp-btn--sm cp-btn--outline" href="<?php echo esc_url( $pin ); ?>" target="_blank" rel="noopener"><?php echo circlepress_icon( 'pinterest', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <span>Pin</span></a>
				</div>
			</div>
		</div>
		<div class="cp-recipe__meta">
			<?php if ( $prep ) : ?><div><span><?php echo circlepress_icon( 'clock', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Prep Time', 'circlepress' ); ?></span><b><?php echo esc_html( $prep ); ?></b></div><?php endif; ?>
			<?php if ( $cook ) : ?><div><span><?php echo circlepress_icon( 'clock', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Cook Time', 'circlepress' ); ?></span><b><?php echo esc_html( $cook ); ?></b></div><?php endif; ?>
			<?php if ( $total ) : ?><div><span><?php echo circlepress_icon( 'clock', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Total Time', 'circlepress' ); ?></span><b><?php echo esc_html( $total ); ?></b></div><?php endif; ?>
			<?php if ( $servings ) : ?><div><span><?php echo circlepress_icon( 'user', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Servings', 'circlepress' ); ?></span><b><?php echo esc_html( $servings ); ?></b></div><?php endif; ?>
			<?php if ( $calories ) : ?><div><span><?php esc_html_e( 'Calories', 'circlepress' ); ?></span><b><?php echo esc_html( $calories ); ?> kcal</b></div><?php endif; ?>
			<?php if ( $course ) : ?><div><span><?php esc_html_e( 'Course', 'circlepress' ); ?></span><b><?php echo esc_html( $course ); ?></b></div><?php endif; ?>
			<?php if ( $cuisine ) : ?><div><span><?php esc_html_e( 'Cuisine', 'circlepress' ); ?></span><b><?php echo esc_html( $cuisine ); ?></b></div><?php endif; ?>
		</div>
		<div class="cp-recipe__cols">
			<div class="cp-recipe__ings">
				<h3><?php esc_html_e( 'Ingredients', 'circlepress' ); ?></h3>
				<ul class="cp-check">
					<?php foreach ( $ingredients as $ing ) : ?><li><?php echo esc_html( $ing ); ?></li><?php endforeach; ?>
				</ul>
			</div>
			<?php if ( $instructions ) : ?>
			<div class="cp-recipe__steps">
				<h3><?php esc_html_e( 'Instructions', 'circlepress' ); ?></h3>
				<ol class="cp-steps">
					<?php foreach ( $instructions as $step ) : ?><li><?php echo esc_html( $step ); ?></li><?php endforeach; ?>
				</ol>
			</div>
			<?php endif; ?>
		</div>
		<?php if ( $notes ) : ?>
			<div class="cp-recipe__notes"><strong><?php esc_html_e( 'Notes', 'circlepress' ); ?></strong><p><?php echo esc_html( $notes ); ?></p></div>
		<?php endif; ?>
		<p class="cp-recipe__tried"><?php esc_html_e( 'Tried this recipe? Rate it below and let us know how it turned out!', 'circlepress' ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'recipe_card', 'circlepress_sc_recipe' );
add_shortcode( 'cp_recipe', 'circlepress_sc_recipe' );

/* ---------- HowTo card ---------- */
function circlepress_sc_howto( $atts ) {
	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$steps = circlepress_parse_lines( get_post_meta( $post_id, '_cp_howto_steps', true ) );
	if ( ! $steps ) {
		return '';
	}
	$name       = get_post_meta( $post_id, '_cp_howto_name', true ) ? get_post_meta( $post_id, '_cp_howto_name', true ) : get_the_title( $post_id );
	$time       = get_post_meta( $post_id, '_cp_howto_time', true );
	$difficulty = get_post_meta( $post_id, '_cp_howto_difficulty', true );
	$cost       = get_post_meta( $post_id, '_cp_howto_cost', true );
	$tools      = circlepress_parse_lines( get_post_meta( $post_id, '_cp_howto_tools', true ) );
	$materials  = circlepress_parse_lines( get_post_meta( $post_id, '_cp_howto_materials', true ) );
	$rating     = get_post_meta( $post_id, '_cp_rating', true );

	ob_start();
	?>
	<div class="cp-box cp-howto" id="cp-howto">
		<h2 class="cp-box__title">🛠️ <?php echo esc_html( $name ); ?></h2>
		<div class="cp-howto__badges">
			<?php if ( $time ) : ?><span class="cp-badge">⏱ <?php esc_html_e( 'Time:', 'circlepress' ); ?> <b><?php echo esc_html( $time ); ?></b></span><?php endif; ?>
			<?php if ( $difficulty ) : ?><span class="cp-badge">📶 <?php esc_html_e( 'Difficulty:', 'circlepress' ); ?> <b><?php echo esc_html( $difficulty ); ?></b></span><?php endif; ?>
			<?php if ( $cost ) : ?><span class="cp-badge">💰 <?php esc_html_e( 'Cost:', 'circlepress' ); ?> <b><?php echo esc_html( $cost ); ?></b></span><?php endif; ?>
			<?php if ( $rating ) : ?><span class="cp-badge"><?php echo circlepress_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?>
		</div>
		<?php if ( $tools ) : ?>
			<h3><?php esc_html_e( 'Tools', 'circlepress' ); ?></h3>
			<ul class="cp-check"><?php foreach ( $tools as $t ) : ?><li><?php echo esc_html( $t ); ?></li><?php endforeach; ?></ul>
		<?php endif; ?>
		<?php if ( $materials ) : ?>
			<h3><?php esc_html_e( 'Materials', 'circlepress' ); ?></h3>
			<ul class="cp-check"><?php foreach ( $materials as $m ) : ?><li><?php echo esc_html( $m ); ?></li><?php endforeach; ?></ul>
		<?php endif; ?>
		<h3><?php esc_html_e( 'Steps', 'circlepress' ); ?></h3>
		<ol class="cp-steps"><?php foreach ( $steps as $s ) : ?><li><?php echo esc_html( $s ); ?></li><?php endforeach; ?></ol>
		<p><button class="cp-btn cp-btn--sm cp-btn--outline" onclick="window.print();return false;">🖨 <?php esc_html_e( 'Print instructions', 'circlepress' ); ?></button></p>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'howto', 'circlepress_sc_howto' );
add_shortcode( 'howto_card', 'circlepress_sc_howto' );
add_shortcode( 'cp_howto', 'circlepress_sc_howto' );

/* ---------- Product box ---------- */
function circlepress_sc_product( $atts ) {
	$post_id = get_the_ID();
	$atts    = shortcode_atts(
		array(
			'name'  => get_post_meta( $post_id, '_cp_product_name', true ),
			'price' => get_post_meta( $post_id, '_cp_product_price', true ),
			'old'   => '',
			'url'   => get_post_meta( $post_id, '_cp_product_url', true ),
			'button' => get_post_meta( $post_id, '_cp_product_cta', true ),
			'image' => '',
		),
		$atts,
		'product_box'
	);
	if ( ! $atts['name'] && ! $atts['price'] ) {
		return '';
	}
	$rating = get_post_meta( $post_id, '_cp_rating', true );
	$button = $atts['button'] ? $atts['button'] : __( 'Check Price', 'circlepress' );
	$img    = $atts['image'] ? $atts['image'] : ( has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'medium' ) : '' );

	ob_start();
	?>
	<div class="cp-box cp-product">
		<?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $atts['name'] ); ?>" loading="lazy"><?php endif; ?>
		<div>
			<h3 class="cp-box__title" style="margin-bottom:4px"><?php echo esc_html( $atts['name'] ); ?></h3>
			<?php if ( $rating ) : ?><div class="cp-rating"><?php echo circlepress_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <strong><?php echo esc_html( $rating ); ?>/5</strong></div><?php endif; ?>
			<?php if ( $atts['price'] ) : ?>
				<div class="cp-product__price"><?php echo esc_html( $atts['price'] ); ?><?php echo $atts['old'] ? ' <s>' . esc_html( $atts['old'] ) . '</s>' : ''; ?></div>
			<?php endif; ?>
			<?php if ( $atts['url'] ) : ?>
				<a class="cp-btn" href="<?php echo esc_url( $atts['url'] ); ?>"<?php echo circlepress_affiliate_rel(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $button ); ?> →</a>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'product_box', 'circlepress_sc_product' );
add_shortcode( 'cp_product', 'circlepress_sc_product' );

/* ---------- Pros / cons ---------- */
function circlepress_sc_proscons( $atts ) {
	$post_id = get_the_ID();
	$atts    = shortcode_atts( array( 'pros' => '', 'cons' => '' ), $atts, 'pros_cons' );
	$pros    = $atts['pros'] ? explode( ';', $atts['pros'] ) : circlepress_parse_lines( get_post_meta( $post_id, '_cp_pros', true ) );
	$cons    = $atts['cons'] ? explode( ';', $atts['cons'] ) : circlepress_parse_lines( get_post_meta( $post_id, '_cp_cons', true ) );
	$pros    = array_filter( array_map( 'trim', $pros ) );
	$cons    = array_filter( array_map( 'trim', $cons ) );
	if ( ! $pros && ! $cons ) {
		return '';
	}
	$out = '<div class="cp-proscons"><div class="cp-pros"><h4>✅ ' . esc_html__( 'Pros', 'circlepress' ) . '</h4><ul>';
	foreach ( $pros as $p ) {
		$out .= '<li>' . esc_html( $p ) . '</li>';
	}
	$out .= '</ul></div><div class="cp-cons"><h4>❌ ' . esc_html__( 'Cons', 'circlepress' ) . '</h4><ul>';
	foreach ( $cons as $c ) {
		$out .= '<li>' . esc_html( $c ) . '</li>';
	}
	$out .= '</ul></div></div>';
	return $out;
}
add_shortcode( 'pros_cons', 'circlepress_sc_proscons' );

/* ---------- Star rating ---------- */
function circlepress_sc_rating( $atts ) {
	$atts = shortcode_atts( array( 'value' => get_post_meta( get_the_ID(), '_cp_rating', true ) ), $atts, 'star_rating' );
	if ( '' === $atts['value'] ) {
		return '';
	}
	return '<div class="cp-rating">' . circlepress_stars( $atts['value'] ) . ' <strong>' . esc_html( $atts['value'] ) . '/5</strong></div>';
}
add_shortcode( 'star_rating', 'circlepress_sc_rating' );

/* ---------- FAQ accordion ---------- */
function circlepress_sc_faq( $atts ) {
	$atts  = shortcode_atts( array( 'source' => 'post' ), $atts, 'faq' );
	$raw   = 'home' === $atts['source'] ? get_theme_mod( 'circlepress_home_faq', '' ) : get_post_meta( get_the_ID(), '_cp_faq', true );
	$items = circlepress_parse_faq( $raw );
	if ( ! $items ) {
		return '';
	}
	$out = '<div class="cp-faq">';
	foreach ( $items as $f ) {
		$out .= '<details><summary>' . esc_html( $f['q'] ) . '</summary><div>' . esc_html( $f['a'] ) . '</div></details>';
	}
	$out .= '</div>';
	return $out;
}
add_shortcode( 'faq', 'circlepress_sc_faq' );
add_shortcode( 'cp_faq', 'circlepress_sc_faq' );

/* ---------- CTA button ---------- */
function circlepress_sc_cta( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'text'  => __( 'Click here', 'circlepress' ),
			'url'   => '#',
			'style' => 'primary',
		),
		$atts,
		'cta_button'
	);
	$class = 'cp-btn';
	if ( 'secondary' === $atts['style'] ) {
		$class .= ' cp-btn--secondary';
	} elseif ( 'outline' === $atts['style'] ) {
		$class .= ' cp-btn--outline';
	}
	return '<p><a class="' . esc_attr( $class ) . '" href="' . esc_url( $atts['url'] ) . '">' . esc_html( $atts['text'] ) . '</a></p>' . do_shortcode( (string) $content );
}
add_shortcode( 'cta_button', 'circlepress_sc_cta' );
add_shortcode( 'cp_cta', 'circlepress_sc_cta' );

/* ---------- Affiliate disclosure ---------- */
function circlepress_sc_disclosure() {
	$text = get_theme_mod( 'circlepress_affiliate_text', '' );
	if ( ! $text ) {
		return '';
	}
	return '<div class="cp-disclosure">ℹ️ ' . esc_html( $text ) . '</div>';
}
add_shortcode( 'disclosure', 'circlepress_sc_disclosure' );
add_shortcode( 'affiliate_disclosure', 'circlepress_sc_disclosure' );

/* ---------- Simple comparison table ----------
 * Usage: [compare cols="Feature|A|B" rows="Price|low|high; Quality|★★★★|★★★"] */
function circlepress_sc_compare( $atts ) {
	$atts = shortcode_atts( array( 'cols' => '', 'rows' => '' ), $atts, 'compare' );
	if ( ! $atts['cols'] || ! $atts['rows'] ) {
		return '';
	}
	$out = '<table class="cp-compare"><thead><tr>';
	foreach ( explode( '|', $atts['cols'] ) as $c ) {
		$out .= '<th>' . esc_html( trim( $c ) ) . '</th>';
	}
	$out .= '</tr></thead><tbody>';
	foreach ( explode( ';', $atts['rows'] ) as $row ) {
		$out .= '<tr>';
		foreach ( explode( '|', $row ) as $cell ) {
			$out .= '<td>' . esc_html( trim( $cell ) ) . '</td>';
		}
		$out .= '</tr>';
	}
	$out .= '</tbody></table>';
	return $out;
}
add_shortcode( 'compare', 'circlepress_sc_compare' );

/* ---------- Auto niche box (recipe/howto/product/faq) ---------- */
function circlepress_niche_box_html() {
	$post_id = get_the_ID();
	if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
		return '';
	}
	$out = '';
	$out .= circlepress_sc_recipe( array() );
	$out .= circlepress_sc_howto( array() );
	$out .= circlepress_sc_product( array() );
	$out .= circlepress_sc_proscons( array() );
	$verdict = get_post_meta( $post_id, '_cp_verdict', true );
	if ( $verdict ) {
		$out .= '<div class="cp-box"><h3 class="cp-box__title">⭐ ' . esc_html__( 'Our verdict', 'circlepress' ) . '</h3><p>' . esc_html( $verdict ) . '</p></div>';
	}
	$has_product = get_post_meta( $post_id, '_cp_product_url', true ) || get_post_meta( $post_id, '_cp_product_name', true );
	$sponsored   = get_post_meta( $post_id, '_cp_sponsored', true );
	if ( ( $has_product || $sponsored ) && get_theme_mod( 'circlepress_affiliate_disclosure', true ) ) {
		$out = circlepress_sc_disclosure() . $out;
	}
	$faq = circlepress_sc_faq( array() );
	if ( $faq ) {
		$out .= '<h2>' . esc_html__( 'Frequently asked questions', 'circlepress' ) . '</h2>' . $faq;
	}
	return $out;
}

function circlepress_auto_niche_box( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$pos = get_theme_mod( 'circlepress_niche_box_position', 'after' );
	if ( 'shortcode' === $pos ) {
		return $content;
	}
	$box = circlepress_niche_box_html();
	if ( '' === $box ) {
		return $content;
	}
	return 'before' === $pos ? $box . $content : $content . $box;
}
add_filter( 'the_content', 'circlepress_auto_niche_box', 15 );
