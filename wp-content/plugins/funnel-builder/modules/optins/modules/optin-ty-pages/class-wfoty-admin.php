<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOTY_Admin
 */
if ( ! class_exists( 'WFOTY_Admin' ) ) {
	class WFOTY_Admin {

		private static $ins = null;

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 92 );
			add_action( 'admin_head', array( $this, 'hide_from_menu' ) );
			add_action( 'edit_form_after_title', [ $this, 'add_back_button' ] );
			add_filter( 'et_builder_enabled_builder_post_type_options', [ $this, 'wffn_add_oty_type_to_divi' ], 999 );

			/**general settings**/
			add_action( 'admin_footer', array( $this, 'maybe_add_js_for_permalink_settings' ), 10 );
			add_filter( 'bwf_general_settings_fields', array( $this, 'add_permalink_settings' ) );
			add_filter( 'bwf_general_settings_default_config', function ( $fields ) {
				$fields['optin_ty_page_base'] = 'op-confirmed';

				return $fields;
			} );

			add_filter( 'bwf_enable_ecommerce_integration_optin', '__return_true' );

		}

		/**
		 * @return WFOTY_Admin|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_admin_menu() {
			$user = WFFN_Core()->role->user_access( 'menu', 'read' );
			if ( $user ) {
				add_submenu_page( 'woofunnels', __( 'Optin Confirmation Pages', 'funnel-builder' ), __( 'Optin Confirmation Pages', 'funnel-builder' ), $user, 'wf-oty', array(
					$this,
					'builder_view',
				) );
			}
		}

		public function builder_view() {

			$section = filter_input( INPUT_GET, 'section' );

			if ( 'settings' === $section ) {
				include_once WFOPP_PLUGIN_DIR . '/admin/views/optin-ty-pages/settings.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

				return;
			}

			include_once WFOPP_PLUGIN_DIR . '/admin/views/optin-ty-pages/without-template.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
		}

		/**
		 * @param $funnel
		 */
		public function get_tabs_html( $oty_id ) {
			$tabs = $this->get_tabs_links( $oty_id );
			?>
			<div class="bwf_menu_list_primary">
				<ul>
					<?php
					foreach ( $tabs as $tab ) {
						$is_active = $this->is_tab_active_class( $tab['section'] );
						$tab_link  = $this->get_tab_link( $tab );
						?>
						<li class="<?php echo esc_attr( $is_active ); ?>">
							<a href="<?php echo empty( $tab_link ) ? 'javascript:void(0);' : esc_url( $tab_link ); ?>">
								<?php
								echo esc_html( $tab['title'] );
								?>
							</a>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			<?php
		}

		public function get_tabs_links( $oty_id ) {
			$funnel_id = get_post_meta( $oty_id, '_bwf_in_funnel', true );
			$tabs      = array(
				array(
					'section' => 'design',
					'title'   => __( 'Design', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-oty',
						'section'         => 'design',
						'edit'            => $oty_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
				array(
					'section' => 'settings',
					'title'   => __( 'Settings', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-oty',
						'section'         => 'settings',
						'edit'            => $oty_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
			);

			return apply_filters( 'wffn_oty_tabs', $tabs );
		}

		public function is_tab_active_class( $section ) {

			if ( isset( $_GET['section'] ) && $section === $_GET['section'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return 'active';
			}
			if ( empty( $section ) && ! isset( $_GET['section'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return 'active';
			}

			return '';
		}

		public function get_tab_link( $tab ) {
			return BWF_Admin_Breadcrumbs::maybe_add_refs( $tab['link'] );
		}

		public function get_tabs_icons( $section ) {
			//Funnels
			$icon = '<span class="dashicons dashicons-art"></span>';
			if ( 'analytics' === $section ) {
				$icon = '<span class="dashicons dashicons-chart-bar"></span>';
			}
			if ( 'settings' === $section ) {
				$icon = '<span class="dashicons dashicons-admin-generic"></span>';
			}

			return $icon;
		}

		public function hide_from_menu() {

			global $submenu, $woofunnels_menu_slug;
			foreach ( $submenu as $key => $men ) {
				if ( $woofunnels_menu_slug !== $key ) {
					continue;
				}
				foreach ( $men as $k => $d ) {
					if ( 'admin.php?page=wf-oty' === $d[2] ) {

						unset( $submenu[ $key ][ $k ] );
					}
				}
			}
			global $parent_file, $plugin_page, $submenu_file; //phpcs:ignore
			if ( 'wf-oty' === $plugin_page ) :
				$parent_file  = $woofunnels_menu_slug;//phpcs:ignore
				$submenu_file = 'admin.php?page=bwf&path=/funnels'; //phpcs:ignore
			endif;

		}


		/**
		 * Adding back to thank you optin page editor
		 */
		public function add_back_button() {
			global $post;
			$oty_type = WFOPP_Core()->optin_ty_pages->get_post_type_slug();
			$oty_id   = ( $oty_type === $post->post_type ) ? $post->ID : 0;
			if ( $oty_id > 0 ) {
				$funnel_id = get_post_meta( $oty_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'    => 'wf-oty',
					'edit'    => $oty_id,
					'section' => 'design',
				], admin_url( 'admin.php' ) ) );

				if ( use_block_editor_for_post_type( $oty_type ) ) {
					add_action( 'admin_footer', array( $this, 'render_back_to_funnel_script_for_block_editor' ) );
				} else { ?>
					<div id="wf_funnel-switch-mode">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Optin Confirmation Page', 'funnel-builder' ); ?>
						</a>
					</div>
					<script>
						window.addEventListener('load', function () {
							( function( window, wp ){
								var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
								if (link) {
									link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
								}

							} )( window, wp )
						});
					</script>
					<?php
				}
			} ?>
			<?php
		}

		public function render_back_to_funnel_script_for_block_editor() {
			global $post;
			$oty_type = WFOPP_Core()->optin_ty_pages->get_post_type_slug();
			$oty_id   = ( $oty_type === $post->post_type ) ? $post->ID : 0;
			if ( $oty_id > 0 ) {
				$funnel_id = get_post_meta( $oty_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'    => 'wf-oty',
					'edit'    => $oty_id,
					'section' => 'design',
				], admin_url( 'admin.php' ) ) ) ?>
				<script id="wf_funnel-back-button-template" type="text/html">
					<div id="wf_funnel-switch-mode">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Optin Confirmation Page', 'funnel-builder' ); ?>
						</a>
					</div>
				</script>

				<script>
					window.addEventListener('load', function () {
						( function( window, wp ){

							const { Toolbar, ToolbarButton } = wp.components;

							var link_button = wp.element.createElement(
								ToolbarButton,
								{
									variant :'secondary',
									href:"<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>",
									id:'wf_funnel-back-button',
									className:'button is-secondary',
									style:{
										display:'flex',
										height:'33px'
									},
									text :"<?php esc_html_e( '← Back to Optin Confirmation Page', 'funnel-builder' ); ?>",
									label :"<?php esc_html_e( 'Back to Optin Confirmation Page', 'funnel-builder' ); ?>"
								}
							);
							var linkWrapper = '<div id="wf_funnel-switch-mode"></div>';

							// check if gutenberg's editor root element is present.
							var editorEl = document.getElementById( 'editor' );
							if( !editorEl ){ // do nothing if there's no gutenberg root element on page.
								return;
							}

							var unsubscribe = wp.data.subscribe( function () {
								setTimeout( function () {
									if ( ! document.getElementById( 'wf_funnel-switch-mode' ) ) {
										var toolbalEl = editorEl.querySelector( '.edit-post-header__toolbar .edit-post-header-toolbar' );
										if( toolbalEl instanceof HTMLElement ){
											toolbalEl.insertAdjacentHTML( 'beforeend', linkWrapper );
											setTimeout(() => {
												wp.element.render( link_button, document.getElementById('wf_funnel-switch-mode') );
											}, 1 );
										}
									}
								}, 1 )
							} );

							var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
							if (link) {
								link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
							}

						} )( window, wp )
					});
				</script>
			<?php }
		}

		/**
		 * @param $options
		 *
		 * @return mixed
		 */
		public function wffn_add_oty_type_to_divi( $options ) {
			$oty_type             = WFOPP_Core()->optin_ty_pages->get_post_type_slug();
			$options[ $oty_type ] = 'on';

			return $options;
		}

		public function maybe_add_js_for_permalink_settings() {
			?>
			<script>
				if (typeof window.bwfBuilderCommons !== "undefined") {
					window.bwfBuilderCommons.addFilter('bwf_common_permalinks_fields', function (e) {
						e.unshift(
							{
								type: "input",
								inputType: "text",
								label: "",
								model: "optin_ty_page_base",
								inputName: 'optin_ty_page_base',
							});
						return e;
					});
				}
			</script>
			<?php
		}

		public function add_permalink_settings( $fields ) {

			$fields['optin_ty_page_base'] = array(
				'label'     => __( 'Optin Confirmation Page', 'funnel-builder' ),
				'hint'      => __( '', 'funnel-builder' ),
				'type'      => 'input',
				'inputType' => 'text',
			);

			return $fields;

		}


	}
}
