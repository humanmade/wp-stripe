<?php
/*
Plugin Name: WP Stripe
Plugin URI: http://wordpress.org/extend/plugins/wp-stripe/
Description: Integration of the payment system Stripe as an alternative to PayPal.
Author: Noel Tock
Version: 1.4.6
Author URI: http://www.noeltock.com
*/

// Defines
// -----------------------------------------------------

define ( 'WP_STRIPE_VERSION', '1.4.6' );
define ( 'WP_STRIPE_PATH',  WP_PLUGIN_URL . '/' . end( explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) ) ) );

// Load PHP Lib - https://github.com/stripe/stripe-php
// -----------------------------------------------------

if (!class_exists('Stripe')) {
    include_once('stripe-php/lib/Stripe.php');
}

// Load WordPress Files
// -----------------------------------------------------

include_once('includes/stripe-cpt.php');
include_once('includes/stripe-options-transactions.php');
include_once('includes/stripe-options.php');
include_once('includes/stripe-functions.php');
include_once('includes/stripe-display.php');

// In Progress
// include_once('includes/stripe-options-projects.php');

// Select correct API Key
// -----------------------------------------------------

$options = get_option('wp_stripe_options');
$switch = $options['stripe_api_switch'];

if ( $options['stripe_api_switch'] ) {
    if ( $options['stripe_api_switch'] == 'Yes') {
    Stripe::setApiKey($options['stripe_test_api']);
    } else {
    Stripe::setApiKey($options['stripe_prod_api']);
    }
}

if ( $options['stripe_api_switch'] ) {
    if ( $options['stripe_api_switch'] == 'Yes') {
        define( 'WP_STRIPE_KEY', $options['stripe_test_api_publish']);
    } else {
        define( 'WP_STRIPE_KEY', $options['stripe_prod_api_publish']);
    }
}

// Enable Recent Donations/Payments Widget?
// -----------------------------------------------------

if ( $options['stripe_recent_switch'] == 'Yes' ) {
    include_once('includes/stripe-widget-recent.php');
}

// Register Settings ( & Defaults )
// -----------------------------------------------------

if (get_option('wp_stripe_options')== '') {
    register_activation_hook(__FILE__, 'wp_stripe_defaults');
}

function wp_stripe_defaults() {

    $arr = array(
        'stripe_header' => 'Donate',
        'stripe_css_switch' => 'Yes',
        'stripe_api_switch'=>'Yes',
        'stripe_recent_switch'=>'Yes',
        'stripe_modal_ssl'=>'No'
    );

    update_option('wp_stripe_options', $arr);

}

// Actions (Overview)
// -----------------------------------------------------

add_action('admin_init', 'wp_stripe_options_init' );
add_action('admin_menu', 'wp_stripe_add_page');
add_action('wp_print_styles', 'load_wp_stripe_css');
add_action('wp_print_scripts', 'load_wp_stripe_js');
add_action('admin_print_styles', 'load_wp_stripe_admin_css');
add_action('admin_print_scripts', 'load_wp_stripe_admin_js');

// JS & CSS
// -----------------------------------------------------

function load_wp_stripe_js() {
    wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v1/', array('jquery') );
    wp_enqueue_script( 'wp-stripe-js', WP_STRIPE_PATH . '/js/wp-stripe.js', array('jquery') );
    wp_localize_script( 'wp-stripe-js', 'wpstripekey', WP_STRIPE_KEY );
    wp_localize_script( 'wp-stripe-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
}

function load_wp_stripe_admin_js() {
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
}

function load_wp_stripe_css() {
    $options = get_option('wp_stripe_options');
    if ( $options['stripe_css_switch'] ) {
        if ( $options['stripe_css_switch'] == 'Yes') {
            wp_enqueue_style('stripe-payment-css', WP_STRIPE_PATH . '/css/wp-stripe-display.css');
        }
    }
    wp_enqueue_style('stripe-widget-css', WP_STRIPE_PATH . '/css/wp-stripe-widget.css');
}

function load_wp_stripe_admin_css() {
    wp_enqueue_style('stripe-css', WP_STRIPE_PATH . '/css/wp-stripe-admin.css');
}

// Add Thickbox to all Pages
// -----------------------------------------------------

function wp_stripe_thickbox() {
    wp_enqueue_script('thickbox');
    wp_enqueue_style('stripe-thickbox', WP_STRIPE_PATH . '/css/wp-stripe-thickbox.css');
}
add_action('wp_print_styles','wp_stripe_thickbox');

function wp_stripe_thickbox_imgs() {
    $thickbox_path = get_option('siteurl') . '/wp-includes/js/thickbox/';
    $stripe_path = WP_STRIPE_PATH . '/images/';
    echo "<script type=\"text/javascript\">\n";
    echo "	var tb_pathToImage = \"${thickbox_path}loadingAnimation.gif\";\n";
    echo "	var tb_closeImage = \"${stripe_path}thickbox_close.png\";\n";
    echo "</script>\n";
}
add_action('wp_footer', 'wp_stripe_thickbox_imgs');

// Create API Key
// -----------------------------------------------------

/*

$api = get_option('wp_stripe_api');

if ( !$api ) {
    update_option('wp_stripe_api', wp_stripe_base62_encode(rand(1,2147483646)) );
}

*/

?>