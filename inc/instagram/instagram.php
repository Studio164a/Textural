<?php

/**
 * A class for displaying Instagram images
 */
class OSFA_Instagram {

	/**
	 * Access token
	 * @var string
	 */
	private $token;

	/**
	 * Client ID
	 * @var string
	 */
	private $client_id = '3c2f1453c64e474cabb2c63f9cd553ee';

    /**
     * Error message
     * @var string
     */
    private $error;    

	/**
	 * Instantiate object
	 */
	public function __construct() {
		$this->token = textural_get_option('instagram_token');
        if ( strlen($this->token) == 0 )
            $this->token = false;
	}

	/**
	 * Display Instagram gallery
	 * @param string $user_id
	 * @param array $args
	 * @param bool $echo
	 * @return string or void, if echo set to true
	 */
	public function display_gallery($user_id, $args = array(), $echo = true) {
		$images = $this->get_photos( $user_id, $args );

		if ( $images === false || empty($images) ) {

            if ( $echo ) 
                echo $this->get_error();

            return $this->get_error();            
        }

		// Start creating output
		$output = sprintf( '<ul class="colorbox_gallery %s" data-album="instagram_gallery">', array_key_exists('class', $args) ? $args['class'] : '' );

		// Get image size to display
		$image_size = $this->get_image_size( $args );

		foreach ( $images->data as $i => $image ) {			
			$caption = $image->caption ? $image->caption->text : '';
			$output .= sprintf( '<li><a href="%s" title="%s" class="instagram_gallery"><img src="%s" title="%s" alt="%s" class="fetched-%d" %s %s /></a></li>', 
				$image->images->standard_resolution->url, 
				$caption, 
				$image_size == 't' ? $image->images->thumbnail->url : ( $image_size == 's' ? $image->images->low_resolution->url : $image->images->standard_resolution->url ) , 
				$caption, 
				$caption,
                $i,
				array_key_exists( 'height', $args ) ? "height={$args['height']}" : '',
                array_key_exists( 'width', $args ) ? "width={$args['width']}" : '' );
		}

		$output .= '</ul>';

		if ( $echo === false )
			return $output;

		echo $output;
	}

    /** 
     * The shortcode
     * @param array $atts
     * @return string
     */
    public function shortcode($atts = array()) {
        // Double-check that api key has been set
        if ( $this->token === false )
            return __( 'Unable to fetch Instagram photos. No access token set in theme settings.', 'textural' );

        // Token is set, so go ahead and set up shortcode settings
        $args = shortcode_atts( array(
            'count' => 12, 'username' => '', 'class' => 'instagram_gallery', 'height' => 75, 'width' => 75, 'cache' => 60
        ), $atts);

        // Verify username length
        if ( strlen($args['username']) < 2 )
            return __( 'Invalid Instagram username.', 'textural' );

        // Get user ID
        $user_id = $this->get_user_id($args['username']);
        if ( $user_id === false )
            return $this->get_error();

        // Get display
        return $this->display_gallery($user_id, $args, false);
    }   	

	/**
     * Get the Instagram User ID 
     * @param string $username
     * @return int
     */
    public function get_user_id($username) {
    	if ( $this->token === false ) {
            $this->set_error( __( 'No Instagram access token set.', 'textural' ) );
    		return false;
        }

    	// Check cache for response
        $transient_key = sprintf( "instagram_user_%s", $username );
        $user = get_transient( $transient_key );

        if ( $user === false ) {
        	$url = sprintf( "https://api.instagram.com/v1/users/search?q=%s&access_token=%s", $username, $this->token );
	        $response = wp_remote_get( $url );

	        if ( is_wp_error( $response ) ) {
                $this->set_error( __( 'Invalid Instagram username.', 'textural' ) );
                return false;
            }

	        $response = json_decode( $response['body'], true );
	        $user = $response['data'][0];
	        set_transient( $transient_key, $user, 0 );
        }
        
        return $user['id'];
    }

    /**
     * Get photos
     * @param string $user_id
     * @param array $args
     * @return array
     */
    protected function get_photos($user_id, $args = array()) {
    	if ( $this->token === false )
    		return array();

    	// Parse arguments
    	$defaults = array( 'count' => 12, 'cache' => 60 );
    	extract( array_merge( $defaults, $args ));

    	// Check cache
    	$transient_key = sprintf( "instagram_%d_%d", $user_id, $count );
		$images = get_transient( $transient_key );
        $images = false;

		if ( $images === false ) {
			$url = sprintf( "https://api.instagram.com/v1/users/%d/media/recent/?access_token=%s&count=%d", $user_id, $this->token, $count );
			$response = wp_remote_get( $url );

			// Check that the object is created correctly 
            if ( is_wp_error( $response ) ) {                 
                $this->set_error( __( 'Unable to load images from Instagram', 'textural' ) );
                return false;
            }

            $images = json_decode( $response['body'] );

            set_transient( $transient_key, $images, 60 * $cache ); // Cached for one hour
		}

		return $images;
    }

    /**
     * Get images size, based on height and width attributes
     * @param array $args
     * @return char
     */
    protected function get_image_size($args) {
    	if ( !array_key_exists('width', $args) && !array_key_exists('height', $args) )
    		return 'l';

    	// If width is set, return size based on that
    	if ( array_key_exists('width', $args) ) {
    		if ( $args['width'] <= 150 )
    			return 't';

    		if ( $args['width'] <= 306 )
    			return 's';

    		return 'l';
    	}
    	// If height is set, return size based on that
    	elseif ( array_key_exists('height', $args) ) {
    		if ( $args['height'] <= 150 )
    			return 't';

    		if ( $args['height'] <= 306 )
    			return 's';

    		return 'l';
    	}
    }

    /**
     * Set error message
     * @param string $message
     * @return void
     */
    protected function set_error($message) {
        $this->error = $message;
    }

    /**
     * Get error message
     * @return string
     */
    public function get_error() {
        return $this->error;
    }    
}