<?php
/*
Plugin Name: Call Now Button
Plugin URI: http://callnowbutton.com
Description: Mobile visitors will see a call now button fixed at the bottom of your site 
Version: 0.2.1
Author: Jerry Rietveld
Author URI: http://www.jgrietveld.com
License: GPL2
*/

/*  Copyright 2013-2017  Jerry Rietveld  (email : jerry@jgrietveld.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
define('CNB_VERSION','0.2.1');
add_action('admin_menu', 'register_cnb_page');
add_action('admin_init', 'cnb_options_init');

$cnb_options = cnb_get_options();

$cnb_options['active'] = isset($cnb_options['active']) ? 1 : 0;
$cnb_options['classic'] = isset($cnb_options['classic']) ? 1 : 0;

$plugin_title = apply_filters( 'cnb_plugin_title',  'Call Now Button');

$cnb_updated = set_basic_options();

add_action( 'admin_enqueue_scripts', 'cnb_enqueue_color_picker' ); // add the color picker

function register_cnb_page() {
	global $plugin_title;
	$page = add_submenu_page('options-general.php', $plugin_title, $plugin_title, 'manage_options', 'call-now-button', 'call_now_settings_page');
	add_action( 'admin_print_styles-' . $page , 'cnb_admin_styling' );
}
function cnb_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'cnb-script-handle', plugins_url('call-now-button.js', __FILE__ ), array( 'wp-color-picker' ), CNB_VERSION, true );
}
function cnb_admin_styling() {
	wp_enqueue_style( 'cnb_styling' );
}
function cnb_options_init() {
	register_setting('cnb_options','cnb');
	wp_register_style( 'cnb_styling', plugins_url('call-now-button.css', __FILE__), false, CNB_VERSION, 'all' );
}
function call_now_settings_page() { 
	global $cnb_options;
	global $plugin_title;
	global $cnb_updated;
	?>
	
	<div class="wrap">	
		<h1>Call Now Button <span class="version">v.<?php echo CNB_VERSION;?></span></h1>
	<!--## NOTIFICATION BARS ##  -->
		<?php 
		// Display notification that the button is active or inactive
		if(!$cnb_options['active']==1) {
			echo '<div class="notice-error notice"><p>The Call Now Button is currently <b>inactive</b>.</p></div>';
		}

		// Display notification that the button is limited to a number of posts/pages
		if($cnb_options['active']==1 && $cnb_options['show'] != "") {
			echo '<div class="notice-error notice">'.
			'<p><span>Appearance of the Button is <b>limited</b><span class="hide-on-mobile"> to specific Posts or Pages</span>. <span class="check-settings">Review settings &raquo;</span></p></div>';
		}

		

		// inform exisiting users about update to the button design
		if($cnb_updated) { ?>
		<div class="notice-warning notice is-dismissible">
			<p>The Call Now Button has a fresh new look! If you prefer the old button, you can <span class="cnb-switch-back">switch back</span> in the Advanced Settings. <a href="http://callnowbutton.com/new-button-design/" target="_blank" class="cnb-external"><i>See the difference &raquo;</i></a></p>
		</div>
		<?php } ?>


<form method="post" action="options.php" class="cnb-container">
	<?php settings_fields('cnb_options'); ?>
	<table class="form-table">
    	<tr valign="top">
			<th scope="row">Button status:</th>
	    	<td class="activated">
	        	<input id="activated" name="cnb[active]" type="checkbox" value="1" <?php checked('1', $cnb_options['active']); ?> /> <label title="Enable" for="activated">Enabled</label> &nbsp; &nbsp; 
	        </td>
	    </tr>
		<tr valign="top"><th scope="row">Phone number:</th>
			<td><input type="text" name="cnb[number]" value="<?php echo $cnb_options['number']; ?>" /></td>
		</tr>
	</table>
    <div id="settings">
		<table class="form-table">
			<tr valign="top"><th scope="row">Button color:</th>
		    	<td><input name="cnb[color]" type="text" value="<?php echo $cnb_options['color']; ?>" class="cnb-color-field" data-default-color="#009900" /></td>
		    </tr>
			<tr valign="top"><th scope="row">Position</th>
		    	<td class="appearance">
		        	<div class="radio-item">
			            <input type="radio" id="appearance1" name="cnb[appearance]" value="right" <?php checked('right', $cnb_options['appearance']); ?>>
			            <label title="right" for="appearance1">Right corner</label>
		        	</div>
		        	<div class="radio-item">
			            <input type="radio" id="appearance2" name="cnb[appearance]" value="left" <?php checked('left', $cnb_options['appearance']); ?>>
			            <label title="left" for="appearance2">Left corner</label>
		        	</div>
		        	<div class="radio-item">
			            <input type="radio" id="appearance3" name="cnb[appearance]" value="middle" <?php checked('middle', $cnb_options['appearance']); ?>>
			            <label title="middle" for="appearance3">Center bottom</label>
		        	</div>
		        	<div class="radio-item">
			            <input type="radio" id="appearance4" name="cnb[appearance]" value="full" <?php checked('full', $cnb_options['appearance']); ?>>
			            <label title="full" for="appearance4">Full bottom</label>
		            </div>
		        </td>
		    </tr>
			<tr valign="top">
				<th scope="row">Click tracking:</th>
				<td> 
				    <div class="radio-item">
					    <input id="tracking3" type="radio" name="cnb[tracking]" value="0" <?php checked('0', $cnb_options['tracking']); ?> /> 
					    <label for="tracking3">Disabled</label><br />
					</div>
					<div class="radio-item">
						<input id="tracking1" type="radio" name="cnb[tracking]" value="2" <?php checked('2', $cnb_options['tracking']); ?> /> 
						<label for="tracking1">Google Universal Analytics (analytics.js)</label><br />
				    </div>
				    <div class="radio-item">
					    <input id="tracking2" type="radio" name="cnb[tracking]" value="1" <?php checked('1', $cnb_options['tracking']); ?> /> 
					    <label for="tracking2">Google Classic Analytics (ga.js)</label>
				    </div>
					<p class="description">Click tracking turned on? Wait for about a day then log into your Google Analytics accunt and click in the <strong>Behavior</strong> section on <strong>Events</strong>. <span class="whatsThis">(<a href="https://support.google.com/analytics/answer/1033068#SeeAlerts" target="_blank">What's this?</a>)</span></p>
				</td>
			</tr>
			<tr valign="top" class="appearance">
				<th scope="row">Limit appearance:</th>
				<td>
					<input type="text" name="cnb[show]" value="<?php echo $cnb_options['show']; ?>" />
					<p class="description">Enter IDs of the posts &amp; pages the Call Now Button should appear on (leave blank for all).</p>
				</td>
			</tr>
			<tr valign="top" class="classic">
				<th scope="row">Back to old button design:</th>
		    	<td>
		        	<input id="classic" name="cnb[classic]" type="checkbox" value="1" <?php checked('1', $cnb_options['classic']); ?> /> <label title="Enable" for="classic">Old button <span class="inline-helper">- <a href="http://callnowbutton.com/new-button-design/" target="_blank" class="cnb-normal"><i>What's the difference?</i></a></span></label>
		        </td>
			</tr>
		</table>
	</div><!--#settings-->
	<input type="hidden" name="cnb[version]" value="<?php echo CNB_VERSION; ?>" />
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	<div id="cnb_settings">+ Advanced settings</div>
</form>

		<div class="feedback-collection">
			<div class="cnb-clear"></div>
  			<p class="cnb-url cnb-center"><a href="http://callnowbutton.com" target="_blank">callnowbutton.com</a></p>

	        <hr>
	  		<p class="cnb-center cnb-spacing">
	  			<a href="http://callnowbutton.com/support/" target="_blank" title="Support">Support</a> &middot;
	        	<a href="http://callnowbutton.com/feature-request/" target="_blank" title="Feature Requests">Suggestions</a> &middot; 
	        	<a href="http://callnowbutton.com/praise/" target="_blank" title="Praise">Just say thanks :-)</a>
	        </p>
	        <!--// Display notification about the testing program -->
			<div class="postbox cnb-alert-box cnb-center">
				<p>We're looking for testers for the Call&nbsp;Now&nbsp;Button&nbsp;<b>Pro</b>! 
					<a class="cnb-external" href="http://callnowbutton.com/join-test-team/" rel="help" target="_blank">Join us here &raquo;</a>
				</p>
			</div>
			
	        <hr>
	        <div class="donate cnb-center">
	            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	                <input type="hidden" name="cmd" value="_s-xclick">
	                <input type="hidden" name="hosted_button_id" value="Q82GBVSERC9AW">
	                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	            </form>
	        </div><!--.donate-->
	    </div>
    </div>
<?php }
if(get_option('cnb') && !is_admin()) {
	
	// Color functions to calculate borders
	function changeColor($color, $direction) {
		if(!preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $color, $parts));
		if(!isset($direction) || $direction == "lighter") { $change = 45; } else { $change = -50; }
		for($i = 1; $i <= 3; $i++) {
		  $parts[$i] = hexdec($parts[$i]);
		  $parts[$i] = round($parts[$i] + $change);
		  if($parts[$i] > 255) { $parts[$i] = 255; } elseif($parts[$i] < 0) { $parts[$i] = 0; }
		  $parts[$i] = dechex($parts[$i]);
		} 
		$output = '#' . str_pad($parts[1],2,"0",STR_PAD_LEFT) . str_pad($parts[2],2,"0",STR_PAD_LEFT) . str_pad($parts[3],2,"0",STR_PAD_LEFT);
		return $output;
	}
	
	
	$cnb_options = get_option('cnb');
	if(isset($cnb_options['active'])) $enabled = $cnb_options['active']; else $enabled = 0;
	if($enabled == '1') {
		// it's enabled so put footer stuff here
		function cnb_head() {
			$cnb_options = get_option('cnb');
			if(isset($cnb_options['classic'])) $classic = $cnb_options['classic']; else $classic = 0;
			$credits 	 = "\n<!-- Call Now Button ".CNB_VERSION." by Jerry Rietveld (callnowbutton.com) -->\n";
			$ButtonExtra = "";
			if($classic == 1) { 

			// OLD BUTTON DESIGN			
				if($cnb_options['appearance'] == 'full' || $cnb_options['appearance'] == 'middle') {
					$ButtonAppearance = "width:100%;left:0;";
					$ButtonExtra = "body {padding-bottom:60px;}";				
				} 
				elseif($cnb_options['appearance'] == 'left') { $ButtonAppearance = "width:100px;left:0;border-bottom-right-radius:40px; border-top-right-radius:40px;"; }
				else { $ButtonAppearance = "width:100px;right:0;border-bottom-left-radius:40px; border-top-left-radius:40px;";	}
				
				$credits .= "<style>#callnowbutton {display:none;} @media screen and (max-width:650px){#callnowbutton {display:block; ".$ButtonAppearance." height:80px; position:fixed; bottom:-20px; border-top:2px solid ".changeColor($cnb_options['color'],'lighter')."; background:url(data:image/svg+xml;base64,".svg(changeColor($cnb_options['color'], 'darker') ).") center 2px no-repeat ".$cnb_options['color']."; text-decoration:none; box-shadow:0 0 5px #888; z-index:9999;background-size:58px 58px}".$ButtonExtra."}</style>\n";

			} else {

			// NEW BUTTON DESIGN
				$credits = "\n<!-- Call Now Button ".CNB_VERSION." by Jerry Rietveld (callnowbutton.com) -->\n";
				$ButtonShape = "width:65px; height:65px; border-radius:80px; border:2px solid #fff; bottom:15px;";
				if($cnb_options['appearance'] == 'full') {
					$ButtonAppearance = "width:100%;left:0;bottom:0;height:60px;border-top:1px solid ".changeColor($cnb_options['color'], 'lighter')."; border-bottom:1px solid ".changeColor($cnb_options['color'], 'darker').";";
					$ButtonExtra = "body {padding-bottom:60px;}";
				}			
				elseif($cnb_options['appearance'] == 'left'  ) { $ButtonAppearance = $ButtonShape . "left:20px;"; }
				elseif($cnb_options['appearance'] == 'middle') { $ButtonAppearance = $ButtonShape . "left:50%; margin-left:-33px;"; }
				else 									   { $ButtonAppearance = $ButtonShape . "right:20px;"; }

				$credits = $credits ."<style>";
				$credits .= "#callnowbutton {display:none;} @media screen and (max-width:650px){#callnowbutton {display:block; position:fixed; text-decoration:none; z-index:9999;";
				$credits .= $ButtonAppearance;
				$credits .= "background:url(data:image/svg+xml;base64,".svg(changeColor($cnb_options['color'], 'darker') ).") center/50px 50px no-repeat ".$cnb_options['color'].";";
				$credits .= "}" . $ButtonExtra . "}";
				$credits .= "</style>\n";
			}	
			echo $credits;
		}
		add_action('wp_head', 'cnb_head');
		
		function cnb_footer() {
			$alloptions = get_option('cnb');
						
			if(isset($alloptions['show']) && $alloptions['show'] != "") {
				$show = explode(',', str_replace(' ', '' ,$alloptions['show']));
				$limited = TRUE;
			} else {
				$limited = FALSE;
			}
			
			if($alloptions['tracking'] == '1') {
				$tracking = "onclick=\"_gaq.push(['_trackEvent', 'Contact', 'Call Now Button', 'Phone']);\""; 
			} elseif($alloptions['tracking'] == '2') {
				$tracking = "onclick=\"ga('send', 'event', 'Contact', 'Call Now Button', 'Phone');\""; 
			} else {
				$tracking = "";
			}

			$callLink = '<a href="tel:'.$alloptions['number'].'" id="callnowbutton" '.$tracking.'>&nbsp;</a>';
			
			if($limited) {
				if(is_single($show) || is_page($show)) {
					echo $callLink;
				}
			} else {
				echo $callLink;
			}
		}
		add_action('wp_footer', 'cnb_footer');
	}
} 

function cnb_get_options() { // Checking and setting the default options
	if(!get_option('cnb')) {
		$default_options = array(
							  'active',
							  'number' => '',
							  'color' => '#009900',
							  'appearance' => 'right',
							  'tracking' => 0,
							  'show' => '',
							  'version' => CNB_VERSION
							  );
		add_option('cnb',$default_options);
		$cnb_options = get_option('cnb');
	} 
	
	$cnb_options = get_option('cnb');
	
	return $cnb_options;
}
function set_basic_options() {
	if(!array_key_exists('version', get_option('cnb'))) {
		$cnb_options = get_option('cnb');
		$cnb_options['active'] = isset($cnb_options['active']) ? 1 : 0;
		$default_options = array(
							  'active' => $cnb_options['active'],
							  'number' => $cnb_options['number'],
							  'color' => $cnb_options['color'],
							  'appearance' => $cnb_options['appearance'],
							  'tracking' => $cnb_options['tracking'],
							  'show' => $cnb_options['show'],
							  'version' => CNB_VERSION
							  );
		update_option('cnb',$default_options);
		return true;  // plugin was updated
	} else {
		return false; // no update
	}
}
function svg($color2) {
	$phone1 = '<path d="M7.104 14.032l15.586 1.984c0 0-0.019 0.5 0 0.953c0.029 0.756-0.26 1.534-0.809 2.1 l-4.74 4.742c2.361 3.3 16.5 17.4 19.8 19.8l16.813 1.141c0 0 0 0.4 0 1.1 c-0.002 0.479-0.176 0.953-0.549 1.327l-6.504 6.505c0 0-11.261 0.988-25.925-13.674C6.117 25.3 7.1 14 7.1 14" fill="'.$color2.'"/><path d="M7.104 13.032l6.504-6.505c0.896-0.895 2.334-0.678 3.1 0.35l5.563 7.8 c0.738 1 0.5 2.531-0.36 3.426l-4.74 4.742c2.361 3.3 5.3 6.9 9.1 10.699c3.842 3.8 7.4 6.7 10.7 9.1 l4.74-4.742c0.897-0.895 2.471-1.026 3.498-0.289l7.646 5.455c1.025 0.7 1.3 2.2 0.4 3.105l-6.504 6.5 c0 0-11.262 0.988-25.925-13.674C6.117 24.3 7.1 13 7.1 13" fill="#fff"/>';
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60">' . $phone1 . '</svg>';
	return base64_encode($svg);
}
function buttonActive() {
	$cnb_options = get_option('cnb');
	if(isset($cnb_options['active'])) { $output = true; } else { $output = false; }
	return $output;
}
?>
