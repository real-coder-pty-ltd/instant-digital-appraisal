<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://realcoder.com.au
 * @since      1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Matthew Neal <matt.neal@realcoder.com.au>
 */
class Pricefinder_Da_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param  string  $plugin_name  The name of this plugin.
     * @param  string  $version  The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pricefinder_Da_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pricefinder_Da_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__).'css/pricefinder-da-admin.css', [], $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pricefinder_Da_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pricefinder_Da_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__).'js/pricefinder-da-admin.js', ['jquery'], $this->version, false);

    }
}

// Creates a subpage under the Tools section
add_action('admin_menu', 'rc_ida_register_settings');
function rc_ida_register_settings()
{
    add_submenu_page(
        'tools.php',
        'RC IDA Settings',
        'RC IDA Settings',
        'manage_options',
        'pricefinder-da',
        'rc_ida_add_settings');
}

// The admin page containing the form
function rc_ida_add_settings()
{ ?>
<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h1>Instant Digital Appraisal Settings</h1>
    <p>Here you can set all your settings for the Instant Digital Appraisal Plugin</p>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
        <h3>Domain API Key</h3>
        <input type="text" name="domain_api_key" size="50" value="<?php echo get_option('domain_api_key'); ?>">
        <h3>Client ID</h3>
        <input type="text" name="rc_ida_client_id" size="50" value="<?php echo get_option('rc_ida_client_id'); ?>">
        <h3>Client Secret</h3>
        <input type="password" name="rc_ida_client_secret" size="50"
            value="<?php echo get_option('rc_ida_client_secret'); ?>">
        <h3>Google Maps Autocomplete API Key</h3>
        <p>You'll need a Google Maps API key with Places enabled.</p>
        <input type="password" name="rc_ida_google_maps_api_key" size="50"
            value="<?php echo get_option('rc_ida_google_maps_api_key'); ?>">
        <h3>Appraisal Page URL Slug</h3>
        <p>Enter the URL slug for the page you want to use for the appraisal form.</p>
        <input type="text" name="rc_ida_appraisal_page_url_slug" size="50"
            value="<?php echo get_option('rc_ida_appraisal_page_url_slug'); ?>">
        <h3>Thank You Page URL Slug</h3>
        <p>Enter the URL slug for the page you want to use for the thank you page.</p>
        <input type="text" name="rc_ida_thank_you_page_url_slug" size="50"
            value="<?php echo get_option('rc_ida_thank_you_page_url_slug'); ?>">
        <input type="hidden" name="action" value="process_form"> <br><br>
        <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Update" />
    </form>
</div>


<div class="wrap">
    <h1>Update Location Postcodes and States</h1>
    <p>You can use this tool to update the postcodes and states of the location terms. This is useful if you have
        imported location terms and need to set the postcodes and states for each term. This assumes you have a
        custom taxonomy for your listing suburbs called "location". This is the term name for Easy Property Listings
        based websites. This is currently not editable, but will be in future versions.</p>
    <form id="pricefinder-form">
        <label for="priority_state">Select the priority state:</label>
        <select name="priority_state" id="priority_state" required>
            <option value="South Australia">South Australia</option>
            <option value="New South Wales">New South Wales</option>
            <option value="Queensland">Queensland</option>
            <option value="Victoria">Victoria</option>
            <option value="Western Australia">Western Australia</option>
            <option value="Tasmania">Tasmania</option>
            <option value="Northern Territory">Northern Territory</option>
            <option value="Australian Capital Territory">Australian Capital Territory</option>
        </select>
        <br><br>
        <button type="button" id="start-update" class="button button-primary">Run Update</button>
        <button type="button" id="stop-update" class="button button-secondary" style="display: none;">Stop</button>
    </form>
    <br>
    <div id="progress-container" style="display: none;">
        <progress id="progress-bar" value="0" max="100" style="width: 100%;"></progress>
        <p id="progress-text">Progress: 0%</p>
    </div>
    <div id="result-log"
        style="margin-top: 20px; max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    let isProcessing = false; // Flag to check if processing is ongoing
    let shouldStop = false; // Flag to indicate if processing should stop

    $('#start-update').on('click', function() {
        if (isProcessing) return; // Prevent multiple clicks

        const priorityState = $('#priority_state').val();
        $('#progress-container').show();
        $('#result-log').html('');
        let progress = 0;
        isProcessing = true;
        shouldStop = false;
        $('#start-update').prop('disabled', true);
        $('#stop-update').show();

        // Make AJAX requests in chunks
        function processChunk(offset) {
            if (shouldStop) {
                isProcessing = false;
                $('#start-update').prop('disabled', false);
                $('#stop-update').hide();
                $('#result-log').append(
                    '<p style="color: orange;"><strong>Update stopped by user.</strong></p>');
                return;
            }

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'update_location_postcodes_and_states',
                    offset: offset,
                    priority_state: priorityState,
                },
                success: function(response) {
                    if (response.success) {
                        // Update progress
                        progress = response.data.percentage;
                        $('#progress-bar').val(progress);
                        $('#progress-text').text('Progress: ' + Math.round(progress) + '%');

                        // Append log messages
                        $('#result-log').append(response.data.message);

                        // Scroll to bottom
                        $('#result-log').scrollTop($('#result-log')[0].scrollHeight);

                        // Continue if not finished
                        if (!response.data.finished) {
                            processChunk(response.data.next_offset);
                        } else {
                            isProcessing = false;
                            $('#start-update').prop('disabled', false);
                            $('#stop-update').hide();
                            $('#result-log').append(
                                '<p><strong>Update completed!</strong></p>');
                        }
                    } else {
                        isProcessing = false;
                        $('#start-update').prop('disabled', false);
                        $('#stop-update').hide();
                        $('#result-log').append('<p style="color: red;">Error: ' + response
                            .data + '</p>');
                    }
                },
                error: function() {
                    isProcessing = false;
                    $('#start-update').prop('disabled', false);
                    $('#stop-update').hide();
                    $('#result-log').append(
                        '<p style="color: red;">An unexpected error occurred.</p>');
                },
            });
        }

        // Start processing the first chunk
        processChunk(0);
    });

    $('#stop-update').on('click', function() {
        shouldStop = true;
    });
});
</script>
<?php
}

