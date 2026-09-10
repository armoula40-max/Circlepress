<?php
/**
 * Conversion & revenue kit: auto-load next post, listicles, shop-the-look,
 * coupon boxes, gift guides. (#1, #2, #3, #4, #5)
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ================= #1 Auto-load next post ================= */
function circlepress_nextpost_sentinel() {
	if ( ! is_singular( 'post' ) || ! get_theme_mod( 'circlepress_nextpost', true ) ) {
		return;
	}
	$next = get_next_post( true );
	if ( ! $next ) {
		$next = get_next_post();
	}
	if ( ! $next ) {
		return;
	}
	$count = max( 1, min( 5, (int) get_theme_mod( 'circlepress_nextpost_count', 2 ) ) );
	echo '<div id="cp-next-sentinel" data-id="' . esc_attr( get_the_ID() ) . '" data-remaining="' . esc_attr( $count ) . '" aria-hidden="true"></div>';
}
add_action( 'wp_footer', 'circlepress_nextpost_sentinel', 35 );

function circlepress_ajax_nextpost() {
	check_ajax_referer( 'circlepress_extra', 'nonce' );
	$id   = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
	$post = get_post( $id );
	if ( ! $post ) {
		wp_send_json_error();
	}
	$GLOBALS['post'] = $post;
	setup_postdata( $post );
	$next = get_next_post( true );
	if ( ! $next ) {
		$next = get_next_post();
	}
	wp_reset_postdata();
	if ( ! $next ) {
		wp_send_json_success( array( 'done' => true ) );
	}
	$GLOBALS['post'] = $next;
	setup_postdata( $next );
	$content  = apply_filters( 'the_content', $next->post_content );
	$content .= circlepress_niche_box_html(); /* Recipe/HowTo/Product/FAQ boxes. */
	$content  = circlepress_ajax_inject_ads( $content ); /* In-content ads (same slots). */
	ob_start();
	?>
	<hr class="cp-next-div">
	<p class="cp-next-label"><?php esc_html_e( 'Keep reading:', 'circlepress' ); ?> <a href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?></a></p>
	<article class="cp-single cp-next-appended">
		<h2 class="cp-single__title"><a href="<?php echo esc_url( get_permalink( $next ) ); ?>" style="color:var(--cp-text)"><?php echo esc_html( get_the_title( $next ) ); ?></a></h2>
		<div class="cp-single__meta"><span>📅 <?php echo esc_html( get_the_date( '', $next ) ); ?></span><span>⏱ <?php echo esc_html( circlepress_reading_time( $next->ID ) ); ?></span></div>
		<?php
		if ( has_post_thumbnail( $next ) ) {
			echo '<figure class="cp-single__thumb">' . get_the_post_thumbnail( $next, 'large' ) . '</figure>';
		}
		?>
		<div class="cp-entry entry-content"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	</article>
	<?php
	$html = ob_get_clean();
	wp_reset_postdata();
	wp_send_json_success(
		array(
			'html'  => $html,
			'id'    => $next->ID,
			'url'   => get_permalink( $next ),
			'title' => get_the_title( $next ),
		)
	);
}
add_action( 'wp_ajax_cp_nextpost', 'circlepress_ajax_nextpost' );
add_action( 'wp_ajax_nopriv_cp_nextpost', 'circlepress_ajax_nextpost' );

/**
 * In-content ad injection for AJAX-loaded posts (mirrors the main injector;
 * respects consent gating via the circlepress_ad_code filter).
 *
 * @param string $content Rendered content.
 * @return string
 */
