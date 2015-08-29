<?php 
/**
 * The author post archive
 */

get_header();


	if ( have_posts() ) : 

		// Get the first post in the loop, so that we have access to the 
		// author's information when forming the archive title
		the_post();
		?>

	<h1 class="archive_title">
		<?php printf( __( 'Author Archives: %s', 'textural' ), 
			'<span class="vcard">
				<a class="url fn n" 
					href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" 
					title="' . esc_attr( get_the_author() ) . '" 
					rel="me">' . get_the_author() . '</a>
			</span>' ); ?>
	</h1>	

		<?php

		// Back to the top.
		rewind_posts()
		?>

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