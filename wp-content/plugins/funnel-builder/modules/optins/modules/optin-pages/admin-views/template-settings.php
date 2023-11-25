<?php ?>
<div class="wf_funnel_templates_outer transparent_cover wffn-hide" v-bind:class="'yes'===template_active?'wffn-show':''">

	<div class="wffn-tabs-view-vertical wffn-widget-tabs">
		<div class="wffn-tabs-wrapper wffn-tab-center">
			<div class="wffn_tab_heading">
				<?php esc_html_e( 'Optin Page Settings', 'funnel-builder' ); ?>
			</div>
			<div class="wffn-tab-title wffn-tab-desktop-title shortcode_tab" id="tab-title-shortcode" data-tab="0" role="tab" aria-controls="wffn-tab-shortcode">
				<?php esc_html_e( 'Shortcodes', 'funnel-builder' ); ?>
			</div>
		</div>

		<div class="wffn-tabs-content-wrapper wffn-optin-forms-container">
			<!-- Shortcode tab content -->
			<div class="wffn-opt-shortcode-tab-area wffn-tab-content" id="wffn_optin_shortcode_setting">

				<div class="wffn-optin-shortcode">
					<fieldset v-if="`yes`===selected_template.show_shortcodes">
						<legend><?php esc_html_e( 'Form Shortcodes', 'funnel-builder' ); ?></legend>
					</fieldset>
					<div v-if="`yes`===selected_template.show_shortcodes" class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Optin Form Shortcode', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in">
								<span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" value="[wfop_form]" type="text"></span>
								<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
									<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg>
									<?php esc_html_e( 'Copy', 'funnel-builder' ); ?>
								</a>
							</div>
						</div>

					</div>
					<div v-if="`yes`===selected_template.show_shortcodes" class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Optin Popup Link', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in">
								<?php if ( WFFN_Common::wffn_is_funnel_pro_active() ) { ?>
									<span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" value="<?php echo esc_attr( WFOPP_Core()->optin_pages->get_open_popup_url() ); ?>" type="text"></span>
									<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
										<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg>
										<?php esc_html_e( 'Copy', 'funnel-builder' ); ?>
									</a>
								<?php } else { ?>
									<span class="wffn-scode-text wffn-scode-copy"><input class="wffn_blur_text" readonly="readonly" value="<?php echo esc_attr( WFOPP_Core()->optin_pages->get_open_popup_url() ); ?>" type="text"></span>
									<a target="_blank" href="<?php echo esc_url(WFFN_Core()->admin->get_pro_link()); ?>" class="wffn_copy_text scode"><?php esc_html_e( 'Get Pro', 'funnel-builder' ); ?></a>
								<?php } ?>
							</div>
						</div>
						<div class="wf_funnel_clear_20"></div>
					</div>
					<fieldset>
						<legend><?php esc_html_e( 'Personalization Shortcodes', 'funnel-builder' ); ?></legend>
					</fieldset>

					<div class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Optin First Name', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfop_first_name]"></span>
								<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
									<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg>
									<?php esc_html_e( 'Copy', 'funnel-builder' ); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Optin Last Name', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfop_last_name]"></span>
								<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
									<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg>
									<?php esc_html_e( 'Copy', 'funnel-builder' ); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Optin Email', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfop_email]"></span>
								<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
									<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg>
									<?php esc_html_e( 'Copy', 'funnel-builder' ); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Optin Phone', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfop_phone]"></span>
								<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
									<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg>
									<?php esc_html_e( 'Copy', 'funnel-builder' ); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Optin Custom Fields', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfop_custom key='Label']"></span>
								<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
									<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg>
									<?php esc_html_e( 'Copy', 'funnel-builder' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home"></div>

		</div>

	</div>
</div>
