<?php
/**
 * Template Name: Page Full Width
 */

get_header(); ?>

	<section id="primary" class="content-area <?=toolkit_get_primary_column_classes();?>">
		<div id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );
				
			endwhile; // End of the loop.
			?>

		</div><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();