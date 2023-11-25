<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Funnel_Canvas
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Funnel_Canvas' ) ) {
	class WFFN_REST_Funnel_Canvas extends WFFN_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'woofunnels-admin';
		protected $rest_base = 'canvas/(?P<funnel_id>[\d]+)/nodes';

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
			spl_autoload_register( array( $this, 'maybe_load_nodes' ) );
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
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),

					'experiment' => array(
						'description' => __( 'is expeiment node', 'funnel-builder' ),
						'type'        => 'boolean',
						'default'     => false,
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_nodes' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/analytics/(?P<step_id>[\d]+)', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_stats' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/order-bump/(?P<step_id>[\d]+)', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),

					'step_id' => array(
						'description' => __( 'step_id', 'funnel-builder' ),
						'type'        => 'integer',

					),
				),
				array(
					'args'                => [],
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_bumps' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/contacts/(?P<step_id>[\d]+)', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),

					'step_id'     => array(
						'description' => __( 'step_id', 'funnel-builder' ),
						'type'        => 'integer',

					),
					'total_count' => array(
						'description' => __( 'step_id', 'funnel-builder' ),
						'type'        => 'boolean',
						'default'     => true,

					),
					's'           => array(
						'description' => __( 'search', 'funnel-builder' ),
						'type'        => 'string',
						'default'     => '',

					),
					'page_no'     => array(
						'description' => __( 'Paged', 'funnel-builder' ),
						'type'        => 'integer',
						'default'     => '1',

					),
					'limit'       => array(
						'description' => __( 'Limit', 'funnel-builder' ),
						'type'        => 'integer',
						'default'     => '25',

					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_contacts' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/rules/(?P<step_id>[\d]+)', array(
				array(
					'args'                => array(
						'funnel_id' => array(
							'description' => __( 'Unique funnel id.', 'funnel-builder' ),
							'type'        => 'integer',
						),

						'step_id' => array(
							'description' => __( 'Unique step id.', 'funnel-builder' ),
							'type'        => 'integer',
						),
					),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_rules' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/experiment/(?P<experiment_id>[\d]+)', array(
				array(
					'args'                => array(
						'funnel_id' => array(
							'description' => __( 'Unique funnel id.', 'funnel-builder' ),
							'type'        => 'integer',
						),

						'experiment_id' => array(
							'description' => __( 'Unique experiment id.', 'funnel-builder' ),
							'type'        => 'integer',
						),
					),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_single_experiment' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
		}

		public function get_read_api_permission_check() {

			if ( WFFN_Core()->role->user_access( 'analytics', 'read' ) ) {
				return true;
			}

			return false;

		}

		/**
		 * @param $type
		 * @param $step
		 *
		 * @return WFFN_Base_Node
		 */
		public function get_node_class( $type, $step ) {

			$classname = 'WFFN_Node_' . ucfirst( $type );

			return new $classname( $step );
		}

		public function maybe_load_nodes( $class_name ) {

			if ( 0 === strpos( $class_name, 'WFFN_Node_' ) ) {
				require_once plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/canvas/node_types/class-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';

			}
			if ( 0 === strpos( $class_name, 'WFFN_Base_Node' ) ) {
				require_once plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/canvas/abstracts/class-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';

			}


		}

		/** Resolve the terminae node with the current index
		 *
		 * @param $index
		 * @param $configs
		 */
		public function resolve_terminate_node( $index, &$configs ) {
			foreach ( $configs as &$config ) {
				if ( isset( $config['target'] ) ) {
					$config['target'] = str_replace( '{{TERMINATION_NODE}}', $index, $config['target'] );
				}
			};
		}

		public function resolve_endnode( $index, &$configs ) {
			foreach ( $configs as &$config ) {
				if ( isset( $config['target'] ) ) {
					$config['target'] = str_replace( '{{END_NODE}}', $index, $config['target'] );
				}
			};
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_nodes( WP_REST_Request $request ) {
			$funnel_id     = $request->get_param( 'funnel_id' );
			$is_experiment = $request->get_param( 'experiment' );
			$funnel        = new WFFN_Funnel( $funnel_id );
			$index         = 1;
			$edge_index    = 1;
			$configs       = [];



			if ( $funnel_id === WFFN_Common::get_store_checkout_id() && false === $funnel->funnel_has_checkout() ) {
				$class = $this->get_node_class( 'native_checkout', [ 'funnel_id' => $funnel_id ] );
				$class->prepare_node_config( $index, $configs, $edge_index );
			}else{
				if ( ! is_array( $funnel->get_steps() ) || count( $funnel->get_steps() ) === 0 ) {
					return rest_ensure_response( array( 'status' => false, 'data' => array( 'nodes' => $configs ) ) );
				}
			}
			/**
			 * Iterate over the funnel steps
			 */
			if(count( $funnel->get_steps() ) > 0) {

				foreach ( $funnel->get_steps() as $step ) {


					/**
					 * Resolve any pending termianate node
					 */
					$this->resolve_terminate_node( $index, $configs );


					$step['experiment'] = $is_experiment;
					$step['funnel_id']  = $funnel_id;

					$class = $this->get_node_class( $step['type'], $step );
					$class->prepare_node_config( $index, $configs, $edge_index );
				}

			}

			/**
			 * Resolve terminate upsells to end
			 */
			$class->resolve_terminate_upsellls( $index, $configs );

			/**
			 * Resolve pending terminate nodes
			 */
			$this->resolve_terminate_node( $index, $configs );

			/**
			 * Prepare an end node
			 */
			$class = $this->get_node_class( 'end', [] );
			$class->prepare_node_config( $index, $configs, $edge_index );

			/**
			 * rearrange an end code
			 */
			$this->resolve_endnode( $index, $configs );
			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}


			return rest_ensure_response( array( 'status' => true, 'data' => array( 'nodes' => $configs ) ) );
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_funnel_settings( WP_REST_Request $request ) {
			$funnel_id = $request->get_param( 'funnel_id' );

			$funnel = new WFFN_Funnel( $funnel_id );

			$resp = array(
				'success' => false,
				'msg'     => __( 'Failed', 'funnel-builder' )
			);

			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			$settings = $request->get_param( 'settings' );

			if ( ! is_array( $settings ) || count( $settings ) === 0 ) {
				return rest_ensure_response( $resp );
			}

			WFFN_Core()->get_dB()->update_meta( $funnel_id, '_settings', $settings );
			$resp = array(
				'success' => true,
				'msg'     => __( 'Success', 'funnel-builder' )
			);

			return rest_ensure_response( $resp );
		}


		public function sanitize_custom( $data ) {
			return json_decode( $data, true );
		}

		public function get_stats( $request ) {
			$response = array();

			$response['totals'] = $this->prepare_item_for_response( $request );

			$response['intervals'] = $this->prepare_item_for_response( $request, 'interval' );


			return rest_ensure_response( array( 'status' => true, 'data' => $response ) );
		}

		public function prepare_item_for_response( $request, $is_interval = '' ) {

			$start_date    = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date      = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );
			$int_request   = ( isset( $request['interval'] ) && '' !== $request['interval'] ) ? $request['interval'] : 'day';
			$step_id       = ( isset( $request['step_id'] ) && '' !== $request['step_id'] ) ? intval( $request['step_id'] ) : 0;
			$type          = ( isset( $request['type'] ) && '' !== $request['type'] ) ? $request['type'] : '';
			$funnel_id     = ( isset( $request['funnel_id'] ) && '' !== $request['funnel_id'] ) ? $request['funnel_id'] : '';
			$experiment_id = ( isset( $request['view_experiment'] ) && '' !== $request['view_experiment'] ) ? intval( $request['view_experiment'] ) : 0;


			$result = [];
			$data   = [
				'view' => array(
					'label' => __( 'Views', 'funnel-builder' ),
					'value' => 0,
				),

			];

			if (  '' === $type ) {
				return $result;
			}

			$step = array( 'id' => $step_id, 'type' => $type, 'funnel_id' => $funnel_id );

			if ( $experiment_id > 0 ) {
				$experiment = $this->get_running_experiment( $experiment_id );
				if ( ! empty( $experiment ) ) {
					$start_date = $experiment->date_started;
				}
				$step['view_experiment'] = $experiment_id;
			}
			$class    = $this->get_node_class( $step['type'], $step );
			$get_data = $class->get_analytics_data( $step_id, $type, $start_date, $end_date, $is_interval, $int_request );

			if ( $class->supports( 'conversion' ) ) {
				$data['conversion'] = array(
					'label' => __( 'Conversions', 'funnel-builder' ),
					'value' => 0,
				);

			}


			if ( $class->supports( 'conversion' ) ) {
				$data['conversion-rate'] = array(
					'label' => __( 'Conversion Rate', 'funnel-builder' ),
					'value' => 0,
				);
			}
			if ( $class->supports( 'revenue' ) ) {
				$data['revenue'] = array(
					'label' => $class->get_revenue_label(),
					'value' => 0,
				);
			}
			if ( isset( $get_data['db_error'] ) ) {
				return $get_data;
			}

			$intervals = array();
			if ( ! empty( $is_interval ) ) {
				$intervals_all = $this->intervals_between( $start_date, $end_date, $int_request );
				foreach ( $intervals_all as $all_interval ) {
					$interval        = $all_interval['time_interval'];
					$start_date      = $all_interval['start_date'];
					$end_date        = $all_interval['end_date'];
					$get_total_visit = $this->maybe_interval_exists( $get_data, 'time_interval', $interval );

					$views       = is_array( $get_total_visit ) ? $get_total_visit[0]['viewed'] : 0;
					$conversions = is_array( $get_total_visit ) && isset( $get_total_visit[0]['converted'] ) ? $get_total_visit[0]['converted'] : 0;
					$revenue     = is_array( $get_total_visit ) && isset( $get_total_visit[0]['revenue'] ) ? $get_total_visit[0]['revenue'] : 0;

					$all_interval['date_start_gmt'] = $this->convert_local_datetime_to_gmt( $start_date )->format( self::$sql_datetime_format );
					$all_interval['date_end_gmt']   = $this->convert_local_datetime_to_gmt( $end_date )->format( self::$sql_datetime_format );

					$intervals['view'] = array(
						'label' => __( 'Views', 'funnel-builder' ),
						'value' => intval( $views ),
					);

					if ( $class->supports( 'conversion' ) ) {
						$intervals['conversion'] = array(
							'label' => __( 'Conversions', 'funnel-builder' ),
							'value' => intval( $conversions ),
						);
					}

					if ( $class->supports( 'conversion' ) ) {
						$intervals['conversion-rate'] = array(
							'label' => __( 'Conversion Rate', 'funnel-builder' ),
							'value' => $this->get_percentage( $views, $conversions ),
						);
					}


					if ( $class->supports( 'revenue' ) ) {
						$intervals['revenue'] = array(
							'label' => $class->get_revenue_label(),
							'value' => floatval( number_format( $revenue, 2, '.', '' ) ),
						);
					}
					$bump_data = array_column( $get_data, 'bump_revenue' );
					if ( is_array( $bump_data ) && count( $bump_data ) > 0 ) {
						$bump_revenue              = is_array( $get_total_visit ) ? $get_total_visit[0]['bump_revenue'] : 0;
						$intervals['bump_revenue'] = array(
							'label' => __( 'Bump Revenue', 'funnel-builder' ),
							'value' => floatval( number_format( $bump_revenue, 2, '.', '' ) ),
						);
					}


					$result[] = array_merge( $all_interval, array( 'subtotals' => $intervals ) );

				}

			} else {
				if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
					foreach ( $get_data as $item ) {
						if ( is_array( $item ) && count( $item ) > 0 ) {
							$data['view']['value'] = is_null( $item['viewed'] ) ? 0 : intval( $item['viewed'] );

							if ( $class->supports( 'conversion' ) ) {
								$data['conversion']['value'] = is_null( $item['converted'] ) ? 0 : intval( $item['converted'] );

							}
							if ( $class->supports( 'revenue' ) ) {
								$data['revenue']['value'] = ( ! isset( $item['revenue'] ) || is_null( $item['revenue'] ) ) ? 0 : floatval( number_format( $item['revenue'], 2, '.', '' ) );

								if ( isset( $item['bump_revenue'] ) ) {
									$data['bump_revenue']['label'] = __( 'Bump revenue', 'woofunnels-funnel-builder' );
									$data['bump_revenue']['value'] = is_null( $item['bump_revenue'] ) ? 0 : floatval( number_format( $item['bump_revenue'], 2, '.', '' ) );

								}

							}
							if ( $class->supports( 'conversion' ) ) {
								$data['conversion-rate']['value'] = $this->get_percentage( $item['viewed'], $item['converted'] );

							}
						}
					}
				}

				$result = $data;

			}

			return $result;

		}

		public function get_stats_collection() {
			$params = array();

			$params['after']    = array(
				'type'              => 'string',
				'format'            => 'date-time',
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'woofunnels-upstroke-one-click-upsell' ),
			);
			$params['before']   = array(
				'type'              => 'string',
				'format'            => 'date-time',
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published before a given ISO8601 compliant date.', 'woofunnels-upstroke-one-click-upsell' ),
			);
			$params['type']     = array(
				'type'              => 'string',
				'validate_callback' => 'rest_validate_request_arg',
				'required'          => true,
				'description'       => __( 'Get step type', 'woofunnels-upstroke-one-click-upsell' ),
			);
			$params['interval'] = array(
				'type'              => 'string',
				'default'           => 'day',
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Time interval to use for buckets in the returned data.', 'woofunnels-upstroke-one-click-upsell' ),
				'enum'              => array(
					'hour',
					'day',
					'week',
					'month',
					'quarter',
					'year',
				),
			);

			return apply_filters( 'wfocu_rest_node_stats_collection', $params );
		}

		/**
		 * Get percentage of a given number against a total
		 *
		 * @param float|int $total total number of occurrences
		 * @param float|int $number the number to get percentage against
		 *
		 * @return float|int
		 */
		function get_percentage( $total, $number ) {
			if ( $total > 0 ) {
				return round( $number / ( $total / 100 ), 2 );
			} else {
				return 0;
			}
		}

		public function get_rules( $request ) {
			$step_id = $request->get_param( 'step_id' );
			$type    = ( isset( $request['type'] ) && '' !== $request['type'] ) ? $request['type'] : '';
			if ( $type === 'wc_thankyou' ) {
				$rules         = WFTY_Rules::get_instance()->get_funnel_rules( $step_id );
				$class         = new WFFN_Node_Rule( array( 'type' => $type, 'id' => $step_id, 'rules' => $rules ) );
				$rules_strings = $class->prepare_rules_strings();
			} else {
				$rules = WFOCU_Common::get_funnel_rules( $step_id );

				$class         = new WFFN_Node_Rule( array( 'type' => $type, 'id' => $step_id, 'rules' => $rules ) );
				$rules_strings = $class->prepare_rules_strings();

				$rules = WFOCU_Common::get_funnel_rules( $step_id, 'product' );

				$class                  = new WFFN_Node_Rule( array( 'type' => $type, 'id' => $step_id, 'rules' => $rules ) );
				$rules_strings_advanced = $class->prepare_rules_strings();
				$rules_strings          = array_merge( $rules_strings_advanced, $rules_strings );


			}


			return rest_ensure_response( array( 'status' => true, 'data' => array( 'rules' => $rules_strings ) ) );


		}

		public function get_bumps( $request ) {
			global $wpdb;
			$funnel_id = $request['funnel_id'];
			$step_id   = $request['step_id'];

			$bump_data = [];

			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}


			$get_step = WFFN_Core()->steps->get_integration_object( 'wc_checkout' );

			$funnel   = new WFFN_Funnel( $funnel_id );
			$substeps = [ 'wc_order_bump' => [] ];
			if ( $funnel_id === WFFN_Common::get_store_checkout_id() && $funnel->is_funnel_has_native_checkout() ) {

				$substeps_meta             = WFFN_Common::get_store_checkout_global_substeps( $funnel->get_id() );
				$substeps['wc_order_bump'] = ( is_array( $substeps_meta ) && isset( $substeps_meta['wc_order_bump'] ) ) ? $substeps_meta['wc_order_bump'] : [];

			} else {
				$substeps = $get_step->get_substeps( $funnel_id, $step_id, array( 'wc_order_bump' ) );

			}

			$substeps = $this->maybe_add_ab_substep_variants( $substeps );

			foreach ( $substeps as $subtype => $substep_ids ) {

				if ( 'wc_order_bump' === $subtype && is_array( $substep_ids ) && count( $substep_ids ) ) {
					$get_substep = WFFN_Core()->substeps->get_integration_object( $subtype );
					if ( ! $get_substep instanceof WFFN_Substep ) {
						break;
					}
					foreach ( $substep_ids as $substep_id ) {
						$bump_data[ $substep_id ] = array(
							'title' => html_entity_decode( get_the_title( $substep_id ), ENT_QUOTES, get_bloginfo( 'charset' ) ),
							'stats' => [
								[ 'label' => __( 'Views' ), 'value' => 0, ],
								[ 'label' => __( 'Accept' ), 'value' => 0, ],
								[ 'label' => __( 'Reject' ), 'value' => 0, ],
								[ 'label' => __( 'Conversion Rate' ), 'value' => 0, ],
								[ 'label' => __( 'Revenue' ), 'value' => 0, ]
							]
						);
					}
				}
			}


			if ( ! empty( $bump_data ) ) {
				$all_bumps     = array_keys( $bump_data );
				$all_bumps_str = implode( ',', $all_bumps );
				$bump_sql      = "SELECT bump.bid as 'object_id',COUNT(CASE WHEN converted = 1 THEN 1 END) AS `converted`, p.post_title as 'object_name',SUM(bump.total) as 'total_revenue',COUNT(bump.ID) as viewed, 'bump' as 'type' FROM " . $wpdb->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $wpdb->prefix . 'posts' . " as p ON bump.bid  = p.id WHERE bump.bid IN (" . $all_bumps_str . ") GROUP by bump.bid ORDER BY bump.bid ASC";

				$get_all_bump_records = $wpdb->get_results( $bump_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

				foreach ( $get_all_bump_records as $record ) {

					$bump_data[ $record['object_id'] ] = array(
						'title' => $record['object_name'],
						'stats' => [
							[ 'label' => __( 'Views' ), 'value' => intval( $record['viewed'] ), ],
							[ 'label' => __( 'Accepted' ), 'value' => intval( $record['converted'] ), ],
							[ 'label' => __( 'Rejected' ), 'value' => intval( $record['viewed'] ) - intval( $record['converted'] ), ],
							[ 'label' => __( 'Conversion Rate' ), 'value' => $this->get_percentage( intval( $record['viewed'] ), intval( $record['converted'] ) ), ],
							[ 'label' => __( 'Revenue' ), 'value' => $record['total_revenue'], ]
						]
					);

				}
			}


			return rest_ensure_response( array( 'status' => true, 'data' => $bump_data ) );
		}


		public function get_contacts( $request ) {
			global $wpdb;
			$step_id       = $request['step_id'];
			$type          = $request['type'];
			$ids           = [];
			$limit         = isset( $request['limit'] ) ? $request['limit'] : 10;
			$offset        = isset( $request['offset'] ) ? $request['offset'] : 1;
			$search        = $request['s'];
			$total_count   = $request['total_count'];
			$experiment_id = ( isset( $request['view_experiment'] ) && '' !== $request['view_experiment'] ) ? intval( $request['view_experiment'] ) : 0;
			$start_date    = '';
			if ( $experiment_id > 0 ) {
				$experiment = $this->get_running_experiment( $experiment_id );
				if ( ! empty( $experiment ) ) {
					$start_date = $experiment->date_started;
				}
			}

			$contact_data = [ 'contacts' => [] ];

			if ( ('wc_checkout' === $type || 'native_checkout' === $type) && class_exists( 'WFACP_Contacts_Analytics' ) ) {


				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'wc_checkout' );
				if ( $get_step instanceof WFFN_Pro_Step && $experiment_id === 0 ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}

				$ids[] = $step_id;


				$aero_obj = WFACP_Contacts_Analytics::get_instance();


				$data = $aero_obj->get_contacts_by_ids( $ids, $request['fid'],$start_date );

			}

			if ( 'optin' === $type && class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {


				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'optin' );
				if ( $get_step instanceof WFFN_Pro_Step && $experiment_id === 0 ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}

				$ids[] = $step_id;


				$aero_obj = WFFN_Optin_Contacts_Analytics::get_instance();

				$data = $aero_obj->get_contacts_by_ids( $ids, $start_date );


			}

			if ( 'offers' === $type && class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$offer_obj = WFOCU_Contacts_Analytics::get_instance();
				$data      = $offer_obj->get_cid_by_offer_id( $step_id, $start_date );
			}

			if ( 'wc_upsells' === $type && class_exists( 'WFOCU_Contacts_Analytics' ) ) {

				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'wc_upsells' );
				if ( $get_step instanceof WFFN_Pro_Step && $experiment_id === 0 ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}

				$ids[] = $step_id;

				$offer_obj = WFOCU_Contacts_Analytics::get_instance();

				$offers     = $offer_obj->get_accepted_offer_ids_by_upsell( $ids, $start_date );
				$offers_ids = wp_list_pluck( $offers, 'offer' );
				$data       = $offer_obj->get_cid_by_offer_id( $offers_ids );

			}

			if ( count( $data ) > 0 ) {
				$get_cids                        = wp_list_pluck( $data, 'cid' );
				$get_total_possible_contacts_str = implode( ',', $get_cids );
				if ( $total_count ) {

					$str = "SELECT count(`id`) FROM " . $wpdb->prefix . "bwf_contact where `id` IN (" . $get_total_possible_contacts_str . ")";
					if ( ! empty( $search ) ) {
						$str .= " AND (f_name LIKE '%$search%' OR email LIKE '%$search%') ";
					}
					$contact_data['total'] = $wpdb->get_var( $str );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				}


				$str = "SELECT id,CONCAT(f_name, ' ', l_name) AS name ,email,contact_no FROM " . $wpdb->prefix . "bwf_contact where `id` IN (" . $get_total_possible_contacts_str . ")";
				if ( ! empty( $search ) ) {
					$str .= " AND (f_name LIKE '%$search%' OR email LIKE '%$search%') ";
				}

				$str                      .= " ORDER BY id DESC LIMIT $offset, $limit";
				$contact_data['contacts'] = $wpdb->get_results( $str, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			}
			$contact_data['offset'] = $offset;
			$contact_data['limit']  = $limit;


			return rest_ensure_response( array( 'status' => true, 'data' => $contact_data ) );
		}


		public function get_single_experiment( $request ) {
			$result = [
				'status'  => false,
				'message' => __( 'No experiment found', 'woofunnels-ab-tests' )
			];

			$experiment_id = $request->get_param( 'experiment_id' );
			if ( $experiment_id > 0 ) {
				$experiment_obj = BWFABT_Core()->admin->get_experiment( $experiment_id );
				$experiment     = (array) $experiment_obj;
				if ( ! empty( $experiment ) ) {
					$statuses             = BWFABT_Core()->admin->get_experiment_statuses();
					$experiment['status'] = isset( $statuses[ $experiment['status'] ] ) ? $statuses[ $experiment['status'] ] : '';

					$variants = [];
					if ( isset( $experiment['variants'] ) && $experiment['variants'] > 0 ) {
						foreach ( $experiment['variants'] as $variant_id => $item ) {

							$variant        = [];
							$variant_obj    = new BWFABT_Variant( $variant_id, $experiment_obj );
							$get_controller = BWFABT_Core()->controllers->get_integration( $experiment_obj->get_type() );
							$heading_urls   = $get_controller->get_variant_heading_url( $variant_obj, $experiment_obj );

							$variant['id']      = $variant_id;
							$variant['edit']    = $heading_urls;
							$variant['title']   = html_entity_decode( $get_controller->get_variant_title( $variant_obj->get_id() ) );
							$variant['desc']    = $get_controller->get_variant_desc( $variant_obj->get_id() );
							$variant['traffic'] = $variant_obj->get_traffic();
							$variant['control'] = $variant_obj->get_control();
							$variant['winner']  = $variant_obj->get_winner();
							$variant['active']  = $get_controller->is_variant_active( $variant_obj->get_id(), $experiment_obj );
							$variants[]         = $variant;

						}

					}
					$archived = array_filter( $variants, function ( $a ) {
						return empty( $a['status'] );
					} );

					$control = array_filter( $variants, function ( $c ) {
						return ! empty( $c['control'] );
					} );

					if ( is_array( $archived ) && count( $archived ) > 0 ) {
						foreach ( $archived as $key => $archive ) {
							unset( $variants[ $key ] );
						}
					}

					if ( is_array( $control ) && count( $control ) > 0 ) {
						foreach ( $control as $key => $con ) {
							unset( $variants[ $key ] );
						}
					}

					$experiment['variants'] = array_merge( $control, $variants, $archived );

					$experiment_url = add_query_arg( array(
						'page'    => 'bwf_ab_tests',
						'section' => 'variants',
						'edit'    => $experiment['id'],
					), admin_url( 'admin.php' ) );

					$experiment['row_actions'] = array(
						'edit'   => [
							'action' => 'edit',
							'text'   => __( 'Edit', 'woofunnels-ab-tests' ),
							'link'   => $experiment_url,
							'attrs'  => '',
						],
						'delete' => [
							'action' => 'delete',
							'text'   => __( 'Delete', 'woofunnels-ab-tests' ),
							'link'   => 'javascript:void(0);',
							'attrs'  => 'class="bwfabt-delete-experiment" data-experiment-id="' . $experiment['id'] . '" id="bwfabt_delete_' . $experiment['id'] . '"',
						],
					);

					$control_id              = isset( $experiment['control'] ) ? $experiment['control'] : 0;
					$result['items']         = $experiment;
					$result['type']          = $experiment['type'];
					$result['exp_create']    = false;
					$result['control_title'] = absint( $control_id ) > 0 ? html_entity_decode( get_the_title( $control_id ) ) : '';
					$result['status']        = true;
					$result['message']       = __( 'Get single experiments', 'woofunnels-ab-tests' );

					$funnel_id = get_post_meta( $control_id, '_bwf_in_funnel', true );
					if ( class_exists( 'WFFN_Core' ) && ! empty( $funnel_id ) ) {
						$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );
						if ( $funnel instanceof WFFN_Funnel ) {
							$result['funnel_id']    = absint( $funnel_id );
							$result['funnel_title'] = $funnel->get_title();
						}
					}

				}
			}

			return rest_ensure_response( $result );
		}

		public function maybe_add_ab_substep_variants( $substeps ) {
			$temp_substeps = [];
			foreach ( $substeps as $subtype => $substep_ids ) {
				if ( empty( $subtype ) ) {
					continue;
				}
				$get_substep = WFFN_Pro_Core()->substeps->get_integration_object( $subtype );
				if ( ! $get_substep instanceof WFFN_Pro_Substep ) {
					continue;
				}
				foreach ( $substep_ids as $substep_id ) {
					$temp_substeps[ $subtype ][] = $substep_id;
					$variant_ids                 = $get_substep->maybe_get_ab_variants( $substep_id );
					foreach ( $variant_ids as $variant_id ) {
						$temp_substeps[ $subtype ][] = $variant_id;
					}
				}
			}

			return $temp_substeps;
		}

		public function get_running_experiment( $experiment_id ) {

			if ( ! class_exists( 'BWFABT_Core' ) ) {
				return false;

			}

			$experiment_object = new BWFABT_Experiment( $experiment_id );

			if ( BWFABT_Experiment::STATUS_START === $experiment_object->get_status() || BWFABT_Experiment::STATUS_PAUSE === $experiment_object->get_status() ) {
				return $experiment_object;
			}

			return false;
		}


	}


	return WFFN_REST_Funnel_Canvas::get_instance();


}
