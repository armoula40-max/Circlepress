<?php
/**
 * Customizer: niche picker, colors, homepage, single, ads, SEO, performance.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function circlepress_customize_register( $wp_customize ) {

	/* ===== NICHE ===== */
	$wp_customize->add_section(
		'circlepress_niche',
		array(
			'title'    => __( '🌐 Site Niche', 'circlepress' ),
			'priority' => 1,
		)
	);
	$wp_customize->add_setting(
		'circlepress_niche',
		array(
			'default'           => 'food',
			'sanitize_callback' => 'circlepress_sanitize_niche',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'circlepress_niche',
		array(
			'label'       => __( 'Active niche (changes style, homepage & schema)', 'circlepress' ),
			'section'     => 'circlepress_niche',
			'type'        => 'select',
			'choices'     => circlepress_niche_choices(),
			'description' => __( 'Each niche has its own colors, fonts, homepage layout and Schema.org markup.', 'circlepress' ),
		)
	);

	/* ===== HEADER ===== */
	$wp_customize->add_section( 'circlepress_header', array( 'title' => __( 'Header', 'circlepress' ), 'priority' => 30 ) );
	$wp_customize->add_setting( 'circlepress_show_topbar', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_show_topbar', array( 'label' => __( 'Show top bar', 'circlepress' ), 'section' => 'circlepress_header', 'type' => 'checkbox' ) );
	$wp_customize->add_setting( 'circlepress_topbar_text', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'circlepress_topbar_text', array( 'label' => __( 'Top bar text (left)', 'circlepress' ), 'section' => 'circlepress_header', 'type' => 'text' ) );
	$wp_customize->add_setting( 'circlepress_header_cta_text', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'circlepress_header_cta_text', array( 'label' => __( 'Header button text (empty = hidden)', 'circlepress' ), 'section' => 'circlepress_header', 'type' => 'text' ) );
	$wp_customize->add_setting( 'circlepress_header_cta_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'circlepress_header_cta_url', array( 'label' => __( 'Header button URL', 'circlepress' ), 'section' => 'circlepress_header', 'type' => 'url' ) );
	$wp_customize->add_setting( 'circlepress_show_search', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_show_search', array( 'label' => __( 'Show search icon', 'circlepress' ), 'section' => 'circlepress_header', 'type' => 'checkbox' ) );

	/* ===== COLORS ===== */
	$wp_customize->add_section( 'circlepress_colors', array( 'title' => __( 'Colors (override niche)', 'circlepress' ), 'priority' => 40 ) );
	foreach ( array(
		'primary'      => __( 'Primary', 'circlepress' ),
		'primary_dark' => __( 'Primary dark', 'circlepress' ),
		'secondary'    => __( 'Secondary', 'circlepress' ),
		'accent'       => __( 'Accent', 'circlepress' ),
		'background'   => __( 'Background', 'circlepress' ),
		'surface_2'    => __( 'Surface 2 (boxes)', 'circlepress' ),
		'border'       => __( 'Border', 'circlepress' ),
	) as $key => $label ) {
		$wp_customize->add_setting( 'circlepress_color_' . $key, array( 'default' => '', 'sanitize_callback' => 'sanitize_hex_color' ) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'circlepress_color_' . $key, array( 'label' => $label, 'section' => 'circlepress_colors' ) )
		);
	}

	/* ===== TYPOGRAPHY / FONTS ===== */
	$wp_customize->add_section( 'circlepress_fonts', array( 'title' => __( 'Typography', 'circlepress' ), 'priority' => 45 ) );
	$wp_customize->add_setting( 'circlepress_google_fonts', array( 'default' => false, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control(
		'circlepress_google_fonts',
		array(
			'label'       => __( 'Load Google Fonts (niche pair)', 'circlepress' ),
			'description' => __( 'OFF = system fonts (fastest, best for small hosting). ON = the niche designer font pair.', 'circlepress' ),
			'section'     => 'circlepress_fonts',
			'type'        => 'checkbox',
		)
	);

	/* ===== HOMEPAGE ===== */
	$wp_customize->add_section( 'circlepress_home', array( 'title' => __( 'Homepage', 'circlepress' ), 'priority' => 50 ) );
	$wp_customize->add_setting( 'circlepress_hero_title', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'circlepress_hero_title', array( 'label' => __( 'Hero title (empty = niche default)', 'circlepress' ), 'section' => 'circlepress_home', 'type' => 'text' ) );
	$wp_customize->add_setting( 'circlepress_hero_sub', array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'circlepress_hero_sub', array( 'label' => __( 'Hero subtitle', 'circlepress' ), 'section' => 'circlepress_home', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'circlepress_hero_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'circlepress_hero_image', array( 'label' => __( 'Hero image (split style)', 'circlepress' ), 'section' => 'circlepress_home' ) ) );
	$wp_customize->add_setting( 'circlepress_home_layout', array( 'default' => 'magazine', 'sanitize_callback' => 'circlepress_sanitize_home_layout' ) );
	$wp_customize->add_control(
		'circlepress_home_layout',
		array(
			'label'       => __( 'Homepage layout shape', 'circlepress' ),
			'description' => __( 'Magazine = niche sections. Grid/List/Showcase = alternative shapes. Can be overridden per page (Page Options).', 'circlepress' ),
			'section'     => 'circlepress_home',
			'type'        => 'select',
			'choices'     => circlepress_home_layouts(),
		)
	);
	foreach ( array(
		'hero'       => __( 'Show hero', 'circlepress' ),
		'categories' => __( 'Show category chips', 'circlepress' ),
		'featured'   => __( 'Show featured posts', 'circlepress' ),
		'trending'   => __( 'Show trending (by comments)', 'circlepress' ),
		'latest'     => __( 'Show latest posts', 'circlepress' ),
		'newsletter' => __( 'Show newsletter box', 'circlepress' ),
		'faq'        => __( 'Show homepage FAQ', 'circlepress' ),
	) as $sec => $label ) {
		$wp_customize->add_setting( 'circlepress_home_' . $sec, array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
		$wp_customize->add_control( 'circlepress_home_' . $sec, array( 'label' => $label, 'section' => 'circlepress_home', 'type' => 'checkbox' ) );
	}
	$wp_customize->add_setting( 'circlepress_featured_tag', array( 'default' => 'featured', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'circlepress_featured_tag',
		array(
			'label'       => __( 'Featured tag slug', 'circlepress' ),
			'description' => __( 'Posts tagged with this slug appear in hero + featured sections.', 'circlepress' ),
			'section'     => 'circlepress_home',
			'type'        => 'text',
		)
	);
	$wp_customize->add_setting( 'circlepress_home_sidebar', array( 'default' => 'no-sidebar', 'sanitize_callback' => 'circlepress_sanitize_sidebar' ) );
	$wp_customize->add_control( 'circlepress_home_sidebar', array( 'label' => __( 'Homepage sidebar', 'circlepress' ), 'section' => 'circlepress_home', 'type' => 'select', 'choices' => circlepress_sidebar_choices() ) );
	$wp_customize->add_setting( 'circlepress_home_faq', array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control(
		'circlepress_home_faq',
		array(
			'label'       => __( 'Homepage FAQ (one per line: Question | Answer)', 'circlepress' ),
			'section'     => 'circlepress_home',
			'type'        => 'textarea',
		)
	);

	/* ===== SINGLE POST ===== */
	$wp_customize->add_section( 'circlepress_single', array( 'title' => __( 'Single Post', 'circlepress' ), 'priority' => 55 ) );
	foreach ( array(
		'show_breadcrumbs' => __( 'Show breadcrumbs', 'circlepress' ),
		'show_toc'         => __( 'Auto table of contents (from H2/H3)', 'circlepress' ),
		'show_share'       => __( 'Show share buttons', 'circlepress' ),
		'show_author'      => __( 'Show author box', 'circlepress' ),
		'show_related'     => __( 'Show related posts', 'circlepress' ),
		'show_progress'    => __( 'Show reading progress bar', 'circlepress' ),
		'show_reading_time' => __( 'Show reading time', 'circlepress' ),
	) as $key => $label ) {
		$wp_customize->add_setting( 'circlepress_' . $key, array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
		$wp_customize->add_control( 'circlepress_' . $key, array( 'label' => $label, 'section' => 'circlepress_single', 'type' => 'checkbox' ) );
	}
	$wp_customize->add_setting( 'circlepress_single_sidebar', array( 'default' => 'right', 'sanitize_callback' => 'circlepress_sanitize_sidebar' ) );
	$wp_customize->add_control( 'circlepress_single_sidebar', array( 'label' => __( 'Single sidebar position', 'circlepress' ), 'section' => 'circlepress_single', 'type' => 'select', 'choices' => circlepress_sidebar_choices() ) );
	$wp_customize->add_setting( 'circlepress_archive_sidebar', array( 'default' => 'right', 'sanitize_callback' => 'circlepress_sanitize_sidebar' ) );
	$wp_customize->add_control( 'circlepress_archive_sidebar', array( 'label' => __( 'Archive sidebar position', 'circlepress' ), 'section' => 'circlepress_single', 'type' => 'select', 'choices' => circlepress_sidebar_choices() ) );
	$wp_customize->add_setting(
		'circlepress_niche_box_position',
		array( 'default' => 'after', 'sanitize_callback' => 'circlepress_sanitize_box_pos' )
	);
	$wp_customize->add_control(
		'circlepress_niche_box_position',
		array(
			'label'   => __( 'Recipe / How-To / Product box position', 'circlepress' ),
			'section' => 'circlepress_single',
			'type'    => 'select',
			'choices' => array(
				'after'  => __( 'After content (automatic)', 'circlepress' ),
				'before' => __( 'Before content (automatic)', 'circlepress' ),
				'shortcode' => __( 'Only via shortcode', 'circlepress' ),
			),
		)
	);

	/* ===== ADS ===== */
	$wp_customize->add_section(
		'circlepress_ads',
		array(
			'title'       => __( '💰 Ads (AdSense / Ezoic / Mediavine)', 'circlepress' ),
			'priority'    => 60,
			'description' => __( 'Paste your ad codes. Works with AdSense <ins> units, Ezoic placeholders and Mediavine. Leave empty to disable a slot.', 'circlepress' ),
		)
	);
	$wp_customize->add_setting( 'circlepress_ads_enabled', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_ads_enabled', array( 'label' => __( 'Enable ads', 'circlepress' ), 'section' => 'circlepress_ads', 'type' => 'checkbox' ) );
	foreach ( array(
		'header'        => __( 'Header ad code (below menu)', 'circlepress' ),
		'below_title'   => __( 'Single: below title', 'circlepress' ),
		'in_content'    => __( 'Single: inside content (auto)', 'circlepress' ),
		'sidebar'       => __( 'Sidebar sticky ad code', 'circlepress' ),
		'footer'        => __( 'Footer ad code (above footer)', 'circlepress' ),
		'sticky_footer' => __( 'Sticky mobile footer ad code', 'circlepress' ),
	) as $slot => $label ) {
		$wp_customize->add_setting( 'circlepress_ad_' . $slot, array( 'default' => '', 'sanitize_callback' => 'circlepress_sanitize_ad' ) );
		$wp_customize->add_control( 'circlepress_ad_' . $slot, array( 'label' => $label, 'section' => 'circlepress_ads', 'type' => 'textarea' ) );
	}
	$wp_customize->add_setting( 'circlepress_ad_paragraphs', array( 'default' => '2,5,8', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'circlepress_ad_paragraphs',
		array(
			'label'       => __( 'In-content ad after paragraphs', 'circlepress' ),
			'description' => __( 'Comma-separated paragraph numbers, e.g. 2,5,8', 'circlepress' ),
			'section'     => 'circlepress_ads',
			'type'        => 'text',
		)
	);
	$wp_customize->add_setting( 'circlepress_ads_lazy', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_ads_lazy', array( 'label' => __( 'Lazy-load ad slots (better Core Web Vitals)', 'circlepress' ), 'section' => 'circlepress_ads', 'type' => 'checkbox' ) );

	/* ===== AFFILIATE ===== */
	$wp_customize->add_section( 'circlepress_affiliate', array( 'title' => __( '🤝 Affiliate', 'circlepress' ), 'priority' => 65 ) );
	$wp_customize->add_setting( 'circlepress_affiliate_disclosure', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_affiliate_disclosure', array( 'label' => __( 'Auto disclosure on posts with product/affiliate data', 'circlepress' ), 'section' => 'circlepress_affiliate', 'type' => 'checkbox' ) );
	$wp_customize->add_setting(
		'circlepress_affiliate_text',
		array(
			'default'           => __( 'This post may contain affiliate links. If you buy through them, we may earn a commission at no extra cost to you.', 'circlepress' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control( 'circlepress_affiliate_text', array( 'label' => __( 'Disclosure text', 'circlepress' ), 'section' => 'circlepress_affiliate', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'circlepress_affiliate_nofollow', array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'circlepress_affiliate_nofollow', array( 'label' => __( 'Add rel="nofollow sponsored" to affiliate buttons', 'circlepress' ), 'section' => 'circlepress_affiliate', 'type' => 'checkbox' ) );

	/* ===== SEO ===== */
	$wp_customize->add_section(
		'circlepress_seo',
		array(
			'title'       => __( '🚀 SEO & Schema', 'circlepress' ),
			'priority'    => 70,
			'description' => __( 'Built-in lightweight SEO. If you install RankMath/Yoast, disable overlapping options here to avoid duplicates.', 'circlepress' ),
		)
	);
	foreach ( array(
		'seo_meta'    => __( 'Meta description + canonical + robots', 'circlepress' ),
		'seo_og'      => __( 'Open Graph + Twitter cards', 'circlepress' ),
		'seo_schema'  => __( 'Schema.org JSON-LD (Article/Recipe/HowTo/Product/FAQ)', 'circlepress' ),
		'seo_crumbs'  => __( 'Breadcrumb schema', 'circlepress' ),
	) as $key => $label ) {
		$wp_customize->add_setting( 'circlepress_' . $key, array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
		$wp_customize->add_control( 'circlepress_' . $key, array( 'label' => $label, 'section' => 'circlepress_seo', 'type' => 'checkbox' ) );
	}
	$wp_customize->add_setting( 'circlepress_org_type', array( 'default' => 'Organization', 'sanitize_callback' => 'circlepress_sanitize_org' ) );
	$wp_customize->add_control( 'circlepress_org_type', array( 'label' => __( 'Publisher type', 'circlepress' ), 'section' => 'circlepress_seo', 'type' => 'select', 'choices' => array( 'Organization' => 'Organization', 'Person' => 'Person' ) ) );

	/* ===== SOCIAL ===== */
	$wp_customize->add_section( 'circlepress_social', array( 'title' => __( 'Social Links', 'circlepress' ), 'priority' => 75 ) );
	foreach ( array( 'facebook', 'instagram', 'pinterest', 'youtube', 'tiktok', 'x' ) as $net ) {
		$wp_customize->add_setting( 'circlepress_social_' . $net, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( 'circlepress_social_' . $net, array( 'label' => ucfirst( $net ) . ' URL', 'section' => 'circlepress_social', 'type' => 'url' ) );
	}

	/* ===== NEWSLETTER ===== */
	$wp_customize->add_section( 'circlepress_newsletter', array( 'title' => __( 'Newsletter', 'circlepress' ), 'priority' => 76 ) );
	$wp_customize->add_setting( 'circlepress_newsletter_title', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'circlepress_newsletter_title', array( 'label' => __( 'Box title', 'circlepress' ), 'section' => 'circlepress_newsletter', 'type' => 'text' ) );
	$wp_customize->add_setting( 'circlepress_newsletter_text', array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'circlepress_newsletter_text', array( 'label' => __( 'Box text', 'circlepress' ), 'section' => 'circlepress_newsletter', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'circlepress_newsletter_action', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		'circlepress_newsletter_action',
		array(
			'label'       => __( 'Form action URL (Mailchimp/ConvertKit/Brevo)', 'circlepress' ),
			'description' => __( 'Paste your provider form action. Empty = decorative box with mailto fallback note.', 'circlepress' ),
			'section'     => 'circlepress_newsletter',
			'type'        => 'url',
		)
	);

	/* ===== PERFORMANCE ===== */
	$wp_customize->add_section( 'circlepress_perf', array( 'title' => __( '⚡ Performance (small hosting)', 'circlepress' ), 'priority' => 80 ) );
	foreach ( array(
		'perf_emoji'    => __( 'Disable emojis', 'circlepress' ),
		'perf_embeds'   => __( 'Disable oEmbeds', 'circlepress' ),
		'perf_jquery'   => __( 'Remove jQuery Migrate on frontend', 'circlepress' ),
		'perf_heartbeat' => __( 'Slow down admin-ajax heartbeat', 'circlepress' ),
		'perf_preload'  => __( 'Preload featured image (single)', 'circlepress' ),
	) as $key => $label ) {
		$wp_customize->add_setting( 'circlepress_' . $key, array( 'default' => true, 'sanitize_callback' => 'circlepress_sanitize_checkbox' ) );
		$wp_customize->add_control( 'circlepress_' . $key, array( 'label' => $label, 'section' => 'circlepress_perf', 'type' => 'checkbox' ) );
	}

	/* ===== FOOTER ===== */
	$wp_customize->add_section( 'circlepress_footer', array( 'title' => __( 'Footer', 'circlepress' ), 'priority' => 90 ) );
	$wp_customize->add_setting( 'circlepress_footer_text', array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'circlepress_footer_text', array( 'label' => __( 'Copyright text (empty = automatic)', 'circlepress' ), 'section' => 'circlepress_footer', 'type' => 'textarea' ) );
}
add_action( 'customize_register', 'circlepress_customize_register' );

/* ---------- Sanitizers ---------- */
function circlepress_sanitize_checkbox( $v ) {
	return (bool) $v;
}
function circlepress_sanitize_niche( $v ) {
	return array_key_exists( (string) $v, circlepress_get_niches() ) ? (string) $v : 'food';
}
function circlepress_sanitize_sidebar( $v ) {
	return in_array( $v, array( 'right', 'left', 'no-sidebar' ), true ) ? $v : 'right';
}
function circlepress_sidebar_choices() {
	return array(
		'right'      => __( 'Right', 'circlepress' ),
		'left'       => __( 'Left', 'circlepress' ),
		'no-sidebar' => __( 'No sidebar', 'circlepress' ),
	);
}
function circlepress_sanitize_box_pos( $v ) {
	return in_array( $v, array( 'after', 'before', 'shortcode' ), true ) ? $v : 'after';
}
function circlepress_sanitize_org( $v ) {
	return in_array( $v, array( 'Organization', 'Person' ), true ) ? $v : 'Organization';
}
/**
 * Allow ad scripts/ins/iframes but strip dangerous tags.
 *
 * @param string $v Raw ad code.
 * @return string
 */
function circlepress_sanitize_ad( $v ) {
	return wp_kses(
		$v,
		array(
			'script' => array( 'async' => true, 'src' => true, 'crossorigin' => true, 'type' => true ),
			'ins'    => array( 'class' => true, 'style' => true, 'data-ad-client' => true, 'data-ad-slot' => true, 'data-ad-format' => true, 'data-full-width-responsive' => true ),
			'div'    => array( 'class' => true, 'id' => true, 'style' => true, 'data-ezoic' => true, 'data-ezoic-id' => true ),
			'iframe' => array( 'src' => true, 'width' => true, 'height' => true, 'style' => true, 'frameborder' => true, 'scrolling' => true, 'allowfullscreen' => true, 'title' => true ),
			'a'      => array( 'href' => true, 'target' => true, 'rel' => true ),
			'img'    => array( 'src' => true, 'alt' => true, 'width' => true, 'height' => true, 'style' => true ),
		)
	);
}
