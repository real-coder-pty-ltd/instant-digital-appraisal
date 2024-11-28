<?php
/*
 * Plugin Name:       Pricefinder Digital Appraisal
 * Plugin URI:        https://realcoder.com.au
 * Description:       Create Digital Appraisals for users, generate suburb profiles using data from the Domain API.
 * Version:           1.0.0
 * Author:            Matthew Neal
 * Author URI:        https://realcoder.com.au
 * License:           GPL-2.0+
 * Text Domain:       pricefinder-da
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

define('PRICEFINDER_DA_VERSION', '1.0.0');


function activate_pricefinder_da(): void
{
    require_once plugin_dir_path(__FILE__).'includes/class-pricefinder-da-activator.php';
    Pricefinder_Da_Activator::activate();
}

function deactivate_pricefinder_da():void
{
    require_once plugin_dir_path(__FILE__).'includes/class-pricefinder-da-deactivator.php';
    Pricefinder_Da_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_pricefinder_da');
register_deactivation_hook(__FILE__, 'deactivate_pricefinder_da');

require plugin_dir_path(__FILE__).'includes/class-pricefinder-da.php';
require plugin_dir_path(__FILE__).'includes/class-register-content-types.php';

new RC_IDA\RegisterContentTypes();

$plugin = new Pricefinder_Da();
$plugin->run();