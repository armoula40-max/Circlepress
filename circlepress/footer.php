<?php
/**
 * Footer.
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>

<?php circlepress_ad( 'footer' ); ?>

<footer class="cp-footer">
	<div class="cp-container">
		<div class="cp-footer__grid">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div>
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
						<?php dynamic_sidebar( 'footer-' . $i ); ?>
					<?php elseif ( 1 === $i ) : ?>
						<h3><?php bloginfo( 'name' ); ?></h3>
						<p style="font-size:.9rem;opacity:.85"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
						<?php $social = circlepress_social_links(); ?>
						<?php if ( $social ) : ?>
							<div class="cp-social">
								<?php foreach ( $social as $s ) : ?>
									<a href="<?php echo esc_url( $s[0] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s[2] ); ?>" title="<?php echo esc_attr( $s[2] ); ?>"><?php echo esc_html( $s[1] ); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php elseif ( 2 === $i ) : ?>
						<h3><?php esc_html_e( 'Explore', 'circlepress' ); ?></h3>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => function () {
									echo '<ul><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'circlepress' ) . '</a></li></ul>';
								},
							)
						);
						?>
					<?php elseif ( 3 === $i ) : ?>
						<h3><?php esc_html_e( 'Categories', 'circlepress' ); ?></h3>
						<ul>
							<?php
							wp_list_categories(
								array(
									'title_li' => '',
									'number'   => 6,
									'show_count' => false,
								)
							);
							?>
						</ul>
					<?php else : ?>
						<h3><?php esc_html_e( 'About', 'circlepress' ); ?></h3>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'circlepress' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'circlepress' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'circlepress' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>
		<div class="cp-footer__bottom">
			<div class="cp-container" style="padding:0">
				<span>
					<?php
					$custom = get_theme_mod( 'circlepress_footer_text', '' );
					if ( $custom ) {
						echo esc_html( $custom );
					} else {
						/* translators: 1: year, 2: site name. */
						printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'circlepress' ), esc_html( gmdate( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) );
					}
					?>
				</span>
				<span><?php esc_html_e( 'Powered by CirclePress', 'circlepress' ); ?></span>
			</div>
		</div>
	</div>
</footer>

<?php circlepress_ad( 'sticky_footer' ); ?>
<button class="cp-top" aria-label="<?php esc_attr_e( 'Back to top', 'circlepress' ); ?>">↑</button>
<?php wp_footer(); ?>
</body>
</html>
