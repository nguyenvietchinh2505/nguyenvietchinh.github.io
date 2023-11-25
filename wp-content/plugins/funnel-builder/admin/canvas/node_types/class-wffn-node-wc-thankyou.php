<?php

class WFFN_Node_WC_thankyou extends WFFN_Base_Node_Step {

	public $id;
	public $type;
	public $supports = [ 'views', 'revenue', 'conversion' ];

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
		 * Resolve any pending upsell termination nodes
		 */
		$this->resolve_terminate_upsellls( $index, $configs );
		$rules = get_post_meta( $this->id, '_wfty_rules', true );
		if ( ! isset( $this->data['no_rules'] ) && true === $this->is_having_rules( $rules ) ) {

			$this->prepare_rule_nodes( $rules, $index, $configs, $edge_index, 'wc_thankyou' );

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
		/**
		 * Show thankyou nodes where no experiment is running in an experiment call
		 */
		parent::prepare_node_config( $index, $configs, $edge_index );
		$get_step        = WFFN_Core()->steps->get_integration_object( $this->type );
		$view_experiment = '';

		$link = $get_step->get_entity_edit_link( $this->id );
		if ( isset( $this->data['experiment_object'] ) ) {
			$view_experiment = '&view_experiment=' . $this->data['experiment_object']->get_id();
			$link            .= '&bwf_exp_ref=' . $this->data['experiment_object']->get_id();
		}
		$get_parent_config = array(
			'type'      => 'wc_thankyou',
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
				'api'   => defined( 'WFFN_PRO_FILE' ) ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/analytics/' . $this->id . '?type=wc_thankyou' . $view_experiment : '',
			),
			array(
				'name'  => 'experiment',
				'title' => __( 'A/B Tests', 'funnel-builder' ),
				'api'   => defined( 'WFFN_PRO_FILE' ) ? 'woofunnels-admin/experiment/?control=' . $this->id . '&data=true' : '',
			),

		);


		if ( isset( $this->data['experiment_object'] ) ) {
			$ab_key = array_search( 'experiment', array_column( $get_parent_config['tabs'], 'name' ), true );
			if ( false !== $ab_key ) {
				unset( $get_parent_config['tabs'][ $ab_key ] );

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
		$this->add_edge( $index - 1, '{{END_NODE}}', $configs, $edge_index );
	}

	/**
	 * @param $step_id
	 *
	 * @return array|false[]
	 */
	public function get_stats( $step_id ) {
		global $wpdb;

		$data = array(
			'views'       => 0,
			'revenue'     => 0,
			'conversions' => 0
		);

		$date_query = '';
		$view_type  = 5;

		if ( 0 === intval( $step_id ) ) {
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
				$data['views'] = $get_data[ $step_id ]['views'];
			}

			return $data;
		}

		$step_ids = implode( ',', $ids );

		$get_query = "SELECT SUM( CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END ) AS viewed FROM " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") " . $date_query . "  ORDER BY object_id ASC";
		$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
			$data['views'] = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
		}

		return $data;
	}

	public function get_analytics_data( $step_id, $type, $start_date, $end_date, $is_interval, $int_request ) {
		global $wpdb;
		$date_col       = "date";
		$interval_query = '';
		$group_by       = '';
		$ids            = [];
		$view_type      = 5;
		$get_data       = [];

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
			$get_controller = BWFABT_Core()->controllers->get_integration( 'thank_you' );
			$get_ab_data    = $get_controller->get_analytics_data( $ids, $this->data['view_experiment'], $is_interval, $int_request );
			if ( is_array( $get_ab_data ) && count( $get_ab_data ) > 0 ) {
				if ( isset( $get_ab_data[ $step_id ] ) && '' === $is_interval ) {
					$get_data[0]['viewed'] = $get_ab_data[ $step_id ]['views'];
				}
				if ( 'interval' === $is_interval ) {
					return $get_ab_data;
				}
			}

			return $get_data;
		}

		$step_ids = implode( ',', $ids );

		$get_query = "SELECT SUM( CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END ) AS viewed " . $interval_query . " FROM " . $wpdb->prefix . 'wfco_report_views' . " WHERE `" . $date_col . "` >= '" . $start_date . "' AND `" . $date_col . "` < '" . $end_date . "' AND object_id IN(" . $step_ids . ") " . $group_by . " ORDER BY object_id ASC";
		$get_data  = $wpdb->get_results( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}
		}

		return $get_data;
	}

	public function is_enable() {
		if ( false === WFFN_Core()->steps->get_integration_object( 'wc_thankyou' ) ) {
			return false;
		}

		return true;
	}


}