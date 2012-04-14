<?php

/**
 * Display Stripe JS Code
 *
 * @since 1.0
 *
 */

function wp_stripe_js() {

    // Get API Key

    $options = get_option('wp_stripe_options');

    if ( $options['stripe_api_switch'] ) {
        if ( $options['stripe_api_switch'] == 'Yes') {
            $apikey = $options['stripe_test_api_publish'];
        } else {
            $apikey = $options['stripe_prod_api_publish'];
        }
    }
    // Generate Token

    ?>

    <script type="text/javascript">

    Stripe.setPublishableKey('<?php echo $apikey; ?>');

    // PUSH Name & Description

    function stripeResponseHandler(status, response) {
        if (response.error) {
            console.log(status);
            console.log(response);
            // re-enable the submit button
            jQuery('.stripe-submit-button').removeAttr("disabled");
            // show the errors on the form
            jQuery(".payment-errors").show().html(response.error.message);
        } else {
            var form$ = jQuery("#wp-stripe-payment-form");
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

            // and submit
            form$.get(0).submit();
        }
    }

    // Validate Form upon Submit

    jQuery(document).ready(function() {
        jQuery("#wp-stripe-payment-form").submit(function(event) {

            jQuery(".payment-errors").hide();

            // disable the submit button to prevent repeated clicks

            jQuery('.stripe-submit-button').attr("disabled", "disabled");

            var amount = jQuery('.wp-stripe-card-amount').val() * 100; //amount you want to charge in cents

            Stripe.createToken({
                name: jQuery('.wp-stripe-name').val(),
                number: jQuery('.card-number').val(),
                cvc: jQuery('.card-cvc').val(),
                exp_month: jQuery('.card-expiry-month').val(),
                exp_year: jQuery('.card-expiry-year').val()
            }, amount, stripeResponseHandler);

            // prevent the form from submitting with the default action

            return false;
        });
    });


    jQuery(document).ready(function() {

            // Validate Credit Card # & CVC during filling out of Form

            jQuery('.card-number').focusout( function(){

                var $field;
                var $card;

                $field = jQuery('.card-number').val();
                $card = Stripe.validateCardNumber($field);
                if ( $card == true ) {
                    jQuery('.card-number').removeClass('stripe-invalid');
                    jQuery('.card-number').addClass('stripe-valid');
                } else {
                    jQuery('.card-number').removeClass('stripe-valid');
                    jQuery('.card-number').addClass('stripe-invalid');
                }
            });

            jQuery('.card-cvc').focusout( function(){

                var $field;
                var $cvc;

                $field = jQuery('.card-cvc').val();
                $cvc = Stripe.validateCVC($field);
                if ( $cvc == true ) {
                    jQuery('.card-cvc').removeClass('stripe-invalid');
                    jQuery('.card-cvc').addClass('stripe-valid');
                } else {
                    jQuery('.card-cvc').removeClass('stripe-valid');
                    jQuery('.card-cvc').addClass('stripe-invalid');
                }
            });

            // Change Submit button after Click

            // TODO Needs to revert upon fail

            /*

            jQuery('form#wp-stripe-payment-form button[type="submit"]').click(function() {
                var stripeheight = jQuery(this).height();
                var stripewidth = jQuery(this).width();
                console.log(stripeheight);
                console.log(stripewidth);
                jQuery(this).css('display', 'none');
                jQuery('form#wp-stripe-payment-form .stripe-spinner').css('display', 'block');
                jQuery('form#wp-stripe-payment-form .stripe-spinner').width(stripewidth);
                jQuery('form#wp-stripe-payment-form .stripe-spinner').height(stripeheight);

            });

            */

    });



    </script>

    <?php

}

/**
 * Display Stripe Form
 *
 * @return string Stripe Form (DOM)
 *
 * @since 1.0
 *
 */

