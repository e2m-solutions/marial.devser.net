<?php
    $footer_bottom_content = legendary_toolkit_get_theme_option('footer_bottom_content');
    if($footer_bottom_content):
?>
<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <?php echo wpautop($footer_bottom_content);?>
            </div>
        </div><!-- close .site-info -->
    </div>
</footer><!-- #colophon -->
<?php endif; ?>