<article>

	<?php get_template_part( 'featured-image' ) ?>	

	<div class="inner_wrap">
			
		<header>
			<h1 class="post_header"><a href="<?php the_permalink() ?>" title="Link to <?php the_title() ?>"><?php the_title() ?></a></h1>
		</header>
			
		<div class="content_wrap">

			<?php the_content() ?>

			<div id="archives" class="cf">

				<div class="column column_33">

					<!-- Show recent posts -->

					<h3><?php _e( 'Last 30 posts', 'textural' ) ?></h3>

					<?php $recent_posts = osfa_get_recent_posts(30);
					if ( $recent_posts->have_posts() ) : ?>

					<ul>

						<?php while ( $recent_posts->have_posts() ) : 
							$recent_posts->the_post() ?>

							<li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>

						<?php endwhile ?>

					</ul>

					<?php endif;

					// Reset post data
					wp_reset_postdata();
					?>

					<!-- End recent posts -->

				</div>

				<div class="column column_33">

					<!-- Monthly archives -->
					<h3><?php _e( 'Posts by month', 'textural' ) ?></h3>
					<?php wp_get_archives() ?>
					<!-- End monthly archives -->

					<!-- Weekly archives -->
					<h3><?php _e( 'Posts by week', 'textural' ) ?></h3>
					<?php wp_get_archives( array( 'type' => 'weekly' ) ) ?>
					<!-- End weekly archives -->

					<!-- List of authors -->
					<h3><?php _e( 'Authors', 'textural' ) ?></h3>
					<?php wp_list_authors( array( 'exclude_admin' => false )) ?>
					<!-- End authors -->

				</div>

				<div class="column column_33 is_last">

					<!-- List pages -->
					<h3><?php _e( 'Pages', 'textural' ) ?></h3>
					<?php wp_list_pages( array( 'title_li' => '' ) ) ?>
					<!-- End pages -->

					<!-- List categories -->
					<h3><?php _e( 'Categories', 'textural' ) ?></h3>
					<?php wp_list_categories( array( 'title_li' => '' )) ?>
					<!-- End categories -->

					<!-- List tags -->
					<h3><?php _e( 'Tags', 'textural' ) ?></h3>
					<?php wp_list_categories( array( 'title_li' => '', 'taxonomy' => 'post_tag' ) ) ?>					
					<!-- End tags -->

				</div>

			</div>

		</div>		

	</div>

</article>

<?php osfa_scroll_top() ?>