function circlepress_ajax_inject_ads( $content ) {
	$code = circlepress_ad_code( 'in_content' );
	if ( '' === trim( (string) $code ) ) {
		return $content;
	}
	$targets = array();
	foreach ( explode( ',', (string) get_theme_mod( 'circlepress_ad_paragraphs', '2,5,8' ) ) as $n ) {
		$n = (int) trim( $n );
		if ( $n > 0 ) {
			$targets[] = $n;
		}
	}
	if ( ! $targets ) {
		return $content;
	}
	$parts = preg_split( '/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( ! $parts || count( $parts ) < 3 ) {
		return $content;
	}
	$para = 0;
	$out  = '';
	foreach ( $parts as $chunk ) {
		$out .= $chunk;
		if ( '</p>' === strtolower( trim( $chunk ) ) ) {
			++$para;
			if ( in_array( $para, $targets, true ) ) {
				$out .= '<div class="cp-ad cp-ad--in-content" role="complementary" aria-label="' . esc_attr__( 'Advertisement', 'circlepress' ) . '"><div class="cp-ad__label">' . esc_html__( 'Advertisement', 'circlepress' ) . '</div><div class="cp-ad__code">' . $code . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
	return $out;
}

/* ================= #2 Listicles: [top_list] + [rank_item] ================= */
function circlepress_rank_badges() {
	return array(
		'best'    => __( '🏆 Best Overall', 'circlepress' ),
		'budget'  => __( '💰 Best Budget', 'circlepress' ),
		'editors' => __( "⭐ Editor's Choice", 'circlepress' ),
		'premium' => __( '💎 Premium Pick', 'circlepress' ),
	);
}

function circlepress_sc_top_list( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'title' => '' ), $atts, 'top_list' );
	$GLOBALS['circlepress_rank_counter'] = 0;
	$out  = '<div class="cp-toplist">';
	if ( $atts['title'] ) {
		$out .= '<h2>' . esc_html( $atts['title'] ) . '</h2>';
	}
	$out .= '<ol class="cp-rank">' . do_shortcode( $content ) . '</ol></div>';
	return $out;
}
add_shortcode( 'top_list', 'circlepress_sc_top_list' );
add_shortcode( 'cp_toplist', 'circlepress_sc_top_list' );

function circlepress_sc_rank_item( $atts, $content = '' ) {
	$a = shortcode_atts(
		array(
			'title'  => '',
			'badge'  => '',
			'price'  => '',
			'old'    => '',
			'url'    => '',
			'button' => '',
			'image'  => '',
			'rating' => '',
		),
		$atts,
		'rank_item'
	);
	$n = isset( $GLOBALS['circlepress_rank_counter'] ) ? ++$GLOBALS['circlepress_rank_counter'] : ( $GLOBALS['circlepress_rank_counter'] = 1 );
	$badges = circlepress_rank_badges();
	$medal  = $n <= 3 ? ' cp-rank--top' . (int) $n : '';
	$out    = '<li class="cp-rank__item' . $medal . '">';
	$out   .= '<span class="cp-rank__num">' . (int) $n . '</span>';
	if ( $a['image'] ) {
		$out .= '<img class="cp-rank__img" src="' . esc_url( $a['image'] ) . '" alt="' . esc_attr( $a['title'] ) . '" loading="lazy">';
	}
	$out .= '<div class="cp-rank__body">';
	if ( $a['badge'] && isset( $badges[ $a['badge'] ] ) ) {
		$out .= '<span class="cp-rank__badge">' . esc_html( $badges[ $a['badge'] ] ) . '</span>';
	}
	$out .= '<h3>' . esc_html( $a['title'] ) . '</h3>';
	if ( '' !== $a['rating'] ) {
		$out .= '<div class="cp-rating">' . circlepress_stars( $a['rating'] ) . ' <strong>' . esc_html( $a['rating'] ) . '/5</strong></div>';
	}
	if ( trim( (string) $content ) ) {
		$out .= '<div class="cp-rank__desc">' . wp_kses_post( do_shortcode( $content ) ) . '</div>';
	}
	if ( $a['price'] ) {
		$out .= '<div class="cp-product__price">' . esc_html( $a['price'] ) . ( $a['old'] ? ' <s>' . esc_html( $a['old'] ) . '</s>' : '' ) . '</div>';
	}
	if ( $a['url'] ) {
		$btn  = $a['button'] ? $a['button'] : __( 'Check Price', 'circlepress' );
		$out .= '<a class="cp-btn cp-btn--sm" href="' . esc_url( $a['url'] ) . '"' . circlepress_affiliate_rel() . '>' . esc_html( $btn ) . ' →</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	$out .= '</div></li>';
	return $out;
}
add_shortcode( 'rank_item', 'circlepress_sc_rank_item' );
add_shortcode( 'cp_rank', 'circlepress_sc_rank_item' );

/* ================= #3 Shop the Look: [shop_look] + [hotspot] ================= */
function circlepress_sc_shop_look( $atts, $content = '' ) {
	$a = shortcode_atts( array( 'image' => '', 'alt' => '' ), $atts, 'shop_look' );
	if ( ! $a['image'] ) {
		return '';
	}
	return '<div class="cp-look"><img src="' . esc_url( $a['image'] ) . '" alt="' . esc_attr( $a['alt'] ) . '" loading="lazy">' . do_shortcode( $content ) . '</div>';
}
add_shortcode( 'shop_look', 'circlepress_sc_shop_look' );

function circlepress_sc_hotspot( $atts ) {
	$a = shortcode_atts( array( 'x' => '50', 'y' => '50', 'title' => '', 'price' => '', 'url' => '' ), $atts, 'hotspot' );
	$x = max( 0, min( 100, (float) $a['x'] ) );
	$y = max( 0, min( 100, (float) $a['y'] ) );
	$out  = '<span class="cp-look__dot" style="left:' . esc_attr( $x ) . '%;top:' . esc_attr( $y ) . '%" tabindex="0" role="button" aria-label="' . esc_attr( $a['title'] ) . '">';
	$out .= '<span class="cp-look__pulse"></span><span class="cp-look__tip"><strong>' . esc_html( $a['title'] ) . '</strong>';
	if ( $a['price'] ) {
		$out .= '<span class="cp-look__price">' . esc_html( $a['price'] ) . '</span>';
	}
	if ( $a['url'] ) {
		$out .= ' <a href="' . esc_url( $a['url'] ) . '"' . circlepress_affiliate_rel() . '>' . esc_html__( 'Shop →', 'circlepress' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	$out .= '</span></span>';
	return $out;
}
add_shortcode( 'hotspot', 'circlepress_sc_hotspot' );

/* ================= #4 Coupon box: [coupon] ================= */
function circlepress_sc_coupon( $atts ) {
	$a = shortcode_atts(
		array(
			'code'   => '',
			'title'  => '',
			'desc'   => '',
			'expiry' => '',
			'url'    => '',
			'button' => '',
		),
		$atts,
		'coupon'
	);
	if ( ! $a['code'] ) {
		return '';
	}
	$btn  = $a['button'] ? $a['button'] : __( 'Get Deal', 'circlepress' );
	$out  = '<div class="cp-coupon">';
	if ( $a['title'] ) {
		$out .= '<h3>🎟️ ' . esc_html( $a['title'] ) . '</h3>';
	}
	if ( $a['desc'] ) {
		$out .= '<p>' . esc_html( $a['desc'] ) . '</p>';
	}
	$out .= '<div class="cp-coupon__row"><button type="button" class="cp-coupon__code" data-cp-coupon="' . esc_attr( $a['code'] ) . '" title="' . esc_attr__( 'Click to copy', 'circlepress' ) . '">' . esc_html( $a['code'] ) . ' <small>⧉</small></button>';
	if ( $a['url'] ) {
		$out .= '<a class="cp-btn" href="' . esc_url( $a['url'] ) . '"' . circlepress_affiliate_rel() . '>' . esc_html( $btn ) . ' →</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	$out .= '</div>';
	if ( $a['expiry'] ) {
		$out .= '<div class="cp-coupon__expiry" data-expiry="' . esc_attr( $a['expiry'] ) . '"></div>';
	}
	$out .= '</div>';
	return $out;
}
add_shortcode( 'coupon', 'circlepress_sc_coupon' );
add_shortcode( 'cp_coupon', 'circlepress_sc_coupon' );

/* ================= #5 Gift guide: [gift_grid] + [gift_item] ================= */
function circlepress_sc_gift_grid( $atts, $content = '' ) {
	$GLOBALS['circlepress_gift_cats'] = array();
	$inner = do_shortcode( $content );
	$cats  = array_values( array_unique( (array) $GLOBALS['circlepress_gift_cats'] ) );
	$out   = '<div class="cp-gift">';
	if ( count( $cats ) > 1 ) {
		$out .= '<div class="cp-chips cp-gift__filters"><button type="button" class="cp-chip is-active" data-gift-filter="all">' . esc_html__( 'All', 'circlepress' ) . '</button>';
		foreach ( $cats as $c ) {
			$out .= '<button type="button" class="cp-chip" data-gift-filter="' . esc_attr( $c ) . '">' . esc_html( $c ) . '</button>';
		}
		$out .= '</div>';
	}
	$out .= '<div class="cp-grid cp-grid--3 cp-gift__grid">' . $inner . '</div></div>';
	return $out;
}
add_shortcode( 'gift_grid', 'circlepress_sc_gift_grid' );

function circlepress_sc_gift_item( $atts, $content = '' ) {
	$a = shortcode_atts(
		array(
			'title' => '',
			'price' => '',
			'old'   => '',
			'url'   => '',
			'image' => '',
			'cat'   => '',
			'badge' => '',
		),
		$atts,
		'gift_item'
	);
	if ( $a['cat'] ) {
		$GLOBALS['circlepress_gift_cats'][] = $a['cat'];
	}
	$badges = circlepress_rank_badges();
	$out    = '<div class="cp-card cp-gift__item" data-cat="' . esc_attr( $a['cat'] ) . '">';
	if ( $a['image'] ) {
		$out .= '<div class="cp-card__media"><img src="' . esc_url( $a['image'] ) . '" alt="' . esc_attr( $a['title'] ) . '" loading="lazy">';
		if ( $a['badge'] && isset( $badges[ $a['badge'] ] ) ) {
			$out .= '<span class="cp-card__cat">' . esc_html( $badges[ $a['badge'] ] ) . '</span>';
		}
		$out .= '</div>';
	}
	$out .= '<div class="cp-card__body"><h3>' . esc_html( $a['title'] ) . '</h3>';
	if ( trim( (string) $content ) ) {
		$out .= '<p class="cp-card__excerpt">' . esc_html( wp_trim_words( wp_strip_all_tags( do_shortcode( $content ) ), 18 ) ) . '</p>';
	}
	if ( $a['price'] ) {
		$out .= '<div class="cp-product__price" style="font-size:1.2rem">' . esc_html( $a['price'] ) . ( $a['old'] ? ' <s>' . esc_html( $a['old'] ) . '</s>' : '' ) . '</div>';
	}
	if ( $a['url'] ) {
		$out .= '<p><a class="cp-btn cp-btn--sm cp-btn--block" href="' . esc_url( $a['url'] ) . '"' . circlepress_affiliate_rel() . '>' . esc_html__( 'Shop Now', 'circlepress' ) . ' →</a></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	$out .= '</div></div>';
	return $out;
}
add_shortcode( 'gift_item', 'circlepress_sc_gift_item' );
