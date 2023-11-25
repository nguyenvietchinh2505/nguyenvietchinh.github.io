(function ($) {
    var wfop_temp = {
        "ajax": function (cls, is_form, cb) {
            const self = this;
            let element = null;
            let handler = {};
            let prefix = "wfop_";
            this.action = null;

            this.data = function (form_data, formEl = null) {

                return form_data;
            };
            this.before_send = function (formEl) {
            };
            this.async = function (bool) {
                return bool;
            };
            this.method = function (method) {
                return method;
            };
            this.success = function (rsp, fieldset, loader, jqxhr, status) {
            };
            this.complete = function (rsp, fieldset, loader, jqxhr, status) {
            };
            this.error = function (rsp, fieldset, loader, jqxhr, status) {
                // if (wfop_localization.error.hasOwnProperty(rsp.status)) {
                //     wfop.wffn_swal(wfop_localization.error[rsp.status]);
                // }

                wfop.hide_spinner();
            };
            this.action = function (action) {
                return action;
            };

            function reset_form(action, fieldset, loader, rsp, jqxhr, status) {
                if (fieldset.length > 0) {
                    fieldset.prop('disabled', false);
                }
                loader.remove();
                let loader2;
                loader2 = $(".bwf_ajax_btn_bottom_container");
                loader2.removeClass('ajax_loader_show');

                if (self.hasOwnProperty(action) === true && typeof self[action] === 'function') {
                    self[action](rsp, fieldset, loader, jqxhr, status);
                }
            }

            function form_post(action) {
                let formEl = element;
                let ajax_loader = null;

                let form_data = new FormData(formEl);

                form_data.append('action', action);

                form_data.append('wfop_secure', wfop_secure.nonce);

                let form_method = $(formEl).attr('method');

                let method = (form_method !== "undefined" && form_method !== "") ? form_method : 'POST';
                if ($(formEl).find("." + action + "_ajax_loader").length === 0) {
                    $(formEl).find(".bwf_form_submit").prepend("<span class='" + action + "_ajax_loader spinner" + "'></span>");
                    ajax_loader = $(formEl).find("." + action + "_ajax_loader");
                } else {
                    ajax_loader = $(formEl).find("." + action + "_ajax_loader");
                }

                let ajax_loader2 = $(".bwf_ajax_btn_bottom_container");
                ajax_loader.addClass('ajax_loader_show');
                ajax_loader2.addClass('ajax_loader_show');

                let fieldset = $(formEl).find("fieldset");
                if (fieldset.length > 0) {
                    fieldset.prop('disabled', true);
                }

                self.before_send(formEl, action);

                let data = self.data(form_data, formEl);

                let request = {
                    url: ajaxurl,
                    async: self.async(true),
                    method: self.method('POST'),
                    data: data,
                    processData: false,
                    contentType: false,
                    //       contentType: self.content_type(false),
                    success: function (rsp, jqxhr, status) {
                        if (typeof rsp === 'object' && rsp.hasOwnProperty('nonce')) {
                            wfop_secure.nonce = rsp.nonce;
                            delete rsp.nonce;
                        }
                        reset_form(action + "_ajax_success", fieldset, ajax_loader, rsp, jqxhr, status);
                        self.success(rsp, jqxhr, status, fieldset, ajax_loader);
                    },
                    complete: function (rsp, jqxhr, status) {
                        reset_form(action + "_ajax_complete", fieldset, ajax_loader, rsp, jqxhr, status);
                        self.complete(rsp, jqxhr, status, fieldset, ajax_loader);
                    },
                    error: function (rsp, jqxhr, status) {
                        reset_form(action + "_ajax_error", fieldset, ajax_loader, rsp, jqxhr, status);
                        self.error(rsp, jqxhr, status, fieldset, ajax_loader);
                    }
                };
                if (handler.hasOwnProperty(action)) {
                    clearTimeout(handler[action]);
                } else {
                    handler[action] = null;
                }
                handler[action] = setTimeout(
                    function (request) {
                        $.ajax(request);
                    }, 200, request
                );
            }

            function send_json(action) {
                let formEl = element;
                let data = self.data({}, formEl);
                if (typeof data === 'object') {
                    data.action = action;
                } else {
                    data = {
                        'action': action
                    };
                }

                self.before_send(formEl, action);
                data.wfop_nonce = wfop_secure.nonce;
                let request = {
                    url: ajaxurl,
                    async: self.async(true),
                    method: self.method('POST'),
                    data: data,
                    success: function (rsp, jqxhr, status) {

                        if (typeof rsp === 'object' && rsp.hasOwnProperty('nonce')) {
                            wfop_secure.nonce = rsp.nonce;
                            delete rsp.nonce;
                        }
                        self.success(rsp, jqxhr, status, element);
                    },
                    complete: function (rsp, jqxhr, status) {

                        self.complete(rsp, jqxhr, status, element);
                    },
                    error: function (rsp, jqxhr, status) {
                        self.error(rsp, jqxhr, status, element);
                    }
                };
                if (handler.hasOwnProperty(action)) {
                    clearTimeout(handler[action]);
                } else {
                    handler[action] = null;
                }
                handler[action] = setTimeout(
                    function (request) {
                        $.ajax(request);
                    }, 200, request
                );
            }

            this.ajax = function (action, data) {
                if (typeof data === 'object') {
                    data.action = action;
                } else {
                    data = {
                        'action': action
                    };
                }

                data.action = prefix + action;
                self.before_send(document.body, action);
                data.wfop_nonce = wfop_secure.nonce;
                let request = {
                    url: ajaxurl,
                    async: self.async(true),
                    method: self.method('POST'),
                    data: data,
                    success: function (rsp, jqxhr, status) {
                        if (typeof rsp === 'object' && rsp.hasOwnProperty('nonce')) {
                            wfop_secure.nonce = rsp.nonce;
                            delete rsp.nonce;
                        }
                        self.success(rsp, jqxhr, status, action);
                    },
                    complete: function (rsp, jqxhr, status) {
                        self.complete(rsp, jqxhr, status, action);
                    },
                    error: function (rsp, jqxhr, status) {

                        self.error(rsp, jqxhr, status, action);
                    }
                };
                if (handler.hasOwnProperty(action)) {
                    clearTimeout(handler[action]);
                } else {
                    handler[action] = null;
                }
                handler[action] = setTimeout(
                    function (request) {
                        $.ajax(request);
                    }, 200, request
                );
            };

            function form_init(cls) {
                if ($(cls).length > 0) {

                    $(cls).on("submit", function (e) {
                        e.preventDefault();
                        let action = $(this).data('bwf-action');
                        if (action !== 'undefined') {
                            action = prefix + action;
                            action = action.trim();
                            element = this;
                            self.action = action;
                            form_post(action);
                        }
                    });

                    if (typeof cb === 'function') {

                        cb(self);
                    }
                }
            }

            function click_init(cls) {
                if ($(cls).length > 0) {
                    $(cls).on("click", function (e) {
                            e.preventDefault();
                            let action = $(this).data('bwf-action');
                            if (action !== 'undefined') {
                                action = prefix + action;
                                action = action.trim();
                                element = this;
                                self.action = action;
                                send_json(action);
                            }
                        }
                    );

                    if (typeof cb === 'function') {
                        cb(self);
                    }
                }
            }

            if (is_form === true) {
                form_init(cls, cb);
                return this;
            }

            if (is_form === false) {
                click_init(cls, cb);
                return this;
            }

            return this;
        },
        "hooks": {
            hooks: {
                action: {},
                filter: {}
            },
            addAction: function (action, callable, priority = 10, tag = '') {
                this.addHook('action', action, callable, priority, tag);
            },
            addFilter: function (action, callable, priority = 10, tag = '') {
                this.addHook('filter', action, callable, priority, tag);
            },
            doAction: function (action) {
                this.doHook('action', action, arguments);
            },
            applyFilters: function (action) {
                return this.doHook('filter', action, arguments);
            },
            removeAction: function (action, tag) {
                this.removeHook('action', action, tag);
            },
            removeFilter: function (action, priority, tag) {
                this.removeHook('filter', action, priority, tag);
            },
            addHook: function (hookType, action, callable, priority, tag) {
                if (undefined == this.hooks[hookType][action]) {
                    this.hooks[hookType][action] = [];
                }
                let hooks = this.hooks[hookType][action];
                if (undefined == tag) {
                    tag = action + '_' + hooks.length;
                }
                if (priority == undefined) {
                    priority = 10;
                }

                this.hooks[hookType][action].push({
                    tag: tag,
                    callable: callable,
                    priority: priority
                });
            },
            doHook: function (hookType, action, args) {

                // splice args from object into array and remove first index which is the hook name
                args = Array.prototype.slice.call(args, 1);
                if (undefined != this.hooks[hookType][action]) {
                    let hooks = this.hooks[hookType][action],
                        hook;
                    //sort by priority
                    hooks.sort(
                        function (a, b) {
                            return a.priority - b.priority;
                        }
                    );

                    for (let i = 0; i < hooks.length; i++) {
                        hook = hooks[i].callable;
                        if (typeof hook != 'function') {
                            hook = window[hook];
                        }
                        if ('action' == hookType) {
                            hook.apply(null, args);
                        } else {
                            args[0] = hook.apply(null, args);
                        }
                    }
                }
                if ('filter' == hookType) {
                    return args[0];
                }
            },
            removeHook: function (hookType, action, priority, tag) {
                if (undefined != this.hooks[hookType][action]) {
                    let hooks = this.hooks[hookType][action];
                    for (let i = hooks.length - 1; i >= 0; i--) {
                        if ((undefined == tag || tag == hooks[i].tag) && (undefined == priority || priority == hooks[i].priority)) {
                            hooks.splice(i, 1);
                        }
                    }
                }
            }
        },
        "tools": {
            /**
             * get keys length of object and array
             * @param obj
             * @returns {number}
             */
            ol: function (obj) {
                let c = 0;
                if (obj != null && typeof obj === "object") {
                    c = Object.keys(obj).length;
                }
                return c;
            },
            isEmpty: function (obj) {
                for (let key in obj) {
                    if (Object.prototype.hasOwnProperty.call(obj, key)) {
                        return false;
                    }
                }
                return true;
            },
            /**
             * Check property exist in object or Array
             * @param obj
             * @param key
             * @returns {boolean}
             */
            hp: function (obj, key) {
                let c = false;
                if (typeof obj === "object" && key !== undefined) {
                    c = Object.prototype.hasOwnProperty.call(obj, key);
                }
                return c;
            },
            /**
             * Convert destroy refresh and reconvert into in json without refrence
             * @param obj
             * @returns {*}
             */
            jsp: function (obj) {
                if (typeof obj === 'object') {
                    let doc = JSON.stringify(obj);
                    doc = JSON.parse(doc);
                    return doc;
                } else {
                    return obj;
                }
            },
            /**
             * get object keys array
             * @param obj
             * @returns Array
             */
            kys: function (obj) {
                if (typeof obj === 'object' && obj != null && this.ol(obj) > 0) {
                    return Object.keys(obj);
                }
                return [];
            },
            /**
             * get object keys array
             * @param obj
             * @returns Array
             */
            values: function (obj) {
                if (typeof obj === 'object' && obj != null && this.ol(obj) > 0) {
                    return Object.values(obj);
                }
                return [];
            },
            ucfirst: (string) => {
                return string.replace(/^\w/, c => c.toUpperCase());
            },
            stripHTML: function (dirtyString) {
                let dirty = $("<div>" + dirtyString + "</div>");
                return dirty.text();
            },
            string_to_bool: (content = '') => {
                if ('' === content || 'false' == content) {
                    return false;
                }

                return (typeof content === "boolean") ? content : ('yes' === content || 1 === content || 'true' === content || '1' === content);
            },
            is_object: function (options) {
                if (options == null) {
                    return false;
                }

                if (typeof options === 'object') {
                    return true;
                }
                return false;
            },
            is_bool: function (mixed_var) {
                mixed_var = wfop.tools.string_to_bool(mixed_var);
                return (typeof mixed_var === 'boolean');
            },
            timestamp: function () {
                let date = new Date();
                return date.getTime();
            }
        },
        "swal": (property) => {
            return wffn_swal($.extend({
                title: '',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0073aa',
                cancelButtonColor: '#e33b3b',
                confirmButtonText: ''
            }, property));
        },


        'sortable': (ui_class, start, stop, hover, item_class = '.wfop_item_drag', non_drag_class = '.ui-state-disabled', placeholder = '', extra_options = {}) => {


            let sortable = $(ui_class);
            if (sortable.length === 0)
                return;


            sortable.off('sortable');

            let options = {
                connectWith: ui_class,
                start: function (event, ui) {
                    start(event, ui);
                },

                stop: function (event, ui) {
                    stop(event, ui);
                },
                over: function (event, ui) {
                    hover('in', event, ui);
                },
                out: function (event, ui) {
                    hover('out', event, ui);
                },
                //axis: 'y',
                cursor: 'move'
            };
            if (wfop.tools.ol(extra_options) > 0) {
                for (let i in extra_options) {
                    options[i] = extra_options[i];
                }
            }
            let drag_item_class = '.wfop_item_drag';
            if (item_class !== '') {
                drag_item_class = item_class;
            }
            if (non_drag_class !== '') {
                drag_item_class += ':not(' + non_drag_class + ')';
            }

            options.items = drag_item_class;

            if ('' !== placeholder) {
                options.placeholder = placeholder;
            }

            sortable.sortable(options);
            sortable.disableSelection();


        },

        'addClass': (el, cl) => {
            $(el).addClass(cl);
        },
        'removeClass': (el, cl) => {
            $(el).removeClass(cl);
        },
        show_spinner: () => {
            let spinner = $('.wfop_spinner.spinner');

            if (spinner.length > 0) {
                spinner.css("visibility", "visible");
            }
        },
        hide_spinner: function hide_spinner() {
            var spinner = $('.wfop_spinner.spinner');

            if (spinner.length > 0) {
                spinner.css("visibility", "hidden");
            }
        },
        show_data_save_model: (title = '') => {
            if ('' !== title) {
                wfop.show_saved_model.iziModal('setTitle', title);
                wfop.show_saved_model.iziModal('open');
            }
        },
        show_pro_message: (type = '') => {
            let title = wffn.pro_info_title;
            let subtitle = wffn.pro_info_subtitle;
            let heading = '';
            if (!_.isEmpty(type)) {
                if ('wfop_optin_phone' === type) {
                    heading = $('.wfop_input_field_place_holder.wfop_locked').find('span').text();
                } else if ('new_field' === type) {
                    heading = $('.template_field_selecter').find('.wfop_input_fields_btn button').text();
                } else if ('popover' === type) {
                    heading = $('.wfopp-tabs.wfopp-tab-popover').text();
                } else if (type.indexOf('data') !== -1) {
                    let action = type.split('_');
                    if (action.length > 0) {
                        if (!_.isEmpty(action[1])) {
                            heading = $("[data-item='" + action[1] + "']").parent('.wffn-show-pro').text();
                        }
                    }
                }
            }

            title = title.replace('{feature_name}', heading.trim());
            subtitle = subtitle.replace('{feature_name}', heading.trim());

            title = wffn.pro_info_lock_icon + title;
            if (!_.isEmpty(heading)) {
                let url = wffn.pro_link;
                if (url.indexOf('utm_content') == -1) {
                    if (url.indexOf('?') != -1) {
                        Vue.set(wffn, 'pro_link', url + '&utm_content=' + heading.trim());
                    } else {
                        Vue.set(wffn, 'pro_link', url + '?utm_content=' + heading.trim());
                    }
                } else {
                    let urlParams = new URL(url);
                    let search_params = urlParams.searchParams;
                    search_params.delete('utm_content');
                    search_params.append('utm_content', heading.trim());
                    urlParams.search = search_params.toString();
                    let new_url = urlParams.toString();
                    Vue.set(wffn, 'pro_link', new_url);
                }
            }

            let sw = wfop.swal({
                'text': subtitle,
                'title': title,
                'type': '',
                'confirmButtonText': wffn.upgrade_button_text,
                'showCancelButton': true,
                'showCloseButton': true,
                'customClass': "swal-pro-modal swal-upgrade-pro",
                'reverseButtons': true,
            });
            sw.then((result) => {
                if (result.value) {
                    window.open(wffn.pro_link);
                }
            });
            sw.catch(() => {
            });
        },
        editorConfig: {
            //'mediaButtons': true,
            "tinymce": {
                "theme": "modern",
                "skin": "lightgray",
                "language": "en",
                "formats": {
                    "alignleft": [{"selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", "styles": {"textAlign": "left"}}, {"selector": "img,table,dl.wp-caption", "classes": "alignleft"}],
                    "aligncenter": [{"selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", "styles": {"textAlign": "center"}}, {"selector": "img,table,dl.wp-caption", "classes": "aligncenter"}],
                    "alignright": [{"selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", "styles": {"textAlign": "right"}}, {"selector": "img,table,dl.wp-caption", "classes": "alignright"}],
                    "strikethrough": {"inline": "del"}
                },
                "relative_urls": false,
                "remove_script_host": false,
                "convert_urls": false,
                "browser_spellcheck": true,
                "fix_list_elements": true,
                "entities": "38,amp,60,lt,62,gt",
                "entity_encoding": "raw",
                "keep_styles": false,
                "cache_suffix": "wp-mce-4800-20180716",
                "resize": "vertical",
                "menubar": false,
                "branding": false,
                "preview_styles": "font-family font-size font-weight font-style text-decoration text-transform",
                "end_container_on_empty_block": true,
                "wpeditimage_html5_captions": true,
                "wp_lang_attr": "en-US",
                "wp_keep_scroll_position": false,
                "wp_shortcut_labels": {
                    "Heading 1": "access1",
                    "Heading 2": "access2",
                    "Heading 3": "access3",
                    "Heading 4": "access4",
                    "Heading 5": "access5",
                    "Heading 6": "access6",
                    "Paragraph": "access7",
                    "Blockquote": "accessQ",
                    "Underline": "metaU",
                    "Strikethrough": "accessD",
                    "Bold": "metaB",
                    "Italic": "metaI",
                    "Code": "accessX",
                    "Align center": "accessC",
                    "Align right": "accessR",
                    "Align left": "accessL",
                    "Justify": "accessJ",
                    "Cut": "metaX",
                    "Copy": "metaC",
                    "Paste": "metaV",
                    "Select all": "metaA",
                    "Undo": "metaZ",
                    "Redo": "metaY",
                    "Bullet list": "accessU",
                    "Numbered list": "accessO",
                    "Insert/edit image": "accessM",
                    "Remove link": "accessS",
                    "Toolbar Toggle": "accessZ",
                    "Insert Read More tag": "accessT",
                    "Insert Page Break tag": "accessP",
                    "Distraction-free writing mode": "accessW",
                    "Add Media": "accessM",
                    "Keyboard Shortcuts": "accessH"
                },
                "toolbar1": "bold,italic,bullist,numlist,link",
                "wpautop": false,
                "indent": true,
                "elementpath": false,
                "plugins": "charmap,colorpicker,hr,lists,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wptextpattern",

            }, "quicktags": {"buttons": "strong,em,link,ul,ol,li,code"},

        }

    };
    for (let ci in wfop_temp) {
        window.wfop[ci] = wfop_temp[ci];
    }
})(jQuery);

