<?php 
/**
 * The archive template
 */

get_header() ?>

	<h2 class="archive_title">		
		<?php if ( is_day() ) : ?>
			<?php printf( __( 'Daily Archives: %s', 'textural' ), '<span>' . get_the_date() . '</span>' ); ?>
		<?php elseif ( is_month() ) : ?>
			<?php printf( __( 'Monthly Archives: %s', 'textural' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'textural' ) ) . '</span>' ); ?>
		<?php elseif ( is_year() ) : ?>
			<?php printf( __( 'Yearly Archives: %s', 'textural' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'textural' ) ) . '</span>' ); ?>
		<?php else : ?>
			<?php _e( 'Archives', 'textural' ); ?>
		<?php endif; ?>
	</h2>

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