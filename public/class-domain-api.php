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

    public $post_id;

    public $updated;

    public $updated_date;

    public $result;

    public function __construct()
    {

        $this->api_url = 'https://api.domain.com.au/v2/';
        $this->api_key = get_option('domain_api_key');
        $this->headers = [
            'accept' => 'application/json',
            'X-Api-Key' => $this->api_key,
        ];
    }

    public function get($endpoint, $params, $route = [], $version = 'v2')
    {
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

        
        $data = $this->cacheResult();

        // If we have the cached data, return it.
        if ($data->updated == 'cached data') {
            $this->data = $data->data;
            $this->result = 'True, with cached data.';
            return $this;
        }

        $response = wp_remote_get($this->request_url, [
            'headers' => $this->headers,
        ]);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $this->data = 'something went wrong: '.$error_message;
            $this->result = false;
        } else {
            $body = wp_remote_retrieve_body($response);
            $this->data = json_decode($body, true);
            $this->result = true;
        }

        return $this;
    }

    public function cacheResult() {

        global $post;
        $this->post_id = $post->ID;

        $cache_key = 'domain_api_data_'.md5($this->request_url);
        $cache_key_timestamp = 'domain_api_data_timestamp_'.md5($this->request_url);
        $now = time();

        $cached_data = get_post_meta($this->post_id, $cache_key, true);
        $timestamp = get_post_meta($this->post_id, $cache_key_timestamp, true);

        if (empty($cached_data) || empty($timestamp)) {
            update_post_meta($this->post_id, $cache_key, $this->data);
            update_post_meta($this->post_id, $cache_key_timestamp, time());

            $this->updated = 'new';
            $this->updated_date = $now;

            return $this;
        }

        // If the cache is older than 60 days, update the cache.
        if ( $now - $timestamp > 5184000 ) {
            update_post_meta($this->post_id, $cache_key, $this->data);
            update_post_meta($this->post_id, $cache_key_timestamp, time());

            $this->updated = 'updated';
            $this->updated_date = $now;
            return $this;
        }

        $this->data = $cached_data;
        $this->updated = 'cached data';
        $this->updated_date = $timestamp;

        return $this;
    }
}