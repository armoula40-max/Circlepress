<?php
/**
 * Engagement kit: action bar, video box, visitor ratings, reviewer/sources,
 * bookmarks, text-to-speech, dark mode, font size, Pinterest Pin buttons.
 * (#6, #7, #8, #11, #12, #13, #14)
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ================= Customizer ================= */
function circlepress_engage_customize( $wp_customize ) {
	$wp_customize->add_section(
		'circlepress_engage',
		array(
			'title'    => __( '⚡ Engagement & Reading', 'circlepress' ),
			'priority' => 67,
		)
	);
	foreach ( array(
		'circlepress_actionbar'        => __( 'Sticky post action bar (Jump/Listen/Save/Print/Pin)', 'circlepress' ),
		'circlepress_nextpost'         => __( 'Auto-load next post (infinite reading)', 'circlepress' ),
		'circlepress_visitor_ratings'  => __( 'Visitor star ratings', 'circlepress' ),
		'circlepress_bookmarks'        => __( 'Save/bookmark posts (no login)', 'circlepress' ),
		'circlepress_tts'              => __( '"Listen to article" button (text-to-speech)', 'circlepress' ),
		'circlepress_darkmode'         => __( 'Dark mode toggle', 'circlepress' ),
		'circlepress_fontsize'         => __( 'Font size buttons (A+/A-)', 'circlepress' ),
		'circlepress_pinit'            => __( 'Pinterest "Pin it" on content images', 'circlepress' ),
	) as $key => $label ) {
		$wp_customize->add_setting( $key, array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
		$wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'circlepress_engage', 'type' => 'checkbox' ) );
	}
	$wp_customize->add_setting( 'circlepress_nextpost_count', array( 'default' => 2, 'sanitize_callback' => 'circlepress_sanitize_absint' ) );
	$wp_customize->add_control(
		'circlepress_nextpost_count',
		array(
			'label'       => __( 'Next posts to auto-load (1-5)', 'circlepress' ),
			'section'     => 'circlepress_engage',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 1, 'max' => 5 ),
		)
	);
}
add_action( 'customize_register', 'circlepress_engage_customize' );

function circlepress_sanitize_absint( $v ) {
	return absint( $v );
}

