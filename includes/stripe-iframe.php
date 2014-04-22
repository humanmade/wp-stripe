<!doctype html>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <title><?php _e( 'Stripe Payment','wp-stripe' ); ?></title>
        <link rel="stylesheet" href="<?php echo esc_url( WP_STRIPE_URL ) . 'css/wp-stripe-display.css'; ?>">

        <script type="text/javascript">
            //<![CDATA[
            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
            var wpstripekey = '<?php echo esc_js( WP_STRIPE_KEY ); ?>';
            //]]>;
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" ></script>
        <script src="https://js.stripe.com/v1/"></script>
        <script src="<?php echo esc_js( WP_STRIPE_URL ) . 'js/wp-stripe.js'; ?>" ></script>

    </head>

    <body>

        <?php echo wp_stripe_form(); ?>

    </body>

</html>