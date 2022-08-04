<?php 
    $logo = legendary_toolkit_get_theme_option('logo');
    $logo_url = ($logo) ? esc_url(wp_get_attachment_image_url($logo, 'medium')) : '';
    $id = $args['id'];
    if ( $logo ): ?>
        <a href="<?=esc_url( home_url( '/' )); ?>">
            <img id="<?=$id;?>" src="<?=$logo_url; ?>" alt="<?=esc_attr( get_bloginfo( 'name' ) ); ?>">
        </a>
    <?php else : ?>
        <a class="site-title" href="<?=esc_url( home_url( '/' )); ?>"><?php esc_url(bloginfo('name')); ?></a>
    <?php endif; ?>