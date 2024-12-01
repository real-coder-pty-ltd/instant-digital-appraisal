<?php
/**
 * Hooks & helper functions for Domain API.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

function dsp_domain_get_suburb_id($suburb, $state, $postcode) {
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
        'NSW' => 'New South Wales',
        'QLD' => 'Queensland',
        'SA' => 'South Australia',
        'TAS' => 'Tasmania',
        'VIC' => 'Victoria',
        'WA' => 'Western Australia',
        'ACT' => 'Australian Capital Territory',
        'NT' => 'Northern Territory',
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