<?php
/**
 * Custom Post Types and Taxonomies.
 */
// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

function dsp_post_type_appraisal()
{
    $labels = [
        'name' => _x('Appraisals', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Appraisal', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Appraisals', 'text_domain'),
        'name_admin_bar' => __('Appraisals', 'text_domain'),
        'archives' => __('Item Archives', 'text_domain'),
        'attributes' => __('Item Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All Appraisals', 'text_domain'),
        'add_new_item' => __('Add New Appraisal', 'text_domain'),
        'add_new' => __('Add New', 'text_domain'),
        'new_item' => __('New Appraisal', 'text_domain'),
        'edit_item' => __('Edit Appraisal', 'text_domain'),
        'update_item' => __('Update Appraisal', 'text_domain'),
        'view_item' => __('View Appraisal', 'text_domain'),
        'view_items' => __('View Appraisals', 'text_domain'),
        'search_items' => __('Search Appraisals', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into Appraisal', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this Appraisal', 'text_domain'),
        'items_list' => __('Appraisals list', 'text_domain'),
        'items_list_navigation' => __('Appraisals list navigation', 'text_domain'),
        'filter_items_list' => __('Filter Appraisals list', 'text_domain'),
    ];
    $args = [
        'label' => __('Appraisal', 'text_domain'),
        'description' => __('Generated appraisals to be used on digital appraisal.', 'text_domain'),
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail'],
        'taxonomies' => ['Appraisal'],
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
    ];
    register_post_type('appraisal', $args);

}
add_action('init', 'dsp_post_type_appraisal', 0);

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

// Register Custom Post Type for Property Profile
function dsp_post_type_property_profile()
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
        'supports' => ['title', 'editor', 'thumbnail'],
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
    ];
    register_post_type('property-profiles', $args);

}
add_action('init', 'dsp_post_type_property_profile', 0);

// Register Custom Taxonomy for Suburb
function dsp_taxonomy_suburb()
{

    $labels = [
        'name' => _x('Suburbs', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Suburb', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Suburbs', 'text_domain'),
        'all_items' => __('All Suburbs', 'text_domain'),
        'parent_item' => __('Parent Suburb', 'text_domain'),
        'parent_item_colon' => __('Parent Suburb:', 'text_domain'),
        'new_item_name' => __('New Suburb Name', 'text_domain'),
        'add_new_item' => __('Add Suburb Item', 'text_domain'),
        'edit_item' => __('Edit Suburb', 'text_domain'),
        'update_item' => __('Update Suburb', 'text_domain'),
        'view_item' => __('View Suburb', 'text_domain'),
        'separate_items_with_commas' => __('Separate Suburb with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Suburbs', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Suburbs', 'text_domain'),
        'search_items' => __('Search Suburbs', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Suburbs', 'text_domain'),
        'items_list' => __('Suburbs list', 'text_domain'),
        'items_list_navigation' => __('Suburbs list navigation', 'text_domain'),
    ];
    $rewrite = [
        'slug' => 'dsp-suburb',
        'with_front' => true,
        'hierarchical' => false,
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => $rewrite,
    ];
    register_taxonomy('dsp-suburb', ['appraisal'], $args);

}
add_action('init', 'dsp_taxonomy_suburb', 0);

// Register Custom Taxonomy for Property
function dsp_taxonomy_property()
{

    $labels = [
        'name' => _x('Properties', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Property', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Properties', 'text_domain'),
        'all_items' => __('All Properties', 'text_domain'),
        'parent_item' => __('Parent Property', 'text_domain'),
        'parent_item_colon' => __('Parent Property:', 'text_domain'),
        'new_item_name' => __('New Property Name', 'text_domain'),
        'add_new_item' => __('Add Property Item', 'text_domain'),
        'edit_item' => __('Edit Property', 'text_domain'),
        'update_item' => __('Update Property', 'text_domain'),
        'view_item' => __('View Property', 'text_domain'),
        'separate_items_with_commas' => __('Separate Property with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Properties', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Properties', 'text_domain'),
        'search_items' => __('Search Properties', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Properties', 'text_domain'),
        'items_list' => __('Properties list', 'text_domain'),
        'items_list_navigation' => __('Properties list navigation', 'text_domain'),
    ];
    $rewrite = [
        'slug' => 'dsp-property',
        'with_front' => true,
        'hierarchical' => false,
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => $rewrite,
    ];
    register_taxonomy('dsp-property', ['appraisal'], $args);

}
add_action('init', 'dsp_taxonomy_property', 0);