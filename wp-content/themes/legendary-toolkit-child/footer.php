<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */
?>
<?php $title = get_field('cta_content', 'option');
      $content = get_field('cta_image', 'option');
      $button = get_field('cta_button', 'option');
?>
<?php if (!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )) : ?>
			</div><!-- .row -->
		</div><!-- .container -->

	<?php if (!is_page('')) {?>
		<section class="cta-section">
			<div class="container">
				<div class="cta-content-wrap text-center">
					<div class="cta-title">
						<?php $title = get_field('title');
							if($title){ ?>
								<span><?php echo $title; ?></span>
							<?php  }
						?>
					</div>
					<div class="cta-content text-center">
						<?php $content = get_field('content');
							if($content){ ?>
								<span><?php echo $content; ?></span>
							<?php  }
						?>
					</div>
					<div class="cta-button">
						<?php 
						$link = get_field('cta_button');
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
		</section>
	<?php	} ?>	
		<div id="footer" style="background-image: url('<?php echo $image['url'] ?>');">
			<?php get_template_part('template-parts/footer', 'widgets');?>
			<?php get_template_part('template-parts/footer', 'copyright');?>
		</div><!--#footer-->
<?php endif; ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>