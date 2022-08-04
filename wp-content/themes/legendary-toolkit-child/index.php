<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */
get_header(); ?>
	<section id="primary" class="content-area">
    <div id="main" class="site-main" role="main">

        <div class="archive-top-section">
            <div class="container">
                <div class="podcast-latest-post">
                    <span>Latest Post</span>
                </div>
                <div class="top-content-wrap d-flex flex-wrap justify-content-between">
				<?php
					$recent_args = array(
						'posts_per_page' => 1,
						'post_status' => 'publish',
					);
					$recent_query = new WP_Query( $recent_args );
					if ( $recent_query->have_posts() ) {
						while ( $recent_query->have_posts() ) { $recent_query->the_post(); ?>
                    <div class="podcast-custom-post-left-part">
                        <div class="podcast-tittle">
                            <h2><?php the_title(); ?></h2>
                        </div>
                        <div class="post-datewrap d-flex align-items-center">
                            <div class="post-date">
                                <?php echo get_the_date(); ?>
                            </div>
                            <div class="podcast-category">
                            <?php $terms = wp_get_post_terms( $recent_query->post->ID, array( 'category' ) ); ?>
								<?php $j=0; foreach ( $terms as $term ) : ?>
									<?php if($j++  > 0){ echo ', '; } ?>
								<?php echo $term->name; ?>
								<?php endforeach; ?>
                            </div>
                        </div>
                        <div class="post-content podcast-post-content">
                            <p><?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?></p>
                        </div>
                        <div class="podcast-read-btn">
                            <a href="<?php the_permalink(); ?>" class="btn">Continue Reading</a>
                        </div>
                    </div>
                    <div class="podcasr-custom-right-part">
                        <div class="post-feture-image">
							<?php if ( get_the_post_thumbnail() ) { ?>
								<a href="<?php the_permalink(); ?>"> <?php  the_post_thumbnail(); ?>  </a>
									<?php
								} else { ?>
								<a href="<?php the_permalink(); ?>"> <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/08/no-img-diane.jpg" alt=""></a>
							<?php } ?>
                        </div>
                    </div>
                    <?php }
						wp_reset_postdata();
					?>
                    <?php } ?>
                </div>
            </div>
        </div>

        
                
		<?php 
		$args1 = array('post_type' => $arcName, 'order' => 'ASC','posts_per_page' => -1,);
		$q1 = new WP_query($args1);
		if($q1->have_posts()) :
		$firstPosts = array();
			while($q1->have_posts()) : $q1->the_post();
				$firstPosts[0] = $post->ID;
			endwhile;
		endif;

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array(
			'post__not_in' => $firstPosts,
			'paged' => $paged,
			'posts_per_page' => 9,
			'order' => 'DESC',
			'post_status' => 'publish',
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			echo '<div class="cpt-podcast-wrap">
				<div class="container">
					<div class="podcast-latest-post post-recent-subtitle">
						<span>Recent Posts</span>
					</div>'
			;
			while ( $the_query->have_posts() ) { $the_query->the_post();?>
			<div class="podcast-recent-post d-flex align-items-center">
				<div class="post-feture-image podcast-recent-post-feture has-no-img-ratio">
					<?php if ( get_the_post_thumbnail() ) { ?>
						<a href="<?php the_permalink(); ?>"> <?php  the_post_thumbnail(); ?>  </a>
							<?php
						} else { ?>
						<a href="<?php the_permalink(); ?>"> <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/08/no-img-diane.jpg" alt=""></a>
					<?php } ?>
				</div>
				<div class="recent-post-content">
					<div class="post-datewrap d-flex align-items-center">
						<div class="post-date">
							<?php echo get_the_date(); ?>
						</div>
						<div class="podcast-category">
						<?php $terms = wp_get_post_terms( $the_query->post->ID, array( 'category' ) ); ?>
							<?php $j=0; foreach ( $terms as $term ) : ?>
								<?php if($j++  > 0){ echo ', '; } ?>
							<?php echo $term->name; ?>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="recent-post-tittle">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					</div>
					<div class="recent-post-description">
						<p><?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?></p>
					</div>
					<div class="podcast-recent-read-btn">
						<a href="<?php the_permalink(); ?>" class="btn">Continue Reading</a>
					</div>
				</div>
			</div>
			<?php
			}
			wp_reset_postdata(); 
			echo '</div>';
			$big = 999999999;
				echo '<div class="blog-post-pagination">';
				echo paginate_links( array(
				'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'format' => '?paged=%#%',
				'current' => max(1, get_query_var('paged') ),
				'total' => $the_query->max_num_pages,
				'prev_text' => '<',
				'next_text' => '>'
			) );
			echo '</div>
			</div>';
		} 
		?>
            

		<section class="subscribe-section">
			<div class="container-fluid">
				<div class="subscribe-wrap d-flex align-items-center justify-content-between">
				<div class="subscribe-title">
					<?php
						$title = get_field('subscribe_title', 'option');
						if($title){ ?>
						<h4> <?php echo $title ?></h4>
						<?php } ?>
				</div>
				<div class="subscribe-content">
				<?php
						$content = get_field('subscribe_content', 'option');
						if($content){ ?>
						<?php echo $content ?>
						<?php } ?>
				</div>
				<div class="subscribe-form">
					<?php 
					$form_id = get_field('subscribe_form', 'option');
					if($form_id){?>
			<?php echo do_shortcode('[formidable id="'.$form_id.'"]') ?>
				<?php } ?>
				</div>
				</div>
			</div>
		</section>


		<section class="taxonomy">
			<div class="container">
				<div class="taxonomy-title">
					<h4>Categories</h4>
				</div>
				<div class="podcast-taxonomy-wrap d-flex justify-content-center">
					<?php
					$taxonomies = get_terms( array(
						'taxonomy' => 'podcast_categories',
						'hide_empty' => false
					) );
					$term = get_queried_object();
					if ( !empty($taxonomies) ) :
						foreach( $taxonomies as $category ) {
							if( $category->parent == 0 ) { ?>
							<div class="podcast-taxonomy">
								<div class="category-name">
								<a href="<?php echo esc_url( get_term_link( $term ) ) ?>">
									<?php echo $category->name; ?>
								</a>
								</div>
								<div class="img-border">
									<div class="podcast-category-image">
										<?php
										//print_r($category->term_id);		
										$image = get_field('featured_image', 'podcast_categories_' . $category->term_id);
										if( !empty( $image ) ): ?>
										<a href="<?php echo esc_url( get_term_link( $term ) ) ?>">
											<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
										</a>
									</div>
								</div>
							</div>
								<?php endif; 
							}
						} 
						echo $output;
					endif;?>
				</div>
			</div>
		</section>
	</div><!-- #main -->
</section><!-- #primary -->
<?php
get_sidebar();
get_footer();
