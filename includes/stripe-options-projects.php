<?php

/**
 * Update Project Transactions Array
 *
 * @param $action either 'add' or 'remove'
 * @param $post_id ID of Stripe Project (custom post type)
 * @param $trx_id ID of Individual Transactions (custom post type)
 */
function wp_stripe_update_project_transactions( $action, $post_id, $trx_id) {

    // Get & update transactions list associated with Project

    $transactions = get_post_meta( $post_id, 'wp-stripe-project-transactions' );

    if ( $action == 'add' ) {

        array_push($transactions, $trx_id);

    } else {

        $position = array_search( $trx_id, $transactions );
        unset( $transactions[$position] );

    }

    update_post_meta( $post_id, 'wp-stripe-project-transactions', $transactions);

    // Update transactions total

    foreach ( $transactions as $transaction ) {

        $amount = get_post_meta( $transaction, 'wp-stripe-amount' );
        $sum .= $amount;

    }

    update_post_meta( $post_id, 'wp-stripe-project-funded', $sum );

}


/**
 * Projects - Display projects within options page
 */
function wp_stripe_options_display_projects() {

        // Query Custom Post Types
        $args = array(
            'post_type' => 'wp-stripe-projects',
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => 'wp-stripe-completion',
            'order' => 'ASC',
            'posts_per_page' => 50
        );

        // - query -
        $my_query = null;
        $my_query = new WP_query( $args );

        while ( $my_query->have_posts() ) : $my_query->the_post();

            $time_format = get_option( 'time_format' );

            // - variables -

            $custom = get_post_custom( get_the_ID() );
            $id = ( $my_query->post->ID );
            $public = $custom["wp-stripe-public"][0];
            $live = $custom["wp-stripe-live"][0];
            $name = $custom["wp-stripe-name"][0];
            $email = $custom["wp-stripe-email"][0];
            $content = get_the_content();
            $date = $custom["wp-stripe-date"][0];
            $cleandate = date('d M', $date);
            $cleantime = date('H:i', $date);
            $amount = $custom["wp-stripe-amount"][0];
            $fee = ($custom["wp-stripe-fee"][0])/100;
            $net = round($amount - $fee,2);

            echo '<tr>';

            // Dot

            if ( $live == 'LIVE' ) {
                $dotlive = '<div class="dot-stripe-live"></div>';
            } else {
                $dotlive = '<div class="dot-stripe-test"></div>';
            }

            if ( $public == 'YES' ) {
                $dotpublic = '<div class="dot-stripe-public"></div>';
            } else {
                $dotpublic = '<div class="dot-stripe-test"></div>';
            }

            // Person

            $img = get_avatar( $email, 32 );
            $person = $img . ' <span class="stripe-name">' . $name . '</span>';

            // Received

            $received = '<span class="stripe-netamount"> + ' . $net . '</span> (-' . $fee . ')';

            // Content

            echo '<td>' . $dotlive . $dotpublic . '</td>';
            echo '<td>' . $person . '</td>';
            echo '<td>' . $received . '</td>';
            echo '<td>' . $cleandate . ' - ' . $cleantime . '</td>';
            echo '<td class="stripe-comment">"' . $content . '"</td>';

            echo '</tr>';

        endwhile;

    }

?>
