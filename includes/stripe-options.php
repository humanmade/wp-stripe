<?php

/**
 * Create Options Fields
 *
 * @since 1.0
 *
 */
function wp_stripe_options_init() {

	register_setting( 'wp_stripe_options', 'wp_stripe_options' );

	add_settings_section( 'wp_stripe_section_main', '', 'wp_stripe_options_header', 'wp_stripe_section' );

	add_settings_field( 'stripe_header', 'Payment Form Header', 'wp_stripe_field_header', 'wp_stripe_section', 'wp_stripe_section_main' );
	add_settings_field( 'stripe_recent_switch', 'Enable Recent Widget?', 'wp_stripe_field_recent', 'wp_stripe_section', 'wp_stripe_section_main' );
	add_settings_field( 'stripe_css_switch', 'Enable Payment Form CSS?', 'wp_stripe_field_css', 'wp_stripe_section', 'wp_stripe_section_main' );
	add_settings_field( 'stripe_labels_on', 'Enable Payment Form Labels?', 'wp_stripe_field_labels', 'wp_stripe_section', 'wp_stripe_section_main' );
	add_settings_field( 'stripe_placeholders_on', 'Enable Payment Form Placeholders?', 'wp_stripe_field_placeholders', 'wp_stripe_section', 'wp_stripe_section_main' );
	add_settings_field( 'stripe_email_required', 'Is Email A Required Field?', 'wp_stripe_field_email_required', 'wp_stripe_section', 'wp_stripe_section_main' );
	add_settings_field( 'stripe_currency', 'Currency', 'wp_stripe_field_currency', 'wp_stripe_section', 'wp_stripe_section_main' );

	add_settings_section( 'wp_stripe_section_api', '', 'wp_stripe_options_header_api', 'wp_stripe_section' );

	add_settings_field( 'stripe_api_switch', 'Enable Test API Environment?', 'wp_stripe_field_switch', 'wp_stripe_section', 'wp_stripe_section_api' );
	add_settings_field( 'stripe_test_api', 'API Secret Key (Test Environment)', 'wp_stripe_field_test', 'wp_stripe_section', 'wp_stripe_section_api' );
	add_settings_field( 'stripe_test_api_publish', 'API Publishable Key (Test Environment)', 'wp_stripe_field_test_publish', 'wp_stripe_section', 'wp_stripe_section_api' );
	add_settings_field( 'stripe_prod_api', 'API Secret Key (Production Environment)', 'wp_stripe_field_prod','wp_stripe_section', 'wp_stripe_section_api' );
	add_settings_field( 'stripe_prod_api_publish', 'API Publishable Key (Production Environment)', 'wp_stripe_field_prod_publish', 'wp_stripe_section', 'wp_stripe_section_api' );

	add_settings_section( 'wp_stripe_section_ssl', '', 'wp_stripe_options_header_ssl', 'wp_stripe_section' );

	add_settings_field( 'stripe_modal_ssl', 'Enable SSL for modal pop-up?', 'wp_stripe_field_ssl', 'wp_stripe_section', 'wp_stripe_section_ssl' );

}

/**
 * Options Page Headers (blank)
 *
 * @since 1.0
 *
 */

function wp_stripe_options_header () { ?>
	<h2>General</h2>
<?php }

function wp_stripe_options_header_api () { ?>
	<h2>API</h2>
<?php }

function wp_stripe_options_header_ssl () { ?>
	<h2>SSL</h2>
<?php }

/**
 * Individual Fields
 *
 * @since 1.0
 *
 */
function wp_stripe_field_header () {

	$options = get_option( 'wp_stripe_options' );
	$value = $options['stripe_header']; ?>

	<input id="setting_api" name="wp_stripe_options[stripe_header]" type="text" size="40" value="<?php echo esc_attr( $value ); ?>" />

<?php }

