<?php

class WFFN_Node_Native_Checkout extends WFFN_Base_Node_Step {

	public $id;
	public $type;
	public $funnel_id;

	public function __construct( $data ) {
		$this->funnel_id = $data['funnel_id'];
		parent::__construct( $data );
	}

	public function prepare_node_config( &$index, &$configs, &$edge_index ) {

		if ( ! $this->is_enable() ) {
			return;
		}

		$this->register_a_node( $index, $configs, $edge_index );


	}

	public function register_a_node( &$index, &$configs, &$edge_index ) {
		parent::prepare_node_config( $index, $configs, $edge_index );


		$get_parent_config = array(
			'type'      => 'native_checkout',
			'id'        => 0,
			'sub_title' => 'Checkout',
			'title'     => 'Native Checkout',
			'edit_link' => '#',
			'view_link' => '#',
		);


		$get_parent_config['stats'] = $this->get_stats( 0 );
		$get_parent_config['tabs']  = array(
			array(
				'name'  => 'analytics',
				'title' => __( 'Analytics', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/analytics/0?type=native_checkout' : '',
			),
			array(
				'name'  => 'contacts',
				'title' => __( 'Contacts', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/contacts/0?type=native_checkout&fid=' . $this->data['funnel_id'] : '',
			),
			array(
				'name'  => 'order-bump',
				'title' => __( 'Order Bumps', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/order-bump/0' : '',
			),


		);


		if ( isset( $this->data['experiment_object'] ) ) {
			$ex_key = array_search( 'experiment', array_column( $get_parent_config['tabs'], 'name' ), true );
			if ( false !== $ex_key ) {
				unset( $get_parent_config['tabs'][ $ex_key ] );

			}
			$bump_key = array_search( 'order-bump', array_column( $get_parent_config['tabs'], 'name' ), true );
			if ( false !== $bump_key ) {
				unset( $get_parent_config['tabs'][ $bump_key ] );

			}
		}

		$configs[ count( $configs ) - 1 ]['data'] = $get_parent_config;
		$index ++;
		if ( isset( $this->data['no_edge'] ) && true === $this->data['no_edge'] ) {
			return;
		}
		/**
		 * Prepare a connection edge by passing correct source and target
		 */
		$this->add_edge( $index - 1, $index, $configs, $edge_index );


	}


	public function get_bumps( $step_id, $start_date = '', $end_date = '', $is_interval = '', $int_request = '' ) {
		global $wpdb;

		$date_col   = "date";
		$group_by   = '';
		$date_query = '';
		$get_data   = [];

		if ( ! class_exists( 'WFACP_Contacts_Analytics' ) || version_compare( WFACP_VERSION, '2.0.7', '<' ) ) {
			return $get_data;
		}
		$interval_query = '';
		if ( 'interval' === $is_interval ) {
			$get_interval   = $this->get_interval_format_query( $int_request, $date_col );
			$interval_query = $get_interval['interval_query'];
			$interval_group = $get_interval['interval_group'];
			$group_by       = "GROUP BY " . $interval_group;

		}

		if ( $start_date !== '' && $end_date !== '' ) {
			$date_query = " AND `" . $date_col . "` >= '" . $start_date . "' AND `" . $date_col . "` < '" . $end_date . "' ";
		}

		$funnel_id = isset( $this->data['funnel_id'] ) ? $this->data['funnel_id'] : 0;

		$bump_data = [];

		if ( $funnel_id === 0 || ! defined('WFFN_PRO_FILE') ) {
			return $bump_data;
		}

		$get_step = WFFN_Core()->steps->get_integration_object( 'wc_checkout' );

		$substeps = $get_step->get_substeps( $funnel_id, $step_id, array( 'wc_order_bump' ) );


		if ( ! is_array( $substeps ) || count( $substeps ) === 0 ) {
			return $bump_data;
		}

		foreach ( $substeps as $subtype => $substep_ids ) {
			if ( 'wc_order_bump' === $subtype && is_array( $substep_ids ) && count( $substep_ids ) ) {
				$get_substep = WFFN_Core()->substeps->get_integration_object( $subtype );
				if ( ! $get_substep instanceof WFFN_Substep ) {
					break;
				}

				$bump_data = $substep_ids;
			}
		}


		if ( ! empty( $bump_data ) ) {
			$all_bumps     = array_values( $bump_data );
			$all_bumps_str = implode( ',', $all_bumps );
			$bump_sql      = "SELECT COUNT(CASE WHEN converted = 1 THEN 1 END) AS `converted`,SUM(bump.total) as 'total_revenue',COUNT(bump.ID) as viewed, 'bump' as 'type' " . $interval_query . "  FROM " . $wpdb->prefix . "wfob_stats AS bump WHERE bump.bid IN (" . $all_bumps_str . ") " . $date_query . " " . $group_by;

			$get_all_bump_records = $wpdb->get_results( $bump_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}

				if ( is_array( $get_all_bump_records ) && count( $get_all_bump_records ) > 0 ) {
					return $get_all_bump_records;
				}
			}


			return $bump_data;
		}
	}

	/**
	 * @param $step_id
	 *
	 * @return array|false[]
	 */
	public function get_stats( $step_id ) {
		global $wpdb;

		$data = array(
			'views'           => 0,
			'conversions'     => 0,
			'conversion_rate' => 0,
			'revenue'         => 0,
		);

		$date_query     = '';
		$con_date_query = '';
		$view_type      = 4;


		if ( ! class_exists( 'WFACP_Contacts_Analytics' ) || version_compare( WFACP_VERSION, '2.0.7', '<' ) || ( class_exists( 'WFOB_Core' ) && version_compare( WFOB_VERSION, '1.8,1', '<=' ) ) ) {
			return $data;
		}

		$ids   = [];
		$ids[] = $step_id;


		$step_ids = implode( ',', $ids );


		$aero_sql = "SELECT SUM(total_revenue) as 'total_revenue',COUNT(ID) as cn FROM " . $wpdb->prefix . 'wfacp_stats' . " WHERE wfacp_id IN(" . $step_ids . ") && fid = $this->funnel_id " . $con_date_query . "  ORDER BY wfacp_id ASC";

		$get_all_checkout_records = $wpdb->get_row( $aero_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		if ( is_array( $get_all_checkout_records ) && count( $get_all_checkout_records ) > 0 ) {

			if ( ! isset( $this->data['experiment_object'] ) ) {
				$get_bumps = $this->get_bumps( $step_id );
			}

			if ( is_array( $get_bumps ) && count( $get_bumps ) > 0 ) {
				foreach ( $get_bumps as $get_bump ) {
					$get_all_checkout_records['total_revenue'] += $get_bump['total_revenue'];
				}
			}
			$data['revenue'] = is_null( $get_all_checkout_records['total_revenue'] ) ? 0 : floatval( number_format( $get_all_checkout_records['total_revenue'], 2, '.', '' ) );

			$data['conversions'] = intval( $get_all_checkout_records['cn'] );

		}

		$get_query = "SELECT object_id, SUM( CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END ) AS viewed FROM " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") " . $date_query . " ORDER BY object_id ASC";
		$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
			$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
			$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $data['conversions'] );
		}

		return $data;
	}


	public function is_enable() {
		if ( false === WFFN_Core()->steps->get_integration_object( 'wc_checkout' ) ) {
			return false;
		}

		return true;
	}

	public function get_analytics_data( $step_id, $type, $start_date, $end_date, $is_interval, $int_request ) {
		global $wpdb;
		$date_col       = "date";
		$interval_query = '';
		$group_by       = '';
		$ids            = [];
		$get_data       = [];
		$get_bumps      = [];
		$conv_date      = $start_date;
		$view_type      = 4;

		if ( ! class_exists( 'WFACP_Contacts_Analytics' ) || version_compare( WFACP_VERSION, '2.0.7', '<' ) ) {
			return $get_data;
		}

		if ( 'interval' === $is_interval ) {
			$get_interval   = $this->get_interval_format_query( $int_request, $date_col );
			$interval_query = $get_interval['interval_query'];
			$interval_group = $get_interval['interval_group'];
			$group_by       = "GROUP BY " . $interval_group;

		}


		$ids[] = $step_id;


		$step_ids = implode( ',', $ids );

		/**
		 * get store checkout data with match funnel id 
		 */
		$aero_sql = "SELECT SUM(total_revenue) as revenue, COUNT(ID) as converted " . $interval_query . " FROM " . $wpdb->prefix . 'wfacp_stats' . " WHERE wfacp_id IN(" . $step_ids . ") AND fid = ".$this->funnel_id." AND `" . $date_col . "` >= '" . $conv_date . "' AND `" . $date_col . "` < '" . $end_date . "' " . $group_by . " ORDER BY wfacp_id ASC";

		$get_record = $wpdb->get_results( $aero_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		$get_query = "SELECT SUM( CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END ) AS viewed, 0 AS revenue " . $interval_query . " FROM " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") AND `" . $date_col . "` >= '" . $start_date . "' AND `" . $date_col . "` < '" . $end_date . "' " . $group_by . " ORDER BY object_id ASC";
		$get_data  = $wpdb->get_results( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}


		if ( is_array( $get_data ) && count( $get_data ) > 0 ) {

			if ( ! isset( $this->data['view_experiment'] ) ) {
				$get_bumps = $this->get_bumps( $step_id, $start_date, $end_date, $is_interval, $int_request );
			}

			foreach ( $get_data as &$value ) {
				$value['converted']    = 0;
				$value['bump_revenue'] = 0;
				if ( is_array( $get_record ) && count( $get_record ) ) {

					foreach ( $get_record as $record ) {
						if ( isset( $value['time_interval'] ) && isset( $record['time_interval'] ) && $value['time_interval'] === $record['time_interval'] ) {
							if ( is_array( $get_bumps ) && count( $get_bumps ) > 0 ) {

								foreach ( $get_bumps as $get_bump ) {

									if ( isset( $get_bump['time_interval'] ) && $record['time_interval'] === $get_bump['time_interval'] ) {
										$value['bump_revenue'] = floatval( $get_bump['total_revenue'] );
									}
								}
							}

							$value['converted'] = intval( $record['converted'] );
							$value['revenue']   = floatval( number_format( $record['revenue'], 2, '.', '' ) );
						}
						if ( 'interval' !== $is_interval ) {
							if ( is_array( $get_bumps ) && count( $get_bumps ) > 0 ) {
								foreach ( $get_bumps as $get_bump ) {
									$value['bump_revenue'] += $get_bump['total_revenue'];
								}
							}
							$value['converted'] = intval( $record['converted'] );
							$value['revenue']   = floatval( number_format( $record['revenue'], 2, '.', '' ) );

						}
					}


				}

			}
		}

		return $get_data;

	}

	public function get_revenue_label() {
		return __( 'Checkout Revenue', 'funnel-builder' );
	}

}