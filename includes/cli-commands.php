<?php

/**
 * Functions for Domain API WP CLI.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('update-locations', 'update_locations_command', [
        'shortdesc' => 'Updates location terms with postcodes and states using a priority state.',
        'synopsis' => [
            [
                'type' => 'assoc',
                'name' => 'state',
                'description' => 'Priority state name or abbreviation (e.g., "SA" or "South Australia").',
                'optional' => false,
            ],
        ],
    ]);
    WP_CLI::add_command('create-suburb-profiles', 'create_suburb_profiles_command', [
        'shortdesc' => 'Creates suburb-profiles posts from taxonomy terms.',
    ]);
    WP_CLI::add_command('delete-suburb-profiles', 'delete_suburb_profiles_command', [
        'shortdesc' => 'Deletes all suburb-profile posts.',
        'synopsis' => [
            [
                'type' => 'flag',
                'name' => 'force',
                'description' => 'Skip confirmation prompt.',
                'optional' => true,
            ],
        ],
    ]);
}

function update_locations_command($args, $assoc_args)
{
    $priority_state_input = isset($assoc_args['state']) ? $assoc_args['state'] : null;

    if (empty($priority_state_input)) {
        WP_CLI::error('Please provide a priority state using the --state parameter.');
    }

    // Convert state input to full name and abbreviation
    $priority_state_full = dsp_get_full_state_name($priority_state_input);
    $priority_state_abbr = dsp_get_state_abbreviation($priority_state_input);

    if (empty($priority_state_full) || empty($priority_state_abbr)) {
        WP_CLI::error('Invalid priority state provided. Please use a valid Australian state name or abbreviation.');
    }

    // Fetch all terms in the 'location' taxonomy
    $terms = get_terms([
        'taxonomy' => 'location',
        'hide_empty' => false,
    ]);

    if (is_wp_error($terms)) {
        WP_CLI::error('Error fetching terms: '.$terms->get_error_message());
    }

    if (empty($terms)) {
        WP_CLI::error('No terms found in the "location" taxonomy.');
    }

    $total_terms = count($terms);
    $processed_terms = 0;
    $skipped_terms = 0;

    // Create the progress bar
    $progress_bar = \WP_CLI\Utils\make_progress_bar('Updating location terms', $total_terms);

    // Collect log messages to display after the progress bar
    $log_messages = [];

    foreach ($terms as $term) {
        $term_name = $term->name;

        // Check if term already has both 'postcode' and 'state' meta
        $existing_postcode = get_term_meta($term->term_id, 'postcode', true);
        $existing_state = get_term_meta($term->term_id, 'state', true);

        if (! empty($existing_postcode) && ! empty($existing_state)) {
            // Skip this term
            $skipped_terms++;
            $progress_bar->tick();

            continue;
        }

        // Build the query string using the full state name
        $query = $term_name.', '.$priority_state_full.', Australia';
        $url = 'https://nominatim.openstreetmap.org/search?'.http_build_query([
            'q' => $query,
            'format' => 'json',
            'limit' => 1,
            'addressdetails' => 1,
            'extratags' => 1,
        ]);

        // Fetch the response from the API
        $response = wp_remote_get($url);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            $log_messages[] = "Error fetching data for term: {$term_name}";
            $progress_bar->tick();

            continue;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (! empty($data) && isset($data[0]['address'])) {
            $address = $data[0]['address'];

            // Extract postcode and state
            $postcode = isset($address['postcode']) ? $address['postcode'] : '';
            $state = isset($address['state']) ? $address['state'] : '';

            $updated_postcode = false;
            $updated_state = false;

            // Update the 'postcode' meta field
            if (! empty($postcode)) {
                update_term_meta($term->term_id, 'postcode', $postcode);
                $updated_postcode = true;
            }

            // Compare the states directly
            if (! empty($state) && strtolower($state) === strtolower($priority_state_full)) {
                update_term_meta($term->term_id, 'state', $priority_state_abbr);
                $updated_state = true;
            }

            // Build the log message
            $log_message = "Processed term: {$term_name}\n";
            $log_message .= 'Postcode: '.($postcode ?: 'N/A')."\n";
            $log_message .= 'State: '.($state ?: 'N/A')."\n";
            $log_message .= 'Postcode Updated: '.($updated_postcode ? 'Yes' : 'No')."\n";
            $log_message .= 'State Updated: '.($updated_state ? 'Yes' : 'No')."\n";
            $log_message .= str_repeat('-', 40);
            $log_messages[] = $log_message;
        } else {
            $log_messages[] = "No data found for term: {$term_name}";
        }

        $processed_terms++;
        $progress_bar->tick();

        // Sleep for a second to respect API rate limits
        sleep(1);
    }

    $progress_bar->finish();

    // Output the log messages after the progress bar
    if (! empty($log_messages)) {
        WP_CLI::log("\n".implode("\n", $log_messages));
    }

    WP_CLI::success("All location terms updated successfully! Processed: {$processed_terms}, Skipped: {$skipped_terms}");
}

function create_suburb_profiles_command($args, $assoc_args)
{
    // Fetch all terms in the 'location' taxonomy
    $terms = get_terms([
        'taxonomy' => 'location',
        'hide_empty' => false,
    ]);

    if (is_wp_error($terms)) {
        WP_CLI::error('Error fetching terms: '.$terms->get_error_message());
    }

    if (empty($terms)) {
        WP_CLI::error('No terms found in the "location" taxonomy.');
    }

    $total_terms = count($terms);
    $processed_terms = 0;
    $skipped_terms = 0;

    // Create the progress bar
    $progress_bar = \WP_CLI\Utils\make_progress_bar('Creating suburb profiles', $total_terms);

    $log_messages = [];

    foreach ($terms as $term) {
        $term_name = $term->name;

        // Check if term has 'postcode' and 'state' meta.
        $postcode = get_term_meta($term->term_id, 'postcode', true);
        $state = get_term_meta($term->term_id, 'state', true);

        if (empty($postcode) || empty($state)) {
            // Skip this term
            $skipped_terms++;
            $progress_bar->tick();

            continue;
        }

        // Temp fix: All states are SA for now. Update $post_title to include state.
        $state = 'SA';

        // Create post title "{Taxonomy Term} {State} {Postcode}"
        $post_title = "{$term_name} {$state} {$postcode}";

        $args = [
            'post_type' => 'suburb-profile',
            'title' => $post_title,
            'post_status' => 'any',
            'posts_per_page' => 1,
            'fields' => 'ids',
        ];

        $existing_posts = new WP_Query($args);

        if ($existing_posts->have_posts()) {
            $log_messages[] = "Post already exists for term: {$term_name}. Skipping.";
            $skipped_terms++;
            $progress_bar->tick();

            continue;
        }

        // Prepare post data
        $post_data = [
            'post_title' => $post_title,
            'post_status' => 'publish',
            'post_type' => 'suburb-profile',
            'meta_input' => [
                'rc_state' => $state,
                'rc_suburb' => $term_name,
                'rc_postcode' => $postcode,
            ],
        ];

        // Insert the post
        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            $log_messages[] = "Error creating post for term: {$term_name}. Error: ".$post_id->get_error_message();
            $progress_bar->tick();

            continue;
        }

        $fetch = new Boundary_Fetcher(ucwords(strtolower($term_name)), dsp_get_full_state_name($state), 'Australia', $post_id);

        update_post_meta($post_id, 'rc_lat', $fetch->getLat());
        update_post_meta($post_id, 'rc_long', $fetch->getLong());

        // Assign the taxonomy term to the 'suburb' taxonomy for the post
        wp_set_object_terms($post_id, $term->term_id, 'location');

        $log_messages[] = "Created suburb profile for term: {$term_name}. Post ID: {$post_id}";

        $processed_terms++;
        $progress_bar->tick();
    }

    $progress_bar->finish();

    // Output log messages
    if (! empty($log_messages)) {
        WP_CLI::log("\n".implode("\n", $log_messages));
    }

    WP_CLI::success("Suburb profiles created successfully! Processed: {$processed_terms}, Skipped: {$skipped_terms}");
}

function delete_suburb_profiles_command($args, $assoc_args)
{
    // Check if the --force flag is set
    $force = isset($assoc_args['force']) && $assoc_args['force'];

    // Fetch all suburb-profile posts
    $query = new WP_Query([
        'post_type' => 'suburb-profile',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]);

    $post_ids = $query->posts;

    if (empty($post_ids)) {
        WP_CLI::success('No suburb profiles found to delete.');

        return;
    }

    $total_posts = count($post_ids);

    // Confirmation prompt if --force is not used
    if (! $force) {
        WP_CLI::confirm("Are you sure you want to delete {$total_posts} suburb-profile posts?");
    }

    // Create the progress bar
    $progress_bar = \WP_CLI\Utils\make_progress_bar('Deleting suburb profiles', $total_posts);

    foreach ($post_ids as $post_id) {
        // Delete the post
        wp_delete_post($post_id, true); // true for force delete (bypass trash)

        $progress_bar->tick();
    }

    $progress_bar->finish();

    WP_CLI::success("Deleted {$total_posts} suburb-profile posts.");
}