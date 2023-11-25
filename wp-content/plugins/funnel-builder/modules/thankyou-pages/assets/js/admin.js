/*global wftp*/
/*global Vue*/
/*global VueFormGenerator*/
/*global wp_admin_ajax*/
/*global wffn_swal*/
/*global wftp_localization*/
/*global _*/
/*global wffn*/
(function ($) {
    'use strict';
    /*eslint-env jquery*/
    /*global Backbone*/

    var wfty_app = {};
    wfty_app.Helpers = {};
    wfty_app.Views = {};
    wfty_app.Events = {};

    _.extend(wfty_app.Events, Backbone.Events);
    wfty_app.Helpers.uniqid = function (prefix, more_entropy) {

        if (typeof prefix == 'undefined') {
            prefix = "";
        }

        var retId;
        var formatSeed = function (seed, reqWidth) {
            seed = parseInt(seed, 10).toString(16); // to hex str
            if (reqWidth < seed.length) { // so long we split
                return seed.slice(seed.length - reqWidth);
            }
            if (reqWidth > seed.length) { // so short we pad
                return Array(1 + (reqWidth - seed.length)).join('0') + seed;
            }
            return seed;
        };

        // BEGIN REDUNDANT
        if (!this.php_js) {
            this.php_js = {};
        }
        // END REDUNDANT
        if (!this.php_js.uniqidSeed) { // init seed with big random int
            this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
        }
        this.php_js.uniqidSeed++;

        retId = prefix; // start with prefix, add current milliseconds hex string
        retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
        retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
        if (more_entropy) {
            // for more entropy we add a float lower to 10
            retId += (Math.random() * 10).toFixed(8).toString();
        }

        return retId;

    };

    class wftp_design {
        constructor() {

            this.id = wftp.id;
            this.selected = wftp.selected;
            this.selected_type = wftp.selected_type;
            this.designs = wftp.designs;
            this.design_types = wftp.design_types;
            this.template_active = wftp.template_active;

            this.main();

            $(".wf_funnel_card_switch input[type='checkbox']").click(function () {
                let wp_ajax = new wp_admin_ajax();
                let toggle_data = {
                    'toggle_state': this.checked,
                    'wftp_id': wftp.id,
                    '_nonce': wftp.nonce_toggle_state,
                };

                let statusWrapper = this.closest('.wffn-ellipsis-menu');
                if (statusWrapper) {
                    if (this.checked) {
                        $(statusWrapper).find('.bwfan-tag-rounded').removeClass('clr-orange').addClass('clr-green');
                        $(statusWrapper).find('.bwfan-funnel-status').text('Published');
                        $(statusWrapper).find('.bwfan-status-toggle').text('Draft');

                    } else {
                        $(statusWrapper).find('.bwfan-tag-rounded').removeClass('clr-green').addClass('clr-orange');
                        $(statusWrapper).find('.bwfan-funnel-status').text('Draft');
                        $(statusWrapper).find('.bwfan-status-toggle').text('Publish');
                    }
                }
                wp_ajax.ajax('tp_toggle_state', toggle_data);
                wp_ajax.success = function () {
                    $('#modal-global-settings_success').iziModal('open');
                }

            });

            if ($('#modal-global-settings_success').length > 0) {
                $("#modal-global-settings_success").iziModal(
                    {
                        title: wftp.texts.settings_success,
                        icon: 'icon-check',
                        headerColor: '#6dbe45',
                        background: '#6dbe45',
                        borderBottom: false,
                        width: 600,
                        timeout: 4000,
                        timeoutProgressbar: true,
                        transitionIn: 'fadeInUp',
                        transitionOut: 'fadeOutDown',
                        bottom: 0,
                        loop: true,
                        pauseOnHover: true,
                        overlay: false
                    }
                );
            }

            var wftp_obj = this;

            /**
             * Trigger async event on plugin install success as we are execuring wp native js API to update/install a plugin
             */
            $(document).on('wp-plugin-install-success', function (event, response) {
                wftp_obj.main.afterInstall(event, response);
            });

        }

        getCustomSettingsSchema() {
            let self = this;
            let custom_settings_custom_css_fields = [{
                type: "textArea",
                label: "",
                model: "custom_css",
                inputName: 'custom_css',
            }];
            for (let keyfields in custom_settings_custom_css_fields) {
                let model = custom_settings_custom_css_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wftp.custom_setting_fields.fields, model)) {
                    $.extend(custom_settings_custom_css_fields[keyfields], wftp.custom_setting_fields.fields[model]);
                }
            }
            //handling of localized label/description coming from php to form fields in vue
            let custom_settings_custom_js_fields = [{
                type: "textArea",
                inputType: 'text',
                label: "",
                model: "custom_js",
                inputName: 'custom_js',
            }];
            for (let keyfields in custom_settings_custom_js_fields) {
                let model = custom_settings_custom_js_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wftp.custom_setting_fields.fields, model)) {
                    $.extend(custom_settings_custom_js_fields[keyfields], wftp.custom_setting_fields.fields[model]);
                }
            }

            let custom_redirect_fields = [
                {
                    type: 'radios',
                    label: "",
                    default: false,
                    model: 'custom_redirect',
                    styleClasses: ['wffn_field_space'],
                    values: () => {
                        return wftp.radio_fields;
                    },
                },
                {
                    type: "vueMultiSelect",
                    label: "",
                    model: "custom_redirect_page",
                    styleClasses: ['wffn_field_space multiselect_cs'],
                    required: true,
                    selectOptions: {
                        multiple: false,
                        key: "id",
                        label: "product",
                        onSearch: function (searchQuery) {
                            let query = searchQuery;
                            $('.multiselect_cs .multiselect__spinner').show();
                            let no_page = wftp.custom_options.not_found;
                            if ($(".multiselect_cs .multiselect__content li.no_found").length === 0) {
                                $(".multiselect_cs .multiselect__content").append('<li class="no_found"><span class="multiselect__option">' + no_page + '</span></li>');
                            }
                            $(".multiselect_cs .multiselect__content li.no_found").hide();

                            if (query !== "") {
                                clearTimeout(self.search_timeout);
                                self.search_timeout = setTimeout((query) => {
                                    let wp_ajax = new wp_admin_ajax();
                                    let product_query = {'term': query, '_nonce': wftp.nonce_page_search};
                                    wp_ajax.ajax('page_search', product_query);
                                    wp_ajax.success = (rsp) => {
                                        if (typeof rsp !== 'undefined' && rsp.length > 0) {
                                            wftp.custom_options.pages = rsp;
                                            $('.multiselect_cs .multiselect__spinner').hide();
                                        } else {
                                            $(".multiselect_cs .multiselect__content li:not(.multiselect__element)").hide();
                                            $(".multiselect_cs .multiselect__content li.no_found").show();
                                        }
                                    };
                                    wp_ajax.complete = () => {
                                        $('.multiselect_cs .multiselect__spinner').hide();
                                    };
                                }, 800, query);
                            } else {
                                $('.multiselect_cs .multiselect__spinner').hide();
                            }

                        }
                    },
                    onChanged: function (model, newVal) {
                        return model.custom_redirect_page = newVal
                    },
                    values: (model) => {
                        return model.pages;
                    },
                    visible: (model) => {
                        return (model.custom_redirect === 'true');
                    }
                },
            ];
            for (let keyfields in custom_redirect_fields) {
                let model = custom_redirect_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wftp.custom_setting_fields.fields, model)) {
                    $.extend(custom_redirect_fields[keyfields], wftp.custom_setting_fields.fields[model]);
                }
            }

            var settings = [
                {
                    legend: wftp.custom_setting_fields.legends_texts.custom_redirect,
                    fields: custom_redirect_fields
                },
                {
                    legend: wftp.custom_setting_fields.legends_texts.custom_css,
                    fields: custom_settings_custom_css_fields
                },
                {
                    legend: wftp.custom_setting_fields.legends_texts.custom_js,
                    fields: custom_settings_custom_js_fields
                },
            ];

            return wffnBuilderCommons.applyFilters('wffn_thankyou_settings_content', settings);

        }

        getShortCodeSettingsSchema() {
            /**
             * handling of localized label/description coming from php to form fields in vue
             */
            let general_fields = [
                {
                    type: "label",
                    label: "",
                    model: "main_head_gen",
                    styleClasses: ['wffn_main_design_heading'],
                },
                {
                    type: "label",
                    label: "",
                    model: "typography",
                    styleClasses: ['wffn_main_design_sub_heading'],
                },
                {
                    type: "select",
                    label: "",
                    model: "txt_fontfamily",
                    inputName: 'txt_fontfamily',
                    styleClasses: ['wffn_design_setting_50'],
                    selectOptions: {
                        hideNoneSelectedText: true,
                    },
                },
                {
                    type: "input",
                    inputType: 'color',
                    label: "",
                    model: "txt_color",
                    styleClasses: ['wffn_design_setting_50'],
                    inputName: 'txt_color',
                },
                {
                    type: "input",
                    inputType: 'number',
                    label: "",
                    model: "txt_font_size",
                    styleClasses: ['wffn_design_setting_50'],
                    inputName: 'txt_font_size',
                    hint: wftp.px_hint,
                },
                {
                    type: "input",
                    inputType: 'color',
                    label: "",
                    model: "head_color",
                    styleClasses: ['wffn_design_setting_50'],
                    inputName: 'head_color',
                },
                {
                    type: "input",
                    inputType: 'number',
                    label: "",
                    model: "head_font_size",
                    styleClasses: ['wffn_design_setting_50'],
                    inputName: 'head_font_size',
                    hint: wftp.px_hint,
                },
                {
                    type: "select",
                    label: "",
                    model: "head_font_weight",
                    styleClasses: ['wffn_design_setting_50'],
                    inputName: 'head_font_weight',
                    selectOptions: {
                        hideNoneSelectedText: true,
                    },
                },
                {
                    type: "label",
                    label: "",
                    model: "order_details_shortcode",
                    styleClasses: ['wffn_main_design_heading'],
                },
                {
                    type: 'switch',
                    label: "",
                    default: 'true',
                    model: 'order_details_img',
                    styleClasses: ['wffn_design_setting_50'],
                    textOn: wftp.switch_fields.true,
                    textOff: wftp.switch_fields.false,
                },
                {
                    type: "label",
                    label: "",
                    model: "order_downloads_shortcode",
                    styleClasses: ['wffn_main_design_sub_heading'],
                },
                {
                    type: "input",
                    inputType: 'text',
                    label: "",
                    model: "order_downloads_btn_text",
                    styleClasses: ['wffn_design_setting_50'],
                    inputName: 'order_downloads_btn_text',
                },
                {
                    type: 'switch',
                    label: "",
                    default: 'true',
                    model: 'order_downloads_show_file_downloads',
                    textOn: wftp.switch_fields.true,
                    textOff: wftp.switch_fields.false,
                    styleClasses: ['wffn_design_setting_50'],
                },
                {
                    type: 'switch',
                    label: "",
                    default: 'true',
                    textOn: wftp.switch_fields.true,
                    textOff: wftp.switch_fields.false,
                    model: 'order_downloads_show_file_expiry',
                    styleClasses: ['wffn_design_setting_50', 'clear_left'],
                },
                {
                    type: "label",
                    label: "",
                    model: "customer_details_shortcode",
                    styleClasses: ['wffn_main_design_heading'],
                },
                {
                    type: "select",
                    label: "",
                    model: "layout_settings",
                    styleClasses: ['wffn_design_setting_50'],
                    inputName: 'layout_settings',
                    selectOptions: {
                        hideNoneSelectedText: true,
                    },
                },
            ];
            let order_details_fields = [];
            let customer_details_fields = [];
            let downloads_details_fields = [];

            for (let keyfields in general_fields) {
                let model = general_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wftp.shortcode_fields.fields, model)) {
                    $.extend(general_fields[keyfields], wftp.shortcode_fields.fields[model]);
                }
            }
            for (let keyfields in order_details_fields) {
                let model = order_details_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wftp.shortcode_fields.fields, model)) {
                    $.extend(order_details_fields[keyfields], wftp.shortcode_fields.fields[model]);
                }
            }
            for (let keyfields in customer_details_fields) {
                let model = customer_details_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wftp.shortcode_fields.fields, model)) {
                    $.extend(customer_details_fields[keyfields], wftp.shortcode_fields.fields[model]);
                }
            }
            for (let keyfields in downloads_details_fields) {
                let model = downloads_details_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wftp.shortcode_fields.fields, model)) {
                    $.extend(downloads_details_fields[keyfields], wftp.shortcode_fields.fields[model]);
                }
            }

            return [
                {
                    fields: general_fields,
                },
            ]
        }

        /**
         * Updating thankyou starts
         */
        get_thankyou_vue_fields() {
            let update_thankyou = [
                {
                    type: "input",
                    inputType: "text",
                    label: "",
                    model: "title",
                    inputName: 'title',
                    featured: true,
                    required: true,
                    placeholder: "",
                    validator: ["string", "required"],
                }, {
                    type: "input",
                    inputType: "text",
                    label: "",
                    model: "slug",
                    inputName: 'slug',
                    featured: true,
                    required: true,
                    placeholder: "",
                    validator: ["string", "required"],
                },];

            for (let keyfields in update_thankyou) {
                let model = update_thankyou[keyfields].model;
                _.extend(update_thankyou[keyfields], wftp.update_popups.label_texts[model]);
            }
            return update_thankyou;
        }

        main() {
            let self = this;
            if (_.isUndefined(this.selected_type)) {
                return;
            }
            this.selected_template = this.designs[this.selected_type][this.selected];

            const wffnIZIDefault = {
                headerColor: '#6dbe45',
                background: '#efefef',
                borderBottom: false,
                width: 600,
                radius: 6,
                overlayColor: 'rgba(0, 0, 0, 0.35)',
                transitionIn: 'fadeInUp',
                transitionOut: 'fadeOut',
                navigateArrows: false,
                history: false,

            };

            var wfabtVueMixin = {
                data: {
                    is_initialized: '1',
                },
                methods: {
                    decodeHtml: function (html) {
                        var txt = document.createElement("textarea");
                        txt.innerHTML = html;
                        return txt.value;
                    },
                }
            };

            Vue.component(
                'tp_custom_settings',
                {
                    data: function () {
                        return {
                            model: wftp.custom_options,
                            countries: [],
                            search_timeout: false,
                            isLoading: "name",
                            schema: {
                                groups: self.getCustomSettingsSchema(),
                            },
                            formOptions: {
                                validateAfterLoad: false,
                                validateAfterChanged: true,
                            },
                        }
                    },
                    components: {
                        "vue-form-generator": VueFormGenerator.component,
                    },
                    template: '#tp_custom_settings_form',
                    methods: {
                        onSubmit: function () {
                            $(".wffn_save_btn_style").addClass('is_busy');
                            let tempSetting = JSON.stringify(this.model);
                            tempSetting = JSON.parse(tempSetting);
                            let data = {"data": tempSetting, 'thankyou_id': wftp.id, '_nonce': wftp.nonce_custom_settings};

                            let wp_ajax = new wp_admin_ajax();
                            wp_ajax.ajax("tp_custom_settings_update", data);
                            wp_ajax.success = function (rsp) {
                                if (typeof rsp === "string") {
                                    rsp = JSON.parse(rsp);
                                }
                                $('#modal-global-settings_success').iziModal('open');
                                $(".wffn_save_btn_style").removeClass('is_busy');
                            };
                            return false;
                        },

                    },
                }
            );

            this.main = new Vue({
                el: "#wffn_ty_edit_vue_wrap",
                components: {
                    "vue-form-generator": VueFormGenerator.component,
                },
                methods: {
                    copy: function (event) {
                        let title = wftp.texts.copy_success;
                        if (jQuery(event.target).attr('class') === 'wffn_copy_text scode') {
                            title = wftp.texts.shortcode_copy_success;
                        }
                        var getInput = event.target.parentNode.querySelector('.wffn-scode-copy input')
                        getInput.select();
                        document.execCommand("copy");
                        $("#modal-global-settings_success").iziModal('setTitle', title);
                        $("#modal-global-settings_success").iziModal('open');
                    },
                    get_edit_link() {
                        let url = wftp.design_template_data[this.selected_type].edit_url;
                        if ('oxy' === this.selected_type && this.selected === 'oxy_1') {
                            let wp_ajax = new wp_admin_ajax();
                            let data = {"url": url, 'id': wftp.id, '_nonce': wftp.nonce_update_edit_url};
                            wp_ajax.ajax('tp_update_edit_url', data);
                            wp_ajax.success = function (rsp) {
                                if (typeof rsp === "string") {
                                    rsp = JSON.parse(rsp);
                                }
                                if (true === rsp.status) {
                                    $('.wf_funnel_templates_action a.wf_edit_builder_link').attr('href', rsp.url);
                                }
                            };
                        }
                        return url;
                    },
                    hp: function (obj, key) {
                        let c = false;
                        if (typeof obj === "object" && key !== undefined) {
                            c = Object.prototype.hasOwnProperty.call(obj, key)
                        }
                        return c;
                    },
                    get_button_text() {
                        return wftp.design_template_data[this.selected_type].button_text;
                    },
                    get_builder_title() {
                        return wftp.design_template_data[this.selected_type].title;
                    },
                    show_template_dropdown: function (el) {
                        let elemParent = el.target.closest('.wffn_field_select_dropdown');
                        elemParent.classList.toggle('active');
                        elemParent.querySelector('.wffn_field_dropdown').classList.toggle('wffn-hide');
                    },
                    hide_tempate_dropdown: function () {
                        let templateWrap = document.querySelector('.wffn_template_editor');
                        if (null != templateWrap) {
                            templateWrap.querySelector('.wffn_field_select_dropdown').classList.remove('active');
                            templateWrap.querySelector('.wffn_field_dropdown').classList.add('wffn-hide');
                        }
                    },
                    setTemplateType(template_type) {
                        Vue.set(this, 'current_template_type', template_type);
                        this.hide_tempate_dropdown();
                    },
                    setTemplate(selected, type, el) {
                        Vue.set(this, 'selected', selected);
                        Vue.set(this, 'selected_type', type);
                        this.template_active = 'yes';
                        return this.save('yes', el);
                    },

                    removeDesign(cb) {
                        let wp_ajax = new wp_admin_ajax();
                        let save_layout = {
                            'wftp_id': self.id,
                            '_nonce': wftp.nonce_remove_design,
                        };
                        wp_ajax.ajax('tp_remove_design', save_layout);

                        wp_ajax.success = (rsp) => {
                            if (typeof cb == "function") {
                                cb(rsp);
                            }
                        };
                        wp_ajax.error = () => {

                        };
                    },
                    GetFirstTemplateGroup() {
                        return window.wffn.default_builder;
                    },
                    showFailedImport(warning_text) {
                        wffn_swal({
                            'html': warning_text,
                            'title': wffn.pageBuildersTexts[this.current_template_type].title,
                            'type': 'warning',
                            'allowEscapeKey': true,
                            'showCloseButton': true,
                            'showCancelButton': false,
                            'confirmButtonText': wffn.pageBuildersTexts[this.current_template_type].close_btn,
                            onClose: () => {
                                $(".wffn_import_template").removeClass('is_busy');
                                $(".wffn_template_sec").removeClass('wffn_template_importing');
                            }
                        });
                    },
                    importTemplate(template, type, current_target_element) {

                        let wp_ajax = new wp_admin_ajax();
                        let save_layout = {
                            'builder': type,
                            'template': template.slug,
                            'wftp_id': self.id,
                            'pro': template.pro ? template.pro : 'no',
                            '_nonce': wftp.nonce_import_design,
                        };
                        this.swalLoadiingText(wffn.i18n.importing);
                        wp_ajax.ajax('tp_import_design', save_layout);
                        wp_ajax.success = (rsp) => {
                            if (true === rsp.status) {
                                this.setTemplate(template.slug, type, current_target_element);
                            } else {
                                setTimeout((msg) => {
                                    this.showFailedImport(msg);
                                }, 200, rsp.error);
                            }
                        };
                    },
                    swalLoadiingText(text) {
                        if ($(".swal2-actions.swal2-loading .loading-text").length === 0) {
                            $(".swal2-actions.swal2-loading").append("<div class='loading-text'></div>");

                        }
                        $(".swal2-actions.swal2-loading .loading-text").text(text);
                    },
                    maybeInstallPlugin(template, type, cb) {

                        this.cb = cb;
                        let page_builder_plugins = wffn.pageBuildersOptions[this.current_template_type]['plugins'];
                        let pluginToInstall = 0;
                        var c = this;
                        $.each(page_builder_plugins, function (index, plugin) {
                            if ('install' === plugin.status) {
                                pluginToInstall++;
                                c.swalLoadiingText(wffn.i18n.plugin_install);
                                // Add each plugin activate request in Ajax queue.
                                // @see wp-admin/js/updates.js
                                window.wp.updates.queue.push({
                                    action: 'install-plugin', // Required action.
                                    data: {
                                        slug: plugin.slug
                                    }
                                });
                            }
                        });

                        // Required to set queue.
                        window.wp.updates.queueChecker();

                        let currentObj = this;
                        if (0 === pluginToInstall) {
                            $.each(page_builder_plugins, function (index, plugin) {
                                if ('activate' === plugin.status) {
                                    currentObj.activatePlugin(plugin.init);

                                }
                            });
                        }
                    },

                    afterInstall(event, response) {
                        let currentObj = this;
                        var page_builder_plugins = wffn.pageBuildersOptions[this.current_template_type]['plugins'];

                        $.each(page_builder_plugins, function (index, plugin) {
                            if ('install' === plugin.status && response.slug === plugin.slug) {
                                currentObj.activatePlugin(plugin.init);

                            }
                        });
                    },
                    activatePlugin(plugin_slug) {
                        this.swalLoadiingText(wffn.i18n.plugin_activate);
                        let currentObj = this;
                        $.ajax({
                            url: window.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'wffn_activate_plugin',
                                plugin_init: plugin_slug,
                                _nonce: wffn.nonce_activate_plugin
                            },
                        })
                            .done(function (rsp) {
                                _.delay(function () {
                                    currentObj.cb();
                                    if ( 'yes' === currentObj.templateOnReqest ) {
                                        currentObj.importTemplate(currentObj.templateOnReqest, currentObj.typeOnRequest, currentObj.CurrenttargetElem);

                                    } else {
                                        currentObj.setTemplate(currentObj.slugOnRequest, currentObj.typeOnRequest, currentObj.CurrenttargetElem);
                                    }
                                    var page_builder_plugins = wffn.pageBuildersOptions[currentObj.current_template_type].plugins;
                                    $.each(page_builder_plugins, function (index, plugin) {
                                        if (plugin.init === rsp.data.init) {
                                            if ('install' === plugin.status || 'activate' === plugin.status) {
                                                wffn.pageBuildersOptions[currentObj.current_template_type].plugins[index].status = null;
                                            }
                                        }
                                    });
                                }, 500);
                            });
                    },
                    get_remove_template() {
                        wffn_swal({
                            'type': '',
                            'allowEscapeKey': false,
                            'showCancelButton': true,
                            'icon': wffn.icons.success_check,
                            'cancelButtonColor': '#e33b3b',
                            'confirmButtonColor': '#0073aa',
                            'confirmButtonText': wftp_localization.importer.remove_template.button_text,
                            'html': `<div class="wf_delete_modal_content">${wftp_localization.importer.remove_template.sub_heading}</div>`,
                            'showLoaderOnConfirm': false,
                            'reverseButtons': true,
                            'showCloseButton': true,
                            'preConfirm': () => {
                                $('button.swal2-cancel.swal2-styled').removeAttr('disabled');
                                return new Promise((resolve) => {
                                    this.removeDesign((rsp) => {
                                        this.template_active = 'no';
                                        resolve(rsp);
                                    });
                                });
                            },
                            onOpen: () => {
                                const swalModalTitle = document.querySelector('.swal2-header');
                                swalModalTitle.insertAdjacentHTML('beforebegin', '<div class="wffn-swal-modal-title">' + wftp_localization.importer.remove_template.modal_title + '</div>');
                            },
                        });
                    },
                    triggerPreview(template, slug, type) {
                        this.is_previewing = {'slug': slug, 'type': type};
                        $('body').addClass('hide_bscroll');
                        setTimeout(function () {
                            $('.wffn_global_loader').hide();
                        }, 600);
                        setTimeout(function () {
                            $(".wffn_template_preview_sidebar .wffn_template_page_options").removeClass('active_preview');
                            $(".wffn_template_preview_sidebar .wffn_template_page_options[ pre_slug=" + slug + "]").addClass('active_preview');
                            var scrollTo = $(".wffn_template_page_options.active_preview");
                            var container = $('.wffn_template_preview_sidebar');
                            var position = scrollTo.offset().top - container.offset().top + container.scrollTop();
                            container.animate({scrollTop: position});
                        }, 100);
                    },
                    previewClosed() {
                        this.is_previewing = false;
                        $('body').removeClass('hide_bscroll');
                    },
                    setViewport(view_cl, el) {
                        let setClass = $(el.currentTarget);
                        $('.wffn_template_viewport .wffn_viewport_icons').removeClass('active');
                        $(setClass).addClass('active');
                        $('.wffn_template_preview_wrap .wffn_template_preview_frame').removeClass('desktop tablet mobile');
                        $('.wffn_template_preview_wrap .wffn_template_preview_frame').addClass(view_cl);
                    },
                    ShouldPreview(slug, type) {
                        if (false === this.is_previewing) {
                            return false;
                        }

                        if (this.is_previewing.slug !== slug) {
                            return false;
                        }

                        if (this.is_previewing.type !== type) {
                            return false;
                        }
                        return true;
                    },
                    getPreviewUrl(prevslug, activeEditor) {
                        if ('oxy' === activeEditor) {
                            activeEditor = 'oxygen';
                        }

                        return 'https://templates-'+activeEditor+'.funnelswp.com/order-confirmed/' + prevslug + '/';

                    },
                    triggerImport(template, slug, type, el) {
                        this.templateOnReqest = template;
                        this.slugOnRequest = slug;
                        this.typeOnRequest = type;

                        $('.wffn_steps_btn_green').hide();
                        let current_target_element = $(el.target);
                        let importBtn = $(el.currentTarget);
                        current_target_element.closest('.wffn_temp_middle_align, .wffn-preview-overlay').find('.wffn_import_template').show();
                        if (current_target_element.closest('.wffn_template_sec').hasClass('wffn_template_importing')) {
                            this.previewClosed();
                            console.log('Importer already running');
                            return;
                        }
                        let parent = current_target_element.closest('.wffn_template_sec');
                        parent.addClass('wffn_template_importing');
                        this.CurrenttargetElem = current_target_element;

                        if ('gutenberg' === type && 'gutenberg_1' === slug) {
                            this.setTemplate(slug, type, current_target_element);
                            return;
                        }
                        /**
                         * Loop over the plugin dependency for the every page builder
                         * If we found any dependency plugin inactive Or not installed we need to hold back the import process and
                         * Alert user about missing dependency and futher proccess to install and activate
                         */
                        var page_builder_plugins = wffn.pageBuildersOptions[this.current_template_type]['plugins'];

                        var anyPluginInactive = true;

                        $.each(page_builder_plugins, function (index, plugin) {

                            if (anyPluginInactive) {
                                if ('install' === plugin.status || 'activate' === plugin.status) {
                                    anyPluginInactive = false;
                                }
                            }
                        });
                        if (false === anyPluginInactive) {
                            let showConfirmBtn = true;
                            if ('no' === wffn.pageBuildersTexts[this.current_template_type].show_cancel_btn) {
                                showConfirmBtn = false;
                            }
                            wffn_swal({
                                'type': '',
                                'allowEscapeKey': false,
                                'showConfirmButton': showConfirmBtn,
                                'confirmButtonText': wffn.pageBuildersTexts[this.current_template_type].ButtonText,
                                'showCancelButton': showConfirmBtn,
                                'cancelButtonText': wffn.pageBuildersTexts[this.current_template_type].close_btn,
                                'allowOutsideClick': false,
                                'cancelButtonColor': '#e33b3b',
                                'reverseButtons': true,
                                'showLoaderOnConfirm': false,
                                'showCloseButton': true,
                                'html': `<div class="wf_delete_modal_content">${wffn.pageBuildersTexts[this.current_template_type].text}</div>`,
                                onOpen: (e) => {
                                    if( e.closest('.swal2-container') ) {
                                        e.closest('.swal2-container').classList.add('bwf-modal-zindex');
                                    }
                                    const swalModalTitle = document.querySelector('.swal2-header');
                                    swalModalTitle.insertAdjacentHTML('beforebegin', '<div class="wffn-swal-modal-title">' + wffn.pageBuildersTexts[this.current_template_type].title + '</div>');
                                },
                                'preConfirm': () => {
                                    let self = this;
                                    if ('no' === wffn.pageBuildersTexts[this.current_template_type].noInstall) {
                                        $('button.swal2-cancel.swal2-styled').css({'display': 'none'});
                                        return new Promise((resolve) => {
                                            this.maybeInstallPlugin(template, type, resolve);
                                        })
                                    }
                                    if( 'yes' === wffn.pageBuildersTexts[this.current_template_type].noInstall && 'install' === wffn.pageBuildersTexts[this.current_template_type].plugin_status ){
                                        self.restoreButtonState(current_target_element, false);
                                        window.open( wffn.pageBuildersTexts[this.current_template_type].builder_link, '_blank' );
                                    }
                                },
                                onClose: () => {
                                    let self = this;
                                    $(document).on("click", function (event) {
                                        if ('swal2-cancel swal2-styled' === event.target.className || 'swal2-close' === event.target.className) {
                                            self.restoreButtonState(current_target_element, false);
                                        }
                                    });
                                }
                            });
                            return;
                        }
                        importBtn.addClass( 'is_busy' );

                        if (template.hasOwnProperty('build_from_scratch') && true === template.build_from_scratch) {
                            this.setTemplate(slug, type, current_target_element);
                        } else {
                            this.importTemplate(template, type, current_target_element);
                        }

                    },
                    restoreButtonState: function (elem, state = true) {
                        let parent = elem.closest('.wffn_template_sec');
                        parent.removeClass('wffn_template_importing');
                        $('.wffn_steps_btn_green').show();
                        if (state === true) {
                            this.template_active = 'yes';
                        }
                        if( elem && elem.hasClass('wfacp_import_template') ) {
                            elem.removeClass( 'is_busy' );
                        } else if (elem && elem.parent('.wfacp_import_template').length ) {
                            elem.removeClass( 'is_busy' );
                        }
                    },
                    save(template_active = 'yes', cb = '') {
                        let inst = this;
                        let wp_ajax = new wp_admin_ajax();
                        let save_layout = {
                            'selected_type': this.current_template_type,
                            'selected': this.selected,
                            'wftp_id': self.id,
                            'template_active': template_active,
                            '_nonce': wftp.nonce_save_design,
                        };

                        wp_ajax.ajax('tp_save_design', save_layout);
                        wp_ajax.success = (rsp) => {
                            this.selected_type = this.current_template_type;
                            this.selected_template = this.designs[this.selected_type][this.selected];
                            $('#wfacp_control > .wfacp_p20').show();
                            inst.previewClosed();
                            if (typeof cb == "function") {
                                cb(rsp);
                            }
                            this.scrollToTop();
                        };
                        wp_ajax.error = () => {
                            inst.previewClosed();
                        };
                    },
                    updateThankyou: function () {
                        let thankyou_edit = "#wf_thankyou_edit_modal";
                        let parsedData = _.extend({}, wffnIZIDefault, {});
                        $(thankyou_edit).iziModal(
                            _.extend(
                                {
                                    onOpening: function (modal) {
                                        wftp_edit_thankyou_vue(modal);
                                    },
                                    onClosed: function () {
                                        //self.wffn_popups_vue.$destroy();
                                        $(thankyou_edit).iziModal('resetContent');
                                        $(thankyou_edit).iziModal('destroy');
                                        $(thankyou_edit).iziModal('close');
                                    },
                                },
                                parsedData
                            ),
                        );
                        $(thankyou_edit).iziModal('open');
                    },
                    onSubmitShortCodeForm: function () {
                        $(".wffn_save_btn_style").addClass('disabled');
                        $('.wffn_loader_global_save').addClass('ajax_loader_show');

                        let tempSetting = JSON.stringify(this.model);
                        tempSetting = JSON.parse(tempSetting);
                        let data = {"data": tempSetting, '_nonce': wftp.nonce_shortcode_settings, "id": wftp.id};

                        let wp_ajax = new wp_admin_ajax();
                        wp_ajax.ajax("tp_shortcode_settings_update", data);
                        wp_ajax.success = function (rsp) {
                            if (typeof rsp === "string") {
                                rsp = JSON.parse(rsp);
                            }
                            $('#modal-global-settings_success').iziModal('open');
                            $(".wffn_save_btn_style").removeClass('disabled');
                            $('.wffn_loader_global_save').removeClass('ajax_loader_show');
                        };
                        return false;
                    },
                    scrollToTop: function () {
                        if (_.size($('#wffn-customizer-design')) > 0) {
                            $('html, body').animate({scrollTop: $('#wffn-customizer-design').offset().top - 20}, 500);
                        }
                        return false;
                    },
                    initializeColopicker(v) {
                        $('.wffn_color_pick input').wpColorPicker(
                            {
                                change: function (event, ui) {
                                    var element = event.target;
                                    var name = element.name;
                                    v.model[name] = ui.color.toString();
                                },
                                clear: function (event) {

                                    let picker = $(event.target).parent().find('input.wp-color-picker');
                                    if (typeof picker == 'undefined' || picker.length === 0) {
                                        return;
                                    }
                                    var name = picker.get(0).name;
                                    v.model[name] = '';
                                }
                            }
                        );
                        $(document).on('click', function () {
                            if ($('.iris-picker:visible').length > 0) {
                                $('.iris-picker').hide();
                            }
                        });
                    },
                },

                mounted: function () {
                    if (this.template_active === 'no') {
                        this.setTemplateType(this.GetFirstTemplateGroup());
                    }
                    this.scrollToTop();
                    var cb = this;
                    this.initializeColopicker(cb);

                    let self = this;
                    $(document.body).click(function (e) {
                        if ($(e.target).attr('class') !== 'wffn_editor_label wffn_field_select_label' && $(e.target).parents().attr('class') !== 'wffn_editor_label wffn_field_select_label') {
                            if ($(e.target).attr('class') !== 'wffn_dropdown_header_label' && $('.wffn_field_select_dropdown').hasClass("active")) {
                                self.hide_tempate_dropdown();
                            }
                        }
                    });
                },
                created: function () {
                    setTimeout(() => {
                        $('.wfty_design_accordion').attr('status', 'close');
                        $('.wfty_design_accordion').find('.form-group').not('.wfty_main_design_heading').slideUp();
                    }, 200);
                },
                watch: {
                    selected_template: function () {
                        let v = this;
                        setTimeout(function () {
                            v.initializeColopicker(v);
                        }, 500);

                    }

                },
                data: {
                    current_template_type: this.selected_type,
                    selected_type: this.selected_type,
                    designs: this.designs,
                    design_types: this.design_types,
                    selected: this.selected,
                    selected_template: this.selected_template,
                    template_active: this.template_active,
                    temp_template_type: '',
                    temp_template_slug: '',
                    view_url: wftp.view_url,
                    ty_title: wftp.ty_title,
                    cb: null,
                    model: wftp.optionsShortCode,
                    is_previewing: false,
                    schema: {
                        groups: wftp.schema,
                    },
                    formOptions: {
                        validateAfterLoad: false,
                        validateAfterChanged: true
                    },
                }
            });

            //Update thankyou page
            /** wftp_edit_thankyou_vue started  **/
            const wftp_edit_thankyou_vue = function (iziMod) {
                self.wftp_edit_thankyou_vue = new Vue(
                    {
                        mixins: [wfabtVueMixin],
                        components: {
                            "vue-form-generator": VueFormGenerator.component,
                        },
                        data: {
                            modal: false,
                            model: wftp.update_popups.values,
                            schema: {
                                fields: self.get_thankyou_vue_fields(),
                            },
                            formOptions: {
                                validateAfterLoad: false,
                                validateAfterChanged: true,
                            },
                            current_state: 1,
                        },
                        methods: {
                            updateThankyou: function () {
                                if (false === this.$refs.update_thankyou_ref.validate()) {
                                    console.log('Validation Error');
                                    return;
                                }

                                let thankyou_id = wftp.id;
                                let data = JSON.stringify(self.wftp_edit_thankyou_vue.model);

                                let wp_ajax = new wp_admin_ajax();
                                if (self.wftp_edit_thankyou_vue.$el) {
                                    let submitButton = self.wftp_edit_thankyou_vue.$el.querySelector('.wf_funnel_btn.wf_funnel_btn_primary');
                                    $(submitButton).addClass('is_busy');
                                }

                                wp_ajax.ajax("update_thankyou_page", {"_nonce": wftp.wftp_edit_nonce, "thankyou_id": thankyou_id, 'data': data});
                                wp_ajax.success = function (rsp) {
                                    if (_.isEqual(true, rsp.status)) {
                                        $(".bwf_breadcrumb ul li:last-child").html(rsp.title);
                                        $(".bwf_breadcrumb > span:last-of-type").html(rsp.title);
                                        $('.bwfan_page_header .bwfan_page_title').html(rsp.title);
                                        $(".bwf-header-bar .bwf-bar-navigation > span:last-child").html(rsp.title);
                                        Vue.set(self.wftp_edit_thankyou_vue.model, 'title', rsp.title);
                                        wftp.update_popups.label_texts.title.value = rsp.title;
                                        $('.wffn_page_title').text(rsp.title);

                                        $('#modal-global-settings_success').iziModal('open');
                                        $('#wf_thankyou_edit_modal').iziModal('close');

                                    }
                                };
                            }
                        },
                    }
                ).$mount('#part-update-thankyou');
            };
            if ($(".wffn-widget-tabs").length > 0) {
                let wfctb = $('.wffn-widget-tabs .wffn-tab-title');
                wfctb.on(
                    'click', function (event) {
                        if ($(event.target).hasClass('hide_bwf_btn')) {
                            $(this).parents('.wffn-widget-tabs').addClass("hide_bwf");
                        } else {
                            $('.wffn-widget-tabs').removeClass("hide_bwf");
                        }
                        let $this = $(this).closest('.wffn-widget-tabs');
                        let tabindex = $(this).attr('data-tab');

                        $this.find('.wffn-tab-title').removeClass('wffn-active');

                        $this.find('.wffn-tab-title[data-tab=' + tabindex + ']').addClass('wffn-active');

                        $($this).find('.wffn-tab-content').removeClass('wffn-activeTab');
                        $($this).find('.wffn_forms_global_settings .vue-form-generator fieldset').hide();
                        $($this).find('.wffn_forms_global_settings .vue-form-generator fieldset').eq(tabindex - 1).addClass('wffn-activeTab');

                        $($this).find('.wffn_forms_global_settings .vue-form-generator fieldset').eq(tabindex - 1).show();


                    }
                );

                wfctb.eq(0).trigger('click');
            }

            $(document.body).on('click', '.wfty_design_accordion .wfty_main_design_heading', function () {
                let status = $(this).attr('status');
                $(this).parent('.wfty_design_accordion').removeClass('wfty_accordion_open');
                if ('close' === status || undefined === status) {
                    $(this).parent('.wfty_design_accordion').find('.form-group').slideDown();
                    $(this).parent('.wfty_design_accordion').attr('status', 'open');
                    $(this).attr('status', 'open');
                    $(this).parent('.wfty_design_accordion').addClass('wfty_accordion_open');
                } else if ('open' === status) {
                    $(this).parent('.wfty_design_accordion').find('.form-group').not('.wfty_main_design_heading').slideUp();
                    $(this).attr('status', 'close');
                    $(this).parent('.wfty_design_accordion').attr('status', 'close');
                }
            });
            return this.main;
        }


    }

    function rules_builder() {
        $(".wfty_funnel_rule_add_settings").on("click", function () {
            $("#wfty_funnel_rule_add_settings").attr("data-is_rules_saved", "yes");
            $("#wfty_funnel_rule_settings").removeClass('wfty-tgl');
            $("#wfty_funnel_rule_settings").attr("data-is_rules_saved", "yes");
        });
        if ($('#modal-rules-settings_success').length > 0) {
            $("#modal-rules-settings_success").iziModal({
                    title: wftp.texts.settings_success,
                    icon: 'icon-check',
                    headerColor: '#f9fdff',
                    background: '#f9fdff',
                    borderBottom: false,
                    width: 600,
                    timeout: 1500,
                    timeoutProgressbar: true,
                    transitionIn: 'fadeInUp',
                    transitionOut: 'fadeOutDown',
                    bottom: 0,
                    loop: true,
                    pauseOnHover: true,
                    overlay: false
                }
            );
        }
        $('#wfty_settings_location').change(function () {
            if ($(this).val() == 'custom:custom') {
                $('.wfty-settings-custom').show();
            } else {
                $('.wfty-settings-custom').hide();
            }
        });

        $('.wfty_save_funnel_rules').on('click', function () {
            let data = {"data": $('.wfty_rules_form').serialize()};
            data.action = 'wfty_save_rules_settings';
            data._nonce = wftp.nonce_rules;

            $(".wfty_save_funnel_rules").addClass('is_busy');

            $.post(window.ajaxurl, data, function () {
                $(".wfty_save_funnel_rules").removeClass('is_busy');
                $('#modal-global-settings_success').iziModal('open');
                $(document).trigger('wfty_rules_updated');
            });

            return false;
        });

        // Ajax Chosen Product Selectors
        var bind_ajax_chosen = function () {
            $(".wfty-date-picker-field").datepicker({
                dateFormat: "yy-mm-dd",
                numberOfMonths: 1,
                showButtonPanel: true,
                beforeShow: function (input, inst) {
                    $(inst.dpDiv).addClass('xl-datepickers');
                }
            });
            $('select.chosen_select').xlChosen();


            $("select.ajax_chosen_select_products").xlAjaxChosen({
                method: 'GET',
                url: window.ajaxurl,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    action: 'woocommerce_json_search_products_and_variations',
                    security: wftp.search_products_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });

            $("select.ajax_chosen_select_users").xlAjaxChosen({
                method: 'GET',
                url: window.ajaxurl,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    action: 'woocommerce_json_search_customers',
                    security: wftp.nonce_rules
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });

            $("select.ajax_chosen_select_coupons").xlAjaxChosen({
                method: 'GET',
                url: window.ajaxurl,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    action: 'wfty_rule_json_search_coupons',
                    security: wftp.search_coupons_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });


            $("select.ajax_chosen_select").each(function (element) {
                $(element).xlAjaxChosen({
                    method: 'GET',
                    url: window.ajaxurl,
                    dataType: 'json',
                    afterTypeDelay: 100,
                    data: {
                        action: 'wfty_json_search',
                        method: $(element).data('method'),
                        security: wftp.nonce_rules
                    }
                }, function (data) {

                    var terms = {};

                    $.each(data, function (i, val) {
                        terms[i] = val;
                    });

                    return terms;
                });
            });

        };

        bind_ajax_chosen();

        //Note - this section will eventually be refactored into the backbone views themselves.  For now, this is more efficent.
        $('.wfty_rules_common').on('change', 'select.rule_type', function () {


            // vars
            var tr = $(this).closest('tr');
            var rule_id = tr.data('ruleid');
            var group_id = tr.closest('table').data('groupid');

            var ajax_data = {
                action: "wfty_change_rule_type",
                security: wftp.nonce_rules,
                rule_category: $(this).parents(".wfty-rules-builder").eq(0).attr('data-category'),
                group_id: group_id,
                rule_id: rule_id,
                rule_type: $(this).val()
            };

            tr.find('td.condition').html('').remove();
            tr.find('td.operator').html('').remove();

            tr.find('td.loading').show();
            tr.find('td.rule-type select').prop("disabled", true);
            // load location html
            $.ajax({
                url: window.ajaxurl,
                data: ajax_data,
                type: 'post',
                dataType: 'html',
                success: function (html) {
                    tr.find('td.loading').hide().before(html);
                    tr.find('td.rule-type select').prop("disabled", false);
                    bind_ajax_chosen();
                }
            });
        });

        //Backbone views to manage the UX.
        var wfty_Rule_Builder = Backbone.View.extend({
            groupCount: 0,
            el: '.wfty-rules-builder[data-category="basic"]',
            events: {
                'click .wfty-add-rule-group': 'addRuleGroup',
            },
            render: function () {

                this.$target = this.$('.wfty-rule-group-target');
                this.category = 'basic';
                wfty_app.Events.bind('wfty:remove-rule-group', this.removeRuleGroup, this);

                this.views = {};
                var groups = this.$('div.wfty-rule-group-container');
                _.each(groups, function (group) {
                    this.groupCount++;
                    var id = $(group).data('groupid');
                    var view = new wfty_Rule_Group({
                        el: group,
                        model: new Backbone.Model({
                            groupId: id,
                            groupCount: this.groupCount,
                            headerText: this.groupCount > 1 ? wftp_localization.text_or : wftp_localization.text_apply_when,
                            removeText: wftp_localization.remove_text,
                            category: this.category,
                        })
                    });

                    this.views[id] = view;
                    view.bind('wfty:remove-rule-group', this.removeRuleGroup, this);

                }, this);

                if (this.groupCount > 0) {
                    $('.rules_or').show();
                }
            },
            addRuleGroup: function (event) {
                event.preventDefault();

                var newId = 'group' + wfty_app.Helpers.uniqid();
                this.groupCount++;

                var view = new wfty_Rule_Group({
                    model: new Backbone.Model({
                        groupId: newId,
                        groupCount: this.groupCount,
                        headerText: this.groupCount > 1 ? wftp_localization.text_or : wftp_localization.text_apply_when,
                        removeText: wftp_localization.remove_text,
                        category: this.category,
                    })
                });

                this.$target.append(view.render().el);
                this.views[newId] = view;

                view.bind('wfty:remove-rule-group', this.removeRuleGroup, this);

                if (this.groupCount > 0) {
                    $('.rules_or').show();
                }

                bind_ajax_chosen();

                return false;
            },
            removeRuleGroup: function (sender) {

                delete (this.views[sender.model.get('groupId')]);
                sender.remove();
            }
        });

        //Backbone views to manage the UX.
        var wfty_Rule_Builder2 = Backbone.View.extend({
            groupCount: 0,
            el: '.wfty-rules-builder[data-category="product"]',
            events: {
                'click .wfty-add-rule-group': 'addRuleGroup',
            },
            render: function () {

                this.$target = this.$('.wfty-rule-group-target');
                this.category = 'product';
                wfty_app.Events.bind('wfty:remove-rule-group', this.removeRuleGroup, this);

                this.views = {};
                var groups = this.$('div.wfty-rule-group-container');
                _.each(groups, function (group) {
                    this.groupCount++;
                    var id = $(group).data('groupid');
                    var view = new wfty_Rule_Group(
                        {
                            el: group,
                            model: new Backbone.Model(
                                {
                                    groupId: id,
                                    groupCount: this.groupCount,
                                    headerText: this.groupCount > 1 ? wftp_localization.text_or : wftp_localization.text_apply_when,
                                    removeText: wftp_localization.remove_text,
                                    category: this.category,
                                })
                        });

                    this.views[id] = view;
                    view.bind('wfty:remove-rule-group', this.removeRuleGroup, this);

                }, this);

                if (this.groupCount > 0) {
                    $('.rules_or').show();
                }
            },
            addRuleGroup: function (event) {
                event.preventDefault();

                var newId = 'group' + wfty_app.Helpers.uniqid();
                this.groupCount++;

                var view = new wfty_Rule_Group({
                    model: new Backbone.Model({
                        groupId: newId,
                        groupCount: this.groupCount,
                        headerText: this.groupCount > 1 ? wftp_localization.text_or : wftp_localization.text_apply_when,
                        removeText: wftp_localization.remove_text,
                        category: this.category,
                    })
                });

                this.$target.append(view.render().el);
                this.views[newId] = view;

                view.bind('wfty:remove-rule-group', this.removeRuleGroup, this);

                if (this.groupCount > 0) {
                    $('.rules_or').show();
                }

                bind_ajax_chosen();

                return false;
            },
            removeRuleGroup: function (sender) {

                delete (this.views[sender.model.get('groupId')]);
                sender.remove();
            }
        });

        var wfty_Rule_Group = Backbone.View.extend({
            tagName: 'div',
            className: 'wfty-rule-group-container',
            template: _.template('<div class="wfty-rule-group-header"><h4 class="rules_or"><%= headerText %></h4><a href="#" class="wfty-remove-rule-group button"><%= removeText %></a></div><table class="wfty-rules" data-groupid="<%= groupId %>"><tbody></tbody></table>'),
            events: {
                'click .wfty-remove-rule-group': 'onRemoveGroupClick'
            },
            initialize: function () {
                this.views = {};
                this.$rows = this.$el.find('table.wfty-rules tbody');

                var rules = this.$('tr.wfty-rule');
                _.each(rules, function (rule) {
                    var id = $(rule).data('ruleid');
                    var view = new wfty_Rule_Item(
                        {
                            el: rule,
                            model: new Backbone.Model({
                                groupId: this.model.get('groupId'),
                                ruleId: id,
                                category: this.model.get('category'),
                            })
                        });

                    view.delegateEvents();

                    view.bind('wfty:add-rule', this.onAddRule, this);
                    view.bind('wfty:remove-rule', this.onRemoveRule, this);

                    this.views.ruleId = view;

                }, this);
            },
            render: function () {

                this.$el.html(this.template(this.model.toJSON()));

                this.$rows = this.$el.find('table.wfty-rules tbody');
                this.$el.attr('data-groupid', this.model.get('groupId'));

                this.onAddRule(null);

                return this;
            },
            onAddRule: function (sender) {
                var newId = 'rule' + wfty_app.Helpers.uniqid();

                var view = new wfty_Rule_Item({
                    model: new Backbone.Model({
                        groupId: this.model.get('groupId'),
                        ruleId: newId,
                        category: this.model.get('category')
                    })
                });

                if (sender == null) {
                    this.$rows.append(view.render().el);
                } else {
                    sender.$el.after(view.render().el);
                }
                view.bind('wfty:add-rule', this.onAddRule, this);
                view.bind('wfty:remove-rule', this.onRemoveRule, this);

                bind_ajax_chosen();

                this.views.ruleId = view;
            },
            onRemoveRule: function (sender) {

                var ruleId = sender.model.get('ruleId');
                const cat = sender.model.get('category');
                var countRules = $(".wfty-rules-builder[data-category='" + cat + "'] .wfty_rules_common .wfty-rule-group-container table tr.wfty-rule").length;

                if (countRules == 1) {
                    var selectedNull = 'general_always';
                    if ('product' === cat) {
                        selectedNull = 'general_always_2';
                    }
                    $(".wfty-rules-builder[data-category='" + cat + "'] .wfty_rules_common .wfty-rule-group-container table tr.wfty-rule .rule_type").val(selectedNull).trigger('change');

                    return;
                }
                delete (this.views[ruleId]);
                sender.remove();


                if ($("table[data-groupid='" + this.model.get('groupId') + "'] tbody tr").length == 0) {
                    wfty_app.Events.trigger('wfty:removing-rule-group', this);

                    this.trigger('wfty:remove-rule-group', this);
                }
            },
            onRemoveGroupClick: function (event) {
                event.preventDefault();
                wfty_app.Events.trigger('wfty:removing-rule-group', this);
                this.trigger('wfty:remove-rule-group', this);
                return false;
            }
        });

        var wfty_Rule_Item = Backbone.View.extend({
            tagName: 'tr',
            className: 'wfty-rule',
            events: {
                'click .wfty-add-rule': 'onAddClick',
                'click .wfty-remove-rule': 'onRemoveClick'
            },
            render: function () {
                const base = this.model.get('category');

                const html = $('#wfty-rule-template-' + base).html();
                const template = _.template(html);
                this.$el.html(template(this.model.toJSON()));
                this.$el.attr('data-ruleid', this.model.get('ruleId'));
                return this;
            },
            onAddClick: function (event) {
                event.preventDefault();

                wfty_app.Events.trigger('wfty:adding-rule', this);
                this.trigger('wfty:add-rule', this);

                return false;
            },
            onRemoveClick: function (event) {
                event.preventDefault();

                wfty_app.Events.trigger('wfty:removing-rule', this);
                this.trigger('wfty:remove-rule', this);

                return false;
            }
        });

        var ruleBuilder = new wfty_Rule_Builder();
        ruleBuilder.render();
        var ruleBuilder2 = new wfty_Rule_Builder2();
        ruleBuilder2.render();


    }

    window.addEventListener('load', () => {
        window.dispatchEvent(new Event('wffn_thankyou_admin_event_loaded'));
        window.wftp_design = new wftp_design();
        rules_builder();
    });
})(jQuery);


