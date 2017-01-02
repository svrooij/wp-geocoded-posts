<?php

class Geocoded_Posts_Editor {


  public function __construct(){
    add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
    add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
    add_action( 'admin_enqueue_scripts', array($this, 'load_scripts' ) );
  }

  /**
  * Meta box initialization.
  */
  public function init_metabox() {
    add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
    add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
  }

  /**
  * Adds the meta box.
  */
  public function add_metabox() {

    add_meta_box(
        'geocoded-posts-editor',
        __( 'Geocoded posts editor', 'geocoded-posts' ),
        array( $this, 'render_metabox' ),
        'post',
        'side',
        'core'
    );

  }

  public function render_metabox($post){
    $latitude = floatval(get_post_meta( $post->ID, 'geo_latitude', true));
    $longitude = floatval(get_post_meta( $post->ID, 'geo_longitude', true));
    $locality = get_post_meta( $post->ID, 'geo_locality', true);
    $visable = (get_post_meta( $post->ID, 'geo_public', true) == 1) ? 'checked ' : '';

    echo "<!-- Geocoded posts - Editor values $latitude $longitude $visable -->";
    wp_nonce_field( 'geocoded_posts_editor', 'geocoded_posts_editor_nonce' );
    echo '<table class="form-table"><tbody>';

    echo '<tr><th><label for="geocoded_posts_lat">'.__('Latitude','geocoded-posts').': </label></th>';
    echo '<td><input type="text" id="geocoded_posts_lat" name="geocoded_posts_lat" value="' . esc_attr( $latitude) . '" /></td></tr>';

    echo '<tr><th><label for="geocoded_posts_long">'.__('Longitude','geocoded-posts').': </label></th>';
    echo '<td><input type="text" id="geocoded_posts_long" name="geocoded_posts_long" value="' . esc_attr( $longitude) . '" /></td></tr>';

    echo '<tr><th><label for="geocoded_posts_locality">'.__('Locality','geocoded-posts').': </label></th>';
    echo '<td><input type="text" id="geocoded_posts_locality" name="geocoded_posts_locality" value="' . esc_attr( $locality) . '" '.(!empty($locality) ? 'readonly="readonly"':'').'  />';

    if($latitude == 0 && $longitude == 0){
      echo ' <button id="btn-search-location" >'.__('Search location','geocoded-posts').'</button>';
    } else {
      echo ' <button id="btn-fetch-locality" '.(!empty($locality)? 'style="display:none;"':'').' >'.__('Load locality','geocoded-posts').'</button>';
    }
    echo '</td></tr>'; //


    echo '<tr><th><label for="geocoded_posts_public">'.__('Visable','geocoded-posts').'</label></th>';
    echo '<td><input type="checkbox" id="geocoded_posts_public" name="geocoded_posts_public" value="1" '.$visable.'/></td></tr>';

    echo '</tbody></table>';
  }

  public function save_metabox($post_id, $post){
    /*
    * We need to verify this came from the our screen and with proper authorization,
    * because save_post can be triggered at other times.
    */

    // Check if our nonce is set.
    if ( ! isset( $_POST['geocoded_posts_editor_nonce'] ) )
      return;

    $nonce = $_POST['geocoded_posts_editor_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'geocoded_posts_editor' ) )
      return;

    // Check if not an autosave.
    if ( wp_is_post_autosave( $post_id ) ) {
        return;
    }

    // // Check if not a revision.
    // if ( wp_is_post_revision( $post_id ) ) {
    //     return;
    // }

    // Check if user has permissions to save data.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }

    // Sanitize user input, we only want the float value of the input.
    $latitude = floatval( $_POST['geocoded_posts_lat'] );
    $longitude = floatval( $_POST['geocoded_posts_long'] );

    $public = isset($_POST['geocoded_posts_public']) ? 1 : 0;

    if($latitude == 0 && $longitude == 0) {
      delete_post_meta( $post_id, 'geo_latitude');
      delete_post_meta( $post_id, 'geo_longitude');
      delete_post_meta( $post_id, 'geo_locality');
      delete_post_meta( $post_id, 'geo_public');
      return;
    }

    // Update the meta field in the database.
    update_post_meta( $post_id, 'geo_locality', strip_tags($_POST['geocoded_posts_locality']));
    update_post_meta( $post_id, 'geo_latitude', $latitude );
    update_post_meta( $post_id, 'geo_longitude', $longitude );
    update_post_meta( $post_id, 'geo_public', $public );
  }

  public function load_scripts($hook){
    // Only on the post edit page.
    if('post.php' != $hook && 'post-new.php' != $hook){
      return;
    }

    // queue the script for loading.
    wp_enqueue_script('geocoded-posts',plugins_url( '../js/editor.js', __FILE__ ),true);

    // Define some keys that can be used in the script.
    // See https://codex.wordpress.org/Function_Reference/wp_localize_script
    wp_localize_script('geocoded-posts','WP_DYNAMIC',array(
      'api_key' => get_option('geocoded_posts_api_key'),
      'site_locale' => get_locale()
    ));
  }
}

// Activate this metabox when this file is required in wordpress.
new Geocoded_Posts_Editor();
