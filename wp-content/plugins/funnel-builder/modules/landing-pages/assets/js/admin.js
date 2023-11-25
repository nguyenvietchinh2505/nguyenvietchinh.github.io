/*global wflp*/
/*global Vue*/
/*global VueFormGenerator*/
/*global wp_admin_ajax*/
/*global wffn_swal*/
/*global wflp_localization*/
/*global _*/
/*global wffn*/
(function ($) {
    'use strict';

    class wflp_design {
        constructor() {

            this.id = wflp.id;
            this.selected = wflp.selected;
            this.selected_type = wflp.selected_type;
            this.designs = wflp.designs;
            this.design_types = wflp.design_types;
            this.template_active = wflp.template_active;
            this.main();

            $("#wffn_lp_edit_vue_wrap .wf_funnel_card_switch input[type='checkbox']").click(function () {
                let wp_ajax = new wp_admin_ajax();
                let toggle_data = {
                    'toggle_state': this.checked,
                    'wflp_id': wflp.id,
                    '_nonce': wflp.nonce_toggle_state,
                };

                let statusWrapper = this.closest('.wffn-ellipsis-menu');
                if( statusWrapper ) {
                    if( this.checked ) {
                        $(statusWrapper).find('.bwfan-tag-rounded').removeClass('clr-orange').addClass('clr-green');
                        $(statusWrapper).find('.bwfan-funnel-status').text('Published');
                        $(statusWrapper).find('.bwfan-status-toggle').text('Draft');
                        
                    } else {
                        $(statusWrapper).find('.bwfan-tag-rounded').removeClass('clr-green').addClass('clr-orange');
                        $(statusWrapper).find('.bwfan-funnel-status').text('Draft');
                        $(statusWrapper).find('.bwfan-status-toggle').text('Publish');
                    }
                }

                wp_ajax.ajax('lp_toggle_state', toggle_data);
                wp_ajax.success = function() {
                    $('#modal-global-settings_success').iziModal('open');
                }

            });
            if ($('#modal-global-settings_success').length > 0) {

                $("#modal-global-settings_success").iziModal(
                    {
                        title: wflp.texts.settings_success,
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

            var wflp_obj = this;

            /**
             * Trigger async event on plugin install success as we are executing wp native js API to update/install a plugin
             */
            $(document).on('wp-plugin-install-success', function (event, response) {
                wflp_obj.main.afterInstall(event, response);
            });
            $(document).on('wp-plugin-install-error', function (event, response) {
                wflp_obj.main.afterInstallError(event, response);
            });
        }

        getCustomSettingsSchema() {
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
                if (Object.prototype.hasOwnProperty.call(wflp.custom_setting_fields.fields, model)) {
                    $.extend(custom_settings_custom_js_fields[keyfields], wflp.custom_setting_fields.fields[model]);
                }
            }
            let custom_settings_custom_css_fields = [{
                type: "textArea",
                label: "",
                model: "custom_css",
                inputName: 'custom_css',
            }];
            for (let keyfields in custom_settings_custom_css_fields) {
                let model = custom_settings_custom_css_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wflp.custom_setting_fields.fields, model)) {
                    $.extend(custom_settings_custom_css_fields[keyfields], wflp.custom_setting_fields.fields[model]);
                }
            }

            var settings = [
                {
                    legend: wflp.custom_setting_fields.legends_texts.custom_css,
                    fields: custom_settings_custom_css_fields
                },
                {
                    legend: wflp.custom_setting_fields.legends_texts.custom_js,
                    fields: custom_settings_custom_js_fields
                },
            ];

            return wffnBuilderCommons.applyFilters('wffn_landing_settings_content', settings);
        }

        /**
         * Updating landing starts
         */
        get_landing_vue_fields() {
            let update_landing = [
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

            for (let keyfields in update_landing) {
                let model = update_landing[keyfields].model;
                _.extend(update_landing[keyfields], wflp.update_popups.label_texts[model]);
            }
            return update_landing;
        }

        main() {
            let self = this;
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

            if (_.isUndefined(this.selected_type)) {
                return;
            }

            this.selected_template = this.designs[this.selected_type][this.selected];

            this.main = new Vue({
                el: "#wffn_lp_edit_vue_wrap",
                components: {
                    "vue-form-generator": VueFormGenerator.component,
                },
                data: {
                    current_template_type: this.selected_type,
                    selected_type: this.selected_type,
                    designs: this.designs,
                    design_types: this.design_types,
                    selected: this.selected,
                    view_url: wflp.view_url,
                    lp_title: wflp.lp_title,
                    selected_template: this.selected_template,
                    template_active: this.template_active,
                    temp_template_type: '',
                    temp_template_slug: '',
                    model: wflp.custom_options,
                    is_importing: false,
                    is_previewing: false,
                    schema: {
                        groups: this.getCustomSettingsSchema(),
                    },
                    formOptions: {
                        validateAfterLoad: false,
                        validateAfterChanged: true
                    },
                },
                methods: {
                    onSubmit: function () {
                        $(".wffn_save_btn_style").addClass('is_busy');
                        // $('.wffn_loader_global_save').addClass('ajax_loader_show');
                        let tempSetting = JSON.stringify(this.model);
                        tempSetting = JSON.parse(tempSetting);
                        let data = {"data": tempSetting, 'landing_id': wflp.id, '_nonce': wflp.nonce_custom_settings};
                        let wp_ajax = new wp_admin_ajax();
                        wp_ajax.ajax("lp_custom_settings_update", data);
                        wp_ajax.success = function (rsp) {
                            if (typeof rsp === "string") {
                                rsp = JSON.parse(rsp);
                            }
                            $('#modal-global-settings_success').iziModal('open');
                            $(".wffn_save_btn_style").removeClass('is_busy');
                            // $('.wffn_loader_global_save').removeClass('ajax_loader_show');
                        };
                        return false;
                    },
                    get_edit_link() {
                        let url = wflp.design_template_data[this.selected_type].edit_url;
                        if ('oxy' === this.selected_type && this.selected === 'oxy_1' ) {
                            let wp_ajax = new wp_admin_ajax();
                            let data = {"url": url, 'id': wflp.id, '_nonce': wflp.nonce_update_edit_url};
                            wp_ajax.ajax('lp_update_edit_url', data);
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
                            c = Object.prototype.hasOwnProperty.call(obj, key);
                        }
                        return c;
                    },
                    copy: function (event) {
                        let title = wflp.texts.copy_success;
                        if (jQuery(event.target).attr('class') === 'wffn_copy_text scode') {
                            title = wflp.texts.shortcode_copy_success;
                        }
                        var getInput = event.target.parentNode.querySelector('.wffn-scode-copy input')
                        getInput.select();
                        document.execCommand("copy");
                        $("#modal-global-settings_success").iziModal('setTitle', title);
                        $("#modal-global-settings_success").iziModal('open');
                    },
                    get_builder_title() {
                        return wflp.design_template_data[this.selected_type].title;
                    },
                    get_button_text() {
                        return wflp.design_template_data[this.selected_type].button_text;
                    },
                    show_template_dropdown: function (el) {
                        let elemParent = el.target.closest('.wffn_field_select_dropdown');
                        elemParent.classList.toggle('active');
                        elemParent.querySelector('.wffn_field_dropdown').classList.toggle('wffn-hide');
                    },
                    setTemplateType(template_type) {
                        Vue.set(this, 'current_template_type', template_type);
                        this.hide_tempate_dropdown();
                    },
                    hide_tempate_dropdown: function () {
                        let templateWrap = document.querySelector('.wffn_template_editor');
                        if (null != templateWrap) {
                            templateWrap.querySelector('.wffn_field_select_dropdown').classList.remove('active');
                            templateWrap.querySelector('.wffn_field_dropdown').classList.add('wffn-hide');
                        }
                    },
                    setTemplate(selected, type, el) {
                        Vue.set(this, 'selected', selected);
                        Vue.set(this, 'selected_type', type);

                        return this.save('yes', el);
                    },

                    removeDesign(cb) {
                        let wp_ajax = new wp_admin_ajax();
                        let save_layout = {
                            'wflp_id': self.id,
                            '_nonce': wflp.nonce_remove_design,
                        };
                        wp_ajax.ajax('lp_remove_design', save_layout);

                        wp_ajax.success = (rsp) => {
                            if (typeof cb == "function") {
                                cb(rsp);
                            }
                        };
                        wp_ajax.error = () => {

                        };
                    },
                    swalLoadiingText(text) {
                        if ($(".swal2-actions.swal2-loading .loading-text").length === 0) {
                            $(".swal2-actions.swal2-loading").append("<div class='loading-text'></div>");

                        }
                        $(".swal2-actions.swal2-loading .loading-text").text(text);
                    },
                    showFailedImport(warning_text) {
                        wffn_swal({
                            'html': warning_text,
                            'title': wffn.pageBuildersTexts[this.current_template_type].title,
                            'type': 'warning',
                            'allowEscapeKey': true,
                            'showCancelButton': false,
                            'confirmButtonText': wffn.pageBuildersTexts[this.current_template_type].ok_btn,
                            onClose: () => {
                                $(".wffn_import_template").removeClass('is_busy');
                            }
                        });
                    },
                    showFailedInstall(warning_text) {
                        wffn_swal({
                            'html': warning_text,
                            'title': wffn.pageBuildersTexts[this.current_template_type].install_fail,
                            'type': 'warning',
                            'allowEscapeKey': true,
                            'showCancelButton': false,
                            'confirmButtonText': wffn.pageBuildersTexts[this.current_template_type].close_btn,
                        });
                    },
                    showFailedActivate(warning_text) {
                        wffn_swal({
                            'html': warning_text,
                            'title': wffn.pageBuildersTexts[this.current_template_type].activate_fail,
                            'type': 'warning',
                            'allowEscapeKey': true,
                            'showCancelButton': false,
                            'confirmButtonText': wffn.pageBuildersTexts[this.current_template_type].close_btn,
                        });
                    },
                    importTemplate(template, type, current_target_element) {
                        let wp_ajax = new wp_admin_ajax();
                        let save_layout = {
                            'builder': type,
                            'template': template.slug,
                            'wflp_id': self.id,
                            '_nonce': wflp.nonce_import_design,
                        };
                        this.swalLoadiingText(wffn.i18n.importing);
                        wp_ajax.ajax('lp_import_template', save_layout);
                        wp_ajax.success = (rsp) => {
                            if (true === rsp.status) {
                                this.setTemplate(template.slug, type, current_target_element);
                            } else {
                                let parent = current_target_element.parents('.wffn_template_sec');
                                if (parent.hasClass('wffn_template_importing')) {
                                    parent.removeClass('wffn_template_importing');
                                }
                                $('.wffn_steps_btn_green').show();
                                setTimeout((msg) => {
                                    this.showFailedImport(msg);
                                }, 200, rsp.error);
                            }
                        };
                    },
                    GetFirstTemplateGroup() {
                        return window.wffn.default_builder;
                    },
                    maybeInstallPlugin(template, type, cb) {
                        let currentObj = this;
                        this.cb = cb;
                        let page_builder_plugins = wffn.pageBuildersOptions[this.current_template_type].plugins;
                        let pluginToInstall = 0;
                        $.each(page_builder_plugins, function (index, plugin) {
                            if ('install' === plugin.status) {
                                pluginToInstall++;
                                currentObj.swalLoadiingText(wffn.i18n.plugin_install);
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
                    afterInstallError(event, response) {
                        this.showFailedInstall(response.errorMessage);
                    },

                    activatePlugin(plugin_slug) {
                        let currentObj = this;
                        currentObj.swalLoadiingText(wffn.i18n.plugin_activate);
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
                                if (!_.isEqual(false, rsp.success)) {

                                    _.delay(function () {
                                        currentObj.cb();

                                        if ('yes' === currentObj.templateOnReqest ) {
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
                                } else {
                                    currentObj.restoreButtonState(currentObj.CurrenttargetElem);
                                    currentObj.showFailedActivate(rsp.data.message);
                                }
                            });
                    },

                    get_remove_template() {
                        wffn_swal({
                            'type': '',
                            'allowEscapeKey': false,
                            'showCancelButton': true,
                            'cancelButtonColor': '#e33b3b',
                            'confirmButtonColor': '#0073aa',
                            'confirmButtonText': wflp_localization.importer.remove_template.button_text,
                            'html': `<div class="wf_delete_modal_content">${wflp_localization.importer.remove_template.sub_heading}</div>`,
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
                                swalModalTitle.insertAdjacentHTML('beforebegin', '<div class="wffn-swal-modal-title">'+wflp_localization.importer.remove_template.modal_title+'</div>');
                            },
                        });

                    },
                    getClasses(template) {
                        let classes = '';

                        if (template.is_pro) {
                            classes += 'wffn_template_sec_design_pro';
                        } else {
                            classes += 'wffn_template_sec_design';
                        }

                        if (this.is_importing) {
                            classes += ' wffn_import_start';
                        }
                        return classes;
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


                        return 'https://templates-'+activeEditor+'.funnelswp.com/sp/' + prevslug + '/';

                    },
                    triggerImport(template, slug, type, el) {
                        this.templateOnReqest = template;
                        this.slugOnRequest = slug;
                        this.typeOnRequest = type;
                        this.is_importing = true;


                        $('.wffn_steps_btn_green').hide();
                        let current_target_element = $(el.target);
                        let importBtn = $(el.currentTarget);
                        current_target_element.closest('.wffn_temp_middle_align, .wffn-preview-overlay').find('.wffn_import_template').show();

                        if (current_target_element.closest('.wffn_template_sec').hasClass('wffn_template_importing')) {
                            this.is_importing = false;
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
                         * Alert user about missing dependency and further process to install and activate
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
                                    swalModalTitle.insertAdjacentHTML('beforebegin', '<div class="wffn-swal-modal-title">'+wffn.pageBuildersTexts[this.current_template_type].title+'</div>');
                                },
                                'preConfirm': () => {
                                    let self = this;
                                    if ('no' === wffn.pageBuildersTexts[this.current_template_type].noInstall) {
                                        $('button.swal2-cancel.swal2-styled').css({'display': 'none'});
                                        return new Promise((resolve) => {
                                            this.maybeInstallPlugin(template, type, resolve);
                                        });
                                    }
                                    if( 'yes' === wffn.pageBuildersTexts[this.current_template_type].noInstall && 'install' === wffn.pageBuildersTexts[this.current_template_type].plugin_status ){
                                        self.restoreButtonState(current_target_element, false);
                                        window.open( wffn.pageBuildersTexts[this.current_template_type].builder_link, '_blank' );
                                    }
                                },
                                onClose: () => {
                                    let self = this;
                                    $(document).on("click", function (event) {
                                        if ('swal2-cancel swal2-styled' === event.target.className || 'swal2-close' === event.target.className ) {
                                            self.restoreButtonState(current_target_element, false);
                                        }
                                    });
                                }
                            });
                            return;
                        }

                        importBtn.addClass('is_busy');

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
                    save(template_active = 'yes', el = '') {
                        let inst = this;
                        let wp_ajax = new wp_admin_ajax();
                        let save_layout = {
                            'selected_type': this.current_template_type,
                            'selected': this.selected,
                            'wflp_id': self.id,
                            'template_active': template_active,
                            '_nonce': wflp.nonce_save_design,
                        };

                        wp_ajax.ajax('lp_save_design', save_layout);
                        wp_ajax.success = () => {
                            this.selected_type = this.current_template_type;
                            this.selected_template = this.designs[this.selected_type][this.selected];
                            $('#wfacp_control > .wfacp_p20').show();
                            this.restoreButtonState(el);
                            inst.is_importing = false;
                            inst.previewClosed();
                        };
                        wp_ajax.error = () => {
                            inst.is_importing = false;
                            inst.previewClosed();
                        };

                    },
                    updateLanding: function () {
                        let landing_edit = "#wf_landing_edit_modal";
                        let parsedData = _.extend({}, wffnIZIDefault, {});
                        $(landing_edit).iziModal(
                            _.extend(
                                {
                                    onOpening: function (modal) {
                                        wffn_edit_landing_vue(modal);
                                    },
                                    onClosed: function () {
                                        $(landing_edit).iziModal('resetContent');
                                        $(landing_edit).iziModal('destroy');
                                        $(landing_edit).iziModal('close');
                                    },
                                },
                                parsedData
                            ),
                        );
                        $(landing_edit).iziModal('open');
                    }
                },
                mounted: function () {
                    let self = this;
                    if (self.template_active === 'no') {
                        self.setTemplateType(self.GetFirstTemplateGroup());
                    }
                    $(document.body).click(function (e) {
                        if ($(e.target).attr('class') !== 'wffn_editor_label wffn_field_select_label' && $(e.target).parents().attr('class') !== 'wffn_editor_label wffn_field_select_label') {
                            if ($(e.target).attr('class') !== 'wffn_dropdown_header_label' && $('.wffn_field_select_dropdown').hasClass("active")) {
                                self.hide_tempate_dropdown();
                            }
                        }
                    });
                },
            });

            //Update landing page
            /** wffn_edit_landing_vue started  **/
            const wffn_edit_landing_vue = function (iziMod) {
                self.wffn_edit_landing_vue = new Vue(
                    {
                        mixins: [wfabtVueMixin],
                        components: {
                            "vue-form-generator": VueFormGenerator.component,
                        },
                        data: {
                            modal: false,
                            model: wflp.update_popups.values,
                            schema: {
                                fields: self.get_landing_vue_fields(),
                            },
                            formOptions: {
                                validateAfterLoad: false,
                                validateAfterChanged: true,
                            },
                            current_state: 1,
                        },
                        methods: {
                            updateLanding: function () {
                                if (false === this.$refs.update_landing_ref.validate()) {
                                    console.log('Validation Error');
                                    return;
                                }
                                let landing_id = wflp.id;
                                let data = JSON.stringify(self.wffn_edit_landing_vue.model);

                                if( self.wffn_edit_landing_vue.$el ) {
                                    let submitButton = self.wffn_edit_landing_vue.$el.querySelector( '.wf_funnel_btn.wf_funnel_btn_primary' );
                                    $( submitButton ).addClass( 'is_busy' );
                                }

                                let wp_ajax = new wp_admin_ajax();

                                wp_ajax.ajax("update_landing_page", {"_nonce": wflp.wflp_edit_nonce, "landing_id": landing_id, 'data': data});
                                wp_ajax.success = function (rsp) {
                                    if (_.isEqual(true, rsp.status)) {
                                        $(".bwf_breadcrumb ul li:last-child").html(rsp.title);
                                        $(".bwf_breadcrumb > span:last-of-type").html(rsp.title);
                                        $('.bwfan_page_header .bwfan_page_title').html(rsp.title);
                                        $(".bwf-header-bar .bwf-bar-navigation > span:last-child").html(rsp.title);
                                        Vue.set(self.wffn_edit_landing_vue.model, 'title', rsp.title);
                                        wflp.update_popups.label_texts.title.value = rsp.title;
                                        $('#modal-global-settings_success').iziModal('open');
                                        $('#wf_landing_edit_modal').iziModal('close');
                                    }
                                };
                            }
                        },
                    }
                ).$mount('#part-update-landing');
            };


            if ($(".wffn-widget-tabs").length > 0) {
                let wfctb = $('.wffn-widget-tabs .wffn-tab-title');
                wfctb.on(
                    'click', function () {
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
            return this.main;
        }


    }

    window.addEventListener('load', () => {
        window.dispatchEvent(new Event('wffn_landing_admin_event_loaded'));
        window.wflp_design = new wflp_design();
    });

})(jQuery);