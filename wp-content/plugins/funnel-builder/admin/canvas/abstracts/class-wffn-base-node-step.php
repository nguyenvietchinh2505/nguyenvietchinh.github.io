<?php

class WFFN_Base_Node_Step extends WFFN_Base_Node {
	public $supports = [ 'views', 'revenue', 'conversion' ];

	public function prepare_node_config( &$index, &$configs, &$edge_index ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter, VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$configs[] = array(
			'type' => 'step',
			'id'   => 'node-' . $index
		);
	}

	public function resolve_terminate_upsellls( $index, &$configs ) {
		foreach ( $configs as &$config ) {
			if ( isset( $config['target'] ) ) {
				$config['target'] = str_replace( '{{TERMINATE_UPSELLS}}', $index, $config['target'] );
			}
		};
	}

	public function is_having_rules( $rules_data ) {

		if ( empty( $rules_data ) ) {
			return false;
		}

		$has_rules = false;
		foreach ( is_array( $rules_data ) ? $rules_data : array() as $rule_groups ) {
			foreach ( is_array( $rule_groups ) ? $rule_groups : array() as $rules_data ) {
				foreach ( is_array( $rules_data ) ? $rules_data : array() as $rules_arr ) {
					if ( isset( $rules_arr['rule_type'] ) && ( 'general_always' !== $rules_arr['rule_type'] && 'general_always_2' !== $rules_arr['rule_type'] ) ) {
						$has_rules = true;
						break 3;
					}
				}
			}
		}


		if ( $has_rules ) {
			return true;
		}

		return false;

	}


	public function get_running_experiment( $step_id ) {

		if ( ! class_exists( 'BWFABT_Core' ) ) {
			return false;
		}
		$exp_data = BWFABT_Core()->get_dataStore()->get_experiment_by_control_id( $step_id );
		foreach ( $exp_data as $experiment ) {
			$experiment_object = new BWFABT_Experiment( $experiment['id'] );

			if ( BWFABT_Experiment::STATUS_START === $experiment_object->get_status() || BWFABT_Experiment::STATUS_PAUSE === $experiment_object->get_status() ) {
				return $experiment_object;
			}
		}

		return false;
	}

	public function prepare_rule_nodes( $rules_data, &$index, &$configs, &$edge_index, $type ) {

		if ( empty( $rules_data ) ) {
			return false;
		}


		$class = new WFFN_Node_Rule( array( 'id' => $this->id, 'rules' => $rules_data, 'funnel_id' => $this->data['funnel_id'], 'type' => $type ) );
		$class->prepare_node_config( $index, $configs, $edge_index );
		$index ++;

		/**
		 * Adding rule mode to the yes node
		 */
		$this->add_edge( $index - 1, $index, $configs, $edge_index );
		/**
		 * Adding rule node to the no nodes
		 */
		$this->add_edge( $index - 1, $index + 1, $configs, $edge_index );

		$class = new WFFN_Node_Condition( array( 'type' => 'success', 'title' => __( 'Yes' ) ) );
		$class->prepare_node_config( $index, $configs, $edge_index );
		$index ++;

		$class = new WFFN_Node_Condition( array( 'type' => 'error', 'title' => __( 'No' ) ) );
		$class->prepare_node_config( $index, $configs, $edge_index );
		$index ++;

		/**
		 * Adding yes node to the next coming base node
		 */
		$this->add_edge( $index - 2, $index, $configs, $edge_index );
		/**
		 * Adding no node to the future termination node
		 */
		$this->add_edge( $index - 1, '{{TERMINATION_NODE}}', $configs, $edge_index );

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


	public function rearrange_variant_terminate( $index, &$configs ) {
		foreach ( $configs as &$config ) {
			if ( isset( $config['target'] ) ) {
				$config['target'] = str_replace( '{{TERMINATE_VARIANT}}', $index, $config['target'] );
			}
		};
	}


	public function date_format( $interval ) {
		switch ( $interval ) {
			case 'hour':
				$format = '%Y-%m-%d %H';
				break;
			case 'day':
				$format = '%Y-%m-%d';
				break;
			case 'month':
				$format = '%Y-%m';
				break;
			case 'quarter':
				$format = 'QUARTER';
				break;
			case 'year':
				$format = 'YEAR';
				break;
			default:
				$format = '%x-%v';
				break;
		}

		return apply_filters( 'WFFN_api_date_format_' . $interval, $format, $interval );
	}

	public function get_interval_format_query( $interval, $table_col ) {

		$interval_type = $this->date_format( $interval );
		$avg           = ( $interval === 'day' ) ? 1 : 0;
		if ( 'YEAR' === $interval_type ) {
			$interval = ", YEAR(" . $table_col . ") ";
			$avg      = 365;
		} elseif ( 'QUARTER' === $interval_type ) {
			$interval = ", CONCAT(YEAR(" . $table_col . "), '-', QUARTER(" . $table_col . ")) ";
			$avg      = 90;
		} elseif ( '%x-%v' === $interval_type ) {
			$first_day_of_week = absint( get_option( 'start_of_week' ) );

			if ( 1 === $first_day_of_week ) {
				$interval = ", DATE_FORMAT(" . $table_col . ", '" . $interval_type . "')";
			} else {
				$interval = ", CONCAT(YEAR(" . $table_col . "), '-', LPAD( FLOOR( ( DAYOFYEAR(" . $table_col . ") + ( ( DATE_FORMAT(MAKEDATE(YEAR(" . $table_col . "),1), '%w') - $first_day_of_week + 7 ) % 7 ) - 1 ) / 7  ) + 1 , 2, '0'))";
			}
			$avg = 7;
		} else {
			$interval = ", DATE_FORMAT( " . $table_col . ", '" . $interval_type . "')";
		}

		$interval       .= " as time_interval ";
		$interval_group = " `time_interval` ";

		return array(
			'interval_query' => $interval,
			'interval_group' => $interval_group,
			'interval_avg'   => $avg,

		);

	}

	public static function default_date( $diff_time = 0 ) {
		$now      = time();
		$datetime = new DateTime();
		if ( $diff_time > 0 ) {
			$week_back = $now - $diff_time;
			$datetime->setTimestamp( $week_back );
		}
		$datetime->setTimezone( new DateTimeZone( wp_timezone_string() ) );

		return $datetime;
	}

	public function supports( $feature ) {
		return in_array( $feature, $this->supports, true );
	}
	public function get_revenue_label() {
		return __( 'Total Revenue', 'funnel-builder' );
	}

}