<?php /* Template Name: Home Page Template */ ?>

<?php get_header(); ?>
<?php $hero_image = get_field('hero_image'); ?>
<section class="hero-section" style=" background-image: url('<?php echo $hero_image; ?>');">
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
        </div>
    </div>
</section>
<?php get_footer(); ?>