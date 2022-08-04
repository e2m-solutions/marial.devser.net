<?php
/**
 * Legendary Toolkit functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package legendary_toolkit
 */

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/legendary-lion/legendary-toolkit',
	__FILE__,
	'legendary-toolkit'
);
$myUpdateChecker->setBranch('master');
$myUpdateChecker->getVcsApi()->enableReleaseAssets();

if ( ! function_exists( 'legendary_toolkit_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function legendary_toolkit_setup() {
        /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on Legendary Toolkit, use a find and replace
        * to change 'legendary-toolkit' to the name of your theme in all the template files.
        */
        load_theme_textdomain( 'legendary-toolkit', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
        add_theme_support( 'title-tag' );

        /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
        add_theme_support( 'post-thumbnails' );

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary', 'legendary-toolkit' ),
        ) );

        /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
        add_theme_support( 'html5', array(
            // 'comment-form',
            // 'comment-list',
            'caption',
        ) );

        // Set up the WordPress core custom background feature.
        add_theme_support( 'custom-background', apply_filters( 'legendary_toolkit_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        function wp_boostrap_starter_add_editor_styles() {
            add_editor_style( 'custom-editor-style.css' );
        }
        add_action( 'admin_init', 'wp_boostrap_starter_add_editor_styles' );

    }
endif;
add_action( 'after_setup_theme', 'legendary_toolkit_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function legendary_toolkit_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'legendary_toolkit_content_width', 1320 );
}
add_action( 'after_setup_theme', 'legendary_toolkit_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function legendary_toolkit_scripts() {
	// load bootstrap css
    if ( get_theme_mod( 'cdn_assets_setting' ) === 'yes' ) {
        wp_enqueue_style( 'legendary-toolkit-bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css' );
        wp_enqueue_style( 'legendary-toolkit-fontawesome-cdn', 'https://use.fontawesome.com/releases/v5.15.1/css/all.css' );
    } else {
        wp_enqueue_style( 'legendary-toolkit-bootstrap', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css' );
        wp_enqueue_style( 'legendary-toolkit-fontawesome', get_template_directory_uri() . '/inc/assets/css/fontawesome.min.css' );
    }
    
	// load bootstrap css
	wp_enqueue_script('jquery');
    
    // Internet Explorer HTML5 support
    wp_enqueue_script( 'html5hiv',get_template_directory_uri().'/inc/assets/js/html5.js', array(), '3.7.0', false );
    wp_script_add_data( 'html5hiv', 'conditional', 'lt IE 9' );

    // bootstrap and popperjs
    wp_enqueue_script('legendary-toolkit-popper', get_template_directory_uri() . '/inc/assets/js/popper.min.js', array(), '', true );
    wp_enqueue_script('legendary-toolkit-bootstrapjs', get_template_directory_uri() . '/inc/assets/js/bootstrap.min.js', array(), '', true );
    
    // theme js
    wp_enqueue_script('legendary-toolkit-themejs', get_template_directory_uri() . '/inc/assets/js/theme-script.js', array(), '', true );
	wp_enqueue_script( 'legendary-toolkit-skip-link-focus-fix', get_template_directory_uri() . '/inc/assets/js/skip-link-focus-fix.min.js', array(), '20151215', true );
    
    // custom mobile menu
    wp_enqueue_style('legendary_toolkit_mobile_menu_styles', get_template_directory_uri() . '/inc/assets/css/menu.css');
    wp_enqueue_script('legendary_toolkit_mobile_menu_script', get_template_directory_uri() . '/inc/assets/js/menu.js', array(), '', true);

    // parent styles
    wp_enqueue_style('legendary-toolkit-parent-styles', get_template_directory_uri() . '/style.css');

    // append theme options stylesheet after parent theme styles
    wp_add_inline_style( 'legendary-toolkit-parent-styles', legendary_toolkit_theme_options_css() );  

    // theme styles from settings
    wp_enqueue_style('legendary-toolkit-theme-settings-styles', get_template_directory_uri() . '/inc/assets/css/theme-styles.css');

}
add_action( 'wp_enqueue_scripts', 'legendary_toolkit_scripts' );

function unparse_url($parsed_url) {
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
}

function legendary_toolkit_theme_options_css() {
    $custom_css = '';
    
    // Define variables for theme options stylesheet
    
    $theme_options = legendary_toolkit_get_theme_options();

    // Add Google Fonts to stylesheet

    $font_files = (array_key_exists('font_files', $theme_options)) ? $theme_options['font_files'] : [];

    foreach ($font_files as $family => $files) {
        foreach ($files as $style => $file_url) {
            $font_style = (strpos($style, 'italic') !== false) ? 'italic' : 'normal';

            $weight = (!preg_match('/\\d/', $style)) ? 'normal' : (int) filter_var($style, FILTER_SANITIZE_NUMBER_INT);

            $ext = pathinfo($file_url, PATHINFO_EXTENSION);

            $format = 'truetype';

            $parsed_file_url = parse_url($file_url);

            $parsed_file_url['scheme'] = (is_ssl()) ? 'https' : 'http';

            $file_url = unparse_url($parsed_file_url);

            switch ($ext) {
                case "ttf":
                    $format = 'truetype';
                    break;
                case "otf":
                    $format = 'opentype';
                    break;
                case "woff":
                    $format = 'woff';
                    break;
                case "woff2":
                    $format = 'woff2';
                    break;
                case "svg":
                    $format = 'svg';
                    break;
                default:
                    $format = 'truetype';
            }

            $custom_css .= "
                @font-face {
                    font-family: '$family';
                    src: url('$file_url') format('$format');
                    font-weight: $weight;
                    font-style: $font_style;
                }
            ";

        }
    }

    // Get options used for CSS variables

    $primary_color                = (array_key_exists('primary_color', $theme_options) && $theme_options['primary_color']) ? $theme_options['primary_color'] : '#0f8bf5';
    $secondary_color              = (array_key_exists('secondary_color', $theme_options) && $theme_options['secondary_color']) ? $theme_options['secondary_color'] : '#f56f0f';
    $logo_height                  = (array_key_exists('logo_height', $theme_options) && $theme_options['logo_height']) ? $theme_options['logo_height'] . 'px' : '100px';
    $scrolling_logo_height        = (array_key_exists('scrolling_header_height', $theme_options) && $theme_options['scrolling_header_height']) ? $theme_options['scrolling_header_height'] . 'px' : $logo_height;
    $header_background            = (array_key_exists('header_background', $theme_options) && $theme_options['header_background']) ? $theme_options['header_background'] : 'black';
    $scrolling_header_background  = (array_key_exists('scrolling_header_background', $theme_options) && $theme_options['scrolling_header_background']) ? $theme_options['scrolling_header_background'] : 'white';
    $header_background            = (array_key_exists('transparent_header', $theme_options) && $theme_options['transparent_header']) ? 'transparent' : $header_background;
    $top_bar_background           = (array_key_exists('top_bar_background', $theme_options) && $theme_options['top_bar_background']) ? $theme_options['top_bar_background'] : '#111111';
    $footer_background            = (array_key_exists('footer_background', $theme_options) && $theme_options['footer_background']) ? $theme_options['footer_background'] : '#111111';
    $copyright_background         = (array_key_exists('copyright_background', $theme_options) && $theme_options['copyright_background']) ? $theme_options['copyright_background'] : 'black';
    
    $mobile_menu_breakpoint       = (array_key_exists('mobile_menu_breakpoint', $theme_options) && $theme_options['mobile_menu_breakpoint']) ? $theme_options['mobile_menu_breakpoint'] - 1 . 'px' : '1200px';

    $page_container_width         = (array_key_exists('page_container_width', $theme_options) && $theme_options['page_container_width']) ? $theme_options['page_container_width'] : '1320';

    $favicon                      = (array_key_exists('favicon', $theme_options) && $theme_options['favicon']) ? $theme_options['favicon'] : '';
    $favicon_url                  = ($favicon) ? esc_url(wp_get_attachment_image_url($favicon, 'medium')) : '';

    if (!function_exists('get_saved_font_family')) {
        function get_saved_font_family($option, $options) {
            if (!array_key_exists($option, $options)) { return; }
            if (!$options[$option]) { return; }
            if ($options[$option] == 'Select Font Family') { return; }
            $option = $options[$option];
            return $option;
        }   
    }
    if (!function_exists('get_saved_font_color')) {
        function get_saved_font_color($option, $options) {
            if (!array_key_exists($option, $options)) { return; }
            if (!$options[$option]) { return; }
            $option = $options[$option];
            return $option;
        }
    }
    if (!function_exists('get_saved_font_style')) {
        function get_saved_font_style($option, $options) {
            if (!array_key_exists($option, $options)) { return; }
            if (!$options[$option]) { return; }
            $option = $options[$option];
            return ( strpos($option, 'italic') !== false ) ? 'italic' : 'normal';
        }   
    }
    if (!function_exists('get_saved_font_weight')) {
        function get_saved_font_weight($option, $options) {
            if (!array_key_exists($option, $options)) { return; }
            if (!$options[$option]) { return; }
            $option = $options[$option];
            return ( preg_match('/\\d/', $option) ) ? (int) filter_var($option, FILTER_SANITIZE_NUMBER_INT) : 'regular';
        }
    }
    if (!function_exists('get_saved_font_size')) {
        function get_saved_font_size($option, $options) {
            if (!array_key_exists($option, $options)) { return; }
            if (!$options[$option]) { return; }
            $option = $options[$option];
            return $option . 'px';
        }
    }
    if (!function_exists('get_saved_font_transform')) {
        function get_saved_font_transform($option, $options) {
            if (!array_key_exists($option, $options)) { return; }
            if (!$options[$option]) { return; }
            $option = $options[$option];
            return $option;
        }
    }
    if (!function_exists('define_font_variables')) {
        function define_font_variables($id, $options) {
            $font_family = get_saved_font_family($id . '_font_family', $options);
            $font_color = get_saved_font_color($id . '_font_color', $options);
            $font_style = get_saved_font_style($id . '_font_style', $options);
            $font_weight = get_saved_font_weight($id . '_font_weight', $options);
            $font_size = get_saved_font_size($id . '_font_size', $options);
            $font_transform = get_saved_font_transform($id . '_font_transform', $options);
            $spacer = "            ";
            $style_return = "";
            $style_return .= ($font_family) ?  "--".$id."_font_family : ".$font_family.";\n"  : '';
            $style_return .= ($font_color) ? $spacer . "--".$id."_font_color : ".$font_color.";\n" : '';
            $style_return .= ($font_style) ? $spacer . "--".$id."_font_style : ".$font_style.";\n" : '';
            $style_return .= ($font_weight) ? $spacer . "--".$id."_font_weight : ".$font_weight.";\n" : '';
            $style_return .= ($font_size) ? $spacer . "--".$id."_font_size : ".$font_size.";\n" : '';
            $style_return .= ($font_transform) ? $spacer . "--".$id."_font_transform : ".$font_transform.";\n" : '';

            return $style_return;
        }
    }

    // Add CSS variables
    $custom_css .= "
        :root {
            --primary_color : $primary_color;
            --secondary_color : $secondary_color;
            --logo_height : $logo_height;
            --scrolling_logo_height : $scrolling_logo_height;
            --scrolling_header_background : $scrolling_header_background;
            --header_background : $header_background;
            --top_bar_background : $top_bar_background;
            --footer_background : $footer_background;
            --copyright_background : $copyright_background;
            --favicon_url : $favicon_url;
            --page_container_width : $page_container_width"."px;
            " . define_font_variables('all', $theme_options) . " 
            " . define_font_variables('body', $theme_options) . " 
            " . define_font_variables('h1', $theme_options) . " 
            " . define_font_variables('h2', $theme_options) . " 
            " . define_font_variables('h3', $theme_options) . " 
            " . define_font_variables('h4', $theme_options) . " 
            " . define_font_variables('h5', $theme_options) . " 
            " . define_font_variables('h6', $theme_options) . " 
            " . define_font_variables('menu_items', $theme_options) . "
        }
        @media all and (max-width: $mobile_menu_breakpoint) {
            #main-nav {
                display:none!important;
            }
        }
    ";
    
    // if is sticky_header
    // $custom_css .= "#page{margin-top:".$logo_height.";}\n";
    $custom_css .= "#content{margin-top:".$logo_height.";}\n";
    // $custom_css .= "#content{padding-top:".$logo_height.";}\n";


    if (is_admin_bar_showing()) {
        $custom_css .=  "#masthead{top:32px}\n";
    }

    // print_r($custom_css);
    return $custom_css;
}

function my_login_logo() { 
    // Define variables for theme options stylesheet
    $theme_options = legendary_toolkit_get_theme_options();
    $favicon       = (array_key_exists('favicon', $theme_options) && $theme_options['favicon']) ? $theme_options['favicon'] : '';
    $favicon_url   = ($favicon) ? esc_url(wp_get_attachment_image_url($favicon, 'medium')) : '';
    ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
            background-image: url(<?php echo $favicon_url;?>);
            background-size:contain;
            height:100px;
            width:100px;
            padding-bottom: 4px;
            }
        </style>
    <?php 
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/**
 * Add Preload for CDN scripts and stylesheet
 */
function legendary_toolkit_preload( $hints, $relation_type ){
    if ( 'preconnect' === $relation_type && get_theme_mod( 'cdn_assets_setting' ) === 'yes' ) {
        $hints[] = [
            'href'        => 'https://cdn.jsdelivr.net/',
            'crossorigin' => 'anonymous',
        ];
        $hints[] = [
            'href'        => 'https://use.fontawesome.com/',
            'crossorigin' => 'anonymous',
        ];
    }
    return $hints;
} 
add_filter( 'wp_resource_hints', 'legendary_toolkit_preload', 10, 2 );



function legendary_toolkit_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form action="' . esc_url( home_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    <div class="d-block mb-3">' . __( "To view this protected post, enter the password below:", "legendary-toolkit" ) . '</div>
    <div class="form-group form-inline"><label for="' . $label . '" class="mr-2">' . __( "Password:", "legendary-toolkit" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" class="form-control mr-2" /> <input type="submit" name="Submit" value="' . esc_attr__( "Submit", "legendary-toolkit" ) . '" class="btn btn-primary"/></div>
    </form>';
    return $o;
}
add_filter( 'the_password_form', 'legendary_toolkit_password_form' );

