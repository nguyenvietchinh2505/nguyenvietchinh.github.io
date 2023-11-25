<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * plugin Name: Breakdance by Breakdance (1.1)
 *
 */


class WFACP_Compatibility_With_Breakdance {

	public function __construct() {
		add_filter( 'wfacp_shortcode_exist', [ $this, 'action' ], 10, 2 );
		add_filter( 'wfacp_detect_shortcode', [ $this, 'send_builder_content' ] );
	}


	public function action( $status, $post ) {

		if ( true == $status ) {
			return $status;
		}

		$content = $this->get_shortcode_content( $post );
		if ( false !== $content ) {
			$this->shortcode_content = $content;
			$status                  = true;
		}


		return $status;


	}

	public function get_shortcode_content( $post ) {


		if ( is_null( $post ) || ! $post instanceof WP_Post ) {
			return false;
		}

		$panels_data = get_post_meta( $post->ID, 'breakdance_data', true );;


		if ( empty( $panels_data ) ) {
			return false;
		}
		$shortcodes     = json_encode( $panels_data );
		$start_position = strpos( $shortcodes, '[wfacp_forms' );
		if ( false === $start_position ) {
			return false;
		}
		$shortcode_string = substr( $shortcodes, $start_position );
		$closing_position = strpos( $shortcode_string, ']', 1 );
		if ( false === $closing_position ) {
			return false;
		}
		$shortcode_string = substr( $shortcodes, $start_position, $closing_position + 1 );
		if ( strlen( $shortcode_string ) <= 0 ) {
			return false;
		}

		return $shortcode_string;

	}

	public function send_builder_content( $post_content ) {
		return ! empty( $this->shortcode_content ) ? $this->shortcode_content : $post_content;
	}

}


if ( ! defined( 'BREAKDANCE_WOO_DIR' ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Breakdance(), 'wfacp-breakdance-builder' );