/* ================= Assets ================= */
function circlepress_extra_assets() {
	wp_enqueue_script( 'circlepress-extra', CIRCLEPRESS_URI . '/assets/js/extra.js', array(), CIRCLEPRESS_VERSION, true );
	wp_localize_script(
		'circlepress-extra',
		'circlepressExtra',
		array(
			'ajax'  => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'circlepress_extra' ),
			'i18n'  => array(
				'copied'    => __( 'Copied!', 'circlepress' ),
				'saved'     => __( 'Saved', 'circlepress' ),
				'save'      => __( 'Save', 'circlepress' ),
				'empty'     => __( 'No saved posts yet. Tap "Save" on any post!', 'circlepress' ),
				'expired'   => __( 'Expired', 'circlepress' ),
				'endsIn'    => __( 'Ends in', 'circlepress' ),
				'stop'      => __( 'Stop', 'circlepress' ),
				'listen'    => __( 'Listen', 'circlepress' ),
				'thanks'    => __( 'Thanks for rating!', 'circlepress' ),
				'copyIng'   => __( 'Copy ingredients', 'circlepress' ),
				'servings'  => __( 'Servings', 'circlepress' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'circlepress_extra_assets' );

/* Anti-FOUC: apply saved theme + font size before CSS paints. */
function circlepress_display_head() {
	if ( ! get_theme_mod( 'circlepress_darkmode', true ) && ! get_theme_mod( 'circlepress_fontsize', true ) ) {
		return;
	}
	echo "<script>try{var t=localStorage.getItem('cp_theme');if(t==='dark'){document.documentElement.setAttribute('data-theme','dark');}var f=parseFloat(localStorage.getItem('cp_font')||'1');if(f&&f!==1){document.documentElement.style.fontSize=(16*f)+'px';}}catch(e){}</script>\n";
}
add_action( 'wp_head', 'circlepress_display_head', 0 );

/* Floating display dock: dark mode + font size. */
function circlepress_display_dock() {
	$dark = get_theme_mod( 'circlepress_darkmode', true );
	$fs   = get_theme_mod( 'circlepress_fontsize', true );
	if ( ! $dark && ! $fs ) {
		return;
	}
	echo '<div class="cp-dock" role="toolbar" aria-label="' . esc_attr__( 'Display options', 'circlepress' ) . '">';
	if ( $dark ) {
		echo '<button type="button" data-cp-theme aria-label="' . esc_attr__( 'Toggle dark mode', 'circlepress' ) . '" title="' . esc_attr__( 'Dark mode', 'circlepress' ) . '">🌙</button>';
	}
	if ( $fs ) {
		echo '<button type="button" data-cp-font-inc aria-label="' . esc_attr__( 'Increase font size', 'circlepress' ) . '">A+</button>';
		echo '<button type="button" data-cp-font-dec aria-label="' . esc_attr__( 'Decrease font size', 'circlepress' ) . '">A−</button>';
	}
	echo '</div>';
}
add_action( 'wp_footer', 'circlepress_display_dock', 40 );

/* ================= #11 Action bar ================= */
function circlepress_action_bar() {
	if ( ! is_singular( 'post' ) || ! get_theme_mod( 'circlepress_actionbar', true ) ) {
		return;
	}
	$id         = get_the_ID();
	$has_recipe = (bool) circlepress_parse_lines( get_post_meta( $id, '_cp_recipe_ingredients', true ) );
	$has_howto  = (bool) circlepress_parse_lines( get_post_meta( $id, '_cp_howto_steps', true ) );
	$video      = get_post_meta( $id, '_cp_video_url', true );
	$pin        = 'https://pinterest.com/pin/create/button/?url=' . rawurlencode( get_permalink() ) . '&description=' . rawurlencode( get_the_title() );
	if ( has_post_thumbnail( $id ) ) {
		$pin .= '&media=' . rawurlencode( get_the_post_thumbnail_url( $id, 'large' ) );
	}
	echo '<div class="cp-actionbar" role="toolbar" aria-label="' . esc_attr__( 'Post actions', 'circlepress' ) . '">';
	if ( $has_recipe ) {
		echo '<a class="cp-btn cp-btn--sm" href="#cp-recipe">⬇ ' . esc_html__( 'Jump to Recipe', 'circlepress' ) . '</a>';
	}
	if ( $has_howto ) {
		echo '<a class="cp-btn cp-btn--sm cp-btn--secondary" href="#cp-howto">⬇ ' . esc_html__( 'Jump to How-To', 'circlepress' ) . '</a>';
	}
	if ( $video ) {
		echo '<a class="cp-btn cp-btn--sm cp-btn--outline" href="#cp-video">▶ ' . esc_html__( 'Video', 'circlepress' ) . '</a>';
	}
	if ( get_theme_mod( 'circlepress_tts', true ) ) {
		echo '<button type="button" class="cp-btn cp-btn--sm cp-btn--outline" data-cp-listen>🔊 ' . esc_html__( 'Listen', 'circlepress' ) . '</button>';
	}
	if ( get_theme_mod( 'circlepress_bookmarks', true ) ) {
		$img = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'medium' ) : '';
		echo '<button type="button" class="cp-btn cp-btn--sm cp-btn--outline" data-cp-save data-id="' . esc_attr( $id ) . '" data-title="' . esc_attr( get_the_title() ) . '" data-url="' . esc_url( get_permalink() ) . '" data-img="' . esc_url( $img ) . '">🤍 ' . esc_html__( 'Save', 'circlepress' ) . '</button>';
	}
	echo '<button type="button" class="cp-btn cp-btn--sm cp-btn--outline" onclick="window.print();return false;">🖨 ' . esc_html__( 'Print', 'circlepress' ) . '</button>';
	echo '<a class="cp-btn cp-btn--sm cp-btn--outline" href="' . esc_url( $pin ) . '" target="_blank" rel="noopener">📌 Pin</a>';
	echo '</div>';
}

/* ================= Auto-append: video + ratings + reviewer + sources ================= */
function circlepress_engagement_append( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$id  = get_the_ID();
	$out = '';

	/* Featured video (finally rendered from the meta box). */
	$video = get_post_meta( $id, '_cp_video_url', true );
	if ( $video ) {
		if ( 'strict' === circlepress_consent_mode() && ! circlepress_has_consent( 'marketing' ) ) {
			$out .= '<div class="cp-box" id="cp-video" style="text-align:center"><a class="cp-btn" href="' . esc_url( $video ) . '" target="_blank" rel="noopener">▶ ' . esc_html__( 'Watch the video', 'circlepress' ) . '</a></div>';
		} else {
			$embed = wp_oembed_get( $video, array( 'width' => 800 ) );
			$out  .= '<div class="cp-video-box" id="cp-video">' . ( $embed ? $embed : '<p style="text-align:center"><a class="cp-btn" href="' . esc_url( $video ) . '" target="_blank" rel="noopener">▶ ' . esc_html__( 'Watch the video', 'circlepress' ) . '</a></p>' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/* Visitor ratings. */
	if ( get_theme_mod( 'circlepress_visitor_ratings', true ) ) {
		$out .= circlepress_ratings_box( $id );
	}

	/* E-E-A-T: reviewed-by. */
	$rname = get_post_meta( $id, '_cp_reviewer_name', true );
	if ( $rname ) {
		$rrole = get_post_meta( $id, '_cp_reviewer_role', true );
		$rdate = get_post_meta( $id, '_cp_reviewed_date', true );
		$out  .= '<div class="cp-reviewer">✓ <strong>' . esc_html__( 'Reviewed by', 'circlepress' ) . ' ' . esc_html( $rname ) . '</strong>';
		if ( $rrole ) {
			$out .= ' · ' . esc_html( $rrole );
		}
		if ( $rdate ) {
			$out .= ' · ' . esc_html( $rdate );
		}
		$out .= '</div>';
	}

	/* E-E-A-T: sources. */
	$sources = circlepress_parse_lines( get_post_meta( $id, '_cp_sources', true ) );
	if ( $sources ) {
		$out .= '<div class="cp-sources"><h3>📚 ' . esc_html__( 'Sources', 'circlepress' ) . '</h3><ol>';
		foreach ( $sources as $line ) {
			if ( false !== strpos( $line, '|' ) ) {
				list( $t, $u ) = array_map( 'trim', explode( '|', $line, 2 ) );
				$out .= '<li><a href="' . esc_url( $u ) . '" target="_blank" rel="noopener">' . esc_html( $t ? $t : $u ) . '</a></li>';
			} else {
				$out .= '<li>' . esc_html( $line ) . '</li>';
			}
		}
		$out .= '</ol></div>';
	}

	return $content . $out;
}
add_filter( 'the_content', 'circlepress_engagement_append', 16 );

/* ================= #7 Visitor ratings ================= */
function circlepress_ratings_box( $id ) {
	$avg   = get_post_meta( $id, '_cp_rating', true );
	$votes = (int) get_post_meta( $id, '_cp_visitor_votes', true );
	$mine  = isset( $_COOKIE[ 'cp_rated_' . $id ] ) ? (int) $_COOKIE[ 'cp_rated_' . $id ] : 0; // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
	ob_start();
	?>
	<div class="cp-box cp-rate" id="cp-rate" data-id="<?php echo esc_attr( $id ); ?>">
		<h3 class="cp-box__title">⭐ <?php esc_html_e( 'Rate this post', 'circlepress' ); ?></h3>
		<div class="cp-rate__row">
			<span class="cp-rate__stars" role="radiogroup" aria-label="<?php esc_attr_e( 'Rate from 1 to 5 stars', 'circlepress' ); ?>">
				<?php
				for ( $i = 1; $i <= 5; $i++ ) {
					echo '<button type="button" data-rate="' . $i . '" aria-label="' . esc_attr( sprintf( __( '%s stars', 'circlepress' ), $i ) ) . '"' . ( $mine ? ' disabled' : '' ) . '>★</button>';
				}
				?>
			</span>
			<span class="cp-rate__info">
				<?php
				if ( '' !== $avg ) {
					echo circlepress_stars( $avg ) . ' <strong>' . esc_html( $avg ) . '/5</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					if ( $votes ) {
						echo ' <small>(' . esc_html( sprintf( _n( '%s vote', '%s votes', $votes, 'circlepress' ), number_format_i18n( $votes ) ) ) . ')</small>';
					}
				}
				?>
			</span>
		</div>
		<p class="cp-rate__msg"><?php echo $mine ? esc_html( sprintf( __( 'Thanks! You rated this %s/5.', 'circlepress' ), $mine ) ) : ''; ?></p>
	</div>
	<?php
	return ob_get_clean();
}

function circlepress_ajax_rate() {
	check_ajax_referer( 'circlepress_extra', 'nonce' );
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
	$r  = isset( $_POST['rating'] ) ? (int) $_POST['rating'] : 0;
	if ( ! $id || $r < 1 || $r > 5 || 'post' !== get_post_type( $id ) ) {
		wp_send_json_error( array( 'msg' => __( 'Invalid vote.', 'circlepress' ) ) );
	}
	if ( ! empty( $_COOKIE[ 'cp_rated_' . $id ] ) ) {
		wp_send_json_error( array( 'msg' => __( 'You already rated this.', 'circlepress' ) ) );
	}
	$sum   = (float) get_post_meta( $id, '_cp_visitor_sum', true );
	$votes = (int) get_post_meta( $id, '_cp_visitor_votes', true );
	$sum  += $r;
	++$votes;
	update_post_meta( $id, '_cp_visitor_sum', (string) $sum );
	update_post_meta( $id, '_cp_visitor_votes', (string) $votes );

	/* Preserve the author's rating once, then keep _cp_rating as the combined value
	 * so stars + schema pick it up automatically. */
	$author = get_post_meta( $id, '_cp_author_rating', true );
	if ( '' === $author ) {
		$author = get_post_meta( $id, '_cp_rating', true );
		if ( '' !== $author ) {
			update_post_meta( $id, '_cp_author_rating', $author );
		}
	}
	$vavg     = $sum / $votes;
	$combined = ( '' !== $author ) ? round( ( (float) $author + $vavg ) / 2, 1 ) : round( $vavg, 1 );
	update_post_meta( $id, '_cp_rating', (string) $combined );
	update_post_meta( $id, '_cp_rating_count', (string) $votes );

	setcookie( 'cp_rated_' . $id, (string) $r, time() + 180 * 86400, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), false );
	wp_send_json_success( array( 'avg' => $combined, 'votes' => $votes, 'rating' => $r ) );
}
add_action( 'wp_ajax_cp_rate', 'circlepress_ajax_rate' );
add_action( 'wp_ajax_nopriv_cp_rate', 'circlepress_ajax_rate' );

/* Keep combined rating correct when the author edits their rating. */
function circlepress_rate_save_recompute( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( empty( $_POST['circlepress_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['circlepress_meta_nonce'] ), 'circlepress_save_meta' ) ) {
		return;
	}
	$votes = (int) get_post_meta( $post_id, '_cp_visitor_votes', true );
	if ( $votes < 1 ) {
		delete_post_meta( $post_id, '_cp_author_rating' );
		return;
	}
	$posted = isset( $_POST['_cp_rating'] ) ? sanitize_text_field( wp_unslash( $_POST['_cp_rating'] ) ) : '';
	if ( '' !== $posted && is_numeric( $posted ) ) {
		update_post_meta( $post_id, '_cp_author_rating', (string) (float) $posted );
	} elseif ( '' === $posted ) {
		delete_post_meta( $post_id, '_cp_author_rating' );
	}
	$author = get_post_meta( $post_id, '_cp_author_rating', true );
	$sum    = (float) get_post_meta( $post_id, '_cp_visitor_sum', true );
	$vavg   = $sum / $votes;
	$combined = ( '' !== $author ) ? round( ( (float) $author + $vavg ) / 2, 1 ) : round( $vavg, 1 );
	update_post_meta( $post_id, '_cp_rating', (string) $combined );
	update_post_meta( $post_id, '_cp_rating_count', (string) $votes );
}
add_action( 'save_post', 'circlepress_rate_save_recompute', 20 );

/* ================= #6 Pinterest Pin-it ================= */
function circlepress_pinit_images( $content ) {
	if ( ! is_singular() || is_admin() || ! get_theme_mod( 'circlepress_pinit', true ) ) {
		return $content;
	}
	if ( false !== stripos( $content, 'cp-pin-wrap' ) ) {
		return $content;
	}
	$url  = rawurlencode( get_permalink() );
	$desc = rawurlencode( get_the_title() );
	return preg_replace_callback(
		'#<img([^>]*?)src=["\']([^"\']+)["\']([^>]*?)>#i',
		function ( $m ) use ( $url, $desc ) {
			$full = $m[0];
			if ( false !== stripos( $full, 'nopin' ) ) {
				return $full;
			}
			$pin = 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . rawurlencode( html_entity_decode( $m[2] ) ) . '&description=' . $desc;
			return '<span class="cp-pin-wrap">' . $full . '<a class="cp-pin-btn" href="' . esc_url( $pin ) . '" target="_blank" rel="noopener" aria-label="Pin it">📌 Pin</a></span>';
		},
		$content
	);
}
add_filter( 'the_content', 'circlepress_pinit_images', 11 );

/* ================= #12 Bookmarks shortcode ================= */
function circlepress_sc_saved( $atts ) {
	$atts = shortcode_atts( array( 'title' => __( 'My saved posts', 'circlepress' ) ), $atts, 'saved_posts' );
	return '<div class="cp-saved"><h2>❤️ ' . esc_html( $atts['title'] ) . '</h2><div class="cp-grid cp-grid--3" data-cp-saved-grid><p>' . esc_html__( 'Loading…', 'circlepress' ) . '</p></div></div>';
}
add_shortcode( 'saved_posts', 'circlepress_sc_saved' );
