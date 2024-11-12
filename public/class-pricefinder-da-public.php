<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://realcoder.com.au
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Matthew Neal <matt.neal@realcoder.com.au>
 */
class Pricefinder_Da_Public
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
     * @param  string  $plugin_name  The name of the plugin.
     * @param  string  $version  The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__).'css/pricefinder-da-public.css', [], $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__).'js/pricefinder-da-public.js', ['jquery'], $this->version, true);

        /**
         * Google Maps Script.
         */
        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.get_option('pricefinder_da_google_maps_api_key').'&amp;libraries=places', [], $this->version, false);
    }
}

function pricefinder_da_notice($level, $message)
{

    $dev_mode = get_option('pricefinder_da_developer_mode');
    if ($dev_mode == 'true') {
        echo '<div class="alert alert-'.$level.' alert-dismissible fade show" role="alert">'.$message.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	  </button></div>';
    } else {
        return;
    }

}

/*
*	A simple function that hits the Pricefinder API to get the Auth Token.
*
*/
function curl_pricefinder_da()
{

    // Check the age of the token
    $time_last_set = get_option('pricefinder_da_temp_token_age');

    // If the token is over 24 hours old...
    if (time() > ($time_last_set + 86400)) {

        pricefinder_da_notice('warning', 'Access Token has expired. Generating new key...');

        // Check if options have been set, if not, break early.
        if (get_option('pricefinder_da_client_id') && get_option('pricefinder_da_secret_key')) {

            $client_id = get_option('pricefinder_da_client_id');
            $client_secret = get_option('pricefinder_da_secret_key');
            pricefinder_da_notice('secondary', 'Client ID & Secret Key set. Attempting to generate key through API...');

        } else {

            pricefinder_da_notice('danger', 'Error: No private key and/or Secret Key set. Please go to tools->rmar settings and add your credentials.');

            return;

        }

        $ch = curl_init();
        $client_id = get_option('pricefinder_da_client_id');
        $client_secret = get_option('pricefinder_da_secret_key');
        $headers = ['Content-Type: application/x-www-form-urlencoded', 'Accept: application/json'];

        $options = [
            CURLOPT_URL => 'https://api.pricefinder.com.au/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id='.$client_id.'&client_secret='.$client_secret,
            CURLOPT_HTTPHEADER => $headers,
        ];

        curl_setopt_array($ch, $options);

        if (curl_errno($ch)) {
            echo 'Error:'.curl_error($ch);
        }

        $result = json_decode(curl_exec($ch));

        curl_close($ch);

        if (! $result->access_token) {

            pricefinder_da_notice('danger', 'Error: Could not obtain access token. Please contact Pricefinder Support.');

            return;

        }

        // Update the DB with token and reset the time since last updated.
        update_option('pricefinder_da_temp_token', $result->access_token);
        update_option('pricefinder_da_temp_token_age', time());
        pricefinder_da_notice('success', 'Access key updated');

    }

}

/*
*	Class that hits the Pricefinder API using curl_pricefinder_da(), uses the response (which is the oauth2.0 token)
*	to make the actual query.
*
*/
class pfda_query
{
    public $body;

    public $response;

    public function __construct($api_init)
    {

        curl_pricefinder_da();

        $this->api = $api_init;
        $this->response = wp_remote_get(
            $this->api, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.get_option('pricefinder_da_temp_token'),
                ],
            ]
        );
        $this->response = wp_remote_retrieve_body($this->response);
        $this->body = json_decode($this->response);
        if (empty($this->body)) {
            $this->body = 0;
        }

    }
}

function build_image($image_content, $id)
{

    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'].'/digital-appraisal/';

    $file_location = $upload_dir.$id.'.jpg';
    if (file_exists($file_location)) {
        return $file_location;
    }
    $fh = fopen($file_location, 'a');
    fwrite($fh, $image_content);
    fclose($fh);

    return $file_location;
}

// function pricefinder_da_render_scripts()
// {
//     global $post;
//     if (has_shortcode($post->post_content, 'pfda_address_form')) {

//         add_action('wp_header', 'pricefinder_da_google_api_key', 10, 1);
//     }
// }

// pricefinder_da_render_scripts();

/*
* 	Adds the google maps scripts to the header.
*
*/
// function pricefinder_da_google_api_key()
// {

//     echo '<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
// 	<script src="https://maps.googleapis.com/maps/api/js?key='.get_option('pricefinder_da_google_maps_api_key').'&callback=initAutocomplete&libraries=places&v=weekly" defer></script>';
// }

// add_action('wp_enqueue_scripts', 'wpdocs_theme_name_scripts');

