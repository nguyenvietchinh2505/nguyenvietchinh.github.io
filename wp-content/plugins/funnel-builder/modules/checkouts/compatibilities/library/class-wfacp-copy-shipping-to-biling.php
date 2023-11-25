<?php

class WFACP_handle_billing_address {

	private $temp_field = [
		'first_name',
		'last_name',
		'address_1',
		'address_2',
		'city',
		'postcode',
		'state',
		'house_number',
		'street_name',
		'house_number_suffix',
	];
	private $add_fields = [
		'address_1',
		'address_2',
		'city',
		'postcode',
		'state',
	];

	public function __construct() {
		add_action( 'wfacp_outside_header', [ $this, 'attach_hooks' ] );
	}

	public function attach_hooks() {
		if ( ! WFACP_Core()->pay->is_order_pay() ) {
			add_action( 'woocommerce_checkout_after_customer_details', [ $this, 'print_billing_fields' ] );
		}
	}

	public function print_billing_fields() {
		$instance = wfacp_template();
		$fields   = $instance->get_checkout_fields();
		if ( false == $instance->have_billing_address() && $instance->have_shipping_address() ) {
			$address_prefix = 'billing_';
			if ( isset( $fields['billing']['billing_first_name'] ) ) {
				unset( $this->temp_field[0] );
			}
			if ( isset( $fields['billing']['billing_last_name'] ) ) {
				unset( $this->temp_field[1] );
			}
			foreach ( $this->temp_field as $item ) {
				echo $this->replace_names_ids( $address_prefix . $item, $address_prefix . $item );
			}
		} elseif ( $instance->have_shipping_address() && $instance->have_billing_address() ) {
			if ( isset( $fields['shipping']['shipping_first_name'] ) && ! isset( $fields['billing']['billing_first_name'] ) ) {
				echo $this->replace_names_ids( 'billing_first_name', 'billing_first_name' );
			}
			if ( isset( $fields['shipping']['shipping_last_name'] ) && ! isset( $fields['billing']['billing_last_name'] ) ) {
				echo $this->replace_names_ids( 'billing_last_name', 'billing_last_name' );
			}
			if ( isset( $fields['billing']['billing_first_name'] ) && ! isset( $fields['shipping']['shipping_first_name'] ) && ! isset( $fields['advanced']['shipping_first_name'] ) ) {
				echo $this->replace_names_ids( 'shipping_first_name', 'shipping_first_name' );
			}
			if ( isset( $fields['billing']['billing_last_name'] ) && ! isset( $fields['shipping']['shipping_last_name'] ) && ! isset( $fields['advanced']['shipping_last_name'] ) ) {
				echo $this->replace_names_ids( 'shipping_last_name', 'shipping_last_name' );
			}

			foreach ( $this->add_fields as $key ) {
				$b_key = 'billing_' . $key;
				$s_key = 'shipping_' . $key;
				if ( ! isset( $fields['billing'][ $b_key ] ) ) {
					echo $this->replace_names_ids( $b_key, $b_key );
				}
				if ( ! isset( $fields['shipping'][ $s_key ] ) ) {
					echo $this->replace_names_ids( $s_key, $s_key );
				}
			}
		}
	}

	public function replace_names_ids( $name, $id ) {
		return sprintf( '<input type="hidden" name="%s" id="%s" form="wfacp_checkout_form"  class="wfacp_hidden_fields">', $name, $id ) . "\n";
	}
}

add_action( 'wfacp_after_template_found', function () {
	new WFACP_handle_billing_address();
} );

