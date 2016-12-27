<?php

class Geocoded_Posts_Editor {


  public function __construct(){
    add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
    add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
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
    $latitude = get_post_meta( $post->ID, 'geo_latitude', true);
    $longitude = get_post_meta( $post->ID, 'geo_longitude', true);
    $visable = (get_post_meta( $post->ID, 'geo_public', true) == 1) ? 'checked' : '';

    echo "<!-- Geocoded posts - Editor values $latitude $longitude $visable -->";
    wp_nonce_field( 'geocoded_posts_editor', 'geocoded_posts_editor_nonce' );
    echo '<label for="geocoded_posts_lat">'.__('Latitude','geocoded-posts').'</label> ';
    echo '<input type="text" id="geocoded_posts_lat" name="geocoded_posts_lat" value="' . esc_attr( $latitude) . '" size="20" />';

    echo '<label for="geocoded_posts_long">'.__('Longitude','geocoded-posts').'</label> ';
    echo '<input type="text" id="geocoded_posts_long" name="geocoded_posts_long" value="' . esc_attr( $longitude) . '" size="20" />';

    echo '<label for="geocoded_posts_public">'.__('Visable','geocoded-posts').'</label> ';
    echo '<input type="checkbox" id="geocoded_posts_public" name="geocoded_posts_public" value="1" '.$visable.'>';
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

    // Sanitize user input.
    $latitude = sanitize_text_field( $_POST['geocoded_posts_lat'] );
    $longitude = sanitize_text_field( $_POST['geocoded_posts_long'] );

    $public = isset($_POST['geocoded_posts_public']) ? 1 : 0;
    // Update the meta field in the database.
    update_post_meta( $post_id, 'geo_latitude', $latitude );
    update_post_meta( $post_id, 'geo_longitude', $longitude );
    update_post_meta( $post_id, 'geo_public', $public );
  }


}

// Activate this metabox when this file is required in wordpress.
new Geocoded_Posts_Editor();
