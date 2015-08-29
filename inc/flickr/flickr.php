<?php

/**
 * A class for displaying Flickr images
 */
class OSFA_Flickr {

    /** 
     * Flickr API key
     * @var string
     */
    private $api_key;

    /**
     * Error message
     * @var string
     */
    private $error;

    /**
     * Instantiate object
     */
    public function __construct() {
        $this->api_key = textural_get_option('flickr_api_key');
        if ( strlen($this->api_key) == 0 )
            $this->api_key = false;
    }

    /** 
     * Displays the photos     
     * @param string $user_id
     * @param array $args
     * @param bool $echo    
     * @return string|void
     */
    public function display_gallery($user_id, $args = array(), $echo = true) {
        $photos = $this->get_photos( $user_id, $args );

        // Display photos
        if ($photos) {

            $html = sprintf( '<ul class="colorbox_gallery %s" data-album="flickr_gallery">'.PHP_EOL, array_key_exists('class', $args) ? $args['class'] : '' ); 
            
            foreach ($photos as $i => $photo) {

                $html .= sprintf( '<li><a href="%s"><img src="%s" alt="%s" title="%s" class="size_%s flickr_gallery fetched-%d" %s %s /></a></li>'.PHP_EOL, 
                    $photo['large'], 
                    $photo['src'], 
                    $photo['title'], 
                    $photo['title'], 
                    $args['size'], 
                    $i, 
                    array_key_exists( 'height', $args ) ? "height={$args['height']}" : '',
                    array_key_exists( 'width', $args ) ? "width={$args['width']}" : '' );
            }

            $html .= '</ul>'.PHP_EOL;

        } 
        else {
            $html = sprintf( __( '%sNo photos found for Flickr user.%s', 'hitched' ), '<p>', '</p>' );
        }

        // Return HTML, or output it
        if ( $echo === false )
            return $html;

        echo $html;
    }

    /** 
     * The shortcode
     * @param array $atts
     * @return string
     */
    public function shortcode($atts = array()) {
        // Double-check that api key has been set
        if ( $this->api_key === false )
            return __( 'Unable to fetch Flickr feed. No API Key set in theme settings.', 'hitched' );

        // API key is set, so go ahead and set up shortcode settings
        $args = shortcode_atts( array(
            'count' => 12, 'size' => 's', 'username' => '', 'class' => 'flickr_gallery', 'height' => '', 'width' => ''
        ), $atts);

        // Verify username length
        if ( strlen($args['username']) < 2 )
            return __( 'Invalid Flickr username.', 'hitched' );

        // Get user ID
        $user_id = $this->get_flickr_id($args['username']);
        if ( $user_id === false )
            return __( 'Invalid Flickr username.', 'hitched' );

        // Get display
        return $this->display_gallery($user_id, $args, false);
    }    

    /**
     * Get Flickr ID
     * @param string $username
     * @return int
     */
    public function get_flickr_id( $username ) {
        // Double-check that api key has been set
        if ( $this->api_key === false ) {
            $this->set_error( __( 'No Flickr API key set.', 'hitched' ) );
            return false;
        }

        // Check cache for response
        $transient_key = sprintf( "flickr_user_%s", $username );
        $user = get_transient( $transient_key );

        // If cache doesn't have it, get it and save to cache
        if ($user === false) {            
            $response = $this->request('flickr.people.findByUsername', array('username' => $username));

            if ($response === false) {
                $this->set_error( __( 'Invalid Flickr username', 'hitched' ) );
                return false;
            }

            $user = unserialize($response['body']);    
            set_transient($transient_key, $user, 0); // Transient never expires, unless explicitly killed
        }
            
        return $user['user']['id'];
    }    

    /**
     * Get photos to display
     * @param string $user_id
     * @param array $args
     * @return array
     */
    protected function get_photos( $user_id, $args ) {
        // Double-check that api key has been set
        if ( $this->api_key === false )
            return false;            

        // Merge arguments with defaults
        extract( array_merge( array( 'count' => 12, 'size' => 's', 'cache' => 60 ), $args ) );

        // Configuration
        $transient_key = sprintf( "flickr_%s_%d_%s", $user_id, $count, $size );
        $photos = get_transient($transient_key);

        // No photos cached, so we run the api call
        if ($photos === false) {
            $response = $this->request('flickr.people.getPublicPhotos', array('user_id' => $user_id, 'per_page' => $count));
            $response = unserialize($response['body']);

            foreach ( $response['photos']['photo'] as $photo) {
                $photos[] = array(
                    'src' => 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_'.$size.'.jpg',
                    'large' => 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_b.jpg',
                    'title' => $photo['title']
                );                          
            }      

            // Store photos for 60 minutes (60 * 60)
            set_transient($transient_key, $photos, $cache * 60);          
        }        

        return $photos;
    }

    /**
     * Perform API request
     * @param string $method
     * @param array $args
     * @return false on failure, object if successful
     */
    protected function request($method, $params) {
        if (!strlen($method)) {
            wp_die('Flickr API method must be specified.');
        }            
        
        $params['api_key'] = $this->api_key;
        $params['method'] = $method;
        $params['format'] = 'php_serial';
        
        $encoded_params = array();
        
        foreach ($params as $key => $value) {
            $encoded_params[] = urlencode($key).'='.urlencode($value);
        }
        
        // Make the call
        $response = wp_remote_get("http://api.flickr.com/services/rest/?".implode('&', $encoded_params));

        if ( is_wp_error( $response ) ) { 
            return false;
        }

        return $response;
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