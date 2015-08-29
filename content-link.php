<article>

	<?php get_template_part( 'featured-image' ) ?>	

	<div class="inner_wrap">

		<p class="post_date"><?php the_time('j F, Y') ?></p>

		<header>		

			<?php $link = get_post_meta( get_the_ID(), 'link', true ) ?>
			<?php if ( $link === false ) $link = get_the_permalink() ?>

			<?php if ( is_single() ) : ?>
				<h1 class="post_header">
					<a href="<?php echo $link ?>" title="Link to <?php the_title() ?>" target="_blank">
						<?php the_title() ?>
						<small><i class="icon-link"></i></small>
					</a>
				</h1>
			<?php else : ?>
				<h2 class="post_header">				
					<a href="<?php echo $link ?>" title="Link to <?php the_title() ?>" target="_blank"><?php the_title() ?>
						<small><i class="icon-link"></i></small>
					</a>				
				</h2>	
			<?php endif ?>	
		</header>
			
		<div class="content_wrap">

			<?php the_content() ?>	

		</div>

	</div>

</article>

<?php if ( is_single() ) : ?>

	<?php get_template_part( 'after-content', get_post_format() ) ?>
	
	<?php comments_template('', true) ?>

<?php endif ?>

<?php osfa_scroll_top() ?>