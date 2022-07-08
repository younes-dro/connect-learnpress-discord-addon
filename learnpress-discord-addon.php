<?php

/**
 * @link              https://www.expresstechsoftwares.com
 * @since             1.0.0
 * @package           Learnpress_Discord_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Connect LearnPress Discord Add-on
 * Plugin URI:        https://www.expresstechsoftwares.com/step-by-step-guide-on-how-to-connect-learnpress-and-discord
 * Description:       Connect LearnPress LMS with Discord and add a new dimension to your LearnPress website by allowing students to collaborate and learn together via Discord community features, manage and assign roles to students based on the course they have registered for.
 * Version:           1.0.0
 * Author:            ExpressTech Softwares Solutions Pvt Ltd
 * Author URI:        https://www.expresstechsoftwares.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connect-learnpress-discord-addon
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
 */
define( 'LEARNPRESS_DISCORD_ADDON_VERSION', '1.0.0' );

/**
 * Define plugin directory path
 */
define( 'LEARNPRESS_DISCORD_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Discord Bot Permissions.
 */
define( 'LEARNPRESS_DISCORD_BOT_PERMISSIONS', 8 );

/**
 * Discord api call scopes.
 */
define( 'LEARNPRESS_DISCORD_OAUTH_SCOPES', 'identify email connections guilds guilds.join gdm.join rpc rpc.notifications.read rpc.voice.read rpc.voice.write rpc.activities.write bot webhook.incoming applications.builds.upload applications.builds.read applications.commands applications.store.update applications.entitlements activities.read activities.write relationships.read' );

/**
 * Define group name for action scheduler actions
 */
define( 'LEARNPRESS_DISCORD_AS_GROUP_NAME', 'ets-learnpress-discord' );
/**
 * Follwing response codes not cosider for re-try API calls.
 */
define( 'LEARNPRESS_DISCORD_DONOT_RETRY_THESE_API_CODES', array( 0, 10003, 50033, 10004, 50025, 10013, 10011 ) );

/**
 * Define plugin directory url
 */
define( 'LEARNPRESS_DISCORD_DONOT_RETRY_HTTP_CODES', array( 400, 401, 403, 404, 405, 502 ) );
/**
 * Discord API url. 
 */
define( 'LEARNPRESS_DISCORD_API_URL', 'https://discord.com/api/v10/' );

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
