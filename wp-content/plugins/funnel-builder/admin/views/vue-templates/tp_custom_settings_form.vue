<script id="tp_custom_settings_form" type="text/x-template" xmlns="http://www.w3.org/1999/html">
<div class="wffn-tabs-view-vertical wffn-widget-tabs">
<div class="wffn-tabs-wrapper wffn-tab-center">
  <div class="wffn-tab-title wffn-tab-desktop-title wffn-active" data-tab="1" role="tab" aria-controls="wffn-tab-content-description">
    <?php _e( 'Custom Redirection', 'funnel-builder' ); ?>
  </div>
  <div class="wffn-tab-title wffn-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="2" role="tab" aria-controls="wffn-tab-content-additional_information">
    <?php _e( 'Custom CSS', 'funnel-builder' ); ?>
  </div>
  <div class="wffn-tab-title wffn-tab-desktop-title description_tab " id="tab-title-description" data-tab="3" role="tab" aria-controls="wffn-tab-content-description">
    <?php _e( 'External Script', 'funnel-builder' ); ?>
  </div>
  <?php do_action('wffn_thankyou_settings_tabs'); ?>
</div>

<div class="wffn-tabs-content-wrapper" style="position:inherit">
  <div class="wffn_custom_lp_setting_inner" id="wffn_global_setting">
    <form class="wffn_forms_wrap wffn_forms_global_settings ">
      <fieldset>
        <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
      </fieldset>
      <div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home">
      </div>
    </form>
    <div class="bwf_form_button">
      <span class="wffn_loader_global_save spinner" style="float: left;"></span>
      <button v-on:click.self="onSubmit" class="wffn_save_btn_style"><?php esc_html_e( 'Save Changes', 'funnel-builder' ); ?></button>

    </div>
  </div>
</div>
</div>
</script>