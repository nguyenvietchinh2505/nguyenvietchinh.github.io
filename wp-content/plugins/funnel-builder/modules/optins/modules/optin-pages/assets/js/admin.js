/*global wfop*/
/*global Vue*/
/*global VueFormGenerator*/
/*global wp_admin_ajax*/
/*global wffn_swal*/
/*global wfop_localization*/
/*global _*/
/*global wffn*/
/*global wfop_action*/
/*global wp*/
/*global tinymce*/
(function ($) {
    'use strict';
    Vue.component("field-button", {
        mixins: [VueFormGenerator.abstractField],
        template: '#vue-f-button',
        mounted: function () {
        },
    });

    /**  Html parsing of auto responder optin forms starts here **/
    function html_parser_js(t) {
        var s;
        s = jQuery, this.validate = function () {
            this.valid = this._inputs.text && this._inputs.text.length || this._inputs.select && this._inputs.select.length || this._inputs.textarea && this._inputs.textarea.length || this._inputs.radio_checkbox && Object.keys(this._inputs.radio_checkbox).length
        }, this.isValid = function () {
            return this.valid
        }, this.setCode = function (t) {
            t instanceof s || (t = s(t)), this.$code = t
        }, this.parse = function (t) {
            return void 0 === t && (t = this._orignial_code), this.setCode(t), this.removeExtra(), this._inputs = {
                hidden: this.getInputs("hidden"),
                radio_checkbox: this.getCheckboxRadio(),
                text: this.getInputs(),
                select: this.getSelect(),
                textarea: this.getTextarea(),
                submit: this.getSubmit(),
                form: this.getForm(),
            }, this.validate(), this._inputs
        }, this.removeExtra = function (t) {
            return (void 0 === t || !(t instanceof Array)) && (t = ["style", "script", "link"]), t.push("input:image"), this.$code.find(t.join(",")).remove(), this.$code
        }, this.getSubmit = function () {
            var t = '',
                e = this.$code.find("input:submit"),
                i = this.$code.find("button");
            return e.length ? (t = e.val(), e.remove()) : i.length && (t = i.text(), i.remove()), t
        }, this.getInputs = function (s) {
            void 0 === s && (s = []), "string" == typeof s && (s = [s]);
            var a = [];
            let self = this;
            return _.each(this.$code.find("input:not(:submit)"), function (t) {
                if (!(0 < s.length && -1 === s.indexOf(t.type))) {
                    var e = {
                            name: t.name,
                            id: t.id,
                            type: t.type,
                            placeholder: t.placeholder,
                            value: t.value,
                        },
                        i = self.$code.find('label[for="' + t.id + '"]');
                    e.label = "";
                    if (i.length === 0 && t.id !== '') {
                        i = self.$code.find('input[name="' + t.value + '"]').parents('div._field-wrapper').siblings('label');
                    }
                    if (t.required && (e.required = t.required), -1 !== t.outerHTML.toLowerCase().indexOf("email") && (e["data-required"] = 1, e["data-validation"] = "email"), i.length && (e.label = i.text()), !e.placeholder) {
                        var n = /\[(.*?)\]/g.exec(e.name || "");
                        n && n[1] ? e.placeholder = n[1] : e.placeholder = e.name
                    }
                    a.push(e), t.parentNode.removeChild(t)
                }
            }, this), a
        }, this.getCheckboxRadio = function () {
            var s = [];
            let self = this;
            return _.each(this.$code.find("input:checkbox, input:radio"), function (t) {
                var e = Math.floor(Math.random() * Math.floor(9999)),
                    i = {
                        name: t.name,
                        id: t.id ? t.id : e,
                        type: t.type,
                        placeholder: t.placeholder,
                        value: t.value,
                    },
                    n = self.$code.find('label[for="' + t.id + '"]');

                i.mlabel = '';
                if (t.id !== '') {
                    if (self.$code.find('#' + t.id).parent('div._row._checkbox-radio').length > 0) {
                        if (t.type === 'checkbox') {
                            i.mlabel = self.$code.find('#' + t.id).parent('div._row._checkbox-radio').siblings('._row').find('label._form-label').text();
                        } else {
                            i.mlabel = self.$code.find('#' + t.id).parent('div._row._checkbox-radio').siblings('label').text();
                        }
                    }
                }
                if ($('#optin-form-builder option:selected').val() === 'infusion-soft') {
                    let idArr = t.id.split("_");
                    if ($.isNumeric(idArr[idArr.length - 1])) {
                        idArr.splice($.inArray(idArr[idArr.length - 1], idArr), 1);
                    }
                    let mId = idArr.join("_");
                    let mlb = self.$code.find('label[for="' + mId + '"]').text();
                    if (mlb !== '') {
                        i.mlabel = mlb;
                    }
                }

                n.length && (i.label = n.text()), void 0 !== i.label && "radio" !== t.type || (i.label === i.value), void 0 === s[t.name] && (s[t.name] = []), s.push(i), t.parentNode.removeChild(t)
            }, this), s
        }, this.getSelect = function () {
            var n = [];
            let self = this;
            return _.each(this.$code.find("select"), function (t) {
                var e = {
                        name: t.name,
                        id: t.id,
                        type: "select",
                        options: [],
                    },
                    i = self.$code.find('label[for="' + t.id + '"]');


                i.length && (e.label = i.text()), s(t).find("option").each(function () {
                    e.options.push({
                        value: this.value,
                        text: this.text
                    })
                }), n.push(e), t.parentNode.removeChild(t)
            }, this), n
        }, this.getTextarea = function () {
            var i = [];
            return _.each(this.$code.find("textarea"), function (t) {
                var e = {
                    name: t.name,
                    id: t.id,
                    type: "textarea",
                    placeholder: t.placeholder,
                    text: t.value,
                    rows: t.rows,
                    cols: t.cols
                };
                i.push(e), t.parentNode.removeChild(t)
            }, this), i
        }, this.getForm = function () {
            var i = {},
                t = this.$code.find("form");

            return t.length ? (i.action = t.attr("action"), i.method = t.attr("method"), i.id = t.attr("id")) : this.$code.each(function (t, e) {
                let formSlug = $('#optin-form-builder option:selected').val();
                if (e.id === '') {
                    e.id = formSlug
                }
                "FORM" === e.tagName && (i.action = e.action, i.method = e.method, i.id = e.id)
            }), i
        }
        this.setCode(t);        

    }

    class wfop_design {
        constructor() {
            this.id = wfop.id;
            this.wffn_ajax = new wp_admin_ajax();
            this.selected = wfop.selected;
            this.selected_type = wfop.selected_type;
            this.designs = wfop.designs;
            this.embedMode = wfop.embedMode;
            this.design_types = wfop.design_types;
            this.template_active = wfop.template_active;
            this.formBuilder = '';
            this.optinFormId = 0;
            this.optinFormGroup = wfop.default_form_group;
            this.htmlCode = '';
            this.formFields = [];
            this.CustomFormFields = wfop.custom_form;
            this.form_field_width = wfop.form_field_width;
            this.addNewFieldData = _.clone(wfop.add_new_field_default);
            this.currentlyEditing = 0;
            this.formStylingOptions = {};
            this.optin_form_shortcode = wfop.optin_form_option.optin_form_shortcode;
            this.globalCss = [];
            this.is_pro_active = wfop.is_wffn_pro_active;
            this.main();
            this.chooseOptinFormMode();
            this.generateForm();
            this.initFormEditor();
            this.initFormCustomizer();
            this.previewGlobalCss();
            setTimeout(() => {
                this.dynamic_font_family();
            }, 200);
            $("#wffn_op_edit_vue_wrap .wf_funnel_card_switch input[type='checkbox']").click(function () {
                let wp_ajax = new wp_admin_ajax();
                let toggle_data = {
                    'toggle_state': this.checked,
                    'wfop_id': wfop.id,
                    '_nonce': wfop.nonce_toggle_state,
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

                wp_ajax.ajax('op_toggle_state', toggle_data);
                wp_ajax.success = function() {
                    $('#modal-global-settings_success').iziModal('open');
                }

            });
            if ($('#modal-global-settings_success').length > 0) {
                $("#modal-global-settings_success").iziModal(
                    {
                        title: wfop.texts.settings_success,
                        icon: 'icon-check',
                        headerColor: '#6dbe45',
                        background: '#6dbe45',
                        borderBottom: false,
                        width: 600,
                        timeout: 4000,
                        timeoutProgressbar: true,
                        transitionIn: 'fadeInUp',
                        transitionOut: 'fadeOut',
                        bottom: 0,
                        loop: true,
                        pauseOnHover: true,
                        overlay: false
                    }
                );
            }
            var wfop_obj = this;

            /**
             * Trigger async event on plugin install success as we are executing wp native js API to update/install a plugin
             */
            $(document).on('wp-plugin-install-success', function (event, response) {
                wfop_obj.main.afterInstall(event, response);
            });

            $(document).ready(function () {
                if ($('#wffn_lead_generation_code').length > 0) {

                    $('#wffn_lead_generation_code').on("change input paste keyup", function () {
                        $('#wffn_generate_form').attr("disabled", "disabled");
                        if ($.trim($('#wffn_lead_generation_code').val()).length < 1) {
                            $('#wffn_generate_form').attr("disabled", "disabled");
                        } else {
                            $('#wffn_generate_form').removeAttr('disabled');
                        }

                    });
                }
            });

        }

        initFormEditor() {

            if ($(".wfop_edit_form_wrap").length === 0) {
                return;
            }
            let curr = this;

            /**
             * Expand and collapse accordions
             */
            $(document.body).on('click', '.bwf_open_accordion', function () {
                let getParent = $(this).parents('.wffn_sortable_field').get(0);
                curr.toggleFieldExpand(getParent);
            });

            curr.preparePreview();
        }

        slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }

        getCustomFieldInputName(newFieldData) {
            if (!wfop.optin_field_types[newFieldData.type].is_custom_field) {
                return wfop.optin_field_types[newFieldData.type].default.InputName;
            }
            return wfop.prefix + this.slugify(newFieldData.label);
        }

        getDefaultOpenFieldType() {
            var def = '';
            jQuery('#wfop_edit_new_field_type option').each(function () {
                if (false === this.disabled && '' === def) {
                    def = this.value;
                }
            });
            alert(def);
            return def;

        }

        onChangeCustomizeMode(value) {
            let curr = this;
            this.embedMode = value;
            this.toggleCustomizerfieldDisplay();
            this.togglePreviewDisplay();
            $('.wfopp-tabs').removeClass('wffn-active');
            $('.wfopp-tabs.wfopp-tab-' + value).addClass('wffn-active');
            $('.wffn_sortable_field.bwf_ac_open').each(function () {
                let getParent = $(this);
                curr.toggleFieldExpand(getParent, true);
            });
            if ('inline' == value) {
                $('.wffn_row_billing[data-cust-popover="yes"]').hide();
            } else {
                $('.wffn_row_billing[data-cust-popover="yes"]').show();
            }


        }

        toggleCustomizerfieldDisplay() {
            $(".wfop_embed_opt").removeClass("wfop_embed_opt_active");
            $(".wfop_embed_opt[data-type='" + this.embedMode + "']").addClass("wfop_embed_opt_active");
            $('.wfop-show-hide-cust-settings div.wfop-cust-settings').hide();
            $('.wfop-show-hide-cust-settings div.wfop-cust-settings[data-cust-' + this.embedMode + ' = yes]').show();
            $('.wffn_op_preview').attr('data-embed', this.embedMode);
        }

        togglePreviewDisplay() {
            this.preparePreview();
        }

        onChangeFieldType(value) {
            $(".wfop_new_fields_wrap").hide();
            this.addNewFieldData.type = value;
            _.each(wfop.optin_field_types, function (v, k) {
                if (k === value) {
                    $(".wfop_new_fields_wrap[data-type='" + value + "'] ").show();
                }
            });
        }

        isFieldTypeDisabled(type) {
            if (wfop.optin_field_types[type].is_custom_field) {
                return '';
            }
            let ret = ''
            _.each(this.CustomFormFields, function (v) {
                if (v.type === type) {
                    ret = 'disabled';
                }
            });
            return ret;
        }

        _repositionFields() {
            var newFieldArr = [];
            var curr = this;
            $(".wffn_accord_field_listing .wffn_sortable_field").each(function () {
                let getInputName = $(this).data('key');
                let search = curr.CustomFormFields.findIndex(x => x.InputName === getInputName);
                newFieldArr.push(curr.CustomFormFields[search]);
            });
            curr.CustomFormFields = newFieldArr;
        }

        preparePreview() {
            let self = this;
            let previewElem = $(".wffn_op_preview");
            let formWrap = $(".wfop_form_preview_wrap");
            previewElem.addClass("bwfop_preview_loading");
            let data = {
                "wffn_custom_form": JSON.stringify(this.CustomFormFields),
                "wffn_embed_mode": this.embedMode,
                'wffn_optin_id': wfop.id,
                wfop_show_preview: 'yes',
                '_nonce': wfop_action.nonce_form_preview
            };
            this.wffn_ajax.ajax('wfop_show_preview', data);
            this.wffn_ajax.success = function (rsp) {
                if (typeof rsp === "string") {
                    formWrap.find('.wfop_form_wrap').html(rsp);
                    previewElem.removeClass("bwfop_preview_loading");
                    previewElem.find('.bwfop_loader').hide();
                    var elems = document.querySelectorAll(".phone_flag_code input[type='tel']");
                    for (var i in elems) {
                        if (typeof elems[i] === 'object') {
                            window.intlTelInput(elems[i], {
                                initialCountry: "auto",
                                separateDialCode: true,
                                geoIpLookup: function (callback) {
                                    $.get('https://ipinfo.io', function () {
                                    }, "jsonp").always(function (resp) {
                                        var countryCode = (resp && resp.country) ? resp.country : "us";
                                        callback(countryCode);
                                    });
                                },
                            });
                        }

                    }
                    let txtValue = {
                        'popup_heading': $('#popup_heading').val(),
                        'popup_sub_heading': $('#popup_sub_heading').val(),
                        'button_text': $('#button_text').val(),
                        'popup_bar_text': $('#popup_bar_text').val(),
                        'popup_footer_text': $('#popup_footer_text').val(),
                    };

                    $.each(txtValue, function (key, value) {
                        self.formStylingOptions[key].cb(value);
                    });


                    self.dynamic_font_family();

                    $('.wffn_loader_global_save').removeClass('ajax_loader_show');

                }

            };
        }

        CollapseAll() {
            $(".wffn_sortable_field").removeClass("bwf_ac_open");
            $(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").removeClass("dashicons-arrow-up");
            $(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").addClass("dashicons-arrow-down");

        }

        toggleFieldExpand(getParent, hard) {
            let parent = $(getParent);
            if (typeof hard === 'undefined') {
                hard = false;
            }
            if (parent.hasClass('bwf_ac_open')) {
                if (true === hard) {
                    parent.find(".wffn_billing_accordion_content").hide();
                    parent.removeClass("bwf_ac_open");
                    parent.find(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").removeClass("dashicons-arrow-up");
                    parent.find(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").addClass("dashicons-arrow-down");

                } else {
                    parent.find(".wffn_billing_accordion_content").slideUp(function () {
                        parent.removeClass("bwf_ac_open");
                        parent.find(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").removeClass("dashicons-arrow-up");
                        parent.find(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").addClass("dashicons-arrow-down");
                    });
                }

                return;
            }

            var getkey = parent.data('index');
            if (!_.isUndefined(getkey)) {
                parent.find(".wffn_billing_accordion_content .wfop_edit_fields_wrap").hide();
                parent.find(".wffn_billing_accordion_content .wfop_edit_fields_wrap[data-type='" + this.CustomFormFields[getkey].type + "']").show();
            }

            parent.find(".wffn_billing_accordion_content").slideDown(function () {
                parent.addClass("bwf_ac_open");
                parent.find(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").addClass("dashicons-arrow-up");
                parent.find(".wffn_billing_accordion_inner.bwf_open_accordion .accordion_right i").removeClass("dashicons-arrow-down");
            });
        }

        initFormCustomizer() {
            let self = this;
            if ($(".wfop_edit_form_wrap").length === 0) {
                return;
            }
            $(".wfop_color_picker").wpColorPicker({
                change: function (event, ui) {
                    var element = event.target;
                    var name = element.name;
                    window.wfop_design.onChangeStylingOptions(name, ui.color.toString())
                },
                clear: function (event) {

                    let picker = $(event.target).parent().find('input.wp-color-picker');
                    if (typeof picker == 'undefined' || picker.length === 0) {
                        return;
                    }
                }
            });

            let sel_size = $('select#button_size').find(":selected").val();
            if( 'custom' === sel_size ){
                $(".wffn_row_billing.wfop_custom_pad").addClass('wfop_show_pad');
            }

            let dynamicStyle = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec input:not(.wfop_submit_btn), .wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec select, .wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec textarea';
            let dynamicStyleTwo = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec, .wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec input, .wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec textarea, .wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec select';
            let buttonStyle = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec .wfop_submit_btn';
            let buttonCont = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec .bwf-custom-button';
            let popupHead = '.wfop_form_wrap .bwf_pp_opt_head';
            let popupSubHead = '.wfop_form_wrap .bwf_pp_opt_sub_head';
            let progressCont = '.wfop_form_wrap .bwf_pp_bar_wrap .bwf_pp_bar';
            let footerText = '.wfop_form_wrap .bwf_pp_footer';

            let stylingConfiguration = {
                'input_border_type': {
                    value: '', cb: function (val) {
                        self.globalCss['input_border_type'] = dynamicStyle + '{border-style:' + val + '}';
                    }
                },
                'input_border_size': {
                    value: '', cb: function (val) {
                        self.globalCss['input_border_size'] = dynamicStyle + '{border-width:' + val + 'px}';
                    }
                },
                'input_border_color': {
                    value: '', cb: function (val) {
                        self.globalCss['input_border_color'] = dynamicStyle + '{border-color:' + val + '}';
                    }
                },
                'input_font_size': {
                    value: '', cb: function (val) {
                        let lineHeight = parseInt(val) + 10;
                        self.globalCss['input_font_size'] = dynamicStyleTwo + '{font-size:' + val + 'px; line-height:' + lineHeight + 'px}';
                    }
                },
                'input_font_family': {
                    value: '', cb: function (val) {
                        self.globalCss['input_font_family'] = dynamicStyleTwo + '{font-family:' + val + '}';
                    }
                },
                'input_font_weight': {
                    value: '', cb: function (val) {
                        self.globalCss['input_font_weight'] = dynamicStyleTwo + '{font-weight:' + val + '}';
                    }
                },
                'show_input_label': {
                    value: '', cb: function (val) {
                        if ("no" === val) {
                            $('.wfop_form_preview_wrap label').each(function () {
                                let fId = $(this).attr('for');
                                $('#' + fId).attr('placeholder', $(this).text());
                            });
                            self.globalCss['show_input_label'] = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec:not(.bwfac_form_field_radio):not(.wfop_radios) > label{display:none}';
                        } else {
                            self.globalCss['show_input_label'] = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec:not(.bwfac_form_field_radio):not(.wfop_radios) > label{display:block}';
                        }
                    }
                },
                'label_font_size': {
                    value: '', cb: function (val) {
                        let lineHeight = parseInt(val) + 10;
                        self.globalCss['label_font_size'] = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec label{font-size:' + val + 'px; line-height:' + lineHeight + 'px}';
                    }
                },
                'input_label_color': {
                    value: '', cb: function (val) {
                        self.globalCss['input_label_color'] = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec label{color:' + val + '}';
                    }
                },
                'input_text_color': {
                    value: '', cb: function (val) {
                        self.globalCss['input_text_color'] = dynamicStyle + '{color:' + val + '}';
                        self.globalCss['input_text_color'] = '.wfop_form_wrap .bwfac_forms_outer .bwfac_form_sec ::placeholder{color:' + val + '}';
                    }
                },
                'input_bg_color': {
                    value: '', cb: function (val) {
                        self.globalCss['input_bg_color'] = dynamicStyle + '{background-color:' + val + '}';
                    }
                },
                'button_text': {
                    value: '', cb: function (val) {
                        $(buttonStyle + ' .bwf_heading').html(val);
                    }
                },
                'button_submitting_text': {
                    value: '', cb: function (val) {
                        $(buttonStyle).attr('button_submitting_text', val);
                    }
                },
                'button_size': {
                    value: '', cb: function (val) {
                        if ("custom" === val) {
                            $(".wffn_row_billing.wfop_custom_pad").addClass('wfop_show_pad');
                            $("#wffn_custom_optin_submit").attr('data-size', val);
                        } else {
                            $(".wffn_row_billing.wfop_custom_pad").removeClass('wfop_show_pad');
                            $("#wffn_custom_optin_submit").attr('data-size', val);
                        }
                    }
                },
                'button_padding_top': {
                    value: '', cb: function (val) {
                        self.globalCss['button_padding_top'] = buttonStyle + '{padding-top:' + val + 'px !important}';
                    }
                },
                'button_padding_right': {
                    value: '', cb: function (val) {
                        self.globalCss['button_padding_right'] = buttonStyle + '{padding-right:' + val + 'px !important}';
                    }
                },
                'button_padding_bottom': {
                    value: '', cb: function (val) {
                        self.globalCss['button_padding_bottom'] = buttonStyle + '{padding-bottom:' + val + 'px !important}';
                    }
                },
                'button_padding_left': {
                    value: '', cb: function (val) {
                        self.globalCss['button_padding_left'] = buttonStyle + '{padding-left:' + val + 'px !important}';
                    }
                },
                'field_size': {
                    value: '', cb: function (val) {
                        $(".bwfac_forms_outer").attr('data-field-size', val);
                    }
                },
                'button_width': {
                    value: '', cb: function (val) {
                        if ("full" === val) {
                            self.globalCss['button_width'] = buttonStyle + '{width:100% !important}';
                        } else {
                            self.globalCss['button_width'] = buttonStyle + '{width:auto !important}';
                        }
                    }
                },
                'button_align': {
                    value: '', cb: function (val) {
                       self.globalCss['button_align'] = buttonCont + '{text-align:' + val + ' !important}';
                    }
                },
                'button_margin_top': {
                    value: '', cb: function (val) {
                        self.globalCss['button_margin_top'] = buttonStyle + '{margin-top:' + val + 'px !important}';
                    }
                },
                'button_margin_right': {
                    value: '', cb: function (val) {
                        self.globalCss['button_margin_right'] = buttonStyle + '{margin-right:' + val + 'px !important}';
                    }
                },
                'button_margin_bottom': {
                    value: '', cb: function (val) {
                        self.globalCss['button_margin_bottom'] = buttonStyle + '{margin-bottom:' + val + 'px !important}';
                    }
                },
                'button_margin_left': {
                    value: '', cb: function (val) {
                        self.globalCss['button_margin_left'] = buttonStyle + '{margin-left:' + val + 'px !important}';
                    }
                },
                'button_border_type': {
                    value: '', cb: function (val) {
                        self.globalCss['button_border_type'] = buttonStyle + '{border-style:' + val + '}';
                    }
                },
                'button_border_size': {
                    value: '', cb: function (val) {
                        self.globalCss['button_border_size'] = buttonStyle + '{border-width:' + val + 'px}';
                    }
                },
                'button_border_color': {
                    value: '', cb: function (val) {
                        self.globalCss['button_border_color'] = buttonStyle + '{border-color:' + val + '}';
                    }
                },
                'button_font_size': {
                    value: '', cb: function (val) {
                        let lineHeight = parseInt(val) + 10;
                        self.globalCss['button_font_size'] = buttonStyle + '{font-size:' + val + 'px; line-height:' + lineHeight + 'px}';
                    }
                },
                'button_font_family': {
                    value: '', cb: function (val) {
                        self.globalCss['button_font_family'] = buttonStyle + '{font-family:' + val + '}';
                    }
                },
                'button_font_weight': {
                    value: '', cb: function (val) {
                        self.globalCss['button_font_weight'] = buttonStyle + '{font-weight:' + val + '}';
                    }
                },
                'button_text_color': {
                    value: '', cb: function (val) {
                        self.globalCss['button_text_color'] = buttonStyle + '{color:' + val + '}';
                    }
                },
                'button_text_color_hover': {
                    value: '', cb: function (val) {
                        self.globalCss['button_text_color_hover'] = buttonStyle + ':hover{color:' + val + '}';
                    }
                },
                'button_bg_color': {
                    value: '', cb: function (val) {
                        self.globalCss['button_bg_color'] = buttonStyle + '{background-color:' + val + '}';
                    }
                },
                'button_bg_color_hover': {
                    value: '', cb: function (val) {
                        self.globalCss['button_bg_color_hover'] = buttonStyle + ':hover{background-color:' + val + '}';
                    }
                },
                'button_border_color_hover': {
                    value: '', cb: function (val) {
                        self.globalCss['button_border_color_hover'] = buttonStyle + ':hover{border-color:' + val + '}';
                    }
                },
                'popup_heading': {
                    value: '', cb: function (val) {
                        $(popupHead).text(val);
                    }
                },
                'popup_heading_color': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_heading_color'] = popupHead + '{color:' + val + '}';
                    }
                },
                'popup_heading_font_size': {
                    value: '', cb: function (val) {
                        let lineHeight = parseInt(val) + 8;
                        self.globalCss['popup_heading_font_size'] = popupHead + '{font-size:' + val + 'px; line-height:' + lineHeight + 'px}';
                    }
                },
                'popup_heading_font_family': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_heading_font_family'] = popupHead + '{font-family:' + val + '}';
                    }
                },
                'popup_heading_font_weight': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_heading_font_weight'] = popupHead + '{font-weight:' + val + '}';
                    }
                },

                'popup_sub_heading': {
                    value: '', cb: function (val) {
                        $(popupSubHead).text(val);
                    }
                },
                'popup_sub_heading_color': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_sub_heading_color'] = popupSubHead + '{color:' + val + '}';
                    }
                },
                'popup_sub_heading_font_size': {
                    value: '', cb: function (val) {
                        let lineHeight = parseInt(val) + 8;
                        self.globalCss['popup_sub_heading_font_size'] = popupSubHead + '{font-size:' + val + 'px; line-height:' + lineHeight + 'px}';
                    }
                },
                'popup_sub_heading_font_family': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_sub_heading_font_family'] = popupSubHead + '{font-family:' + val + '}';
                    }
                },
                'popup_sub_heading_font_weight': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_sub_heading_font_weight'] = popupSubHead + '{font-weight:' + val + '}';
                    }
                },

                'popup_bar_pp': {
                    value: '', cb: function (val) {
                        if ("disable" === val) {
                            self.globalCss['popup_bar_pp'] = '.wfop_form_wrap .bwf_pp_bar_wrap{display:none}';
                        } else {
                            self.globalCss['popup_bar_pp'] = '.wfop_form_wrap .bwf_pp_bar_wrap{display: flex;}';
                        }
                    }
                },
                'popup_bar_animation': {
                    value: '', cb: function (val) {

                        if ("yes" === val || "on" === val) {
                            $(progressCont).attr('class', 'bwf_pp_bar bwf_pp_animate');
                        } else {
                            $(progressCont).attr('class', 'bwf_pp_bar');
                        }
                    }

                },
                'popup_bar_width': {
                    value: '', cb: function (val) {
                        if (val > 100) {
                            val = 100;
                            $('#popup_bar_width').val(100);
                        }
                        self.globalCss['popup_bar_width'] = '.wfop_form_wrap .bwf_pp_bar_wrap .bwf_pp_bar{width:' + val + '%}';
                    }
                },
                'popup_bar_height': {
                    value: '', cb: function (val) {
                        if (val < 10) {
                            val = 10;
                            $('#popup_bar_height').val(10);
                        }
                        self.globalCss['popup_bar_height'] = '.wfop_form_wrap .bwf_pp_bar_wrap{height:' + val + 'px}';
                    }
                },
                'popup_bar_inner_gap': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_bar_inner_gap'] = '.wfop_form_wrap .bwf_pp_bar_wrap{padding:' + val + 'px}';
                    }
                },

                'popup_bar_text': {
                    value: '', cb: function (val) {
                        $(progressCont).text(val);
                    }
                },
                'popup_bar_font_size': {
                    value: '', cb: function (val) {
                        let lineHeight = parseInt(val) + 8;
                        self.globalCss['popup_bar_font_size'] = progressCont + '{font-size:' + val + 'px; line-height:' + lineHeight + 'px}';
                    }
                },
                'popup_bar_font_family': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_bar_font_family'] = progressCont + '{font-family:' + val + '}';
                    }
                },
                'popup_bar_text_color': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_bar_text_color'] = progressCont + '{color:' + val + '}';
                    }
                },
                'popup_bar_color': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_bar_color'] = progressCont + '{background-color:' + val + '}';
                    }
                },
                'popup_bar_bg_color': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_bar_bg_color'] = '.wfop_form_wrap .bwf_pp_bar_wrap{background-color:' + val + '}';
                    }
                },

                'popup_footer_text': {
                    value: '', cb: function (val) {
                        $(footerText).text(val);
                    }
                },
                'popup_footer_font_size': {
                    value: '', cb: function (val) {
                        let lineHeight = parseInt(val) + 8;
                        self.globalCss['popup_footer_font_size'] = footerText + '{font-size:' + val + 'px; line-height:' + lineHeight + 'px}';
                    }
                },
                'popup_footer_font_family': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_footer_font_family'] = footerText + '{font-family:' + val + '}';
                    }
                },
                'popup_footer_text_color': {
                    value: '', cb: function (val) {
                        self.globalCss['popup_footer_text_color'] = footerText + '{color:' + val + '}';
                    }
                },

                'popup_width': {
                    value: '', cb: function (val) {
                        $('.wfop_form_preview_wrap').css('maxWidth', val + 'px');
                    }
                },
                'popup_padding': {
                    value: '', cb: function (val) {
                        $('.wfop_form_preview_wrap').css('padding', val + 'px');
                    }
                },
                'popup_open_animation': {
                    value: '', cb: function (val) {
                        $('.bwf_pp_overlay').attr('class', 'bwf_pp_overlay bwf_pp_effect_' + val);
                    }
                },
            }
            _.each(stylingConfiguration, function (k, v) {
                stylingConfiguration[v].value = wfop.optin_customization_options[v];
            })
            this.formStylingOptions = stylingConfiguration;
            $('#wfop_custom_css').before('<style>' + stylingConfiguration + '</style>');
            this.toggleCustomizerfieldDisplay();


        }

        previewGlobalCss() {
            $('#wf_funnel_optin').append('<style id="preview_css"></style>');
        }

        onChangeStylingOptions(key, value) {
            this.formStylingOptions[key].value = value;
            this.formStylingOptions[key].cb(value);
            let cssStr = '';
            for (var k in this.globalCss) {
                cssStr += this.globalCss[k];
            }
            this.dynamic_font_family();

            $('#preview_css').html('');
            $('#preview_css').append(cssStr);

        }

        onChangeWidth(key, value) {
            let fid = 'wfop_id_' + key;
            let el = $('[id*="' + fid + '"]');
            let parent = el.closest('.bwfac_form_sec');
            if (parent.length > 0) {
                parent.removeClass('wffn-sm-100');
                parent.removeClass('wffn-sm-33');
                parent.removeClass('wffn-sm-50');
                parent.removeClass('wffn-sm-67');
                parent.addClass(value);
                this.form_field_width[key] = value;
            }
        }

        getFormStylingOptionsValue() {
            let formStyleValues = {};
            _.each(this.formStylingOptions, function (v, k) {
                formStyleValues[k] = v.value;
            });
            return formStyleValues;
        }

        onsubmitForm() {
            $(".wffn_save_btn_style").addClass('is_busy');
            let data = {
                "wffn_embed_mode": this.embedMode,
                "wffn_custom_form": JSON.stringify(this.CustomFormFields),
                "wffn_form_customizations": JSON.stringify(this.getFormStylingOptionsValue()),
                'wffn_optin_id': wfop.id,
                'wffn_form_field_width': JSON.stringify(this.form_field_width),
                '_nonce': wfop_action.nonce_form_save
            };
            this.wffn_ajax.ajax('wfop_form_save', data);
            this.wffn_ajax.success = function () {
                $('#modal-global-settings_success').iziModal('open');
                $(".wffn_save_btn_style").removeClass('is_busy');
            };
        }

        main() {
            let self = this;
            const wffnIZIDefault = {
                headerColor: '#6dbe45',
                background: '#efefef',
                borderBottom: false,
                width: 600,
                radius: 8,
                overlayColor: 'rgba(0, 0, 0, 0.35)',
                transitionIn: 'fadeInUp',
                transitionOut: 'fadeOut',
                navigateArrows: false,
                history: false,
                overlay: true,

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
                el: "#wffn_op_edit_vue_wrap",
                components: {
                    "vue-form-generator": VueFormGenerator.component,
                },
                created: function () {
                    this.enableEditor();
                },
                data: wffnBuilderCommons.applyFilters('wffn_js_optin_vue_data', {
                    current_template_type: this.selected_type,
                    selected_type: this.selected_type,
                    designs: this.designs,
                    design_types: this.design_types,
                    selected: this.selected,
                    view_url: wfop.view_url,
                    op_title: wfop.op_title,
                    selected_template: this.selected_template,
                    template_active: this.template_active,
                    temp_template_type: '',
                    model: wfop.custom_options,
                    temp_template_slug: '',
                    search_timeout: false,
                    filters: wfop.filters,
                    currentStepsFilter: wfop.currentStepsFilter,
                    modelAction: wfop_action.action_options,
                    modelLMS: wfop_action.action_options,
                    modelLifterLMS: wfop_action.action_options,
                    modelAffiliateWP: wfop_action.action_options,
                    modelWebhook: wfop_action.action_options,
                    is_wffn_pro_active: wfop.is_wffn_pro_active,
                    is_previewing: false,
                    schema: {
                        groups: this.getCustomSettingsSchema(),
                    },
                    schemaAction: {
                        groups: this.get_optinAction_fields()
                    },
                    schemaFusion: {
                        groups: this.get_optinAction_fields()
                    },
                    schemaWebhook: {
                        groups: this.get_webhook_fields()
                    },
                    schemaLMS: {
                        groups: this.get_lms_fields()
                    },                    
                    schemaLifterLMS: {
                        groups: this.get_lifterlms_fields()
                    },
                    schemaAffiliateWP: {
                        groups: this.get_affiliatewp_fields()
                    },
                    formOptions: {
                        validateAfterLoad: false,
                        validateAfterChanged: true
                    }
                }),
                methods: {
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
                    onSubmitActions: function (event) {
                        if (_.isEqual(false, this.$refs.action_ref.validate())) {
                            console.log('validation error');
                            return;
                        }
                        let tempSetting = JSON.stringify(this.modelAction);
                        tempSetting = JSON.parse(tempSetting);
                        let optinServiceForm = JSON.stringify(this.optinServiceForm());
                        optinServiceForm = JSON.parse(optinServiceForm);


                        let testEmail = false;
                        if (typeof event === 'string' && 'test' === event) {
                            testEmail = true;
                        }

                        if (testEmail === false) {
                            $(".wffn_save_btn_style").addClass('is_busy');
                            $(".email_test.button").removeClass('wffn_loader_global_save');
                        } else {
                            $(".email_test.button").addClass('is_busy');
                            $('#emailMsg').remove();
                        }

                        let data = {"data": tempSetting, "email_testing": testEmail, "optin_service_form": optinServiceForm, 'optin_id': wfop.id, '_nonce': wfop_action.nonce_actions_settings};
                        self.wffn_ajax.ajax('op_actions_settings_update', data);
                        self.wffn_ajax.success = function (rsp) {
                            if (typeof rsp === "string") {
                                rsp = JSON.parse(rsp);
                            }

                            if (testEmail === true) {
                                let msgClass = "error";
                                if (rsp.status === true) {
                                    msgClass = "updated";
                                }
                                $('#test-email').parents('.wrapper').append('<div id="emailMsg" class="' + msgClass + '">' + rsp.msg + '</div>');
                                $('#emailMsg').fadeIn(500).delay(4000).fadeOut(500);
                                $(".email_test.button").removeClass('is_busy');
                            }
                            if (testEmail === false) {
                                $('#modal-global-settings_success').iziModal('open');
                                $(".wffn_save_btn_style").removeClass('is_busy');
                            }
                        };
                        return false;
                    },
                    checkInArray: function (group, value) {

                        return jQuery.inArray(value, group) !== -1;
                    },

                    optinServiceForm: function () {
                        let fields = {};
                        let optinServiceForm = {};

                        if ($('#wffn_form_fields').length > 0) {
                            $('.wffn-optin-fields').each(function () {
                                fields[$(this).find('label').attr('for')] = $(this).find('select').val();
                            });
                        }

                        let enableInit = $("input[type=radio][name='optin_form_enable']:checked").val();
                        self.formBuilder = $('#optin-form-builder').find(':selected').val();

                        if (self.formBuilder !== '') {
                            optinServiceForm = {
                                'optin_form_enable': enableInit,
                                'formBuilder': self.formBuilder,
                                'formFields': self.formFields,
                                'fields': fields,
                            }
                        }
                        return optinServiceForm;
                    },

                    onSubmit: function () {

                        $(".wffn_save_btn_style").addClass('is_busy');
                        let tempSetting = JSON.stringify(this.model);
                        tempSetting = JSON.parse(tempSetting);
                        let data = {"data": tempSetting, 'optin_id': wfop.id, '_nonce': wfop.nonce_custom_settings};
                        self.wffn_ajax.ajax('op_custom_settings_update', data);

                        
                        self.wffn_ajax.success = function (rsp) {
                            if (typeof rsp === "string") {
                                rsp = JSON.parse(rsp);
                            }
                            $('#modal-global-settings_success').iziModal('open');
                            $(".wffn_save_btn_style").removeClass('is_busy');
                            $('.wffn_loader_global_save').removeClass('ajax_loader_show');
                        };
                        return false;
                    },
                    get_edit_link() {
                        let url = wfop.design_template_data[this.selected_type].edit_url;
                        if ('oxy' === this.selected_type && this.selected === 'oxy_1' ) {
                            let data = {"url": url, 'id': wfop.id, '_nonce': wfop.nonce_update_edit_url};
                            self.wffn_ajax.ajax('op_update_edit_url', data);
                            self.wffn_ajax.success = function (rsp) {
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
                        let title = wfop.texts.copy_success;
                        if (jQuery(event.target).attr('class') === 'wffn_copy_text scode') {
                            title = wfop.texts.shortcode_copy_success;
                        }
                        var getInput = event.target.parentNode.querySelector('.wffn-scode-copy input')
                        getInput.select();
                        document.execCommand("copy");
                        $("#modal-global-settings_success").iziModal('setTitle', title);
                        $("#modal-global-settings_success").iziModal('open');
                    },
                    get_builder_title() {
                        return wfop.design_template_data[this.selected_type].title;
                    },
                    get_button_text() {
                        return wfop.design_template_data[this.selected_type].button_text;
                    },
                    setTemplateType(template_type, col) {
                        Vue.set(this, 'current_template_type', template_type);
                        this.hide_tempate_dropdown();
                    },
                    setTemplate(selected, type, el = '') {
                        Vue.set(this, 'selected', selected);
                        Vue.set(this, 'selected_type', type);
                        this.template_active = 'yes';
                        return this.save('yes', el);
                    },

                    removeDesign(cb) {
                        let save_layout = {
                            'wfop_id': self.id,
                            '_nonce': wfop.nonce_remove_design,
                        };
                        self.wffn_ajax.ajax('op_remove_design', save_layout);

                        self.wffn_ajax.success = (rsp) => {
                            if (typeof cb == "function") {
                                cb(rsp);
                            }
                        };
                        self.wffn_ajax.error = () => {

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
                            'showCloseButton': true,
                            'showCancelButton': false,
                            'confirmButtonText': wffn.pageBuildersTexts[this.current_template_type].close_btn,
                            onClose: () => {
                                $(".wffn_import_template").removeClass('is_busy');
                                $(".wffn_template_sec").removeClass('wffn_template_importing');
                            }
                        });
                    },
                    showFailedActivate(warning_text) {
                        wffn_swal({
                            'html': warning_text,
                            'title': wffn.pageBuildersTexts[this.current_template_type].activate_fail,
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
                    importTemplate(template, type, el) {
                        let save_layout = {
                            'builder': type,
                            'template': template.slug,
                            'wfop_id': self.id,
                            '_nonce': wfop.nonce_import_design,
                        };
                        this.swalLoadiingText(wffn.i18n.importing);
                        self.wffn_ajax.ajax('op_import_template', save_layout);
                        self.wffn_ajax.success = (rsp) => {
                            if (true === rsp.status) {
                                this.setTemplate(template.slug, type, el);
                            } else {
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
                                } else {
                                    currentObj.restoreButtonState(currentObj.CurrenttargetElem);
                                    currentObj.showFailedActivate(rsp.data.message);
                                }
                            });

                    },

                    get_remove_template() {
                        wffn_swal({
                            'allowEscapeKey': false,
                            'showCancelButton': true,
                            'cancelButtonColor': '#e33b3b',
                            'confirmButtonColor': '#0073aa',
                            'confirmButtonText': wfop_localization.importer.remove_template.button_text,
                            'html': `<div class="wf_delete_modal_content">${wfop_localization.importer.remove_template.sub_heading}</div>`,
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
                                swalModalTitle.insertAdjacentHTML('beforebegin', '<div class="wffn-swal-modal-title">'+wfop_localization.importer.remove_template.modal_title+'</div>');
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
                        return 'https://templates-' + activeEditor + '.funnelswp.com/op/' + prevslug + '/';
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
                        let parent = current_target_element.closest('.wffn_template_sec');
                        parent.addClass('wffn_template_importing');
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
                        let save_layout = {
                            'selected_type': this.current_template_type,
                            'selected': this.selected,
                            'wfop_id': self.id,
                            'template_active': template_active,
                            '_nonce': wfop.nonce_save_design,
                        };

                        self.wffn_ajax.ajax('op_save_design', save_layout);
                        self.wffn_ajax.success = () => {
                            this.selected_type = this.current_template_type;
                            this.selected_template = this.designs[this.selected_type][this.selected];
                            $('#wfop_control > .wfop_p20').show();
                            this.restoreButtonState(el);
                            inst.previewClosed();
                        };
                        self.wffn_ajax.error = () => {
                            inst.previewClosed();
                        };

                    },
                    updateOptin: function () {
                        let optin_edit = "#wf_optin_edit_modal";
                        let parsedData = _.extend({}, wffnIZIDefault, {});

                        $(optin_edit).iziModal(
                            _.extend(
                                {
                                    onOpening: function (modal) {
                                        wffn_edit_optin_vue(modal);
                                    },
                                    onClosed: function () {
                                        $(optin_edit).iziModal('resetContent');
                                        $(optin_edit).iziModal('destroy');
                                        $(optin_edit).iziModal('close');
                                    },
                                },
                                parsedData
                            ),
                        );
                        $(optin_edit).iziModal('open');
                    },

                    setProductContent(editor) {
                        Vue.set(this.modelAction, editor.targetElm.id, editor.getContent());
                    },
                    enableEditor() {
                        let self = this;
                        setTimeout(() => {
                            let editor = $('.wffn_editor_field');
                            if (editor.length > 0) {
                                editor.each(function () {
                                    let editorID = $(this).find('textarea').attr('id');
                                    let editorConfig = wfop.tools.jsp(wfop.editorConfig);
                                    editorConfig.tinymce.selector = '#' + editorID;
                                    editorConfig.tinymce.init_instance_callback = (editor) => {
                                        editor.on('keyup', () => {
                                            self.setProductContent(editor);
                                        });
                                        editor.on('Change', () => {
                                            self.setProductContent(editor);
                                        });
                                    };
                                    wp.editor.initialize(editorID, editorConfig);
                                });
                            }
                        }, 300);
                    },

                },
                mounted: function () {
                    let self = this;
                    
                    $(document.body).on('change', 'input[type=radio][name="lead_enable_notify"]', function () {
                        _.delay(function () {
                            tinymce.remove('#lead_notification_body');
                            if ($('.mce-tinymce.mce-container.mce-panel').length === 0) {
                                self.enableEditor();
                            }
                        }, 200);

                    });
                    if (this.template_active === 'no') {
                        self.setTemplateType(this.GetFirstTemplateGroup());
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

            //Update optin page
            /** wffn_edit_optin_vue started  **/
            const wffn_edit_optin_vue = function (iziMod) {
                self.wffn_edit_optin_vue = new Vue(
                    {
                        mixins: [wfabtVueMixin],
                        components: {
                            "vue-form-generator": VueFormGenerator.component,
                        },
                        data: {
                            modal: false,
                            model: wfop.update_popups.values,
                            schema: {
                                fields: self.get_optin_vue_fields(),
                            },
                            formOptions: {
                                validateAfterLoad: false,
                                validateAfterChanged: true,
                            },
                            current_state: 1,
                        },
                        methods: {
                            updateOptin: function () {
                                if (false === this.$refs.update_optin_ref.validate()) {
                                    return;
                                }
                                let optin_id = wfop.id;
                                let data = JSON.stringify(self.wffn_edit_optin_vue.model);

                                if( self.wffn_edit_optin_vue.$el ) {
                                    let submitButton = self.wffn_edit_optin_vue.$el.querySelector( '.wf_funnel_btn.wf_funnel_btn_primary' );
                                    $( submitButton ).addClass( 'is_busy' );
                                }

                                self.wffn_ajax.ajax("update_optin_page", {"_nonce": wfop.wfop_edit_nonce, "optin_id": optin_id, 'data': data});
                                self.wffn_ajax.success = function (rsp) {
                                    if (_.isEqual(true, rsp.status)) {
                                        $(".bwf_breadcrumb ul li:last-child").html(rsp.title);
                                        $(".bwf_breadcrumb > span:last-of-type").html(rsp.title);
                                        $('.bwfan_page_header .bwfan_page_title').html(rsp.title);
                                        $(".bwf-header-bar .bwf-bar-navigation > span:last-child").html(rsp.title);
                                        Vue.set(self.wffn_edit_optin_vue.model, 'title', rsp.title);
                                        wfop.update_popups.label_texts.title.value = rsp.title;
                                        $('.wffn_page_title').text(rsp.title);

                                        $('#modal-global-settings_success').iziModal('open');
                                        $('#wf_optin_edit_modal').iziModal('close');


                                    }
                                };
                            },
                        },
                    }
                ).$mount('#part-update-optin');
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

                        $($this).find('.wffn-optin-forms-container .wffn-tab-content').hide();
                        $($this).find('.wffn-optin-forms-container .wffn-tab-content').eq(tabindex).addClass('wffn-activeTab');
                        $($this).find('.wffn-optin-forms-container .wffn-tab-content').eq(tabindex).show();
                    }
                );

                wfctb.eq(0).trigger('click');
            }
            return this.main;
        }


        /***Get choose form mode either existing or create a new one **/
        chooseOptinFormMode() {
            let self = this;

            self.formBuilder = $(this).find(':selected').val();

            $(document).ready(function () {
                if ($("input[type=radio][name='optin_form_enable']").length > 0) {
                    let enableInit = $("input[type=radio][name='optin_form_enable']:checked").val();
                    if (enableInit === 'true') {
                        $('.init_form').removeClass('wffn-hide');

                    }

                    $(document.body).on('change', 'input[type=radio][name="optin_form_enable"]', function () {
                        let enableInit = $("input[type=radio][name='optin_form_enable']:checked").val();
                        if (enableInit === 'true') {
                            $('.init_form').removeClass('wffn-hide');
                        } else {
                            $('.init_form').addClass('wffn-hide');
                        }
                    });
                }
            });

            $(document.body).on('click', '.wffn-tab-title', function (event) {
                if ($(event.target).hasClass('hide_bwf_btn')) {
                    $('.bwf_form_button').hide();
                } else {
                    $('.bwf_form_button').show();
                }

                if ($(event.target).hasClass('crm_only')) {
                    $('.action-crm-container').show();
                } else {
                    $('.action-crm-container').hide();
                }
            });

            $(document.body).on('click', 'input[name="optin_form_enable"]', function (event) {
                let enableInit = $("input[type=radio][name='optin_form_enable']:checked").val();
                if (enableInit === 'false') {
                    $('.bwf_form_button').show();
                } else {
                    $('.bwf_form_button').hide();
                }
            });
            $(document.body).on('change', '#wffn_form_fields select', function (event) {
                $('.bwf_form_button').show();
            });

            $(document.body).on('change', '#optin-form-builder', function () {
                $('.bwf_form_button').show();
                let currentBuilder = '';
                if (typeof wfop.optin_form_option.formBuilder !== "undefined") {
                    currentBuilder = wfop.optin_form_option.formBuilder;
                }
                self.formBuilder = $(this).find(':selected').val();
                if (($('.wffn-gen-form.button-primary').length === 0) && currentBuilder !== '' && currentBuilder === self.formBuilder) {
                    $('.wffn-map-fields, .wffn-paste-form-html').show();
                } else {
                    $('.wffn-map-fields, .wffn-paste-form-html').hide();
                }
                if (self.formBuilder !== '') {
                    $('.bwf_form_button').hide();
                    $('#wffn_lead_generation_code').val('');
                    $('.wffn-paste-form-html').show();

                }
            });
            if (typeof wfop.optin_form_option.formFields !== "undefined" && Object.prototype.hasOwnProperty.call(wfop.optin_form_option.formFields, 'form')) {
                let selVal = wfop.optin_form_option.formBuilder;
                self.formFields = wfop.optin_form_option.formFields;
                $("#optin-form-builder option[value='" + selVal + "']").prop('selected', true);
                $(".wffn-paste-form-html").show();
                $('#wffn_generate_form').prop("disabled", false);
                $('#wffn_generate_form').removeAttr("disabled");
                self.hideBoxUI();
                self.showMapUI();
                let inputText = wfop.optin_form_option.formFields.text;
                let inputSelect = wfop.optin_form_option.formFields.select;
                let inputTextarea = wfop.optin_form_option.formFields.textarea;
                let inputCheckbox = wfop.optin_form_option.formFields.radio_checkbox;
                let inputFields = inputText.concat(inputSelect, inputTextarea, inputCheckbox);
                self.prepareMapFieldsOutPut(inputFields);

            }
        }

        /** Choose form mode ends here **/

        /** Generate optin form from auto responders html **/


        generateForm() {
            let self = this;

            $(document.body).on('click', '#wffn_generate_form', function () {

                let htmlcode = $('#wffn_lead_generation_code').val();

                if ($(this).hasClass('reset_ready')) {
                    $('#optin-form-builder option:selected').prop("selected", false);
                    self.hideMapUI();
                    self.showBoxUI();
                    return;
                }
                if (_.isEmpty(htmlcode)) {
                    self.hideMapUI();
                    self.hideConnectButton();
                    $('.html_err_print').html(wfop.texts.html_err).show();
                    return;
                }
                self.htmlCode = htmlcode;
                htmlcode = htmlcode.replace(/(\s)?(onclick|onkeyup|onkeypress|onkeydown)="(.+?)"/g, ""), htmlcode = _.unescape(htmlcode);

                let parsedHtml = new html_parser_js(htmlcode);

                let formFields = parsedHtml.parse(htmlcode);
                if (_.isEmpty(formFields.form)) {
                    self.hideMapUI();
                    self.hideConnectButton();
                    $('.html_err_print').html(wfop.texts.html_err).show();
                    return;
                }

                if (_.isEmpty(formFields.form.action)) {
                    self.hideMapUI();
                    self.hideConnectButton();
                    $('.html_err_print').html(wfop.texts.html_err).show();
                    return;
                }

                $('.html_err_print').hide();
                self.formFields = formFields;
                let inputText = formFields.text;
                let inputSelect = formFields.select;
                let inputTextarea = formFields.textarea;
                let inputCheckbox = formFields.radio_checkbox;
                $('.wffn-map-fields, .wffn_form_fields, #wffn_optin_form_submit').show();
                $('#wffn_form_fields').empty();
                self.hideBoxUI();
                let inputFields = inputText.concat(inputSelect, inputTextarea, inputCheckbox);
                self.prepareMapFieldsOutPut(inputFields);

                $('.bwf_form_button').removeClass('wffn-hide').show();
                $('.wffn-optin-link, .wffn-update-fields').hide();
            });
        }

        prepareMapFieldsOutPut(inputFields) {
            let self = this;
            let fieldHead = '<div class="wffn-optin-fields field-wrap optin-map-table-head"><div class="fmap-head">' + wfop.field_map_table.label_head + '</div><div class="lmap-head">' + wfop.field_map_table.field_head + '</div></div>';
            $('#wffn_form_fields').append(fieldHead);
            for (let field_key in self.CustomFormFields) {
                let fieldHtml = '';
                fieldHtml += '<div class="wffn-optin-fields field-wrap"><label for="' + self.CustomFormFields[field_key].InputName + '">' + self.CustomFormFields[field_key].label + '</label>';
                let saved_field = _.has(wfop.optin_form_option.fields, self.CustomFormFields[field_key].InputName) ? wfop.optin_form_option.fields[self.CustomFormFields[field_key].InputName] : '';
                fieldHtml += '<select id="' + self.CustomFormFields[field_key].InputName + '"><option value="0">Select Field</option>';
                $.each(inputFields, function (fkey, fvalue) {
                    if (typeof fvalue !== 'undefined') {
                        let fieldName = fvalue.name;
                        fieldHtml += "<option " + (_.isEqual(fieldName, saved_field) ? 'selected' : '') + " value=" + fieldName + ">" + fieldName + "</option>";
                    }
                });
                fieldHtml += '</select></div>';
                $('#wffn_form_fields').append(fieldHtml);
            }
        }

        hideMapUI() {
            $('.wffn-map-fields').hide();
            $('#wffn_form_fields').empty();
        }

        showMapUI() {
            $('.wffn-map-fields, .wffn_form_fields, #wffn_optin_form_submit').show();
        }

        hideBoxUI() {
            $('#wffn_lead_generation_code').val('');
            $('#wffn_lead_generation_code').hide();
            $('.wffn-gen-form').addClass('reset_ready');
            $('.wffn-gen-form').removeClass('button-primary');
            $('.wffn-gen-form').addClass('button-secondary');
            $('.wffn-gen-form').text('Reset');
        }

        showBoxUI() {
            $('#wffn_lead_generation_code').show();
            $('.wffn-gen-form').removeClass('reset_ready');
            $('.wffn-gen-form').addClass('button-primary');
            $('.wffn-gen-form').removeClass('button-secondary');
            $('.wffn-gen-form').text('Continue');
        }

        hideConnectButton() {
            $('.bwf_form_button').hide();
        }

        /** Generate optin form from auto responders html ends here. **/


        /** Get form list ends here**/


        /** Html parsing of auto responder optin forms ends here **/

        /**
         * Updating optin starts
         */
        get_optin_vue_fields() {
            let update_optin = [
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

            for (let keyfields in update_optin) {
                let model = update_optin[keyfields].model;
                _.extend(update_optin[keyfields], wfop.update_popups.label_texts[model]);
            }
            return update_optin;
        }

        getCustomSettingsSchema() {
            //handling of localized label/description coming from php to form fields in vue
            let custom_settings_valid_fields = [
                {
                    type: "radios",
                    label: "",
                    model: "op_valid_enable",
                    values: () => {
                        return wfop_action.action_fileld.radio_fields;
                    },
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "",
                    model: "op_valid_text",
                    inputName: 'op_valid_text',
                    visible: (model) => {
                        return (model.op_valid_enable === 'true');
                    },
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "",
                    model: "op_valid_email",
                    inputName: 'op_valid_email',
                    visible: (model) => {
                        return (model.op_valid_enable === 'true');
                    },
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "",
                    model: "op_valid_phone",
                    inputName: 'op_valid_phone',
                    visible: (model) => {
                        return (model.op_valid_enable === 'true');
                    },
                },
            ];
            for (let keyfields in custom_settings_valid_fields) {
                let model = custom_settings_valid_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wfop.custom_setting_fields.fields, model)) {
                    $.extend(custom_settings_valid_fields[keyfields], wfop.custom_setting_fields.fields[model]);
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
                        return wfop.radio_fields;
                    },
                },
                {
                    type: "vueMultiSelect",
                    label: "",
                    model: "custom_redirect_page",
                    styleClasses: ['wffn_field_space multiselect_cs'],
                    required: true,
                    hint: wfop.custom_setting_fields.fields.search_hint,
                    selectOptions: {
                        multiple: false,
                        key: "id",
                        label: "name",
                        onSearch: function (searchQuery) {
                            let query = searchQuery;
                            $('.multiselect_cs .multiselect__spinner').show();
                            let no_page = wfop.custom_options.not_found;
                            if ($(".multiselect_cs .multiselect__content li.no_found").length === 0) {
                                $(".multiselect_cs .multiselect__content").append('<li class="no_found"><span class="multiselect__option">' + no_page + '</span></li>');
                            }
                            $(".multiselect_cs .multiselect__content li.no_found").hide();

                            if (query !== "" && query.length >= 3) {
                                clearTimeout(self.search_timeout);
                                self.search_timeout = setTimeout((query) => {
                                    let wp_ajax = new wp_admin_ajax();
                                    let product_query = {'term': query, '_nonce': wfop.nonce_page_search};
                                    wp_ajax.ajax('op_page_search', product_query);
                                    wp_ajax.success = (rsp) => {
                                        if (typeof rsp !== 'undefined' && rsp.length > 0) {
                                            wfop.custom_options.pages = rsp;
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
                if (Object.prototype.hasOwnProperty.call(wfop.custom_setting_fields.fields, model)) {
                    $.extend(custom_redirect_fields[keyfields], wfop.custom_setting_fields.fields[model]);
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
                if (Object.prototype.hasOwnProperty.call(wfop.custom_setting_fields.fields, model)) {
                    $.extend(custom_settings_custom_css_fields[keyfields], wfop.custom_setting_fields.fields[model]);
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
                if (Object.prototype.hasOwnProperty.call(wfop.custom_setting_fields.fields, model)) {
                    $.extend(custom_settings_custom_js_fields[keyfields], wfop.custom_setting_fields.fields[model]);
                }
            }

            var settings = [
                {
                    legend: wfop.custom_setting_fields.legends_texts.op_valid_field_label,
                    fields: custom_settings_valid_fields
                },
                {
                    legend: wfop.custom_setting_fields.legends_texts.custom_redirect,
                    fields: custom_redirect_fields
                },
                {
                    legend: wfop.custom_setting_fields.legends_texts.custom_css,
                    fields: custom_settings_custom_css_fields
                },
                {
                    legend: wfop.custom_setting_fields.legends_texts.custom_js,
                    fields: custom_settings_custom_js_fields
                },
            ];


            return wffnBuilderCommons.applyFilters('wffn_optin_settings_content', settings);
        }

        /** Get form fields ends here**/

        get_lms_fields() {
            let fields = [];
            let self = this;


            if (Object.prototype.hasOwnProperty.call(wfop_action, 'lms_active') && true == wfop_action.lms_active) {
                let lms_fields = [
                    {
                        type: "radios",
                        label: wfop_action.action_fileld.lms.lms_course,
                        model: "lms_course",
                        values: () => {
                            return wfop_action.action_fileld.radio_fields;
                        },
                    },
                    {
                        type: "vueMultiSelect",
                        label: wfop_action.action_fileld.lms.assign_ld_course,
                        model: "assign_ld_course",
                        styleClasses: 'multiselect_cs',
                        placeholder: wfop_action.action_fileld.lms.course_placeholder,
                        hint: wfop_action.action_fileld.lms.hint,
                        required: true,
                        selectOptions: {
                            multiSelect: false,
                            closeOnSelect: true,
                            showLabels: true,
                            searchable: true,
                            loading: false,
                            internalSearch: true,
                            optionsLimit: 5,
                            key: "id",
                            label: "name",
                            onSearch: function (searchQuery) {
                                let query = searchQuery;
                                $('.multiselect_cs .multiselect__spinner').show();
                                let no_page = wfop_action.action_fileld.lms.not_found;
                                if ($(".multiselect_cs .multiselect__content li.no_found").length === 0) {
                                    $(".multiselect_cs .multiselect__content").append('<li class="no_found"><span class="multiselect__option">' + no_page + '</span></li>');
                                }
                                $(".multiselect_cs .multiselect__content li.no_found").hide();
                                if (query !== "" && query.length >= 3) {
                                    clearTimeout(self.search_timeout);
                                    self.search_timeout = setTimeout(() => {
                                        let wp_ajax = new wp_admin_ajax();
                                        let product_query = {'term': searchQuery, 'variations': self.include_variations, '_nonce': wfop_action.nonce_course_search};
                                        self.wffn_ajax.ajax('course_search', product_query);
                                        self.wffn_ajax.success = (rsp) => {
                                            if (typeof rsp !== 'undefined' && rsp.length > 0) {
                                                wfop_action.action_options.courses = rsp;
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
                        values: function (model) {
                            return model.courses;
                        },
                        visible: function (model) {
                            return (model.lms_course === 'true');
                        },
                    },

                ];
                fields.push({
                    legend: wfop_action.action_fileld.lms.heading,
                    fields: lms_fields
                });
            }
            return fields;
        }

        get_lifterlms_fields() {

            let fields = [];
            let self = this;

            if (Object.prototype.hasOwnProperty.call(wfop_action, 'lifterlms_active') && true == wfop_action.lifterlms_active) {
                let lifterlms_fields = [
                    {
                        type: "radios",
                        label: wfop_action.action_fileld.lifterlms.lms_course,
                        model: "lifterlms_course",
                        values: () => {
                            return wfop_action.action_fileld.radio_fields;
                        },
                    },
                    {
                        type: "vueMultiSelect",
                        label: wfop_action.action_fileld.lifterlms.assign_ld_course,
                        model: "assign_lifter_course",
                        styleClasses: 'multiselect_cs',
                        placeholder: wfop_action.action_fileld.lifterlms.course_placeholder,
                        hint: wfop_action.action_fileld.lifterlms.hint,
                        required: true,
                        selectOptions: {
                            multiSelect: false,
                            closeOnSelect: true,
                            showLabels: true,
                            searchable: true,
                            loading: false,
                            internalSearch: true,
                            optionsLimit: 5,
                            key: "id",
                            label: "name",
                            onSearch: function (searchQuery) {
                                let query = searchQuery;
                                $('.multiselect_cs .multiselect__spinner').show();
                                let no_page = wfop_action.action_fileld.lifterlms.not_found;
                                if ($(".multiselect_cs .multiselect__content li.no_found").length === 0) {
                                    $(".multiselect_cs .multiselect__content").append('<li class="no_found"><span class="multiselect__option">' + no_page + '</span></li>');
                                }
                                $(".multiselect_cs .multiselect__content li.no_found").hide();
                                if (query !== "" && query.length >= 3) {
                                    clearTimeout(self.search_timeout);
                                    self.search_timeout = setTimeout(() => {
                                        let wp_ajax = new wp_admin_ajax();
                                        let product_query = {'term': searchQuery, 'variations': self.include_variations, '_nonce': wfop_action.nonce_lifterlms_course_search};
                                        self.wffn_ajax.ajax('lifterlms_course_search', product_query);
                                        self.wffn_ajax.success = (rsp) => {
                                            if (typeof rsp !== 'undefined' && rsp.length > 0) {
                                                wfop_action.action_options.courses = rsp;
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
                        values: function (model) {
                            return model.courses;
                        },
                        visible: function (model) {
                            return (model.lifterlms_course === 'true');
                        },
                    },

                ];
                fields.push({
                    legend: wfop_action.action_fileld.lifterlms.heading,
                    fields: lifterlms_fields
                });
            }
            return fields;
        }

        get_affiliatewp_fields() {

            let fields = [];
            let self = this;

            if (Object.prototype.hasOwnProperty.call(wfop_action, 'affiliatewp_active') && true == wfop_action.affiliatewp_active) {
                let affiliatewp_fields = [
                    {
                        type: "radios",
                        label: wfop_action.action_fileld.affiliatewp.enable,
                        model: "affiliatewp_id",
                        values: () => {
                            return wfop_action.action_fileld.radio_fields;
                        },
                    },

                ];
                fields.push({
                    legend: wfop_action.action_fileld.affiliatewp.heading,
                    fields: affiliatewp_fields
                });
            }
            return fields;
        }

        get_webhook_fields() {
            let fields = [];
            let self = this; 
                        
            let whook_fields = [
                {
                    type: "radios",
                    label: "Enable",
                    model: "op_webhook_enable",
                    values: () => {
                        return wfop_action.action_fileld.radio_fields;
                    },
                },  
                {
                    type: 'input',
                    inputType: 'url',
                    model: 'op_webhook_url',
                    label: wfop_action.action_fileld.webhook.fields.op_webhook_url.label,
                    placeholder: wfop_action.action_fileld.webhook.fields.op_webhook_url.placeholder,                     
                    //value: wfop_action.action_options.op_webhook_url  
                    visible: (model) => {
                        return (model.op_webhook_enable === 'true');
                    }                     
                }
            ];
            fields.push({
                legend: wfop_action.action_fileld.webhook.heading,
                fields: whook_fields
            });

            return fields;
        }

        get_optinAction_fields() {            
            let lead_notifications_fields = [
                {
                    type: "radios",
                    label: "",
                    model: "lead_enable_notify",
                    values: () => {
                        return wfop_action.action_fileld.radio_fields;
                    },
                },
                {
                    type: "input",
                    inputType: 'text',
                    label: "",
                    model: "lead_notification_subject",
                    inputName: 'lead_notification_subject',
                    placeholder: '',
                    visible: (model) => {
                        return (model.lead_enable_notify === 'true');
                    },
                },
                {
                    type: "textArea",
                    rows: 8,
                    label: "",
                    model: "lead_notification_body",
                    inputName: 'lead_notification_body',
                    id: 'lead_notification_body',
                    styleClasses: "wffn_editor_field wffn_lead_hint",
                    placeholder: '',
                    hint: '',
                    visible: (model) => {
                        return (model.lead_enable_notify === 'true');
                    },
                },
                {
                    type: "input",
                    inputType: 'text',
                    label: "",
                    model: "test_email",
                    inputName: 'test_email',
                    placeholder: '',
                    visible: function (model) {
                        return (model.lead_enable_notify === 'true');
                    }

                },
                {
                    type: "button",
                    label: "fgjjghj",
                    visible: function (model) {
                        return (model.lead_enable_notify === 'true');
                    }
                },

                {
                    type: "radios",
                    styleClasses: "gl_radio",
                    label: "",
                    model: "admin_email_notify",
                    values: () => {
                        return wfop_action.action_fileld.radio_fields;
                    },
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "",
                    model: "op_admin_email",
                    inputName: 'op_admin_email',
                    required: true,
                    placeholder: "",
                    validator: ["required"],
                    visible: (model) => {
                        return (model.admin_email_notify === 'true');
                    },
                }
            ];
            for (let keyfields in lead_notifications_fields) {
                let model = lead_notifications_fields[keyfields].model;
                if (Object.prototype.hasOwnProperty.call(wfop_action.action_fileld.email_notify.fields, model)) {
                    $.extend(lead_notifications_fields[keyfields], wfop_action.action_fileld.email_notify.fields[model]);
                }
            }

            let fields = [
                {
                    legend: wfop_action.action_fileld.email_notify.legends_texts.email_notification,
                    fields: lead_notifications_fields
                },
            ]


            return fields;
        }

        dynamic_font_family() {
            let family = $('.wffn_font_family');
            let a = [];
            if (family.length > 0) {
                family.each(function () {
                    let v = $(this).val();
                    if (v === 'default') {
                        return;
                    }
                    v = "family=" + encodeURIComponent(v).replace(/%20/g, "+") + ':wght@400;700';
                    a.push(v);
                });
                if (a.length > 0) {
                    let url = a.join('&');

                    let link_rel = $('#wffn_google_font_optin');
                    if (link_rel.length > 0) {
                        link_rel.attr('href', 'https://fonts.googleapis.com/css2?' + url);
                    }
                }
            }

        }

        /*** End optin action tab ***/
    }

    class wfop_layouts {
        constructor(builder) {
            this.add_field_model_open = false;
            this.edit_field_model_open = false;
            this.step_name = 'single_step';
            this.current_edit_field = {};
            this.field_index = null;
            let el = document.getElementById("wfop_layout_container");
            if (el != null) {
                this.builder = builder;
                this.steps = builder.layout.steps;
                this.input_fields = builder.layout.input_fields;
                this.available_fields = builder.layout.available_fields;
                this.fieldsets = builder.layout.fieldsets;
                this.available_steps = ['single_step', 'two_step', 'third_step'];
                this.model();
                this.main();
                this.editFieldVue();
            }
        }

        main() {
            let self = this;
            this.layout_vue = new Vue({
                    el: "#wfop_layout_container",
                    created: function () {
                        this.enableSortable();
                        wfop.hooks.doAction('wfop_layout_created', this);
                        this.enableTabs();
                    },
                    methods: {
                        enableTabs() {
                            let $this = this;
                            setTimeout(() => {
                                $(document).on('click', ".wfop_template_tabs", function () {
                                    if ($(this).hasClass('wfop_active_tabs')) {
                                        return;
                                    }
                                    let slug = $(this).data('slug');
                                    $this.show_template_frame(slug);
                                });
                            }, 1500);
                        },
                        addNewStep() {
                            if (self.available_steps.indexOf(this.current_step) > -1) {
                                let field_container = [{'class': '', 'fields': [], 'is_default': 'no'}];
                                if ('single_step' === this.current_step) {
                                    this.set_template('two_step');
                                    Vue.set(this.steps.two_step, 'active', 'yes');
                                    Vue.set(this.fieldsets, 'two_step', field_container);
                                } else if ('two_step' === this.current_step) {
                                    this.set_template('third_step');
                                    Vue.set(this.steps.third_step, 'active', 'yes');
                                    Vue.set(this.fieldsets, 'third_step', field_container);
                                }
                            }
                        },
                        deleteStep(step) {
                            if (self.available_steps.indexOf(step) > -1) {
                                if ('two_step' === step) {
                                    this.moveStepField('two_step');
                                    this.set_template('single_step');
                                    Vue.set(this.steps.two_step, 'active', 'no');
                                } else if ('third_step' === step) {
                                    this.moveStepField('third_step');
                                    this.set_template('two_step');
                                    Vue.set(this.steps.third_step, 'active', 'no');
                                }
                            }
                        },
                        set_template(template) {
                            Vue.set(this, 'current_step', template);
                            this.enableSortable();
                            this.show_template_frame(template);
                        },
                        show_template_frame(template) {
                            setTimeout(() => {
                                let frame = $(".single_step_template[data-slug='" + template + "']");
                                let tabs = $(".wfop_template_tabs[data-slug='" + template + "']");
                                if (frame.length > 0) {
                                    $(".wfop_template_tabs").removeClass('wfop_active_tabs');
                                    $(".single_step_template").hide();
                                    tabs.addClass('wfop_active_tabs');
                                    frame.show();
                                }
                            }, 0, template);
                        },
                        enableSortable() {
                            setTimeout(() => {
                                // for item drag
                                let placeholder = 'wfop_save_btn_style ui-sortable-placeholder ui-state-highlight';
                                wfop.sortable('.template_field_container', this.StartSortable, this.StopSortable, this.hoverDrag, '', '', placeholder);
                            }, 300);
                        },

                        StartSectionSortable(event) {
                            $(event.srcElement).parents(".wfop_field_container").addClass('highlight_field_container');
                        },

                        StopSectionSortable(event) {
                            $(event.srcElement).parents(".wfop_field_container").removeClass('highlight_field_container');
                        },
                        hoverDrag(type, event) {
                            let placeholder = $(event.target).find('.template_field_placeholder_tbl');
                            if ("in" === type) {
                                if ($(event.srcElement).parents(".wfop_field_container")[0] !== $(event.target).parents(".wfop_field_container")[0]) {
                                    $(event.target).parents(".wfop_field_container").addClass('highlight_field_container');

                                    if (placeholder.length > 0) {
                                        placeholder.hide();
                                    }
                                }
                            } else {
                                if (placeholder.length > 0) {
                                    placeholder.show();
                                }
                                $(event.target).parents(".wfop_field_container").removeClass('highlight_field_container');
                            }
                        },
                        /**
                         * this function when element drag internal
                         * @param event
                         * @param element
                         * @constructor
                         */
                        StartSortable(event, element) {
                            let text_placeholder = $('.wfop_save_btn_style.ui-sortable-placeholder.ui-state-highlight');
                            if ($(element.item[0]).length > 0 && text_placeholder.length > 0) {
                                text_placeholder.width($(element.item[0]).width());
                                text_placeholder.html("<span class='wfop_placeholder_text_here'>placeholder</span>");
                            }
                            wfop.addClass('.wfop_field_container', 'activate_dragging_field');
                        },
                        /**
                         * Reposition of fields array
                         * @param event HTMLElementEvent
                         * @param element
                         * @constructor
                         */
                        StopSortable(event) {

                            $('.wfop_field_container').removeClass('activate_dragging_field highlight_field_container');
                            let new_parent = $(event.toElement).parents('.wfop_field_container');
                            let old_parent = $(event.target);
                            let unique_id;
                            let listElement = $(event.toElement);
                            if (event.toElement.localName === 'span') {
                                listElement = $(event.toElement).parent('div');
                            }
                            unique_id = listElement.attr('data-id');
                            let step_name = new_parent.attr('step-name');
                            let old_step_name = old_parent.attr('step-name');
                            let new_index = new_parent.attr('field-index');
                            let old_index = old_parent.attr('field-index');
                            if (step_name !== old_step_name) {
                                listElement.remove();
                                this.parentSwitching(old_step_name, step_name, old_index, new_index, unique_id);
                            } else {
                                // Same step Switching
                                if (new_index !== old_index) {
                                    listElement.remove();
                                    this.updateInput(step_name, old_index, new_index, unique_id);

                                }
                            }
                        },

                        dragStart(event) {
                            self.dragStart(event);
                        },
                        allowDrop(event) {
                            self.allowDrop(event);
                        },
                        dragEnd(event) {
                            self.dragEnd(event);
                        },
                        dragEnter(event) {
                            self.dragEnter(event);
                        },
                        dragLeave(event) {
                            self.dragLeave(event);
                        },


                        /**
                         * step_name means single step of multistep
                         * @param event
                         * @param section fieldset section
                         * @param step_name
                         */
                        drop(event, step_name, section) {

                            let el_data = self.drop(event);
                            if (el_data === false) {
                                return null;
                            }
                            let unique_id = el_data[0];
                            let input_section = el_data[1];

                            if (input_section === '')
                                return null;

                            this.addInput(step_name, section, unique_id, input_section);
                        },
                        /**
                         * Add input field this work when person drag element from right to section container
                         * @param step_name
                         * @param section
                         * @param unique_id
                         * @param input_section
                         * @returns {null}
                         */
                        addInput(step_name, section, unique_id, input_section) {
                            let allowEnter = true;

                            let sub_section = $(".single_step").find(".wfop_field_container[field-index=" + section + "]");
                            if (sub_section.length > 0) {
                                if (sub_section.find('.wfop_item_drag[data-id=' + unique_id + ']').length > 0) {
                                    allowEnter = false;
                                }
                            }

                            if (!allowEnter) {
                                return;
                            }

                            let fields;
                            fields = wfop.tools.jsp(this.input_fields[input_section][unique_id]);
                            fields.id = unique_id;
                            fields.field_type = input_section;
                            this.pushFieldInStep(step_name, section, fields);
                            Vue.delete(this.input_fields[input_section], unique_id);

                            $(".wfop_field_container").removeClass('highlight_field_container');
                            $(".wfop_field_container").removeClass('highlight_field_container activate_dragging_field');
                        },
                        getFieldFromSection(step_name, section, unique_id) {
                            let index = this.findFieldIndex(step_name, section, unique_id);
                            if (index == null)
                                return null;

                            let fields = wfop.tools.jsp(this.fieldsets[step_name][section].fields[index]);
                            if (!wfop.tools.hp(this.fieldsets[step_name][section], 'fields')) {
                                Vue.set(this.fieldsets[step_name][section], 'fields', []);
                            }
                            return fields;
                        },
                        pushFieldInStep(step_name, section, fields) {
                            if (!wfop.tools.hp(this.fieldsets[step_name][section], 'fields')) {
                                Vue.set(this.fieldsets[step_name][section], 'fields', []);
                            }
                            let step_section_fields = wfop.tools.jsp(this.fieldsets[step_name][section].fields);
                            step_section_fields.push(fields);

                            Vue.set(this.fieldsets[step_name][section], 'fields', step_section_fields);
                            this.hideFieldContainerPlaceholder(step_name, section);
                        },
                        parentSwitching(old_step_name, step_name, old_index, new_index, unique_id) {
                            let fields = this.getFieldFromSection(old_step_name, old_index, unique_id);
                            if (null !== fields) {
                                this.pushFieldInStep(step_name, new_index, fields);
                                let old_section_fields = wfop.tools.jsp(this.fieldsets[old_step_name][old_index].fields);
                                Vue.set(this.fieldsets[old_step_name][old_index], 'fields', old_section_fields);
                            }
                        },

                        /**
                         * Update field this work when person drag element btw section container
                         * @param step_name
                         * @param old_index
                         * @param new_index
                         * @param unique_id
                         * @returns {null}
                         */
                        updateInput(step_name, old_index, new_index, unique_id) {
                            let fields = this.getFieldFromSection(step_name, old_index, unique_id);

                            if (null == fields) {
                                return;
                            }
                            this.pushFieldInStep(step_name, new_index, fields);
                        },
                        /**
                         * Move two step section field back to input fields
                         * @returns {boolean}
                         */
                        moveStepField(current_step) {

                            wfop.hooks.doAction('manage_step_field', wfop.tools.jsp(current_step), this);
                            let section_fields = wfop.tools.jsp(this.fieldsets[current_step]);
                            if (typeof section_fields === "undefined" || wfop.tools.ol(section_fields) === 0) {
                                return;
                            }
                            for (let step in section_fields) {
                                if (wfop.tools.ol(section_fields[step].fields) === 0) {
                                    continue;
                                }
                                let fields = section_fields[step].fields;
                                for (let i = 0; i < fields.length; i++) {
                                    let id = fields[i].id;
                                    let field_type = fields[i].field_type;
                                    Vue.set(this.input_fields[field_type], id, fields[i]);
                                }
                                Vue.set(this.fieldsets[current_step][step], 'fields', []);
                            }
                        },
                        /**
                         * Remove field from steps objects
                         * @param step_name
                         * @param unique_id input field ID
                         * @param field_type Means basic or advanced
                         * @param section  section index number 0,1,2
                         * @returns {null|true}
                         */
                        removeField(step_name, unique_id, field_type, section, event) {
                            event.stopPropagation();

                            let index = this.findFieldIndex(step_name, section, unique_id);
                            if (index == null) {
                                return null;
                            }

                            let step_section_fields = wfop.tools.jsp(this.fieldsets[step_name][section].fields);
                            let fields = step_section_fields[index];
                            step_section_fields.splice(index, 1);

                            Vue.set(this.fieldsets[step_name][section], 'fields', step_section_fields);
                            if (wfop.tools.ol(fields) > 0) {
                                Vue.set(this.input_fields[field_type], unique_id, fields);
                            }

                            this.showFieldContainerPlaceholder(step_name, section);
                            return true;
                        },

                        /**
                         * Find index of element in steps array
                         * @param step_name
                         * @param unique_id
                         * @param section
                         * @returns {*}
                         */
                        findFieldIndex(step_name, section, unique_id) {
                            if (!wfop.tools.hp(this.fieldsets[step_name][section], 'fields')) {
                                this.fieldsets[step_name][section].fields = [];
                                return null;
                            }

                            let out = wfop.tools.jsp(this.fieldsets[step_name][section].fields);
                            if (out.length === 0) {
                                return null;
                            }
                            for (let i = 0; i < out.length; i++) {
                                if (out[i].id === unique_id) {
                                    return i;
                                }
                            }
                            return null;
                        },

                        prepareErrorMsg(required_fields, separator = '') {
                            let msg = [];

                            for (let key in required_fields) {
                                msg.push(required_fields[key]);
                            }
                            return msg.join(separator);
                        },
                        getRequiredField() {
                            return wfop.tools.jsp(wfop_localization.fields.input_field_error);
                        },
                        getEmptySteps() {
                            return wfop.tools.jsp(wfop_localization.fields.steps_error_msgs);
                        },
                        /**
                         *Return Sorted all input fields from field container
                         * @returns Object steps and remaining required fied
                         *
                         */

                        getSortedElement() {
                            let self = this;
                            let required_fields = this.getRequiredField();
                            let empty_steps_errors = this.getEmptySteps();
                            let empty_steps = {};

                            let templates = $('.single_step_template');
                            let new_step_field = {'single_step': [], 'two_step': [], 'third_step': []};
                            if (templates.length > 0) {
                                templates.each(function () {
                                    let new_section = 0;
                                    let step_name = $(this).attr('data-slug');

                                    /*if ($(this).find(".wfop_item_drag").length === 0) {
										empty_steps[step_name] = empty_steps_errors[step_name];
										return;
									}*/ //Commented this to save the next step having no fields

                                    if (!wfop.tools.hp(self.fieldsets, step_name)) {
                                        empty_steps[step_name] = empty_steps_errors[step_name];
                                        return;
                                    }
                                    let temp_step_data = wfop.tools.jsp(self.fieldsets[step_name]);

                                    if (0 === temp_step_data.length) {
                                        empty_steps[step_name] = empty_steps_errors[step_name];
                                        return;
                                    }

                                    let field_container = $(this).find('.wfop_field_container');

                                    if (field_container.length > 0) {
                                        field_container.each(function () {
                                            let el = $(this);
                                            let section = el.attr('field-index');// field set index
                                            let fields_el = el[0].querySelectorAll('.wfop_item_drag');

                                            if (false === wfop.tools.hp(new_step_field, step_name)) {
                                                new_step_field[step_name] = wfop.tools.jsp(temp_step_data);
                                            }
                                            /*if (fields_el.length === 0) {
												return;
											}*/

                                            new_step_field[step_name][new_section] = wfop.tools.jsp(temp_step_data[section]);
                                            new_step_field[step_name][new_section].fields = [];
                                            let steps_fields = wfop.tools.jsp(temp_step_data[section].fields);
                                            fields_el.forEach((ele) => {
                                                let id = ele.getAttribute('data-id');

                                                if (wfop.tools.hp(required_fields, id)) {
                                                    delete required_fields[id];
                                                }
                                                let index = self.findFieldIndex(step_name, section, id);
                                                if (index !== null) {
                                                    let field = steps_fields[index];
                                                    new_step_field[step_name][new_section].fields.push(field);
                                                }
                                            });
                                            new_section++;
                                        });
                                    }
                                });
                            }

                            return {
                                'fieldsets': new_step_field,
                                'required': required_fields,
                                'empty_steps': empty_steps,
                            };
                        },

                        showStepsError(empty_step) {
                            let empty_step1 = empty_step;


                            if (Object.keys(empty_step1).length !== 0 && Object.prototype.hasOwnProperty.call(empty_step1, "third_step")) {
                                delete empty_step1.third_step;
                            }


                            let error_msg = this.prepareErrorMsg(empty_step1, ' and ');

                            error_msg += " " + wfop_localization.fields.empty_step_error;

                            wfop.swal({
                                'text': error_msg,
                                'title': wfop_localization.fields.validation_error,
                                'type': 'error',
                                'confirmButtonText': wfop_localization.importer.close_prompt_text,
                                'showCancelButton': false
                            });
                            return false;
                        },
                        /**
                         * Save Layout data into db
                         * @returns {boolean}
                         */
                        save_template() {
                            
                            let sorted_steps = this.getSortedElement();
                            let empty_step = sorted_steps.empty_steps;
                            let step_length = wfop.tools.ol(empty_step);
                            if (step_length > 0) {
                                if (step_length === 1) {
                                    let last_step = $('.wfop_step_heading .wfop_template_tabs:last').data('slug');
                                    if (!wfop.tools.hp(empty_step, last_step)) {
                                        this.showStepsError(empty_step);
                                        return false;
                                    }

                                } else {
                                    this.showStepsError(empty_step);
                                    return false;
                                }
                            }
                            let required_field = sorted_steps.required;
                            if (wfop.tools.ol(required_field) > 0) {

                                let error_msg = this.prepareErrorMsg(required_field);
                                wfop.swal({
                                    'text': error_msg,
                                    'title': 'Validation Error',
                                    'type': 'error',
                                    'confirmButtonText': wfop_localization.global.confirm_button_text,
                                    'showCancelButton': false
                                });
                                return false;
                            }

                            /**
                             * Check optin email present in single step. If no then show alert message
                             */
                            let wp_ajax = new wfop.ajax();
                            let save_layout = {
                                'steps': wfop.tools.jsp(this.steps),
                                'fieldsets': sorted_steps.fieldsets,
                                'wfop_id': wfop.id,
                                'current_step': this.current_step
                            };
                            wp_ajax.ajax('save_layout', save_layout);
                            $('.bwf_form_submit .wfop_save_btn_style').addClass('is_busy');
                            wp_ajax.success = (rsp) => {
                                wfop.show_data_save_model(rsp.msg);
                                $('.bwf_form_submit .wfop_save_btn_style').removeClass('is_busy');
                                $('#modal-global-settings_success').iziModal('open');
                            };
                        },
                        addField() {
                            self.modal_add_field.iziModal('open');
                        },

                        /**
                         * Open edit field model whit default data
                         * @param step_name
                         * @param section
                         * @param index
                         * @returns {*}
                         */
                        editField(step_name, section, index) {
                            let editFieldVue = self.editFieldVue();
                            let field = wfop.tools.jsp(this.fieldsets[step_name][section].fields[index]);
                            editFieldVue.current_field_id = field.id;
                            self.current_edit_field = {'step_name': step_name, 'index': index, 'section': section, 'field': field};
                            let exclude_id = [];
                            let exclude_type = [];
                            if ($.inArray(field.id, exclude_id) > -1 || $.inArray(field.type, exclude_type) > -1) {
                                editFieldVue.model_sub_title = '';
                                editFieldVue.edit_model_field_label = '';
                                editFieldVue.model_field_type = '';
                            } else {
                                editFieldVue.edit_model_field_label = field.label;

                                editFieldVue.model_sub_title = wfop_localization.fields.field_types_label + " : " + field.type;

                            }
                            editFieldVue.setData();
                        },
                        deleteCustomFieldAjax(section, index, status, vthis, cb) {
                            if( ! status ) {
                                return;
                            }
                            Vue.delete(vthis.input_fields[section], index);
                            Vue.delete(vthis.available_fields[section], index);

                            let wp_ajax = new wfop.ajax();

                            let add_query = {'section': section, 'index': index, 'wfop_id': wfop.id};
                            wp_ajax.ajax('delete_custom_field', add_query);
                            wp_ajax.success = (rsp) => {
                                if (rsp.status === true) {
                                    self.addField(rsp.data);
                                    wfop.show_data_save_model(rsp.msg);
                                    $('#modal-global-settings_success').iziModal('open');
                                }
                                if( 'function' == typeof cb ) {
                                    cb(rsp);
                                }
                            };

                        },
                        /**
                         * Delete custom created fields
                         * @param section basic or advanced
                         * @param index i
                         * @param label String
                         */
                        deleteCustomField(section, index, label) {
                            let vthis = this;

                            let action = wfop.swal({
                                'html': `<div class="wf_delete_modal_content">${wfop_localization.fields.delete_c_field_sub_heading_1} <strong>${label}</strong> ${wfop_localization.fields.delete_c_field_sub_heading_2}</div>`,
                                'confirmButtonText': wfop_localization.fields.yes_delete_the_field,
                                'cancelButtonText': wfop_localization.global.cancel_button_text,
                                'showCancelButton': true,
                                'showCloseButton': true,
                                reverseButtons: true,
                                onOpen: () => {
                                    const swalModalTitle = document.querySelector('.swal2-header');
                                    swalModalTitle.insertAdjacentHTML('beforebegin', '<div class="wffn-swal-modal-title">'+wfop_localization.fields.delete_c_field_title+'</div>');
                                },
                                'preConfirm': function(status) {
                                    $('button.swal2-cancel.swal2-styled').removeAttr('disabled');
                                    return new Promise((resolve) => {
                                        vthis.deleteCustomFieldAjax(section, index, status, vthis, (rsp) => {
                                            resolve(rsp);
                                        });
                                    });
                                }
                            });
                            action.catch((err) => {
                                console.log("Delete Custom Field Error:", err);
                            });


                        },

                        showFieldContainerPlaceholder(step_name, section) {
                            let el = $('.template_field_container.' + step_name + '[field-index=' + section + ']');
                            if (el.length > 0) {
                                let fields = el.find('.wfop_item_drag');
                                if (fields.length === 0) {
                                    el.find('.template_field_placeholder_tbl').show();
                                }
                            }

                        },
                        hideFieldContainerPlaceholder(step_name, section) {
                            let el = $('.template_field_container.' + step_name + '[field-index=' + section + ']');
                            if (el.length > 0) {
                                el.find('.template_field_placeholder_tbl').hide();
                            }
                        },

                    },
                    data: {
                        current_step: this.builder.layout.current_step,
                        steps: this.steps,
                        fieldsets: this.fieldsets,
                        input_fields: this.input_fields,
                        available_fields: this.available_fields,
                        required_fields: wfop_localization.fields.input_field_error,
                        error: [],
                        noofstep: 1,
                        error_msg: {},
                    }
                }
            );
            return this.layout_vue;
        }

        defaultStepObj(number) {
            return {'name': 'Step', "slug": 'step_' + number, 'friendly_name': 'Step ' + number, 'active': 'yes'};
        }

        /**
         * Work when user drag field from available fields
         * @param ev
         */
        dragStart(ev) {
            wfop.addClass('.wfop_field_container', 'activate_dragging_field');
            ev.dataTransfer.setData("unique_id", ev.target.id);
            ev.dataTransfer.setData("section_name", ev.target.getAttribute('data-input-section'));
        }

        /**
         * Work when user drag field from available fields
         * @param ev
         */
        dragEnd() {
            wfop.removeClass('.wfop_field_container', 'activate_dragging_field');
            wfop.removeClass('.wfop_field_container', 'highlight_field_container');
        }

        drop(ev) {
            ev.preventDefault();
            let unique_id = ev.dataTransfer.getData("unique_id");
            if (unique_id === ev.target.id) {
                return false;
            }
            let section_name = ev.dataTransfer.getData("section_name");
            this.layout_vue.enableSortable();
            return [unique_id, section_name];
        }

        allowDrop(ev) {
            ev.preventDefault();
        }

        /**
         * Work when user drag field from available fields
         * @param ev
         */
        dragEnter(ev) {
            $(ev.target).parents('.wfop_field_container').removeClass(ev.target, 'activate_dragging_field');
            $(ev.target).parents('.wfop_field_container').addClass('highlight_field_container');
        }

        /**
         * Work when user drag field from available fields
         * @param ev
         */
        dragLeave(ev) {
            $(ev.target).parents('.wfop_field_container').addClass(ev.target, 'activate_dragging_field');
            $(ev.target).parents('.wfop_field_container').removeClass('highlight_field_container');
        }


        getSection(step_name, field_index) {
            if (wfop.tools.hp(this.layout_vue.fieldsets, step_name) && wfop.tools.hp(this.layout_vue.fieldsets[step_name], field_index)) {
                return wfop.tools.jsp(this.layout_vue.fieldsets[step_name][field_index]);
            }
            return null;
        }

        /**
         * Adding custom field to advanced section
         * @param data
         */
        addField(data) {
            if (wfop.tools.ol(data) > 0) {
                let unique_id = data.unique_id;
                let field_type = data.field_type;
                Vue.set(this.layout_vue.input_fields[field_type], unique_id, data);
                Vue.set(this.layout_vue.available_fields[field_type], unique_id, data);
            }
        }

        getEditableFields() {
            let fields = [
                {
                    type: "input",
                    inputType: "text",
                    label: wfop_localization.fields.label_field_label,
                    model: "label",
                    required: false,
                    validator: VueFormGenerator.validators.string

                },
                {
                    type: "input",
                    inputType: "text",
                    label: wfop_localization.fields.options_field_label,
                    model: "options",
                    required: true,
                    validator: VueFormGenerator.validators.string,
                    visible: (model) => {
                        return ($.inArray(model.field_type, ['select', 'radio']) > -1);
                    }
                },
                {
                    type: "input",
                    inputType: "text",
                    label: wfop_localization.fields.placeholder_field_label,
                    model: "placeholder",
                    validator: VueFormGenerator.validators.string,
                    visible: (model) => {
                        return !(($.inArray(model.field_type, ['radio', 'checkbox', 'hidden']) > -1));
                    },
                },
                {
                    type: "input",
                    inputType: "text",
                    label: wfop_localization.fields.default_field_label,
                    model: "default",
                    validator: VueFormGenerator.validators.string,
                    visible: (model) => {
                        return (model.field_type !== 'checkbox');
                    }
                },
                {
                    type: "select",
                    inputType: "select",
                    label: wfop_localization.fields.default_field_label,
                    model: "default",
                    selectOptions: {hideNoneSelectedText: true},
                    values: () => {
                        return wfop.tools.jsp(wfop_localization.fields.default_field_checkbox_options);
                    },
                    visible: (model) => {
                        return (model.field_type === 'checkbox');
                    }
                },
                {
                    type: "radios",
                    label: wfop_localization.fields.radio_field_alignment,
                    model: "radio_alignment",
                    values: () => {
                        return wfop.tools.jsp(wfop_localization.fields.field_radio_alignment_options);
                    },
                    visible: (model) => {
                        return (model.field_type === 'radio');
                    },
                },
            ];

            fields = wfop.hooks.applyFilters('wfop_editable_fields', fields);

            let required = {
                type: "checkbox",
                label: wfop_localization.fields.required_field_label,
                model: "required",
                styleClasses: 'wfop_required_cls',
                default: true,
                visible: (model) => {
                    return (model.field_type !== 'hidden' && ($.inArray(model.field_type, ['product', 'wfop_html']) < 0));
                }
            };

            fields.push(required);

            let temp_required = {
                type: "checkbox",
                label: 'wfop_initiator_field',
                model: "temp_required",
                styleClasses: 'wfop_initiator_field',
                default: true,
            };

            fields.push(temp_required);

            let phoneValidation = {
                type: "checkbox",
                label: wfop_localization.fields.enable_phone_validation,
                model: "phone_validation",
                styleClasses: 'wfop_required_cls',
                default: false,
                visible: (model) => {
                    return (model.field_type === 'tel');
                }
            };

            fields.push(phoneValidation);

            return fields;
        }

        updateField(data) {
            if (wfop.tools.ol(data) === 0) {
                return false;
            }

            if (wfop.tools.hp(this.layout_vue.fieldsets, data.step_name) && wfop.tools.hp(this.layout_vue.fieldsets[data.step_name][data.section], 'fields')) {
                let myoptions = {};
                let options = wfop.tools.jsp(data.field.options);

                if (wfop.tools.ol(options) > 0) {
                    for (let i in options) {
                        let v = options[i];
                        v = v.trim();
                        myoptions[v] = v;
                    }
                    data.field.options = myoptions;
                }
                Vue.set(this.layout_vue.fieldsets[data.step_name][data.section].fields, data.index, data.field);
            }
        }

        addFieldVue(modal) {
            if (this.add_field_model_open === true) {
                return;
            }
            this.add_field_model_open = true;
            let self = this;

            this.add_field_vue = new Vue({
                    'el': '#add-field-form',
                    components: {
                        "vue-form-generator": VueFormGenerator.component
                    },
                    methods: {
                        defaultModel() {
                            return {
                                label: '',
                                placeholder: "",
                                field_type: 'text',
                                section_type: 'advanced',
                                options: '',
                                default: '',
                                enable_time: false,
                                preferred_time_format: '12',
                                show_custom_field_at_thankyou: false,
                                show_custom_field_at_email: false,
                                required: true,
                                width: 'wffn-sm-100'
                            };
                        },
                        onSubmit() {
                            if( this.$el ) {
                                let submitButton = $(this.$el).find('.wfop_btn.wfop_btn_primary');
                                $(submitButton).addClass('is_busy');
                            }
                            
                            let wp_ajax = new wfop.ajax();
                            if (this.model.field_type === 'wfop_wysiwyg') {
                                let dt = new Date();
                                this.model.name = dt.getTime();
                            }
                            let add_query = {'fields': wfop.tools.jsp(this.model), 'section': 'advanced', 'wfop_id': wfop.id};
                            wp_ajax.ajax('add_field', add_query);
                            wp_ajax.success = (rsp) => {
                                if (rsp.status === true) {
                                    self.addField(rsp.data);
                                }
                            };
                            wp_ajax.complete = () => {
                                if( this.$el ) {
                                    let submitButton = $(this.$el).find('.wfop_btn.wfop_btn_primary');
                                    $(submitButton).removeClass('is_busy');
                                }
                                $('#modal-global-settings_success').iziModal('open');
                                self.modal_add_field.iziModal('close');
                                this.model = this.defaultModel();
                            };
                        }
                    },
                    data: {
                        modal: modal,
                        isLoading: false,
                        wfop_id: self.builder.id,
                        model: {
                            label: '',
                            placeholder: "",
                            field_type: 'text',
                            section_type: 'advanced',
                            options: '',
                            default: '',
                            required: true,
                            width: 'wffn-sm-100',
                            radio_alignment: 'horizontal',
                        },
                        schema: {
                            fields: [
                                {
                                    type: "select",
                                    label: wfop_localization.fields.field_types_label,
                                    model: "field_type",
                                    values: () => {
                                        return wfop.tools.jsp(wfop_localization.fields.field_types);
                                    },
                                    selectOptions: {hideNoneSelectedText: true},
                                },
                                {
                                    type: "input",
                                    inputType: "text",
                                    label: wfop_localization.fields.label_field_label,
                                    model: "label",
                                    required: true,
                                    validator: VueFormGenerator.validators.string
                                },
                                {
                                    type: "input",
                                    inputType: "text",
                                    label: wfop_localization.fields.placeholder_field_label,
                                    model: "placeholder",
                                    validator: VueFormGenerator.validators.string,
                                    visible: (model) => {
                                        return ($.inArray(model.field_type, ['radio', 'checkbox', 'hidden', 'wfop_wysiwyg']) < 0);
                                    },
                                },
                                {
                                    type: "input",
                                    inputType: "text",
                                    label: wfop_localization.fields.default_field_label,
                                    model: "default",
                                    validator: VueFormGenerator.validators.string,
                                    visible: (model) => {
                                        return ($.inArray(model.field_type, ['checkbox', 'wfop_wysiwyg']) <= -1);
                                    },
                                },
                                {
                                    type: "select",
                                    label: wfop_localization.fields.default_field_label,
                                    model: "default",
                                    validator: VueFormGenerator.validators.string,
                                    values: () => {
                                        return wfop.tools.jsp(wfop_localization.fields.default_field_checkbox_options);
                                    },
                                    visible: (model) => {
                                        return (model.field_type === 'checkbox');
                                    },
                                    selectOptions: {hideNoneSelectedText: true},
                                },
                                {
                                    type: "checkbox",
                                    inputType: "text",
                                    label: wfop_localization.fields.required_field_label,
                                    model: "required",
                                    styleClasses: ['wfop_full_width_fields'],
                                    visible: (model) => {
                                        return (model.field_type !== 'hidden' && model.field_type !== 'wfop_wysiwyg');
                                    }
                                },
                                {
                                    type: "input",
                                    inputType: "text",
                                    label: wfop_localization.fields.options_field_label,
                                    model: "options",
                                    placeholder: wfop_localization.fields.options_field_placeholder,
                                    required: true,
                                    visible: (model) => {
                                        return ($.inArray(model.field_type, ['select', 'radio']) > -1);
                                    },
                                    validator: VueFormGenerator.validators.string
                                },
                                {
                                    type: "radios",
                                    label: wfop_localization.fields.radio_field_alignment,
                                    model: "radio_alignment",
                                    values: () => {
                                        return wfop.tools.jsp(wfop_localization.fields.field_radio_alignment_options);
                                    },
                                    visible: (model) => {
                                        return (model.field_type === 'radio');
                                    },
                                },
                            ]
                        },
                        formOptions: {
                            validateAfterChanged: true
                        }
                    }
                }
            );
            return this.add_field_vue;
        }

        editFieldVue() {
            if (this.edit_field_model_open === true) {
                return this.edit_field_vue;
            }
            this.edit_field_model_open = true;
            let self = this;

            this.edit_field_vue = new Vue({
                    'el': '#edit-field-form',
                    components: {
                        "vue-form-generator": VueFormGenerator.component
                    },
                    methods: {
                        defaultModel() {
                            return {
                                field_type: 'text',
                                section_type: 'advanced',
                                label: '',
                                placeholder: "",
                                options: '',
                                id: "",
                                default: '',
                                width: 'wffn-sm-100',
                                required: true,
                                radio_alignment: 'horizontal',
                                phone_validation: false

                            };
                        },

                        setData() {
                            let data = self.current_edit_field;
                            if (wfop.tools.ol(data) === 0)
                                return true;

                            this.model.label = data.field.label;
                            this.model.placeholder = typeof data.field.placeholder !== "undefined" ? data.field.placeholder : '';
                            this.model.width = typeof data.field.width !== "undefined" ? data.field.width : '';

                            this.model.section_type = data.field.field_type;
                            this.model.field_type = data.field.type;

                            if (wfop.tools.ol(data.field.options) > 0) {
                                this.model.options = wfop.tools.values(data.field.options).join(",");
                            } else {
                                this.model.options = '';
                            }

                            this.model.default = data.field.default;
                            this.model.required = wfop.tools.string_to_bool(data.field.required);
                            this.model.id = data.field.id;
                            if (Object.prototype.hasOwnProperty.call(data.field, 'radio_alignment')) {
                                this.model.radio_alignment = data.field.radio_alignment;
                            }
                            if (this.model.field_type === 'wfop_wysiwyg') {
                                this.model.required = false;
                            }
                            if (Object.prototype.hasOwnProperty.call(data.field, 'phone_validation')) {
                                this.model.phone_validation = wfop.tools.string_to_bool(data.field.phone_validation);
                            }
                            this.model = wfop.hooks.applyFilters('wfop_field_data_merge_with_model', this.model, data);
                            let wfop_initiator_field = document.getElementById('wfacp-initiator-field');
                            if (null !== wfop_initiator_field) {
                                wfop_initiator_field.click();
                            }

                            setTimeout(() => {
                                self.modal_edit_field.WoofunnelModal('open');
                            }, 100);

                        },
                        onSubmit() {
                            let model = wfop.tools.jsp(this.model);
                            let data = self.current_edit_field;
                            data.field.label = model.label;
                            data.field.placeholder = model.placeholder;
                            data.field.width = model.width;
                            data.field.type = model.field_type;
                            data.field.field_type = model.section_type;
                            data.field.options = model.options.split(',');

                            data.field.default = '';
                            if (wfop.tools.hp(model, 'default')) {
                                data.field.default = model.default;
                            }
                            data.field.required = model.required;
                            if (Object.prototype.hasOwnProperty.call(model, 'radio_alignment')) {
                                data.field.radio_alignment = model.radio_alignment;
                            }
                            if (Object.prototype.hasOwnProperty.call(model, 'phone_validation')) {
                                data.field.phone_validation = model.phone_validation;
                            }
                            data = wfop.hooks.applyFilters('wfop_before_field_save', data, model);
                            data = wfop.tools.jsp(data);
                            if (data.field.type === 'hidden') {
                                data.field.required = false;
                            }
                            self.updateField(data);

                            this.model = this.defaultModel();

                            document.getElementById('wfop_save_form_layout').click();
                            self.modal_edit_field.WoofunnelModal('close');
                        }
                    },
                    data: {
                        wfop_id: self.builder.id,
                        current_field_id: 'default',
                        default_products: [],
                        model_title: wfop_localization.fields.add_field,
                        address_order_html: {'address': '', 'shipping-address': ''},
                        model_sub_title: '',
                        edit_model_field_label: '',
                        model_field_type: '',
                        model: {
                            field_type: 'text',
                            section_type: 'advanced',
                            options: '',
                            default: '',
                            label: '',
                            placeholder: "",
                            required: true,
                            width: 'wffn-sm-100',
                            radio_alignment: 'horizontal'
                        },
                        schema: {
                            groups: [{
                                fields: self.getEditableFields()
                            }],
                        },
                        formOptions: {
                            validateAfterChanged: true
                        }
                    }
                }
            );
            return this.edit_field_vue;
        }

        model() {
            let self = this;
            this.modal_add_field = $("#modal-add-field");
            if (this.modal_add_field.length > 0) {
                this.modal_add_field.iziModal({
                    title: wfop_localization.fields.add_field,
                    headerColor: '#6dbe45',
                    background: '#efefef',
                    borderBottom: false,
                    width: 640,
                    overlayColor: 'rgba(0, 0, 0, 0.35)',
                    transitionIn: 'fadeInUp',
                    transitionOut: 'fadeOut',
                    navigateArrows: "false",
                    radius: 8,
                    onOpened: function (modal) {
                        self.addFieldVue(modal);
                    }
                });
            }

            this.modal_edit_field = $("#modal-edit-field");
            if (this.modal_edit_field.length > 0) {
                this.modal_edit_field.WoofunnelModal();

                this.modal_edit_field.on('onopend', function () {
                    setTimeout(() => {
                        let wfop_wysiwyg = $("#wfop_wysiwyg_editor");
                        if (wfop_wysiwyg.length > 0) {
                            let wysiwyg_editor = 'wfop_wysiwyg_editor';
                            wp.editor.remove(wysiwyg_editor);
                            let editorConfig = wfop.tools.jsp(wfop.editorConfig);
                            editorConfig.tinymce.init_instance_callback = (editor) => {
                                editor.on('Change', () => {
                                    let editFieldVue = self.editFieldVue();
                                    Vue.set(editFieldVue.model, 'default', editor.getContent());
                                });
                            };
                            wp.editor.initialize(wysiwyg_editor, editorConfig);
                        }
                    });
                });
                this.modal_edit_field.on('onclosed', function () {
                    let editField = self.editFieldVue();
                    editField.products = editField.products_updated;
                    let wfop_wysiwyg = $("#wfop_wysiwyg_editor");
                    if (wfop_wysiwyg.length > 0) {
                        let wysiwyg_editor = 'wfop_wysiwyg_editor';
                        wp.editor.remove(wysiwyg_editor);
                    }
                });
            }

            wfop.show_saved_model = $("#modal-saved-data-success");
            if (wfop.show_saved_model.length === 0) {
                return;
            }
            wfop.show_saved_model.iziModal({
                    title: wfop_localization.global.data_saving,
                    icon: 'icon-check',
                    headerColor: '#6dbe45',
                    background: '#6dbe45',
                    borderBottom: false,
                    width: 600,
                    timeout: 4000,
                    timeoutProgressbar: true,
                    transitionIn: 'fadeInUp',
                    transitionOut: 'fadeOut',
                    bottom: 0,
                    loop: true,
                    pauseOnHover: true,
                    overlay: false,
                    onOpening: () => {
                        let spinner = $('.wfop_spinner.spinner');
                        if (spinner.length > 0) {
                            spinner.css("visibility", "hidden");
                        }
                    },
                    onClosed: () => {
                        wfop.show_saved_model.iziModal('setTitle', wfop_localization.global.data_saving);
                        let spinner_el = $(".wfop_spinner.spinner");
                        if (spinner_el.is(":visible")) {
                            spinner_el.css("visibility", "hidden");
                        }

                    }
                }
            );
        }

    }

    var WoofunnelModalMethods = {
        init: function () {
            let el = $(this);
            setTimeout(el => {
                el.find('.iziModal-button-close').on('click', function () {
                    $(this).trigger('onclosed');
                    $('html').removeClass('iziModal-isAttached');
                    $('.wfacp_overlay').removeClass('wfacp_overlay_active');
                    el.fadeOut();
                });
                $(this).trigger('oninit');
            }, 300, el);
        },
        open: function () {
            let el = $(this);
            el.css('top', '50px');
            el.css('bottom', 'auto');

            setTimeout(() => {
                el.fadeIn();
                el.addClass('hasScroll');

                let wH = $(window).height();
                let mH = parseInt(wH * 0.9);
                let iziHeadH = parseInt($('.iziModal-header').outerHeight());
                let iciContH = parseInt($(this).find('.iziModal-content').height());
                let eTop = parseInt((wH - (iziHeadH + iciContH)) / 2) + 'px';

                if ((iziHeadH + iciContH) < mH) {
                    el.height((iziHeadH + iciContH));
                    el.find('.iziModal-wrap').height(iciContH);

                    el.css('top', eTop);
                } else {
                    el.height(mH);
                    el.find('.iziModal-wrap').height((mH - iziHeadH));
                }

                $('html').addClass('iziModal-isAttached');
                $('.wfacp_overlay').addClass('wfacp_overlay_active');
            }, 150);

            $(this).trigger('onopend');
        },// IS
        close: function () {
            $(this).trigger('onclosed');

            $('html').removeClass('iziModal-isAttached');
            $('.wfacp_overlay').removeClass('wfacp_overlay_active');
            $(this).fadeOut();

        }
    };

    $.fn.WoofunnelModal = function (methodOrOptions) {
        if (WoofunnelModalMethods[methodOrOptions]) {
            return WoofunnelModalMethods[methodOrOptions].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof methodOrOptions === 'object' || !methodOrOptions) {
            // Default to "init"
            return WoofunnelModalMethods.init.apply(this, arguments);
        } else {
            $.error('Method ' + methodOrOptions + ' does not exist on jQuery.tooltip');
        }
    };

    window.addEventListener('load', () => {
        window.dispatchEvent(new Event('wffn_optin_admin_event_loaded'));
        window.wfop_design = new wfop_design();
        window.wfop_layout = new wfop_layouts(wfop);
        setTimeout(function () {
            $('.wfopp_loader').hide();
            $('.wfop_izimodal_default').css('visibility', 'visible');
        }, 600);
    });    

})(jQuery);