function add_html5_support() {
    add_theme_support( 'html5', [ 'script', 'style' ] );
}
add_action('after_setup_theme', 'add_html5_support');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load plugin compatibility file.
 */
require get_template_directory() . '/inc/plugin-compatibility/plugin-compatibility.php';

/**
 * Load custom WordPress nav walker.
 */
if ( ! class_exists( 'wp_bootstrap_navwalker' )) {
    require_once(get_template_directory() . '/inc/wp_bootstrap_navwalker.php');
}

/**
 * Load Theme Options
 */

require get_template_directory() . '/inc/options.php';




// skip cropping
function logo_size_change(){
	remove_theme_support( 'custom-logo' );
	add_theme_support( 'custom-logo', array(
	    'height'      => 100,
	    'width'       => 400,
	    'flex-height' => true,
	    'flex-width'  => true,
	) );
}
add_action( 'after_setup_theme', 'logo_size_change', 11 );

add_shortcode( 'page_title', 'legendary_toolkit_page_title' );
function legendary_toolkit_page_title() {
    return get_the_title();
}

add_shortcode('breadcrumbs', 'legendary_toolkit_breadcrumbs');
function legendary_toolkit_breadcrumbs($atts) {
    $args = shortcode_atts( array(
        'sep' => '&raquo;', // string
        'show_on_home' => 0, // intBool
        'home_text' => 'Home', // string
        'show_current' => 1, // intBool
        'before_current' => '<span class="current">', // string (tag)
        'after_current' => '</span>' // string (close tag)
    ), $atts );
    $showOnHome = $args['show_on_home']; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = $args['sep']; // delimiter between crumbs
    $home = $args['home_text']; // text for the 'Home' link
    $showCurrent = $args['show_current']; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = $args['before_current']; // tag before the current crumb
    $after = $args['after_current']; // tag after the current crumb
    $output = '';

    global $post;
    $homeLink = get_bloginfo('url');
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
        }
    } else {
        $output .= '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $output .= get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
            }
            $output .= $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
        } elseif (is_search()) {
            $output .= $before . 'Search results for "' . get_search_query() . '"' . $after;
        } elseif (is_day()) {
            $output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            $output .= '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            $output .= $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            $output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            $output .= $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            $output .= $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                $output .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) {
                    $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                $output .= $cats;
                if ($showCurrent == 1) {
                    $output .= $before . get_the_title() . $after;
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            $output .= $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            $output .= get_category_parents($cat, true, ' ' . $delimiter . ' ');
            $output .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if ($showCurrent == 1) {
                $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                $output .= $before . get_the_title() . $after;
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                $output .= $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) {
                    $output .= ' ' . $delimiter . ' ';
                }
            }
            if ($showCurrent == 1) {
                $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_tag()) {
            $output .= $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            $output .= $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif (is_404()) {
            $output .= $before . 'Error 404' . $after;
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                $output .= ' (';
            }
            $output .= __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                $output .= ')';
            }
        }
        $output .= '</div>';
    }
    return $output;
}

