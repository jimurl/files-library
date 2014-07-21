<?php 

/* 
 * Checks to ensure (once per session) that the AutoThumb Rewrite is in place and working.
 */
	function check_autothumb_rewrite(){
		$wp_ht_access_file = ABS_PATH . '.htaccess';
		
		if( isset($_SESSION['image_rewrite']) ){
			return;
		}
		
		if( file_exists( $wp_ht_access_file ) && file_exists(ABS_PATH.'/wp-content/autothumb/highSecurityPassword.php') ){
			$data = file_get_contents($wp_ht_access_file);
			if( strpos($data, '/autothumb/image.php') === false ){
				$data = str_replace(
					'RewriteRule ^index\.php$ - [L]', 
					'RewriteRule ^index\.php$ - [L]'."\n".'RewriteRule ^images/(.*)$ /wp-content/plugins/autothumb/image.php?$1 [QSA,L]."\n"', 
					$data
					);
				file_put_contents($wp_ht_access_file, $data);
			} 
		}
		
		$_SESSION['image_rewrite'] = 1;
		
		return;
	}
	
	add_action('init','check_autothumb_rewrite');

/* FUNCTION: theme_uri()
 * What is it good for? Absolutely noth... er, backwards compatibility.
 * Don't be an idiot: Use the THEME_URI constant instead.
 * USAGE: Figure it out yourself.
 * PARAMS: $echo (bool) >> True to echo, false to return. Default: true 
 */
function theme_uri($echo=true){
	if( $echo != true ){
		return THEME_URI;
	} else {
		echo THEME_URI;
	}
}

/* FUNCTION: image_size()
 * Resizes an image using phpThumb, installed via the "AutoThumb" WordPress plug-in
 * USAGE: image_size('src=/folder/image.jpg?w=100&h=100&zc=1&aoe=1&fltr[]=bvl|1\1\ffffff|000000') or such
 * PARAMS: $params >> (string) phpThumb parameters in URL query variables format (see above line)
 * REQUIRED: $atCode (string) The security code from the AutoThumb plug-in
 */

/* 
 * FUNCTION: Generate navigation "breadcrumbs" for the current (or specified) page
 * PARAMS: $postID >> The post ID # to generate breadcrumbs for. Defaults to the current Post's ID #
 * RETURNS: $output (string) >> HTML content for the nav. breadcrumbs
 */
 	function get_breadcrumbs($postID=null){
 		global $wpdb, $post;
		
		if( is_home() || is_front_page() ){
			return '';
		}
		
		if( $postID == null ){
			$post_data = $post;	
			$postID = $post_data->ID;
		} else {
			# Get post data for the speicifed post
			$post_data = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'posts WHERE id = '.$postID);
			$post_data->ancestors = get_post_ancestors($postID);
		}
		
		$breadcrumbs = '<span class="current_page"><strong>'.stripslashes($post_data->post_title).'</strong></span>';
		
		if( $post_data->post_type == 'post' ){
			$page_for_posts = $wpdb->get_row('SELECT id as ID, post_title FROM '.$wpdb->prefix.'posts WHERE id = (SELECT option_value FROM '.$wpdb->prefix.'options WHERE option_name = "page_for_posts") LIMIT 1');
			$breadcrumbs = '<a href="'.get_permalink($page_for_posts->ID).'">'.stripslashes($page_for_posts->post_title).'</a> &raquo; '.$breadcrumbs;
		}
		
		if( $post_data->ancestors ){
			$ancestor_list = implode(',', $post_data->ancestors);
			$ancestor_data = $wpdb->get_results('SELECT id as ID, post_title FROM '.$wpdb->prefix.'posts WHERE id IN ('.$ancestor_list.') ORDER BY FIELD(id,'.$ancestor_list.')');
			
			foreach( $ancestor_data as $ancestors ){
				$breadcrumbs = '<a href="'.get_permalink($ancestors->ID).'">'.stripslashes($ancestors->post_title).'</a> &raquo; '.$breadcrumbs;
			}
		}
		
		# Add the link to the 'Home' page
		$breadcrumbs = '<a href="/">Home</a> &raquo ' . $breadcrumbs;
		
		// $output = '<pre>CURRENT POST DATA: '.print_r($post_data,true).'</pre>';
		$output = '<div id="PSRP_breadcrumbsNav">'.$breadcrumbs.'</div>'."\n";
		
		return $output;
 	}


