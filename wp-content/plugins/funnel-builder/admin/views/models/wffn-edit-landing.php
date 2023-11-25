<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly
/**
 * Edit Sales page model
 */
?>
<!-----  EDIT MODAL  ------->
<div id="wf_landing_edit_modal" class="iziModal" data-izimodal-group="alerts">
	<div id="part-update-landing">

		<div v-if="`1`==current_state" class="wffn-update-landing-form">
			<div class="wf_funnel_popup_header">
				<div class="wf_funnel_pop_title"><?php esc_html_e( 'Edit Sales Page', 'funnel-builder' ); ?></div>
				<button data-iziModal-close class="icon-close wf_funnel_popup_close">
					<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16"><path d="M9.46702 7.99987L15.6972 1.76948C16.1027 1.36422 16.1027 0.708964 15.6972 0.303702C15.292 -0.10156 14.6367 -0.10156 14.2315 0.303702L8.00106 6.5341L1.77084 0.303702C1.36539 -0.10156 0.710327 -0.10156 0.305065 0.303702C-0.100386 0.708964 -0.100386 1.36422 0.305065 1.76948L6.53528 7.99987L0.305065 14.2303C-0.100386 14.6355 -0.100386 15.2908 0.305065 15.696C0.507032 15.8982 0.772588 15.9998 1.03795 15.9998C1.30332 15.9998 1.56869 15.8982 1.77084 15.696L8.00106 9.46565L14.2315 15.696C14.4336 15.8982 14.699 15.9998 14.9643 15.9998C15.2297 15.9998 15.4951 15.8982 15.6972 15.696C16.1027 15.2908 16.1027 14.6355 15.6972 14.2303L9.46702 7.99987Z" fill="#353030"></path></svg>
				</button>
			</div>
			<div class="wf_funnel_pop_body">
				<form class="wffn_forms_update_landing wffn_swl_btn">
					<div class="bwfabt_row">
						<div class="bwfabt_vue_forms">
							<vue-form-generator ref="update_landing_ref" :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
						</div>
					</div>
					<div class="wf_funnel_clear_20"></div>
					<div class="wffn-modal-action">
						<input data-iziModal-close type="button" class="wf_funnel_btn wf_cancel_btn" value="Cancel"/>
						<input type="button" v-on:click="updateLanding()" class="wf_funnel_btn wf_funnel_btn_primary" value="Update"/>
					</div>
				</form>
			</div>
		</div>


	</div>
</div>
