<?php

/**
 * Return the currently set value for the given option key
 * @param string $option_key
 * @return mixed
 */
if ( !function_exists( 'textural_get_option' ) ) {
	function textural_get_option( $option_key ) {
		$options = get_option('theme_textural_options');
		return isset( $options[$option_key] ) ? $options[$option_key] : false;
	}
}

/**
 * Display social links
 * @param $echo 	Default is true.
 * @return string|void
 * @since Textural 1.0
 */ 
if ( !function_exists('osfa_social_links') ) {
	function osfa_social_links( $echo = true ) {
		$html = '<ul class="social">';

		foreach ( array_keys( array_reverse( osfa_get_social_sites() ) ) as $site ) {
			if ( strlen( get_theme_mod($site) ) ) {
				$html .= apply_filters( 'osfa_social_link', '<li><a class="'.$site.'" href="'.get_theme_mod($site).'"></a></li>', $site );				
			}
		}

		$html .= '</ul>';
		if ( $echo == false ) 
			return apply_filters( 'osfa_social_links', $html );

		echo apply_filters( 'osfa_social_links', $html );
	}
}

/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Textural 1.0
 */
if ( ! function_exists( 'osfa_content_nav' ) ) {
	function osfa_content_nav( $html_id ) {
		global $wp_query;

		$html_id = esc_attr( $html_id );

		$current_page = get_query_var('paged');

		if ( $wp_query->max_num_pages > 1 ) :

			if ( true ) : 

				?>
				<nav id="<?php echo $html_id; ?>" class="navigation nav-after" role="navigation">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'textural' ) ?></h3>
					<span class="nav-more">
						<?php if ( $current_page == $wp_query->max_num_pages ) : _e( 'There are no more posts.', 'textural' ) ?>
						<?php else : next_posts_link( __( 'Load more', 'textural') ); endif ?>
					</span>
				</nav>

				<?php

			else : 		

				$next_posts_link = get_next_posts_link( __( 'Older posts', 'textural') );
				$previous_posts_link = get_previous_posts_link( __( 'Newer posts', 'textural') );
				?>

				<nav id="<?php echo $html_id; ?>" class="navigation nav-after" role="navigation">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'textural' ) ?></h3>				
					<span class="nav-previous">
						<?php if ( strlen( $next_posts_link ) ) : ?>
							<i class="icon-caret-left"></i><?php echo $next_posts_link ?>
						<?php endif ?>
					</span>
					<span class="nav-next">
						<?php if ( strlen( $previous_posts_link ) ) : ?>
							<?php echo $previous_posts_link ?><i class="icon-caret-right"></i>
						<?php endif ?>
					</span>
				</nav><!-- #<?php echo $html_id; ?> .navigation -->

			<?php endif;

		endif;
	}
}

/**
 * Displays post header
 * @param bool $echo
 * @since Textural 1.0
 */
if ( !function_exists( 'osfa_post_header' ) ) {
	function osfa_post_header($echo = true) {
		global $post;

		$html = '<header>';
		$html .= is_single() ? '<h1 ' : '<h2 ';
		$html .= sprintf( 'class="post_header"><a href="%s" title="%s">%s</a></', get_permalink(), sprintf( __( 'Link to %s', 'textural' ), get_the_title() ), get_the_title() );
		$html .= is_single() ? 'h1>' : 'h2>';
		$html .= '</header>';
		
		if ( $echo === true )
			echo apply_filters( 'osfa_post_header', $html );

		return apply_filters( 'osfa_post_header', $html );
	}
}		

/**
 * Displays the scroll to top link
 * @param bool $echo
 * @since Textural 1.0
 */
if ( !function_exists( 'osfa_scroll_top' ) ) {
	function osfa_scroll_top( $echo = true ) {
		$html = '<a class="scroll_top"><i class="icon-arrow-up"></i></a>';

		if ( $echo === true )
			echo apply_filters( 'osfa_scroll_top', $html );

		return apply_filters( 'osfa_scroll_top', $html );

	}
}

/**
 * Displays the site title class
 * @param bool $echo
 * @since Textural 1.0
 */
if ( !function_exists( 'osfa_site_title' ) ) {
	function osfa_site_title( $echo = true ) {
		$classes = get_theme_mod('hide_site_title') ? 'site_title hidden' : 'site_title';
		$classes = apply_filters( 'osfa_site_title_class', $classes );

		// Set up HTML 
		$html = is_front_page() ? '<h1 ' : '<div ';
		$html .= 'class="'.$classes.'">';
		$html .= '<a href="'.site_url().'" title="'.__( 'Go to homepage', 'textural' ).'">';
		$html .= get_bloginfo('title');
		$html .= '</a>';
		$html .= is_front_page() ? '</h1>' : '</div>';

		// Allow child themes to filter this
		$html = apply_filters( 'osfa_site_title', $html );

		if ( $echo === true )
			echo $html;

		return $html;
	}
}

/**
 * Displays the site description class
 * @param bool $echo
 * @since Textural 1.0
 */
if ( !function_exists( 'osfa_site_description' ) ) {
	function osfa_site_description( $echo = true ) {
		$classes = get_theme_mod('hide_site_tagline') ? 'description hidden' : 'description';
		$classes = apply_filters( 'osfa_site_description_class', $classes );

		// Set up HTML
		$html = '<h3 class="'.$classes.'">'.get_bloginfo('description').'</h3>';

		// Allow child themes to filter this
		$html = apply_filters( 'osfa_site_description', $html );
		
		if ( $echo === true )
			echo $html;

		return $html;
	} 
}

/**
 * Prints the content for a video post.
 * 
 * @return void
 * @since Textural 1.3
 */
if ( !function_exists( 'osfa_video_format_the_content' ) ) {
	function osfa_video_format_the_content($more_link_text = null, $stripteaser = false) {
		$content = get_the_content($more_link_text, $stripteaser);
		$content = osfa_strip_embed_shortcode( $content, 1 );
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);		
		echo $content;
	}
}

/**
 * Prints the content for a gallery post.
 * 
 * @return void
 * @since Textural 1.3
 */
if ( !function_exists( 'osfa_gallery_format_the_content' ) ) {
	function osfa_gallery_format_the_content($more_link_text = null, $stripteaser = false) {
		$content = get_the_content($more_link_text, $stripteaser);
		$content = osfa_strip_gallery_shortcodes( $content );
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);		
		echo $content;		
	}
}

/**
 * Prints classes on the HTML element.
 * 
 * @return void
 * @since Textural 1.3
 */
if ( !function_exists( 'osfa_html_class' ) ) {
	function osfa_html_class() {
		$classes = array();
		if ( get_theme_mod( 'background_color' ) || get_background_image() )
			$classes[] = 'custom-background';
		echo 'class="' . implode(' ', $classes ) . '"';
	}
}