/*
* 	Google Places Autocomplete shortcode form.
*
*/
function pricefinder_da_autocomplete_address_form($atts)
{
    // Define default attributes
    $atts = shortcode_atts(
        array(
            'tabs' => 'general',
            'form_placeholder' => 'Enter your address',
            'form_submit' => 'Submit'
        ),
        $atts,
        'pricefinder_da_form'
    );

    // Extract attributes
    $tabs = explode(',', $atts['tabs'] ? :  'general');
    $url_slug = get_option('pricefinder_da_appraisal_page_url_slug') ? : 'instant-digital-appraisal';
    $form_placeholder = $atts['form_placeholder'];
    $form_submit = $atts['form_submit'];

    echo '
    <form id="pricefinder-da-form" method="GET" action="/' . $url_slug . '/">';
    
    if (is_array($tabs)) {
        $index = 0;
        $ul_class = count($tabs) === 1 ? 'd-none' : '';
    
        echo '<ul id="pricefinder-da-appraisal-type-wrapper" class="nav nav-pills mb-3 ' . $ul_class . '" id="pills-tab" role="tablist">';
        foreach ($tabs as $tab) {
            $active_class = $index === 0 ? ' active' : '';
            $aria_selected = $index === 0 ? 'true' : 'false';
    
            echo '<div class="radio">';
            echo '<input type="radio" class="pricefinder-da-appraisal-type d-none" name="appraisal-type" id="tool_' . esc_attr($tab) . '" value="' . esc_attr($tab) . '"' . ($index === 0 ? ' checked' : '') . '>';
            echo '<label for="tool_' . esc_attr($tab) . '" class="nav-link text-capitalize' . $active_class . '" data-bs-toggle="pill" role="tab" aria-selected="' . $aria_selected . '">' . esc_html($tab) . '</label>';
            echo '</div>';
    
            $index++;
        }
        echo '</ul>';
    }
    
    echo '
        <div class="d-flex flex-row">
            <div class="pricefinder-da-address position-relative w-100">
                <input class="form-control input-l rounded h-100" placeholder="' . $form_placeholder . '" name="address" type="text" required>
                <div id="pricefinder-da-result" class="gform-theme__disable-reset position-absolute start-0 top-100 w-100 small"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg rounded text-nowrap ms-2">' . $form_submit . '</button>
        </div>
    </form>
    <div id="loading-container">
        <img id="loading-image" src="/app/plugins/instant-digital-appraisal/public/images/loader.jpg" alt="Loading..." />
    </div>';


    if (is_plugin_active('easy-property-listings/easy-property-listings.php')) {
        foreach ($tabs as $tab) {
            pfda_fetch_addresses($tab);
        }
    }
    
}
add_shortcode('pfda_address_form', 'pricefinder_da_autocomplete_address_form');

/*
* 	Fetch addresses from the listings.
*
*/
function pfda_fetch_addresses($tab){

    // Initialize variables for post type and property status
    $post_type = '';
    $property_status = '';

    // Set post type and property status based on the tool parameter
    if ($tab === 'buy') {
        $post_type = 'property';
        $property_status = 'current';
    } elseif ($tab === 'rental') {
        $post_type = 'rental';
        $property_status = 'current';
    }
    
    // Define the query arguments
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'property_status',
                'value' => $property_status,
                'compare' => '='
            )
        )
    );

    // Execute the query
    $query = new WP_Query($args);

    // Initialize an array to store addresses
    $addresses = [
        'buy' => [],
        'rental' => []
    ];
    
    // Check if the query has posts
    if ($query->have_posts()) {

        // Loop through the query results
        while ($query->have_posts()) {
            $query->the_post();

            // Check if the address should be displayed
            $display_address = get_post_meta(get_the_ID(), 'property_address_display', true);
    
            // Retrieve address components based on display flag
            if ($display_address === 'yes') {
                $street_number = get_post_meta(get_the_ID(), 'property_address_street_number', true);
                $street_name = get_post_meta(get_the_ID(), 'property_address_street_name', true);
                $sub_number = get_post_meta(get_the_ID(), 'property_address_sub_number', true);
            } else {
                $street_number = '';
                $street_name = '';
                $sub_number = '';
            }
    
            // Retrieve other address components
            $suburb = get_post_meta(get_the_ID(), 'property_address_suburb', true);
            $state = get_post_meta(get_the_ID(), 'property_address_state', true);
            $postcode = get_post_meta(get_the_ID(), 'property_address_postal_code', true);
    
            // Construct the full address
            $full_address = trim($street_number . ' ' . $street_name . ' ' . $sub_number);
            if (!empty($full_address)) {
                $full_address .= ', ';
            }
            $full_address .= $suburb . ', ' . $state . ' ' . $postcode;
    
            // Add the full address to the addresses array
            $addresses[$tab][] = $full_address;
        }

        // Reset post data
        wp_reset_postdata();
        
        // Convert addresses array to JSON and embed in a script tag
        $json_addresses = json_encode($addresses);
        echo "<script> var addresses = $json_addresses;</script>";
    }
}

