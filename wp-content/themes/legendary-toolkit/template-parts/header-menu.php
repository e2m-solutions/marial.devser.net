<?php
// $header_behavior_class = (legendary_toolkit_get_theme_option('sticky_header')) ? 'sticky-top' : '';
$header_behavior_class = (legendary_toolkit_get_theme_option('sticky_header')) ? 'sticky_header' : '';
$transparent_class = (legendary_toolkit_get_theme_option('transparent_header')) ? 'is_transparent' : '';
$mobile_menu_width = (legendary_toolkit_get_theme_option('mobile_menu_width')) ? legendary_toolkit_get_theme_option('mobile_menu_width') . 'px' : '100%';
$mobile_menu_breakpoint = (legendary_toolkit_get_theme_option('mobile_menu_breakpoint')) ? legendary_toolkit_get_theme_option('mobile_menu_breakpoint') : 1200;
$mobile_menu_position = (legendary_toolkit_get_theme_option('mobile_menu_position')) ? legendary_toolkit_get_theme_option('mobile_menu_position') : 'right';
$mobile_menu_top_content = legendary_toolkit_get_theme_option('mobile_menu_top_content');
$mobile_menu_bottom_content = legendary_toolkit_get_theme_option('mobile_menu_bottom_content');
?>

<header id="masthead" class="site-header navbar-static-top <?=$header_behavior_class;?> <?=$transparent_class;?>" role="banner">
    <div class="container">
        <nav class="navbar navbar-expand p-0">
            <div class="navbar-brand">
                <?php get_template_part('template-parts/header', 'logo', ['id' => 'site_logo']);?>
            </div>
            <?php
                // Desktop Menu
                wp_nav_menu(
                    array(
                        'theme_location'    => 'primary',
                        'container'         => 'div',
                        'container_id'      => 'main-nav',
                        'container_class'   => 'collapse navbar-collapse justify-content-end',
                        'menu_id'           => false,
                        'menu_class'        => 'navbar-nav',
                        'depth'             => 10,
                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                        'walker'            => new wp_bootstrap_navwalker()
                    )
                );
            ?>
            <!-- Mobile Menu -->
            <slide-drawer width="<?=$mobile_menu_width;?>" overlayOpacity=".7" mobileWidth="<?=$mobile_menu_width;?>" mobileBreak="<?=$mobile_menu_breakpoint;?>" <?=$mobile_menu_position;?>>
                <div id="menu-wrapper" style="display:none";>
                    <div id="menu_top">
                        <?php get_template_part('template-parts/header', 'logo', ['id' => 'mobile_site_logo']);?>
                        <div id="mobile-menu-top-content">
                            <?php echo wpautop($mobile_menu_top_content);?>
                        </div>
                    </div>
                    <?php
                        wp_nav_menu(
                            array(
                            'theme_location'    => 'primary',
                            'container'         => false,
                            'menu_id'           => 'mobile_menu',
                            )
                        );
                    ?>
                    
                    <div id="mobile_menu_bottom">
                        <div id="mobile-menu-bottom-content">
                            <?php echo wpautop($mobile_menu_bottom_content);?>
                        </div>
                    </div>
                </div>
            </slide-drawer>
        </nav>
    </div>
</header><!-- #masthead -->