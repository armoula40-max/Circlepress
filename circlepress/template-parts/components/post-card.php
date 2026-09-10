<?php
/**
 * Post card (grid).
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$cats = get_the_category();
$cat  = $cats ? $cats[0] : null;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'cp-card' ); ?>>
	<a class="cp-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>" tabindex="-1">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span class="cp-card__placeholder"><?php echo circlepress_icon( 'image', 44 ); ?></span>
		<?php endif; ?>
		<?php if ( $cat ) : ?><span class="cp-card__cat"><?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
	</a>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="cp-card__save" href="https://pinterest.com/pin/create/button/?url=<?php echo rawurlencode( get_permalink() ); ?>&media=<?php echo rawurlencode( get_the_post_thumbnail_url( null, 'large' ) ); ?>&description=<?php echo rawurlencode( get_the_title() ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Save to Pinterest', 'circlepress' ); ?>"><?php echo circlepress_icon( 'pinterest', 13 ); ?> <?php esc_html_e( 'Save', 'circlepress' ); ?></a>
	<?php endif; ?>
	<div class="cp-card__body">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php circlepress_card_rating(); ?>
		<p class="cp-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
		<div class="cp-card__meta"><?php circlepress_posted_on(); ?></div>
	</div>
</article>
