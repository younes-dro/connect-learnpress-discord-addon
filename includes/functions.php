<?php
/*
* common functions file.
*/

/**
 * 
 */
function ets_learnpress_discord_pages_list( $ets_learnpress_discord_redirect_page_id ){
    $args = array(
    'sort_order' => 'asc',
    'sort_column' => 'post_title',
    'hierarchical' => 1,
    'exclude' => '',
    'include' => '',
    'meta_key' => '',
    'meta_value' => '',
    'exclude_tree' => '',
    'number' => '',
    'offset' => 0,
    'post_type' => 'page',
    'post_status' => 'publish'
        ); 
    $pages = get_pages($args);
       
   
    $options = '<option value="" disabled>-</option>';
	if ( is_array( $pages ) ) {
		foreach( $pages as $page ){ 
			$selected = ( esc_attr( $page->ID ) === $ets_learnpress_discord_redirect_page_id  ) ? ' selected="selected"' : '';
			$options .= '<option data-page-url="' . ets_get_learnpress_discord_formated_discord_redirect_url ( $page->ID ) .'" value="' . esc_attr( $page->ID ) . '" '. $selected .'> ' . $page->post_title . ' </option>';
		}
	}
	return $options;
}

/**
 * 
 * @param INT $page_id
 * @return string
 */
function ets_get_learnpress_discord_formated_discord_redirect_url( $page_id ) {
    $url = esc_url( get_permalink( $page_id ) );
    
	$parsed = parse_url( $url, PHP_URL_QUERY );
	if ( $parsed === null ) {
		return $url .= '?via=learnpress-discord';
	} else {
		if ( stristr( $url, 'via=learnpress-discord' ) !== false ) {
			return $url;
		} else {
			return $url .= '&via=learnpress-discord';
		}
	}
}

/**
 * To check settings values saved or not
 *
 * @param NONE
 * @return BOOL $status
 */
function learnpress_discord_check_saved_settings_status() {
	$ets_learnpress_discord_client_id     = get_option( 'ets_learnpress_discord_client_id' );
	$ets_learnpress_discord_client_secret = get_option( 'ets_learnpress_discord_client_secret' );
	$ets_learnpress_discord_bot_token     = get_option( 'ets_learnpress_discord_bot_token' );
	$ets_learnpress_discord_redirect_url  = get_option( 'ets_learnpress_discord_redirect_url' );
	$ets_learnpress_discord_server_id      = get_option( 'ets_learnpress_discord_server_id' );

	if ( $ets_learnpress_discord_client_id && $ets_learnpress_discord_client_secret && $ets_learnpress_discord_bot_token && $ets_learnpress_discord_redirect_url && $ets_learnpress_discord_server_id ) {
			$status = true;
	} else {
			 $status = false;
	}

		 return $status;
}

/**
 * Get student's courses ids
 *
 * @param INT $user_id
 * @return ARRAY|NULL $curr_course_id
 */
function ets_learnpress_discord_get_student_courses_id( $user_id = 0 ) {
    
	global $wpdb;
	$table_user_items = $wpdb->prefix . 'learnpress_user_items';

	$list_courses = $wpdb->prepare( "SELECT item_id FROM `$table_user_items` WHERE user_id = %d", $user_id );
	$user_courses = $wpdb->get_results( $list_courses , ARRAY_A );

        
	if ( is_array( $user_courses ) ) {  
		$result = [];
		foreach ( $user_courses as $key => $course ) {
			array_push( $result, $course['item_id'] );
		}
		return $result;
	} else {
		return null;
	}    

}

/**
 * Get current screen URL
 *
 * @param NONE
 * @return STRING $url
 */
function ets_learnpress_discord_get_current_screen_url() {
	$parts           = parse_url( home_url() );
	$current_uri = "{$parts['scheme']}://{$parts['host']}" . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) . add_query_arg( null, null );
	
        return $current_uri;
}
/**
 * Check API call response and detect conditions which can cause of action failure and retry should be attemped.
 *
 * @param ARRAY|OBJECT $api_response
 * @param BOOLEAN
 */
function ets_learnpress_discord_check_api_errors( $api_response ) {
	// check if response code is a WordPress error.
	if ( is_wp_error( $api_response ) ) {
		return true;
	}

	// First Check if response contain codes which should not get re-try.
	$body = json_decode( wp_remote_retrieve_body( $api_response ), true );
	if ( isset( $body['code'] ) && in_array( $body['code'], LEARNPRESS_DISCORD_DONOT_RETRY_THESE_API_CODES ) ) {
		return false;
	}

	$response_code = strval( $api_response['response']['code'] );
	if ( isset( $api_response['response']['code'] ) && in_array( $response_code, LEARNPRESS_DISCORD_DONOT_RETRY_HTTP_CODES ) ) {
		return false;
	}

	// check if response code is in the range of HTTP error.
	if ( ( 400 <= absint( $response_code ) ) && ( absint( $response_code ) <= 599 ) ) {
		return true;
	}
}
/**
 * Get the highest available last attempt schedule time
 */

function ets_learnpress_discord_get_highest_last_attempt_timestamp() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.last_attempt_gmt FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s ORDER BY aa.last_attempt_gmt DESC limit 1', LEARNPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return strtotime( $result['0']['last_attempt_gmt'] );
	} else {
		return false;
	}
}

