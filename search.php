<?php 
/**
 * The search results template
 */

get_header() ?>

	<h1 class="archive_title">
		<?php printf( __( 'You searched for: &#8220;%s&#8221;', 'textural' ), '<span>' . get_search_query() . '</span>' ) ?>
	</h1>

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