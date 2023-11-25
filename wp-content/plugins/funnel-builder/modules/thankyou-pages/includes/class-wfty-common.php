<?php
defined( 'ABSPATH' ) || exit;


/**
 * Class WFTY_Common
 * Handles Common Functions For admins as well as front end interface
 * @package WFTY
 * @author FunnelKit
 */
if ( ! class_exists( 'WFTY_Common' ) ) {
	class WFTY_Common {


		public static function get_view_path() {
			return WFFN_Core()->thank_you_pages->get_module_path() . '/views';
		}

		public static function get_component_path() {
			return WFFN_Core()->thank_you_pages->get_module_path() . '/components';
		}

		public static function get_funnel_id() {

			$page = filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW );
			$edit = filter_input( INPUT_GET, 'edit', FILTER_UNSAFE_RAW );
			if ( ! is_null( $page ) && 'wf-ty' === $page && ! is_null( $edit ) && $edit > 0 ) {
				return $edit;
			}

			return 0;
		}

		/**
		 * Prepares single post url and add query arg to that woocommerce pick that url
		 *
		 * @param $link
		 * @param $order
		 *
		 * @return mixed|void
		 */
		public static function prepare_single_post_url( $link, $order ) {
			$order_key = $order->get_order_key();
			$order_id  = $order->get_id();

			$link = add_query_arg( 'key', $order_key, $link );
			$link = add_query_arg( 'order_id', $order_id, $link );

			return apply_filters( 'wfty_woocommerce_get_checkout_order_received_url', $link, $order );
		}

		public static function get_layout_setting_values() {
			return array(
				array(
					'id'   => '2c',
					'name' => __( 'Two Columns', 'funnel-builder' )
				),
				array(
					'id'   => 'full-width',
					'name' => __( 'Full Width', 'funnel-builder' )
				)
			);
		}

		public static function get_font_weight_values() {
			return array(
				array(
					'id'   => 'default',
					'name' => __( 'Default', 'funnel-builder' )
				),
				array(
					'id'   => 'normal',
					'name' => __( 'Normal', 'funnel-builder' )
				),
				array(
					'id'   => 'bold',
					'name' => __( 'Bold', 'funnel-builder' )
				),
				array(
					'id'   => '300',
					'name' => __( '300', 'funnel-builder' )
				),
				array(
					'id'   => '400',
					'name' => __( '400', 'funnel-builder' )
				),
				array(
					'id'   => '500',
					'name' => __( '500', 'funnel-builder' )
				),
				array(
					'id'   => '600',
					'name' => __( '600', 'funnel-builder' )
				),
				array(
					'id'   => '700',
					'name' => __( '700', 'funnel-builder' )
				)
			);
		}


	}
}