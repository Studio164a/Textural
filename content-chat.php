<article>

	<?php get_template_part( 'featured-image' ) ?>	

	<div class="inner_wrap">

		<p class="post_date"><?php the_time('j F, Y') ?></p>

		<?php osfa_post_header() ?>
				
		<div class="content_wrap">

			<blockquote>
				<?php the_content() ?>	
			</blockquote>

		</div>

		<?php if ( is_single() ) : ?>

			<?php get_template_part( 'after-content', get_post_format() ) ?>

			<?php comments_template('', true) ?>

		<?php endif ?>

	</div>

</article>

<?php osfa_scroll_top() ?>