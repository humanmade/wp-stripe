<?php

/**
 * Display the Stripe Form in a Thickbox Pop-up
 *
 * @param $atts array Undefined, have not found any use yet
 * @return string Form Pop-up Link (wrapped in <a></a>)
 *
 * @since 1.3
 *
 */
function wp_stripe_shortcode( $atts ) {

	$options = get_option( 'wp_stripe_options' );
	$url     = add_query_arg( array( 'wp-stripe-iframe' => 'true', 'keepThis' => 'true', 'TB_iframe' => 'true', 'height' => 580, 'width' => 400 ), home_url() );
	$count   = 1;

	if ( isset( $options['stripe_modal_ssl'] ) && $options['stripe_modal_ssl'] === 'Yes' ) {
		$url = str_replace( 'http://', 'https://', $url, $count );
	}

	extract( shortcode_atts(array(
		'cards' => 'true'
	), $atts ) );

	if ( $cards === 'true' )  {
		$payments = '<div id="wp-stripe-types"></div>';
	}

	return '<a class="thickbox" id="wp-stripe-modal-button" title="' . esc_attr( $options['stripe_header'] ) . '" href="' . esc_url( $url ) . '"><span>' . esc_html( $options['stripe_header'] ) . '</span></a>' . $payments;

}
add_shortcode( 'wp-stripe', 'wp_stripe_shortcode' );

/**
 * Display Legacy Stripe form in-line
 *
 * @param $atts array Undefined, have not found any use yet
 * @return string Form / DOM Content
 *
 * @since 1.3
 *
 */
function wp_stripe_shortcode_legacy( $atts ){
	return wp_stripe_form();
}
add_shortcode( 'wp-legacy-stripe', 'wp_stripe_shortcode_legacy' );

/**
 * Create Charge using Stripe PHP Library
 *
 * @param $amount int transaction amount in cents (i.e. $1 = '100')
 * @param $card string
 * @param $description string
 * @return array
 *
 * @since 1.0
 *
 */
function wp_stripe_charge($amount, $card, $name, $description) {

	$options = get_option( 'wp_stripe_options' );

	$currency = $options['stripe_currency'];

	/*
	 * Card - Token from stripe.js is provided (not individual card elements)
	 */
	$charge = array(
		'card'     => $card,
		'amount'   => $amount,
		'currency' => $currency,
	);

	if ( $description ) {
		$charge['description'] = $description;
	}

	$response = Stripe_Charge::create( $charge );

	return $response;

}

/**
 * 3-step function to Process & Save Transaction
 *
 * 1) Capture POST
 * 2) Create Charge using wp_stripe_charge()
 * 3) Store Transaction in Custom Post Type
 *
 * @since 1.0
 *
 */
function wp_stripe_charge_initiate() {

		// Security Check
		if ( ! wp_verify_nonce( $_POST['nonce'], 'wp-stripe-nonce' ) ) {
			wp_die( __( 'Nonce verification failed!', 'wp-stripe' ) );
		}

		// Define/Extract Variables
		$public = sanitize_text_field( $_POST['wp_stripe_public'] );
		$name   = sanitize_text_field( $_POST['wp_stripe_name'] );
		$email  = sanitize_email( $_POST['wp_stripe_email'] );

		// Strip any comments from the amount
		$amount = str_replace( ',', '', sanitize_text_field( $_POST['wp_stripe_amount'] ) );
		$amount = str_replace( '$', '', $amount ) * 100;

		$card = sanitize_text_field( $_POST['stripeToken'] );

		$widget_comment = '';

		if ( empty( $_POST['wp_stripe_comment'] ) ) {
			$stripe_comment = __( 'E-mail: ', 'wp-stipe') . sanitize_text_field( $_POST['wp_stripe_email'] ) . ' - ' . __( 'This transaction has no additional details', 'wp-stripe' );


		} else {
			$stripe_comment = __( 'E-mail: ', 'wp-stipe' ) . sanitize_text_field( $_POST['wp_stripe_email'] ) . ' - ' . sanitize_text_field( $_POST['wp_stripe_comment'] );
			$widget_comment = sanitize_text_field( $_POST['wp_stripe_comment'] );
		}

		// Create Charge
		try {

			$response = wp_stripe_charge( $amount, $card, $name, $stripe_comment );

			$id       = $response->id;
			$amount   = $response->amount / 100;
			$currency = $response->currency;
			$created  = $response->created;
			$live     = $response->livemode;
			$paid     = $response->paid;

			if ( isset( $response->fee ) ) {
				$fee  = $response->fee;
			}

			$result =  '<div class="wp-stripe-notification wp-stripe-success"> ' . sprintf( __( 'Success, you just transferred %s', 'wp-stripe' ), '<span class="wp-stripe-currency">' . esc_html( $currency ) . '</span> ' . esc_html( $amount ) ) . ' !</div>';

			// Save Charge
			if ( $paid === true ) {

				$post_id = wp_insert_post( array(
                    'post_type'	   => 'wp-stripe-trx',
                    'post_author'  => 1,
                    'post_content' => $widget_comment,
                    'post_title'   => $id,
                    'post_status'  => 'publish',
                ) );

				// Define Livemode
				if ( $live ) {
					$live = 'LIVE';
				} else {
					$live = 'TEST';
				}

				// Define Public (for Widget)
				if ( $public === 'public' ) {
					$public = 'YES';
				} else {
					$public = 'NO';
				}

				// Update Meta
				update_post_meta( $post_id, 'wp-stripe-public', $public );
				update_post_meta( $post_id, 'wp-stripe-name', $name );
				update_post_meta( $post_id, 'wp-stripe-email', $email );

				update_post_meta( $post_id, 'wp-stripe-live', $live );
				update_post_meta( $post_id, 'wp-stripe-date', $created );
				update_post_meta( $post_id, 'wp-stripe-amount', $amount );
				update_post_meta( $post_id, 'wp-stripe-currency', strtoupper( $currency ) );

				if ( isset( $fee ) )
					update_post_meta( $post_id, 'wp-stripe-fee', $fee );

				do_action( 'wp_stripe_post_successful_charge', $response, $email, $stripe_comment );

				// Update Project
				// wp_stripe_update_project_transactions( 'add', $project_id , $post_id );

			}

		// Error
		} catch ( Exception $e ) {

			$result = '<div class="wp-stripe-notification wp-stripe-failure">' . sprint_f( __( 'Oops, something went wrong (%s)', 'wp-stripe' ), $e->getMessage() ) . '</div>';
			do_action( 'wp_stripe_post_fail_charge', $email, $e->getMessage() );

		}

		// Return Results to JS
		header( 'Content-Type: application/json' );
		echo json_encode( $result );
		exit;

}
add_action('wp_ajax_wp_stripe_charge_initiate', 'wp_stripe_charge_initiate');
add_action('wp_ajax_nopriv_wp_stripe_charge_initiate', 'wp_stripe_charge_initiate');
