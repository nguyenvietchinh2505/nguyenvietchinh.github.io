<?php

if ( ! function_exists( 'wffn_clean' ) ) {
	function wffn_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'wffn_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists( 'wffn_show_notice' ) ) {
	function wffn_show_notice( $args, $context ) {
		global $wffn_notices;

		ob_start();
		if ( $context === 'version_mismatch' ) {
			?>
			<div class="bwf-notice error">
				<p>
					<strong><?php esc_html_e( 'Attention', 'woofunnels-upstroke-power-pack' ); ?></strong>
					<?php
					/* translators: %1$s: Plugin name %2$s Plugin name */
					echo sprintf( esc_html__( 'The %1$s version running your site is not compatible with the Funnel Builder plugin, Please update %1$s to the recent version. ', 'woofunnels-upstroke-power-pack' ), esc_attr( $args['pname'] ) );
					?>
				</p>
			</div>
			<?php

		} else {
			echo wp_kses_post( $args['text'] );

		}

		$wffn_notices[] = ob_get_clean();
	}
}

/**
 * Converts a string (e.g. 'yes' or 'no' , 'true') to a bool.
 *
 * @param $string
 *
 * @return bool
 */
if ( ! function_exists( 'wffn_string_to_bool' ) ) {
	function wffn_string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
	}
}

/**
 * Converts a bool to a 'yes' or 'no'.
 *
 * @param bool $bool String to convert.
 *
 * @return string
 * @since 3.0.0
 */
if ( ! function_exists( 'wffn_bool_to_string' ) ) {
	function wffn_bool_to_string( $bool ) {
		if ( ! is_bool( $bool ) ) {
			$bool = wffn_string_to_bool( $bool );
		}

		return true === $bool ? 'yes' : 'no';
	}
}
if ( ! function_exists( 'wffn_maybe_import_funnel_in_background' ) ) {
	if ( ! function_exists( 'wffn_maybe_import_funnel_in_background' ) ) {
		function wffn_maybe_import_funnel_in_background() {
			$funnel_id = get_option( '_wffn_scheduled_funnel_id', 0 );
			BWF_Logger::get_instance()->log( "Running the callback wffn_maybe_import_funnel_in_background: $funnel_id ", 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			if ( $funnel_id > 0 ) {
				BWF_Logger::get_instance()->log( 'Importing template for funnel: ' . print_r( $funnel_id, true ) . '-fn- ' . __FUNCTION__, 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

				$funnel = new WFFN_Funnel( $funnel_id );

				$funnel_steps      = $funnel->get_steps();
				$has_any_scheduled = 0;
				BWF_Logger::get_instance()->log( 'Funnel steps: ' . print_r( $funnel_steps, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

				foreach ( $funnel_steps as $funnel_step ) {
					$get_object = WFFN_Core()->steps->get_integration_object( $funnel_step['type'] );
					if ( ! empty( $get_object ) ) {
						$has_scheduled = $get_object->has_import_scheduled( $funnel_step['id'] );

						if ( is_array( $has_scheduled ) ) {
							$has_any_scheduled ++;
							BWF_Logger::get_instance()->log( 'Ready to import, step ID: ' . $funnel_step['id'] . ', Template: ' . print_r( $has_scheduled, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

							$get_object->do_import( $funnel_step['id'] );
							$get_object->update_template_data( $funnel_step['id'], [
								'selected'      => $has_scheduled['template'],
								'selected_type' => $has_scheduled['template_type'],
							] );
						}
					}
				}
			}
		}
	}
}


if ( ! function_exists( 'wffn_price' ) ) {
	function wffn_price( $price, $args = array() ) {

		if ( function_exists( 'wc_price' ) ) {
			return wc_price( $price, $args );
		}


		$currency_pos = 'left';
		$format       = '%1$s%2$s';


		$price_format = apply_filters( 'wffn_price_format', $format, $currency_pos );

		$args = apply_filters( 'wffn_price_args', wp_parse_args( $args, array(
			'ex_tax_label'       => false,
			'currency'           => '',
			'decimal_separator'  => apply_filters( 'wffn_get_price_decimal_separator', '.' ),
			'thousand_separator' => apply_filters( 'wffn_get_price_thousand_separator', '.' ),
			'decimals'           => apply_filters( 'wffn_get_price_thousand_separator', '2' ),
			'price_format'       => $price_format,
		) ) );

		$unformatted_price = $price;
		$negative          = $price < 0;
		$price             = apply_filters( 'wffn_raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price             = apply_filters( 'wffn_formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

		if ( apply_filters( 'wffn_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
			$price = preg_replace( '/' . preg_quote( $args['decimal_separator'], '/' ) . '0++$/', '', $price );
		}
		$currency = $args['currency'];
		if ( ! $currency ) {
			$currency = 'USD';
		}

		$symbols = wffn_currency_symbols();

		$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

		$symbol          = apply_filters( 'woocommerce_currency_symbol', $currency_symbol, $currency );
		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '' . $symbol . '', $price );
		$return          = $formatted_price;


		/**
		 * Filters the string of price markup.
		 *
		 * @param string $return Price HTML markup.
		 * @param string $price Formatted price.
		 * @param array $args Pass on the args.
		 * @param float $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
		 */
		return apply_filters( 'wffn_price', $return, $price, $args, $unformatted_price );
	}
}
if ( ! function_exists( 'wffn_currency_symbols' ) ) {
	function wffn_currency_symbols() {

		$symbols = apply_filters( 'wffn_currency_symbols', array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => '&#8376;',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRU' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#1088;&#1089;&#1076;',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STN' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		) );

		return $symbols;
	}
}
if ( ! function_exists( 'wffn_is_valid_funnel' ) ) {
	function wffn_is_valid_funnel( $funnel ) {
		return ( $funnel instanceof WFFN_Funnel && 0 < $funnel->get_id() );
	}
}

if ( ! function_exists( 'wffn_is_wc_active' ) ) {
	function wffn_is_wc_active() {
		return wffn_is_plugin_active( 'woocommerce/woocommerce.php' );
	}
}

if ( ! function_exists( 'wffn_is_plugin_active' ) ) {
	function wffn_is_plugin_active( $plugin_basename ) {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		if ( in_array( $plugin_basename, apply_filters( 'active_plugins', $active_plugins ), true ) || array_key_exists( $plugin_basename, apply_filters( 'active_plugins', $active_plugins ) ) ) {
			return true;
		}


		return false;

	}
}


if ( ! function_exists( 'wffn_get_ip_address' ) ) {
	function wffn_get_ip_address() {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) ); //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {  //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );  //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders,WordPressVIPMinimum.Variables.RestrictedVariables
		}

		return '127.0.0.1';
	}
}

if ( ! function_exists( 'wffn_get_user_agent' ) ) {
	function wffn_get_user_agent() {
		return isset( $_SERVER['HTTP_USER_AGENT'] ) ? wffn_clean( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';  //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables
	}
}

if ( ! function_exists( 'wffn_fk_automations_active' ) ) {
	function wffn_fk_automations_active() {
		return class_exists('BWFAN_Core');
	}
}
