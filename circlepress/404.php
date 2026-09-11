<?php
/**
 * 404.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="cp-container">
	<div class="cp-404">
		<h1>404</h1>
		<h2><?php esc_html_e( 'Oops! That page can\'t be found.', 'circlepress' ); ?></h2>
		<p style="color:var(--cp-muted)"><?php esc_html_e( 'Try searching, or explore our latest posts below.', 'circlepress' ); ?></p>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:flex;gap:10px;max-width:480px;margin:20px auto">
			<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search…', 'circlepress' ); ?>" style="flex:1;border:1px solid var(--cp-border);border-radius:999px;padding:12px 20px">
			<button class="cp-btn" type="submit"><?php esc_html_e( 'Search', 'circlepress' ); ?></button>
		</form>
		<p><a class="cp-btn cp-btn--outline" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Back to homepage', 'circlepress' ); ?></a></p>
	</div>
	<section class="cp-section">
		<div class="cp-section__head"><h2><?php esc_html_e( 'Latest posts', 'circlepress' ); ?></h2></div>
		<div class="cp-grid cp-grid--3">
			<?php
			$q = new WP_Query( array( 'posts_per_page' => 3, 'no_found_rows' => true ) );
			while ( $q->have_posts() ) :
				$q->the_post();
				get_template_part( 'template-parts/components/post-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
</div>
<?php
get_footer();
