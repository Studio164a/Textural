<?php
/**
 * A collection of shortcodes
 */

/**
 * Wraps a column in a div with class "column"
 * @param array $atts
 * @param string $content
 * @return string
 */
if ( !function_exists('osfa_column_shortcode') ) {    
    function osfa_column_shortcode($atts, $content = null) {
        extract(shortcode_atts( array(
            'width' => '1/2',
            'class' => '', 
            'style' => '', 
            'last' => false, 
            'divider' => false,
            'id' => ''
        ), $atts));        

        // Set up empty variables
        $supported = array(
            '1/2' => 'column_50', '50%' => 'column_50',
            '1/3' => 'column_33', '33%' => 'column_33', 
            '1/4' => 'column_25', '25%' => 'column_25', 
            '1/5' => 'column_20', '20%' => 'column_20', 
            '2/3' => 'column_66', '66%' => 'column_66', '67%' => 'column_66', 
            '2/5' => 'column_40', '40%' => 'column_40', 
            '3/4' => 'column_75', '75%' => 'column_75',
            '3/5' => 'column_60', '60%' => 'column_60', 
            '4/5' => 'column_80', '80%' => 'column_80'
        ); 

        // If a supported width fraction or percentage was passed, set the according class
        if ( array_key_exists($width, $supported) ) {      
            $class .= " $supported[$width]";      
        }      
        // If width was expressed in pixels or as a non-default percentage, add to inline styles
        elseif ( '%' == substr($width, '-1') || 'px' == substr($width, '-2') ) {
            $style .=  "width: $width;";
        }    

        // If it's the last column, add that to classes
        if ($last) {
            $class .= " column_last";
        }            

        // Add divider, if set to true
        if ($divider) {
            $class .= " divider";
        }

        // Start putting output together
        $html = '<div class="'.$class.'"';

        // Add inline styles
        if ( $style && strlen( $style ) ) {
            $html .= $html . ' style="'.$style.'"';
        }

        // Add ID
        if ( $id && strlen( $id ) ) {
            $html .= $html . ' id="'.$id.'"';
        }

        // Filter out empty paragraphs in content    
        $content = preg_replace('#^<\/p>|<p>$#', '', $content);

        $html .= '>'.do_shortcode($content).'</div>';

        return $html;
    }
}

/**
 * Create a button
 * @param array $atts
 * @param string $content
 * @return string
 */
if ( !function_exists('osfa_button_shortcode') ) {
    function osfa_button_shortcode($atts, $content = null) {
        extract(shortcode_atts( array(
            'class' => '', 'large' => false, 'link' => ''
        ), $atts));        

        if ( strlen( $class ) )
            $class = " $class";

        if ( $large ) 
            $large = ' is_large';

        return '<a href="'.$link.'" class="button '.$class.$large.'">'.$content.'</a>';
    }
}

/**
 * Display gallery
 * @param string $html
 * @param array $attr       Shortcode attributes. 
 * @return string
 */
if ( !function_exists('osfa_gallery_shortcode') ) {
    function osfa_gallery_shortcode( $html, $attr = array() ) {
        global $post;

        // NOTE: This is all more or less the same as WP's gallery_shortcode() method,
        // but it sets up theme-specific markup for the gallery
        static $instance = 0;
        $instance++;

        // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
        if ( isset( $attr['orderby'] ) ) {
            $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
            if ( !$attr['orderby'] )
                unset( $attr['orderby'] );
        }

        extract(shortcode_atts(array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post->ID,
            'itemtag'    => 'li',
            'captiontag' => 'p',
            'columns'    => '',
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => ''
        ), $attr));

        $id = intval($id);
        if ( 'RAND' == $order )
            $orderby = 'none';

        if ( !empty($include) ) {
            $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

            $attachments = array();
            foreach ( $_attachments as $key => $val ) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif ( !empty($exclude) ) {
            $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        } else {
            $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        }

        if ( empty($attachments) )
            return '';

        if ( is_feed() ) {
            $output = "\n";
            foreach ( $attachments as $att_id => $attachment )
                $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
            return $output;
        }

        $itemtag = tag_escape($itemtag);
        $captiontag = tag_escape($captiontag);
        $columns = intval($columns);
        $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
        $float = is_rtl() ? 'right' : 'left';

        $selector = "gallery-{$instance}";

        $gallery_ul = "<div class='cf'><ul id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns}'>";
        if ( !is_singular() ) 
            $gallery_ul = "<div class=\"slider\">{$gallery_ul}\n";
        $output = apply_filters( 'gallery_style', $gallery_ul );

        $i = 0;
        foreach ( $attachments as $id => $attachment ) {
            $image_attr = wp_get_attachment_image_src( $id, 'full' );
            $link = sprintf( '<a href="%s" title="%s">%s</a>', $image_attr[0], $attachment->post_title, wp_get_attachment_image($id, 'fullwidth') );

            $column_end = $columns > 0 && ++$i % $columns == 0;
            $extra_class = $column_end ? 'is_row_end' : '';

            $output .= "<{$itemtag} class='gallery-item $extra_class'>$link";
            if ( $captiontag && ( trim($attachment->post_excerpt) || trim($attachment->post_title) ) && $size != 'thumbnail' ) {
                $caption = trim($attachment->post_excerpt) ? trim($attachment->post_excerpt) : trim($attachment->post_title);
                $output .= "
                    <{$captiontag} class='wp-caption-text gallery-caption'>
                    " . wptexturize($caption) . "
                    </{$captiontag}>";
            }
            $output .= "</{$itemtag}>";
            
            if ( $column_end )
                $output .= '<br style="clear: both" />';
        }

        $output .= "
            </ul></div>\n";

        if ( !is_singular() )
            $output .= "</div>\n";

        return $output;
    }
}

/**
 * Flickr shortcode
 * @param array $atts
 * @return string
 */
if (!function_exists('osfa_flickr_shortcode')) {
    function osfa_flickr_shortcode($atts) {
        $f = new OSFA_Flickr();
        return $f->shortcode($atts);
    }
}

/**
 * Instagram shortcode
 * @param array $atts
 * @return string
 */
if (!function_exists('osfa_instagram_shortcode')) {
    function osfa_instagram_shortcode($atts) {
        $i = new OSFA_Instagram();
        return $i->shortcode($atts);
    }
}

/**
 * Notification boxes shortcode
 * @param array $atts
 * @param string $content
 * @return string
 */
if (!function_exists('osfa_notification_shortcode')) {
    function osfa_notification_shortcode($atts, $content = "") {
        extract(shortcode_atts( array( 'class' => '' ), $atts));
        return sprintf( '<div class="notice %s">%s</div>', $class, do_shortcode($content) );
    }
}