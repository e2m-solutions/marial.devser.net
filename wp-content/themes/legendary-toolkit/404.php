<?php
/**
 * The template for displaying 404 pages (Not Found)
 */

get_header();
?>
	<div class="wrapper <?=toolkit_get_primary_column_classes();?>">
		<div class="page-content-cover">
			<section class="not-found-cover" style="text-align:center;">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'wp-bootstrap-starter' ); ?></h1>
				</header><!-- .page-header -->
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'wp-bootstrap-starter' ); ?></p>

				<?php
					get_search_form();
				?>
				
			</section>
		</div>
	</div>
<?php
get_sidebar();
get_footer();
