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

<?php if (!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )) : ?>
			</div><!-- .row -->
		</div><!-- .container -->
		<section class="cta-section">
			<div class="container">
				<div class="cta-content-wrap text-center">
					<div class="cta-title">
						<?php $cta_title = get_field('cta_title', 'option');
							if($cta_title){ ?>
								<h3><?php echo $cta_title; ?></h3>
							<?php  }
						?>
					</div>
					<div class="cta-content">
						<?php $cta_content = get_field('cta_content', 'option');
							if($cta_content){ ?>
								<?php echo $cta_content; ?>
							<?php  }
						?>
					</div>
					<div class="cta-button">
						<?php 
						$link = get_field('cta_button', 'option');
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
	
		<div id="footer" style="background-image: url('<?php echo $image['url'] ?>');">
			<?php get_template_part('template-parts/footer', 'widgets');?>
			<?php get_template_part('template-parts/footer', 'copyright');?>
		</div><!--#footer-->
<?php endif; ?>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</html>