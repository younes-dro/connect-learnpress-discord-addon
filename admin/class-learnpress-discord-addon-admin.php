<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Learnpress_Discord_Addon
 * @subpackage Learnpress_Discord_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Learnpress_Discord_Addon
 * @subpackage Learnpress_Discord_Addon/admin
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Learnpress_Discord_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance of Learnpress_Discord_Addon_Public class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Learnpress_Discord_Addon_Public
	 */
	private $learnpress_discord_public_instance; 
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $learnpress_discord_public_instance ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->learnpress_discord_public_instance = $learnpress_discord_public_instance;                
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Learnpress_Discord_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Learnpress_Discord_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name .'-select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . 'discord_tabs_css', plugin_dir_url( __FILE__ ) . 'css/skeletabs.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/learnpress-discord-addon-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Learnpress_Discord_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Learnpress_Discord_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( $this->plugin_name . '-select2',  plugin_dir_url( __FILE__ ) . 'js/select2.js', array( 'jquery' ), $this->version, false );
            
		wp_register_script( $this->plugin_name . '-tabs-js', plugin_dir_url( __FILE__ ) . 'js/skeletabs.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/learnpress-discord-addon-admin.js', array( 'jquery' ), $this->version, false );                
		$script_params = array(
			'admin_ajax'                       => admin_url( 'admin-ajax.php' ),
			'permissions_const'                => LEARNPRESS_DISCORD_BOT_PERMISSIONS,
			'is_admin'                         => is_admin(),
			'ets_learnpress_discord_nonce' => wp_create_nonce( 'ets-learnpress-discord-ajax-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'etsLearnPressParams', $script_params );

	}
        
	/**
	 * Method to add discord setting sub-menu under top level menu of LearnPress lms
	 *
	 * @since    1.0.0
	 */
	public function ets_learnpress_discord_add_settings_menu() {
		add_submenu_page( 'learn_press', __( 'Discord Settings', 'learnpress-discord-addon' ), __( 'Discord Settings', 'learnpress-discord-addon' ), 'manage_options', 'learnpress-discord-settings', array( $this, 'ets_learnpress_discord_setting_page' ) );
	}
        
	/**
	 * Callback to Display settings page
	 *
	 * @since    1.0.0
	 */        
	public function ets_learnpress_discord_setting_page(){
    
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		wp_enqueue_style( $this->plugin_name .'-select2' );                
		wp_enqueue_style( $this->plugin_name . 'discord_tabs_css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->plugin_name );                
		wp_enqueue_script( $this->plugin_name . '-select2' );
		wp_enqueue_script( $this->plugin_name . '-tabs-js' );                
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );  
                
		require_once LEARNPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/learnpress-discord-addon-admin-display.php';                
	}
        
	/**
	 * Callback to Connect to bot
	 *
	 * @since    1.0.0
	 */
	public function ets_learnpress_discord_connect_to_bot() {

//		if ( current_user_can( 'administrator' ) && isset( $_GET['action'] ) && $_GET['action'] == 'learnpress-discord-connect-to-bot' ) {
//			$params                    = array(
//				'client_id'   => sanitize_text_field( trim( get_option( 'ets_learnpress_discord_client_id' ) ) ),
//				'permissions' => LEARNPRESS_DISCORD_BOT_PERMISSIONS,
//				'scope'       => 'bot',
//				'guild_id'    => sanitize_text_field( trim( get_option( 'ets_learnpress_discord_server_id' ) ) ),
//			);
//			$discord_authorise_api_url = LEARNPRESS_DISCORD_API_URL . 'oauth2/authorize?' . http_build_query( $params );
//
//			wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
//			exit;
//		} 
        }
        
	/*
	Save application details
	*/
	public function ets_learnpress_discord_application_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_learnpress_discord_client_id = isset( $_POST['ets_learnpress_discord_client_id'] ) ? sanitize_text_field( trim( $_POST['ets_learnpress_discord_client_id'] ) ) : '';

		$ets_learnpress_discord_client_secret = isset( $_POST['ets_learnpress_discord_client_secret'] ) ? sanitize_text_field( trim( $_POST['ets_learnpress_discord_client_secret'] ) ) : '';

		$ets_learnpress_discord_bot_token = isset( $_POST['ets_learnpress_discord_bot_token'] ) ? sanitize_text_field( trim( $_POST['ets_learnpress_discord_bot_token'] ) ) : '';

		$ets_learnpress_discord_redirect_url = isset( $_POST['ets_learnpress_discord_redirect_url'] ) ? sanitize_text_field( trim( $_POST['ets_learnpress_discord_redirect_url'] ) ) : '';
                
                $ets_learnpress_discord_redirect_page_id  = isset( $_POST['ets_learnpress_discord_redirect_page_id'] ) ? sanitize_text_field( trim( $_POST['ets_learnpress_discord_redirect_page_id'] ) ) : '';

		$ets_learnpress_discord_server_id = isset( $_POST['ets_learnpress_discord_server_id'] ) ? sanitize_text_field( trim( $_POST['ets_learnpress_discord_server_id'] ) ) : '';
                
		$ets_current_url = sanitize_text_field( trim( $_POST['current_url'] ) ) ;                

		if ( isset( $_POST['submit'] ) ) {
			if ( isset( $_POST['ets_learnpress_discord_save_settings'] ) && wp_verify_nonce( $_POST['ets_learnpress_discord_save_settings'], 'save_learnpress_discord_general_settings' ) ) {
				if ( $ets_learnpress_discord_client_id ) {
					update_option( 'ets_learnpress_discord_client_id', $ets_learnpress_discord_client_id );
				}

				if ( $ets_learnpress_discord_client_secret ) {
					update_option( 'ets_learnpress_discord_client_secret', $ets_learnpress_discord_client_secret );
				}

				if ( $ets_learnpress_discord_bot_token ) {
					update_option( 'ets_learnpress_discord_bot_token', $ets_learnpress_discord_bot_token );
				}

				if ( $ets_learnpress_discord_redirect_url ) {
					update_option( 'ets_learnpress_discord_redirect_page_id', $ets_learnpress_discord_redirect_url );					
					$ets_learnpress_discord_redirect_url = ets_get_learnpress_discord_formated_discord_redirect_url( $ets_learnpress_discord_redirect_url );
					update_option( 'ets_learnpress_discord_redirect_url', $ets_learnpress_discord_redirect_url );
                                        
				}

				if ( $ets_learnpress_discord_server_id ) {
					update_option( 'ets_learnpress_discord_server_id', $ets_learnpress_discord_server_id );
				}
				/**
                                 * Call function to save bot name option 
				 */
				ets_learnpress_discord_update_bot_name_option();
				$message = 'Your settings are saved successfully.';

					
					
				$pre_location = $ets_current_url . '&save_settings_msg=' . $message . '#ets_learnpress_application_details';
				wp_safe_redirect( $pre_location );

			}
		}
	}
       
	/**
	 * Load discord roles from server
	 *
	 * @return OBJECT REST API response
	 */
	public function ets_learnpress_discord_load_discord_roles() {

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_learnpress_discord_nonce'], 'ets-learnpress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$user_id = get_current_user_id();

		$guild_id          = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_server_id' ) ) );
		$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_bot_token' ) ) );
		$client_id          = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_client_id' ) ) );                                                
		if ( $guild_id && $discord_bot_token ) {
			$discod_server_roles_api = LEARNPRESS_DISCORD_API_URL . 'guilds/' . $guild_id . '/roles';
			$guild_args              = array(
				'method'  => 'GET',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bot ' . $discord_bot_token,
				),
			);
			$guild_response          = wp_remote_post( $discod_server_roles_api, $guild_args );

			//ets_learnpress_discord_log_api_response( $user_id, $discod_server_roles_api, $guild_args, $guild_response );

			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );

			if ( is_array( $response_arr ) && ! empty( $response_arr ) ) {
				if ( array_key_exists( 'code', $response_arr ) || array_key_exists( 'error', $response_arr ) ) {
					Learnpress_Discord_Add_On_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				} else {
					$response_arr['previous_mapping'] = get_option( 'ets_learnpress_discord_role_mapping' );

					$discord_roles = array();
					foreach ( $response_arr as $key => $value ) {
						$isbot = false;
						if ( is_array( $value ) ) {
							if ( array_key_exists( 'tags', $value ) ) {
								if ( array_key_exists( 'bot_id', $value['tags'] ) ) {
									$isbot = true;
									if( $value['tags']['bot_id'] === $client_id ){
										$response_arr['bot_connected'] = 'yes';
									}                                                                        
								}
							}
						}
						if ( $key != 'previous_mapping' && $isbot == false && isset( $value['name'] ) && $value['name'] != '@everyone' ) {
							$discord_roles[ $value['id'] ] = $value['name'];
							$discord_roles_color[ $value['id'] ] = $value['color'];                                                        
						}
					}
					update_option( 'ets_learnpress_discord_all_roles', serialize( $discord_roles ) );
					update_option( 'ets_learnpress_discord_roles_color', serialize( $discord_roles_color ) );                                                                                
				}
			}
				return wp_send_json( $response_arr );
		}

				exit();

	}
        
	/**
	 * Save Role mapping settings
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_learnpress_discord_save_role_mapping() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_discord_roles = isset( $_POST['ets_learnpress_discord_role_mapping'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_role_mapping'] ) ) : '';

		$ets_learnpress_discord_default_role_id = isset( $_POST['learnpress_defaultRole'] ) ? sanitize_textarea_field( trim( $_POST['learnpress_defaultRole'] ) ) : '';
		$allow_none_student = isset( $_POST['allow_none_student'] ) ? sanitize_textarea_field( trim( $_POST['allow_none_student'] ) ) : '';
		$ets_current_url = sanitize_text_field( trim( $_POST['current_url'] ) ) ;                                
		$ets_discord_roles   = stripslashes( $ets_discord_roles );
		$save_mapping_status = update_option( 'ets_learnpress_discord_role_mapping', $ets_discord_roles );
		if ( isset( $_POST['ets_learnpress_discord_role_mappings_nonce'] ) && wp_verify_nonce( $_POST['ets_learnpress_discord_role_mappings_nonce'], 'learnpress_discord_role_mappings_nonce' ) ) {
			if ( ( $save_mapping_status || isset( $_POST['ets_learnpress_discord_role_mapping'] ) ) && ! isset( $_POST['flush'] ) ) {
				if ( $ets_learnpress_discord_default_role_id ) {
					update_option( 'ets_learnpress_discord_default_role_id', $ets_learnpress_discord_default_role_id );
				}
				if ( $allow_none_student ) {
					update_option( 'ets_learnpress_discord_allow_none_student', $allow_none_student );
				}                                

				$message = 'Your mappings are saved successfully.';
			}
			if ( isset( $_POST['flush'] ) ) {
				delete_option( 'ets_learnpress_discord_role_mapping' );
				delete_option( 'ets_learnpress_discord_default_role_id' );

				$message = 'Your settings flushed successfully.';
			}
			$pre_location = $ets_current_url . '&save_settings_msg=' . $message . '#ets_learnpress_discord_role_mapping';
			wp_safe_redirect( $pre_location );
		}
	}
        
	/**
	 * Save advanced settings
	 *
	 * @param NONE
	 * @return NONE
	 */        
	public function ets_learnpress_discord_save_advance_settings() {

		if ( ! current_user_can( 'administrator' ) || ! wp_verify_nonce( $_POST['ets_learnpress_discord_advance_settings_nonce'], 'learnpress_discord_advance_settings_nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

			$ets_learnpress_discord_send_welcome_dm = isset( $_POST['ets_learnpress_discord_send_welcome_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_send_welcome_dm'] ) ) : '';
			$ets_learnpress_discord_welcome_message = isset( $_POST['ets_learnpress_discord_welcome_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_welcome_message'] ) ) : '';
			$ets_learnpress_discord_send_course_complete_dm = isset( $_POST['ets_learnpress_discord_send_course_complete_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_send_course_complete_dm'] ) ) : '';                        
			$ets_learnpress_discord_course_complete_message = isset( $_POST['ets_learnpress_discord_course_complete_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_course_complete_message'] ) ) : '';                                                
			$ets_learnpress_discord_send_lesson_complete_dm = isset( $_POST['ets_learnpress_discord_send_lesson_complete_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_send_lesson_complete_dm'] ) ) : '';                        
			$ets_learnpress_discord_lesson_complete_message = isset( $_POST['ets_learnpress_discord_lesson_complete_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_lesson_complete_message'] ) ) : '';                                                


