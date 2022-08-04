<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */
    $favicon = esc_url(wp_get_attachment_image_url(legendary_toolkit_get_theme_option('favicon'), 'medium'));
    $content_container = (toolkit_get_sidebar_selection() || is_page_template('blank-page.php') || is_page_template('page-full-width.php') ||  is_page_template('page-templates/home-template.php') ||  is_page_template('page-templates/about-template.php') || is_archive() || is_single() || is_home()) ? 'container-fluid' : 'container';
?> 
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <?php wp_head(); ?>
        <?php if ( $favicon ) : ?>
            <link rel="shortcut icon" href="<?=$favicon;?>" />
        <?php endif; ?>
    </head>
    <body <?php body_class(); ?>>
        <?php 
            if ( function_exists( 'wp_body_open' ) ) {
                wp_body_open();
            } else {
                do_action( 'wp_body_open' );
            }
        ?>
        <div id="page" class="site">
            <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'legendary-toolkit' ); ?></a>
            <?php 
                if (!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )) {
                    get_template_part('template-parts/header', 'topbar');
                    get_template_part('template-parts/header', 'menu');
                    get_template_part('template-parts/header', 'title');
                }
            ?>
            <div id="content" class="site-content <?=$content_container;?>">
                <div class="">
            