// Disable Comments by Default
add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);


// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

// Remove unecessary base menu items for non-admins
add_action('admin_menu', function () {
    if(!current_user_can('administrator')){
        remove_menu_page('themes.php');
        remove_menu_page('vc-general');
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit.php?post_type=acf-field-group');
        remove_menu_page('wpseo_dashboard');
        remove_menu_page('cptui_main_menu');
        remove_menu_page('edit.php?post_type=seopages');
        remove_menu_page('formidable');
    }
});

/**
 * Control excerpt length by theme options
 */

add_filter( 'excerpt_length', function($length) {
    $custom_limit = legendary_toolkit_get_theme_option('excerpt_length_limit');
    if ($custom_limit) {
        return $custom_limit;
    } else {
        return 2;
    }
}, PHP_INT_MAX );

/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */

add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );
function wpdocs_excerpt_more( $more ) {
    if ( ! is_single() ) {
        $more = sprintf( '<a class="read-more" href="%1$s">%2$s</a>',
            get_permalink( get_the_ID() ),
            __( '<br/>Continue reading <span class="meta-nav">&rarr;</span>', 'wp-bootstrap-starter' )
        );
    }
    return $more;
}

/**
 * Custom WooCommerce Cart in Menu
 */

add_filter( 'wp_nav_menu_items', 'legendary_cart_in_menu', 10, 2 );

