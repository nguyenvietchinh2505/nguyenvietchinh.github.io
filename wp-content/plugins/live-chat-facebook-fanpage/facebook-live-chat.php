<?php

/**
 * @link              http://www.themeinthebox.com
 * @since             1.0.0
 * @package           Facebook_Live_Chat
 *
 * @wordpress-plugin
 * Plugin Name:       Live Chat for Facebook Fanpage
 * Plugin URI:        http://www.themeinthebox.it/
 * Description:       Generate a quick Live Chat with the Fanpage.
 * Version:           1.2.1
 * Author:            ThemeintheBox
 * Author URI:        http://www.themeinthebox.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       live-chat-facebook-fanpage
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action('init', 'titb_lcff_onwpinit');

function titb_lcff_onwpinit () {

	add_action('wp_enqueue_scripts', 'titb_lcff_enqueue');

	add_action('wp_footer', 'titb_lcff_front');
	
	add_action( 'wp_head', 'titb_lcff_stylehead' );	

}


// Add settings link on plugin page
function flc_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=fb-live-chat.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'flc_settings_link' );

/* enqueue */

function titb_lcff_enqueue() {

	wp_register_script( 'flc-js', plugins_url( 'assets/js/flc-main.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
	wp_register_style( 'flc-css', plugins_url( 'assets/css/flc-style.css', __FILE__ ), array(), '1.0.0', 'all' );


	/*	Fontello Icons	*/
	wp_register_style( 'flc-fontello', plugins_url( 'assets/css/flc.css', __FILE__ ), array(), '1.0.0', 'all' );

	wp_enqueue_script( 'flc-js' );
	wp_enqueue_style( 'flc-css' );
	wp_enqueue_style( 'flc-fontello' );
}


//$flc_dir = plugin_dir_path( __FILE__ );
/**
 * Load Titan Framework plugin checker
 */
require_once( plugin_dir_path( __FILE__ ) . 'admin/titan-framework-embedder.php' );

/**
 * Load Titan Framework options
 */
require_once ( plugin_dir_path( __FILE__ ) . 'admin/titan-option.php' );



function titb_lcff_stylehead() {
	$titan = TitanFramework::getInstance( 'titb_flc' );
	echo "<style>
		#btn-flc { ". $titan->getOption( 'button_position' ) .": 40px;}
		.popup-box  { ". $titan->getOption( 'button_position' ) .": 20px; }	
	</style>"; 
}


function titb_lcff_front(){

	// Check Comments option
	$titan = TitanFramework::getInstance( 'titb_flc' );
	$titb_flp = $titan->getOption( 'titb_flc_url' );
	$titb_btnlabel = $titan->getOption( 'titb_flc_btnlabel' );
	$titb_titlesize = $titan->getOption( 'titb_flc_titlesize' );
	$titb_btnsize = $titan->getOption( 'button_size' );
	$titb_lang = $titan->getOption( 'titb_flc_language' );

	if(!empty($titb_flp)){



	?>



		<div id="btn-flc" class="round hollow text-center">
			<button id="addClass" class="<?php echo $titb_btnsize; ?>"><span class="flc-facebook-circled"></span> <?php echo $titb_btnlabel; ?> </button>
		</div>

		<div class="popup-box chat-popup" id="qnimate">
			<div class="flc-close">
				<button data-widget="remove" id="removeClass" type="button"><i class="flc-cancel"></i></button>
			</div>

		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/<?php echo $titb_lang; ?>/sdk.js#xfbml=1&version=v2.8&appId=1548919625405563";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div class="fb-page" data-href="<?php echo $titb_flp; ?>" data-tabs="messages" data-small-header="<?php echo $titb_titlesize; ?>" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"></div>
		</div>

	<?php
	}

}
