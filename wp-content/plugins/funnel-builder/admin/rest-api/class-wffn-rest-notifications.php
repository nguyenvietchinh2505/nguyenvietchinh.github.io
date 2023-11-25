<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Notifications
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Global_Settings' ) ) {
	class WFFN_REST_Notifications extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'woofunnels-admin';
		protected $rest_base = 'notifications';

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
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );
		}

		public function get_read_api_permission_check() {
			if ( WFFN_Core()->role->user_access( 'funnel', 'read' ) ) {
				return true;
			}

			return false;
		}

		public function get( WP_REST_Request $request ) {
			$id                    = $request->get_param( 'user_id' );
			$all_registered_notifs = WFFN_Core()->admin_notifications->get_notifications();

			$filter_notifs = WFFN_Core()->admin_notifications->filter_notifs( $all_registered_notifs, $id );

			return rest_ensure_response( array( 'success' => true, 'notifications' => $filter_notifs ) );

		}






	}


}

return WFFN_REST_Notifications::get_instance();