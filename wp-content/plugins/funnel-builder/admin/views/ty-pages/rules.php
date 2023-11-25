<?php
/**
 * Without template
 */
defined( 'ABSPATH' ) || exit; //Exit if accessed directly
?>
    <style>
        .wffn_content_wrap {
            background: #fff;
            padding: 25px;
        }
    </style>
    <div id="wffn_ty_edit_vue_wrap">
		<?php
		if ( class_exists( 'WFFN_Header' ) ) {
			$header_ins = new WFFN_Header();
			$header_ins->set_level_1_navigation_active( 'funnels' );

			$funnel_status = WFFN_Core()->thank_you_pages->get_status();
			ob_start();
			?>
            <div class="wffn-ellipsis-menu">
                <div class="wffn-menu__toggle">
					<span class="bwfan-tag-rounded bwfan_ml_12 <?php echo 'draft' == $funnel_status ? 'clr-orange' : 'clr-green'; ?>">
						<span class="bwfan-funnel-status"><?php echo 'draft' == $funnel_status ? 'Draft' : 'Published'; ?></span>
						
						<?php echo file_get_contents( plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/icons/arrow-down.svg' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
                </div>
                <div class="wffn-ellipsis-menu-dropdown">
                    <a v-on:click="updateThankyou()" href="javascript:void(0);" class="bwf_edt wffn-ellipsis-menu-item"><?php esc_html_e( 'Edit' ) ?></a>
                    <a v-bind:href="view_url" target="_blank" class="wffn-step-preview wffn-step-preview-admin wffn-ellipsis-menu-item"><?php esc_html_e( 'Preview' ) ?></a>
                    <div class="wf_funnel_card_switch">
                        <label class="wf_funnel_switch wffn-ellipsis-menu-item">
                            <span class="bwfan-status-toggle"><?php echo 'draft' == $funnel_status ? 'Publish' : 'Draft'; ?></span>
                            <input type="checkbox" <?php checked( 'publish', WFFN_Core()->thank_you_pages->get_status() ); ?>>
                        </label>
                    </div>
                </div>
            </div>
			<?php
			$funnel_actions = ob_get_contents();
			ob_end_clean();

			$get_header_data = BWF_Admin_Breadcrumbs::render_top_bar( true );
			if ( is_array( $get_header_data ) ) {
				$data_count      = count( $get_header_data );
				$page_title_data = $get_header_data[ $data_count - 1 ];
				$back_link_data  = ( 1 < $data_count ) ? $get_header_data[ $data_count - 2 ] : array();
				$page_title      = $page_title_data['text'] ?? esc_html( 'Funnels' );
				$back_link       = $back_link_data['link'] ?? '#';

				$header_ins->set_page_back_link( $back_link );
				$header_ins->set_page_heading( "$page_title" );
				$header_ins->set_page_heading_meta( $funnel_actions );
			}


			$header_nav_data = WFFN_Core()->thank_you_pages->admin->get_tabs_links( WFFN_Core()->thank_you_pages->get_edit_id() ); //navigation data
			$header_nav_data = array_column( $header_nav_data, null, 'section' );
			$header_ins->set_level_2_side_navigation( $header_nav_data ); //set header 2nd level navigation
			$header_ins->set_level_2_side_navigation_active( filter_var( $_GET['section'], FILTER_UNSAFE_RAW ) );//phpcs:ignore

			echo $header_ins->render();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
        <div class="wrap">

            <div class="wffn_content_wrap wffn_ty_edit_rules">
				<?php if ( ! class_exists( 'WFTP_PRO_Core' ) ) {
				?>
                <div id="wfty_funnel_rule_add_settings" data-is_rules_saved="no">
                    <div class="wfty_welcome_wrap wf_funnel_center_align">
                        <div class="wfty_welcome_wrap_in">
                            <div class="wfty_welc_head">
                                <div class="wffn_clear_40"></div>
                                <div class="wffn_clear_20"></div>
                                <div class="wfty_welc_icon"><span style="color: #0074ac; position: relative; font-size: 32px; right: 8px;" class="dashicons dashicons-lock"></span></div>
                                <div class="wfty_welc_title">Rules are PRO feature!</div>
                            </div>
                            <div class="wfty_welc_text"><p>Unlock this Pro feature now and experience the fully-loaded version.</p>
                                <div class="wfty_welc_text"><p>
                                        <a href="<?php echo WFFN_Core()->admin->get_pro_link(); ?>" target="__blank" rel="noopener noreferrer" class="wf_funnel_btn_temp wf_funnel_btn_blue_temp">Upgrade
                                            to
                                            PRO</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

					<?php
					} else { ?>
						<?php do_action( 'wfty_dashboard_page_rules' );
					} ?>
                </div>
            </div>
        </div>
    </div>


<?php include_once dirname( __DIR__ ) . '/models/wffn-edit-thankyou.php'; ?>