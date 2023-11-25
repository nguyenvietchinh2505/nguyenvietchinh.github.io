<?php
/**
 * Adding contact page
 */
defined( 'ABSPATH' ) || exit; //Exit if accessed directly
$funnel    = WFFN_Core()->admin->get_funnel();
$funnel_id = $funnel->get_id();
?>
<script>
    var bwf_admin_logo = '<?php echo esc_url(plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/bwf-icon-white-bg.svg'); ?>';
</script>
<div id="wffn-contacts" class="wffn-page"></div>