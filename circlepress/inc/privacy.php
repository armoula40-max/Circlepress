<?php
/**
 * GDPR / Cookie consent: lightweight banner, strict ad & embed blocking,
 * click-to-load video facades, Google Consent Mode v2, settings shortcode.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ================= Helpers ================= */

/**
 * Consent mode: disabled | inform (banner only) | strict (block ads+embeds until consent).
 *
 * @return string
 */
function circlepress_consent_mode() {
	if ( ! get_theme_mod( 'circlepress_consent_enabled', true ) ) {
		return 'disabled';
	}
	$mode = get_theme_mod( 'circlepress_consent_mode', 'strict' );
	return in_array( $mode, array( 'strict', 'inform' ), true ) ? $mode : 'strict';
}

/**
 * Parse the visitor's stored consent.
 *
 * @return array|false [analytics=>bool, marketing=>bool] or false when not chosen yet.
 */
function circlepress_user_consent() {
	if ( empty( $_COOKIE['cp_consent'] ) ) {
		return false;
	}
	$data = json_decode( wp_unslash( $_COOKIE['cp_consent'] ), true ); // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
	if ( ! is_array( $data ) || ! isset( $data['marketing'] ) ) {
		return false;
	}
	return array(
		'analytics' => ! empty( $data['analytics'] ),
		'marketing' => ! empty( $data['marketing'] ),
	);
}

/**
 * Whether a category is allowed (gating only applies in strict mode).
 *
 * @param string $cat analytics|marketing.
 * @return bool
 */
function circlepress_has_consent( $cat = 'marketing' ) {
	if ( 'strict' !== circlepress_consent_mode() ) {
		return true;
	}
	$c = circlepress_user_consent();
	if ( ! $c ) {
		return false;
	}
	return ! empty( $c[ $cat ] );
}

