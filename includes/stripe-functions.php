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

function wp_stripe_shortcode( $atts ){

    $settings = '?keepThis=true&TB_iframe=true&height=580&width=400';
    $path = WP_STRIPE_PATH . '/includes/stripe-iframe.php'. $settings;
    $options = get_option('wp_stripe_options');

    extract(shortcode_atts(array(
        'cards' => 'true'
    ), $atts));

    if ( $cards == 'true' )  {
        $payments = '<div id="wp-stripe-types"></div>';
    }

    return '<a class="thickbox" id="wp-stripe-modal-button" title="' . $options['stripe_header'] . '" href="' . $path . '">' . $options['stripe_header'] . '</a>' . $payments;

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

    /*
     * Currency - All amounts must be denominated in USD when creating charges with Stripe — the currency conversion happens automatically
     */

    $currency = 'usd';

    /*
     * Card - Token from stripe.js is provided (not individual card elements)
     */

    $charge = array(
        'card' => $card,
        'amount' => $amount,
        'currency' => $currency,
    );

    if ( $description ) {
        $charge['description'] = $description;
    }

    $response = Stripe_Charge::create($charge);

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

    if ( isset($_POST['wp_stripe_form'] ) == '1') {

        // Define/Extract Variables

        $public = $_POST['wp_stripe_public'];
        $name = $_POST['wp_stripe_name'];
        $email = $_POST['wp_stripe_email'];
        $amount = $_POST['wp_stripe_amount'] * 100;
        $card = $_POST['stripeToken'];

        if ( !$_POST['wp_stripe_comment'] ) {
            $comment = __('This transaction has no additional details', 'wp-stripe');
        } else {
            $comment = $_POST['wp_stripe_comment'];
        }

        // Create Charge

        try {

            $response = wp_stripe_charge($amount, $card, $name, $comment);

            $id = $response->id;
            $amount = ($response->amount)/100;
            $currency = $response->currency;
            $created = $response->created;
            $live = $response->livemode;
            $paid = $response->paid;
            $fee = $response->fee;

            echo '<div class="wp-stripe-notification wp-stripe-success"> ' . __('Success, you just transferred ', 'wp-stripe') . '<span class="wp-stripe-currency">' . $currency . '</span> ' . $amount . ' !</div>';

            // Save Charge

            if ( $paid == true ) {

                $new_post = array(
                    'ID' => '',
                    'post_type' => 'wp-stripe-trx',
                    'post_author' => 1,
                    'post_content' => $comment,
                    'post_title' => $id,
                    'post_status' => 'publish',
                );

                $post_id = wp_insert_post( $new_post );

                // Define Livemode

                if ( $live ) {
                    $live = 'LIVE';
                } else {
                    $live = 'TEST';
                }

                // Define Public (for Widget)

                if ( $public == 'public' ) {
                    $public = 'YES';
                } else {
                    $public = 'NO';
                }

                // Update Meta

                update_post_meta( $post_id, 'wp-stripe-public', $public);
                update_post_meta( $post_id, 'wp-stripe-name', $name);
                update_post_meta( $post_id, 'wp-stripe-email', $email);

                update_post_meta( $post_id, 'wp-stripe-live', $live);
                update_post_meta( $post_id, 'wp-stripe-date', $created);
                update_post_meta( $post_id, 'wp-stripe-amount', $amount);
                update_post_meta( $post_id, 'wp-stripe-currency', strtoupper($currency));
                update_post_meta( $post_id, 'wp-stripe-fee', $fee);

                // TODO Add Project or Plan Post ID

            }

        // Error

        } catch (Exception $e) {
            echo '<div class="wp-stripe-notification wp-stripe-failure">' . __('Oops, something went wrong', 'wp-stripe' ) . ' (' . $e->getMessage() . ')</div>';
        }

    }
}

?>