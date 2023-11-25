<div class="single_step_template" v-for="(step,slug) in steps" v-if="step.active=='yes'" v-bind:data-slug="slug" v-bind:style="slug=='single_step'?'':'display:none'">
	<div v-bind:class="'wfop_sections_holder '+slug">
		<div v-for="(fieldset,f_index) in fieldsets[slug]" class="wfop_field_container" v-bind:field-index="f_index" v-bind:step-name="slug">
			<div class="wfop_fields_border_div">
				<div class="wfop_field_container_head clearfix">
					<div class="wfop_field_container_heading">
						<h4><?php esc_html_e( 'Fields', 'funnel-builder' ); ?></h4>
					</div>
				</div>
				<div v-bind:class="'template_field_container '+slug" v-bind:field-index="f_index" v-on:drop="drop($event,slug,f_index)" v-on:dragover="allowDrop($event)" v-on:dragenter="dragEnter($event)" v-on:dragleave="dragLeave($event)" v-bind:step-name="slug">
					<div v-if="wfop.tools.ol(fieldset.fields)>0" v-for="(data,index) in fieldset.fields" v-bind:data-id="data.id" class="wfop_save_btn_style wfop_item_drag" v-if="data.label" v-bind:data-input-section="data.field_type" v-on:click="editField(slug,f_index,index,$event)">
						<span class="wfop_remove_fields dashicons dashicons-no" v-on:click="removeField(slug,data.id,data.field_type,f_index,$event)" v-if="data.id!='payment_method'"></span>
						<span class="wfop_tooltip"><?php esc_html_e( 'Click to edit. Drag to re-order.', 'funnel-builder' ) ?></span>
						<span>{{data.label}}</span>
					</div>
					<div v-if="wfop.tools.ol(fieldset.fields)==0" class="template_field_placeholder_tbl">
						<div class="template_field_placeholder_tbl_cel"><?php esc_html_e( 'Drag new fields here to populate the section', 'funnel-builder' ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>