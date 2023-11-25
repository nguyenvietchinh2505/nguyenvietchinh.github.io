<?php
function wfacp_is_elementor() {

	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		if ( version_compare( ELEMENTOR_VERSION, '3.2.0', '<=' ) ) {
			return \Elementor\Plugin::$instance->db->is_built_with_elementor( WFACP_Common::get_id() );
		} else {
			return \Elementor\Plugin::$instance->documents->get( WFACP_Common::get_id() )->is_built_with_elementor();
		}
	}

	return false;
}


/**
 * Return instance of Current Template Class
 * @return WFACP_Template_Common
 */
function wfacp_template() {
    if ( is_null( WFACP_Core()->template_loader ) ) {
        return null;
    }

    return WFACP_Core()->template_loader->get_template_ins();
}

function wfacp_elementor_edit_mode() {
	$status = false;
	if ( isset( $_REQUEST['elementor-preview'] ) || ( isset( $_REQUEST['action'] ) && ( 'elementor' == $_REQUEST['action'] || 'elementor_ajax' == $_REQUEST['action'] ) ) ) {
		$status = true;

	}
	if ( ( isset( $_REQUEST['preview_id'] ) && isset( $_REQUEST['preview_nonce'] ) ) || isset( $_REQUEST['elementor-preview'] ) ) {
		$status = true;
	}

	return $status;
}
function wfacp_check_nonce() {
    $rsp = [
        'status' => 'false',
        'msg'    => 'Invalid Call',
    ];
    if ( isset( $_POST['post_data'] ) ) {
        $post_data   = [];
        $t_post_data = filter_input( INPUT_POST, 'post_data', FILTER_UNSAFE_RAW );
        parse_str( $t_post_data, $post_data );
        if ( ! empty( $post_data ) ) {
            WFACP_Common::$post_data = $post_data;
        }
    }
    $wfacp_nonce = filter_input( INPUT_POST, 'wfacp_nonce', FILTER_UNSAFE_RAW );

    if ( is_null( $wfacp_nonce ) || ! wp_verify_nonce( $wfacp_nonce, 'wfacp_secure_key' ) ) {
        wp_send_json( $rsp );
    }
}