<?php
/**
 * Template Name: CirclePress Home (Active Niche)
 * Description: Magazine homepage using the niche selected in Customizer → Site Niche.
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
