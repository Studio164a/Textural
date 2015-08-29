<?php 
/**
 * The default template
 */

get_header() ?>

	<?php if ( have_posts() ) : ?>

		<div id="posts">

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" <?php post_class()?>>
		
				<?php get_template_part( 'sticky' ) ?>

				<?php get_template_part( 'content', get_post_format() ) ?>				

			</div>			

		<?php endwhile ?>

		</div>

		<?php osfa_content_nav( 'nav_below' ) ?>

	<?php endif ?>

<?php get_footer () ?>