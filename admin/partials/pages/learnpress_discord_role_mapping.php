<?php

$args_courses = array(
    'orderby'          => 'title',
    'order'            => 'ASC',
    'post_status'    => 'publish',
    'numberposts' => -1,
    'post_type'   => 'lp_course'
);
$courses = get_posts( $args_courses );

$default_role        = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_default_role_id' ) ) );
$allow_none_student  = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_allow_none_student' ) ) );
?>
<div class="notice notice-warning ets-notice">
  <p><i class='fas fa-info'></i> <?php echo __( 'Drag and Drop the Discord Roles over to the LearnPress Courses', 'learnpress-discord-addon' ); ?></p>
</div>

<div class="row-container">
  <div class="ets-column learnpress-discord-roles-col">
	<h2><?php echo __( 'Discord Roles', 'learnpress-discord-addon' ); ?></h2>
	<hr>
	<div class="learnpress-discord-roles">
	  <span class="spinner"></span>
	</div>
  </div>
  <div class="ets-column">
	<h2><?php echo __( 'Courses', 'learnpress-discord-addon' ); ?></h2>
	<hr>
	<div class="learnpress-discord-courses">
	<?php
	foreach ( $courses as $course ) {
		
			?>
		  <div class="makeMeDroppable" data-learnpress_course_id="<?php echo esc_attr( $course->ID ); ?>" ><span><?php echo esc_html( $course->post_title ); ?></span></div>
			<?php
		
	}
	?>
	</div>
  </div>
</div>
<form method="post" action="<?php echo get_site_url().'/wp-admin/admin-post.php' ?>">
 <input type="hidden" name="action" value="learnpress_discord_save_role_mapping">
 <input type="hidden" name="current_url" value="<?php echo ets_learnpress_discord_get_current_screen_url()?>">   
  <table class="form-table" role="presentation">
	<tbody>
	  <tr>
		<th scope="row"><label for="learnpress-defaultRole"><?php echo __( 'Default Role', 'learnpress-discord-addon' ); ?></label></th>
		<td>
		  <?php wp_nonce_field( 'learnpress_discord_role_mappings_nonce', 'ets_learnpress_discord_role_mappings_nonce' ); ?>
		  <input type="hidden" id="selected_default_role" value="<?php echo esc_attr( $default_role ); ?>">
		  <select id="learnpress-defaultRole" name="learnpress_defaultRole">
			<option value="none"><?php echo __( '-None-', 'learnpress-discord-addon' ); ?></option>
		  </select>
		  <p class="description"><?php echo __( 'This Role will be assigned to all', 'learnpress-discord-addon' ); ?></p>
		</td>
	  </tr>
	  <tr>
		<th scope="row"><label><?php echo __( 'Allow non-student', 'learnpress-discord-addon' ); ?></label></th>
		<td>
		  <fieldset>
		  <label><input type="radio" name="allow_none_student" value="yes"  
		  <?php
			if ( $allow_none_student == 'yes' ) {
				echo 'checked="checked"'; }
			?>
			 > <span><?php echo __( 'Yes', 'learnpress-discord-addon' ); ?></span></label><br>
		  <label><input type="radio" name="allow_none_student" value="no" 
		  <?php
			if ( empty( $allow_none_student ) || $allow_none_student == 'no' ) {
				echo 'checked="checked"'; }
			?>
			 > <span><?php echo __( 'No', 'learnpress-discord-addon' ); ?></span></label>
		  <p class="description"><?php echo __( 'Display connect button to normal wordpress site users having LearnPress account', 'learnpress-discord-addon' ); ?></p>
		  </fieldset>
		</td>
	  </tr>          

	</tbody>
  </table>
	<br>
  <div class="mapping-json">
	<textarea id="ets_learnpress_mapping_json_val" name="ets_learnpress_discord_role_mapping">
	<?php
	if ( isset( $ets_discord_roles ) ) {
		echo stripslashes( esc_html( $ets_discord_roles ));}
	?>
	</textarea>
  </div>
  <div class="bottom-btn">
	<button type="submit" name="submit" value="ets_submit" class="ets-submit ets-btn-submit ets-bg-green">
	  <?php echo __( 'Save Settings', 'learnpress-discord-addon' ); ?>
	</button>
	<button id="revertMapping" name="flush" class="ets-submit ets-btn-submit ets-bg-red">
	  <?php echo __( 'Flush Mappings', 'learnpress-discord-addon' ); ?>
	</button>
  </div>
</form>