/**
 * Get randon integer between a predefined range.
 *
 * @param INT $add_upon
 */
function ets_learnpress_discord_get_random_timestamp( $add_upon = '' ) {
	if ( $add_upon != '' && $add_upon !== false ) {
		return $add_upon + random_int( 5, 15 );
	} else {
		return strtotime( 'now' ) + random_int( 5, 15 );
	}
}

/**
 * Log API call response
 *
 * @param INT          $user_id
 * @param STRING       $api_url
 * @param ARRAY        $api_args
 * @param ARRAY|OBJECT $api_response
 */
function ets_learnpress_discord_log_api_response( $user_id, $api_url = '', $api_args = array(), $api_response = '' ) {
	$log_api_response = get_option( 'ets_learnpress_discord_log_api_response' );
	if ( $log_api_response == true ) {
		$log_string  = '==>' . $api_url;
		$log_string .= '-::-' . serialize( $api_args );
		$log_string .= '-::-' . serialize( $api_response );

		$logs = new LearnPress_Discord_Add_On_Logs();
		$logs->write_api_response_logs( $log_string, $user_id );
	}
}
/**
 * Get how many times a hook is failed in a particular day.
 *
 * @param STRING $hook
 */
function ets_learnpress_discord_count_of_hooks_failures( $hook ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT count(last_attempt_gmt) as hook_failed_count FROM ' . $wpdb->prefix . 'actionscheduler_actions WHERE `hook`=%s AND status="failed" AND DATE(last_attempt_gmt) = %s', $hook, date( 'Y-m-d' ) ), ARRAY_A );
	
        if ( ! empty( $result ) ) {
		return $result['0']['hook_failed_count'];
	} else {
		return false;
	}
}

/**
 * Get pending jobs 
 */
function ets_learnpress_discord_get_all_pending_actions() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.* FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s AND aa.status="pending" ', LEARNPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result['0'];
	} else {
		return false;
	}
}

function ets_learnpress_discord_get_all_failed_actions(){
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.action_id, aa.hook, ag.slug AS as_group FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id=ag.group_id WHERE  ag.slug=%s AND aa.status = "failed" ' , LEARNPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result ;
	} else {
		return false;
	}        
}
/**
 * Get formatted message to send in DM
 *
 * @param INT $user_id
 * Merge fields: [LP_COURSES], [LP_STUDENT_NAME], [LD_STUDENT_EMAIL], [SITE_URL], [BLOG_NAME]
 */
function ets_learnpress_discord_get_formatted_dm( $user_id, $courses, $message ) {
    
	$user_obj    = get_user_by( 'id', $user_id );
	$STUDENT_USERNAME = $user_obj->user_login;
	$STUDENT_EMAIL    = $user_obj->user_email;
	$SITE_URL  = get_bloginfo( 'url' );
	$BLOG_NAME = get_bloginfo( 'name' );
	$COURSES = '';        
	if ( is_array( $courses ) ){
		$args_courses = array(
		'orderby'          => 'title',
		'order'            => 'ASC',
		'numberposts' => count( $courses ),
		'post_type'   => 'lp_course',
		'post__in' => $courses
		);

		$enrolled_courses = get_posts( $args_courses );
		$lastKeyCourse = array_key_last($enrolled_courses);
		$commas = ', ';
		foreach ( $enrolled_courses as $key => $course ) {
			if ( $lastKeyCourse === $key )  
				$commas = ' ' ;
				$COURSES .= esc_html( $course->post_title ). $commas;
		}            
        }



		$find    = array(
			'[LP_COURSES]',
			'[LP_STUDENT_NAME]',
			'[LP_STUDENT_EMAIL]',
			'[SITE_URL]',
			'[BLOG_NAME]'
		);
		$replace = array(
			$COURSES,                    
			$STUDENT_USERNAME,
			$STUDENT_EMAIL,
			$SITE_URL,
			$BLOG_NAME
		);

		return str_replace( $find, $replace, $message );

}
function ets_learnpress_discord_update_bot_name_option ( ){
 
	$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_bot_token' ) ) );
	if ( $discord_bot_token ) {
            
                $discod_current_user_api = LEARNPRESS_DISCORD_API_URL . 'users/@me';
                
		$app_args              = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);                
                
		$app_response = wp_remote_post( $discod_current_user_api, $app_args );

		$response_arr =  json_decode ( wp_remote_retrieve_body( $app_response ), true );
                
		if( is_array( $response_arr ) && array_key_exists( 'username', $response_arr ) ){
                    
			update_option( 'ets_learnpress_discord_connected_bot_name', $response_arr ['username'] );
		}else{
			delete_option( 'ets_learnpress_discord_connected_bot_name' );
                }
                        
                
	}

}
function ets_learnpress_discord_remove_usermeta ( $user_id ){
 
	global $wpdb;
        
        
	$usermeta_table = $wpdb->prefix . "usermeta";
	$usermeta_sql = "DELETE FROM " . $usermeta_table . " WHERE `user_id` = %d AND  `meta_key` LIKE '_ets_learnpress_discord%'; ";
	$delete_usermeta_sql = $wpdb->prepare( $usermeta_sql, $user_id );
	$wpdb->query( $delete_usermeta_sql );
             
}
