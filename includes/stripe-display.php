<?php

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

    ?>

    <!-- Start WP-Stripe -->

    <div id="wp-stripe-wrap">

    <form id="wp-stripe-payment-form">

    <input type="hidden" name="action" value="wp_stripe_charge_initiate" />
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'wp-stripe-nonce' ); ?>" />

    <div class="wp-stripe-details">

        <div class="wp-stripe-notification wp-stripe-failure payment-errors" style="display:none"></div>

        <div class="stripe-row">
                <input type="text" name="wp_stripe_name" class="wp-stripe-name" placeholder="<?php _e('Name', 'wp-stripe'); ?> *" autofocus required />
        </div>

        <div class="stripe-row">
                <input type="email" name="wp_stripe_email" class="wp-stripe-email" placeholder="<?php _e('E-mail', 'wp-stripe'); ?>" />
        </div>

        <div class="stripe-row">
                <textarea name="wp_stripe_comment" class="wp-stripe-comment" placeholder="<?php _e('Comment', 'wp-stripe'); ?>"></textarea>
        </div>

    </div>

    <div class="wp-stripe-card">

        <div class="stripe-row">
            <input type="text" name="wp_stripe_amount" autocomplete="off" class="wp-stripe-card-amount" id="wp-stripe-card-amount" placeholder="<?php _e('Amount (USD)', 'wp-stripe'); ?> *" required />
        </div>

        <div class="stripe-row">
            <input type="text" autocomplete="off" class="card-number" placeholder="<?php _e('Card Number', 'wp-stripe'); ?> *" required />
        </div>

        <div class="stripe-row">
            <div class="stripe-row-left">
                <input type="text" autocomplete="off" class="card-cvc" placeholder="<?php _e('CVC Number', 'wp-stripe'); ?> *" maxlength="4" required />
            </div>
            <div class="stripe-row-right">
                <span class="stripe-expiry">EXPIRY</span>
                <select class="card-expiry-month">
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
                <select class="card-expiry-year">
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

        <?php $options = get_option('wp_stripe_options'); if ( $options['stripe_recent_switch'] == 'Yes' ) { ?>

        <div class="stripe-row">

            <input type="checkbox" name="wp_stripe_public" value="public" checked="checked" /> <label><?php _e('Display on Website?', 'wp-stripe'); ?></label>

            <p class="stripe-display-comment"><?php _e('If you check this box, the name as you enter it (including the avatar from your e-mail) and comment will be shown in recent donations. Your e-mail address and donation amount will not be shown.', 'wp-stripe'); ?></p>

        </div>

        <?php }; ?>

        <div style="clear:both"></div>

        <input type="hidden" name="wp_stripe_form" value="1"/>

        <button type="submit" class="stripe-submit-button"><?php _e('Submit Payment', 'wp-stripe'); ?></button>
        <div class="stripe-spinner"></div>


    </form>

    </div>

    <div class="wp-stripe-poweredby">Payments powered by <a href="http://wordpress.org/extend/plugins/wp-stripe" target="_blank">WP-Stripe</a>. No card information is stored on this server.</div>

    <!-- End WP-Stripe -->

    <?php

    $output = apply_filters( 'wp_stripe_filter_form', ob_get_contents());
    ob_end_clean();

    return $output;

}

?>
