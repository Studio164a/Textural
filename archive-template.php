<?php
/**
 * Template name: Archives
 */

get_header() ?>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>
			
			<div id="page-<?php the_ID() ?>" <?php post_class()?>>
			
				<?php get_template_part( 'content', 'archives' ) ?>

			</div>		

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer () ?>