function wp_stripe_field_recent () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_recent_switch" name="wp_stripe_options[stripe_recent_switch]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_recent_switch'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function wp_stripe_field_css () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_css_switch" name="wp_stripe_options[stripe_css_switch]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_css_switch'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function wp_stripe_field_labels () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_labels_on" name="wp_stripe_options[stripe_labels_on]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_labels_on'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function wp_stripe_field_placeholders () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_placeholders_switch" name="wp_stripe_options[stripe_placeholders_on]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_placeholders_on'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function wp_stripe_field_email_required () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_email_required" name="wp_stripe_options[stripe_email_required]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_email_required'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function wp_stripe_field_switch () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_api_switch" name="wp_stripe_options[stripe_api_switch]">

		<?php foreach( $items as $item ) {

			$selected = $options['stripe_api_switch'] === $item ? 'selected="selected"' : ''; ?>

			<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

		<?php } ?>

	</select>

<?php }

function wp_stripe_field_currency () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'USD', 'CAD', 'GBP', 'EUR', 'AUD' ); ?>

	<select id="stripe_currency" name="wp_stripe_options[stripe_currency]">

		<?php foreach( $items as $item ) {

			$selected = $options['stripe_currency'] === $item ? 'selected="selected"' : ''; ?>

			<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

		<?php } ?>

	</select>

<?php }

function wp_stripe_field_test () {

	$options = get_option( 'wp_stripe_options' );
	$value = $options['stripe_test_api']; ?>

	<input id="setting_api" name="wp_stripe_options[stripe_test_api]" class="code" type="text" size="40" value="<?php echo esc_attr( $value ); ?>" />

<?php }

function wp_stripe_field_test_publish () {

	$options = get_option( 'wp_stripe_options' );
	$value = $options['stripe_test_api_publish']; ?>

	<input id="setting_api" name="wp_stripe_options[stripe_test_api_publish]" class="code" type="text" size="40" value="<?php echo esc_attr( $value ); ?>" />

<?php }

function wp_stripe_field_prod () {

	$options = get_option( 'wp_stripe_options' );
	$value = $options['stripe_prod_api']; ?>

	<input id="setting_api" name="wp_stripe_options[stripe_prod_api]" class="code" type="text" size="40" value="<?php echo esc_attr( $value ); ?>" />

<?php }

function wp_stripe_field_prod_publish () {

	$options = get_option( 'wp_stripe_options' );
	$value = $options['stripe_prod_api_publish']; ?>

	<input id="setting_api" name="wp_stripe_options[stripe_prod_api_publish]" class="code" type="text" size="40" value="<?php echo esc_attr( $value ); ?>" />

<?php }

