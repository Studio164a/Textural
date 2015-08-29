<?php
 
class OSFA_Twitter_Widget extends WP_Widget {
  
    function OSFA_Twitter_Widget() {
        $widget_ops = array(
            'classname' => 'osfa_twitter_widget', 
            'description' => __('Display your most recent Twitter posts.', 'textural')
        );
        
        $this->WP_Widget('OSFA_Twitter_Widget', __('Twitter by 164a', 'textural'), $widget_ops);
    }
 
    function form($instance) {
        $defaults = array( 
            'title' => __('Tweets', 'textural'), 
            'count' => 5, 
            'retweets' => false,
            'replies' => false, 
            'username' => '',
            'access_token' => ''
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $title = $instance['title'];
        $username = $instance['username'];
        $count = $instance['count'];
        $retweets = $instance['retweets'];
        $replies = $instance['replies'];
        ?>
        <?php if ( !$this->has_authorized() ) : ?>

            <p>
                <strong><?php printf( __( 'Before using the Twitter widget, you need to set up Twitter integration on the %stheme settings page%s.', 'textural' ), '<a href="'.admin_url('themes.php?page=textural-options').'">', '</a>' ) ?></strong>
            </p>

        <?php else : ?> 

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'textural') ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username:', 'textural') ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo esc_attr($username); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of Tweets:', 'textural') ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('count') ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count ?>" />                
                </label>
            </p>    
            <p>
                <label for="<?php echo $this->get_field_id('retweets') ?>"><?php _e('Include retweets:', 'textural') ?>
                    <input id="<?php echo $this->get_field_id('retweets') ?>" name="<?php echo $this->get_field_name('retweets') ?>" value="1" <?php checked( $retweets ) ?> type="checkbox" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('replies') ?>"><?php _e('Include replies:', 'textural') ?>
                    <input id="<?php echo $this->get_field_id('replies') ?>" name="<?php echo $this->get_field_name('replies') ?>" value="1" <?php checked( $replies ) ?> type="checkbox" />
                </label>
            </p>
        <?php
        endif;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['username'] = $new_instance['username'];
        $instance['count'] = $new_instance['count']; 
        $instance['retweets'] = isset( $new_instance['retweets'] ) ? $new_instance['retweets'] : false;
        $instance['replies'] = isset( $new_instance['replies'] ) ? $new_instance['replies'] : false;        
        return $instance;
    }
 
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
 
        // A username is required. Return doing nothing without this.
        if ( empty($instance['username']) ) 
            return;

        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $name = $instance['username'];
        $count = empty($instance['count']) ? 3 : $instance['count'];
        $retweets = empty($instance['retweets']) ? false : $instance['retweets'];
        $replies = empty($instance['replies']) ? false : $instance['replies'];

        if (!empty($title)) {
          echo $before_title . $title . $after_title;;
        }

        // User has not provided all the required authorization details
        if ( $this->has_authorized() === false ) {
            echo '<p>' . __( 'Missing Twitter app credentials.', 'textural' ) . '</p>';            
        }
        else {                    

            $transient_key = sprintf( "twitter_%s_%d", $name, $count );

            // Try to fetch tweets from transient
            $tweets = get_transient($transient_key);

            if ($tweets === false) {

                $twitter = new TwitterOAuth(textural_get_option('twitter_consumer_key'), textural_get_option('twitter_consumer_secret'), textural_get_option('twitter_token'), textural_get_option('twitter_token_secret'));

                $tweets = $twitter->get('statuses/user_timeline', array( 
                    'screen_name' => $name, 
                    'count' => $count, 
                    'include_entities' => 1, 
                    'include_rts' => $retweets, 
                    'exclude_replies' => !$replies
                ) );

                set_transient( $transient_key, $tweets, 60 * 5 ); // Cached for 5 minutes
            }

            if ($tweets) {
            ?>        
    	
            <ul>
                <?php foreach ( $tweets as $t ) : ?>

                <?php $tweet_text = $this->get_parsed_tweet_text( $t ) ?>
                
                <li>
                    <p class="tweet"><?php echo $tweet_text ?></p>
                    <p class="time_ago"><?php printf( "%s %s", human_time_diff( strtotime($t->created_at), current_time('timestamp') ), _x( 'ago', 'time since item was posted', 'textural' ) ) ?></p>
                </li>

                <?php endforeach ?>

                <li class="floatright"><?php printf( '<a href="%s" title="%s"><i class="icon-twitter"></i> %s</a>', "http://twitter.com/$name", _x( 'Click to go to our Twitter profile', 'twitter profile link title attribute', 'textural' ), __( 'Follow us on Twitter', 'textural' ) ) ?></li>
            </ul>        

            <?php        
            }

        }

        echo $after_widget;        
    }

    /**
     * Returns tweet text with links parsed
     * @return string
     */
    protected function get_parsed_tweet_text($tweet) {
        // Deal with HTML entities
        $output = htmlentities(html_entity_decode($tweet->text, ENT_NOQUOTES, 'UTF-8'), ENT_NOQUOTES, 'UTF-8');

        // Parse URLs
        foreach ($tweet->entities->urls as $url) {
            $output = str_replace( $url->url, $this->get_parsed_twitter_link( $url, 'url' ), $output );
        }

        // Parse hashtags
        foreach ($tweet->entities->hashtags as $hashtags) {
            $output = str_ireplace( '#'.$hashtags->text, $this->get_parsed_twitter_link( $hashtags, 'hashtag' ), $output);
        }

        // Parse usernames
        foreach ($tweet->entities->user_mentions as $user_mentions) {
            $output = str_ireplace( '@'.$user_mentions->screen_name, $this->get_parsed_twitter_link( $user_mentions, 'user_mention' ), $output );
        }

        // Parse media URLs
        if ( array_key_exists( 'media', $tweet->entities ) ) {            
            foreach ($tweet->entities->media as $media) { 
                $output = str_ireplace( $media->url, $this->get_parsed_twitter_link( $media, 'media' ), $output );
            }
        }

        return $output;
    }

    /** 
     * Return the parsed Twitter link     
     * @return string
     */
    protected function get_parsed_twitter_link( $entity, $type ) {   
        $target = apply_filters( 'jnewsticker_open_links_in_new_window', false ) === true ? ' target="_blank"' : '';        

        switch ( $type ) {
            case 'url': 
            case 'media':
                return '<a href="' . $entity->url . '"' . $target . '>' . $entity->display_url . '</a>';
                break;
            case 'user_mention':
                return '<a href="https://twitter.com/#!/' . $entity->screen_name . '"' . $target . '>@' . $entity->screen_name . '</a>';
                break;
            case 'hashtag':
                return '<a href="https://twitter.com/#!/search/' . $entity->text . '"' . $target . '>#' . $entity->text . '</a>';                
                break; 
        }
    }

    /**
     * Returns whether the user has provided the required authorization details.
     * 
     * @return bool
     */
    protected function has_authorized() {
        return textural_get_option('twitter_token') 
            && textural_get_option('twitter_token_secret') 
            && textural_get_option('twitter_consumer_secret') 
            && textural_get_option('twitter_consumer_key');
    }
}