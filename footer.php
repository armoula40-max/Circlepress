<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
</main>
<footer class="site-footer"><div class="container">
  <div class="footer-grid">
    <div><a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="brand-mark">C</span><span><?php bloginfo( 'name' ); ?></span></a><p class="footer-about">Clear recipes and generous everyday cooking, made for real kitchens.</p></div>
    <div><h3>Explore</h3><ul class="footer-nav"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li><a href="<?php echo esc_url( home_url( '/recipes/' ) ); ?>">Recipes</a></li><li><a href="<?php echo esc_url( home_url( '/meal-plans/' ) ); ?>">Meal Plans</a></li><li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li><li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li></ul></div>
    <div><h3>Kitchen notes</h3><p class="footer-about">Get occasional useful recipe ideas. No spam, and you can opt out anytime.</p><form class="search-form" action="#" method="post"><label class="screen-reader-text" for="footer-email">Email address</label><input id="footer-email" type="email" placeholder="Email address" required><button class="button" type="submit">Join</button></form></div>
  </div>
  <div class="footer-bottom"><span>© <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</span><span><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a></span></div>
</div></footer><?php wp_footer(); ?></body></html>
