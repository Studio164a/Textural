<div class="sharrre" data-title="sharrre" data-url="<?php the_permalink() ?>"></div>

<ul class="meta">

	<li class="author">
		<?php printf( "%s <a href=\"%s\" title=\"%s\">%s</a>", 
			__( 'Written by:', 'textural' ), 
			get_author_posts_url( get_the_author_meta('ID') ),
			__( 'Go to author profile', 'textural' ),
			get_the_author() ) ?>
	</li>

	<?php if ( !is_page() ) : ?>
		<li class="date_posted"><?php printf( "%s %s", __( 'Date posted: ', 'textural' ), get_the_time('j F, Y') ) ?></li>
	<?php endif ?>

	<?php if ( has_category() ) : ?>
		<li class="category"><?php printf( "%s %s", __( 'Posted in', 'textural' ), get_the_category_list(', ') ) ?></li>
	<?php endif ?>

	<?php if ( has_tag() ) : ?>
		<li class="tags"><?php the_tags() ?></li>		
	<?php endif ?>	

</ul>