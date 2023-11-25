<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Plugin Name: Divi Den Pro by WP Den
 */
if ( ! class_exists( 'WFACP_Compatibility_With_DDPRO' ) ) {
	class WFACP_Compatibility_With_DDPRO {
		public function __construct() {
			/* checkout page */
			add_action( 'wfacp_divi_page_content_replaced', [ $this, 'remove_divi_content_filter' ] );
		}

		public function remove_divi_content_filter( $instance ) {
			remove_filter( 'the_content', [ $instance, 'replace_divi_our_page_content' ], 1 );
		}
	}

	if ( ! function_exists( 'ddp_check_ddpdm' ) ) {
		return;
	}

	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_DDPRO(), 'ddpro' );
}
