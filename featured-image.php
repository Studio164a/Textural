<?php
/**
 * Display the post thumbnail, if there is one
 */

if ( has_post_thumbnail() ) : 

	$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );
	?> 

	<div class="featured_image" style="width: <?php echo $thumbnail_src[1] ?>px;">
		<a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'textural' ), get_the_title() ) ?>">
			<?php the_post_thumbnail() ?> 
		</a>
	</div>

<?php endif ?>