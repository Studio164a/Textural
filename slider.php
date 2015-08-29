<?php
/**
 * Displays a slider, using FlexSlider
 */

$images = osfa_get_gallery_images( get_the_content() );

if ( count( $images ) ) : ?>

	<div class="flexslider">

		<ul class="slides">

		<?php foreach ( $images as $image ) : ?>

			<?php $image_id = is_object( $image ) ? $image->ID : $image ?>
			<?php $fullsize = wp_get_attachment_image_src( $image_id, 'full' ) ?>
			<li>
				<a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'textural' ), get_the_title() ) ?>">
					<?php echo wp_get_attachment_image( $image_id, 'fullwidth' ) ?>
				</a>
			</li>

		<?php endforeach ?>

		</ul>

	</div>

<?php endif ?>