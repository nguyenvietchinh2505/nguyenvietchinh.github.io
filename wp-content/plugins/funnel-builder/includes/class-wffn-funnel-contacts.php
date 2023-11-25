<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel contact class
 * Class WFFN_Funnel_Contacts
 */
if ( ! class_exists( 'WFFN_Funnel_Contacts' ) ) {
	class WFFN_Funnel_Contacts {
		private static $ins = null;

		/**
		 * WFFN_Funnel_Contacts constructor.
		 */
		public function __construct() {

			add_action( 'rest_api_init', [ $this, 'register_contact_data_endpoint' ], 11 );
		}

		/**
		 * @return WFFN_Funnel_Contacts|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_contact_data_endpoint() {
			register_rest_route( 'woofunnels-analytics', '/funnels' . '/(?P<id>[\d]+)/contacts/', array(
				'args'                => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_funnel_contacts' ),
				'permission_callback' => array( $this, 'check_contact_view_permission' ),
			) );


			register_rest_route( 'woofunnels-analytics', '/funnels' . '/(?P<id>[\d]+)/contacts/(?P<cid>[\d]+)', array(
				'args' => array(
					'id'  => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'cid' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_funnel_contacts_single' ),
					'permission_callback' => array( $this, 'check_contact_view_permission' ),

				),

			) );
		}

		/**
		 * @return bool
		 */
		public function check_contact_view_permission() {
			if ( WFFN_Core()->role->user_access( 'funnel', 'read' ) ) {
				return true;
			}

			return false;
		}

		public function get_funnel_contacts( $request ) {


			$args     = array(
				'funnel_id'   => isset( $request['id'] ) ? $request['id'] : 0,
				's'           => isset( $request['s'] ) ? $request['s'] : '',
				'limit'       => isset( $request['limit'] ) ? $request['limit'] : 20,
				'page_no'     => isset( $request['page_no'] ) ? $request['page_no'] : 1,
				'orderby'     => isset( $request['orderby'] ) ? $request['orderby'] : 'last_modified',
				'order'       => ( isset( $request['order'] ) && 'DESC' === $request['order'] ) ? $request['order'] : 'ASC',
				'delete_cid'  => isset( $request['delete_cid'] ) ? $request['delete_cid'] : false,
				'total_count' => isset( $request['total_count'] ) ? $request['total_count'] : false,
			);
			$contacts = $this->get_contacts( $args );

			return rest_ensure_response( $contacts );
		}

		/**
		 * @param array $args
		 *
		 * @return mixed
		 */
		public function get_contacts( $args = array() ) {
			global $wpdb;
			$funnel_id = $args['funnel_id'];
			$data      = array( 'records' => 0 );

			if ( intval( $funnel_id ) < 1 ) {
				return rest_ensure_response( $data );
			}

			$is_store_checkout = ( absint( $funnel_id ) === WFFN_Common::get_store_checkout_id() ) ? true : false;

			$defaults    = array(
				's'           => '',
				'limit'       => 10,
				'page_no'     => 1,
				'orderby'     => 'last_modified',
				'order'       => 'DESC',
				'delete_cid'  => 0,
				'total_count' => false,
			);
			$args        = wp_parse_args( $args, $defaults );
			$search      = $args['s'];
			$total_count = wffn_string_to_bool( $args['total_count'] );
			$total       = null;
			$db_results  = [];

			$delete_cid = $args['delete_cid'];
			if ( ! empty( $delete_cid ) ) {
				$delete = $this->delete_funnel_contacts( $delete_cid, $funnel_id );
				if ( is_array( $delete ) && $delete['db_error'] ) {
					return rest_ensure_response( $delete );
				}
				$total_count = true;
			}

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj = WFACP_Contacts_Analytics::get_instance();
				$aero_sql = $aero_obj->get_contacts_by_funnel( $funnel_id );
			}
			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				$optin_obj = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin_sql = $optin_obj->get_contacts_by_funnel( $funnel_id );

			}
			if ( ! isset( $aero_sql ) ) {
				$get_total_possible_contacts = $wpdb->get_results( $optin_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			} else {
				$get_total_possible_contacts = $wpdb->get_results( 'SELECT id FROM (' . $aero_sql . ')u UNION ALL (' . $optin_sql . ')', ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			}

			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return rest_ensure_response( $db_error );
			}

			$get_total_possible_contacts = array_unique( wp_list_pluck( $get_total_possible_contacts, 'id' ) );


			$get_total_possible_contacts_str = implode( ',', $get_total_possible_contacts );
			if ( ! is_array( $get_total_possible_contacts ) || count( $get_total_possible_contacts ) === 0 ) {
				return rest_ensure_response( $data );
			}
			if ( $total_count ) {

				$str = "SELECT count(`id`) FROM " . $wpdb->prefix . "bwf_contact where `id` IN (" . $get_total_possible_contacts_str . ")";
				if ( ! empty( $search ) ) {
					$str .= " AND (f_name LIKE '%$search%' OR email LIKE '%$search%') ";
				}
				$total = $wpdb->get_var( $str );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			}

			$limit   = $args['limit'];
			$page    = $args['page_no'];
			$orderby = $args['orderby'];


			$offset = intval( $limit ) * intval( $page - 1 );


			$str = "SELECT id,f_name,l_name,email FROM " . $wpdb->prefix . "bwf_contact where `id` IN (" . $get_total_possible_contacts_str . ")";
			if ( ! empty( $search ) ) {
				$str .= " AND (f_name LIKE '%$search%' OR email LIKE '%$search%') ";
			}

			$str .= " ORDER BY $orderby DESC";
			$str .= " LIMIT $offset, $limit";


			$contact_data = $wpdb->get_results( $str, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$db_error     = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return rest_ensure_response( $db_error );
			}

			$filtered_ids = wp_list_pluck( $contact_data, 'id' );
			if ( class_exists( 'WFACP_Contacts_Analytics' ) && is_array( $filtered_ids ) && 0 < count( $filtered_ids ) ) {
				$aero_data_obj = WFACP_Contacts_Analytics::get_instance();
				$db_results    = $aero_data_obj->get_contacts_data( $funnel_id, $filtered_ids );

				if ( isset( $db_results['db_error'] ) ) {
					return rest_ensure_response( $db_results );
				}
			}

			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) && is_array( $filtered_ids ) && 0 < count( $filtered_ids ) ) {
				$optin_data_object = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin_result      = $optin_data_object->get_contacts_data( $funnel_id, $filtered_ids );

				if ( isset( $optin_result['db_error'] ) ) {
					return rest_ensure_response( $optin_result );
				}

				$db_results = array_merge( $db_results, $optin_result );
			}

			$cids = array_unique( wp_list_pluck( $db_results, 'cid' ) );

			if ( class_exists( 'WFOB_Contacts_Analytics' ) && array( $cids ) && count( $cids ) > 0 ) {
				$bump_data_obj = WFOB_Contacts_Analytics::get_instance();
				$bump_result   = $bump_data_obj->get_contacts_data( $funnel_id, $cids );

				if ( isset( $bump_result['db_error'] ) ) {
					return rest_ensure_response( $bump_result );
				}

				$db_results = array_merge( $db_results, $bump_result );
			}

			if ( class_exists( 'WFOCU_Contacts_Analytics' ) && array( $cids ) && count( $cids ) > 0 ) {
				$upsell_data_obj = WFOCU_Contacts_Analytics::get_instance();
				$upsell_result   = $upsell_data_obj->get_contacts_data( $funnel_id, $cids );

				if ( isset( $upsell_result['db_error'] ) ) {
					return rest_ensure_response( $upsell_result );
				}

				$db_results = array_merge( $db_results, $upsell_result );
			}

			$final_result = [];

			foreach ( $db_results as $db_result ) {
				$cid = $db_result['cid'];
				if ( isset( $final_result[ $cid ] ) ) {
					if ( isset( $db_result['total_revenue'] ) ) {
						$final_result[ $cid ]['total_revenue'] += floatval( $db_result['total_revenue'] );
					}
					if ( strtotime( $db_result['date'] ) < strtotime( $final_result[ $cid ]['date'] ) ) {
						$final_result[ $cid ]['date'] = $db_result['date'];
					}
					$final_result[ $cid ]['in_checkout'] = ( true !== $final_result[ $cid ]['in_checkout'] ) ? $this->is_in_checkout( $final_result, $db_result, $cid, $is_store_checkout ) : true;
					$final_result[ $cid ]['in_optin']    = ( true !== $final_result[ $cid ]['in_optin'] ) ? $this->is_in_optin( $final_result, $db_result, $cid ) : true;
					$final_result[ $cid ]['in_bump']     = ( true !== $final_result[ $cid ]['in_bump'] ) ? $this->is_in_bump( $final_result, $db_result, $cid ) : true;
					$final_result[ $cid ]['in_upsell']   = ( true !== $final_result[ $cid ]['in_upsell'] ) ? $this->is_in_upsell( $final_result, $db_result, $cid ) : true;
					if ( empty( $final_result[ $cid ]['date'] ) ) {
						$final_result[ $cid ]['date'] = $db_result['date'];
					}
				} else {
					$final_result[ $cid ] = array(
						'f_name'        => $this->get_contact_prop( $contact_data, $cid, $filtered_ids, 'f_name' ),
						'l_name'        => $this->get_contact_prop( $contact_data, $cid, $filtered_ids, 'l_name' ),
						'email'         => $this->get_contact_prop( $contact_data, $cid, $filtered_ids, 'email' ),
						'in_checkout'   => $this->is_in_checkout( $final_result, $db_result, $cid, $is_store_checkout ),
						'in_optin'      => $this->is_in_optin( $final_result, $db_result, $cid ),
						'in_bump'       => $this->is_in_bump( $final_result, $db_result, $cid ),
						'in_upsell'     => $this->is_in_upsell( $final_result, $db_result, $cid ),
						'date'          => $db_result['date'],
						'total_revenue' => isset( $db_result['total_revenue'] ) ? $db_result['total_revenue'] : 0
					);
				}
			}
			if ( ! empty( $filtered_ids ) ) {
				$filtered_ids = array_map( 'absint', $filtered_ids );
				uksort( $final_result, function ( $a, $b ) use ( $filtered_ids ) {
					return array_search( $a, $filtered_ids, true ) > array_search( $b, $filtered_ids, true ) ? 1 : - 1;
				} );
				$final_result = array_map( function ( $k ) use ( $final_result ) {
					return array_merge( $final_result[ $k ], array( 'cid' => $k ) );
				}, $filtered_ids );
			}

			$data = array( 'records' => $final_result );
			if ( ! is_null( $total ) ) {
				$data['total_records'] = $total;
			}
			$funnel             = new WFFN_Funnel( $funnel_id );
			$data['count_data'] = array(
				'contacts' => WFFN_Core()->wffn_contacts->get_total_count( $funnel_id ),
				'steps'    => $funnel->get_step_count(),
			);

			return rest_ensure_response( $data );
		}

		public function get_contact_prop( $list, $contact_id, $filtered_ids, $prop = 'email' ) {
			$filtered_ids = array_map( 'absint', $filtered_ids );
			$array_search = array_search( absint( $contact_id ), $filtered_ids, true );

			return ( $array_search === false ) ? '' : $list[ $array_search ][ $prop ];
		}

		/**
		 * @param $freuslt
		 * @param $db_result
		 * @param $cid
		 * @param $is_store_checkout
		 *
		 * @return string
		 */
		public function is_in_checkout( $freuslt, $db_result, $cid, $is_store_checkout = false ) {

			if ( true === $is_store_checkout && isset( $db_result['wfacp_id'] ) && absint( $db_result['wfacp_id'] ) === 0 ) {
				return true;
			}

			if ( isset( $db_result['wfacp_id'] ) && $db_result['wfacp_id'] > 0 ) {
				return true;
			}
			if ( isset( $freuslt[ $cid ] ) && isset( $freuslt[ $cid ]['in_checkout'] ) ) {
				return $freuslt[ $cid ]['in_checkout'];
			}

			return null;
		}

		/**
		 * @param $freuslt
		 * @param $db_result
		 * @param $cid
		 *
		 * @return string
		 */
		public function is_in_optin( $freuslt, $db_result, $cid ) {
			if ( isset( $db_result['opid'] ) && $db_result['opid'] ) {
				return true;
			}
			if ( isset( $freuslt[ $cid ] ) && isset( $freuslt[ $cid ]['in_optin'] ) ) {
				return $freuslt[ $cid ]['in_optin'];
			}

			return null;
		}

		/**
		 * @param $freuslt
		 * @param $db_result
		 * @param $cid
		 *
		 * @return string
		 */
		public function is_in_bump( $freuslt, $db_result, $cid ) {
			if ( isset( $db_result['bid'] ) && $db_result['bid'] > 0 && isset( $db_result['converted'] ) ) {
				return ( 1 === intval( $db_result['converted'] ) );
			}

			if ( isset( $freuslt[ $cid ] ) && isset( $freuslt[ $cid ]['in_bump'] ) ) {
				return $freuslt[ $cid ]['in_bump'];
			}

			return null;
		}

		/**
		 * @param $fresult
		 * @param $db_result
		 * @param $cid
		 *
		 * @return string
		 */
		public function is_in_upsell( $fresult, $db_result, $cid ) {
			if ( isset( $db_result['session_id'] ) && $db_result['session_id'] > 0 && isset( $db_result['action_type_id'] ) && '2' === $db_result['action_type_id'] && isset( $db_result['total_revenue'] ) && '' === $db_result['total_revenue'] ) {
				return false;

			}
			if ( isset( $db_result['session_id'] ) && $db_result['session_id'] > 0 && ( isset( $db_result['action_type_id'] ) && ( '4' === $db_result['action_type_id'] || '6' === $db_result['action_type_id'] || '9' === $db_result['action_type_id'] ) ) && isset( $db_result['total_revenue'] ) ) {
				return ( '' !== $db_result['total_revenue'] );
			}
			if ( isset( $fresult[ $cid ] ) && isset( $fresult[ $cid ]['in_upsell'] ) ) {
				return $fresult[ $cid ]['in_upsell'];
			}

			return null;
		}

		/**
		 * Deleting contact for given contact in this funnel
		 *
		 * @param $cid
		 * @param $funnel_id
		 */
		public function delete_funnel_contacts( $cids, $funnel_id ) {

			if ( is_string( $cids ) ) {
				$get_cids = explode( ',', $cids );
			} else {
				$get_cids = [ $cids ];
			}

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj = WFACP_Contacts_Analytics::get_instance();
				$aero     = $aero_obj->delete_contact( $funnel_id, $get_cids );
				if ( is_array( $aero ) && isset( $aero['db_error'] ) && $aero['db_error'] === true ) {
					return $aero;
				}
			}

			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				$optin_obj = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin     = $optin_obj->delete_contact( $funnel_id, $get_cids );
				if ( is_array( $optin ) && isset( $optin['db_error'] ) && $optin['db_error'] === true ) {
					return $optin;
				}

			}

			if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj = WFOB_Contacts_Analytics::get_instance();
				$bump     = $bump_obj->delete_contact( $funnel_id, $get_cids );
				if ( is_array( $bump ) && isset( $bump['db_error'] ) && $bump['db_error'] === true ) {
					return $bump;
				}
			}

			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj = WFOCU_Contacts_Analytics::get_instance();
				$upsell     = $upsell_obj->delete_contact( $funnel_id, $get_cids );
				if ( is_array( $upsell ) && isset( $upsell['db_error'] ) && $upsell['db_error'] === true ) {
					return $upsell;
				}
			}
		}

		public function get_funnel_contacts_single( $request ) {
			$id                       = (int) $request['cid'];
			$get_all_upsell_records   = [];
			$get_all_optin_records    = [];
			$get_all_checkout_records = [];
			$get_all_bump_records     = [];
			$user_info                = [];
			$funnel_id                = (int) $request['id'];//phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$bwf_contacts    = BWF_Contacts::get_instance();
			$bwf_contact     = $bwf_contacts->get_contact_by( 'id', $id );
			$additional_info = [];
			if ( $bwf_contact instanceof WooFunnels_Contact and $bwf_contact->get_id() > 0 ) {
				$user_info['first_name'] = $bwf_contact->get_f_name();
				$user_info['last_name']  = $bwf_contact->get_l_name();
				$user_info['email']      = $bwf_contact->get_email();
				$additional_info         = [];
				$additional_info[]       = [ 'name' => 'contact_id', 'value' => $bwf_contact->get_id() ];

			}
			$optin_contact_data = [];
			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {

				$optin_obj             = WFFN_Optin_Contacts_Analytics::get_instance();
				$get_all_optin_records = $optin_obj->get_all_contacts_records( $funnel_id, $id );

				if ( isset( $get_all_optin_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_optin_records );
				}

				if ( is_array( $get_all_optin_records ) && count( $get_all_optin_records ) > 0 ) {
					$get_last_optin_data = $get_all_optin_records[ count( $get_all_optin_records ) - 1 ]->data;
					$get_last_optin_data = json_decode( $get_last_optin_data, true );
					if ( is_array( $get_last_optin_data ) && count( $get_last_optin_data ) > 0 ) {
						foreach ( $get_last_optin_data as $k => $d ) {
							if ( in_array( $k, array( 'optin_first_name', 'optin_last_name' ), true ) ) {
								continue;
							}
							$optin_contact_data[] = [ 'name' => $k, 'value' => $d ];
						}
					}
				}

			}

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj                 = WFACP_Contacts_Analytics::get_instance();
				$get_all_checkout_records = $aero_obj->get_all_contacts_records( $funnel_id, $id );

				if ( isset( $get_all_checkout_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_checkout_records );
				}
			}

			if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj             = WFOB_Contacts_Analytics::get_instance();
				$get_all_bump_records = $bump_obj->get_all_contacts_records( $funnel_id, $id );

				if ( isset( $get_all_bump_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_bump_records );
				}
			}

			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj             = WFOCU_Contacts_Analytics::get_instance();
				$get_all_upsell_records = $upsell_obj->get_all_contacts_records( $funnel_id, $id );

				if ( isset( $get_all_upsell_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_upsell_records );
				}
			}

			$records_by_date = $this->sort_by_date( array_merge( $get_all_optin_records, $get_all_checkout_records, $get_all_bump_records, $get_all_upsell_records ) );

			if ( is_array( $records_by_date ) && count( $records_by_date ) > 0 ) {
				$additional_info[] = array( 'name' => 'creation_date', 'value' => $records_by_date[ count( $records_by_date ) - 1 ]->date );
			}

			$additional_info         = array_merge( $additional_info, $optin_contact_data );
			$user_info['additional'] = $this->get_nice_names_for_keys( $additional_info );

			return rest_ensure_response( array(
				'user_info' => $user_info,
				'records'   => $this->add_links_to_records( $records_by_date, $funnel_id )
			) );

		}

		public function get_nice_names_for_keys( $user_info ) {
			$new_user_info = [];

			$nice_names = array(
				'optin_phone'      => __( 'Phone', 'funnel-builder' ),
				'creation_date'    => __( 'Creation Date', 'funnel-builder' ),
				'user_id'          => __( 'User ID', 'funnel-builder' ),
				'optin_first_name' => __( 'First Name', 'funnel-builder' ),
				'optin_last_name'  => __( 'Last Name', 'funnel-builder' ),
				'contact_id'       => __( 'Contact ID', 'funnel-builder' ),
			);
			foreach ( $user_info as $key => $value ) {
				if ( isset( $nice_names[ $value['name'] ] ) ) {
					$new_user_info[ $key ] = array( 'name' => $nice_names[ $value['name'] ], 'value' => $value['value'] );
				} else {
					$new_user_info[ $key ] = array( 'name' => $value['name'], 'value' => $value['value'] );
				}
			}

			return $new_user_info;
		}

		/**
		 * Sorting in descending order
		 *
		 * @param $records
		 *
		 * @return mixed
		 */
		public function sort_by_date( $records ) {
			usort( $records, function ( $a, $b ) {
				if ( strtotime( $a->date ) > strtotime( $b->date ) ) {
					return - 1;
				}
				if ( strtotime( $a->date ) < strtotime( $b->date ) ) {
					return 1;
				}
				if ( strtotime( $a->date ) === strtotime( $b->date ) ) {
					return 1;
				}
			} );

			return $records;
		}

		public function add_links_to_records( $records, $funnel_id ) {

			foreach ( $records as &$record ) {
				if ( is_null( $record->object_name ) ) {
					continue;
				}
				switch ( $record->type ) {
					case 'optin':
						$record->link = admin_url( 'admin.php?page=wf-op&edit=' . $record->object_id . '&section=design&wffn_funnel_ref=' . $funnel_id );
						break;
					case 'checkout':
						$record->link = admin_url( 'admin.php?page=wfacp&wfacp_id=' . $record->object_id . '&wffn_funnel_ref=' . $funnel_id );
						break;
					case 'upsell':
						$record->link = admin_url( 'admin.php?page=upstroke&section=offers&edit=' . $record->object_id . '&wffn_funnel_ref=' . $funnel_id );
						break;
					case 'bump':
						$record->link = admin_url( 'admin.php?page=wfob&section=products&wfob_id=' . $record->object_id . '&wffn_funnel_ref=' . $funnel_id );

						break;
					default:
						$record->link = get_permalink( $record->object_id );
				}

			}

			return $records;
		}


		public function get_total_count( $funnel_id, $search = '' ) {
			global $wpdb;

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj = WFACP_Contacts_Analytics::get_instance();
				$aero_sql = $aero_obj->get_contacts_by_funnel( $funnel_id );
			}
			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				$optin_obj = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin_sql = $optin_obj->get_contacts_by_funnel( $funnel_id );

			}
			if ( ! isset( $aero_sql ) ) {
				$get_total_possible_contacts = $wpdb->get_results( $optin_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			} else {
				$get_total_possible_contacts = $wpdb->get_results( 'SELECT id FROM (' . $aero_sql . ')u UNION ALL (' . $optin_sql . ')', ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			}

			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return 0;
			}

			$get_total_possible_contacts = array_unique( wp_list_pluck( $get_total_possible_contacts, 'id' ) );


			$get_total_possible_contacts_str = implode( ',', $get_total_possible_contacts );
			if ( ! is_array( $get_total_possible_contacts ) || count( $get_total_possible_contacts ) === 0 ) {
				return 0;
			}


			$str = "SELECT count(`id`) FROM " . $wpdb->prefix . "bwf_contact where `id` IN (" . $get_total_possible_contacts_str . ")";
			if ( ! empty( $search ) ) {
				$str .= " AND (f_name LIKE '%$search%' OR email LIKE '%$search%') ";
			}

			return $wpdb->get_var( $str );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		}
	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'wffn_contacts', 'WFFN_Funnel_Contacts' );
	}
}
