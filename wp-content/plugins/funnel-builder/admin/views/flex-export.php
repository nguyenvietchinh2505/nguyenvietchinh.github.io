<?php
/**
 * Adding analytics page
 */
defined( 'ABSPATH' ) || exit; //Exit if accessed directly
?>


<div class="">
	<div class="postbox">
		<div class="inside">
			<div class="wffn_flex_import_page wffn_flex_export_page">
				<div class="wffn_import_head"><?php esc_html_e( 'Export All Funnels', 'funnel-builder' ) ?></div>
				<div class="wf_funnel_clear_10"></div>
				<div class="wffn_import_para"><?php echo esc_html( __( 'Note: Designs made using page builders needs to be exported separately.', 'funnel-builder' ) ); ?></div>
				<div class="wf_funnel_clear_10"></div>
				<form method="POST" enctype="multipart/form-data">
					<input type="hidden" name="wffn-action" value="export">
					<div class="wf_funnel_clear_10"></div>
					<p style="margin-bottom:0">
						<input type="hidden" id="wffn-action" name="wffn-action-nonce" value="<?php echo esc_attr( wp_create_nonce( 'wffn-action-nonce' ) ); ?>">
						<input type="submit" name="submit" class="wf_funnel_btn wf_funnel_btn_primary" value="<?php echo esc_html_e( 'Download Export File', 'funnel-builder' ); ?> "></p>
				</form>
			</div>
		</div>
	</div>
</div>
