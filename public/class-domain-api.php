<?php

/**
 * Class Domain API
 */
class Domain_API
{
    public $api_key;

    public $query_params;

    public $api_url;

    public $request_url;

    public $headers;

    public $endpoint;

    public $data;

    public $data_key;

    public $cache_key;

    public $post_id;

    public $cached_timestamp;

    public $current_timestamp;

    public $result;

    public function __construct($endpoint, $params, $route = [], $version = 'v2')
    {

        $this->api_url = 'https://api.domain.com.au/v2/';
        
        if ($version == 'v1') {
            $this->api_url = str_replace('v2', 'v1', $this->api_url);
        }

        $this->api_key = get_option('domain_api_key');
        $this->headers = [
            'accept' => 'application/json',
            'X-Api-Key' => $this->api_key,
        ];
        $this->current_timestamp = time();
        $this->endpoint = $this->api_url.$endpoint;
        $this->query_params = $params;

        if (! empty($route)) {
            $route = implode('/', $route);
            $this->endpoint = $this->endpoint.'/'.$route;
        }

        $this->request_url = add_query_arg($this->query_params, $this->endpoint);
        $this->request_url = $this->request_url;


        $this->data_key = 'domain_api_data_'.md5($this->request_url);
        $this->cache_key = 'domain_api_cache_'.md5($this->request_url);

        $this->get();
    }

    public function buildQuery($params) {}

    public function get()
    {

        global $post;
        $this->post_id = $post->ID;

        if ( $this->post_id === null ) {
            $this->result = 'False, no post ID. Please run the code on a single post or page.';
        }
        
        if ($this->isCached()) {
            if (! $this->hasCacheExpired()) {
                return $this;
            }
        }

        $response = wp_remote_get($this->request_url, [
            'headers' => $this->headers,
        ]);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $this->data = 'something went wrong: '.$error_message;
            $this->result = false;

            return $this;
        }

        $body = wp_remote_retrieve_body($response);

        $this->data = json_decode($body, true);
        $this->result = 'True, with fresh data from Domain API.';

        $this->updateData();

        return $this;
    }

    public function isCached()
    {
        $cached_data = get_post_meta($this->post_id, $this->data_key, true);
        $cached_timestamp = get_post_meta($this->post_id, $this->cache_key, true);

        if (empty($cached_data) || empty($cached_timestamp)) {
            return false;
        }

        $this->result = 'True, with cached data.';
        $this->data = $cached_data;
        $this->cached_timestamp = $cached_timestamp;

        return true;
    }

    // If the cache is older than 60 days, update the cache.
    public function hasCacheExpired()
    {
        if ($this->current_timestamp - $this->cached_timestamp > 5184000) {
            return true;
        }

        return false;
    }

    public function updateData()
    {
        update_post_meta($this->post_id, $this->data_key, $this->data);
        update_post_meta($this->post_id, $this->cache_key, $this->current_timestamp);

        $this->result = 'True, with updated data. Cache expired.';

    }
}

function rc_ida_domain_get_suburb_id($suburb, $state, $postcode) {
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

function rc_ida_domain_get_location_profile($suburb_id){
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

// Register the AJAX action
add_action('wp_ajax_update_location_postcodes_and_states', 'update_location_postcodes_and_states_ajax');

function update_location_postcodes_and_states_ajax() {
    // Verify the required parameters
    if (!isset($_POST['offset'], $_POST['priority_state'])) {
        wp_send_json_error('Missing parameters.');
    }

    $offset = intval($_POST['offset']);
    $priority_state = sanitize_text_field($_POST['priority_state']);
    $chunk_size = 10; // Number of terms to process in each request

    // Get the terms for the current chunk
    $terms = get_terms([
        'taxonomy' => 'location',
        'hide_empty' => false,
        'offset' => $offset,
        'number' => $chunk_size,
    ]);

    if (is_wp_error($terms)) {
        wp_send_json_error('Error fetching terms.');
    }

    $total_terms = wp_count_terms('location', ['hide_empty' => false]);

    if ($total_terms == 0) {
        wp_send_json_error('No terms found in the "location" taxonomy.');
    }

    $processed = $offset + count($terms);
    $percentage = ($processed / $total_terms) * 100;

    $log_messages = '';

    foreach ($terms as $term) {
        $term_name = $term->name;

        // Build the query string using the full state name
        $query = $term_name . ', ' . $priority_state . ', Australia';
        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
            'q' => $query,
            'format' => 'json',
            'limit' => 1,
            'addressdetails' => 1,
            'extratags' => 1,
        ]);

        // Fetch the response from the API
        $response = wp_remote_get($url);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            $log_messages .= "<p style='color: red;'>Error fetching data for term: {$term_name}</p>";
            continue; // Skip on API error or invalid response
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!empty($data) && isset($data[0]['address'])) {
            $address = $data[0]['address'];

            // Extract postcode and state
            $postcode = isset($address['postcode']) ? $address['postcode'] : '';
            $state = isset($address['state']) ? $address['state'] : '';

            $updated_postcode = false;
            $updated_state = false;

            // Update the 'postcode' meta field
            if (!empty($postcode)) {
                update_term_meta($term->term_id, 'postcode', $postcode);
                $updated_postcode = true;
            }

            // Compare the states directly
            if (!empty($state) && strtolower($state) === strtolower($priority_state)) {
                // Store the abbreviation of the state
                $state_abbr = convert_australian_state($priority_state);
                update_term_meta($term->term_id, 'state', $state_abbr);
                $updated_state = true;
            }

            // Build the log message
            $log_messages .= "<p>";
            $log_messages .= "<strong>Term:</strong> {$term_name}<br>";
            $log_messages .= "<strong>Postcode:</strong> " . ($postcode ?: 'N/A') . "<br>";
            $log_messages .= "<strong>State:</strong> " . ($state ?: 'N/A') . "<br>";
            $log_messages .= "Postcode Updated: " . ($updated_postcode ? 'Yes' : 'No') . "<br>";
            $log_messages .= "State Updated: " . ($updated_state ? 'Yes' : 'No') . "<br>";
            $log_messages .= "</p>";
        } else {
            $log_messages .= "<p class=\"text-dark\" style=\"color: orange;\">No data found for term: {$term_name}</p>";
        }

        // Sleep for a second to respect API rate limits
        sleep(1);
    }

    // Determine if we're finished
    $finished = ($processed >= $total_terms);

    // Send response back to the frontend
    wp_send_json_success([
        'message'    => $log_messages,
        'percentage' => $percentage,
        'next_offset' => $offset + $chunk_size,
        'finished'   => $finished,
    ]);
}