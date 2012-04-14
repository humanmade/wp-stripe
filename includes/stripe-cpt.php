<?php

// Custom Post Type - Transactions
// Custom Post Type - Projects

function create_wp_stripe_cpt_trx() {
    
    $labels = array(
        'name'                  => _x('Stripe Payments', ''),
        'singular_name'         => _x('Payment', 'post type singular name'),
        'add_new'               => _x('Add New', 'Payments'),
        'add_new_item'          => __('Add New Payment'),
        'edit_item'             => __('Edit Payment'),
        'new_item'              => __('New Payment'),
        'view_item'             => __('View Payment'),
        'search_items'          => __('Search Payments'),
        'not_found'             => __('No Payments found'),
        'not_found_in_trash'    => __('No Payments found in Trash'),
        'parent_item_colon'     => '',
    );

    $args = array(
        'labels' 		    => $labels,
        'public' 		    => false,
        'can_export' 	    => true,
        'capability_type'   => 'post',
        'hierarchical' 	    => false,
        'supports'		    => array( 'title', 'editor' )
    );

    register_post_type( 'wp-stripe-trx', $args);
    
}

// Custom Post Type - Projects

function create_wp_stripe_cpt_projects() {

    $labels = array(
        'name'                  => _x('Stripe Projects', ''),
        'singular_name'         => _x('Project', 'post type singular name'),
        'add_new'               => _x('Add New', 'Payments'),
        'add_new_item'          => __('Add New Project'),
        'edit_item'             => __('Edit Project'),
        'new_item'              => __('New Project'),
        'view_item'             => __('View Project'),
        'search_items'          => __('Search Projects'),
        'not_found'             => __('No Projects found'),
        'not_found_in_trash'    => __('No Projects found in Trash'),
        'parent_item_colon'     => '',
    );

    $args = array(
        'labels' 		    => $labels,
        'public' 		    => false,
        'can_export' 	    => true,
        'capability_type'   => 'post',
        'hierarchical' 	    => false,
        'supports'		    => array( 'title', 'editor', 'thumbnail' )
    );

    register_post_type( 'wp-stripe-projects', $args);

}

add_action( 'init', 'create_wp_stripe_cpt_trx' );
add_action( 'init', 'create_wp_stripe_cpt_projects' );

?>