function legendary_cart_in_menu ( $items, $args ) {
    if (!class_exists( 'woocommerce' )) {
        return $items;
    }
    $show_cart_in_menu = legendary_toolkit_get_theme_option('show_cart_in_menu');
    if (!$show_cart_in_menu) {
        return $items;
    }
    global $woocommerce;
    $cart_items = $woocommerce->cart->get_cart();
    $item_display = '';
    if ($cart_items) {
        foreach($cart_items as $item => $values) { 
            $_product_id = $values['data']->get_id();
            $_product =  wc_get_product($_product_id); 
            $link = $_product->get_permalink($_product_id);
            $image = $_product->get_image('woocommerce_thumbnail', ['class' => 'menu-cart-image']);
            $item_display .= '<tr><th><a href="'. $link .'">'. $image . $_product->get_title() . '</a></th><td align="right">'. $values['quantity'] .'</td>';
        }
    }

    $items_count = WC()->cart->get_cart_contents_count();
    $count_badge = '
        <span style="top:calc(50% - 15px);" class="position-absolute badge rounded-pill bg-danger">
            '. $items_count .'
            <span class="d-none">Items in Cart</span>
        </span>
    ';
    if ($args->theme_location == 'primary') {
        $items .= '
        <li class="menu-item nav-item menu-item-has-children dropdown menu-cart">
            <a class="tester" id="menu-item-woocommerce-cart" href="' . wc_get_cart_url() . '" title="View your shopping cart"><i class="fas fa-shopping-cart"></i>'. $count_badge .'</a>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="menu-item-woocommerce-cart">
                <li class="menu-item nav-item">
                    <div class="dropdown-item">
                        <table border="1" cellpadding="40">
                            <thead>
                                <tr>
                                    <th style="min-width: 400px;">Product</th>
                                    <th>Qty.</th>
                                </tr>
                            </thead>
                            <tbody>
                            ' . $item_display . '
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        Total:
                                    </th>
                                    <td align="right">
                                ' . WC()->cart->get_cart_total() . '
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div id="woo-cart-navigation-container" style="text-align:center;">
                            <a class="btn btn-primary cart-navigation-btn" href="'.get_permalink( wc_get_page_id( 'cart' ) ).'">View Cart</a>
                            <a class="btn btn-primary cart-navigation-btn" href="'.wc_get_checkout_url().'" >Checkout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
        ';
    }
    return $items;
}

