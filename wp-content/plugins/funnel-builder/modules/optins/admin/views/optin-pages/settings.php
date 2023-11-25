<?php
/**
 * Without template
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
		$header_ins->set_level_2_side_navigation_active( filter_var( $_GET['section'], FILTER_UNSAFE_RAW) );//phpcs:ignore

		echo $header_ins->render();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>

	<div class="wrap">

		<div class="wffn-tabs-view-vertical wffn-widget-tabs">

			<div class="wffn-tabs-wrapper wffn-tab-center">
				<div class="wffn-tab-title wffn-tab-desktop-title validation_tab " id="tab-title-validation" data-tab="1" role="tab" aria-controls="wffn-tab-content-validation">
					<?php esc_html_e( 'Validation', 'funnel-builder' ); ?>
				</div>
				<div class="wffn-tab-title wffn-tab-desktop-title additional_redirection wffn-active" id="tab-title-additional_redirection" data-tab="2" role="tab" aria-controls="wffn-tab-content-additional_redirection">
					<?php esc_html_e( 'Custom Redirection', 'funnel-builder' ); ?>
				</div>
				<div class="wffn-tab-title wffn-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="3" role="tab" aria-controls="wffn-tab-content-additional_information">
					<?php esc_html_e( 'Custom CSS', 'funnel-builder' ); ?>
				</div>
				<div class="wffn-tab-title wffn-tab-desktop-title description_tab " id="tab-title-description" data-tab="4" role="tab" aria-controls="wffn-tab-content-description">
					<?php esc_html_e( 'External Scripts', 'funnel-builder' ); ?>
				</div>
				<?php do_action('wffn_optin_settings_tabs'); ?>
			</div>

			<div class="wffn-tabs-content-wrapper" id="wffn_wfop_settings">
				<div class="wffn_custom_op_setting_inner" id="wffn_global_setting">

					<form class="wffn_forms_wrap wffn_forms_global_settings ">
						<fieldset>
							<vue-form-generator ref="form_mapping" :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
						</fieldset>
						<div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home">
						</div>
					</form>

					<div class="bwf_form_button">
						<button v-on:click.self="onSubmit" style="float: left;" class="wffn_save_btn_style"><?php esc_html_e( 'Save Changes', 'funnel-builder' ); ?></button>
						<span class="wffn_loader_global_save spinner" style="float: left;"></span>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>

<?php
include_once dirname( __DIR__ ) . '/models/wffn-edit-optin.php';
