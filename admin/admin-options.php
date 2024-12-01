<?php
/**
 * The admin-specific functionality of the plugin.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

function dsp_register_settings()
{
    add_submenu_page(
        'tools.php',
        'Domain Settings',
        'Domain Settings',
        'manage_options',
        'dsp',
        'dsp_add_settings');
}

// The admin page containing the form
function dsp_add_settings()
{ ?>
<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h1>Domain Suburb Profiles Settings</h1>
    <p>Here you can set all your settings for the Domain Suburb Profiles Plugin</p>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
        <h3>Domain API Key</h3>
        <input type="text" name="domain_api_key" size="50" value="<?php echo get_option('domain_api_key'); ?>">
        <h3>Client ID</h3>
        <input type="text" name="dsp_client_id" size="50" value="<?php echo get_option('dsp_client_id'); ?>">
        <h3>Client Secret</h3>
        <input type="password" name="dsp_client_secret" size="50"
            value="<?php echo get_option('dsp_client_secret'); ?>">
        <h3>Google Maps Autocomplete API Key</h3>
        <p>You'll need a Google Maps API key with Places enabled.</p>
        <input type="password" name="dsp_google_maps_api_key" size="50"
            value="<?php echo get_option('dsp_google_maps_api_key'); ?>">
        <input type="hidden" name="action" value="process_form"> <br><br>
        <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Update" />
    </form>
</div>
<?php
}
add_action('admin_menu', 'dsp_register_settings');

function dsp_submit_key()
{
    if (isset($_POST['domain_api_key'])) {

        $api_key = sanitize_text_field($_POST['domain_api_key']);
        $api_exists = get_option('domain_api_key');

        if (! empty($api_key) && ! empty($api_exists)) {

            update_option('domain_api_key', $api_key);

        } else {

            add_option('domain_api_key', $api_key);

        }

    }
    if (isset($_POST['dsp_client_id'])) {

        $api_key = sanitize_text_field($_POST['dsp_client_id']);
        $api_exists = get_option('dsp_client_id');

        if (! empty($api_key) && ! empty($api_exists)) {

            update_option('dsp_client_id', $api_key);

        } else {

            add_option('dsp_client_id', $api_key);

        }

    }

    if (isset($_POST['dsp_client_secret'])) {

        $api_key = sanitize_text_field($_POST['dsp_client_secret']);
        $api_exists = get_option('dsp_client_secret');

        if (! empty($api_key) && ! empty($api_exists)) {

            update_option('dsp_client_secret', $api_key);

        } else {

            add_option('dsp_client_secret', $api_key);

        }

    }

    if (isset($_POST['dsp_google_maps_api_key'])) {

        $google_maps_api_key = sanitize_text_field($_POST['dsp_google_maps_api_key']);
        $google_maps_api_exists = get_option('dsp_google_maps_api_key');

        if (! empty($google_maps_api_exists) && ! empty($google_maps_api_exists)) {

            update_option('dsp_google_maps_api_key', $google_maps_api_key);

        } else {

            add_option('dsp_google_maps_api_key', $google_maps_api_key);

        }

    }

    wp_redirect($_SERVER['HTTP_REFERER']);

}

add_action('admin_post_nopriv_process_form', 'dsp_submit_key');
add_action('admin_post_process_form', 'dsp_submit_key');

/**
 * Add custom fields for state/postcode to the location taxonomy.
 * To get this data, you can do this:
 * $postcode = get_term_meta($term_id, 'postcode', true);
 * $state = get_term_meta($term_id, 'state', true);
 */
// Add fields to the "Add New" taxonomy form
add_action('location_add_form_fields', 'add_custom_fields_to_location_taxonomy');

// Add fields to the "Edit" taxonomy form
add_action('location_edit_form_fields', 'edit_custom_fields_in_location_taxonomy', 10, 2);

function add_custom_fields_to_location_taxonomy($taxonomy) {
    ?>
<div class="form-field">
    <label for="postcode">Postcode</label>
    <input type="text" name="postcode" id="postcode" value="">
    <p class="description">Enter the postcode for this location.</p>
</div>
<div class="form-field">
    <label for="state">State</label>
    <input type="text" name="state" id="state" value="">
    <p class="description">Enter the state for this location.</p>
</div>
<?php
}

function edit_custom_fields_in_location_taxonomy($term, $taxonomy) {
    // Get existing values for the fields
    $postcode = get_term_meta($term->term_id, 'postcode', true);
    $state = get_term_meta($term->term_id, 'state', true);
    ?>
<tr class="form-field">
    <th scope="row" valign="top"><label for="postcode">Postcode</label></th>
    <td>
        <input type="text" name="postcode" id="postcode" value="<?php echo esc_attr($postcode); ?>">
        <p class="description">Enter the postcode for this location.</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="state">State</label></th>
    <td>
        <input type="text" name="state" id="state" value="<?php echo esc_attr($state); ?>">
        <p class="description">Enter the state for this location.</p>
    </td>
</tr>
<?php
}

add_action('created_location', 'save_custom_fields_for_location_taxonomy');
add_action('edited_location', 'save_custom_fields_for_location_taxonomy');

function save_custom_fields_for_location_taxonomy($term_id) {
    // Save 'postcode'
    if (isset($_POST['postcode'])) {
        update_term_meta($term_id, 'postcode', sanitize_text_field($_POST['postcode']));
    }
    // Save 'state'
    if (isset($_POST['state'])) {
        update_term_meta($term_id, 'state', sanitize_text_field($_POST['state']));
    }
}

// Add custom columns to the taxonomy list table
add_filter('manage_edit-location_columns', 'add_custom_columns_to_location_taxonomy');
add_filter('manage_location_custom_column', 'populate_custom_columns_in_location_taxonomy', 10, 3);

function add_custom_columns_to_location_taxonomy($columns) {
    $columns['postcode'] = 'Postcode';
    $columns['state'] = 'State';
    return $columns;
}

function populate_custom_columns_in_location_taxonomy($content, $column_name, $term_id) {
    if ($column_name === 'postcode') {
        $content = get_term_meta($term_id, 'postcode', true);
    } elseif ($column_name === 'state') {
        $content = get_term_meta($term_id, 'state', true);
    }
    return $content;
}

// Remove the 'Description' column from the taxonomy edit screen
add_filter('manage_edit-location_columns', 'remove_description_column_from_location');

function remove_description_column_from_location($columns) {
    // Unset the 'Description' column
    if (isset($columns['description'])) {
        unset($columns['description']);
    }
    return $columns;
}