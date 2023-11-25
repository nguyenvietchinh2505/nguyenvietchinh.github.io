<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_User_Preferences
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Global_Settings' ) ) {
	class WFFN_REST_User_Preferences extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'woofunnels-admin';
		protected $rest_base = 'user-preference';

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Register the routes for taxes.
		 */
		public function register_routes() {

			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_user_preferences' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );
		}

		public function get_write_api_permission_check() {
			if ( WFFN_Core()->role->user_access( 'funnel', 'write' ) ) {
				return true;
			}

			return false;
		}

		public function update_user_preferences( WP_REST_Request $request ) {
			$action = $request->get_param( 'action' );
			if ( ! in_array( $action, [ 'notice_close', 'update_fb_site_options' ], true ) ) {
				return new WP_Error( 'woofunnels_user_pref_wrong_action', __( 'Invalid Action', 'funnel-builder' ), array( 'status' => 404 ) );

			}

			return call_user_func( [ $this, $action ], $request );
		}

		public function notice_close( WP_REST_Request $request ) {
			$key     = $request->get_param( 'key' );
			$user_id = $request->get_param( 'user_id' );
			if ( ! empty( $key ) ) {
				$userdata   = get_user_meta( $user_id, '_bwf_notifications_close', true );
				$userdata   = empty( $userdata ) && ! is_array( $userdata ) ? [] : $userdata;
				$userdata[] = $key;
				update_user_meta( $user_id, '_bwf_notifications_close', $userdata ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_update_user_meta

				return rest_ensure_response( [ 'success' => true ] );
			}

			return rest_ensure_response( [ 'success' => false ] );
		}

		/**
		 * Update Funnel Builder Site options
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
		 */
		public function update_fb_site_options( WP_REST_Request $request ) {
			$key = $request->get_param( 'optionkey' );
			$val = $request->get_param( 'optionval' );

			if ( empty( $key ) || empty( $val ) ) {
				return rest_ensure_response( [ 'success' => false ] );
			}

			$fb_site_options = get_option( 'fb_site_options', [] );

			$fb_site_options[ $key ] = $val;

			$result = update_option( 'fb_site_options', $fb_site_options, true );
			if ( $result ) {
				return rest_ensure_response( [ 'success' => true ] );
			}

			return rest_ensure_response( [ 'success' => false ] );
		}

	}


}

return WFFN_REST_User_Preferences::get_instance();