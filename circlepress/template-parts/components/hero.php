<?php
/**
 * Niche hero (magazine / bold / split / minimal).
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$niche_id = circlepress_get_current_niche();
$niche    = circlepress_get_niche( $niche_id );
$style    = isset( $niche['hero_style'] ) ? $niche['hero_style'] : 'magazine';
$title    = get_theme_mod( 'circlepress_hero_title', '' ) ? get_theme_mod( 'circlepress_hero_title', '' ) : $niche['hero_title'];
$sub      = get_theme_mod( 'circlepress_hero_sub', '' ) ? get_theme_mod( 'circlepress_hero_sub', '' ) : $niche['hero_sub'];

if ( 'bold' === $style ) :
	?>
	<section class="cp-hero cp-hero--bold">
		<span class="cp-kicker"><?php echo esc_html( $niche['icon'] . ' ' . $niche['label'] ); ?></span>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p><?php echo esc_html( $sub ); ?></p>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:flex;gap:10px;max-width:520px;margin:0 auto">
			<input type="search" name="s" placeholder="<?php esc_attr_e( 'What are you looking for?', 'circlepress' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'circlepress' ); ?>" style="flex:1;border:0;border-radius:999px;padding:13px 22px">
			<button class="cp-btn cp-btn--light" type="submit"><?php esc_html_e( 'Search', 'circlepress' ); ?></button>
		</form>
		<div class="cp-hero__cta">
			<a class="cp-btn cp-btn--light" href="#cp-latest"><?php esc_html_e( 'Browse latest', 'circlepress' ); ?> ↓</a>
		</div>
	</section>
	<?php
	return;
endif;

if ( 'split' === $style ) :
	$img = get_theme_mod( 'circlepress_hero_image', '' );
	$q   = circlepress_featured_query( 1 );
	if ( ! $img && $q->have_posts() ) {
		$q->the_post();
		$img = get_the_post_thumbnail_url( null, 'large' );
		wp_reset_postdata();
	}
	?>
	<section class="cp-hero cp-hero--split">
		<div class="cp-hero__text">
			<span class="cp-kicker"><?php echo esc_html( $niche['icon'] . ' ' . $niche['label'] ); ?></span>
			<h1><?php echo esc_html( $title ); ?></h1>
			<p style="color:var(--cp-muted)"><?php echo esc_html( $sub ); ?></p>
			<div class="cp-hero__cta">
				<a class="cp-btn" href="#cp-latest"><?php esc_html_e( 'Start exploring', 'circlepress' ); ?> →</a>
				<a class="cp-btn cp-btn--outline" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'All articles', 'circlepress' ); ?></a>
			</div>
		</div>
		<div class="cp-hero__media" <?php echo $img ? 'style="background-image:url(' . esc_url( $img ) . ')"' : ''; ?> role="img" aria-label="<?php echo esc_attr( $title ); ?>"></div>
	</section>
	<?php
	return;
endif;

if ( 'minimal' === $style ) :
	?>
	<section class="cp-hero cp-hero--minimal">
		<span class="cp-kicker"><?php echo esc_html( $niche['icon'] . ' ' . $niche['label'] ); ?></span>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p style="color:var(--cp-muted)"><?php echo esc_html( $sub ); ?></p>
	</section>
	<?php
	return;
endif;

/* Default: magazine hero (1 big + 2 small). */
$q = circlepress_featured_query( 3 );
if ( ! $q->have_posts() ) {
	return;
}
$posts = array();
while ( $q->have_posts() ) {
	$q->the_post();
	$posts[] = get_post();
}
wp_reset_postdata();
$main = array_shift( $posts );
?>
<section class="cp-hero cp-hero--magazine">
	<article class="cp-hero__main">
		<?php if ( has_post_thumbnail( $main ) ) : ?>
			<?php echo get_the_post_thumbnail( $main, 'large' ); ?>
		<?php endif; ?>
		<div class="cp-hero__overlay">
			<span class="cp-kicker"><?php esc_html_e( 'Featured', 'circlepress' ); ?></span>
			<h2><a href="<?php echo esc_url( get_permalink( $main ) ); ?>"><?php echo esc_html( get_the_title( $main ) ); ?></a></h2>
			<div class="cp-hero__meta"><span>📅 <?php echo esc_html( get_the_date( '', $main ) ); ?></span><span>⏱ <?php echo esc_html( circlepress_reading_time( $main->ID ) ); ?></span></div>
		</div>
	</article>
	<div class="cp-hero__side">
		<?php foreach ( $posts as $p ) : ?>
			<article class="cp-hero__mini">
				<?php if ( has_post_thumbnail( $p ) ) : ?>
					<?php echo get_the_post_thumbnail( $p, 'medium_large' ); ?>
				<?php endif; ?>
				<div class="cp-hero__overlay">
					<h3><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
					<div class="cp-hero__meta"><span>📅 <?php echo esc_html( get_the_date( '', $p ) ); ?></span></div>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
