<?php

class wp_stripe_recent_widget extends WP_Widget {

/**
* Widget setup.
*/
function wp_stripe_recent_widget() {
    
/* Widget settings. */
$widget_ops = array( 'classname' => 'wp-stripe-recent', 'description' => __('Shows latest donors who wish to be shown.', 'wp-stripe') );

/* Widget control settings. */
$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'wp-stripe-recent' );

/* Create the widget. */
$this->WP_Widget( 'wp-stripe-recent', __('WP Stripe - Recent', 'wp-stripe'), $widget_ops, $control_ops );
}

/**
* How to display the widget on the screen.
*/
function widget( $args, $instance ) {
extract( $args );

// - our variables from the widget settings -

$title = apply_filters('widget_title', $instance['stripe-recent-title'] );
$headdesc = $instance['stripe-recent-headdesc'];
$footdesc = $instance['stripe-recent-footdesc'];
$limit = $instance['stripe-recent-limit'];

// widget display

echo $before_widget;

if ( $title ) {echo $before_title . $title . $after_title;}
if ( $headdesc ) {echo '<p>' . $headdesc . '</p>';}
echo '<div class="wp-stripe-recent">';

    // The Query

    $args = array(
        'post_type' => 'wp-stripe-trx',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'order' => 'DESC',
        'orderby' => 'date',
        'meta_key' => 'wp-stripe-public',
        'meta_value' => 'YES'
     );

    $the_query = new WP_Query( $args );

    // The Loop
    while ( $the_query->have_posts() ) : $the_query->the_post();
    $custom = get_post_custom( get_the_ID() );
    $name = $custom["wp-stripe-name"][0];
    $email = $custom["wp-stripe-email"][0];
    $content = get_the_content();

    echo '<div class="stripe-item">';

    $img = get_avatar( $email, 80 );
    echo $img;
    ?>

        <div class="stripe-recent-comment">
            <div class="stripe-recent-name"><?php echo $name; ?></div>
            <div class="stripe-recent-content"><?php echo $content; ?></div>
        </div>

    </div>
    <div style="clear:both;"></div>
    <?php
    endwhile;

    // Reset Post Data
    wp_reset_postdata();

    echo '</div>';
if ( $footdesc ) {echo '<p>' . $footdesc . '</p>';}

echo $after_widget;
}

/**
* Update the widget settings.
*/
function update( $new_instance, $old_instance ) {
$instance = $old_instance;

/* Strip tags for title and name to remove HTML (important for text inputs). */
$instance['stripe-recent-title'] = strip_tags( $new_instance['stripe-recent-title'] );
$instance['stripe-recent-headdesc'] = strip_tags( $new_instance['stripe-recent-headdesc'] );
$instance['stripe-recent-footdesc'] = strip_tags( $new_instance['stripe-recent-footdesc'] );
$instance['stripe-recent-limit'] = strip_tags( $new_instance['stripe-recent-limit'] );

return $instance;
}

/**
* Displays the widget settings controls on the widget panel.
* Make use of the get_field_id() and get_field_name() function
* when creating your form elements. This handles the confusing stuff.
*/
function form( $instance ) {

/* Set up some default widget settings. */
$defaults = array( 'stripe-recent-title' => __('Recent Donations', 'wp-stripe'), 'stripe-recent-limit' => '5');
$instance = wp_parse_args( (array) $instance, $defaults );
$limit = $instance['stripe-recent-limit'];
?>

<!-- Widget Title: Text Input -->
<p><label for="<?php echo $this->get_field_id( 'stripe-recent-title' ); ?>"><?php _e('Title:', 'wp-stripe'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'stripe-recent-title' ); ?>" name="<?php echo $this->get_field_name( 'stripe-recent-title' ); ?>" value="<?php echo $instance['stripe-recent-title']; ?>" /></p>
<label><?php _e('Display Limit:', 'wp-stripe'); ?></label>
<select id="<?php echo $this->get_field_id( 'stripe-recent-limit' ); ?>" name="<?php echo $this->get_field_name( 'stripe-recent-limit' ); ?>">
    <option value='1' <?php selected( $limit, 1); ?>>1</option>
    <option value='2' <?php selected( $limit, 2); ?>>2</option>
    <option value='3' <?php selected( $limit, 3); ?>>3</option>
    <option value='4' <?php selected( $limit, 4); ?>>4</option>
    <option value='5' <?php selected( $limit, 5); ?>>5</option>
    <option value='6' <?php selected( $limit, 6); ?>>6</option>
</select>
<p><label><?php _e('Text above payments:', 'wp-stripe'); ?></label><textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'stripe-recent-headdesc' ); ?>" name="<?php echo $this->get_field_name( 'stripe-recent-headdesc' ); ?>"><?php echo $instance['stripe-recent-headdesc']; ?></textarea></p>
<p><label><?php _e('Text below payments:', 'wp-stripe'); ?></label><textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'stripe-recent-footdesc' ); ?>" name="<?php echo $this->get_field_name( 'stripe-recent-footdesc' ); ?>"><?php echo $instance['stripe-recent-footdesc']; ?></textarea></p>
<?php
}
}
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'wp_stripe_recent_load_widgets' );

/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function wp_stripe_recent_load_widgets() {
    register_widget( 'wp_stripe_recent_widget' );
}

?>