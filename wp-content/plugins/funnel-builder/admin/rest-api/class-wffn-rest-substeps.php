<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Substeps
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Substeps' ) ) {
	class WFFN_REST_Substeps extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'woofunnels-admin';
		protected $rest_base = 'funnels/(?P<funnel_id>[\d]+)/steps/(?P<step_id>[\d]+)/substeps';

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
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'step_id'   => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_substep' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), $this->get_create_substeps_collection() ),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<substep_id>[\d]+)', array(
				'args' => array(
					'funnel_id'  => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'step_id'    => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'substep_id' => array(
						'description' => __( 'Current substep id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),

				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_substep' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ), $this->get_create_substeps_collection() )
				),

				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_substep' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => $this->get_delete_substeps_collection()
				),

				'schema' => array( $this, 'get_public_item_schema' ),
			) );

		}

		public function get_write_api_permission_check() {

			if ( WFFN_Core()->role->user_access( 'funnel', 'write' ) ) {
				return true;
			}

			return false;
		}

		public function create_substep( $request ) {

			$resp = array(
				'status' => false,
			);

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			$step_id      = $request->get_param( 'step_id' );
			$step_id      = ! empty( $step_id ) ? $step_id : 0;
			$type         = isset( $request['type'] ) ? $request['type'] : '';
			$title        = isset( $request['title'] ) ? $request['title'] : __( 'New Sub Step', 'funnel-builder' );
			$design       = isset( $request['design'] ) ? $request['design'] : '';
			$duplicate_id = isset( $request['duplicate_id'] ) ? $request['duplicate_id'] : 0;
			$inherit_id   = isset( $request['inherit_from'] ) ? $request['inherit_from'] : 0;

			$posted_data              = array();
			$posted_data['funnel_id'] = $funnel_id;
			$posted_data['step_id']   = $step_id;
			$posted_data['type']      = $type;


			BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );

			if ( $funnel_id > 0 && $step_id > 0 && ! empty( $type ) ) {
				$get_substep = WFFN_Core()->substeps->get_integration_object( $type );
				if ( $get_substep instanceof WFFN_Substep ) {

					if ( $duplicate_id > 0 ) {
						/**
						 * Case of existing order bump delete operation
						 */
						$data         = $get_substep->duplicate_single_substep( $funnel_id, '', $step_id, $type, $duplicate_id, 0, array() );
						$resp['data'] = $get_substep->populate_substeps_data_properties( [ $data[ $type ][0]->id ] )[0];


					} else {
						if ( $inherit_id > 0 && '' !== $title ) {
							/**
							 * Case of inherited from the existing
							 */
							$posted_data['design']            = $design;
							$posted_data['design_name']['id'] = $inherit_id;
							$posted_data['existing']          = 'true';
						}
						/**
						 * Case of new order bump
						 */
						$posted_data['title']                      = $title;
						$data                                      = $get_substep->add_substep( $funnel_id, $step_id, $type, $posted_data );
						$data_package                              = $get_substep->populate_substeps_data_properties( [ $data->id ] )[0];
						$resp['data']                              = $data_package;
						$resp['data']['substeps']                  = [];
						$resp['data']['substeps']['wc_order_bump'] = [ $data_package ];

					}

					if ( ! empty( $data ) ) {

						$resp['status'] = true;
					}
				}
			}

			return $resp;

		}

		public function get_create_substeps_collection() {
			$params                 = array();
			$params['type']         = array(
				'description' => __( 'Step type.', 'funnel-builder' ),
				'type'        => 'string',
				'required'    => true,

			);
			$params['title']        = array(
				'description' => __( 'Step name.', 'funnel-builder' ),
				'type'        => 'string',

			);
			$params['design']       = array(
				'description' => __( 'Step Design.', 'funnel-builder' ),
				'type'        => 'string',
				'default'     => 'scratch'
			);
			$params['inherit_from'] = array(
				'description' => __( 'Inherit Step.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,
			);
			$params['duplicate_id'] = array(
				'description' => __( 'Duplicate Step.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,
			);

			return apply_filters( 'wffn_rest_create_substeps_collection', $params );
		}

		public function update_substep_permissions_check( $request ) {
			if ( 'PUT' !== $request->get_method() ) {
				return false;
			}

			return true;
		}

		public function update_substep( $request ) {

			$resp = array(
				'status'   => false,
				'switched' => 0,
			);

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			$substep_id = $request->get_param( 'substep_id' );
			$substep_id = ! empty( $substep_id ) ? $substep_id : 0;

			$type       = isset( $request['type'] ) ? $request['type'] : '';
			$new_status = isset( $request['new_status'] ) ? $request['new_status'] : 0;

			if ( $funnel_id === 0 || $substep_id === 0 ) {
				return $resp;
			}
			BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
			if ( $funnel_id > 0 && ! empty( $type ) ) {
				$get_substep = WFFN_Core()->substeps->get_integration_object( $type );
				if ( $get_substep instanceof WFFN_Substep ) {
					$switched         = $get_substep->switch_status( $substep_id, $new_status );
					$resp['status']   = true;
					$resp['switched'] = $switched;
				}
			}

			return $resp;
		}

		public function get_update_substeps_collection() {
			$params               = array();
			$params['type']       = array(
				'description' => __( 'Step type.', 'funnel-builder' ),
				'type'        => 'string',
				'required'    => true,
			);
			$params['new_status'] = array(
				'description' => __( 'Set step status.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,

			);

			return apply_filters( 'wffn_rest_update_substeps_collection', $params );
		}

		public function delete_substep_permissions_check( $request ) {
			if ( 'DELETE' !== $request->get_method() ) {
				return false;
			}

			return true;
		}

		public static function delete_substep( $request ) {

			$resp = array(
				'status' => false,
			);

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			$step_id = $request->get_param( 'step_id' );
			$step_id = ! empty( $step_id ) ? $step_id : 0;

			$substep_id = $request->get_param( 'substep_id' );
			$substep_id = ! empty( $substep_id ) ? $substep_id : 0;

			$type = isset( $request['type'] ) ? $request['type'] : '';

			if ( $funnel_id === 0 || $step_id === 0 || $substep_id === 0 ) {
				return $resp;
			}

			if ( $funnel_id > 0 && ! empty( $type ) ) {
				$get_substep = WFFN_Core()->substeps->get_integration_object( $type );
				if ( $get_substep instanceof WFFN_Substep ) {
					$deleted        = $get_substep->delete_substep( $funnel_id, $step_id, $substep_id, $type );
					$resp['status'] = ( $deleted > 0 ) ? true : false;
				}
			}
			$resp['setup'] = WFFN_REST_Setup::get_instance()->get_status_reponses( false );

			return $resp;
		}

		public function get_delete_substeps_collection() {
			$params         = array();
			$params['type'] = array(
				'description' => __( 'Step type.', 'funnel-builder' ),
				'type'        => 'string',
				'required'    => true,
			);

			return apply_filters( 'wffn_rest_delete_substeps_collection', $params );
		}

	}

	if ( ! function_exists( 'wffn_rest_substeps' ) ) {

		function wffn_rest_substeps() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Substeps::get_instance();
		}
	}

	wffn_rest_substeps();
}