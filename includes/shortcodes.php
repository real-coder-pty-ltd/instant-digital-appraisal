<?php
/**
 * Instant Digital Appraisal Shortcodes.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

function dsp_suburb_profile_extra_shortcode() 
{
    ob_start();

    $template = plugin_dir_path(dirname(__FILE__)) . 'public/templates/single-suburb-profile.php';

    if ( file_exists( $template ) ) {
        include $template;
    }

    $additional_content = ob_get_clean();

    return $additional_content;

}
add_shortcode( 'rc_domain_suburb_profile', 'dsp_suburb_profile_extra_shortcode' );