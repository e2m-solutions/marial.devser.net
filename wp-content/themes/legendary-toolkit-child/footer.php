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
<?php $image = get_field('footer_background_image', 'option');
      $cta_background = get_field('cta_background', 'option');
      $cta_image = get_field('cta_image', 'option');
      $cta_content = get_field('cta_content', 'option');
?>
<?php if (!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )) : ?>
			</div><!-- .row -->
		</div><!-- .container -->

	<?php if (!is_page('258')) {?>
		<section class="help-section-cover" style="background-image: url('<?php echo $cta_background['url'] ?>');">
			<div class="container">
				<div class="help-content-cover d-flex">
				<?php if($cta_image) {?>

						<div class="left-side-part">
							<img src="<?php echo $cta_image['url'] ?>" alt="<?php echo $cta_image['alt'] ?>">
						</div>
						<div class="right-content">
							<?php if($cta_content){ ?>
							<?php  echo $cta_content ?>
						<?php	} ?>
						<div class="talk-btn">
								<?php
							$link = get_field('cta_button', 'option');
								if( $link ): 
									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
									?>
									<a class="btn" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
								<?php endif; ?>
						</div>
						</div>
				<?php }  ?>
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