<?php

class WFFN_Node_Offers extends WFFN_Base_Node_Step {


	public $id;

	public function __construct( $data ) {

		$this->id = $data['id'];
		parent::__construct( $data );
	}

	public function prepare_node_config( &$index, &$configs, &$edge_index ) {
		parent::prepare_node_config( $index, $configs, $edge_index );
		$get_step        = WFFN_Core()->steps->get_integration_object( 'wc_upsells' );
		$view_experiment = '';
		$upsell_id       = 0;

		if ( isset( $this->data['funnel_id'] ) ) {
			$upsell_id = $this->data['funnel_id'];
		}
		$link = $get_step->get_entity_edit_link( $upsell_id );
		if ( isset( $this->data['experiment_object'] ) ) {
			$view_experiment = '&view_experiment=' . $this->data['experiment_object']->get_id();
			$link            .= '&bwf_exp_ref=' . $this->data['experiment_object']->get_id();
		}

		$get_parent_config = array(
			'type'      => 'offer',
			'id'        => $this->id,
			'sub_title' => __( 'Offer', 'funnel-builder' ),
			'title'     => $get_step->get_entity_title( $this->id ),
			'edit_link' => $link,
			'view_link' => $get_step->get_entity_view_link($upsell_id),
		);


		$get_parent_config['stats'] = $this->get_stats( $this->id );
		$get_parent_config['tabs']  = array(
			array(
				'name'  => 'analytics',
				'title' => __( 'Analytics', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/analytics/' . $this->id . '?type=offers' : '',
			),
			array(
				'name'  => 'contacts',
				'title' => __( 'Contacts', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/contacts/' . $this->id . '?type=offers' . $view_experiment : '',
			),
		);


		$configs[ count( $configs ) - 1 ]['data'] = $get_parent_config;
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

		if ( 0 === intval( $step_id ) ) {
			return $data;
		}

		if ( ! class_exists( 'WFOCU_Core' ) || ! class_exists( 'WFOCU_Contacts_Analytics' ) || ! version_compare( WFOCU_VERSION, '2.2.0', '>=' ) ) {
			return $data;
		}

		$get_query = "SELECT COUNT(CASE WHEN action_type_id = 4 THEN 1 END) AS `converted`, COUNT(CASE WHEN action_type_id = 2 THEN 1 END) AS `viewed`, object_id  as 'offer', action_type_id, SUM(value) as revenue FROM " . $wpdb->prefix . 'wfocu_event' . " WHERE object_id = " . $step_id . " AND (action_type_id = '2' OR action_type_id = '4' ) GROUP BY object_id";
		$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
			$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
			$data['conversions']     = is_null( $get_data['converted'] ) ? 0 : intval( $get_data['converted'] );
			$data['revenue']         = isset( $get_data['revenue'] ) && ! is_null( $get_data['revenue'] ) ? floatval( number_format( $get_data['revenue'], 2, '.', '' ) ) : 0;
			$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $get_data['converted'] );

		}

		return $data;
	}

	public function get_analytics_data( $step_id, $type, $start_date, $end_date, $is_interval, $int_request ) {
		global $wpdb;
		$date_col       = "timestamp";
		$interval_query = '';
		$group_by       = '';
		$get_data       = [];

		if ( ! class_exists( 'WFOCU_Core' ) || ! class_exists( 'WFOCU_Contacts_Analytics' ) || ! version_compare( WFOCU_VERSION, '2.2.0', '>=' ) ) {
			return $get_data;
		}

		if ( 'interval' === $is_interval ) {
			$get_interval   = $this->get_interval_format_query( $int_request, $date_col );
			$interval_query = $get_interval['interval_query'];
			$interval_group = $get_interval['interval_group'];
			$group_by       = "GROUP BY " . $interval_group;

		}

		$get_query = "SELECT COUNT(CASE WHEN action_type_id = 4 THEN 1 END) AS `converted`, COUNT(CASE WHEN action_type_id = 2 THEN 1 END) AS `viewed`, action_type_id, SUM(value) as revenue " . $interval_query . " FROM " . $wpdb->prefix . 'wfocu_event' . " WHERE object_id = " . $step_id . " AND (action_type_id = '2' OR action_type_id = '4' ) AND `" . $date_col . "` >= '" . $start_date . "' AND `" . $date_col . "` < '" . $end_date . "' " . $group_by . " ORDER BY object_id";
		$get_data  = $wpdb->get_results( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		return $get_data;
	}

	public function get_contact( $step_id ) {
		global $wpdb;

		$data = array(
			'views'           => 0,
			'conversions'     => 0,
			'conversion_rate' => 0,
			'revenue'         => 0,
		);

		if ( 0 === intval( $step_id ) ) {
			return $data;
		}

		if ( ! class_exists( 'WFOCU_Core' ) || ! class_exists( 'WFOCU_Contacts_Analytics' ) || ! version_compare( WFOCU_VERSION, '2.2.0', '>=' ) ) {
			return $data;
		}

		$get_query = "SELECT COUNT(CASE WHEN action_type_id = 4 THEN 1 END) AS `converted`, COUNT(CASE WHEN action_type_id = 2 THEN 1 END) AS `viewed`, object_id  as 'offer', action_type_id, SUM(value) as revenue FROM " . $wpdb->prefix . 'wfocu_event' . " WHERE object_id = " . $step_id . " AND (action_type_id = '2' OR action_type_id = '4' ) GROUP BY object_id";
		$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
			$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
			$data['conversions']     = is_null( $get_data['converted'] ) ? 0 : intval( $get_data['converted'] );
			$data['revenue']         = isset( $get_data['revenue'] ) && ! is_null( $get_data['revenue'] ) ? $get_data['revenue'] : 0;
			$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $get_data['converted'] );

		}

		return $data;
	}

}