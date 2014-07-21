<?php 

/** 
 * Add "Styles" drop-down content or classes 
 */    
function mw_tinymce_editor_settings($settings) {
    
	
	/** 
		  
    if (!empty($settings['theme_advanced_styles'])){
        $settings['theme_advanced_styles'] .= ';';
	} else {  
        $settings['theme_advanced_styles'] = '';
	}
    
     * Add styles in $classes array. 
     * The format for this setting is "Name to display=class-name;". 
     * More info: http://wiki.moxiecode.com/index.php/TinyMCE:Configuration/theme_advanced_styles 
     * 
     * To be allow translation of the class names, these can be set in a PHP array (to keep them 
     * readable) and then converted to TinyMCE's format. You will need to replace 'textdomain' with 
     * your theme's textdomain. 
     */  
     
    /* $classes = array(  
        'Framed Image (Left)' => 'framed_image alignleft',
        'Framed Image (Right)' => 'framed_image alignright',
        'Framed Image (Center)' => 'framed_image aligncenter', 
        'Framed Image (No Align)' => 'framed_image',  
    );  
  
    $class_settings = '';  
    foreach ( $classes as $name => $value ){
        $class_settings .= "{$name}={$value};";
	}  
  
    $settings['theme_advanced_styles'] .= trim($class_settings, '; ');  
    return $settings; */
    
    $settings['webkit_fake_resize'] = 1;
    
	// DEBUG
	echo "\n\n".'<!-- TinyMCE Pre-Styles: '.print_r($settings,true).' -->'."\n\n";	
	
    return $settings;
}   
  
add_filter('tiny_mce_before_init', 'mw_tinymce_editor_settings');  

?>