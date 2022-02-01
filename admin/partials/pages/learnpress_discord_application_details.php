<?php
$ets_learnpress_discord_client_id     = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_client_id' ) ) );
$ets_learnpress_discord_client_secret = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_client_secret' ) ) );
$ets_learnpress_discord_bot_token     = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_bot_token' ) ) );
$ets_learnpress_discord_redirect_url  = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_redirect_url' ) ) );
$ets_learnpress_discord_redirect_page_id  = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_redirect_page_id' ) ) );
$ets_learnpress_discord_server_id     = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_server_id' ) ) );
?>
<form method="post" action="<?php echo get_site_url() . '/wp-admin/admin-post.php'; ?>">
  <input type="hidden" name="action" value="learnpress_discord_application_settings">
	<?php wp_nonce_field( 'save_learnpress_discord_general_settings', 'ets_learnpress_discord_save_settings' ); ?>
  <div class="ets-input-group">
	<label><?php echo __( 'Client ID', 'learnpress-discord-addon' ); ?> :</label>
	<input type="text" class="ets-input" name="ets_learnpress_discord_client_id" value="<?php
	if ( isset( $ets_learnpress_discord_client_id ) ) {
		echo esc_attr( $ets_learnpress_discord_client_id ); }
	?>" required placeholder="Discord Client ID">
  </div>
	<div class="ets-input-group">
	  <label><?php echo __( 'Client Secret', 'learnpress-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_learnpress_discord_client_secret" value="<?php
		if ( isset( $ets_learnpress_discord_client_secret ) ) {
			echo esc_attr( $ets_learnpress_discord_client_secret ); }
    ?>" required placeholder="Discord Client Secret">
	</div>
	<div class="ets-input-group">
            <label><?php echo __( 'Redirect URL', 'learnpress-discord-addon' ); ?> :</label>
            <p><b><?php echo $ets_learnpress_discord_redirect_url ?></b></p>
		<select class= "ets-input" id="ets_learnpress_discord_redirect_url" name="ets_learnpress_discord_redirect_url" style="max-width: 100%" required>
		<?php echo ets_learnpress_discord_pages_list( $ets_learnpress_discord_redirect_page_id ) ; ?>
		</select>
		<p class="description"><?php echo __( 'Registered discord app redirect url', 'learnpress-discord-addon' ); ?></p>
	</div>
	<div class="ets-input-group">
	  <label><?php echo __( 'Bot Token', 'learnpress-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_learnpress_discord_bot_token" value="<?php
		if ( isset( $ets_learnpress_discord_bot_token ) ) {
			echo esc_attr( $ets_learnpress_discord_bot_token ); }
		?>" required placeholder="Discord Bot Token">
	</div>
	<div class="ets-input-group">
	  <label><?php echo __( 'Server ID', 'learnpress-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_learnpress_discord_server_id"
		placeholder="Discord Server Id" value="<?php
		if ( isset( $ets_learnpress_discord_server_id ) ) {
			echo esc_attr( $ets_learnpress_discord_server_id ); }
		?>" required>
	</div>
	<?php if ( empty( $ets_learnpress_discord_client_id ) || empty( $ets_learnpress_discord_client_secret ) || empty( $ets_learnpress_discord_bot_token ) || empty( $ets_learnpress_discord_redirect_url ) || empty( $ets_learnpress_discord_server_id ) ) { ?>
	  <p class="ets-danger-text description">
		<?php echo __( 'Please save your form', 'learnpress-discord-addon' ); ?>
	  </p>
	<?php } ?>
	<p>
	  <button type="submit" name="submit" value="ets_discord_submit" class="ets-submit ets-bg-green">
		<?php echo __( 'Save Settings', 'learnpress-discord-addon' ); ?>
	  </button>
	  <?php if ( get_option( 'ets_learnpress_discord_client_id' ) ) : ?>
		<a href="?action=learnpress-discord-connect-to-bot" class="ets-btn learnpress-btn-connect-to-bot" id="learnpress-connect-discord-bot"><?php echo __( 'Connect your Bot', 'learnpress-discord-addon' ); ?> <i class='fab fa-discord'></i></a>
	  <?php endif; ?>
	</p>
</form>
