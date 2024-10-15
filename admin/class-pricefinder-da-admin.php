<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://realcoder.com.au
 * @since      1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Matthew Neal <matt.neal@realcoder.com.au>
 */
class Pricefinder_Da_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param  string  $plugin_name  The name of this plugin.
     * @param  string  $version  The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pricefinder_Da_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pricefinder_Da_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__).'css/pricefinder-da-admin.css', [], $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pricefinder_Da_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pricefinder_Da_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__).'js/pricefinder-da-admin.js', ['jquery'], $this->version, false);

    }
}

// Creates a subpage under the Tools section
add_action('admin_menu', 'register_pricefinder_da_settings');
function register_pricefinder_da_settings()
{
    add_submenu_page(
        'tools.php',
        'Pricefinder DA Settings',
        'Pricefinder DA Settings',
        'manage_options',
        'pricefinder-da',
        'add_pricefinder_da_settings');
}

// The admin page containing the form
function add_pricefinder_da_settings()
{ ?>
    <div class="wrap">
		<div id="icon-tools" class="icon32"></div>
        <h1>Pricefinder Digital Appraisal Settings</h1>
		<p>Here you can set all your settings for the Pricefinder Digital Appraisal Plugin</p>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <h3>Pricefinder Client ID</h3>
			<p>Please contact Pricefinder <a href="https://www.pricefinder.com.au/api/" target="_blank">here</a> to obtain your client/secret keys. If you enter a key here, it will overwrite the old one.</p>
			<input type="text" name="pricefinder_da_client_id" size="50" value="<?php echo get_option('pricefinder_da_client_id'); ?>">
			<h3>Pricefinder Secret Key</h3>
            <input type="password" name="pricefinder_da_secret_key" size="50" value="<?php echo get_option('pricefinder_da_secret_key'); ?>">
			<h3>Google Maps Autocomplete API Key</h3>
			<p>You'll need a google maps API key with Places enabled.</p>
            <input type="password" name="pricefinder_da_google_maps_api_key" size="50" value="<?php echo get_option('pricefinder_da_google_maps_api_key'); ?>">
			<input type="hidden" name="action" value="process_form">	<br><br>		 
            <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Update"  />
        </form> 		
    </div>
    <?php
}

function submit_pricefinder_da_key()
{

    if (isset($_POST['pricefinder_da_client_id'])) {

        $api_key = sanitize_text_field($_POST['pricefinder_da_client_id']);
        $api_exists = get_option('pricefinder_da_client_id');

        if (! empty($api_key) && ! empty($api_exists)) {

            update_option('pricefinder_da_client_id', $api_key);

        } else {

            add_option('pricefinder_da_client_id', $api_key);

        }

    }

    if (isset($_POST['pricefinder_da_secret_key'])) {

        $api_key = sanitize_text_field($_POST['pricefinder_da_secret_key']);
        $api_exists = get_option('pricefinder_da_secret_key');

        if (! empty($api_key) && ! empty($api_exists)) {

            update_option('pricefinder_da_secret_key', $api_key);

        } else {

            add_option('pricefinder_da_secret_key', $api_key);

        }

    }

    if (isset($_POST['pricefinder_da_google_maps_api_key'])) {

        $google_maps_api_key = sanitize_text_field($_POST['pricefinder_da_google_maps_api_key']);
        $google_maps_api_exists = get_option('pricefinder_da_google_maps_api_key');

        if (! empty($google_maps_api_exists) && ! empty($google_maps_api_exists)) {

            update_option('pricefinder_da_google_maps_api_key', $google_maps_api_key);

        } else {

            add_option('pricefinder_da_google_maps_api_key', $google_maps_api_key);

        }

    }

    wp_redirect($_SERVER['HTTP_REFERER']);

}

add_action('admin_post_nopriv_process_form', 'submit_pricefinder_da_key');
add_action('admin_post_process_form', 'submit_pricefinder_da_key');

