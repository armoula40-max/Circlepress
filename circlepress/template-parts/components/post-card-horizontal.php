<?php
/**
 * Post card — horizontal (list layout).
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$cats = get_the_category();
$cat  = $cats ? $cats[0] : null;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'cp-card cp-card--horizontal' ); ?>>
	<a class="cp-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span style="display:flex;align-items:center;justify-content:center;height:100%;min-height:200px;font-size:3rem;background:linear-gradient(135deg,var(--cp-surface-2),var(--cp-border))"><?php echo esc_html( circlepress_get_niche( circlepress_get_current_niche() )['icon'] ); ?></span>
		<?php endif; ?>
		<?php if ( $cat ) : ?><span class="cp-card__cat"><?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
	</a>
	<div class="cp-card__body">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="cp-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
		<div class="cp-card__meta"><?php circlepress_posted_on(); ?></div>
		<p style="margin:6px 0 0"><a href="<?php the_permalink(); ?>"><strong><?php esc_html_e( 'Read more →', 'circlepress' ); ?></strong></a></p>
	</div>
</article>
