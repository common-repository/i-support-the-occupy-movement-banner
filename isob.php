<?php
/*
Plugin Name: Support OCCUPY WALL STREET banner
Plugin URI: http://occupybanner.wordpress.com
Description: Display a small banner in support of Occupy Wall Street/Occupy Everywhere
Version: 1.2
Author: Jeff Couturier
Author URI: http://jeffcouturier.com/occupybanner/
License: GPL2
*/

/*  Copyright 2011  Jeff Couturier  (email : me@jeffcouturier.com)

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

// some definition we will use
define( 'ISOB_PUGIN_NAME', 'Support Occupy Wall Street banner');
define( 'ISOB_PLUGIN_DIRECTORY', 'i-support-occupy-banner');
define( 'ISOB_CURRENT_VERSION', '1.2' );
define( 'ISOB_CURRENT_BUILD', '1' );
define( 'ISOB_LOGPATH', str_replace('\\', '/', WP_CONTENT_DIR).'/isob-logs/');
define( 'ISOB_DEBUG', false);		# never use debug mode on productive systems


// create custom plugin settings menu
add_action( 'admin_menu', 'isob_create_menu' );

//call register settings function
add_action( 'admin_init', 'isob_register_settings' );


register_activation_hook(__FILE__, 'isob_activate');
register_deactivation_hook(__FILE__, 'isob_deactivate');
register_uninstall_hook(__FILE__, 'isob_uninstall');

// activating the default values
function isob_activate() {
	add_option('isob_option_1', '1');
	add_option('isob_option_2', '1');
}

// deactivating
function isob_deactivate() {
	// needed for proper deletion of every option
	delete_option('isob_option_1');
	delete_option('isob_option_2');	
}

// uninstalling
function isob_uninstall() {
	# delete all data stored
	delete_option('isob_option_1');
	delete_option('isob_option_2');	
	// delete log files and folder only if needed
	if (function_exists('isob_deleteLogFolder')) isob_deleteLogFolder();
}

add_action('wp_footer','showBanner');	


function isob_create_menu() {

	// create new top-level menu
	add_menu_page( 
	__('Occupy Banner', EMU2_I18N_DOMAIN),
	__('Occupy Banner', EMU2_I18N_DOMAIN),
	0,
	ISOB_PLUGIN_DIRECTORY.'/isob_settings_page.php',
	'',
	plugins_url('/images/icon.png', __FILE__));
	
	
	add_submenu_page( 
	ISOB_PLUGIN_DIRECTORY.'/isob_settings_page.php',
	__("HTML Title", EMU2_I18N_DOMAIN),
	__("Settings", EMU2_I18N_DOMAIN),
	0,
	ISOB_PLUGIN_DIRECTORY.'/isob_settings_page.php'
	);	

	// or create options menu page
	add_options_page(__('HTML Title 3', EMU2_I18N_DOMAIN), __("Menu title 3", EMU2_I18N_DOMAIN), 9,  ISOB_PLUGIN_DIRECTORY.'/isob_settings_page.php');

	// or create sub menu page
	$parent_slug="index.php";	# For Dashboard
	#$parent_slug="edit.php";		# For Posts
	// more examples at http://codex.wordpress.org/Administration_Menus
	add_submenu_page( $parent_slug, __("HTML Title 4", EMU2_I18N_DOMAIN), __("Menu title 4", EMU2_I18N_DOMAIN), 9, ISOB_PLUGIN_DIRECTORY.'/isob_settings_page.php');
}


function isob_register_settings() {
	//register settings
	register_setting( 'isob-settings-group', 'isob_option_1' );
	register_setting( 'isob-settings-group', 'isob_option_2' );
}

// check if debug is activated
function isob_debug() {
	# only run debug on localhost
	if ($_SERVER["HTTP_HOST"]=="localhost" && defined('EPS_DEBUG') && EPS_DEBUG==true) return true;
}

function showBanner() {
	$banner_orientation = get_option('isob_option_1');
	if ($banner_orientation == '1') {
		$bo = 'left';
	} else {
		$bo = 'right';
	}
	
	$banner_url = get_option('isob_option_2');
	if ($banner_url == '1'){
		$bu = 'http://occupybanner.wordpress.com';
	} else if ($banner_url == '2') {
		$bu = 'http://occupywallst.org';
	} else if ($banner_url == '3') {
		$bu = 'http://occupytogether.org';
	}
	?>
<!-- // I Support The Occupy Movement banner project: banner and script by @jeffcouturer / jeffcouturier.com/occupybanner/ -->
<script type="text/javascript">
	function occupySwap(whichState) {
		if (whichState==1) {
			document.getElementById('occupyimg').src="<?php echo plugins_url( 'i-support-occupy-banner/images/isupportoccupy-'.$bo.'-blue.png' , dirname(__FILE__) ); ?>";
		} else {
			document.getElementById('occupyimg').src="<?php echo plugins_url( 'i-support-occupy-banner/images/isupportoccupy-'.$bo.'-red.png' , dirname(__FILE__) ); ?>";
		}
	}
</script>
<style type="text/css">#occupy{position:absolute;top:0;<?php echo $bo; ?>:0;z-index:1000;width:156px;height:157px;overflow:hidden;background:url(<?php echo plugins_url( 'i-support-occupy-banner/images/isupportoccupy-'.$bo.'-red.png' , dirname(__FILE__) ); ?>) no-repeat -1000px -1000px;}</style>'
<div id="occupy" onmouseover="occupySwap(0);" onmouseout="occupySwap(1);"><a href="<?php echo $bu; ?>" title="Click to get your own banner and find out more about the OCCUPY WALLSTREET movement."><img id="occupyimg" src="<?php echo plugins_url( 'i-support-occupy-banner/images/isupportoccupy-'.$bo.'-blue.png' , dirname(__FILE__) ); ?>" alt="I support the OCCUPY movement" /></a></div>
<?	
}