/**
 * Is woocommerce page
 *
 * @param   string $page        ( 'cart' | 'checkout' | 'account' | 'endpoint' )
 * @param   string $endpoint    If $page == 'endpoint' and you want to check for specific endpoint
 * @return  boolean
 */
if( ! function_exists('is_woocommerce_page') ){
    function is_woocommerce_page( $page = '', $endpoint = '' ){
        if ( ! class_exists( 'WooCommerce' ) ) {
            return false;
        }
        if( ! $page ){
            return ( is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url() );
        }

        switch ( $page ) {
            case 'cart':
                return is_cart();
                break;

            case 'checkout':
                return is_checkout();
                break;

            case 'account':
                return is_account_page();
                break;

            case 'endpoint':
                if( $endpoint ) {
                    return is_wc_endpoint_url( $endpoint );
                }

                return is_wc_endpoint_url();
                break;
        }

        return false;
    }
}

/**
 * Custom function to simplify addition of admin columns
 */

function add_admin_column($column_title, $post_type, $field_id, $index = 0){

    // Callback function to retrieve field value
    $cb = function($post_id) use ($field_id, $index) {
        $value = ($index) ? get_post_meta( $post_id, $field_id, true )[$index] : get_post_meta( $post_id, $field_id, true );
        echo $value;
    };

    // Column Header
    add_filter( 'manage_' . $post_type . '_posts_columns', function($columns) use ($field_id, $column_title) {
        $columns[$field_id] = $column_title;
        return $columns;
    } );

    // Column Content
    add_action( 'manage_' . $post_type . '_posts_custom_column' , function( $column, $post_id ) use ($column_title, $cb, $field_id) {
        if($field_id === $column){
            $cb($post_id);
        }
    }, 10, 2 );

    // Make Column Sortable
    add_filter('manage_edit-'. $post_type .'_sortable_columns', function( $columns ) use ($field_id, $column_title) {
            $columns[$field_id] = $column_title;
            return $columns;
        }
    );
}