function rc_ida_submit_key()
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
    if (isset($_POST['rc_ida_client_id'])) {

        $api_key = sanitize_text_field($_POST['rc_ida_client_id']);
        $api_exists = get_option('rc_ida_client_id');

        if (! empty($api_key) && ! empty($api_exists)) {

            update_option('rc_ida_client_id', $api_key);

        } else {

            add_option('rc_ida_client_id', $api_key);

        }

    }

    if (isset($_POST['rc_ida_client_secret'])) {

        $api_key = sanitize_text_field($_POST['rc_ida_client_secret']);
        $api_exists = get_option('rc_ida_client_secret');

        if (! empty($api_key) && ! empty($api_exists)) {

            update_option('rc_ida_client_secret', $api_key);

        } else {

            add_option('rc_ida_client_secret', $api_key);

        }

    }

    if (isset($_POST['rc_ida_google_maps_api_key'])) {

        $google_maps_api_key = sanitize_text_field($_POST['rc_ida_google_maps_api_key']);
        $google_maps_api_exists = get_option('rc_ida_google_maps_api_key');

        if (! empty($google_maps_api_exists) && ! empty($google_maps_api_exists)) {

            update_option('rc_ida_google_maps_api_key', $google_maps_api_key);

        } else {

            add_option('rc_ida_google_maps_api_key', $google_maps_api_key);

        }

    }

    if (isset($_POST['rc_ida_appraisal_page_url_slug'])) {

        $rc_ida_appraisal_page_url_slug = sanitize_text_field($_POST['rc_ida_appraisal_page_url_slug']);
        $rc_ida_appraisal_page_url_slug_exists = get_option('rc_ida_appraisal_page_url_slug');

        if (! empty($rc_ida_appraisal_page_url_slug_exists) && ! empty($rc_ida_appraisal_page_url_slug_exists)) {

            update_option('rc_ida_appraisal_page_url_slugy', $rc_ida_appraisal_page_url_slug);

        } else {

            add_option('rc_ida_appraisal_page_url_slug', $rc_ida_appraisal_page_url_slug);

        }

    }

    if (isset($_POST['rc_ida_thank_you_page_url_slug'])) {

        $rc_ida_thank_you_page_url_slug = sanitize_text_field($_POST['rc_ida_thank_you_page_url_slug']);
        $rc_ida_thank_you_page_url_slug_exists = get_option('rc_ida_thank_you_page_url_slug');

        if (! empty($rc_ida_thank_you_page_url_slug_exists) && ! empty($rc_ida_thank_you_page_url_slug_exists)) {

            update_option('rc_ida_thank_you_page_url_slugy', $rc_ida_thank_you_page_url_slug);

        } else {

            add_option('rc_ida_thank_you_page_url_slug', $rc_ida_thank_you_page_url_slug);

        }

    }

    wp_redirect($_SERVER['HTTP_REFERER']);

}

