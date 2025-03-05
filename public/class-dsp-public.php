<?php

/**
 * The public-facing functionality of the plugin.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

class DSP_Public
{
    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__).'css/dsp-public.css', [], $this->version, 'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__).'js/dsp-public.js', ['jquery'], $this->version, true);

        $enqueue_google_maps = get_option('dsp_enqueue_google_maps');
        if ($enqueue_google_maps) {
            wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.get_option('dsp_google_maps_api_key').'&amp;libraries=places', [], $this->version, false);
        }

        wp_enqueue_script('domain-ajax', plugin_dir_url(__FILE__).'js/dpp-domain-ajax.js', ['jquery'], null, true);
        wp_localize_script('domain-ajax', 'domain_ajax_obj', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
    }
}