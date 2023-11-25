<?php

class WFFN_Node_experiments extends WFFN_Base_Node_Step {


	/**
	 * @var BWFABT_Experiment
	 */
	public $experiment_object;

	public function __construct( $data ) {

		$this->experiment_object = $data['experiment_object'];
		parent::__construct( $data );
	}

	public function prepare_node_config( &$index, &$configs, &$edge_index ) {

		/**
		 * Register an Ab node node
		 */
		parent::prepare_node_config( $index, $configs, $edge_index );
		$get_controller    = BWFABT_Core()->controllers->get_integration( $this->experiment_object->get_type() );
		$get_parent_config = array(
			'type'      => 'experiment',
			'id'        => $this->experiment_object->get_id(),
			'sub_title' => 'A/B Experiment',
			'title'     => $this->experiment_object->get_title(),
			'edit_link' => admin_url( 'admin.php' ) . '?page=bwf&path=/funnels/' . $this->data['funnel_id'] . '/experiments/' . $this->experiment_object->get_control(),
			'view_link' => $get_controller->get_entity_view_link( $this->experiment_object->get_control() ),
			'stats'     => [],
		);


		$get_parent_config['tabs'] = array(
			array(
				'name'  => 'experiment',
				'title' => __( 'A/B Tests', 'funnel-builder' ),
				'api'   => defined('WFFN_PRO_FILE') ? 'woofunnels-admin/experiment/?control=' . $this->experiment_object->get_control() . '&data=true' : '',
			),

		);


		$configs[ count( $configs ) - 1 ]['data'] = $get_parent_config;

		$last_count = count( $configs ) - 1;
		$index ++;


		$experiment = $this->data['experiment_object'];
		$variants   = $experiment->get_variants();
		$base_index = $index;
		$get_stats  = array(
			'views'           => 0,
			'conversions'     => 0,
			'conversion_rate' => 0,
			'revenue'         => 0,
		);

		/**
		 * loop over the variants and prepare the respective node
		 */
		foreach ( $variants as $id => $step ) {

			/**
			 * add connecting node from the base index which denoted a/b node
			 */
			$this->add_edge( $base_index - 1, $index, $configs, $edge_index );

			$data               = $this->data;
			$data['id']         = $id;
			$data['experiment'] = false;
			$data['no_edge']    = true;
			$data['no_rules']   = true;

			$count_config = count( $configs );
			$get_class    = new $this->data['type_class']( $data );
			$get_class->prepare_node_config( $index, $configs, $edge_index );

			/**
			 * Collecting the stats of the previous inserted node to make sure we show correct stats for the ab node
			 */
			if ( isset( $configs[ count( $configs ) - 1 ] ) && isset( $configs[ count( $configs ) - 1 ]['data'] ) ) {
				if ( isset( $configs[ count( $configs ) - 1 ]['data']['stats'] ) ) {
					$stats                        = $configs[ count( $configs ) - 1 ]['data']['stats'];
					$get_stats['views']           += isset( $stats['views'] ) ? $stats['views'] : 0;
					$get_stats['conversions']     += isset( $stats['conversions'] ) ? $stats['conversions'] : 0;
					$get_stats['conversion_rate'] = $this->get_percentage( $get_stats['views'], $get_stats['conversions'] );
					$get_stats['revenue']         += isset( $stats['revenue'] ) ? $stats['revenue'] : 0;

				}
			}

			if ( isset( $configs[ $count_config ] ) && $this->data['type_class'] === 'WFFN_Node_WC_Upsells' ) {
				if ( isset( $configs[ $count_config ]['data']['stats'] ) ) {
					$stats                        = $configs[ $count_config ]['data']['stats'];
					$get_stats['views']           += isset( $stats['views'] ) ? $stats['views'] : 0;
					$get_stats['conversions']     += isset( $stats['conversions'] ) ? $stats['conversions'] : 0;
					$get_stats['conversion_rate'] = $this->get_percentage( $get_stats['views'], $get_stats['conversions'] );
					$get_stats['revenue']         += isset( $stats['revenue'] ) ? $stats['revenue'] : 0;;

				}
			}

			/**
			 * Ignore any edges in the upsells node
			 */
			if ( $this->data['type_class'] === 'WFFN_Node_WC_Upsells' ) {
				continue;
			}

			/**
			 * if its a thankyou then register the end edge
			 */
			if ( $this->data['type_class'] === 'WFFN_Node_WC_thankyou' ) {
				$this->add_edge( $index - 1, '{{END_NODE}}', $configs, $edge_index );
			} else {
				/**
				 * Else we need to register a variant edge, since we do not know where this will be
				 */
				$this->add_edge( $index - 1, '{{TERMINATE_VARIANT}}', $configs, $edge_index );
			}


		}

		$get_stats['revenue'] = floatval( number_format( $get_stats['revenue'], 2, '.', '' ) );
		if ( class_exists( 'WFFN_Pro_Core' ) ) {
			$configs[ $last_count ]['data']['stats'] = $get_stats;
		}


	}
}