/**
 * Custom Widgets to use a template library
 */

function create_ll_widgets_post_type() {
    $supports = array(
        'title',
        'editor',
    );
    $labels = array(
        'name' => _x('Widgets', 'plural'),
        'singular_name' => _x('Widget', 'singular'),
        'menu_name' => _x('Widgets', 'admin menu'),
        'name_admin_bar' => _x('Widgets', 'admin bar'),
        'add_new' => _x('Add New', 'add new'),
        'add_new_item' => __('Add New Widget'),
        'new_item' => __('New Widget'),
        'edit_item' => __('Edit Widget'),
        'view_item' => __('View Widget'),
        'all_items' => __('All Widgets'),
        'search_items' => __('Search Widgets'),
        'not_found' => __('No widgets found.'),
    );
    $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 100,
        'query_var' => false,
        'rewrite' => array('slug' => 'll_widgets'),
        'has_archive' => false,
        'hierarchical' => false,
        'menu_icon' => 'dashicons-welcome-widgets-menus'
    );
    register_post_type('ll_widgets', $args);
}
add_action('init', 'create_ll_widgets_post_type');

/**
 * Custom widget shortcode [custom_widget id=X]
 */
function render_widget_markup( $atts = '' ) {
    $params = shortcode_atts( array(
        'id' => '',
    ), $atts );

    if (!$params['id']) {
        return;
    }

    $widget_post = get_post($params['id']);
    if (!$widget_post) {
        return;
    }
    $content = $widget_post->post_content;
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

    return $content;
}
add_shortcode('custom_widget', 'render_widget_markup');

function register_custom_page_options_meta_box() {
    add_meta_box( 'll-page-options', __( 'Page Options', 'legendary-toolkit' ), 'render_custom_page_options_form', 'page' );
}
add_action( 'add_meta_boxes', 'register_custom_page_options_meta_box' );

function render_custom_page_options_form ( $post ) {
    require_once get_template_directory() . '/inc/page-options.php';
}

function save_custom_page_options_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'll_page_sidebar',
        'll_sidebar_position',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post', 'save_custom_page_options_meta_box' );


function toolkit_get_view_type() {
    global $wp_query;
    $loop = 'notfound';

    if ( $wp_query->is_page ) {
        $loop = is_front_page() ? 'front' : 'page';
        $loop = (is_woocommerce_page()) ? 'woocommerce-page' : $loop;
    } elseif ( $wp_query->is_home ) {
        $loop = 'archive';
    } elseif ( $wp_query->is_single ) {
        $loop = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
    } elseif ( $wp_query->is_category ) {
        $loop = 'archive';
    } elseif ( $wp_query->is_tag ) {
        $loop = 'archive';
    } elseif ( $wp_query->is_tax ) {
        $loop = 'archive';
    } elseif ( $wp_query->is_archive ) {
        //$loop = 'archive';
        //$loop = (is_shop()) ? 'shop' : $loop;
    } elseif ( $wp_query->is_search ) {
        $loop = 'search';
    } elseif ( $wp_query->is_404 ) {
        $loop = 'notfound';
    }

    return $loop;
}

