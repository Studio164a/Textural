<?php
/**
 * Returns the first gallery from the content
 * @param string $content
 * @return array 		Will return an empty array if there are no matches.
 */
function osfa_get_gallery_shortcode($content) {
	preg_match('/\[gallery(.*)]/', $content, $matches);
	return $matches;
}

/**
 * Returns the images for the gallery
 * @param string $content
 * @return array
 */
function osfa_get_gallery_images() {
	global $post;

	$match = osfa_get_gallery_shortcode($post->post_content);

	// Get all attached images if specific image IDs weren't specified
	if ( empty( $match ) || strpos( $match[1], 'ids' ) === false /* Match found without "ids" specified */ ) {		
		$images = get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post_status' => 'any', 'post_parent' => $post->ID ) );
	}
	else {
		// Strip the beginning and end bits, leaving us with just a comma-separated list of image IDs
		list( $a, $image_ids, $b ) = explode( '"', $match[1] );	
		$images = get_posts( array( 'include' => explode( ',', $image_ids ), 'post_per_page' => -1, 'post_type' => 'attachment', 'post_status' => 'any' ) );
	}
	return $images;
}

/**
 * Strips the gallery shortcodes from the content
 * @param string $content
 * @return string
 */
function osfa_strip_gallery_shortcodes($content) {
	return preg_replace('/\[gallery(.*)]/', '', $content);
}

/**
 * Returns the audio formats for this post. Used by audio posts. 
 * @param int $post_id
 * @return array|false
 */
function osfa_get_audio_formats($post_id) {

	$formats = array();
	
	// Loop through audio formats available to jPlayer
	foreach ( array( 'mp3', 'm4a', 'oga', 'webma', 'fla', 'wav' ) as $format ) {
		$has_format = get_post_meta( $post_id, $format, true );
		if ( $has_format != false ) {
			$formats[$format] = $has_format;
		}
	}

	if ( empty( $formats ) )
		return false;

	return $formats;
}

/**
 * Returns a JSON encoded array of media for the audio track.
 *
 * @param array|false $formats
 * @param array|false $poster
 * @return string|false
 */
function osfa_jplayer_get_media_json($formats, $poster) {
	if ($formats === false)
		$formats = array();

	if ($poster === false)
		return json_encode( $formats );

	$formats['poster'] = $poster[0];
	return json_encode( $formats );
}


/**
 * This retrieves an image's post ID based on its URL.
 * Credit: http://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @param string $image_url
 * @since Textural 1.2
 */
function osfa_get_image_id_from_url($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s;", $image_url ) ); 
    return $attachment[0]; 
}    

/**
 * Get social sites.
 *
 * @return array
 * @since Textural 1.0
 */
function osfa_get_social_sites() {
    return apply_filters( 'osfa_social_sites', array(            
        'behance' => __( 'Behance', 'textural' ), 
        'blogger' => __( 'Blogger', 'textural' ), 
        'deviant' => __( 'DeviantART', 'textural' ), 
        'digg' => __( 'Digg', 'textural' ), 
        'dribbble' => __( 'Dribbble', 'textural' ),             
        'facebook' => __( 'Facebook', 'textural' ), 
        'flickr' => __( 'Flickr', 'textural' ), 
        'forrst' => __( 'Forrst', 'textural' ), 
        'gplus' => __( 'Google+', 'textural' ),                     
        'lastfm' => __( 'Last.fm', 'textural' ),
        'linkedin' => __( 'Linkedin', 'textural'),
        'myspace' => __( 'MySpace', 'textural' ), 
        'orkut' => __( 'Orkut', 'textural' ), 
        'pinterest' => __( 'Pinterest', 'textural' ), 
        'rss' => __( 'RSS', 'textural' ),
        'stumbleupon' => __( 'Stumbleupon', 'textural' ), 
        'tumblr' => __( 'Tumblr', 'textural'),
        'twitter' => __( 'Twitter', 'textural' ),
        'viddler' => __( 'Viddler', 'textural' ),
        'vimeo' => __( 'Vimeo', 'textural' ),
        'youtube' => __( 'YouTube', 'textural' ),
        'appnet' => __( 'App.net', 'textural' )
    ) );
}

/**
 * Get a WP_Query object with the most recent posts.
 *
 * @param int $count 			Default is 10	
 * @return WP_Query
 * @since Textural 1.2
 */
function osfa_get_recent_posts($count = 10) {
	return new WP_Query( array( 'posts_per_page' => $count ) );
}

/**
 * Returns the first embed shortcode from the content.
 *
 * @param string $content
 * @return array 					Will return an empty array if there are no matches.
 * @since Textural 1.3
 */
function osfa_get_embed_shortcode($content) {
	preg_match('/\[embed(.*)](.*)\[\/embed]/', $content, $matches);
	return $matches;
}

/**
 * Returns the images for the gallery.
 *
 * @param string $content
 * @return array
 * @since Textural 1.3
 */
function osfa_do_first_embed() {
	global $post, $wp_embed;

	// Get the first embed
	$match = osfa_get_embed_shortcode($post->post_content);

	if ( empty( $match ) ) 
		return;

	return $wp_embed->run_shortcode( $match[0] );
}

/**
 * Strips the embed shortcodes from the content
 *
 * @param string $content
 * @param int $limit 					Optional. Allows you to define how many of the shortcodes will be stripped. Defaults to -1.
 * @return string
 * @since Textural 1.3
 */
function osfa_strip_embed_shortcode($content, $limit = '-1' ) {
	return preg_replace('/\[embed(.*)](.*)\[\/embed]/', '', $content, $limit);	
}