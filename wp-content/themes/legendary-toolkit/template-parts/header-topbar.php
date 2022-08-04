<?php 

$enable_top_bar = legendary_toolkit_get_theme_option('enable_top_bar');
$top_bar_content = legendary_toolkit_get_theme_option('top_bar_content');

if ($enable_top_bar) : ?>
    <div class="top-bar-content">
        <?=$top_bar_content;?>
    </div>
<?php endif; ?>