<?php
/**
 * Class Boundary Fetcher
 * 
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

class Boundary_Fetcher
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