/* ================= Customizer ================= */
function circlepress_privacy_customize( $wp_customize ) {
	$wp_customize->add_section(
		'circlepress_privacy',
		array(
			'title'       => __( '🍪 Privacy & Consent (GDPR)', 'circlepress' ),
			'priority'    => 66,
			'description' => __( 'Cookie banner for GDPR + AdSense compliance. Strict mode blocks ads and video embeds until the visitor accepts marketing cookies.', 'circlepress' ),
		)
	);
	$wp_customize->add_setting( 'circlepress_consent_enabled', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_consent_enabled', array( 'label' => __( 'Enable cookie banner', 'circlepress' ), 'section' => 'circlepress_privacy', 'type' => 'checkbox' ) );

	$wp_customize->add_setting( 'circlepress_consent_mode', array( 'default' => 'strict', 'sanitize_callback' => 'circlepress_sanitize_consent_mode' ) );
	$wp_customize->add_control(
		'circlepress_consent_mode',
		array(
			'label'   => __( 'Mode', 'circlepress' ),
			'section' => 'circlepress_privacy',
			'type'    => 'select',
			'choices' => array(
				'strict' => __( 'Strict (GDPR: block ads & videos until consent)', 'circlepress' ),
				'inform' => __( 'Inform only (banner, nothing blocked)', 'circlepress' ),
			),
		)
	);
	$wp_customize->add_setting( 'circlepress_consent_position', array( 'default' => 'bar', 'sanitize_callback' => 'circlepress_sanitize_consent_pos' ) );
	$wp_customize->add_control(
		'circlepress_consent_position',
		array(
			'label'   => __( 'Banner style', 'circlepress' ),
			'section' => 'circlepress_privacy',
			'type'    => 'select',
			'choices' => array(
				'bar'  => __( 'Bottom bar (full width)', 'circlepress' ),
				'card' => __( 'Floating card (corner)', 'circlepress' ),
			),
		)
	);
	$wp_customize->add_setting(
		'circlepress_consent_text',
		array(
			'default'           => __( 'We use cookies to improve your experience, analyze traffic and serve personalized ads. You can accept all cookies, reject, or customize your choices.', 'circlepress' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control( 'circlepress_consent_text', array( 'label' => __( 'Banner text', 'circlepress' ), 'section' => 'circlepress_privacy', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'circlepress_privacy_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		'circlepress_privacy_url',
		array(
			'label'       => __( 'Privacy policy URL', 'circlepress' ),
			'description' => __( 'Empty = yoursite.com/privacy-policy/', 'circlepress' ),
			'section'     => 'circlepress_privacy',
			'type'        => 'url',
		)
	);
	$wp_customize->add_setting( 'circlepress_consent_reject', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_consent_reject', array( 'label' => __( 'Show "Reject" button (required by GDPR)', 'circlepress' ), 'section' => 'circlepress_privacy', 'type' => 'checkbox' ) );
	$wp_customize->add_setting( 'circlepress_consent_mode_v2', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control(
		'circlepress_consent_mode_v2',
		array(
			'label'       => __( 'Google Consent Mode v2 (recommended for AdSense/Analytics)', 'circlepress' ),
			'description' => __( 'Sends consent signals to Google tags (gtag/GTM/AdSense).', 'circlepress' ),
			'section'     => 'circlepress_privacy',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'circlepress_privacy_customize' );

function circlepress_sanitize_consent_mode( $v ) {
	return in_array( $v, array( 'strict', 'inform' ), true ) ? $v : 'strict';
}
function circlepress_sanitize_consent_pos( $v ) {
	return in_array( $v, array( 'bar', 'card' ), true ) ? $v : 'bar';
}

/* ================= Assets ================= */
function circlepress_consent_assets() {
	if ( 'disabled' === circlepress_consent_mode() ) {
		return;
	}
	wp_enqueue_script( 'circlepress-consent', CIRCLEPRESS_URI . '/assets/js/consent.js', array(), CIRCLEPRESS_VERSION, true );
	wp_localize_script(
		'circlepress-consent',
		'circlepressConsent',
		array(
			'mode'       => circlepress_consent_mode(),
			'expiryDays' => 180,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'circlepress_consent_assets' );

/* ================= Google Consent Mode v2 (must run before any Google tag) ================= */
function circlepress_consent_mode_head() {
	if ( 'disabled' === circlepress_consent_mode() ) {
		return;
	}
	if ( ! get_theme_mod( 'circlepress_consent_mode_v2', true ) ) {
		return;
	}
	$mode = circlepress_consent_mode();
	$c    = circlepress_user_consent();
	if ( $c ) {
		$ad = $c['marketing'] ? 'granted' : 'denied';
		$an = $c['analytics'] ? 'granted' : 'denied';
	} elseif ( 'strict' === $mode ) {
		$ad = 'denied';
		$an = 'denied';
	} else {
		$ad = 'granted';
		$an = 'granted';
	}
	?>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('consent','default',{'ad_storage':'<?php echo esc_js( $ad ); ?>','ad_user_data':'<?php echo esc_js( $ad ); ?>','ad_personalization':'<?php echo esc_js( $ad ); ?>','analytics_storage':'<?php echo esc_js( $an ); ?>','wait_for_update':500});</script>
	<?php
}
add_action( 'wp_head', 'circlepress_consent_mode_head', 1 );

/* ================= Strict gating: ads ================= */
function circlepress_gate_ads_by_consent( $code, $slot ) {
	if ( '' === trim( (string) $code ) ) {
		return $code;
	}
	if ( ! circlepress_has_consent( 'marketing' ) ) {
		return '';
	}
	return $code;
}
add_filter( 'circlepress_ad_code', 'circlepress_gate_ads_by_consent', 5, 2 );

/* ================= Strict gating: video embeds → click-to-load facade ================= */
function circlepress_gate_embeds( $content ) {
	if ( is_admin() || 'strict' !== circlepress_consent_mode() || circlepress_has_consent( 'marketing' ) ) {
		return $content;
	}
	$content = preg_replace_callback(
		'#<iframe[^>]*?src=["\'](https?://(?:www\.)?(?:youtube\.com|youtube-nocookie\.com)/embed/([A-Za-z0-9_-]{6,})[^"\']*)["\'][^>]*></iframe>#i',
		'circlepress_video_facade',
		$content
	);
	$content = preg_replace_callback(
		'#<iframe[^>]*?src=["\'](https?://player\.vimeo\.com/video/(\d+)[^"\']*)["\'][^>]*></iframe>#i',
		'circlepress_video_facade',
		$content
	);
	return $content;
}
add_filter( 'the_content', 'circlepress_gate_embeds', 12 );
add_filter( 'widget_text', 'circlepress_gate_embeds', 12 );

function circlepress_video_facade( $m ) {
	$src      = $m[1];
	$id       = $m[2];
	$is_vimeo = false !== stripos( $src, 'vimeo' );
	$thumb    = $is_vimeo ? '' : 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg';
	$label    = __( 'Click to load video (loads external content)', 'circlepress' );
	$out      = '<div class="cp-video-facade" data-src="' . esc_url( $src ) . '">';
	if ( $thumb ) {
		$out .= '<img src="' . esc_url( $thumb ) . '" alt="" loading="lazy">';
	}
	$out .= '<button type="button" class="cp-video-facade__play" aria-label="' . esc_attr( $label ) . '">▶</button>';
	$out .= '<span class="cp-video-facade__note">' . esc_html( $label ) . '</span></div>';
	return $out;
}

/* ================= Banner HTML ================= */
function circlepress_consent_banner() {
	$mode = circlepress_consent_mode();
	if ( 'disabled' === $mode ) {
		return;
	}
	$pos     = get_theme_mod( 'circlepress_consent_position', 'bar' );
	$text    = get_theme_mod( 'circlepress_consent_text', '' );
	$privacy = get_theme_mod( 'circlepress_privacy_url', '' );
	if ( ! $privacy ) {
		$privacy = home_url( '/privacy-policy/' );
	}
	$has_choice = (bool) circlepress_user_consent();
	?>
	<div id="cp-consent" class="cp-consent cp-consent--<?php echo esc_attr( $pos ); ?>" role="dialog" aria-label="<?php esc_attr_e( 'Cookie consent', 'circlepress' ); ?>" data-mode="<?php echo esc_attr( $mode ); ?>"<?php echo $has_choice ? ' hidden' : ''; ?>>
		<div class="cp-consent__inner">
			<div class="cp-consent__text">
				<strong>🍪 <?php esc_html_e( 'We value your privacy', 'circlepress' ); ?></strong>
				<p><?php echo esc_html( $text ); ?> <a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Learn more', 'circlepress' ); ?></a></p>
			</div>
			<div class="cp-consent__btns">
				<button type="button" class="cp-btn cp-btn--sm" data-cp-accept><?php esc_html_e( 'Accept all', 'circlepress' ); ?></button>
				<?php if ( get_theme_mod( 'circlepress_consent_reject', true ) ) : ?>
					<button type="button" class="cp-btn cp-btn--sm cp-btn--outline" data-cp-reject><?php esc_html_e( 'Reject', 'circlepress' ); ?></button>
				<?php endif; ?>
				<button type="button" class="cp-btn cp-btn--sm cp-btn--outline" data-cp-custom><?php esc_html_e( 'Customize', 'circlepress' ); ?></button>
			</div>
			<div class="cp-consent__prefs" hidden>
				<label class="cp-switch"><input type="checkbox" checked disabled> <?php esc_html_e( 'Necessary (always on)', 'circlepress' ); ?></label>
				<label class="cp-switch"><input type="checkbox" data-cp-pref="analytics"> <?php esc_html_e( 'Analytics', 'circlepress' ); ?></label>
				<label class="cp-switch"><input type="checkbox" data-cp-pref="marketing"> <?php esc_html_e( 'Marketing / Ads', 'circlepress' ); ?></label>
				<button type="button" class="cp-btn cp-btn--sm" data-cp-save><?php esc_html_e( 'Save choices', 'circlepress' ); ?></button>
			</div>
		</div>
	</div>
	<button type="button" id="cp-consent-fab" class="cp-consent-fab" aria-label="<?php esc_attr_e( 'Cookie settings', 'circlepress' ); ?>" title="<?php esc_attr_e( 'Cookie settings', 'circlepress' ); ?>"<?php echo $has_choice ? '' : ' hidden'; ?>>🍪</button>
	<?php
}
/* Priority <20: banner HTML must exist before footer scripts execute (they bind immediately). */
add_action( 'wp_footer', 'circlepress_consent_banner', 15 );

/* ================= Shortcode: [cookie_settings] ================= */
function circlepress_sc_cookie_settings( $atts ) {
	$atts = shortcode_atts( array( 'text' => __( 'Cookie Settings', 'circlepress' ), ), $atts, 'cookie_settings' );
	return '<a href="#" class="cp-cookie-settings">' . esc_html( $atts['text'] ) . '</a>';
}
add_shortcode( 'cookie_settings', 'circlepress_sc_cookie_settings' );
