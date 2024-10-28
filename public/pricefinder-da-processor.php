<?php
/**
 * This file grabs all the data required and generates the digital appraisal into a post type called 'appraisal'.
 */

/*
*   Load WordPress core so we can use its functions.
*   Main Variables. Include a unique ID, the Base, etc.
*   Anything that can be used across all API callls.
*/
// $path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
// require_once $path.'wp-load.php';

$base = 'https://api.pricefinder.com.au/v1/';
$uniqueid = uniqid();
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp/wp-load.php';
/*
*   Exit if no address is sent. This code looks silly?
*/
if (isset($_POST['address'])) {
    $location = $_POST['address'];
} else {
    if (isset($_GET['address'])) {
        $location = $_GET['address'];
    } else {
        echo 'No Address found. Exiting.';

        return;
    }
}

/*
*
*   Search for the property. This gets the Suburb ID and the Property ID for other calls.
*   Create the WordPress Appraisal.
*   Set the permalink.
*/

$search = new pfda_query($base.'suggest/properties?q='.$location.'&match_ids=false');

if (! empty($search->body)) {

    $suburb_id = $search->body->matches[0]->suburb->id;
    $property_id = $search->body->matches[0]->property->id;
    $appraisal = [
        'post_type' => 'appraisal',
        'post_title' => $search->body->matches[0]->display,
        'post_status' => 'publish',
        'post_name' => $uniqueid,
    ];

    $post_id = wp_insert_post($appraisal);
    $permalink = get_permalink($post_id);
    update_field('unique_id', $uniqueid, $post_id);
    update_field('suggest_result', json_encode($search->body->matches[0]), $post_id);

}

/**
 *  Get all the fields from the different API end points we require.
 *  I considered adding these to their respective areas, but certain fields require
 *  bits from more than one end point, so it got messy.
 */
$demographics = new pfda_query($base.'suburbs/'.$suburb_id.'/demographics');

if (! empty($demographics->response)) {

    update_field('demographics_result', json_encode($demographics->body), $post_id);

    /**
     *  Get the Home Ownership Sensus Topic from the $demographics array so we can
     * calculate the % of home ownership ((Purchasing + fully owned) / total).
     */
    $topic_list = [];

    foreach ($demographics->body->censusTopics as $topic) {

        if ($topic->topic == 'Housing' && strpos($topic->title, 'Home Ownership') !== false) {

            $topic_list[] = $topic;
            $total = $topic->total;

        }

    }

    /**
     * -------------------
     * Demographics Fields
     * -------------------
     * Gets the most populus age group.
     */
    $demographics_array = [];
    foreach ($demographics->body->censusTopics[0]->data as $demo) {

        $demographics_array[] = ['category' => $demo->category, 'count' => $demo->count];

    }
    usort($demographics_array, function ($a, $b) {
        return $b['count'] <=> $a['count'];
    });

    /**
     * Add Get fully owned and purchasing numbers and
     * add them together and convert to percentage.
     */
    $owning = [];
    foreach ($topic_list[0]->data as $type) {

        if ($type->category == 'Fully Owned' || $type->category == 'Purchasing') {
            $owning[] = $type->count;
        }
    }

    $owning = array_sum($owning);
    
    if ($total != 0) {
        $ownership_percentage = round(($owning / $total) * 100, 2);
    } else {
        $ownership_percentage = 0;
    }

}

/**
 * Get total properties available for sale, add to an array and count the array items
 * to get the total.
 */
$listings = new pfda_query($base.'suburbs/'.$suburb_id.'/listings?date_end=now');

if (! empty($listings->response)) {

    update_field('listings_result', json_encode($listings->body), $post_id);

    $listing_count = 0;
    foreach ($listings->body->listings as $listing) {
        $listing_count++;
    }

}

/**
 * Average Days On Market FOR SALE.
 * Get all properties sales history, add the numbers and divide by total properties.
 */
$sales = new pfda_query($base.'suburbs/'.$suburb_id.'/sales?date_end=now&date_start=today-6m');

if (! empty($sales->response)) {

    $history = [];

    foreach ($sales->body->sales as $sale) {

        $history[] = $sale->listingHistory->daysToSell;
    }

    $total_properties = count(array_filter($history));
    
    if ($total_properties != 0) {
        $average_days_on_market = round(array_sum($history) / $total_properties);
    } else {
        $average_days_on_market = 0;
    }

    update_field('sales_result', json_encode($sales->body), $post_id);
}

/**
 * Average Days On Market FOR RENT.
 * Get all properties rental history, add the numbers and divide by total properties.
 */
$rentals = new pfda_query($base.'suburbs/'.$suburb_id.'/rentals?date_end=now&date_start=today-6m');

if (! empty($rentals->response)) {

    update_field('rentals_result', json_encode($rentals->body), $post_id);

    $rental_history = [];

    if (! empty($rentals->body->listings)) {

        foreach ($rentals->body->listings as $rental) {

            $rental_history[] = $rental->listingHistory->daysOnMarket;

        }

        $total_properties_rented = count(array_filter($rental_history)); // Total properties with a "daysToSell" that isn't empty.
        $average_days_on_market_rental = round(array_sum($rental_history) / $total_properties_rented);

    } else {

        $average_days_on_market_rental = '';

    }
}

/**
 * --------------------------
 * Download and import Map Image
 * --------------------------
 **/
