<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

if ( !toolkit_get_sidebar_selection() ) {
	return;
}

$id = toolkit_get_sidebar_selection()['id'];
?>
<aside id="custom_sidebar" class="<?=toolkit_get_sidebar_column_classes();?>" role="complementary">
	<?php echo do_shortcode("[custom_widget id=$id]");?>
</aside>
