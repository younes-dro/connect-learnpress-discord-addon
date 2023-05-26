<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Learnpress_Discord_Addon
 * @subpackage Learnpress_Discord_Addon/admin/partials
 */
?>
<?php
if ( isset( $_GET['save_settings_msg'] ) ) {
	?>
	<div class="notice notice-success is-dismissible support-success-msg">
		<p><?php echo esc_html( $_GET['save_settings_msg'] ); ?></p>
	</div>
	<?php
}
?>
<h1><?php esc_html_e( 'LearnPress Discord Add On Settings', 'connect-learnpress-discord-addon' ); ?></h1>
		<div id="learnpress-discord-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 0 }'>
			<ul class="skltbs-tab-group">
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="settings" ><?php esc_html_e( 'Application Details', 'connect-learnpress-discord-addon' ); ?><span class="initialtab spinner"></span></button>
				</li>
				<li class="skltbs-tab-item">
				<?php if ( learnpress_discord_check_saved_settings_status() ): ?>
				<button class="skltbs-tab" data-identity="level-mapping" ><?php esc_html_e( 'Role Mappings', 'connect-learnpress-discord-addon' ); ?></button>
				<?php endif; ?>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="advanced" ><?php esc_html_e( 'Advanced', 'connect-learnpress-discord-addon' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="appearance" ><?php esc_html_e( 'Appearance', 'connect-learnpress-discord-addon' ); ?>	
				</button>
				</li>                                
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="logs" ><?php esc_html_e( 'Logs', 'connect-learnpress-discord-addon' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="support" ><?php esc_html_e( 'Support', 'connect-learnpress-discord-addon' ); ?>	
				</button>
				</li>				
            </ul>
			<div class="skltbs-panel-group">
				<div id="ets_learnpress_application_details" class="learnpress-discord-tab-conetent skltbs-panel">
				<?php require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/learnpress_discord_application_details.php'; ?>
				</div>
				<?php if ( learnpress_discord_check_saved_settings_status() ): ?>      
				<div id="ets_learnpress_discord_role_mapping" class="learnpress-discord-tab-conetent skltbs-panel">
					<?php require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/learnpress_discord_role_mapping.php'; ?>
				</div>
				<?php endif; ?>
				<div id='ets_learnpress_discord_advanced' class="learnpress-discord-tab-conetent skltbs-panel">
				<?php require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/learnpress_discord_advanced.php'; ?>
				</div>
				<div id='ets_learnpress_discord_appearance' class="learnpress-discord-tab-conetent skltbs-panel">
				<?php require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/learnpress_discord_appearance.php'; ?>
				</div>                            
				<div id='ets_learnpress_discord_logs' class="learnpress-discord-tab-conetent skltbs-panel">
				<?php require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/learnpress_discord_error_log.php'; ?>
				</div>
				<div id='ets_learnpress_discord_support' class="learnpress-discord-tab-conetent skltbs-panel">
				<?php require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/learnpress_discord_support.php'; ?>
				</div>                           
			</div>  
		</div>
