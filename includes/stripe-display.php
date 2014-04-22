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

	$options = get_option( 'wp_stripe_options' );

	$currency        = $options['stripe_currency'];
	$labels_on       = $options['stripe_labels_on'] === 'Yes';
	$placeholders_on = $options['stripe_placeholders_on'] === 'Yes';
	$email_required  = $options['stripe_email_required'] === 'Yes';

	ob_start(); ?>

	<div id="wp-stripe-wrap">

		<form id="wp-stripe-payment-form">

		<input type="hidden" name="action" value="wp_stripe_charge_initiate" />
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'wp-stripe-nonce' ); ?>" />

		<div class="wp-stripe-details">

			<div class="wp-stripe-notification wp-stripe-failure payment-errors" style="display:none"></div>

			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
					<label for="wp_stripe_name"><?php _e( 'Name', 'wp-stripe' ); ?></label>
				<?php endif; ?>
				<input type="text" id="wp_stripe_name" name="wp_stripe_name" class="wp-stripe-name" <?php if ( $placeholders_on ) : ?>placeholder="<?php _e( 'Name', 'wp-stripe' ); ?> *"<?php endif;?> autofocus required />
			</div>

			<div class="stripe-row">
				<?php if ( $labels_on) : ?>
					<label for="wp_stripe_email"><?php _e( 'Email', 'wp-stripe' ); ?></label>
				<?php endif; ?>
				<input type="email" id="wp_stripe_email" name="wp_stripe_email" class="wp-stripe-email" <?php if ( $placeholders_on ) : ?>placeholder="<?php _e( 'E-mail', 'wp-stripe' ); ?><?php echo $email_required ? ' *' : ''; ?>"<?php endif; ?> <?php echo $email_required ? ' required' : ''; ?> />
			</div>

			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
					<label for="wp_stripe_comment"><?php _e( 'Comment', 'wp-stripe' ); ?></label>
				<?php endif; ?>
				<textarea id="wp_stripe_comment" name="wp_stripe_comment" class="wp-stripe-comment" <?php if ( $placeholders_on ) : ?>placeholder="<?php _e( 'Comment', 'wp-stripe' ); ?>"<?php endif; ?>></textarea>
			</div>

		</div>

		<div class="wp-stripe-card">

			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
					<label for="wp_stripe_amount"><?php printf( __( 'Amount (%s)', 'wp-stripe' ), esc_html( $currency ) ); ?></label>
				<?php endif; ?>
				<input type="text" id="wp_stripe_amount" name="wp_stripe_amount" autocomplete="off" class="wp-stripe-card-amount" id="wp-stripe-card-amount" <?php if ( $placeholders_on) : ?>placeholder="<?php printf( __( 'Amount (%s)', 'wp-stripe' ), $currency ); ?> *"<?php endif; ?> required />
			</div>

			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
					<label for="card-number"><?php _e( 'Card Number', 'wp-stripe' ); ?></label>
				<?php endif; ?>
				<input type="text" id="card-number" autocomplete="off" class="card-number" <?php if ( $placeholders_on) : ?>placeholder="<?php _e( 'Card Number', 'wp-stripe' ); ?> *"<?php endif; ?> required />
			</div>

			<div class="stripe-row">
				<div class="stripe-row-left">
					<?php if ( $labels_on ) : ?>
						<label for="card-cvc"><?php _e( 'CVC Number', 'wp-stripe' ); ?></label>
					<?php endif; ?>
					<input type="text" id="card-cvc" autocomplete="off" class="card-cvc" <?php if ( $placeholders_on ) : ?>placeholder="<?php _e( 'CVC Number', 'wp-stripe' ); ?> *"<?php endif; ?> maxlength="4" required />
				</div>
				<div class="stripe-row-right">
					<label for="card-expiry" class="stripe-expiry">Expiry</label>
					<select id="card-expiry" class="card-expiry-month">
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

						<?php $year = date( 'Y',time() );
						$num = 1;

						while ( $num <= 7 ) { ?>

							<option value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></option>

							<?php
							$year++;
							$num++;
						} ?>

					</select>
				</div>

			</div>

			</div>

			<?php $options = get_option( 'wp_stripe_options' );

			if ( isset( $options['stripe_recent_switch'] ) && $options['stripe_recent_switch'] === 'Yes' ) { ?>

				<div class="wp-stripe-meta">

					<div class="stripe-row">

						<input type="checkbox" name="wp_stripe_public" value="public" checked="checked" /> <label><?php _e( 'Display on Website?', 'wp-stripe' ); ?></label>

						<p class="stripe-display-comment"><?php _e( 'If you check this box, the name as you enter it (including the avatar from your e-mail) and comment will be shown in recent donations. Your e-mail address and donation amount will not be shown.', 'wp-stripe' ); ?></p>

					</div>

				</div>

			<?php }; ?>

			<div style="clear:both"></div>

			<input type="hidden" name="wp_stripe_form" value="1" />

			<button type="submit" class="stripe-submit-button"><span><div class="spinner">&nbsp;</div><?php _e( 'Submit Payment', 'wp-stripe' ); ?></span></button>

			<div class="stripe-spinner"></div>

		</form>

	</div>

	<div class="wp-stripe-poweredby"><?php printf( __( 'Payments powered by %s. No card information is stored on this server.', 'wp-stripe' ), '<a href="http://wordpress.org/extend/plugins/wp-stripe" target="_blank">WP-Stripe</a>' ); ?></div>

	<?php $output = apply_filters( 'wp_stripe_filter_form', ob_get_contents() );

	ob_end_clean();

	return $output;

}
