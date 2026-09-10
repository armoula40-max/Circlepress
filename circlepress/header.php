<?php
/**
 * Header.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'circlepress' ); ?></a>

<?php if ( get_theme_mod( 'circlepress_show_topbar', true ) ) : ?>
<div class="cp-topbar">
	<div class="cp-container">
		<span><?php echo esc_html( get_theme_mod( 'circlepress_topbar_text', '' ) ? get_theme_mod( 'circlepress_topbar_text', '' ) : get_bloginfo( 'description' ) ); ?></span>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'top',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
				'items_wrap'     => '<nav aria-label="' . esc_attr__( 'Top bar', 'circlepress' ) . '"><ul style="display:flex;gap:14px;list-style:none;margin:0;padding:0">%3$s</ul></nav>',
			)
		);
		?>
	</div>
</div>
<?php endif; ?>

<header class="cp-header">
	<div class="cp-container cp-header__inner">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a class="cp-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="cp-logo__mark"><?php echo esc_html( circlepress_get_niche( circlepress_get_current_niche() )['icon'] ); ?></span>
				<span><?php bloginfo( 'name' ); ?><span class="cp-logo__tag"><?php echo esc_html( circlepress_get_niche( circlepress_get_current_niche() )['label'] ); ?></span></span>
			</a>
		<?php endif; ?>

		<nav class="cp-nav" aria-label="<?php esc_attr_e( 'Primary', 'circlepress' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'circlepress_fallback_menu',
				)
			);
			?>
		</nav>

		<div class="cp-header__actions">
			<?php if ( get_theme_mod( 'circlepress_show_search', true ) ) : ?>
				<button class="cp-icon-btn cp-search-toggle" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>" aria-expanded="false">⌕</button>
			<?php endif; ?>
			<?php
			$cta_text = get_theme_mod( 'circlepress_header_cta_text', '' );
			$cta_url  = get_theme_mod( 'circlepress_header_cta_url', '' );
			if ( $cta_text && $cta_url ) :
				?>
				<a class="cp-btn cp-btn--sm cp-header__cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_text ); ?></a>
			<?php endif; ?>
			<button class="cp-icon-btn cp-hamburger" aria-label="<?php esc_attr_e( 'Menu', 'circlepress' ); ?>" aria-expanded="false">☰</button>
		</div>
	</div>
	<div class="cp-search-bar">
		<div class="cp-container">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search recipes, guides, reviews…', 'circlepress' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>">
				<button class="cp-btn" type="submit"><?php esc_html_e( 'Search', 'circlepress' ); ?></button>
			</form>
		</div>
	</div>
	<nav class="cp-mobile-nav" aria-label="<?php esc_attr_e( 'Mobile', 'circlepress' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => 'circlepress_fallback_menu',
			)
		);
		?>
	</nav>
	<?php if ( is_singular() && get_theme_mod( 'circlepress_show_progress', true ) ) : ?>
		<div class="cp-progress" aria-hidden="true"><span></span></div>
	<?php endif; ?>
</header>

<?php circlepress_ad( 'header' ); ?>

<main id="primary">
<?php
/**
 * Fallback menu when no menu assigned.
 */
function circlepress_fallback_menu() {
	echo '<ul>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'circlepress' ) . '</a></li>';
	$pages = get_pages( array( 'number' => 5, 'sort_column' => 'menu_order' ) );
	foreach ( $pages as $page ) {
		echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $page->post_title ) . '</a></li>';
	}
	echo '</ul>';
}
