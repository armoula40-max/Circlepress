<?php
/**
 * Template Name: CirclePress Home — Home Decor
 * Description: Magazine homepage forced to the Home Decor style (overrides the global niche on this page only).
 *
 * @package CirclePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="cp-container">
	<?php get_template_part( 'template-parts/home/home-sections' ); ?>
</div>
<?php
get_footer();