//			$ets_learnpress_discord_send_quiz_complete_dm = isset( $_POST['ets_learnpress_discord_send_quiz_complete_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_send_quiz_complete_dm'] ) ) : '';                                                                        
//			$ets_learnpress_discord_quiz_complete_message = isset( $_POST['ets_learnpress_discord_quiz_complete_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_quiz_complete_message'] ) ) : '';                                                                                                                        
			$retry_failed_api                           = isset( $_POST['retry_failed_api'] ) ? sanitize_textarea_field( trim( $_POST['retry_failed_api'] ) ) : '';
			$kick_upon_disconnect                       = isset( $_POST['kick_upon_disconnect'] ) ? sanitize_textarea_field( trim( $_POST['kick_upon_disconnect'] ) ) : '';                        
			$retry_api_count                            = isset( $_POST['ets_learnpress_retry_api_count'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_retry_api_count'] ) ) : '';
			$set_job_cnrc                               = isset( $_POST['set_job_cnrc'] ) ? sanitize_textarea_field( trim( $_POST['set_job_cnrc'] ) ) : '';
			$set_job_q_batch_size                       = isset( $_POST['set_job_q_batch_size'] ) ? sanitize_textarea_field( trim( $_POST['set_job_q_batch_size'] ) ) : '';
			$log_api_res                                = isset( $_POST['log_api_res'] ) ? sanitize_textarea_field( trim( $_POST['log_api_res'] ) ) : '';
			$embed_messaging_feature                    = isset( $_POST['embed_messaging_feature'] ) ? sanitize_textarea_field( trim( $_POST['embed_messaging_feature'] ) ) : '';                                                
			$ets_current_url = sanitize_text_field( trim( $_POST['current_url'] ) ) ;
			$allow_discord_login                        = isset( $_POST['allow_discord_login'] ) ? sanitize_textarea_field( trim( $_POST['allow_discord_login'] ) ) : '';                                                                        

		if ( isset( $_POST['ets_learnpress_discord_advance_settings_nonce'] ) && wp_verify_nonce( $_POST['ets_learnpress_discord_advance_settings_nonce'], 'learnpress_discord_advance_settings_nonce' ) ) {
			if ( isset( $_POST['adv_submit'] ) ) {

				if ( isset( $_POST['ets_learnpress_discord_send_welcome_dm'] ) ) {
					update_option( 'ets_learnpress_discord_send_welcome_dm', true );
				} else {
					update_option( 'ets_learnpress_discord_send_welcome_dm', false );
				}
				if ( isset( $_POST['ets_learnpress_discord_welcome_message'] ) && $_POST['ets_learnpress_discord_welcome_message'] != '' ) {
					update_option( 'ets_learnpress_discord_welcome_message', $ets_learnpress_discord_welcome_message );
				} else {
					update_option( 'ets_learnpress_discord_welcome_message', '' );
				}
				if ( isset( $_POST['ets_learnpress_discord_send_course_complete_dm'] ) ) {
					update_option( 'ets_learnpress_discord_send_course_complete_dm', true );
				} else {
					update_option( 'ets_learnpress_discord_send_course_complete_dm', false );
				}
				if ( isset( $_POST['ets_learnpress_discord_course_complete_message'] ) && $_POST['ets_learnpress_discord_course_complete_message'] != '' ) {
					update_option( 'ets_learnpress_discord_course_complete_message', $ets_learnpress_discord_course_complete_message );
				} else {
					update_option( 'ets_learnpress_discord_course_complete_message', '' );
				}
				if ( isset( $_POST['ets_learnpress_discord_send_lesson_complete_dm'] ) ) {
					update_option( 'ets_learnpress_discord_send_lesson_complete_dm', true );
				} else {
					update_option( 'ets_learnpress_discord_send_lesson_complete_dm', false );
				}
				if ( isset( $_POST['ets_learnpress_discord_lesson_complete_message'] ) && $_POST['ets_learnpress_discord_lesson_complete_message'] != '' ) {
					update_option( 'ets_learnpress_discord_lesson_complete_message', $ets_learnpress_discord_lesson_complete_message );
				} else {
					update_option( 'ets_learnpress_discord_lesson_complete_message', '' );
				}
//				if ( isset( $_POST['ets_learnpress_discord_send_quiz_complete_dm'] ) ) {
//					update_option( 'ets_learnpress_discord_send_quiz_complete_dm', true );
//				} else {
//					update_option( 'ets_learnpress_discord_send_quiz_complete_dm', false );
//				}
//				if ( isset( $_POST['ets_learnpress_discord_quiz_complete_message'] ) && $_POST['ets_learnpress_discord_quiz_complete_message'] != '' ) {
//					update_option( 'ets_learnpress_discord_quiz_complete_message', $ets_learnpress_discord_quiz_complete_message );
//				} else {
//					update_option( 'ets_learnpress_discord_quiz_complete_message', '' );
//				}                                
				if ( isset( $_POST['retry_failed_api'] ) ) {
					update_option( 'ets_learnpress_discord_retry_failed_api', true );
				} else {
					update_option( 'ets_learnpress_discord_retry_failed_api', false );
				}
				if ( isset( $_POST['kick_upon_disconnect'] ) ) {
					update_option( 'ets_learnpress_discord_kick_upon_disconnect', true );
				} else {
					update_option( 'ets_learnpress_discord_kick_upon_disconnect', false );
				}                                
				if ( isset( $_POST['ets_learnpress_retry_api_count'] ) ) {
					if ( $retry_api_count < 1 ) {
						update_option( 'ets_learnpress_discord_retry_api_count', 1 );
					} else {
						update_option( 'ets_learnpress_discord_retry_api_count', $retry_api_count );
					}
				}
				if ( isset( $_POST['set_job_cnrc'] ) ) {
					if ( $set_job_cnrc < 1 ) {
						update_option( 'ets_learnpress_discord_job_queue_concurrency', 1 );
					} else {
						update_option( 'ets_learnpress_discord_job_queue_concurrency', $set_job_cnrc );
					}
				}
				if ( isset( $_POST['set_job_q_batch_size'] ) ) {
					if ( $set_job_q_batch_size < 1 ) {
						update_option( 'ets_learnpress_discord_job_queue_batch_size', 1 );
					} else {
						update_option( 'ets_learnpress_discord_job_queue_batch_size', $set_job_q_batch_size );
					}
				}
				if ( isset( $_POST['log_api_res'] ) ) {
					update_option( 'ets_learnpress_discord_log_api_response', true );
				} else {
					update_option( 'ets_learnpress_discord_log_api_response', false );
				}
				if ( isset( $_POST['embed_messaging_feature'] ) ) {
					update_option( 'ets_learnpress_discord_embed_messaging_feature', true );
				} else {
					update_option( 'ets_learnpress_discord_embed_messaging_feature', false );
				}
				if ( isset( $_POST['allow_discord_login'] ) ) {
					update_option( 'ets_learnpress_discord_allow_discord_login', true );
				} else {
					update_option( 'ets_learnpress_discord_allow_discord_login', false );
				}
				$message = 'Your settings are saved successfully.';

				$pre_location = $ets_current_url . '&save_settings_msg=' . $message . '#ets_learnpress_discord_advanced';
				wp_safe_redirect( $pre_location );

			}
		}

	}
	/**
	 * Save appearance settings
	 *
	 * @param NONE
	 * @return NONE
	 */        
	public function ets_learnpress_discord_save_appearance_settings() {

		if ( ! current_user_can( 'administrator' ) || ! wp_verify_nonce( $_POST['ets_learnpress_discord_appearance_settings_nonce'], 'learnpress_discord_appearance_settings_nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}  
		$ets_learnpress_discord_connect_button_bg_color = isset( $_POST['ets_learnpress_discord_connect_button_bg_color'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_connect_button_bg_color'] ) ) : '';
		$ets_learnpress_discord_disconnect_button_bg_color = isset( $_POST['ets_learnpress_discord_disconnect_button_bg_color'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_disconnect_button_bg_color'] ) ) : '';                
		$ets_learnpress_discord_loggedin_button_text = isset( $_POST['ets_learnpress_discord_loggedin_button_text'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_loggedin_button_text'] ) ) : '';                                
		$ets_learnpress_discord_non_login_button_text = isset( $_POST['ets_learnpress_discord_non_login_button_text'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_non_login_button_text'] ) ) : '';                                                
		$ets_learnpress_discord_disconnect_button_text = isset( $_POST['ets_learnpress_discord_disconnect_button_text'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_disconnect_button_text'] ) ) : '';                                                                
		$ets_current_url = sanitize_text_field( trim( $_POST['current_url'] ) ) ;                                                        

		$ets_learnpress_discord_send_welcome_dm = isset( $_POST['ets_learnpress_discord_send_welcome_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_learnpress_discord_send_welcome_dm'] ) ) : '';
		if ( isset( $_POST['ets_learnpress_discord_appearance_settings_nonce'] ) && wp_verify_nonce( $_POST['ets_learnpress_discord_appearance_settings_nonce'], 'learnpress_discord_appearance_settings_nonce' ) ) {
			if ( isset( $_POST['appearance_submit'] ) ) {

				if ( isset( $_POST['ets_learnpress_discord_connect_button_bg_color'] ) ) {
					update_option( 'ets_learnpress_discord_connect_button_bg_color', $ets_learnpress_discord_connect_button_bg_color );
				} else {
					update_option( 'ets_learnpress_discord_connect_button_bg_color', '' );
				}
				if ( isset( $_POST['ets_learnpress_discord_disconnect_button_bg_color'] ) ) {
					update_option( 'ets_learnpress_discord_disconnect_button_bg_color', $ets_learnpress_discord_disconnect_button_bg_color );
				} else {
					update_option( 'ets_learnpress_discord_disconnect_button_bg_color', '' );
				}                                
				if ( isset( $_POST['ets_learnpress_discord_loggedin_button_text'] ) ) {
					update_option( 'ets_learnpress_discord_loggedin_button_text', $ets_learnpress_discord_loggedin_button_text );
				} else {
					update_option( 'ets_learnpress_discord_loggedin_button_text', '' );
				}
				if ( isset( $_POST['ets_learnpress_discord_non_login_button_text'] ) ) {
					update_option( 'ets_learnpress_discord_non_login_button_text', $ets_learnpress_discord_non_login_button_text );
				} else {
					update_option( 'ets_learnpress_discord_non_login_button_text', '' );
				} 
				if ( isset( $_POST['ets_learnpress_discord_disconnect_button_text'] ) ) {
					update_option( 'ets_learnpress_discord_disconnect_button_text', $ets_learnpress_discord_disconnect_button_text );
				} else {
					update_option( 'ets_learnpress_discord_disconnect_button_text', '' );
				}                                

				$message = 'Your settings are saved successfully.';

				$pre_location = $ets_current_url . '&save_settings_msg=' . $message . '#ets_learnpress_discord_appearance';
				wp_safe_redirect( $pre_location );

			}
		}

	}         
	/**
	 * 
	 *
	 * @since    1.0.0
	 */        
	public function ets_learnpress_discord_user_course_enrolled( $course_item_ref_id,$course_id,$user_get_id ){
    
		if ( current_user_can( 'administrator' )  ||  is_user_logged_in()  ) {
			$this->learnpress_discord_public_instance->ets_learnpress_discord_update_course_access( $user_get_id, $course_id );                                    

		}else{
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();                    
		}

	}        

	/**
	 * Add learnpress Discord column to WP Users listing 
	 *
	 * @param array $columns 
	 * @return NONE
	 */        
	public function ets_learnpress_discord_add_learnpress_discord_column( $columns ) {
            
		$columns['ets_learnpress_discord_api'] = esc_html__( 'LearnPress Discord', 'learnpress-discord-addon' );
		return $columns;            
        }

	/**
	 * Display Run API button
	 *
	 * @param array $columns 
	 * @return NONE
	 */        
	public function ets_learnpress_discord_run_learnpress_discord_api( $value, $column_name, $user_id ) {
           
		if ( $column_name === 'ets_learnpress_discord_api' ){
                    	wp_enqueue_script( $this->plugin_name . '-tabs-js' );                
			wp_enqueue_script( $this->plugin_name );
			$access_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );
			if ( $access_token  ){
				return '<a href="#" data-user-id="' . $user_id  . '" class="ets-learnpress-discord-run-api" >' . esc_html__( 'RUN API', 'learnpress-discord-addon' ) . '</a><span class=" run-api spinner" ></span><div class="run-api-success"></div>';                            
			}
			return esc_html__( 'Not Connected', 'learnpress-discord-addon' );			
		}
		return $value;            
	}
	/**
	 * Run API 
	 *
	 * 
	 * @return NONE
	 */        
	public function ets_learnpress_discord_run_api(  ) {


		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_learnpress_discord_nonce'], 'ets-learnpress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
                
		$user_id = $_POST['ets_learnpress_discord_user_id'];
		$access_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );
		$refresh_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_refresh_token', true ) ) );                
		$ets_learnpress_discord_role_mapping = json_decode( get_option( 'ets_learnpress_discord_role_mapping' ), true );
		$default_role                       = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_default_role_id' ) ) );
		$last_default_role = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_last_default_role', true ) ) );                
		$student_courses = ets_learnpress_discord_get_student_courses_id( $user_id );
                
		if ( $access_token && $refresh_token && is_array( $ets_learnpress_discord_role_mapping ) && is_array( $student_courses ) ){
			foreach ( $student_courses as $course_id ) {                    
		
                            $student_role_for_course  = get_user_meta( $user_id,'_ets_learnpress_discord_role_id_for_' . $course_id , true);

                            if( $student_role_for_course && array_key_exists( 'learnpress_course_id_' . $course_id, $ets_learnpress_discord_role_mapping ) ){

                                    // Nothing to do;

                            }
                            if( $student_role_for_course && array_key_exists( 'learnpress_course_id_' . $course_id, $ets_learnpress_discord_role_mapping ) && $ets_learnpress_discord_role_mapping['learnpress_course_id_' . $course_id] != $student_role_for_course ){

                                    // Remove $student_role_for_course
                                    $old_role = $student_role_for_course;
                                    delete_user_meta( $user_id, '_ets_learnpress_discord_role_id_for_' . $course_id , $old_role );
                                    $this->learnpress_discord_public_instance->delete_discord_role( $user_id, $old_role );
                                    

                                    // Assign $ets_learnpress_discord_role_mapping['learnpress_course_id_' . $course_id]
                                    $new_role = $ets_learnpress_discord_role_mapping['learnpress_course_id_' . $course_id];
                                    update_user_meta( $user_id, '_ets_learnpress_discord_role_id_for_' . $course_id , $new_role );
                                    $this->learnpress_discord_public_instance->put_discord_role_api( $user_id, $new_role );
                                   

                            }                        

                            if( ! $student_role_for_course && array_key_exists( 'learnpress_course_id_' . $course_id, $ets_learnpress_discord_role_mapping ) ){

                                    $new_role = $ets_learnpress_discord_role_mapping['learnpress_course_id_' . $course_id];
                                    update_user_meta( $user_id, '_ets_learnpress_discord_role_id_for_' . $course_id , $new_role );
                                    $this->learnpress_discord_public_instance->put_discord_role_api( $user_id, $new_role );
                                    
                            }

                            if ( $student_role_for_course && ! array_key_exists( 'learnpress_course_id_' . $course_id, $ets_learnpress_discord_role_mapping ) ){

                                    $old_role = $student_role_for_course;
                                    delete_user_meta( $user_id, '_ets_learnpress_discord_role_id_for_' . $course_id , $old_role ); 
                                    $this->learnpress_discord_public_instance->delete_discord_role( $user_id, $old_role ); 
                                    
                            }
			}

		}
 		if ( $access_token && $refresh_token ){
                    if ( $default_role && $default_role != 'none' && $default_role === $last_default_role ){
                        //

                    }elseif ( $default_role && $default_role != 'none' && $default_role != $last_default_role  ) {

                            update_user_meta( $user_id, '_ets_learnpress_discord_last_default_role', $default_role );
                            $this->learnpress_discord_public_instance->delete_discord_role( $user_id, $last_default_role );
                            $this->learnpress_discord_public_instance->put_discord_role_api( $user_id, $default_role );
                            
                          
                    }else{

                            delete_user_meta( $user_id, '_ets_learnpress_discord_last_default_role' );
                            $this->learnpress_discord_public_instance->delete_discord_role( $user_id, $last_default_role );
                            
                    }                    
 		}                       
        
	exit();
           
        }

	/**
	 * Display Disconnect Discord button Profile User Page
	 *
	 */        
	public function ets_learnpress_discord_disconnect_user_button(  ) {
           
		if (  current_user_can( 'administrator' ) ) {
                    	wp_enqueue_script( $this->plugin_name . '-tabs-js' );                
			wp_enqueue_script( $this->plugin_name );
			$user_id =  ( isset( $_GET['user_id'] ) ) ? $_GET['user_id'] : get_current_user_id() ;
			$access_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );
			$refresh_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_refresh_token', true ) ) );
			$_ets_learnpress_discord_username = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_username', true ) ) );                                                
			if( $access_token && $refresh_token ){
				$DisConnect = '<h3>'.  esc_html__( 'Disconnect', 'learnpress-discord-addon' ).'</h3>';
				$DisConnect .= '<button data-user-id='. $user_id .' type="button" class="button disconnect-discord-user" id="disconnect-discord-user">'. esc_html__( 'Disconnect from discord ' , 'learnpress-discord-addon' ) . ' <i class="fab fa-discord"></i> <span class="spinner"></span> </button>';                    
				$DisConnect .= '<p>' . esc_html__ ( sprintf( 'Connected account: %s', $_ets_learnpress_discord_username ) , 'learnpress-discord-addon' ) . '</p>';                                
				_e( wp_kses( $DisConnect, ets_learnpress_discord_allowed_html() ) );
                        }   
		}          
	}
	/**
	 * Run disconnect discord
	 * 
	 * 
	 */        
	public function ets_learnpress_disconnect_user(  ) {
           
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_learnpress_discord_nonce'], 'ets-learnpress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}                
		$user_id              = sanitize_text_field( trim( $_POST['ets_learnpress_discord_user_id'] ) );
		$kick_upon_disconnect = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_kick_upon_disconnect' ) ) );
		$access_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );
		$refresh_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_refresh_token', true ) ) );                
		if ( $user_id && $access_token && $refresh_token ) {
			delete_user_meta( $user_id, '_ets_learnpress_discord_access_token' );
			delete_user_meta( $user_id, '_ets_learnpress_discord_refresh_token' );

			$user_roles = ets_learnpress_discord_get_user_roles( $user_id );                        
			if( $kick_upon_disconnect ){
                            
				if( is_array( $user_roles ) ) {
					foreach ( $user_roles as $user_role ) {
						$this->learnpress_discord_public_instance->delete_discord_role( $user_id, $user_role );
					}
				}
			}else{
				$this->learnpress_discord_public_instance->delete_member_from_guild( $user_id, false );
                        }
			$event_res = array(
				'status'  => 1,
				'message' => 'Successfully disconnected',
			);
			wp_send_json( $event_res );
                	exit();                        
		}
		
                exit();                                        
                
	}
	/**
	 * Add learnpress Discord Connection column to WP Users listing 
	 *
	 * @param array $columns 
	 * @return NONE
	 */        
	public function ets_learnpress_discord_add_learnpress_disconnect_discord_column( $columns ) {
            
		$columns['ets_learnpress_disconnect_discord_connection'] = esc_html__( 'LP Discord Connection', 'learnpress-discord-addon' );
		return $columns;            
        }

	/**
	 * Display Run API button
	 *
	 * @param array $columns 
	 * @return NONE
	 */        
	public function ets_learnpress_discord_disconnect_discord_button( $value, $column_name, $user_id ) {
           
		if ( $column_name === 'ets_learnpress_disconnect_discord_connection' ){
		
			$access_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );
			$_ets_learnpress_discord_username = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_username', true ) ) );                                                                        
			if ( $access_token  ){
				return '<button  data-user-id="' . $user_id  . '" class="disconnect-discord-user" >' . esc_html__ ( 'Disconnect from discord ' , 'learnpress-discord-addon' ) . ' <i class="fab fa-discord"></i> <span class="spinner"></span> </button><p>' . esc_html__ ( sprintf( 'Connected account: %s', $_ets_learnpress_discord_username ) , 'learnpress-discord-addon' ) . '</p>';                                 
			}
			return esc_html__( 'Not Connected', 'learnpress-discord-addon' );			
		}
		return $value;            
	}
        
        public function ets_learnpress_discord_delete_order( $postid, $post ) {
            
		if( get_post_type( $postid ) !== 'lp_order' ){
			return;
		}
            
		$order    = learn_press_get_order( $postid );
		$user_id = $order->get_user( 'id' );
		$access_token  = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );            
                if( $access_token ){
			$order_items = $order->get_items();
			if( is_array( $order_items ) && !empty( $order_items ) ){
    
				foreach ( $order_items as  $item ) {
                            
					$course_id = $item['course_id'];
					$student_role_for_course  = get_user_meta( $user_id,'_ets_learnpress_discord_role_id_for_' . $course_id , true );
					if( $student_role_for_course ){
						delete_user_meta( $user_id, '_ets_learnpress_discord_role_id_for_' . $course_id , $student_role_for_course );                                           
						$this->learnpress_discord_public_instance->delete_discord_role( $user_id, $student_role_for_course );
					}
				}
			}                    
                    
		}

	}
	public function ets_learnpress_discord_checkout_order_processed ( $order_id, $lp_checkout ){
            
		//$result = $lp_checkout->payment_method->process_payment( $order_id );
                
                //$result_process_payment = $result ['result'];
                
                //
            
	}
	public function ets_learnpress_discord_order_status_changed ( $order_id, $old_status, $new_status ){
            
		// We will assign course mapped role and Welcome DB only after successful PAYMENT
		if( $new_status == 'completed' ){
			$allow_none_student = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_allow_none_student' ) ) );                    
			$order    = learn_press_get_order( $order_id );
			$user_id = $order->get_user( 'id' );
                	$order_items = $order->get_items();
                	if( is_array( $order_items ) && !empty( $order_items ) ){
    
				foreach ( $order_items as  $item ) {
                            
					$course_id = $item['course_id'];
					//as_schedule_single_action( ets_learnpress_discord_get_random_timestamp( ets_learnpress_discord_get_highest_last_attempt_timestamp() ), 'ets_learnpress_discord_as_send_dm', array( $user_id, array ( $course_id ), 'welcome' ), LEARNPRESS_DISCORD_AS_GROUP_NAME );
					if ( $allow_none_student == 'no' ) {
						$discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_user_id', true ) ) );
                                            	$access_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );
						$this->learnpress_discord_public_instance->add_discord_member_in_guild( $discord_user_id, $user_id, $access_token );
					}
					if ( $allow_none_student == 'yes' ) {
						$courses = array ( $course_id );
						as_schedule_single_action( ets_learnpress_discord_get_random_timestamp( ets_learnpress_discord_get_highest_last_attempt_timestamp() ), 'ets_learnpress_discord_as_send_dm', array( $user_id, $courses, 'welcome' ), LEARNPRESS_DISCORD_AS_GROUP_NAME );
						$this->learnpress_discord_public_instance->ets_learnpress_discord_update_course_access( $user_id, $course_id );                                                                            
					}        
				}
			}
		}
	}
	public function ets_learnpress_discord_update_redirect_url(  ) {
           
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_learnpress_discord_nonce'], 'ets-learnpress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$page_id = $_POST['ets_learnpress_page_id'];
		if( isset( $page_id ) ){
			$formated_discord_redirect_url = ets_get_learnpress_discord_formated_discord_redirect_url( $page_id );
			update_option( 'ets_learnpress_discord_redirect_page_id' ,$page_id );
			update_option( 'ets_learnpress_discord_redirect_url' ,$formated_discord_redirect_url );
			$res = array(
				'formated_discord_redirect_url' => $formated_discord_redirect_url,
			);
			wp_send_json( $res );
		
		}
		exit();
                
	}        
}
