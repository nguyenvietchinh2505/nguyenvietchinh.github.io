<?php

class WFFN_Node_WC_Upsells extends WFFN_Base_Node_Step {

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
		$this->rearrange_variant_terminate( $index - 1, $configs );

	}


	public function register_a_node( &$index, &$configs, &$edge_index ) {
		/**
		 * Upsell node register
		 */
		parent::prepare_node_config( $index, $configs, $edge_index );
		$get_step = WFFN_Core()->steps->get_integration_object( $this->type );


		$get_parent_config = array(
			'type'      => 'upsells',
			'id'        => $this->id,
			'sub_title' => $get_step->get_title(),
			'title'     => $get_step->get_entity_title( $this->id )
		);

		$get_parent_config['stats'] = [ 'views' => 0, 'conversion' => 0, 'conversion_rate' => 0 ];
		$get_parent_config['tabs']  = [];


		$configs[ count( $configs ) - 1 ]['data'] = $get_parent_config;
		$index ++;

		/**
		 * Get rules
		 */
		$rules = get_post_meta( $this->id, '_wfocu_rules', true );

		/**
		 * Add edge from upsell to either rules/offers
		 */
		$this->add_edge( $index - 1, $index, $configs, $edge_index );


		/**
		 * Render rules
		 */
		if ( true === $this->is_having_rules( $rules ) ) {
			/**
			 * connecting upsells to rules
			 */

			$this->prepare_rule_nodes( $rules, $index, $configs, $edge_index, 'upsells' );
		}

		/**
		 * get all the steps
		 */
		$steps = get_post_meta( $this->id, '_funnel_steps', true );

		if ( is_array( $steps ) && count( $steps ) > 0 ) {
			$count = 1;

			foreach ( $steps as $step ) {


				if ( $step['type'] === 'downsell' ) {
					$this->resolve_terminate_reject( $index, $configs, $step['id'] );
				} else {
					$this->resolve_terminate_accept( $index, $configs, $step['id'] );
					$this->resolve_terminate_reject( $index, $configs, $step['id'] );
				}
				$offer_settings = WFOCU_Core()->offers->get_offer( $step['id'], false );


				$step['funnel_id'] = $this->id;
				if ( isset( $this->data['experiment_object'] ) ) {
					$step['experiment_object'] = $this->data['experiment_object'];
				}
				$class = new WFFN_Node_Offers( $step );
				$class->prepare_node_config( $index, $configs, $edge_index );
				$index ++;

				$this->add_edge( $index - 1, $index, $configs, $edge_index );
				$this->add_edge( $index - 1, $index + 1, $configs, $edge_index );

				$class = new WFFN_Node_Condition( array( 'type' => 'success', 'title' => __( 'Accept' ) ) );
				$class->prepare_node_config( $index, $configs, $edge_index );
				$index ++;

				$class = new WFFN_Node_Condition( array( 'type' => 'error', 'title' => __( 'Reject' ) ) );
				$class->prepare_node_config( $index, $configs, $edge_index );
				$index ++;


				$jump_accepted = ( isset( $offer_settings->settings ) && isset( $offer_settings->settings->jump_on_accepted ) && isset( $offer_settings->settings->jump_to_offer_on_accepted ) && true === $offer_settings->settings->jump_on_accepted ) ? $offer_settings->settings->jump_to_offer_on_accepted : 'automatic';
				$jump_rejected = ( isset( $offer_settings->settings ) && isset( $offer_settings->settings->jump_on_rejected ) && isset( $offer_settings->settings->jump_to_offer_on_rejected ) && true === $offer_settings->settings->jump_on_rejected ) ? $offer_settings->settings->jump_to_offer_on_rejected : 'automatic';


				if ( $count === count( $steps ) ) {
					$this->add_edge( $index - 2, '{{TERMINATE_UPSELLS}}', $configs, $edge_index );
					$this->add_edge( $index - 1, '{{TERMINATE_UPSELLS}}', $configs, $edge_index );
					break;
				}

				/**
				 * Check the settings
				 */
				if ( $jump_accepted === 'automatic' ) {
					$this->add_edge( $index - 2, '{{TERMINATE_OFFERS_ACCEPT}}', $configs, $edge_index );
				} elseif ( $jump_accepted === 'terminate' ) {
					$this->add_edge( $index - 2, '{{TERMINATE_UPSELLS}}', $configs, $edge_index );
				} else {
					$this->add_edge( $index - 2, '{{TERMINATE_OFFERS_ACCEPT_' . $jump_accepted . '}}', $configs, $edge_index );
				}


				/**
				 *
				 */
				if ( $jump_rejected === 'automatic' ) {
					$this->add_edge( $index - 1, '{{TERMINATE_OFFERS_REJECT}}', $configs, $edge_index );
				} elseif ( $jump_rejected === 'terminate' ) {
					$this->add_edge( $index - 1, '{{TERMINATE_UPSELLS}}', $configs, $edge_index );
				} else {
					$this->add_edge( $index - 1, '{{TERMINATE_OFFERS_REJECT_' . $jump_rejected . '}}', $configs, $edge_index );

				}
				$count ++;

			}

		}

		/**
		 * replace the term offer with the upsell so that next offer loop will not resolve this
		 */
		$this->replace_terminate_offers( $index, $configs );
	}

	public function resolve_terminate_accept( $index, &$configs, $id ) {
		foreach ( $configs as &$config ) {
			if ( isset( $config['target'] ) ) {
				$config['target'] = str_replace( '{{TERMINATE_OFFERS_ACCEPT}}', $index, $config['target'] );
				$config['target'] = str_replace( '{{TERMINATE_OFFERS_ACCEPT_' . $id . '}}', $index, $config['target'] );
			}
		};
	}

	public function resolve_terminate_reject( $index, &$configs, $id ) {
		foreach ( $configs as &$config ) {
			if ( isset( $config['target'] ) ) {
				$config['target'] = str_replace( '{{TERMINATE_OFFERS_REJECT}}', $index, $config['target'] );

				$config['target'] = str_replace( '{{TERMINATE_OFFERS_REJECT_' . $id . '}}', $index, $config['target'] );


			}
		};
	}

	public function replace_terminate_offers( $index, &$configs ) {
		foreach ( $configs as &$config ) {
			if ( isset( $config['target'] ) ) {
				$config['target'] = str_replace( '{{TERMINATE_OFFERS_ACCEPT}}', '{{TERMINATE_UPSELLS}}', $config['target'] );
				$config['target'] = str_replace( '{{TERMINATE_OFFERS_REJECT}}', '{{TERMINATE_UPSELLS}}', $config['target'] );
			}
		};
	}


	public function is_enable() {
		if ( false === WFFN_Core()->steps->get_integration_object( 'wc_upsells' ) ) {
			return false;
		}

		return true;
	}

}