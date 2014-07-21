<?php 

// Add some theme configuration stuff if necessary

add_action('after_setup_theme','enable_post_thumbnails');

function enable_post_thumbnails(){
	# Enable post thumbnails
	add_theme_support( 'post-thumbnails' );
}
	
# Enable shortcodes within sidebar text widgets
add_filter('widget_text', 'do_shortcode');

?>