$map_query = '?aerial=true&area=true&boundary=true&dimensions=true&easements=true&height=700&legend=true&property_boundary=true&shading=true&title=false&width=1920&zoom=far';
$map = new pfda_query($base.'properties/'.$property_id.'/map'.$map_query);
$image_name = $property_id.'-map';
build_image($map->response, $image_name);

/**
 * --------------------------
 * Download and import images - DEPRECATED. DO NOT USE. it takes far too many API calls. V V expensive.
 * --------------------------
 *
 * We download the images to the server, only because we don't want to expose the
 * auth token through the browser. Pricefinder API restricts access to images behind
 * the OAUTH 2.0 thingie (requires token to simply view image from their server).
 *
 * This request requires up to ~20 API calls. Maybe we can reduce this?
 */
$main_image = new pfda_query($base.'properties/'.$property_id.'/images/main');

if (! empty($main_image->response)) {
    $main_image_content = new pfda_query($base.'images/'.$main_image->body->id.'?height=1920&width=1080');

    build_image($main_image_content->response, $main_image->body->id);
    pfda_set_featured_image($post_id, $main_image->body->id);
}

/**
 * ----------------------------------
 * Get all the extra property details
 * ----------------------------------
 * 26/11/2020 - Do we still need to check and set all these fields if we have the JSON response data in a field that we can use?
 *
 **/
$properties = new pfda_query($base.'properties/'.$property_id.'/extended');

while ($properties->body == 0) {
    $properties = new pfda_query($base.'properties/'.$property_id.'/extended');
}

if (! empty($properties->response)) {

    update_field('property_result', json_encode($properties->body, JSON_UNESCAPED_UNICODE), $post_id);

    $property_details = [
        'beds' => $properties->body->features->bedrooms,
        'baths' => $properties->body->features->bathrooms,
        'cars' => $properties->body->features->carParks,
        'land_size' => round($properties->body->landDetails->propertyArea),
        'property_type' => $properties->body->type,
    ];
    update_field('property_details', $property_details, $post_id);

    /**
     * Get all sales history, add to array and import into appraisal post
     */
    $rental_sales_history = [];
    if ($properties->body->saleHistory->sales) {
        foreach ($properties->body->saleHistory->sales as $sale) {
            $rental_sales_history[] = [
                'date' => $sale->saleDate->display,
                'action' => 'sold',
                'price' => $sale->price->display,
                'sale_type' => $sale->saleType,
            ];
        }
    }
    update_field('rental_sales_history', $rental_sales_history, $post_id);

}

$market_value = new pfda_query($base.'properties/'.$property_id.'/avm');

if (! empty($market_value->response)) {

    update_field('avm_result', json_encode($market_value->body), $post_id);

    if ($market_value->body->salesAVM) {

        $pricing_information = [
            'price_confidence' => $market_value->body->salesAVM->confidence,
            'low' => $market_value->body->salesAVM->priceRangeMinimum,
            'medium' => $market_value->body->salesAVM->price,
            'high' => $market_value->body->salesAVM->priceRangeMaximum,
        ];

    } else {

        $pricing_information = [
            'price_confidence' => 'No estimate available. We\'re unable to provide a medium to high confidence estimate.
            This property is most likely highly unique, high end, and/or in an area with low sales activity.',
        ];
    }
    update_field('pricing_information', $pricing_information, $post_id);
}

$suburb = new pfda_query($base.'suburbs/'.$suburb_id.'/summary');

if (! empty($suburb->response)) {

    update_field('suburb_response', json_encode($suburb->body), $post_id);

    $suburb_statistics = [
        'properties_sold' => $suburb->body->house->saleCount,
        'units_sold' => $suburb->body->unit->saleCount,
        'population' => $demographics->body->censusTopics[0]->total,
        'average_age' => $demographics_array[0]['category'],
        'properties_for_sale' => $listing_count,
        'owner_occupier' => $ownership_percentage,
    ];
    update_field('suburb_statistics', $suburb_statistics, $post_id);

    $investment_potential = [
        'average_rent' => $suburb->body->house->medianRentalPrice,
        'average_rental_yield' => $suburb->body->house->suburbRentalYield,
        'average_capital_growth' => $suburb->body->house->suburbGrowth,
        'days_on_market_rental' => $average_days_on_market_rental,
        'days_on_market_sale' => $average_days_on_market,
    ];
    update_field('investment_potential', $investment_potential, $post_id);

}

/**
 * Get all Schools, add to array, then update Schools data on appraisal post type.
 */
$schools = new pfda_query($base.'properties/'.$property_id.'/schools');

if (! empty($schools->response)) {

    $school_count = 0;
    while (($schools->body->error == true) && ($school_count < 5)) {
        $schools = new pfda_query($base.'properties/'.$property_id.'/schools');
        $school_count++;
    }

    update_field('schools_result', json_encode($schools->body), $post_id);
    update_field('data_dump', $school_count, $post_id);

    $schools_data = [];
    foreach ($schools->body->schools as $school) {

        $schools_data[] = [
            'name' => $school->name,
            'distance' => $school->location->lat.', '.$school->location->lon,
            'school_type' => $school->type,
        ];

    }
    update_field('schools', $schools_data, $post_id);
}

// If everything completed successfully, redirect to the post permalink.
wp_redirect($permalink);
exit;
