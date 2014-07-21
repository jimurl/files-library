<?php 
/* Custom Wordpress Post Types */

// "Custom Elements" (custom_element) Post Type
function files_library_register() {
 
	$labels = array(
		'name' => _x('Files', 'post type general name'),
		'singular_name' => _x('Library File', 'post type singular name'),
		'add_new' => _x('Add New', 'portfolio item'),
		'add_new_item' => __('Add New File to the Library'),
		'edit_item' => __('Edit Library File'),
		'new_item' => __('New Library File'),
		'view_item' => __('View Photo Gallery'),
		'search_items' => __('Search Library Files'),
		'not_found' =>  __('No matching Library Files were found'),
		'not_found_in_trash' => __('No Library Files are in the Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => MW_PLUGIN_URI . '/admin-icons/photo_album.png',
		'rewrite' => array('slug'=>'toolkit'),
		'capability_type' => 'post',
		'hierarchical' => false,
		'can_export' => true,
		'menu_position' => null,
		'supports' => array('title','thumbnail','custom-fields','author','revisions'), // POSSIBLE OPTIONS: title, editor, author, thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes
		'show_in_nav_menus' => false
	  ); 

	register_post_type( 'files_library' , $args );
}

// Activate Custom Post Types
add_action('init', 'files_library_register');

?>