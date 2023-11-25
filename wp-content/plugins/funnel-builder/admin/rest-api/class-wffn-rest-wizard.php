<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Wizard
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Wizard' ) ) {
	class WFFN_REST_Wizard extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'wizard';

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
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/activate-builder', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'activate_builder' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'status'          => array(
							'description'       => __( 'Check plugin status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'slug'            => array(
							'description'       => __( 'Check plugin slug', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'init'            => array(
							'description'       => __( 'Check builder status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'default_builder' => array(
							'description'       => __( 'Set default builder status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/other-plugins', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'other_plugins' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/optin-setup', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'setup_optin' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'op_name'  => array(
							'description'       => __( 'Get optin name', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'op_email' => array(
							'description'       => __( 'Get optin email', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/get-steps-data', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'maybe_update_steps_data' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
			) );
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

		public function activate_builder( $request ) {

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$resp = array(
				'status'  => false,
				'message' => __( 'No builder found', 'funnel-builder' )
			);

			$plugin_init     = $request->get_param( 'init' );
			$plugin_slug     = $request->get_param( 'slug' );
			$plugin_status   = $request->get_param( 'status' );
			$default_builder = $request->get_param( 'default_builder' );

			$plugin_init     = isset( $plugin_init ) ? $plugin_init : '';
			$plugin_slug     = isset( $plugin_slug ) ? $plugin_slug : '';
			$plugin_status   = isset( $plugin_status ) ? $plugin_status : '';
			$default_builder = isset( $default_builder ) ? $default_builder : '';
			$activate        = '';


			if ( $plugin_init === '' || $plugin_slug === '' ) {
				return rest_ensure_response( $resp );
			}

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( $plugin_status === 'install' && $plugin_slug !== '' ) {
				$install_plugin = $this->install_plugin( $plugin_slug );
				if ( isset( $install_plugin['status'] ) && $install_plugin['status'] === false ) {
					return rest_ensure_response( $install_plugin );
				}
			}

			$activate = activate_plugin( $plugin_init, '', false, true );

			if ( '' !== $default_builder && ( ! is_wp_error( $activate ) || $plugin_status === 'activated' ) ) {
				$get_config                             = get_option( 'bwf_gen_config', true );
				$get_config['default_selected_builder'] = $default_builder;
				$general_settings                       = BWF_Admin_General_Settings::get_instance();

				$general_settings->update_global_settings_fields( $get_config );
			}

			if ( is_wp_error( $activate ) ) {
				$resp = array(
					'status'  => false,
					'message' => $activate->get_error_message(),
					'slug'    => $plugin_slug,
				);
			} else {
				$resp = array(
					'status'  => true,
					'message' => __( 'Plugin Successfully Activated', 'funnel-builder' ),
					'slug'    => $plugin_slug,
				);
			}

			return rest_ensure_response( $resp );
		}

		public function other_plugins() {

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$resp = array(
				'status'  => false,
				'message' => __( 'No builder found', 'funnel-builder' )
			);

			$plugins = array(
				array(
					'slug'   => 'woocommerce',
					'init'   => 'woocommerce/woocommerce.php',
					'status' => $this->get_plugin_status( 'woocommerce/woocommerce.php' ),
				),
				array(
					'slug'   => 'wp-marketing-automations',
					'init'   => 'wp-marketing-automations/wp-marketing-automations.php',
					'status' => $this->get_plugin_status( 'wp-marketing-automations/wp-marketing-automations.php' ),
				),
				array(
					'slug'   => 'funnelkit-stripe-woo-payment-gateway',
					'init'   => 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php',
					'status' => $this->get_plugin_status( 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php' ),
				),
				array(
					'slug'   => 'cart-for-woocommerce/',
					'init'   => 'cart-for-woocommerce/plugin.php',
					'status' => $this->get_plugin_status( 'cart-for-woocommerce/plugin.php' ),
				)
			);

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			foreach ( $plugins as $plugin ) {
				$plugin_init   = $plugin['init'];
				$plugin_slug   = $plugin['slug'];
				$plugin_status = $plugin['status'];
				if ( $plugin_status === 'install' && $plugin_slug !== '' ) {
					$install_plugin = $this->install_plugin( $plugin_slug );
					if ( isset( $install_plugin['status'] ) && $install_plugin['status'] === false ) {
						return rest_ensure_response( $install_plugin );
					}
				}
				$activate = activate_plugin( $plugin_init, '', false, true );

				if ( "woocommerce/woocommerce.php" === $plugin_init ) {
					update_option( 'bwf_needs_rewrite', 'yes', true );
				}

				if ( is_wp_error( $activate ) ) {
					$resp = array(
						'status'  => false,
						'message' => $activate->get_error_message(),
						'slug'    => $plugin_slug,
					);

					return rest_ensure_response( $resp );
				}

			}
			$substeps_data            = WFFN_Common::get_substeps_data();
			$substeps_data['substep'] = true;
			$resp                     = array(
				'status'  => true,
				'message' => __( 'Plugin Successfully Activated', 'funnel-builder' ),
				'slug'    => '',
				'api'    => 'get-steps-data',
			);

			return rest_ensure_response( $resp );
		}

		public function maybe_update_steps_data() {
			$resp = array(
				'status'  => false,
				'message' => __( 'Something wrong try again', 'funnel-builder' )
			);

			if ( ! class_exists( 'WFFN_Common' ) ) {
				return $resp;
			}

			$substeps_data            = WFFN_Common::get_substeps_data();
			$substeps_data['substep'] = true;

			$resp                     = array(
				'status'  => true,
				'steps'   => WFFN_Common::get_steps_data(),
				'substep' => $substeps_data,
				'message' => __( 'Successfully', 'funnel-builder' )
			);

			return rest_ensure_response( $resp );
		}

		public function setup_optin( $request ) {
			$resp = array(
				'status'  => false,
				'message' => __( 'Something wrong try again', 'funnel-builder' )
			);


			$op_email = $request->get_param( 'op_email' );


			$op_email = isset( $op_email ) ? trim( $op_email ) : '';

			if ( $op_email !== '' ) {


				if ( ! is_email( $op_email ) ) {
					$resp['message'] = __( 'Please enter a valid email address', 'funnel-builder' );

					return rest_ensure_response( $resp );
				}

				$api_params = array(
					'action' => 'woofunnelsapi_email_optin',
					'data'   => array( 'email' => $op_email, 'site' => home_url() ),
				);

				$request_args = WooFunnels_API::get_request_args( array(
					'timeout'   => 10, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
					'sslverify' => WooFunnels_API::$is_ssl,
					'body'      => urlencode_deep( $api_params ),
				) );


				/**
				 * We do not need to track the result of the call, simply move forward and show success to the user
				 */
				wp_remote_post( WooFunnels_API::get_api_url( WooFunnels_API::$woofunnels_api_url ), $request_args );

				update_option( 'bwf_is_opted_email', 'yes', true );
				update_option( 'bwf_is_opted_data', array( 'email' => $op_email ), true );
				update_option( '_wffn_onboarding_completed', true );
				delete_transient( '_wc_activation_redirect' );

				$resp = array(
					'status'  => true,
					'message' => __( 'Successfully', 'funnel-builder' ),
				);

			}

			return rest_ensure_response( $resp );
		}

		public function install_plugin( $plugin_slug ) {


			$resp = array(
				'status'  => false,
				'message' => __( 'No builder found', 'funnel-builder' )
			);

			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			include_once ABSPATH . '/wp-admin/includes/admin.php';
			include_once ABSPATH . '/wp-admin/includes/plugin-install.php';
			include_once ABSPATH . '/wp-admin/includes/plugin.php';
			include_once ABSPATH . '/wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . '/wp-admin/includes/class-plugin-upgrader.php';

			$api = plugins_api( 'plugin_information', array(
				'slug'   => $plugin_slug,
				'fields' => array(
					'sections' => false,
				),
			) );

			if ( is_wp_error( $api ) ) {
				$resp['message'] = $api->get_error_message();

				return $resp;
			}

			$upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
			$result   = $upgrader->install( $api->download_link );

			if ( is_wp_error( $result ) ) {
				$resp['message'] = $result->get_error_message();

				return $resp;
			}

			if ( is_null( $result ) ) {
				global $wp_filesystem;
				$resp['message'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'funnel-builder' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
					$resp['message'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				return $resp;
			}

			$resp = install_plugin_install_status( $api );

			return $resp;
		}

		public function get_plugin_status( $plugin_init_file ) {
			$plugins = get_plugins();

			if ( ! is_array( $plugins ) || ! isset( $plugins[ $plugin_init_file ] ) ) {
				return 'install';
			}

			if ( ! is_plugin_active( $plugin_init_file ) ) {
				return 'activate';
			}

			if ( isset( $plugins[ $plugin_init_file ] ) ) {
				return 'activated';
			}

			return '';
		}

		public function sanitize_custom( $data ) {

			return json_decode( $data, true );
		}

	}


	if ( ! function_exists( 'wffn_rest_wizard' ) ) {
		function wffn_rest_wizard() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Wizard::get_instance();
		}
	}

	wffn_rest_wizard();
}