add_action('admin_post_nopriv_process_form', 'rc_ida_submit_key');
add_action('admin_post_process_form', 'rc_ida_submit_key');

// ACF Field Group for Suburb Profile
add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_67354825610bf',
        'title' => 'RC Suburb Profile',
        'fields' => [
            [
                'key' => 'field_673ac62cad807',
                'label' => 'General',
                'name' => '',
                'aria-label' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'placement' => 'top',
                'endpoint' => 0,
                'selected' => 0,
            ],
            [
                'key' => 'field_673ac6b4d993d',
                'label' => 'General',
                'name' => 'general',
                'aria-label' => '',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_673ac669ad80b',
                        'label' => 'Address',
                        'name' => 'address',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'maxlength' => '',
                        'allow_in_bindings' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ],
                    [
                        'key' => 'field_673ac649ad808',
                        'label' => 'Suburb',
                        'name' => 'suburb',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'maxlength' => '',
                        'allow_in_bindings' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ],
                    [
                        'key' => 'field_673ac655ad809',
                        'label' => 'State',
                        'name' => 'state',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'maxlength' => '',
                        'allow_in_bindings' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ],
                    [
                        'key' => 'field_673ac65cad80a',
                        'label' => 'Postcode',
                        'name' => 'postcode',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'maxlength' => '',
                        'allow_in_bindings' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ],
                    [
                        'key' => 'field_673ac6d2d993e',
                        'label' => 'Created Date',
                        'name' => 'created_date',
                        'aria-label' => '',
                        'type' => 'date_time_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ],
                        'display_format' => 'Y-m-d H:i:s',
                        'return_format' => 'Y-m-d H:i:s',
                        'first_day' => 1,
                        'allow_in_bindings' => 0,
                    ],
                    [
                        'key' => 'field_673ac6f6d993f',
                        'label' => 'Modified Date',
                        'name' => 'modified_date',
                        'aria-label' => '',
                        'type' => 'date_time_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ],
                        'display_format' => 'Y-m-d H:i:s',
                        'return_format' => 'Y-m-d H:i:s',
                        'first_day' => 1,
                        'allow_in_bindings' => 0,
                    ],
                ],
            ],
            [
                'key' => 'field_6735482570707',
                'label' => 'Demographics',
                'name' => '',
                'aria-label' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'placement' => 'top',
                'endpoint' => 0,
                'selected' => 0,
            ],
            [
                'key' => 'field_67368db0efd1a',
                'label' => 'Demographics',
                'name' => 'demographics',
                'aria-label' => '',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_67368a105bc04',
                        'label' => 'Age Group of Population',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_67368eb6bd548',
                        'label' => 'Age Group of Population',
                        'name' => 'agegroupofpopulation',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_67368e73bd546',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_67368e8abd547',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_67368edbbd549',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => 'table',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_67368ee9bd54a',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368edbbd549',
                                    ],
                                    [
                                        'key' => 'field_67368f14bd54b',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368edbbd549',
                                    ],
                                    [
                                        'key' => 'field_67368f1bbd54c',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368edbbd549',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368ad15bc05',
                        'label' => 'Country of Birth',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_67368fbf9a500',
                        'label' => 'Country of Birth',
                        'name' => 'countryofbirth',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_67368fbf9a501',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_67368fbf9a502',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_67368fbf9a503',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_67368fbf9a504',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368fbf9a503',
                                    ],
                                    [
                                        'key' => 'field_67368fbf9a505',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368fbf9a503',
                                    ],
                                    [
                                        'key' => 'field_67368fbf9a506',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368fbf9a503',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368ae05bc06',
                        'label' => 'Nature of Occupancy',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_67368fe39a507',
                        'label' => 'Nature of Occupancy',
                        'name' => 'natureofoccupancy',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_67368fe39a508',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_67368fe39a509',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_67368fe39a50a',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_67368fe39a50b',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368fe39a50a',
                                    ],
                                    [
                                        'key' => 'field_67368fe39a50c',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368fe39a50a',
                                    ],
                                    [
                                        'key' => 'field_67368fe39a50d',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_67368fe39a50a',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368aef5bc07',
                        'label' => 'Occupation',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673690129a50e',
                        'label' => 'Occupation',
                        'name' => 'occupation',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673690129a50f',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690129a510',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690129a511',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673690129a512',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690129a511',
                                    ],
                                    [
                                        'key' => 'field_673690129a513',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690129a511',
                                    ],
                                    [
                                        'key' => 'field_673690129a514',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690129a511',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368afc5bc08',
                        'label' => 'Geographical Population',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673690369a515',
                        'label' => 'Geographical Population',
                        'name' => 'geographicalpopulation',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673690369a516',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690369a517',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690369a518',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673690369a519',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690369a518',
                                    ],
                                    [
                                        'key' => 'field_673690369a51a',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690369a518',
                                    ],
                                    [
                                        'key' => 'field_673690369a51b',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690369a518',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b0e5bc09',
                        'label' => 'Dwelling Structure',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673690569a51c',
                        'label' => 'Dwelling Structure',
                        'name' => 'dwellingstructure',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673690579a51d',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690579a51e',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690579a51f',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673690579a520',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690579a51f',
                                    ],
                                    [
                                        'key' => 'field_673690579a521',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690579a51f',
                                    ],
                                    [
                                        'key' => 'field_673690579a522',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690579a51f',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b1e5bc0a',
                        'label' => 'Education Attendance',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_6736906e9a523',
                        'label' => 'Education Attendance',
                        'name' => 'educationattendance',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_6736906e9a524',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_6736906e9a525',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_6736906e9a526',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_6736906e9a527',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736906e9a526',
                                    ],
                                    [
                                        'key' => 'field_6736906e9a528',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736906e9a526',
                                    ],
                                    [
                                        'key' => 'field_6736906e9a529',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736906e9a526',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b305bc0b',
                        'label' => 'Housing Loan Repayment',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673690969a52b',
                        'label' => 'Housing Loan Repayment',
                        'name' => 'housingloanrepayment',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673690969a52c',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690969a52d',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690969a52e',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673690969a52f',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690969a52e',
                                    ],
                                    [
                                        'key' => 'field_673690969a530',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690969a52e',
                                    ],
                                    [
                                        'key' => 'field_673690969a531',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690969a52e',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b3f5bc0c',
                        'label' => 'Marital Status',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673690b59a532',
                        'label' => 'Marital Status',
                        'name' => 'maritalstatus',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673690b69a533',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690b69a534',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690b69a535',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673690b69a536',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690b69a535',
                                    ],
                                    [
                                        'key' => 'field_673690b69a537',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690b69a535',
                                    ],
                                    [
                                        'key' => 'field_673690b69a538',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690b69a535',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b485bc0d',
                        'label' => 'Religion',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673690d09a539',
                        'label' => 'Religion',
                        'name' => 'religion',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673690d09a53a',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690d09a53b',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690d09a53c',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673690d09a53d',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690d09a53c',
                                    ],
                                    [
                                        'key' => 'field_673690d09a53e',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690d09a53c',
                                    ],
                                    [
                                        'key' => 'field_673690d09a53f',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690d09a53c',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b505bc0e',
                        'label' => 'Transport To Work',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673690f29a540',
                        'label' => 'Transport To Work',
                        'name' => 'transporttowork',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673690f29a541',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690f29a542',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673690f29a543',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673690f29a544',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690f29a543',
                                    ],
                                    [
                                        'key' => 'field_673690f29a545',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690f29a543',
                                    ],
                                    [
                                        'key' => 'field_673690f29a546',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673690f29a543',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b5e5bc0f',
                        'label' => 'Family Composition',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673691099a547',
                        'label' => 'Family Composition',
                        'name' => 'familycomposition',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673691099a548',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673691099a549',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673691099a54a',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673691099a54b',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673691099a54a',
                                    ],
                                    [
                                        'key' => 'field_673691099a54c',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673691099a54a',
                                    ],
                                    [
                                        'key' => 'field_673691099a54d',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673691099a54a',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b6d5bc10',
                        'label' => 'Household Income',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_6736913b9a555',
                        'label' => 'Household Income',
                        'name' => 'householdincome',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_6736913b9a556',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_6736913b9a557',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_6736913b9a558',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_6736913b9a559',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736913b9a558',
                                    ],
                                    [
                                        'key' => 'field_6736913b9a55a',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736913b9a558',
                                    ],
                                    [
                                        'key' => 'field_6736913b9a55b',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736913b9a558',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b795bc11',
                        'label' => 'Rent',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_6736914d9a55c',
                        'label' => 'Rent',
                        'name' => 'rent',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_6736914d9a55d',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_6736914d9a55e',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_6736914d9a55f',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_6736914d9a560',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736914d9a55f',
                                    ],
                                    [
                                        'key' => 'field_6736914d9a561',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736914d9a55f',
                                    ],
                                    [
                                        'key' => 'field_6736914d9a562',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_6736914d9a55f',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_67368b845bc12',
                        'label' => 'Labour Force Status',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'placement' => 'left',
                        'endpoint' => 0,
                        'selected' => 0,
                    ],
                    [
                        'key' => 'field_673691609a563',
                        'label' => 'Labour Force Status',
                        'name' => 'labourforcestatus',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_673691609a564',
                                'label' => 'Total',
                                'name' => 'total',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673691609a565',
                                'label' => 'Year',
                                'name' => 'year',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ],
                            [
                                'key' => 'field_673691609a566',
                                'label' => 'Items',
                                'name' => 'items',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => '',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add Row',
                                'rows_per_page' => 20,
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673691609a567',
                                        'label' => 'Label',
                                        'name' => 'label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673691609a566',
                                    ],
                                    [
                                        'key' => 'field_673691609a568',
                                        'label' => 'Value',
                                        'name' => 'value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673691609a566',
                                    ],
                                    [
                                        'key' => 'field_673691609a569',
                                        'label' => 'Composition',
                                        'name' => 'composition',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'allow_in_bindings' => 0,
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_673691609a566',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_673689aa5bc02',
                'label' => 'Suburb Performance Statistics',
                'name' => '',
                'aria-label' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'placement' => 'top',
                'endpoint' => 0,
                'selected' => 0,
            ],
            [
                'key' => 'field_673698385b352',
                'label' => 'Suburb Performance Statistics',
                'name' => 'suburb_performance_statistics',
                'aria-label' => '',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_673c0cf041efe',
                        'label' => 'Items',
                        'name' => 'items',
                        'aria-label' => '',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layout' => 'block',
                        'pagination' => 0,
                        'min' => 0,
                        'max' => 0,
                        'collapsed' => '',
                        'button_label' => 'Add Row',
                        'rows_per_page' => 20,
                        'sub_fields' => [
                            [
                                'key' => 'field_673c0d1a41eff',
                                'label' => 'Label',
                                'name' => 'label',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'parent_repeater' => 'field_673c0cf041efe',
                            ],
                            [
                                'key' => 'field_673c0d7641f00',
                                'label' => 'Bedrooms',
                                'name' => 'bedrooms',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'parent_repeater' => 'field_673c0cf041efe',
                            ],
                            [
                                'key' => 'field_673698f55b356',
                                'label' => 'Property Category',
                                'name' => 'propertycategory',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 0,
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'parent_repeater' => 'field_673c0cf041efe',
                            ],
                            [
                                'key' => 'field_6736990b5b357',
                                'label' => 'Series',
                                'name' => 'series',
                                'aria-label' => '',
                                'type' => 'group',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'layout' => 'block',
                                'parent_repeater' => 'field_673c0cf041efe',
                                'sub_fields' => [
                                    [
                                        'key' => 'field_673699455b358',
                                        'label' => 'Series Info',
                                        'name' => 'seriesinfo',
                                        'aria-label' => '',
                                        'type' => 'repeater',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => [
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ],
                                        'layout' => 'block',
                                        'pagination' => 0,
                                        'min' => 0,
                                        'max' => 0,
                                        'collapsed' => '',
                                        'button_label' => 'Add Row',
                                        'rows_per_page' => 20,
                                        'sub_fields' => [
                                            [
                                                'key' => 'field_673699525b359',
                                                'label' => 'Year',
                                                'name' => 'year',
                                                'aria-label' => '',
                                                'type' => 'text',
                                                'instructions' => '',
                                                'required' => 0,
                                                'conditional_logic' => 0,
                                                'wrapper' => [
                                                    'width' => '50',
                                                    'class' => '',
                                                    'id' => '',
                                                ],
                                                'default_value' => '',
                                                'maxlength' => '',
                                                'allow_in_bindings' => 0,
                                                'placeholder' => '',
                                                'prepend' => '',
                                                'append' => '',
                                                'parent_repeater' => 'field_673699455b358',
                                            ],
                                            [
                                                'key' => 'field_673699675b35a',
                                                'label' => 'Month',
                                                'name' => 'month',
                                                'aria-label' => '',
                                                'type' => 'text',
                                                'instructions' => '',
                                                'required' => 0,
                                                'conditional_logic' => 0,
                                                'wrapper' => [
                                                    'width' => '50',
                                                    'class' => '',
                                                    'id' => '',
                                                ],
                                                'default_value' => '',
                                                'maxlength' => '',
                                                'allow_in_bindings' => 0,
                                                'placeholder' => '',
                                                'prepend' => '',
                                                'append' => '',
                                                'parent_repeater' => 'field_673699455b358',
                                            ],
                                            [
                                                'key' => 'field_673699705b35b',
                                                'label' => 'Values',
                                                'name' => 'values',
                                                'aria-label' => '',
                                                'type' => 'group',
                                                'instructions' => '',
                                                'required' => 0,
                                                'conditional_logic' => 0,
                                                'wrapper' => [
                                                    'width' => '',
                                                    'class' => '',
                                                    'id' => '',
                                                ],
                                                'layout' => 'block',
                                                'parent_repeater' => 'field_673699455b358',
                                                'sub_fields' => [
                                                    [
                                                        'key' => 'field_673699ca5b35c',
                                                        'label' => 'Median Sold Price',
                                                        'name' => 'medianSoldPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a015b35d',
                                                        'label' => 'Number Sold',
                                                        'name' => 'numberSold',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a1b5b35e',
                                                        'label' => 'Highest Sold Price',
                                                        'name' => 'highestSoldPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a1f5b35f',
                                                        'label' => 'Lowest Sold Price',
                                                        'name' => 'lowestSoldPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a2d5b360',
                                                        'label' => '5th Percentile Sold Price',
                                                        'name' => '5thPercentileSoldPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a3c5b361',
                                                        'label' => '25th Percentile Sold Price',
                                                        'name' => '25thPercentileSoldPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a415b362',
                                                        'label' => '75th Percentile Sold Price',
                                                        'name' => '75thPercentileSoldPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a485b363',
                                                        'label' => '95th Percentile Sold Price',
                                                        'name' => '95thPercentileSoldPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a545b364',
                                                        'label' => 'Median Sale Listing Price',
                                                        'name' => 'medianSaleListingPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a5c5b365',
                                                        'label' => 'Number Sale Listing',
                                                        'name' => 'numberSaleListing',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a645b366',
                                                        'label' => 'Highest Sale Listing Price',
                                                        'name' => 'highestSaleListingPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a6b5b367',
                                                        'label' => 'Lowest Sale Listing Price',
                                                        'name' => 'lowestSaleListingPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a725b368',
                                                        'label' => 'Auction Number Auctioned',
                                                        'name' => 'auctionNumberAuctioned',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a7f5b369',
                                                        'label' => 'Auction Number Sold',
                                                        'name' => 'auction_numberSold',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a835b36a',
                                                        'label' => 'Auction Number Withdrawn',
                                                        'name' => 'auctionNumberWithdrawn',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a8a5b36b',
                                                        'label' => 'Days On Market',
                                                        'name' => 'daysOnMarket',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a925b36c',
                                                        'label' => 'Discount Percentage',
                                                        'name' => 'discountPercentage',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369a9b5b36d',
                                                        'label' => 'Median Rent Listing Price',
                                                        'name' => 'medianRentListingPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369aa95b36e',
                                                        'label' => 'Number Rent Listing',
                                                        'name' => 'numberRentListing',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369ab25b36f',
                                                        'label' => 'Highest Rent Listing Price',
                                                        'name' => 'highestRentListingPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                    [
                                                        'key' => 'field_67369abb5b370',
                                                        'label' => 'Lowest Rent Listing Price',
                                                        'name' => 'lowestRentListingPrice',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 0,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => [
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ],
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'allow_in_bindings' => 0,
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'key' => 'field_673d5669bffef',
                                'label' => 'Series Data',
                                'name' => 'series_data',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 1,
                                'rows' => '',
                                'placeholder' => '',
                                'new_lines' => '',
                                'parent_repeater' => 'field_673c0cf041efe',
                            ],
                            [
                                'key' => 'field_674422e24e0d1',
                                'label' => 'Series Data (Quarters)',
                                'name' => 'series_data_quarters',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                                'default_value' => '',
                                'maxlength' => '',
                                'allow_in_bindings' => 1,
                                'rows' => '',
                                'placeholder' => '',
                                'new_lines' => '',
                                'parent_repeater' => 'field_673c0cf041efe',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'suburb-profile',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => [
            0 => 'discussion',
            1 => 'comments',
        ],
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ]);
});

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