function pdfa_load_addreses(){
    $url_slug = get_option('pricefinder_da_appraisal_page_url_slug') ? : 'instant-digital-appraisal';
    $tab = isset($_GET['appraisal-type']) ? $_GET['appraisal-type'] : 'sell';

    if (is_page($url_slug)) {
        pfda_fetch_addresses($tab);
    }
    
}
add_action('wp_footer', 'pdfa_load_addreses');

/*
* 	Instant Digital Appraisal Form Shortcode.
*
*/
function pricefinder_da_appraisal_form_shortcode($atts)
{
    // Define default attributes
    $atts = shortcode_atts(
        array(
            'class_section' => 'bg-light',
            'class_container' => 'mw-1512',
            'class_row' => 'row mx-0 align-items-center',
            'class_col' => 'col-lg-6 px-0',
            'class_form_wrapper' => 'container px-lg-0',
            'class_form' => 'bg-white rounded shadow p-5 mx-0 mx-lg-5 my-5',
            'class_map_wrapper' => 'container px-lg-0 d-none d-lg-block',
            'class_map' => 'vh-100',
        ),
        $atts
    );

    echo '
        <section class="section--instant-digital-appraisal ' . $atts['class_section'] . '">
            <div class="' . $atts['class_container'] . '">
                <div class="' . $atts['class_row'] . '">
                    <div class="' . $atts['class_col'] . '">
                        <div class="' . $atts['class_form_wrapper'] . '">
                            <div class="' . $atts['class_form'] . '">' . do_shortcode('[gravityform id="1" title="false"]') . '</div>
                        </div>
                    </div>
                    <div class="' . $atts['class_col'] . '">
                        <div class="' . $atts['class_map_wrapper'] . '">
                            <div class="' . $atts['class_map'] . '" id="pfda-appraisal-map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
}
add_shortcode('pfda_appraisal_form', 'pricefinder_da_appraisal_form_shortcode');

function pfda_set_featured_image($post_id, $image_filename)
{

    // Need to include these because we're outside of admin environment
    require_once ABSPATH.'wp-admin/includes/media.php';
    require_once ABSPATH.'wp-admin/includes/file.php';
    require_once ABSPATH.'wp-admin/includes/image.php';

    $upload = wp_upload_dir();
    $upload_dir = $upload['baseurl'].'/digital-appraisal/';
    $image = $upload_dir.$image_filename.'.jpg';

    // magic sideload image returns an HTML image, not an ID
    $media = media_sideload_image($image, $post_id);

    // therefore we must find it so we can set it as featured ID
    if (! empty($media) && ! is_wp_error($media)) {
        $args = [
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'post_parent' => $post_id,
        ];

        // reference new image to set as featured
        $attachments = get_posts($args);

        if (isset($attachments) && is_array($attachments)) {
            foreach ($attachments as $attachment) {
                // grab source of full size images (so no 300x150 nonsense in path)
                $image = wp_get_attachment_image_src($attachment->ID, 'full');
                // determine if in the $media image we created, the string of the URL exists
                if (strpos($media, $image[0]) !== false) {
                    // if so, we found our image. set it as thumbnail
                    set_post_thumbnail($post_id, $attachment->ID);
                    // only want one image
                    break;
                }
            }
        }
    }
}

/* Filter the single_template with our custom function*/
add_filter('single_template', 'pricefinder_da_single_appraisal_template');
function pricefinder_da_single_appraisal_template($single)
{
    global $post;
    if ($post->post_type == 'appraisal') {
        $file = plugin_dir_path(__FILE__).'templates/single-appraisal.php';
        if (file_exists($file)) {
            return $file;
        }
    }

    return $single;
}

/**
 * Generate icons and output.
 */
function pricefinder_da_icons_constructor($property_result)
{

    $icon = '<ul id="icon-list" class="features">';

    foreach ($property_result as $key => $detail) {

        if ($detail == false) {
            continue;
        }

        $end = '';
        if ($key == 'land_size') {
            $end .= 'm<sup>2</sup>';
        }

        $icon .= '<li id="'.$key.'" class="card-text">';
        $icon .= '<span>'.$detail.$end.'</span>';
        if ($key !== 'property_type') {
            $icon .= '<img class="feature" src="'.plugin_dir_url(__FILE__).'icons/'.$key.'.png" alt="'.$key.'" />';
        }
        $icon .= '</li>';

    }

    $icon .= '</ul>';

    return $icon;
}

