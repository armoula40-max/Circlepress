<?php
/**
 * Template Name: CirclePress A-Z Index
 * Description: Searchable A-Z index of all posts with category filter (great for recipes/patterns).
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="cp-container">
	<section class="cp-hero cp-hero--minimal">
		<span class="cp-kicker">🔤 <?php esc_html_e( 'Index', 'circlepress' ); ?></span>
		<h1><?php the_title(); ?></h1>
		<?php
		while ( have_posts() ) :
			the_post();
			$content = get_the_content();
			if ( trim( $content ) ) {
				echo '<div style="color:var(--cp-muted)">' . apply_filters( 'the_content', $content ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		endwhile;
		?>
	</section>

	<?php
	$q = new WP_Query(
		array(
			'post_type'              => 'post',
			'posts_per_page'         => 600,
			'orderby'                => 'title',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts'    => true,
		)
	);
	if ( ! $q->have_posts() ) {
		echo '<p>' . esc_html__( 'No posts yet.', 'circlepress' ) . '</p>';
		get_footer();
		return;
	}
	$groups = array();
	$allcats = array();
	while ( $q->have_posts() ) {
		$q->the_post();
		$title  = get_the_title();
		$letter = function_exists( 'mb_substr' ) ? mb_strtoupper( mb_substr( trim( $title ), 0, 1 ) ) : strtoupper( substr( trim( $title ), 0, 1 ) );
		if ( ! preg_match( '/[A-Z]/u', $letter ) ) {
			$letter = '#';
		}
		$cats = array();
		foreach ( get_the_category() as $c ) {
			$cats[] = $c->name;
			$allcats[ $c->slug ] = $c->name;
		}
		$groups[ $letter ][] = array(
			'title' => $title,
			'url'   => get_permalink(),
			'cats'  => implode( '|', $cats ),
			'date'  => get_the_date(),
		);
	}
	wp_reset_postdata();
	ksort( $groups );
	?>
	<div class="cp-az__controls">
		<input type="search" id="cp-az-search" placeholder="<?php esc_attr_e( 'Filter… (e.g. chocolate)', 'circlepress' ); ?>" aria-label="<?php esc_attr_e( 'Filter index', 'circlepress' ); ?>">
		<?php if ( $allcats ) : ?>
			<select id="cp-az-cat" aria-label="<?php esc_attr_e( 'Filter by category', 'circlepress' ); ?>">
				<option value=""><?php esc_html_e( 'All categories', 'circlepress' ); ?></option>
				<?php foreach ( $allcats as $slug => $name ) : ?>
					<option value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
	</div>
	<nav class="cp-az__letters" aria-label="<?php esc_attr_e( 'Letters', 'circlepress' ); ?>">
		<?php foreach ( array_keys( $groups ) as $letter ) : ?>
			<a href="#az-<?php echo esc_attr( $letter ); ?>"><?php echo esc_html( $letter ); ?></a>
		<?php endforeach; ?>
	</nav>
	<div class="cp-az">
		<?php foreach ( $groups as $letter => $items ) : ?>
			<section class="cp-az__group" data-az-group id="az-<?php echo esc_attr( $letter ); ?>">
				<h2><?php echo esc_html( $letter ); ?></h2>
				<ul>
					<?php foreach ( $items as $it ) : ?>
						<li data-az-item data-cats="<?php echo esc_attr( $it['cats'] ); ?>">
							<a href="<?php echo esc_url( $it['url'] ); ?>"><?php echo esc_html( $it['title'] ); ?></a>
							<small><?php echo esc_html( $it['date'] ); ?></small>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endforeach; ?>
	</div>
</div>
<?php
get_footer();