/*
 * FUNCTION: Fetch all META fields for the specified post ID #
 * PARAMS: $postID >> The post ID # for which to fetch META fields
 * RETURNS: $postMETA >> An array of the META fields, with META keys as array keys, and META values as array values.
 * 						(When a META key occurs more than once, it's values becomes a multi-dimensional array)
 */
	function fetch_post_meta($postID=null){
		global $wpdb, $post;
		
		if( $postID == null ){
			$postID = $post->ID;
		}
		
		if( $postID >= 1 ){
			# Get all META data for the current Page/Post: For any non-unique meta keys, store them in a multidimensional array such that it becomes $postMETA[meta_key][0], $postMETA[meta_key][1], etc.
			foreach( $wpdb->get_results('SELECT meta_key, meta_value FROM '.$wpdb->prefix.'postmeta WHERE post_id = '.$postID.' ORDER BY meta_id ASC') as $meta ){
				 if( !isset($postMETA[$meta->meta_key]) ){
				 	$postMETA[$meta->meta_key] = $meta->meta_value;
				 } else {
				 	if( !is_array($postMETA[$meta->meta_key]) ){
						$postMETA[$meta->meta_key] = array(0 => strval($postMETA[$meta->meta_key]));
				 	}
					$postMETA[$meta->meta_key][] = $meta->meta_value;
				 }
			}
		} else {
			$postMETA = array('error' => 'No META information could be found for Post ID #'.$postID);
		}
		
		return $postMETA;
	}

/* 
 * FUNCTION: Fetch all (or one/many specific) posts of the type "custom_element" (AKA Custom Site ELements)
 * PARAMS: $posts >> One or more ID #s (separated by commas) of the Custom Site Elements to get. Leaving this "null" will fetch ALL of the Custom Site Elements.
 * RETURNS: $fetchedContent >> An array of Custom Site Elements, with each post's ID # as the key, and each key's value is the post content
 */
	function fetch_custom_elements($posts=null){
		global $wpdb;
		# If posts is specified, modify the SQL query
		if( $posts != null ){
			$queryMOD = ' AND id IN ('.$posts.')';
		}
		# Get "Custom Site Element" posts from the DB and store into $fetchedContent[post-id]
		foreach( $wpdb->get_results('SELECT id, post_content FROM '.$wpdb->prefix.'posts WHERE post_type = "custom_element" AND post_status = "publish"'.$queryMOD) as $fcResult ){
			$fetchedContent[$fcResult->id] = $fcResult->post_content;
		}
		
		return $fetchedContent;
	}
 
function files_library_list_files($cat_slug = NULL ){
	$displayable_filetypes = array('jpg', 'jpeg', 'png', 'pdf', 'gif', 'eps', 'mp4', 'wmv', 'flv', 'ogg', 'ogv', 'm4a' );
	// the query

	$args = array(
		'tax_query' => array(
		    array(
		        'taxonomy' => 'files_library_taxonomy',
		        'field' => 'slug',
		        'terms' => $cat_slug
		    ),
			),
		'post_type' => 'files_library', 
		'status' => 'publish');
	
	$the_query = new WP_Query( $args ); 
	//var_dump($the_query);
	if ( $the_query->have_posts() ) : 
		$out .= "<div class ='files-library-files-list'>";

		while( $the_query->have_posts() ) : $the_query->the_post();
			//var_dump($the_query->posts);
			//$item = setup_postdata($the_query->post->ID);
			//the_title();
			$filedata = files_library_file_details($the_query->post->ID);

			// Process file data into display
			$out .= "<div class = 'files-library-item-wrapper'> <div class ='file-image'>" . $filedata->image. "</div>";


			$out .= "<div class ='files-library-file-info'> 
				<div class='files-library-title'>  ". $filedata->title. "</div>";
						
				$out .= "<div class = 'files-library-filetypesize'> ".$filedata->extension. " - " . 
					sprintf("%.2f", $filedata->filesizedisplay) . $filedata->filesizeunits."</div>" ;
									
			$out .= "</div>"; // end files-library-file-info
			
			$out .= "<div class ='files-library-file-download-view'>"; 
				
			if (in_array($filedata->extension, $displayable_filetypes )){
									
				switch 	($filedata->extension){
					case 'pdf': 
						$out .= "<a href ='?pdfurl=/OHA". $filedata->path ."' class = 'colorbox-popup'><img src = '".plugins_url( '../images/view.png' , __FILE__ ) ."' /></a>";
					break;
					case 'mp4':
					case 'flv':
					case 'ogg':
					case 'ogv':
					case 'wmv':
					case 'mov':
						$out .= "<a href ='?movieurl=/OHA". $filedata->path ."' class = 'colorbox-popup'><img src = '".plugins_url( '../images/view.png' , __FILE__ ) ."' /></a>";
					break;
					case 'mp3':
					case 'm4a':
					case 'wav':
					case 'wma':
					case 'ogg':
						$out .= "<a href ='?audiourl=/OHA". $filedata->path ."' class = 'colorbox-popup'><img src = '".plugins_url( '../images/view.png' , __FILE__ ) ."' /></a>";
					break;
					default:
						$out .= "	<a href ='/OHA". $filedata->path ." class = 'colorbox-popup'><img src = '".plugins_url( '../images/view.png' , __FILE__ ) ."' /></a>";
					break;
					
				}
					
			} //  end if in acceptable filetypes	
				
				
				
			$out .= "	<a href ='/OHA". $filedata->path ."'><img src = '".plugins_url( '../images/download.png' , __FILE__ ) ."' /></a>
			</div>";
			
			$out .= "</div>"; //  end /file-library-items-wrapper ; 
		
		endwhile; 
		
		$out .= "</div>"; //end /files-library-files-list
	  ?><!-- end of the loop -->

	  <!-- pagination here -->

	  <?php 
		wp_reset_postdata(); 
		endif; 
	
	return $out;
}

