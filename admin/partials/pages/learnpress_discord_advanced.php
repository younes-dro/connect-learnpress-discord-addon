<?php
$ets_learnpress_discord_send_welcome_dm         = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_send_welcome_dm' ) ) );
$ets_learnpress_discord_welcome_message         = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_welcome_message' ) ) );
$ets_learnpress_discord_send_course_complete_dm = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_send_course_complete_dm' ) ) );
$ets_learnpress_discord_course_complete_message = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_course_complete_message' ) ) );
$ets_learnpress_discord_send_lesson_complete_dm = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_send_lesson_complete_dm' ) ) );
$ets_learnpress_discord_lesson_complete_message = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_lesson_complete_message' ) ) );


// $ets_learnpress_discord_send_quiz_complete_dm   = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_send_quiz_complete_dm' ) ) );
// $ets_learnpress_discord_quiz_complete_message   = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_quiz_complete_message' ) ) );

$retry_failed_api                   = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_retry_failed_api' ) ) );
$kick_upon_disconnect               = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_kick_upon_disconnect' ) ) );
$retry_api_count                    = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_retry_api_count' ) ) );
$set_job_cnrc                       = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_job_queue_concurrency' ) ) );
$set_job_q_batch_size               = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_job_queue_batch_size' ) ) );
$log_api_res                        = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_log_api_response' ) ) );
$embed_messaging_feature            = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_embed_messaging_feature' ) ) );
$allow_discord_login                = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_allow_discord_login' ) ) );
$ets_learnpress_discord_data_erases = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_data_erases' ) ) );
?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="learnpress_discord_save_advance_settings">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_learnpress_discord_get_current_screen_url() ); ?>">   
<?php wp_nonce_field( 'learnpress_discord_advance_settings_nonce', 'ets_learnpress_discord_advance_settings_nonce' ); ?>
  <table class="form-table" role="presentation">
	<tbody>	<tr>
		<th scope="row"><?php esc_html_e( 'Shortcode:', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		[ets_learnpress_discord]
		<br/>
		<small><?php esc_html_e( 'Use this shortcode [ets_learnpress_discord] to display connect to discord button on any page.', 'connect-learnpress-discord-addon' ); ?></small>
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Data erases on uninstall?', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_learnpress_discord_data_erases" type="checkbox" id="ets_learnpress_discord_data_erases" 
		<?php
		if ( $ets_learnpress_discord_data_erases == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
				<br/>
				<small>By checking this box, you are indicating that you want to delete all data associated with the plugin when it is uninstalled.</small>                
		</fieldset></td>
	  </tr>		            
	<tr>
		<th scope="row"><?php esc_html_e( 'Send welcome message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_learnpress_discord_send_welcome_dm" type="checkbox" id="ets_learnpress_discord_send_welcome_dm" 
		<?php
		if ( $ets_learnpress_discord_send_welcome_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Use rich embed messaging feature?', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="embed_messaging_feature" type="checkbox" id="embed_messaging_feature" 
		<?php
		if ( $embed_messaging_feature == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
				<br/>
				<small>Use [LINEBREAK] to split lines.</small>                
		</fieldset></td>
	  </tr>        
	<tr>
		<th scope="row"><?php esc_html_e( 'Welcome message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<textarea class="ets_learnpress_discord_dm_textarea" name="ets_learnpress_discord_welcome_message" id="ets_learnpress_discord_welcome_message" row="25" cols="50">
		<?php
		if ( $ets_learnpress_discord_welcome_message ) {
			echo esc_textarea( wp_unslash( $ets_learnpress_discord_welcome_message ) ); }
		?>
		</textarea> 
	<br/>
	<small>Merge fields: [LP_STUDENT_NAME], [LP_STUDENT_EMAIL], [LP_COURSES], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Course Complete message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_learnpress_discord_send_course_complete_dm" type="checkbox" id="ets_learnpress_discord_send_course_complete_dm" 
		<?php
		if ( $ets_learnpress_discord_send_course_complete_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Course Complete message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<textarea class="ets_learnpress_discord_course_complete_message" name="ets_learnpress_discord_course_complete_message" id="ets_learnpress_discord_course_complete_message" row="25" cols="50">
		<?php
		if ( $ets_learnpress_discord_course_complete_message ) {
			echo esc_textarea( wp_unslash( $ets_learnpress_discord_course_complete_message ) ); }
		?>
		</textarea> 
	<br/>
	<small>Merge fields: [LP_STUDENT_NAME], [LP_STUDENT_EMAIL], [LP_COURSE_NAME], [LP_COURSE_COMPLETE_DATE], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Lesson Complete message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_learnpress_discord_send_lesson_complete_dm" type="checkbox" id="ets_learnpress_discord_send_lesson_complete_dm" 
		<?php
		if ( $ets_learnpress_discord_send_lesson_complete_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Lesson Complete message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<textarea class="ets_learnpress_discord_lesson_complete_message" name="ets_learnpress_discord_lesson_complete_message" id="ets_learnpress_discord_lesson_complete_message" row="25" cols="50">
		<?php
		if ( $ets_learnpress_discord_lesson_complete_message ) {
			echo esc_textarea( wp_unslash( $ets_learnpress_discord_lesson_complete_message ) ); }
		?>
		</textarea> 
	<br/>
	<small>Merge fields:  [LP_STUDENT_NAME], [LP_STUDENT_EMAIL], [LP_LESSON_NAME], [LP_COURSE_LESSON_DATE], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	  </tr>
		   
<!--  <tr>
		<th scope="row"><?php // esc_html_e( 'Send Quiz Complete message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_learnpress_discord_send_quiz_complete_dm" type="checkbox" id="ets_learnpress_discord_send_quiz_complete_dm" 
		<?php
		// if ( $ets_learnpress_discord_send_quiz_complete_dm == true ) {
		// echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>-->
<!--	<tr>
		<th scope="row"><?php // esc_html_e( 'Topic Quiz message', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<textarea class="ets_learnpress_discord_quiz_complete_message" name="ets_learnpress_discord_quiz_complete_message" id="ets_learnpress_discord_quiz_complete_message" row="25" cols="50"><?php // if ( $ets_learnpress_discord_quiz_complete_message ) { echo esc_textarea ( wp_unslash( $ets_learnpress_discord_quiz_complete_message ) ); } ?></textarea> 
	<br/>
	<small>Merge fields: [LP_STUDENT_NAME], [LP_STUDENT_EMAIL], [LP_QUIZ_NAME], [LP_QUIZ_DATE], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	  </tr>          -->

	  <tr>
		<th scope="row"><?php esc_html_e( 'Retry Failed API calls', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="retry_failed_api" type="checkbox" id="retry_failed_api" 
		<?php
		if ( $retry_failed_api == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Don\'t kick students upon disconnect', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="kick_upon_disconnect" type="checkbox" id="kick_upon_disconnect" 
		<?php
		if ( $kick_upon_disconnect == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php echo __( 'Allow Discord Authentication before checkout?', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="allow_discord_login" type="checkbox" id="allow_discord_login" 
		<?php
		if ( $allow_discord_login == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset>
  </td>
	  </tr>           
	<tr>
		<th scope="row"><?php esc_html_e( 'How many times a failed API call should get re-try', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_learnpress_retry_api_count" type="number" min="1" id="ets_learnpress_retry_api_count" value="
		<?php
		if ( isset( $retry_api_count ) ) {
			echo esc_attr( intval( $retry_api_count ) );
		} else {
			echo 1; }
		?>
		">
		</fieldset></td>
	  </tr> 
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue concurrency', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="set_job_cnrc" type="number" min="1" id="set_job_cnrc" value="
		<?php
		if ( isset( $set_job_cnrc ) ) {
			echo esc_attr( intval( $set_job_cnrc ) );
		} else {
			echo 1; }
		?>
		">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue batch size', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="set_job_q_batch_size" type="number" min="1" id="set_job_q_batch_size" value="
		<?php
		if ( isset( $set_job_q_batch_size ) ) {
			echo esc_attr( intval( $set_job_q_batch_size ) );
		} else {
			echo 10; }
		?>
		">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Log API calls response (For debugging purpose)', 'connect-learnpress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="log_api_res" type="checkbox" id="log_api_res" 
		<?php
		if ( $log_api_res == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	
	</tbody>
  </table>
  <div class="bottom-btn">
	<button type="submit" name="adv_submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-learnpress-discord-addon' ); ?>
	</button>
  </div>
</form>
