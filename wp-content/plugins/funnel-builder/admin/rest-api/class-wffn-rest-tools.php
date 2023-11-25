<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Tools
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Tools' ) ) {
	class WFFN_REST_Tools extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'woofunnels-admin';
		protected $rest_base = 'woofunnels/tools';

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
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/', array(

				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_tools' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),

				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'tools_action' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'woofunnels_transient' => array(
							'description'       => __( 'Clear woofunnels transient', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'woofunnels_tracking'  => array(
							'description'       => __( 'Clear woofunnels tracking', 'funnel-builder' ),
							'type'              => 'boolean',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'index_past_order'     => array(
							'description'       => __( 'Clear woofunnels tracking', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/get-all-log-files', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_log_files' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),

				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/view-log-file', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'view_log_files' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'log_selected' => array(
							'description'       => __( 'Selected log file for view', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/delete-log-file', array(
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_log_files' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'log_selected' => array(
							'description'       => __( 'Selected log file for view', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
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

		public function get_index_orders() {
			$get_threshold_order = get_option( '_bwf_order_threshold', BWF_THRESHOLD_ORDERS );
			$bwf_db_upgrade      = WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->get_upgrade_state();
			$index_orders        = [];

			if ( ! class_exists( 'WooCommerce' ) ) {
				return $index_orders;
			}

			if ( '3' !== $bwf_db_upgrade || $get_threshold_order < 1 ) {
				add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'woofunnels_handle_indexed_orders', 10, 2 );
				$all_order_ids = wc_get_orders( array(
					'return'      => 'ids',
					'numberposts' => '-1',
					'post_type'   => 'shop_order',
					'status'      => wc_get_is_paid_statuses(),
				) );
				remove_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'woofunnels_handle_indexed_orders', 10 );
				$get_threshold_order = count( $all_order_ids );
			}


			if ( 0 === $get_threshold_order && 0 === absint( $bwf_db_upgrade ) ) {
				WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->set_upgrade_state( '5' );
				$bwf_db_upgrade = '5';
			}

			$description = __( 'This tool will scan all the previous orders and create an optimized index to run efficient queries.<br/> <a href="https://funnelkit.com/docs/upstroke/miscellaneous/index-past-order/?utm_source=WordPress&utm_medium=Index+Past+Orders&utm_campaign=Lite+Plugin">Learn more about this process</a>', 'funnel-builder' );

			if ( '1' === $bwf_db_upgrade || '6' === $bwf_db_upgrade ) {
				$description .= esc_html__( 'Unable to complete indexing of orders.', 'woofunnels' );

				$description .= '<a target="_blank" href="https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Indexing+Failed+Support&utm_campaign=Lite+Plugin">contact support to get the issue resolved.</a>';

			}
			if ( true === apply_filters( 'bwf_needs_order_indexing', false ) ) {
				$index_orders = array(
					'title' => __( 'Index Past Orders', 'funnel-builder' ),
					'desc'  => $description,

				);


				if ( '3' === $bwf_db_upgrade ) {

					$index_orders['cta'] = array(
						'type' => 'button',
						'text' => __( 'Running', 'funnel-builder' ),
						'slug' => 'index_past_order',
						'prop' => 'disabled',
					);
				} elseif ( '4' === $bwf_db_upgrade || '5' === $bwf_db_upgrade ) {
					$index_orders['cta'] = array(
						'type' => 'button',
						'text' => __( 'Completed', 'funnel-builder' ),
						'slug' => 'index_past_order',
						'prop' => 'disabled',
					);
				} else {
					$index_orders['cta'] = array(
						'type' => 'button',
						'text' => __( 'Start', 'funnel-builder' ),
						'slug' => 'index_past_order',
						'prop' => ( $get_threshold_order > 0 ) ? '' : 'disabled',
					);
				}

				if ( '3' === WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->get_upgrade_state() ) {
					$index_orders['notice'] = array(
						'type' => 'success',
						'text' => __( 'Indexing of orders has started. It may take sometime to finish the process. We will update this notice once the process completes.', 'woofunnels' )
					);
				}


			}

			return $index_orders;
		}

		public function get_all_tools() {


			return rest_ensure_response( $this->get_all_tools_array() );
		}

		public function get_all_tools_array() {
			$tools_array = array(

				array(
					'title' => __( 'FunnelKit transients', 'funnel-builder' ),
					'desc'  => __( 'This tool will clear all the FunnelKit plugins transients cache.', 'funnel-builder' ),
					'cta'   => array(
						'type' => 'button',
						'text' => __( 'Clear Transients', 'funnel-builder' ),
						'slug' => 'woofunnels_transient',
					),
				),
				array(
					'title' => __( 'Usage Tracking', 'funnel-builder' ),
					'desc'  => __( 'This action controls Usage Tracking', 'funnel-builder' ),
					'cta'   => array(
						'type'         => 'toggle',
						'value'        => wffn_string_to_bool( WooFunnels_OptIn_Manager::get_optIn_state() ),
						'text_enable'  => __( 'User Tracking Enabled', 'funnel-builder' ),
						'text_disable' => __( 'User Tracking Disabled', 'funnel-builder' ),
						'slug'         => 'woofunnels_tracking',
					),
				),

			);
			$index       = $this->get_index_orders();
			if ( count( $index ) > 0 ) {
				return array_merge( [ $index ], $tools_array );
			} else {
				return $tools_array;
			}

		}

		public function get_all_log_files() {

			$file_list   = array();
			$file_list[] = array(
				'label' => 'Select Log File',
				'value' => ''
			);

			if ( ! class_exists( 'BWF_Logger' ) ) {
				return rest_ensure_response( $file_list );
			}

			$logger_obj        = BWF_Logger::get_instance();
			$final_logs_result = $logger_obj->get_log_options();

			foreach ( $final_logs_result as $plugin_folder => $plugin_log_files ) {
				foreach ( $plugin_log_files as $file_slug => $file_name ) {
					$option_value = $plugin_folder . '/' . $file_slug;
					$file_list[]  = array(
						'label' => $file_name,
						'value' => $option_value
					);

				}
			}

			return rest_ensure_response( $file_list );
		}

		public function view_log_files( $request ) {

			$resp = array(
				'status' => false,
				'msg'    => __( 'No log file found', 'funnel-builder' )
			);

			$selected_log_file = isset( $request['log_selected'] ) ? $request['log_selected'] : '';

			if ( empty( $selected_log_file ) ) {
				return rest_ensure_response( $resp );
			}

			$folder_prefix    = explode( '/', $selected_log_file );
			$folder_file_name = $folder_prefix[1];
			$folder_prefix    = $folder_prefix[0];
			$file_api         = new WooFunnels_File_Api( $folder_prefix );

			// View log submit is clicked, get the content from the selected file
			$content = $file_api->get_contents( $folder_file_name );

			if ( $content !== false ) {
				return rest_ensure_response( $content );
			}

			return rest_ensure_response( $resp );
		}

		public function delete_log_files( $request ) {

			$resp = array(
				'status' => false,
				'msg'    => __( 'No log file found', 'funnel-builder' )
			);

			$selected_log_file = isset( $request['log_selected'] ) ? $request['log_selected'] : '';

			if ( empty( $selected_log_file ) ) {
				return rest_ensure_response( $resp );
			}

			$folder_prefix    = explode( '/', $selected_log_file );
			$folder_file_name = $folder_prefix[1];
			$folder_prefix    = $folder_prefix[0];
			$file_api         = new WooFunnels_File_Api( $folder_prefix );

			// View log submit is clicked, get the content from the selected file
			$delete = $file_api->delete_file( $folder_file_name );

			if ( $delete ) {
				$resp = array(
					'status' => true,
					'msg'    => __( 'Successfully delete log file', 'funnel-builder' )
				);
			}

			return rest_ensure_response( $resp );
		}

		public function tools_action( $request ) {
			$resp = array(
				'status' => false,
			);

			$transient    = ( isset( $request['woofunnels_transient'] ) && $request['woofunnels_transient'] === 'yes' ) ? $request['woofunnels_transient'] : '';
			$tracking     = ( isset( $request['woofunnels_tracking'] ) ) ? $request['woofunnels_tracking'] : '';
			$index_orders = ( isset( $request['index_past_order'] ) && $request['index_past_order'] === 'yes' ) ? $request['index_past_order'] : '';

			if ( $transient !== '' ) {
				$woofunnels_transient_obj = WooFunnels_Transient::get_instance();
				$woofunnels_transient_obj->delete_force_transients();
				$resp['status'] = true;
				$resp['tool']   = $this->get_index_orders();
				$resp['msg']    = __( 'All Plugins transients cleared.', 'woofunnels' );

				return rest_ensure_response( $resp );
			}

			if ( $tracking !== '' ) {

				if ( true === $tracking ) {
					WooFunnels_OptIn_Manager::Allow_optin();
				} else {
					delete_option( 'bwf_is_opted' );
				}

				if ( false !== wp_next_scheduled( 'bwf_maybe_track_usage_scheduled' ) ) {
					wp_clear_scheduled_hook( 'bwf_maybe_track_usage_scheduled' );
				}
				$resp['status'] = true;
				$resp['msg']    = __( sprintf( 'Usage tracking successfully %s.', true === $tracking ? 'enabled' : 'disabled' ), 'woofunnels' );

				return rest_ensure_response( $resp );
			}

			if ( $index_orders !== '' ) {

				if ( 'yes' === $index_orders && '0' === WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->get_upgrade_state() ) {
					WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->set_upgrade_state( '2' );
				}
				if ( '2' === WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->get_upgrade_state() ) {
					WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->bwf_start_indexing();
				}


				$get_index_array = $this->get_all_tools_array();
				$resp['tool']    = $get_index_array[0];
				$resp['status']  = true;
				$resp['msg']     = __( 'Indexing started successfully', 'woofunnels' );

				return rest_ensure_response( $resp );
			}

			return rest_ensure_response( $resp );

		}


		public function sanitize_custom( $data ) {
			return json_decode( $data, true );
		}

	}


	return WFFN_REST_Tools::get_instance();


}