<div id="wffn_ty_design_vue_wrap" class="wffn-tabs-view-vertical wffn-widget-tabs wffn-ty-shortcodes-tab">

    <div class="wffn-tabs-wrapper wffn-tab-center">
        <div class="wffn_tab_heading">
			<?php esc_html_e( 'Thank You Page Settings', 'funnel-builder' ); ?>
        </div>
        <div class="wffn-tab-title wffn-tab-desktop-title additional_information_tab wffn-active" id="tab-title-additional_information" data-tab="0" role="tab" aria-controls="wffn-tab-content-additional_information">
			<?php esc_html_e( 'Shortcodes', 'funnel-builder' ); ?>
        </div>
        <div v-if="`yes`==selected_template.show_shortcode" class="wffn-tab-title wffn-tab-desktop-title additional_information_tab " id="tab-title-additional_information" data-tab="1" role="tab" aria-controls="wffn-tab-content-additional_information">
			<?php esc_html_e( 'Design', 'funnel-builder' ); ?>
        </div>
    </div>

    <div class="wffn-tabs-content-wrapper">
        <div class="wffn-ty-shortcode-tab-area wffn_forms_global_settings wffn-opt-shortcode-tab-area" id="wffn_global_setting">
            <div class="wffn-scodes-products">
                <div class="wfty-thankyou-shortcodes" v-if="`yes`==selected_template.show_shortcode">
                    <fieldset>
                        <legend><?php esc_html_e( 'Order Shortcodes', 'funnel-builder' ); ?></legend>
                    </fieldset>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Order Details', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" value="[wfty_order_details]" type="text"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>

                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer Details', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfty_customer_details]"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>

                </div>

                <fieldset>
                    <legend><?php esc_html_e( 'Personalization Shortcodes', 'funnel-builder' ); ?></legend>
                </fieldset>

                <div v-if="current_template_type ==='oxy'">
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer Email', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in">
                                <span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[oxygen data='phpfunction' function='wfty_customer_email']"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer First Name', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in">
                                <span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[oxygen data='phpfunction' function='wfty_customer_first_name']"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer Last Name', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in">
                                <span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[oxygen data='phpfunction' function='wfty_customer_last_name']"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer Phone Number', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in">
                                <span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[oxygen data='phpfunction' function='wfty_customer_phone_number']"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Order Number', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in">
                                <span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[oxygen data='phpfunction' function='wfty_order_number']"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer Email', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfty_customer_email]"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer First Name', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfty_customer_first_name]"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer Last Name', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfty_customer_last_name]"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Customer Phone Number', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfty_customer_phone_number]"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>
                    <div class="wffn-scodes-row">
                        <h4 class="wffn-scodes-label"><?php esc_html_e( 'Order Number', 'funnel-builder' ); ?></h4>
                        <div class="wffn-scodes-value">
                            <div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfty_order_number]"></span>
                                <a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
                                    <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                                    </svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
                        </div>
                    </div>

					<div class="wffn-scodes-row">
						<h4 class="wffn-scodes-label"><?php esc_html_e( 'Order Total', 'funnel-builder' ); ?></h4>
						<div class="wffn-scodes-value">
							<div class="wffn-scodes-value-in"><span class="wffn-scode-text wffn-scode-copy"><input readonly="readonly" type="text" value="[wfty_order_total]"></span>
								<a href="javascript:void(0)" v-on:click="copy" class="wffn_copy_text scode">
									<svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
										<path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
									</svg><?php esc_html_e( 'Copy', 'funnel-builder' ); ?></a></div>
						</div>
					</div>

					<?php
					do_action( 'wffn_addon_scodes_row' );
					?>

                </div>
            </div>
        </div>

        <div v-if="`yes`==selected_template.show_shortcode" class="wffn-ty-shortcode-tab-area" id="wffn_global_setting">
            <form id="wffn_design_setting" class="wffn_forms_wrap">
                <fieldset class="show_fieldset">
                    <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
                </fieldset>
            </form>
            <div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home">
            </div>
            <div class="bwf_form_button">
                <span class="wffn_loader_global_save" style="float: left;"></span>
                <button v-on:click.self="onSubmitShortCodeForm" style="float: left;" class="wffn_save_btn_style"><?php esc_html_e( 'Save Changes', 'funnel-builder' ); ?></button>
            </div>
        </div>

    </div>

</div>