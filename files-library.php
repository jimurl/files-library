<?php 

/**
 * @package Files Library
 * @version 1.0
 */
/*
Plugin Name: Files Library
Description: Provides Custom Post Types, Taxonomies, Functions, etc. for MostOfUs interactive sites
Author: Media Works / Kory Sutherland	/ Jim Earl
Version: 1.0
Author URI: http://www.mediaworksmt.com/
*/

// Setup Defined Constants

	define(ABS_PATH, $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder']);

	define(THEME_PATH, get_stylesheet_directory() );

	define(THEME_URI, get_stylesheet_directory_uri() );

	define(MW_PLUGIN_PATH, plugin_dir_path(__FILE__) );
	
	define(MW_PLUGIN_URI, plugin_dir_url(__FILE__) );
	
	wp_enqueue_style('files_library' , MW_PLUGIN_URI . 'files_library.css');
	
	wp_enqueue_script('colorbox-popup' ,MW_PLUGIN_URI. '/scripts/colorbox-popup.js', array(), '1.0.0', true  );
			
	//wp_enqueue_script('files_library', MW_PLUGIN_URI . 'colorbox/colorbox-jquerykickoff.js');
	
// Include customization files

	# Custom Functions
	include_once MW_PLUGIN_PATH . 'includes/custom-functions.php';
	
	# Custom Rewrites
	//include_once MW_PLUGIN_PATH . 'includes/custom-rewrites.php';
	
	# Custom WP Filters
	//include_once MW_PLUGIN_PATH . 'includes/custom-filters.php';
	
	# Custom Short Codes
	include_once MW_PLUGIN_PATH . 'includes/custom-short-codes.php';
	
	# Custom Post Types
	include_once MW_PLUGIN_PATH . 'includes/custom-post-types.php';
	
	# Custom Taxonomies
	include_once MW_PLUGIN_PATH . 'includes/custom-taxonomies.php';
	
	# Custom JavaScripts
	//include_once MW_PLUGIN_PATH . 'includes/custom-scripts.php';
	
	# Custom Sidebars
	//include_once MW_PLUGIN_PATH . 'includes/custom-sidebars.php';
	
	# Custom Widgets
	//include_once MW_PLUGIN_PATH . 'includes/custom-widgets.php';
	
	# TinyMCE Customizations
	//include_once MW_PLUGIN_PATH . 'includes/custom-tinymce.php';
	
	# WP Admin Customizations
	//include_once MW_PLUGIN_PATH . 'includes/custom-admin.php';

?>