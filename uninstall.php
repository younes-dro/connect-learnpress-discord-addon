<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Learnpress_Discord_Addon
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( $_REQUEST['plugin'] == 'connect-learnpress-discord-addon/learnpress-discord-addon.php'
		&& $_REQUEST['slug'] == 'learnpress-discord-add'
	&& wp_verify_nonce( $_REQUEST['_ajax_nonce'], 'updates' )
  ) {
	$ets_learnpress_discord_data_erases = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_data_erases' ) ) );
	if ( $ets_learnpress_discord_data_erases ) {
		global $wpdb;
		$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . "usermeta WHERE `meta_key` LIKE '_ets_learnpress_discord%'" );
		$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . "options WHERE `option_name` LIKE 'ets_learnpress_discord_%'" );
	}
}

