<?php

// Check if the address is set in the POST or GET request
$suburb = $_POST['suburb'] ?? $_GET['suburb'] ?? null;
$state = $_POST['state'] ?? $_GET['state'] ?? null;
$postcode = $_POST['postcode'] ?? $_GET['postcode'] ?? null;

// If the address is not set, exit the script
if (!$suburb || !$state || !$postcode) {
    echo 'No Address found. Exiting.';
    return;
}

// Dynamically determine the path to wp-load.php
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp/wp-load.php';

rc_ida_domain_suburb_profile($suburb, $state, $postcode);