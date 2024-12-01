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