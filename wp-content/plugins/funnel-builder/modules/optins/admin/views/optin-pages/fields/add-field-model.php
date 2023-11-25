<?php

defined( 'ABSPATH' ) || exit;
?>

<!-- add Field modal start-->
<div class="wfop_izimodal_default" id="modal-add-field" style="visibility: hidden">
	<div class="sections">
		<form id="add-field-form" data-bwf-action="add_field" v-on:submit.prevent="onSubmit">
			<div class="wfop_vue_forms">
				<vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
				<fieldset>
					<div class="bwf_form_submit wffn_swl_btn">
						<input data-iziModal-close type="button" class="wfop_btn wf_cancel_btn" value="Cancel"/>
						<span class="add_field_spinner"><span class="wfop_spinner spinner"></span>
					</span>
					<input type="submit" class="wfop_btn wfop_btn_primary" value="<?php esc_html_e( 'Add Field', 'funnel-builder' ); ?>"/>
					</div>
				</fieldset>
			</div>
		</form>
	</div>
</div>