/**
 * find address using lat long
 */
function geolocationaddress($lat, $long)
{
    $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=AIzaSyA7OpqsOyv1YChC_TxY-oKCvOTBvscJrMw";
    $ch = curl_init();
    $options = [
        CURLOPT_URL => $geocode,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_PROXYPORT => 3128,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ];
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($response);
    $dataarray = get_object_vars($output);

    if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
        if (isset($dataarray['results'][0]->formatted_address)) {
            $address = $dataarray['results'][0]->formatted_address;
        } else {
            $address = 'Not Found';
        }
    } else {
        $address = 'Not Found';
    }

    return $address;
}

/**
 * Pretty up our numbers
 */
function nice_number($n)
{
    // first strip any formatting;
    $n = str_replace(',', '', $n);

    // is this a number?
    if (!is_numeric($n)) {
        return false;
    }

    // convert to a number
    $n = (float)$n;

    // now filter it;
    if ($n > 1000000000000) {
        return '$'.round(($n / 1000000000000), 2).'T';
    } elseif ($n > 1000000000) {
        return '$'.round(($n / 1000000000), 2).'B';
    } elseif ($n > 1000000) {
        return '$'.round(($n / 1000000), 2).'M';
    } elseif ($n > 1000) {
        return '$'.round(($n / 1000), 2).'K';
    }

    return number_format($n);
}

function GetDrivingDistance($lat1, $long1, $lat2, $long2)
{
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$lat1.','.$long1.'&destinations='.$lat2.','.$long2.'&key='.get_option('pricefinder_da_google_maps_api_key');
    $ch = curl_init();
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_PROXYPORT => 3128,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ];
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

    return ['distance' => $dist, 'time' => $time];
}

/**
 * Hooks for Instant Digital Appraisal Form and Page
 */
function pdfa_instant_digital_appraisal_hooks(){
    $url_slug = get_option('pricefinder_da_appraisal_page_url_slug') ? : 'instant-digital-appraisal';

    // Remove the required legend from the form
    if (is_page($url_slug)) {
        add_filter('gform_required_legend', '__return_empty_string');
    }
};

add_action('wp', 'pdfa_instant_digital_appraisal_hooks');


/**
 * Domain API Functions
 */

// Fetch the access token from Domain API
function rc_domain_fetch_access_token() {
    // Set your client ID and client secret
    $client_id = get_option('pricefinder_da_client_id');
    $client_secret = get_option('pricefinder_da_secret_key');

    // Define the token URL
    $token_url = 'https://auth.domain.com.au/v1/connect/token';

    // Prepare the POST data
    $data = [
        'grant_type' => 'client_credentials',
        'scope' => 'api_properties_read api_addresslocators_read api_demographics_read api_suburbperformance_read api_locations_read',
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // Decode the JSON response
        $response_data = json_decode($response, true);

        // Output the access token
        if (isset($response_data['access_token'])) {
            return $response_data['access_token'];
        } else {
            echo 'Error retrieving access token: ' . $response;
        }
    }

    // Close cURL session
    curl_close($ch);

    return null;
}

// Fetch property suggestions from Domain API
function rc_domain_fetch_property_suggest($location) {
    $access_token = rc_domain_fetch_access_token();
    if (!$access_token) {
        return null;
    }

    // Define the base API URL
    $base_url = 'https://api.domain.com.au/';
    $api_version = 'v1';
    $endpoint = 'properties/_suggest';

    // Construct the full API URL
    $api_url = $base_url . $api_version . '/' . $endpoint;

    // Prepare the query parameters
    $params = [
        'terms' => $location,
        'pageSize' => 20,
        'channel' => 'All'
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $api_url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        return null;
    } else {
        // Decode the JSON response
        $response_data = json_decode($response, true);

        // Return the response data
        return $response_data;
    }

    // Close the cURL session
    curl_close($ch);
}

// Fetch property details from Domain API
function rc_domain_fetch_property($extracted_property_id) {
    $access_token = rc_domain_fetch_access_token();
    if (!$access_token) {
        return null;
    }

    // Define the base API URL
    $base_url = 'https://api.domain.com.au/';
    $api_version = 'v1';
    $endpoint = 'properties/' . $extracted_property_id;

    // Construct the full API URL
    $api_url = $base_url . $api_version . '/' . $endpoint;

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    $options = [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ],
    ];

    curl_setopt_array($ch, $options);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        return null;
    } else {
        // Decode the JSON response
        $result = json_decode($response, true);

        // Check for API errors
        if (isset($result['error'])) {
            echo 'Error: ' . $result['error'];
            return null;
        } else {
            // Return the result
            return $result;
        }
    }

    // Close the cURL session
    curl_close($ch);
}

