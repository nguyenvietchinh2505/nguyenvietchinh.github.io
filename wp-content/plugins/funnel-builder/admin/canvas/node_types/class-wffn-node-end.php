<?php

class WFFN_Node_End extends WFFN_Base_Node {


	public function prepare_node_config( &$index, &$configs, &$edge_index ) {


		parent::prepare_node_config( $index, $configs, $edge_index );


		$configs[] = array(
			'type' => 'end',
			'id'   => 'node-' . $index,
			'data' => array( 'title' => __( 'End' ) )
		);

	}
}