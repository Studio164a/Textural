<?php

/**
 * Admin theme settings page
 *
 * @author Eric Daams <eric@nicolaas.com>
 * @since 1.2
 */

class OSFA_Theme_Settings { 

    /**
    * Display admin page
    */
    public function display() {
        ?>

        <div class="wrap">

            <div id="icon-themes" class="icon32"><br /></div>   
            <h2><?php _e( 'Textural Theme Options', 'textural' ) ?></h2>         

            <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
                <div class='updated'><p><?php _e( 'Theme settings updated successfully.', 'textural' ) ?></p></div>
            <?php endif ?>

            <!-- Start form -->
            <form action="options.php" method="post">

                <?php settings_fields( 'theme_textural_options' ) ?>
                <?php do_settings_sections( 'textural' ) ?>

                <p class="submit">
                    <button name="theme_textural_options[do-action]" value="submit" class="button-primary"><?php esc_attr_e( 'Save Settings', 'textural' ) ?></button>
                    <button name="theme_textural_options[do-action]" value="reset" class="button-secondary"><?php esc_attr_e( 'Reset Defaults', 'textural' ) ?></button>
                </p>

            </form>
            <!-- End form -->

        </div>

        <?php
    }  

    /**
     * General settings tab
     * @return void
     */
    public function get_general_tab() {
        // Sections        
        add_settings_section( 'textural_settings_general_ganalytics', __( 'Google Analytics', 'textural' ), array( &$this, 'get_ganalytics_section' ), 'textural' );
        add_settings_section( 'textural_settings_general_integrations', __( 'Flickr & Instagram', 'textural' ), array( &$this, 'get_integrations_section' ), 'textural' );
        add_settings_section( 'textural_settings_general_twitter', __( 'Twitter', 'textural' ), array( &$this, 'get_twitter_section' ), 'textural' );

        // Scripts
        add_settings_field( 'textural_settings_ganalytics_field', __( 'Google Analytics', 'textural' ), array( &$this, 'get_ganalytics_field' ), 'textural', 'textural_settings_general_ganalytics' );

        // Plugin integrations        
        add_settings_field( 'textural_settings_instagram_token_field', __( 'Instagram access token', 'textural' ), array( &$this, 'get_instagram_token_field' ), 'textural', 'textural_settings_general_integrations' );
        add_settings_field( 'textural_settings_flickr_api_key_field', __( 'Flickr API key', 'textural' ), array( &$this, 'get_flickr_api_key_field' ), 'textural', 'textural_settings_general_integrations' );

        // Twitter integration
        add_settings_field( 'textural_settings_twitter_consumer_key_field', __( 'Consumer key', 'textural' ), array( &$this, 'get_twitter_consumer_key_field' ), 'textural', 'textural_settings_general_twitter' );
        add_settings_field( 'textural_settings_twitter_consumer_secret_field', __( 'Consumer secret', 'textural' ), array( &$this, 'get_twitter_consumer_secret_field' ), 'textural', 'textural_settings_general_twitter' );
        add_settings_field( 'textural_settings_twitter_token_field', __( 'Access token', 'textural' ), array( &$this, 'get_twitter_token_field' ), 'textural', 'textural_settings_general_twitter' );
        add_settings_field( 'textural_settings_twitter_token_secret_field', __( 'Access token secret', 'textural' ), array( &$this, 'get_twitter_token_secret_field' ), 'textural', 'textural_settings_general_twitter' );
    }

    /**
     * Google Analytics section
     * @return void
     */
    public function get_ganalytics_section() {}

    /**
     * Integrations section
     * @return void
     */
    public function get_integrations_section() {}

    /**
     * Twitter section
     * @return void
     */
    public function get_twitter_section() {
        ?>
        <p><?php _e( 'In order to use the Twitter widget, you are required to create an app through Twitter. Follow these simple steps:', 'textural' ) ?></p>
        <ol>
            <li><?php printf( __( 'Go to %s to register your app. You may be asked to log in.', 'textural' ), '<a href="https://twitter.com/apps" target="_blank">https://twitter.com/apps</a>' ) ?></li>
            <li><?php printf( __( 'Once you are logged in, click the button that says %sCreate a new application%s.', 'textural' ), '<strong>', '</strong>' ) ?></li>
            <li><?php _e( 'Complete the form, tick the terms and conditions and solve the Captcha. Create your application.', 'textural' ) ?></li>
            <li><?php _e( 'You have now created a Twitter application. The final step is to create an access token. Do this by clicking the button at the bottom of the page.', 'textural' ) ?></li>
            <li><?php _e( 'Finally, copy and paste the relevant details about your Twitter application into the fields below.', 'textural' ) ?></li>
        </ol>
        <?php 
    }

