<?php

function wp_stripe_rewrite() {

	// Register a rewrite rule for our stripe template
	add_rewrite_rule( '^wp-stripe-iframe/?$', 'index.php?wp-stripe-iframe=true', 'top' );

}
add_action( 'init', 'wp_stripe_rewrite' );

function wp_stripe_rewrite_add_var( $vars ) {
		$vars[] = 'wp-stripe-iframe';
		return $vars;
}
add_filter( 'query_vars', 'wp_stripe_rewrite_add_var' );

function wp_stripe_rewrite_load_iframe() {

	if ( get_query_var( 'wp-stripe-iframe' ) ) {
		require_once( WP_STRIPE_PATH . 'includes/stripe-iframe.php' );
		exit;
	}

}
add_action( 'template_redirect', 'wp_stripe_rewrite_load_iframe' );