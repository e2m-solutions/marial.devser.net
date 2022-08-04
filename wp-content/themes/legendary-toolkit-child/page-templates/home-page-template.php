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
<section class="meet-mariel-section">
    <div class="container">
        <div class="mariel-content-wrap d-flex">
            <div class="mariel-image">
                <?php $client_image = get_field('client_image');
                    if($hero_content){ ?>
                     <img src="<?php echo $client_image['url']; ?>" alt="<?php echo $client_image['alt']; ?>">
                    <?php  }
                    ?>
            </div>
            <div class="mariel-content">
                <div class="mariel-title">
                    <?php $mariel_title = get_field('mariel_title');
                        if($mariel_title){ ?>
                            <h2><?php echo $mariel_title; ?></h1>
                        <?php  }
                    ?>
                </div>
                <div class="mariel-description">
                    <?php $mariel_content = get_field('mariel_content');
                        if($mariel_content){ ?>
                            <?php echo $mariel_content; ?>
                        <?php  }
                    ?>
                </div>
                <div class="mariel-btn">
                <?php 
                    $link = get_field('mariel_button');
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="btn-blue" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="connection-section">
    <div class="container">
        <div class="connection-wrap d-flex justify-content-between">
            <div class="left-section">
                <div class="connection-title">
                    <?php $connection_title = get_field('connection_title');
                            if($connection_title){ ?>
                                <h2><?php echo $connection_title; ?></h1>
                            <?php  }
                        ?>
                </div>

                <div class="connection-content">
                    <?php $connection_content = get_field('connection_content');
                            if($connection_content){ ?>
                                <?php echo $connection_content; ?>
                            <?php  }
                        ?>
                </div>
                <div class="connection-button">
                    <?php 
                        $link = get_field('connection_button');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a class="btn-blue" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right-section">
                <div class="connection-right-image">
                    <?php $connection_image = get_field('connection_image');
                        if($connection_image){ ?>
                        <img src="<?php echo $connection_image['url']; ?>" alt="<?php echo $connection_image['alt']; ?>">
                        <?php  }
                        ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="testimonial-section">
    <div class="container">
        <div class="testimonial-wrap text-center">
            
            <?php if(have_rows('testimonial_repeater')) {
                while(have_rows('testimonial_repeater')){
                    the_row();
                    $testimonial_title = get_sub_field('testimonial_title');
                    $testimonial_content = get_sub_field('testimonial_content');
                    $testimonial_author = get_sub_field('testimonial_author'); ?>
                    <div class="testimonial">
                        <div class="testimonial-title">
                            <h2><?php echo $testimonial_title ?></h2>
                        </div>
                        <div class="testimonial_content">
                            <?php echo $testimonial_content ?>
                        </div>
                        <div class="testimonial-author">
                            <span><?php echo $testimonial_author ?></span>
                        </div>
                    </div>
                <?php }
            }?>
          
        </div>
    </div>
</section>
<?php get_footer(); ?>