<?php 
/**
 * Bootstraps the theme and all its associated functionality. 
 * 
 * This class can only be instantiated once and cannot be extended.
 * 
 * @author Eric Daams <eric@ericnicolaas.com>
 * @final
 */

class OSFBootstrap {

	/**
    * Stores instance of class.
    * @var OSFBootstrap
    */
    private static $instance = null;

    /**
     * Theme database version
     * @var string
     */
    private $theme_db_version;  

    /**
     * Private constructor. Singleton pattern.
     */
	private function __construct() { 
        // Include other files
        require_once('inc/comments.php');
        require_once('inc/shortcodes.php');
        require_once('inc/helpers.php');
        require_once('inc/template-tags.php');

        // Twitter
        require_once('inc/twitter/widget.php');
        require_once('inc/twitter/twitteroauth/twitteroauth.php');

        // FontAwesome
        require_once('inc/fontawesome/wp-fontawesome.php');        

        // Flickr class
        require_once('inc/flickr/widget.php');
        require_once('inc/flickr/flickr.php');

        // Instagram class
        require_once('inc/instagram/widget.php');
        require_once('inc/instagram/instagram.php');

        // Admin classes
        require_once('inc/admin/customize.php');
        require_once('inc/admin/theme-settings.php');

        // Check for theme update
        $this->theme_db_version = mktime(23,18,0,3,1,2013);
        $this->version_update();

        add_action('wp_head', array(&$this, 'wp_head'));
        add_action('after_setup_theme', array(&$this, 'after_setup_theme'));
        add_action('widgets_init', array(&$this, 'widgets_init'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('wp_default_scripts', array(&$this, 'wp_default_scripts') );
        add_action('wp_print_footer_scripts', array(&$this, 'wp_print_footer_scripts') );
        
		if ( !is_admin() )
			add_action('wp_enqueue_scripts', array(&$this, 'load_scripts_and_styles'));

        // Filters
        add_filter('single_template', array(&$this, 'single_template'));
        add_filter('the_content_more_link', array(&$this, 'the_content_more_link'), 10, 2);
        add_filter('post_gallery', 'osfa_gallery_shortcode', 10, 2);
        add_filter('post_class', array(&$this, 'post_class'));
        add_filter('next_posts_link_attributes', array(&$this, 'next_posts_link_attributes'));
        add_filter('previous_posts_link_attributes', array(&$this, 'previous_posts_link_attributes'));
        add_filter('wp_nav_menu_items', array(&$this, 'wp_nav_menu_items'), 10, 2);        
        add_filter('image_send_to_editor', array(&$this, 'enable_colorbox'), 10, 6);
        add_filter('wp_title', array(&$this, 'wp_title'), 10, 2);

        // Shortcodes
        add_shortcode('column', 'osfa_column_shortcode');
        add_shortcode('button', 'osfa_button_shortcode');
        add_shortcode('notification', 'osfa_notification_shortcode');
        add_shortcode('flickr', 'osfa_flickr_shortcode');
        add_shortcode('instagram', 'osfa_instagram_shortcode');
	}

	/**
     * Get class instance
     * @static
     * @return OSFBootstrap
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
          self::$instance = new OSFBootstrap();
        }
        return self::$instance;
    } 

    /**
     * Check for plugin version update
     */
    public function version_update() {
        // Check whether we are updated to the most recent version
        $db_version = get_option('textural_db_version');
        if ( $db_version === false || $db_version < $this->theme_db_version ) {                
            require_once('inc/upgrade.php');        
            OSFUpgradeHelper::do_upgrade($this->theme_db_version, $db_version);
            update_option('textural_db_version', $this->theme_db_version);
        }    
    }    


    /**
     * Enqueue stylesheets and scripts
     * @return void
     */
    public function load_scripts_and_styles() {      
    	// Theme directory
        $theme_dir = get_template_directory_uri();

        // Stylesheets
        wp_register_style('reset', get_bloginfo('stylesheet_url'));
        wp_enqueue_style('reset');        

        $stylesheets = array( 
            'colorbox' => 'colorbox', 
            'skin' => get_theme_mod('skin', 'light'), 
            'colour' => 'teal' 
        );

        foreach ( $stylesheets as $id => $stylesheet ) {
            wp_register_style( $id, sprintf( "%s/media/css/%s.css", $theme_dir, $stylesheet ) );
            wp_enqueue_style( $id );
        }        

        // Scripts     
        wp_register_script('dropdownmenu', sprintf( "%s/media/js/jquery.dropdownmenu.js", $theme_dir ), array( 'jquery' ), 0.1, true );
        wp_register_script('fitvids', sprintf( "%s/media/js/jquery.fitvids.min.js", $theme_dir ), array( 'jquery' ), 0.1, true );
        wp_register_script('flexslider', sprintf( "%s/media/js/jquery.flexslider-min.js", $theme_dir ), array('jquery'), 0.1, true );
        wp_register_script('jplayer', sprintf( "%s/media/js/jquery.jplayer.js", $theme_dir ), array('jquery'), 0.1, true );
        wp_register_script('flexnav', sprintf( "%s/media/js/jquery.flexnav.js", $theme_dir ), array('jquery'), 0.1, true );
        wp_register_script('sharrre', sprintf( "%s/media/js/jquery.sharrre-1.3.4.min.js", $theme_dir ), array('jquery'), 0.1, true );
        wp_register_script('colorbox', sprintf( "%s/media/js/jquery.colorbox-min.js", $theme_dir ), array('jquery'), 0.1, true );
        wp_register_script('imagehover', sprintf( "%s/media/js/jquery.imageHover.js", $theme_dir ), array('jquery'), 0.1, true );
        wp_register_script('main', sprintf( "%s/media/js/main.js", $theme_dir ), array('jquery', 'flexslider', 'fitvids', 'jplayer', 'dropdownmenu', 'flexnav', 'sharrre', 'colorbox', 'imagehover'), 0.1, true);
	    wp_enqueue_script('main');

        wp_localize_script('main', 'Sofa_Localized', array(
            'sharrre_url' => get_template_directory_uri() . '/inc/sharrre/sharrre.php', 
            'sharrre_text' => __('Share', 'textural')
        ) );         
    }

    /**
     * Executed on wp_default_scripts hook. Used to print jQuery in footer instead of head.
     * 
     * Credit http://wp-snippets.com/load-jquery-in-footer/
     * @return void
     */
    public function wp_default_scripts( &$scripts) {
        if ( ! is_admin() )
            $scripts->add_data( 'jquery', 'group', 1 );
    }      

    /**
     * Executed on wp_print_footer_scripts hook. 
     * @return void
     */
    public function wp_print_footer_scripts() {
        $template_directory = get_template_directory_uri();
        ?>
<!--[if lt IE 9]>
    <script src="<?php echo $template_directory ?>/media/js/html5shiv.js" type="text/javascript"></script>
    <script src="<?php echo $template_directory ?>/media/js/selectivizr-min.js" type="text/javascript"></script>
    <script src="<?php echo $template_directory ?>/media/js/PIE.js" type="text/javascript"></script>
<![endif]-->
        <?php
    }    

    /**
     * Insert output into end of <head></head> section
     * @return void
     */
    public function wp_head() {

        // Add Analytics
        echo textural_get_option('analytics');
        ?>
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700,900|Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800|Open+Sans+Condensed:300,700,300italic' rel='stylesheet' type='text/css'>
        <?php
    }

    /**
     * Insert output to set up custom backgorunds
     * @return void
     */
    public function custom_background() {
        // $background is the saved custom image, or the default image.
        $background = set_url_scheme( get_background_image() );

        // $color is the saved custom color.
        // A default has to be specified in style.css. It will not be printed here.
        $color = get_theme_mod( 'background_color' );

        if ( ! $background && ! $color )
            return;

        $style = $color ? "background-color: #$color;" : '';

        if ( $background ) {
            $image = " background-image: url('$background');";

            $repeat = get_theme_mod( 'background_repeat', 'repeat' );
            if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
                $repeat = 'repeat';
            $repeat = " background-repeat: $repeat;";

            $position = get_theme_mod( 'background_position_x', 'left' );
            if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
                $position = 'left';
            $position = " background-position: top $position;";

            $attachment = get_theme_mod( 'background_attachment', 'scroll' );
            if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
                $attachment = 'scroll';
            $attachment = " background-attachment: $attachment;";

            $style .= $image . $repeat . $position . $attachment;
        }
        else {
            $style .= "background-image: none;";
        }
        ?>
            <style type="text/css" id="custom-background-css">
                html.custom-background { <?php echo trim( $style ); ?> }
            </style>
        <?php
    }

    /**
     * Runs on after_setup_theme hook
     * @return void
     */
    public function after_setup_theme() {
        // Set up localization
        load_theme_textdomain( 'textural', get_template_directory() . '/languages' );

        // Post formats
        add_theme_support( 'post-formats', array( 'image', 'quote', 'gallery', 'video', 'aside', 'link', 'status', 'audio', 'chat' ) );

        // Automatic feed links
        add_theme_support( 'automatic-feed-links' );

        // Enable post thumbnail support 
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(800, 'auto', true);
        add_image_size('fullwidth', 800, 0, false);

        // Custom backgrounds
        add_theme_support( 'custom-background', array( 'wp-head-callback' => array(&$this, 'custom_background' ) ) );

        // Register menu
        register_nav_menus(array(
            'primary_navigation' => __( 'Primary Navigation', 'textural' )
        ));
    }

    /**
     * Executed on widgets_init hook
     * @return void
     */
    public function widgets_init() {
           
        register_sidebar( array(
            'id' => 'footer_1',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'name' => __( 'Footer left', 'textural' ),
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget_title">',
            'after_title' => '</h3>'
        ));

        register_sidebar( array(
            'name' => __( 'Footer middle', 'textural' ),
            'id' => 'footer_2',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget_title">',
            'after_title' => '</h3>'
        ));

        register_sidebar( array(
            'name' => __( 'Footer right', 'textural' ),
            'id' => 'footer_3',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget_title">',
            'after_title' => '</h3>'
        ));

        // Register custom widgets
        register_widget('OSFA_Twitter_Widget');
        register_widget('OSFA_Flickr_Widget');
        register_widget('OSFA_Insta_Widget');        
    }  

    /**
     * Admin menu tweaks
     * @return void
     */
    public function admin_menu() {
        $this->admin_page = new OSFA_Theme_Settings();

        add_theme_page( __( 'Textural Options', 'textural' ), __( 'Textural Theme Settings', 'textural' ), 'edit_theme_options', 'textural-options', array( $this->admin_page, 'display' ) );
        add_theme_page( __( 'Customize', 'textural' ), __( 'Customize', 'textural' ), 'edit_theme_options', 'customize.php' );
    }

    /**
     * Admin init hook
     * @return void
     */
    public function admin_init() {
        global $pagenow;

        $this->admin_page = new OSFA_Theme_Settings();

        // Register Textural settings object
        register_setting( 'theme_textural_options', 'theme_textural_options', array( $this->admin_page, 'validate' ) );

        if ( 'themes.php' == $pagenow && isset( $_GET['page'] ) && 'textural-options' == $_GET['page'] ) {
            $this->admin_page->get_general_tab();
        }
    }
    
    /**
     * Single template filter
     * @param array $templates
     * @return array
     */
    public function single_template( $templates = "" ) {
        $format = get_post_format();

        if ( in_array( $format, array( 'image', 'gallery', 'video', 'audio' ) ) ) {
            $new_templates = array( "single-format-$format.php", "single-format-media.php", "single.php" );
        }
        elseif ( $format != "" ) {
            $new_templates = array( "single-format-$format.php", "single.php" );
        }
        else {
            return $templates;
        }

        if( !is_array( $templates ) && !empty( $templates ) ) {
            array_push( $new_templates, $templates );
            $templates = locate_template( $new_templates, false);            
        } 
        elseif( empty ($templates ) ) {
            $templates = locate_template( $new_templates, false);
        }
        else {
            $new_template = locate_template( $new_templates );
            if( !empty($new_template) ) array_unshift( $templates, $new_template );
        }

        return $templates;
    }

    /**
     * Filters the "more" link on post archives
     * @return string
     */
    public function the_content_more_link($more_link, $more_link_text) {
        return str_replace( $more_link_text, __( 'Continue', 'textural' ), $more_link );
    }

    /**
     * Filters the post_class output
     * @param array $classes
     * @return array
     */
    public function post_class($classes) {
        if (has_post_thumbnail())
            $classes[] = 'has_featured_image';

        return $classes;
    }

    /**
     * Apply a class of "next" to the next posts link
     * @return string
     */
    public function next_posts_link_attributes() {
        return 'class="next ajax-next" title="'.__( 'Earlier posts', 'textural' ).'"';
    }

    /**
     * Apply a class of "previous" to the next posts link
     * @return string
     */
    public function previous_posts_link_attributes() {
        return 'class="previous" title="'.__( 'Later posts', 'textural' ).'"';
    }

    /**
     * Add a search field to the menu
     * @param string $items
     * @param array $args
     * @return string
     */
    public function wp_nav_menu_items($items, $args) {
        if ( get_theme_mod('show_search') ) {
            $items .= '
<li id="search" class="menu-item">
    <form method="get" id="searchform" action="'.esc_url( home_url( '/' ) ).'">
        <label for="s" class="assistive-text">'.__( 'Search', 'textural' ).'</label>
        <input type="text" class="field" name="s" id="s" placeholder="'.esc_attr__( 'Search', 'textural' ).'" />  
        <button type="submit" class="button mini"><i class="icon-search"></i></button>
    </form>
</li>';
        }
        return $items;
    }

    /**
     * Adds colorbox class to images
     * @param string $html
     * @return string
     */
    public function enable_colorbox($html, $id, $caption, $title, $align, $url='') {
        if ( $url ) {
            $html = str_replace( '<a href', '<a class="colorbox" data-rel="post_gallery" href', $html );
        }
        
        return $html;
    }    

    /**
     * Formats the output of the wp_title tag
     * @param string $title
     * @param string $sep
     * @return string
     * @since Textural 1.0
     */     
    function wp_title( $title, $sep ) {
        global $paged, $page;

        if ( is_feed() )
            return $title;

        // Add the site name.
        $title .= get_bloginfo( 'name' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            $title = "$title $sep $site_description";

        // Add a page number if necessary.
        if ( $paged >= 2 || $page >= 2 )
            $title = "$title $sep " . sprintf( __( 'Page %s', 'textural' ), max( $paged, $page ) );

        return $title;
    }   
}

OSFBootstrap::get_instance();

// Set $content_width global variable
if ( ! isset( $content_width ) ) $content_width = 800;