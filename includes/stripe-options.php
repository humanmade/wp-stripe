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

function wp_stripe_options_header () {

    ?>

    <h2>General</h2>

    <?php

}

function wp_stripe_options_header_api () {

    ?>

    <h2>API</h2>

    <?php

}

function wp_stripe_options_header_ssl () {

    ?>

    <h2>SSL</h2>

    <?php

}

/**
 * Individual Fields
 *
 * @since 1.0
 *
 */

function wp_stripe_field_header () {

        $options = get_option( 'wp_stripe_options' );
        $value = $options['stripe_header'];
        echo "<input id='setting_api' name='wp_stripe_options[stripe_header]' type='text' size='40' value='$value' />";

}

function wp_stripe_field_recent () {

        $options = get_option( 'wp_stripe_options' );
        $items = array( 'Yes', 'No' );
        echo "<select id='stripe_api_switch' name='wp_stripe_options[stripe_recent_switch]'>";

        foreach( $items as $item ) {
            $selected = ($options['stripe_recent_switch']==$item) ? 'selected="selected"' : '';
            echo "<option value='$item' $selected>$item</option>";
        }

        echo "</select>";
}

function wp_stripe_field_css () {

        $options = get_option( 'wp_stripe_options' );
        $items = array( 'Yes', 'No' );
        echo "<select id='stripe_api_switch' name='wp_stripe_options[stripe_css_switch]'>";

        foreach( $items as $item ) {
            $selected = ($options['stripe_css_switch']==$item) ? 'selected="selected"' : '';
            echo "<option value='$item' $selected>$item</option>";
        }

        echo "</select>";

}

function wp_stripe_field_switch () {

        $options = get_option( 'wp_stripe_options' );
        $items = array( 'Yes', 'No' );
        echo "<select id='stripe_api_switch' name='wp_stripe_options[stripe_api_switch]'>";

            foreach( $items as $item ) {
                    $selected = ($options['stripe_api_switch']==$item) ? 'selected="selected"' : '';
                    echo "<option value='$item' $selected>$item</option>";
            }

        echo "</select>";

}

function wp_stripe_field_test () {

        $options = get_option( 'wp_stripe_options' );
        $value = $options['stripe_test_api'];
        echo "<input id='setting_api' name='wp_stripe_options[stripe_test_api]' type='text' size='40' value='$value' />";

}

function wp_stripe_field_test_publish () {

        $options = get_option( 'wp_stripe_options' );
        $value = $options['stripe_test_api_publish'];
        echo "<input id='setting_api' name='wp_stripe_options[stripe_test_api_publish]' type='text' size='40' value='$value' />";

}

function wp_stripe_field_prod () {

        $options = get_option( 'wp_stripe_options' );
        $value = $options['stripe_prod_api'];
        echo "<input id='setting_api' name='wp_stripe_options[stripe_prod_api]' type='text' size='40' value='$value' />";

}

function wp_stripe_field_prod_publish () {

        $options = get_option( 'wp_stripe_options' );
        $value = $options['stripe_prod_api_publish'];
        echo "<input id='setting_api' name='wp_stripe_options[stripe_prod_api_publish]' type='text' size='40' value='$value' />";

}

function wp_stripe_field_ssl () {

    $options = get_option( 'wp_stripe_options' );
    $items = array( 'Yes', 'No' );
    echo "<select id='stripe_modal_ssl' name='wp_stripe_options[stripe_modal_ssl]'>";

    foreach( $items as $item ) {
        $selected = ($options['stripe_modal_ssl']==$item) ? 'selected="selected"' : '';
        echo "<option value='$item' $selected>$item</option>";
    }

    echo "</select>";

}

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

function wp_stripe_options_page() {
    ?>

    <script type="text/javascript">
        jQuery(function() {
            jQuery("#wp-stripe-tabs").tabs();
        });
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
              <thead><tr class="wp-stripe-absolute"></tr><tr>

                  <th style="width:44px;"><div class="dot-stripe-live"></div><div class="dot-stripe-public"></div></th>
                  <th style="width:200px;">Person</th>
                  <th style="width:100px;">Net Amount (Fee)</th>
                  <th style="width:80px;">Date</th>

                  <th>Comment</th>

              </tr></thead>

            <?php

                wp_stripe_options_display_trx();

            ?>

            <p style="color:#777">The amount of payments display is limited to 500. Log in to your Stripe account to see more.</p>
            <div style="color:#777"><div class="dot-stripe-live"></div>Live Environment (as opposed to Test API)</div>
            <div style="color:#777"><div class="dot-stripe-public"></div>Will show in Widget (as opposed to only being visible to you)</div>

            <br />

            <form method="POST">
                <input type="hidden" name="wp_stripe_delete_tests" value="1">
                <input type="submit" value="Delete all test transactions">
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

                    echo '<td><div class="progress-stripe-wrap"><div class="progress-stripe-value" style="width:40px"></div></div></td>';
                    echo '<td>' . $person . '</td>';
                    echo '<td>' . $received . '</td>';
                    echo '<td>' . $cleandate . ' - ' . $cleantime . '</td>';
                    echo '<td class="stripe-comment">"' . $content . '"</td>';

                    echo '</tr>';


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
                <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
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

            <p>This plugin was created by <a href="http://www.twitter.com/noeltock" target="_blank">@noeltock</a>, follow me for updates & WordPress goodness.</p>
            <p>If you need any support, please use the <a href="http://wordpress.org/tags/wp-stripe?forum_id=10">forums</a>, this is the only location I will provide unpaid support. Thank you!</p>

    </div>

<?php
}


function wp_stripe_delete_tests() {

    if ( isset($_POST['wp_stripe_delete_tests'] ) == '1') {

        // Query Custom Post Types
        $args = array(
            'post_type' => 'wp-stripe-trx',
            'post_status' => 'publish',
            'posts_per_page' => 500
        );

        // Query
        $my_query = null;
        $my_query = new WP_query( $args );

        while ( $my_query->have_posts() ) : $my_query->the_post();

            // Meta
            $custom = get_post_custom( get_the_ID() );
            $id = ( $my_query->post->ID );
            $live = $custom["wp-stripe-live"][0];

            // Delete Post
            if ( $live == 'TEST' ) {
                wp_delete_post( $id, true );
            }

        endwhile;

        wp_redirect( wp_get_referer() );
        exit;

    }

}

add_action( 'admin_init', 'wp_stripe_delete_tests');

?>