<?php
/**
 * Filters and function to customize the post comments area
 */


/**
 * Customize comment form default fields
 * @uses comment_form_field_comment filter
 * @param string $field
 * @return string
 */
if ( !function_exists( 'osfa_comment_form_field_comment') ) {
	function osfa_comment_form_default_fields( $fields ) {
		$fields = '
		<p class="required" tabindex="1">
			<input type="text" name="author" id="commenter_name" placeholder="'.__( 'Name', 'textural' ).'" required />			
		</p>
		<p class="required" tabindex="2">
			<input type="email" name="email" id="commenter_email" placeholder="'.__( 'Email', 'textural' ).'" required />			
		</p>
		<p tabindex="3">
			<input type="text" name="url" id="commenter_url" placeholder="'.__( 'Website', 'textural' ).'" />
		</p>
		';
		return $fields;
	}

	add_filter( 'comment_form_default_fields', 'osfa_comment_form_default_fields', 10, 2 );
}

/**
 * Customize comment output
 * @param 
 * @param array $args
 * @param 
 * @return string
 */
if ( !function_exists( 'osfa_comment' ) ) {
	function osfa_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>

		<li class="pingback">
			<p><?php _e( 'Pingback:', 'textural' ); ?> <?php comment_author_link() ?></p>
			<?php edit_comment_link( __( 'Edit', 'textural' ), '<p class="comment_meta">', '</p>' ); ?>
		</li>
		
		<?php	
				break;
			default :
		?>

		<li <?php comment_class( get_option('show_avatars') ? 'avatars' : 'no-avatars' ) ?> id="li-comment-<?php comment_ID(); ?>">

			<?php echo get_avatar( $comment, 50 ) ?>

			<div class="comment_details">
				<h6 class="comment_author vcard"><?php comment_author_link() ?></h6>				
				<div class="comment_text"><?php comment_text() ?></div>
				<p class="comment_meta">
					<span class="comment_date"><?php printf( '<i class="icon-comment"></i> %1$s %2$s %3$s', get_comment_date(), _x( 'at', 'comment post on date at time', 'textural'), get_comment_time() ) ?></span>
					<span class="comment_reply floatright"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => sprintf( '<i class="icon-pencil"></i> %s', _x( 'Reply', 'reply to comment' , 'textural' ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?></span>
				</p>
			</div>		

		</li>

		<?php
				break;
		endswitch;	
	}
}