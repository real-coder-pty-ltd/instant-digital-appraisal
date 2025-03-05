<?php
/**
 * Hooks & helper functions for Domain API.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

function dsp_domain_get_suburb_id($suburb, $state, $postcode) 
{
    $suburb_id = null;
    $id = new Domain_API(
        'addressLocators',
        [
            'searchLevel' => 'Suburb',
            'suburb' => $suburb,
            'state' => $state,
            'postcode' => $postcode,
        ],
        [], 
        'v1'
    );

    foreach ($id->data[0]['ids'] as $item) {
        if (isset($item['level']) && $item['level'] === 'Suburb') {
            $suburb_id = $item['id'];
            break;
        }
    }

    if ($suburb_id !== null) {
        return $suburb_id;
    } else {
        return 0;
    }
}

function dsp_domain_get_suburb_demographics($suburb, $state, $postcode) 
{
    $demographics = new Domain_API(
        'demographics',
        [
            'types' => 'Rent,HouseholdIncome,DwellingStructure,NatureOfOccupancy,TransportToWork,Occupation,',
        ], 
        [ $state, $suburb, $postcode ],
        'v2'
    );

    if ($demographics !== null) {
        return $demographics;
    } else {
        return null;
    }
}

function dsp_domain_get_suburb_performance_statistics($suburb, $state, $postcode, $category) 
{
    $statistics = new Domain_API(
        'suburbPerformanceStatistics',
        [
            'propertyCategory' => $category,
            'bedrooms' => '3',
            'periodSize' => 'years',
            'startingPeriodRelativeToCurrent' => '1',
            'totalPeriods' => '10',
        ], 
        [ $state, $suburb, $postcode ],
        'v2'
    );

    if ($statistics !== null) {
        return $statistics;
    } else {
        return null;
    }
}

function dsp_domain_get_location_profile($suburb_id){
    $suburb_profile = null;
    $profile = new Domain_API(
        'locations',
        ['' => ''],
        ['profiles',$suburb_id], 
        'v1'
    );

    if ($profile  !== null) {
        $suburb_profile = $profile->data;
    } else {
        return null;
    }

    return $suburb_profile;
}

// Helper function to get full state name from input
function dsp_get_full_state_name($input)
{
    $states = [
        'NSW' => 'New South Wales',
        'QLD' => 'Queensland',
        'SA' => 'South Australia',
        'TAS' => 'Tasmania',
        'VIC' => 'Victoria',
        'WA' => 'Western Australia',
        'ACT' => 'Australian Capital Territory',
        'NT' => 'Northern Territory',
        'New South Wales' => 'New South Wales',
        'Queensland' => 'Queensland',
        'South Australia' => 'South Australia',
        'Tasmania' => 'Tasmania',
        'Victoria' => 'Victoria',
        'Western Australia' => 'Western Australia',
        'Australian Capital Territory' => 'Australian Capital Territory',
        'Northern Territory' => 'Northern Territory',
    ];

    $input_upper = strtoupper($input);
    $input_ucwords = ucwords(strtolower($input));

    if (isset($states[$input_upper])) {
        return $states[$input_upper];
    } elseif (isset($states[$input_ucwords])) {
        return $states[$input_ucwords];
    }

    return null;
}

// Helper function to get state abbreviation from input
function dsp_get_state_abbreviation($input)
{
    $states = [
        'NSW' => 'NSW',
        'QLD' => 'QLD',
        'SA' => 'SA',
        'TAS' => 'TAS',
        'VIC' => 'VIC',
        'WA' => 'WA',
        'ACT' => 'ACT',
        'NT' => 'NT',
        'New South Wales' => 'NSW',
        'Queensland' => 'QLD',
        'South Australia' => 'SA',
        'Tasmania' => 'TAS',
        'Victoria' => 'VIC',
        'Western Australia' => 'WA',
        'Australian Capital Territory' => 'ACT',
        'Northern Territory' => 'NT',
    ];

    $input_upper = strtoupper($input);
    $input_ucwords = ucwords(strtolower($input));

    if (isset($states[$input_upper])) {
        return $states[$input_upper];
    } elseif (isset($states[$input_ucwords])) {
        return $states[$input_ucwords];
    }

    return null;
}

/**
 * Pretty up our numbers
 */
function dsp_nice_number($n)
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

function create_api_usage_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'google_distance_api_usage';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        total_calls BIGINT(20) UNSIGNED DEFAULT 0,
        successful_responses BIGINT(20) UNSIGNED DEFAULT 0,
        last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Insert initial row
    $wpdb->insert($table_name, [
        'total_calls' => 0,
        'successful_responses' => 0,
    ]);
}

function dpp_domain_get_property_suggest($query)
{
    $api_url = 'https://api.domain.com.au/v1/properties/_suggest';
    $api_key = get_option('domain_api_key');

    $response = wp_remote_get($api_url, [
        'headers' => [
            'accept' => 'application/json',
            'X-Api-Key' => $api_key,
        ],
        'body' => [
            'terms' => $query,
            'channel' => 'All',
            'pageSize'=> 5,
        ],
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    return $data; 
}

function dpp_domain_get_property_profile($property_id)
{
    // $property_profile = null;
    // $profile = new Domain_API(
    //     'properties',
    //     ['' => ''],
    //     [$property_id], 
    //     'v1'
    // );

    // if ($profile  !== null) {
    //     $property_profile = $profile->data;
    // } else {
    //     return null;
    // }


    $api_url = 'https://api.domain.com.au/v1/properties/' . $property_id;
    $api_key = get_option('domain_api_key');

    $response = wp_remote_get($api_url, [
        'headers' => [
            'accept' => 'application/json',
            'X-Api-Key' => $api_key,
        ],
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    return $data; 
}

function dpp_domain_get_property_price_estimate($property_id)
{
    // $property_profile = null;
    // $profile = new Domain_API(
    //     'properties',
    //     ['' => ''],
    //     [$property_id,'priceEstimate'], 
    //     'v1'
    // );

    // if ($profile  !== null) {
    //     $property_profile = $profile->data;
    // } else {
    //     return null;
    // }


    $api_url = 'https://api.domain.com.au/v1/properties/' . $property_id . '/priceEstimate';
    $api_key = get_option('domain_api_key');

    $response = wp_remote_get($api_url, [
        'headers' => [
            'accept' => 'application/json',
            'X-Api-Key' => $api_key,
        ],
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    return $data;
}

function register_domain_address_suggest_ajax()
{
    add_action('wp_ajax_domain_address_suggest', 'domain_address_suggest');
    add_action('wp_ajax_nopriv_domain_address_suggest', 'domain_address_suggest');
}

function register_domain_ajax()
{
    register_domain_address_suggest_ajax();
}

add_action('init', 'register_domain_ajax');

function domain_address_suggest()
{
    if (! isset($_POST['query'])) {
        wp_send_json_error('No query provided');
        wp_die();
    }

    $query = sanitize_text_field($_POST['query']);
    $suggestions = dpp_domain_get_property_suggest($query);

    if ($suggestions) {
        wp_send_json_success($suggestions);
    } else {
        wp_send_json_error($suggestions);
    }

    wp_die();
}