// Fetch property price estimate from Domain API
function rc_domain_fetch_property_price_estimate($extracted_property_id) {
    $access_token = rc_domain_fetch_access_token();
    if (!$access_token) {
        return null;
    }

    // Define the base API URL
    $base_url = 'https://api.domain.com.au/';
    $api_version = 'v1';
    $endpoint = 'properties/' . $extracted_property_id . '/priceEstimate';

    // Construct the full API URL
    $api_url = $base_url . $api_version . '/' . $endpoint;

    var_dump($api_url);

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    $options = [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ],
    ];

    curl_setopt_array($ch, $options);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        return null;
    } else {
        // Decode the JSON response
        $result = json_decode($response, true);

        // Check for API errors
        if (isset($result['error'])) {
            echo 'Error: ' . $result['error'];
            return null;
        } else {
            // Return the result
            return $result;
        }
    }

    // Close the cURL session
    curl_close($ch);
}

// Fetch schools from Domain API
function rc_domain_fetch_schools($latitude, $longitude) {
    $access_token = rc_domain_fetch_access_token();
    if (!$access_token || !$latitude || !$longitude) {
        return null;
    }

    // Define the base API URL
    $base_url = 'https://api.domain.com.au/';
    $api_version = 'v2';
    $endpoint = 'schools/' . urlencode($latitude) . '/' . urlencode($longitude);

    // Construct the full API URL
    $api_url = $base_url . $api_version . '/' . $endpoint;

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    $options = [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ],
    ];

    curl_setopt_array($ch, $options);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        return null;
    } else {
        // Decode the JSON response
        $result = json_decode($response, true);

        // Check for API errors
        if (isset($result['error'])) {
            echo 'Error: ' . $result['error'];
            return null;
        } else {
            // Return the result
            return $result;
        }
    }

    // Close the cURL session
    curl_close($ch);
}

// Fetch demographics from Domain API
function rc_domain_fetch_demographics($state, $suburb, $postcode) {
    $access_token = rc_domain_fetch_access_token();
    if (!$access_token || !$state || !$suburb || !$postcode) {
        return null;
    }

    // Define the base API URL
    $base_url = 'https://api.domain.com.au/';
    $api_version = 'v2';
    $endpoint = 'demographics/' . urlencode($state) . '/' . urlencode($suburb) . '/' . urlencode($postcode);

    // Construct the full API URL
    $api_url = $base_url . $api_version . '/' . $endpoint;

    // Calculate the year 10 years ago from now
    $year = date('Y') - 10;

    // Prepare the query parameters
    $params = [
        'types' => 'AgeGroupOfPopulation, CountryOfBirth, NatureOfOccupancy, Occupation, GeographicalPopulation, DwellingStructure, EducationAttendance, HousingLoanRepayment, MaritalStatus, Religion, TransportToWork, FamilyComposition, HouseholdIncome, Rent, LabourForceStatus',
        'year' => $year
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    $options = [
        CURLOPT_URL => $api_url . '?' . http_build_query($params),
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ],
    ];

    curl_setopt_array($ch, $options);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        return null;
    } else {
        // Decode the JSON response
        $result = json_decode($response, true);

        // Check for API errors
        if (isset($result['error'])) {
            echo 'Error: ' . $result['error'];
            return null;
        } else {
            // Return the result
            return $result;
        }
    }

    // Close the cURL session
    curl_close($ch);
}

// Fetch suburb performance statistics from Domain API
function rc_domain_fetch_suburb_performance_statistics($state, $suburb, $postcode) {
    $access_token = rc_domain_fetch_access_token();
    if (!$access_token) {
        return null;
    }

    // Define the base API URL
    $base_url = 'https://api.domain.com.au/';
    $api_version = 'v2';
    $endpoint = 'suburbPerformanceStatistics/' . urlencode($state) . '/' . urlencode($suburb) . '/' . urlencode($postcode);

    // Construct the full API URL
    $api_url = $base_url . $api_version . '/' . $endpoint;

    // Prepare the query parameters
    $params = [
        'propertyCategory' => '',
        'bedrooms' => '',
        'periodSize' => 'quarters',
        'startingPeriodRelativeToCurrent' => '1',
        'totalPeriods' => '40'
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    $options = [
        CURLOPT_URL => $api_url . '?' . http_build_query($params),
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ],
    ];

    curl_setopt_array($ch, $options);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        return null;
    } else {
        // Decode the JSON response
        $result = json_decode($response, true);

        // Check for API errors
        if (isset($result['error'])) {
            echo 'Error: ' . $result['error'];
            return null;
        } else {
            // Return the result
            return $result;
        }
    }

    // Close the cURL session
    curl_close($ch);
}

