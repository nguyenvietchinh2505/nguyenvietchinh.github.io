<?php

class WFFN_Node_WC_Checkout extends WFFN_Base_Node_Step {

	public $id;
	public $type;

	public function __construct( $data ) {

		$this->id   = $data['id'];
		$this->type = $data['type'];

		parent::__construct( $data );
	}

	public function prepare_node_config( &$index, &$configs, &$edge_index ) {

		if ( ! $this->is_enable() ) {
			return;
		}
		/**
		 * Check if we need to handle any experiment node or steps
		 */
		if ( true === $this->data['experiment'] ) {
			$expriment = $this->get_running_experiment( $this->id );

			if ( false !== $expriment ) {
				$experiment_data                      = $this->data;
				$experiment_data['experiment_object'] = $expriment;
				$experiment_data['type_class']        = __CLASS__;
				$experiment_node                      = new WFFN_Node_experiments( $experiment_data );
				$experiment_node->prepare_node_config( $index, $configs, $edge_index );

			} else {
				$this->register_a_node( $index, $configs, $edge_index );
			}
		} else {

			$this->register_a_node( $index, $configs, $edge_index );
		}


		if ( isset( $this->data['no_edge'] ) && true === $this->data['no_edge'] ) {
			return;
		}

		/**
		 * resolve the merge tag we left for the terminate variant
		 */
		$this->rearrange_variant_terminate( $index, $configs );


	}

