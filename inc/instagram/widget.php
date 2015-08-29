<?php
/*
Plugin Name: Instawidget
Plugin URI: 
Description: A simple Instagram widget, enabling you to easily 
Version: 0.1
Author: Studio164
Author URI: http://164a.com
*/
class OSFA_Insta_Widget extends WP_Widget {

	public function OSFA_Insta_Widget() {
		$widget_ops = array(
	        'classname' => 'osfa_insta_widget', 
	        'description' => __('Display your most recent Instagram photos.', 'textural')
	    );
	    
	    $this->WP_Widget('OSFA_Insta_Widget', __('Instagram by 164a', 'textural'), $widget_ops);
	}

	public function form($instance) {	   
	    $instance = wp_parse_args((array) $instance, array( 'title' => '', 'username' => '', 'count' => 20, 'token' => '', 'user_id' => '' ));

	    $title = $instance['title'];
	    $username = $instance['username'];
	    $count = $instance['count'];
		?>

		<p>
	    	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'textural') ?> 
	        	<input class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo esc_attr($title) ?>" />
	    	</label>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:', 'textural') ?> 
	        	<input class="widefat" id="<?php echo $this->get_field_id('username') ?>" name="<?php echo $this->get_field_name('username') ?>" type="text" value="<?php echo esc_attr($username) ?>" />
	    	</label>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('count') ?>"><?php _e('Number of photos to show:', 'textural') ?>
	    		<input class="widefat" id="<?php echo $this->get_field_id('count') ?>" name="<?php echo $this->get_field_name('count') ?>" type="text" value="<?php echo $count ?>" />
	    	</label>
	    </p>

		<?php
	}
 
  	public function update($new_instance, $old_instance) {	
		$instance = $old_instance;
    	$instance['title'] = $new_instance['title'];
    	$instance['username'] = trim( $new_instance['username'] );
    	$instance['count'] = $new_instance['count'];

    	return $instance;
  	}
 
  	public function widget($args, $instance) {
    	extract($args, EXTR_SKIP);
     	
     	// Collect variables
	    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);	    
	    $count = empty($instance['count']) ? 12 : $instance['count'];
	    $username = $instance['username'];	    	    

        // Get Instagram object
        $instagram = new OSFA_Instagram();    
        $instagram_id = $instagram->get_user_id($username);    

	    // Start creating widget output
	    echo $before_widget;
	 
	    if (!empty($title)) {
	    	echo $before_title . $title . $after_title;;
	    }	    	    

	    // Verify username length
        if ( strlen($username) < 2 ) {        	
            echo '<p>' . __( 'Invalid Instagram username.', 'textural' ) . '</p>';
        }
		else {
			// Ensure it's a valid Instagram ID
		    if ( $instagram_id === false ) {
		    	echo '<p>' . $instagram->get_error() . '</p>';
		    }
		    else {
		    	$instagram->display_gallery( $instagram_id, array( 'count' => $count, 'height' => 81, 'width' => 81 ) );
		    }
		}	    
                   
        echo $after_widget;
    }
}