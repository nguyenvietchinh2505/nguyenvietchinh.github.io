<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFLP_Admin
 */
if ( ! class_exists( 'WFLP_Admin' ) ) {
	class WFLP_Admin {

		private static $ins = null;
		public $edit_id = 0;

		public function __construct() {
			$this->process_url();
			add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 92 );
			add_action( 'admin_head', array( $this, 'hide_from_menu' ) );

			add_action( 'edit_form_after_title', [ $this, 'add_back_button' ] );
			add_action( 'admin_footer', array( $this, 'maybe_add_js_for_permalink_settings' ) );

			add_filter( 'bwf_enable_ecommerce_integration_landing', '__return_true' );
			add_action( 'load-woofunnels_page_wf-lp', array( $this, 'maybe_add_templates' ) );
			add_action( 'wffn_admin_assets', [ $this, 'load_assets' ] );
			add_action( 'admin_enqueue_scripts', array( $this, 'maybe_register_breadcrumb_nodes' ), 88 );



		}

		/**
		 * @return WFLP_Admin|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function get_edit_id() {
			return $this->edit_id;
		}

		private function process_url() {
			if ( isset( $_REQUEST['page'] ) && 'wf-lp' === $_REQUEST['page'] && isset( $_REQUEST['edit'] ) && $_REQUEST['edit'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['edit'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['post'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['action'] ) && 'elementor_ajax' === $_REQUEST['action'] && isset( $_REQUEST['editor_post_id'] ) && $_REQUEST['editor_post_id'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['editor_post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}

		public function register_template_type( $data ) {

			if ( isset( $data['slug'] ) && ! empty( $data['slug'] ) && isset( $data['title'] ) && ! empty( $data['title'] ) ) {
				$slug  = sanitize_title( $data['slug'] );
				$title = esc_html( trim( $data['title'] ) );
				if ( ! isset( $this->template_type[ $slug ] ) ) {
					$this->template_type[ $slug ]        = trim( $title );
					$this->design_template_data[ $slug ] = [
						'edit_url'    => $data['edit_url'],
						'button_text' => $data['button_text'],
						'title'       => $data['title'],
						'description' => isset( $data['description'] ) ? $data['description'] : '',
					];
				}
			}
		}

		public function register_template( $slug, $data, $type = 'pre_built' ) {
			if ( '' !== $slug && ! empty( $data ) ) {
				$this->templates[ $type ][ $slug ] = $data;
			}
		}

		public function maybe_add_templates() {
			do_action( 'wffn_wflp_before_register_templates' );
			$template = [
				'slug'        => 'wp_editor',
				'title'       => __( 'Other', 'funnel-builder' ),
				'button_text' => __( 'Edit', 'funnel-builder' ),
				'edit_url'    => add_query_arg( [
					'post'   => $this->get_edit_id(),
					'action' => 'edit',
				], admin_url( 'post.php' ) ),
			];
			$this->register_template_type( $template );
			$designs = [
				'wp_editor' => [
					'wp_editor_1' => [
						'type'               => 'view',
						'show_import_popup'  => 'no',
						'slug'               => 'wp_editor_1',
						'build_from_scratch' => true
					],
				],
			];
			foreach ( $designs as $d_key => $templates ) {

				if ( is_array( $templates ) ) {
					foreach ( $templates as $temp_key => $temp_val ) {
						$this->register_template( $temp_key, $temp_val, $d_key );
					}
				}
			}
			do_action( 'wffn_wflp_register_templates' );
		}

		public function register_admin_menu() {
			$user = WFFN_Core()->role->user_access( 'menu', 'read' );
			if ( $user ) {
				add_submenu_page( 'woofunnels', __( 'Sales Page', 'funnel-builder' ), __( 'Sales Page', 'funnel-builder' ), $user, 'wf-lp', array(
					$this,
					'builder_view',
				) );
			}
		}

		public function builder_view() {

			$section = filter_input( INPUT_GET, 'section' );

			if ( 'settings' === $section ) {
				include_once WFFN_PLUGIN_DIR . '/admin/views/landing-pages/settings.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

				return;
			}

			include_once WFFN_PLUGIN_DIR . '/admin/views/landing-pages/without-template.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
		}

		/**
		 * @param $funnel
		 */
		public function get_tabs_html( $lp_id ) {
			$tabs = $this->get_tabs_links( $lp_id );
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

		public function get_tabs_links( $lp_id ) {
			$funnel_id = get_post_meta( $lp_id, '_bwf_in_funnel', true );
			$tabs      = array(
				array(
					'section' => 'design',
					'title'   => __( 'Design', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-lp',
						'section'         => 'design',
						'edit'            => $lp_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
				array(
					'section' => 'settings',
					'title'   => __( 'Settings', 'funnel-builder' ),
					'link'    => add_query_arg( array(
						'page'            => 'wf-lp',
						'section'         => 'settings',
						'edit'            => $lp_id,
						'wffn_funnel_ref' => $funnel_id
					), admin_url( 'admin.php' ) ),
				),
			);

			return apply_filters( 'wffn_lp_tabs', $tabs );
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
					if ( 'admin.php?page=wf-lp' === $d[2] ) {

						unset( $submenu[ $key ][ $k ] );
					}
				}
			}
			global $parent_file, $plugin_page, $submenu_file; //phpcs:ignore
			if ( 'wf-lp' === $plugin_page ) :
				$parent_file  = $woofunnels_menu_slug;//phpcs:ignore
				$submenu_file = 'admin.php?page=bwf&path=/funnels'; //phpcs:ignore
			endif;
		}


		/**
		 * Adding back to landing page editor
		 */
		public function add_back_button() {
			global $post;
			$lp_type = WFFN_Core()->landing_pages->get_post_type_slug();
			$lp_id   = ( $lp_type === $post->post_type ) ? $post->ID : 0;
			if ( $lp_id > 0 ) {
				$funnel_id = get_post_meta( $lp_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'    => 'wf-lp',
					'edit'    => $lp_id,
					'section' => 'design',
				], admin_url( 'admin.php' ) ) );

				if ( use_block_editor_for_post_type( $lp_type ) ) {
					add_action( 'admin_footer', array( $this, 'render_back_to_funnel_script_for_block_editor' ) );
				} else { ?>
					<div id="wf_funnel-switch-mode">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Sales Page', 'funnel-builder' ); ?>
						</a>
					</div>
					<script>
                        window.addEventListener('load', function () {
                            (function (window, wp) {
                                var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
                                if (link) {
                                    link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
                                }

                            })(window, wp)
                        });
					</script>
					<?php
				}
			} ?>
			<?php
		}

		public function render_back_to_funnel_script_for_block_editor() {
			global $post;
			$lp_type = WFFN_Core()->landing_pages->get_post_type_slug();
			$lp_id   = ( $lp_type === $post->post_type ) ? $post->ID : 0;
			if ( $lp_id > 0 ) {
				$funnel_id = get_post_meta( $lp_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'wffn_funnel_ref', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'    => 'wf-lp',
					'edit'    => $lp_id,
					'section' => 'design',
				], admin_url( 'admin.php' ) ) ) ?>
				<script id="wf_funnel-back-button-template" type="text/html">
					<div id="wf_funnel-switch-mode" style="margin-right: 15px;margin-left: -5px;">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Sales Page', 'funnel-builder' ); ?>
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
                                    text: "<?php esc_html_e( '← Back to Sales Page', 'funnel-builder' ); ?>",
                                    label: "<?php esc_html_e( 'Back to Sales Page', 'funnel-builder' ); ?>"
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
                                model: "landing_page_base",
                                inputName: 'landing_page_base',
                            });
                        return e;
                    });
                }
			</script>
			<?php
		}




		public function load_assets() {
			$page_now = filter_input( INPUT_GET, 'page' );
			if ( 'wf-lp' === $page_now ) {
				wp_enqueue_script( 'wffn_lp_js', WFFN_Core()->landing_pages->url . 'assets/js/admin.js', [], time() );
				wp_localize_script( 'wffn_lp_js', 'wflp', $this->localize_data() );
				wp_localize_script( 'wffn_lp_js', 'wflp_localization', $this->localize_text_data() );
				if ( 'design' === filter_input( INPUT_GET, 'section' ) ) {
					wp_enqueue_style( 'wffn-vfg', WFFN_Core()->admin->get_admin_url() . '/assets/vuejs/vfg.min.css', array(), WFFN_VERSION_DEV );
				}
			}
		}

		public function localize_data() {
			$data                          = [];
			$design                        = [];
			$data['nonce_save_design']     = wp_create_nonce( 'wffn_lp_save_design' );
			$data['nonce_remove_design']   = wp_create_nonce( 'wffn_lp_remove_design' );
			$data['nonce_import_design']   = wp_create_nonce( 'wffn_lp_import_design' );
			$data['nonce_custom_settings'] = wp_create_nonce( 'wffn_lp_custom_settings_update' );
			$data['nonce_update_edit_url'] = wp_create_nonce( 'wffn_lp_update_edit_url' );
			$data['nonce_toggle_state']    = wp_create_nonce( 'wffn_lp_toggle_state' );
			$data['wflp_edit_nonce']       = wp_create_nonce( 'wflp_edit_landing' );
			$data['design_template_data']  = $this->design_template_data;
			$data['custom_options']        = WFFN_Core()->landing_pages->get_custom_option();
			$data['texts']                 = array(
				'settings_success'       => __( 'Changes saved', 'funnel-builder' ),
				'copy_success'           => __( 'Link copied!', 'funnel-builder' ),
				'shortcode_copy_success' => __( 'Shortcode Copied!', 'funnel-builder' ),
			);

			$data['update_popups']         = array(

				'label_texts' => array(
					'title' => array(
						'label'       => __( 'Name', 'funnel-builder' ),
						'placeholder' => __( 'Enter Name', 'funnel-builder' ),
					),
					'slug'  => array(
						'label'       => sprintf( __( '%s URL Slug', 'funnel-builder' ), WFFN_Core()->landing_pages->get_module_title() ),
						'placeholder' => __( 'Enter Slug', 'funnel-builder' ),
					),
				),

			);
			$data['custom_setting_fields'] = array(
				'legends_texts' => array(
					'custom_css' => __( 'Custom CSS', 'funnel-builder' ),
					'custom_js'  => __( 'External Scripts', 'funnel-builder' ),
				),
				'fields'        => array(
					'custom_css' => array(
						'label'       => __( 'Custom CSS Tweaks', 'funnel-builder' ),
						'placeholder' => __( 'Paste your CSS code here', 'funnel-builder' ),
					),
					'custom_js'  => array(
						'label'       => __( 'Custom JS Tweaks', 'funnel-builder' ),
						'placeholder' => __( 'Paste your code here', 'funnel-builder' ),
					),
				),
			);
			if ( 0 !== $this->edit_id ) {
				$post = get_post( $this->edit_id );

				$data['id']                   = $this->get_edit_id();
				$data['title']                = $post->post_title;
				$data['lp_title']             = WFFN_Core()->landing_pages->get_module_title();
				$data['status']               = $post->post_status;
				$data['content']              = $post->post_content;
				$data['view_url']             = get_the_permalink( $this->edit_id );
				$data['design_template_data'] = $this->design_template_data;
				$design                       = WFFN_Core()->landing_pages->get_page_design( $this->edit_id );

				$data['update_popups']['values'] = array(
					'title' => $post->post_title,
					'slug'  => $post->post_name,
				);
			}

			$design = array_merge( [
				'designs'         => $this->templates,
				'design_types'    => $this->template_type,
				'template_active' => "yes"
			], $design, $data );

			return $design;
		}


		public function localize_text_data() {
			$data = [
				'importer' => [
					'activate_template' => [
						'heading'     => __( 'Are you sure you want to Activate this template?', 'funnel-builder' ),
						'sub_heading' => '',
						'button_text' => __( 'Yes, activate this template!', 'funnel-builder' ),
					],
					'add_template'      => [
						'heading'     => __( 'Are you sure you want to import this template?', 'funnel-builder' ),
						'sub_heading' => '',
						'button_text' => __( 'Yes, Import this template!', 'funnel-builder' ),
					],
					'remove_template'   => [
						'heading'     => __( 'Are you sure you want to remove this template?', 'funnel-builder' ),
						'sub_heading' => __( 'You are about to remove this template. Any changes done to the current template will be lost. Cancel to stop, Remove to proceed.', 'funnel-builder' ),
						'button_text' => __( 'Remove', 'funnel-builder' ),
						'modal_title' => __( 'Remove Template', 'funnel-builder' ),
					],
				],
			];

			return $data;
		}

		public function maybe_register_breadcrumb_nodes() {
			if ( WFFN_Core()->admin->is_wffn_flex_page( 'wf-lp' ) ) {
				BWF_Admin_Breadcrumbs::register_node( array(
					'text' => get_the_title( $this->edit_id ),
					'link' => '',
				) );
			}
		}





	}
}
