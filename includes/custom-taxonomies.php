<?php 

# Example Taxonomy to be used for creating future ones
 function files_library_taxonomy() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'                => _x( 'Categories', 'taxonomy general name' ),
    'singular_name'       => _x( 'Category', 'taxonomy singular name' ),
    'search_items'        => __( 'Search Categories' ),
    'all_items'           => __( 'All Categories' ),
    'parent_item'         => __( 'Parent Categories' ),
    'parent_item_colon'   => __( 'Parent Categories:' ),
    'edit_item'           => __( 'Edit Category' ), 
    'update_item'         => __( 'Update Category' ),
    'add_new_item'        => __( 'Add New Category' ),
    'new_item_name'       => __( 'New Categories' ),
    'menu_name'           => __( 'Categories' ),
  );

  $args = array(
    'hierarchical'        => true,
    'labels'              => $labels,
    'show_ui'             => true,
    'show_admin_column'   => true,
    'query_var'           => true,
    'rewrite'             => array( 'slug' => 'toolkit' )
  );

  register_taxonomy( 'files_library_taxonomy', array( 'files_library' ), $args );
}

add_action( 'init', 'files_library_taxonomy', 0 ); 
?>