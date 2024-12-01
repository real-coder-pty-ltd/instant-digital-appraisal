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
        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.get_option('rc_ida_google_maps_api_key').'&amp;libraries=places', [], $this->version, false);

        /**
         * Domain API - Property Suggest.
         */
        global $post;

        if ( $post === null ) {
            return;
        }

        if (has_shortcode($post->post_content, 'rc_ida_address_form') || has_shortcode($post->post_content, 'rc_ida_appraisal_form')) {
            wp_enqueue_script('rc-domain-property-suggest-address', plugin_dir_url(__FILE__).'/js/rc-ida-domain-property-suggest-address.js', ['jquery'], null, true);

            wp_localize_script('rc-domain-property-suggest-address', 'autocomplete_params', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('autocomplete_nonce'),
            ]);
        }

        if (has_shortcode($post->post_content, 'rc_ida_suburb_form')) {
            wp_enqueue_script('rc-domain-property-suggest-suburb', plugin_dir_url(__FILE__).'/js/rc-ida-domain-property-suggest-suburb.js', ['jquery'], null, true);
        }
    }
}

class BoundaryFetcher
{
    private $country;

    private $state;

    private $suburb;

    private $url = 'https://overpass-api.de/api/interpreter';

    public $data;

    public $boundary;

    public $is_error;

    public $center;

    public $post_id;

    public function __construct($suburb, $state = 'Queensland', $country = 'Australia', $post_id = null)
    {
        $this->country = $country;
        $this->state = $state;
        $this->suburb = $suburb;

        $this->post_id = $post_id;

        $this->fetchBoundaryData();

        return $this;
    }

    public function getLat()
    {
        $lat = ($this->data[0]['bounds']['minlat'] + $this->data[0]['bounds']['maxlat']) / 2;
        if ($lat) {
            return $lat;
        }

        return null;
    }

    public function getLong()
    {
        $long = ($this->data[0]['bounds']['minlon'] + $this->data[0]['bounds']['maxlon']) / 2;
        if ($long) {
            return $long;
        }

        return null;
    }

    private function setCenter()
    {
        global $post;

        $post_id = $this->post_id ?? $post->ID;

        $center = [
            'lat' => ($this->data[0]['bounds']['minlat'] + $this->data[0]['bounds']['maxlat']) / 2,
            'lng' => ($this->data[0]['bounds']['minlon'] + $this->data[0]['bounds']['maxlon']) / 2,
        ];

        update_post_meta($post_id, 'rc_lat', $center['lat']);
        update_post_meta($post_id, 'rc_long', $center['lng']);

        $this->center = json_encode($center);
        update_post_meta($post_id, 'rc_center', $this->center);
    }

    public function fetchBoundaryData()
    {
        if (! $this->country || ! $this->state || ! $this->suburb) {
            return [
                'error' => true,
                'message' => 'Country, state, and suburb must be set before fetching data.',
            ];
        }

        $query = <<<EOT
[out:json];
area["name"="{$this->country}"]["boundary"="administrative"]->.a;
area["name"="{$this->state}"]["boundary"="administrative"](area.a)->.b;
relation["name"="{$this->suburb}"]["boundary"="administrative"](area.b);
out geom;
EOT;

        $response = wp_remote_post($this->url, [
            'body' => [
                'data' => $query,
            ],
        ]);

        if (is_wp_error($response)) {

            $this->is_error = true;

            return [
                'error' => true,
                'message' => $response->get_error_message(),
            ];
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($data['elements'])) {
            return [
                'error' => true,
                'message' => 'No data found for the specified query.',
            ];
        }

        $this->data = $data['elements'];

        $this->stichPolygons();
        $this->setCenter();

        return $this;
    }

