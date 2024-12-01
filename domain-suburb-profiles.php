<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Domain Suburb Profiles
 * Plugin URI:        https://realcoder.com.au
 * Description:       Create Suburb Profiles from Domain API.
 * Version:           0.1.01
 * Author:            Matthew Neal
 * Author URI:        https://realcoder.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dsp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

define('RC_IDA_VERSION', '0.1.01');

function activate_domain_suburb_profiles()
{
    require_once plugin_dir_path(__FILE__).'includes/class-dsp-activator.php';
    DSP_Activator::activate();
}

function deactivate_domain_suburb_profiles()
{
    require_once plugin_dir_path(__FILE__).'includes/class-dsp-deactivator.php';
    DSP_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_domain_suburb_profiles');
register_deactivation_hook(__FILE__, 'deactivate_domain_suburb_profiles');

require plugin_dir_path(__FILE__).'includes/class-domain-suburb-profiles.php';
require plugin_dir_path(__FILE__).'includes/class-boundary-fetcher.php';

require plugin_dir_path(__FILE__).'includes/helper-functions.php';
require plugin_dir_path(__FILE__).'includes/shortcodes.php';
require plugin_dir_path(__FILE__).'includes/cli-commands.php';
require plugin_dir_path(__FILE__).'includes/custom-posts-taxonomies.php';

require plugin_dir_path(__FILE__).'admin/admin-options.php';

require plugin_dir_path(__FILE__).'public/class-domain-api.php';

$plugin = new Domain_Suburb_Profiles();
$plugin->run();