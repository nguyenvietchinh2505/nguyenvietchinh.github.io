<?php
defined( 'ABSPATH' ) || exit;?>

	<div id="wfop_layout_container">
		<div class="wfopp_loader"><span class="spinner"></span></div>
		<div class="wfop_box_size">
			<div class="wfop_wrap_inner wfop_wrap_inner_offers" style="margin-left: 0;">
				<div class="wfop_wrap_r">
					<div class="template_field_holder" style="min-height: 500px">
						<div class="template_steps_container" style="float: left;width:70%">
							<div class="wfop_fsetting_table_head">
								<div class="wfop_fsetting_table_head_in wfop_clearfix">
									<div class="wfop_fsetting_table_title">
										<div class="wfop_template_tabs_container clearfix">
											<div class="wfop_step_actions">
											</div>
											<div class="wfop_add_new_step">
											</div>
										</div>
									</div>
									<div class="bwf_ajax_save_buttons bwf_form_submit">
										<span class="wfop_spinner spinner"></span>
										<a href="javascript:void(0)" id="wfop_save_form_layout" class="wfop_save_btn_style" v-on:click="save_template()"><?php esc_html_e( 'Save Changes', 'funnel-builder' ); ?></a>
									</div>
								</div>
							</div>

							<?php include_once __DIR__ . '/fields/field_container.php'; ?>

						</div>
						<div class="template_field_selecter" style="float: right; width:28%">

							<div class="wfop_fsetting_table_head">
								<div class="wfop_fsetting_table_head_in wfop_clearfix">
									<div class="wfop_fsetting_table_title">
										<strong><span class="wfop_template_friendly_name"><?php esc_html_e( 'Fields', 'funnel-builder' ); ?></span></strong>
									</div>
								</div>
							</div>
							<?php include_once __DIR__ . '/fields/input_fields.php'; ?>
						</div>
						<div style="clear: both"></div>
					</div>
				</div>
				<div style="clear: both"></div>
			</div>
		</div>

	</div>
	<div style="display: none" id="modal-global-settings_success">
	</div>
<?php
include __DIR__ . '/fields/add-field-model.php';
include __DIR__ . '/fields/edit-field-model.php';
include __DIR__ . '/fields/css.php';
