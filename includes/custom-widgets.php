<?php 

/*
 * WIDGET: Use "Custom Site Element" content for Sidebar
 */
class PostToSB extends WP_Widget {
    /** Widget Constructor */
    function PostToSB() {
        parent::WP_Widget(false, $name = 'Custom Site Element Widget', array('description' => 'Use any Custom Site Element as content for the Sidebar'));
    }

    /** Widget Output */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $post2show = $instance['post2show'];
		
		# Add the color class into the opening-tag for the widget
		$before_widget = str_ireplace('class="', 'class="rich_content_area ', $before_widget);
		
        echo $before_widget;
		
        	if ( $title ){
        		echo $before_title , $title , $after_title; 
        	} else {
        		echo $before_title , get_the_title($post2show) , $after_title; 
        	}
        	if ( $post2show > 0 ){
        		$contentToShow = get_post($post2show);
        		echo apply_filters('the_content',$contentToShow->post_content);
        	}	
			
        echo $after_widget;
    }
    
    /** Widget Input Updating */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }
    
    /** Widget Input from Back-End */
    function form($instance) {
    	global $wpdb;				
        $title = esc_attr($instance['title']);
        $post2show = esc_attr($instance['post2show']);
        
        $siteElementPosts = $wpdb->get_results('SELECT id, post_title FROM '.$wpdb->prefix.'posts WHERE post_type = "custom_element" AND post_status = "publish" ORDER BY post_title ASC');
        
        foreach($siteElementPosts as $siteElement){
        	if( $siteElement->id == $post2show ){
        		$siteElementsList .= '<option value="'.$siteElement->id.'" selected="selected">'.$siteElement->post_title.'</option>'."\n";
        	} else {
        		$siteElementsList .= '<option value="'.$siteElement->id.'">'.$siteElement->post_title.'</option>'."\n";
        	}
        }
        
        ?>
            <p>
            	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
            	<small>(Optional - This will override the Element's title.)</small>
            </p> 
            <p><label for="<?php echo $this->get_field_id('post2show'); ?>"><?php _e('Element to Show:'); ?> <select class="widefat" id="<?php echo $this->get_field_id('post2show'); ?>" name="<?php echo $this->get_field_name('post2show'); ?>"><?php echo $siteElementsList; ?></select></label></p>
        <?php 
    }
} // END Site Element Content for Sidebar Class

add_action('widgets_init', create_function('', 'return register_widget("PostToSB");'));

?>