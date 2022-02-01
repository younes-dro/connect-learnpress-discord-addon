<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.expresstechsoftwares.com
 * @since             1.0.0
 * @package           Learnpress_Discord_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       LearnPress Discord Add
 * Plugin URI:        https://www.expresstechsoftwares.com/step-by-step-guide-on-how-to-connect-learnpress-and-discord
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           4.0.0
 * Author:            ExpressTech Softwares Solutions Pvt Ltd
 * Author URI:        https://www.expresstechsoftwares.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       learnpress-discord-addon
 * Domain Path:       /languages
 * 
 * Require_LP_Version: 4.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LEARNPRESS_DISCORD_ADDON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-learnpress-discord-addon-activator.php
 */
function activate_learnpress_discord_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learnpress-discord-addon-activator.php';
	Learnpress_Discord_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-learnpress-discord-addon-deactivator.php
 */
function deactivate_learnpress_discord_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learnpress-discord-addon-deactivator.php';
	Learnpress_Discord_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_learnpress_discord_addon' );
register_deactivation_hook( __FILE__, 'deactivate_learnpress_discord_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-learnpress-discord-addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_learnpress_discord_addon() {

	$plugin = new Learnpress_Discord_Addon();
	$plugin->run();

}
run_learnpress_discord_addon();