// Extract the property suggest data
function rc_domain_extract_property_suggest($fetched_property_suggest) {
    if (!$fetched_property_suggest) {
        return null;
    }

    // Decode the JSON string
    $fetched_property_suggest = json_decode($fetched_property_suggest, true);

    // Extract the required data
    $extracted_data = [];

    foreach ($fetched_property_suggest as $property) {
        $address_components = $property['addressComponents'];

        $extracted_data[] = [
            'address' => $property['address'],
            'unit_number' => isset($address_components['unitNumber']) ? $address_components['unitNumber'] : null,
            'street_number' => isset($address_components['streetNumber']) ? $address_components['streetNumber'] : null,
            'street_name' => isset($address_components['streetName']) ? $address_components['streetName'] : null,
            'street_type' => isset($address_components['streetType']) ? $address_components['streetType'] : null,
            'street_type_long' => isset($address_components['streetTypeLong']) ? $address_components['streetTypeLong'] : null,
            'suburb' => isset($address_components['suburb']) ? $address_components['suburb'] : null,
            'post_code' => isset($address_components['postCode']) ? $address_components['postCode'] : null,
            'state' => isset($address_components['state']) ? $address_components['state'] : null,
            'id' => isset($property['id']) ? $property['id'] : null,
            'relative_score' => isset($property['relativeScore']) ? $property['relativeScore'] : null,
        ];
    }

    // Return the extracted data as an associative array
    return $extracted_data;
}

// Extract the property data
function rc_domain_extract_property($fetched_property) {
    if (!$fetched_property) {
        return null;
    }

    // Decode the JSON string
    $fetched_property = json_decode($fetched_property, true);

    // Extract general data
    $id = isset($fetched_property['id']) ? $fetched_property['id'] : null;
    $canonical_url = isset($fetched_property['canonicalUrl']) ? $fetched_property['canonicalUrl'] : null;
    $url_slug = isset($fetched_property['urlSlug']) ? $fetched_property['urlSlug'] : null;
    $url_slug_short = isset($fetched_property['urlSlugShort']) ? $fetched_property['urlSlugShort'] : null;
    $on_market_types = isset($fetched_property['onMarketTypes']) ? $fetched_property['onMarketTypes'] : [];
    $status = isset($fetched_property['status']) ? $fetched_property['status'] : null;
    $adverts = isset($fetched_property['adverts']) ? $fetched_property['adverts'] : [];
    $flat_number = isset($fetched_property['flatNumber']) ? $fetched_property['flatNumber'] : null;
    $is_residential = isset($fetched_property['isResidential']) ? $fetched_property['isResidential'] : null;
    $internal_area = isset($fetched_property['internalArea']) ? $fetched_property['internalArea'] : null;
    $area_size = isset($fetched_property['areaSize']) ? $fetched_property['areaSize'] : null;
    $created = isset($fetched_property['created']) ? $fetched_property['created'] : null;
    $updated = isset($fetched_property['updated']) ? $fetched_property['updated'] : null;

    // Extract address details
    $address_id = isset($fetched_property['addressId']) ? $fetched_property['addressId'] : null;
    $address = isset($fetched_property['address']) ? $fetched_property['address'] : null;
    $address_coordinates = isset($fetched_property['addressCoordinate']) ? $fetched_property['addressCoordinate'] : null;
    $latitude = isset($address_coordinates['lat']) ? $address_coordinates['lat'] : null;
    $longitude = isset($address_coordinates['lon']) ? $address_coordinates['lon'] : null;
    $postcode = isset($fetched_property['postcode']) ? $fetched_property['postcode'] : null;
    $state = isset($fetched_property['state']) ? $fetched_property['state'] : null;
    $street_address = isset($fetched_property['streetAddress']) ? $fetched_property['streetAddress'] : null;
    $street_name = isset($fetched_property['streetName']) ? $fetched_property['streetName'] : null;
    $street_number = isset($fetched_property['streetNumber']) ? $fetched_property['streetNumber'] : null;
    $street_type = isset($fetched_property['streetType']) ? $fetched_property['streetType'] : null;
    $street_type_long = isset($fetched_property['streetTypeLong']) ? $fetched_property['streetTypeLong'] : null;
    $suburb = isset($fetched_property['suburb']) ? $fetched_property['suburb'] : null;
    $suburb_id = isset($fetched_property['suburbId']) ? $fetched_property['suburbId'] : null;

    // Extract features
    $bathrooms = isset($fetched_property['bathrooms']) ? $fetched_property['bathrooms'] : null;
    $bedrooms = isset($fetched_property['bedrooms']) ? $fetched_property['bedrooms'] : null;
    $car_spaces = isset($fetched_property['carSpaces']) ? $fetched_property['carSpaces'] : null;
    $created = isset($fetched_property['created']) ? $fetched_property['created'] : null;
    $features = isset($fetched_property['features']) ? $fetched_property['features'] : [];

    // Extract history
    $sales_history = isset($fetched_property['history']['sales']) ? $fetched_property['history']['sales'] : [];
    $rentals_history = isset($fetched_property['history']['rentals']) ? $fetched_property['history']['rentals'] : [];

    // Extract photos
    $photos = isset($fetched_property['photos']) ? $fetched_property['photos'] : [];

    // Return the extracted data as an associative array
    return [
        'general_data' => [
            'id' => $id,
            'canonical_url' => $canonical_url,
            'url_slug' => $url_slug,
            'url_slug_short' => $url_slug_short,
            'on_market_types' => $on_market_types,
            'status' => $status,
            'adverts' => $adverts,
            'flat_number' => $flat_number,
            'is_residential' => $is_residential,
            'internal_area' => $internal_area,
            'area_size' => $area_size,
            'created' => $created,
            'updated' => $updated,
        ],
        'address_data' => [
            'address_id' => $address_id,
            'address' => $address,
            'address_coordinates' => $address_coordinates,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'postcode' => $postcode,
            'state' => $state,
            'street_address' => $street_address,
            'street_name' => $street_name,
            'street_number' => $street_number,
            'street_type' => $street_type,
            'street_type_long' => $street_type_long,
            'suburb' => $suburb,
            'suburb_id' => $suburb_id,
        ],
        'features_data' => [
            'bathrooms' => $bathrooms,
            'bedrooms' => $bedrooms,
            'car_spaces' => $car_spaces,
            'created' => $created,
            'features' => $features,
        ],
        'history_data' => [
            'sales_history' => $sales_history,
            'rentals_history' => $rentals_history,
        ],
        'photos_data' => $photos,
    ];
}

