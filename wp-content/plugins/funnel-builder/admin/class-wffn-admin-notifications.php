<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Admin_Notifications
 * Handles All the methods about admin notifications
 */
if ( ! class_exists( 'WFFN_Admin_Notifications' ) ) {
	class WFFN_Admin_Notifications {

		private static $ins = null;
		private $notifs = [];

		public function __construct() {

		}

		/**
		 * @return WFFN_Admin_Notifications|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function get_notifications() {
			$this->prepare_notifications();

			return $this->notifs;
		}


		public function prepare_notifications() {


			if ( ! defined( 'WFFN_PRO_VERSION' ) ) {

					$promotion_buttons_bfcm = [
						[
							'label'     => __( "Get FunnelKit PRO", "funnel-builder" ),
							'href'      => add_query_arg( [
								'utm_source'   => 'WordPress',
								'utm_medium'   => 'Notice+FKFB',
								'utm_campaign' => 'BFCM2022'
							], "https://funnelkit.com/exclusive-offer/" ),
							'className' => 'button button-primary',
							'target'    => '__blank',
						],
						[
							'label'     => __( "Learn More", "funnel-builder" ),
							'href'      => add_query_arg( [
								'utm_source'   => 'WordPress',
								'utm_medium'   => 'Notice+FKFB',
								'utm_campaign' => 'BFCM2022'
							], "https://funnelkit.com/wordpress-funnel-builder/" ),
							'className' => 'button',
							'target'    => '__blank',
						],
						[
							'label'     => __( "Dismiss", "funnel-builder" ),
							'action'    => 'close_notice',
							'className' => 'button-link',

						]
					];
				/**
				 * Sale promotional menus, according to the timestamps
				 */
				if ( strtotime( gmdate( 'c' ) ) < 1669593599 ) {
					$this->notifs[] = array(
						'key'           => 'promo_bf_2022',
						'content'       => $this->promo_bfcm(),
						'customButtons' => $promotion_buttons_bfcm
					);
				} elseif ( strtotime( gmdate( 'c' ) ) < 1670025599 ) {
					$this->notifs[] = array(
						'key'           => 'promo_cm_2022',
						'content'       => $this->promo_cmonly(),
						'customButtons' => $promotion_buttons_bfcm
					);
				}


			}
			if ( defined( 'WFFN_PRO_VERSION' ) && version_compare( WFFN_PRO_VERSION, '2.4.0', '<' ) ) {
				$this->notifs[] = array(
					'key'           => 'pro_update_2_4',
					'content'       => $this->pro_update_2_4(),
					'customButtons' => [
						[
							'label'     => __( "Go to plugin updates", "funnel-builder" ),
							'href'      => admin_url( "plugins.php?s=funnel+builder+pro" ),
							'className' => 'button button-primary',
							'target'    => '__blank',
						],

						[
							'label'     => __( "Dismiss", "funnel-builder" ),
							'action'    => 'close_notice',
							'className' => 'button-link',

						]
					]
				);
			}

			$global_funnel_id = WFFN_Common::get_store_checkout_id();
			if ( absint( $global_funnel_id ) > 0 ) {
				$get_funnel = new WFFN_Funnel( $global_funnel_id );
				if ( $get_funnel instanceof WFFN_Funnel && 0 !== $get_funnel->get_id() ) {
					$get_version = WFFN_Core()->get_dB()->get_meta( $global_funnel_id, '_version' );

					if ( empty( $get_version ) ) {
						$this->notifs[] = array(
							'key'           => 'mig_store_checkout',
							'content'       => $this->store_checkout_migrated(),
							'customButtons' => [
								[
									'label'     => __( "Go to Store Checkout", "funnel-builder" ),
									'href'      => admin_url( 'admin.php?page=bwf&path=/store-checkout' ),
									'action'    => 'close_notice',
									'className' => 'button-primary',
								],
								[
									'label'  => __( "Learn more", "funnel-builder" ),
									'href'   => "https://funnelkit.com/woofunnels-is-now-funnelkit/?utm_source=WordPress&utm_medium=Store+Checkout+Migrate&utm_campaign=Lite+Plugin",
									'target' => '__blank',
								]
							]
						);
					}


				}
			}

			/** Check if brand change notice is required */
			$first_v = get_option( 'wffn_first_v', false );
			if ( empty( $first_v ) || version_compare( $first_v, '2.4.1', '<' ) ) {
				$this->notifs[] = array(
					'key'     => 'brandchange',
					'content' => $this->brandchange(),

					'customButtons' => [
						[
							'label'     => __( "Learn more", "funnel-builder" ),
							'href'      => 'https://funnelkit.com/woofunnels-is-now-funnelkit/?utm_source=WordPress&utm_medium=Brand+Name+Change&utm_campaign=Lite+Plugin',
							'className' => 'button-primary',
							'target'    => '__blank',
						],

					],
				);
			}

			if ( class_exists( 'WFFN_Pro_Modules' ) ) {
				$is_upsell_exists   = WFFN_Pro_Modules::get_module( 'one-click-upsells/woofunnels-upstroke-one-click-upsell.php' )::is_module_exists();
				$is_bump_exists     = WFFN_Pro_Modules::get_module( 'order-bumps/woofunnels-order-bump.php' )::is_module_exists();
				$is_checkout_exists = WFFN_Pro_Modules::get_module( 'checkout/woofunnels-aero-checkout.php' )::is_module_exists();
				$is_ab_exists       = WFFN_Pro_Modules::get_module( 'woofunnels-ab-tests/woofunnels-ab-tests.php' )::is_module_exists();

				$array_of_strings = [];
				if ( $is_upsell_exists ) {
					$array_of_strings[] = 'Upsells';
				}
				if ( $is_checkout_exists ) {
					$array_of_strings[] = 'Checkout';
				}
				if ( $is_bump_exists ) {
					$array_of_strings[] = 'Order Bump';
				}
				if ( $is_ab_exists ) {
					$array_of_strings[] = 'A/B testing';
				}

				if ( count( $array_of_strings ) > 0 ) {
					$this->notifs[] = array(
						'key'           => 'indi_plugins',
						'content'       => $this->individual_plugins_found( WFFN_Common::natural_language_join( $array_of_strings ), count( $array_of_strings ) ),
						'customButtons' => [
							[
								'label'     => __( "Deactivate Plugins", "funnel-builder" ),
								'href'      => admin_url( 'plugins.php?s=woofunnels' ),
								'className' => 'button-primary',
								'target'    => '__blank',
							],

							[
								'label'  => __( "Dismiss", "funnel-builder" ),
								'action' => 'close_notice',

							]
						]
					);
				}
			}
		}


		public function brandchange() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . __( "Alert! WooFunnels is now FunnelKit", "funnel-builder" ) . '</h3>
					<p class="bwf-notifications-content">' . __( "We are proud to announce that WooFunnels is now called FunnelKit. Only the name changes, everything else remains the same.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function store_checkout_migrated() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . __( "Global Checkout has been migrated to Store Checkout!", "funnel-builder" ) . '</h3>
					<p class="bwf-notifications-content">' . __( "To make your storefront's more accessible, we have migrated Global Checkout. All the steps of the checkout are available under Store Checkout.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function individual_plugins_found( $plugins_str, $count ) {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . sprintf( "Deactivate individual Plugin%s for %s.", ( 1 < $count ) ? 's' : '', $plugins_str ) . '</h3>
					<p class="bwf-notifications-content">' . __( "Funnel Builder Pro is active. It contains all the modules and you don't need separate plugins. Please deactivate them. Don't worry no data will be lost.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function pro_update_2_4() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . __( "Update Funnel Builder Pro to version 2.4", "funnel-builder" ) . '</h3>
					<p class="bwf-notifications-content">' . __( "It seems that you are running an older version of Funnel Builder Pro. For a smoother experience, update Funnel Builder Pro to version 2.4.", "funnel-builder" ) . '</p>
				</div>';
		}


		public function promo_bfcm() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title"><img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . __( "Black Friday is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" ) . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg"></h3>
					<p class="bwf-notifications-content">' . __( "<strong>Get started using FunnelKit to grow your revenue today for up to 55% OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing  and much more! Expires Friday, Nov 25, at midnight ET.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function promo_cmonly() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title"><img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . __( "Cyber Monday is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" ) . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg"></h3>
					<p class="bwf-notifications-content">' . __( "<strong>Get started using FunnelKit to grow your revenue today for up to 55% OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing  and much more! Expires Friday, Dec 02, at midnight ET.", "funnel-builder" ) . '</p>
				</div>';
		}


		public function filter_notifs( $all_registered_notifs, $id ) {
			$userdata = get_user_meta( $id, '_bwf_notifications_close', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
			if ( empty( $userdata ) ) {
				return $all_registered_notifs;
			}

			foreach ( $all_registered_notifs as $k => $notif ) {
				if ( ! in_array( $notif['key'], $userdata, true ) ) {
					continue;
				}
				unset( $all_registered_notifs[ $k ] );
			}


			return $all_registered_notifs;
		}

		public function user_has_notifications( $id ) {
			$all_registered_notifs = $this->get_notifications();

			$filter_notifs = $this->filter_notifs( $all_registered_notifs, $id );

			return count( $filter_notifs ) > 0 ? true : false;

		}

	}


}


if ( class_exists( 'WFFN_Core' ) ) {
	WFFN_Core::register( 'admin_notifications', 'WFFN_Admin_Notifications' );
}