	public function register_a_node( &$index, &$configs, &$edge_index ) {
		parent::prepare_node_config( $index, $configs, $edge_index );
		$get_step        = WFFN_Core()->steps->get_integration_object( $this->type );
		$view_experiment = '';

		$link = $get_step->get_entity_edit_link( $this->id );
		if ( isset( $this->data['experiment_object'] ) ) {
			$view_experiment = '&view_experiment=' . $this->data['experiment_object']->get_id();
			$link            .= '&bwf_exp_ref=' . $this->data['experiment_object']->get_id();
		}
		$get_parent_config = array(
			'type'      => 'wc_checkout',
			'id'        => $this->id,
			'sub_title' => $get_step->get_title(),
			'title'     => $get_step->get_entity_title( $this->id ),
			'edit_link' => $link,
			'view_link' => $get_step->get_entity_view_link( $this->id ),
		);
		if ( isset( $this->data['experiment_object'] ) ) {
			$get_parent_config['control'] = false;
			if ( absint( $this->data['experiment_object']->get_control() ) === absint( $this->id ) ) {
				$get_parent_config['control'] = true;
			}

		}

		$get_parent_config['stats'] = $this->get_stats( $this->id );
		$get_parent_config['tabs']  = array(
			array(
				'name'  => 'analytics',
				'title' => __( 'Analytics', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/analytics/' . $this->id . '?type=wc_checkout' . $view_experiment.'&fid='.$this->data['funnel_id'] : '',
			),
			array(
				'name'  => 'contacts',
				'title' => __( 'Contacts', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/contacts/' . $this->id . '?type=wc_checkout' . $view_experiment : '',
			),
			array(
				'name'  => 'order-bump',
				'title' => __( 'Order Bumps', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/order-bump/' . $this->id : '',
			),
			array(
				'name'  => 'experiment',
				'title' => __( 'A/B Tests', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/experiment/?control=' . $this->id . '&data=true' : '',
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

		if ( 0 === intval( $step_id ) ) {
			return $data;
		}

		if ( ! class_exists( 'WFACP_Contacts_Analytics' ) || version_compare( WFACP_VERSION, '2.0.7', '<' ) || ( class_exists( 'WFOB_Core' ) && version_compare( WFOB_VERSION, '1.8,1', '<=' ) ) ) {
			return $data;
		}

		if ( class_exists( 'WFFN_Pro_Core' ) && ! isset( $this->data['experiment_object'] ) ) {
			$get_step = WFFN_Pro_Core()->steps->get_integration_object( $this->type );
			if ( $get_step instanceof WFFN_Pro_Step ) {
				$ids = $get_step->maybe_get_ab_variants( $step_id );
			}
		}
		$ids[] = $step_id;

		if ( isset( $this->data['experiment_object'] ) ) {
			$get_controller = BWFABT_Core()->controllers->get_integration( $this->data['experiment_object']->get_type() );
			$get_data       = $get_controller->get_analytics_data( $ids, $this->data['experiment_object']->get_id() );

			if ( is_array( $get_data ) && count( $get_data ) > 0 && isset( $get_data[ $step_id ] ) ) {
				$data['views']           = $get_data[ $step_id ]['views'];
				$data['conversions']     = $get_data[ $step_id ]['conversions'];
				$data['conversion_rate'] = $get_data[ $step_id ]['conversion_rate'];
				$data['revenue']         = $get_data[ $step_id ]['revenue'];
			}

			return $data;
		}

		$step_ids = implode( ',', $ids );


		$aero_sql = "SELECT SUM(total_revenue) as 'total_revenue',COUNT(ID) as cn FROM " . $wpdb->prefix . 'wfacp_stats' . " WHERE wfacp_id IN(" . $step_ids . ") " . $con_date_query . "  ORDER BY wfacp_id ASC";

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
			$data['revenue']     = is_null( $get_all_checkout_records['total_revenue'] ) ? 0 : floatval( number_format( $get_all_checkout_records['total_revenue'], 2, '.', '' ) );
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

		if ( class_exists( 'WFFN_Pro_Core' ) && ! isset( $this->data['view_experiment'] ) ) {
			$get_step = WFFN_Pro_Core()->steps->get_integration_object( $type );
			if ( $get_step instanceof WFFN_Pro_Step ) {
				$ids = $get_step->maybe_get_ab_variants( $step_id );
			}
		}

		$ids[] = $step_id;

		if ( isset( $this->data['view_experiment'] ) ) {
			$get_controller = BWFABT_Core()->controllers->get_integration( 'aero' );
			$get_ab_data    = $get_controller->get_analytics_data( $ids, $this->data['view_experiment'], $is_interval, $int_request );
			if ( is_array( $get_ab_data ) && count( $get_ab_data ) > 0 ) {
				if ( isset( $get_ab_data[ $step_id ] ) && '' === $is_interval ) {
					$get_data[0]['viewed']    = $get_ab_data[ $step_id ]['views'];
					$get_data[0]['converted'] = $get_ab_data[ $step_id ]['conversions'];
					$get_data[0]['revenue']   = $get_ab_data[ $step_id ]['revenue'];
				}
				if ( 'interval' === $is_interval ) {
					return $get_ab_data;
				}
			}

			return $get_data;
		}

		$step_ids = implode( ',', $ids );

		$aero_sql   = "SELECT SUM(total_revenue) as revenue, COUNT(ID) as converted " . $interval_query . " FROM " . $wpdb->prefix . 'wfacp_stats' . " WHERE wfacp_id IN(" . $step_ids . ") AND `" . $date_col . "` >= '" . $conv_date . "' AND `" . $date_col . "` < '" . $end_date . "' " . $group_by . " ORDER BY wfacp_id ASC";
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

		if ( $funnel_id === 0 || ! defined( 'WFFN_PRO_FILE' ) ) {
			return $bump_data;
		}

		$get_step = WFFN_Core()->steps->get_integration_object( 'wc_checkout' );

		$substeps = $get_step->get_substeps( $funnel_id, $step_id, array( 'wc_order_bump' ) );
		$substeps = $this->maybe_add_ab_substep_variants( $substeps );


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

	public function is_enable() {
		if ( false === WFFN_Core()->steps->get_integration_object( 'wc_checkout' ) ) {
			return false;
		}

		return true;
	}

	public function get_revenue_label() {
		return __( 'Checkout Revenue', 'funnel-builder' );
	}

}