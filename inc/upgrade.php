<?php
/**
 * Handles version to version upgrading
 * 
 * @author Eric Daams <eric@ericnicolaas.com>
 */

class OSFUpgradeHelper {

    /**
     * Perform upgrade
     * @param int $current
     * @param int|false $db_version 
     * @static
     */
    public static function do_upgrade($current, $db_version) {
    	if ($db_version === false) {            
            self::upgrade_1_5();
        }

        return;
    }

    /**
     * Upgrade to version 1.5
     */
    protected static function upgrade_1_5() {
        $options = get_option('theme_textural_options', array());

        foreach ( array( 'twitter_token_secret', 'twitter_consumer_secret', 'twitter_consumer_key' ) as $key ) {
            if ( !array_key_exists($key, $options) ) {
                $options[$key] = '';
            } 
        }

        update_option('theme_textural_options', $options);
    }
}