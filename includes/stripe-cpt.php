<?php

// Custom Post Type - Transactions
// Custom Post Type - Projects

function create_wp_stripe_cpt_trx() {

    $args = array(
        'public' 		    => false,
        'can_export' 	    => true,
        'capability_type'   => 'post',
        'hierarchical' 	    => false,
    );

    register_post_type( 'wp-stripe-trx', $args);

}
add_action( 'init', 'create_wp_stripe_cpt_trx' );

// Custom Post Type - Projects

function create_wp_stripe_cpt_projects() {

    $labels = array(
        'name'                  => _x( 'Stripe Projects', '', 'wp-stripe' ),
        'singular_name'         => _x( 'Project', 'post type singular name', 'wp-stripe' ),
        'add_new'               => _x( 'Add New', 'Payments', 'wp-stripe' ),
        'add_new_item'          => __( 'Add New Project', 'wp-stripe' ),
        'edit_item'             => __( 'Edit Project', 'wp-stripe' ),
        'new_item'              => __( 'New Project', 'wp-stripe' ),
        'view_item'             => __( 'View Project', 'wp-stripe' ),
        'search_items'          => __( 'Search Projects', 'wp-stripe' ),
        'not_found'             => __( 'No Projects found', 'wp-stripe' ),
        'not_found_in_trash'    => __( 'No Projects found in Trash', 'wp-stripe' ),
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
//add_action( 'init', 'create_wp_stripe_cpt_projects' );