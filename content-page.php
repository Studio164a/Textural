<article>

	<?php get_template_part( 'featured-image' ) ?>	

	<div class="inner_wrap">
			
		<header>
			<h1 class="post_header"><a href="<?php the_permalink() ?>" title="Link to <?php the_title() ?>"><?php the_title() ?></a></h1>
		</header>
			
		<div class="content_wrap">

			<?php the_content() ?>	

		</div>		

		<?php get_template_part( 'after-content' ) ?>

		<?php comments_template('', true) ?>

	</div>

</article>

<?php osfa_scroll_top() ?>