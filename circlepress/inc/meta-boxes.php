<?php
/**
 * Post meta boxes: general SEO/rating + Recipe + HowTo + Product + FAQ.
 * Powers the visible cards AND the JSON-LD schema. No plugin needed.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function circlepress_register_meta_boxes() {
	add_meta_box(
		'circlepress_details',
		__( 'CirclePress: Niche Details (Recipe / How-To / Product / FAQ)', 'circlepress' ),
		'circlepress_meta_box_html',
		'post',
		'normal',
		'high'
	);
	add_meta_box(
		'circlepress_page',
		__( 'CirclePress: Page Options', 'circlepress' ),
		'circlepress_page_box_html',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'circlepress_register_meta_boxes' );

/* ---------- Field definitions ---------- */
function circlepress_meta_fields() {
	return array(
		'general' => array(
			'title'  => __( 'General', 'circlepress' ),
			'fields' => array(
				'_cp_subtitle'         => array( 'label' => __( 'Subtitle (under title)', 'circlepress' ), 'type' => 'text' ),
				'_cp_meta_description' => array( 'label' => __( 'Custom meta description (SEO)', 'circlepress' ), 'type' => 'text' ),
				'_cp_video_url'        => array( 'label' => __( 'Featured video URL (YouTube, optional)', 'circlepress' ), 'type' => 'url' ),
				'_cp_rating'           => array( 'label' => __( 'Star rating (0–5, e.g. 4.5)', 'circlepress' ), 'type' => 'number' ),
				'_cp_rating_count'     => array( 'label' => __( 'Rating count (for schema)', 'circlepress' ), 'type' => 'number' ),
				'_cp_verdict'          => array( 'label' => __( 'Short verdict (reviews)', 'circlepress' ), 'type' => 'textarea' ),
				'_cp_hide_ads'         => array( 'label' => __( 'Hide ads on this post', 'circlepress' ), 'type' => 'checkbox' ),
				'_cp_hide_toc'         => array( 'label' => __( 'Hide table of contents', 'circlepress' ), 'type' => 'checkbox' ),
				'_cp_sponsored'        => array( 'label' => __( 'Sponsored post (shows disclosure)', 'circlepress' ), 'type' => 'checkbox' ),
			),
		),
		'recipe'  => array(
			'title' => __( '🍲 Recipe (Food niche → Recipe schema)', 'circlepress' ),
			'fields' => array(
				'_cp_recipe_name'         => array( 'label' => __( 'Recipe name (default = post title)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_prep'         => array( 'label' => __( 'Prep time (e.g. 15 min)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_cook'         => array( 'label' => __( 'Cook time (e.g. 30 min)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_total'        => array( 'label' => __( 'Total time (e.g. 45 min)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_servings'     => array( 'label' => __( 'Servings (e.g. 4)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_calories'     => array( 'label' => __( 'Calories per serving (number)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_course'       => array( 'label' => __( 'Course (Dinner, Dessert…)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_cuisine'      => array( 'label' => __( 'Cuisine (Italian, Mexican…)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_keywords'     => array( 'label' => __( 'Keywords (comma separated)', 'circlepress' ), 'type' => 'text' ),
				'_cp_recipe_ingredients'  => array( 'label' => __( 'Ingredients (one per line)', 'circlepress' ), 'type' => 'textarea' ),
				'_cp_recipe_instructions' => array( 'label' => __( 'Instructions (one step per line)', 'circlepress' ), 'type' => 'textarea' ),
				'_cp_recipe_notes'        => array( 'label' => __( 'Recipe notes / tips', 'circlepress' ), 'type' => 'textarea' ),
			),
		),
		'howto'   => array(
			'title' => __( '🛠️ How-To (Crochet / DIY / Nails / Gardening / Beauty / Decor → HowTo schema)', 'circlepress' ),
			'fields' => array(
				'_cp_howto_name'      => array( 'label' => __( 'Project name (default = post title)', 'circlepress' ), 'type' => 'text' ),
				'_cp_howto_time'      => array( 'label' => __( 'Total time (e.g. 2h 30m)', 'circlepress' ), 'type' => 'text' ),
				'_cp_howto_difficulty' => array( 'label' => __( 'Difficulty', 'circlepress' ), 'type' => 'select', 'options' => array( '' => '—', 'Beginner' => 'Beginner', 'Easy' => 'Easy', 'Intermediate' => 'Intermediate', 'Advanced' => 'Advanced' ) ),
				'_cp_howto_cost'      => array( 'label' => __( 'Estimated cost (number, e.g. 25)', 'circlepress' ), 'type' => 'text' ),
				'_cp_howto_tools'     => array( 'label' => __( 'Tools (one per line)', 'circlepress' ), 'type' => 'textarea' ),
				'_cp_howto_materials' => array( 'label' => __( 'Materials / supplies (one per line)', 'circlepress' ), 'type' => 'textarea' ),
				'_cp_howto_steps'     => array( 'label' => __( 'Steps (one per line)', 'circlepress' ), 'type' => 'textarea' ),
			),
		),
		'product' => array(
			'title' => __( '🛋️ Product / Review (Furniture / Beauty / Pets / Decor → Product schema)', 'circlepress' ),
			'fields' => array(
				'_cp_product_name'         => array( 'label' => __( 'Product name', 'circlepress' ), 'type' => 'text' ),
				'_cp_product_price'        => array( 'label' => __( 'Price (number, e.g. 199.99)', 'circlepress' ), 'type' => 'text' ),
				'_cp_product_currency'     => array( 'label' => __( 'Currency (e.g. USD)', 'circlepress' ), 'type' => 'text' ),
				'_cp_product_brand'        => array( 'label' => __( 'Brand', 'circlepress' ), 'type' => 'text' ),
				'_cp_product_availability' => array( 'label' => __( 'Availability', 'circlepress' ), 'type' => 'select', 'options' => array( 'InStock' => 'In stock', 'OutOfStock' => 'Out of stock', 'PreOrder' => 'Pre-order', 'LimitedAvailability' => 'Limited' ) ),
				'_cp_product_url'          => array( 'label' => __( 'Affiliate / buy URL', 'circlepress' ), 'type' => 'url' ),
				'_cp_product_cta'          => array( 'label' => __( 'Button text (default: Check Price)', 'circlepress' ), 'type' => 'text' ),
				'_cp_pros'                 => array( 'label' => __( 'Pros (one per line)', 'circlepress' ), 'type' => 'textarea' ),
				'_cp_cons'                 => array( 'label' => __( 'Cons (one per line)', 'circlepress' ), 'type' => 'textarea' ),
			),
		),
		'faq'     => array(
			'title' => __( '❓ FAQ (→ FAQPage schema + visible accordion)', 'circlepress' ),
			'fields' => array(
				'_cp_faq' => array( 'label' => __( 'Questions (one per line: Question | Answer)', 'circlepress' ), 'type' => 'textarea' ),
			),
		),
		'eeat'    => array(
			'title' => __( '✅ E-E-A-T: Reviewer & Sources (→ reviewedBy schema)', 'circlepress' ),
			'fields' => array(
				'_cp_reviewer_name' => array( 'label' => __( 'Reviewer name (e.g. Dr. Jane Smith)', 'circlepress' ), 'type' => 'text' ),
				'_cp_reviewer_role' => array( 'label' => __( 'Reviewer role (e.g. Veterinarian)', 'circlepress' ), 'type' => 'text' ),
				'_cp_reviewed_date' => array( 'label' => __( 'Reviewed date (YYYY-MM-DD)', 'circlepress' ), 'type' => 'text' ),
				'_cp_sources'       => array( 'label' => __( 'Sources (one per line: Title | URL)', 'circlepress' ), 'type' => 'textarea' ),
			),
		),
	);
}

/* ---------- Render ---------- */
function circlepress_meta_box_html( $post ) {
	wp_nonce_field( 'circlepress_save_meta', 'circlepress_meta_nonce' );
	echo '<p>' . esc_html__( 'Fill only the group you need. The theme shows a styled card automatically and adds matching Schema.org markup.', 'circlepress' ) . '</p>';
	foreach ( circlepress_meta_fields() as $group ) {
		echo '<h3 style="margin:18px 0 8px;padding:10px 12px;background:#f6f7f7;border:1px solid #e2e4e7;">' . esc_html( $group['title'] ) . '</h3>';
		echo '<table class="form-table" style="margin-top:0">';
		foreach ( $group['fields'] as $key => $field ) {
			$value = get_post_meta( $post->ID, $key, true );
			echo '<tr><th scope="row" style="padding:8px 10px 8px 0;"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td style="padding:6px 0;">';
			circlepress_meta_field_html( $key, $field, $value );
			echo '</td></tr>';
		}
		echo '</table>';
	}
}

function circlepress_meta_field_html( $key, $field, $value ) {
	$type = isset( $field['type'] ) ? $field['type'] : 'text';
	switch ( $type ) {
		case 'textarea':
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="4" class="large-text">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'checkbox':
			echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1"' . checked( $value, '1', false ) . '>';
			break;
		case 'number':
			echo '<input type="number" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" step="0.1" min="0" max="5" class="small-text">';
			break;
		case 'select':
			echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
			foreach ( (array) $field['options'] as $ov => $ol ) {
				echo '<option value="' . esc_attr( $ov ) . '"' . selected( $value, $ov, false ) . '>' . esc_html( $ol ) . '</option>';
			}
			echo '</select>';
			break;
		case 'url':
			echo '<input type="url" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="large-text" placeholder="https://…">';
			break;
		default:
			echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="large-text">';
	}
}

function circlepress_page_box_html( $post ) {
	wp_nonce_field( 'circlepress_save_meta', 'circlepress_meta_nonce' );
	$current = get_post_meta( $post->ID, '_cp_niche_override', true );
	echo '<p><label for="_cp_niche_override"><strong>' . esc_html__( 'Niche style for this page', 'circlepress' ) . '</strong></label></p>';
	echo '<select id="_cp_niche_override" name="_cp_niche_override" style="width:100%">';
	echo '<option value="">' . esc_html__( '— Use global niche —', 'circlepress' ) . '</option>';
	foreach ( circlepress_get_niches() as $id => $n ) {
		echo '<option value="' . esc_attr( $id ) . '"' . selected( $current, $id, false ) . '>' . esc_html( $n['icon'] . ' ' . $n['label'] ) . '</option>';
	}
	echo '</select>';
	$home_layout = get_post_meta( $post->ID, '_cp_home_layout', true );
	echo '<p style="margin-top:12px"><label for="_cp_home_layout"><strong>' . esc_html__( 'Homepage layout (for home templates)', 'circlepress' ) . '</strong></label></p>';
	echo '<select id="_cp_home_layout" name="_cp_home_layout" style="width:100%">';
	echo '<option value="">' . esc_html__( '— Use global layout —', 'circlepress' ) . '</option>';
	foreach ( circlepress_home_layouts() as $lid => $llabel ) {
		echo '<option value="' . esc_attr( $lid ) . '"' . selected( $home_layout, $lid, false ) . '>' . esc_html( $llabel ) . '</option>';
	}
	echo '</select>';
	$hide_ads = get_post_meta( $post->ID, '_cp_hide_ads', true );
	echo '<p><label><input type="checkbox" name="_cp_hide_ads" value="1"' . checked( $hide_ads, '1', false ) . '> ' . esc_html__( 'Hide ads on this page', 'circlepress' ) . '</label></p>';
}

/* ---------- Save ---------- */
function circlepress_save_meta( $post_id ) {
	if ( ! isset( $_POST['circlepress_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['circlepress_meta_nonce'] ), 'circlepress_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$keys = array( '_cp_niche_override', '_cp_home_layout', '_cp_hide_ads' );
	foreach ( circlepress_meta_fields() as $group ) {
		foreach ( $group['fields'] as $key => $field ) {
			$keys[ $key ] = isset( $field['type'] ) ? $field['type'] : 'text';
		}
	}
	foreach ( $keys as $key => $type ) {
		if ( is_int( $key ) ) { /* page-only keys */
			$key  = $type;
			$type = '_cp_hide_ads' === $key ? 'checkbox' : 'text';
		}
		if ( 'checkbox' === $type ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, '1' );
			} else {
				delete_post_meta( $post_id, $key );
			}
			continue;
		}
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		switch ( $type ) {
			case 'url':
				$val = esc_url_raw( $raw );
				break;
			case 'number':
				$val = is_numeric( $raw ) ? (string) (float) $raw : '';
				break;
			case 'textarea':
				$val = sanitize_textarea_field( $raw );
				break;
			default:
				$val = sanitize_text_field( $raw );
		}
		if ( '_cp_niche_override' === $key && $val && ! array_key_exists( $val, circlepress_get_niches() ) ) {
			$val = '';
		}
		if ( '' === $val ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $val );
		}
	}
}
add_action( 'save_post', 'circlepress_save_meta' );