// Extract the property price estimate data
function rc_domain_extract_property_price_estimate($fetched_property_price_estimate) {
    if (!$fetched_property_price_estimate) {
        return null;
    }

    // Decode the JSON string
    $fetched_property_price_estimate = json_decode($fetched_property_price_estimate, true);

    // Extract the required data
    $date = isset($fetched_property_price_estimate['date']) ? $fetched_property_price_estimate['date'] : null;
    $lower_price = isset($fetched_property_price_estimate['lowerPrice']) ? $fetched_property_price_estimate['lowerPrice'] : null;
    $mid_price = isset($fetched_property_price_estimate['midPrice']) ? $fetched_property_price_estimate['midPrice'] : null;
    $price_confidence = isset($fetched_property_price_estimate['priceConfidence']) ? $fetched_property_price_estimate['priceConfidence'] : null;
    $source = isset($fetched_property_price_estimate['source']) ? $fetched_property_price_estimate['source'] : null;
    $upper_price = isset($fetched_property_price_estimate['upperPrice']) ? $fetched_property_price_estimate['upperPrice'] : null;
    $history = isset($fetched_property_price_estimate['history']) ? $fetched_property_price_estimate['history'] : [];
    
    // Return the extracted data as an associative array
    return [
        'date' => $date,
        'lower_price' => $lower_price,
        'mid_price' => $mid_price,
        'price_confidence' => $price_confidence,
        'source' => $source,
        'upper_price' => $upper_price,
        'history' => $history,
    ];
}

// Extract the schools data
function rc_domain_extract_schools($fetched_schools) {
    if (!$fetched_schools) {
        return null;
    }

    // Decode the JSON string
    $fetched_schools = json_decode($fetched_schools, true);
    
    // Extract the required data
    $extracted_data = [];

    var_dump($extracted_schools);

    foreach ($fetched_schools as $school_info) {
        $school = $school_info['school'];
        $profile = isset($school['profile']) ? $school['profile'] : [];
    
        $extracted_data[] = [
            'distance' => $school_info['distance'],
            'name' => $school['name'],
            'suburb' => $school['suburb'],
            'state' => $school['state'],
            'postcode' => $school['postcode'],
            'url' => isset($profile['url']) ? $profile['url'] : null,
            'totalEnrolments' => isset($profile['totalEnrolments']) ? $profile['totalEnrolments'] : null
        ];
    }

    // Return the extracted data as an associative array
    return $extracted_data;
}