function files_library_display_audio(){
 		if ( $_GET['audiourl'] != '' ){
			$audiourl= mysql_real_escape_string($_GET['audiourl']);
			wp_head();
			echo do_shortcode('[audio src='.$audiourl.']');
			wp_footer();
			exit;
		}
}

function files_library_display_movie(){
 		if ( $_GET['movieurl'] != '' ){
			$movieurl= mysql_real_escape_string($_GET['movieurl']);
			wp_head();
			echo do_shortcode('[video height=500 width=700 src='.$movieurl.']');
			wp_footer();
			exit;
		}
}


function files_library_display_pdf(){
	if ( $_GET['pdfurl'] != '' ){
		$pdfurl = mysql_real_escape_string($_GET['pdfurl']);
		echo do_shortcode('[pdfjs-viewer url='. $pdfurl .' viewer_width=700px viewer_height=500px fullscreen=false download=false print=true openfile=false]');
		exit;
		
	}
	
	
}

add_action( 'init', 'files_library_display_pdf');
add_action( 'init', 'files_library_display_movie');
add_action( 'init', 'files_library_display_audio');


function files_library_file_details($ID){
	//first let's fetch the attached files info   
	$full_path = "/wp-content/uploads/".get_post_meta( get_post_meta($ID, 'upload_file', TRUE ), '_wp_attached_file' ,TRUE );
		//get_attached_file( get_post_meta($post_meta, '_wp_attached_file', TRUE ), FALSE );
	
	$file_info->extension = strtolower(array_pop(explode('.',$full_path)));
	$file_info->title = get_the_title($ID);
	
	$file_info->filesize = filesize(ABSPATH.$full_path);
	
	$file_info->filesizeunits = ($file_info->filesize > 1000000) ? "mb" : "kb";
	
	$file_info->filesizedisplay = ($file_info->filesize > 1000000) ? $file_info->filesize / 1000000 : $file_info->filesize / 1000 ;
	
	//var_dump($the_file_url);
	$file_info->path = $full_path   ;
	// Now we see if it has a thmubnail already
	
	$thumbsize = array(150,150);
	$attr = array('class' => "user-provided");
	$img_string = get_the_post_thumbnail($ID, $thumbnail, $attr);
	
	if ($img_string == "") { // ... then we'll need to figure it out from the filetype
		switch ( strtolower($file_info->extension) ){
			case 'jpeg':  //  
			case 'jpg':   // For these image-type files, we use the phpthumb wrapper image_size() 
			case 'png':   //
			case 'gif':
			case 'tiff':
			case 'tif':
			case 'psd':
			case 'eps':
			case 'pdf':
			
			$img_string = "<img src ='". image_size( 'src='.$full_path.'&w=128&h=128' ) ."'>"; ;
			break;
		
			default:		
				$img_string = "<img src ='" .plugins_url( '../images/'. strtolower($file_info->extension).'.png' , __FILE__ ) ."'>";
			}
	}
	$file_info->image = $img_string;
	
	return $file_info;

}

?>