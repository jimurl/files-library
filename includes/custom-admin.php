<?php 

// WordPress Customizations for the WP Admin area
# NOTE: Anything put in here will ONLY apply the WP Admin back-end

if( is_admin() ){

	# Add an image size for previewing larger thumbnails in the Photo Galleries
	add_image_size( 'Gallery_Preview_Size', 180, 130, true);
	
}

?>