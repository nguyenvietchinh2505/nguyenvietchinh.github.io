<?php

/**
 *
 * Plugin Name: WooCommerce NL Postcode Checker
 * Plugin URI: https://wpovernight.com/downloads/woocommerce-postcode-checker/
 */
class WFACP_Wcnl_Postcode_Checker_Field {
	public function __construct() {
		add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
		add_action( 'wfacp_internal_css', [ $this, 'js' ] );
	}

	public function setup_fields_billing() {
		new WFACP_Add_Address_Field( 'postcodeNl_address_autocomplete', [
			'type'         => 'text',
			'label'        => __( 'Autocomplete address', 'wpo_wcnlpc' ),
			'placeholder'  => __( 'Start typing the address', 'wpo_wcnlpc' ),
			'required'     => false,
			'class'        => [
				'form-row-wide',
				'postcodenl-address-autocomplete',
			],
			'autocomplete' => 'off',
			'priority'     => 45,
		] );
		new WFACP_Add_Address_Field( 'postcodeNl_address_autocomplete', [
			'type'         => 'text',
			'label'        => __( 'Autocomplete address', 'wpo_wcnlpc' ),
			'placeholder'  => __( 'Start typing the address', 'wpo_wcnlpc' ),
			'required'     => false,
			'class'        => [
				'form-row-wide',
				'postcodenl-address-autocomplete',
			],
			'autocomplete' => 'off',
			'priority'     => 45,
		], 'shipping' );
	}

	public function js() {
		?>
        <script>
            window.addEventListener('bwf_checkout_js_load', function () {
                jQuery('body').on('update_checkout', function () {
                    jQuery('.address-field input, .wfacp_postcode_checker input').each(function () {
                        let parent = jQuery(this).closest('p.form-row');
                        parent.removeClass('wfacp-anim-wrap');
                        if ('' !== jQuery(this).val()) {
                            parent.addClass('wfacp-anim-wrap');
                        }
                    });
                })
            });
        </script>
		<?php
	}

}


if ( ! class_exists( 'WPO_WC_Postcode_Checker', false ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Wcnl_Postcode_Checker_Field(), 'wcnl_overnight_postcode_checker' );