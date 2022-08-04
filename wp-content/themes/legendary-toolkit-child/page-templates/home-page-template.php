<?php /* Template Name: Home Page Template */ ?>

<?php get_header(); ?>
<?php $hero_image = get_field('hero_image'); ?>
<section class="hero-section" style=" background-image: url('<?php echo $hero_image['url']; ?>');">
    <div class="container">
        <div class="hero-text">
            <div class="hero-sub-title">
                <?php $hero_sub_title = get_field('hero_sub_title');
                if($hero_sub_title){ ?>
                    <span><?php echo $hero_sub_title; ?></span>
                <?php  }
                ?>
            </div>
            <div class="hero-title">
                <?php $hero_title = get_field('hero_title');
                if($hero_title){ ?>
                    <h1><?php echo $hero_title; ?></h1>
                <?php  }
                ?>
            </div>
            <div class="hero-content">
                <?php $hero_content = get_field('hero_content');
                if($hero_content){ ?>
                   <?php echo $hero_content; ?>
                <?php  }
                ?>
            </div>
            <div class="hero-btn d-flex">
                <?php 
                    $link = get_field('hero_button_1');
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="btn-white" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                <?php endif; ?>

                <?php 
                    $link = get_field('hero_button_2');
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="btn-transparent" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>