    public function stichPolygons()
    {
        global $post;
        $post_id = $this->post_id ?? $post->ID;

        $stitchedPolygon = [];

        $ways = array_filter($this->data[0]['members'], function ($way) {
            return $way['type'] === 'way';
        });

        $segments = array_map(function ($way) {
            return $way['geometry'];
        }, $ways);

        $stitchedPolygon = array_shift($segments);

        while (! empty($segments)) {
            $lastPoint = end($stitchedPolygon);

            foreach ($segments as $key => $segment) {
                $firstPoint = $segment[0];
                $lastSegmentPoint = end($segment);

                if ($lastPoint === $firstPoint) {

                    $stitchedPolygon = array_merge($stitchedPolygon, array_slice($segment, 1));
                    unset($segments[$key]);
                    break;
                } elseif ($lastPoint === $lastSegmentPoint) {

                    $stitchedPolygon = array_merge($stitchedPolygon, array_slice(array_reverse($segment), 1));
                    unset($segments[$key]);
                    break;
                }
            }
        }

        $firstPoint = $stitchedPolygon[0];
        $lastPoint = end($stitchedPolygon);
        if ($firstPoint !== $lastPoint) {
            $stitchedPolygon[] = $firstPoint;
        }

        $suburb_coords_json = json_encode($stitchedPolygon);
        $suburb_coords_json = str_replace('lon', 'lng', $suburb_coords_json);

        $this->boundary = $suburb_coords_json;
        update_post_meta($post_id, 'rc_boundary', $this->boundary);
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
        if (get_option('rc_ida_client_id') && get_option('rc_ida_client_secret')) {

            $client_id = get_option('rc_ida_client_id');
            $client_secret = get_option('rc_ida_client_secret');
            pricefinder_da_notice('secondary', 'Client ID & Secret Key set. Attempting to generate key through API...');

        } else {

            pricefinder_da_notice('danger', 'Error: No private key and/or Secret Key set. Please go to tools->rmar settings and add your credentials.');

            return;

        }

        $ch = curl_init();
        $client_id = get_option('rc_ida_client_id');
        $client_secret = get_option('rc_ida_client_secret');
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
// 	<script src="https://maps.googleapis.com/maps/api/js?key='.get_option('rc_ida_google_maps_api_key').'&callback=initAutocomplete&libraries=places&v=weekly" defer></script>';
// }

// add_action('wp_enqueue_scripts', 'wpdocs_theme_name_scripts');

/*
* 	Address Form Shortcode.
*
*/
function rc_ida_address_form($atts)
{
    // Define default attributes
    $atts = shortcode_atts(
        [
            'form_placeholder' => 'Enter your address',
            'form_submit' => 'Submit',
        ],
        $atts,
        'rc_ida_address_form'
    );

    // Extract attributes
    $url_slug = get_option('rc_ida_appraisal_page_url_slug') ?: 'instant-digital-appraisal';
    $form_placeholder = $atts['form_placeholder'];
    $form_submit = $atts['form_submit'];

    echo '
        <form id="rc-ida-address-form" method="GET" action="/'.$url_slug.'/">
            <div class="d-flex flex-row">
                <div id="rc-ida-search" class="rc-ida-search position-relative w-100">
                    <input id="rc-ida-address" class="rc-ida-address form-control input-l rounded h-100" placeholder="'.$form_placeholder.'" name="address" type="text" required>
                    <input id="rc-ida-state" class="rc-ida-state" type="hidden" name="state">
                    <input id="rc-ida-suburb" class="rc-ida-suburb" type="hidden" name="suburb">
                    <input id="rc-ida-postcode" class="rc-ida-postcode" type="hidden" name="postcode">
                    <input id="rc-ida-property-id" class="rc-ida-property-id" type="hidden" name="property_id">
                    <ul id="rc-ida-results" class="rc-ida-results card shadow gform-theme__disable-reset position-absolute start-0 top-100 w-100 list-unstyled z-2 px-2 d-none"></ul>
                </div>
                <button id="rc-ida-submit" type="submit" class="btn btn-primary btn-lg rounded text-nowrap ms-2" disabled>'.$form_submit.'</button>
            </div>
        </form>
        <div id="loading-container">
            <img id="loading-image" src="/app/plugins/instant-digital-appraisal/public/images/loader.jpg" alt="Loading..." />
        </div>
    ';

}
add_shortcode('rc_ida_address_form', 'rc_ida_address_form');

/*
* 	Suburb Form Shortcode.
*
*/
function rc_ida_suburb_form($atts)
{
    $google_maps_api_key = get_option('rc_ida_google_maps_api_key');

    // Define default attributes
    $atts = shortcode_atts(
        [
            'form_placeholder' => 'Select your suburb.',
        ],
        $atts,
        'rc-ida-suburb-form'
    );

    // Extract attributes
    $form_placeholder = $atts['form_placeholder'];

    // Get all terms from the 'suburb' taxonomy
    $suburbs = get_terms([
        'taxonomy' => 'location',
        'hide_empty' => false,
    ]);

    // Initialize the suburbs list
    $suburb_list = [];
    foreach ($suburbs as $suburb) {
        $suburb_list[] = $suburb->name;
    }

    // Define the query arguments
    $args = [
        'post_type' => 'suburb-profile',
        'posts_per_page' => -1,
        'meta_query' => [
            'relation' => 'OR',
            [
                'key' => 'rc_suburb',
                'value' => $suburb_list,
                'compare' => 'IN',
            ],
        ],
        'orderby' => 'title',
        'order' => 'ASC',
    ];

    // Create a new WP_Query instance
    $query = new WP_Query($args);
    $suburb_results = '';

    // Check if there are any posts to display
    if ($query->have_posts()) {

        // The Loop
        while ($query->have_posts()) {
            $query->the_post();
            $suburb_meta = get_post_meta(get_the_ID(), 'rc_suburb', true);
            $suburb_meta = ucwords(strtolower($suburb_meta));
            $state_meta = get_post_meta(get_the_ID(), 'rc_state', true);
            $postcode_meta = get_post_meta(get_the_ID(), 'rc_postcode', true);
            $link = get_post_permalink(get_the_ID());

            $suburb_results .= '<li class="px-1 py-2 border-bottom" data-url="'.$link.'">'.$suburb_meta.', '.$state_meta.' '.$postcode_meta.'</li>';
        }
    } else {
        // No posts found
        echo '<li class="px-1 py-2 border-bottom">No suburb profiles found.</li>';
    }

    // Restore original Post Data
    wp_reset_postdata();

    echo '
        <div id="rc-ida-suburb-form" method="GET" action="'.$url_slug.'">
            <div class="d-flex flex-row">
                <div id="rc-ida-search" class="rc-ida-search position-relative w-100">
                    <input id="rc-ida-address" class="rc-ida-address form-control input-l rounded" placeholder="'.$form_placeholder.'" name="address" type="text" required style="height:45px">
                    <ul id="rc-ida-results" class="rc-ida-results card shadow gform-theme__disable-reset position-absolute start-0 top-100 w-100 list-unstyled z-2 px-2 d-none">
                        '.$suburb_results.'
                    </ul>
                </div>
            </div>
        </div>
    ';

}
add_shortcode('rc_ida_suburb_form', 'rc_ida_suburb_form');

/*
* 	Appraisal Form Shortcode.
*
*/
function rc_ida_appraisal_form($atts)
{
    // Define default attributes
    $atts = shortcode_atts(
        [
            'class_section' => 'bg-light',
            'class_container' => 'mw-1512',
            'class_row' => 'row mx-0 align-items-center',
            'class_col' => 'col-lg-6 px-0',
            'class_form_wrapper' => 'container px-lg-0',
            'class_form' => 'bg-white rounded shadow p-5 mx-0 mx-lg-5 my-5',
            'class_map_wrapper' => 'container px-lg-0 d-none d-lg-block',
            'class_map' => 'vh-100',
        ],
        $atts
    );

    echo '
        <section class="section--instant-digital-appraisal '.$atts['class_section'].'">
            <div class="'.$atts['class_container'].'">
                <div class="'.$atts['class_row'].'">
                    <div class="'.$atts['class_col'].'">
                        <div class="'.$atts['class_form_wrapper'].'">
                            <div class="'.$atts['class_form'].'">'.do_shortcode('[gravityform id="1" title="false"]').'</div>
                        </div>
                    </div>
                    <div class="'.$atts['class_col'].'">
                        <div class="'.$atts['class_map_wrapper'].'">
                            <div class="'.$atts['class_map'].'" id="rc-ida-google-map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>\
    ';
}
add_shortcode('rc_ida_appraisal_form', 'rc_ida_appraisal_form');

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
function rc_ida_nice_number($n)
{
    // first strip any formatting;
    $n = str_replace(',', '', $n);

    // is this a number?
    if (! is_numeric($n)) {
        return false;
    }

    // convert to a number
    $n = (float) $n;

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
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$lat1.','.$long1.'&destinations='.$lat2.','.$long2.'&key='.get_option('rc_ida_google_maps_api_key');
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
function rc_ida_hooks()
{
    $url_slug = get_option('rc_ida_appraisal_page_url_slug') ?: 'instant-digital-appraisal';

    // Remove the required legend from the form
    if (is_page($url_slug)) {
        add_filter('gform_required_legend', '__return_empty_string');
    }
}
add_action('wp', 'rc_ida_hooks');

/**
 * Suburb Profile Functions
 */

// Create or update suburb profile post, redirect to single page
function rc_ida_suburb_profile($suburb, $state, $postcode)
{
    if (! $suburb || ! $state || ! $postcode) {
        return null;
    }

    // Prepare the post data
    $post_id = 0;
    $post_action = 'created';
    $address = $suburb.', '.$state.' '.$postcode;
    $slug = sanitize_title($suburb.'-'.$state.'-'.$postcode);

    // Check if a post with the given slug exists
    $post = get_page_by_path($slug, OBJECT, 'suburb-profile');

    // Prepare the post data
    $today = date('Y-m-d H:i:s');

    if ($post) {
        // Post exists, update its data
        $post_id = $post->ID;
        $post_action = 'updated';
        $modified_date = get_field('general_modified_date', $post_id);
        $date_diff = (strtotime($today) - strtotime($modified_date)) / (60 * 60 * 24);

        $post_data = [
            'ID' => $post_id,
            'post_title' => $address,
        ];

        wp_update_post($post_data);

        if ($date_diff > 30) {
            update_field('general_modified_date', $today, $post_id);
        }

        // Redirect to single post page
        wp_redirect(get_permalink($post_id));
        exit;

    } else {
        // Post does not exist, create a new one
        $post_action = 'created';
        $post_data = [
            'post_title' => $address,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_type' => 'suburb-profile',
        ];
        $post_id = wp_insert_post($post_data);

        $fields = [
            'general_created_date' => $today,
            'general_modified_date' => $today,
            'general_address' => $address,
            'general_suburb' => $suburb,
            'general_state' => $state,
            'general_postcode' => $postcode,
        ];
        foreach ($fields as $field_key => $field_value) {
            update_field($field_key, $field_value, $post_id);
        }

        // Redirect to single post page
        wp_redirect(get_permalink($post_id));
        exit;
    }
}

/**
 * Domain API Functions
 */

// Fetch the access token from Domain API
function rc_ida_domain_fetch_access_token()
{
    // Set your client ID and client secret
    $client_id = get_option('rc_ida_client_id');
    $client_secret = get_option('rc_ida_client_secret');

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
        'Content-Type: application/x-www-form-urlencoded',
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, $client_id.':'.$client_secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: '.curl_error($ch);
    } else {
        // Decode the JSON response
        $response_data = json_decode($response, true);

        // Output the access token
        if (isset($response_data['access_token'])) {
            return $response_data['access_token'];
        } else {
            echo 'Error retrieving access token: '.$response;
        }
    }

    // Close cURL session
    curl_close($ch);

    return null;
}

// Fetch property suggestions from Domain API
function rc_ida_domain_fetch_property_suggest($location)
{
    $access_token = rc_ida_domain_fetch_access_token();
    if (! $access_token) {
        return null;
    }

    // Define the base API URL
    $base_url = 'https://api.domain.com.au/';
    $api_version = 'v1';
    $endpoint = 'properties/_suggest';

    // Construct the full API URL
    $api_url = $base_url.$api_version.'/'.$endpoint;

    // Prepare the query parameters
    $params = [
        'terms' => $location,
        'pageSize' => 20,
        'channel' => 'All',
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $api_url.'?'.http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$access_token,
        'Content-Type: application/json',
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: '.curl_error($ch);

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

// Fetch property suggest data via AJAX
function rc_ida_domain_fetch_property_suggest_ajax()
{
    check_ajax_referer('autocomplete_nonce', 'nonce');

    if (! isset($_POST['location'])) {
        wp_send_json_error('Location not provided');
    }

    $location = sanitize_text_field($_POST['location']);
    $suggestions = rc_ida_domain_fetch_property_suggest($location);

    if ($suggestions) {
        wp_send_json_success($suggestions);
    } else {
        wp_send_json_error('No suggestions found');
    }
}
add_action('wp_ajax_rc_ida_domain_fetch_property_suggest', 'rc_ida_domain_fetch_property_suggest_ajax');
add_action('wp_ajax_nopriv_rc_ida_domain_fetch_property_suggest', 'rc_ida_domain_fetch_property_suggest_ajax');