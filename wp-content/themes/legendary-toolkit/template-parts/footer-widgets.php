<?php 
	$footer_column_count = intval(legendary_toolkit_get_theme_option('footer_columns'));
	$footer_column_class = ($footer_column_count) ? 'col-sm-' . 12 / $footer_column_count : 'col-sm-12';
    if ($footer_column_count) : ?>
    <section id="footer_columns">
        <div class="container">
            <div class="row">
                <?php for ($i=0; $i < $footer_column_count; $i++) : ?>
                    <div class="<?php echo $footer_column_class;?>" id="footer_column_<?php echo $i+1;?>">
                        <?php echo do_shortcode(wpautop(legendary_toolkit_get_theme_option('footer_column_'. ($i + 1))));?>
                    </div>
                <?php endfor;?>
            </div>
        </div>
    </section>
<?php endif;?>