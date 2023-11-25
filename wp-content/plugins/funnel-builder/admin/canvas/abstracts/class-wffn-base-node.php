<?php

class WFFN_Base_Node {


	public $data = [];

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function prepare_node_config( &$index, &$config, &$edge_index ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		return array();
	}

	public function add_edge( $source, $target, &$configs, &$edge_index ) {
		$configs[ count( $configs ) ] = array(
			'id' => 'edge-id-' . $edge_index,
			'source' => 'node-' . ( $source ),
			'target' => 'node-' . $target,
		);
		$edge_index ++;
	}

	public function is_enable() {
		return true;
	}
}