<?php
?>
<style>
    /* WooFunnels - Aero Checkout */
    #wfop_design_container * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .woofunnels_page_wfacp {
        height: auto;
        position: relative;
    }


    .wfop_clear {
        clear: both
    }

    .clearfix:before, .clearfix:after {
        display: table;
        content: "";
    }

    .clearfix:after {
        clear: both;
    }

    .wfop_clear_5 {
        clear: both;
        height: 10px
    }

    .wfop_clear_10 {
        clear: both;
        height: 10px
    }

    .wfop_clear_20 {
        clear: both;
        height: 20px
    }

    .wfop_clear_30 {
        clear: both;
        height: 30px
    }

    .wfop_hide {
        display: none
    }

    .wfop_fl {
        float: left
    }

    .wfop_fr {
        float: right
    }

    .wfop_tl {
        text-align: left
    }

    .wfop_tr {
        text-align: right
    }

    .wfop_tc {
        text-align: center
    }

    .wfop_p0 {
        padding: 0
    }

    .wfop_p10 {
        padding: 10px
    }

    .wfop_p15 {
        padding: 15px
    }

    .wfop_p20 {
        padding: 20px
    }

    .wfop_p25 {
        padding: 25px
    }

    .wfop_pt0 {
        padding-top: 0
    }

    .wfop_pt10 {
        padding-top: 10px
    }

    .wfop_pt20 {
        padding-top: 20px
    }

    .wfop_pb0 {
        padding-bottom: 0
    }

    .wfop_pb10 {
        padding-bottom: 10px
    }

    .wfop_pb20 {
        padding-bottom: 20px
    }

    .wfop_p20_wrap {
        padding: 0 20px
    }

    .wfop_m0 {
        margin: 0
    }

    .wfop_m10 {
        margin: 10px
    }

    .wfop_m20 {
        margin: 20px
    }

    .wfop_mt0 {
        margin-top: 0
    }

    .wfop_mt10 {
        margin-top: 10px
    }

    .wfop_mt20 {
        margin-top: 20px
    }

    .wfop_mb0 {
        margin-bottom: 0
    }

    .wfop_mb10 {
        margin-bottom: 10px
    }

    .wfop_mb20 {
        margin-bottom: 20px
    }

    .wfop_h10 {
        height: 10px
    }

    .wfop_h20 {
        height: 20px
    }

    .wfop_table {
        display: table;
        height: 100%;
        width: 100%
    }

    .wfop_table_cell {
        display: table-cell;
        vertical-align: middle
    }

    .wfop_box_size {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -o-box-sizing: border-box;
        box-sizing: border-box;
    }

    .wfop_heading_s {
        color: #23282d;
        font-size: 1em;
        margin: 0 0 1em 0
    }

    .wfop_heading_m {
        color: #23282d;
        font-size: 1.3em;
        margin: 0 0 1em 0;
        font-weight: 600
    }

    .wfop_heading_l {
        color: #23282d;
        font-size: 1.4em;
        margin: 0 0 1em 0;
        font-weight: 600
    }

    .wfop_heading_xl {
        color: #23282d;
        font-size: 2em;
        margin: 0 0 1em 0;
        font-weight: 600
    }

    .wfop_heading_xl img {
        max-width: 45px;
        margin-right: 15px;
        position: relative;
        top: 12px
    }

    .wfop_text {
        font-size: 17px;
        line-height: 23px;
        margin-bottom: 15px
    }

    .wfop_c_l {
        color: var(--wffn-desc);
    }

    .wfop_body a {
        outline: none !important;
        text-shadow: none !important;
        text-decoration: none;
    }

    /* header */
    .wfop_fixed_header {
        height: 56px;
        margin-top: 5px;
    }

    .wfop_head_l {
        width: 20%
    }

    .wfop_head_m {
        width: 100%;
        font-size: 22px;
        line-height: 24px;
        font-weight: 300;
    }

    .wfop_head_m .dashicons {
        font-size: 17px;
        line-height: 29px !important;
        line-height: inherit;
        width: auto;
        height: auto;
    }

    .wfop_head_ml {
        display: inline-block;
    }

    .wfop_head_mr {
        display: inline-block;
        margin-right: 8px;
        position: relative;

    }

    .wfop_head_mr span, .wfop_head_mr .funnel_state_toggle {
        display: inline-flex;
        top: 2px;
        position: relative;
    }

    .wfop_head_mr span {
        font-weight: 600;
        color: #6dbe45;
    }

    span.wfop_head_funnel_state_off {
        color: #fc7626;
    }

    .wfop_head_mr[data-status="sandbox"] span {
        color: #fc7626;
    }


    .wfop_head_r {
        width: 20%
    }

    .wfop_head_m strong {
        color: #222;
    }

    .wfop_head_close {
        background-color: #b5b5b5;
        width: 35px;
        height: 35px;
        display: inline-block;
        text-align: center;
        color: #fff !important
    }

    .wfop_head_close:hover {
        background-color: #8a8a8a;
    }

    .wfop_head_close i {
        width: auto;
        height: auto;
        font-size: 28px;
        line-height: 35px;
    }

    .wfop_fixed_header .wfop_box_size {
        display: flex;
    }

    /* sidebar */
    .wfop_fixed_sidebar {
        padding: 0 20px;
        top: 62px;
        left: 0;
        bottom: 0;
        background: #fff;
    }

    .wfop_s_menu {
        display: inline-block;
        color: #777;
        padding: 15px 15px;
        box-sizing: border-box;
        margin-right: 16px;
        border-bottom: 4px solid transparent;
    }

    .wfop_primary_tabs_wrap {
        margin-bottom: 20px;
    }

    .wfop_s_menu.publish, .wfop_s_menu.publish:hover, .wfop_s_menu.publish:focus {
        background-color: #5d9940;
        color: #fff;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        -ms-box-shadow: none;
        -o-box-shadow: none;
        box-shadow: none;
    }

    .wfop_s_menu:hover, .wfop_s_menu:focus, .wfop_s_menu.active {
        border-color: #6dbe45;
        color: #6dbe45;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        -ms-box-shadow: none;
        -o-box-shadow: none;
        box-shadow: none;
    }

    .wfop_s_menu:hover span.wfop_s_menu_i, .wfop_s_menu:focus span.wfop_s_menu_i, .wfop_s_menu.active span.wfop_s_menu_i {
        color: #6dbe45;
    }

    .wfop_s_menu span {
        width: 100%;
        display: inline;
        text-align: center;
        font-size: 14px
    }

    .wfop_s_menu span.wfop_s_menu_i {
        color: #444444;
        padding: 0 2px 5px 0;
        font-size: 15px;
        margin: 0 auto;
        height: auto
    }

    .wfop_s_menu span.wfop_s_menu_i img {
        width: 100%
    }

    /* content */
    .wfop_wrap {
        width: 100%;
        min-height: calc(100vh - 200px);
        position: relative;
    }

    .wfop_wrap_inner {
        margin-left: 370px;
        clear: both
    }

    .wfop_wrap_l {
        float: left;
        width: 350px;
        margin-left: -370px;
        background: none;
        min-height: 500px
    }

    .wfop_wrap_r {
        float: right;
        width: 100%;
        background-color: transparent;
        position: relative;
    }

    /* steps */
    .wfop_steps {
        width: 100%;
        display: block
    }

    .wfop_step {
        position: relative;
        display: inline-block;
        text-align: center;
        padding: 13px 80px;
        font-size: 15px;
        color: #6b6b6b !important;
        background: #fff;
        border: 1px solid #e6e0e0;
        margin-bottom: 12px;
        box-sizing: border-box
    }

    .wfop_steps_sortable {
        margin-bottom: 15px;
    }

    .wfop_button_add {
        border: 2px dashed #85bdd8;
        color: #0372a7 !important;
        font-weight: 600;
        margin: 0;
        width: auto;
        cursor: pointer;
        display: inline-block;
    }

    .wfop_button_inline {
        width: auto;
        margin: 0;
    }

    .wfop_down_arrow:after, .wfop_down_arrow:before {
        position: absolute;
        content: "";
        width: 0;
        left: 50%;
        bottom: -11px;
        margin-left: -14px;
        height: 0
    }

    .wfop_down_arrow:after {
        border-left: 11px solid transparent;
        border-right: 11px solid transparent;
        border-top: 11px solid #fff
    }

    a.wfop_step.current_offer .wfop_down_arrow:after {
        border-top: 11px solid #dcf5df;
    }

    .wfop_down_arrow:before {
        border-left: 13px solid transparent;
        border-right: 13px solid transparent;
        border-top: 13px solid #e6e0e0;
        margin-left: -16px;
        bottom: -13px
    }

    .wfop_up_arrow:after, .wfop_up_arrow:before {
        position: absolute;
        content: "";
        width: 0;
        left: 50%;
        top: -1px;
        margin-left: -14px;
        height: 0
    }

    .wfop_up_arrow:after {
        border-left: 11px solid transparent;
        border-right: 11px solid transparent;
        border-top: 11px solid #f1f1f1
    }

    .wfop_up_arrow:before {
        border-left: 13px solid transparent;
        border-right: 13px solid transparent;
        border-top: 13px solid #e6e0e0;
        margin-left: -16px
    }

    .wfop_icon_fixed {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 40px;
        height: auto;
        line-height: 60px;
        background: transparent;
        text-align: center
    }

    .wfop_icon_fixed {
        font-size: 32px;
    }

    /* button */
    .wfop_button_fill {
        padding: 15px 25px;
        font-size: 18px;
        line-height: 24px;
        color: #fff;
        font-weight: 400;
        min-width: 160px;
        display: inline-block;
        cursor: pointer;
        border: none;
        background: #0073aa
    }

    .wfop_button_fill:hover, .wfop_button_fill:focus {
        color: #fff;
        outline: none;
        box-shadow: none
    }

    /* forms */
    .wfop_vue_forms {
    }

    .wfop_vue_forms .form-group {
        margin: 0 0 15px 0;
		clear:both;
    }

    .wfop_vue_forms .errors.help-block {
        margin-top: 5px;
    }

    .wfop_vue_forms .error {
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        -ms-box-shadow: none;
        -o-box-shadow: none;
        box-shadow: none;
        background-color: transparent;
    }


    #modal-edit-field .iziModal-header p {
        font-size: 14px;
        line-height: 1.5;
        white-space: inherit;
        overflow: unset;
        text-overflow: unset;
        color: #000;
        padding: 0 12px;
    }

    .wfop_vue_forms .form-group label {
        font-size: 13px;
        line-height: 1.4em;
        color: #3c434a;
        display: block;
        margin-bottom: 7px;
        cursor: default;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.wfop_show_field_lable.field-label {
        width: 33.3%;
        float: left;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.wfop_full_width_input.field-input {
        width: 66.8%;
        margin-right: 0;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-label {
        display: block;
        width: 100%;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-switch {
        display: inline-block;
        width: 200px;
        margin-right: 6%;
        margin-top: 10px;
        margin-bottom: 1rem;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-switch div.field-wrap {
        float: left;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-switch > label {
        float: right;
        font-size: 14px;
        line-height: 17px;
        width: 150px;
    }

    #edit-field-form .vue-form-generator .field-switch input ~ .label, #wfop_global_settings .vue-form-generator .field-switch input ~ .label {
        background-color: #fc7626;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-input {
        display: inline-block;
        margin-right: 15px;
        width: 32%;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-input:nth-child(3n) {
        margin-right: 0;
    }

    #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-input label {
        display: none;
    }

    #edit-field-form .vue-form-generator .field-switch input:checked ~ .label, #wfop_global_settings .vue-form-generator .field-switch input:checked ~ .label {
        background: #8dc73f;
    }

    .vue-form-generator .field-switch .field-wrap label {
        width: 40px;
        height: 20px
    }

    .template_steps_container .single_step_template .template_step_title a {
        float: right;
    }

    #edit-field-form .vue-form-generator .field-switch .handle {
        width: 18px;
        height: 18px;
        top: 0;
        left: 0;
    }

    .swal2-header .swal2-icon.swal2-warning {
        font-size: 15px;
        line-height: 77px;
        background: #ee6723; border: 0; position: relative; margin: 0 auto 15px;
    }
    .swal2-header .swal2-icon.swal2-warning, .swal2-header .swal2-icon.swal2-error{
        display: none !important;
    }
.swal2-icon.swal2-warning.swal2-animate-warning-icon:before {
    content: '';
    position: absolute;
    width: calc(100% + 16px);
    height: calc(100% + 16px);
    border: 4px solid #007cba;
    top: -12px;
    border-radius: 50%;
}

    #edit-field-form .vue-form-generator .field-switch .field-wrap label {
        width: 36px;
        height: 18px;
    }

    #edit-field-form .vue-form-generator .field-switch input:checked ~ .handle, #wfop_global_settings .vue-form-generator .field-switch input:checked ~ .handle {
        right: 0;
        left: auto;
        top: 0
    }

    #edit-field-form .vue-form-generator [data-on="Active"], #edit-field-form .vue-form-generator [data-on="Inactive"] {
        text-indent: -999999px;
    }

    #edit-field-form .vue-form-generator .field-switch .handle:before, #wfop_global_settings .vue-form-generator .field-switch .handle:before {
        margin: -4px 0 0 -4px;
        width: 8px;
        height: 8px;
    }

    #wfop_global_settings .vue-form-generator .field-switch .label:before, #wfop_global_settings .vue-form-generator .field-switch .label:after {
        display: none
    }

    .vue-form-generator .field-switch .field-wrap label {
        width: 48px;
        height: 23px;
    }

    .vue-form-generator .field-switch .label:before, .vue-form-generator .field-switch .label:after {
        content: ""
    }

    .wfop_vue_forms .form-group input[type='text'],
    .wfop_vue_forms .form-group textarea,
    .wfop_vue_forms .form-group select,
    .wfop_vue_forms .form-group textarea {
        font-size: 13px;
        line-height: normal;
        color: #3c434a;
        padding: 12px 10px;
        background-color: #fff;
        border: 1px solid #d5d5d5;
        box-shadow: none;
        display: block;
        width: 100%;
        max-width: 100%;
        margin: 0;
        resize: none;
        box-sizing: border-box;
        border-radius: 2px;
    }

    .wfop_vue_forms .form-group select {
        background-image: none;
        -webkit-appearance: menulist;
    }

    .no_product_wrap {
        padding: 10px 0 15px;
    }

    .wfop_vue_forms .form-group.error input[type='text'], .wfop_vue_forms .form-group.error textarea, .wfop_vue_forms .form-group.error select, .wfop_vue_forms .form-group.error textarea {
        border-color: #dc3232;
    }

    .wfop_vue_forms .form-group.error {
        border-color: transparent;
        margin-left: 0;
        padding-left: 0;
        border: none;
    }

    .wfop_vue_forms .form-group label span.help {
        margin-left: .3em;
        position: relative
    }

    .wfop_vue_forms .form-group label span.help .icon {
        display: inline-block;
        font-size: 16px;
        font-family: 'dashicons';
        font-style: normal;
        color: #666;
        position: absolute;
        top: 0px;
    }

    .wfop_vue_forms .form-group label span.help .icon:before {
        content: "\f223";
    }

    .wfop_vue_forms .form-group label span.help .helpText {
        background-color: #444;
        bottom: 30px;
        color: #fff;
        display: block;
        left: 0;
        opacity: 0;
        padding: 10px 15px;
        pointer-events: none;
        position: absolute;
        text-align: justify;
        width: 300px;
        -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -moz-border-radius: 6px;
        -webkit-border-radius: 6px;
        -ms-border-radius: 6px;
        -o-border-radius: 6px;
        border-radius: 6px;
        font-size: 11px;
        line-height: 18px;
        z-index: 10;
    }

    .wfop_vue_forms .form-group label span.help .helpText a {
        font-weight: 700;
        text-decoration: underline
    }

    .wfop_vue_forms .form-group label span.help .helpText:before {
        bottom: -20px;
        content: " ";
        display: block;
        height: 20px;
        left: 0;
        position: absolute;
        width: 100%
    }

    .wfop_vue_forms .form-group label span.help:hover .helpText {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0)
    }

    .wfop_vue_forms .form-group.wfop_form_button .field-wrap label {
        display: inline-block;
        background: #ccc;
        color: #fff;
        padding: 10px 25px;
        margin-right: 15px
    }

    .wfop_vue_forms .form-group.wfop_form_button .field-wrap label input {
        position: absolute;
        visibility: hidden;
        display: none
    }

    .wfop_vue_forms .form-group.wfop_form_button .field-wrap label.is-checked {
        background: #9a929e;
    }

    #edit-field-form .wfop_vue_forms .form-group.field-radios .radio-list > label {
        margin-right: 15px;
    }

    #modal-add-product-form #product_search {
        margin-bottom: 15px;
    }

    #modal-add-product-form .form-group {
        margin-bottom: 15px;
    }

    #modal-add-product-form .wfop_pro_label_wrap .wfop_select_pro_wrap {
        float: left;
        width: 50%;
    }

    #modal-add-product-form .wfop_pro_label_wrap .wfop_inc_var_wrap {
        float: right;
        width: 50%;
        text-align: right;
    }

    .iziModal .iziModal-button-close:hover {
        transform: none;
    }

    .iziModal-content .bwf_form_submit {
        text-align: right;
        padding: 0;
        margin: 20px 0px 0px;
        display: flex;
        justify-content: flex-end;
        gap:8px;
        width: 100%;
        box-sizing: border-box;
    }

    .iziModal:not(.wfop_success_modal) .iziModal-header .iziModal-header-title {
        font-size: 20px;
        font-family: 'Open Sans', sans-serif;
    }

    .wfop_success_modal.iziModal {
        left: auto;
    }

    .iziModal {
        background: #fff !important;
    }

    a.wfop_step.current_offer {
        background: #dcf5df;
    }

    .wfop_step_container {
        position: relative;
    }

    span.wfop_icon_delete.dashicons.dashicons-dismiss {
        color: red;
        width: 25px;
        height: 25px;
        font-size: 25px;
        position: absolute;
        right: -11px;
        top: -11px;
        display: none;
        z-index: 100;
    }

    .wfop_step_container:hover span.wfop_icon_delete.dashicons.dashicons-dismiss {
        display: inline;
    }

    .wfop_step:hover {
        background: #dcf5df;
    }

    .wfop_step[data-select='selected'] {
        background: #dcf5df;
    }

    .wfop_step[data-current-template='yes'] {
        background: #dcf5df;
    }

    button.wfop_save_btn_style {
        display: inline-block;
        width: 29%;
        margin: 2%;
    }
    .wfop_save_btn_style.is_busy {
        opacity: 1 !important;
        background-image: linear-gradient( -45deg,#007cba 33%,#005a87 0,#005a87 70%,#007cba 0) !important;
        animation: components-button__busy-animation 2.5s linear infinite;
        color: #fff;
        pointer-events: none;

    }

    .wfop_step:hover .wfop_down_arrow:after {
        border-top-color: #dcf5df
    }

    a.wfop_step.wfop_offer_off:hover {
        background: #dcf5df;
    }

    .wfop_template_box {
        width: 33.33%;
        float: left;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    ul.wfop_field_container.ui-sortable {
        padding: 10px;
    }

    .wfop_save_btn_style.wfop_item_drag {
        /*width: 100px;*/
        margin: 5px 10px 5px 0;
        position: relative;;
    }

    .wfop_input_fields_list_wrap .wfop_save_btn_style.wfop_item_drag {
        margin: 0;
        cursor: grabbing;
    }

    span.wfop_remove_fields {
        display: none;
    }

    .wfop_save_btn_style:hover span.wfop_remove_fields {
        display: inline;
    }

    .template_steps_container .single_step_template, .wfop_input_fields_list_wrap {
        background: #fff;
        padding: 25px 10px 10px;
    }

    .template_steps_container .wfop_input_fields_btn .button {
        max-width: 200px;
    }

    .template_steps_container .wfop_input_fields_btn p {
        font-size: 17px;
        color: var(--wffn-desc);
        margin: 0 auto 27px;
    }

    .template_steps_container .wfop_input_fields_btn {
        width: 100%;
        text-align: left;
        padding: 0;
    }

    .wfop_input_fields_list_wrap {
        padding: 25px 25px 10px 25px;
    }

    .single_step_template_inner {
        padding: 8px 0
    }

    .wfop_input_fields_list_wrap {
        margin-bottom: 0px;
    }

    .wfop_input_fields_btn .button {
        box-shadow: none;
    background: #f9fdff;
    height: auto;
    border: 2px dashed var(--wffn-primary);
    color: var(--wffn-primary);
    min-width: 84px;
    text-align: center;
    padding: 0 16px;
    font-size: 14px;
    min-height: 36px;
    border-radius: 3px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    }

    .wfop_input_fields_btn .button span {
        margin-right: 9px
    }

    .template_steps_container .single_step_template {
        margin-bottom: 15px;
        padding-bottom: 35px;
        position: relative;
    }

    #wfop_layout_container {
        margin-right: 20px;
        margin-top:-24px;
    }

    #wfop_layout_container .single_step_template {
        padding: 25px 15px;
        background: #fff;
        margin-left: 15px;
        margin-right: 15px;
    }

    #wfop_layout_container .template_field_selecter {
        margin-bottom: 40px;
        border: 1px solid var(--wffn-border-color);
        border-radius: 10px;
        overflow: hidden;
    }

    .wfop_wrap_inner_fields .template_steps_container {
        position: relative;
    }


    .wfop_wrap_inner_fields .wfop_step_actions {
        font-size: 16px;
        display: inline;
    }

    .wfop_wrap_inner_fields .wfop_step_heading {
        position: relative;
        float: left;
        background: #fff;
        font-weight: 600;
        color: #303030;
        height: 51px;
    }

    .template_steps_container .wfop_template_tabs_container {
        display: inline;
    }

    .wfop_template_tabs_container .wfop_add_new_step {
        display: inline;
        float: left;
    }

    .wfop_template_tabs {
        padding: 11px 40px 10px 40px;
        cursor: pointer;
        background: #f6fcff;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        border-bottom: 2px solid #f6fcff;
    }

    .wfop_template_tabs.wfop_active_tabs {
        border-bottom: 2px solid #0085c9;
        color: #0085c9;
        cursor: default;
        background: #fff;
    }

    .wfop_wrap_inner_fields .wfop_step_heading .dashicons-dismiss {
        color: #d2e9f5;
        cursor: pointer;
        display: inline-none;
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .wfop_wrap_inner_fields .wfop_step_heading .dashicons-dismiss:hover {
        color: #ff7070;
    }

    .wfop_step.wfop_button_add.wfop_modal_open {
        height: 51px;
        padding: 13px 20px;
        background: #fff;
    }

    .wfop_wrap_inner_fields .wfop_step_remove {
        float: left;
        padding: 15px 30px;
        cursor: pointer;
    }

    .wfop_input_fields_title, .wfop_input_fields_title b {
        text-transform: capitalize;
        font-size: 16px;
        font-weight: 500;
    }

    .wfop_input_fields_btn .button:hover {
        background: var(--wffn-border-color);
        border-color: var(--wffn-primary-alter);
        color:var(--wffn-primary-alter);
    }

    body .wfop_input_fields {
        margin-top: 0;
        margin-bottom: 0px;
    }

    .wfop_input_fields_btn {
        padding: 0 0 20px 0;
        background: #fff;
        text-align: center;
    }

    .template_step_title {
        font-size: 16px;
    }

    .wfop_input_fields_list span.wfop_remove_fields,
    .template_steps_container span.wfop_remove_fields,
    .wfop_input_fields_list_wrap .wfcp_remove_fields {
        display: none;
        position: absolute;
        top: -9px;
        right: -9px;
        border: 1px solid red;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 15px;
        line-height: 13px;
        text-align: center;
        background: red;
        color: #fff;
        text-shadow: none;
        cursor: pointer;
        padding: 2px 2px 17px 2px;
        z-index: 999999999;

    }

    .template_steps_container span.wfop_remove_fields:before {
        line-height: 16px;
        display: block;
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        margin-top: -8px;
        text-align: center;
        font-size: 15px;

    }

    .wfop_input_fields_list .wfop_save_btn_style:hover span.wfop_remove_fields {
        display: inline;
    }


    .wfop_field_container .template_field_container {
        margin: 0px;
        min-height: 100px;
        padding: 10px;

    }


    .wfop_field_container.activate_dragging_field {
        border-color: #e4e4e4;
    }

    body .wfop_field_container .ui-state-highlight span.placeholder_text_here {
        opacity: 1;
        display: inline-block;
    }

    body .wfop_field_container .ui-state-highlight {
        background: transparent;
        min-width: 75px;
        height: auto;

        border-radius: 0;
        padding: 5px 11px;
        margin: 5px 10px 5px 0;
        text-shadow: none;
        box-shadow: none;
        border: 1px dashed #adadad;
    }

    #wfop_setting_container.wfop_inner_setting_wrap .wfop_global_checkout_note .hint {
        max-width: 600px;
    }

    #wfop_setting_container.wfop_inner_setting_wrap .form-group.wfop_setting_heading.wfop_admin_preview_feilds_wrap .hint {
        max-width: 600px;
    }

    .wfop_inner_setting_wrap .form-group.wfop_setting_heading.field-label.wfop_inner_preview_heading {
        margin: 0 0 -10px;
    }


    .wfop_field_container.highlight_field_container,
    .wfop_field_container .wfop_save_btn_style.wfop_item_drag.ui-sortable-helper {
        border: 2px dashed #0085BA;
    }

    .bwf_ajax_save_buttons {
        display: inline-block;
        margin-left: 5px;
        position: relative;
        float: right;

    }

    .ajax_loader_show {
        visibility: visible
    }

    .wfop_template_box .wfop_template_box_inner {
        border: 4px solid #e0e0e0;
        margin-left: 10px;
        margin-right: 10px;
    }

    .wfop_template_box[data-select="selected"] .wfop_template_box_inner {
        border: 4px solid #a0d587;
    }

    .wfop_template_box_holder {
        padding: 25px 40px 40px;
        background: #fff;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        border: 1px solid var(--wffn-border-color, #dedfea);
    }

    .wfop_template_box_holder .design_container .wfop_template_box:nth-child(3n+1) {
        clear: both;
    }

    .wfop_template_holder_head .wfop_screen_title {
        margin: 0 0 0.7em;
        font-weight: 100;
    }

    .wfop_template_holder_head .wfop_screen_title span {

        font-weight: 700;
    }

    .wfop_field_container .wfop_save_btn_style.wfop_item_drag .dashicons-edit {
        margin-top: 3px;
        margin-right: 0px;

    }

    .wfop_template_holder_head {
        padding: 0px 8px 25px;
    }

    span.wfop_step_offer_state {
        position: absolute;
        width: 11px;
        height: 11px;
        background: #6dbe45;
        border-radius: 50%;
        right: 14px;
        top: 24px;
    }

    span.wfop_step_offer_state.state_off {
        background: #fc7626;
    }

    .wfop_template_box .wfop_template_btm_strip {
        background-color: #f6f7f8;
        padding: 10px;
        position: relative;
    }

    .wfop_template_box .wfop_template_name {
        font-size: 14px;
        line-height: 20px;
        color: #454545;
        padding-right: 95px;
    }

    .wfop_template_box .wfop_template_button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        z-index: 1;
    }

    .wfop_template_box .wfop_template_img_cover .wfop_overlay {
        background-color: #eeeeee;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: none;
        z-index: 1;
    }

    .wfop_template_box .wfop_template_img_cover {
        display: block;
        text-align: center;
        position: relative;
    }

    .wfop_template_box .wfop_template_img_cover:hover .wfop_overlay {
        display: block;
    }

    .wfop_template_box .wfop_overlay .wfop_view_img {
        z-index: 1;
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
    }

    .wfop_template_box .wfop_template_thumbnail img {
        max-width: 100%;
    }

    .wfop_clearfix:before {
        display: table;
        content: '';
    }

    .wfop_clearfix:after {
        display: table;
        content: '';
        clear: both;
    }

    .modal-prev-temp .iziModal-content .wfop_img_preview {
        padding: 0;
    }

    .modal-prev-temp .wfop_img_preview img {
        width: 100%;
        max-width: 100%;
    }

    .wfop_template_box .wfop_template_thumbnail {
        position: relative;
    }

    .wfop_template_box .wfop_overlay_icon i {
        font-size: 75px;
        width: auto;
        height: auto;
        color: #fff;
    }

    .wfop_template_box .wfop_overlay_icon {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        z-index: 1;
    }

    .wfop_template_box.wfop_temp_box_custom .wfop_thumb_publish {
        display: none;
    }

    .wfop_template_box.wfop_temp_box_custom.wfop_selected_template .wfop_thumb_def {
        display: none;
    }

    .wfop_template_box.wfop_temp_box_custom.wfop_selected_template .wfop_thumb_publish {
        display: block;
    }

    .wfop_save_btn_style.wfop_item_drag, .wfop_submit_btn_style.wfop_item_drag {
        width: 100%;
    background: #f7f7f7;
    border-color: var(--wffn-border-color);
    color: var(--wffn-text);
    height: auto;
    min-width: 84px;
    text-align: center;
    padding: 0 6px;
    border-radius: 3px;
    font-size: 14px;
    min-height: 36px;
    box-shadow: none;
    text-decoration: none;
    display: inline-flex;
    text-align: center;
    align-items: center;
    justify-content: center;
    }

    .wfop_input_fields_list {
        margin-top: 16px;
    }

    .wfop_field_container .wfop_save_btn_style.wfop_item_drag {
        text-transform: capitalize;
        position: relative;
        border: 1px solid var(--wffn-border-color);
        min-width: 84px;
        text-align: center;
        padding: 0 16px;
        border-radius: 3px;
        font-size: 14px;
        min-height: 36px;
        box-shadow: none;
        text-decoration: none;
        display: inline-flex;
        text-align: center;
        align-items: center;
        justify-content: center;
    }

    .wfop_field_container .wfop_save_btn_style.wfop_item_drag {
        color: #3A3A3A;
        width: auto;
    }


    .wfop_field_container .wfop_save_btn_style.wfop_item_drag:hover {
        color: #a8c4d1;
    }

    .wfop_field_container .wfop_save_btn_style.wfop_item_drag:hover {
        background: #d0ebf8;
        border-color: #cce7f5;
        cursor: move;
    }

    .wfop_field_container .wfop_save_btn_style.wfop_item_drag:hover:after {
        content: '\f464';
        font-family: 'dashicons';
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-size: 18px;
        margin: auto;
        color: #0085ba;
        font-size: 18px;
        line-height: 36px;
        z-index: 444444444;
    }

    .wfop_field_container .wfop_save_btn_style.wfop_item_drag:hover span:last-child {
        opacity: 0.5;
    }

    @media (min-width: 1200px) {
        .wfop_template_list .wfop_template_box:nth-child(3n+1) {
            clear: both;
        }
    }

    /** datepicker css**/
    .xl-datepickers.ui-datepicker {
        width: 17em;
        padding: .2em .2em 0;
        display: none
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-header {
        position: relative;
        padding: .2em 0
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-next, .xl-datepickers.ui-datepicker .ui-datepicker-prev {
        position: absolute;
        top: 2px;
        width: 1.8em;
        height: 1.8em
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-next-hover, .xl-datepickers.ui-datepicker .ui-datepicker-prev-hover {
        top: 1px
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-prev {
        left: 2px
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-next {
        right: 2px
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-prev-hover {
        left: 1px
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-next-hover {
        right: 1px
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-next span, .xl-datepickers.ui-datepicker .ui-datepicker-prev span {
        display: block;
        position: absolute;
        left: 50%;
        margin-left: -8px;
        top: 50%;
        margin-top: -8px
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-title {
        margin: 0 2.3em;
        line-height: 1.8em;
        text-align: center
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-title select {
        font-size: 1em;
        margin: 1px 0
    }

    .xl-datepickers.ui-datepicker select.ui-datepicker-month, .xl-datepickers.ui-datepicker select.ui-datepicker-year {
        width: 45%
    }

    .xl-datepickers.ui-datepicker table {
        width: 100%;
        font-size: .9em;
        border-collapse: collapse;
        margin: 0 0 .4em
    }

    .xl-datepickers.ui-datepicker th {
        padding: .7em .3em;
        text-align: center;
        font-weight: 700;
        border: 0
    }

    .xl-datepickers.ui-datepicker td {
        border: 0;
        padding: 1px
    }

    .xl-datepickers.ui-datepicker td a, .xl-datepickers.ui-datepicker td span {
        display: block;
        padding: .2em;
        text-align: right;
        text-decoration: none
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-buttonpane {
        background-image: none;
        margin: .7em 0 0;
        padding: 0 .2em;
        border-left: 0;
        border-right: 0;
        border-bottom: 0
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-buttonpane button {
        float: right;
        margin: .5em .2em .4em;
        cursor: pointer;
        padding: .2em .6em .3em;
        width: auto;
        overflow: visible
    }

    .xl-datepickers.ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current, .xl-datepickers.ui-datepicker-multi .ui-datepicker-group, .xl-datepickers.ui-datepicker-rtl .ui-datepicker-buttonpane button {
        float: left
    }

    .xl-datepickers.ui-datepicker.ui-datepicker-multi {
        width: auto
    }

    .xl-datepickers.ui-datepicker-multi .ui-datepicker-group table {
        width: 95%;
        margin: 0 auto .4em
    }

    .xl-datepickers.ui-datepicker-multi-2 .ui-datepicker-group {
        width: 50%
    }

    .xl-datepickers.ui-datepicker-multi-3 .ui-datepicker-group {
        width: 33.3%
    }

    .xl-datepickers.ui-datepicker-multi-4 .ui-datepicker-group {
        width: 25%
    }

    .xl-datepickers.ui-datepicker-multi .ui-datepicker-group-last .ui-datepicker-header, .xl-datepickers.ui-datepicker-multi .ui-datepicker-group-middle .ui-datepicker-header {
        border-left-width: 0
    }

    .xl-datepickers.ui-datepicker-multi .ui-datepicker-buttonpane {
        clear: left
    }

    .xl-datepickers.ui-datepicker-row-break {
        clear: both;
        width: 100%;
        font-size: 0
    }

    .xl-datepickers.ui-datepicker-rtl {
        direction: rtl
    }

    .xl-datepickers.ui-datepicker-rtl .ui-datepicker-prev {
        right: 2px;
        left: auto
    }

    .xl-datepickers.ui-datepicker-rtl .ui-datepicker-next {
        left: 2px;
        right: auto
    }

    .ui-datepicker-rtl .ui-datepicker-prev:hover {
        right: 1px;
        left: auto
    }

    .xl-datepickers.ui-datepicker-rtl .ui-datepicker-next:hover {
        left: 1px;
        right: auto
    }

    .xl-datepickers.ui-datepicker-rtl .ui-datepicker-buttonpane {
        clear: right
    }

    .xl-datepickers.ui-datepicker-rtl .ui-datepicker-buttonpane button.ui-datepicker-current, .xl-datepickers.ui-datepicker-rtl .ui-datepicker-group {
        float: right
    }

    .xl-datepickers.ui-datepicker-rtl .ui-datepicker-group-last .ui-datepicker-header, .xl-datepickers.ui-datepicker-rtl .ui-datepicker-group-middle .ui-datepicker-header {
        border-right-width: 0;
        border-left-width: 1px
    }

    .xl-datepickers.ui-datepicker .ui-icon {
        display: block;
        text-indent: -99999px;
        overflow: hidden;
        background-repeat: no-repeat;
        left: .5em;
        border: 1px solid #ddd;
        top: .3em
    }

    .xl-datepickers.ui-widget-content.ui-datepicker {
        background: #fff;
        color: #333
    }

    .xl-datepickers.ui-widget-content.ui-datepicker {
        border: 1px solid #c5c5c5
    }

    .xl-datepickers.ui-widget-content.ui-datepicker .ui-state-default {
        border: 1px solid #c5c5c5;
        background: #f6f6f6;
        font-weight: normal;
        color: #454545;
    }

    /* funnel listing */
    .wfop_global .wfop_page_col2_wrap {
        margin-right: 0px;
        clear: both;
        margin-top: 15px;
    }

    .wfop_global .wfop_page_left_wrap {
        float: left;
        width: calc(100% - 0px);
        min-height: 1px;
    }

    .wfop_global.wfop_post_table .wfop_page_left_wrap {
        width: calc(100% - 300px);
    }

    .wfop_global .wfop_page_right_wrap {
        float: left;
        margin-left: 20px;
        width: 280px;
    }

    /* side content */
    .postbox.wfop_woofunnels {
        border: none;
        box-shadow: none !important;
        padding: 10px;
        color: #fff;
        font-size: 15px;
        position: relative
    }

    .postbox.wfop_woofunnels_sales_trigger {
        background: #9164b5
    }

    .postbox.wfop_woofunnels:after {
        display: table;
        clear: both;
        content: ''
    }

    .postbox.wfop_woofunnels > img {
        max-width: 60px;
        position: absolute;
        left: 10px;
        top: 15px
    }

    .postbox.wfop_woofunnels > a {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 10
    }

    .postbox.wfop_woofunnels > .wfop_plugin_head {
        font-weight: 700;
        font-size: 14px;
        line-height: 18px;
        margin: 0;
        margin-bottom: 5px;
        padding: 0 0 0 70px
    }

    .postbox.wfop_woofunnels > .wfop_plugin_desc {
        font-size: 12px;
        line-height: 15px;
        padding: 0 0 0 70px;
        margin: 0
    }

    /* Listing table */
    .wfop_fsetting_table_title {
        text-align: right;
        /* top: -3px; */
        position: relative;
    }

    .wfop_fsetting_table_title .offer_state.wfop_toggle_btn {
        display: inline-block;
        position: relative;
        top: 3px;
    }


    table.wp-list-table.woofunnels_page_aero_checkout .check-column {
        width: 2.2em;
    }

    table.wp-list-table.woofunnels_page_aero_checkout #preview {
        width: 2.2em;
    }

    table.wp-list-table.woofunnels_page_aero_checkout #description {
        width: 20%;
    }

    table.wp-list-table.woofunnels_page_aero_checkout #last_update {
        width: 15%;
    }

    table.wp-list-table.woofunnels_page_aero_checkout #priority {
        width: 10%;
    }

    table.wp-list-table.woofunnels_page_aero_checkout #quick_links {
        width: 22%;
    }

    .wfop_page_col2_wrap ul.subsubsub.subsubsub_wfacp li:after {
        /*content: "|";*/
        padding: 0 3px
    }

    .wfop_page_col2_wrap ul.subsubsub.subsubsub_wfacp li:last-child:after {
        content: ""
    }

    .wfacp-instance-table tr#column-quick_links {
        width: 20%;
    }

    /* listing */
    .wrap.wfop_global {
        margin-top: 30px;
    }

    .wfop_page_heading {
        font-size: 36px;
        font-weight: 500;
        margin: 25px 0;
    }

    .wfop_page_heading span {
        color: #6dbe45;
    }

    .wfop_page_heading i {
        font-size: 46px;
        width: auto;
        height: auto;
        padding-right: 8px;
        position: relative;
        top: -12px;
    }


    .wfop_head_bar .wfop_bar_head {
        font-size: 26px;
        line-height: 1;
        display: inline-block;
        color: #383737;
        padding-right: 5px;
    }

    .wfop_global .button-green {
        background: #6dbe45;
        border-width: 0;
        /*    box-shadow: 0 0 0 1px #5d9940;*/
        color: #fff;
        text-decoration: none;

        border-radius: 0;
    }

    .wfop_global .wfop_btn_setting {
        margin-left: 5px;
    }

    .wfop_global .button-green.hover, .wfop_global .button-green:hover, .wfop_global .button-green.focus, .wfop_global .button-green:focus {
        background: #5d9940;
        border-color: #5d9940;
        color: #fff;
    }

    /* Form: Offer settings */
    .wfop_forms_offer_settings {
        margin-top: 12px;
    }

    .wfop_forms_offer_settings .form-group {
        margin-bottom: 12px;
        position: relative;
    }

    .wfop_forms_offer_settings .form-group.field-label {
        padding-top: 8px;
    }

    .wfop_forms_offer_settings .form-group.field-label label {
        font-size: 14px;
        font-weight: 500;
    }

    .wfop_forms_offer_settings .form-group.field-checkbox label {
        padding-left: 25px;
    }

    .wfop_forms_offer_settings .form-group.field-checkbox .field-wrap {
        float: left;
        position: absolute;
        top: 2px;
    }

    .wfop_forms_offer_settings .form-group.wfop_inner_level_1 {
        padding-left: 25px;
    }

    .wfop_forms_offer_settings .form-group:before, .wfop_forms_offer_settings .form-group:after {
        display: table;
        content: '';
    }

    .wfop_forms_offer_settings .form-group:after {
        display: table;
        content: '';
        clear: both;
    }

    .wfop_forms_offer_settings fieldset {
        position: relative;
    }

    .wfop_forms_offer_settings .form-group.field-label {
        position: absolute;
        left: 0;
        z-index: 1;
        padding: 0;
        font-style: italic;
    }

    .wfop_forms_offer_settings .form-group {
        padding-left: 150px;
        margin-bottom: 15px;
    }

    .wfop_wrap_inner_design .wfop_wrap_r.wfop_no_offer_wrap_r {
        background-color: #fff;
        border: 1px solid var(--wffn-border-color, #dedfea);
    }

    .iziModal-content .sections {
        padding: 0px 24px 24px;
    }

    .wfop_izimodal_default .sections {
        min-height: 140px;
    }

    .iziModal .iziModal-header.iziModal-noSubtitle {
        padding: 24px 24px 16px;
        box-sizing: border-box;
        height: auto;
        border-bottom: 0px solid #ddd;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        animation: unset !important;
        background: #fff !important;
    }
    #modal-global-settings_success.iziModal .iziModal-header.iziModal-noSubtitle {
        padding: 14px 34px 16px 24px !important;
        display: block;
    }

    .iziModal .iziModal-header.iziModal-noSubtitle .iziModal-header-buttons {
        right: 14px;
    }

    /* Toggle */
    .wfacp-tgl {
        display: none !important;
    }

    .wfacp-tgl, .wfacp-tgl:after, .wfacp-tgl:before, .wfacp-tgl *, .wfacp-tgl *:after, .wfacp-tgl *:before, .wfacp-tgl + .wfacp-tgl-btn {
        box-sizing: border-box;
    }

    .wfacp-tgl::-moz-selection, .wfacp-tgl:after::-moz-selection, .wfacp-tgl:before::-moz-selection, .wfacp-tgl *::-moz-selection, .wfacp-tgl *:after::-moz-selection, .wfacp-tgl *:before::-moz-selection, .wfacp-tgl + .wfacp-tgl-btn::-moz-selection {
        background: none;
    }

    .wfacp-tgl::selection, .wfacp-tgl:after::selection, .wfacp-tgl:before::selection, .wfacp-tgl *::selection, .wfacp-tgl *:after::selection, .wfacp-tgl *:before::selection, .wfacp-tgl + .wfacp-tgl-btn::selection {
        background: none;
    }

    .wfacp-tgl + .wfacp-tgl-btn {
        outline: 0;
        display: block;
        width: 4em;
        height: 2em;
        position: relative;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .wfacp-tgl + .wfacp-tgl-btn-small {
        width: 1.7em;
        height: 1em;
    }

    .wfop_fixed_header .wfacp-tgl + .wfacp-tgl-btn-small {
        width: 36px;
        height: 1em;
    }

    .wfacp-tgl + .wfacp-tgl-btn:after, .wfacp-tgl + .wfacp-tgl-btn:before {
        position: relative;
        display: block;
        content: "";
        width: 50%;
        height: 100%;
    }

    .wfacp-tgl + .wfacp-tgl-btn:after {
        left: 0;
    }

    .wfacp-tgl + .wfacp-tgl-btn:before {
        display: none;
    }

    .wfacp-tgl:checked + .wfacp-tgl-btn:after {
        left: 50%;
    }

    .wfacp-tgl-ios + .wfacp-tgl-btn {
        background-color: #fc7626;
        border-radius: 2em;
        padding: 2px;
        transition: all .4s ease;
        border: 1px solid #e8eae9;
    }

    .wfacp-tgl-ios + .wfacp-tgl-btn:after {
        border-radius: 2em;
        background: #fbfbfb;
        transition: left 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), padding 0.3s ease, margin 0.3s ease;
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1), 0 4px 0 rgba(0, 0, 0, 0.08);
    }

    .wfacp-tgl-ios + .wfacp-tgl-btn:hover:after {
        will-change: padding;
    }

    .wfacp-tgl-ios + .wfacp-tgl-btn:publish {
        box-shadow: inset 0 0 0 2em #e8eae9;
    }

    .wfacp-tgl-ios + .wfacp-tgl-btn:publish:after {
        padding-right: .8em;
    }

    .wfacp-tgl-ios:checked + .wfacp-tgl-btn {
        background: #6dbe45;
    }

    .wfacp-tgl-ios:checked + .wfacp-tgl-btn:publish {
        box-shadow: none;
    }

    .wfacp-tgl-ios:checked + .wfacp-tgl-btn:publish:after {
        margin-left: -.8em;
    }

    .wfacp-loader {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(255, 255, 255, 1);
        text-align: center;
        z-index: 9;
    }

    .swal2-popup .swal2-title {
        line-height: normal;
    }

    .wfacp-loader img {
        max-width: 30px;
        padding-top: 250px;
    }

    .postbox.wfop_woofunnels_finale {
        background: #fd7e21;
    }

    /* GLOBAL FORM SETTINGS */
    .wfop_global .wfop_forms_global_settings .form-group {
        margin-bottom: 10px;
    }

    .wfop_forms_global_settings {
        background: #ffffff;
        padding: 0px 15px 0px;
    }

    .wfop_forms_global_settings .form-group {
        padding: 1em;
    }

    .wfop_forms_global_settings .form-group:before {
        display: table;
        content: '';
    }

    .wfop_forms_global_settings .form-group:after {
        display: table;
        content: '';
        clear: both;
    }

    .wfop_forms_global_settings .form-group.wfop_gsettings_sec_head label {
        font-weight: 600;
    }

    .wfop_forms_global_settings .form-group label {
        width: 200px;
        float: left;
        padding: 6px 10px 7px 0;
        margin-bottom: 0;
        box-sizing: border-box;
        font-size: 14px;
        line-height: 1.5;
        font-weight: 400;

    }

    .wfop_forms_global_settings .bwf_form_submit {
        padding-left: 1em;
    }

    .wfop_forms_global_settings .radio-list label {
        width: auto;

    }

    .wfop_forms_global_settings .form-group .wp-picker-container label {
        width: initial !important;
        float: none;;
    }

    .wfop_forms_global_settings .field-wrap label {
        font-weight: 400;
    }

    .wfop_forms_global_settings .vue-form-generator .field-wrap {
        padding: 0;
        margin-left: 0;
        padding-left: 201px;
        display: block;
    }

    .wfop_global .wfop_forms_global_settings .form-group.wfop_gsettings_sec_note {
        padding: 0 1em;
    }

    .wfop_global .wfop_forms_global_settings .form-group.wfop_gsettings_sec_no_gateways .hint {
        clear: initial;
    }

    .wfop_forms_global_settings .form-group.field-input input[type="text"], .wfop_forms_global_settings .form-group.field-input input[type="email"], .wfop_forms_global_settings .form-group.field-input input[type="tel"] {
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 0;
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
    }

    .wfop_global .wfop_forms_global_settings .wp-color-picker {
        width: 65px;
        font-size: 12px;
        line-height: 16px;
        vertical-align: top;
        margin: 0;
    }

    .wfop_global .wfop_forms_wrap .form-group input[type="text"], .wfop_global .wfop_forms_wrap .form-group textarea {
        padding: 5px;
        border: 1px solid #ddd;
        -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        -ms-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        background-color: #fff;
        color: #32373c;
        outline: 0;
    }

    .wfop_forms_global_settings .form-group.field-textArea textarea {
        width: 100%;
        height: 150px;
    }

    .wfop_forms_global_settings .wfop_form_button {
        padding: 1em;
    }

    .wfop_forms_global_settings .form-group .help {
        margin-top: 3px;
    }

    .wfop_forms_global_settings .form-group .help .helpText {
        font-weight: 400;
        font-size: 11px;
        line-height: 18px;
    }

    .wfop_forms_global_settings .form-group .hint, .wfop_funnel_setting .form-group .hint {
        margin-left: 0;
        padding-left: 201px;
        font-size: 11px;
        line-height: 19px;
        font-style: normal;
        font-weight: 400;
        display: block;
        clear: both;
    }

    .wfop_global_settings .wfop_global_settings_wrap .wfop_page_left_wrap {
        background-color: transparent;
    }

    .wfop_global .wfop_forms_global_settings .wfop_gsettings_sec_chlist .listbox {
        margin-bottom: 12px
    }

    .wfop_global .wfop_forms_global_settings .wfop_gsettings_sec_chlist .list-row {
        margin-bottom: 5px;
    }

    /* FUNNEL SETTINGS */

    .wfop_wrap_inner_settings {
        margin-left: 0;
        background-color: #fff;
        border: 1px solid var(--wffn-border-color, #dedfea);
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
    }

    .wfop_forms_funnel_settings .form-group {
    }

    .wfop_forms_funnel_settings .form-group:before {
        display: table;
        content: '';
    }

    .wfop_forms_funnel_settings .form-group:after {
        display: table;
        content: '';
        clear: both;
    }

    .wfop_forms_funnel_settings label {
        width: 200px;
        float: left;
        padding: 0 1% 0 0;
        box-sizing: border-box;
        font-size: 14px;
        line-height: 1.3;
        font-weight: 400;

    }

    .wfop_forms_funnel_settings .radio-list label {
        width: auto;

    }

    .wfop_forms_funnel_settings .field-wrap label {
        font-weight: 400;
    }

    .wfop_forms_funnel_settings .field-wrap {
        padding: 0;
        margin-left: 0;
        padding-left: 210px;
    }

    .wfop_forms_funnel_settings .form-group input:not(.wp-color-picker), .wfop_forms_funnel_settings .form-group textarea {
        padding: 5px;
        border: 1px solid #ddd;
        -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        -ms-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        background-color: #fff;
        color: #32373c;
        outline: 0;
        -webkit-transition: 50ms border-color ease-in-out;
        -moz-transition: 50ms border-color ease-in-out;
        -ms-transition: 50ms border-color ease-in-out;
        transition: 50ms border-color ease-in-out;
    }

    .wfop_forms_funnel_settings .form-group.field-textArea textarea {
        width: 25em;
    }

    .wfop_forms_funnel_settings .form-group.field-input input {
        width: 25em;
    }

    .wfop_forms_funnel_settings .form-group {
        margin-bottom: 30px;
    }

    .wfop_forms_funnel_settings {
        padding: 30px 25px;
    }

    .wfop_forms_funnel_settings .form-group.wfop_hr_gap {
        border-bottom: 1px solid #ddd;
        padding-bottom: 30px;
    }

    .wfop_forms_funnel_settings .form-group.wfop_setting_label_head label {
        display: block;
        font-weight: 700;
        font-size: 13px;
        line-height: 1.4em;
        margin: 0 0 3px;
    }

    .wfop_forms_funnel_settings .form-group.field-label {
        margin-bottom: 15px;
    }

    .wfop_wrap_inner_settings .wfop_btm_grey_area {
        background-color: var(--wffn-tertiary, #dedfea);
        padding: 15px 25px;
        text-align: right;
        border-top: 1px solid var(--wffn-border-color, #dedfea);
    }

    /*FUNNEL RULES SETTINGS*/
    .wfop_wrap_inner_rules {
        margin-left: 0;

    }

    .wfop_wrap_inner_rules #wfop_funnel_rule_settings[data-is_rules_saved="yes"] {
        background-color: #fff;
        border: 1px solid var(--wffn-border-color, #dedfea);
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
    }

    .wfop_wrap_inner_rules hr {
        display: none;
    }

    .wfop_wrap_inner_rules .wfop_rules_form .wfacp-rules-builder {
        padding: 25px 25px 30px;
        border-bottom: 1px solid var(--wffn-border-color, #dedfea);

    }

    .wfop_wrap_inner_rules .wfop_btm_grey_area {
        background-color: var(--wffn-tertiary, #EBF2F6);
        padding: 10px 25px;
        text-align: right;
        border-top: 1px solid var(--wffn-border-color, #dedfea);
    }

    .wfacp-rules-builder .wfacp-rules td.condition tbody > tr td {
        /*    padding-top: 0;  */
        padding-bottom: 0;
    }

    .wfacp-rules-builder .wfacp-rules td.condition {
        /*    padding: 10px 4px;*/
    }

    .wfop_input_field_btn_holder {
        display: inline-block;
        width: 48%;
        margin: 0 4% 13px 0;
        text-transform: capitalize;
        vertical-align: top
    }

    .wfop_input_field_btn_holder:nth-child(2n) {
        margin-right: 0;
    }

    .wfop_input_field_btn_holder .dashicons, .wfop_input_field_btn_holder .dashicons-before:before {
        line-height: 10px;
        vertical-align: middle;
        margin-top: 8px;
    }

    .template_steps_container .wfop_input_fields_btn {
        text-align: center;
        padding-left: 0;
        padding-bottom: 20px;
    }

    .wfop_save_btn_style, .wfop_submit_btn_style, .wfop_save_btn_style:focus, .wfop_submit_btn_style:focus {
        min-width: 84px;
        text-align: center;
        padding: 0 16px;
        border-radius: 3px;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        font-size: 14px;
        min-height: 36px;
        box-shadow: none;
        display: inline-flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--wffn-primary);
        color: #fff;
        background: var(--wffn-primary);
        text-decoration:none;
    }

    div.wfop_input_field_place_holder {
        background: #fff;
    border-color: var(--wffn-border-color);
    color: #c7c7c7;
    pointer-events: none;
    width: 100%;
    min-width: 84px;
    text-align: center;
    padding: 0 6px;
    border-radius: 6px;
    font-size: 13px;
    font-size: 0.8125rem;
    min-height: 36px;
    box-shadow: none;
    text-decoration: none;
    display: inline-flex;
    text-align: center;
    align-items: center;
    justify-content: center;
    gap: 8px;
    }

    div.wfop_input_field_place_holder span {
        display: inline-block;
        line-height: 20px;
    }

    .wfop_submit_btn_style {
        height: 30px;
        line-height: 28px;
        padding: 0 12px 2px;
    }

    .wfop_save_btn_style:focus, .wfop_save_btn_style:hover, .wfop_save_btn_style:publish, .wfop_submit_btn_style:focus, .wfop_submit_btn_style:hover, .wfop_submit_btn_style:publish {
        background: #008ec2;
        border-color: #006799;
        color: #fff;
    }

    .wfop_save_btn_style:disabled, .wfop_submit_btn_style:disabled {
        background: #0085ba;
        border-color: #0073aa #006799 #006799;
        color: #fff;
        -webkit-box-shadow: 0 1px 0 #006799;
        -moz-box-shadow: 0 1px 0 #006799;
        -ms-box-shadow: 0 1px 0 #006799;
        -o-box-shadow: 0 1px 0 #006799;
        box-shadow: 0 1px 0 #006799;
    }

    /*FUNNEL STEPS SETTINGS*/

    .wfop_wrap_inner_offers .wfop_form_remove_product:hover {
        color: #dc3232;
        border: none;
    }

    .wfop_wrap_inner_offers .wfop_form_remove_product {
        cursor: pointer;
        color: #d0cdcd;
        -webkit-appearance: none;
        -moz-appearance: none;
        -ms-appearance: none;
        appearance: none;
        background-color: transparent;
        border: none;
        transition-property: border, background, color;
        transition-duration: .05s;
        transition-timing-function: ease-in-out;
    }

    .wfop_wrap_inner_offers .wfop_btm_grey_area {
        background-color: var(--wffn-tertiary, #EBF2F6);
        padding: 12px 25px;
        text-align: right;
        border-top: 1px solid var(--wffn-border-color, #dedfea);
    }

    .wfop_wrap_inner_offers .offer_forms .wfop_fsetting_table_title {
        position: relative;
    }

    .wfop_wrap_inner_offers .wfop_fsetting_table_title .bwf_ajax_save_buttons .wfop_save_btn_style {
        display: block;
    }

    .wfop_fsetting_table_head .wfop_fsetting_table_head_in {
        position: relative;
    }

    .wfop_fsetting_table_head .wfop_fsetting_table_title {
        float: left;
    }

    .wfop_fsetting_table_head .bwf_form_submit {
        float: right;
    }

    .wfop_btm_grey_area .wfop_btm_save_wrap {
        display: inline-block;
    }

    .swal2-container {
        z-index: 20000 !important
    }

    .wfop_steps_sortable .wfop_step_container span.wfop_remove_step i {
        font-size: 13px;
        width: 15px;
        height: 15px;
        line-height: 16px;
        vertical-align: middle;
        display: block;
    }

    .wfop_steps_sortable .wfop_step_container span.wfop_remove_step {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #de6666;
        color: #fff;
        border-radius: 100%;
        display: none;

    }

    .wfop_steps_sortable .wfop_step_container a:hover span.wfop_remove_step {
        display: block;
    }

    @media (max-width: 850px) {
        .wfop_global .wfop_page_col2_wrap {
            margin: 0px;
        }

        .wfop_global .wfop_page_right_wrap {
            margin-right: 0;
            width: 100%;
            float: none;
            right: 0;
        }
    }

    @media (max-width: 850px) {
        .wfop_global .wfop_page_col2_wrap {
            margin: 0px;
        }

        .wfop_global .wfop_page_right_wrap {
            margin-right: 0;
            width: 100%;
            float: none;
        }
    }

    .wfop_funnel_setting .wfop_fsetting_table_head {
        position: relative;
    }

    #wfop_funnel_rule_add_settings[data-is_rules_saved="no"] {
        display: block;
        /* Attribute has this exact value */
    }

    #wfop_funnel_rule_add_settings[data-is_rules_saved="yes"] {
        display: none;
        /* Attribute has this exact value */
    }

    .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-product-tabs {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
    }

    .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-product-tabs-content-wrapper {
        -webkit-box-flex: 1;
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        border: none;
    }

    .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-product-tabs-wrapper {
        width: 250px;
        background-color: #EBF2F6;
        float: left;
    }

    .wfacp-product-tabs-view-vertical .wfacp-product-widget-container {
        width: calc(100% - 260px);
    background-color: #fff;
    float: left;
    border-radius: 8px;
    border: 1px solid var(--wfacp-border-color);

    }


    .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-tab-desktop-title.wfacp-publish {
        border-right-style: none;
    }

    .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-tab-desktop-title.wfacp-publish:before, .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-tab-desktop-title.wfacp-publish:after {
        height: 999em;
        width: 0;
        right: 0;
        border-right-style: solid;
    }

    .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-tab-desktop-title.wfacp-publish:before {
        top: 0;
        -webkit-transform: translateY(-100%);
        -ms-transform: translateY(-100%);
        transform: translateY(-100%);
    }

    .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-tab-desktop-title.wfacp-publish:after {
        top: 100%;
    }

    .wfacp-product-widget-tabs .wfacp-product-tabs-wrapper {
        font-size: 0;
    }

    .wfacp-product-widget-tabs .wfacp-tab-title,
    .wfacp-product-widget-tabs .wfacp-tab-title:before,
    .wfacp-product-widget-tabs .wfacp-tab-title:after,
    .wfacp-product-widget-tabs .wfacp-tab-content,
    .wfacp-product-widget-tabs .wfacp-product-tabs-content-wrapper {
        border: 1px none #eeeded;
    }

    .wfacp-product-widget-tabs .wfacp-product-tabs {
        text-align: left;
    }

    .wfacp-product-widget-tabs .wfacp-tab-title.wfacp-publish {
        color: #00a0d2;
    }

    .wfacp-product-widget-tabs .wfacp-tab-title {
        cursor: pointer;
        outline: none;
        font-size: 14px;
        line-height: 22px;
        color: #000;
        font-weight: 400;
        position: relative;
    }

    .wfacp-product-widget-tabs .wfacp-tab-desktop-title {
        position: relative;
        padding: 18px 18px 17px;
        display: block;
        line-height: 1.4;
    }

    .wfacp-product-widget-tabs .wfacp-tab-title.wfacp-active {
        color: #000;
        background: #fff;
        border: none;
        margin: 0;
        margin-right: -1px;

    }


    .wfacp-product-widget-tabs .wfacp-tab-desktop-title.wfacp-publish {
        border-color: #D4D4D4;
        border: none;
        border-left: solid;
        background-color: #fff;
    }

    .wfacp-product-widget-tabs .wfacp-tab-desktop-title.wfacp-publish:before, .wfacp-product-widget-tabs .wfacp-tab-desktop-title.wfacp-publish:after {
        display: block;
        content: '';
        position: absolute;
    }

    .wfacp-product-widget-tabs .wfacp-tab-mobile-title {
        padding: 10px 10px;
        cursor: pointer;
    }

    .wfacp-product-widget-tabs .wfacp-tab-content {
        padding: 20px;
        display: none;
    }

    .wfop_forms_global_settings .vue-form-generator fieldset {
        display: none;
    }

    .wfop_forms_global_settings .vue-form-generator fieldset legend {
        font-size: 20px;
        font-weight: bold;
        padding-bottom: 15px;
        padding-left: 10px;
    }

    .wfop_global_settings_wrap {
        margin-top: 50px;
    }

    .wfop_global_settings * {
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -o-box-sizing: border-box;
    }

    .wfop_global_settings_wrap .wfacp-product-tabs-view-vertical {
        margin-bottom: 20px;
        float: left;
        background: #EBF2F6;
        width: 100%;
    }

    /* */
    .product_section_table table.variation_products {
        border: none;
        border: 1px solid #f0f0f0;
        border-spacing: 0;
        border-collapse: collapse;
        margin: 15px;
        -moz-width: calc(100% - 30px);
        -webkit-width: calc(100% - 30px);
        -ms-width: calc(100% - 30px);
        -o-width: calc(100% - 30px);
        width: calc(100% - 30px);
    }

    .product_section_table table.variation_products thead {
        text-transform: capitalize;
    }

    .product_section_table table.variation_products tr th {
        border-width: 0px;
        font-weight: 400;
        padding: 20px 10px;
        font-size: 14px;
        line-height: 1.5;
    }

    .product_section_table table.variation_products tr td {
        border-width: 0px;
        text-align: center;
        font-size: 13px;
        line-height: 1.5;
        padding: 15px 10px;
    }

    .product_section_table table.variation_products tr td p {
        font-size: 13px;
        line-height: 1.5;
        margin: 0 0 5px;
    }

    .product_section_table table.variation_products tbody tr {
        border-top: 1px solid #eee;
    }

    .product_section_table table.variation_products tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .product_section_table table.variation_products tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    .product_section_table table.variation_products tbody > tr {
        width: 100%;
        table-layout: fixed;
        display: table;
    }

    .product_section_table table.variation_products thead {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .product_section_table table.variation_products tbody {
        max-height: 450px;
        overflow: auto;
        display: block;
    }

    /*.product_section_table table.variation_products thead{display: block; }
.product_section_table table.variation_products thead tr{
display: block;
position: relative;
}
.product_section_table table.variation_products tbody {
display: block;
overflow: auto;
width: 100%;
height: 200px;
}*/

    .product_section_table table.variation_products tr th:nth-child(1), .product_section_table table.variation_products tr td:nth-child(1) {
        width: 6%;
        padding: 20px 0px;
    }

    .product_section_table table.variation_products tr th:nth-child(2), .product_section_table table.variation_products tr td:nth-child(2) {
        width: 10%;
    }

    .product_section_table table.variation_products tr th:nth-child(3), .product_section_table table.variation_products tr td:nth-child(3) {
        width: 35%;
        padding-left: 30px;
        padding-right: 30px;
        text-align: left;
    }

    .product_section_table table.variation_products tr th:nth-child(4), .product_section_table table.variation_products tr td:nth-child(4) {
        width: 35%;
        text-align: left;
    }

    .product_section_table table.variation_products tr th:nth-child(5), .product_section_table table.variation_products tr td:nth-child(5) {
        width: 13%;
    }

    .product_section_table table.variation_products input.variation_discount {
        width: 70px;
        text-align: center;
        padding: 10px;
    }

    .product_section_table .product_name {
        font-size: 15px;
        line-height: 1.5;
        margin-bottom: 7px;
        /*border: 1px solid #ddd;*/
        /*box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);*/
        background-color: transparent;
        /*padding: 5px;*/
        position: relative;
    }

    span.dashicons.ok_btn_setting.wfop_save_title {
        position: absolute;
        top: 0;
        right: -5px;
        bottom: 0;
        margin: 0;
        background: #f7f6f6;
        width: 40px;
        color: #747272;
        border: 1px solid #ededed;
        height: 100%;

        font-family: unset;
    }

    span.dashicons.ok_btn_setting.wfop_save_title:before {
        content: 'OK';
        font-size: 13px;
        line-height: 13px;
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        margin-top: -8px;
        font-family: unset;
    }

    span.dashicons.ok_btn_setting.wfop_save_title:hover {
        background: #eaeaea;
    }

    .product_section_table .product_options {
        font-size: 13px;
        line-height: 1.5;
    }

    .product_section_table .product_options p {
        margin: 0 0 5px;
        display: inline;
    }

    .product_section_table .product_image {
        width: 90px;
        float: left;
        padding-right: 15px;
    }

    .product_section_table .product_details {
        padding-left: 90px;
        display: block;
    }

    .product_details {
        text-align: left;
    }

    .wfacp-product-row .discount_number {
        display: inline-block;
    }

    .have_variation {
        padding: 0 15px;
        font-size: 14px;
        /*text-decoration: underline;*/
        /* font-weight: 500; */
    }

    .product_section_table .product_image img {
        border: 1px solid #ededed;
        min-height: 75px;
        width: 100%;
        border-radius: 6px;
    }

    .product_section_table {
        border-spacing: 0;
        border-top: 0;
    }

    .product_section_table tbody tr:not(:nth-child(1)) .listing_table_head {
        display: none;
    }

    .product_section_table input[type="text"], .product_section_table select {
        height: 35px;
        margin: 3px 0;
        vertical-align: middle;
    }

    .product_section_table input[type="text"] {
        text-align: center;
    }

    .product_section_table select {
        /*width: 198px*/
    }

    .product_section_table .tb_body tr.product_tb_row > td {
        border-bottom: 1px solid #ededed;
    }

    .product_section_table .tb_body tr.product_tb_row:last-child > td {
        border-bottom: none;
    }

    .product_list input[type="hidden"] {
        margin: 0;
        padding: 0;
    }

    .product_section_table tbody tr > td {
        text-align: center;
        padding: 15px;
        background: #fff;
        border-top: 1px solid var(--wfacp-border-color)
    }

    .product_section_table table.main_products {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .product_section_table table.main_products tr td {
        text-align: center;
        padding: 15px 15px;
    }

    .product_section_table table.main_products tr td:nth-child(1) {
        width: 35%;
    }

    .product_section_table table.main_products tr td:nth-child(2) {
        width: 15%;

    }

    .product_section_table table.main_products tr td:nth-child(3) {
        width: 20%;
    }

    .product_section_table table.main_products tr td:nth-child(4) {
        width: 10%;
    }

    .product_section_table table.main_products tr td:nth-child(5) {
        width: 15%;
    }

    .product_section_table table.main_products tr td:nth-child(6) {
        width: 5%;
    }

    .product_section_table .listing_table_head {
        background-color: #fff;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .product_section_table .listing_table_head tr th {
        text-align: center;
    }

    .product_section_table .listing_table_head tr th {
        border-width: 0px;
        font-weight: 500;
        font-size: 13px;
        line-height: 16px;
        padding: 14px 16px;
        color: var(--wffn-text);
        background: var(--wffn-tertiary);
    }


    .wfop_product_table_sortable tr.ui-sortable-helper:hover > td {
        border-top: 2px dashed #0085BA;
        border-bottom: 2px dashed #0085BA;

    }

    .wfop_product_table_sortable tr.ui-sortable-helper:hover > td:first-child {
        border-left: 2px dashed #0085BA;
    }

    .wfop_product_table_sortable tr.ui-sortable-helper:hover > td:last-child {
        border-right: 2px dashed #0085BA;
    }

    .product_section_table tbody tr > td {
        height: 120px;
    }

    .product_section_table td.wfop_product_drag_handle .wfop_drag_handle {
        color: #d0cdcd;
    }

    .product_section_table td.wfop_product_drag_handle .wfop_drag_handle:hover {
        color: #444;
    }

    .product_section_table .listing_table_head tr th:nth-child(1),
    .product_section_table .tb_body tr td:nth-child(1) {
        width: 5%;
    }

    .product_section_table .listing_table_head tr th:nth-child(2),
    .product_section_table .tb_body tr td:nth-child(2) {
        width: 20%;
        /* width: 345px; */
    }

    .product_section_table .listing_table_head tr th:nth-child(3),
    .product_section_table .tb_body tr td:nth-child(3) {
        width: 30%;
        /* width: 276px; */
    }

    .product_section_table .listing_table_head tr th:nth-child(4),
    .product_section_table .tb_body tr td:nth-child(4) {
        width: 25%;
        /* width: 375px; */
    }

    .product_section_table .listing_table_head tr th:nth-child(5),
    .product_section_table .tb_body tr td:nth-child(5) {
        width: 10%;
        /* width: 207px; */
    }

    td.wfacp_product_input_type input[type="number"] {
        width: 60px;
        text-align: center;
    }

    .product_section_table .listing_table_head tr th:nth-child(6),
    .product_section_table .tb_body tr td:nth-child(6) {
        width: 10%;
        /* width: 138px; */
    }


    table.wfop_product_tab_wrap {
        table-layout: fixed;
        width: 100%;
    }


    /*.listing_table_head tr th:nth-child(8) { width: 10%;}*/
    .wfop_product_list_wrap {
        padding: 40px 25px;
        background: #fff;
    }

    .wfop_product_list_wrap h2 {
        margin: 0 0 1em;
    }

    .wfop_forms_offer_settings {
        padding: 0 15px;
    }

    /* DESIGN SCREEN: Shortcode Section Styling*/
    .wfop_wrap_r .product_settings {
        padding: 25px;
    }

    .wfop_wrap_r .product_settings:before, .product_settings:after {
        content: "";
        display: table;
    }

    .wfop_wrap_r .product_settings:after {
        clear: both;
    }

    .wfop_wrap_r .product_settings .product_settings_title {
        font-size: 16px;
        line-height: 27px;
        border-bottom: 1px solid #dedede;
        padding-bottom: 7px;
        margin-bottom: 23px;
        font-weight: bold;
    }

    .wfop_wrap_r .product_settings_checkout_behavior_heading {
        float: left;
        width: 9%;
        display: block;
        margin-right: 3%;
    }

    .wfop_wrap_r .product_settings_checkout_behavior_setting {
        width: 88%;
        float: left;
    }

    .product_settings_checkout_behavior_setting p {
        margin-top: 0;
    }

    .wfop_wrap_inner * {
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -o-box-sizing: border-box;
    }

    .wfop_wrap_inner_design .wfop_wrap_r {
        background-color: transparent;
        border: none;
    }

    .wfop_wrap_inner_design .wfop_template {
        background-color: #fff;
        border: 1px solid var(--wffn-border-color, #dedfea);
        margin-bottom: 30px;

    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap {
        background-color: #fff;
        border: 1px solid var(--wffn-border-color, #dedfea);
        margin-bottom: 30px;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-head {
        border-bottom: 1px solid #dedede;
        padding: 12px 25px;
        background-color: var(--wffn-tertiary, #EBF2F6);
        font-size: 16px;
        line-height: 24px;
        color: #454545;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-inner-wrap {
        padding: 20px 25px;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-list-wrap {
        background-color: #fff;
        border: 1px solid var(--wffn-border-color, #dedfea);
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scode-product-head {
        border-bottom: 1px solid #dedede;
        padding: 8px 25px;
        background-color: var(--wffn-tertiary, #EBF2F6);
        font-size: 15px;
        line-height: 24px;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-products {
        padding: 20px 25px;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-row {
        display: table;
        width: 100%;
        height: 100%;
        margin-bottom: 10px;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-row .wfacp-scodes-label {
        display: table-cell;
        vertical-align: middle;
        width: 30%;
        font-size: 13px;
        color: #454545
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-row .wfacp-scodes-value {
        display: table-cell;
        vertical-align: middle;
        width: 70%;
        border: 1px solid #efefef;
        padding: 8px 20px;
        color: #454545
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-value input {
        width: 100%;
        border: none;
        height: 100%;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        -ms-box-shadow: none;
        -o-box-shadow: none;
        box-shadow: none;
        padding: 0;
        margin: 0;
        background-color: #fff;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-row .wfacp-scode-text {
        display: block;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfacp-scodes-value-in {
        position: relative;
        padding-right: 45px;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfop_copy_text {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        color: #0073aa;
        font-size: 13px;
    }

    .wfop_wrap_inner_design .wfacp-scodes-wrap .wfop_copy_text:hover {
        color: #00a0d2;
    }

    /*Welcome text area*/
    .wfop_wrap_inner_rules .wfop_welc_btn {
        font-size: 16px;
        line-height: 24px;
        -webkit-appearance: none;
        padding: 15px 50px;
        display: inline-block;
        height: auto;
        border: none;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        -ms-border-radius: 0;
        -o-border-radius: 0;
        text-shadow: none;
        border-radius: 0;
        background-color: #0073aa;

    }

    .wfop_welcome_wrap {
        text-align: center;
        width: 100%;
    }

    .wfop_welcome_wrap .wfop_welc_text {
        max-width: 585px;
        margin: 0 auto 35px;
    }

    .wfop_welcome_wrap .wfop_welc_text p {
        font-size: 16px;
        line-height: 24px;
        color: #8a8a8a;
        margin: 0 0 15px;
    }

    .wfop_welcome_wrap .wfop_welc_head {
        font-size: 26px;
        line-height: 34px;
        color: #000000;
        margin: 0 0 10px;
    }

    .wfop_welcome_wrap .wfop_welc_icon {
        display: inline-block;
        vertical-align: middle;
    }

    .wfop_welcome_wrap .wfop_welc_title {
        display: inline-block;
        vertical-align: middle;
    }

    #wfop_funnel_rule_add_settings {
        position: relative;
    }

    .wfop_welcome_wrap {
        background-color: #fff;
        padding: 80px 0;
    }

    .wfop_welcome_wrap .wfop_welc_btn {
        font-size: 16px;
        line-height: 24px;
        -webkit-appearance: none;
        padding: 15px 50px;
        display: inline-block;
        height: auto;
        border: none;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        -ms-border-radius: 0;
        -o-border-radius: 0;
        text-shadow: none;
        border-radius: 0;
        background: #0073aa !important;
        width: auto;
        color: #fff !important;
    }

    .wfop_welcome_wrap .wfop_welc_btn:focus, .wfop_welcome_wrap .wfop_welc_btn:hover {
        background: #008ec2;
        border-color: #006799;
        color: #fff;
    }

    .wfop_save_btn_style.wfop_left {
        background: red;

        border-color: #FF250F #ff1a1e #ff0000;
    }

    a.wfop_del_offer_style {
        color: #a00;
        text-decoration: underline;
        line-height: 28px;
    }

    a.wfop_del_offer_style:hover, a.wfop_del_offer_style:focus, a.wfop_del_offer_style:publish {
        color: #dc3232;
        border: none;
        outline: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        -ms-box-shadow: none;
        -o-box-shadow: none;
        box-shadow: none;
    }

    .wfop_left {
        float: left;
    }

    .wfop_step_container {
        cursor: pointer;
    }

    /* RULES TABLE*/

    #wfacp-rules-groups .wfacp-rule-group-container table.wfacp-rules {
        background-color: #f9f9f9;
    }

    #wfacp-rules-groups .wfacp-rule-group-container table {
        border: 1px solid #ddd;
        padding: 20px 10px;
    }

    .wfacp-rules-builder .wfacp-rules > tbody > tr td {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .wfacp-rules-builder .wfacp-rules td.condition tbody > tr td {
        padding-top: 0;
        padding-bottom: 0;
    }

    #wfacp-rules-groups .wfacp-rule-group-container .condition table {
        border: 1px solid #eee;
        border: none;
        padding: 0 10px;
    }

    .wfacp-rules-builder .wfacp-rules td.operator {
        width: 15%;
    }

    #wfacp-rules-groups .wfacp-rule-group-container {
        margin: 0;
        padding-bottom: 10px;
    }

    #wfacp-rules-groups .wfacp-rule-group-container h4.rules_or {
        font-size: 14px;
        margin-top: 0;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .wfacp-rules-builder .wfacp-rules td.rule-type {
        vertical-align: bottom;
    }

    .wfacp-rules-builder .wfacp-rules td.operator {
        vertical-align: bottom;
    }

    .wfacp-rules-builder .wfacp-rules td.condition {
        vertical-align: top;
    }

    .wfacp-rules-builder .wfacp-rules td.loading {
        vertical-align: bottom;
    }

    .wfacp-rules-builder .wfacp-rules td.add {
        vertical-align: bottom;
    }

    .wfacp-rules-builder .wfacp-rules td.remove {
        vertical-align: bottom;
    }

    .wfop_forms_global_settings span.help, .wfop_funnel_setting span.help {
        margin-left: .3em;
        position: relative;
    }

    .wfop_forms_global_settings span.help .icon, .wfop_funnel_setting span.help .icon {
        font-size: 16px;
        font-family: 'dashicons';
        font-style: normal;
        color: #666;
        position: absolute;
        top: 0px;
    }

    .wfop_forms_global_settings span.help .icon:before, .wfop_funnel_setting span.help .icon:before {
        content: "\f223";
    }

    .wfop_forms_global_settings span.help .helpText, .wfop_funnel_setting span.help .helpText {
        background-color: #444;
        bottom: 30px;
        color: #fff;
        display: block;
        left: 0;
        opacity: 0;
        padding: 10px 15px;
        pointer-events: none;
        position: absolute;
        text-align: justify;
        width: 400px;
        -webkit-transition: all .25s ease-out;
        -moz-transition: all .25s ease-out;
        -ms-transition: all .25s ease-out;
        -o-transition: all .25s ease-out;
        transition: all .25s ease-out;
        -webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        -ms-border-radius: 6px;
        -o-border-radius: 6px;
        border-radius: 6px;
        font-size: 11px;
        line-height: 18px;
    }

    .wfop_forms_global_settings span.help .helpText a, .wfop_funnel_setting span.help .helpText a {
        font-weight: 700;
        text-decoration: underline
    }

    .wfop_forms_global_settings span.help .helpText:before, .wfop_funnel_setting span.help .helpText:before {
        bottom: -20px;
        content: " ";
        display: block;
        height: 20px;
        left: 0;
        position: absolute;
        width: 100%
    }

    .wfop_forms_global_settings span.help:hover .helpText, .wfop_funnel_setting span.help:hover .helpText {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0)
    }

    #wfop_funnel_rule_settings .chosen-container .chosen-results {
        clear: both;
    }

    /* Modal Update Offer : Offer type radio field button styling*/
    #modal-update-offer .radio-list .radio-li input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    #modal-update-offer .radio-list .radio-li input[type="radio"]:checked + label {
        background: #9a929e;
    }

    #modal-update-offer .radio-list .radio-li {
        float: left;
        margin-right: 15px;
    }

    #modal-update-offer .radio-list .radio-li input[type="radio"] + label {
        background: #ccc;
        color: #fff;
        padding: 10px 25px;
    }

    #modal-update-offer input#funnel-step-slug {
        display: inline-block;
        max-width: 250px;
    }

    /*SWAL STYLING */
    .swal2-container .swal2-popup .swal2-styled:focus {
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        -ms-box-shadow: none;
        -o-box-shadow: none;
        box-shadow: none;

    }

    .swal2-container .swal2-popup .swal2-styled.swal2-confirm:focus, .swal2-container .swal2-popup .swal2-styled.swal2-confirm:hover, .swal2-container .swal2-popup .swal2-styled.swal2-confirm:publish {
        color: #fff;
    }

    .swal2-container .swal2-popup .swal2-styled {
        font-size: 18px;
        line-height: 26px;
        font-weight: 400;
        -moz-border-radius: 0;
        -ms-border-radius: 0;
        -webkit-border-radius: 0;
        -o-border-radius: 0;
        border-radius: 0;
    }

    .swal2-container .swal2-popup .swal2-styled.swal2-confirm, .swal2-container .swal2-popup .swal2-styled.swal2-cancel {
        /* min-width: 84px; */
        text-align: center;
        padding: 0 16px;
        font-weight: 500;
        border-radius: 3px;
        font-size: 14px;
        min-height: 36px;
        box-shadow: none;
        text-decoration: none;
        display:inline-flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--wffn-primary);
        margin: 0;
    }
    body .swal2-popup .swal2-styled.swal2-confirm {
        background-color: var(--wffn-primary);
    }
    body .swal2-popup .swal2-styled.swal2-confirm[disabled], body .swal2-popup .swal2-styled.swal2-confirm.is_busy {
        opacity: 1 !important;
        background-image: linear-gradient( -45deg,#007cba 33%,#005a87 0,#005a87 70%,#007cba 0) !important;
        animation: components-button__busy-animation 2.5s linear infinite;

    }
    @keyframes components-button__busy-animation {
        0% {
            background-position: 200px 0;
        }
    }
    .swal2-container .swal2-popup .swal2-styled.swal2-cancel, .swal2-container .swal2-popup .swal2-styled.swal2-cancel:hover {
        border: 1px solid transparent !important;
        color:  #5F6381;
    }

    .swal2-container .swal2-popup .swal2-title {
        font-size: 1.375rem;
        line-height: 35px;
        font-weight: 400;
        color: #353030;
        margin: 8px 0px;
    }

    .swal2-popup #swal2-content {
        font-size: 13px;
        font-weight: 400;
        line-height: 20px;
        color: #82838e;
    }
    .swal2-popup #swal2-content .wf_delete_modal_content {
        text-align: left;   
        /* margin-top: -6px; */
    }

    .swal2-container .swal2-popup {
        width:33em;
		padding:24px;
		border-radius:6px;
        border: 1px solid #ddd;
        min-width:540px;
        box-shadow: 0 3px 30px rgb(0 0 0 / 20%);
    }

    .aero_checkout_logo {
        max-width: 245px;
    }

    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo {
        text-align: center;
        margin-bottom: 25px;
    }

    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo .swal2-icon.swal2-success [class^='swal2-success-circular-line'][class$='left'] {
        background-color: transparent !important;
    }

    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo .swal2-icon.swal2-success [class^=swal2-success-circular-line][class$='right'] {
        background-color: transparent !important;
    }

    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo .swal2-icon.swal2-success .swal2-success-fix {
        background-color: transparent !important;
    }

    .wfacp-funnel-create-success-wrap {
        display: none;
        margin: 25px 0;
    }

    .wfacp-funnel-create-message {
        font-size: 16px;
        line-height: 24px;
        color: #666666;
        text-align: center;
    }

    .wfacp-scodes-list-wrap .wfacp-scodes-products .wfacp-scodes-row:last-child .wfacp-scodes-value-in .wfop_description {
        margin-bottom: 5px;
    }

    @media (min-width: 1400px) {
        .wfop_forms_global_settings .form-group.field-textArea textarea {
            width: 600px;
            height: 200px;
        }

        .wfop_forms_global_settings .form-group.field-input input[type="text"],
        .wfop_forms_global_settings .form-group.field-input input[type="email"],
        .wfop_forms_global_settings .form-group.field-input input[type="tel"],
        .wfop_forms_global_settings .wfop_vue_forms .form-group select {
            width: 25em;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.5;
        }

        .wfop_forms_global_settings .wfop_vue_forms .form-group select {
            padding: 9px 12px;
        }
    }

    @media (max-width: 1299px) {
        .product_section_table .product_image {
            width: 75px;
        }

        .product_section_table table.main_products tr td {
            padding: 15px 10px;
        }

        .product_section_table .listing_table_head tr th {
            padding: 10px 10px;
        }

        .product_section_table table.variation_products {
            margin: 15px 10px;
            -moz-width: calc(100% - 20px);
            -webkit-width: calc(100% - 20px);
            -ms-width: calc(100% - 20px);
            -o-width: calc(100% - 20px);
            width: calc(100% - 20px);
        }

        .product_section_table .product_details {
            padding-left: 75px;
            display: block;
        }

        .wfop_forms_offer_settings {
            padding: 0 10px;
        }
    }

    @media (max-width: 1200px) {
        .wfop_global_settings .wfop_page_col2_wrap {
            margin-right: 0;
        }

        .wfop_global_settings .wfop_page_left_wrap {
            float: none;
            margin-bottom: 30px;
        }

        .wfop_global_settings .wfop_page_right_wrap {
            float: none;
            margin-right: 0px;
        }

        .product_section_table table.variation_products tr th:nth-child(3), .product_section_table table.variation_products tr td:nth-child(3) {
            padding-left: 15px;
            padding-right: 15px;
        }

    }

    .spinner {
        float: left;
    }

    #update-nag, .update-nag {
        display: none;
    }

    .wfop_gsettings_sec_head {
    }

    .wfop_forms_global_settings .form-group.wfop_gsettings_sec_head label {

    }

    .woocommerce-help-tip::after {
        font-family: Dashicons;
        speak: none;
        font-weight: 400;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        margin: 0;
        text-indent: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        text-align: center;
        content: "ïˆ£";
        cursor: help
    }

    .wrap_t {
        position: relative;
    }

    .wfacp-help-tip::after {
        font-family: Dashicons;
        speak: none;
        font-weight: 400;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        text-indent: 0px;
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        text-align: center;
        content: "ïˆ£";
        cursor: help;
        font-variant: normal;
        margin: 0px;
    }

    .wfacp-help-tip {
        padding: 0;
        margin: -4px 0 0 5px;
        vertical-align: middle;
        cursor: help;
        line-height: 1;
        color: #666;
        display: inline-block;
        font-size: 1.1em;
        font-style: normal;
        height: 16px;
        line-height: 1;
        position: relative;
        vertical-align: middle;
        width: 16px;
        font-size: 1.1em;
    }

    #tiptip_content {
        color: #fff;
        font-size: .8em;
        max-width: 400px;
        background: #333;
        text-align: center;
        border-radius: 3px;
        padding: .618em 1em;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    }

    #tiptip_arrow, #tiptip_arrow_inner {
        position: absolute;
        border-color: transparent;
        border-style: solid;
        border-width: 6px;
        height: 0;
        width: 0;
    }

    #tiptip_holder {
        display: none;
        z-index: 8675309;
        position: absolute;
        top: 0;
        left: 0;
    }

    #tiptip_holder.tip_bottom #tiptip_arrow_inner {
        margin-top: -5px;
        margin-left: -6px;
        border-bottom-color: #333;
    }

    .wfop_global .wfop_forms_wrap .wrapper {
        float: left;
    }

    .wfop_forms_global_settings .wfop_gsettings_sec_note label {
        font-weight: 300;
        font-style: italic;
        width: 100%;
        font-style: italic;
        color: #757575;
    }

    .wfop_global .wfop_forms_global_settings .form-group.wfop_gsettings_sec_chlist .listbox, .wfop_global .wfop_forms_global_settings .form-group.wfop_gsettings_sec_chlist .listbox .list-row label {
        /*    display: inline-block;*/
    }

    .wfop_global .wfop_forms_wrap .form-group.wfop_gsettings_sec_chlist .wrapper {
        float: none;
    }

    .wfop_global .wfop_forms_global_settings .form-group.wfop_gsettings_sec_chlist .listbox .list-row label {
        width: 100%;
        float: none;
    }

    th.manage-column.column-toggle {
        width: 5%;
    }

    .wfop_funnels .wfacp-preview:not(.wfacp-preview-admin) {
        display: inline-block;
        border: 2px solid transparent;
        border-radius: 4px;
        top: 0px;
    }


    .widefat .check-column {
        width: 3%;
    }

    .wfop_page_left_wrap table.wp-list-table .column-name {
        width: 37%;
    }

    td.preview.column-preview {
        position: relative;
        width: 12%;
    }

    .wfop_page_left_wrap table.wp-list-table .column-name p {
        margin-bottom: 0;
    }

    .wfop_page_left_wrap td.last_update.column-last_update,
    .wfop_page_left_wrap #last_update {
        width: 18%;
    }

    .wfop_page_left_wrap td.quick_links.column-quick_links,
    .wfop_page_left_wrap #quick_links {
        width: 30%;
    }


    .wfop_funnels .wfacp-preview.wfacp-preview-admin:before {
        display: none;
    }

    .wfop_funnels .widefat td,
    .wfop_funnels .widefat th {
        padding: 12px 10px;
    }

    .wfop_funnels .wfacp-preview.wfacp-preview-admin {
        margin-left: 5px;
    }


    .wfop_funnels .wfacp-preview::before {
        font-family: WooCommerce;
        speak: none;
        font-weight: 400;
        text-transform: none;
        text-indent: 0px;
        position: absolute;
        left: 0px;
        width: 100%;
        height: 100%;
        text-align: center;
        content: "î€";
        line-height: 16px;
        font-size: 14px;
        vertical-align: middle;
        top: 50%;
        font-variant: normal;
        margin: 0px;
        margin-top: -8px;
    }

    .wfop_funnels .wfacp-preview .preview-edit::before {
        font-family: WooCommerce;
        speak: none;
        font-weight: 400;
        text-transform: none;
        text-indent: 0px;
        position: absolute;
        left: 0px;
        text-align: center;
        content: "î€";
        line-height: 16px;
        font-size: 14px;
        vertical-align: middle;
        top: 50%;
        font-variant: normal;
        margin-top: -8px;
    }

    .wfop_funnels .wfacp-preview.disabled::before {
        content: '';
        background: url(../img/wpspin.gif) no-repeat center top;
    }

    .show_half_link {
        display: inline-block;
    }

    .wfop_vue_forms .form-group .show_permalink input[type='text'] {
        padding: 4px 15px;
        display: inline-block;
        max-width: 250px;
        height: auto;
        margin-right: 0px;
        margin-top: -10px;
    }

    .wfacp-os {
        float: right;
        margin-right: 54px;
    }


    .wfacp-fp-wrap {
        padding: 20px 40px 0;
    }

    .wfacp-fp-wrap:last-child {
        padding-bottom: 20px;
    }

    .wfacp-fp-name, .wfacp-fp-offer-type, .wfacp-fp-offer-products {
        display: block;
        margin-bottom: 3px;
        position: relative;
    }

    .wfacp-fp-name {
        font-size: 17px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 5px;
    }

    #wc-backbone-modal-dialog .order-status {
        background: transparent;
        border-color: transparent;
        float: none;
        border-radius: 20px;
        padding: 0px 10px;
        font-size: 13px;
        line-height: 0;
        width: auto;
        line-height: 23px;
        color: #fff;
        margin-left: 6px;
    }

    #wc-backbone-modal-dialog .status-draft {
        background: red;
    }

    #wc-backbone-modal-dialog .status-publish {
        background: #78d086;
    }

    .wfacp-fp-name.wfacp-fp-state-0:after {
        background: #fc7626;
    }

    .wfacp-fp-offer-type, .wfacp-fp-offer-products {
        font-size: 13px;
    }

    /* Listing pop view */
    .wfacp-os {
        margin-right: 54px;
    }

    .wfacp-os.status-publish {
        color: #777;
    }

    .wfacp-os.status-draft {
        color: #777;
    }

    .wfacp-fp-wrap {
        padding: 20px 30px 0;
    }

    .wfacp-fp-wrap:last-child {
        padding-bottom: 20px;
    }

    .wfacp-fp-name, .wfacp-fp-offer-type, .wfacp-fp-offer-products {
        display: block;
        margin-bottom: 3px;
        position: relative;
    }

    .wfacp-fp-name {
        font-size: 15px;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
        line-height: 20px;
    }

    /* new styling */
    .preview_sec h3 {
        margin: 0 0 5px;
        font-size: 18px;
        line-height: 22px;
    }

    .product_preview .wfacp-sec {
        margin-bottom: 0px;
    }

    .product_preview .wfacp-sec:last-child {
        margin-bottom: 0;
    }

    .wfacp-fp-name.wfacp-fp-state-0:after {
        background: #fc7626;
    }

    .wfacp-fp-offer-type, .wfacp-fp-offer-products {
        font-size: 13px;
    }

    .wfacp-funnel-pop-no-offer {
        text-align: center;
    }

    .wfacp-funnel-create-success-wrap {
        display: none;
    }

    .wfacp-global-settings-help-ofc .wfop_img_preview img, .wfacp-funnel-settings-help-messages .wfop_img_preview img {
        max-width: 100%;
    }

    .wfop_offer_settings_textarea .field-wrap textarea {
        width: 100%;
    }

    .wfop_forms_funnel_settings .form-group.field-label.wfop_gsettings_sec_note label {
        width: 500px;
        font-style: italic;
        font-weight: 300;
        color: #757575;
    }

    .wfacp-global-settings-help-ofc.iziModal {
        z-index: 999999 !important;
    }

    span.wfacp-help {
        margin-left: .3em;
        position: relative;
        display: inline;

    }

    .wfop_side_content ul.wfacp-list-dec {
        padding-left: 26px;
        counter-reset: countval;
        position: relative;
    }

    .wfop_side_content ul.wfacp-list-dec li {
        margin-bottom: 10px;
        position: relative;
    }

    .wfop_side_content ul.wfacp-list-dec li::before {
        background: #f7990c;
        counter-increment: countval;
        content: counter(countval);
        position: absolute;
        width: 20px;
        height: 20px;
        left: -26px;
        top: -1px;
        border-radius: 50%;
        color: #fff;
        text-align: center;
    }

    .wfop_side_content ul.wfacp-list-dec li a {
        font-size: 14px;
    }

    .product_section_table .product_options span.wfacp-help i.icon {
        top: 50%;
        line-height: 0;
        transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
    }

    span.wfacp-help .icon {
        display: inline-block;
        font-size: 16px;
        font-family: 'dashicons';
        font-style: normal;
        color: #666;
        position: absolute;
        top: 0px;
    }

    span.wfacp-help .icon:before {
        content: "\f223";
    }

    span.wfacp-help .helpText {
        background-color: #444;
        bottom: 30px;
        color: #fff;
        display: block;
        left: 0;
        opacity: 0;
        padding: 10px 15px;
        pointer-events: none;
        position: absolute;
        width: 300px;
        transition: all .25s ease-out;
        -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        box-shadow: 2px 2px 6px rgba(0, 0, 0, .5);
        -moz-border-radius: 6px;
        -webkit-border-radius: 6px;
        -ms-border-radius: 6px;
        -o-border-radius: 6px;
        border-radius: 6px;
        font-size: 11px;
        line-height: 18px;
        z-index: 10;
    }

    span.wfacp-help .helpText a {
        font-weight: 700;
        text-decoration: underline
    }

    span.wfacp-help .helpText:before {
        bottom: -20px;
        content: " ";
        display: block;
        height: 20px;
        left: 0;
        position: absolute;
        width: 100%
    }

    span.wfacp-help:hover .helpText {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0)
    }

    .template_previewer {
        display: inline-block;
        padding: 5px;
        margin: 5px;
        border: 1px solid #ccc;
        /* max-height: 150px; */
    }

    .template_previewer[data-select='selected'] {
        border: 1px solid deepskyblue;
    }

    .wp-core-ui .wfacp-button-third {
        color: #fff;
        border-color: #fb0e0e;
        background: #fb0e0e;
        box-shadow: 0 1px 0 #fb0e0e;
        vertical-align: top;
    }

    .wfop_add_new_steps {
        width: 100%;
        background: #fff;
        padding: 27px;
        margin-bottom: 30px;
        text-align: center;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        border: 1px solid var(--wffn-border-color, #dedfea);
    }


    #footer-thankyou {
        font-style: italic;
        display: none;
    }


    .wfop_settings_sections {
        padding: 25px 40px 40px;
        background: #fff;
    }

    .wfop_settings_section {
        max-width: 400px;
    }

    .wfop_field_container {
        background: #fff;
        border: 2px dashed transparent;
        margin-bottom: 16px;
        min-height: 140px;
        position: relative
    }

    .template_field_placeholder {
        margin: 0px;
        min-height: 100px;
        padding: 0;
        position: absolute;
        right: 0;
        left: 0;
        bottom: 0;
        text-align: center;
        z-index: 1;
        top: 0;
    }

    .template_field_placeholder_tbl {
        display: table;
        width: 100%;
        height: 100px;
    }

    .template_field_placeholder_tbl_cel {
        display: table-cell;
        vertical-align: middle;
        /*padding-top: 59px;*/
        font-size: 18px;
        color: #dfdfdf;
        text-align: center;
    }

    .wfop_field_container.wfop_section_drag {
        background: #eee;
    }

    .wfop_save_btn_style.wfop_item_drag.ui-sortable-placeholder {
        display: none
    }

    .wfop_field_container_head {
        padding: 16px 24px;
        border-bottom: 1px solid var(--wffn-border-color);
        border-radius: 10px 10px 0 0;
    }

    .wfop_field_container_heading {
        display: block;
        float: left;
    }

    .wfop_field_container_heading h4 {
        margin: 0px;
        font-size: 15px;
        font-weight:500;
    }

    .wfop_field_container_heading h5 {
        margin: 0 0 2px 0;
        color: #777;
    }

    .wfop_field_container_action {
        display: inline-block;
        float: right;
    }

    .product_add_new {
        padding: 15px;
        background: #fff;
    }

    .product_add_new .wfop_steps {
        margin: 0;
    }

    .product_add_new .wfop_step {
        color: #fff !important;
        cursor: pointer;

    }

    .wfop_page_col2_wrap .subsubsub li a {
        padding: 5px;
    }

    #wfop_design_container .wfop_steps_title {
        padding-left: 26px;
        margin: 15px 0 30px 0;
        font-size: 16px;
        font-weight: 600;
    }

    #wfop_design_container .wfop_wrap_l .wfop_step.wfop_button_add {
        width: 295px;
        margin-bottom: 20px;
        padding: 15px;
        margin-left: 26px;
    }

    #wfop_design_container .wfop_wrap_l .wfop_step.wfop_button_add {
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        border: 1px solid var(--wffn-border-color, #dedfea);
    }

    .custom_design_radio {
        position: relative;
        display: block;
        width: 22px;
        height: 22px;
        float: left;
    }

    .custom_design_radio:before {
        content: "";
        height: 21px;
        width: 21px;
        background-color: #fff;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        display: block;
        border: 1px solid #a0ada3;
    }

    [data-select="selected"].wfop_step.wfop_button_add.wfop_modal_open .custom_design_radio:after {
        content: "";
        top: 50%;
        left: 50%;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #6fbe47;
        position: absolute;
        margin-left: -5px;
        margin-top: -5px;
    }

    .wfacp-fsetting-header {
        font-size: 16px;
        line-height: 27px;
        color: #454545;
        padding-right: 15px;
        font-weight: 600;
    }

    .wfacp-short-code-wrapper {
        margin-bottom: 32px;
    }

    .wfacp-scodes-inner-wrap {
        padding: 25px 40px 40px;
        background: #fff;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        border: 1px solid var(--wffn-border-color, #dedfea);
    }

    .wfacp-short-code-wrapper .wfacp-scodes-list-wrap {
        background-color: #fff;
        border: 1px solid var(--wffn-border-color, #dedfea);
    }

    .wfacp-scodes-list-wrap .wfacp-scode-product-head {
        border-bottom: 1px solid #dedede;
        padding: 8px 25px;
        background-color: var(--wffn-tertiary, #EBF2F6);
        font-size: 15px;
        line-height: 24px;
    }

    .wfacp-scodes-list-wrap .wfacp-scodes-products {
        padding: 20px 25px;
    }

    .wfacp-scodes-list-wrap .wfacp-scodes-row {
        display: table;
        width: 100%;
        height: 100%;
        margin-bottom: 10px;
    }

    .wfacp-scodes-row .wfacp-scodes-label {
        display: table-cell;
        vertical-align: middle;
        width: 100%;
        font-size: 13px;
        color: #454545;
    }

    .wfacp-scodes-row .wfacp-scodes-value {
        display: table-cell;
        vertical-align: middle;
        width: 70%;
        border: 1px solid #efefef;
        padding: 8px 20px;
        color: #454545;
        min-height: 35px;
    }

    .wfacp-scodes-row .wfacp-scodes-value-in {
        position: relative;
        padding-right: 60px;
    }

    .wfacp-scodes-row a.wfop_copy_text {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        color: #0073aa;
        font-size: 13px;
        padding: 0 5px;
    }



    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo {
        text-align: center;
        margin-bottom: 25px;
    }

    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo .swal2-icon.swal2-success [class^='swal2-success-circular-line'][class$='left'] {
        background-color: transparent !important;
    }

    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo .swal2-icon.swal2-success [class^=swal2-success-circular-line][class$='right'] {
        background-color: transparent !important;
    }

    .wfacp-funnel-create-success-wrap .wfacp-funnel-create-success-logo .swal2-icon.swal2-success .swal2-success-fix {
        background-color: transparent !important;
    }

    .wfacp-funnel-create-success-wrap {
        display: none;
        margin: 25px 0;
    }

    .wfop_edit_title, .wfop_save_title {
        display: inline-block;
        margin-top: 6px;
        cursor: pointer;
        /*float: left;*/
    }

    .wfacp-funnel-create-success-wrap, .wfop_save_title {
        display: none;
    }

    .product_section_table .product_name .wfop_product_title {
        display: inline-block;
        min-width: 50px;
        border: 1px solid transparent;
        box-shadow: inset 0 1px 2px transparent;
        padding: 5px;
        max-width: 290px;
    }

    .product_section_table .product_options {
        clear: unset;
        padding-left: 6px;
    }

    .product_section_table .product_name .wfop_product_title.wfop_title_border {
        border: 1px solid #ddd;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        width: calc(100% - 5px);
    }

    th#preview {
        width: 5%;
    }

    .bwf_ajax_save_buttons a.wfop_save_btn_style:hover {
        color: #fff;
    }

    /* close icon style */

    .wfop_input_fields_list_wrap .wfop_save_btn_style.wfop_item_drag {

        margin: 0;
    }

    .template_field_selecter .wfop_input_field_btn_holder span.dashicons.dashicons-no-alt {
        position: absolute;
        width: 20px;
        height: 20px;
        right: -5px;
        bottom: 0;
        top: -14px;
        background: red;
        display: none;
        color: #fff;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        cursor: pointer;
    }

    .template_field_selecter .wfop_input_field_btn_holder span.dashicons.dashicons-no-alt:before {
        font-size: 15px;
        line-height: 20px;
        top: 0px;
        position: relative;
    }

    .template_field_selecter .wfop_input_field_btn_holder .wfop_save_btn_style:hover > span.dashicons {
        display: block;
    }

    .wfop_required_cls {
        position: relative;
    }

    .wfop_required_cls .field-wrap {
        position: absolute;
        top: 0;
        display: block;
        max-width: 20px;
        margin: 0;
    }

    .wfop_required_cls .field-wrap input[type="checkbox"] {
        margin: 0;
        display: block;
        margin-top: 4px;
    }

    .wfop_required_cls label {
        padding-left: 30px;
    }

    .wfop_fsetting_table_head .bwf_form_submit a:hover, .wfop_btm_grey_area .wfop_btm_save_wrap a:hover, .wfop_fsetting_table_head .bwf_form_submit a:focus, .wfop_btm_grey_area .wfop_btm_save_wrap a:focus {
        background: var(--wffn-primary-alter);
        border-color: var(--wffn-primary-alter);
        color: #fff;
    }

    /* global setting css */
    .wfop_forms_global_settings .hide_label_cls label {
        display: none;
    }

    .wfop_forms_global_settings .hide_label_cls .field-wrap {
        width: 100%;
        display: block;
        padding-left: 0;
    }

    .wfop_forms_global_settings .hide_label_cls .field-wrap textarea {
        width: 100%;
    }

    .wfop_required_cls label {
        cursor: default;
    }

    .wfop_settings_sections .vue-form-generator input.form-control {
        width: 25em;
        min-height: 42px;
    }

    .wfop_settings_sections .vue-form-generator fieldset {
        margin-bottom: 20px
    }

    .wfop_settings_sections .vue-form-generator fieldset:last-child {
        margin-bottom: 0px
    }

    .wfop_settings_sections .form-group.wfop_setting_heading.field-label label {
        font-weight: 700;
        font-size: 15px;
        line-height: 1.4em;
        margin: 0px 0 15px;
        display: block;
        width: 100%;
    }

    .wfop_settings_sections .vue-form-generator fieldset .form-group label {
        width: 295px;
        float: left;
        padding: 0 1% 0 0;
        font-size: 14px;
        line-height: 1.3;
        min-height: 50px;
    }

    .wfop_settings_sections .form-group.field-textArea textarea {
        width: 25em;
        padding: 8px;
        border: 1px solid #ddd;
        -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        -ms-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        background-color: #fff;
        color: #32373c;
        outline: 0;
        -webkit-transition: 50ms border-color ease-in-out;
        -moz-transition: 50ms border-color ease-in-out;
        -ms-transition: 50ms border-color ease-in-out;
        transition: 50ms border-color ease-in-out;
    }

    .wfop_settings_sections .vue-form-generator .field-switch .field-wrap label, #wfop_global_settings .vue-form-generator .field-switch .field-wrap label {
        width: 47px;
        height: 22px;
        padding: 0;
    }

    .wfop_settings_sections .vue-form-generator .form-group.field-switch [data-on="Active"], .wfop_settings_sections .vue-form-generator .form-group.field-switch [data-on="Inactive"] {
        text-indent: -999999px;
    }

    .wfop_settings_sections .vue-form-generator .field-switch .handle, #wfop_global_settings .vue-form-generator .field-switch .handle {
        width: 22px;
        height: 22px;
        top: 0;
        left: 0;
    }

    .wfop_settings_sections .vue-form-generator .field-switch input:checked ~ .handle {
        left: auto;
        right: 0;
    }

    .wfop_settings_sections .vue-form-generator .field-switch .label {
        background: #fc7626;
    }

    .wfop_settings_sections .vue-form-generator .field-switch input:checked ~ .label {
        background: #6dbe45;
    }

    .wfop_settings_sections .vue-form-generator .form-control {
        border-radius: 0;
        font-size: 13px;
    }

    .wfop_checkout_url .field-wrap .wrapper .form-control {
        display: inline-block;
    }

    body .wfacp-scodes-list-wrap .wfacp-scodes-row {
        height: 50px;
    }

    .wfop_inner_setting_wrap .form-group.wfop_setting_heading.field-label label > span {
        margin-left: 0;
    }

    /* Local Setting Css */
    .wfop_inner_setting_wrap .form-group.wfop_setting_heading.field-label label {
        line-height: 1.5;
        min-height: 1px;
        display: block;
        margin: 0 0 5px;
    }

    .wfop_inner_setting_wrap .vue-form-generator .wfop_setting_heading.form-group .hint {
        font-size: 14px;
        line-height: 1.5;
        color: #757575;
    }

    .wfop_inner_setting_wrap .form-group.wfop_setting_heading.preview_heading_wrap.field-label .hint {
        margin: 0 0 20px;
    }

    #wfop_setting_container.wfop_inner_setting_wrap .hint {
        font-size: 14px;
        line-height: 1.5;
        max-width: 500px;
        color: #757575;
        float: left;
    }

    #wfop_setting_container.wfop_inner_setting_wrap .empty_cart_heading_hint .hint {
        padding-left: 225px;
    }

    .wfop_inner_setting_wrap .form-group.wfop_setting_heading.field-label {
        margin: 0 0 30px;
    }

    .wfop_inner_setting_wrap .wfop_settings_sections .vue-form-generator fieldset .form-group .radio-list label {
        width: 70px;

    }

    .wfop_fsetting_table_head {
        padding: 10px 16px;
        background-color: var(--wffn-boxer);
        margin-bottom: 25px;
        border-bottom: 1px solid var(--wffn-border-color, #dedfea);
        border-radius: 10px 10px 0 0;
    }

    .wfop_fsetting_table_head.wfacp-scodes-head.wfop_shotcode_tab_wrap {
        padding-left: 18px;
        padding-right: 18px;
    }

    .wfacp-scodes-value-in + .hint {
        font-size: 14px;
        line-height: 1.5;
        color: #757575;
        margin: 5px 0 0;
        font-weight: normal;
    }

    #wfop_optimization_container .wfop_fsetting_table_head {
        margin-bottom: 0;
    }

    #wfop_layout_container .wfop_fsetting_table_head {
        margin-bottom: 0;
    }

    #wfop_layout_container .single_step_template {
        margin-left: 0;
        margin-right: 0;
    }

    .wfop_fsetting_table_head .wfop_fsetting_table_title {
        font-size: 16px;
        line-height: 36px;
        color: #454545;
        padding-right: 15px;
        display: block;
        text-align: left;
    }

    .wfop_global .wfop_forms_global_settings .wfop_global_label_cls label,
    .wfop_global .wfop_forms_global_settings .wfop_global_css_wrap label {
        font-weight: 700;
    }

    .wfop_form_note_sec p {
        font-size: 16px;
        color: #ADB2B4;
        margin: 36px 10px 36px;
        text-align: center;
    }

    .wfop_form_note_sec {
        margin: 0 0 40px 0;
    }

    #edit-field-form .vue-form-generator .hint, #add-section-form .vue-form-generator .hint {
        font-size: 12px;
    }

    #modal-edit-field .hint.wfop_hint {
        font-size: 12px !important;
        color: #444444;
        font-style: italic;
    }

    span.wfop_page_title.wfop_bold_here {
        font-weight: 500;
    }

    .wfop_loader {
        position: absolute;
        width: calc(100% - 0px);
        height: 100%;
        text-align: center;
        background: #fff;
        z-index: 100;
        /*margin-left: 19px;*/
    }

    .wfop_loader .spinner {
        visibility: visible;
        margin: auto;
        width: 30px;
        float: none;
        margin-top: 200px;
        height: 30px;
        background: url(../img/spinner-2x.gif) no-repeat;
        background-size: 30px;
    }

    .wfop_wrap.wfop_box_size.product .wfop_loader span.spinner {
        margin-top: 188px;
    }

    .wfop_wrap.wfop_box_size.settings .wfop_loader span.spinner {
        margin-top: 188px;
    }

    #wfop_global_setting_vue {
        position: relative;
    }

    #wfop_global_setting_vue .wfop_loader {
        min-height: 655px; /*opacity: 0.7;*/
    }

    body .iziModal .iziModal-header.iziModal-noSubtitle .iziModal-header-title {
        font-size: 24px;
        line-height: 32px;
        color: #353030;
        float: left;
        width: 100%;
        line-height: 25px;
        font-weight: 600;
        animation: unset !important;
        margin: 0px;
    }

    .wfop_product_list_wrap .wfop_overlay_active.wfop_overlay_none_here {
        display: none;
    }

    .wfop_product_list_wrap .wfop_overlay_active {
        position: absolute;
        left: 0;
        right: 0;
        background: #f1f1f1;
        top: 0;
        bottom: 0;
        z-index: 1;
        opacity: .5;

    }
    .wfacp_overlay.wfacp_overlay_active {
        display: block;
        z-index: 497;
        background: rgba(0, 0, 0, 0.35)
    }

    .wfacp_overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
    }

    .wfop_product_list_wrap .product_list table td {
        position: relative;
    }

    .hint.wfop_hint {
        clear: both;
        font-size: 8px !important;
    }

    #screen-meta, #screen-meta-links {
        /*display: none !important;*/
    }

    .wfop_forms_global_settings .form-group.wfop_global_css_wrap_field .field-wrap textarea {
        width: 600px;
        height: 200px;
    }

    .wfop_forms_global_settings .form-group.wfop_global_css_wrap_field .field-wrap {
        padding-left: 201px;
        width: 100%;
    }

    .wfop_global_css_wrap_field .hint {
        padding-left: 200px !important;
    }

    /* cache setting */
    #poststuff .notification_wrap h3.hndle {
        font-size: 17px;
        line-height: 1.5;
        margin: 0;
        text-align: center;
        padding: 12px 0;
        font-weight: normal;
    }

    #poststuff .notification_wrap h3.hndle span {
        padding-left: 18px;
    }

    .notification_wrap .swal2-icon.swal2-warning {
        font-size: 6px;
        line-height: 33px;
    }

    .notification_content_sec p {
        font-size: 15px;
        line-height: 1.5;
        position: relative;
        padding-left: 20px;
        margin-bottom: 5px;
    }

    .notification_content_sec p:before {
        width: 10px;
        content: '';
        height: 10px;
        background: red;
        position: absolute;
        left: 0;
        border-radius: 50%;
        top: 6px;
    }


    .notification_wrap .inside {
        padding: 0px 15px 30px;
    }

    .notification_wrap a.notice-dismiss {
        bottom: auto;
        top: auto;
        position: relative;
        float: none;
        right: 0;
        padding: 0;
        margin-top: 0;
        font-size: 13px;
        line-height: 20px;
        text-decoration: none;
        height: 20px;
        display: inline-block;
    }

    .wfop_notification_dismiss_link {
        text-align: right;
    }

    .notification_wrap a.notice-dismiss:before {
        position: absolute;
        top: 0px;
        left: -20px;
        -webkit-transition: all .1s ease-in-out;
        transition: all .1s ease-in-out;
    }

    .notification_content_sec:last-child {
        margin-bottom: 0;
    }

    .notification_content_sec {
        margin-bottom: 30px;
    }

    .notification_wrap.closed a.notice-dismiss {
        display: none;
    }

    .wf_notification_wrap h3.hndle {
        font-size: 17px;
        line-height: 1.5;
        margin: 0;
        text-align: center;
        padding: 12px 0;
        font-weight: normal;
    }

    .wf_notification_wrap h3.hndle span {
        padding-left: 18px;
    }

    .wfop_learn_how_wrap {
        margin-bottom: 0;
        padding-top: 0;
        padding: 25px 25px 0px 25px;
        background: #fff;
    }

    #wfop_product_container .wfop_welcome_wrap + .wfop_learn_how_wrap {
        display: none;
    }

    #wfop_design_container .wfop_fsetting_table_head,
    #wfop_product_container .wfop_fsetting_table_head,
    #wfop_setting_container .wfop_fsetting_table_head {
        margin-bottom: 0;
        background: #f6fcff;
    }

    /**
Product Switcher Styling Start
*/

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul {
        margin: 0 0 15px;
        padding: 0;
        border-bottom: 1px solid #B9B9B9;
    }


    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li {
        display: block;
        margin: 0;
        float: left;
        position: relative;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul:after,
    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul:before {
        content: '';
        display: block;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul:after {
        clear: both;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li a {
        display: block;
        padding: 12px 15px;
        margin: 0;
        cursor: pointer;
        font-size: 16px;
        line-height: 22px;
        border: 1px solid transparent;
        border-bottom-color: #B9B9B9;
        border-bottom: none;
        color: #777777;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li a.wfop_tab_link.activelink {
        border-color: #B9B9B9;
        border-bottom: transparent;
        background: #fbfbfb;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li a.wfop_tab_link.activelink {
        border-color: #B9B9B9;
        border-bottom: transparent;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li a.wfop_tab_link.activelink:after {
        content: '';
        height: 1px;
        position: absolute;
        left: 0;
        right: 0;
        background: #efefef;
        bottom: -1px;
    }

    .wfop_product_swicther_field_wrap .product_switching_fields {
        margin-bottom: 25px;
    }

    .vue-form-generator .product_switching_fields .form-group {
        margin-bottom: 0;
    }


    .wfop_product_swicther_field_wrap .product_switching_table_heading .product_switching_table_heading_col.form-group {
        font-size: 16px;
        line-height: 1.5;
    }


    body .wfop_product_swicther_field_wrap .wfop_pr_col_style1,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_first_half,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_two_third,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style1,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style2 {
        margin-bottom: 0;
    }

    body .wfop_product_swicther_field_wrap .wfop_pr_col_style1 .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2 .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_first_half .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_two_third .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style1 .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style2 .wp-editor-wrap {
        width: 100%;
    }


    .wfop_product_swicther_field_wrap * {
        box-sizing: border-box;
    }

    .wfop_product_switching_table_col_wrap .product_switching_table_row {
        position: relative;
        display: flex;
    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style1 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 14%;
        flex: 0 0 14%;
        max-width: 14%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;

    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style2 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 43%;
        flex: 0 0 43%;
        max-width: 43%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;
    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style3 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 43%;
        flex: 0 0 43%;
        max-width: 43%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;
    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style_first_half {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 40%;
        flex: 0 0 40%;
        max-width: 40%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;
    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style_two_third {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 60%;
        flex: 0 0 60%;
        max-width: 60%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;

    }


    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row_col input[type="checkbox"] {
        margin-left: 18px;
    }

    body .wfop_product_swicther_field_wrap .wfop_pr_col_style1 input[type="checkbox"],
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2 input[type="checkbox"] {
        margin: 0;
    }

    .wfop_product_swicther_field_wrap .product_switching_table_heading:after, .product_switching_table_heading:before,
    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row:after,
    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row:before {
        display: block;
        content: '';
    }

    .wfop_product_swicther_field_wrap .product_switching_table_heading:after,
    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row:after {
        clear: both;
    }

    .wfop_product_swicther_field_wrap .product_switching_table_heading {
        background: #E8E8E8;
        padding: 10px 0;
        display: flex;
    }


    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap {
        padding: 22px 0;
        border-bottom: 1px solid #d1d1d1;
    }

    .wfop_product_swicther_field_wrap .wfop_tab_container {
        margin-bottom: 25px;

    }

    .wfop_product_swicther_field_wrap #product_switching_advanced_setting {
        padding-top: 10px;
    }

    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row_col input[type="radio"] {
        height: 20px;
        width: 20px;
        min-width: 20px;
        margin: 12px 0;
    }

    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row_col input[type="radio"]:before {
        width: 8px;
        height: 8px;
        margin: 5px;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field {
        margin: 0 0 15px;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field:last-child {
        margin-bottom: 0;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field input[type="checkbox"] {
        margin: 1px 0 0 0;
        float: left;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field input[type="checkbox"] + label {
        padding-left: 30px;
        display: block;
        color: #666666;
        font-size: 14px;
        line-height: 18px;
    }

    .wfop_pr_sec {
        margin-bottom: 20px;
    }

    .wfop_pr_sec input[type="checkbox"], .wfop_pr_sec input[type="radio"] {
        margin-top: 0;
    }

    body .wfop_product_swicther_field_wrap .wfop_vue_forms .wfop_multi_input input {
        margin-bottom: 15px;
    }

    body .wfop_product_swicther_field_wrap .wfop_vue_forms .wfop_multi_input input:last-child {
        margin-bottom: 0;
    }

    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2.dis_flex {
        display: flex;
    }

    .wfop_product_switcher_hide_additional_information label {
        font-size: 15px;
        line-height: 1.5;
    }

    body .wfop_product_swicther_field_wrap .helper_text {
        margin-bottom: -25px;
        margin-top: 25px;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info {
        margin-left: -15px;
        margin-right: -15px;
    }

    .wfop_ps_conditional_wrap {
        margin-top: 30px;
    }

    .wfop_ps_conditional_wrap p {
        font-style: italic;
        margin: 4px 0 15px;
        display: block;
        width: 100%;

    }

    .wfop_ps_conditional_wrap .helper_text .helper_text_heading p {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        margin: 0 0 5px;
        font-style: normal;
    }

    .wfop_ps_conditional_wrap input {
        margin-top: 0;
        margin-right: 6px;
        float: left;
    }

    .wfop_ps_conditional_wrap label {
        font-size: 15px;
        font-weight: normal;
        line-height: 20px;
    }

    #product_switching_advanced_setting h3 {
        margin: 0 0 12px;
        font-weight: normal;
        font-size: 16px;
        line-height: 24px;
        color: #414141;
        display: block;
    }

    body .wfop_product_swicther_field_wrap .pro_mb {
        margin: 0 0 20px;
    }

    .pro_mb:last-child {
        margin-bottom: 0;
    }


    body .wfop_product_swicther_field_wrap .pro_other_info .wfop_pr_sec label {
        font-weight: normal;
        display: block;
        color: #666666;
        font-size: 14px;
        line-height: 18px;
        margin-bottom: 5px;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info .wfop_pr_sec {
        max-width: 50%;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info select,
    body .wfop_product_swicther_field_wrap .pro_other_info input {
        font-weight: normal;
        color: #666666;
        font-size: 14px;
        line-height: 18px;
        min-height: 44px;

    }

    .old_product_name {
        color: #666666;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info .wfop_pr_sec {
        width: 33.33%;
        float: left;
        padding: 0 15px;
        margin-bottom: 20px;
    }

    .pro_other_info.wfop_vue_forms:after, .pro_other_info.wfop_vue_forms:before {
        content: '';
        display: block;
    }

    .pro_other_info.wfop_vue_forms:after {
        clear: both;
    }

    /**
Product Switcher Styling End
*/

    /**
Product Switcher Styling Start
*/

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul {
        margin: 0;
        padding: 0;
        border-bottom: 1px solid #ececec;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li {
        display: block;
        margin: 0;
        float: left;
        position: relative;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul:after,
    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul:before {
        content: '';
        display: block;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul:after {
        clear: both;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li a {
        display: block;
        padding: 12px 15px;
        margin: 0;
        cursor: pointer;
        font-size: 16px;
        line-height: 22px;
        border: 1px solid transparent;
        border-bottom-color: #B9B9B9;
        border-bottom: none;
        color: #777777;
    }


    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li a.wfop_tab_link.activelink {
        border-color: #ececec;
        border-bottom: transparent;
    }

    .wfop_product_swicther_field_wrap #product_switching .wfop_tabs ul li a.wfop_tab_link.activelink:after {
        content: '';
        height: 1px;
        position: absolute;
        left: 0;
        right: 0;
        background: #fff;
        bottom: -1px;
    }

    .wfop_product_swicther_field_wrap .wfop_tab_container {
        border-top: none;
    }

    .wfop_product_swicther_field_wrap .product_switching_fields {
        margin-bottom: 25px;
    }

    .vue-form-generator .product_switching_fields .form-group {
        margin-bottom: 0;
    }


    .wfop_product_swicther_field_wrap .product_switching_table_heading .product_switching_table_heading_col.form-group {
        font-size: 16px;
        line-height: 1.5;
    }


    body .wfop_product_swicther_field_wrap .wfop_pr_col_style1,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_first_half,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_two_third,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style1,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style2 {
        margin-bottom: 0;
    }

    body .wfop_product_swicther_field_wrap .wfop_pr_col_style1 .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2 .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_first_half .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style_two_third .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style1 .wp-editor-wrap,
    body .wfop_product_swicther_field_wrap .product_switching_table_heading_col.wfop_pr_col_style2 .wp-editor-wrap {
        width: 100%;
    }

    body .wfop_product_swicther_field_wrap .product_switching_table_heading .product_switching_table_heading_col.form-group {
        padding: 0 15px;
        margin: 0;
    }


    .wfop_product_swicther_field_wrap * {
        box-sizing: border-box;
    }

    .wfop_product_switching_table_col_wrap .product_switching_table_row {
        position: relative;
        display: flex;
    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style1 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 14%;
        flex: 0 0 14%;
        max-width: 14%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;

    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style2 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 43%;
        flex: 0 0 43%;
        max-width: 43%;
        display: block;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;
    }

    /*.wfop_product_swicther_field_wrap .wfop_pr_col_style2:nth-child(2n) {*/
    /*    -ms-flex: 0 0 57%;*/
    /*    flex: 0 0 57%;*/
    /*    max-width: 57%;*/
    /*}*/

    .wfop_product_swicther_field_wrap .wfop_pr_col_style3 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 43%;
        flex: 0 0 43%;
        max-width: 43%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;
    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style_first_half {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 40%;
        flex: 0 0 40%;
        max-width: 40%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;
    }

    .wfop_product_swicther_field_wrap .wfop_pr_col_style_two_third {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 60%;
        flex: 0 0 60%;
        max-width: 60%;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        color: #414141;
        line-height: 1.5;

    }


    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row_col input[type="checkbox"] {
        margin-left: 18px;
    }

    body .wfop_product_swicther_field_wrap .wfop_pr_col_style1 input[type="checkbox"],
    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2 input[type="checkbox"] {
        margin: 0;
    }

    .wfop_product_swicther_field_wrap .product_switching_table_heading:after, .product_switching_table_heading:before,
    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row:after,
    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row:before {
        display: block;
        content: '';
    }

    .wfop_product_swicther_field_wrap .product_switching_table_heading:after,
    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row:after {
        clear: both;
    }

    .wfop_product_swicther_field_wrap .product_switching_table_heading {
        background: #f1f1f1;
        padding: 10px 0;
        display: flex;
    }


    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap {
        padding: 22px 0;
        border-bottom: 1px solid #d1d1d1;
    }


    .wfop_product_swicther_field_wrap .wfop_tab_container {


        margin-bottom: 25px;
        padding: 15px 18px;
        border: 1px solid #ececec;
        border-top: none;
    }

    .wfop_product_swicther_field_wrap #product_switching_advanced_setting {
        padding-top: 10px;
    }

    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row_col input[type="radio"] {
        height: 20px;
        width: 20px;
        min-width: 20px;
        margin: 12px 0;
    }

    .wfop_product_swicther_field_wrap .wfop_product_switching_table_col_wrap .product_switching_table_row_col input[type="radio"]:before {
        width: 8px;
        height: 8px;
        margin: 5px;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field {
        margin: 0 0 15px;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field:last-child {
        margin-bottom: 0;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field input[type="checkbox"] {
        margin: 1px 0 0 0;
        float: left;
    }

    .wfop_product_swicther_field_wrap .wfop_tabs .product_switching_advanced_field input[type="checkbox"] + label {
        padding-left: 8px;
        display: block;
        color: #666666;
        font-size: 14px;
        line-height: 18px;
    }

    .wfop_pr_sec {
        margin-bottom: 20px;
    }

    .wfop_pr_sec input[type="checkbox"], .wfop_pr_sec input[type="radio"] {
        margin-top: 0;
    }

    body .wfop_product_swicther_field_wrap .wfop_vue_forms .wfop_multi_input input {
        margin-bottom: 15px;
    }

    body .wfop_product_swicther_field_wrap .wfop_vue_forms .wfop_multi_input input:last-child {
        margin-bottom: 0;
    }

    body .wfop_product_swicther_field_wrap .wfop_pr_col_style2.dis_flex {
        display: flex;
    }

    .wfop_product_switcher_hide_additional_information label {
        font-size: 15px;
        line-height: 1.5;
    }

    body .wfop_product_swicther_field_wrap .helper_text {
        margin-bottom: -25px;
        margin-top: 25px;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info {
        margin-left: -15px;
        margin-right: -15px;
    }

    #product_switching_advanced_setting h3 {
        margin: 0 0 12px;
        font-weight: normal;
        font-size: 16px;
        line-height: 24px;
        color: #414141;
        display: inline-block;
    }

    body .wfop_product_swicther_field_wrap .pro_mb {
        margin: 0 0 35px;
    }

    .pro_mb:last-child {
        margin-bottom: 0;
    }


    body .wfop_product_swicther_field_wrap .pro_other_info .wfop_pr_sec label {
        font-weight: normal;
        display: block;
        color: #666666;
        font-size: 14px;
        line-height: 18px;
        margin-bottom: 5px;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info .wfop_pr_sec {
        max-width: 50%;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info select,
    body .wfop_product_swicther_field_wrap .pro_other_info input {
        font-weight: normal;
        color: #666666;
        font-size: 14px;
        line-height: 18px;
        min-height: 44px;

    }

    .old_product_name {
        color: #666666;
    }

    body .wfop_product_swicther_field_wrap .pro_other_info .wfop_pr_sec {
        width: 33.33%;
        float: left;
        padding: 0 15px;
        margin-bottom: 20px;
    }

    .pro_other_info.wfop_vue_forms:after, .pro_other_info.wfop_vue_forms:before {
        content: '';
        display: block;
    }

    .pro_other_info.wfop_vue_forms:after {
        clear: both;
    }

    /**
Product Switcher Styling End
*/
    .wfop_product_swicther_field_wrap .form-group.field-input .hint {
        font-weight: bold;
    }

    hr {
        border-color: #E4E4E4;
        border-bottom: none;
        background: #E4E4E4;
        height: 2px;
    }

    /* Dependency Message */
    .wfop_field_dependency_messages {
        margin: 25px 0 25px;

        padding: 17px 10px;
        border-left: 4px solid #dc3232;
        background: #fff;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        position: relative;
    }

    .wfop_field_dependency_messages.wfop_warning {
        border-color: #ffb900;
    }

    .wfop_dependency_alert_icon img {
        max-width: 100%;
    }

    .wfop_dependency_alert_icon {
        max-width: 22px;
        float: left;
        line-height: 0;
        display: none;
    }

    .wfop_field_dependency_messages p {
        margin: 0 0 0;
        font-size: 14px;
        line-height: 1.5;
        color: #656363;
    }

    #wfop_admin_advanced_field .optional {
        display: none;
    }

    .notice_msg_wrap {
        padding-right: 24px;
    }

    .wfop_close_icon {
        cursor: pointer;
        position: absolute;
        right: 10px;
        width: 16px;
        height: 16px;
        border: 1px solid #b9b7b7;
        font-size: 13px;
        line-height: 12px;
        text-align: center;
        border-radius: 50%;
        color: #b9b7b7;
        top: 50%;
        margin-top: -8px;
    }

    /* Preview field setting */

    .wfop_settings_sections .vue-form-generator fieldset .form-group.wfop_preview_fields label {
        width: auto;
        padding: 0;
        min-height: 10px;
        text-transform: capitalize;
    }


    .wfop_settings_sections .vue-form-generator fieldset .form-group.wfop_preview_fields .field-wrap {
        display: inline-block;
        vertical-align: middle;
        position: absolute;
        left: 0;
        top: 50%;
        margin-top: -8px;
    }

    .form-group.valid.wfop_preview_fields.field-checkbox {
        width: auto;
        display: inline-block;
        margin: 0 25px 10px 0;
        position: relative;
        padding-left: 25px;
    }

    .form-group.valid.wfop_preview_fields.field-checkbox:after, .form-group.valid.wfop_preview_fields.field-checkbox:before {
        content: '';
        display: block;
    }

    .form-group.valid.wfop_preview_fields.field-checkbox:after {
        clear: both;
    }

    .wfop_settings_sections .vue-form-generator fieldset .form-group.wfop_preview_fields .field-wrap input[type="checkbox"] {
        margin: 0;
        margin: 0;
        vertical-align: middle;
        display: block;
    }

    .form-group.valid.wfop_preview_fields.field-checkbox:last-child {
        margin: 0 0 10px;
    }

    .wfop_inner_setting_wrap .form-group.wfop_setting_heading.preview_heading_wrap.field-label {
        margin-bottom: 0;
    }

    .wfop_inner_setting_wrap .form-group.wfop_setting_heading.wfop_admin_preview_feilds_wrap.field-label {
        margin-bottom: 20px;
    }

    #wfop_setting_container .form-group.valid.wfop_setting_heading.wfop_preview_note.field-label .hint {
        font-style: normal;
        font-weight: normal;
        color: #444;
    }

    .wfaco_preview_top_spacing {
        margin-top: 1rem;
    }

    .wfop_set_shipping_method_wrap {
        position: relative;
    }

    .vue-form-generator .wfop_checkbox_wrap.field-checkbox input {
        margin-left: 0;
    }

    .wfop_set_shipping_method_wrap .field-wrap:before {
        content: 'Shipping Method Prices';
        position: absolute;
        left: 16px;
        font-size: 14px;
        line-height: 1.3;
        font-weight: 400;
        color: #414141;
    }


    /* CSS ADDED TO CHANGES DESIGN */

    nav.wfob_funnels_nav_tabing {
        border-bottom: 1px solid #ccc;
        margin: 0;
        padding-top: 9px;
        padding-bottom: 0;
        line-height: inherit;
        float: left;
        width: 100%;
    }

    .wfop_nav_tabs {
        float: left;
        border: 1px solid #ccc;
        margin-left: .5em;
        padding: 5px 10px;
        font-size: 14px;
        line-height: 1.71428571;
        font-weight: 600;
        background: var(--wffn-border-color, #dedfea);
        color: #555;
        text-decoration: none;
        white-space: nowrap;
        position: relative;
        top: 1px;
    }

    .wfop_nav_tabs:focus, .wfop_nav_tabs:hover {
        background-color: #fff;
        color: #444;
    }

    .wfop_nav_tabs.active {
        background-color: #f1f1f1;
        color: #444;
        border-bottom-color: transparent;
    }


    /* IZI MODAL CHANGES */


    body .iziModal .iziModal-content {
        background: #fff !important;
    }

    body .iziModal .iziModal-header {
        background: #f9fdff !important;
        box-shadow: 0 0 0 0 !important;
        border-bottom: 1px solid #eee;
    }
    body .iziModal .iziModal-button-close{
        transition: unset;
        background: none !important;
        -webkit-font-smoothing: unset !important;
        animation: unset !important;
        text-decoration: none !important;
    }
    body .iziModal .iziModal-button-close:hover:before {
        color: #f27474
    }
    .iziModal .iziModal-header.iziModal-noSubtitle .iziModal-button {
        opacity: 1;
        display: flex;
        place-content: center;
        align-items: center;
        overflow: hidden;
        border: 0;
    }

    body .iziModal .iziModal-button-close:focus {
        outline: none;
        box-shadow: 0;
    }


    .wfop_head_ml a {
        font-size: 17px;
        /*margin-left: 12px !important;*/
    }

    .wfop_head_ml a span {
        margin-left: 5px !important;
    }

    body .wfop_head_ml ul {
        margin: 0;
        padding: 0;
        display: inline-block;
    }

    body .wfop_head_ml ul li {
        display: inline-block;
        position: relative;
        padding-right: 25px;
    }

    body .wfop_head_ml ul li a {
        cursor: pointer;
        margin: 0;
        font-size: 22px;
    }

    body .wfop_head_ml ul li:after {
        content: "\f345";
        font-family: dashicons;
        position: absolute;
        right: 1px;
        top: 2px;
        color: #3f3f3f;
        font-size: 22px;
    }

    body .wfop_head_ml ul li:last-child {
        padding-right: 0;
    }


    body .wfop_head_ml strong {
        color: #222;
    }

    body .wfop_head_ml ul li:last-child:after {
        content: '';
    }

    body .wfop_body {
        padding: 25px 0 0 20px;
        margin: 0 20px 0 0;
    }

    .wfop_wrap_inner_fields .wfop_wrap_r {
        background: transparent;
    }

    .template_field_holder .template_steps_container {
            background: #fff;
            border: 1px solid var(--wffn-border-color);
            border-radius: 10px;
    }


    .wfop_wrap_inner_fields .template_steps_container .wfop_fsetting_table_head {
        padding: 3px 15px 0 0;
        height: 51px;
        border-bottom: 2px solid #f6fcff;
    }


    .wfop_step_add_step_btn {
        padding: 1px;
        margin: 12px;
        line-height: 15px;
        border: 1px solid #0475ac;
        border-radius: 3px;
        cursor: pointer;
    }

    .wfop_step_add_step_btn .dashicons {
        padding: 5px 0;
        height: auto;
        color: #0475AC !important;
        background-color: #BEE5F8;
        font-size: 12px;
        border-radius: 3px;
        height: 20px;
        width: 20px;
    }

    .wfop_field_container.ui-sortable-handle {
        cursor: move;
    }


    .wfop_tooltip {
        right: 0;
        position: absolute;
        background: #3d3d3d;
        left: 0;
        width: 205px;
        line-height: 34px;
        color: #fff;
        top: 0px;
        opacity: 0;
        border-radius: 3px;
        box-shadow: 0 0 1px rgba(0, 0, 0, 0.75);
        z-index: -1;
        text-transform: none;
    }

    .wfop_tooltip::after {
        content: "";
        position: absolute;
        top: -10px;
        left: 15%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #3d3d3d transparent;
    }

    .wfop_item_drag:hover .wfop_tooltip {
        opacity: 1;
        top: 44px;
        z-index: 1;
    }


    .wfop_select_step_columns ul {
        display: table;
        padding: 10px 15px 10px 15px;
        margin: 0;
    }

    .wfop_select_step_columns ul li {
        display: table-cell;
        vertical-align: middle;
        padding-right: 3px;
        font-size: 14px;
        color: #3A3737;
    }

    .wfop_select_step_columns ul li button {
        background: none;
        border: none;
        padding: 0 5px 0 3px;
        border-right: 1px solid #ebebeb;
    }

    .wfop_select_step_columns ul li:last-child button {
        border-color: transparent;
    }

    .wfop_select_step_columns ul li button img {
        vertical-align: middle;
    }

    .wfop_select_step_columns ul li button.wfop_act svg path {
        stroke: #0085BA;
    }

    .wfop_field_container_action a {
        position: relative;
        padding: 0 6px;
    }

    .wfop_field_container_action a:after {
        content: '|';
        color: #dedede;
        position: absolute;
        left: -4px;
    }

    .wfop_field_container_action a:first-child:after {
        color: transparent;
    }

    .wfop_field_container_action a:last-child {
        padding-right: 0;
    }

    .wfop_template_fileds_note_outer {
        background: #fff;
        padding: 1px;
    }

    .wfop_template_fileds_note {
        background: #f8f8f8;
        padding: 35px 15px;
        font-size: 14px;
        color: #6C6C6C;
        line-height: 1.4;
        border-top: 1px solid #f1f1f1;
        /*border-right: 1px solid #fff;
border-bottom: 1px solid #fff;
border-left: 1px solid #fff;*/
    }

    .wfop_template_fileds_note a {
        color: #0073AA;
        text-decoration: none;
    }

    .wfop_fields_border_div {
        cursor: move;
        border: 1px solid var(--wffn-border-color);
        border-radius: 10px;
    }

    .wfop_form_note_sec {
        border: 2px solid #d0e6f0;
        clear: both;
    }


    /* TEMPLATES DESIGN NEW PART */

    .wfop_form_templates_outer {
        width: 900px;
        margin: 0 auto;
        background: #fff;
    }

    .wfop_templates_inner {
        width: 100%;
        display: block;
    }

    .wfop_templates_design {
        width: 56%;
        float: left;
    }

    .wfop_center_align {
        text-align: center;
    }

    .wfop_templates_design .wfop_img {
        width: 331px;
        margin: 0 auto;
        border: 3px solid var(--wffn-border-color, #dedfea);
        height: 418px;
    }


    .wfop_img div {
        padding: 2px 2px;
    }

    .wf_funnel_templates_design img {
        display: block;
        margin: 0 auto;
        width: 100%;
    }


    .wfop_btn_white {
        font-size: 15px;
        line-height: 27px;
        text-align: center;
        text-decoration: none;
        color: #fff;
        bottom: 0;
        width: 100%;
        font-weight: 600;
        opacity: 0;
        z-index: 99;
        left: 0;
        padding: 8px 0;
        transition: all .4s ease-in-out;
    }

    .wfop_btn_white:hover {
        color: #fff;
    }

    .wfop_tab-content .wfop_pick_template .wfop_template_sec:hover .wfop_btn_white {
        opacity: 1;
    }

    .wfop_display_block {
        display: block;
    }

    .wfop_templates_action {
        width: 50%;
        float: left;
    }

    .wfop_temp {
        font-size: 20px;
        line-height: 1.5;
    }

    .wfop_btn {
        position: relative;
        min-width: 84px;
        text-align: center;
        padding: 0 16px;
        font-weight: 500;
        border-radius: 3px;
        font-size: 14px;
        min-height: 36px;
        box-shadow: none;
        text-decoration: none;
        display:inline-flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        cursor: pointer;
        color: var(--wffn-primary);
        border: 1px solid var(--wffn-primary);
    }
    .wfop_btn.is_busy {
        opacity: 1 !important;
        background-image: linear-gradient( -45deg,#007cba 33%,#005a87 0,#005a87 70%,#007cba 0) !important;
        animation: components-button__busy-animation 2.5s linear infinite;
        color: #fff;
        pointer-events: none;
    }

    .wfop_btn_primary, .wfop_btn_success {
        color: #fff;
    }

    .wfop_btn_primary:focus,
    .wfop_btn_success:focus {
        color: #fff;
    }

    .wfop_btn_primary {
        background: #0073AA;
        color: #fff;
    }

    .wfop_btn_primary:hover {
        color: #fff !important;
        background: #035a84;
    }

    .wfop_btn_primary:focus {
        color: #fff !important;
    }

    .wfop_btn_success {
        background: #54C867;
    }

    .wfop_btn_success:hover {
        background: #45b157;
        color: #fff !important;
    }

    .wfop_btn_grey {
        color: #444444;
        background-color: #fff;
        border-color: #444444;
    }

    .wfop_btn_grey:hover {
        color: #fff;
        background-color: #444444;
        border-color: #444444;
    }


    .wfop_template_bottom {
        background: #F6F9FB;
        padding: 0 20px;
    }

    .wfop_link {
        font-size: 16px;
        text-decoration: none;
    }

    .wfop_blue_link {
        color: #0085BA;
    }

    .wfop_red_link {
        color: #dc3232;
    }

    .wfop_red_link:hover {
        color: red;
    }

    .wfop_pull_right {
        float: right;
    }

    img {
        max-width: 100%;
    }

    /* TEMPLATES DESIGN NEW PART */


    /*Fun begins*/
    .wfop_tab_container {
        width: 100%;
        margin: 0;
        box-sizing: border-box;
        position: relative;

        background-color: #f9fdff;
    }

    .wfwfop_tab_container input, .wfop_tab_container section {
        clear: both;
        padding-top: 10px;
        display: none;
    }

    .wfop_tab_container label {
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        font-weight: 500;
        font-size: 14px;
        display: block;
        line-height: 21px;
        color: #000;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        background: #f9fdff;
        border-bottom: 2px solid transparent;
        margin-right: 10px;
    }

    .wfop_tab_container #wfop_tab1:checked ~ #wfop_content1,
    .wfop_tab_container #wfop_tab2:checked ~ #wfop_content2,
    .wfop_tab_container #wfop_tab3:checked ~ #wfop_content3,
    .wfop_tab_container #wfop_tab4:checked ~ #wfop_content4,
    .wfop_tab_container #wfop_tab5:checked ~ #wfop_content5 {
        display: block;
        padding: 30px 20px 60px 20px;
        background: #fff;
        color: #999;
    }

    .wfop_tab_container .wfop_tab-content,
    .wfop_tab_container .wfop_tab-content {
        position: relative;
        top: 0px;
        padding: 40px 15px;
        margin: auto;
        min-width: 1030px;
        width: 1030px;
        border: none;

    }

    #wfop_design_container #wfop_content1 {
        padding-top: 0;
    }

    .wfop_tab_container [id^="wfop_tab"]:checked + label {
        z-index: 9;
        position: relative;
        background: #fff;
        border-bottom: 3px solid #E0ECF2;
        border-left: 1px solid #E0ECF2;
        border-right: 1px solid #E0ECF2;
    }

    .wfop_tab_container [id^="wfop_tab"]:checked + label .fa {
        color: #0CE;
    }

    .wfop_tab_container label .fa {
        font-size: 1.3em;
        margin: 0 0.4em 0 0;
    }

    .wfop_tab_container div[data-select="selected"] label {
        z-index: 1;
        position: relative;
        background: #0073AA;
        color: #fff;

    }

    .wfop_temp_anchor {
        display: inline-block;
    }


    /*Content Animation*/
    @keyframes fadeInScale {
        0% {
            transform: scale(0.9);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }


    .wfop_temp_head {
        display: inline-block;
        color: #040404;
        font-size: 16px;
        font-weight: 600;
        float: left;
        line-height: 56px;
        padding-left: 15px;
    }

    .wfop_tabs {
        display: inline-block;
        float: left;
    }

    .wfop_temp_cont {
        position: relative;
        margin-top: 50px;
    }

    .wfop_temp_overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0);
        transition: background 0.5s ease;
        z-index: 454
    }

    .wfop_selected_designed .wfop_temp_overlay .wfop_temp_middle_align .wfop_p {
        margin: 0;
    }

    .wfop_temp_cont:hover .overlay {
        display: block;
        background: rgba(0, 0, 0, .9);
    }

    .wfop_temp_cont img {
        position: absolute;
        left: 0;
    }

    .wfop_temp_title {
        position: absolute;
        left: 0;
        top: 120px;
        font-weight: 700;
        font-size: 30px;
        text-align: center;
        text-transform: uppercase;
        color: white;
        z-index: 1;
        transition: top .5s ease;
    }

    .wfop_temp_cont:hover .wf_funnel_temp_title {
        top: 90px;
    }

    .wfop_temp_button {
        position: absolute;
        left: 0;
        top: 180px;
        text-align: center;
        opacity: 0;
        transition: opacity .35s ease;
    }

    .wfop_temp_button a {
        padding: 12px 48px;
        text-align: center;
        color: white;
        border: solid 2px white;
        z-index: 1;
    }

    .wfop_temp_cont:hover .wf_funnel_temp_button {
        opacity: 1;
    }

    .wfop_pick_template:after, .wfop_pick_template:before {
        display: block;
        content: '';
    }

    .wfop_pick_template:after {
        clear: both;
    }

    .wfop_tab-content .wfop_pick_template {
        margin: 0;
    }

    .wfop_tab-content .wfop_pick_template .wfop_temp_card {
        width: 33%;
        float: left;
        padding: 0 14px;
        box-sizing: border-box;
        -webkit-animation: fadein .89s linear forwards;
        animation: fadein .89s linear forwards;
    }

    @-webkit-keyframes fadein {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    .wfop_tab-content .wfop_pick_template .wfop_temp_card .wfop_template_sec {
        margin: 30px auto;
        padding: 0px;
        text-align: center;
        max-width: 100%;
        height: 383px;
        width: 100%;
        position: relative;
        border: 3px solid #f2f2f2;
        overflow: hidden;
    }


    .wfop_tab-content .wfop_pick_template .wfop_temp_card .wfop_template_sec img {
        border: 4px solid #fff;
    }

    .wfop_tab-content .wfop_pick_template .wfop_temp_card .wfop_template_sec:before {
        content: '';
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.72);
        opacity: 0;
        transition: all .3s ease-in-out;
        z-index: 2;
    }

    .wfop_template_sec_ribbon {
        position: absolute;
        display: block;
        padding: 2px 0 4px;
        background-color: #0073aa;
        color: #fff;
        font-size: 12px;
        line-height: 12px;
        font-weight: normal;
        text-align: center;
        text-transform: uppercase;
        z-index: 1;
        width: 100px;
        left: -30px;
        top: 8px;
        transform: rotate(-45deg);
        box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.2);
    }

    .wfop_template_sec_ribbon.wfop_pro {
        background: #5ba238;
        font-weight: bold;
    }

    .wfop_template_sec {
        /* box-shadow: 0 0 5px #cac8c8; */
    }

    .wfop_build_from_scratch {
        box-shadow: none;
    }

    /* .wfop_tab-content .wfop_pick_template .wfop_temp_card:first-child .wfop_template_sec{ margin-left: 0; }
.wfop_tab-content .wfop_pick_template .wfop_temp_card:last-child .wfop_template_sec{ margin-right: 0; } */

    .wfop_template_sec_design {
        margin: auto;
        position: relative;
        max-height: 322px;
        overflow: hidden;
        max-width: 286px;
    }

    .wfop_tab-content .wfop_pick_template .wfop_temp_card .wfop_template_sec.wfop_build_from_scratch {
        padding-top: 5px;
    }

    .wfop_temp_overlay {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        /* background: rgba(0, 0, 0, 0.72); */
        z-index: 44;
        opacity: 0;
        display: table;
        border: 2px solid transparent;
    }

    .wfop_temp_middle_align {
        display: table-cell;
        vertical-align: middle;
    }

    .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch {
        background-color: #ffffff;
    }

    .wfop_build_from_scratch .wfop_temp_middle_align {
        height: 363px;
        overflow: visible;
    }

    .wfop_build_from_scratch .wfop_template_sec_design {
        overflow: visible;
    }

    .wfop_tab-content .wfop_pick_template .wfop_template_sec:hover .wfop_temp_overlay {
        opacity: 1;
        border: 2px dashed transparent;
    }

    .wfop_tab-content .wfop_pick_template .wfop_temp_card .wfop_template_sec:hover:before {
        opacity: 1;
    }

    .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch .wfop_temp_overlay {
        opacity: 1;
        cursor: pointer;
        border: 2px dashed #b5b5b5;
        box-sizing: border-box;
    }

    .wfop_tmp_pro_tab {
        background: #f43c04;
        color: #fff;
        position: absolute;
        top: 8px;
        right: 4px;
        font-size: 14px;
        padding: 1px 3px;
        border-radius: 4px;
        z-index: 55;
        display: none;
    }

    .wfop_steps_btn_add {
        position: absolute;
        width: 30px;
        background: red;
        height: 30px;
        background-color: transparent;
        border: 3px solid #0073aa;
        border-radius: 50%;
        height: 30px;
        /* position: absolute; */
        width: 30px;
        line-height: 20px;
        font-size: 26px;
        font-weight: 600;
        left: 44.0%;

    }

    .wfop_steps_btn_add span {
        color: #0073aa;
        font-weight: 800;
    }

    .wfop_p {
        font-size: 18px;
        color: #000;
        margin: 11px 0 0 0;
    }

    .wfop_steps_temp a {
        color: #777;
        background: transparent;
        border: 2px solid transparent;
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 25px;
    }

    .wfop_steps_temp a.wfop_active_step {
        background: #FAFAFA;
        border-color: var(--wffn-border-color, #dedfea);
    }

    .wfop_temp_middle_align .wfop_steps_btn {
        display: table;
        margin: 9px auto 15px auto;
    }

    .wfop_temp_middle_align .wfop_steps_btn span {
        vertical-align: middle;
        display: none;
        font-size: 16px;
    }

    .wfop_tab-content .wfop_pick_template .wfop_template_sec .wfop_template_sec_design_pro .wfop_temp_middle_align .wfop_steps_btn span {
        display: inline;
    }

    .wfop_tab-content .wfop_pick_template .wfop_template_sec .wfop_template_sec_design_pro .wfop_btn_white {
        background: #f43c04;
        border-color: #f43c04;
        transition: all .7s ease-in-out;
    }

    .wfop_temp_overlay {
        transition: all .3s ease-in-out;
    }

    .wfop_tab-content .wfop_pick_template .wfop_template_sec:hover .wfop_template_sec_design_pro .wfop_temp_overlay {
        border: 2px solid #f43c04;
    }

    .wfop_template_sec_design_pro .wfop_tmp_pro_tab {
        display: block;
    }


    .wfop_steps_temp {
        margin-bottom: 25px;
        margin-left: 20px;
    }

    .wfop_steps_btn {
        line-height: 41px;
        min-width: 155px;
        display: inline-block;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
    }

    .wfop_steps_btn_success {
        color: #0073aa;
        background-color: #fff;
    }

    .wfop_steps_btn_success:hover {
        color: #fff;
        background-color: #0073aa;
    }

    .wfop_steps_btn_white {
        color: #fff;
        background-color: #5f5c5c;
    }

    .wfop_steps_btn_danger {
        background: #dd6363;
        border: 1px solid #dd6363;
        color: #fff;
    }

    .wfop_steps_btn_danger:hover {
        background: #ca3636;
        border: 1px solid transparent;
        color: #fff;
    }

    .wfop_steps_btn_green {
        color: #fff;
        background-color: #80c63c;
    }

    .wfop_steps_btn_green:hover {
        background: #5ba238;
        color: #fff;
    }

    .wfop_tab-content {
        margin: -3px 0 0 0;
        border-top: 3px solid #f1f1f1;
    }


    .wfop_template_bottom a:focus {
        box-shadow: none;
    }


    a:focus {
        box-shadow: none;
    }

    #wfop_product_container .wfop_bg_table {
        background: #f8f8f8;
        padding: 0;

    }

    #wfop_product_container .product_add_new {
        padding-bottom: 30px;
    }


    .wfop_welcome_card {
        text-align: center;
        width: 800px;
        margin: 20px auto;
        background: #fff;
        padding: 30px 0;
        box-shadow: 0px 1px 2px #e6e6e6;
    }

    .wfop_product_h {
        font-size: 24px;
        color: #000;
        font-weight: 600;

    }

    .wfop_product_p {
        font-size: 15px;
        color: #878787;
        line-height: 1.5;
    }

    #wfop_product_container .wfop_wrap_r {
        background: none;
    }

    #wfop_product_container {
        margin-top: 0px;
    }

    div#wfop_product_container table {
        width: 100%;
        border-bottom: 1px solid #f1f1f1;;
    }

    #wfop_product_container .wfop_welcome_card {
        top: 20px;
        position: relative;
    }


    /* TWO STEP TEMPLATE */


    .two_step_template {
        width: calc(50% - 10px);
        float: left;
    }


    .two_step_form_outer {
        float: left;
        width: calc(100% - 30px);
        margin: 15px;
    }

    .two_step_form_outer .two_step_template {
        margin: 5px !important;
    }

    .template_field_selecter .wfop_input_fields_list_wrap {
        position: relative;
    }

    button {
        cursor: pointer;
    }

    input[type="submit"] {
        cursor: pointer;
    }


    /* POPUP PREVIEW */


    .wc-backbone-modal-main footer {
        padding: 1em 1.5em 1em 1.5em !important;
        background: #fff !important;
        border-top: 0 !important;
        box-shadow: none !important;
    }

    .wc-backbone-modal-main .wc-backbone-modal-header .modal-close-link {
        border-left: 0px !important;
        height: 0 !important;
    }


    #wc-backbone-modal-dialog .wc-backbone-modal-header {
        background: #f6fcff !important;
        border-bottom: 1px solid #eee !important;
    }


    table.wfop_pop_table {
        border-collapse: collapse;
        width: 100%;
    }

    table.wfop_pop_table td, table.wfop_pop_table th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    table.wfop_pop_table .wfacb_center {
        text-align: center;
    }

    /*table.wfop_pop_table tr:nth-child(even) {
background-color: #f1f1f1;
}*/


    .wc-backbone-modal-main .wc-backbone-modal-header h1 {
        display: inline-block;
    }

    .order-status > span {
        margin: 0 5px !important;
    }


    /* Remove Template Popup */


    #wfop_popup_temp_id {
        width: 530px;
        margin: 0 auto;
        text-align: center;
        background: #fff;
        padding: 30px 75px;
        border-radius: 5px;
        position: absolute;
        left: 0;
        right: 0;
        top: 20%;
        z-index: 5;
    }

    .wfop_confirm_btn {
        background: #0073aa;
        color: #fff;
        font-size: 18px;
        line-height: 38px;
        font-weight: 400;
        border: none;
        min-width: 120px;
        margin: 0 5px;
    }

    .wfop_cancel_btn {
        background: rgb(227, 59, 59);
        color: #fff;
        font-size: 18px;
        line-height: 38px;
        font-weight: 400;
        border: none;
        min-width: 120px;
        margin: 0 5px;
    }

    .wfop_h3_popup {
        font-size: 22px;
        color: #000;
        margin-bottom: 15px;
    }

    .wfop_p_popup {
        font-size: 16px;
        color: #777;
        margin-bottom: 10px;
    }

    .wfop_alert_popup {
        font-size: 15px;
        color: red;
        margin-bottom: 10px;
    }


    .wfop_popup_temp .form-group input[type="text"] {
        font-size: 16px;
        line-height: 24px;
        color: #444;
        padding: 9px 12px;
        background-color: #fff;
        border: 1px solid #d5d5d5;
        box-shadow: none;
        display: block;
        width: 100%;
        max-width: 100%;
        margin: 0;
        height: auto;
        border-radius: 0;
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
        -ms-border-radius: 0;
    }

    .wfop_popup_temp label {
        font-size: 16px;
        line-height: 24px;
        color: #414141;
        display: block;
        margin-bottom: 7px;
        cursor: default;
        text-align: left;
    }

    .wfop_popup_temp_overlay {
        display: block !important;
        position: absolute;
        top: 0;
        bottom: 0;
    }

    .wfop_popup_temp {
        clear: both;
        display: none;
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        top: 0;
        left: 0;
    }

    .wfop_pick_template {
        width: 100%;
    }


    #wfop_product_container .wfop_learn_how_wrap {
        background: #fff;
        border-top: 1px solid #f1f1f1;
        margin-top: 30px;
    }


    #product_switching_general_setting,
    #product_switching_additional_information,
    #product_switching_advanced_setting,
    #product_switching_templates {

        background: #fbfbfb;
    }

    #product_switching label {
        background: transparent;
        text-align: left;
        line-height: 1.05 !important;
        display: inline-block;
        border-bottom: 4px solid transparent;
        font-weight: normal;
    }

    #edit-field-form .wfop_vue_forms .product_switching_fields .form-group label {
        line-height: 44px !important;
    }

    #product_switching .wfop_tabs {
        display: block;
        float: none;
        width: 100%;
    }

    #product_switching .wfop_tabs:after,
    #product_switching .wfop_tabs:before {
        content: '';
        display: block;
    }

    #product_switching .wfop_tabs:after {
        clear: both;
    }

    #product_switching .product_switching_advanced_field {
        margin: 0 0 0 0;
    }

    .wfop_step_add_step_btn .wfop_tooltip {
        left: -18px;
        top: 39px;
        width: 120px;
    }

    .wfop_step_add_step_btn:hover .wfop_tooltip {
        opacity: 1;
    }


    .template_field_selecter .wfop_fsetting_table_head {
        margin-bottom: 0;
        border: 0;
        border-bottom: 1px solid var(--wffn-border-color, #dedfea);
        border-radius: 0;
    }


    #wfop_design_container input[name="wfop_tabs"] {
        display: none;
    }

    #wfop_design_container .wfop_tab_container label {
        margin: 0 0 0 0;
        min-width: 150px;
        line-height: 43px;
    }

    .wfop_heading_choosen_template {
        padding: 0px 20px;
        font-size: 15px;
        background: #f6fcff;
        line-height: 1.5;
    }

    #wfop_design_container {
        position: relative;
        top: 20px;
    }

    .pro_checkbox_wrap .product_switching_advanced_field {
        margin-bottom: 15px !important;
    }


    .wfop_item_drag .wfop_tooltip:hover {
        opacity: 0;

    }

    .iziModal .iziModal-button-close:hover {
        transform: none !important;
    }

    .wfop_red_link:focus {
        color: #dc3232;
    }

    .wfop_product_text_align {
        top: -24px;
        position: relative;
        padding-left: 10px;
        font-weight: 400;
    }


    #wfop_setting_container .wfop_welcome_wrap {
        width: 800px;
        margin: 20px auto;
        box-shadow: 0px 1px 2px #e6e6e6;
    }

    .wfop_product_switcher_hide_additional_information.wfop_pr_sec input[type="checkbox"] {
        margin: 1px 0 0 0;
        float: left;
    }

    .wfop_product_switcher_hide_additional_information.wfop_pr_sec label {
        color: #666666;
        padding-left: 8px;
    }

    .wfop_mb30 {
        margin-bottom: 30px;
    }

    .wfop_success_modal h2.iziModal-header-title {
        font-size: 15px !important;
        margin: 3px 0 0 0;
        font-weight: 400 !important;
        color: #000;
    }

    .wfop_tabs_templates_part {
        display: inline-block;
        width: 100%;
        text-align: center;
        background: #F6FAFF;
    }

    .wfop_forms_global_settings {
        padding: 30px 20px;
        min-height: 404px;
        position: relative;
    }

    div#wfop_global_settings .wfop_vue_forms .bwf_form_submit {
        position: absolute;
        left: 0;
        bottom: 20px;
        float: none !important;
        padding-left: 0;
    }

    .wfacp-product-widget-container.wfop_optimise_global_setting .bwf_form_submit {
        position: absolute;
        left: 0;
        bottom: 20px;
        float: none !important;
        padding-left: 0;
    }

    .wfacp-product-widget-container.wfop_optimise_global_setting .wfop_settings_sections {
        padding: 0;
        background: transparent;
    }

    .wfacp-product-widget-container.wfop_optimise_global_setting {
        padding: 25px 40px 20px;
        background: #fff;
        position: relative;
    }

    .wfacp-product-widget-container.wfop_optimise_global_setting button.wfop_save_btn_style {
        margin: 0;
    }

    .wfop_forms_global_settings .vue-form-generator .field-label span {
        margin-left: 0px;
    }


    .wfop_izimodal_default {
        z-index: 999999999 !important;
        overflow: hidden;
        background: #fff;
    }

    /*
.iziModal.hasScroll .iziModal-wrap{
overflow: inherit!important;
}*/

    .wc-backbone-modal-main footer .inner {
        text-align: center !important;
    }


    .wfop_checkout_url .field-wrap .wrapper:before {
        font-size: 16px;
        line-height: 24px;
        color: #b4b4b4;
        padding: 10px 12px;
        background-color: #fff;
        border: 1px solid #d5d5d5;
        box-shadow: none;
        /* display: block; */
        /* width: 100%; */
        /* max-width: 100%; */
        margin: 0;
        height: auto;
        border-radius: 0;
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
        -ms-border-radius: 0;
        background: #f0f0f0;
    }


    .wfacp-fp-offer-products p {
        margin: 10px 0 6px 0 !important;
        padding: 8px 6px;
        box-shadow: 0px 0px 2px #e8e8e8;
        font-size: 14px;
    }

    .wfacp-fp-offer-products .wfop_popup_table_wrap p {
        margin: 0 0 12px !important;
    }

    .wfacp-fp-offer-products .wfop_popup_table_wrap p:last-child {
        margin-bottom: 0 !important;
    }

    .wfacp-fp-offer-products {
        padding-bottom: 30px;
    }


    #modal-global-settings-form .wfop_vue_forms fieldset .bwf_form_submit {
        float: right;
    }

    #wfop_optimization_container .vue-form-generator select {
        display: block;
        padding: 6px 12px;
        font-size: 14px;
        width: 25em;
        line-height: 1.5;
        color: #555;
        height: auto;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .wfop_checkout_url_disabled_url input {
        font-size: 16px;
        line-height: 24px;
        color: #b4b4b4;
        padding: 10px 12px;
        background-color: #f0f0f0;
        border: 1px solid #d5d5d5;
        box-shadow: none;
        display: block;
        width: 100%;
        max-width: 100%;
        margin: 0;
        height: auto;
        border-radius: 0;
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
        -ms-border-radius: 0;
    }

    form#add-new-form .wfop_checkout_url_disabled_url input {
        background: #fff;
    }

    /* Selected designed */
    .wfop_templates_inner.wfop_selected_designed {
        padding: 0 25px;
        box-sizing: border-box;
    }

    .wfop_selected_designed .wfop_temp {
        font-weight: 300;
    }

    .wfop_selected_designed .wfop_template_sec {
        box-shadow: 0 0 5px #e0e0e0;
        margin: 0 auto;
        text-align: center;
        width: 264px;
        min-height: 240px;
    }

    .wfop_selected_designed .wfop_template_sec:hover .wfop_temp_overlay {
        opacity: 1;
        cursor: pointer;

    }


    .wfop_selected_designed .wfop_templates_design {
        width: 50%;
        float: left;
    }

    .wfop_selected_designed .wfop_temp_overlay {
        opacity: 1;
        transition: border .7s ease-in-out;
        cursor: pointer;
        border: 2px dashed #b5b5b5;
        background: #fff;
    }

    .wfop_body hr {
        border-color: #f1f1f1;
        border-bottom: none;
        background: #ffffff;
        height: 1px;
    }

    .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch:hover .wfop_temp_overlay {
        background: transparent;
    }


    .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch .wfop_temp_overlay .wfop_p {
        padding: 11px 20px;
        border-radius: 4px;
        display: inline-block;
    }

    .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch:hover .wfop_temp_overlay .wfop_p {
        color: #fff;
        background-color: #80c63c;
    }

    .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch:hover .wfop_temp_overlay .wfop_steps_btn_add, .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch:hover .wfop_temp_overlay .wfop_steps_btn_add span {
        border-color: #fff;
        color: #fff;
    }

    .wfop_tab-content .wfop_pick_template .wfop_build_from_scratch:hover .wfop_temp_overlay .wfop_p:hover {
        background: #5ba238;
    }

    .wfop_templates_inner.wfop_selected_designed.wfop_template_importing_process {
        background: red;
        position: relative;
    }

    .wfop_template_importing_loader {
        height: 100%;
        background: #fff;
        text-align: center;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
    }

    .wfop_template_importing_loader .spinner {
        margin-top: 41%;
        visibility: visible;
        width: 20px;
        float: none;
    }

    .wfop_templates_inner.wfop_selected_designed .wfop_template_importing_loader .spinner {
        margin-top: 0;
        visibility: visible;
        width: 20px;
        float: none;
        margin: 0;
        left: 0;
        right: 0;
        margin-top: 0;
        margin: 0 auto;
        position: absolute;
        top: 50%;
        margin-top: -10px;

    }

    /* SHORT CODES DESIGNS */
    .wfop_shortcodes_designs {
        margin-top: 0;
    }

    .wfop_form_templates_outer .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-product-tabs-content-wrapper {
        width: 100%;
    }

    .wfop_form_templates_outer .wfacp-short-code-wrapper {
        background: #f1f1f1;
        padding-top: 50px;
    }

    #wfop_design_container .wfop_form_templates_outer .wfacp-short-code-wrapper .wfop_fsetting_table_head {
        background: #f6f9fb;
        border: 0;
    }

    #wfop_design_container .wfop_form_templates_outer .wfacp-short-code-wrapper .wfacp-fsetting-header {
        font-size: 15px;
        font-weight: 400;
        line-height: 1.5;
    }

    .wfop_fsetting_table_head.wfop_shotcode_tab_wrap {
        padding-top: 0;
        padding-bottom: 0;
    }

    .wfacp-short-code-wrapper .wfacp-product-widget-tabs .wfacp-tab-title.wfacp-active {
        color: #454545;
        border-color: #d5dce2;
        background: #fff;
        width: 100%;
        margin: -1px 0 0 0;
    }

    .wfacp-short-code-wrapper .wfacp-scodes-row .wfacp-scodes-label {
        display: block;
    }

    .wfop_shortcodes_designs .wfacp-scodes-row .wfacp-scodes-value {
        display: block;
        width: 100%;
        border: 0px;
        padding: 8px 0;
    }

    .wfop_shortcodes_designs input[type=checkbox],
    .wfop_shortcodes_designs input[type=color],
    .wfop_shortcodes_designs input[type=date],
    .wfop_shortcodes_designs input[type=datetime-local],
    .wfop_shortcodes_designs input[type=datetime],
    .wfop_shortcodes_designs input[type=email],
    .wfop_shortcodes_designs input[type=radio],
    .wfop_shortcodes_designs input[type=search],
    .wfop_shortcodes_designs input[type=tel],
    .wfop_shortcodes_designs input[type=text],
    .wfop_shortcodes_designs input[type=time],
    .wfop_shortcodes_designs select,
    .wfop_shortcodes_designs textarea {
        width: 100%;
        border: 1px solid #dfdfdf;
        background: #fff;
        height: 38px;
        padding: 0 10px;
        color: #878787;
    }

    .wfop_shortcodes_designs .wfacp-scodes-row .wfacp-scodes-value-in {
        padding-right: 0;
    }

    .wfop_shortcodes_designs .vue-form-generator fieldset legend {
        padding-left: 0;
        padding-bottom: 0;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.5;
    }

    .wfop_shortcodes_designs .wfacp-scodes-row {
        padding: 10px 0;
    }

    .wfop_shortcodes_designs .wfop_forms_global_settings {
        padding: 30px;
    }

    .wfop_shortcodes_designs .wfacp-scodes-row a.wfop_copy_text {
        position: absolute;
        right: 10px;
        z-index: 2;
        color: #0073aa;
        font-size: 14px;
        padding: 0 5px;
        top: 0;
        line-height: 38px;
        left: 10px;
        text-align: right;
    }

    .wfop_shortcodes_designs .wfacp-scodes-row .wfacp-scodes-label {
        font-weight: 400;
        color: #878787;
        font-size: 14px;
        margin: 0 0 -2px 0;
        line-height: 1.5;
    }

    .wfacp-short-code-wrapper .wfacp-product-tabs-content-wrapper .wfop_vue_forms {
        padding: 25px 30px;
        border-left: 1px solid #d5dce2;
    }

    .wfacp-short-code-wrapper .wfop_global_settings_wrap .wfacp-product-tabs-view-vertical {
        margin-bottom: 0;
    }

    .wfop_global_settings .wfop_funnels_listing .wp-heading-inline {
        margin-top: 15px;
        font-size: 26px;
    }

    .wfop_shortcode .wfop_shortcode_inner {
        margin: 0 0 20px;
    }

    .wfop_shortcode_inner:after {
        clear: both;
    }

    .wfop_shortcode_inner:after,
    .wfop_shortcode_inner:before {
        content: '';
        display: block;
    }

    a.wfop_copy_text {
        float: right;
    }


    .wfop_copy_text_wrap a {
        line-height: 1.5;
    }


    /* Import and Export CSS*/
    .wfop_flex_import_page {
        width: 730px;
        background: #fff;
        margin: 0 auto;
        text-align: center;
        padding: 110px 25px;
        margin-top: 115px;
    }

    .wfop_import_head {
        font-size: 20px;
        color: #000;
    }

    .wfop_import_para {
        font-size: 15px;
        color: #878787;
    }

    .wfop_flex_import_page input[type='file'] {
        margin-left: 65px;
    }

    .wf_funnel_clear_10 {
        clear: both;
        height: 10px;
    }

    .wf_funnel_clear_20 {
        clear: both;
        height: 20px;
    }

    .wf_funnel_clear_30 {
        clear: both;
        height: 30px;
    }

    .wf_funnel_clear_40 {
        clear: both;
        height: 40px;
    }

    .wf_funnel_btn_primary {
        background: #0073AA;
        color: #fff;
        border: none;
    }

    .wf_funnel_btn_primary:hover {
        background: #035a84;
        color: #fff;
    }

    .wf_funnel_btn_primary, .wf_funnel_btn_success, .wf_funnel_btn_grey_border {
        color: #fff;
    }

    .wf_funnel_btn_success:hover {
        color: #fff;
        background: #45b157;
        border-color: transparent;
    }

    .wf_funnel_btn:focus {
        box-shadow: none;
        outline: none;
        color: #fff;
    }

    .wf_funnel_btn_grey {
        background-color: #5C5C5C;
        color: #fff;
    }

    .wf_funnel_btn_grey:hover {
        background: #2b2b2b;
        color: #fff;
    }
    .wffn_swl_btn .wfop_btn{
		margin:0;
    }

    .wffn_swl_btn .wfop_btn.wf_cancel_btn{
        border: 1px solid transparent !important;
        color:  #5F6381;
        background: #fff;
    }

    .wfop_export .postbox, .wfop_import .postbox {
        background: none;
        box-shadow: none;
        border: none;
    }

    .wfacp-short-code-wrapper #wfop_global_setting_vue:after,
    .wfacp-short-code-wrapper #wfop_global_setting_vue:before {
        content: '';
        display: block;
    }

    .wfacp-short-code-wrapper #wfop_global_setting_vue:after {
        clear: both;
    }

    .wfacp-short-code-wrapper .wfacp-product-widget-tabs.wfacp-product-tabs-view-vertical .wfacp-product-tabs-wrapper,
    .wfacp-short-code-wrapper .wfacp-product-tabs-view-vertical .wfacp-product-widget-container {
        min-height: 250px;
    }

    .wfacp-short-code-wrapper .wfacp-product-tabs-content-wrapper .wfop_vue_forms {
        border: none;
    }


    .wfacp-short-code-wrapper .wfacp-product-tabs-view-vertical .wfacp-product-widget-container .wfacp-scodes-label {
        display: block;
        width: auto;
    }

    .swal2-loading .loading-text {
        display: inline-block;
        width: 100%;
        margin-left: 7px;
        font-size: 20px;
    }

    .wfop_template_section_message_box_wrap {
        text-align: center;
    }

    .wfop_template_section_message_box {
        background: #fdfdfd;
        border: 1px solid #ffd15c;
        padding: 8px 20px;
        text-align: center;
        width: auto;
        display: inline-block;
        margin: 30px auto 10px;
    }

    .wfop_template_section_message_box .wfop_msg_section_p {
        font-size: 14px;
        font-weight: 300;
        color: #000;
        line-height: 1.5;
    }

    td.wfop_product_drag_handle .wfop_drag_handle {
        cursor: move;
    }

    .wfop_selected_designed .wfop_build_from_scratch {
        margin-bottom: 126px;
    }


    .form-group.valid.autopopulate_state_service.field-radios .field-wrap label {
        display: block;
        width: 150px !important;
    }


    /*
Billing and shipping address
*/
    .wfop_billing_row {
        margin: 0 0 15px 0;
        width: 100%;
        box-sizing: border-box;
    }

    .wfop_billing_label label {
        font-size: 16px;
        line-height: 24px;
        color: #414141;
        display: block;
        margin-bottom: 7px;
        cursor: default;
    }

    .wfop_billing_label.required label:after {
        content: "*";
        font-weight: 400;
        color: red;
        padding-left: .2em;
        font-size: 1em;
    }

    .wfop_billing_row .wfop_billing_field-wrap .wrapper input[type='text'], .wfop_vue_forms .form-group textarea, .wfop_vue_forms .form-group select, .wfop_vue_forms .form-group textarea {
        font-size: 13px;
        line-height: 24px;
        color: #444;
        padding: 9px 12px;
        background-color: #fff;
        border: 1px solid #d5d5d5;
        box-shadow: none;
        display: block;
        width: 100%;
        max-width: 100%;
        margin: 0;
        height: auto;
        border-radius: 0;
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
        -ms-border-radius: 0;
        box-sizing: border-box;
    }

    .wfop_billing_col_3 {
        width: 33.33%;
        float: left;
        padding: 0 10px;
        box-sizing: border-box;
    }

    .wfop_billing_col_3 .fields_head {
        margin-left: 5px;
    }

    .wfop_billing_clear {
        clear: both;
    }

    .wfop_negative_margin_row {
        margin-left: -5px;
        margin-right: -5px;
        width: auto;
        padding-top: 16px;
        clear: both;;
    }


    /* SWITCHER */


    .wfop_billing_content .field-switch label {
        width: 36px;
        height: 18px;
        border-radius: 30px;
    }

    .field-wrap.field-switch {
        width: 36px;
        height: 18px;
        position: relative;
    }

    .wfop_billing_content .field-switch input {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;

    }

    .wfop_billing_content .field-switch input:checked ~ .label {
        background: #8dc73f;
    }

    .field-switch input:checked ~ .label, .field-switch input:checked ~ .label {
        background: #8dc73f;
    }

    .field-switch input ~ .label, .field-switch input ~ .label {
        background-color: #fc7626;
    }

    #edit-field-form .vue-form-generator [data-on="Active"], #edit-field-form .vue-form-generator [data-on="Inactive"] {
        text-indent: -999999px;
    }

    .field-switch input:checked ~ .label {
        background: #e1b42b;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .15), inset 0 0 3px rgba(0, 0, 0, .2);
    }

    .field-switch .handle, .field-switch .label {
        transition: all .3s ease;
    }

    .field-switch .label {
        position: relative;
        display: block;
        height: inherit;
        font-size: 10px;
        text-transform: uppercase;
        background: #eceeef;
        border-radius: inherit;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .12), inset 0 0 2px rgba(0, 0, 0, .15);
    }

    .field-switch input:checked ~ .label:before {
        opacity: 0;
    }

    .field-switch .label:before, .field-switch .label:after {
        content: "";
    }

    .field-switch .label:before, .field-switch .label:after {
        content: "";
    }

    .field-switch .handle, .field-switch .label {
        transition: all .3s ease;
        cursor: pointer;
    }

    .field-switch .handle {
        top: 0;
        position: absolute;
        width: 18px;
        height: 18px;
        background: linear-gradient(180deg, #fff 40%, #f0f0f0);
        background-image: -webkit-linear-gradient(top, #fff 40%, #f0f0f0);
        border-radius: 100%;
        box-shadow: 1px 1px 5px rgba(0, 0, 0, .2);
    }

    .field-switch .handle:before, .field-switch .handle:before {
        margin: -4px 0 0 -4px;
        width: 8px;
        height: 8px;
    }

    .field-switch .handle:before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -6px 0 0 -6px;
        width: 12px;
        height: 12px;
        background: linear-gradient(180deg, #eee, #fff);
        background-image: -webkit-linear-gradient(top, #eee, #fff);
        border-radius: 6px;
        box-shadow: inset 0 1px rgba(0, 0, 0, .02);
        transition: all .5s;
    }

    .field-switch input:checked ~ .handle, .field-switch input:checked ~ .handle {
        right: 0;
        left: auto;
        top: 0;
    }


    /* ACCORDION */

    .wfop_billing_accordion_inner .accordion_heading {
        display: inline-block;
        font-size: 16px;
        font-weight: 800;
        margin-left: 10px;
    }

    .accordion_left {
        text-align: left;
        display: inline-block;
        width: 80%;
        float: left;
    }

    .wfop_hint_small {
        display: inline-block;
        line-height: 13px;
        margin-left: 5px;
    }

    .wfop_hint_small small {
        font-style: italic;
        font-size: 12px;
        color: #666;
        font-weight: normal;
    }

    .accordion_right {
        text-align: right;
        display: inline-block;
        width: 20%;
        float: left;
    }

    .accordion_right .dashicons-arrow-down,
    .accordion_left i {
        cursor: pointer;
    }

    .accordion_right .wfop_drag_address_icon {
        cursor: move;
        color: #d0cdcd;
    }

    .accordion_right .wfop_drag_address_icon:hover,
    .ui-sortable-helper .wfop_billing_accordion_inner .wfop_drag_address_icon {
        color: #444;
    }


    .accordion_right .wfop_drag_address_icon:before {
        content: "\f333";
    }

    .wfop_billing_accordion_inner {
        color: #31373c;
        border: 1px solid #ececec;
        padding: 16px 18px;
        margin: 10px 0;

    }

    .wfop_billing_accordion_inner:focus {
        outline: none;
        cursor: move !important;
    }

    .wfop_billing_accordion_inner {
        margin: 15px 0 0 0;
        background: #fff;
    }

    .wfop_billing_accordion_inner p {
        margin: 0;
    }

    .accordion_right i {
        margin-left: 10px;
    }

    .wfop_billing_accordion_content {
        display: none;
        background: #fbfbfb;
        padding: 15px 18px;
        border: 1px solid #ececec;
        border-top: 0px;
    }

    .wfop_row_billing {
        width: 100%;
        display: table;
        margin: 0 0 15px 0;
    }

    .wfop_billing_left {
        width: 135px;
        vertical-align: middle;
        display: table-cell;
    }

    .wfop_billing_right {
        width: calc(100% - 135px);
        display: table-cell;
        vertical-align: middle;
    }

    .wfop_billing_left label {
        font-size: 16px;
        line-height: 24px;
        color: #414141;
        display: block;
        margin-bottom: 0;
        cursor: default;
    }

    .wfop_billing_right input[type="text"],
    .wfop_billing_right textarea,
    .wfop_billing_right select {
        font-size: 16px;
        line-height: 24px;
        color: #444;
        padding: 9px 12px;
        background-color: #fff;
        border: 1px solid #d5d5d5;
        box-shadow: none;
        display: block;
        width: 100%;
        max-width: 100%;
        margin: 0;
        height: auto;
        border-radius: 0;
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
        -ms-border-radius: 0;
        box-sizing: border-box;
    }


    .wfop_billing_accordion_inner.wfop_accordion_active .fa-caret-down {
        transform: rotate(180deg);
        transition: all .1s;
    }

    .ui-sortable-helper .wfop_billing_accordion_inner {
        border: 2px dotted #0085BA;

        background: #fff;
    }

    .wfop_billing_accordion_inner .accordion_left i.wfop_drag_address_field_enable.dashicons-hidden {
        opacity: 0.5;
    }

    /* New Styling for edit fields */


    #edit-field-form .wfop_vue_forms .form-group label {
        max-width: 200px;
        float: left;
        display: block;
        margin: 0;
        line-height: 44px;
        font-size: 15px;
    }

    #edit-field-form .wfop_required_cls .field-wrap {
        position: absolute;
        top: 50%;
        display: block;
        max-width: 20px;
        margin: 0;
        margin-top: -12px;
    }


    #edit-field-form .wfop_vue_forms .form-group .field-wrap {
        display: block;
        width: auto;
        padding-left: 210px;
    }

    .wfop_edit_field_wrap {
        padding-bottom: 15px;
    }


    .wfop_edit_field_wrap p {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.5;
    }

    .wfop_edit_field_wrap p span {
        font-style: italic;
        font-size: 12px;
        color: #666;
        font-weight: normal;
    }
    .wfop_shortcodes_designs .vue-form-generator {
        background: transparent;
        border: none;
        padding: 0;
    }

    #edit-field-form .wfop_vue_forms .form-group:after,
    #edit-field-form .wfop_vue_forms .form-group:before {
        content: '';
        display: block;
    }

    #edit-field-form .wfop_vue_forms .form-group:after {
        clear: both;
    }

    #edit-field-form .wfop_vue_forms .form-group:last-child {
        margin-bottom: 0;
    }

    #edit-field-form .wfop_vue_forms .form-group.field-checkbox label {
        padding-left: 0;
    }

    #edit-field-form .wfop_vue_forms .form-group .field-wrap + .hint {
        padding-left: 210px;
        font-weight: normal;
        margin-top: 12px;
    }

    #edit-field-form .form-group.valid.wfop_required_cls.field-checkbox .field-wrap + .hint {
        padding-left: 235px;
    }


    /* for add field */

    #add-field-form .wfop_vue_forms .form-group.field-select label,
    #add-field-form .wfop_vue_forms .form-group.field-input label,
    #add-field-form .wfop_vue_forms .form-group.field-checkbox label,
    #add-field-form .wfop_vue_forms .form-group.field-radios label{
        max-width: 200px;
        float: left;
        display: block;
        margin: 0;
        line-height: 44px;
        position: relative;
        z-index: 5;
        font-size: 13px;
    }

    #add-field-form .wfop_vue_forms .form-group.field-checkbox.wfop_full_width_fields label {
        line-height: 26px;
    }

    #add-field-form .wfop_required_cls.field-select .field-wrap,
    #add-field-form .wfop_required_cls.field-input .field-wrap,
    #add-field-form .wfop_required_cls.field-checkbox .field-wrap {
        position: absolute;
        top: 50%;
        display: block;
        max-width: 20px;
        margin: 0;
        margin-top: -12px;
    }


    #add-field-form .wfop_vue_forms .form-group.field-select .field-wrap,
    #add-field-form .wfop_vue_forms .form-group.field-input .field-wrap,
    #add-field-form .wfop_vue_forms .form-group.field-checkbox .field-wrap,
    #add-field-form .wfop_vue_forms .form-group.field-radios .field-wrap,
    #add-field-form .wfop_vue_forms .form-group.error .errors.help-block{
        display: block;
        width: auto;
        padding-left: 210px;
    }

    #add-field-form .wfop_vue_forms .form-group.error{
		display:block !important;
        padding: 0;
	}

    #add-field-form .wfop_vue_forms .form-group.error .errors.help-block {
        color: #dc3232;
    }

    #add-field-form .wfop_vue_forms .form-group.field-select .field-wrap + .hint,
    #add-field-form .wfop_vue_forms .form-group.field-input .field-wrap + .hint,
    #add-field-form .wfop_vue_forms .form-group.field-checkbox .field-wrap + .hint {
        padding-left: 210px;
    }

    #add-field-form .wfop_vue_forms .form-group.field-radios .field-wrap label {
        float: left;
        width: 100%;
    }

    #add-field-form .wfop_required_cls label {
        padding-left: 0;
    }

    #modal-global-settings-form .vue-form-generator, #wfop_setting_container .vue-form-generator, #wfop_optimization_container .vue-form-generator, #part-add-funnel .vue-form-generator {
        background: #fff;
        padding: 0;
        border: 0;
    }

    .form-group.valid.wfop_initiator_field.field-checkbox {
        display: none;
    }

    .wfop_ps_conditional_wrap .wfop_product_switcher_delete_options {
        margin: 0 0 20px;
    }

    .wfop_ps_conditional_wrap .wfop_product_switcher_delete_options:last-child {
        margin: 0;
    }

    .wfop_ps_conditional_wrap .wfop_product_switcher_delete_options label {
        color: #444;
    }

    /* bottom links */
    .wfop_temp_link_bottom_wrap .wfop_edit_post_links {
        width: 50%;
        float: left;
        text-align: left;
    }

    .wfop_temp_link_bottom_wrap .wfop_template_links {
        width: 50%;
        float: left;
        text-align: right;
    }

    .wfop_temp_link_bottom_wrap a {
        display: inline-block;
        font-size: 14px;
        line-height: 1.5;
    }

    .wfop_temp_link_bottom_wrap .wfop_template_links a {
        border-right: 1px solid #0085BA;
        padding: 0 10px;
    }

    .wfop_temp_link_bottom_wrap .wfop_template_links a:last-child {
        padding-right: 0;
        border: none;
    }

    .wfop_temp_link_bottom_wrap .wfop_template_links a:first-child {
        padding-left: 0;
    }

    #part-add-funnel .vue-form-generator .form-group.required > label:after {
        padding-left: 0px;
    }

    .wfop_template_fields_note_outer {
        margin-top: 15px;
    }

    #wpbody {
        overflow-y: hidden;
    }

    .wfop_filter_container_inner {
        display: inline-block;
        padding: 0;
        border: none;
        border-bottom: 4px solid transparent;
        cursor: pointer;
        font-size: 14px;
        margin-right: 40px;
        padding-bottom: 10px;
    }

    .wfop_filter_container_inner:last-child {
        margin-right: 0;
    }

    .wfop_filter_container {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 10px;
    }

    .wfop_filter_container_inner.wfop_selected_filter {
        border-color: #6dbe45;
    }

    .wfop_filter_container .wfop_template_filters span {
        font-size: 11px;
        color: #e3090a;
        margin-left: 6px;
        font-weight: 500;
    }

    .wfop_address_type_radio_container {
        display: none;
    }

    .wfop_address_type_radio_container.wfop_address_radio_container_show {
        display: block;
    }

    .wfop_single_template {
        display: none;
    }

    .wfop_single_template[data-steps='1'] {
        display: inline-block;
    }

    .wfop_wrap.wfop_box_size.product .wfop_loader {
        width: calc(100% - 0px);
        height: 100%;
    }

    footer.wfop_launch_btn_outer {
        border-top: 1px solid #dddddd !important;
    }

    .wc-backbone-modal-main footer.wfop_launch_btn_outer .inner {
        text-align: right !important;
    }


    body div#wfop_optimization_container .wfop_wrap_inner_offers .form-group label {
        line-height: 42px;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .dropList,
    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox {
        height: auto;
        border: none;
        overflow: unset;
        padding: 0;
        margin: 0;
        box-shadow: none;
        width: 100%;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox:after, body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox:before {
        display: block;
        content: '';
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox:after {
        clear: both;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .dropList .list-row,
    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox .list-row {
        display: inline-block;
        width: auto;
        float: none;
        margin-right: 20px;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .wrapper {
        width: 100%;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .dropList .list-row label,
    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox .list-row label {
        width: auto;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox .list-row > label {
        display: block;
        float: none;
        padding: 0;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers select {
        -webkit-appearance: menulist;
        webkit-appearance: menulist;
        -moz-webkit-appearance: menulist;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .vue-form-generator .field-checklist .listbox .list-row:last-child {
        margin-right: 0;
    }

    body div#wfop_optimization_container .wfop_wrap_inner_offers .form-group.field-textArea textarea {
        width: 50em !important;
        height: 100px;
    }


    @media (max-width: 1366px) {
        .wfop_page_left_wrap table.wp-list-table .column-name {
            width: 25%;
        }

        .wfop_page_left_wrap td.quick_links.column-quick_links {
            width: 42%;
        }

    }

    @media (max-width: 1558px) {
        body .wfop_forms_global_settings .form-group.field-textArea textarea {
            width: 100% !important;
        }


    }

    @media (max-width: 767px) {
        #edit-field-form .wfop_vue_forms .vue-form-generator fieldset:nth-child(2) .form-group.field-switch {
            margin-right: 4%;
        }

        .wfop_global .wfop_page_left_wrap {
            float: none;
            min-height: 500px;
            margin: 0 0 30px;
        }

    }

    @media (max-width: 991px) {

        body .wfop_global .wfop_page_left_wrap {
            float: none;
        }
    }


    #wfop_setting_container .wfop_box_size {
        background: #EBF2F6;
    }

    #wfop_setting_container .wfop_box_size .wfop_wrap_inner.wfop_wrap_inner_offers {
        margin-left: 0;
        margin-top: -18px;
    }

    @media (max-width: 1024px) {
        #edit-field-form.wfop_product_swicther_field_wrap .iziModal-wrap {
            height: 415px !important;
        }
    }

    .wfop_fixed_header .wfop_bread {
        align-items: center;
    }

    .wfop_fixed_header .wfop_bread .wfop_head_m {
        width: auto;
    }

    .bwf_brad_status_btn {
        position: relative;
        margin-left: 10px;
    }

    .bwf_brad_status_btn span[data-status="0"]:before {
        background: #ff0000;
        position: absolute;
        width: 15px;
        height: 15px;
        content: " ";
        border-radius: 50%;
        left: 0;
        margin-top: -8px;
        cursor: pointer;
    }

    .wp-list-table .wfacp-tgl + .wfacp-tgl-btn-small {
        width: 1.7em;
    }

    .form-group.bwf_wrap_custom_html_tracking_general > label {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .form-group.bwf_tracing_general_text {
        width: 545px;
        display: inline-block;
        clear: both;
    }

    .form-group.bwf_tracing_general_text .field-wrap, .form-group.bwf_tracing_general_text .hint {
        padding-left: 0;
        font-size: 14px;
    }

    .template_steps_container .wfop_template_tabs_container {
        display: -webkit-box;
    }

    .template_steps_container .wfop_step_actions {
        display: inherit;
    }

    .wfop_input_fields {
        margin-top: 25px;
    }

    .wfopp_loader {
        position: absolute;
        left: 20px;
        right: 20px;
        height: 100%;
        text-align: center;
        background: #fff;
        z-index: 100;
    }

    .wfopp_loader .spinner {
        visibility: visible;
        margin: auto;
        width: 20px;
        height: 20px;
        background-size: 20px;
        float: none;
        margin-top: 200px;
        background: url(<?php echo esc_url( admin_url('images/spinner.gif')) ?>) no-repeat;
    }

    .bwf_ajax_save_buttons.bwf_form_submit .wfop_save_btn_style {
        position: relative;
    }
    /** sweetalert popup style */
    .swal2-icon {
        transform: scale(0.8);
    }
    .swal2-icon.swal2-success .swal2-success-ring {
        background: #13c37b;
    }
    .swal2-icon.swal2-success .swal2-success-ring:before {
        position: absolute;
        width: calc(100% + 20px);
        height: calc(100% + 20px);
        content: "";
        left: -13px;
        border: 3px solid #007cba;
        border-radius: 50%;
        top: -13px;
    }
    .swal2-header .swal2-icon.swal2-success {
        margin: 0 auto 0.5em;
    }
    .swal2-header .swal2-icon.swal2-success [class^=swal2-success-line] {
        z-index: 3;
        background-color: #fff;
        height: 0.2125em;
    }
    .swal2-header .swal2-icon.swal2-error [class^=swal2-x-mark-line] {
        width: 2.9375em;
        height: 0.2125em;
        background: #fff;
    }
    .swal2-header .swal2-icon.swal2-error .swal2-x-mark {
        background: #e6283f;
        border-radius: 50%;
    }
    .swal2-header .swal2-icon.swal2-error {
        padding: 8px;
        border: 4px solid #007cba;
        margin: 0 auto;
		display:none !important;
    }
    .swal2-container .swal2-popup .swal2-actions {
        gap: 8px;
    }

    .add_field_spinner {
        display:inline-block
    }
    .add_field_spinner .wfop_spinner{
        margin:9px 0px 0;
		display: none;
	}
	.add_field_spinner.show_wfop_spinner .wfop_spinner{
        display: block;
        visibility: visible;
        float: left;
	}
    .add_field_spinner.show_wfop_spinner .wfop_btn_primary{
        pointer-events: none;
	}
    #wp-wfop_wysiwyg_editor-wrap{
		min-height:185px;
	}
    #wp-wfop_wysiwyg_editor-wrap .wp-switch-editor{
		height:auto;
	}


</style>
