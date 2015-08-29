<?php 
/**
 * The template for gallery posts
 */

get_header() ?>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" <?php post_class()?>>

				<?php get_template_part( 'content', get_post_format() ) ?>				

			</div>

			<?php get_template_part( 'adjacent' ) ?>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer () ?>