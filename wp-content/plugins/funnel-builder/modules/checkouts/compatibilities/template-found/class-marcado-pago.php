<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WFACP_Compatibility_With_Marcadopago {


	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ], 999 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css_js' ] );
	}

	public function remove_actions() {
		if ( class_exists( 'WC_WooMercadoPago_CustomGateway' ) ) {
			WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'WC_WooMercadoPago_CustomGateway', 'add_checkout_scripts_custom' );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_checkout_scripts_custom' ), 15 );
		}

	}

	public function add_checkout_scripts_custom() {
		if ( ! get_query_var( 'order-received' ) ) {

			$path = WFACP_Common::get_class_path( 'WC_WooMercadoPago_CustomGateway' );
			$path .= '/woocommerce-mercadopago.php';
			wp_enqueue_style( 'woocommerce-mercadopago-style', plugins_url( 'assets/css/custom_checkout_mercadopago.css', plugin_dir_path( $path ) ) );

			wp_enqueue_script( 'mercado-pago-module-custom-js', 'https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js', [ 'underscore', 'wp-util' ] );

		}
	}

	public function internal_css_js() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body";
		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . ".mp-input-document .mp-input .mp-document-select{height: auto !important;}";
		$cssHtml .= $bodyClass . "#mp-custom-checkout-form-container select{height: auto !important;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
		?>

        <script>
            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {
                    // WooCommerce Marcado Emi Gateway
                    let checkout_form = $('form.checkout');
                    let mercado_gateway = $('#payment_method_woo-mercado-pago-custom');
                    let form_attributes = $("input[form='wfacp_checkout_form']");
                    var MPv1_running = false;

                    if (mercado_gateway.length === 0) {
                        return;
                    }

                    // change attribute to checkout from wfacp_checkout_form because of mercado pago change checkout form id to checkout
                    //
                    form_attributes.each(function () {
                        $(this).attr('form', 'checkout');
                    });

                    $(document.body).on('wfacp_checkout_data', function () {
                        let paymentGateway = $('#payment_method_woo-mercado-pago-custom');
                        if (paymentGateway.length > 0 && "woo-mercado-pago-custom" === paymentGateway.val()) {
                            MPv1_running = true;
                            update_checkout();
                        }
                    });
                    checkout_form.on('click', 'input[name="payment_method"]', function () {
                        if ("woo-mercado-pago-custom" === $(this).val() && typeof MPv1 == "object" && MPv1_running == true && typeof MPv1 !== "undefined") {
                            MPv1.guessingPaymentMethod({'event': 'keyup'});
                            MPv1_running = false;
                        }
                    });

                    $(document.body).on('wfacp_coupon_apply', function () {
                        let paymentGateway = $('#payment_method_woo-mercado-pago-custom');
                        if (paymentGateway.length > 0 && "woo-mercado-pago-custom" === paymentGateway.val()) {
                            MPv1_running = true;
                            update_checkout();
                        }
                    });
                    // WooCommerce Marcado Emi Gateway end here
                    $(document.body).on('wfacp_coupon_form_removed', function () {
                        let paymentGateway = $('#payment_method_woo-mercado-pago-custom');
                        if (paymentGateway.length > 0 && "woo-mercado-pago-custom" === paymentGateway.val()) {
                            MPv1_running = true;
                            update_checkout();
                        }
                    });

                })(jQuery);
            })

        </script>
		<?php
	}

}


if ( ! class_exists( 'WC_WooMercadoPago_Init' ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Marcadopago(), 'marcadopago' );