if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group([
        'key' => 'group_5f90e9ab4e34c',
        'title' => 'Property Details',
        'fields' => [
            [
                'key' => 'field_5f90e9ac60f5f',
                'label' => 'Property Details',
                'name' => 'property_details',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key' => 'field_5f90ea1360f60',
                        'label' => 'Beds',
                        'name' => 'beds',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ],
                    [
                        'key' => 'field_5f90ea4760f61',
                        'label' => 'Baths',
                        'name' => 'baths',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ],
                    [
                        'key' => 'field_5f90ea5060f62',
                        'label' => 'Cars',
                        'name' => 'cars',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ],
                    [
                        'key' => 'field_5f90ea5d60f63',
                        'label' => 'Land Size',
                        'name' => 'land_size',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ],
                    [
                        'key' => 'field_5f90ea6e60f64',
                        'label' => 'Property Type',
                        'name' => 'property_type',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                ],
            ],
            [
                'key' => 'field_5f90eb5280d7f',
                'label' => 'Pricing Information',
                'name' => 'pricing_information',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key' => 'field_5f90eb6480d80',
                        'label' => 'Price Confidence',
                        'name' => 'price_confidence',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90eb8380d81',
                        'label' => 'Low',
                        'name' => 'low',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90eb9080d82',
                        'label' => 'Medium',
                        'name' => 'medium',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90eb9480d83',
                        'label' => 'High',
                        'name' => 'high',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                ],
            ],
            [
                'key' => 'field_5f90ebdc22621',
                'label' => 'Suburb Statistics',
                'name' => 'suburb_statistics',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key' => 'field_5f90ebe822622',
                        'label' => 'Properties Sold',
                        'name' => 'properties_sold',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ],
                    [
                        'key' => 'field_5f90ebf622623',
                        'label' => 'Units Sold',
                        'name' => 'units_sold',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ],
                    [
                        'key' => 'field_5f90f2c1103a1',
                        'label' => 'Population',
                        'name' => 'population',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f2c7103a2',
                        'label' => 'Average Age',
                        'name' => 'average_age',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f2cc103a3',
                        'label' => 'Owner Occupier',
                        'name' => 'owner_occupier',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f966ca6d85a7',
                        'label' => 'Properties For Sale',
                        'name' => 'properties_for_sale',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                ],
            ],
            [
                'key' => 'field_5f90f14fc43c5',
                'label' => 'Investment Potential',
                'name' => 'investment_potential',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key' => 'field_5f90f15cc43c6',
                        'label' => 'Average Rent',
                        'name' => 'average_rent',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f167c43c7',
                        'label' => 'Average Rental Yield',
                        'name' => 'average_rental_yield',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f1b061ac4',
                        'label' => 'Average Capital Growth',
                        'name' => 'average_capital_growth',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f1ba61ac5',
                        'label' => 'Days On Market Rental',
                        'name' => 'days_on_market_rental',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f1c961ac6',
                        'label' => 'Days On Market Sale',
                        'name' => 'days_on_market_sale',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                ],
            ],
            [
                'key' => 'field_5f90f1e93973a',
                'label' => 'Rental And Sales History',
                'name' => 'rental_sales_history',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => [
                    [
                        'key' => 'field_5f90f287008b0',
                        'label' => 'Date',
                        'name' => 'date',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f290008b1',
                        'label' => 'Action',
                        'name' => 'action',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f294008b2',
                        'label' => 'Price',
                        'name' => 'price',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f299008b3',
                        'label' => 'Sale Type',
                        'name' => 'sale_type',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                ],
            ],
            [
                'key' => 'field_5f90f2fe50d42',
                'label' => 'Schools',
                'name' => 'schools',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => [
                    [
                        'key' => 'field_5f90f30550d43',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f90f30c50d44',
                        'label' => 'Distance',
                        'name' => 'distance',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_5f9118dba55f5',
                        'label' => 'school_type',
                        'name' => 'school_type',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                ],
            ],
            [
                'key' => 'field_5f90f3574df52',
                'label' => 'Image Links',
                'name' => 'image_links',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => [
                    [
                        'key' => 'field_5f90f3664df53',
                        'label' => 'URL',
                        'name' => 'url',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                ],
            ],
            [
                'key' => 'field_5f90fba8a55d1',
                'label' => 'Unique ID',
                'name' => 'unique_id',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ],
            [
                'key' => 'field_5f964ca49e086',
                'label' => 'Data Dump',
                'name' => 'data_dump',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 20,
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf37f1387b9',
                'label' => 'Suggest Result',
                'name' => 'suggest_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf39a41743e',
                'label' => 'Demographics Result',
                'name' => 'demographics_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf3dc7d63de',
                'label' => 'Property Result',
                'name' => 'property_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf40640f008',
                'label' => 'Listings Result',
                'name' => 'listings_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf41544d6d6',
                'label' => 'Sales Result',
                'name' => 'sales_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf422570921',
                'label' => 'Rentals Result',
                'name' => 'rentals_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf43d52cfd4',
                'label' => 'AVM Result',
                'name' => 'avm_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf44afee93f',
                'label' => 'Schools Result',
                'name' => 'schools_result',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
            [
                'key' => 'field_5fbf48163eaca',
                'label' => 'Suburb Response',
                'name' => 'suburb_response',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'appraisal',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ]);

}
