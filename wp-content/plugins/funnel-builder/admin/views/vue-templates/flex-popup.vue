<script id="flex_popup_template" type="text/x-template" xmlns="http://www.w3.org/1999/html">
<div class="popup-wrap">
<div class="placeholder_img" style="min-height: 380px;"><img src="<?php echo esc_url( $this->get_admin_url() ) . '/assets/img/readiness-loader.gif'?>"></div>
<div class="info-wrap" v-if="`info`===popup.popup_type||`loader`===popup.popup_type||`alert`===popup.popup_type">
  <div class="wf_funnel_pop_body">
    <div class="bwfabt_row">
      <div v-if="`loader`==popup.popup_type" class="wf_funnel_center_align">
        <img v-bind:src="popup.img_url">
      </div>
      <div v-if="`info`===popup.popup_type||`alert`===popup.popup_type" class="wf_funnel_center_align">
        <div class="wf_funnel_clear_40"></div>
        <div v-html="popup.icon"></div>
        <div class="wf_funnel_clear_20"></div>
      </div>
    </div>
    <div class="wf_funnel_clear_20"></div>
    <div class="wf_funnel_center_align">
      <div class="wf_funnel_h3"> {{ decodeHtml(popup.title) }}</div>
      <div class="wf_funnel_p"> {{ decodeHtml(popup.subtitle) }}</div>
    </div>
    <div v-if="`alert`===popup.popup_type" class="wf_funnel_clear_20"></div>
    <div v-if="`alert`===popup.popup_type" class="wf_funnel_center_align">
      <a href="javascript:void(0);" v-bind:class="'' !== popup.cta_class?popup.cta_class:`wf_funnel_btn_danger `" class=" wf_funnel_btn wf_funnel_btn_success" v-on:click="popup.cta_call(popup)">
        {{ decodeHtml(popup.submit_btn) }} </a>
      <a href="javascript:void(0);" class=" wf_funnel_btn wf_funnel_btn_grey" v-on:click="closePopup()"> Close </a>
    </div>

    <div class="wf_funnel_clear_30"></div>
  </div>
</div>

<div v-if="`add_form`===popup.popup_type" class="step_wrap">
  <flex-add-step-form v-bind:popup="popup"></flex-add-step-form>
</div>

<!-----  NEXT STEP MODAL  ------->
<div v-if="`choose_step_popup`===popup.popup_type" class="step_wrap">
  <div class="wf_funnel_popup_header">
    <div class="wf_funnel_pop_title">{{ decodeHtml(popup.popup_title) }}</div>
    <button data-iziModal-close class="icon-close wf_funnel_popup_close"><span class="dashicons dashicons-no-alt"></span>
    </button>
  </div>
  <div class="wf_funnel_pop_body">
    <div class="bwfabt_row">
      <flex-step-list v-bind:popup="popup"></flex-step-list>
    </div>
  </div>
</div>
<!-----  NEXT STEP MODAL  ------->
<div v-if="'' !== popup.html" v-html="popup.html"></div>

</div>
</script>