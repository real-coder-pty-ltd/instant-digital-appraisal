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
    $url_slug = get_option('pricefinder_da_page_url_slug') ? : 'instant-digital-appraisal';
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
    $url_slug = get_option('pricefinder_da_page_url_slug') ? : 'instant-digital-appraisal';
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
    $url_slug = get_option('pricefinder_da_page_url_slug') ? : 'instant-digital-appraisal';

    // Remove the required legend from the form
    if (is_page($url_slug)) {
        add_filter('gform_required_legend', '__return_empty_string');
    }
};

add_action('wp', 'pdfa_instant_digital_appraisal_hooks');