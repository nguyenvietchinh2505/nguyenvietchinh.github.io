<?php

class WFFN_Node_Rule extends WFFN_Base_Node {
	public $id;
	public $rules;

	public function __construct( $data ) {
		$this->id    = $data['id'];
		$this->rules = $data['rules'];
		parent::__construct( $data );
	}

	public function prepare_node_config( &$index, &$configs, &$edge_index ) {


		parent::prepare_node_config( $index, $configs, $edge_index );
		$get_parent_config = array(
			'id'        => $this->id,
			'sub_title' => 'Rules (' . $this->get_rules_count() . ')',
			'title'     => 'Rules (' . $this->get_rules_count() . ')',
			'eeit_link' => '',
		);


		$get_parent_config['tabs'] = array(
			array(
				'name'  => 'rules',
				'title' => __( 'Rules', 'funnel-builder' ),
				'api'   => class_exists( 'WFFN_Pro_Core' ) ? 'woofunnels-admin/canvas/' . $this->data['funnel_id'] . '/nodes/rules/' . $this->id . '?type=' . $this->data['type'] : '',
			),

		);


		$configs[] = array(
			'type' => 'rule',
			'id'   => 'node-' . $index,
			'data' => $get_parent_config
		);

	}

	public function get_rules_count() {

		$count      = 0;
		$rules_data = $this->rules;

		foreach ( is_array( $rules_data ) ? $rules_data : array() as $rule_groups ) {
			foreach ( is_array( $rule_groups ) ? $rule_groups : array() as $rules_data ) {
				foreach ( is_array( $rules_data ) ? $rules_data : array() as $rules_arr ) {
					if ( isset( $rules_arr['rule_type'] ) && ( 'general_always' === $rules_arr['rule_type'] || 'general_always_2' === $rules_arr['rule_type'] ) ) {
						continue;
					}

					$count ++;

				}
			}
		}

		return $count;
	}

	public function prepare_rules_strings() {
		$array       = [];
		$group_count = 0;
		foreach ( $this->rules as $group ) {
			$array[ $group_count ] = [];
			foreach ( $group as $rule ) {

				if ( $rule['rule_type'] === 'general_always' || $rule['rule_type'] === 'general_always_2' ) {
					unset( $array[ $group_count ] );
					continue;
				}
				if ( $this->data['type'] === 'wc_thankyou' ) {
					$class = WFTY_Rules::get_instance()->woocommerce_wfty_rule_get_rule_object( $rule['rule_type'] );

				} else {

					$class = WFOCU_Core()->rules->woocommerce_wfocu_rule_get_rule_object( $rule['rule_type'] );

				}

				$array[ $group_count ][] = $class->get_nice_string( $rule );
			}
			$group_count ++;
		}

		return $array;
	}
}