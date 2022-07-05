<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Learnpress_Discord_Addon
 * @subpackage Learnpress_Discord_Addon/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Learnpress_Discord_Addon
 * @subpackage Learnpress_Discord_Addon/includes
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Learnpress_Discord_Addon {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Learnpress_Discord_Addon_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LEARNPRESS_DISCORD_ADDON_VERSION' ) ) {
			$this->version = LEARNPRESS_DISCORD_ADDON_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'learnpress-discord-addon';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_common_hooks();                

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Learnpress_Discord_Addon_Loader. Orchestrates the hooks of the plugin.
	 * - Learnpress_Discord_Addon_i18n. Defines internationalization functionality.
	 * - Learnpress_Discord_Addon_Admin. Defines all hooks for the admin area.
	 * - Learnpress_Discord_Addon_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all methods that help to schedule actions.
		 */            
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/libraries/action-scheduler/action-scheduler.php';

		/**
		 * The class responsible for Logs
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-learnpress-discord-add-on-logs.php';            

		/**
		 * Common functions file.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';            

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-learnpress-discord-addon-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-learnpress-discord-addon-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-learnpress-discord-addon-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-learnpress-discord-addon-public.php';

		$this->loader = new Learnpress_Discord_Addon_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Learnpress_Discord_Addon_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Learnpress_Discord_Addon_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Learnpress_Discord_Addon_Admin( $this->get_plugin_name(), $this->get_version(), Learnpress_Discord_Addon_Public::get_learnpress_discord_public_instance( $this->get_plugin_name(), $this->get_version() ) );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ets_learnpress_discord_add_settings_menu' , 99 );
		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'ets_learnpress_discord_add_learnpress_discord_column' );                                                                                
		$this->loader->add_filter( 'manage_users_custom_column', $plugin_admin, 'ets_learnpress_discord_run_learnpress_discord_api', 99, 3 );                                
		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'ets_learnpress_discord_add_learnpress_disconnect_discord_column' );                                                                                
		$this->loader->add_filter( 'manage_users_custom_column', $plugin_admin, 'ets_learnpress_discord_disconnect_discord_button', 99, 3 );                 
		$this->loader->add_action( 'admin_post_learnpress_discord_application_settings', $plugin_admin, 'ets_learnpress_discord_application_settings' );                
		$this->loader->add_action( 'admin_init', $plugin_admin, 'ets_learnpress_discord_connect_to_bot' ); 
		$this->loader->add_action( 'wp_ajax_ets_learnpress_discord_load_discord_roles', $plugin_admin, 'ets_learnpress_discord_load_discord_roles' );
		$this->loader->add_action( 'admin_post_learnpress_discord_save_role_mapping', $plugin_admin, 'ets_learnpress_discord_save_role_mapping' );                
		$this->loader->add_action( 'admin_post_learnpress_discord_save_advance_settings', $plugin_admin, 'ets_learnpress_discord_save_advance_settings' );                
		$this->loader->add_action( 'admin_post_learnpress_discord_save_appearance_settings', $plugin_admin, 'ets_learnpress_discord_save_appearance_settings' );                                
		$this->loader->add_action( 'wp_ajax_ets_learnpress_discord_run_api', $plugin_admin, 'ets_learnpress_discord_run_api' );                                
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'ets_learnpress_discord_disconnect_user_button' , 99 );                                                
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'ets_learnpress_discord_disconnect_user_button' , 99 );  
		$this->loader->add_action( 'wp_ajax_ets_learnpress_discord_disconnect_user', $plugin_admin, 'ets_learnpress_disconnect_user' );                                                
//		$this->loader->add_action( 'learn_press_confirm_order', $plugin_admin, 'ets_learnpress_discord_confirm_order', 10, 1 );                                
		$this->loader->add_action( 'learnpress/user/course-enrolled', $plugin_admin, 'ets_learnpress_discord_user_course_enrolled', 10, 3 );                                                
//		$this->loader->add_action( 'learn-press/deleted-order-item', $plugin_admin, 'ets_learnpress_discord_delete_order', 10, 2 );                                                                
//		$th->loader->add_action( 'learn-press/before-delete-order-item', $plugin_admin, 'ets_learnpress_discord_delete_order_item', 10, 1 );  
		$this->loader->add_action( 'before_delete_post', $plugin_admin, 'ets_learnpress_discord_delete_order' ,10 ,2 );                                                                
//		$this->loader->add_action( 'learn-press/checkout-order-processed', $plugin_admin, 'ets_learnpress_discord_checkout_order_processed' ,10 ,2 );                                                                                
		$this->loader->add_action( 'learn-press/order/status-changed', $plugin_admin, 'ets_learnpress_discord_order_status_changed' ,10 ,3 );                                                                                                
		$this->loader->add_action( 'wp_ajax_ets_learnpress_discord_update_redirect_url', $plugin_admin, 'ets_learnpress_discord_update_redirect_url' );                                                                                                                

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Learnpress_Discord_Addon_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    		$this->loader->add_action( 'wp_ajax_learnpress_disconnect_from_discord', $plugin_public, 'ets_learnpress_discord_disconnect_from_discord' );            
		$this->loader->add_action( 'ets_learnpress_discord_as_schedule_delete_member', $plugin_public, 'ets_learnpress_discord_as_handler_delete_member_from_guild', 10, 3 );                
//		$this->loader->add_filter( 'learn-press/profile-tabs', $plugin_public, 'ets_learnpress_discord_add_connect_discord_button', 99, 1 );                
//		$this->loader->add_filter( 'learn-press/before-profile-dashboard-user-general-statistic', $plugin_public, 'ets_learnpress_discord_add_connect_discord_button', 10);                                                
		$this->loader->add_filter( 'learn-press/user-profile-tabs', $plugin_public, 'ets_learnpress_discord_add_connect_discord_button');
		$this->loader->add_action( 'init', $plugin_public, 'ets_learnpress_discord_api_callback' );
		$this->loader->add_action( 'ets_learnpress_discord_as_handle_add_member_to_guild', $plugin_public, 'ets_learnpress_discord_as_handler_add_member_to_guild', 10, 4 );
		$this->loader->add_action( 'ets_learnpress_discord_as_send_dm', $plugin_public, 'ets_learnpress_discord_handler_send_dm', 10, 3 );                                
		$this->loader->add_action( 'ets_learnpress_discord_as_schedule_member_put_role', $plugin_public, 'ets_learnpress_discord_as_handler_put_member_role', 10, 3 );                
		$this->loader->add_action( 'ets_learnpress_discord_as_schedule_delete_role',  $plugin_public, 'ets_learnpress_discord_as_handler_delete_memberrole' , 10, 3 );                
//		$this->loader->add_action( 'learn-press/after-checkout-account-login-fields',  $plugin_public, 'ets_learnpress_discord_registration_form' , 99 );                                
                $this->loader->add_action( 'learn-press/checkout-form',  $plugin_public, 'ets_learnpress_discord_registration_form' , 99 );                                                                                               
		$this->loader->add_action( 'template_redirect', $plugin_public, 'ets_learnpress_discord_login_with_discord' );
		$this->loader->add_action( 'learn-press/user-course-finished', $plugin_public, 'ets_learnpress_discord_course_finished', 10, 3 );                                
		$this->loader->add_action( 'learn-press/user-completed-lesson', $plugin_public, 'ets_learnpress_discord_complete_lessson', 10, 3 );                
	}

	/**
	 * Define actions which are not in admin or not public
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {
		$this->loader->add_action( 'action_scheduler_failed_execution',  $this, 'ets_learnpress_discord_reschedule_failed_action' );		     		
		$this->loader->add_filter( 'action_scheduler_queue_runner_batch_size', $this, 'ets_learnpress_discord_queue_batch_size' );                
		$this->loader->add_filter( 'action_scheduler_queue_runner_concurrent_batches', $this, 'ets_learnpress_discord_concurrent_batches' );            
		
        }

        /**
	 * Re-schedule  failed action 
	 *
	 * @param INT            $action_id
	 * @param OBJECT         $e
	 * @param OBJECT context
	 * @return NONE
	 */
	public function ets_learnpress_discord_reschedule_failed_action( $action_id  ) {
		// First check if the action is for learnpress discord.
		$action_data = ets_learnpress_discord_as_get_action_data( $action_id );
		if ( $action_data !== false ) {
			$hook              = $action_data['hook'];
			$args              = json_decode( $action_data['args'] );
			$retry_failed_api  = sanitize_text_field( trim( get_option( 'ets_learnpress_discord_retry_failed_api' ) ) );
			$hook_failed_count = ets_learnpress_discord_count_of_hooks_failures( $hook );
			$retry_api_count   = absint( sanitize_text_field( trim( get_option( 'ets_learnpress_discord_retry_api_count' ) ) ) );
			if ( $hook_failed_count < $retry_api_count && $retry_failed_api == true && $action_data['as_group'] == LEARNPRESS_DISCORD_AS_GROUP_NAME && $action_data['status'] === 'failed' ) {
				as_schedule_single_action( ets_learnpress_discord_get_random_timestamp( ets_learnpress_discord_get_highest_last_attempt_timestamp() ), $hook, array_values( $args ), LEARNPRESS_DISCORD_AS_GROUP_NAME );
			}
		}
	}
        
	/**
	 * Set action scheuduler batch size.
	 *
	 * @param INT $batch_size
	 * @return INT $concurrent_batches
	 */
	public function ets_learnpress_discord_queue_batch_size( $batch_size ) {
		if ( ets_learnpress_discord_get_all_pending_actions() !== false ) {
			return absint( get_option( 'ets_learnpress_discord_job_queue_batch_size' ) );
		} else {
			return $batch_size;
		}
	}
        
	/**
	 * Set action scheuduler concurrent batches.
	 *
	 * @param INT $concurrent_batches
	 * @return INT $concurrent_batches
	 */
	public function ets_learnpress_discord_concurrent_batches( $concurrent_batches ) {
		if ( ets_learnpress_discord_get_all_pending_actions() !== false ) {
			return absint( get_option( 'ets_learnpress_discord_job_queue_concurrency' ) );
		} else {
			return $concurrent_batches;
		}
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Learnpress_Discord_Addon_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
