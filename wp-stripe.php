<?php

/*
Plugin Name: WP Stripe
Plugin URI: http://wordpress.org/extend/plugins/wp-stripe/
Description: Integration of the payment system Stripe as an alternative to PayPal.
Author: Human Made Limited
Version: 1.5
Author URI: http://hmn.md
*/

// Defines
// -----------------------------------------------------

define( 'WP_STRIPE_PATH',  plugin_dir_path( __FILE__ ) );
define( 'WP_STRIPE_URL', plugin_dir_url(  __FILE__  ) );
define( 'WP_STRIPE_VERSION', '1.5' );

// Load PHP Lib - https://github.com/stripe/stripe-php
// -----------------------------------------------------

if ( ! class_exists( 'Stripe' ) ) {
	require_once( WP_STRIPE_PATH . 'stripe-php/lib/Stripe.php' );
}

// Load WordPress Files
// -----------------------------------------------------

require_once( WP_STRIPE_PATH . 'includes/stripe-cpt.php' );
require_once( WP_STRIPE_PATH . 'includes/stripe-options-transactions.php' );
require_once( WP_STRIPE_PATH . 'includes/stripe-options.php' );
require_once( WP_STRIPE_PATH . 'includes/stripe-functions.php' );
require_once( WP_STRIPE_PATH . 'includes/stripe-display.php' );
require_once( WP_STRIPE_PATH . 'includes/stripe-rewrite.php' );


// In Progress
// require_once('includes/stripe-options-projects.php');

// Select correct API Key
// -----------------------------------------------------

$options = get_option( 'wp_stripe_options' );

if ( ! empty( $options['stripe_api_switch'] ) ) {

	if ( $options['stripe_api_switch'] === 'Yes') {
		Stripe::setApiKey( $options['stripe_test_api'] );
		define( 'WP_STRIPE_KEY', $options['stripe_test_api_publish'] );

	} else {
		Stripe::setApiKey( $options['stripe_prod_api'] );
		define( 'WP_STRIPE_KEY', $options['stripe_prod_api_publish'] );
	}
}

// Enable Recent Donations/Payments Widget?
// -----------------------------------------------------

if ( $options['stripe_recent_switch'] === 'Yes' ) {
	require_once( WP_STRIPE_PATH . 'includes/stripe-widget-recent.php' );
}

// Register Settings ( & Defaults )
// -----------------------------------------------------

if ( get_option( 'wp_stripe_options' ) === '' ) {
	register_activation_hook( __FILE__, 'wp_stripe_defaults' );
}

function wp_stripe_defaults() {

	flush_rewrite_rules();

	update_option( 'wp_stripe_options', array(
		'stripe_header'           => 'Donate',
		'stripe_css_switch'       => 'Yes',
		'stripe_api_switch'       => 'Yes',
		'stripe_recent_switch'    => 'Yes',
		'stripe_modal_ssl'        => 'No',
		'stripe_currency'         => 'USD',
		'stripe_labels_on'        => 'No',
		'stripe_placeholders_on'  => 'Yes',
		'stripe_email_required'   => 'No'
	) );

}

// Actions (Overview)
// -----------------------------------------------------
add_action( 'admin_init', 'wp_stripe_options_init' );
add_action( 'admin_menu', 'wp_stripe_add_page' );

// JS & CSS
// -----------------------------------------------------

function load_wp_stripe_js() {

	wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v1/', array( 'jquery' ), WP_STRIPE_VERSION );
	wp_enqueue_script( 'wp-stripe-js', WP_STRIPE_URL . 'js/wp-stripe.js', array( 'jquery' ), WP_STRIPE_VERSION );

	// Pass some variables to JS
	wp_localize_script( 'wp-stripe-js', 'wpstripekey', WP_STRIPE_KEY );
	wp_localize_script( 'wp-stripe-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

}
add_action( 'wp_print_scripts', 'load_wp_stripe_js' );

function load_wp_stripe_admin_js() {
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-tabs' );
}
add_action( 'admin_print_scripts', 'load_wp_stripe_admin_js' );

function load_wp_stripe_css() {

	$options = get_option( 'wp_stripe_options' );

	if ( isset( $options['stripe_css_switch'] ) && $options['stripe_css_switch'] === 'Yes' ) {
		wp_enqueue_style('stripe-payment-css', WP_STRIPE_URL . 'css/wp-stripe-display.css', array(), WP_STRIPE_VERSION );
	}

	wp_enqueue_style( 'stripe-widget-css', WP_STRIPE_URL . 'css/wp-stripe-widget.css', array(), WP_STRIPE_VERSION );
}
add_action( 'wp_print_styles', 'load_wp_stripe_css' );

function load_wp_stripe_admin_css() {
	wp_enqueue_style( 'stripe-css', WP_STRIPE_URL . 'css/wp-stripe-admin.css', array(), WP_STRIPE_VERSION );
}
add_action( 'admin_print_styles', 'load_wp_stripe_admin_css' );

/**
 * Add Thickbox to all Pages
 */
function wp_stripe_thickbox() {
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'stripe-thickbox', WP_STRIPE_URL . 'css/wp-stripe-thickbox.css', array(), WP_STRIPE_VERSION );
}
add_action( 'wp_print_styles','wp_stripe_thickbox' );

/**
 * Replace the Thickbox images with our own
 */
function wp_stripe_thickbox_imgs() { ?>

	<script type="text/javascript">
		var tb_pathToImage = "<?php echo esc_js( WP_STRIPE_URL ); ?>images/loadingAnimation.gif";
	</script>

<?php }
add_action( 'wp_footer', 'wp_stripe_thickbox_imgs' );

// Create API Key
// -----------------------------------------------------

/*

$api = get_option('wp_stripe_api');

if ( !$api ) {
	update_option('wp_stripe_api', wp_stripe_base62_encode(rand(1,2147483646)) );
}

*/