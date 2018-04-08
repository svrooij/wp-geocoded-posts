<?php

class Geocoded_Posts_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'geocoded_posts_widget', // Base ID
			__('Posts with location','geocoded-posts'), // Name
			array(
				'description' => __('Recent posts with location','geocoded-posts'),
			) // Args
		);

		add_action('wp_enqueue_scripts', array($this, 'register_script'));
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$count = isset($instance['count'])? $instance['count'] : 5;
		$cat = isset($instance['cat'])? $instance['cat'] : '';

		$showAuthor = isset($instance['showAuthor']) ? boolval($instance['showAuthor']) : true;
		echo $args['before_widget'];

		if ( ! empty( $title ) ){ echo $args['before_title'] . $title . $args['after_title']; }

		echo "<ul data-count='$count' data-author='$showAuthor'></ul>";
		
		echo $args['after_widget'];
		
	}

 	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __('Posts with location','geocoded-posts');
		}

		if(isset($instance['cat'])){ $cat = $instance['cat'];}
		else { $cat = ''; }

		if(isset($instance['count'])){ $count = $instance['count'];}
		else { $count = '10'; }

		$showAuthor = isset($instance['showAuthor']) ? boolval($instance['showAuthor']) : false;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title','geocoded-posts'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e('Category','geocoded-posts'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>" type="text" value="<?php echo esc_attr( $cat ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e('Number of posts','geocoded-posts'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>" />
		</p>
		<p>
		<input class="checkbox" type="checkbox" <?php if($showAuthor) { echo 'checked="checked"'; } ?> id="<?php echo $this->get_field_id( 'showAuthor' ); ?>" name="<?php echo $this->get_field_name( 'showAuthor' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'showAuthor' ); ?>"><?php _e('Show Author','geocoded-posts'); ?></label>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['cat'] = ( ! empty( $new_instance['cat'] ) ) ? strip_tags( $new_instance['cat'] ) : '';
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
		$instance['showAuthor'] = (!empty($new_instance['showAuthor'])) ? boolval($new_instance['showAuthor']) : true;

		return $instance;
	}

	function register_script() {
		wp_register_script('geocoded-widget',
			plugins_url( '../js/widget.js', __FILE__ ), 
            array ('jquery'),
			false, true
		);
		if ( is_active_widget(false, false, $this->id_base, true) ) {
			wp_enqueue_script('geocoded-widget');
		}
	}
}