// Extract the demographics data
function rc_domain_extract_demographics($fetched_demographics) {
    if (!$fetched_demographics) {
        return null;
    }

    // Decode the JSON string
    $fetched_demographics = json_decode($fetched_demographics, true);

    // Extract the required data
    $extracted_data = [];

    foreach ($fetched_demographics['demographics'] as $demographic) {
        $type = $demographic['type'];
        $total = $demographic['total'];
        $year = $demographic['year'];
        $items = $demographic['items'];

        $extracted_items = [];
        foreach ($items as $item) {
            $extracted_items[] = [
                'label' => $item['label'],
                'value' => $item['value'],
                'composition' => $item['composition']
            ];
        }

        $extracted_data[] = [
            'type' => $type,
            'total' => $total,
            'year' => $year,
            'items' => $extracted_items
        ];
    }

    // Return the extracted data as an associative array
    return $extracted_data;
}

// Extract the suburb performance statistics data
function rc_domain_extract_suburb_performance_statistics($fetched_suburb_performance_statistics) {
    if (!$fetched_suburb_performance_statistics) {
        return null;
    }

    // Decode the JSON string
    $fetched_suburb_performance_statistics = json_decode($fetched_suburb_performance_statistics, true);

    // Extract the required data
    $header = $fetched_suburb_performance_statistics['header'];
    $series_info = $fetched_suburb_performance_statistics['series']['seriesInfo'];

    $extracted_data = [
        'suburb' => $header['suburb'],
        'state' => $header['state'],
        'property_category' => $header['propertyCategory'],
        'series' => []
    ];
    
    foreach ($series_info as $info) {
        $values = $info['values'];
        $extracted_data['series'][] = [
            'year' => $info['year'],
            'month' => $info['month'],
            'median_sold_price' => isset($values['medianSoldPrice']) ? $values['medianSoldPrice'] : null,
            'number_sold' => isset($values['numberSold']) ? $values['numberSold'] : null,
            'highest_sold_price' => isset($values['highestSoldPrice']) ? $values['highestSoldPrice'] : null,
            'lowest_sold_price' => isset($values['lowestSoldPrice']) ? $values['lowestSoldPrice'] : null,
            '5th_percentile_sold_price' => isset($values['5thPercentileSoldPrice']) ? $values['5thPercentileSoldPrice'] : null,
            '25th_percentile_sold_price' => isset($values['25thPercentileSoldPrice']) ? $values['25thPercentileSoldPrice'] : null,
            '75th_percentile_sold_price' => isset($values['75thPercentileSoldPrice']) ? $values['75thPercentileSoldPrice'] : null,
            '95th_percentile_sold_price' => isset($values['95thPercentileSoldPrice']) ? $values['95thPercentileSoldPrice'] : null,
            'median_sale_listing_price' => isset($values['medianSaleListingPrice']) ? $values['medianSaleListingPrice'] : null,
            'number_sale_listing' => isset($values['numberSaleListing']) ? $values['numberSaleListing'] : null,
            'highest_sale_listing_price' => isset($values['highestSaleListingPrice']) ? $values['highestSaleListingPrice'] : null,
            'lowest_sale_listing_price' => isset($values['lowestSaleListingPrice']) ? $values['lowestSaleListingPrice'] : null,
            'auction_number_auctioned' => isset($values['auctionNumberAuctioned']) ? $values['auctionNumberAuctioned'] : null,
            'auction_number_sold' => isset($values['auctionNumberSold']) ? $values['auctionNumberSold'] : null,
            'auction_number_withdrawn' => isset($values['auctionNumberWithdrawn']) ? $values['auctionNumberWithdrawn'] : null,
            'days_on_market' => isset($values['daysOnMarket']) ? $values['daysOnMarket'] : null,
            'discount_percentage' => isset($values['discountPercentage']) ? $values['discountPercentage'] : null,
            'median_rent_listing_price' => isset($values['medianRentListingPrice']) ? $values['medianRentListingPrice'] : null,
            'number_rent_listing' => isset($values['numberRentListing']) ? $values['numberRentListing'] : null,
            'highest_rent_listing_price' => isset($values['highestRentListingPrice']) ? $values['highestRentListingPrice'] : null,
            'lowest_rent_listing_price' => isset($values['lowestRentListingPrice']) ? $values['lowestRentListingPrice'] : null
        ];
    }

    // Return the extracted data as an associative array
    return $extracted_data;
}