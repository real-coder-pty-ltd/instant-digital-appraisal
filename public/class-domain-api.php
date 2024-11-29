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

    public function __construct()
    {

        $this->api_url = 'https://api.domain.com.au/v2/';
        $this->api_key = '';
        $this->headers = [
            'accept' => 'application/json',
            'X-Api-Key' => $this->api_key,
        ];

    }

    public function get($endpoint, $params, $route = [], $version = 'v2'): array|string
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

        $response = wp_remote_get($this->request_url, [
            'headers' => $this->headers,
        ]);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $this->data = 'something went wrong: '.$error_message;

            return false;
        } else {
            $body = wp_remote_retrieve_body($response);
            $this->data = json_decode($body, true);

            return true;
        }
    }
}

new Domain_API;