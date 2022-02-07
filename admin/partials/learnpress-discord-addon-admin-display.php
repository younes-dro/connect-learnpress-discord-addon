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
<h1><?php echo __( 'LearnPress Discord Add On Settings', 'learnpress-discord-addon' ); ?></h1>
		<div id="learnpress-discord-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 0 }'>
			<ul class="skltbs-tab-group">
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="settings" ><?php echo __( 'Application Details', 'learnpress-discord-addon' ); ?><span class="initialtab spinner"></span></button>
				</li>
				<li class="skltbs-tab-item">
				<?php if ( learnpress_discord_check_saved_settings_status() ): ?>
				<button class="skltbs-tab" data-identity="level-mapping" ><?php echo __( 'Role Mappings', 'learnpress-discord-addon' ); ?></button>
				<?php endif; ?>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="advanced" ><?php echo __( 'Advanced', 'learnpress-discord-addon' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="logs" ><?php echo __( 'Logs', 'learnpress-discord-addon' ); ?>	
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
				<div id='ets_learnpress_discord_logs' class="learnpress-discord-tab-conetent skltbs-panel">
				<?php require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/learnpress_discord_error_log.php'; ?>
				</div>                            
			</div>  
		</div>
