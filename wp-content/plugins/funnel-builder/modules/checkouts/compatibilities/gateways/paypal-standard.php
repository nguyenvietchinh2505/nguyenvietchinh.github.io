<?php

class WFACP_PayPal_Standard {
	public function __construct() {
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'order_processed' ] );
	}

	public function order_processed() {
		$public = WFACP_Core()->public;
		if ( ! $public instanceof WFACP_Public ) {
			return;
		}
		$payment_method = filter_input( INPUT_POST, 'payment_method', FILTER_UNSAFE_RAW );
		if ( 'paypal' !== $payment_method ) {
			return;
		}
		remove_filter( 'woocommerce_checkout_no_payment_needed_redirect', [ $public, 'reset_session_when_order_processed' ] );
		remove_filter( 'woocommerce_payment_successful_result', [ $public, 'reset_session_when_order_processed' ] );

	}
}

new WFACP_PayPal_Standard();