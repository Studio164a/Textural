<article>

	<div class="inner_wrap">		

		<div class="content_wrap">		

			<p class="post_date"><?php the_time('j F, Y') ?></p>

			<h2><?php the_content() ?></h2>			

		</div>

		<?php if ( is_single() ) : ?>

			<?php get_template_part( 'after-content', get_post_format() ) ?>
			
			<?php comments_template('', true) ?>

		<?php endif ?>

	</div>

</article>

<?php osfa_scroll_top() ?>