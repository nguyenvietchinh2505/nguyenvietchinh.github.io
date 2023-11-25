<?php


class WFFN_Node_Condition extends WFFN_Base_Node {


	public function prepare_node_config( &$index, &$configs, &$edge_index ) {


		parent::prepare_node_config( $index, $configs, $edge_index );


		$configs[] = array(
			'type' => 'condition',
			'id'   => 'node-' . $index,
			'data' => $this->data
		);

	}
}