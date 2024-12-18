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
{
    $settings = [
        'title' => 'Domain Suburb Profiles Settings',
        'description' => 'Here you can set all your settings for the Domain Suburb Profiles Plugin',
        'fields' => [
            [
                'title' => 'Domain API Key',
                'description' => 'Enter your Domain API key here.',
                'name' => 'domain_api_key',
                'value' => get_option('domain_api_key'),
            ],
            [
                'title' => 'Client ID',
                'description' => 'Enter your Domain Client ID here.',
                'name' => 'dsp_client_id',
                'value' => get_option('dsp_client_id'),
            ],
            [
                'title' => 'Client Secret',
                'description' => 'Enter your Domain Client Secret here.',
                'name' => 'dsp_client_secret',
                'value' => get_option('dsp_client_secret'),
            ],
            [
                'title' => 'Google Maps Autocomplete API Key',
                'description' => 'You\'ll need a Google Maps API key with Places enabled.',
                'name' => 'dsp_google_maps_api_key',
                'value' => get_option('dsp_google_maps_api_key'),
            ],
            [
                'title' => 'Google Maps Server Side API key',
                'description' => 'This can be the same as the Autocomplete API key, but it will not work if you set access restrictions. It is HIGHLY recommended to create a separate key solely for use here. It only needs access to the Distance Matrix Endpoint. Do not restrict its access. Make sure to keep this safe, as it will allow for unrestricted acccess to the Distance Matrix API.',
                'name' => 'dsp_google_maps_server_side_api_key',
                'value' => get_option('dsp_google_maps_server_side_api_key'),
            ],
        ],
    ];
    ?>

<div class="wrap">
    <h1><?php echo $settings['title']; ?></h1>
    <p><?php echo $settings['description']; ?></p>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
        <table class="form-table" role="presentation">
            <?php foreach ($settings['fields'] as $field) { ?>
            <tr>
                <th scope="row">
                    <label for="<?php echo $field['name']; ?>"><?php echo $field['title']; ?></label>
                </th>
                <td>
                    <input type="text" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>"
                        value="<?php echo $field['value']; ?>" size="45">
                    <p class="description"><?php echo $field['description']; ?></p>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <th scope="row">Enqueue Google Maps</th>
                <td>
                    <label for="dsp_enqueue_google_maps">
                        <input name="dsp_enqueue_google_maps" type="checkbox" id="dsp_enqueue_google_maps"
                            <?php echo (get_option('dsp_enqueue_google_maps')) ? 'checked="checked"' : ''; ?>>Output
                        Google Map code on page.</label>
                </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="process_form"> <br><br>
        <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Update">
    </form>
</div>
<?php
}
add_action('admin_menu', 'dsp_register_settings');

function dsp_submit_key()
{
    $options = [
        'domain_api_key',
        'dsp_google_maps_api_key',
        'dsp_google_maps_server_side_api_key',
        'dsp_client_id',
        'dsp_client_secret',
        'dsp_enqueue_google_maps',
    ];

    foreach ($options as $option) {
        
        if ( isset($_POST[$option])) {
            $value = sanitize_text_field($_POST[$option]);
            update_option($option, $value);
            continue;
        }
        
        if ( ! $value) {
            delete_option($option);
            continue;
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

function add_custom_fields_to_location_taxonomy($taxonomy)
{
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

function edit_custom_fields_in_location_taxonomy($term, $taxonomy)
{
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

function save_custom_fields_for_location_taxonomy($term_id)
{
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

function add_custom_columns_to_location_taxonomy($columns)
{
    $columns['postcode'] = 'Postcode';
    $columns['state'] = 'State';

    return $columns;
}

function populate_custom_columns_in_location_taxonomy($content, $column_name, $term_id)
{
    if ($column_name === 'postcode') {
        $content = get_term_meta($term_id, 'postcode', true);
    } elseif ($column_name === 'state') {
        $content = get_term_meta($term_id, 'state', true);
    }

    return $content;
}

// Remove the 'Description' column from the taxonomy edit screen
add_filter('manage_edit-location_columns', 'remove_description_column_from_location');

function remove_description_column_from_location($columns)
{
    // Unset the 'Description' column
    if (isset($columns['description'])) {
        unset($columns['description']);
    }

    return $columns;
}

add_action('admin_menu', 'register_api_usage_menu');

function register_api_usage_menu() {
    add_menu_page(
        'API Usage Stats', 
        'API Usage', 
        'manage_options', 
        'api-usage-stats', 
        'display_api_usage_stats', 
        'dashicons-chart-line', 
        26
    );
}

function display_api_usage_stats() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'google_distance_api_usage';
    $usage = $wpdb->get_row("SELECT total_calls, successful_responses, last_updated FROM $table_name");

    if ($usage) {
        echo '<h1>Google Distance Matrix API Usage</h1>';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead>';
        echo '<tr><th>Metric</th><th>Value</th></tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr><td>Total Calls</td><td>' . esc_html($usage->total_calls) . '</td></tr>';
        echo '<tr><td>Successful Responses</td><td>' . esc_html($usage->successful_responses) . '</td></tr>';
        echo '<tr><td>Last Updated</td><td>' . esc_html($usage->last_updated) . '</td></tr>';
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No data available yet.</p>';
    }
}
