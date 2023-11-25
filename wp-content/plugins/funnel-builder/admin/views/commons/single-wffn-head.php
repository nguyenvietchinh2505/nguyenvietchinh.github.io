<?php
/**
 * Section head
 */
/** Registering Settings in top bar */
$section = filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW );

if ( class_exists( 'WFFN_Header' ) ) {
	$header_ins = new WFFN_Header();
	$header_ins->set_back_link( 1, admin_url( 'admin.php?page=bwf&path=/funnels' ) );
	$header_ins->set_level_2_title( __( 'New Funnel', 'funnel-builder' ) );
	$header_ins->set_level_1_navigation_active( 'funnels' );
	$header_ins->set_level_2_post_title(); //used set_level_2_post_title as left inner html
	echo $header_ins->render();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
?>
<div class="wrap wffn-funnel-common">
