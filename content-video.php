<article>

	<?php 
	$video = get_post_meta( get_the_ID(), 'video', true );

	if ( !$video )  
		$video = osfa_do_first_embed();

	if ( $video ) : ?>

		<div class="fit_video fullwidth">
			<?php echo $video ?>
		</div>

	<?php endif ?>

	<div class="inner_wrap">

		<p class="post_date"><?php the_time('j F, Y') ?></p>

		<?php osfa_post_header() ?>
		
		<div class="content_wrap">

			<?php osfa_video_format_the_content() ?>

		</div>

		<?php if ( is_single() ) : ?>

			<?php get_template_part( 'after-content', get_post_format() ) ?>

			<?php comments_template('', true) ?>

		<?php endif ?>

	</div>

</article>

<?php osfa_scroll_top() ?>