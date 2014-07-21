<?php 

function show_navigation($atts){
	# Extract the parameters
	extract( shortcode_atts( array(
		'align' => null,
		'depth' => 1,
		'heading' => 'Explore',
	), $atts ) );
	
	# Check for alignment
	switch( $align ){
		case 'left' : 
			$align = ' alignleft'; 
			break;
		case 'right' : 
			$align = ' alignright'; 
			break;
		case 'center' : 
			$align = ' aligncenter'; 
			break;
		default : 
			$align = '';
	}
	
	$out = wp_list_pages(array(
			'depth'=>$depth,
			'child_of' => 0,
			'title_li'=>'',
			'echo'=>0,
			'sort_column' => 'menu_order'
			)
		);
		
	$out = '<nav class="FPG_navigation_block'.$align.' Block">
	'.( $heading != null ? '<h4>'.$heading.'</h4>' : '' ).'
		<ul>
		'.$out.'
		</ul>
	</nav>';
	
	return $out;
}

add_shortcode('show-navigation','show_navigation');

function show_files_categories($atts){   
	
	# Extract the parameters	
	$term_list = get_terms( array('files_library_taxonomy'));
	//var_dump($term_list);
	$out .= "<div class = 'files-library-terms-list' >";
	foreach($term_list as $term){
		$out .= "<div class = 'library-term-item'>";
		$out .= "<a href ='". get_site_url() ."/toolkit/".$term->slug."' class ='terms-list'>";
			$out .= $term->name;
			$out .= "</a>";
		$out .= "<br/>";
		$out .= "</div>";
		
	}
	$out .= "</div>";
	
	return $out;
}

function show_file_list($atts){
//	
// seek which category is being identified in the url:
//	
	$path = explode ( '/' , $_SERVER['REQUEST_URI']);
	//return files_library_list_files($path[3]);
	return files_library_list_files('adult-communication-campaign-toolkitmedia');
	//var_dump($file_info);
}
	
add_shortcode('file-categories','show_files_categories');

add_shortcode('file-list', 'show_file_list');

?>