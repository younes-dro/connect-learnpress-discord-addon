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
    foreach($pages as $page){ 
        $selected = ( esc_attr( $page->ID ) === $ets_learnpress_discord_redirect_page_id  ) ? ' selected="selected"' : '';
        $options .= '<option value="' . esc_attr( $page->ID ) . '" '. $selected .'> ' . $page->post_title . ' </option>';
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

        
	if ( $user_courses ) {  
		$result = [];
		foreach ($user_courses as $key => $course) {
			array_push($result, $course['item_id']);
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

