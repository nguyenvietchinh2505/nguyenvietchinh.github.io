<?php
/**
 * Integration tab setting
 */
defined( 'ABSPATH' ) || exit; //Exit if accessed directly
?>
<div id="wffn_op_edit_vue_wrap">
	<?php
	if ( class_exists( 'WFFN_Header' ) ) {
		$header_ins = new WFFN_Header();
		$header_ins->set_level_1_navigation_active( 'funnels' );
		
		
        $funnel_status = WFOPP_Core()->optin_pages->get_status();
		ob_start();
		?>
		<div class="wffn-ellipsis-menu">
			<div class="wffn-menu__toggle">
				<span class="bwfan-tag-rounded bwfan_ml_12 <?php echo 'draft' === $funnel_status ? 'clr-orange' : 'clr-green'; ?>">
					<span class="bwfan-funnel-status"><?php echo 'draft' === $funnel_status ? 'Draft' : 'Published'; ?></span>
					
					<?php echo file_get_contents(  plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/icons/arrow-down.svg'  ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</div>
			<div class="wffn-ellipsis-menu-dropdown">
                <a v-on:click="updateOptin()" href="javascript:void(0);" class="bwf_edt wffn-ellipsis-menu-item"><?php esc_html_e( 'Edit' ) ?></a>
				<a v-bind:href="view_url" target="_blank" class="wffn-step-preview wffn-step-preview-admin wffn-ellipsis-menu-item"><?php esc_html_e( 'Preview' ) ?></a>
				<div class="wf_funnel_card_switch">
					<label class="wf_funnel_switch wffn-ellipsis-menu-item">
						<span class="bwfan-status-toggle"><?php echo 'draft' === $funnel_status ? 'Publish' : 'Draft'; ?></span>
						<input type="checkbox" <?php checked( 'publish', WFOPP_Core()->optin_pages->get_status() ); ?>>
					</label>
				</div>
			</div>
		</div>
		<?php
		$funnel_actions = ob_get_contents();
		ob_end_clean();

        $get_header_data = BWF_Admin_Breadcrumbs::render_top_bar(true);
		if( is_array( $get_header_data ) ) {
			$data_count      = count($get_header_data);
			$page_title_data = $get_header_data[ $data_count - 1 ];
			$back_link_data  = ( 1 < $data_count ) ? $get_header_data[ $data_count - 2 ] : array();
			$page_title      = $page_title_data['text'] ?? esc_html( 'Funnels' );
			$back_link       = $back_link_data['link'] ?? '#';
	
			$header_ins->set_page_back_link( $back_link );
			$header_ins->set_page_heading( "$page_title" );
			$header_ins->set_page_heading_meta($funnel_actions);
		}
	
		$header_nav_data = WFOPP_Core()->optin_pages->admin->get_tabs_links( WFOPP_Core()->optin_pages->get_edit_id() ); //navigation data
		$header_nav_data = array_column( $header_nav_data, null, 'section' );
		$header_ins->set_level_2_side_navigation( $header_nav_data ); //set header 2nd level navigation
		$header_ins->set_level_2_side_navigation_active( filter_var( $_GET['section'], FILTER_UNSAFE_RAW) ); //phpcs:ignore

		echo $header_ins->render();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>

	<div class="wrap">
	
		<?php
		$form_builders = WFOPP_Core()->form_controllers->get_supported_form_controllers(); //phpcs:ignore @SuppressWarnings(PHPMD.UnusedFormalParameter)
	
	
		$optin_form_options = WFOPP_Core()->optin_pages->get_optin_form_integration_option();
		$is_inti            = isset( $optin_form_options['optin_form_enable'] ) ? $optin_form_options['optin_form_enable'] : 'false';
			
		$lms_obj = WFOPP_Core()->optin_actions->get_integration_object( 'assign_ld_course' );
		$lifterlms_obj = WFOPP_Core()->optin_actions->get_integration_object( 'assign_lifter_course' );
		$affiliatewp_obj = WFOPP_Core()->optin_actions->get_integration_object( 'affiliatewp_lead' );
	
		$lms_active = $lifterlms_active = $affiliatewp_active = false;

		$hide_class_lms = '';
		$hide_class_crm = '';		

		if ( $lms_obj instanceof WFFN_Optin_Action ) {
			$lms_active = $lms_obj->should_register();
		}		

		if ( ! $lms_active ) {
			$hide_class_lms = 'hide_bwf_btn';
		}					

		if ( ! WFFN_Common::wffn_is_funnel_pro_active() ) {
			$hide_class_crm = 'hide_bwf_btn';
		}
	
		?>
		<div class="wffn-tabs-view-vertical wffn-widget-tabs">
	
			<div class="wffn-tabs-wrapper wffn-tab-center">
				
				<div class="wffn-tab-title wffn-tab-desktop-title" data-tab="1" role="tab">
					<?php esc_html_e( 'Notifications', 'funnel-builder' ); ?>
				</div>
				<div class="wffn-tab-title wffn-tab-desktop-title crm_only <?php echo esc_attr( $hide_class_crm ); ?>" data-tab="2" role="tab"><?php esc_html_e( 'CRM', 'funnel-builder' ); ?></div>
				<div class="wffn-tab-title wffn-tab-desktop-title <?php echo esc_attr( $hide_class_lms ); ?>" data-tab="3" role="tab"><?php esc_html_e( 'Learndash', 'funnel-builder' ); ?></div>				

				<?php do_action('wffn_optin_action_tabs'); ?>
			</div>
	
			<div class="wffn-tabs-content-wrapper" id="wffn_actions_container">
				<div class="wffn_custom_op_setting_inner" id="wffn_global_setting">
	
					<form class="wffn_forms_wrap wffn_forms_global_settings">
						<fieldset>
	
							<!-- vue form generator responsible for the notifications settings -->
							<!-- fieldsets for Webhook settings -->						
	
							<vue-form-generator ref="action_ref" :schema="schemaAction" :model="modelAction" :options="formOptions"></vue-form-generator>
	
							<!-- fieldsets for CRM integration settings -->
							<div class="wffn-integration">
								<div class="vue-form-generator">
									<fieldset>
										<legend class="wffn-show-pro"><span v-if="!wfop.is_wffn_pro_active" data-item="crm" v-on:click="wfop.show_pro_message('data_crm')"><img src="<?php echo esc_url(WFFN_PLUGIN_URL . '/admin/assets/img/lock.svg') ?>" ></span><?php esc_html_e( 'CRM', 'funnel-builder' ); ?></legend>
										<div v-bind:class="!wfop.is_wffn_pro_active?'wffn-is-disabled':''" class="form-group valid field-radios">
											<label for="optin-form-enable"><span><?php esc_html_e( 'Enable Integration', 'funnel-builder' ); ?></span></label>
											<div class="field-wrap">
												<div class="radio-list">
													<label class=""><input id="optin-form-enable-1" type="radio" name="optin_form_enable" value="true" <?php if ( $is_inti === 'true' ) {
															echo "checked";
														} ?>><?php esc_html_e( 'Yes', 'funnel-builder' ); ?></label>
													<label class=""><input id="optin-form-enable-2" type="radio" name="optin_form_enable" value="false" <?php if ( $is_inti === 'false' ) {
															echo "checked";
														} ?>><?php esc_html_e( 'No', 'funnel-builder' ); ?></label>
												</div>
											</div>
										</div>
										<?php do_action('wfopp_admin_crm_settings',$optin_form_options); ?>
									</fieldset>
	
									<!-- learndsh integration html below, if environment found we show the form generator, else show message only
									Since vue-form-generator comes with its own fieldset and legend tags then we are managing it accordingly
									 -->
	
									<div v-if="!wfop.is_wffn_pro_active" class="no-learndash">
										<fieldset>
											<legend class="wffn-show-pro"><span data-item="learndash" v-on:click="wfop.show_pro_message('data_learndash')"><img src="<?php echo esc_url(WFFN_PLUGIN_URL . '/admin/assets/img/lock.svg') ?>" ></span><?php esc_html_e( 'Learndash', 'funnel-builder' ); ?></legend>
											<div v-bind:class="!wfop.is_wffn_pro_active?'wffn-is-disabled':''" class="form-group valid field-radios">
												<label for="optin-form-enable"><span><?php esc_html_e( 'LMS Course', 'funnel-builder' ); ?></span></label>
												<div class="field-wrap">
													<div class="radio-list">
														<label class=""><input id="lms-course-5" type="radio" name="lms_course_5" value="true"><?php esc_html_e( 'Yes', 'funnel-builder' ); ?></label>
														<label class=""><input id="lms-course-6" type="radio" name="lms_course_5" value="false" checked ><?php esc_html_e( 'No', 'funnel-builder' ); ?></label>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<div v-else-if="!wfop_action.lms_active" class="no-learndash">
										<fieldset>
											<legend><?php esc_html_e( 'Learndash', 'funnel-builder' ); ?></legend>
											<p class="no-pro">
												<?php esc_html_e( 'Note: Learndash plugin needs to be activated to enable integration.', 'funnel-builder' ); ?>
											</p>
										</fieldset>
									</div>
									<div v-else>
										<vue-form-generator ref="learndash_ref" :schema="schemaLMS" :model="modelLMS" :options="formOptions"></vue-form-generator>
	
									</div>									

									<?php do_action('wffn_optin_action_tabs_content')?>

								</div>
							</div>														

						</fieldset>
					</form>
					<div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home">
					</div>
					<div class="bwf_form_button">
						<button style="float: left" v-on:click.self="onSubmitActions" id="wffn_optin_form_submit" class="wffn_save_btn_style"><?php esc_html_e( 'Save Changes', 'funnel-builder' ); ?></button>
						<span class="wffn_loader_global_save spinner" style="float: left;"></span>
						<span class="wffn_success_msg wffn-hide"></span>
					</div>
				</div>
			</div>
		</div>
	
	</div>
</div>
<?php
include_once dirname( __DIR__ ) . '/models/wffn-edit-optin.php';
?>
<script id="vue-f-button" type="text/x-template" xmlns="http://www.w3.org/1999/html">
	{{schema.type}}
	<a onclick="window.wfop_design.main.onSubmitActions('test')" style="float: left;" class=" email_test button button-primary">Test Email</a>
	{{schema.type}}
</script>

