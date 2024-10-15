<?php

/**
 * Fired during plugin activation
 *
 * @link       https://stafflink.com.au
 * @since      1.0.0
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 *
 * @author     Matthew Neal <matt.neal@stafflink.com.au>
 */
class Pricefinder_Da_Activator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {

        if (! get_option('pricefinder_da_temp_token')) {
            add_option('pricefinder_da_temp_token', 'none yet');
        }

        if (! get_option('pricefinder_da_temp_token_age')) {
            add_option('pricefinder_da_temp_token_age', 0);
        }

        if (! get_option('pricefinder_da_developer_mode')) {
            add_option('pricefinder_da_developer_mode', false);
        }
        if (! get_option('pricefinder_da_google_maps_api_key')) {
            add_option('pricefinder_da_google_maps_api_key', 'none yet');
        }
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir.'/digital-appraisal';
        if (! is_dir($upload_dir)) {
            mkdir($upload_dir, 0700);
        }

    }
}
