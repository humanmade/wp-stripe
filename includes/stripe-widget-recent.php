<?php

class wp_stripe_recent_widget extends WP_Widget {

	/**
	* Widget setup.
	*/
	function wp_stripe_recent_widget() {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'wp-stripe-recent', 'description' => __( 'Display a list of the latest public donations.', 'wp-stripe' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'wp-stripe-recent' );

		/* Create the widget. */
		$this->WP_Widget( 'wp-stripe-recent', __( 'WP Stripe - Recent', 'wp-stripe' ), $widget_ops, $control_ops );

	}

	/**
	* How to display the widget on the screen.
	*/
	function widget( $args, $instance ) {

		// - our variables from the widget settings -
		$headdesc = $footdesc = $title = $limit = '';

		if ( isset( $instance['stripe-recent-headdesc'] ) ) {
			$headdesc = $instance['stripe-recent-headdesc'];
		}

		if ( isset( $instance['stripe-recent-footdesc'] ) ) {
			$footdesc = $instance['stripe-recent-footdesc'];
		}

		if ( isset( $instance['stripe-recent-title'] ) ) {
			$title = esc_html( apply_filters( 'widget_title', $instance['stripe-recent-title'] ) );
		}

		if ( isset( $instance['stripe-recent-limit'] ) ) {
			$limit = $instance['stripe-recent-limit'];
		}

		if ( ! empty( $args['before_widget'] ) ) {
			echo $args['before_widget'];
		}

		if ( ! empty( $args['before_title'] ) ) {
			echo $args['before_title'];
		}

		echo esc_html( $title );

		if ( ! empty( $args['after_title'] ) ) {
			echo $args['after_title'];
		}

		if ( $headdesc ) { ?>

			<p><?php echo esc_html( $headdesc ); ?></p>

		<?php } ?>

		<div class="wp-stripe-recent">

			<?php

			// The Query
			$donations = new WP_Query( array(
				'post_type' => 'wp-stripe-trx',
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'order' => 'DESC',
				'orderby' => 'date',
				'meta_key' => 'wp-stripe-public',
				'meta_value' => 'YES'
			 ) );

			while ( $donations->have_posts() ) : $donations->the_post(); ?>

				<div class="stripe-item">

					<?php echo get_avatar( get_post_meta( get_the_id(), 'wp-stripe-email', true ), 80 ); ?>

					<div class="stripe-recent-comment">
						<div class="stripe-recent-name"><?php echo esc_html( get_post_meta( get_the_id(), 'wp-stripe-name', true ) ); ?></div>
						<div class="stripe-recent-content"><?php echo get_the_content(); ?></div>
					</div>

				</div>
				<div style="clear:both;"></div>

			<?php endwhile;

			wp_reset_postdata(); ?>

			</div>

		<?php if ( $footdesc ) { ?>
			<p><?php echo esc_html( $footdesc ); ?></p>

		<?php } ?>

		<?php if ( ! empty( $args['after_widget'] ) ) {
			echo $args['after_widget'];
		}

	}

	/**
	* Update the widget settings.
	*/
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['stripe-recent-title']    = sanitize_text_field( $new_instance['stripe-recent-title'] );
		$instance['stripe-recent-headdesc'] = sanitize_text_field( $new_instance['stripe-recent-headdesc'] );
		$instance['stripe-recent-footdesc'] = sanitize_text_field( $new_instance['stripe-recent-footdesc'] );
		$instance['stripe-recent-limit']    = sanitize_text_field( $new_instance['stripe-recent-limit'] );

		return $instance;

	}

	/**
	* Displays the widget settings controls on the widget panel.
	* Make use of the get_field_id() and get_field_name() function
	* when creating your form elements. This handles the confusing stuff.
	*/
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'stripe-recent-title' => __( 'Recent Donations', 'wp-stripe' ), 'stripe-recent-limit' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$limit = $instance['stripe-recent-limit']; ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-title' ) ); ?>"><?php _e( 'Title:', 'wp-stripe' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stripe-recent-title' ) ); ?>" value="<?php echo esc_attr( $instance['stripe-recent-title'] ); ?>" />
		</p>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-limit' ) ); ?>"><?php _e( 'Display Limit:', 'wp-stripe' ); ?></label>

			<select id="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stripe-recent-limit' ) ); ?>">
				<option value="1" <?php selected( $limit, 1); ?>>1</option>
				<option value="2" <?php selected( $limit, 2); ?>>2</option>
				<option value="3" <?php selected( $limit, 3); ?>>3</option>
				<option value="4" <?php selected( $limit, 4); ?>>4</option>
				<option value="5" <?php selected( $limit, 5); ?>>5</option>
				<option value="6" <?php selected( $limit, 6); ?>>6</option>
			</select>

		</p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-headdesc' ) ); ?>"><?php _e( 'Text above payments:', 'wp-stripe'); ?></label><textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-headdesc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stripe-recent-headdesc' ) ); ?>"><?php echo esc_textarea( $instance['stripe-recent-headdesc'] ); ?></textarea></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-headdesc' ) ); ?>"><?php _e( 'Text below payments:', 'wp-stripe' ); ?></label><textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'stripe-recent-footdesc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stripe-recent-footdesc' ) ); ?>"><?php echo esc_textarea( $instance['stripe-recent-footdesc'] ); ?></textarea></p>

	<?php }

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