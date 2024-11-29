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

    public $response;

    public function __construct()
    {

        $this->api_url = 'https://api.domain.com.au/v2/';
        $this->api_key = get_option('domain_api_key');
        $this->headers = [
            'accept' => 'application/json',
            'X-Api-Key' => $this->api_key,
        ];

        $this->current_timestamp = time();
        $this->response = 'API not used.';
    }

    public function buildQuery($params) {}

    public function get($endpoint, $params, $route = [], $version = 'v2')
    {

        global $post;
        $this->post_id = $post->ID;
        $this->endpoint = $this->api_url.$endpoint;
        $this->query_params = $params;

        if (! empty($route)) {
            $route = implode('/', $route);
            $this->endpoint = $this->endpoint.'/'.$route;
        }

        if ($version == 'v1') {
            str_replace('v2', 'v1', $this->api_url);
        }

        $this->request_url = add_query_arg($this->query_params, $this->endpoint);

        $this->data_key = 'domain_api_data_'.md5($this->request_url);
        $this->cache_key = 'domain_api_cache_'.md5($this->request_url);

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
        $this->response = json_decode($response, true);
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