function toolkit_get_sidebar_selection() {
    $view_type = toolkit_get_view_type();

    $option_page_sidebar = '';
    $option_page_sidebar_position = '';
    $option_post_sidebar = '';
    $option_post_sidebar_position = '';
    $option_archive_sidebar = '';
    $option_archive_sidebar_position = '';

    // Get page sidebar from theme settings
    if (!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php')) {
        $option_page_sidebar = legendary_toolkit_get_theme_option('page_sidebar');
        $option_page_sidebar_position = legendary_toolkit_get_theme_option('page_sidebar_position');
    }
    // Check for page options sidebar override
    if ($view_type == 'page') {
        $option_override_page_sidebar = esc_attr( get_post_meta( get_the_ID(), 'll_page_sidebar', true ) );
        
        // if 
        if($option_override_page_sidebar == 'sidebar_off'){
            return false;
        }
        $option_override_page_sidebar_position = esc_attr( get_post_meta( get_the_ID(), 'll_sidebar_position', true ) );
        if ($option_override_page_sidebar && $option_override_page_sidebar_position) {
            $option_page_sidebar = $option_override_page_sidebar;
            $option_page_sidebar_position = $option_override_page_sidebar_position;
        }
    }

    // Get post sidebar from theme settings
    $option_post_sidebar = legendary_toolkit_get_theme_option('post_sidebar');
    $option_post_sidebar_position = legendary_toolkit_get_theme_option('post_sidebar_position');

    // Get archive sidebar from theme settings
    $option_archive_sidebar = legendary_toolkit_get_theme_option('archives_sidebar');
    $option_archive_sidebar_position = legendary_toolkit_get_theme_option('archives_sidebar_position');

    
    $page_sidebar = ['id' => $option_page_sidebar, 'position' => $option_page_sidebar_position];
    $single_sidebar = ['id' => $option_post_sidebar, 'position' => $option_post_sidebar_position];
    $archive_sidebar = ['id' => $option_archive_sidebar, 'position' => $option_archive_sidebar_position];
    
    $sidebars = array(
        'page'  => $page_sidebar,
        'single' => $single_sidebar,
        'archive' => $archive_sidebar,
    );

    $sidebar = (array_key_exists($view_type, $sidebars)) ? $sidebars[$view_type] : false;

    if (!$sidebar || empty($sidebar["id"])) {
        return false;
    }
    
    return $sidebar;
}

function toolkit_get_primary_column_classes() {
    $sidebar = toolkit_get_sidebar_selection();
    $primary_order_class = 'order-md-1';
    $primary_column_class = 'col-md-12';
    $primary_offset_class = '';
    if ($sidebar) {
        $sidebar_position = $sidebar['position'];
        $primary_column_class = 'col-md-8 col-lg-7';
        if ($sidebar_position == 'left') {
            $primary_order_class = 'order-md-2';
        }
        else {
            $primary_offset_class = 'offset-lg-2';
        }
    }
    if (is_page_template('page-full-width.php') && $sidebar) {
        $primary_column_class = 'col-md-8';
        $primary_offset_class = '';
    }
    return "$primary_column_class $primary_order_class $primary_offset_class";
}

function toolkit_get_sidebar_column_classes() {
    $sidebar = toolkit_get_sidebar_selection();
    if (!$sidebar) {
        return false;
    }
    $sidebar_position = $sidebar['position'];
    $sidebar_column_class = 'col-md-4 col-lg-3';
    $sidebar_order_class = 'order-md-2';
    if ($sidebar_position == 'left') {
        $sidebar_order_class = 'order-md-1';
    }
    if (is_page_template('page-full-width.php') && $sidebar) {
        $sidebar_column_class = 'col-md-4';
    }
    return "$sidebar_column_class $sidebar_order_class";
}

add_action( 'wp', 'enable_gdpr_compliance' );
function enable_gdpr_compliance(){
    if(legendary_toolkit_get_theme_option('enable_gdpr_compliance')){

        // include the script
        wp_enqueue_script( 'enable_gdpr_compliance', get_template_directory_uri().'/inc/assets/js/gdpr.js', '', '', false );

    }

}