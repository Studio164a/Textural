<article>	

	<div class="content_wrap">

		<h2><a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'textural' ), get_the_title() ) ?>"><?php the_content() ?></a></h2>
		<h4 class="attribution"><?php the_title() ?></h4>		

	</div>	
	
	<?php if ( is_single() ) : ?>

		<div class="inner_wrap">

			<?php get_template_part( 'after-content', get_post_format() ) ?>
			
			<?php comments_template('', true) ?>

		</div>

	<?php endif ?>

</article>

<?php osfa_scroll_top() ?>