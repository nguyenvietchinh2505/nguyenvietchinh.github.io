<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Funnels
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Funnels' ) ) {
	class WFFN_REST_Funnels extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'woofunnels-admin';
		protected $rest_base = 'funnels';
		protected $rest_base_id = 'funnels/(?P<funnel_id>[\d]+)/';

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
					'callback'            => array( $this, 'get_all_funnels' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'offset' => array(
							'description'       => __( 'Offset', 'funnel-builder' ),
							'type'              => 'integer',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'limit'  => array(
							'description'       => __( 'Limit', 'funnel-builder' ),
							'type'              => 'integer',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'status' => array(
							'description'       => __( 'Funnel status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						's'      => array(
							'description'       => __( 'Search funnel', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_funnel' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), $this->get_create_funnels_collection() ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_funnel' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Delete funnels', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base_id, array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_funnel' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_funnel' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'funnel_id'   => array(
							'description'       => __( 'Funnel ID', 'funnel-builder' ),
							'type'              => 'integer',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
						'title'       => array(
							'description'       => __( 'title', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'description' => array(
							'description'       => __( 'description', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'steps'       => array(
							'description'       => __( 'steps', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_funnel' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/export/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'export_funnels' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'ids' => array(
							'description'       => __( 'Funnel id', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/import/', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'import_funnels' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => []
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/duplicate/(?P<funnel_id>[\d]+)', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'duplicate_funnel' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/import_template', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'import_template' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'funnel_id' => array(
							'description'       => __( 'Unique funnel id.', 'funnel-builder' ),
							'type'              => 'integer',
							'validate_callback' => 'rest_validate_request_arg',
							'default'           => 0,
						),
						'title'     => array(
							'description'       => __( 'Funnel name.', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'template'  => array(
							'description'       => __( 'template slug identifier', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'builder'   => array(
							'description'       => __( 'template group identifier', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'steps'     => array(
							'description'       => __( 'steps', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<funnel_id>[\d]+)/import-status', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'funnel_import_status' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),

				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/get-templates/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_templates' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => []
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/activate-plugin', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'activate_plugin' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'status' => array(
							'description'       => __( 'Check plugin status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'slug'   => array(
							'description'       => __( 'Check plugin slug', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'init'   => array(
							'description'       => __( 'Check builder status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
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

		public function get_funnel( WP_REST_Request $request ) {
			$funnel_id = $request->get_param( 'funnel_id' );

			$funnel = new WFFN_Funnel( $funnel_id );

			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}
			BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );

			$steps = $funnel->get_steps( true );

			if ( is_array( $steps ) && count( $steps ) > 0 ) {
				$steps = apply_filters( 'wffn_rest_get_funnel_steps', $steps, $funnel );

			}

			$return = array(
				'id'          => $funnel->get_id(),
				'title'       => $funnel->get_title(),
				'description' => $funnel->get_desc(),
				'link'        => $funnel->get_view_link(),
				'steps'       => $steps,
				'count_data'  => array(
					'contacts' => WFFN_Core()->wffn_contacts->get_total_count( $funnel_id ),
					'steps'    => $funnel->get_step_count(),
				)
			);

			return rest_ensure_response( $return );
		}

		public function create_funnel( $request ) {

			$resp        = array(
				'status' => false,
				'msg'    => __( 'Funnel creation failed', 'funnel-builder' )
			);
			$funnel_id   = 0;
			$posted_data = array();

			$posted_data['funnel_id']   = isset( $request['funnel_id'] ) ? $request['funnel_id'] : 0;
			$posted_data['funnel_name'] = ( isset( $request['funnel_name'] ) && ! empty( $request['funnel_name'] ) ) ? $request['funnel_name'] : '';

			do_action( 'wffn_load_api_import_class' );

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			if ( $posted_data['funnel_id'] === 0 && $posted_data['funnel_name'] !== '' ) {
				$funnel_name = ! empty( $posted_data['funnel_name'] ) ? $posted_data['funnel_name'] : __( '(no title)', 'funnel-builder' );
				$funnel      = WFFN_Core()->admin->get_funnel( $posted_data['funnel_id'] );

				if ( $funnel instanceof WFFN_Funnel ) {
					if ( $funnel->get_id() === 0 ) {
						$funnel_id = $funnel->add_funnel( array(
							'title'  => $funnel_name,
							'desc'   => '',
							'status' => 1,
						) );

						if ( $funnel_id > 0 ) {
							$funnel->id = $funnel_id;
						}
					}
				}

				if ( wffn_is_valid_funnel( $funnel ) ) {

					if ( $funnel_id > 0 ) {
						if ( defined( 'ICL_LANGUAGE_CODE' ) && 'all' !== ICL_LANGUAGE_CODE ) {
							WFFN_Core()->get_dB()->update_meta( $funnel_id, '_lang', ICL_LANGUAGE_CODE );
						}

						$redirect_link = WFFN_Common::get_funnel_edit_link( $funnel_id );

						$resp['status']        = true;
						$resp['funnel']        = $funnel;
						$resp['redirect_link'] = $redirect_link;
						$resp['msg']           = __( 'Funnel create successfully', 'funnel-builder' );

					} else {
						$resp['msg'] = __( 'Sorry, we are unable to create funnel due to some technical difficulties. Please contact support', 'funnel-builder' );
					}
				}
			}

			return $resp;

		}

		public function get_all_funnels( WP_REST_Request $request ) {
			$result = [
				'status'  => false,
				'message' => __( 'No funnels found', 'funnel-builder' )
			];

			$args    = $all_funnels = [];
			$offset  = $request->get_param( 'offset' );
			$status  = $request->get_param( 'status' );
			$limit   = $request->get_param( 'limit' );
			$search  = $request->get_param( 's' );
			$filters = $request->get_param( 'filters' );

			if ( isset( $offset ) ) {
				$args['offset'] = $offset;
			}
			if ( isset( $limit ) ) {
				$args['limit'] = $limit;
			}
			if ( isset( $status ) ) {
				$args['status'] = $status;
			}
			if ( isset( $search ) ) {
				$args['s'] = $search;
			}
			if ( isset( $filters ) ) {
				$args['filters'] = $filters;
			}

			if ( count( $args ) === 0 ) {
				$args['funnels'] = 'all';
			}
			$args['meta'] = array( 'key' => '_is_global', 'compare' => 'NOT_EXISTS' );
			$funnels      = WFFN_Core()->admin->get_funnels( $args );

			if ( is_array( $funnels ) && isset( $funnels['found_posts'] ) && $funnels['found_posts'] > 0 ) {
				$result            = $funnels;
				$result['status']  = true;
				$result['message'] = 'Get all funnels';

				if ( isset( $offset ) ) {
					$result['offset'] = $offset;
				}
				if ( isset( $limit ) ) {
					$result['limit'] = $limit;
				}
			}

			return rest_ensure_response( $result );
		}

		public function export_funnels( WP_REST_Request $request ) {

			$result = [
				'status'  => false,
				'message' => __( 'Funnel not exports', 'funnel-builder' )
			];

			do_action( 'wffn_load_api_export_class' );
			$items   = [];
			$funnels = [];

			$ids = $request->get_param( 'ids' );
			$ids = ( isset( $ids ) && $ids !== '' ) ? explode( ',', $ids ) : '';

			if ( is_array( $ids ) && count( $ids ) > 0 ) {
				foreach ( $ids as $funnel_id ) {
					$funnel = WFFN_Core()->admin->get_funnel( (int) $funnel_id );
					if ( $funnel instanceof WFFN_Funnel ) {
						$items[] = array(
							'id'         => $funnel->get_id(),
							'title'      => $funnel->get_title(),
							'desc'       => $funnel->get_desc(),
							'date_added' => $funnel->get_date_added(),
							'steps'      => $funnel->get_steps( true ),
							'__funnel'   => $funnel,
						);
					}
				}
				$funnels['items'] = $items;
			} else {
				$funnels = WFFN_Core()->admin->get_funnels();
			}

			if ( ! isset( $funnels['items'] ) || count( $funnels['items'] ) === 0 ) {
				return rest_ensure_response( $result );
			}

			$funnels_to_export = [];

			foreach ( $funnels['items'] as $key => $funnel ) {
				$funnels_to_export[ $key ] = [];
				/**
				 * var WFFN_Funnel $get_funnel
				 */
				$get_funnel                         = $funnel['__funnel'];
				$funnels_to_export[ $key ]['title'] = $get_funnel->get_title();
				$funnels_to_export[ $key ]['steps'] = [];

				$steps = $get_funnel->get_steps( true );
				foreach ( $steps as $k => $step ) {
					$get_object                               = WFFN_Core()->steps->get_integration_object( $step['type'] );
					$step_export_data                         = $get_object->get_export_data( $step );
					$funnels_to_export[ $key ]['steps'][ $k ] = $step_export_data;
				}
			}
			$funnels_to_export = apply_filters( 'wffn_export_data', $funnels_to_export );
			nocache_headers();

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=wffn-funnels-export-' . gmdate( 'm-d-Y' ) . '.json' );
			header( 'Expires: 0' );

			echo wp_json_encode( $funnels_to_export );
			exit;
		}

		public function import_funnels( WP_REST_Request $request ) {
			$result = [
				'status' => false,
			];

			$files = $request->get_file_params();

			do_action( 'wffn_load_api_import_class' );

			if ( ! function_exists( 'post_exists' ) ) {
				require_once ABSPATH . 'wp-admin/includes/post.php';
			}

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			if ( empty( $files ) ) {
				$result['message'] = __( 'Import File missing.', 'funnel-builder' );

				return $result;
			}

			if ( ! isset( $files['files']['name'] ) ) {
				$result['message'] = __( 'File name not valid.', 'funnel-builder' );

				return $result;
			}
			if ( ! isset( $files['files']['tmp_name'] ) ) {
				$result['message'] = __( 'File type not valid.', 'funnel-builder' );

				return $result;
			}
			$filename  = wffn_clean( $files['files']['name'] );
			$file_info = explode( '.', $filename );
			$extension = end( $file_info );

			if ( 'json' !== $extension ) {
				$result['message'] = __( 'Please upload a valid .json file', 'funnel-builder' );

				return $result;
			}

			$file = wffn_clean( $files['files']['tmp_name'] );

			if ( empty( $file ) ) {
				$result['message'] = __( 'Please upload a file to import', 'funnel-builder' );

				return $result;
			}

			// Retrieve the settings from the file and convert the JSON object to an array.
			$funnels = json_decode( file_get_contents( $file ), true ); //phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown

			if ( true === WFFN_Core()->import->validate_json( $funnels ) ) {
				WFFN_Core()->import->import_from_json_data( $funnels );
				$result = [
					'status' => true,
				];
			} else {
				$result = [
					'message' => __( 'Error: Invalid File Format. Please contact support.', 'funnel-builder' ),
					'status'  => false,
				];
			}


			return rest_ensure_response( $result );
		}

		public function duplicate_funnel( $request ) {
			$resp = array(
				'status'    => false,
				'funnel_id' => 0,
			);

			$funnel_id = $request->get_param( 'funnel_id' );

			if ( $funnel_id > 0 ) {
				$new_funnel = WFFN_Core()->admin->get_funnel();
				$funnel     = WFFN_Core()->admin->get_funnel( $funnel_id );

				if ( $new_funnel instanceof WFFN_Funnel ) {
					$new_funnel_id = $new_funnel->add_funnel( array(
						'title'  => $funnel->title . ' - ' . __( 'Copy', 'woofunnels-aero-checkout' ),
						'desc'   => $funnel->desc,
						'status' => 1,
					) );

					do_action( 'wffn_duplicate_funnel', $new_funnel, $funnel );

					if ( $new_funnel_id > 0 ) {

						if ( isset( $funnel->steps ) && is_array( $funnel->steps ) ) {
							foreach ( $funnel->steps as $steps ) {
								$type        = $steps['type'];
								$step_id     = $steps['id'];
								$posted_data = array( 'duplicate_funnel_id' => $funnel_id );

								BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $new_funnel_id );
								if ( ! empty( $type ) ) {
									$get_step = WFFN_Core()->steps->get_integration_object( $type );
									if ( $get_step instanceof WFFN_Step ) {
										$posted_data['original_id']     = $step_id;
										$posted_data['step_id']         = $step_id;
										$posted_data['_data']           = [];
										$posted_data['_data']['title']  = $get_step->get_entity_title( $step_id );
										$posted_data['_data']['desc']   = $get_step->get_entity_description( $step_id );
										$posted_data['_data']['status'] = $get_step->get_entity_status( $step_id );
										$posted_data['_data']['edit']   = $get_step->get_entity_edit_link( $step_id );
										$posted_data['_data']['view']   = $get_step->get_entity_view_link( $step_id );
										$get_step->duplicate_step( $new_funnel_id, $step_id, $posted_data );
									}
								}
							}
						}

						$excluded_meta = array( '_is_global' );
						$all_meta      = WFFN_Core()->get_dB()->get_meta( $funnel_id );
						if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
							foreach ( $all_meta as $key => $meta ) {
								if ( in_array( $key, $excluded_meta, true ) ) {
									continue;
								}
								WFFN_Core()->get_dB()->update_meta( $new_funnel_id, $key, maybe_unserialize( $meta[0] ) );
							}
						}
						$resp['funnel_id'] = $new_funnel_id;
						$resp['status']    = true;
					}
				}
			}

			return rest_ensure_response( $resp );
		}

		public function delete_funnel( $request ) {

			$result = [
				'status'  => false,
				'message' => __( 'Something wrong', 'funnel-builder' )
			];

			$ids        = $request->get_param( 'id' );
			$funnel_ids = isset( $ids ) ? $ids : false;

			if ( empty( $funnel_ids ) ) {
				return rest_ensure_response( $result );
			}

			if ( is_string( $funnel_ids ) ) {
				$funnel_ids = explode( ',', $funnel_ids );
			} else {
				$funnel_ids = [ $funnel_ids ];
			}

			foreach ( $funnel_ids as $funnel_id ) {
				if ( $funnel_id > 0 ) {
					$funnel  = WFFN_Core()->admin->get_funnel( $funnel_id );
					$deleted = $funnel->delete();
					if ( ! $deleted ) {
						return rest_ensure_response( $result );
					}
				}
			}

			$result = [
				'status'  => true,
				'message' => __( 'Funnel deleted', 'funnel-builder' ),
				'setup'   => WFFN_REST_Setup::get_instance()->get_status_reponses( false ),
			];

			return rest_ensure_response( $result );
		}

		public function update_funnel( WP_REST_Request $request ) {

			$funnel_id = $request->get_param( 'funnel_id' );

			$funnel = new WFFN_Funnel( $funnel_id );

			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			$title = $request->get_param( 'title' );
			if ( $title ) {
				$funnel->set_title( $title );
			}

			$description = $request->get_param( 'description' );
			if ( ! empty( $description ) ) {
				$funnel->set_desc( $description );
			} else {
				$funnel->set_desc( '' );
			}
			BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );

			/**
			 * Handle steps reordering
			 */
			$steps = $request->get_param( 'steps' );
			if ( $steps ) {
				$native_key = array_search( WFFN_Common::store_native_checkout_slug(), wp_list_pluck( $steps, 'type' ), true );
				if ( false !== $native_key ) {
					unset( $steps[ $native_key ] );
				}
				$funnel->reposition_steps( $steps );
			}
			$funnel->save();
			$return = array(
				'id'          => $funnel->get_id(),
				'title'       => $funnel->get_title(),
				'description' => $funnel->get_desc(),
				'link'        => $funnel->get_view_link(),
				'steps'       => $funnel->get_steps( true )
			);

			return rest_ensure_response( $return );
		}

		public function import_template( WP_REST_Request $request ) {
			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$funnel_id   = $request->get_param( 'funnel_id' );
			$funnel_name = $request->get_param( 'title' );
			$template    = $request->get_param( 'template' );
			$builder     = $request->get_param( 'builder' );
			$steps       = $request->get_param( 'steps' );

			$resp = [];

			if ( ! empty( $builder ) ) {
				$builder_status = WFFN_Core()->page_builders->builder_status( $builder );

				if ( isset( $builder_status['builders_options']['status'] ) && ! empty( $builder_status['builders_options']['status'] ) && 'activated' !== $builder_status['builders_options']['status'] ) {
					return rest_ensure_response( $builder_status );
				}
			}

			if ( empty( $funnel_id ) && $funnel_name !== '' ) {
				$funnel_name = ! empty( $funnel_name ) ? $funnel_name : __( '(no title)', 'funnel-builder' );
				$funnel      = WFFN_Core()->admin->get_funnel( $funnel_id );
				if ( $funnel instanceof WFFN_Funnel ) {
					if ( $funnel->get_id() === 0 ) {
						$funnel_id = $funnel->add_funnel( array(
							'title'  => $funnel_name,
							'desc'   => '',
							'status' => 1,
						) );

						if ( $funnel_id > 0 ) {
							$funnel->id = $funnel_id;
						}
					}
				}

				if ( wffn_is_valid_funnel( $funnel ) ) {

					if ( $funnel_id > 0 ) {
						if ( defined( 'ICL_LANGUAGE_CODE' ) && 'all' !== ICL_LANGUAGE_CODE ) {
							WFFN_Core()->get_dB()->update_meta( $funnel_id, '_lang', ICL_LANGUAGE_CODE );
						}
					} else {
						$resp['msg'] = __( 'Sorry, we are unable to create funnel due to some technical difficulties. Please contact support', 'funnel-builder' );

						return rest_ensure_response( $resp );
					}
				}
			}

			if ( 0 === absint( $funnel_id ) ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			if ( ! empty( $template ) && ! empty( $builder ) ) {
				$funnel_data = WFFN_Core()->remote_importer->get_remote_template( 'funnel', $template, $builder, $steps );

				if ( is_array( $funnel_data ) && isset( $funnel_data['error'] ) ) {
					$resp['msg'] = $funnel_data['error'];

					return rest_ensure_response( $resp );
				}


				/**
				 * Lets do the data import which will first create the steps and their respective entities
				 */
				$funnel_data[0]['id'] = $funnel_id;

				if ( ! function_exists( 'post_exists' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/post.php' );
				}

				do_action( 'wffn_load_api_import_class' );
				WFFN_Core()->import->import_from_json_data( $funnel_data );

				update_option( '_wffn_scheduled_funnel_id', $funnel_id );
				BWF_Logger::get_instance()->log( sprintf( 'Background template importer for funnel id %d is started', $funnel_id ), 'wffn_template_import' );
				WFFN_Core()->admin->wffn_maybe_run_templates_importer();

				/**
				 * return success
				 */
				$resp['status']    = true;
				$resp['funnel_id'] = $funnel_id;
				$resp['msg']       = __( 'Success', 'funnel-builder' );

			}

			return rest_ensure_response( $resp );
		}

		public function funnel_import_status( WP_REST_Request $request ) {

			$resp = array(
				'status' => false,
			);

			if ( ! function_exists( 'post_exists' ) ) {
				require_once ABSPATH . 'wp-admin/includes/post.php';
			}

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			if ( $funnel_id === 0 ) {
				return $resp;
			}

			$funnel_id_db = get_option( '_wffn_scheduled_funnel_id', 0 );
			if ( $funnel_id_db > 0 ) {
				BWF_Logger::get_instance()->log( sprintf( 'Background template importer for funnel id %d is started in get_import_status', $funnel_id ), 'wffn_template_import' );
				WFFN_Core()->admin->wffn_updater->trigger();
				$resp['success'] = false;
			} else {
				$redirect_url = WFFN_Common::get_funnel_edit_link( $funnel_id );

				$resp['status']   = true;
				$resp['redirect'] = $redirect_url;

			}


			return $resp;
		}

		public function get_templates() {
			$resp = array();

			$resp['all_builder'] = array(
				'funnel'      => [
					'elementor' => 'Elementor',
					'divi'      => 'Divi',
					'gutenberg' => 'Gutenberg',
					'oxy'       => 'Oxygen',
					'wp_editor' => 'Other'
				],
				'landing'     => [
					'elementor' => 'Elementor',
					'divi'      => 'Divi',
					'gutenberg' => 'Gutenberg',
					'oxy'       => 'Oxygen',
					'wp_editor' => 'Other'
				],
				'optin'       => [
					'elementor' => 'Elementor',
					'divi'      => 'Divi',
					'gutenberg' => 'Gutenberg',
					'oxy'       => 'Oxygen',
					'wp_editor' => 'Other (Using Shortcodes)'
				],
				'optin_ty'    => [
					'elementor' => 'Elementor',
					'divi'      => 'Divi',
					'gutenberg' => 'Gutenberg',
					'oxy'       => 'Oxygen',
					'wp_editor' => 'Other (Using Shortcodes)',
				],
				'wc_thankyou' => [
					'elementor' => 'Elementor',
					'divi'      => 'Divi',
					'gutenberg' => 'Gutenberg',
					'oxy'       => 'Oxygen',
					'wp_editor' => 'Other (Using Shortcodes)'
				],
				'wc_checkout' => [
					'elementor'  => 'Elementor',
					'divi'       => 'Divi',
					'gutenberg'  => 'Gutenberg',
					'oxy'        => 'Oxygen',
					'customizer' => 'Customizer', //pre_built
					'wp_editor'  => 'Other (Using Shortcodes)'
				],
				'upsell'      => [
					'elementor'  => 'Elementor',
					'divi'       => 'Divi',
					'gutenberg'  => 'Gutenberg',
					'oxy'        => 'Oxygen',
					'customizer' => 'Customizer',
					'wp_editor'  => 'Other (Using Shortcodes)'
				]
			);

			$resp['sub_filter_group'] = array(
				'funnel'      => [
					'all'   => 'All',
					'sales' => 'Sales Funnels',
					'optin' => 'Optin Funnels'
				],
				'landing'     => [
					'all' => 'All'
				],
				'optin'       => [
					'inline' => 'Inline',
					'popup'  => 'Popup'
				],
				'wc_thankyou' => [
					'all' => 'All'
				],
				'wc_checkout' => [
					'1' => 'One Step',
					'2' => 'Two Step',
					'3' => 'Three Step'
				],
				'upsell'      => [
					'all' => 'All'
				]
			);

			do_action( 'wffn_rest_before_get_templates' );
			$general_settings        = BWF_Admin_General_Settings::get_instance();
			$default_builder         = $general_settings->get_option( 'default_selected_builder' );
			$resp['default_builder'] = ( ! empty( $default_builder ) ) ? $default_builder : 'elementor';

			$templates = WooFunnels_Dashboard::get_all_templates();
			$json_data = isset( $templates['funnel'] ) ? $templates['funnel'] : [];

			if ( empty( $json_data ) || isset( $json_data['divi']['divi_funnel_1']['import_button_text'] ) ) {
				$templates = WooFunnels_Dashboard::get_all_templates( true );
				$json_data = isset( $templates['funnel'] ) ? $templates['funnel'] : [];
			}

			foreach ( $json_data as &$templates_nt ) {
				if ( is_array( $templates_nt ) ) {
					foreach ( $templates_nt as $k => &$temp_val ) {
						if ( isset( $temp_val['pro'] ) && 'yes' === $temp_val['pro'] ) {
							$temp_val['license_exist'] = WFFN_Core()->admin->get_license_status();

							/**
							 * Check if template is set to replace lite template
							 * if yes and license exists then replace lite, otherwise keep lite and unset pro
							 */
							if ( isset( $temp_val['replace_to'] ) ) {
								if ( false === $temp_val['license_exist'] ) {
									unset( $templates_nt[ $k ] );
								} else {
									unset( $templates_nt[ $temp_val['replace_to'] ] );
								}
							}

							if ( WFFN_Admin::get_instance()->is_basic_exists() && ! in_array( 'wc_checkout', $temp_val['group'], true ) && true === $temp_val['license_exist'] ) {

								$temp_val['license_exist'] = false;
							}


						}
					}
				}
			}
			$templates['funnel'] = $json_data;
			if ( is_array( $templates ) && count( $templates ) > 0 ) {
				$templates         = $this->add_default_template_list( $templates );
				$resp['templates'] = apply_filters( 'wffn_rest_get_templates', $templates );
			}

			return $resp;
		}

		public function get_create_funnels_collection() {
			$params                   = array();
			$params['template_group'] = array(
				'description'       => __( 'Choose template group.', 'funnel-builder' ),
				'type'              => 'string',
				'enum'              => array( 'gutenberg', 'elementor', 'divi', 'custom' ),
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => 'rest_validate_request_arg',

			);
			$params['template_type']  = array(
				'description'       => __( 'Choose template type.', 'funnel-builder' ),
				'type'              => 'string',
				'enum'              => array( 'all', 'sales', 'optin' ),
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => 'rest_validate_request_arg',

			);
			$params['template']       = array(
				'description' => __( 'Choose template.', 'funnel-builder' ),
				'type'        => 'string',

			);
			$params['title']          = array(
				'description' => __( 'Funnel name.', 'funnel-builder' ),
				'type'        => 'string',

			);
			$params['funnel_id']      = array(
				'description' => __( 'Funnel id.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,
			);
			$params['step_type']      = array(
				'description'       => __( 'Step type', 'funnel-builder' ),
				'type'              => 'string',
				'validate_callback' => 'rest_validate_request_arg',
				'sanitize_callback' => array( $this, 'sanitize_custom' ),
			);

			return apply_filters( 'wffn_rest_create_funnels_collection', $params );
		}

		public function sanitize_custom( $data ) {

			return json_decode( $data, true );
		}


		public function activate_plugin( $request ) {

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$resp = array(
				'status' => false,
				'msg'    => __( 'No builder found', 'funnel-builder' )
			);

			$plugin_init   = $request->get_param( 'init' );
			$plugin_slug   = $request->get_param( 'slug' );
			$plugin_status = $request->get_param( 'status' );

			$plugin_init   = isset( $plugin_init ) ? $plugin_init : '';
			$plugin_slug   = isset( $plugin_slug ) ? $plugin_slug : '';
			$plugin_status = isset( $plugin_status ) ? $plugin_status : '';

			if ( $plugin_init === '' ) {
				return rest_ensure_response( $resp );
			}

			if ( 'current' === $plugin_status ) {
				$plugin_active = WFFN_Core()->page_builders->get_plugin_status( $plugin_init );

				$resp = array(
					'success'       => true,
					'plugin_status' => $plugin_active,
					'init'          => $plugin_init,
				);

				if ( 'wp-marketing-automations/wp-marketing-automations.php' === $plugin_init && $plugin_active === 'activated' ) {
					$woocommerce_active = $new_order_automation = $cart_abandoned_automation = $any_automation = false;
					if ( ( function_exists( 'wfocu_is_woocommerce_active' ) && wfocu_is_woocommerce_active() ) || ( function_exists( 'wfacp_is_woocommerce_active' ) && wfacp_is_woocommerce_active() ) ) {
						$woocommerce_active = true;
					}
					$automation_status = $this->check_for_automation_exists();
					if ( in_array( 'wc_new_order', $automation_status, true ) ) {
						$new_order_automation = true;
					}
					if ( in_array( 'ab_cart_abandoned', $automation_status, true ) ) {
						$cart_abandoned_automation = true;
					}

					$first_automation_id = BWFAN_Model_Automations::get_first_automation_id();
					if ( intval( $first_automation_id ) > 0 ) {
						$any_automation = true;
					}

					$resp['new_order_automation']      = $new_order_automation;
					$resp['cart_abandoned_automation'] = $cart_abandoned_automation;
					$resp['woocommerce_status']        = $woocommerce_active;
					$resp['any_automation']            = $any_automation;
				}

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

			if ( is_wp_error( $activate ) ) {
				$resp = array(
					'success' => false,
					'message' => $activate->get_error_message(),
					'init'    => $plugin_init,
				);
			} else {
				$resp = array(
					'success' => true,
					'message' => __( 'Plugin Successfully Activated', 'funnel-builder' ),
					'init'    => $plugin_init,
				);
			}

			return rest_ensure_response( $resp );
		}

		/** Checks for automation active status */
		public function check_for_automation_exists() {
			global $wpdb;
			$result       = $wpdb->get_results( $wpdb->prepare( 'SELECT `event` FROM %1$s WHERE `event` IN ("wc_new_order", "ab_cart_abandoned") GROUP BY `event`', $wpdb->prefix . "bwfan_automations" ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,WordPress.DB.PreparedSQL.NotPrepared
			$active_event = [];
			foreach ( $result as $event ) {
				$active_event[] = $event['event'];
			}

			return $active_event;
		}

		public function install_plugin( $plugin_slug ) {


			$resp = array(
				'status' => false,
				'msg'    => __( 'No builder found', 'funnel-builder' )
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
				$resp['msg'] = $api->get_error_message();

				return $resp;
			}

			$upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
			$result   = $upgrader->install( $api->download_link );

			if ( is_wp_error( $result ) ) {
				$resp['msg'] = $result->get_error_message();

				return $resp;
			}

			if ( is_null( $result ) ) {
				global $wp_filesystem;
				$resp['msg'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'funnel-builder' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
					$resp['msg'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				return $resp;
			}

			$resp = install_plugin_install_status( $api );

			return $resp;
		}

		/**
		 *Add wp editor template in template list
		 *
		 * @param $templates
		 *
		 * @return array
		 */
		public function add_default_template_list( $templates ) {
			$default_template = array(
				'wp_editor_1' => array(
					'template_active'    => 'no',
					'build_from_scratch' => true,
					'name'               => __( 'Start from scratch', 'funnel-builder' )
				)
			);

			if ( isset( $templates['landing'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}
			if ( isset( $templates['optin'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}
			if ( isset( $templates['optin_ty'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}
			if ( isset( $templates['wc_thankyou'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}

			return $templates;
		}


	}


	if ( ! function_exists( 'wffn_rest_funnels' ) ) {

		function wffn_rest_funnels() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Funnels::get_instance();
		}
	}

	wffn_rest_funnels();
}
