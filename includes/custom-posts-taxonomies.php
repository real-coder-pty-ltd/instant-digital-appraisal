<?php
/**
 * Custom Post Types and Taxonomies.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type for Suburb Profile
function dsp_post_type_suburb_profile()
{
    $labels = [
        'name' => _x('Suburb Profiles', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Suburb Profile', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Suburb Profiles', 'text_domain'),
        'name_admin_bar' => __('Suburb Profiles', 'text_domain'),
        'archives' => __('Item Archives', 'text_domain'),
        'attributes' => __('Item Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All Suburb Profiles', 'text_domain'),
        'add_new_item' => __('Add New Suburb Profile', 'text_domain'),
        'add_new' => __('Add New', 'text_domain'),
        'new_item' => __('New Suburb Profile', 'text_domain'),
        'edit_item' => __('Edit Suburb Profile', 'text_domain'),
        'update_item' => __('Update Suburb Profile', 'text_domain'),
        'view_item' => __('View Suburb Profile', 'text_domain'),
        'view_items' => __('View Suburb Profiles', 'text_domain'),
        'search_items' => __('Search Suburb Profiles', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into Suburb Profile', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this Suburb Profile', 'text_domain'),
        'items_list' => __('Suburb Profiles list', 'text_domain'),
        'items_list_navigation' => __('Suburb Profiles list navigation', 'text_domain'),
        'filter_items_list' => __('Filter Suburb Profiles list', 'text_domain'),
    ];
    $args = [
        'label' => __('Suburb Profile', 'text_domain'),
        'description' => __('Generated suburb profiles to be used on digital appraisal.', 'text_domain'),
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes', 'comments', 'revisions'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'rewrite' => ['slug' => 'suburb-profile', 'with_front' => false],
    ];
    register_post_type('suburb-profile', $args);

}
add_action('init', 'dsp_post_type_suburb_profile', 0);

function add_location_tax_to_my_cpt() {
    register_taxonomy_for_object_type( 'location', 'suburb-profile' );
}
add_action( 'init', 'add_location_tax_to_my_cpt', 100 );

function sp_register_suburb_profile_meta_box() {
    add_meta_box(
        'sp_suburb_meta_box',          // ID of the meta box
        'Suburb Profile Details',      // Title of the meta box
        'sp_suburb_meta_box_callback', // Callback that renders the fields
        'suburb-profile',              // Post type
        'side',                        // Context: side column
        'default'                      // Priority
    );
}
add_action( 'add_meta_boxes', 'sp_register_suburb_profile_meta_box' );

function sp_suburb_meta_box_callback( $post ) {
    // Retrieve existing values from post meta
    $rc_suburb   = get_post_meta( $post->ID, 'rc_suburb', true );
    $rc_lat      = get_post_meta( $post->ID, 'rc_lat', true );
    $rc_long     = get_post_meta( $post->ID, 'rc_long', true );
    $rc_state    = get_post_meta( $post->ID, 'rc_state', true );
    $rc_boundary = get_post_meta( $post->ID, 'rc_boundary', true );
    $rc_center   = get_post_meta( $post->ID, 'rc_center', true );
    $rc_postcode = get_post_meta( $post->ID, 'rc_postcode', true );

    // Add a nonce field for security
    wp_nonce_field( 'sp_suburb_profile_nonce_action', 'sp_suburb_profile_nonce' );
    ?>
    <p>
        <label for="rc_suburb"><strong>Suburb:</strong></label><br>
        <input type="text" name="rc_suburb" id="rc_suburb" value="<?php echo esc_attr($rc_suburb); ?>" style="width:100%;">
    </p>
    <p>
        <label for="rc_postcode"><strong>Postcode:</strong></label><br>
        <input type="text" name="rc_postcode" id="rc_postcode" value="<?php echo esc_attr($rc_postcode); ?>" style="width:100%;">
    </p>
    <p>
        <label for="rc_state"><strong>State:</strong></label><br>
        <input type="text" name="rc_state" id="rc_state" value="<?php echo esc_attr($rc_state); ?>" style="width:100%;">
    </p>
    <p>
        <label for="rc_lat"><strong>Latitude (rc_lat):</strong></label><br>
        <input type="text" name="rc_lat" id="rc_lat" value="<?php echo esc_attr($rc_lat); ?>" style="width:100%;">
    </p>
    <p>
        <label for="rc_long"><strong>Longitude (rc_long):</strong></label><br>
        <input type="text" name="rc_long" id="rc_long" value="<?php echo esc_attr($rc_long); ?>" style="width:100%;">
    </p>
    <p>
        <label for="rc_center"><strong>Center (JSON):</strong></label><br>
        <textarea name="rc_center" id="rc_center" style="width:100%; height:100px;"><?php echo esc_textarea($rc_center); ?></textarea>
    </p>
    <p>
        <label for="rc_boundary"><strong>Boundary (JSON):</strong></label><br>
        <textarea name="rc_boundary" id="rc_boundary" style="width:100%; height:100px;"><?php echo esc_textarea($rc_boundary); ?></textarea>
    </p>
    <?php
}

function sp_save_suburb_profile_meta_box( $post_id ) {
    // Check nonce for security
    if ( ! isset($_POST['sp_suburb_profile_nonce']) || 
         ! wp_verify_nonce( $_POST['sp_suburb_profile_nonce'], 'sp_suburb_profile_nonce_action' ) ) {
        return;
    }

    // Check if this is an autosave or user doesn't have permissions
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save/Update the meta values
    $fields = ['rc_suburb', 'rc_lat', 'rc_long', 'rc_state', 'rc_boundary', 'rc_center', 'rc_postcode'];
    foreach ( $fields as $field ) {
        if ( isset($_POST[$field]) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post_suburb-profile', 'sp_save_suburb_profile_meta_box' );

function dpp_post_type_property_profile()
{
    $labels = [
        'name' => _x('Property Profiles', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Property Profile', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Property Profiles', 'text_domain'),
        'name_admin_bar' => __('Property Profiles', 'text_domain'),
        'archives' => __('Item Archives', 'text_domain'),
        'attributes' => __('Item Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All Property Profiles', 'text_domain'),
        'add_new_item' => __('Add New Property Profile', 'text_domain'),
        'add_new' => __('Add New', 'text_domain'),
        'new_item' => __('New Property Profile', 'text_domain'),
        'edit_item' => __('Edit Property Profile', 'text_domain'),
        'update_item' => __('Update Property Profile', 'text_domain'),
        'view_item' => __('View Property Profile', 'text_domain'),
        'view_items' => __('View Property Profiles', 'text_domain'),
        'search_items' => __('Search Property Profiles', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into Property Profile', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this Property Profile', 'text_domain'),
        'items_list' => __('Property Profiles list', 'text_domain'),
        'items_list_navigation' => __('Property Profiles list navigation', 'text_domain'),
        'filter_items_list' => __('Filter Property Profiles list', 'text_domain'),
    ];
    $args = [
        'label' => __('Property Profile', 'text_domain'),
        'description' => __('Generated property profiles to be used on digital appraisal.', 'text_domain'),
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes', 'comments', 'revisions'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 6,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'rewrite' => ['slug' => 'property-profile', 'with_front' => false],
    ];
    register_post_type('property-profile', $args);

}
add_action('init', 'dpp_post_type_property_profile', 0);

function add_location_tax_to_my_cpt_pp()
{
    register_taxonomy_for_object_type('location', 'property-profile');
}
add_action('init', 'add_location_tax_to_my_cpt_pp', 100);

function sp_register_property_profile_meta_box() {
    add_meta_box(
        'sp_property_meta_box',          // ID of the meta box
        'Property Profile Details',      // Title of the meta box
        'sp_property_meta_box_callback', // Callback that renders the fields
        'property-profile',              // Post type
        'side',                        // Context: side column
        'default'                      // Priority
    );
}
add_action( 'add_meta_boxes', 'sp_register_property_profile_meta_box' );

function sp_property_meta_box_callback( $post ) {
    // Retrieve existing values from post meta
    $rc_suburb   = get_post_meta( $post->ID, 'rc_property', true );
    $rc_state    = get_post_meta( $post->ID, 'rc_state', true );
    $rc_postcode = get_post_meta( $post->ID, 'rc_postcode', true );

    // Add a nonce field for security
    wp_nonce_field( 'sp_property_profile_nonce_action', 'sp_property_profile_nonce' );
    ?>
    <p>
        <label for="rc_suburb"><strong>Suburb:</strong></label><br>
        <input type="text" name="rc_suburb" id="rc_suburb" value="<?php echo esc_attr($rc_suburb); ?>" style="width:100%;">
    </p>
    <p>
        <label for="rc_postcode"><strong>Postcode:</strong></label><br>
        <input type="text" name="rc_postcode" id="rc_postcode" value="<?php echo esc_attr($rc_postcode); ?>" style="width:100%;">
    </p>
    <p>
        <label for="rc_state"><strong>State:</strong></label><br>
        <input type="text" name="rc_state" id="rc_state" value="<?php echo esc_attr($rc_state); ?>" style="width:100%;">
    </p>
    <?php
}

function sp_save_property_profile_meta_box( $post_id ) {
    // Check nonce for security
    if ( ! isset($_POST['sp_property_profile_nonce']) || 
         ! wp_verify_nonce( $_POST['sp_property_profile_nonce'], 'sp_property_profile_nonce_action' ) ) {
        return;
    }

    // Check if this is an autosave or user doesn't have permissions
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save/Update the meta values
    $fields = ['rc_suburb', 'rc_state', 'rc_postcode'];
    foreach ( $fields as $field ) {
        if ( isset($_POST[$field]) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post_property-profile', 'sp_save_property_profile_meta_box' );