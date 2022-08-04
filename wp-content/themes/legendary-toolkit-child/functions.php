<?php 
add_action( 'wp_enqueue_scripts', 'legendary_toolkit_child_enqueue_styles' );
function legendary_toolkit_child_enqueue_styles() {
    $parenthandle = 'legendary-toolkit-parent-styles';
    $theme = wp_get_theme();
    wp_enqueue_style( 'legendary-child', get_stylesheet_directory_uri() . '/style.css', 
        array(
            'legendary-toolkit-bootstrap',
            'legendary-toolkit-fontawesome',
            'legendary_toolkit_mobile_menu_styles',
            'legendary_toolkit_mobile_menu_styles',
            'legendary-toolkit-parent-styles',
            'legendary-toolkit-theme-settings-styles',
            // 'js_composer_front',
    ),  
        // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
}

// include additional scripts to header of theme
add_action('wp_head', 'legendary_add_js_scripts');
function legendary_add_js_scripts() {
    wp_enqueue_script( 'script-js', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array(), '1.0.0', true );
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array(), '1.0.0', true );
}


// option-page

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'Theme General Settings',
        'menu_title'	=> 'Theme Options',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));



}

// Menu  shortcode
function print_menu_shortcode($atts, $content = null) {
    extract(shortcode_atts(array( 'name' => null, 'class' => null ), $atts));
    return wp_nav_menu( array( 'menu' => $name, 'menu_class' => $class, 'echo' => false ) );
}

add_shortcode('menu', 'print_menu_shortcode');