    /**
     * Google Analytics field
     * @return void
     */
    public function get_ganalytics_field() {
        $options = $this->get_options();
        ?>
        <textarea name="theme_textural_options[analytics]" rows="10" cols="60"><?php echo stripslashes( $options['analytics'] ) ?></textarea>
        <?php
    }

    /**
     * Twitter consumer key
     * @return void
     */
    public function get_twitter_consumer_key_field() {
        $options = $this->get_options();
        ?>
        <input name="theme_textural_options[twitter_consumer_key]" type="text" value="<?php echo $options['twitter_consumer_key'] ?>" />        
        <?php
    }

    /**
     * Twitter consumer secret
     * @return void
     */
    public function get_twitter_consumer_secret_field() {
        $options = $this->get_options();
        ?>
        <input name="theme_textural_options[twitter_consumer_secret]" type="text" value="<?php echo $options['twitter_consumer_secret'] ?>" />        
        <?php
    }

    /**
     * Twitter token
     * @return void
     */
    public function get_twitter_token_field() {
        $options = $this->get_options();
        ?>
        <input name="theme_textural_options[twitter_token]" type="text" value="<?php echo $options['twitter_token'] ?>" />        
        <?php
    }  

    /**
     * Twitter token secret
     * @return void
     */
    public function get_twitter_token_secret_field() {
        $options = $this->get_options();
        ?>
        <input name="theme_textural_options[twitter_token_secret]" type="text" value="<?php echo $options['twitter_token_secret'] ?>" />        
        <?php
    }  

    /**
     * Instagram token field
     * @return void
     */
    public function get_instagram_token_field() {
        $options = $this->get_options();
        
        // Add thickbox
        add_thickbox()
        ?>
        <input name="theme_textural_options[instagram_token]" type="text" value="<?php echo $options['instagram_token'] ?>" />
        <p>
            <?php printf( __( 'Don\'t have an access token for Instagram? %sGet one here.%s', 'textural' ), '<a href="http://164a.com/authenticate/instagram/osf_theme.php" target="_blank">', '</a>' ) ?>
        </p>
        <?php 
    }

    /**
     * Flickr API key field
     * @return void
     */
    public function get_flickr_api_key_field() {
        $options = $this->get_options();
        ?>
        <input name="theme_textural_options[flickr_api_key]" type="text" value="<?php echo $options['flickr_api_key'] ?>" />
        <p>
            <?php printf( __( 'You will require a Flickr API Key to use the Flickr widget or shortcode. %sGet one from Flickr.%s', 'textural' ), '<a href="http://www.flickr.com/services/apps/create/apply/" target="_blank">', '</a>') ?>
        </p>
        <?php 
    }    
    /**
     * Sanitize option's value
     */
    public function validate( $input ) {
        $options = $this->get_options();

        // Get action (either "submit" or "reset")
        $action = $input['do-action'];
            
        if ( $action == 'submit' ) {
            unset( $input['do-action']);

            $input = array_merge( $options, $input );
        } 
        elseif ( $action == 'reset' ) {
            $input = $this->get_default_theme_options();
        }

        return $input;
    }

    /**
     * Get current options
     * @return array
     */
    public function get_options() {
        if ( !isset( $this->options ) ) {
            $this->options = get_option( 'theme_textural_options', false );
            if ( $this->options === false )
                $this->options = $this->get_default_theme_options();
        }
        return $this->options;
    }

    /**
     * Get default theme settings
     * @return array
     */
    public function get_default_theme_options() {
        return array(
            'analytics' => '', 
            'flickr_api_key' => '',
            'instagram_token' => '',
            'twitter_token' => '',
            'twitter_token_secret' => '', 
            'twitter_consumer_secret' => '',
            'twitter_consumer_key' => '',
            'theme_version' => '1.5'
        );
    }    
}