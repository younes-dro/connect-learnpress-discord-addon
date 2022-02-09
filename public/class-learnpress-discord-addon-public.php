<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Learnpress_Discord_Addon
 * @subpackage Learnpress_Discord_Addon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Learnpress_Discord_Addon
 * @subpackage Learnpress_Discord_Addon/public
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Learnpress_Discord_Addon_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/learnpress-discord-addon-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/learnpress-discord-addon-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add button to make connection in between user and discord
	 *
         * @param NONE
         * @return STRING
	 * @since    1.0.0
	 */
	public function ets_learnpress_discord_add_connect_discord_button(  ) {

		$user_id = sanitize_text_field( trim( get_current_user_id() ) );

		$access_token = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_learnpress_discord_access_token', true ) ) );
		$allow_none_student  = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_allow_none_student' ) ) );

		$default_role                   = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_default_role_id' ) ) );
		$ets_learnpress_discord_role_mapping = json_decode( get_option( 'ets_learnpress_discord_role_mapping' ), true );
		$all_roles                      = unserialize( get_option( 'ets_learnpress_discord_all_roles' ) );
		$enrolled_courses                  = ets_learnpress_discord_get_student_courses_id( $user_id );
		$mapped_role_name               = '';
		if ( is_array ( $enrolled_courses ) && is_array( $all_roles ) ) {
                    $lastKey = array_key_last( $enrolled_courses );
                    $spacer =  ', ';
                    foreach ( $enrolled_courses as $key => $enrolled_course_id ){
			if ( is_array( $ets_learnpress_discord_role_mapping ) && array_key_exists( 'learnpress_course_id_' . $enrolled_course_id, $ets_learnpress_discord_role_mapping ) ) {
				
                            $mapped_role_id = $ets_learnpress_discord_role_mapping[ 'learnpress_course_id_' . $enrolled_course_id ];
				
                                if ( array_key_exists( $mapped_role_id, $all_roles ) ) {
                                    if  ( $lastKey === $key )
                                        $spacer = '.';
					$mapped_role_name .= $all_roles[ $mapped_role_id ] . $spacer;
				}
			}
                    }
		}
                
		$default_role_name = '';
		if ( $default_role != 'none' && is_array( $all_roles ) && array_key_exists( $default_role, $all_roles ) ) {
			$default_role_name = $all_roles[ $default_role ];
		}
                $restrictcontent_discord = '';
		if ( learnpress_discord_check_saved_settings_status() ) {

			if ( $access_token ) {
				
                                $restrictcontent_discord .= '<div class="learnpress-discord">';
                                $restrictcontent_discord .='<div class="">';
				$restrictcontent_discord .= '<label class="ets-connection-lbl">' . esc_html__( 'Discord connection', 'learnpress-discord-addon' ) . '</label>';
                                $restrictcontent_discord .= '</div>';
                                $restrictcontent_discord .= '<div class="">';
				$restrictcontent_discord .= '<a href="#" class="ets-btn learnpress-discord-btn-disconnect" id="learnpress-discord-disconnect-discord" data-user-id="'. esc_attr( $user_id ) .'">'. esc_html__( 'Disconnect From Discord ', 'learnpress-discord-addon' ) . '<i class="fab fa-discord"></i> </a>';
				$restrictcontent_discord .= '<span class="ets-spinner"></span>';
                                $restrictcontent_discord .= '</div>';
                                $restrictcontent_discord .= '</div>';
				
		
                        } elseif(  
                                ( ets_learnpress_discord_get_student_courses_id( $user_id ) && $mapped_role_name )
                                || ( ets_learnpress_discord_get_student_courses_id( $user_id ) && !$mapped_role_name && $default_role_name )
                                ||  ( $allow_none_student == 'yes' && $default_role_name )  ) {
                            
                            
				
                                $restrictcontent_discord .= '<div class="learnpress-discord">';
				$restrictcontent_discord .= '<h3>' . esc_html__( 'Discord connection', 'learnpress-discord-addon' ) .'</h3>';
                                $restrictcontent_discord .= '<div class="">';
				$restrictcontent_discord .= '<a href="?action=learnpress-discord-login" class="learnpress-discord-btn-connect ets-btn" >' . esc_html__( 'Connect To Discord', 'learnpress-discord-addon' ) . '<i class="fab fa-discord"></i> </a>';
                                $restrictcontent_discord .= '</div>';
				if ( $mapped_role_name ) {
					$restrictcontent_discord .= '<p class="ets_assigned_role">';
					
					$restrictcontent_discord .= __( 'Following Roles will be assigned to you in Discord: ', 'learnpress-discord-addon' );
					$restrictcontent_discord .= esc_html( $mapped_role_name  );
					if ( $default_role_name ) {
						$restrictcontent_discord .= ' ' . esc_html( $default_role_name ); 
                                                
                                        }
					
					$restrictcontent_discord .= '</p>';
				 } elseif( $default_role_name ) {
                                        $restrictcontent_discord .= '<p class="ets_assigned_role">';
					
					$restrictcontent_discord .= esc_html__( 'Following Role will be assigned to you in Discord: ', 'learnpress-discord-addon' );
                                        $restrictcontent_discord .= esc_html( $default_role_name ); 
					
                                        $restrictcontent_discord .= '</p>';
                                         
                                 }
                                   
                                $restrictcontent_discord .= '</div>';
			
			}
		}
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );
                
                echo $restrictcontent_discord ;
        }
}
