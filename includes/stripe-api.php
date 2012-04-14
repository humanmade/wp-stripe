<?php

// Work in Progress

function wp_stripe_send_api($amount, $created, $post_id) {

    //set POST variables
    $url = 'http://domain.com/get-post.php';

    $transaction_post = get_post_custom( $post_id );
    $project_id = $transaction_post["wp-stripe-projectid"][0];
    if ( $project_id ) {
        $project = 1;
        $project_post = get_post_custom( $post_id );
        $project_size = $project_post["wp-stripe-project-size"][0];
        $project_raised = $project_post["wp-stripe-project-raised"][0];
    } else {
        $project = 0;
        $project_size = 0;
        $project_raised = 0;
    }



    $fields = array(
        'amount'=>urlencode($amount),
        'date'=>urlencode($created),
        'p'=>urlencode($project),
        'ps'=>urlencode($project_size),
        'pr'=>urlencode($project_raised),
        'country'=>urlencode($country),
        'type'=>urlencode($type)
    );

    //url-ify the data for the POST
    $fields_string = '';
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&amp;'; }
    rtrim($fields_string,'&amp;');

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

}

function wp_stripe_base62_encode($val) {
    $base=62;
    $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    do {
        $i = $val % $base;
        $str = $chars[$i] . $str;
        $val = ($val - $i) / $base;
    } while($val > 0);
    return $str;
}

?>