<?php 

/* 
 * Ensure that only ONE copy of jQuery is being loaded on the front-end of the site
 * It does not affect jQuery loading for the back-end / WP admin
 * (This assumes that all plugins will use wp_enqueue_script() to load JavaScript files...
 * It will not prevent JS file loading by plugins which are written by happless code-weiners.) 
 */
	function load_jquery_only_once(){
		if( !is_admin() ){
			wp_deregister_script('jquery');
			wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"), false, '1.7.2');
			wp_enqueue_script('jquery');
		}
	}
	
	add_action('wp_enqueue_scripts','load_jquery_only_once');
	
/* Load "jQuery Cycle 2" for image slidshow type stuff */
	
	function load_jquery_cycle_v2(){
		if( !is_admin() ){
			wp_register_script('cycle_v2', (MW_PLUGIN_URI . 'scripts/jquery.cycle2.min.js'), false, '2.1.5');
			wp_enqueue_script('cycle_v2');
		} 
	}
	
	add_action('wp_enqueue_scripts','load_jquery_cycle_v2');
	
/* Load the jQuery Marquee plugin */
	
	function load_jquery_marquee(){
		if( !is_admin() ){
			wp_register_script('jquery_marquee', (MW_PLUGIN_URI . 'scripts/jquery.marquee.min.js'), false, '1.0.0');
			wp_enqueue_script('jquery_marquee');
		} 
	}
	
	add_action('wp_enqueue_scripts','load_jquery_marquee');

/* Load the jQuery "PrintThis" plugin */

	function load_jquery_print_this(){
		# Only load the printThis plugin outside of /wp-admin and when the current Post is an attachment
		if( !is_admin() && is_attachment() ){
			wp_register_script('jquery_printthis', (MW_PLUGIN_URI . 'scripts/printThis.js'), false, '1.3');
			wp_enqueue_script('jquery_printthis');
		}
	}
	
	add_action('wp_enqueue_scripts','load_jquery_print_this');

?>