function wp_stripe_field_ssl () {

	$options = get_option( 'wp_stripe_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_modal_ssl" name="wp_stripe_options[stripe_modal_ssl]">

	<?php foreach( $items as $item ) {

		$selected = ($options['stripe_modal_ssl']==$item) ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

/**
 * Register Options Page
 *
 * @since 1.0
 *
 */
function wp_stripe_add_page() {
	add_options_page( 'WP Stripe', 'WP Stripe', 'manage_options', 'wp_stripe', 'wp_stripe_options_page' );
}

/**
 * Create Options Page Content
 *
 * @since 1.0
 *
 */
function wp_stripe_options_page() { ?>

	<script type="text/javascript">
		jQuery( function() {
			jQuery( '#wp-stripe-tabs' ).tabs();
		} );
	</script>

	<div id="wp-stripe-tabs">

		<h1 class="stripe-title">WP Stripe</h1>

		<ul id="wp-stripe-tabs-nav">
			<li><a href="#wp-stripe-tab-transactions">Transactions</a></li>
		 <!--   <li><a href="#wp-stripe-tab-projects">Projects</a></li> -->
			<li><a href="#wp-stripe-tab-settings">Settings</a></li>
			<li><a href="#wp-stripe-tab-about">About</a></li>
		</ul>

		<div style="clear:both"></div>

		<div id="wp-stripe-tab-transactions">

			<table class="wp-stripe-transactions">

			  <thead>

			  <tr>

				  <th style="width:44px;"><div class="dot-stripe-live"></div><div class="dot-stripe-public"></div></th>
				  <th style="width:200px;">Person</th>
				  <th style="width:140px;">Net Amount (Fee)</th>
				  <th style="width:80px;">Date</th>

				  <th>Comment</th>

			  </tr></thead>

			<?php wp_stripe_options_display_trx(); ?>

			<p style="color:#777">The amount of payments display is limited to 500. Log in to your <a href="https://manage.stripe.com/dashboard">Stripe dashboard</a> to see more.</p>
			<div style="color:#777"><div class="dot-stripe-live"></div>Live Environment (as opposed to Test API)</div>
			<div style="color:#777"><div class="dot-stripe-public"></div>Will show in Widget (as opposed to only being visible to you)</div>

			<br />

			<form method="POST">
				<input type="hidden" name="wp_stripe_delete_tests" value="1">
				<input type="submit" class="button" value="Delete all test transactions">
			</form>

		</div>

<!--

		<div id="wp-stripe-tab-projects">

				 <table class="wp-stripe-projects">
					<thead><tr class="wp-stripe-absolute"></tr><tr>

						<th style="width:100px;">Progress</th>
						<th style="width:200px;">Raised (Target)</th>
						<th style="width:200px;">Project</th>
						<th>Description</th>

					</tr></thead>

					<?php

					// Content

					//echo '<td><div class="progress-stripe-wrap"><div class="progress-stripe-value" style="width:40px"></div></div></td>';
					//echo '<td>' . $person . '</td>';
					//echo '<td>' . $received . '</td>';
					//echo '<td>' . $cleandate . ' - ' . $cleantime . '</td>';
					//echo '<td class="stripe-comment">"' . $content . '"</td>';

					//echo '</tr>';


					//endwhile;

					?>


				</table>

		</div>

-->

		<div id="wp-stripe-tab-settings">

			<form action="options.php" method="post">
				<?php settings_fields( 'wp_stripe_options' ); ?>
				<?php do_settings_sections( 'wp_stripe_section' ); ?>
				<br />
				<input name="Submit" type="submit" class="button button-primary button-large" value="<?php _e( 'Save Changes', 'wp-stripe' ); ?>" />
			</form>

			<p style="margin-top:20px;color:#777">I highly suggest you test payments using the <strong>Test Environment</strong> first. You can use the following details:</p>
			<ul style="color:#777">
				<li><strong>Card Number</strong> 4242424242424242</li>
				<li><strong>Card Month</strong> 05</li>
				<li><strong>Card Year</strong> 2015</li>
			</ul>
			<p style="color:#777"><strong>Note:</strong> CVC is optional when payments are made.</p>

		</div>

		<div id="wp-stripe-tab-about">

			<p>This plugin was orginially created by <a href="http://www.twitter.com/noeltock" target="_blank">@noeltock</a> and has now been expanded to be <a href="http://hmn.md">Human Made</a> as a whole, follow us for updates &amp; other WordPress goodness.</p>
			<p>If you need any support, please use the <a href="http://wordpress.org/tags/wp-stripe?forum_id=10">forums</a>, this is the only location we will provide support. If you are interested in contributing or raising development issues, please visit the <a href="https://github.com/humanmade/wp-stripe">Github respository</a>. Thank you!</p>

	</div>

<?php }

function wp_stripe_delete_tests() {

	if ( isset( $_POST['wp_stripe_delete_tests'] ) && $_POST['wp_stripe_delete_tests'] === '1' ) {

		$test_transactions = new WP_query( array(
			'post_type' => 'wp-stripe-trx',
			'post_status' => 'publish',
			'posts_per_page' => 500
		) );

		while ( $test_transactions->have_posts() ) : $test_transactions->the_post();

			// Delete Post
			if ( get_post_meta( get_the_id(), 'wp-stripe-live', true ) === 'TEST' ) {
				var_dump( the_title() );
				wp_delete_post( get_the_id(), true );
			}

		endwhile;

		wp_redirect( wp_get_referer() );

		exit;

	}

}
add_action( 'admin_init', 'wp_stripe_delete_tests');