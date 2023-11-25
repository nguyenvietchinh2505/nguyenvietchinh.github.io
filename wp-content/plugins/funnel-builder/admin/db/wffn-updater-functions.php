<?php
if ( ! function_exists( 'wffn_handle_store_checkout_config' ) ) {
	function wffn_handle_store_checkout_config() {
		if ( ! class_exists( 'WFACP_Common' ) ) {
			return;
		}
		/** check if store checkout already exists */
		if ( WFFN_Common::get_store_checkout_id() > 0 ) {
			return;
		}

		/** Remove _is_global meta if any funnel exists */
		$sql_query     = "SELECT bwf_funnel_id as id FROM {table_name_meta} WHERE meta_key = '_is_global'";
		$found_funnels = WFFN_Core()->get_dB()->get_results( $sql_query );
		if ( is_array( $found_funnels ) && count( $found_funnels ) > 0 && isset( $found_funnels[0]['id'] ) && absint( $found_funnels[0]['id'] ) > 0 ) {
			foreach ( $found_funnels as $funnel ) {
				if ( isset( $funnel['id'] ) ) {
					$del_query = "DELETE FROM {table_name_meta} WHERE bwf_funnel_id = " . $funnel['id'] . " AND meta_key = '_is_global'";
					WFFN_Core()->get_dB()->delete_multiple( $del_query );
				}
			}
		}

		$global_settings = WFACP_Common::global_settings( true );

		if ( ! is_array( $global_settings ) || count( $global_settings ) === 0 ) {
			return;
		}

		if ( ! isset( $global_settings['override_checkout_page_id'] ) || absint( $global_settings['override_checkout_page_id'] ) === 0 ) {
			return;
		}

		$wfacp_id = absint( $global_settings['override_checkout_page_id'] );

		$get_funnel_id = get_post_meta( $wfacp_id, '_bwf_in_funnel', true );

		if ( empty( $get_funnel_id ) ) {
			return;
		}


		WFFN_Common::update_store_checkout_meta( $get_funnel_id, 1 );

		/** we need to remove the old settings here since we are usinng filter for frontend execution
		 * If the settings exists then the current setup will always show
		 */

		unset( $global_settings['override_checkout_page_id'] );
		WFACP_AJAX_Controller::update_global_settings_fields( $global_settings );

	}
}