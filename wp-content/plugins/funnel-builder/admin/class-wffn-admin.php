<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class to initiate admin functionalists
 * Class WFFN_Admin
 */
if ( ! class_exists( 'WFFN_Admin' ) ) {
	class WFFN_Admin {

		private static $ins = null;
		private $funnel = null;

		/**
		 * @var WFFN_Background_Importer $updater
		 */
		public $wffn_updater;

		/**
		 * WFFN_Admin constructor.
		 */
		public function __construct() {


			/** Admin enqueue scripts*/
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'js_variables' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'maybe_register_breadcrumb_nodes' ), 5 );

			/**
			 * DB updates and table installation
			 */
			add_action( 'admin_init', array( $this, 'check_db_version' ), 990 );
			add_action( 'admin_init', array( $this, 'maybe_update_database_update' ), 995 );


			add_action( 'wp_print_scripts', array( $this, 'no_conflict_mode_script' ), 1000 );
			add_action( 'admin_print_footer_scripts', array( $this, 'no_conflict_mode_script' ), 9 );
			add_action( 'admin_init', array( $this, 'reset_wizard' ) );
			add_action( 'admin_head', array( $this, 'hide_from_menu' ) );


			add_filter( 'get_pages', array( $this, 'add_landing_in_home_pages' ), 10, 2 );
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );

			add_action( 'admin_notices', array( $this, 'maybe_show_notices' ) );
			add_action( 'admin_notices', array( $this, 'remove_all' ), - 1 );
			add_filter( 'plugin_action_links_' . WFFN_PLUGIN_BASENAME, array( $this, 'plugin_actions' ) );

			/** Initiate Background updater if action scheduler is not available for template importing */
			add_action( 'init', array( $this, 'wffn_maybe_init_background_updater' ), 110 );
			add_filter( 'bwf_general_settings_link', function () {
				return admin_url( 'admin.php?page=bwf&path=/funnels' );
			}, 100000 );
			add_filter( 'woofunnels_show_reset_tracking', '__return_true', 999 );
			add_action( 'admin_head', array( $this, 'menu_highlight' ), 99999 );
			add_action( 'pre_get_posts', [ $this, 'load_page_to_home_page' ], 9999 );
			add_filter( 'bwf_settings_config_general', array( $this, 'settings_config' ) );

			add_filter( 'bwf_settings_config_general', array( $this, 'maybe_add_oxygen_in_global_settings' ) );
			add_filter( 'bwf_experiment_ref_link', array( $this, 'maybe_modify_link' ), 10, 2 );

			add_action( 'before_delete_post', array( $this, 'delete_funnel_step_permanently' ), 10, 2 );
			add_filter( 'wffn_rest_get_funnel_steps', array( $this, 'maybe_delete_funnel_step' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'maybe_show_wizard' ), 9 );

		}


		/**
		 * @return WFFN_Admin|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		public function add_automations_menu() {
			$user = WFFN_Core()->role->user_access( 'menu', 'read' );
			if ( $user ) {
				add_submenu_page( 'woofunnels', __( 'Automations', 'funnel-builder' ), __( 'Automations', 'funnel-builder' ) . '<span style="padding-left: 2px;color: #f18200; vertical-align: super; font-size: 9px;"> NEW!</span>', $user, 'bwf&path=/automations', array(
					$this,
					'bwf_funnel_pages',
				) );
			}
		}

		public function register_admin_menu() {
			$steps = WFFN_Core()->steps->get_supported_steps();
			if ( count( $steps ) < 1 ) {
				return;
			}

			$user = WFFN_Core()->role->user_access( 'menu', 'read' );
			if ( $user ) {
				add_submenu_page( 'woofunnels', __( 'Dashboard', 'funnel-builder' ), __( 'Dashboard', 'funnel-builder' ), $user, 'bwf', array(
					$this,
					'bwf_funnel_pages',
				) );

				add_submenu_page( 'woofunnels', __( 'Funnels', 'funnel-builder' ), __( 'Funnels', 'funnel-builder' ), $user, 'bwf&path=/funnels', array(
					$this,
					'bwf_funnel_pages',
				) );

				add_submenu_page( 'woofunnels', __( 'Templates', 'funnel-builder' ), __( 'Templates', 'funnel-builder' ), $user, 'bwf&path=/templates', array(
					$this,
					'bwf_funnel_pages',
				) );

				add_submenu_page( 'woofunnels', __( 'Store Checkout', 'funnel-builder' ), __( 'Store Checkout', 'funnel-builder' ), $user, 'bwf&path=/store-checkout', array(
					$this,
					'bwf_funnel_pages',
				) );
			}

		}

		public function is_basic_exists() {
			return defined( 'WFFN_BASIC_FILE' );

		}

		public function bwf_dashboard() {

			include_once WFFN_PLUGIN_DIR . '/admin/views/funnel-dashboard.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
		}

		public function bwf_funnel_pages() {

			?>
            <div id="wffn-contacts" class="wffn-page">
            </div>
			<?php

			wp_enqueue_style( 'wffn-flex-admin', $this->get_admin_url() . '/assets/css/admin.css', array(), WFFN_VERSION_DEV );


		}

		public function bwf_funnels_funnels() {

			$wffn_page    = filter_input( INPUT_GET, 'page' );
			$wffn_section = filter_input( INPUT_GET, 'section' );
			$view_loaded  = apply_filters( 'wffn_admin_view_loaded', false, $wffn_page, $wffn_section );
			if ( ! $view_loaded && 'bwf_funnels' === $wffn_page ) {
				if ( ! empty( $wffn_section ) ) {
					if ( 'import' === $wffn_section ) {
						include_once WFFN_PLUGIN_DIR . '/admin/views/flex-import.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
					} elseif ( 'funnel' === $wffn_section ) {
						include_once WFFN_PLUGIN_DIR . '/admin/views/flex-funnel-view.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
					} elseif ( 'bwf_settings' === $wffn_section ) {
						?>
                        <script>
                            var bwf_admin_logo = '<?php echo plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/bwf-icon-white-bg.svg'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
                        </script>
						<?php
						echo '<div id="bwf_settings_wrap">';

						echo '</div>';
					} else {
						include_once WFFN_PLUGIN_DIR . '/admin/views/flex-export.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
					}
				} else {
					include_once WFFN_PLUGIN_DIR . '/admin/views/funnels-listing-view.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
				}
			}
		}


		public function admin_enqueue_assets( $hook_suffix ) {
			wp_enqueue_style( 'bwf-admin-font', $this->get_admin_url() . '/assets/css/bwf-admin-font.css', array(), WFFN_VERSION_DEV );


			if ( strpos( $hook_suffix, 'woofunnels_page' ) > - 1 || strpos( $hook_suffix, 'page_woofunnels' ) > - 1 ) {
				wp_enqueue_style( 'bwf-admin-header', $this->get_admin_url() . '/assets/css/admin-global-header.css', array(), WFFN_VERSION_DEV );
			}

			if ( $this->is_wffn_flex_page( 'all' ) ) {

				wp_enqueue_style( 'wffn-admin-swal', $this->get_admin_url() . '/assets/css/sweetalert2.css', array(), WFFN_VERSION_DEV );
				wp_enqueue_style( 'wffn-izimodal-style', $this->get_admin_url() . '/assets/iziModal/izimodal.css', array(), WFFN_VERSION_DEV );
				wp_enqueue_style( 'wffn-flex-admin', $this->get_admin_url() . '/assets/css/admin.css', array(), WFFN_VERSION_DEV );

				if ( $this->is_wffn_flex_page( 'wf-op' ) || $this->is_wffn_flex_page( 'wf-lp' ) || $this->is_wffn_flex_page( 'wf-oty' ) || $this->is_wffn_flex_page( 'wf-ty' ) ) {
					wp_enqueue_script( 'updates' );

					wp_enqueue_script( 'wffn-admin-ajax', $this->get_admin_url() . '/assets/js/wffn-ajax.js', [], WFFN_VERSION_DEV );
					wp_enqueue_script( 'wffn-admin-scripts', $this->get_admin_url() . '/assets/js/wffn-admin.js', array( 'underscore' ), WFFN_VERSION_DEV, true );
					wp_enqueue_script( 'wffn-izimodal-scripts', $this->get_admin_url() . '/assets/iziModal/iziModal.js', array(), WFFN_VERSION_DEV, true );
					wp_enqueue_script( 'wffn-sweetalert', $this->get_admin_url() . '/assets/js/wffn-sweetalert.min.js', array(), WFFN_VERSION_DEV, true );

					/**
					 * Including vuejs assets
					 */
					wp_enqueue_style( 'wffn-vue-multiselect', $this->get_admin_url() . '/assets/vuejs/vue-multiselect.min.css', array(), WFFN_VERSION_DEV );

					wp_enqueue_script( 'wffn-vuejs', $this->get_admin_url() . '/assets/vuejs/vue.min.js', array(), '2.6.10' );
					wp_enqueue_script( 'wffn-vue-vfg', $this->get_admin_url() . '/assets/vuejs/vfg.min.js', array(), '2.3.4' );
					wp_enqueue_script( 'wffn-vue-multiselect', $this->get_admin_url() . '/assets/vuejs/vue-multiselect.min.js', array(), WFFN_VERSION_DEV );
				}


				if ( WFFN_Core()->admin->is_wffn_flex_page( 'wf-op' ) ) {
					wp_enqueue_style( 'jquery-ui' );
					wp_enqueue_script( 'jquery-ui-sortable' );
				}

				if ( WFFN_Core()->admin->is_wffn_flex_page() ) {
					$this->load_react_app( 'main' );
					wp_localize_script( 'wffn-contact-admin', 'bwfAdminGen', BWF_Admin_General_Settings::get_instance()->get_localized_data() );

					add_filter( 'wffn_noconflict_scripts', function ( $scripts = array() ) {
						return array_merge( $scripts, array( 'wffn-contact-admin' ) );
					} );
				}

				$data = array();
				$data = apply_filters( 'wffn_localized_nonces_data', $data );
				wp_localize_script( 'wffn-admin-scripts', 'wffnParams', $data );
				do_action( 'wffn_admin_assets', $this );
			}
		}

		public function get_local_app_path() {
			return '/admin/views/contact/dist/';
		}

		public function load_react_app( $app_name = 'main' ) {
			$min               = 60 * get_option( 'gmt_offset' );
			$sign              = $min < 0 ? "-" : "+";
			$absmin            = abs( $min );
			$tz                = sprintf( "%s%02d:%02d", $sign, $absmin / 60, $absmin % 60 );
			$contact_page_data = array(
				'is_wc_active'        => false,
				'date_format'         => get_option( 'date_format', 'F j, Y' ) . ' ' . get_option( 'time_format', 'g:i a' ),
				'is_pro'              => defined( 'WFFN_PRO_VERSION' ),
				'is_basic'            => $this->is_basic_exists(),
				'license_exist'       => [
					'funnel'      => $this->get_license_status(),
					'wc_checkout' => $this->get_checkout_license_status(),
					'upsell'      => $this->get_upsell_license_status()
				],
				'app_path'            => WFFN_Core()->get_plugin_url() . '/admin/views/contact/dist/',
				'timezone'            => $tz,
				'updated_pro_version' => defined( 'WFFN_PRO_VERSION' ) && version_compare( WFFN_PRO_VERSION, '2.4.0', '>=' ),
				'plugin_screen_url'   => admin_url( 'plugins.php?plugin_status=all' ),
				'funnel_page_url'     => admin_url( 'admin.php?page=bwf&path=/funnels' ),
				'get_pro_link'        => WFFN_Core()->admin->get_pro_link(),
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$currency                          = get_woocommerce_currency();
				$contact_page_data['currency']     = [
					'code'              => $currency,
					'precision'         => wc_get_price_decimals(),
					'symbol'            => html_entity_decode( get_woocommerce_currency_symbol( $currency ) ),
					'symbolPosition'    => get_option( 'woocommerce_currency_pos' ),
					'decimalSeparator'  => wc_get_price_decimal_separator(),
					'thousandSeparator' => wc_get_price_thousand_separator(),
					'priceFormat'       => html_entity_decode( get_woocommerce_price_format() ),
				];
				$contact_page_data['is_wc_active'] = true;
				$contact_page_data['admin_url']    = esc_url( $this->get_admin_url() );
			}
			$frontend_dir = ( 0 === WFFN_REACT_ENVIRONMENT ) ? WFFN_REACT_DEV_URL : WFFN_Core()->get_plugin_url() . $this->get_local_app_path();
			if ( class_exists( 'WooCommerce' ) ) {
				wp_dequeue_style( 'woocommerce_admin_styles' );
				wp_dequeue_style( 'wc-components' );
			}


			$assets_path = 1 === WFFN_REACT_ENVIRONMENT ? WFFN_PLUGIN_DIR . $this->get_local_app_path() . "$app_name.asset.php" : $frontend_dir . "/$app_name.asset.php";
			$assets      = file_exists( $assets_path ) ? include $assets_path : array(
				'dependencies' => array(
					'lodash',
					'moment',
					'react',
					'react-dom',
					'wp-api-fetch',
					'wp-components',
					'wp-compose',
					'wp-date',
					'wp-deprecated',
					'wp-dom',
					'wp-element',
					'wp-hooks',
					'wp-html-entities',
					'wp-i18n',
					'wp-keycodes',
					'wp-polyfill',
					'wp-primitives',
					'wp-url',
					'wp-viewport',
					'wp-color-picker',
					'wp-i18n',
				),
				'version'      => time(),
			);
			$deps        = ( isset( $assets['dependencies'] ) ? array_merge( $assets['dependencies'], array( 'jquery' ) ) : array( 'jquery' ) );
			$version     = $assets['version'];

			$script_deps = array_filter( $deps, function ( $dep ) {
				return false === strpos( $dep, 'css' );
			} );
			if ( 'settings' === $app_name ) {
				$script_deps = array_merge( $script_deps, array( 'wp-color-picker' ) );
			}

			if ( class_exists( 'WFFN_Header' ) ) {
				$header_ins                       = new WFFN_Header();
				$contact_page_data['header_data'] = $header_ins->get_render_data();
			}


			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'wffn_material_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons+Outlined' );
			wp_enqueue_style( 'wffn-contact-admin', $frontend_dir . "$app_name.css", array(), $version );
			wp_register_script( 'wffn-contact-admin', $frontend_dir . "$app_name.js", $script_deps, $version, true );
			wp_localize_script( 'wffn-contact-admin', 'wffn_contacts_data', $contact_page_data );
			wp_enqueue_script( 'wffn-contact-admin' );
			wp_set_script_translations( 'wffn-contact-admin', 'funnel-builder' );

			$this->setup_js_for_localization( $app_name, $frontend_dir, $script_deps, $version );
		}

		public function get_checkout_license_status() {
			$licence_exist = false;
			if ( wffn_is_wc_active() && class_exists( 'WFACP_Core' ) && method_exists( WFACP_Core()->importer, 'get_license_key' ) && false !== WFACP_Core()->importer->get_license_key() || ( false !== $this->get_license_status() ) ) {
				$licence_exist = true;
			}

			return $licence_exist;
		}

		public function get_upsell_license_status() {
			$licence_exist = false;

			if ( class_exists( 'WFOCU_Remote_Template_Importer' ) && ( false !== WFOCU_Remote_Template_Importer::get_instance()->get_license_key() || false !== $this->get_license_status() ) ) {
				$licence_exist = true;
			}

			return $licence_exist;
		}

		public function get_admin_url() {
			return WFFN_Core()->get_plugin_url() . '/admin';
		}

		public function get_admin_path() {
			return WFFN_PLUGIN_DIR . '/admin';
		}

		/**
		 * @param string $section
		 *
		 * @return bool
		 */
		public function is_wffn_flex_page( $page = 'bwf' ) {

			if ( isset( $_GET['page'] ) && $_GET['page'] === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}

			if ( isset( $_GET['page'] ) && 'wf-op' === $_GET['page'] && 'all' === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}
			if ( isset( $_GET['page'] ) && 'bwf' === $_GET['page'] && 'all' === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}
			if ( isset( $_GET['page'] ) && 'wf-lp' === $_GET['page'] && 'all' === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}
			if ( isset( $_GET['page'] ) && 'wf-oty' === $_GET['page'] && 'all' === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}
			if ( isset( $_GET['page'] ) && 'wf-ty' === $_GET['page'] && 'all' === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}


			return false;
		}

		/**
		 * Defines scripts needed for "no conflict mode".
		 *
		 * @access public
		 * @global $wp_scripts
		 *
		 * @uses WFFN_Admin::no_conflict_mode()
		 */
		public function no_conflict_mode_script() {
			if ( ! apply_filters( 'wffn_no_conflict_mode', true ) ) {
				return;
			}

			global $wp_scripts;

			$wp_required_scripts   = array( 'admin-bar', 'common', 'jquery-color', 'utils', 'svg-painter', 'updates' );
			$wffn_required_scripts = apply_filters( 'wffn_no_conflict_scripts', array(
				'common'       => array(
					'wffn-admin-ajax',
					'wffn-izimodal-scripts',
					'wffn-sweetalert',
					'wffn-vuejs',
					'wffn-vue-vfg',
					'wffn-vue-multiselect',
					'wffn-admin-scripts',
					'query-monitor',
				),
				'steps'        => array(
					'wffn-sortable-js',
					'wffn-vue-sortable-admin',
				),
				'funnel'       => array(
					'wffn-select2-js',
					'wffn-contact-admin',
				),
				'wf-lp'        => array(
					'wffn_lp_js',
				),
				'wf-ty'        => array(
					'wffn_tp_js',
				),
				'bwf_settings' => array( 'bwf-general-settings', 'wffn-contact-admin' ),
			) );
			$this->no_conflict_mode( $wp_scripts, $wp_required_scripts, $wffn_required_scripts, 'scripts' );
		}

		/**
		 * Runs "no conflict mode".
		 *
		 * @param $wp_objects
		 * @param $wp_required_objects
		 * @param $wffn_required_scripts
		 * @param string $type
		 */
		public function no_conflict_mode( &$wp_objects, $wp_required_objects, $wffn_required_scripts, $type = 'scripts' ) {

			$current_page = trim( strtolower( filter_input( INPUT_GET, 'page' ) ) );

			if ( 'bwf_funnels' !== $current_page ) {
				return;
			}

			$section      = filter_input( INPUT_GET, 'section' );
			$page_objects = isset( $wffn_required_scripts[ $section ] ) ? $wffn_required_scripts[ $section ] : array();

			//disable no-conflict if $page_objects is false
			if ( $page_objects === false ) {
				return;
			}

			if ( ! is_array( $page_objects ) ) {
				$page_objects = array();
			}

			//merging wp scripts with wffn scripts
			$required_objects = array_merge( $wp_required_objects, $wffn_required_scripts['common'], $page_objects );

			//allowing addons or other products to change the list of no conflict scripts
			$required_objects = apply_filters( "wffn_noconflict_{$type}", $required_objects );

			$queue = array();
			foreach ( $wp_objects->queue as $object ) {
				if ( in_array( $object, $required_objects, true ) ) {
					$queue[] = $object;
				}
			}
			$wp_objects->queue = $queue;

			$required_objects = $this->add_script_dependencies( $wp_objects->registered, $required_objects );

			//unregistering scripts
			$registered = array();
			foreach ( $wp_objects->registered as $script_name => $script_registration ) {
				if ( in_array( $script_name, $required_objects, true ) ) {
					$registered[ $script_name ] = $script_registration;
				}
			}

			$wp_objects->registered = $registered;
		}

		/**
		 * Adds script dependencies needed.
		 *
		 * @param $registered
		 * @param $scripts
		 *
		 * @return array
		 */
		public function add_script_dependencies( $registered, $scripts ) {

			//gets all dependent scripts linked to the $scripts array passed
			do {
				$dependents = array();
				foreach ( $scripts as $script ) {
					$deps = isset( $registered[ $script ] ) && is_array( $registered[ $script ]->deps ) ? $registered[ $script ]->deps : array();
					foreach ( $deps as $dep ) {
						if ( ! in_array( $dep, $scripts, true ) && ! in_array( $dep, $dependents, true ) ) {
							$dependents[] = $dep;
						}
					}
				}
				$scripts = array_merge( $scripts, $dependents );
			} while ( ! empty( $dependents ) );

			return $scripts;
		}


		public function js_variables() {
			if ( $this->is_wffn_flex_page( 'all' ) ) {
				$steps_data               = WFFN_Common::get_steps_data();
				$substeps_data            = WFFN_Common::get_substeps_data();
				$substeps_data['substep'] = true;

				$funnel_data      = WFFN_Common::get_funnel_data();
				$funnel_delete    = WFFN_Common::get_funnel_delete_data();
				$funnel_duplicate = WFFN_Common::get_funnel_duplicate_data();

				$success_popups                      = WFFN_Common::get_success_popups();
				$success_popups['funnel_duplicated'] = __( 'Funnel successfully duplicated.', 'woofunnel-flex-funnels' );
				$success_popups['funnel_deleted']    = __( 'Funnel successfully deleted.', 'woofunnel-flex-funnels' );

				$success_popups['popup_type'] = 'info';
				$success_popups['subtitle']   = '';
				$success_popups['duplicated'] = __( 'Step successfully duplicated.', 'funnel-builder' );

				$loader_popups = WFFN_Common::get_loader_popups();

				$loader_popups['popup_type']                = 'loader';
				$loader_popups['subtitle']                  = __( 'Please wait it may take couple of moments...', 'funnel-builder' );
				$loader_popups['duplicate']['title']        = __( 'Duplicating the step', 'funnel-builder' );
				$loader_popups['duplicate_funnel']['title'] = __( 'Duplicating the step', 'funnel-builder' );

				$funnel    = $this->get_funnel();
				$funnel_id = $funnel->get_id();

				if ( $funnel_id > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
				}


				$upsell_exist = function_exists( 'WFOCU_Core' );


				$data = array(

					'funnel_id'      => $funnel_id,
					'steps_data'     => $steps_data,
					'substeps'       => $substeps_data,
					'success_popups' => $success_popups,
					'loader_popups'  => $loader_popups,
					'upsell_exist'   => $upsell_exist,
					'icons'          => array(
						'error_cross'   => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" class="wffn_loader wffn_loader_error">
                        <circle fill="#e6283f" stroke="#e6283f" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" class="path circle"></circle>
                        <line fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" class="path line"></line>
                        <line fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2" class="path line"></line>
                    </svg>',
						'success_check' => '<svg class="wffn_loader wffn_loader_ok" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                <circle class="path circle" fill="#13c37b" stroke="#13c37b" stroke-width="5" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"></circle>
                                <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="9" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "></polyline>
                            </svg>',
						'delete_alert'  => '<div class="swal2-header wf_funnel-icon-without-swal"><div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"><span class="swal2-icon-text">!</span></div></div>',
					),
					'images'         => array(
						'readiness_loader' => esc_url( $this->get_admin_url() ) . '/assets/img/readiness-loader.gif',
						'check'            => esc_url( $this->get_admin_url() ) . '/assets/img/check.png',
					),

					'duplicate_funnel_popup' => array(
						'popup_type' => 'loader',
						'title'      => __( 'Please wait while we duplicating your funnel', 'woofunnel-flex-funnels' ),
						'submit_btn' => __( 'Yes, Duplicate', 'woofunnel-flex-funnels' ),
						'funnel'     => $funnel_duplicate,
						'substeps'   => array(
							'title' => __( 'Are you sure you want to duplicate this {{SUBSTEP_TITLE}}?', 'woofunnel-flex-funnels' ),
						),
					),

					'delete_popup' => array(
						'popup_type' => 'alert',
						'title'      => __( 'Are you sure you want to delete this Step?', 'funnel-builder' ),
						'subtitle'   => __( 'It will also delete the analytics data associated with the step, Please disable the step if you want to keep the data.', 'funnel-builder' ),
						'submit_btn' => __( 'Yes, Delete', 'funnel-builder' ),
						'funnel'     => $funnel_delete,
						'substeps'   => array(
							'title' => __( 'Are you sure you want to delete this {{SUBSTEP_TITLE}}?', 'funnel-builder' ),

						),
					),

					'add_step_form'     => array(
						'popup_type'           => 'add_form',
						'submit_btn'           => __( 'Create', 'funnel-builder' ),
						'default_design_model' => array(
							'title'       => '',
							'design'      => 'scratch',
							'design_name' => '',
							'allDesigns'  => [],
						),
						'not_found'            => __( 'Oops! No elements found. Consider changing the search query.', 'funnel-builder' ),
						'label_texts'          => array(
							'title'  => array(
								'label'       => __( 'Title', 'funnel-builder' ),
								'placeholder' => __( 'Enter Name', 'funnel-builder' ),
							),
							'design' => array(
								'values' => array(
									array(
										'name'  => __( 'Create From Scratch', 'funnel-builder' ),
										'value' => 'scratch',
									),
									array(
										'name'  => __( 'Copy from existing', 'funnel-builder' ),
										'value' => 'existing',
									),
								),
							),
						),
					),
					'choose_step_popup' => array(
						'popup_type'  => 'choose_step_popup',
						'popup_title' => __( 'Select Step', 'funnel-builder' ),
						'submit_btn'  => __( 'Continue', 'funnel-builder' ),
					),
					'update_funnel'     => array(
						'submit_btn'  => __( 'Update', 'funnel-builder' ),
						'label_texts' => array(
							'title' => array(
								'label'       => __( 'Name', 'funnel-builder' ),
								'placeholder' => __( 'Enter Name', 'funnel-builder' ),
								'value'       => $funnel->get_title(),
							),
							'desc'  => array(
								'label'       => __( 'Description (optional)', 'funnel-builder' ),
								'placeholder' => __( 'Enter Description (optional)', 'funnel-builder' ),
								'value'       => $funnel->get_desc(),
							),
						),
					),
					'funnel_home_link'  => admin_url( 'admin.php?page=bwf&path=/funnels' ),
					'flex_links'        => array(
						'edit'      => __( 'Edit', 'funnel-builder' ),
						'view'      => __( 'View', 'funnel-builder' ),
						'duplicate' => __( 'Duplicate', 'funnel-builder' ),
						'delete'    => __( 'Delete', 'funnel-builder' ),
					),
					'openReOrderSteps'  => array(
						'popup_type'   => 'loader',
						'title'        => 'Re-Ordering Steps...',
						'afterSuccess' => array(
							'popup_type' => 'info',
							'title'      => 'Reordered Successfully',
						),
						'subtitle'     => '',
						'popup_title'  => __( 'Reorder Steps', 'funnel-builder' ),
						'submit_btn'   => __( 'Continue', 'funnel-builder' ),
					),
					'openEyeIcon'       => array(
						'popup_type'   => 'loader',
						'title'        => 'Getting Data...',
						'afterSuccess' => array(
							'popup_type' => 'info',
							'title'      => 'Reordered Successfully',
						),
						'modal'        => array(
							'width' => 800,
						),
						'subtitle'     => '',
						'popup_title'  => __( 'Reorder Steps', 'funnel-builder' ),
						'submit_btn'   => __( 'Continue', 'funnel-builder' ),
					),
					'flexes'            => array(
						'current_state' => 'second',
						'funnel_data'   => $funnel_data,
					),
				);


				$data['filters']                 = $this->get_template_filter();
				$data['currentStepsFilter']      = 'all';
				$data['view_link']               = $funnel->get_view_link();
				$data['nonce_import_design']     = wp_create_nonce( 'wffn_import_design' );
				$data['nonce_activate_plugin']   = wp_create_nonce( 'wffn_activate_plugin' );
				$data['nonce_get_import_status'] = wp_create_nonce( 'wffn_get_import_status' );
				$data['settings_texts']          = apply_filters( 'wffn_funnel_settings', [] );


				$data['texts'] = array(
					'settings_success' => __( 'Changes saved', 'funnel-builder' ),
					'copy_success'     => __( 'Link copied!', 'funnel-builder' ),
				);

				$data['importer'] = [
					'activate_template' => [
						'heading'     => __( 'Are you sure you want to Activate this funnel?', 'funnel-builder' ),
						'sub_heading' => '',
						'button_text' => __( 'Yes, activate this funnel!', 'funnel-builder' ),
					],
					'add_template'      => [
						'heading'     => __( 'Are you sure you want to import this funnels?', 'funnel-builder' ),
						'sub_heading' => '',
						'button_text' => __( 'Yes, Import this funnel!', 'funnel-builder' ),
					],
				];
				$data['i18n']     = [
					'plugin_activate' => __( 'Activating plugin...', 'funnel-builder' ),
					'plugin_install'  => __( 'Installing plugin...', 'funnel-builder' ),
					'preparingsteps'  => __( 'Preparing steps...', 'funnel-builder' ),
					'redirecting'     => __( 'Redirecting...', 'funnel-builder' ),
					'importing'       => __( 'Importing...', 'funnel-builder' ),
					'custom_import'   => __( 'Setting up your funnel...', 'funnel-builder' ),
					'ribbons'         => array(
						'lite' => __( 'Lite', 'funnel-builder' ),
						'pro'  => __( 'PRO', 'funnel-builder' )
					),
					'test'            => __( 'Test', 'funnel-builder' ),
				];
				if ( wffn_is_wc_active() && false === $upsell_exist ) {
					$data['wc_upsells'] = [
						'type'      => 'wc_upsells',
						'group'     => WFFN_Steps::STEP_GROUP_WC,
						'title'     => __( 'One Click Upsells', 'funnel-builder' ),
						'desc'      => __( 'Deploy post purchase one click upsells to increase average order value', 'funnel-builder' ),
						'dashicons' => 'dashicons-tag',
						'icon'      => 'tags',
						'lock_img'  => esc_url( $this->get_admin_url() ) . '/assets/img/lock.png',
						'pro'       => true,
					];
				}
				if ( $this->is_wffn_flex_page( 'all' ) ) {

					$data['pageBuildersTexts']   = WFFN_Core()->page_builders->localize_page_builder_texts();
					$data['pageBuildersOptions'] = WFFN_Core()->page_builders->get_plugins_groupby_page_builders();

				}


				$data['welcome_note_dismiss'] = get_user_meta( get_current_user_id(), '_wffn_welcome_note_dismissed', true );//phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
				$data['is_bump_dismissed']    = get_user_meta( get_current_user_id(), '_wffn_bump_promotion_hide', true );//phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
				$data['is_upsell_dismissed']  = get_user_meta( get_current_user_id(), '_wffn_upsell_promotion_hide', true );//phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta

				$data['user_display_name']    = get_user_by( 'id', get_current_user_id() )->display_name;
				$data['currrent_logged_user'] = get_current_user_id();
				$data['is_rtl']               = is_rtl();

				$default_builder = BWF_Admin_General_Settings::get_instance()->get_option( 'default_selected_builder' );

				$data['default_builder']          = ( ! empty( $default_builder ) ) ? $default_builder : 'elementor';
				$data['is_ab_experiment']         = class_exists( 'BWFABT_Core' ) ? 1 : 0;
				$data['is_ab_experiment_support'] = ( class_exists( 'BWFABT_Core' ) && version_compare( BWFABT_VERSION, '1.3.5', '>' ) ) ? 1 : 0;
				$data['admin_url']                = admin_url();
				$data['wizard_status']            = get_option( '_wffn_onboarding_completed', false );
				$data['automation_img_url']       = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/automation-img.png' );
				$data['automation_img_modal_url'] = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/automation-img-modal.png' );
				$data['automation_plugin_status'] = WFFN_Core()->page_builders->get_plugin_status( 'wp-marketing-automations/wp-marketing-automations.php' );
				$data['fkcart_img_url']           = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/fkcart-img.png' );
				$data['fkcart_plugin_status']     = WFFN_Core()->page_builders->get_plugin_status( 'cart-for-woocommerce/plugin.php' );
				$data['automation_count']         = class_exists( 'BWFAN_Model_Automations' ) ? BWFAN_Model_Automations::count_rows() : 0;
				$data['ob_arrow_blink_img_url']   = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/arrow-blink.gif' );
				$data['ob_modal_img_path']        = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/ob_modal/' );
				$data['user_preferences']         = array( 'notices_close' => get_user_meta( get_current_user_id(), '_bwf_notifications_close', true ) ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
				$data['user_has_notifications']   = WFFN_Core()->admin_notifications->user_has_notifications( get_current_user_id() );
				$data['pro_link']                 = $this->get_pro_link();
				$data['upgrade_button_text']      = __( 'Upgrade to PRO Now', 'funnel-builder' );
				$data['pro_info_title']           = __( '{feature_name} is a PRO Feature', 'funnel-builder' );
				$data['pro_info_subtitle']        = __( "We're sorry, the {feature_name} is not available on your plan. Please upgrade to the PRO plan to unlock all these awesome features.", 'funnel-builder' );
				$data['pro_info_lock_icon']       = '<img src="' . WFFN_PLUGIN_URL . '/admin/assets/img/lock.svg">';
				?>
                <script>window.wffn = <?php echo wp_json_encode( apply_filters( 'wffn_localize_admin', $data ) ); ?></script>
				<?php
			}
		}

		/**
		 * Get the already setup funnel object
		 * @return WFFN_Funnel
		 */
		public function get_funnel( $funnel_id = 0 ) {
			if ( $funnel_id > 0 ) {
				if ( $this->funnel instanceof WFFN_Funnel && $funnel_id === $this->funnel->get_id() ) {
					return $this->funnel;
				}
				$this->initiate_funnel( $funnel_id );
			}
			if ( $this->funnel instanceof WFFN_Funnel ) {
				return $this->funnel;
			}
			$this->funnel = new WFFN_Funnel( $funnel_id );

			return $this->funnel;
		}

		/**
		 * @param $funnel_id
		 */
		public function initiate_funnel( $funnel_id ) {
			if ( ! empty( $funnel_id ) ) {
				$this->funnel = new WFFN_Funnel( $funnel_id, true );
				/**
				 * IF we do not have any funnel set against this ID then die here
				 */
				if ( empty( $this->funnel->get_id() ) ) {
					wp_die( esc_html__( 'No funnel exist with this id.', 'funnel-builder' ) );
				}
			}
		}

		public function get_all_templates() {
			$templates = WooFunnels_Dashboard::get_all_templates();
			$json_data = isset( $templates['funnel'] ) ? $templates['funnel'] : [];

			foreach ( $json_data as &$templates ) {
				if ( is_array( $templates ) ) {
					foreach ( $templates as $k => &$temp_val ) {
						if ( isset( $temp_val['pro'] ) && 'yes' === $temp_val['pro'] ) {
							$temp_val['license_exist'] = ( true === WFFN_Core()->admin->get_license_status() ? true : false );

							/**
							 * Check if template is set to replace lite template
							 * if yes and license exists then replace lite, otherwise keep lite and unset pro
							 */
							if ( isset( $temp_val['replace_to'] ) ) {
								if ( false === $temp_val['license_exist'] ) {
									unset( $templates[ $k ] );
								} else {
									unset( $templates[ $temp_val['replace_to'] ] );
								}
							}

						}
					}
				}
			}

			$designs = [
				'custom' => [
					'custom_1' => [
						'type'               => 'view',
						'show_import_popup'  => 'no',
						'slug'               => 'custom_1',
						'build_from_scratch' => true,
						"group"              => [ "sales", "optin", "wc_checkout" ]
					],
				],
			];

			if ( ! isset( $json_data['divi'] ) || ! is_array( $json_data['divi'] ) ) {
				$designs_divi = [
					'divi' => [
						'divi_1' => [
							'type'               => 'view',
							'show_import_popup'  => 'no',
							'slug'               => 'divi_1',
							'build_from_scratch' => true,
							"group"              => [ "sales", "optin", "wc_checkout" ]
						],
					],
				];
				$json_data    = array_merge( $json_data, $designs_divi );
			}

			return array_merge( $json_data, $designs );

		}

		public static function get_template_filter() {

			$options = [
				'all'   => __( 'All', 'funnel-builder' ),
				'sales' => __( 'Sales', 'funnel-builder' ),
				'optin' => __( 'Optin', 'funnel-builder' ),
			];

			return $options;
		}

		public function get_template_nice_name_by( $template, $template_group ) {
			$get_all = $this->get_all_templates();

			if ( ! isset( $get_all[ $template_group ] ) ) {
				return '';
			}
			if ( ! isset( $get_all[ $template_group ][ $template ] ) ) {
				return '';
			}

			return $get_all[ $template_group ][ $template ]['name'];

		}

		public function get_license_status() {
			$license_key = WFFN_Core()->remote_importer->get_license_key( true );


			if ( empty( $license_key ) ) {
				return false;
			} elseif ( isset( $license_key['manually_deactivated'] ) && 1 === $license_key['manually_deactivated'] ) {
				return 'deactiavted';
			} elseif ( isset( $license_key['expired'] ) && 1 === $license_key['expired'] ) {
				return 'expired';
			}

			return true;
		}


		/**
		 * @hooked over `admin_enqueue_scripts`
		 * Check the environment and register appropiate node for the breadcrumb to process
		 * @since 1.0.0
		 */
		public function maybe_register_breadcrumb_nodes() {
			$single_link = '';
			$funnel      = null;
			/**
			 * IF its experiment builder UI
			 */
			if ( $this->is_wffn_flex_page() ) {

				$funnel = $this->get_funnel();

			} else {

				/**
				 * its its a page where experiment page is a referrer
				 */
				$get_ref = filter_input( INPUT_GET, 'wffn_funnel_ref' );
				$get_ref = apply_filters( 'maybe_setup_funnel_for_breadcrumb', $get_ref );
				if ( ! empty( $get_ref ) ) {
					$funnel = $this->get_funnel( $get_ref );
					if ( absint( $funnel->get_id() ) === WFFN_Common::get_store_checkout_id() ) {
						$single_link = WFFN_Common::get_store_checkout_edit_link();
					} else {
						$single_link = WFFN_Common::get_funnel_edit_link( $funnel->get_id() );
					}
				}

			}

			/**
			 * Register nodes
			 */
			if ( ! empty( $funnel ) && null === filter_input( INPUT_GET, 'bwf_exp_ref' ) ) {

				BWF_Admin_Breadcrumbs::register_node( array(
					'text' => $funnel->get_title(),
					'link' => $single_link,
				) );
				BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel->get_id() );

			}


		}

		public function get_tab_link( $tab ) {
			return $tab['link'];
		}

		public function get_date_format() {
			return get_option( 'date_format', '' ) . ' ' . get_option( 'time_format', '' );
		}

		/**
		 * @return array
		 */
		public function get_funnels( $args = array() ) {

			$paged = isset( $_GET['paged'] ) ? absint( wffn_clean( $_GET['paged'] ) ) : 0;  // phpcs:ignore WordPress.Security.NonceVerification

			if ( isset( $args['s'] ) ) {
				$search_str = wffn_clean( $args['s'] );
			} else {
				$search_str = isset( $_REQUEST['s'] ) ? wffn_clean( $_REQUEST['s'] ) : '';  // phpcs:ignore WordPress.Security.NonceVerification
			}

			if ( isset( $args['status'] ) ) {
				$status = wffn_clean( $args['status'] );
			} else {
				$status = isset( $_REQUEST['status'] ) ? wffn_clean( $_REQUEST['status'] ) : '';  // phpcs:ignore WordPress.Security.NonceVerification
			}
			$args['meta'] = isset( $args['meta'] ) ? $args['meta'] : [];
			$limit        = isset( $args['limit'] ) ? $args['limit'] : $this->posts_per_page();

			$sql_query = 'SELECT {table_name}.id as funnel_id FROM {table_name}';

			$args = apply_filters( 'wffn_funnels_args_query', $args );

			if ( isset( $args['meta'] ) && is_array( $args['meta'] ) && ! empty( $args['meta'] ) && ! isset( $args['meta']['compare'] ) ) {
				$args['meta']['compare'] = '=';
			}

			/*
			 * Trying to add join in query base on meta
			 */
			if ( ! empty( $args['meta'] ) ) {
				if ( $args['meta']['compare'] === 'NOT_EXISTS' ) {
					$sql_query .= ' LEFT JOIN ';
				} else {
					$sql_query .= ' INNER JOIN ';
				}
				$sql_query .= '{table_name_meta} ON ( {table_name}.id = {table_name_meta}.bwf_funnel_id ';
				if ( $args['meta']['compare'] === 'NOT_EXISTS' ) {
					$sql_query .= 'AND {table_name_meta}.meta_key = \'' . $args['meta']['key'] . '\'';
				}
				$sql_query .= ')';

			}

			/*
			 * where clause start here in query
			 */
			$sql_query .= ' WHERE 1=1';


			if ( ! empty( $status ) && 'all' !== $status ) {
				$status    = ( 'live' === $status ) ? 1 : 0;
				$sql_query .= ' AND `status` = ' . "'$status'";
			}

			if ( ! empty( $search_str ) ) {
				$sql_query .= " AND ( `title` LIKE '%" . $search_str . "%' OR `desc` LIKE '%" . $search_str . "%' )";
			}
			if ( ! empty( $args['meta'] ) ) {
				if ( $args['meta']['compare'] === 'NOT_EXISTS' ) {
					$sql_query .= ' AND ({table_name_meta}.bwf_funnel_id IS NULL) GROUP BY {table_name}.id';
				} else {
					$sql_query .= ' AND ( {table_name_meta}.meta_key = \'' . $args['meta']['key'] . '\' AND {table_name_meta}.meta_value = \'' . $args['meta']['value'] . '\' )';
				}
			}
			$sql_query .= " ORDER BY {table_name}.id DESC";

			$found_funnels = WFFN_Core()->get_dB()->get_results( $sql_query );

			$if_paged = false;
			if ( ! isset( $args['funnels'] ) && count( $found_funnels ) > $limit ) {
				$paged = ( $paged > 0 ) ? ( $paged - 1 ) : $paged;

				if ( isset( $args['offset'] ) ) {
					$sql_query .= ' LIMIT ' . $args['offset'] . ', ' . $limit;;
				} else {
					$sql_query .= ' LIMIT ' . $limit * $paged . ', ' . $limit;
				}
				$if_paged = true;
			}

			$funnel_ids = ( $if_paged ) ? WFFN_Core()->get_dB()->get_results( $sql_query ) : $found_funnels;
			$items      = array();

			foreach ( $funnel_ids as $funnel_id ) {
				$funnel = new WFFN_Funnel( $funnel_id['funnel_id'] );
				$steps  = $funnel->get_steps();
				$view   = ( is_array( $steps ) && count( $steps ) > 0 ) ? get_permalink( $steps[0]['id'] ) : "";

				$row_actions = array();

				$row_actions['id'] = array(
					'action' => 'id',
					'text'   => 'ID: ' . $funnel->get_id(),
					'link'   => '',
					'attrs'  => '',
				);

				$row_actions['edit'] = array(
					'action' => 'edit',
					'text'   => __( 'Edit', 'funnel-builder' ),
					'link'   => WFFN_Common::get_funnel_edit_link( $funnel->get_id() ),
					'attrs'  => '',
				);

				$row_actions['view'] = array(
					'action' => 'view',
					'text'   => __( 'View', 'funnel-builder' ),
					'link'   => $view,
					'attrs'  => 'target="_blank"',
				);

				$row_actions['contacts'] = array(
					'action' => 'contacts',
					'text'   => __( 'Contact', 'funnel-builder' ),
					'link'   => WFFN_Common::get_funnel_edit_link( $funnel->get_id(), '/contacts' ),
					'attrs'  => '',
				);

				$row_actions['analytics'] = array(
					'action' => 'analytics',
					'text'   => __( 'Analytics', 'funnel-builder' ),
					'link'   => WFFN_Common::get_funnel_edit_link( $funnel->get_id(), '/analytics' ),
					'attrs'  => '',
				);

				$row_actions['duplicate'] = array(
					'action' => 'duplicate',
					'text'   => __( 'Duplicate', 'funnel-builder' ),
					'link'   => 'javascript:void(0);',
					'attrs'  => 'v-on:click="duplicateFunnel(' . $funnel->get_id() . ')" class="wffn-duplicate-funnel" data-funnel-id="' . $funnel->get_id() . '" id="wffn_duplicate_' . $funnel->get_id() . '"',
				);

				$row_actions['delete'] = array(
					'action' => 'delete',
					'text'   => __( 'Delete', 'funnel-builder' ),
					'link'   => 'javascript:void(0);',
					'attrs'  => 'v-on:click="deleteFunnel(' . $funnel->get_id() . ')" class="wffn-delete-funnel" data-funnel-id="' . $funnel->get_id() . '" id="wffn_delete_' . $funnel->get_id() . '"',
				);

				$items[] = array(
					'id'          => $funnel->get_id(),
					'title'       => $funnel->get_title(),
					'desc'        => $funnel->get_desc(),
					'date_added'  => $funnel->get_date_added(),
					'steps'       => $steps,
					'row_actions' => $row_actions,
					'__funnel'    => $funnel,
				);
			}

			$found_posts = array( 'found_posts' => count( $found_funnels ) );

			$found_posts['items'] = $items;

			return apply_filters( 'wffn_funnels_lists', $found_posts );
		}

		public function posts_per_page() {
			return 20;
		}


		public function hide_from_menu() {
			global $submenu;
			foreach ( $submenu as $key => $men ) {
				if ( 'woofunnels' !== $key ) {
					continue;
				}
				foreach ( $men as $k => $d ) {
					if ( 'woofunnels-settings' === $d[2] ) {
						unset( $submenu[ $key ][ $k ] );
					}
				}
			}
		}


		/**
		 * Adding landing pages in homepage display settings
		 *
		 * @param $pages
		 * @param $args
		 *
		 * @return array
		 */
		public function add_landing_in_home_pages( $pages, $args ) {
			if ( is_array( $args ) && isset( $args['name'] ) && 'page_on_front' !== $args['name'] && '_customize-dropdown-pages-page_on_front' !== $args['name'] ) {
				return $pages;
			}

			if ( is_array( $args ) && isset( $args['name'] ) && ( 'page_on_front' === $args['name'] || '_customize-dropdown-pages-page_on_front' === $args['name'] ) ) {
				$landing_pages = get_posts( array( //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
					'post_type'   => WFFN_Core()->landing_pages->get_post_type_slug(),
					'numberposts' => 100,
					'post_status' => 'publish'
				) );


				$optin_pages = get_posts( array( //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
					'post_type'   => WFOPP_Core()->optin_pages->get_post_type_slug(),
					'numberposts' => 100,
					'post_status' => 'publish'
				) );


				$pages = array_merge( $pages, $landing_pages, $optin_pages );
			}

			return $pages;
		}


		public function admin_footer_text( $footer_text ) {
			if ( false === WFFN_Core()->role->user_access( 'funnel', 'read' ) ) {
				return $footer_text;
			}

			$current_screen = get_current_screen();
			$wffn_pages     = array( 'woofunnels_page_bwf', 'woofunnels_page_wffn-settings' );

			// Check to make sure we're on a WooFunnels admin page.
			if ( isset( $current_screen->id ) && apply_filters( 'bwf_funnels_funnels_display_admin_footer_text', in_array( $current_screen->id, $wffn_pages, true ), $current_screen->id ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				// Change the footer text.
				$footer_text = __( 'Thanks for creating with FunnelKit. Need help? <a href="https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Support+Footer&utm_campaign=Lite+Plugin" target="_blank">Contact Support</a>', 'funnel-builder' );

			}

			return $footer_text;
		}

		public function maybe_show_notices() {


			global $wffn_notices;
			if ( ! is_array( $wffn_notices ) || empty( $wffn_notices ) ) {
				return;
			}

			foreach ( $wffn_notices as $notice ) {
				echo wp_kses_post( $notice );
			}
		}

		public function remove_all() {
			if ( $this->is_wffn_flex_page( 'all' ) ) {

				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' );
			}
		}

		/**
		 * Hooked over 'plugin_action_links_{PLUGIN_BASENAME}' WordPress hook to add deactivate popup support & add PRO link
		 *
		 * @param array $links array of existing links
		 *
		 * @return array modified array
		 */
		public function plugin_actions( $links ) {
			if ( isset( $links['deactivate'] ) ) {
				$links['deactivate'] .= '<i class="woofunnels-slug" data-slug="' . WFFN_PLUGIN_BASENAME . '"></i>';
			}
			if ( false === wfacp_pro_dependency() ) {
				$link  = add_query_arg( [
					'utm_source'   => 'WordPress',
					'utm_medium'   => 'All+Plugins',
					'utm_campaign' => 'Lite+Plugin',
					'utm_content'  => WFFN_VERSION
				], $this->get_pro_link() );
				$links = array_merge( [
					'pro_upgrade' => '<a href="' . $link . '" target="_blank" style="color: #1da867 !important;font-weight:600">' . __( 'Upgrade to Pro', 'funnel-builder' ) . '</a>'
				], $links );
			}


			return $links;
		}

		/**
		 * Initiate WFFN_Background_Importer class if ActionScheduler class doesn't exist
		 * @see woofunnels_maybe_update_customer_database()
		 */
		public function wffn_maybe_init_background_updater() {
			if ( class_exists( 'WFFN_Background_Importer' ) ) {
				$this->wffn_updater = new WFFN_Background_Importer();
			}


		}

		/**
		 * @hooked over `admin_init`
		 * This method takes care of template importing
		 * Checks whether there is a need to import
		 * Iterates over define callbacks and passes it to background updater class
		 * Updates templates for all steps of the funnels
		 */
		public function wffn_maybe_run_templates_importer() {
			if ( is_null( $this->wffn_updater ) ) {
				return;
			}
			$funnel_id = get_option( '_wffn_scheduled_funnel_id', 0 );

			if ( $funnel_id > 0 ) { // WPCS: input var ok, CSRF ok.

				$task = 'wffn_maybe_import_funnel_in_background';  //Scanning order table and updating customer tables
				$this->wffn_updater->push_to_queue( $task );
				BWF_Logger::get_instance()->log( '**************START Importing************', 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				$this->wffn_updater->save()->dispatch();
				BWF_Logger::get_instance()->log( 'First Dispatch completed', 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
		}

		/**
		 * Delete wffn-wizard and redirect install
		 */
		public function reset_wizard() {
			if ( current_user_can( 'manage_options' ) && isset( $_GET['wffn_show_wizard_force'] ) && 'yes' === $_GET['wffn_show_wizard_force'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended

				delete_option( '_wffn_onboarding_completed' );
				wp_redirect( $this->wizard_url() );
				exit;

			}
		}

		/**
		 * @return array
		 */
		public function get_all_active_page_builders() {
			$page_builders = [ 'gutenberg', 'elementor', 'divi', 'oxy' ];

			return $page_builders;
		}


		public function include_template_preview_helpers( $admin_instance, $identifier_variable ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,WordPressVIPMinimum.Variables.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

			include_once WFFN_Core()->admin->get_admin_path() . '/views/commons/template-new.php';  //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomFunction
			include_once WFFN_Core()->admin->get_admin_path() . '/views/commons/template-preview.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomFunction
		}

		public function get_template_helper_settings_html( $admin_instance ) {

			if ( is_callable( [ $admin_instance, 'get_template_settings' ] ) ) {
				$admin_instance->get_template_settings();
			}
		}

		/**
		 * Keep the menu open when editing the flows.
		 * Highlights the wanted admin (sub-) menu items for the CPT.
		 *
		 * @since 1.0.0
		 */
		public function menu_highlight() {
			global $submenu_file;

			if ( filter_input( INPUT_GET, 'wffn_funnel_ref' ) ) {
				$submenu_file = 'admin.php?page=bwf&path=/funnels'; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		/**
		 * @param $query WP_Query
		 */
		public function load_page_to_home_page( $query ) {
			if ( $query->is_main_query() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

				$post_type = $query->get( 'post_type' );

				$page_id = $query->get( 'page_id' );

				if ( empty( $post_type ) && ! empty( $page_id ) ) {
					$t_post = get_post( $page_id );
					if ( in_array( $t_post->post_type, [ WFFN_Core()->landing_pages->get_post_type_slug(), WFOPP_Core()->optin_pages->get_post_type_slug() ], true ) ) {
						$query->set( 'post_type', get_post_type( $page_id ) );
					}
				}
			}
		}

		public function check_db_version() {

			$get_db_version = get_option( '_wffn_db_version', '0.0.0' );

			if ( version_compare( WFFN_DB_VERSION, $get_db_version, '>' ) ) {


				include_once plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/db/class-wffn-db-tables.php';
				$tables = WFFN_DB_Tables::get_instance();
				$tables->define_tables();
				$tables->add_if_needed();

			}

		}

		/**
		 * @hooked over `admin_init`
		 * This method takes care of database updating process.
		 * Checks whether there is a need to update the database
		 * Iterates over define callbacks and passes it to background updater class
		 */
		public function maybe_update_database_update() {


			$task_list          = array( '3.3.1' => array( 'wffn_handle_store_checkout_config' ) );
			$current_db_version = get_option( '_wffn_db_version', '0.0.0' );
			$most_recent_vrsion = '0.0.0';
			if ( ! empty( $task_list ) ) {
				foreach ( $task_list as $version => $tasks ) {
					if ( version_compare( $current_db_version, $version, '<' ) ) {
						foreach ( $tasks as $update_callback ) {

							call_user_func( $update_callback );
							update_option( '_wffn_db_version', $version, true );
							$most_recent_vrsion = $version;
						}
					}
				}

				/**
				 * If we do not have any task for the specific DB version then directly update option
				 */
				if ( version_compare( $most_recent_vrsion, WFFN_DB_VERSION, '<' ) ) {
					update_option( '_wffn_db_version', WFFN_DB_VERSION, true );
				}

			}

		}

		public function settings_config( $config ) {
			$License = WooFunnels_licenses::get_instance();
			$fields  = [];
			if ( is_object( $License ) && is_array( $License->plugins_list ) && count( $License->plugins_list ) ) {
				foreach ( $License->plugins_list as $license ) {
					if ( in_array( $license['product_file_path'], array( '7b31c172ac2ca8d6f19d16c4bcd56d31026b1bd8', '913d39864d876b7c6a17126d895d15322e4fd2e8' ), true ) ) {
						continue;
					}

					$license_data = [];
					if ( isset( $license['_data'] ) && isset( $license['_data']['data_extra'] ) ) {
						$license_data = $license['_data']['data_extra'];
						if ( isset( $license_data['api_key'] ) ) {
							$license_data['api_key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $license_data['api_key'], - 6 );
							$license_data['licence'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $license_data['api_key'], - 6 );
						}
					}

					$data = array(
						'id'                      => $license['product_file_path'],
						'label'                   => $license['plugin'],
						'type'                    => 'license',
						'key'                     => $license['product_file_path'],
						'license'                 => ! empty( $license_data ) ? $license_data : false,
						'is_manually_deactivated' => ( isset( $license['_data']['manually_deactivated'] ) && true === wffn_string_to_bool( $license['_data']['manually_deactivated'] ) ) ? 1 : 0,
						'activated'               => ( isset( $license['_data']['activated'] ) && true === wffn_string_to_bool( $license['_data']['activated'] ) ) ? 1 : 0,
						'expired'                 => ( isset( $license['_data']['expired'] ) && true === wffn_string_to_bool( $license['_data']['expired'] ) ) ? 1 : 0
					);
					if ( $license['plugin'] === 'FunnelKit Funnel Builder Pro' || $license['plugin'] === 'FunnelKit Funnel Builder Basic' ) {
						array_unshift( $fields, $data );
					} else {
						$fields[] = $data;
					}
				}
			}

			if ( empty( $fields ) ) {
				$pro_link = add_query_arg( [
					'utm_source'   => 'WordPress',
					'utm_medium'   => 'Settings+License',
					'utm_campaign' => 'Lite+Plugin'
				], $this->get_pro_link() );
				$fields[] = array(
					'key'  => 'no_license',
					'hint' => sprintf( __( '<h3 style="margin: 25px 0;">License</h3><p>You are using Funnel Builder plugin, no license needed ! 🙂</p><p>To unlock more features consider <a target="_blank" class="bwf-a-no-underline" href="%s">Upgrading to Funnel Builder PRO Now</a></p><br/>', 'funnel-builder' ), $pro_link ),
				);
			}

			return array_merge( $fields, $config );
		}

		public function maybe_add_oxygen_in_global_settings( $config ) {
			$get_index = false;
			foreach ( $config as &$v ) {
				if ( $v['key'] === 'default_selected_builder' ) {
					$get_all_builders = wp_list_pluck( $v['values'], 'id' );
					if ( in_array( 'oxy', $get_all_builders, true ) ) {
						break;
					}
					foreach ( $v['values'] as $index => $vv ) {
						if ( $vv['id'] === 'divi' ) {
							$get_index = $index;
							break;
						}
					}
					if ( false !== $get_index ) {

						array_splice( $v['values'], $get_index + 1, 0, [ [ 'id' => 'gutenberg', 'name' => __( 'Gutenberg', 'woofunnels' ) ] ] );

						array_splice( $v['values'], $get_index + 2, 0, [ [ 'id' => 'oxy', 'name' => __( 'Oxygen', 'woofunnels' ) ] ] );
					}


				}
			}


			return $config;
		}

		/**
		 * @param $link
		 * @param BWFABT_Experiment $experiment
		 *
		 * @return string
		 */
		function maybe_modify_link( $link, $experiment ) {


			$get_control_id = $experiment->get_control();

			$get_funnel_id = get_post_meta( $get_control_id, '_bwf_in_funnel', true );

			if ( ! empty( $get_funnel_id ) ) {

				return WFFN_Common::get_experiment_edit_link( $get_funnel_id, $get_control_id );
			}

			return $link;
		}

		/*
		 * @param $post_id
		 * @param $all_meta
		 *
		 * Return selected builder based on post meta when import page
		 * @return string[]
		 */
		public function get_selected_template( $post_id, $all_meta ) {
			$meta = '';
			if ( ! empty( $all_meta ) ) {
				$meta = wp_list_pluck( $all_meta, 'meta_key' );
			}

			$template = [
				'selected'        => 'wp_editor_1',
				'selected_type'   => 'wp_editor',
				'template_active' => 'yes'
			];


			$selected_template = apply_filters( 'wffn_set_selected_template_on_duplicate', array(), $post_id, $meta );

			if ( is_array( $selected_template ) && count( $selected_template ) > 0 ) {
				return $selected_template;
			}

			if ( is_array( $meta ) ) {
				if ( in_array( '_elementor_data', $meta, true ) ) {
					$template['selected']      = 'elementor_1';
					$template['selected_type'] = 'elementor';

					return $template;
				}
				if ( in_array( '_et_builder_version', $meta, true ) ) {
					$template['selected']      = 'divi_1';
					$template['selected_type'] = 'divi';

					return $template;
				}
				if ( in_array( 'ct_builder_shortcodes', $meta, true ) ) {
					$template['selected']      = 'oxy_1';
					$template['selected_type'] = 'oxy';

					return $template;
				}
			}

			if ( false !== strpos( get_post_field( 'post_content', $post_id ), '<!-- wp:' ) ) {
				$template['selected']      = 'gutenberg_1';
				$template['selected_type'] = 'gutenberg';

				return $template;
			}

			return $template;
		}

		public function get_pro_link() {
			return esc_url( 'https://funnelkit.com/funnel-builder-lite-upgrade/' );
		}

		public function setup_js_for_localization( $app_name, $frontend_dir, $script_deps, $version ) {
			/** enqueue other js file from the dist folder */
			$path = WFFN_PLUGIN_DIR . $this->get_local_app_path();
			foreach ( glob( $path . "*.js" ) as $dist_file ) {
				$file_info = pathinfo( $dist_file );

				if ( $app_name === $file_info['filename'] ) {
					continue;
				}
				wp_register_script( "wffn_admin_" . $file_info['filename'], $frontend_dir . "" . $file_info['basename'], $script_deps, $version, true );
				wp_set_script_translations( "wffn_admin_" . $file_info['filename'], 'funnel-builder' );
			}
			add_action( 'admin_print_footer_scripts', function () {

				if ( 0 === WFFN_REACT_ENVIRONMENT ) {
					return;
				}
				$path = WFFN_PLUGIN_DIR . $this->get_local_app_path();
				global $wp_scripts;
				foreach ( glob( $path . "*.js" ) as $dist_file ) {

					$file_info = pathinfo( $dist_file );

					$translations = $wp_scripts->print_translations( "wffn_admin_" . $file_info['filename'], false );
					if ( $translations ) {
						$translations = sprintf( "<script%s id='%s-js-translations'>\n%s\n</script>\n", '', esc_attr( "wffn_admin_" . $file_info['filename'] ), $translations );
					}
					echo $translations; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

			}, 99999 );
		}

		/**
		 * @param $post_id
		 * @param $post
		 *
		 * hooked over `before_delete_post`
		 * Checks if funnel step delete, then update associated funnel step meta
		 *
		 * @return void
		 */
		public function delete_funnel_step_permanently( $post_id, $post ) {

			if ( is_null( $post ) ) {
				return;
			}

			if ( ! in_array( $post->post_type, array(
				'wfacp_checkout',
				'wffn_landing',
				'wffn_ty',
				'wffn_optin',
				'wffn_oty',
			), true ) ) {
				return;
			}

			$get_funnel_id = get_post_meta( $post_id, '_bwf_in_funnel', true );

			if ( empty( $get_funnel_id ) ) {
				return;
			}

			$funnel = new WFFN_Funnel( $get_funnel_id );

			if ( $funnel instanceof WFFN_Funnel ) {
				$funnel->delete_step( $get_funnel_id, $post_id );
			}

		}

		/**
		 * @param $steps
		 * @param $funnel
		 *
		 * Removed step if not exists on funnel steps listing
		 *
		 * @return mixed
		 */
		public function maybe_delete_funnel_step( $steps, $funnel ) {

			if ( ! $funnel instanceof WFFN_Funnel ) {
				return $steps;
			}
			if ( is_array( $steps ) && count( $steps ) > 0 ) {
				foreach ( $steps as $key => &$step ) {

					/**
					 * Skip if store funnel have native checkout
					 */
					if ( absint( $funnel->get_id() ) === WFFN_Common::get_store_checkout_id() && WFFN_Common::store_native_checkout_slug() === $step['type'] ) {
						continue;
					}

					/**
					 * IF current step post not exist, then remove this step from funnel meta
					 */
					if ( 0 <= $step['id'] && ! get_post( $step['id'] ) instanceof WP_Post ) {
						unset( $steps[ $key ] );
						$funnel->delete_step( $funnel->get_id(), $step['id'] );
					}
				}

			}

			return $steps;

		}

		public function maybe_show_wizard() {

			if ( ! WFFN_Core()->admin->is_wffn_flex_page( 'all' ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			if ( ! class_exists( 'WFFN_Pro_Admin' ) ) {
				return;
			}

			WFFN_Common::remove_actions( 'admin_init', 'WFFN_Pro_Admin', 'maybe_show_wizard' );

			if ( isset( $_GET['path'] ) && strpos( wffn_clean( $_GET['path'] ), 'user-setup' ) !== false ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			if ( WFFN_PRO_Core()->support->is_license_present() === false ) {
				wp_redirect( $this->wizard_url() );
				exit;
			}

		}

		public function wizard_url() {
			return admin_url( 'admin.php?page=bwf&path=/user-setup' );
		}

	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'admin', 'WFFN_Admin' );
	}
}
