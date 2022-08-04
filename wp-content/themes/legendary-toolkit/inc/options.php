<?php
/**
 * Create A Simple Theme Options Panel
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'Legendary_Toolkit_Theme_Options' ) ) {

	class Legendary_Toolkit_Theme_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end

			if ( is_admin() ) {
                add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' ); 
                add_action( 'admin_enqueue_scripts', array( 'Legendary_Toolkit_Theme_Options' , 'legendary_toolkit_options_enqueue_scripts' ) );
				add_action( 'admin_menu', array( 'Legendary_Toolkit_Theme_Options', 'add_admin_menu' ) );
				add_action( 'admin_init', array( 'Legendary_Toolkit_Theme_Options', 'register_settings' ) );
			}
            add_action( 'admin_bar_menu', array( 'Legendary_Toolkit_Theme_Options', 'add_toolbar_items' ), 50, 2);
            add_action( 'wp_before_admin_bar_render', array( 'Legendary_Toolkit_Theme_Options', 'remove_customize') ); 
		}

        public static function legendary_toolkit_options_enqueue_scripts() {
            wp_register_script( 'legendary_toolkit_uploader', get_template_directory_uri() .'/inc/assets/js/uploader.js', array('jquery','media-upload','thickbox') );
            if ( 'toplevel_page_theme-settings' == get_current_screen()->id ) {
                wp_enqueue_script('legendary_toolkit_uploader');
                wp_enqueue_style( 'wp-color-picker' ); 
                wp_enqueue_style( 'legendary_toolkit_admin_styles', get_template_directory_uri() . '/inc/assets/css/admin-styles.css' ); 
                wp_enqueue_script( 'legendary_toolkit_color_picker', get_template_directory_uri() . '/inc/assets/js/color-picker.js', array( 'wp-color-picker' ), false, true ); 
                wp_enqueue_script( 'legendary_toolkit_google_font_selector', get_template_directory_uri() . '/inc/assets/js/google-font-selector.js'); 
                wp_enqueue_script( 'legendary_toolkit_admin_scripts', get_template_directory_uri() . '/inc/assets/js/admin-scripts.js', array('jquery'), false, true); 
            }
            wp_enqueue_editor();
        }

		/**
		 * Returns all theme options
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_options() {
			return get_option( 'theme_options' );
		}

		/**
		 * Returns single theme option
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_option( $id ) {
			$options = self::get_theme_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			add_menu_page(
				esc_html__( 'Theme Settings', 'legendary-toolkit' ),
				esc_html__( 'Theme Settings', 'legendary-toolkit' ),
				'manage_options',
				'theme-settings',
				array( 'Legendary_Toolkit_Theme_Options', 'create_admin_page' )
			);
		}
        public static function add_toolbar_items( $admin_bar ) {
            $admin_bar->add_menu( array(
                'id'    => 'legendary-toolkit-settings',
                'title' => '<span class="ab-icon dashicons dashicons-admin-generic"></span>' . 'Theme Settings',
                'href'  => admin_url('admin.php?page=theme-settings'),
                'meta'  => array(
                    'title' => __('Theme Settings'),            
                ),
            ));
        }
        public static function remove_customize() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('customize');
            $wp_admin_bar->remove_menu('comments');
        }
		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'theme_options', 'theme_options', array( 'Legendary_Toolkit_Theme_Options', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Logo
				if ( ! empty( $options['logo'] ) ) {
					$options['logo'] = sanitize_text_field( $options['logo'] );
				} else {
					unset( $options['logo'] ); // Remove from options if empty
				}

                // ============
                // Examples
                // ============
				// Checkbox
				// if ( ! empty( $options['checkbox_example'] ) ) {
				// 	$options['checkbox_example'] = 'on';
				// } else {
				// 	unset( $options['checkbox_example'] ); // Remove from options if not checked
				// }

				// // Input
				// if ( ! empty( $options['input_example'] ) ) {
				// 	$options['input_example'] = sanitize_text_field( $options['input_example'] );
				// } else {
				// 	unset( $options['input_example'] ); // Remove from options if empty
				// }

				// // Select
				// if ( ! empty( $options['select_example'] ) ) {
				// 	$options['select_example'] = sanitize_text_field( $options['select_example'] );
				// }

			}

			// Return sanitized options
			return $options;

		}

        public static function get_google_fonts() {
            $google_api_key = "AIzaSyCzOdFDkLRrWOePEGIribIpUV3BM2SuO9s";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/webfonts/v1/webfonts?key=" . $google_api_key. "&sort=popularity");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
            $fonts_list = json_decode(curl_exec($ch), true);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($http_code != 200) 
                exit('Error : Failed to get Google Fonts list');
    
            // echo out list of fonts
            
            $google_fonts = $fonts_list["items"];
            return $google_fonts;
        }

        public static function typography_field($id, $has_size = true, $has_transform = false) {
            
            ob_start();
            ?>
            <table data-type="<?=$id;?>" class="inner-form-table">
                <tr valign="top">
                    <td>
                        <div class="legendary-toolkit-input-group">
                            <?php $value = self::get_theme_option( $id . '_font_family' );?>
                            <label class="prefix" for="theme_options[<?=$id;?>_font_family]">Family</label>
                            <select data-type="<?=$id;?>" class="font-selector" name="theme_options[<?=$id;?>_font_family]">
                                <option>Select Font Family</option>
                                <?php foreach (self::get_google_fonts() as $i => $font) : ?>
                                    <option value="<?=$font['family'];?>" <?php selected( $value, $font['family'], true );?>>
                                        <?=$font['family'];?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="legendary-toolkit-input-group">
                            <?php $value = self::get_theme_option( $id . '_font_weight' );?>
                            <label class="prefix" for="theme_options[<?=$id;?>_font_weight]">Weight</label>
                            <select data-type="<?=$id;?>" data-selected="<?=esc_attr( $value );?>" name="theme_options[<?=$id;?>_font_weight]" class="font-selector-weight"></select>
                        </div>
                    </td>
                    <td>
                        <?php $value = self::get_theme_option( $id . '_font_color' );?>
                        <input class="color-field" type="text" name="theme_options[<?=$id;?>_font_color]" value="<?=esc_attr( $value );?>">
                    </td>
                    <td>
                        <?php if ($has_size) : ?>
                            <div class="legendary-toolkit-input-group">
                                <?php $value = self::get_theme_option( $id . '_font_size' );?>
                                <label class="prefix" for="theme_options[<?=$id;?>_font_size]">Size</label>
                                <input type="number" name="theme_options[<?=$id;?>_font_size]" value="<?=esc_attr($value);?>">
                                <label class="suffix" for="theme_options[<?=$id;?>_font_size]">px</label>
                            </div>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if ($has_transform) : ?>
                            <div class="legendary-toolkit-input-group">
                                <?php $value = self::get_theme_option( $id . '_font_transform' );?>
                                <label class="prefix" for="theme_options[<?=$id;?>_font_transform]">Transform</label>
                                <select name="theme_options[<?=$id;?>_font_transform]">
                                    <option value="none">None</option>
                                    <?php
                                    $options = array(
                                        'uppercase' => 'Uppercase',
                                        'lowercase' => 'Lowercase',
                                    );
                                    foreach ( $options as $option_id => $label ) : ?>
                                        <option value="<?=esc_attr( $option_id );?>" <?php selected( $value, $option_id, true );?>>
                                            <?=strip_tags( $label );?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        <?php endif;?>
                    </td>
                </tr>
            </table>
            
            <?php $files = self::get_theme_option( 'font_files' );?>
            <div class="files-field" style="display:none;" data-type="<?=$id;?>"></div>
            <?php
            return ob_get_clean();
        }

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { 
            ?>
            <div class="wrap">
                <h2></h2>
            </div>
			<div class="theme-settings-wrap">
                <div id="options_header">
                    <div id="options_banner">
                        <h1><?php esc_html_e( 'Legendary Toolkit Options', 'legendary-toolkit' );?></h1>
                        <div id="save_container">
                            <div id="toast_container">
                                <div class="save-toast save-loading hidden"></div>
                                <div class="save-toast save-success hidden"></div>
                                <div class="save-toast save-error hidden"></div>
                            </div>
                            <?php submit_button( __( 'Save Changes', 'legendary-toolkit' ), 'primary hidden', 'submit', true, array( 'form' => 'legendary_toolkit_form' ) );?>
                        </div>
                    </div>
                    <!-- <div class="updated notice inline hidden" style="margin:0px;">
                        <p>Setting Saved!</p>
                    </div>
                    <div class="error notice inline hidden" style="margin:0px;">
                        <p>Error Saving Settings.</p>
                    </div> -->
                    <!-- Tab links -->
                    <div class="tab">
                        <button class="tablinks" onclick="open_settings_tab(event, 'legendary_toolkit_general')" id="legendary_toolkit_general_tab">General</button>
                        <button class="tablinks" onclick="open_settings_tab(event, 'legendary_toolkit_header')">Header</button>
                        <button class="tablinks" onclick="open_settings_tab(event, 'legendary_toolkit_menu')">Menu</button>
                        <button class="tablinks" onclick="open_settings_tab(event, 'legendary_toolkit_footer')">Footer</button>
                        <button class="tablinks" onclick="open_settings_tab(event, 'legendary_toolkit_typography')">Typography</button>
                        <button class="tablinks" onclick="open_settings_tab(event, 'legendary_toolkit_blog')">Blog</button>
                        <button class="tablinks" onclick="open_settings_tab(event, 'legendary_toolkit_pages_posts')">Pages &amp; Posts</button>
                    </div>
                </div>
				<form method="post" action="options.php" id="legendary_toolkit_form">

					<?php settings_fields( 'theme_options' );?>
                    <!-- Tab content -->
                    <div id="legendary_toolkit_general" class="tabcontent">
                        <h3>General</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Logo', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'logo' ); ?>
                                    <input type="hidden" name="theme_options[logo]" id="logo" value="<?php echo $value; ?>" />
                                    <div id="logo_preview" class="logo btn_logo toolkit-media-upload" style="background-image:url(<?php echo wp_get_attachment_image_url($value, 'medium'); ?>)" ></div>
                                    <button id="btn_logo" data-id="logo" class="button default btn_logo btn-upload">Select Logo</button>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Favicon', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'favicon' ); ?>
                                    <input type="hidden" name="theme_options[favicon]" id="favicon" value="<?php echo $value; ?>" />
                                    <div id="favicon_preview" class="favicon btn_favicon toolkit-media-upload" style="background-image:url(<?php echo wp_get_attachment_image_url($value, 'medium'); ?>)" ></div>
                                    <button id="btn_favicon" data-id="favicon" class="button default btn_favicon btn-upload">Select Logo</button>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Primary Color', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'primary_color' );?>
                                    <input class="color-field" type="text" name="theme_options[primary_color]" value="<?=esc_attr( $value );?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Secondary Color', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'secondary_color' );?>
                                    <input class="color-field" type="text" name="theme_options[secondary_color]" value="<?=esc_attr( $value );?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Page Container Width', 'legendary-toolkit' );?></th>
                                <td>
                                    <div class="legendary-toolkit-input-group">
                                        <?php $value = self::get_theme_option( 'page_container_width' );?>
                                        <input type="number" name="theme_options[page_container_width]" value="<?=(esc_attr($value)) ? esc_attr($value) : '1320';?>">
                                        <label class="suffix" for="theme_options[page_container_width]">px</label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Enable GDPR Compliance?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'enable_gdpr_compliance' );?>
                                    <label><input type="checkbox" name="theme_options[enable_gdpr_compliance]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="legendary_toolkit_header" class="tabcontent">
                        <h3>Header</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Enable Transparent Header?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'transparent_header' );?>
                                    <label><input type="checkbox" name="theme_options[transparent_header]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                            <?php
                                $transparent_background = self::get_theme_option( 'transparent_header' );
                                $hidden = ( $transparent_background ) ? 'hidden' : '';
                            ?>
                            <tr valign="top" id="header_background_row" class="<?php echo $hidden;?>">
                                <th scope="row"><?php esc_html_e( 'Header Background', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'header_background' );?>
                                    <input class="color-field" type="text" name="theme_options[header_background]" value="<?=esc_attr( $value );?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Scrolling Header Background', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'scrolling_header_background' );?>
                                    <input class="color-field" type="text" name="theme_options[scrolling_header_background]" value="<?=esc_attr( $value );?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Enable Sticky Header?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'sticky_header' );?>
                                    <label><input type="checkbox" name="theme_options[sticky_header]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Logo Height', 'legendary-toolkit' );?></th>
                                <td>
                                    <div class="legendary-toolkit-input-group">
                                        <?php $value = self::get_theme_option( 'logo_height' );?>
                                        <input type="number" name="theme_options[logo_height]" value="<?=(esc_attr($value)) ? esc_attr($value) : '100';?>">
                                        <label class="suffix" for="theme_options[logo_height]">px</label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Scrolling Header Height', 'legendary-toolkit' );?></th>
                                <td>
                                    <div class="legendary-toolkit-input-group">
                                        <?php $value = self::get_theme_option( 'scrolling_header_height' );?>
                                        <input type="number" name="theme_options[scrolling_header_height]" value="<?=esc_attr($value);?>">
                                        <label class="suffix" for="theme_options[scrolling_header_height]">px</label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <h3>Top Bar</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Enable Top Bar?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'enable_top_bar' );?>
                                    <label><input type="checkbox" name="theme_options[enable_top_bar]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Top Bar Background', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'top_bar_background' );?>
                                    <input class="color-field" type="text" name="theme_options[top_bar_background]" value="<?=esc_attr( $value );?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Top Bar Content', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'top_bar_content' );?>
                                    <?php echo wp_editor( $value, 'top_bar_content', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[top_bar_content]') );?>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <h3>Page Header</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Enable Page Title?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'page_title' );?>
                                    <label><input type="checkbox" name="theme_options[page_title]" <?php checked( $value, 'on' );?>> <?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                            <?php 
                            $page_title = self::get_theme_option( 'page_title' );
                            $hidden = ( !$page_title ) ? 'hidden' : '';?>
                            <tr valign="top" id="page_title_content_row" class="<?=$hidden;?>">
                                <th scope="row"><?php esc_html_e( 'Page Title Content', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php 
                                        $value = self::get_theme_option( 'page_title_content' );
                                        if (!$value) {
                                            $value = "<h1>[page_title]</h1><p>[breadcrumbs]</p>";
                                        }
                                    ?>
                                    <?php echo wp_editor( $value, 'page_title_content', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[page_title_content]') );?>
                                    <label for="theme_options[mobile_menu_width]"><small><strong>Default:</strong><code>&lt;h1&gt;[page_title]&lt;/h1&gt;&lt;p&gt;[breadcrumbs]&lt;/p&gt;</code></small></label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="legendary_toolkit_menu" class="tabcontent">
                        <h3>Menu</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Show Cart in Menu?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'show_cart_in_menu' );?>
                                    <label><input type="checkbox" name="theme_options[show_cart_in_menu]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Menu Items', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('menu_items', true, true);?></td>
                            </tr>
                        </table>
                        <hr>
                        <h3>Mobile Menu</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Mobile Menu Position', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'mobile_menu_position' );?>
                                    <select name="theme_options[mobile_menu_position]">
                                        <?php
                                        $options = array(
                                            'right' => esc_html__( 'Right', 'legendary-toolkit' ),
                                            'left' => esc_html__( 'Left', 'legendary-toolkit' ),
                                        );
                                        foreach ( $options as $id => $label ) { ?>
                                            <option value="<?=esc_attr( $id );?>" <?php selected( $value, $id, true );?>>
                                                <?=strip_tags( $label );?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Mobile Menu Width', 'legendary-toolkit' );?></th>
                                <td>
                                    <div class="legendary-toolkit-input-group">
                                        <?php $value = self::get_theme_option( 'mobile_menu_width' );?>
                                        <input type="number" name="theme_options[mobile_menu_width]" value="<?=(esc_attr($value)) ? esc_attr($value) : '300';?>">
                                        <label class="suffix" for="theme_options[mobile_menu_width]">px</label>
                                    </div>
                                    <label for="theme_options[mobile_menu_width]"><small>Use "0" for 100% width</small></label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Mobile Menu Breakpoint', 'legendary-toolkit' );?></th>
                                <td>
                                    <div class="legendary-toolkit-input-group">
                                        <?php $value = self::get_theme_option( 'mobile_menu_breakpoint' );?>
                                        <input type="number" name="theme_options[mobile_menu_breakpoint]" value="<?=(esc_attr($value)) ? esc_attr($value) : '1200';?>">
                                        <label class="suffix" for="theme_options[mobile_menu_breakpoint]">px</label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" id="" class="">
                                <th scope="row"><?php esc_html_e( 'Mobile Menu Top Content', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'mobile_menu_top_content' );?>
                                    <?php echo wp_editor( $value, 'mobile_menu_top_content', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[mobile_menu_top_content]') );?>
                                </td>
                            </tr>
                            <tr valign="top" id="" class="">
                                <th scope="row"><?php esc_html_e( 'Mobile Menu Bottom Content', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'mobile_menu_bottom_content' );?>
                                    <?php echo wp_editor( $value, 'mobile_menu_bottom_content', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[mobile_menu_bottom_content]') );?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="legendary_toolkit_footer" class="tabcontent">
                        <h3>Footer</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Footer Background', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'footer_background' );?>
                                    <input class="color-field" type="text" name="theme_options[footer_background]" value="<?=esc_attr( $value );?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Copyright Background', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'copyright_background' );?>
                                    <input class="color-field" type="text" name="theme_options[copyright_background]" value="<?=esc_attr( $value );?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Full Width Footer?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'full_width_footer' );?>
                                    <label><input type="checkbox" name="theme_options[full_width_footer]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Footer Columns', 'legendary-toolkit' );?></th>
                                <td style="display:flex; align-items: center; ">
                                    <?php $value = self::get_theme_option( 'footer_columns' );?>
                                    <input type="range" min="0" max="4" step="1" name="theme_options[footer_columns]" oninput="this.nextElementSibling.value = this.value" value="<?=(esc_attr( $value )) ? esc_attr($value) : 0;?>">
                                    <output style="margin-left:10px;"><?=(esc_attr( $value )) ? esc_attr( $value ) : 0;?></output>
                                </td>
                            </tr>
                            <?php $footer_column_count = self::get_theme_option('footer_columns');?>
                            <?php $hidden = ( $footer_column_count < 1 ) ? 'hidden' : '';?>
                            <tr valign="top" id="footer_column_row_1" class="<?=$hidden;?>">
                                <th scope="row"><?php esc_html_e( 'Footer Column 1', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'footer_column_1' );?>
                                    <?php echo wp_editor( $value, 'footer_column_1', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[footer_column_1]') );?>
                                </td>
                            </tr>
                            <?php $hidden = ( $footer_column_count < 2 ) ? 'hidden' : '';?>
                            <tr valign="top" id="footer_column_row_2" class="<?=$hidden;?>">
                                <th scope="row"><?php esc_html_e( 'Footer Column 2', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'footer_column_2' );?>
                                    <?php echo wp_editor( $value, 'footer_column_2', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[footer_column_2]') );?>
                                </td>
                            </tr>
                            <?php $hidden = ( $footer_column_count < 3 ) ? 'hidden' : '';?>
                            <tr valign="top" id="footer_column_row_3" class="<?=$hidden;?>">
                                <th scope="row"><?php esc_html_e( 'Footer Column 3', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'footer_column_3' );?>
                                    <?php echo wp_editor( $value, 'footer_column_3', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[footer_column_3]') );?>
                                </td>
                            </tr>
                            <?php $hidden = ( $footer_column_count < 4 ) ? 'hidden' : '';?>
                            <tr valign="top" id="footer_column_row_4" class="<?=$hidden;?>">
                                <th scope="row"><?php esc_html_e( 'Footer Column 4', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'footer_column_4' );?>
                                    <?php echo wp_editor( $value, 'footer_column_4', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[footer_column_4]') );?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Footer Bottom', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'footer_bottom_content' );?>
                                    <?php echo wp_editor( $value, 'footer_bottom_content', $settings = array('textarea_rows'=> '10', 'textarea_name' => 'theme_options[footer_bottom_content]') );?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="legendary_toolkit_typography" class="tabcontent">
                        <h3>Typography</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Body', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('body', true, true);?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'All Headings', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('all', false, true);?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Heading 1', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('h1', true, true);?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Heading 2', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('h2', true, true);?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Heading 3', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('h3', true, true);?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Heading 4', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('h4', true, true);?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Heading 5', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('h5', true, true);?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Heading 6', 'legendary-toolkit' );?></th>
                                <td><?php echo self::typography_field('h6', true, true);?></td>
                            </tr>
                        </table>
                    </div>
                    <div id="legendary_toolkit_blog" class="tabcontent">
                        <h3>Blog</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Excerpt Length', 'legendary-toolkit' );?></th>
                                <td>
                                    <div class="legendary-toolkit-input-group">
                                        <?php $value = self::get_theme_option( 'excerpt_length_limit' );?>
                                        <input type="number" name="theme_options[excerpt_length_limit]" value="<?=(esc_attr($value)) ? esc_attr($value) : '40';?>">
                                        <label class="suffix" for="theme_options[excerpt_length_limit]">Words</label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Show Sidebar on Single Posts?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'show_sidebar_single' );?>
                                    <label><input type="checkbox" name="theme_options[show_sidebar_single]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Show Sidebar on Archive Pages?', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php $value = self::get_theme_option( 'show_sidebar_archive' );?>
                                    <label><input type="checkbox" name="theme_options[show_sidebar_archive]" <?php checked( $value, 'on' );?>><?php esc_html_e( 'Enable', 'legendary-toolkit' );?></label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="legendary_toolkit_pages_posts" class="tabcontent">
                        <h3>Pages</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Page Sidebar', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php 
                                        $value = self::get_theme_option( 'page_sidebar' );
                                        
                                        // Get widgets for sidebar

                                        function get_sidebar_options() {
                                            $sidebars = [];
                                            $args = array(
                                                'post_type' => 'll_widgets',
                                            );
                                            $q_sidebars = new wp_query($args);
                                            if (!$q_sidebars->have_posts()) {
                                                return false;
                                            }
                                            while ($q_sidebars->have_posts()) {
                                                $q_sidebars->the_post();
                                                $id = get_the_id();
                                                $sidebar_name = get_the_title();
                                                $sidebars[] = ['id' => $id, 'name' => $sidebar_name];
                                            }
                                            wp_reset_postdata();
                                            return $sidebars;
                                        }

                                        $selected_sidebar = self::get_theme_option( 'page_sidebar' );
                                        $selected_sidebar_none = (!$selected_sidebar) ? 'selected' : '';

                                        $selected_sidebar_position = self::get_theme_option( 'page_sidebar_position' );
                                        $selected_no_sidebar = (!$selected_sidebar_position) ? 'selected' : '';
                                        $sidebar_position_options = ['left','right'];
                                        if (!get_sidebar_options()) {
                                            echo '<strong>No Sidebars Found</strong></br><a href="/wp-admin/post-new.php?post_type=ll_widgets">Create your first sidebar</a>';
                                        }
                                        else {
                                            echo "<select name='theme_options[page_sidebar]' id='theme_options[page_sidebar]'>";
                                                echo "<option value='0' $selected_sidebar_none>No Sidebar</option>";
                                                foreach (get_sidebar_options() as $i => $sidebar) {
                                                    $id = $sidebar['id'];
                                                    $name = $sidebar['name'];
                                                    $selected = ($selected_sidebar == $id) ? 'selected' : '';
                                                    echo "<option value='$id' $selected>$name</option>";
                                                }
                                            echo "</select>";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Page Sidebar Position', 'legendary-toolkit' );?></th>
                                <td>
                                <?php 
                                    echo "<select name='theme_options[page_sidebar_position]' id='theme_options[page_sidebar_position]'>";
                                        echo "<option value='0' $selected_no_sidebar>No Sidebar</option>";
                                        foreach ($sidebar_position_options as $i => $value) {
                                            $label = ucwords($value);
                                            $selected = ($selected_sidebar_position == $value) ? 'selected' : '';
                                            echo "<option value='$value' $selected>$label</option>";
                                        }
                                    echo "</select>";
                                ?>
                                </td>
                            </tr>
                        </table>
                        <hr/>
                        <h3>Posts</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Post Sidebar', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php 
                                        $value = self::get_theme_option( 'post_sidebar' );
                                        $post_selected_sidebar = self::get_theme_option( 'post_sidebar' );
                                        $post_selected_sidebar_none = (!$post_selected_sidebar) ? 'selected' : '';
                                        $post_selected_sidebar_position = self::get_theme_option( 'post_sidebar_position' );
                                        $post_selected_no_sidebar = (!$post_selected_sidebar_position) ? 'selected' : '';
                                        $post_sidebar_position_options = ['left','right'];
                                        if (!get_sidebar_options()) {
                                            echo '<strong>No Sidebars Found</strong></br><a href="/wp-admin/post-new.php?post_type=ll_widgets">Create your first sidebar</a>';
                                        }
                                        else {
                                            echo "<select name='theme_options[post_sidebar]' id='theme_options[post_sidebar]'>";
                                                echo "<option value='0' $post_selected_sidebar_none>No Sidebar</option>";
                                                foreach (get_sidebar_options() as $i => $sidebar) {
                                                    $id = $sidebar['id'];
                                                    $name = $sidebar['name'];
                                                    $selected = ($post_selected_sidebar == $id) ? 'selected' : '';
                                                    echo "<option value='$id' $selected>$name</option>";
                                                }
                                            echo "</select>";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Post Sidebar Position', 'legendary-toolkit' );?></th>
                                <td>
                                <?php 
                                    echo "<select name='theme_options[post_sidebar_position]' id='theme_options[post_sidebar_position]'>";
                                        // echo "<option value='0' $post_selected_no_sidebar>No Sidebar</option>";
                                        foreach ($post_sidebar_position_options as $i => $value) {
                                            $label = ucwords($value);
                                            $selected = ($post_selected_sidebar_position == $value) ? 'selected' : '';
                                            echo "<option value='$value' $selected>$label</option>";
                                        }
                                    echo "</select>";
                                ?>
                                </td>
                            </tr>
                        </table>
                        <hr/>
                        <h3>Archives</h3>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Archives Sidebar', 'legendary-toolkit' );?></th>
                                <td>
                                    <?php 
                                        $value = self::get_theme_option( 'archives_sidebar' );
                                        $archives_selected_sidebar = self::get_theme_option( 'archives_sidebar' );
                                        $archives_selected_sidebar_none = (!$archives_selected_sidebar) ? 'selected' : '';
                                        $archives_selected_sidebar_position = self::get_theme_option( 'archives_sidebar_position' );
                                        $archives_selected_no_sidebar = (!$archives_selected_sidebar_position) ? 'selected' : '';
                                        $archives_sidebar_position_options = ['left','right'];
                                        if (!get_sidebar_options()) {
                                            echo '<strong>No Sidebars Found</strong></br><a href="/wp-admin/post-new.php?post_type=ll_widgets">Create your first sidebar</a>';
                                        }
                                        else {
                                            echo "<select name='theme_options[archives_sidebar]' id='theme_options[archives_sidebar]'>";
                                                echo "<option value='0' $archives_selected_sidebar_none>No Sidebar</option>";
                                                foreach (get_sidebar_options() as $i => $sidebar) {
                                                    $id = $sidebar['id'];
                                                    $name = $sidebar['name'];
                                                    $selected = ($archives_selected_sidebar == $id) ? 'selected' : '';
                                                    echo "<option value='$id' $selected>$name</option>";
                                                }
                                            echo "</select>";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Archives Sidebar Position', 'legendary-toolkit' );?></th>
                                <td>
                                <?php 
                                    echo "<select name='theme_options[archives_sidebar_position]' id='theme_options[archives_sidebar_position]'>";
                                        // echo "<option value='0' $archives_selected_no_sidebar>No Sidebar</option>";
                                        foreach ($archives_sidebar_position_options as $i => $value) {
                                            $label = ucwords($value);
                                            $selected = ($archives_selected_sidebar_position == $value) ? 'selected' : '';
                                            echo "<option value='$value' $selected>$label</option>";
                                        }
                                    echo "</select>";
                                ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php if (defined('TOOLKIT_DEBUG') && TOOLKIT_DEBUG) : ?>
                        <div id="legendary_toolkit_examples" class="tabcontent">
                            <h3>Option Examples</h3>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Checkbox Example', 'legendary-toolkit' );?></th>
                                    <td>
                                        <?php $value = self::get_theme_option( 'checkbox_example' );?>
                                        <input type="checkbox" name="theme_options[checkbox_example]" <?php checked( $value, 'on' );?>> <label><?php esc_html_e( 'Checkbox example description.', 'legendary-toolkit' );?></label>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Input Example', 'legendary-toolkit' );?></th>
                                    <td>
                                        <?php $value = self::get_theme_option( 'input_example' );?>
                                        <input type="text" name="theme_options[input_example]" value="<?=esc_attr( $value );?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Select Example', 'legendary-toolkit' );?></th>
                                    <td>
                                        <?php $value = self::get_theme_option( 'select_example' );?>
                                        <select name="theme_options[select_example]">
                                            <?php
                                            $options = array(
                                                '1' => esc_html__( 'Option 1', 'legendary-toolkit' ),
                                                '2' => esc_html__( 'Option 2', 'legendary-toolkit' ),
                                                '3' => esc_html__( 'Option 3', 'legendary-toolkit' ),
                                            );
                                            foreach ( $options as $id => $label ) { ?>
                                                <option value="<?=esc_attr( $id );?>" <?php selected( $value, $id, true );?>>
                                                    <?=strip_tags( $label );?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Range Example', 'legendary-toolkit' );?></th>
                                    <td style="display:flex; align-items: center; ">
                                        <?php $value = self::get_theme_option( 'range_example' );?>
                                        <input type="range" min="0" max="4" step="1" name="theme_options[range_example]" oninput="this.nextElementSibling.value = this.value" value="<?=(esc_attr( $value )) ? esc_attr($value) : 4;?>">
                                        <output style="margin-left:10px;"><?=(esc_attr( $value )) ? esc_attr( $value ) : 4;?></output>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endif;?>
				</form>

			</div><!-- .wrap -->
            <?php 
            if (defined('TOOLKIT_DEBUG') && TOOLKIT_DEBUG) {
                // =================
                // Debug Console
                // =================
                echo '<h3>Debug Console</h3>';
                echo '<pre style="height:200px; width: 100%; overflow:scroll; white-space: pre-wrap; resize:vertical">';

                    echo '<hr><strong>get_theme_options()</strong><hr>';
                    print_r(self::get_theme_options());

                    echo '<hr><strong>Available Shortcodes</strong><hr>';
                    print htmlspecialchars( print_r( $GLOBALS['shortcode_tags'], TRUE ) );

                    // echo '<hr><strong>get_google_fonts()</strong><hr>';
                    // print_r(self::get_google_fonts());

                echo '</pre>';
            }

        }

	}
}
new Legendary_Toolkit_Theme_Options();

// Helper function to use in your theme to return a theme option value
function legendary_toolkit_get_theme_option( $id = '' ) {
	return Legendary_Toolkit_Theme_Options::get_theme_option( $id );
}
function legendary_toolkit_get_theme_options() {
    $options = Legendary_Toolkit_Theme_Options::get_theme_options();
    if (is_array($options)) {
        return $options;    
    }
	return [];
}


global $enqueued_scripts;
global $enqueued_styles;

add_action( 'wp_print_scripts', 'legendary_toolkit_list_scripts' );
function legendary_toolkit_list_scripts() {
    global $wp_scripts;
    global $enqueued_scripts;
    $enqueued_scripts = array();
    foreach( $wp_scripts->queue as $handle ) {
        $enqueued_scripts[] = $wp_scripts->registered[$handle]->src;
    }
}

add_action( 'wp_print_styles', 'legendary_toolkit_list_styles' );
function legendary_toolkit_list_styles() {
    global $wp_styles;
    global $enqueued_styles;
    $enqueued_styles = array();
    foreach( $wp_styles->queue as $handle ) {
        $enqueued_styles[] = $wp_styles->registered[$handle]->src;
    }
}

