<?php
defined( 'ABSPATH' ) || exit;
?>
<div v-for="(fields,section) in available_fields" class="wfop_input_fields">
	<div class="wfop_input_fields_list_wrap">
		<div class="wfop_input_fields_title" v-if="section==='basic'"><b><?php esc_html_e( 'Basic ', 'funnel-builder' ); ?></b></div>
		<div class="wfop_input_fields_title" v-if="section!=='basic'"><b>{{section}} </b></div>
		<hr/>
		<div class="wfop_input_fields_list" v-bind:id="'input_field_'+section+'_container'">

			<div v-if="wfop.tools.ol(fields)<1">
				<p class="wfop-no-fields"><?php esc_html_e( 'Add a new field to drag it into the optin form', 'funnel-builder' ); ?></p>
			</div>

			<div class="wfop_input_field_btn_holder" v-for="(data,index) in fields">
				<div class="wfop_locked_field" v-if="'yes'==data.is_locked&&!wfop.is_wffn_pro_active" v-on:click="(!wfop.is_wffn_pro_active)?wfop.show_pro_message(index):''">
					<div class="wfop_save_btn_style wfop_input_field_place_holder wfop_locked" v-on:click="wfop.show_pro_message('phone')">
						<img src="<?php echo esc_url( WFFN_PLUGIN_URL . '/admin/assets/img/lock.svg' ); ?>"><span>{{data.label}}</span>
					</div>
				</div>
				<div v-else="">
					<div v-if="true==wfop.tools.hp(input_fields[section],index)" v-bind:id="index" class="ss1 wfop_save_btn_style wfop_item_drag" v-bind:data-input-section="section" draggable="true" v-on:dragstart="dragStart($event)" v-on:dragend="dragEnd($event)">
						<span class="dashicons dashicons-no-alt" v-bind:class="section" v-on:click="deleteCustomField(section,index,data.label)" v-if='section=="advanced"'></span>
						<span>{{data.label}}</span>
					</div>
					<div v-if="false==wfop.tools.hp(input_fields[section],index)" class="ss wfop_save_btn_style wfop_input_field_place_holder">
						<span>{{data.label}}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="wfop_input_fields_btn">
	<button class="button" v-on:click="wfop.is_wffn_pro_active?addField():wfop.show_pro_message('new_field');">
		<img v-if="!wfop.is_wffn_pro_active" src="<?php echo esc_url( WFFN_PLUGIN_URL . '/admin/assets/img/lock.svg' ); ?>">
		<?php esc_html_e( 'Add New Field', 'funnel-builder' ); ?></button>
</div>