<!DOCTYPE html> 
<html <?php language_attributes() ?> <?php osfa_html_class() ?>> 
<head>
    <meta charset="<?php bloginfo( 'charset' ) ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title( "&raquo;", true, 'right' ) ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>" />
    <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ) ?>
    <?php wp_head() ?> 	
</head>
<body <?php body_class() ?>>	

	<header id="header">
        <div class="logo"><a href="<?php echo site_url() ?>" title="<?php _e('Return home', 'textural') ?>"></a></div>
		<hgroup class="site_title_group">
            <?php osfa_site_title() ?>
            <?php osfa_site_description() ?>
		</hgroup>

        <nav id="primary_navigation" role="navigation">
            <a class="menu-button"><?php _e( 'Menu', 'textural' ) ?></a>
            <?php wp_nav_menu( array(   
                'theme_location' => 'primary_navigation',
                'container' => false,
                'menu_class' => 'menu responsive_menu' ) ) ?>
        </nav>

        <?php if ( get_theme_mod('show_search') ) echo get_search_form() ?>
	</header>