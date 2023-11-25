<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Global_Settings
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Global_Settings' ) ) {
	class WFFN_REST_Global_Settings extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'woofunnels-admin';
		protected $rest_base = 'funnels/settings';

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
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<tab>[\w-]+)', array(
				'args' => array(
					'tab' => array(
						'description' => __( 'Unique tab for the resource.', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'woofunnels_global_settings' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'settings' => array(
							'description'       => __( 'settings', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
			) );
			register_rest_route( $this->namespace, '/funnels/global-settings', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_funnel_global_settings' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/funnels/general-settings/update-default-builder', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_default_builder' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'settings' => array(
							'description'       => __( 'settings', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
			) );


		}

		public function get_funnel_global_settings() {
			$License = WooFunnels_licenses::get_instance();
			$License->get_data();
			$data = [];

			if ( ! function_exists( 'get_current_screen' ) ) {
				require_once ABSPATH . 'wp-admin/includes/screen.php';
			}
			$get_all_registered_settings = apply_filters( 'woofunnels_global_settings', [] );

			if ( is_array( $get_all_registered_settings ) && count( $get_all_registered_settings ) > 0 ) {
				usort( $get_all_registered_settings, function ( $a, $b ) {
					if ( $a['priority'] === $b['priority'] ) {
						return 0;
					}

					return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
				} );
				$data['global_settings_tabs'] = $get_all_registered_settings;
			}
			$data['global_settings'] = apply_filters( 'woofunnels_global_settings_fields', [] );

			if ( isset( $data['global_settings']['wfacp'] ) && isset( $data['global_settings']['wfacp']['wfacp_global_checkout'] ) ) {

				if ( $this->should_hide_global_checkout_field() ) {
					unset( $data['global_settings']['wfacp']['wfacp_global_checkout'] );

					$data['global_settings']['wfacp']['wfacp_global_checkouts'] = array(
						'title'    => __( 'Global Checkout', 'woofunnels' ),
						'heading'  => __( 'Global Checkout', 'woofunnels' ),
						'slug'     => 'wfacp_global_checkouts',
						'fields'   => array(
							array(
								'key'          => 'override_checkout_page_id',
								'styleClasses' => 'group-one-class',
								'hint'         => __( "Global checkout now called the Store checkout and all the checkout steps, upsell steps and orderbumps will be available under <a href='" . admin_url( 'admin.php' ) . "?page=bwf&path=/store-checkout'>Funnels > Store checkout</a>", 'funnel-builder' ),
							)
						),
						'priority' => 5,
					);
				}

			}

			return rest_ensure_response( $data );
		}

		public function get_read_api_permission_check() {

			if ( WFFN_Core()->role->user_access( 'funnel', 'read' ) ) {
				return true;
			}

			return false;

		}

		public function get_write_api_permission_check() {

			if ( WFFN_Core()->role->user_access( 'funnel', 'write' ) ) {
				return true;
			}

			return false;
		}

		public function woofunnels_global_settings( WP_REST_Request $request ) {
			$resp = array(
				'success' => false,
				'msg'     => __( 'Failed', 'funnel-builder' )
			);

			$settings = $request->get_param( 'settings' );
			$tab      = $request->get_param( 'tab' );

			if ( ! is_array( $settings ) || count( $settings ) === 0 ) {
				return rest_ensure_response( $resp );
			}

			do_action( 'bwf_global_save_settings_' . $tab, $settings );

			$resp = array(
				'success' => true,
				'msg'     => __( 'Settings Updated', 'funnel-builder' ),
				'setup'   => WFFN_REST_Setup::get_instance()->get_status_reponses( false ),
			);

			return rest_ensure_response( $resp );
		}

		public function update_default_builder( WP_REST_Request $request ) {

			$get_config = get_option( 'bwf_gen_config',true );
			$settings = $request->get_param( 'settings' );

			if( ! empty( $settings['default_selected_builder'] ) ){
				$get_config['default_selected_builder'] = $settings['default_selected_builder'];
			}

			$general_settings = BWF_Admin_General_Settings::get_instance();

			$general_settings->update_global_settings_fields( $get_config );

			$resp = array(
				'success' => true,
				'msg'     => __( 'Settings Updated', 'funnel-builder' ),
			);

			return rest_ensure_response( $resp );

		}


		public function sanitize_custom( $data ) {
			return json_decode( $data, true );
		}

		public function should_hide_global_checkout_field() {


			/**
			 * Hide global checkout field if pro version not installed
			 */
			if ( false === defined( 'WFFN_PRO_VERSION' ) ) {
				return true;
			}


			/**
			 * Check if the person doesn't have access to the pro module
			 */
			$modules = get_option( '_bwf_individual_modules' );

			if ( ! isset( $modules['checkout'] ) || 'no' === $modules['checkout'] ) {
				return true;
			}


			/**
			 * Check if we do not have aero settings saved in DB
			 */
			$global_settings = WFACP_Common::global_settings( true );

			if ( ! is_array( $global_settings ) || count( $global_settings ) === 0 ) {
				return true;
			}

			if ( ! isset( $global_settings['override_checkout_page_id'] ) || absint( $global_settings['override_checkout_page_id'] ) === 0 ) {
				return true;
			}

			$wfacp_id = absint( $global_settings['override_checkout_page_id'] );

			if ( empty( $wfacp_id ) ) {
				return true;
			}

			/**
			 * Check if we have the store checkout setup already
			 */
			$store_checkout_funnel_id = WFFN_Common::get_store_checkout_id();
			if ( ! empty( $store_checkout_funnel_id ) ) {
				return true;
			}

			return false;
		}

	}

	if ( ! function_exists( 'wffn_rest_global_settings' ) ) {

		function wffn_rest_global_settings() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Global_Settings::get_instance();
		}
	}

	wffn_rest_global_settings();
}