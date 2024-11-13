<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://realcoder.com.au
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Pricefinder Digital Appraisal
 * Plugin URI:        https://realcoder.com.au
 * Description:       Create Digital Appraisals for users, generate suburb reports using data from Pricefinder.
 * Version:           1.0.0
 * Author:            Matthew Neal
 * Author URI:        https://realcoder.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pricefinder-da
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PRICEFINDER_DA_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pricefinder-da-activator.php
 */
function activate_pricefinder_da()
{
    require_once plugin_dir_path(__FILE__).'includes/class-pricefinder-da-activator.php';
    Pricefinder_Da_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pricefinder-da-deactivator.php
 */
function deactivate_pricefinder_da()
{
    require_once plugin_dir_path(__FILE__).'includes/class-pricefinder-da-deactivator.php';
    Pricefinder_Da_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_pricefinder_da');
register_deactivation_hook(__FILE__, 'deactivate_pricefinder_da');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-pricefinder-da.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pricefinder_da()
{

    $plugin = new Pricefinder_Da();
    $plugin->run();

}
run_pricefinder_da();

// Register Custom Post Type for Appraisal
function rc_ida_post_type_appraisal()
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
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    ];
    register_post_type('appraisal', $args);

}
add_action('init', 'rc_ida_post_type_appraisal', 0);

// Register Custom Post Type for Suburb Report
function rc_ida_post_type_suburb_report()
{
    $labels = [
        'name' => _x('Suburb Reports', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Suburb Report', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Suburb Reports', 'text_domain'),
        'name_admin_bar' => __('Suburb Reports', 'text_domain'),
        'archives' => __('Item Archives', 'text_domain'),
        'attributes' => __('Item Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All Suburb Reports', 'text_domain'),
        'add_new_item' => __('Add New Suburb Report', 'text_domain'),
        'add_new' => __('Add New', 'text_domain'),
        'new_item' => __('New Suburb Report', 'text_domain'),
        'edit_item' => __('Edit Suburb Report', 'text_domain'),
        'update_item' => __('Update Suburb Report', 'text_domain'),
        'view_item' => __('View Suburb Report', 'text_domain'),
        'view_items' => __('View Suburb Reports', 'text_domain'),
        'search_items' => __('Search Suburb Reports', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into Suburb Report', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this Suburb Report', 'text_domain'),
        'items_list' => __('Suburb Reports list', 'text_domain'),
        'items_list_navigation' => __('Suburb Reports list navigation', 'text_domain'),
        'filter_items_list' => __('Filter Suburb Reports list', 'text_domain'),
    ];
    $args = [
        'label' => __('Suburb Report', 'text_domain'),
        'description' => __('Generated suburb reports to be used on digital appraisal.', 'text_domain'),
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail'],
        'taxonomies' => ['suburb'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    ];
    register_post_type('suburb-report', $args);

}
add_action('init', 'rc_ida_post_type_suburb_report', 0);

// Register Custom Post Type for Property Report
function rc_ida_post_type_property_report()
{
    $labels = [
        'name' => _x('Property Reports', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Property Report', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Property Reports', 'text_domain'),
        'name_admin_bar' => __('Property Reports', 'text_domain'),
        'archives' => __('Item Archives', 'text_domain'),
        'attributes' => __('Item Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All Property Reports', 'text_domain'),
        'add_new_item' => __('Add New Property Report', 'text_domain'),
        'add_new' => __('Add New', 'text_domain'),
        'new_item' => __('New Property Report', 'text_domain'),
        'edit_item' => __('Edit Property Report', 'text_domain'),
        'update_item' => __('Update Property Report', 'text_domain'),
        'view_item' => __('View Property Report', 'text_domain'),
        'view_items' => __('View Property Reports', 'text_domain'),
        'search_items' => __('Search Property Reports', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into Property Report', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this Property Report', 'text_domain'),
        'items_list' => __('Property Reports list', 'text_domain'),
        'items_list_navigation' => __('Property Reports list navigation', 'text_domain'),
        'filter_items_list' => __('Filter Property Reports list', 'text_domain'),
    ];
    $args = [
        'label' => __('Property Report', 'text_domain'),
        'description' => __('Generated property reports to be used on digital appraisal.', 'text_domain'),
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail'],
        'taxonomies' => ['suburb'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    ];
    register_post_type('property-report', $args);

}
add_action('init', 'rc_ida_post_type_property_report', 0);

// Register Custom Taxonomy for Suburb
function rc_ida_taxonomy_suburb()
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
        'slug' => 'rc-ida-suburb',
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
    register_taxonomy('rc-ida-suburb', ['appraisal'], $args);

}
add_action('init', 'rc_ida_taxonomy_suburb', 0);

// Register Custom Taxonomy for Property
function rc_ida_taxonomy_property()
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
        'slug' => 'rc-ida-property',
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
    register_taxonomy('rc-ida-property', ['appraisal'], $args);

}
add_action('init', 'rc_ida_taxonomy_property', 0);