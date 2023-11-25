<?php

if ( ! class_exists( 'WFFN_REST_API_Dashboard_EndPoint' ) ) {
	class WFFN_REST_API_Dashboard_EndPoint extends WFFN_REST_Controller {

		private static $ins = null;

		/**
		 * WFFN_REST_API_Dashboard_EndPoint constructor.
		 */
		public function __construct() {

			add_action( 'rest_api_init', [ $this, 'register_endpoint' ], 12 );
		}

		/**
		 * @return WFFN_REST_API_Dashboard_EndPoint|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_endpoint() {
			register_rest_route( 'woofunnels-analytics', '/funnels/stats/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_stats' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( 'woofunnels-analytics', '/funnels/top-funnels/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_top_funnels' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( 'woofunnels-analytics', '/funnels/top-optins/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_top_optins' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( 'woofunnels-analytics', '/funnels/top-upsells/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_top_upsells' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( 'woofunnels-analytics', '/funnels/top-bumps/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_top_bumps' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( 'woofunnels-analytics', '/funnels/top-checkouts/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_top_checkouts' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( 'woofunnels-analytics', '/stream/timeline/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_timeline_funnels' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
		}

		/**
		 * @return bool
		 */
		public function get_read_api_permission_check() {
			if ( WFFN_Core()->role->user_access( 'analytics', 'read' ) ) {
				return true;
			}

			return false;
		}

		public function get_stats( $request ) {
			$response = array();

			$response['totals'] = $this->prepare_data( $request );

			return rest_ensure_response( $response );
		}

		public function prepare_data( $request ) {

			$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );

			$funnel_id      = '';
			$total_revenue  = null;
			$upsell_revenue = 0;
			$bump_revenue   = 0;

			$get_total_orders = $this->get_total_orders( $start_date, $end_date );
			if ( isset( $get_total_orders['db_error'] ) ) {
				return $get_total_orders;
			}
			$get_total_revenue = $this->get_total_revenue( $funnel_id, $start_date, $end_date );
			if ( isset( $get_total_revenue['db_error'] ) ) {
				return $get_total_revenue;
			}
			$get_total_contacts = $this->get_total_contacts( $start_date, $end_date );
			if ( isset( $get_total_contacts['db_error'] ) ) {
				return $get_total_contacts;
			}

			if ( count( $get_total_revenue['aero'] ) > 0 ) {
				$total_revenue = $get_total_revenue['aero'][0]['sum_aero'];
			}
			if ( count( $get_total_revenue['bump'] ) > 0 ) {
				$bump_revenue  = $get_total_revenue['bump'][0]['sum_bump'];
				$total_revenue += $bump_revenue;
			}
			if ( count( $get_total_revenue['upsell'] ) > 0 ) {
				$upsell_revenue = $get_total_revenue['upsell'][0]['sum_upsells'];
				$total_revenue  += $upsell_revenue;
			}

			$result = [
				'orders'              => $get_total_orders,
				'revenue'             => is_null( $total_revenue ) ? 0 : $total_revenue,
				'contacts'            => $get_total_contacts,
				'upsell_revenue'      => floatval( $upsell_revenue ),
				'bump_revenue'        => floatval( $bump_revenue ),
				'average_order_value' => ( is_null( $total_revenue ) || ( is_null( $get_total_orders ) || intval( $get_total_orders ) === 0 ) ) ? 0 : $total_revenue / $get_total_orders,
				'revenue_per_contact' => ( is_null( $total_revenue ) || ( is_null( $get_total_contacts ) || intval( $get_total_contacts ) === 0 ) ) ? 0 : $total_revenue / $get_total_contacts,
			];

			return $result;

		}

		public function get_top_funnels( $request ) {


			$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );

			$aero_funnels   = [];
			$bump_funnels   = [];
			$upsell_funnels = [];
			$response       = [];
			$limit          = 5;

			/**
			 * get aero funnels
			 */
			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj     = WFACP_Contacts_Analytics::get_instance();
				$aero_funnels = $aero_obj->get_top_funnels( $start_date, $end_date, $limit );
				if ( isset( $aero_funnels['db_error'] ) ) {
					return $aero_funnels;
				}
			}

			/**
			 * get bump funnels
			 */
			if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj     = WFOB_Contacts_Analytics::get_instance();
				$bump_funnels = $bump_obj->get_top_funnels( $start_date, $end_date, $limit );
				if ( isset( $bump_funnels['db_error'] ) ) {
					return $bump_funnels;
				}
			}

			/**
			 * get upsells funnels
			 */
			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj     = WFOCU_Contacts_Analytics::get_instance();
				$upsell_funnels = $upsell_obj->get_top_funnels( $start_date, $end_date, $limit );
				if ( isset( $bump_funnels['db_error'] ) ) {
					return $bump_funnels;
				}
			}

			$funnels = array_merge( $aero_funnels, $bump_funnels, $upsell_funnels );


			$total_sum  = [];
			$funnel_ids = [];
			$i          = 0;

			if ( count( $funnels ) < $limit ) {
				$funnels_existing = WFFN_Core()->admin->get_funnels( array( 'limit' => 5 ) );

				foreach ( $funnels_existing['items'] as $item ) {
					$total_sum[] = array( 'fid' => absint( $item['id'] ), 'title' => $item['title'], 'revenue' => 0 );

				}
			}


			if ( ! empty( $funnels ) ) {
				foreach ( $funnels as $item ) {
					$fid   = $item['fid'];
					$total = $item['total'];

					if ( array_key_exists( $fid, $funnel_ids ) ) {
						$total_sum[ $funnel_ids[ $fid ] ]['revenue'] = ( $total_sum[ $funnel_ids[ $fid ] ]['revenue'] + $total );
					} else {
						$funnel_object      = new WFFN_Funnel( $fid );
						$total_sum[ $i ]    = array( 'fid' => absint( $fid ), 'title' => $funnel_object->get_title(), 'revenue' => $total );
						$funnel_ids[ $fid ] = $i;
						$i ++;
					}
				}
			}
			if ( ! empty( $total_sum ) ) {
				usort( $total_sum, function ( $a, $b ) {
					if ( $a['revenue'] < $b['revenue'] ) {
						return 1;
					}
					if ( $a['revenue'] > $b['revenue'] ) {
						return - 1;
					}
					if ( $a['revenue'] === $b['revenue'] ) {
						return - 1;
					}
				} );
			} else {
				return $response;
			}


			$total_sum = array_slice( $total_sum, 0, $limit );

			foreach ( $total_sum as &$total ) {
				$total['link'] = WFFN_Common::get_funnel_edit_link( $total['fid'] );
			}

			return $total_sum;

		}

		public function get_top_upsells( $request ) {

			$items = [];

			if ( ! class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				return $items;
			}

			$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );

			$limit = 5;

			$upsell_obj  = WFOCU_Contacts_Analytics::get_instance();
			$upsell_data = $upsell_obj->get_top_upsells( $start_date, $end_date, $limit );

			if ( isset( $upsell_data['db_error'] ) ) {
				return $upsell_data;
			}

			if ( count( $upsell_data ) < $limit ) {
				$args         = array(
					'post_type'   => WFOCU_Common::get_offer_post_type_slug(),
					'post_status' => 'any',
					'showposts'   => $limit - count( $upsell_data )
				);

				$exclude_ids = wp_list_pluck( $upsell_data, 'object_id' );
				if ( is_array( $exclude_ids ) && count( $exclude_ids ) > 0 ) {
					$args['post__not_in'] = $exclude_ids;
				}

				$query_result = new WP_Query( $args );


				if ( $query_result->have_posts() ) {
					foreach ( $query_result->posts as $p ) {
						$obj             = new stdClass();
						$obj->object_id  = $p->ID;
						$obj->title      = $p->post_title;
						$obj->conversion = 0;
						$obj->revenue    = 0;
						$upsell_data[]   = $obj;
					}
				}
			}

			if ( ! empty( $upsell_data ) ) {
				usort( $upsell_data, function ( $a, $b ) {
					if ( $a->revenue < $b->revenue ) {
						return 1;
					}
					if ( $a->revenue > $b->revenue ) {
						return - 1;
					}
					if ( $a->revenue === $b->revenue ) {
						return - 1;
					}
				} );

				foreach ( $upsell_data as &$item ) {
					$item->link = WFFN_Common::get_step_edit_link( $item->object_id, 'upsell' );
				}
			}

			return $upsell_data;

		}

		public function get_top_bumps( $request ) {

			$items = [];
			if ( ! class_exists( 'WFOB_Contacts_Analytics' ) ) {
				return $items;
			}

			$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );

			$limit = 5;

			$bump_obj  = WFOB_Contacts_Analytics::get_instance();
			$bump_data = $bump_obj->get_top_bumps( $start_date, $end_date, $limit );

			if ( isset( $bump_data['db_error'] ) ) {
				return $bump_data;
			}

			if ( count( $bump_data ) < $limit ) {
				$args         = array(
					'post_type'   => WFOB_Common::get_bump_post_type_slug(),
					'post_status' => 'any',
					'showposts'   => $limit - count( $bump_data ),
				);

				$exclude_ids = wp_list_pluck( $bump_data, 'bid' );
				if ( is_array( $exclude_ids ) && count( $exclude_ids ) > 0 ) {
					$args['post__not_in'] = $exclude_ids;
				}

				$query_result = new WP_Query( $args );

				if ( $query_result->have_posts() ) {
					foreach ( $query_result->posts as $p ) {
						$obj             = new stdClass();
						$obj->bid        = $p->ID;
						$obj->title      = $p->post_title;
						$obj->conversion = 0;
						$obj->revenue    = 0;
						$bump_data[]     = $obj;
					}
				}
			}
			if ( ! empty( $bump_data ) ) {
				usort( $bump_data, function ( $a, $b ) {
					if ( $a->revenue < $b->revenue ) {
						return 1;
					}
					if ( $a->revenue > $b->revenue ) {
						return - 1;
					}
					if ( $a->revenue === $b->revenue ) {
						return - 1;
					}
				} );

				foreach ( $bump_data as &$item ) {
					$item->link = WFFN_Common::get_step_edit_link( $item->bid, 'bump' );
				}
			}

			return $bump_data;

		}

		public function get_top_checkouts( $request ) {

			$items = [];

			if ( ! class_exists( 'WFACP_Contacts_Analytics' ) ) {
				return $items;
			}

			$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );

			$limit = 5;

			$aero_obj  = WFACP_Contacts_Analytics::get_instance();
			$aero_data = $aero_obj->get_top_checkouts( $start_date, $end_date, $limit );

			if ( isset( $aero_data['db_error'] ) ) {
				return $aero_data;
			}

			if ( count( $aero_data ) < $limit ) {
				$args         = array(
					'post_type'   => WFACP_Common::get_post_type_slug(),
					'post_status' => 'any',
					'showposts'   => $limit - count( $aero_data ),
				);

				$exclude_ids = wp_list_pluck( $aero_data, 'wfacp_id' );
				if ( is_array( $exclude_ids ) && count( $exclude_ids ) > 0 ) {
					$args['post__not_in'] = $exclude_ids;
				}

				$query_result = new WP_Query( $args );

				if ( $query_result->have_posts() ) {
					foreach ( $query_result->posts as $p ) {
						$obj             = new stdClass();
						$obj->wfacp_id   = $p->ID;
						$obj->title      = $p->post_title;
						$obj->conversion = 0;
						$obj->revenue    = 0;
						$aero_data[]     = $obj;
					}
				}
			}
			if ( ! empty( $aero_data ) ) {
				usort( $aero_data, function ( $a, $b ) {
					if ( $a->revenue < $b->revenue ) {
						return 1;
					}
					if ( $a->revenue > $b->revenue ) {
						return - 1;
					}
					if ( $a->revenue === $b->revenue ) {
						return - 1;
					}
				} );

				foreach ( $aero_data as &$item ) {
					$item->link = WFFN_Common::get_step_edit_link( $item->wfacp_id, 'bump' );
				}
			}

			return $aero_data;

		}

		public function get_top_optins( $request ) {

			$response = [];

			if ( ! class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				return $response;
			}

			$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( YEAR_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );
			$limit      = 5;

			$optin_obj = WFFN_Optin_Contacts_Analytics::get_instance();

			$result = $optin_obj->get_top_optins( $start_date, $end_date, $limit );

			if ( isset( $result['db_error'] ) ) {
				return $result;
			}


			if ( count( $result ) < $limit ) {
				$args = array(
					'post_type'   => array( WFOPP_Core()->optin_pages->get_post_type_slug() ),
					'post_status' => 'any',
					'showposts'   => $limit - count( $result ),
				);

				$exclude_ids = wp_list_pluck( $result, 'step_id' );
				if ( is_array( $exclude_ids ) && count( $exclude_ids ) > 0 ) {
					$args['post__not_in'] = $exclude_ids;
				}

				$query_result = new WP_Query( $args );
				if ( $query_result->have_posts() ) {
					foreach ( $query_result->posts as $p ) {
						$obj             = new stdClass();
						$obj->step_id    = $p->ID;
						$obj->title      = $p->post_title;
						$obj->conversion = 0;
						$result[]        = $obj;
					}
				}
			}
			if ( empty( $result ) ) {
				return $response;
			}

			foreach ( $result as &$item ) {
				$item->link = WFFN_Common::get_step_edit_link( $item->step_id, 'optin' );
			}

			return $result;

		}

		public function get_timeline_funnels() {
			global $wpdb;
			$aero_timeline   = '';
			$bump_timeline   = '';
			$upsell_timeline = '';
			$optin_timeline  = '';
			$limit           = 20;
			$can_union       = false;
			/**
			 * get aero timeline
			 */
			if ( class_exists( 'WFACP_Contacts_Analytics' ) && defined( 'WFACP_VERSION' ) && ( version_compare( WFACP_VERSION, '2.0.7', '>=' ) ) ) {
				$aero_obj      = WFACP_Contacts_Analytics::get_instance();
				$aero_timeline = $aero_obj->get_timeline_data_query( $limit );
				$can_union     = true;
			}

			/**
			 * get bump timeline
			 */
			if ( class_exists( 'WFOB_Contacts_Analytics' ) && defined( 'WFOB_VERSION' ) && ( version_compare( WFOB_VERSION, '1.8.1', '>=' ) ) ) {
				$bump_obj      = WFOB_Contacts_Analytics::get_instance();
				$bump_timeline = $bump_obj->get_timeline_data_query( $limit );
				$can_union     = true;
			}

			/**
			 * get upsells timeline
			 */
			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj      = WFOCU_Contacts_Analytics::get_instance();
				$upsell_timeline = $upsell_obj->get_timeline_data_query( $limit );
				$can_union       = true;
			}

			/**
			 * get optin timeline
			 */

			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				$optin_obj      = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin_timeline = $optin_obj->get_timeline_data_query( $limit, 'DESC', 'date', $can_union );
			}
			if ( $can_union === true ) {
				$final_q = 'SELECT u.id as id, u.cid as cid, u.order_id as order_id, u.total_revenue as tot, u.post_title as post_title, contact.f_name as f_name, contact.l_name as l_name, u.type as type, u.date as date FROM (';
				if ( ! empty( $aero_timeline ) ) {
					$final_q .= '(';
					$final_q .= $aero_timeline;
					$final_q .= ') ';
				}
				if ( ! empty( $bump_timeline ) ) {
					$final_q .= 'UNION ALL (';
					$final_q .= $bump_timeline;
					$final_q .= ') ';
				}
				if ( ! empty( $optin_timeline ) ) {
					$final_q .= 'UNION ALL (';
					$final_q .= $optin_timeline;
					$final_q .= ') ';
				}
				if ( ! empty( $upsell_timeline ) ) {
					$final_q .= 'UNION ALL (';
					$final_q .= $upsell_timeline;
					$final_q .= ') ';
				}

				$final_q .= ')u LEFT JOIN ' . $wpdb->prefix . 'bwf_contact AS contact ON contact.id=cid WHERE contact.id != "" ORDER BY date DESC LIMIT ' . $limit;
			} else {
				$final_q = $optin_timeline;
			}

			$steps    = $wpdb->get_results( $final_q, ARRAY_A ); //phpcs:ignore
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( ! is_array( $steps ) || count( $steps ) === 0 ) {
				return [];
			}

			foreach ( $steps as &$step ) {
				if ( isset( $step['id'] ) && isset( $step['type'] ) ) {
					$step['edit_link'] = WFFN_Common::get_step_edit_link( $step['id'], $step['type'] );
				}
				if ( isset( $step['order_id'] ) ) {
					if ( wffn_is_wc_active() ) {
						$order = wc_get_order( $step['order_id'] );
						if ( $order instanceof WC_Order ) {
							$step['order_edit_link'] = $order->get_edit_order_url();
						} else {
							$step['order_edit_link'] = '';
						}
					} else {
						$step['order_edit_link'] = '';
					}

				}
			}

			return $steps;

		}

		public function get_total_contacts( $start_date, $end_date ) {

			$aero_cids  = [];
			$optin_cids = [];

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj  = WFACP_Contacts_Analytics::get_instance();
				$aero_cids = $aero_obj->get_total_cids( $start_date, $end_date );
				if ( is_array( $aero_cids ) && isset( $aero_cids['db_error'] ) ) {
					return $aero_cids;
				}
			}

			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				$op_obj     = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin_cids = $op_obj->get_total_cids( $start_date, $end_date );
				if ( is_array( $optin_cids ) && isset( $optin_cids['db_error'] ) ) {
					return $aero_cids;
				}
			}

			$contacts = count( array_merge( $aero_cids, $optin_cids ) );

			return $contacts;


		}

		public function get_unique_visits( $funnel_id, $start_date, $end_date ) {

			global $wpdb;

			$funnel_id = ( $funnel_id !== '' ) ? " AND object_id = " . $funnel_id . " " : '';

			$unique_views = $wpdb->get_results( "SELECT  SUM(no_of_sessions) as unique_views  FROM `" . $wpdb->prefix . "wfco_report_views` WHERE 1=1 AND date >= '" . $start_date . "' AND date < '" . $end_date . "' AND `type` = 7 " . $funnel_id . " ORDER BY id ASC", ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			return $unique_views;
		}

		public function get_total_orders( $start_date, $end_date ) {

			$total_orders = 0;

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj    = WFACP_Contacts_Analytics::get_instance();
				$aero_orders = $aero_obj->get_total_orders( '', $start_date, $end_date );
				if ( isset( $aero_orders['db_error'] ) ) {
					return $aero_orders;
				}

				if ( isset( $aero_orders['total_orders'] ) && absint( $aero_orders['total_orders'] ) > 0 ) {
					$total_orders = absint( $aero_orders['total_orders'] );
				}
			}

			return $total_orders;
		}

		public function get_total_revenue( $funnel_id, $start_date, $end_date ) {

			$total_revenue_aero    = [];
			$total_revenue_bump    = [];
			$total_revenue_upsells = [];

			/**
			 * get aero revenue
			 */
			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj           = WFACP_Contacts_Analytics::get_instance();
				$total_revenue_aero = $aero_obj->get_total_revenue( $funnel_id, $start_date, $end_date );
				if ( isset( $total_revenue_aero['db_error'] ) ) {
					return $total_revenue_aero;
				}
			}

			/**
			 * get bump revenue
			 */
			if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj           = WFOB_Contacts_Analytics::get_instance();
				$total_revenue_bump = $bump_obj->get_total_revenue( $funnel_id, $start_date, $end_date );
				if ( isset( $total_revenue_bump['db_error'] ) ) {
					return $total_revenue_bump;
				}
			}

			/**
			 * get upsells revenue
			 */
			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj            = WFOCU_Contacts_Analytics::get_instance();
				$total_revenue_upsells = $upsell_obj->get_total_revenue( $funnel_id, $start_date, $end_date );
				if ( isset( $total_revenue_upsells['db_error'] ) ) {
					return $total_revenue_upsells;
				}
			}

			return array( 'aero' => $total_revenue_aero, 'bump' => $total_revenue_bump, 'upsell' => $total_revenue_upsells );
		}

		public function get_stats_collection() {
			$params = array();

			$params['after']  = array(
				'type'              => 'string',
				'format'            => 'date-time',
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'funnel-builder' ),
			);
			$params['before'] = array(
				'type'              => 'string',
				'format'            => 'date-time',
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published before a given ISO8601 compliant date.', 'funnel-builder' ),
			);
			$params['limit']  = array(
				'type'              => 'integer',
				'default'           => 5,
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published before a given ISO8601 compliant date.', 'funnel-builder' ),
			);

			return apply_filters( 'wfocu_rest_funnels_dashboard_stats_collection', $params );
		}

	}

	WFFN_REST_API_Dashboard_EndPoint::get_instance();
}
