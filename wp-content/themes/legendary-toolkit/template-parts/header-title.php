<?php 
$page_title = legendary_toolkit_get_theme_option('page_title');
$page_title_content = legendary_toolkit_get_theme_option('page_title_content');
if ($page_title && !is_front_page()) : ?>
    <div id="page_title">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo do_shortcode($page_title_content);?>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>