/*global wffn*/
/*global Vue*/
Vue.config.devtools = true;
Vue.config.debug = true;


(function ($, doc, win) {
    'use strict';
    Vue.component('multiselect', window.VueMultiselect.default);
    $(window).on('load', function () {
        setTimeout(function () {
            $('.wffn_global_loader').hide();
        }, 600);
    });


    let wffnBuilderCommons = {
        hooks: {action: {}, filter: {}},
        tools: {
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
             * Check property exist in object or Array
             * @param obj
             * @param key
             * @returns {boolean}
             */
            hp: function (obj, key) {
                let c = false;
                if (typeof obj === "object" && key !== undefined) {
                    c = obj.hasOwnProperty(key);
                }
                return c;
            },
        },
        addAction: function (action, callable, priority, tag) {
            this.addHook('action', action, callable, priority, tag);
        },
        addFilter: function (action, callable, priority, tag) {
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
            var hooks = this.hooks[hookType][action];
            if (undefined == tag) {
                tag = action + '_' + hooks.length;
            }
            if (priority == undefined) {
                priority = 10;
            }

            this.hooks[hookType][action].push({tag: tag, callable: callable, priority: priority});
        },
        doHook: function (hookType, action, args) {

            // splice args from object into array and remove first index which is the hook name
            args = Array.prototype.slice.call(args, 1);
            if (undefined !== this.hooks[hookType][action]) {
                var hooks = this.hooks[hookType][action], hook;
                //sort by priority
                hooks.sort(
                    function (a, b) {
                        return a["priority"] - b["priority"]
                    }
                );
                for (var i = 0; i < hooks.length; i++) {
                    hook = hooks[i].callable;
                    if (typeof hook != 'function') {
                        hook = window[hook];
                    }
                    if ('action' === hookType) {
                        hook.apply(null, args);
                    } else {
                        args[0] = hook.apply(null, args);
                    }
                }
            }
            if ('filter' === hookType) {
                return args[0];
            }
        },
        removeHook: function (hookType, action, priority, tag) {
            if (undefined !== this.hooks[hookType][action]) {
                var hooks = this.hooks[hookType][action];
                for (var i = hooks.length - 1; i >= 0; i--) {
                    if ((undefined === tag || tag == hooks[i].tag) && (undefined === priority || priority === hooks[i].priority)) {
                        hooks.splice(i, 1);
                    }
                }
            }
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
                "toolbar1": "bold,italic,bullist,numlist, alignleft,aligncenter,alignright,link,forecolor",
                //"toolbar1": "formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,wp_adv,dfw",
                //"toolbar2": "strikethrough,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
                "wpautop": false,
                "indent": true,
                "elementpath": false,
                "plugins": "charmap,colorpicker,hr,lists,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wptextpattern",

            }, "quicktags": {"buttons": "strong,em,link,ul,ol,li,code"},

        }
    };

    let wffn_builder = function () {
        const self = this;
        /****** Declaring vue objects ******/
        this.wffn_flex_vue = null;
        this.wffn_popups_vue = null;
        this.wffn_listing_vue = null;
        this.wffn_breadcrumb_vue = null;
        this.wffn_edit_landing_vue = null;

        if ($('#modal-global-settings_success').length > 0) {
            $("#modal-global-settings_success").iziModal(
                {
                    title: wffn.texts.settings_success,
                    icon: 'icon-check',
                    headerColor: '#6dbe45',
                    background: '#6dbe45',
                    borderBottom: false,
                    width: 600,
                    timeout: 4000,
                    timeoutProgressbar: true,
                    transitionIn: 'fadeInUp',
                    transitionOut: 'fadeOutDown',
                    overlayColor: 'rgba(0, 0, 0, 0.35)',
                    bottom: 0,
                    loop: true,
                    pauseOnHover: true,
                    overlay: false,
                    navigateArrows: false
                }
            );
        }


        $(document).on('wp-plugin-install-success', function (event, response) {
            if ($("#wffn_flex_container_vue").length > 0) {
                self.wffn_flex_vue.afterInstall(event, response);
            }
        });

        $(document).on('wp-plugin-install-error', function (event, response) {
            if ($("#wffn_flex_container_vue").length > 0) {
                self.wffn_flex_vue.afterInstallError(event, response);
            }
        });

        function wffn_tabs() {

            let wfctb = $('.wffn-widget-tabs .wffn-tab-title');
            $(document.body).on(
                'click', '.wffn-widget-tabs .wffn-tab-title',
                function () {
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
            if (wfctb.length > 0) {
                wfctb.eq(0).trigger('click');
            }

        }

        wffn_tabs();

        function wffn_ty_shortcode_tab() {

            let wfctb = $('.wffn-ty-shortcodes-tab .wffn-tab-title');
            $(document.body).on('click',
                '.wffn-ty-shortcodes-tab .wffn-tab-title',
                function () {
                    let tabindex = $(this).attr('data-tab');

                    $('.wffn-ty-shortcode-tab-area').hide();
                    $('.wffn-ty-shortcode-tab-area').eq(tabindex).show();

                }
            );
            if ($(".wffn-ty-shortcodes-tab").length > 0) {
                wfctb.eq(0).trigger('click');
            }
        }

        wffn_ty_shortcode_tab();

        $(document).on('click', '.bwf-ellipsis-menu.bwf-ellipsis--alter-alter .bwf-ellipsis-menu__toggle', function () {
            $(this).parents('.bwf-ellipsis--alter-alter').toggleClass('show-menu');
        });
        $(document).on('click', function (e) {
            if ($('.bwf-ellipsis-menu.bwf-ellipsis--alter-alter').length) {
                if (!e.target.matches('.bwf-ellipsis-menu__toggle') && !e.target.closest('.bwf-ellipsis-menu__toggle')) {
                    $('.bwf-ellipsis-menu.bwf-ellipsis--alter-alter').removeClass('show-menu');
                }
            }
            if( e.target.matches( '.wffn-ellipsis-menu' ) ) {
                $(e.target).toggleClass('is-opened');
            } else if ( e.target.closest('.wffn-ellipsis-menu') ) {
                if( e.target.closest( '.wffn-ellipsis-menu-dropdown' ) ) {
                    $(e.target.closest('.wffn-ellipsis-menu')).removeClass('is-opened');
                } else {
                    $(e.target.closest('.wffn-ellipsis-menu')).toggleClass('is-opened');
                }
            } else {
                if( $('.wffn-ellipsis-menu').length ) {
                    $('.wffn-ellipsis-menu').removeClass('is-opened');
                }
            }
        });
        // 
        $(document).on('click', '.wf_pro_modal_trigger', function(e) {
            // eslint-disable-next-line no-undef
            let title = wffn.pro_info_title;
            let subtitle = wffn.pro_info_subtitle;
            let heading = 'Templates';

            title = title.replace('{feature_name}', heading);
            subtitle = subtitle.replace('{feature_name}', heading);
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

            let sw = wffn_swal({ 
                'html': subtitle,
                'title': title,
                'type': '',
                'confirmButtonText': wffn.upgrade_button_text,
                'showCancelButton': true,
                'showCloseButton': true,
                'customClass': "wf-swal-pro-modal swal-pro-modal swal-upgrade-pro",
                'focusConfirm': false,
                'reverseButtons': true,
                'onOpen': (e) => {
                    let modalWrapper = e.closest( '.swal2-container' );
                    if( modalWrapper ) {
                        modalWrapper.classList.add( 'wf-pro-modal-container' );
                    }
                }
            });
            sw.then((result) => {
                if (result.value) {
                    // Success
                    window.open(wffn.pro_link, '_blank');
                }
            });
            sw.catch(() => {
            });
            e.preventDefault();

        });


    }; //let wffn_builder = function ()
    $(win).load(
        function () {
            window.wffnBuilder = new wffn_builder();
            $('div.updated, div.error, div.notice').not('.inline, .below-h2').insertBefore($(".wp-header-end"));

        }
    );
    window.wffnBuilderCommons = wffnBuilderCommons;
})(jQuery, document, window);