function wp_stripe_form() {

    ob_start();

    echo '<!-- Start WP-Stripe --><div id="wp-stripe-wrap">';

    // Insert Stripe JS

    wp_stripe_js();

    // Display POST data again for non-sensitive data

    if ( isset($_POST['wp_stripe_form'] ) == '1') {

        $stripe_post_name = $_POST['wp_stripe_name'];
        $stripe_post_email = $_POST['wp_stripe_email'];
        $stripe_post_comment = $_POST['wp_stripe_comment'];

    }

    ?>

    <form action="" method="POST" id="wp-stripe-payment-form">
    <div class="wp-stripe-details">
            <div class="wp-stripe-notification wp-stripe-failure payment-errors" style="display:none"></div>
            <?php wp_stripe_charge_initiate(); ?>
        <div class="stripe-row">
                <input type="text" name="wp_stripe_name" class="wp-stripe-name" value="<?php echo $stripe_post_name; ?>" placeholder="<?php _e('Name', 'wp-stripe'); ?> *" />
        </div>
        <div class="stripe-row">
                <input type="text" name="wp_stripe_email" class="wp-stripe-email" value="<?php echo $stripe_post_email; ?>" placeholder="<?php _e('E-mail', 'wp-stripe'); ?>" />
        </div>
        <div class="stripe-row">
                <textarea name="wp_stripe_comment" class="wp-stripe-comment" placeholder="<?php _e('Comment', 'wp-stripe'); ?>"><?php echo $stripe_post_comment; ?></textarea>
        </div>
    </div>
    <div class="wp-stripe-card">
        <div class="stripe-row">
                <input type="text" name="wp_stripe_amount" autocomplete="off" class="wp-stripe-card-amount" id="wp-stripe-card-amount" placeholder="<?php _e('Amount (USD)', 'wp-stripe'); ?> *" />
        </div>
        <div class="stripe-row">
                <input type="text" name="wp_stripe_cardn" autocomplete="off" class="card-number" placeholder="<?php _e('Card Number', 'wp-stripe'); ?> *" />
        </div>
        <div class="stripe-row">

        </div>
        <div class="stripe-row">
            <div class="stripe-row-left">
                <input type="text" name="wp_stripe_cardcvc" autocomplete="off" class="card-cvc" placeholder="<?php _e('CVC Number', 'wp-stripe'); ?> *" />
            </div>
            <div class="stripe-row-right">
                <span class="stripe-expiry">EXPIRY</span>
            <select name="wp_stripe_cardem" class="card-expiry-month">
                <option value="1">01</option>
                <option value="2">02</option>
                <option value="3">03</option>
                <option value="4">04</option>
                <option value="5">05</option>
                <option value="6">06</option>
                <option value="7">07</option>
                <option value="8">08</option>
                <option value="9">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <span></span>
            <select name="wp_stripe_cardem" class="card-expiry-year">
            <?php
                $year = date(Y,time());
                $num = 1;

                while ( $num <= 7 ) {
                    echo '<option value="' . $year .'">' . $year . '</option>';
                    $year++;
                    $num++;
                }
            ?>
            </select>
            </div>

        </div>



        </div>

        <div class="stripe-row">

            <input type="checkbox" name="wp_stripe_public" value="public" checked="checked" /> <label><?php _e('Display on Website?', 'wp-stripe'); ?></label>

            <p class="stripe-display-comment"><?php _e('If you check this box, the name as you enter it (including the avatar from your e-mail) and comment will be shown in recent donations. Your e-mail address and donation amount will not be shown.', 'wp-stripe'); ?></p>

        </div>

        <input type="hidden" name="wp_stripe_form" value="1"/>

        <button type="submit" class="stripe-submit-button">Submit Payment</button>
        <div class="stripe-spinner"></div>


    </form>

    </div>

    <div class="wp-stripe-poweredby">Payments powered by <a href="http://wordpress.org/extend/plugins/wp-stripe" target="_blank">WP-Stripe</a>. No card information is stored on this server.</div>

    <!-- End WP-Stripe -->

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;

}

?>
