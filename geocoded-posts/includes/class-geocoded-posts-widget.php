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
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$count = isset($instance['count'])? $instance['count'] : 5;
		$cat = isset($instance['cat'])? $instance['cat'] : '';

		$showAuthor = isset($instance['showAuthor']) ? boolval($instance['showAuthor']) : true;

		/* Posts ophalen */
		$q = array(
			'posts_per_page' => $count,
			'category' => $cat,
			'post_type' => 'post',
			'post_status' => 'publish',
			'suppress_filters' => true,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'geo_latitude',
					'compare' => 'EXISTS'
				),
				array(
					'key' => 'geo_longitude',
					'compare' => 'EXISTS'
				),
				array(
					'key' => 'geo_public',
					'value' => '1'
				),
			)
		);

		$widget_query = new WP_Query($q);
		if($widget_query->have_posts()){
			echo $args['before_widget'];

			if ( ! empty( $title ) ){ echo $args['before_title'] . $title . $args['after_title']; }
			
			echo '<ul>';

			while($widget_query->have_posts()) {
				$widget_query->the_post();
				if($showAuthor) {
					printf('<li><a href="%s">%s</a></li>', get_permalink(), sprintf(__('%s by %s'), get_the_title(), get_the_author()));
				} else {
					printf('<li><a href="%s">%s</a></li>', get_permalink(), get_the_title());
				}
			}

			echo '</ul>';
			echo $args['after_widget'];

		}
		wp_reset_query();
		wp_reset_postdata();

		// $posts_array = get_posts($q);
		// if(count($posts_array) > 0){
		// 	echo $args['before_widget'];

		// 	if ( ! empty( $title ) )
		// 		echo $args['before_title'] . $title . $args['after_title'];

		// 	echo '<ul>';

		// 	foreach($posts_array as $geo_post) {
		// 		//print_r($geo_post);
		// 		$link = get_permalink($geo_post);
		// 		$title = get_the_title($geo_post);
		// 		if($showAuthor) {
		// 			$author = get_the_author($geo_post->ID);
		// 			echo '<li><a href="'.$link.'" >'.sprintf(__('%s by %s'), $title, $author).'</a></li>';
		// 		} else {
		// 			echo '<li><a href="'.$link.'" >'.$title.'</a></li>';
		// 		}
		// 	}

		// 	echo '</ul>';
		// 	echo $args['after_widget'];
		// }
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
}
