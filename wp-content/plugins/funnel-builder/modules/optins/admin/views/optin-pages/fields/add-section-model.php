<?php
?>
<div class="wfop_success_modal" style="display: none" id="modal-saved-data-success" data-iziModal-icon="icon-home"></div>
<!-- add product modal start-->
<div class="wfop_izimodal_default" id="modal-add-section">
	<div class="sections">
		<form id="add-section-form" data-bwf-action="add_section" v-on:submit.prevent="onSubmit">
			<div class="wfop_vue_forms">
				<vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
			</div>
			<fieldset>
				<div class="bwf_form_submit">
					<button type="submit" class="wfop_btn wfop_btn_primary">{{btn_name}}</button>
				</div>
			</fieldset>
		</form>
	</div>
</div>
