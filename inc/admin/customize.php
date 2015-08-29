<?php

/**
 * Sets up the Wordpress customizer
 *
 * @since 1.2
 */

class OSFA_Customizer {

	/**
     * Stores instance of class.
     * @var OSFA_Custuomizer
     */
    private static $instance = null;

	private function __construct() {
		add_action('customize_register', array(&$this, 'customize_register'));        
        add_action('customize_preview_init', array(&$this, 'customize_preview_init') ); 
        add_action('wp_head', array(&$this, 'wp_head'));
	}

	/**
     * Get class instance
     * @static
     * @return OSF_Customizer
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
          self::$instance = new OSFA_Customizer();
        }
        return self::$instance;
    }

    /**
     * Theme customization
     * @return void
     */
    public function customize_register($wp_customize) {
        /** 
         * Title & tagline section
         */
        $wp_customize->add_setting( 'logo_url', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'retina_logo_url', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'hide_site_title', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'hide_site_tagline', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_url',
            array(
                'settings' => 'logo_url',
                'section'  => 'title_tagline',
                'label'    => __( 'Logo', 'textural' )
            ) )
        );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'retina_logo_url',
            array(
                'settings' => 'retina_logo_url',
                'section'  => 'title_tagline',
                'label'    => __( 'Retina version of logo (2x)', 'textural' )
            ) )
        );        
        $wp_customize->add_control( 'hide_site_title', array(
            'settings' => 'hide_site_title', 
            'label' => __( 'Hide the site title', 'textural' ),
            'section' => 'title_tagline', 
            'type' => 'checkbox'            
        ) );
        $wp_customize->add_control( 'hide_site_tagline', array(
            'settings' => 'hide_site_tagline', 
            'label' => __( 'Hide the tagline', 'textural' ),
            'section' => 'title_tagline', 
            'type' => 'checkbox'            
        ) );

        /** 
         * Colors
         */
        $wp_customize->add_setting( 'accent_colour' , array( 'default' => '79BD9A', 'transport'   => 'postMessage' ) );
        $wp_customize->add_setting( 'skin', array( 'default' => 'gray_jean', 'transport' => 'postMessage') );        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_colour', array(
            'label'        => __( 'Accent Colour', 'textural' ),
            'section'    => 'colors',
            'settings'   => 'accent_colour',
        ) ) );
        $wp_customize->add_control( 'skin', array(
            'settings' => 'skin',
            'label' => __( 'Skin', 'textural' ), 
            'section' => 'colors', 
            'type' => 'select', 
            'choices' => array(
                'light' => __( 'Light', 'textural' ),
                'dark' => __( 'Dark', 'textural' )                
            )
        ) );

        /**
         * Nav
         */
        $wp_customize->add_setting( 'show_search', array( 'default' => 1, 'transport' => 'postMessage' ) );
        $wp_customize->add_control( 'show_search', array(
            'settings' => 'show_search', 
            'label' => __( 'Show search', 'textural' ),
            'section' => 'nav', 
            'type' => 'checkbox'            
        ) );

        /**
         * Social
         */ 
        $wp_customize->add_section( 'social', array( 
            'priority' => 103, 
            'title' => __( 'Social', 'textural' ),
            'description' => __( 'Set up links to your online social presences', 'textural' )
        ) );

        // Loop over all the social sites the theme supports, creating settings and controls for each one
        foreach ( osfa_get_social_sites() as $setting_key => $label ) {
            $wp_customize->add_setting( $setting_key, array( 'transport' => 'postMessage' ) );
            $wp_customize->add_control( $setting_key, array( 
                'settings' => $setting_key,
                'label' => $label, 
                'section' => 'social', 
                'type' => 'text'
            ) );
        }
    }    

    /**
     * Used by hook: 'customize_preview_init'
     * 
     * @see add_action('customize_preview_init',$func)
     */
    public function customize_preview_init() {
        ?>
        <script>
        var OSFA = {
            theme_dir : "<?php echo get_template_directory_uri() ?>",
            accent_colour : "<?php echo get_theme_mod('accent_colour', '#79BD9A') ?>"
        };
        </script>
        <?php
        wp_register_script( 'theme-customizer', get_template_directory_uri().'/media/js/theme-customize.js', array( 'jquery', 'customize-preview' ), 0.1, true );
        wp_enqueue_script( 'theme-customizer' );        
    }     

    /**
     * Insert output into end of <head></head> section
     * @return void
     */
    public function wp_head() {
        $template_directory = get_template_directory_uri();        
        $accent_colour = get_theme_mod('accent_colour', false);
        $logo = get_theme_mod('logo_url', false);
        
        $has_logo = $logo !== false && ( strlen( $logo ) > 0 );

        if ( $has_logo ) {
            $logo_post_id = osfa_get_image_id_from_url( $logo );
            $logo_meta = wp_get_attachment_metadata( $logo_post_id );            

            // Get the retina version (if there is one)
            $retina_logo = get_theme_mod('retina_logo_url', false);
            $has_retina_logo = $retina_logo !== false && ( strlen( $retina_logo ) > 0 );
        }
        ?>

        <!-- Theme Customizations -->
<style type="text/css">    

<?php 
// Accent colour replacement
if ( $accent_colour !== false ) : ?>

    a:hover,
    a:active, 
    a:focus,
    .highlight,
    .button,
    button,
    input[type="submit"],
    input[type="reset"],
    input[type="button"],
    .button:hover,
    button:hover,
    input[type="submit"]:hover,
    input[type="reset"]:hover,
    input[type="button"]:hover,
    .flex-prev,
    .flex-next,
    .sharrre .middle a:hover { background-color:<?php echo $accent_colour ?>; }
    *::selection { background-color:<?php echo $accent_colour ?>; }
    *::-moz-selection { background-color:<?php echo $accent_colour ?>; }

    #primary_navigation a:hover,
    article h1,
    article h2,
    article h3 { color:<?php echo $accent_colour ?>; }

<?php endif ?>    

<?php 
// Logo replacement
if ( $has_logo ) : ?>

    .logo {
        background:url(<?php echo $logo ?>) no-repeat top left;
        width: <?php echo $logo_meta['width'] ?>px;
        height: <?php echo $logo_meta['height'] ?>px;
        border-radius: 0; -moz-border-radius: 0;
    }   
    /* Retina version */
    @media only screen and (-Webkit-min-device-pixel-ratio: 1.5), only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) {
        <?php if ( $has_retina_logo ) : ?>
            .logo { 
                background-image:url(<?php echo $retina_logo ?>);
                background-size: <?php echo $logo_meta['width'] ?>px <?php echo $logo_meta['height'] ?>px;
            }
        <?php else : ?>
            .logo { 
                background-size: <?php echo $logo_meta['width'] ?>px <?php echo $logo_meta['height'] ?>px; 
            }
        <?php endif ?>
    }

<?php elseif ( $accent_colour !== false ) : ?>

    .logo { background-color:<?php echo $accent_colour ?>; }

<?php endif ?>

</style>
        <?php
    }    

}

OSFA_Customizer::get_instance();