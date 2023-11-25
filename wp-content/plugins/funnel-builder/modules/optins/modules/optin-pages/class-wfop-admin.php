<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOP_Admin
 */
if ( ! class_exists( 'WFOP_Admin' ) ) {
	class WFOP_Admin {

		private static $ins = null;
		public $wfop_id = 0;

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 92 );
			add_action( 'admin_head', array( $this, 'hide_from_menu' ) );
			add_filter( 'woofunnels_global_settings', function ( $menu ) {
				$menu[] = array(
					'title'    => __( 'Optin Pages', 'funnel-builder' ),
					'slug'     => 'op-settings',
					'priority' => 20,
				);

				return $menu;
			} );
			add_action( 'edit_form_after_title', [ $this, 'add_back_button' ] );
			add_filter( 'et_builder_enabled_builder_post_type_options', [ $this, 'wffn_add_op_type_to_divi' ], 999 );

			/**general settings**/
			add_action( 'admin_footer', array( $this, 'maybe_add_js_for_permalink_settings' ), 11 );
			add_filter( 'bwf_general_settings_fields', array( $this, 'add_permalink_settings' ) );
			add_filter( 'bwf_general_settings_default_config', function ( $fields ) {
				$fields['optin_page_base'] = 'op';

				return $fields;
			} );

			add_filter( 'bwf_enable_ecommerce_integration_optin', '__return_true' );
		}

		/**
		 * @return WFOP_Admin|null
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
				add_submenu_page( 'woofunnels', __( 'Optin Page', 'funnel-builder' ), __( 'Optin Page', 'funnel-builder' ), $user, 'wf-op', array(
					$this,
					'builder_view',
				) );
			}
		}

		public function builder_view() {

			$section = filter_input( INPUT_GET, 'section' );

			if ( 'action' === $section ) {
				include_once WFOPP_PLUGIN_DIR . '/admin/views/optin-pages/action.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

				return;
			}

			if ( 'form' === $section ) {
				include_once WFOPP_PLUGIN_DIR . '/admin/views/optin-pages/form.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

				return;
			}

			if ( 'customize' === $section ) {
				include_once WFOPP_PLUGIN_DIR . '/admin/views/optin-pages/customize.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

				return;
			}
			if ( 'settings' === $section ) {
				include_once WFOPP_PLUGIN_DIR . '/admin/views/optin-pages/settings.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

				return;
			}
			include_once WFOPP_PLUGIN_DIR . '/admin/views/optin-pages/without-template.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
		}

		/**
		 * @param $op_id
		 */
		public function get_tabs_html( $op_id ) {
			$tabs = $this->get_tabs_links( $op_id );
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

		public function get_tabs_links( $op_id ) {
			$funnel_id = get_post_meta( $op_id, '_bwf_in_funnel', true );
			$tabs      = array(
				array(
					'section' => 'design',
					'title'   => __( 'Design', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-op',
						'section'         => 'design',
						'edit'            => $op_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
				array(
					'section' => 'form',
					'title'   => __( 'Form', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-op',
						'section'         => 'form',
						'edit'            => $op_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
				array(
					'section' => 'customize',
					'title'   => __( 'Customize', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-op',
						'section'         => 'customize',
						'edit'            => $op_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
				array(
					'section' => 'action',
					'title'   => __( 'Actions', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-op',
						'section'         => 'action',
						'edit'            => $op_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
				array(
					'section' => 'settings',
					'title'   => __( 'Settings', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-op',
						'section'         => 'settings',
						'edit'            => $op_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
			);

			return apply_filters( 'wffn_op_tabs', $tabs );
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
			if ( 'action' === $section ) {
				$icon = '<span class="dashicons dashicons-admin-tools"></span>';
			}
			if ( 'form' === $section ) {
				$icon = '<span class="dashicons dashicons-menu-alt"></span>';
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
					if ( 'admin.php?page=wf-op' === $d[2] ) {

						unset( $submenu[ $key ][ $k ] );
					}
				}
			}
			global $parent_file, $plugin_page, $submenu_file;//phpcs:ignore
			if ( 'wf-op' === $plugin_page ) :
				$parent_file  = $woofunnels_menu_slug;//phpcs:ignore
				$submenu_file = 'admin.php?page=bwf&path=/funnels';//phpcs:ignore
			endif;
		}


		/**
		 * Adding back to optin page editor
		 */
		public function add_back_button() {
			global $post;
			$op_type = WFOPP_Core()->optin_pages->get_post_type_slug();
			$op_id   = ( $op_type === $post->post_type ) ? $post->ID : 0;
			if ( $op_id > 0 ) {
				$funnel_id = get_post_meta( $op_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'    => 'wf-op',
					'edit'    => $op_id,
					'section' => 'design',
				], admin_url( 'admin.php' ) ) );

				if ( use_block_editor_for_post_type( $op_type ) ) {
					add_action( 'admin_footer', array( $this, 'render_back_to_funnel_script_for_block_editor' ) );
				} else { ?>
					<div id="wf_funnel-switch-mode">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Optin Page', 'funnel-builder' ); ?>
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
			$op_type = WFOPP_Core()->optin_pages->get_post_type_slug();
			$op_id   = ( $op_type === $post->post_type ) ? $post->ID : 0;
			if ( $op_id > 0 ) {
				$funnel_id = get_post_meta( $op_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'    => 'wf-op',
					'edit'    => $op_id,
					'section' => 'design',
				], admin_url( 'admin.php' ) ) ) ?>
				<script id="wf_funnel-back-button-template" type="text/html">
					<div id="wf_funnel-switch-mode">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Optin Page', 'funnel-builder' ); ?>
						</a>
					</div>
				</script>
				<script>
                    window.addEventListener('load', function () {
                        (function (window, wp) {

                            const {Toolbar, ToolbarButton} = wp.components;

                            var link_button = wp.element.createElement(
                                ToolbarButton,
                                {
                                    variant: 'secondary',
                                    href: "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>",
                                    id: 'wf_funnel-back-button',
                                    className: 'button is-secondary',
                                    style: {
                                        display: 'flex',
                                        height: '33px'
                                    },
                                    text: "<?php esc_html_e( '← Back to Optin Page', 'funnel-builder' ); ?>",
                                    label: "<?php esc_html_e( 'Back to Optin Page', 'funnel-builder' ); ?>"
                                }
                            );
                            var linkWrapper = '<div id="wf_funnel-switch-mode"></div>';

                            // check if gutenberg's editor root element is present.
                            var editorEl = document.getElementById('editor');
                            if (!editorEl) { // do nothing if there's no gutenberg root element on page.
                                return;
                            }

                            var unsubscribe = wp.data.subscribe(function () {
                                setTimeout(function () {
                                    if (!document.getElementById('wf_funnel-switch-mode')) {
                                        var toolbalEl = editorEl.querySelector('.edit-post-header__toolbar .edit-post-header-toolbar');
                                        if (toolbalEl instanceof HTMLElement) {
                                            toolbalEl.insertAdjacentHTML('beforeend', linkWrapper);
                                            setTimeout(() => {
                                                wp.element.render(link_button, document.getElementById('wf_funnel-switch-mode'));
                                            }, 1);
                                        }
                                    }
                                }, 1)
                            });

							var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
							if (link) {
								link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
							}

                        })(window, wp)
                    });
				</script>
			<?php }
		}

		/**
		 * @param $options
		 *
		 * @return mixed
		 */
		public function wffn_add_op_type_to_divi( $options ) {
			$op_type             = WFOPP_Core()->optin_pages->get_post_type_slug();
			$options[ $op_type ] = 'on';

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
                                model: "optin_page_base",
                                inputName: 'optin_page_base',
                            });
                        return e;
                    });
                }
			</script>
			<?php
		}

		public function add_permalink_settings( $fields ) {

			$fields['optin_page_base'] = array(
				'label'     => __( 'Optin Page', 'funnel-builder' ),
				'hint'      => __( '', 'funnel-builder' ),
				'type'      => 'input',
				'inputType' => 'text',
			);

			return $fields;

		}

		public function show_optin_shortcodes_in_checkout() { ?>
			<div class="wfacp-short-code-wrapper">
				<div class="wfacp_fsetting_table_head wfacp-scodes-head wfacp_shotcode_tab_wrap">
					<div class="wfacp_clear_20"></div>
					<div class="wfacp-fsetting-header"><?php esc_html_e( 'Optin Shortcodes', 'funnel-builder' ); ?></div>
					<div class="wfacp_clear_20"></div>
				</div>

				<!-----  NEW ADDED ACC TO DESIGN  ------->
				<div class=" wfacp_global_settings_wrap wfacp_page_col2_wrap wfacp_shortcodes_designs">
					<div class="wfacp_page_left_wrap" id="wfacp_global_setting_vue">
						<div class="wfacp_loader" style="display: none;"><span class="spinner"></span></div>
						<div class="wfacp-product-tabs-view-vertical wfacp-product-widget-tabs">
							<div class="wfacp-product-tabs-wrapper wfacp-tab-center">
								<div class="wfacp_embed_form_tab wfacp-tab-title wfacp-tab-desktop-title wfacp-active" data-tab="1" role="tab" aria-controls="wfacp-shortcode-fieldset">
									<?php esc_html_e( 'Shortcode', 'funnel-builder' ); ?>
								</div>

							</div>
							<div class="wfacp-product-widget-container">
								<div class="wfacp-product-tabs wfacp-tabs-style-line" role="tablist">
									<div class="wfacp-product-tabs-content-wrapper">
										<div class="wfacp_global_setting_inner">
											<div class="wfacp_vue_forms">
												<div class="vue-form-generator">
													<fieldset class="wfacp_embed_fieldset wfacp-activeTab wfacp-shortcode-fieldset" style="display: block;">
														<?php
														if ( class_exists( 'WFOPP_Core' ) ) { ?>
															<legend><?php esc_html_e( 'Optin Shortcodes', 'funnel-builder' ); ?></legend>


															<div class="wfacp-scodes-row">
																<h4 class="wfacp-scodes-label"><?php esc_html_e( 'Optin First Name', 'funnel-builder' ); ?></h4>
																<div class="wfacp-scodes-value">
																	<div class="wfacp-scodes-value-in">

																		<div class="wfacp_description">
																			<input type="text" value="[wfop_first_name]" style="width:100%;" readonly>
																		</div>
																		<a href="javascript:void(0)" class="wfacp_copy_text">
																			<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
																				<path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
																			</svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a>

																	</div>
																</div>
															</div>

															<div class="wfacp-scodes-row">
																<h4 class="wfacp-scodes-label"><?php esc_html_e( 'Optin Last Name', 'funnel-builder' ); ?></h4>
																<div class="wfacp-scodes-value">
																	<div class="wfacp-scodes-value-in">

																		<div class="wfacp_description">
																			<input type="text" value="[wfop_last_name]" style="width:100%;" readonly>
																		</div>
																		<a href="javascript:void(0)" class="wfacp_copy_text">
																			<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
																				<path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
																			</svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a>

																	</div>
																</div>
															</div>

															<div class="wfacp-scodes-row">
																<h4 class="wfacp-scodes-label"><?php esc_html_e( 'Optin Email', 'funnel-builder' ); ?></h4>
																<div class="wfacp-scodes-value">
																	<div class="wfacp-scodes-value-in">

																		<div class="wfacp_description">
																			<input type="text" value="[wfop_email]" style="width:100%;" readonly>
																		</div>
																		<a href="javascript:void(0)" class="wfacp_copy_text">
																			<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
																				<path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
																			</svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a>

																	</div>
																</div>
															</div>

															<div class="wfacp-scodes-row">
																<h4 class="wfacp-scodes-label"><?php esc_html_e( 'Optin Phone', 'funnel-builder' ); ?></h4>
																<div class="wfacp-scodes-value">
																	<div class="wfacp-scodes-value-in">

																		<div class="wfacp_description">
																			<input type="text" value="[wfop_phone]" style="width:100%;" readonly>
																		</div>
																		<a href="javascript:void(0)" class="wfacp_copy_text">
																			<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
																				<path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
																			</svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a>

																	</div>
																</div>
															</div>

															<div class="wfacp-scodes-row">
																<h4 class="wfacp-scodes-label"><?php esc_html_e( 'Optin Custom Fields', 'funnel-builder' ); ?></h4>
																<div class="wfacp-scodes-value">
																	<div class="wfacp-scodes-value-in">

																		<div class="wfacp_description">
																			<input type="text" value="[wfop_custom key='Label']" style="width:100%;" readonly>
																		</div>
																		<a href="javascript:void(0)" class="wfacp_copy_text">
																			<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
																				<path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
																			</svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a>

																	</div>
																</div>
															</div>


															<div style="display:none" id="modal-global-settings_success" data-iziModal-icon="icon-home"></div>


														<?php }

														?>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


			</div>

			<?php
		}


	}
}
