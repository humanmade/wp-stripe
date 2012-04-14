<?php

// Include WordPress Header

define('WP_USE_THEMES', false);
require('../../../../wp-blog-header.php');

// Head

?>

<!doctype html>
<html lang="en">
<head>

      <meta charset="utf-8">
      <title><?php _e('Stripe Payment','wp-stripe'); ?></title>
      <link rel="stylesheet" href="<?php echo WP_STRIPE_PATH . '/css/wp-stripe-display.css'; ?>">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      <script src="https://js.stripe.com/v1/"></script>

</head>
<body>

<?php

// Stripe

echo wp_stripe_form();

// Footer
?>


</body>
</html>

<?php

?>