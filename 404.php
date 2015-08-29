<?php 
/**
 * The 404 page template
 */

get_header() ?>

	<div class="inner_wrap">

		<h1 class="post_header"><?php _e( '404 Error', 'textural' ) ?></h1>
		<h3><?php _e( 'We\'re sorry, but it seems that you have reached a dead end.', 'textural' ) ?></h3>
		<h3><a href="<?php echo site_url() ?>" class="home-link"><?php _e( 'Return home', 'textural' ) ?></a></h3>
			
	</div>

<?php get_footer () ?>