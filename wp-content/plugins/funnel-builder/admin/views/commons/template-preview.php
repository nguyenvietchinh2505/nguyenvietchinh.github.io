<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff ?>
<div class="wfacp_template_preview_container">
    <div class="wf_funnel_templates_outer wf_template_preview_wrap wffn-hide" v-if="'yes'==template_active" v-if="'yes'==template_active" v-bind:class="'yes'==template_active?'wffn-show':''">
        <div class="wf_funnel_heading_choosen_template">

            <div class="wffn_funnel_heading">
                <strong><?php esc_html_e( 'Selected Template', 'funnel-builder' ) ?> :</strong>&nbsp;
                <span v-if="selected_template.build_from_scratch"><?php esc_html_e( 'Start from scratch', 'funnel-builder' ) ?></span>
                <span v-else>{{selected_template.name}}</span>
                <span class="bwfan-tag-rounded bwfan_ml_12 clr-primary" v-html="get_builder_title()"></span>
            </div>

            <div class="wffn_funnel_header_action">

                <div class="wffn-ellipsis-menu">
                    <div class="wffn-ellipsis-menu__toggle">
						<?php echo file_get_contents( plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/icons/ellipsis-menu.svg' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <div class="wffn-ellipsis-menu-dropdown">
                        <a href="javascript:void(0)" class="wffn-ellipsis-menu-item" v-on:click="get_remove_template()">
							<?php echo file_get_contents( plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/icons/delete.svg' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php esc_html_e( 'Remove Template' ) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="wf_funnel_clear_10"></div>

        <div class="wf_funnel_templates_inner wf_funnel_selected_designed">
            <div class="wf_funnel_templates_design" v-if="selected_template.build_from_scratch">
                <div class="wf_funnel_temp_card">
                    <div class="wf_funnel_template_sec wf_funnel_build_from_scratch">
                        <div class="wf_funnel_template_sec_design">
                            <div class="wf_funnel_temp_overlay">
                                <div class="wf_funnel_temp_middle_align">
                                    <div class="wffn_template_btn_add">
                                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect fill="white"></rect><path d="M12 2C6.48566 2 2 6.48566 2 12C2 17.5143 6.48566 22 12 22C17.5143 22 22 17.5136 22 12C22 6.48645 17.5143 2 12 2ZM12 20.4508C7.34082 20.4508 3.54918 16.66 3.54918 12C3.54918 7.34004 7.34082 3.54918 12 3.54918C16.6592 3.54918 20.4508 7.34004 20.4508 12C20.4508 16.66 16.66 20.4508 12 20.4508Z" fill="#000000"></path><path d="M15.873 11.1557H12.7746V8.05734C12.7746 7.62976 12.4284 7.28273 12 7.28273C11.5716 7.28273 11.2254 7.62976 11.2254 8.05734V11.1557H8.12703C7.69867 11.1557 7.35242 11.5027 7.35242 11.9303C7.35242 12.3579 7.69867 12.7049 8.12703 12.7049H11.2254V15.8033C11.2254 16.2309 11.5716 16.5779 12 16.5779C12.4284 16.5779 12.7746 16.2309 12.7746 15.8033V12.7049H15.873C16.3013 12.7049 16.6476 12.3579 16.6476 11.9303C16.6476 11.5027 16.3013 11.1557 15.873 11.1557Z" fill="#000000"></path></svg>
                                    </div>
                                    <div class="wffn_p"><b><?php echo esc_html__( 'Start from scratch', 'funnel-builder' ); ?></b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wf_funnel_templates_design" v-else>
                <div class="wf_funnel_img" style="position: relative">
                    <div class="wf_funnel_template_importing_loader" style="display: none">
                        <span class="spinner"></span>
                    </div>
                    <span>
						<img v-bind:src="selected_template.thumbnail">
					</span>
                </div>
            </div>

            <div class="wf_funnel_templates_action">
                <div class="wf_funnel_clear_5"></div>

                <a v-if="'embed_forms'!==selected_template.template_type" class="wf_funnel_btn_temp_alter wf_edit_builder_link wf_funnel_btn_blue_temp" v-bind:href="get_edit_link()">
                    {{get_button_text()}} <?php esc_html_e( 'Template' ) ?>
                </a> <?php //phpcs:ignore WordPressVIPMinimum.Security.Vuejs.RawHTMLDirectiveFound ?>

                <a target="_blank" v-bind:href="<?php echo esc_attr( $identifier_variable ); //phpcs:ignore ?>.view_url" class="wf_funnel_btn_temp_alter wf_funnel_btn_white_temp wf_funnel_btn_blue_outline">
					<?php esc_html_e( 'Preview', 'funnel-builder' ) ?>
                </a>

                <a class="wf_funnel_btn_temp_alter wf_funnel_btn_white_temp" href=" <?php echo esc_url( admin_url( 'post.php?post=' . $admin_instance->get_edit_id() . '&action=edit' ) ); //phpcs:ignore ?>">
					<?php esc_html_e( ' Switch to WordPress Editor', 'funnel-builder' ) ?>
                </a>
            </div>
        </div>

        <div class="wf_funnel_clear_40"></div>
		<?php do_action( 'wf_funnel_builder_design_after_template' ); ?>
    </div>

    <!------  WITHOUT TEMPLATES  ------->
    <div class="wf_funnel_clear_30"></div>
    <div class="wf_funnel_templates_outer transparent_cover wffn-hide" v-bind:class="'yes'==template_active?'wffn-show':''">
		<?php WFFN_Core()->admin->get_template_helper_settings_html( $admin_instance ); //phpcs:ignore ?>
    </div>
</div>
